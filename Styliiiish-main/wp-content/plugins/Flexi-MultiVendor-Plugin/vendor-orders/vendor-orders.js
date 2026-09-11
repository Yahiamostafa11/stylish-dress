jQuery(function($){


    let currentSupportOrder = null;
    let lastMessageId = 0;
    let supportPolling = null;
    let scrollPosition = 0;
    
    function lockBodyScroll() {
        scrollPosition = window.pageYOffset || document.documentElement.scrollTop;
    
        document.body.style.position = 'fixed';
        document.body.style.top = `-${scrollPosition}px`;
        document.body.style.left = '0';
        document.body.style.right = '0';
        document.body.style.width = '100%';
        document.body.classList.add('tracker-scroll-lock');
    }
    
    function unlockBodyScroll() {
        document.body.style.position = '';
        document.body.style.top = '';
        document.body.style.left = '';
        document.body.style.right = '';
        document.body.style.width = '';
        document.body.classList.remove('tracker-scroll-lock');
    
        window.scrollTo(0, scrollPosition);
    }

    // Tabs
    $('.wf-tabs li').on('click', function(){
            let tab = $(this).data('tab');
            $(this).addClass('active').siblings().removeClass('active');
            $('.wf-tab-content').hide();
            $('.wf-tab-' + tab).fadeIn(200);
        });
    // Status Button Click
    let statusIcons = {
        'processing':'⏳',
        'received':'✅',
        'ready':'📦',
        'with_courier':'🚚'
    };

$(document).on('click', '.wf-status-btn', function (e) {

    if ($(this).hasClass('track-order-btn')) return;

    const btn = $(this);
    const card = btn.closest('.wf-card');

    const originalText = btn.text();

    btn.prop('disabled', true).text('Updating...');

    $.post(wfVendorOrders.ajax_url, {
        action: 'wf_update_item_status',
        order_id: btn.data('order'),
        status: btn.data('status'),
        nonce: wfVendorOrders.nonce
    }, function (res) {

        if (!res.success) {
            btn.prop('disabled', false).text(originalText);
            return;
        }

        card.addClass('wf-loading');

        $.post(wfVendorOrders.ajax_url, {
            action: 'wf_reload_vendor_orders',
            nonce: wfVendorOrders.nonce
        }, function (r) {

            if (!r.success) return;

            const html = $(r.data.html);

            const newCard = html.find(
                '.wf-card:has(.wf-order-id:contains("#' + btn.data('order') + '"))'
            );

            if (newCard.length) {
                card.replaceWith(newCard);
            }

        });

    });

});


    
    // Simple Toast Notification
    function showToast(message, type='info'){
            let colors = { success:'#4CAF50', error:'#F44336', info:'#2196F3' };
            let toast = $('<div>')
                .text(message)
                .css({
                    position:'fixed',
                    bottom:'20px',
                    right:'20px',
                    background: colors[type] || colors.info,
                    color:'#fff',
                    padding:'10px 18px',
                    borderRadius:'6px',
                    boxShadow:'0 2px 6px rgba(0,0,0,0.2)',
                    zIndex:9999,
                    display:'none'
                })
                .appendTo('body')
                .fadeIn(300);
    
            setTimeout(function(){ toast.fadeOut(400, function(){ $(this).remove(); }); }, 3000);
        }

     /************************************************
     * ORDER TRACKER – MAIN (UNIFIED LOGIC)
     * SAFE REPLACEMENT – PRODUCTION READY
     ************************************************/
    /* ========= CORE TIMELINE LOGIC   MAIN ========= */
function runOrderTrackerTimeline({ timelineSelector, stepSelector, progressSelector, isMobile }) {

    document.querySelectorAll(timelineSelector).forEach(timeline => {

        if (isMobile) {
            runOrderTrackerTimelineMobile(timeline, stepSelector, progressSelector);
        } else {
            runOrderTrackerTimelineDesktop(timeline, stepSelector, progressSelector);
        }

    });
}


/************************************************
     * ORDER TRACKER –  MOBILE (UNIFIED LOGIC)
     * SAFE REPLACEMENT – PRODUCTION READY
     ************************************************/
    /* ========= CORE TIMELINE LOGIC   MOBILE ========= */
function runOrderTrackerTimelineMobile(timeline, stepSelector, progressSelector) {

    const steps = Array.from(timeline.querySelectorAll(stepSelector));
    const progress = timeline.querySelector(progressSelector);
    if (!steps.length || !progress) return;

    const orderStatus = timeline.dataset.currentStep;

    let completedIndex = steps.findIndex(
        step => step.dataset.step === orderStatus
    );
    if (completedIndex < 0) completedIndex = 0;

    const lastIndex = steps.length - 1;

    /* ===== Apply Step States ===== */
    steps.forEach((step, index) => {

        step.classList.remove('completed', 'current', 'upcoming');

        if (index <= completedIndex) {
            step.classList.add('completed');
        } else if (index === completedIndex + 1) {
            step.classList.add('current');
        } else {
            step.classList.add('upcoming');
        }
    });

    /* ===== OLD PROGRESS (AS IS – WORKING) ===== */
    const scaleY = lastIndex === 0 ? 1 : completedIndex / lastIndex;
    progress.style.transform = `scaleY(${Math.min(scaleY, 1)})`;
    progress.style.background = '#43a047';

    /* ===== Loading Line (Visual Only) ===== */
    const loadingBar = timeline.querySelector('.mobile-timeline-loading');

    if (loadingBar && completedIndex < lastIndex) {

        const stepSize = 100 / lastIndex;
        const startPercent = completedIndex * stepSize;

        loadingBar.style.top = startPercent + '%';
        loadingBar.style.height = stepSize + '%';
        loadingBar.style.display = 'block';

    } else if (loadingBar) {
        loadingBar.style.display = 'none';
    }
}


/************************************************
     * ORDER TRACKER – DESKTOP  (UNIFIED LOGIC)
     * SAFE REPLACEMENT – PRODUCTION READY
     ************************************************/
    /* ========= CORE TIMELINE LOGIC  DESKTOP ========= */
function runOrderTrackerTimelineDesktop(timeline, stepSelector, progressSelector) {

    const steps = Array.from(timeline.querySelectorAll(stepSelector));
    const progress = timeline.querySelector(progressSelector);
    const loadingBar = timeline.querySelector('.timeline-loading');

    if (!steps.length || !progress) return;

    const orderStatus = timeline.dataset.currentStep;

    let completedIndex = steps.findIndex(
        step => step.dataset.step === orderStatus
    );
    if (completedIndex < 0) completedIndex = 0;

    /* ===== STEP STATES (كما هي) ===== */
    steps.forEach((step, index) => {
        step.classList.remove('completed', 'current', 'upcoming');

        if (index <= completedIndex) {
            step.classList.add('completed');
        } else if (index === completedIndex + 1) {
            step.classList.add('current');
        } else {
            step.classList.add('upcoming');
        }
    });

    /* ===== MANUAL POSITION MAP ===== */
    const stepPositions = {
        processing:        { greenEnd: 0,   nextEnd: 20 },
        received:          { greenEnd: 20,  nextEnd: 40 },
        ready:             { greenEnd: 40,  nextEnd: 60 },
        with_courier:      { greenEnd: 60,  nextEnd: 75 },
        out_for_delivery:  { greenEnd: 75,  nextEnd: 90 },
        completed:         { greenEnd: 100 }
    };

    const currentConfig = stepPositions[orderStatus] || { greenEnd: 0 };

    /* ===== GREEN LINE ===== */
    progress.style.insetInlineStart = '0';
    progress.style.width = currentConfig.greenEnd + '%';

    /* ===== LOADING LINE ===== */
    if (loadingBar && currentConfig.nextEnd !== undefined) {

        loadingBar.style.insetInlineStart = currentConfig.greenEnd + '%';
        loadingBar.style.width =
            (currentConfig.nextEnd - currentConfig.greenEnd) + '%';

        loadingBar.style.display = 'block';

    } else if (loadingBar) {
        loadingBar.style.display = 'none';
    }
}

document.addEventListener('DOMContentLoaded', function () {
    
        const isMobile = window.matchMedia('(max-width: 768px)').matches;
    
        if (isMobile) {
            runOrderTrackerTimeline({
                timelineSelector: '.mobile-tracker-timeline',
                stepSelector: '.mobile-tracker-step',
                progressSelector: '.mobile-timeline-progress',
                isMobile: true
            });
        } else {
            runOrderTrackerTimeline({
                timelineSelector: '.tracker-timeline',
                stepSelector: '.tracker-step',
                progressSelector: '.timeline-progress',
                isMobile: false
            });
        }
    });

    /* ========= OPEN TRACKER ========= */
$(document).on('click', '.track-order-btn', function () {
        
    const order_id = $(this).data('order-id');
    const status   = $(this).data('status');
    const isMobile = window.matchMedia('(max-width: 768px)').matches;

    if (!order_id) return;

    /* =================================================
     * 🟢 هذا هو منطقك الأصلي 100% (لم يُلمس)
     * ================================================= */

    const modal = isMobile ? $('#mobile-order-tracker') : $('#order-tracker-modal');
    const timeline = isMobile
        ? modal.find('.mobile-tracker-timeline')
        : modal.find('.tracker-timeline');

    timeline.attr('data-current-step', status);
    modal.fadeIn(150);
    lockBodyScroll();

    /* =========================
 * MOBILE
 * ========================= */
if (isMobile) {

    if (wfOrderDates && wfOrderDates[order_id]) {

        const steps = wfOrderDates[order_id];

        timeline.find('.mobile-tracker-step').each(function () {
            const step = $(this).data('step');
            const date = steps[step] || '';
            $(this).find('.mobile-step-date').text(date);
        });
    }

    runOrderTrackerTimeline({
        timelineSelector: '.mobile-tracker-timeline',
        stepSelector: '.mobile-tracker-step',
        progressSelector: '.mobile-timeline-progress',
        isMobile: true
    });

}
/* =========================
 * DESKTOP
 * ========================= */
else {

    runOrderTrackerTimeline({
        timelineSelector: '.tracker-timeline',
        stepSelector: '.tracker-step',
        progressSelector: '.timeline-progress',
        isMobile: false
    });

}

    /* =================================================
     * 🟡 إضافة AJAX (بدون لمس البروجريس)
     * ================================================= */

    $.post(wfVendorOrders.ajax_url, {
        action: 'wf_get_order_full_data',
      nonce: wfVendorOrders.nonce,
      order_id: order_id
    }, function (res) {
        if (!res.success) {
        return;
    }
        
        
        const data = res.data;
        

        /* ===== Order Info فقط ===== */
        $('.js-order-id').text('#' + data.order_id);
        $('.js-payment').text(data.payment || '—');
        $('.js-tracking').text(data.tracking || '—');
        $('.js-arrival').text(data.arrival || '—');
        /* ===== Mobile Order Info ===== */
        modal.find('.js-mobile-order-id').text('#' + data.order_id);
        modal.find('.js-mobile-tracking').text(data.tracking || '—');
        modal.find('.js-mobile-payment').text(data.payment || '—');
        const arrivalEl = modal.find('.js-mobile-arrival');
            arrivalEl.text(data.arrival || '—');
            
            applyArrivalStatus(arrivalEl, data.arrival, data.status);

        
        
        



        /* ===== تواريخ الخطوات فقط (بدون لمس status) ===== */
        // AFTER AJAX – Real dates overwrite
            timeline.find('.mobile-tracker-step').each(function () {
            
                const step = $(this).data('step');
                const ajaxDate = data.dates?.[step] || '';
                const currentDate = $(this).find('.mobile-step-date').text();
                
                if (ajaxDate) {
                    $(this).find('.mobile-step-date').text(ajaxDate);
                }

            });


        /* ❌ ممنوع إعادة تشغيل runOrderTrackerTimeline هنا */
    });

});
    /* ========= CLOSE TRACKER ========= */
$(document).on( 'click', '.close-tracker, .tracker-overlay, .mobile-tracker-overlay, .mobile-close-tracker', function () {
            $('#order-tracker-modal, #mobile-order-tracker').fadeOut(150);
            unlockBodyScroll();
            
        });
    
    
function applyArrivalStatus(el, arrivalDate, orderStatus) {

    if (!arrivalDate) return;

    const today = new Date();
    today.setHours(0,0,0,0);

    const arrival = new Date(arrivalDate);
    arrival.setHours(0,0,0,0);

    el.removeClass('arrival-warning arrival-late');

    // 🟡 اليوم المتوقع هو اليوم
    if (arrival.getTime() === today.getTime()) {
        el.addClass('arrival-warning');
    }

    // 🔴 التأخير (والطلب مش مقفول)
    if (
        arrival < today &&
        !['completed','refunded','failed','cancelled'].includes(orderStatus)
    ) {
        el.addClass('arrival-late');
    }
}


    









/* ===============================
   SUPPORT CHAT CORE
================================ */






function sendSupportMessage(e){

  const btn = $(this);

  // نطلع أقرب box فيه input
  const wrapper = btn.closest('.wf-chat-card, #wf-support-modal, #wf-chat-container');

  const input = wrapper.find('.wf-support-text');

  if(!input.length){
    console.warn('No input found');
    return;
  }

  const msg = input.val();

  if(!msg || !msg.trim() || !currentSupportOrder) return;

  input.val('');

  $.post(wfVendorOrders.ajax_url,{

    action:'wf_send_support_message',
    order_id: currentSupportOrder,
    message: msg.trim(),
    nonce: wfVendorOrders.nonce

  }, function(res){

    if(!res.success){
      console.error('SEND ERROR:', res.data);
      return;
    }

    loadSupportMessages(currentSupportOrder, true);

  });

}



/* ===============================
   LOAD MESSAGES
================================ */

function loadSupportMessages(orderId,force=false){

  $.post(wfVendorOrders.ajax_url,{

    action:'wf_get_support_messages',

    order_id:orderId,

    nonce: wfVendorOrders.nonce

  },function(res){


    if(!res.success) return;

    renderSupportMessages(res.data,force);

  });

}



/* ===============================
   RENDER
================================ */

function renderSupportMessages(messages, force){

  const box = document.querySelector('.wf-support-messages');

  if(!box){
    console.warn('Chat box not ready yet');
    return;
  }

  if(force){
    box.innerHTML = '';
    lastMessageId = 0;
  }

  messages.forEach(msg => {

    if(msg.id <= lastMessageId) return;

    lastMessageId = msg.id;

    let cls =
      (msg.sender_role === 'support_agent' ||
       msg.sender_role === 'administrator')
      ? 'admin'
      : 'vendor';

    box.insertAdjacentHTML('beforeend', `
      <div class="wf-msg ${cls}">
        ${escapeHtml(msg.message)}
      </div>
    `);

  });

  box.scrollTop = box.scrollHeight;
}



/* ===============================
   SECURITY
================================ */

function escapeHtml(text){

  return text.replace(/[&<>"']/g,function(m){

    return ({
      '&':'&amp;',
      '<':'&lt;',
      '>':'&gt;',
      '"':'&quot;',
      "'":'&#039;'
    })[m];

  });

}



/* ===============================
   POLLING
================================ */

function startSupportPolling(){

  stopSupportPolling();


  supportPolling = setInterval(function(){

    if(currentSupportOrder){

      loadSupportMessages(currentSupportOrder);

    }

  },3000);

}


function stopSupportPolling(){

  if(supportPolling){

    clearInterval(supportPolling);

    supportPolling = null;

  }

}




function renderAdminMessages(list){

  const box = document.getElementById('wf-admin-chat-container');

  if(!box) return;

  box.innerHTML = `
    <div class="wf-chat-card">
      <div class="wf-chat-header">
        💬 Admin Chat — Order #${currentSupportOrder}
      </div>

      <div class="wf-chat-body wf-support-messages"></div>

      <div class="wf-chat-input">
        <textarea class="wf-support-text"></textarea>
        <button class="wf-support-send">➤</button>
      </div>
    </div>
  `;

  renderSupportMessages(list,true);
}


function setRealVH(){

  let vh = window.innerHeight * 0.01;

  document.documentElement.style.setProperty(
    '--real-vh',
    `${vh}px`
  );
}

setRealVH();

window.addEventListener('resize', setRealVH);
window.addEventListener('orientationchange', setRealVH);


$(document).on('focus','.wf-support-text',function(){

  setTimeout(() => {

    this.scrollIntoView({
      behavior:'smooth',
      block:'center'
    });

  },300);

});


$(document).on('click','.wf-support-send', function(e){
  e.preventDefault();
  sendSupportMessage.call(this, e);
});


$(document).on('keypress','.wf-support-text',function(e){

  if(e.which === 13 && !e.shiftKey){

    e.preventDefault();

    sendSupportMessage();

  }

});


/* ===============================
   OPEN CHAT
================================ */

$(document).on('click','.wf-track-btn',function(){

  lockBodyScroll();

  const card = $(this).closest('.wf-card');

  const orderId = parseInt(
      card.find('.wf-order-id').text().replace('#',''),
      10
    );
  const product = card.find('.wf-product-name').text();
  const qty = card.find('.wf-product-qty').text();
  const status = card.find('.wf-status-badge').text();
  const img = card.find('img').attr('src');


  /* Fill UI */

  $('.wf-support-thumb').attr('src',img);
  $('.wf-support-title').text(product);
  $('.wf-support-order-id').text(orderId);
  $('.wf-support-qty').text(qty);
  $('.wf-support-badge').text(status);


  /* Show */

  $('#wf-support-modal').fadeIn(200);


  currentSupportOrder = orderId;


  /* Mark seen */
  $.post(wfVendorOrders.ajax_url,{
    action:'wf_mark_support_seen',
    nonce: wfVendorOrders.nonce,
    order_id:orderId
  });


  /* Load + Poll */

  loadSupportMessages(orderId,true);

  startSupportPolling();

});



/* ===============================
   CLOSE CHAT
================================ */

$(document).on(
  'click',
  '.wf-support-close,.wf-support-overlay',
  function(){

    $('#wf-support-modal').fadeOut(200);

    unlockBodyScroll();

    stopSupportPolling();

    currentSupportOrder = null;
    lastMessageId = 0;

  }
);


    // Admin Open Chat
document.addEventListener('click',function(e){

  const btn = e.target.closest('.wf-admin-open-chat');

  if(!btn) return;

  e.preventDefault();

  const order = btn.dataset.order;

  if(!order) return;

  currentSupportOrder = order;

  document.getElementById('wf-admin-chat-container').innerHTML =
    '<div class="wf-placeholder">Loading chat...</div>';

  fetch(wfVendorOrders.ajax_url,{
    method:'POST',
    credentials:'same-origin',
    body:new URLSearchParams({
      action:'wf_get_support_messages',
      order_id:order,
      nonce: wfVendorOrders.nonce
    })
  })
  .then(r=>r.json())
  .then(res=>{

    if(!res.success) return;

    setTimeout(function(){

    renderSupportMessages(res.data,true);
    startSupportPolling();

  },100);

  });

});



/* Open chat from agent sidebar */





/* Ticket Click */

$(document).on('click','.wf-ticket-item',function(e){

  e.preventDefault();

  const order = parseInt($(this).data('order'),10);

  if(!order) return;

  console.log('Opening agent ticket:', order);

  $('.wf-ticket-item').removeClass('active');
  $(this).addClass('active');

  if($(this).data('link')){
    history.pushState({},'',$(this).data('link'));
  }

  $('#wf-chat-container').html(
    '<div class="wf-placeholder">Loading...</div>'
  );

  currentSupportOrder = order;
  lastMessageId = 0;

  $.post(wfVendorOrders.ajax_url,{

    action:'wf_load_support_chat',
    order_id: order,
    nonce: wfVendorOrders.nonce

  },function(res){

    if(!res.success) return;

    /* Inject HTML */
    $('#wf-chat-container').html(res.data);

    /* 🔥 FORCE LAYOUT REBUILD */
    setTimeout(function(){

      // Force browser reflow
      window.dispatchEvent(new Event('resize'));

      // Fix flex height
      const panel = document.querySelector('.wf-chat-panel');
      if(panel){
        panel.style.minHeight = '0';
      }

      // Scroll messages
      const body = document.querySelector('.wf-chat-body');
      if(body){
        body.scrollTop = body.scrollHeight;
      }

      // Mark UI ready
      document.body.classList.add('wf-chat-ready');

    },50);


    /* Your logic */
    waitForChatBoxThenLoad(order);

  });

});


/* Send */

 


function waitForTicketsAndOpen(){

  const params = new URLSearchParams(window.location.search);
  const order  = parseInt(params.get('order'),10);

  if(!order) return;

  let tries = 0;

  const timer = setInterval(function(){

    const ticket = document.querySelector(
      '.wf-ticket-item[data-order="'+order+'"]'
    );

    if(ticket){

      clearInterval(timer);

      console.log('Auto opening ticket:', order);

      ticket.click();

    }

    tries++;

    if(tries > 20){ // بعد 4 ثواني

      clearInterval(timer);

      console.warn('Ticket never appeared:', order);

    }

  },200);

}


$(window).on('load', waitForTicketsAndOpen);





function waitForChatBoxThenLoad(order){

  let tries = 0;

  const timer = setInterval(function(){

    const box = document.querySelector('.wf-support-messages');

    if(box){

      clearInterval(timer);

      console.log('Chat ready:', order);

      loadSupportMessages(order,true);
      startSupportPolling();

    }

    tries++;

    if(tries > 20){

      clearInterval(timer);

      console.warn('Chat box never loaded');

    }

  },150);

}






/* ===============================
   MOBILE SCREEN SWITCH
================================ */


  // Open chat
  $(document).on('click','.wf-ticket-item',function(){

    if(window.innerWidth > 768) return;

    $('.wf-agent-panel').addClass('chat-open');

  });


  // Back to inbox
  $(document).on('click','.wf-chat-back',function(){

    $('.wf-agent-panel').removeClass('chat-open');

  });




















});
