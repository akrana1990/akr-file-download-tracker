<?php

/* No direct access */
if (!defined('ABSPATH')) exit;
if (!defined('AFDT_BASE_FILE')) wp_die('What ?');

class Akr_file_download_tracker_Admin
{

    private $plugin_slug = 'afdt_options_page';

    function __construct()
    {

        /*to save default options upon activation*/
        register_activation_hook(plugin_basename(AFDT_BASE_FILE), array($this, 'create_new_table'));

        /*settings link on plugin listing page*/
        add_filter('plugin_action_links_' . plugin_basename(AFDT_BASE_FILE), array($this, 'add_plugin_actions_links'), 10, 2);

        add_action( 'admin_menu', array($this, 'afdt_setup_menus' ));

    }

    function create_new_table()
    {
        //update_option('afdt_email_subject', 'Welcome Woodstock');
        //update_option( 'afdt_email_text', 'Type a text Message Here' );

        if(!get_option('afdt_email_options')){
            update_option('afdt_email_options',$this->get_default_options());
        }

        global $wpdb;
        $table_name = $wpdb->prefix . "file_downloader";


        $sql="
                CREATE TABLE IF NOT EXISTS $table_name (
                id int(11) NOT NULL AUTO_INCREMENT,
                name varchar(30) NOT NULL,
                email varchar(200) NOT NULL,
                gender varchar(1) NOT NULL,
                nationality varchar(100) NOT NULL,
                file_title varchar(100) NOT NULL,
                date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY id (id)
                )
                ";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

    }


    function get_default_options(){
        $gefault_msg="Dear |%NAME%|,\n\n I am delighted to know that you are interested in a Woodstock education for your son or daughter!  Woodstock’s educational approach has been carefully thought out and has stood the test of time. The education we offer is designed to develop our students’ intellect and to give them an excellent academic foundation.  But it is also designed to achieve far more than that. We pay as much attention to the development of qualities of character, developing a sound moral compass and reliable personal values as we do to test scores and exam grades. \n\n |%GRADE_PDF_LINK%|";
        return array(
            'email_subject' => 'Welcome to woodstock',
            'email_template' => $gefault_msg
        );
    }

    function add_plugin_actions_links($links, $file)
    {
        if (current_user_can('manage_options')) {
            $build_url = add_query_arg('page', $this->plugin_slug, 'options-general.php');
            array_unshift(
                $links,
                sprintf('<a href="%s">%s</a>', $build_url, __('Settings'))
            );
        }
        return $links;
    }

    /**
     * Menu item will allow us to load the page to display the table
     */
    public function afdt_setup_menus()
    {
        add_menu_page( 'PDF Downloads', 'PDF Downloads', 'manage_options', 'akr-wp-list-table', array($this, 'list_table_page') );

        add_submenu_page(
            'akr-wp-list-table',       // parent slug
            'Email Format for Users',    // page title
            'Set Up Email',             // menu title
            'manage_options',           // capability
            'set-up-email',      // slug
            array($this,'afdt_set_up_email') // callback
        );
    }

    public function afdt_set_up_email()
    {
        ?>
        <div class="wrap">
            <div id="icon-users" class="icon32"></div>
            <h2>Set Up Email</h2>

            <?php
                $setUpEmail=new Afdt_Set_up_Email();
                $setUpEmail->prepare_email();
            ?>

        </div>
    <?php
    }

    /**
     * Display the list table page
     *
     * @return Void
     */
    public function list_table_page()
    {
        $exampleListTable = new Afdt_Example_List_Table();
        $exampleListTable->prepare_items();

        ?>
        <div class="wrap">
            <div id="icon-users" class="icon32"></div>
            <h2>Total PDF Downloads</h2>

            <form id="events-filter" method="POST">
                <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
                <?php $exampleListTable->search_box('search', 'search_id'); ?>
                <?php
                $exampleListTable->display();
                ?>
            </form>

        </div>
    <?php
    }

}