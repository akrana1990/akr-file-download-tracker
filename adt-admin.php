<?php

/* No direct access */
if (!defined('ABSPATH')) exit;
if (!defined('ADT_BASE_FILE')) wp_die('What ?');

class Ank_Download_Tracker_Admin
{

    private $plugin_slug = 'adt_options_page';

    function __construct()
    {

        /*to save default options upon activation*/
        register_activation_hook(plugin_basename(ADT_BASE_FILE), array($this, 'create_new_table'));

        /*settings link on plugin listing page*/
        add_filter('plugin_action_links_' . plugin_basename(ADT_BASE_FILE), array($this, 'add_plugin_actions_links'), 10, 2);
        /* Add settings link under admin->settings menu */
        add_action('admin_menu', array($this, 'add_to_settings_menu'));

        /* register ajax save function */
        //add_action('wp_ajax_' . ADT_AJX_ACTION, array(&$this, 'process_download_request'));

    }

    function create_new_table()
    {

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

        add_submenu_page('options-general.php', 'Ank Download Tracker', 'Ank Download Tracker', 'manage_options', $this->plugin_slug, array($this, 'ADT_options_page'));

    }

    function ADT_options_page()
    {
        ?>
        <div class="wrap">
            <h2>hello test</h2>
        </div>
    <?php

    }

    /*function process_download_request()
    {
        if (isset($_GET['action']) && $_GET['action'] === ADT_AJX_ACTION) {


            //form handling
            die('1');
        }
    }*/


}