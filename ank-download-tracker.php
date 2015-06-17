<?php
/*
Plugin Name: Akr File Download Tracker
Plugin URI: https://github.com/akrana1990
Description:  WordPress Plugin.
Version: 0.1
Author: Ankit Rana
Author URI: http://akrana1990.github.io/
License: GPL2
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/
?>
<?php
/* No direct access*/
if (!defined('ABSPATH')) exit;

define('AFDT_PLUGIN_VER', '0.1');
define('AFDT_BASE_FILE', __FILE__);
//define('ADT_AJX_ACTION', 'adt_download');


class Akr_file_download_tracker {
    private $form_errors = array();

    function __construct()
    {
        add_shortcode('akr_show_form', array($this, 'do_shortcode'));
    }

    static public function form()
    {
        $name='';
        $email='';
        if(isset($_POST['your-name']))
        {
            $name=$_POST["your-name"];
        }
        if(isset($_POST['your-email']))
        {
            $email=$_POST["your-email"];
        }
        echo '<form action="' . $_SERVER['REQUEST_URI'] . '" method="post">';

        echo '<p>';
        echo 'Your Name (required) <br/>';
        echo '<input type="text" name="your-name" value="' . $name . '" size="40" />';
        echo '</p>';

        echo '<p>';
        echo 'Your Email (required) <br/>';
        echo '<input type="text" name="your-email" value="' . $email . '" size="40" />';
        echo '</p>';

        echo '<p><input type="submit" name="form-submitted" value="Send"></p>';
        echo '</form>';

    }

    public function validate_form( $name, $email ) {

        // If any field is left empty, add the error message to the error array
        if ( empty($name) || empty($email) ) {
            array_push( $this->form_errors, 'No field should be left empty' );
        }

        // if the name field isn't alphabetic, add the error message
        if ( strlen($name) < 4 ) {
            array_push( $this->form_errors, 'Name should be at least 4 characters' );
        }

        // Check if the email is valid
        if ( !is_email($email) ) {
            array_push( $this->form_errors, 'Email is not valid' );
        }
    }

    public function send_email($name, $email) {

        // Ensure the error array ($form_errors) contain no error
        if ( count($this->form_errors) < 1 ) {

            // sanitize form values
            $name = sanitize_text_field($name);
            $email = sanitize_email($email);
            $subject = 'Test';
            $message = 'Test';

            // get the blog administrator's email address
            $to = get_option('admin_email');

            $headers = "From: $name <$email>" . "\r\n";

            // If email has been process for sending, display a success message
            if ( wp_mail($to, $subject, $message, $headers) )
                echo '<div style="background: #3b5998; color:#fff; padding:2px;margin:2px">';
            echo 'Thanks for contacting me, expect a response soon.';
            echo '</div>';
        }
    }

    public function process_functions() {
        if ( isset($_POST['form-submitted']) ) {

            $name=$_POST["your-name"];
            $email=$_POST["your-email"];
            // call validate_form() to validate the form values
            $this->validate_form($name, $email);

            // display form error if it exist
            if (is_array($this->form_errors)) {
                foreach ($this->form_errors as $error) {
                    echo '<div>';
                    echo '<strong>ERROR</strong>:';
                    echo $error . '<br/>';
                    echo '</div>';
                }
            }
            $this->send_email( $name, $email );
        }



        self::form();
    }

    public function do_shortcode() {
        ob_start();
        $this->process_functions();
        return ob_get_clean();
    }

}


if (is_admin()) {
    /* Load admin part only if we are inside wp-admin */
    require(trailingslashit(dirname(__FILE__)) . "adt-admin.php");
    //init admin class
    global $Ank_Download_Tracker_Admin;
    $Ank_Download_Tracker_Admin = new Ank_Download_Tracker_Admin();
} else {
    /*init front end part*/
    global $Ank_Download_Tracker;
    $Ank_Download_Tracker = new Akr_file_download_tracker();
}