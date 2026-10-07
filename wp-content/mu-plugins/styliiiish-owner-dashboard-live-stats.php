<?php
/**
 * Plugin Name: Styliiiish – Owner Dashboard live stats
 * Description: Adds two live panels to the owner dashboard's Statistics section:
 *              1) storefront visitors (online now / today / last 14 days / top pages) from the first-party
 *                 `styliiiish_pageviews` table written by Laravel's POST /api/track;
 *              2) transactional email stats pulled from Brevo's API (sent / delivered / opened / clicked /
 *                 bounced… plus a recent-activity feed), like Brevo's own Statistics page.
 *              The Brevo API key never reaches the browser — requests go through admin-ajax on the server.
 */

if (!defined('ABSPATH')) {
	exit;
}

const STYLIIISH_BREVO_KEY_OPTION = 'styliiiish_brevo_api_key';

/* ------------------------------------------------------------------ helpers */

function styliiiish_od_live_active() {
	if (function_exists('is_page') && (is_page('owner-dashboard') || is_page(1893))) {
		return true;
	}

	// "My account → Moderate Your Site" renders the same dashboard on its own route.
	return function_exists('wf_is_moderate_site_request') && wf_is_moderate_site_request();
}

/** Same audience as the dashboard itself: plugin admins, listed managers, or WooCommerce managers. */
function styliiiish_od_live_can() {
	if (!is_user_logged_in()) {
		return false;
	}
	if (current_user_can('manage_woocommerce') || current_user_can('manage_options')) {
		return true;
	}
	if (function_exists('wf_od_is_user_plugin_admin') && wf_od_is_user_plugin_admin()) {
		return true;
	}
	if (function_exists('wf_od_get_manager_ids') && in_array(get_current_user_id(), (array) wf_od_get_manager_ids(), true)) {
		return true;
	}

	return false;
}

function styliiiish_od_live_guard() {
	check_ajax_referer('ajax_nonce', 'nonce');
	if (!styliiiish_od_live_can()) {
		wp_send_json_error(array('message' => 'forbidden'), 403);
	}
}

function styliiiish_od_cairo_day($offset_days = 0) {
	$d = new DateTime('now', new DateTimeZone('Africa/Cairo'));
	if ($offset_days) {
		$d->modify(($offset_days > 0 ? '+' : '') . $offset_days . ' day');
	}

	return $d->format('Y-m-d');
}

/* ------------------------------------------------------------------ assets */

add_action('wp_enqueue_scripts', function () {
	if (!styliiiish_od_live_active() || !styliiiish_od_live_can()) {
		return;
	}

	$base = WP_CONTENT_DIR . '/mu-plugins/styliiiish/';
	$ver  = function ($f) use ($base) {
		return file_exists($base . $f) ? (string) filemtime($base . $f) : null;
	};

	wp_enqueue_script(
		'styliiiish-od-live',
		content_url('mu-plugins/styliiiish/owner-dashboard-live.js'),
		array(),
		$ver('owner-dashboard-live.js'),
		true
	);

	$rtl = is_rtl();
	wp_localize_script('styliiiish-od-live', 'styliiiishLive', array(
		'ajax'       => admin_url('admin-ajax.php'),
		'nonce'      => wp_create_nonce('ajax_nonce'),
		'canConfig'  => current_user_can('manage_options'),
		'configured' => (bool) get_option(STYLIIISH_BREVO_KEY_OPTION),
		'rtl'        => $rtl,
		'tz'         => 'Africa/Cairo',
		't'          => $rtl ? array(
			'visitors' => 'زوار الموقع', 'live' => 'لحظي', 'online' => 'متصلين الآن', 'today' => 'زوار اليوم', 'views' => 'مشاهدات اليوم',
			'yesterday' => 'أمس', 'last14' => 'آخر 14 يوم', 'topPages' => 'أكثر الصفحات زيارة اليوم', 'noData' => 'لسه مفيش زيارات النهارده',
			'emails' => 'الإيميلات', 'sent' => 'إيميل اتبعت', 'delivered' => 'وصلت', 'opened' => 'اتفتحت', 'clicked' => 'اتضغط عليها',
			'bounced' => 'ارتدّت', 'complaint' => 'شكاوى', 'blocked' => 'محجوبة', 'range_today' => 'اليوم', 'range_7d' => '7 أيام', 'range_30d' => '30 يوم',
			'recent' => 'آخر النشاط', 'noEvents' => 'مفيش نشاط في الفترة دي', 'connect' => 'اربطي Brevo لعرض إحصائيات الإيميلات',
			'connectHelp' => 'من Brevo: SMTP & API ← API Keys ← Generate a new API key، وألصقيه هنا.', 'save' => 'حفظ وربط', 'keyPh' => 'xkeysib-…',
			'adminOnly' => 'الأدمن بس يقدر يربط Brevo.', 'error' => 'تعذر جلب البيانات', 'updated' => 'آخر تحديث', 'unit' => 'زائر',
		) : array(
			'visitors' => 'Site visitors', 'live' => 'Live', 'online' => 'Online now', 'today' => 'Visitors today', 'views' => 'Page views today',
			'yesterday' => 'Yesterday', 'last14' => 'Last 14 days', 'topPages' => 'Top pages today', 'noData' => 'No visits yet today',
			'emails' => 'Emails', 'sent' => 'emails sent', 'delivered' => 'Delivered', 'opened' => 'Opened', 'clicked' => 'Clicked',
			'bounced' => 'Bounced', 'complaint' => 'Complaint', 'blocked' => 'Blocked', 'range_today' => 'Today', 'range_7d' => '7 days', 'range_30d' => '30 days',
			'recent' => 'Recent activity', 'noEvents' => 'No activity in this period', 'connect' => 'Connect Brevo to see email statistics',
			'connectHelp' => 'In Brevo: SMTP & API → API Keys → Generate a new API key, then paste it here.', 'save' => 'Save & connect', 'keyPh' => 'xkeysib-…',
			'adminOnly' => 'Only an administrator can connect Brevo.', 'error' => 'Couldn\'t load data', 'updated' => 'Updated', 'unit' => 'visitors',
		),
	));
}, 100);

/* ------------------------------------------------------------------ visitors */

add_action('wp_ajax_styliiiish_od_visitors', function () {
	styliiiish_od_live_guard();
	global $wpdb;

	$t = 'styliiiish_pageviews';
	if ($wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $t)) !== $t) {
		wp_send_json_success(array('ready' => false));
	}

	$today     = styliiiish_od_cairo_day(0);
	$yesterday = styliiiish_od_cairo_day(-1);
	$since5    = gmdate('Y-m-d H:i:s', time() - 300);

	$online = (int) $wpdb->get_var($wpdb->prepare("SELECT COUNT(DISTINCT visitor) FROM {$t} WHERE created_at >= %s", $since5));

	$day = function ($d) use ($wpdb, $t) {
		$r = $wpdb->get_row($wpdb->prepare("SELECT COUNT(DISTINCT visitor) AS v, COUNT(*) AS p FROM {$t} WHERE day = %s", $d), ARRAY_A);

		return array('visitors' => (int) ($r['v'] ?? 0), 'views' => (int) ($r['p'] ?? 0));
	};

	$rows   = $wpdb->get_results($wpdb->prepare(
		"SELECT day, COUNT(DISTINCT visitor) AS v, COUNT(*) AS p FROM {$t} WHERE day >= %s GROUP BY day ORDER BY day ASC",
		styliiiish_od_cairo_day(-13)
	), ARRAY_A);
	$by_day = array();
	foreach ($rows as $r) {
		$by_day[$r['day']] = array('visitors' => (int) $r['v'], 'views' => (int) $r['p']);
	}
	$series = array();
	for ($i = 13; $i >= 0; $i--) {
		$d        = styliiiish_od_cairo_day(-$i);
		$series[] = array('day' => $d) + ($by_day[$d] ?? array('visitors' => 0, 'views' => 0));
	}

	$top = $wpdb->get_results($wpdb->prepare(
		"SELECT path, COUNT(*) AS n FROM {$t} WHERE day = %s GROUP BY path ORDER BY n DESC LIMIT 6",
		$today
	), ARRAY_A);

	wp_send_json_success(array(
		'ready'     => true,
		'online'    => $online,
		'today'     => $day($today),
		'yesterday' => $day($yesterday),
		'series'    => $series,
		'top'       => array_map(function ($r) {
			return array('path' => $r['path'], 'n' => (int) $r['n']);
		}, $top),
		'at'        => time(),
	));
});

/* ------------------------------------------------------------------ Brevo */

function styliiiish_brevo_request($path, array $query = array()) {
	$key = (string) get_option(STYLIIISH_BREVO_KEY_OPTION);
	if ($key === '') {
		return new WP_Error('no_key', 'Brevo API key not set');
	}

	$url = 'https://api.brevo.com/v3' . $path . ($query ? '?' . http_build_query($query) : '');
	$res = wp_remote_get($url, array(
		'timeout' => 12,
		'headers' => array('api-key' => $key, 'accept' => 'application/json'),
	));
	if (is_wp_error($res)) {
		return $res;
	}

	$code = (int) wp_remote_retrieve_response_code($res);
	$body = json_decode(wp_remote_retrieve_body($res), true);
	if ($code < 200 || $code >= 300) {
		$msg = is_array($body) && !empty($body['message']) ? $body['message'] : ('HTTP ' . $code);

		return new WP_Error('brevo_' . $code, $msg);
	}

	return is_array($body) ? $body : array();
}

function styliiiish_mask_email($email) {
	$email = (string) $email;
	$at    = strpos($email, '@');
	if ($at === false) {
		return $email;
	}

	return substr($email, 0, 1) . str_repeat('•', max(2, min(5, $at - 1))) . substr($email, $at);
}

add_action('wp_ajax_styliiiish_od_emails', function () {
	styliiiish_od_live_guard();

	if (!get_option(STYLIIISH_BREVO_KEY_OPTION)) {
		wp_send_json_success(array('configured' => false));
	}

	$range = isset($_POST['range']) ? sanitize_key(wp_unslash($_POST['range'])) : 'today';
	$days  = array('today' => 1, '7d' => 7, '30d' => 30);
	$n     = isset($days[$range]) ? $days[$range] : 1;

	$cache_key = 'sty_brevo_' . $range;
	$cached    = get_transient($cache_key);
	if ($cached !== false) {
		wp_send_json_success($cached);
	}

	$report = styliiiish_brevo_request('/smtp/statistics/aggregatedReport', array(
		'startDate' => styliiiish_od_cairo_day(-($n - 1)),
		'endDate'   => styliiiish_od_cairo_day(0),
	));
	if (is_wp_error($report)) {
		wp_send_json_success(array('configured' => true, 'error' => $report->get_error_message()));
	}

	$events = styliiiish_brevo_request('/smtp/statistics/events', array('limit' => 25, 'sort' => 'desc', 'days' => min(30, max(1, $n))));
	$feed   = array();
	if (!is_wp_error($events) && !empty($events['events'])) {
		foreach ($events['events'] as $e) {
			$feed[] = array(
				'email'   => styliiiish_mask_email($e['email'] ?? ''),
				'subject' => mb_substr((string) ($e['subject'] ?? ''), 0, 70),
				'event'   => (string) ($e['event'] ?? ''),
				'date'    => (string) ($e['date'] ?? ''),
			);
		}
	}

	$pick = function ($k) use ($report) {
		return (int) ($report[$k] ?? 0);
	};

	$out = array(
		'configured' => true,
		'range'      => $range,
		'report'     => array(
			'requests'     => $pick('requests'),
			'delivered'    => $pick('delivered'),
			'opens'        => $pick('opens'),
			'uniqueOpens'  => $pick('uniqueOpens'),
			'clicks'       => $pick('clicks'),
			'uniqueClicks' => $pick('uniqueClicks'),
			'hardBounces'  => $pick('hardBounces'),
			'softBounces'  => $pick('softBounces'),
			'blocked'      => $pick('blocked'),
			'spamReports'  => $pick('spamReports'),
			'invalid'      => $pick('invalid'),
		),
		'events'     => $feed,
		'at'         => time(),
	);

	set_transient($cache_key, $out, 25);
	wp_send_json_success($out);
});

/** Administrator pastes the Brevo API key into the dashboard; it's validated before being stored. */
add_action('wp_ajax_styliiiish_od_save_brevo_key', function () {
	styliiiish_od_live_guard();
	if (!current_user_can('manage_options')) {
		wp_send_json_error(array('message' => 'admin only'), 403);
	}

	$key = isset($_POST['key']) ? trim(sanitize_text_field(wp_unslash($_POST['key']))) : '';
	if ($key === '' || strlen($key) < 20 || strpos($key, 'xsmtpsib-') === 0) {
		wp_send_json_error(array('message' => 'That looks like an SMTP key. Use an API key (starts with xkeysib-).'));
	}

	// Validate against Brevo before saving.
	$res = wp_remote_get('https://api.brevo.com/v3/account', array('timeout' => 12, 'headers' => array('api-key' => $key, 'accept' => 'application/json')));
	if (is_wp_error($res) || (int) wp_remote_retrieve_response_code($res) !== 200) {
		$code = is_wp_error($res) ? $res->get_error_message() : 'HTTP ' . wp_remote_retrieve_response_code($res);
		wp_send_json_error(array('message' => 'Brevo rejected this key (' . $code . '). Check it and any IP restrictions in Brevo → Security.'));
	}

	update_option(STYLIIISH_BREVO_KEY_OPTION, $key, false);
	foreach (array('today', '7d', '30d') as $r) {
		delete_transient('sty_brevo_' . $r);
	}
	wp_send_json_success(array('saved' => true));
});
