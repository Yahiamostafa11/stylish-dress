<?php
/*

*/

// ضع كل أكوادك هنا بدون تغيير
/**
 * Load Styles
 */
 
 
 
 
 
 
 
 
 
 
 
// =============================================
// OWNER DASHBOARD – EMAIL SENDER
// =============================================
function styliiiish_render_email_sender() {
    if (!current_user_can('administrator')) {
        echo "<p>You do not have permission to use this feature.</p>";
        return;
    }

    // Handle sending email
    if (isset($_POST['send_email'])) {

        $recipient = sanitize_email($_POST['email_to']);
        $subject   = sanitize_text_field($_POST['email_subject']);
        $message   = wp_kses_post($_POST['email_message']);

        $headers = array('Content-Type: text/html; charset=UTF-8');

        if (wp_mail($recipient, $subject, nl2br($message), $headers)) {
            echo "<div style='padding:10px;background:#d4edda;border-left:5px solid #28a745;margin-bottom:15px;'>
                    ✔ Email sent successfully to: <strong>$recipient</strong>
                  </div>";
        } else {
            echo "<div style='padding:10px;background:#f8d7da;border-left:5px solid #dc3545;margin-bottom:15px;'>
                    ❌ Failed to send email.
                  </div>";
        }
    }

    ?>


    <div class="email-box">

        <form method="POST">

            <label>Send To (Email Address)</label>
            <input type="text" name="email_to" placeholder="customer@example.com" required>

            <label>Email Subject</label>
            <input type="text" name="email_subject" placeholder="Your Subject Here" required>

            <label>Email Message</label>
            <textarea name="email_message" rows="8" placeholder="Write your message..." required></textarea>

            <button type="submit" name="send_email" class="email-send-btn">
                Send Email
            </button>

        </form>

    </div>
    <?php
}