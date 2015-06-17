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
        /* Add settings link under admin->settings menu */
        add_action('admin_menu', array($this, 'add_to_settings_menu'));

        /* register ajax save function */
        //add_action('wp_ajax_' . AFDT_AJX_ACTION, array(&$this, 'process_download_request'));

    }

    function create_new_table()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . "file_downloader";
        $sql = "CREATE TABLE $table_name (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                name tinytext NOT NULL,
                email VARCHAR(100) NOT NULL,
                file_title VARCHAR(100) NOT NULL ,
                date TIMESTAMP DEFAULT '0000-00-00 00:00:00' NOT NULL,
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

    function add_to_settings_menu()
    {

        add_submenu_page('options-general.php', 'Afdt Download Tracker', 'Afdt Download Tracker', 'manage_options', $this->plugin_slug, array($this, 'AFDT_options_page'));

    }

    function AFDT_options_page()
    {
        ?>
        <div class="wrap">
            <h2>hello test</h2>
        </div>
    <?php

    }


}