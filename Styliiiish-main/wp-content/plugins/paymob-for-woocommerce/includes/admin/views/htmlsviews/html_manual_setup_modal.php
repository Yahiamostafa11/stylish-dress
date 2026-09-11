<?php 

$paymobOptions = get_option('woocommerce_paymob-main_settings');
$mode = isset($paymobOptions['mode']) ? $paymobOptions['mode'] : 'test';
$checked = ($mode=='test')?'':'checked';
$apiKey = isset($paymobOptions['api_key']) ? $paymobOptions['api_key'] : '';
$live_pub_key = isset($paymobOptions['live_pub_key']) ? $paymobOptions['live_pub_key'] : '';
$live_sec_key = isset($paymobOptions['live_sec_key']) ? $paymobOptions['live_sec_key'] : '';
$test_sec_key = isset($paymobOptions['test_sec_key']) ? $paymobOptions['test_sec_key'] : '';
$test_pub_key = isset($paymobOptions['test_pub_key']) ? $paymobOptions['test_pub_key'] : '';
?>
<div id="manualsetupconfirmationModal" class="manualsetup-modal" style="display:none;">

 <!-- Mode Toggle Section (Aligned like input fields) -->


 <script>
    var mode = '<?=$mode;?>';
</script>

    <div class="manualsetup-modal-content">
        <h2>Manual Setup</h2>
        <div id="popupError"></div>
       <!-- Input Fields -->
 
        <div class="mode-toggle-container">
            <label for="mode-toggle">Mode</label>
            <label class="switch">
                <input type="checkbox" id="mode-toggle" <?= $checked;?>>
                <span class="slider round"></span>
            </label>
            <span id="mode-status2">Test</span> <!-- Default mode status -->
        </div>
        <input type="text" id="api_key" placeholder="Paymob API Key" required  value="<?= $apiKey; ?>">
        

 
        <div id="liveKeys" class="tabcontent">
            <input type="text" id="live_secret_key" placeholder="Paymob Live Secret Key" value="<?= $live_sec_key; ?>">
            <input type="text" id="live_public_key" placeholder="Paymob Live Public Key" value="<?= $live_pub_key; ?>">
        </div>
        <div id="testKeys" class="tabcontent">
        <input type="text" id="test_secret_key" placeholder="Paymob Test Secret Key" value="<?= $test_sec_key; ?>">
            <input type="text" id="test_public_key" placeholder="Paymob Test Public Key" value="<?= $test_pub_key; ?>">
        </div>

      

        <!-- Modal Buttons -->
        <div class="modal-buttons">
            <button id="manualsetupmodal_confirm_button">Confirm</button>
            <button id="manualsetupmodal_close_button">Cancel</button>
        </div>

    </div>
</div>

<div class="loader_paymob"></div>