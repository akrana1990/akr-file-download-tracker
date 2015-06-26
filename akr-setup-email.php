<?php
/* No direct access */
if (!defined('ABSPATH')) exit;
if (!defined('AFDT_BASE_FILE')) wp_die('What ?');


/**
 * Create new class that will set up the email
 */

class Afdt_Set_up_Email{

    /**
     *
     */

    public function prepare_email()
    {
        if(isset($_POST['save-email']))
        {
            //update_option('afdt_email_subject',trim( $_POST['afdt_email_subject'] ));
            //update_option( 'afdt_email_text', trim( $_POST['afdt_email_text'] ) );

            update_option('afdt_email_options',array(
                'email_subject' => $_POST['afdt_email_subject'],
                'email_template' => $_POST['afdt_email_text']
            ));

        }
        $email_options=get_option('afdt_email_options');
        ?>

        <div id="poststuff">
            <div class="error">
                <p>Please use |%NAME%|, |%GRADE_PDF_LINK%| shortcodes inside the email body to display Name and Grade PDF download link, System will replace the shortcodes to original values.</p>
            </div>

            <div class="error">
                <p>Emails will not be send until Email Subject and Email Body are not configured.</p>
            </div>

            <div class="postbox">
                <h3>Specify the Email</h3>
                <div class="inside">
                    <form method="post" action="">
                        <p>
                            <label>Email Subject</label>
                            <input style="width: 100%" type="text" name="afdt_email_subject" value="<?php echo $email_options['email_subject']; ?>">
                        </p>
                        <p>
                            <label>Email Body</label>
                        <textarea style="width: 100%" rows="15" name="afdt_email_text"><?php
                            echo $email_options['email_template'];
                            ?></textarea>
                        </p>

                        <?php submit_button('Save Email','','save-email'); ?>
                    </form>
                </div>
            </div>
        </div>

    <?php
    }


}