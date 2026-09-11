<div id="confirmation-modal" style="display:none;">
	<div id="confirmation-modal-content">
		<h2 id="confirmation-modal-title"></h2>
		<p id="confirmation-modal-message"></p>

		<div class="modal-buttons">
		<button type="button"  id="confirmation-modal-confirm"><?php echo esc_html( __( 'Confirm', 'paymob-woocommerce' ) ); ?></button>
		<button type="button" id="confirmation-modal-cancel"><?php echo esc_html( __( 'Cancel', 'paymob-woocommerce' ) ); ?></button>
        </div>
		
	</div>
</div> 
<div class="loader_paymob"></div>
<?php
$paymobOptions = get_option('woocommerce_paymob-main_settings');
$mode = isset($paymobOptions['mode']) ? $paymobOptions['mode'] : 'test';
$sliderMode = ($mode=='test')?'':'silderMode';
// Generate HTML row.
$tabs = include PAYMOB_PLUGIN_PATH . '/includes/admin/paymob-admin-tabs.php';
$modeSwitcher =  '<div id="changemodemodal_confirm_button" class="mode-toggle-container switch-mode" style="max-width: 20%;">
    <label for="mode-toggle"></label>
    <label class="switch">
        <span class="slider round '.$sliderMode.'"></span>
    </label>
    <span id="mode-status">'. ucfirst($mode) .'</span>
</div>';

echo $tabs. '<br/><br/>'.$modeSwitcher;
?>
