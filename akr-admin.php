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


        add_action( 'admin_menu', array($this, 'add_menu_example_list_table_page' ));

    }

    function create_new_table()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . "file_downloader";
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                name tinytext NOT NULL,
                email VARCHAR(100) NOT NULL,
                file_title VARCHAR(100) NOT NULL ,
                date TIMESTAMP NOT NULL,
                PRIMARY KEY id (id)
                )";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

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
    public function add_menu_example_list_table_page()
    {
        add_menu_page( 'Example List Table', 'Example List Table', 'manage_options', 'akr-wp-list-table.php', array($this, 'list_table_page') );
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
            <h2>Example List Table Page</h2>
            <form id="events-filter" method="POST">
                <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
                <?php
                $exampleListTable->display();
                ?>
            </form>

        </div>
    <?php
    }

}