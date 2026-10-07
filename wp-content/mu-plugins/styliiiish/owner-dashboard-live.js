/* Owner dashboard — live Visitors + Emails panels (see styliiiish-owner-dashboard-live-stats.php) */
(function () {
  "use strict";
  var L = window.styliiiishLive;
  if (!L) return;
  var T = L.t;
  var range = "today";
  var timers = [];

  function esc(s) {
    return String(s == null ? "" : s).replace(/[&<>"']/g, function (c) {
      return { "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#39;" }[c];
    });
  }
  function nf(n) { return Number(n || 0).toLocaleString("en-US"); }
  function pct(a, b) { return b > 0 ? Math.round((a / b) * 1000) / 10 : 0; }
  function $(id) { return document.getElementById(id); }

  function post(action, extra) {
    var fd = new FormData();
    fd.append("action", action);
    fd.append("nonce", L.nonce);
    Object.keys(extra || {}).forEach(function (k) { fd.append(k, extra[k]); });
    return fetch(L.ajax, { method: "POST", credentials: "same-origin", body: fd }).then(function (r) { return r.json(); });
  }

  /* ------------------------------------------------------------ mount */
  function mount() {
    var grid = document.querySelector(".stats-grid");
    if (!grid) return false;
    if ($("sty-live")) return true;

    var wrap = document.createElement("div");
    wrap.id = "sty-live";
    wrap.className = "sty-live";
    wrap.innerHTML =
      '<section class="sty-live-card" id="sty-vis">' +
        '<header><h4>' + esc(T.visitors) + '</h4><span class="sty-live-dot"><i></i>' + esc(T.live) + '</span></header>' +
        '<div class="sty-kpis">' +
          '<div class="sty-kpi"><b id="v-online">–</b><span>' + esc(T.online) + '</span></div>' +
          '<div class="sty-kpi"><b id="v-today">–</b><span>' + esc(T.today) + '<em id="v-delta"></em></span></div>' +
          '<div class="sty-kpi"><b id="v-views">–</b><span>' + esc(T.views) + '</span></div>' +
        '</div>' +
        '<div class="sty-bars-head"><span>' + esc(T.last14) + '</span></div>' +
        '<div class="sty-bars" id="v-bars"></div>' +
        '<h5>' + esc(T.topPages) + '</h5>' +
        '<ol class="sty-top" id="v-top"><li class="muted">…</li></ol>' +
      '</section>' +
      '<section class="sty-live-card" id="sty-mail">' +
        '<header><h4>' + esc(T.emails) + ' <small>Brevo</small></h4>' +
          '<div class="sty-tabs" id="m-tabs">' +
            ['today', '7d', '30d'].map(function (r) {
              return '<button type="button" data-r="' + r + '"' + (r === range ? ' class="on"' : "") + '>' + esc(T["range_" + r]) + '</button>';
            }).join("") +
          '</div></header>' +
        '<div id="m-body"><p class="muted">…</p></div>' +
      '</section>';
    grid.parentNode.insertBefore(wrap, grid.nextSibling);

    $("m-tabs").addEventListener("click", function (e) {
      var b = e.target.closest("button[data-r]");
      if (!b) return;
      range = b.getAttribute("data-r");
      Array.prototype.forEach.call($("m-tabs").children, function (x) { x.classList.toggle("on", x === b); });
      loadEmails();
    });
    return true;
  }

  /* ------------------------------------------------------------ visitors */
  function renderVisitors(d) {
    if (!d || !d.ready) {
      $("v-top").innerHTML = '<li class="muted">' + esc(T.noData) + "</li>";
      return;
    }
    $("v-online").textContent = nf(d.online);
    $("v-today").textContent = nf(d.today.visitors);
    $("v-views").textContent = nf(d.today.views);

    var y = d.yesterday.visitors, t = d.today.visitors, delta = $("v-delta");
    if (y > 0) {
      var p = Math.round(((t - y) / y) * 100);
      delta.className = p >= 0 ? "up" : "down";
      delta.textContent = (p >= 0 ? "▲ " : "▼ ") + Math.abs(p) + "% " + T.yesterday;
    } else {
      delta.textContent = "";
    }

    var max = Math.max.apply(null, d.series.map(function (s) { return s.visitors; }).concat([1]));
    $("v-bars").innerHTML = d.series.map(function (s, i) {
      var h = Math.max(3, Math.round((s.visitors / max) * 100));
      var last = i === d.series.length - 1;
      return '<div class="sty-bar' + (last ? " today" : "") + '" title="' + esc(s.day) + " — " + nf(s.visitors) + " " + esc(T.unit) + ", " + nf(s.views) + " " + esc(T.views) + '">' +
        '<i style="height:' + h + '%"></i><span>' + esc(s.day.slice(8)) + "</span></div>";
    }).join("");

    $("v-top").innerHTML = d.top.length
      ? d.top.map(function (r) { return '<li><span dir="ltr">' + esc(r.path) + "</span><b>" + nf(r.n) + "</b></li>"; }).join("")
      : '<li class="muted">' + esc(T.noData) + "</li>";
  }

  function loadVisitors() {
    if (document.hidden) return;
    post("styliiiish_od_visitors").then(function (r) { if (r && r.success) renderVisitors(r.data); }).catch(function () {});
  }

  /* ------------------------------------------------------------ emails */
  var EVT = {
    requests: ["sent", "#9C7F6C"], delivered: ["delivered", "#3F7160"], opened: ["opened", "#BA5D70"], clicks: ["clicked", "#BA5D70"],
    softBounces: ["soft bounce", "#a8691f"], hardBounces: ["hard bounce", "#b3261e"], blocked: ["blocked", "#b3261e"],
    spam: ["complaint", "#b3261e"], invalid: ["invalid", "#b3261e"], deferred: ["deferred", "#a8691f"], unsubscribed: ["unsubscribed", "#9C7F6C"],
  };

  function meter(label, count, percent, cls) {
    return '<div class="sty-meter ' + cls + '"><div><span>' + esc(label) + "</span><b>" + percent.toFixed(2) + "%</b></div>" +
      '<div class="sty-meter-bar"><i style="width:' + Math.min(100, percent) + '%"></i></div><small>' + nf(count) + "</small></div>";
  }

  function connectPanel() {
    if (!L.canConfig) return '<p class="muted">' + esc(T.adminOnly) + "</p>";
    return '<div class="sty-connect"><p><b>' + esc(T.connect) + "</b></p><p class=\"muted\">" + esc(T.connectHelp) + "</p>" +
      '<div class="sty-connect-row"><input type="password" id="m-key" placeholder="' + esc(T.keyPh) + '" autocomplete="off" dir="ltr">' +
      '<button type="button" class="button button-primary" id="m-save">' + esc(T.save) + "</button></div><p class=\"sty-err\" id=\"m-err\"></p></div>";
  }

  function renderEmails(d) {
    var body = $("m-body");
    if (!d || !d.configured) {
      body.innerHTML = connectPanel();
      var save = $("m-save");
      if (save) {
        save.addEventListener("click", function () {
          var key = $("m-key").value.trim();
          if (!key) return;
          save.disabled = true;
          post("styliiiish_od_save_brevo_key", { key: key }).then(function (r) {
            if (r && r.success) { L.configured = true; loadEmails(); }
            else { $("m-err").textContent = (r && r.data && r.data.message) || T.error; save.disabled = false; }
          }).catch(function () { $("m-err").textContent = T.error; save.disabled = false; });
        });
      }
      return;
    }
    if (d.error) {
      body.innerHTML = '<p class="sty-err">' + esc(T.error) + ": " + esc(d.error) + "</p>";
      return;
    }

    var r = d.report, sent = r.requests, del = r.delivered;
    var bounced = r.hardBounces + r.softBounces;
    var html =
      '<div class="sty-sent"><b>' + nf(sent) + "</b> <span>" + esc(T.sent) + "</span></div>" +
      '<div class="sty-meters">' +
        meter(T.delivered, del, pct(del, sent), "c-blue") +
        meter(T.opened, r.uniqueOpens, pct(r.uniqueOpens, del), "c-teal") +
        meter(T.clicked, r.uniqueClicks, pct(r.uniqueClicks, del), "c-green") +
        meter(T.bounced, bounced, pct(bounced, sent), "c-red") +
        meter(T.complaint, r.spamReports, pct(r.spamReports, del), "c-red") +
        meter(T.blocked, r.blocked, pct(r.blocked, sent), "c-red") +
      "</div>" +
      "<h5>" + esc(T.recent) + "</h5>";

    if (d.events && d.events.length) {
      html += '<ul class="sty-feed">' + d.events.map(function (e) {
        var m = EVT[e.event] || [e.event, "#9C7F6C"];
        var when = "";
        try { when = new Date(e.date).toLocaleTimeString([], { hour: "2-digit", minute: "2-digit", timeZone: L.tz }); } catch (x) { when = ""; }
        return '<li><span class="sty-ev" style="--c:' + m[1] + '">' + esc(m[0]) + "</span>" +
          '<span class="sty-ev-to" dir="ltr">' + esc(e.email) + "</span>" +
          '<span class="sty-ev-sub">' + esc(e.subject) + "</span><time>" + esc(when) + "</time></li>";
      }).join("") + "</ul>";
    } else {
      html += '<p class="muted">' + esc(T.noEvents) + "</p>";
    }
    body.innerHTML = html;
  }

  function loadEmails() {
    if (document.hidden) return;
    post("styliiiish_od_emails", { range: range })
      .then(function (r) { if (r && r.success) renderEmails(r.data); else renderEmails({ configured: true, error: T.error }); })
      .catch(function () { renderEmails({ configured: true, error: T.error }); });
  }

  /* ------------------------------------------------------------ boot */
  function start() {
    if (!mount()) return;
    loadVisitors();
    loadEmails();
    timers.push(setInterval(loadVisitors, 10000));
    timers.push(setInterval(loadEmails, 30000));
    document.addEventListener("visibilitychange", function () {
      if (!document.hidden) { loadVisitors(); loadEmails(); }
    });
  }

  if (document.readyState === "loading") document.addEventListener("DOMContentLoaded", start);
  else start();
})();
