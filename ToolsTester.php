<?php wp_enqueue_script('jquery'); ?>
<h2> <?php esc_attr_e( 'Send message easily', 'WpAdminStyle' ); ?></h2>
<div class="wrap">
    <div class="metabox-holder columns-2">
        <div class="meta-box-sortables ui-sortable">
            <div class="postbox">
                <h2 class="hndle">
                    <span> <?php esc_attr_e( 'SEND SMS', 'WpAdminStyle' ); ?></span>
                </h2>
                <div class="inside">
                    <form method="post" name="cleanup_options" id="smsForm" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                        <input type="text" name="numbers" class="regular-text" placeholder="+23480597..." required /><br><br>
                        <textarea name="message" cols="50" rows="7" placeholder="Message"></textarea><br><br>
                        <input class="button-primary" type="submit" value="SEND MESSAGE" />

                        <input type="hidden" name="action" value="submit_sms_test_form">
                        <?php wp_nonce_field('submit_sms_test_form_action', 'submit_sms_test_form_nonce'); ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
jQuery(document).ready(function($) {
    $('#smsForm').on('submit', function(event) {
        event.preventDefault(); // Zapobiega przeładowaniu strony

        var formData = $(this).serialize(); // Serializuje dane formularza

        $.ajax({
            type: 'POST',
            url: $(this).attr('action'), // Akcja formularza (admin-post.php)
            data: formData, // Dane do wysłania
            success: function(response) {
                // Tutaj możesz obsłużyć odpowiedź serwera
                alert('Message sent!'); // Możesz zmienić to na inną logikę, np. wyświetlenie komunikatu
            },
            error: function() {
                alert('Error sending message.'); // Obsługuje błędy
            }
        });
    });
});
</script>

