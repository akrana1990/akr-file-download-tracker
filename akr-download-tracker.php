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
        $countries = array("Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "France Metropolitan", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao, People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "St. Helena", "St. Pierre and Miquelon", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Yugoslavia", "Zambia", "Zimbabwe");
        ?>
        <form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post">
            <p>Your Name (required) <br/>
                <input type="text" name="afdt-name" value="">
            </p>

            <p>Your Email (required) <br/>
                <input type="text" name="afdt-email" value="">
            </p>

            <p>Gender <br/>
                <input type="radio" name="afdt-sex" value="M">&nbsp;Male&emsp;
                <input type="radio" name="afdt-sex" value="F">&nbsp;Female
            </p>

            <p>Passport/Nationality <br/>
                <select name="afdt-nationality">
                    <option selected disabled>Select</option>
                    <?php foreach($countries as $country) { ?>
                    <option><?php echo $country; ?></option>
                    <?php } ?>

                </select>
            </p>

            <p>
                <input type="checkbox" id="is_dual_nationality" value="1" name="is_dual_nationality" >&nbsp;
                If Dual Nationality
            </p>

            <p style="display: none" id="afdt-second-nationality">Second Nationality <br/>
                <select name="afdt-nationality2">
                    <option selected disabled>Select</option>
                    <?php foreach($countries as $country) { ?>
                        <option><?php echo $country; ?></option>
                    <?php } ?>
                </select>
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

    public function validate_form( $name, $email,$pdf_id, $gender, $nationality ) {

        // If any field is left empty, add the error message to the error array
        if ( empty($name) || empty($email) || empty($pdf_id) ||empty($gender) || empty($nationality) ) {
            array_push( $this->form_errors, 'No field should be left empty' );
        }

        // if the name field isn't alphabetic, add the error message
        if ( strlen($name) < 3 ) {
            array_push( $this->form_errors, 'Name should be at least 3 characters' );
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

        $email_options=get_option('afdt_email_options');

        $subject =trim($email_options['email_subject']);


        $search_array=array('|%NAME%|','|%GRADE_PDF_LINK%|');
        $replace_array=array($name,$pdf_url);
        //$message = trim(get_option('afdt_email_text'))."\r\n".$pdf_url;

        $message=str_replace($search_array,$replace_array,trim($email_options['email_template']));


        // get the blog administrator's email address
        //$to = get_option('admin_email');
        $to=$email;

        $headers = "From: $name <$email>" . "\r\n";

        // If email has been process for sending, display a success message
        if ( wp_mail($to, $subject, $message, $headers))

        {
            echo '<div style="background: #3b5998; color:#fff; padding:2px;margin:2px">';
            echo 'Thanks for contacting me, expect a response soon.';
            echo '</div>';

            return true;
        }
        else
        {

            echo '<br>Request Can\'t be send. Try again later.';

            return false;
        }


    }
    public function insert_db($name,$email,$pdf_id,$gender,$nationality)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . "file_downloader";
        $file_title=get_the_title($pdf_id);
        $sql="INSERT INTO $table_name(name,email,gender,nationality,file_title) VALUES ('$name','$email','$gender','$nationality','$file_title')";
        $wpdb->query($sql);
    }

    public function process_functions($file_ids)
    {
        if ( isset($_POST['form-submitted']) )
        {

            $name=isset($_POST["afdt-name"])?$_POST['afdt-name']:'';
            $email=isset($_POST["afdt-email"])?$_POST['afdt-email']:'';
            $gender=isset($_POST["afdt-sex"])?$_POST['afdt-sex']:'';
            $nationality_1=isset($_POST["afdt-nationality"])?$_POST['afdt-nationality']:'';
            $pdf_id=isset($_POST['afdt-pdf'])?$_POST['afdt-pdf']:'';

            $nationality_2=isset($_POST["afdt-nationality2"])?$_POST['afdt-nationality2']:'';
            $is_dual_nationality=isset($_POST["is_dual_nationality"])?$_POST['is_dual_nationality']:0;

            if( $nationality_1!='' && $nationality_2!='' && $is_dual_nationality )
            {
                $nationality=array($nationality_1,$nationality_2);
                $nationality=implode(',',$nationality);
                //$nationality=json_encode($nationality);
            }
            else
            {
                $nationality=$nationality_1;
            }

            // call validate_form() to validate the form values
            $this->validate_form($name, $email, $pdf_id, $gender, $nationality);

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
                if($this->send_email($name, $email, $pdf_id))
                {
                    $this->insert_db($name,$email,$pdf_id,$gender,$nationality);
                }
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

        //var_dump($params['file_ids']);

        wp_enqueue_script('afdt-user-script', plugins_url('assets/afdt-user.js', __FILE__), array('jquery'), 0.1, true);
        wp_enqueue_style('adft-user-style', plugins_url('assets/afdt-user.css', __FILE__), array(), 0.1, true);

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

    require(trailingslashit(dirname(__FILE__)) . "akr-wp-list-table.php");

    require(trailingslashit(dirname(__FILE__)) . "akr-setup-email.php");
    require(trailingslashit(dirname(__FILE__)) . "how-to-use.php");

} else {
    /*init front end part*/
    global $Akr_file_download_tracker;
    $Akr_file_download_tracker = new Akr_file_download_tracker();
}