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

    static public function form($file_ids)
    {
        ?>
        <form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post">
            <p>Your Name (required) <br/>
                <input type="text" name="afdt-name" value="" size="40">
            </p>

            <p>Your Email (required) <br/>
                <input type="text" name="afdt-email" value="" size="40" />
            </p>

            <p>Select PDF (required) <br/>
                <select style="width: 100%" name="afdt-pdf" >
                    <option disabled selected>Select</option>
                    <?php
                    foreach($file_ids as $id) {
                        //echo wp_get_attachment_url( absint($id) );
                        //echo get_the_title(absint($id));                   }
                        echo '<option value="' . $id . '">' . get_the_title(absint($id)) . '</option>';
                        }
                    ?>
                </select>
            </p><br>

            <p><input type="submit" name="form-submitted" value="Send"></p>
        </form>
        <?php
    }

    public function validate_form( $name, $email,$pdf_id ) {

        // If any field is left empty, add the error message to the error array
        if ( empty($name) || empty($email) || empty($pdf_id) ) {
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

    public function send_email($name, $email, $pdf_id) {

        // sanitize form values
        $name = sanitize_text_field($name);
        $email = sanitize_email($email);
        $pdf_url=wp_get_attachment_url($pdf_id);

        $subject = 'Test';
        $message = 'Test'.$pdf_url;

        // get the blog administrator's email address
        //$to = get_option('admin_email');
        $to=$email;

        $headers = "From: $name <$email>" . "\r\n";

        // If email has been process for sending, display a success message
        if ( wp_mail($to, $subject, $message, $headers) )
        {
            echo '<div style="background: #3b5998; color:#fff; padding:2px;margin:2px">';
            echo 'Thanks for contacting me, expect a response soon.';
            echo '</div>';
            $this->insert_db($name,$email,$pdf_id);
        }

    }
    public function insert_db($name,$email,$pdf_id)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . "file_downloader";
        $file_title=get_the_title($pdf_id);
        $sql="INSERT INTO $table_name(name,email,file_title) VALUES ($name,$email,$file_title)";
        $wpdb->query($sql);
    }

    public function process_functions($file_ids) {
        if ( isset($_POST['form-submitted']) ) {

            $name=isset($_POST["afdt-name"])?$_POST['afdt-name']:'';
            $email=isset($_POST["afdt-email"])?$_POST['afdt-email']:'';
            $pdf_id=$_POST['afdt-pdf'];
            // call validate_form() to validate the form values
            $this->validate_form($name, $email, $pdf_id);

            // display form error if it exist
            if (is_array($this->form_errors))
            {
                foreach ($this->form_errors as $error) {
                    echo '<div>';
                    echo '<strong>ERROR</strong>:';
                    echo $error . '<br/>';
                    echo '</div>';
                }
            }
            // Ensure the error array ($form_errors) contain no error
            if ( count($this->form_errors) < 1 )
            {
                $this->send_email($name, $email, $pdf_id);
            }
        }
        self::form($file_ids);
    }

    public function do_shortcode($params)
    {

        $params = shortcode_atts(array(
            'file_ids' => array(),
        ), $params);

        $params['file_ids'] = explode(',',$params['file_ids']);
        if(empty($params['file_ids'])) return;

        var_dump($params['file_ids']);

        ob_start();
        $this->process_functions($params['file_ids']);
        return ob_get_clean();
    }

}


if (is_admin()) {
    /* Load admin part only if we are inside wp-admin */
    require(trailingslashit(dirname(__FILE__)) . "akr-admin.php");
    //init admin class
    global $Akr_file_download_tracker_Admin;
    $Akr_file_download_tracker_Admin = new Akr_file_download_tracker_Admin();
} else {
    /*init front end part*/
    global $Akr_file_download_tracker;
    $Akr_file_download_tracker = new Akr_file_download_tracker();
}