<?php



/* No direct access */
if (!defined('ABSPATH')) exit;
if (!defined('AFDT_BASE_FILE')) wp_die('What ?');

// WP_List_Table is not loaded automatically so we need to load it in our application
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * Create a new table class that will extend the WP_List_Table
 */
class Afdt_Example_List_Table extends WP_List_Table
{
    /**
     * Prepare the items for the table to process
     *
     * @return Void
     */
    public function prepare_items()
    {

        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();

        $this->process_bulk_action();

        $data = $this->table_data();
        usort( $data, array( &$this, 'sort_data' ) );

        $perPage = 20;
        $currentPage = $this->get_pagenum();
        $totalItems = count($data);

        $this->set_pagination_args( array(
            'total_items' => $totalItems,
            'per_page'    => $perPage
        ) );

        $data = array_slice($data,(($currentPage-1)*$perPage),$perPage);

        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $data;

    }

    /**
     * Override the parent columns method. Defines the columns to use in your listing table
     *
     * @return Array
     */
    public function get_columns()
    {
        $columns = array(
            'cb'        => '<input type="checkbox" />',
            'id'          => 'ID',
            'name'        => 'Name',
            'email'       => 'Email',
            'gender'      => 'Gender',
            'nationality' => 'Nationality',
            'file_title'  => 'Applying for Grade',
            'date'        => 'Date'

        );

        return $columns;
    }

    /**
     * Define which columns are hidden
     *
     * @return Array
     */
    public function get_hidden_columns()
    {
        return array();
    }

    /**
     * Define the sortable columns
     *
     * @return Array
     */
    public function get_sortable_columns()
    {
        return array(
            'name' => array('name', false),
            'email' => array('email', false),
            'id' => array('id', false),
            'gender' => array('gender', false),
            'nationality' => array('nationality', false),
            'file_title' => array('file_title', false),
            'date' => array('date', false),
        );
    }

    /**
     * Get the table data
     *
     * @return Array
     */
    private function table_data()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . "file_downloader";

        $search = ( isset( $_REQUEST['s'] ) ) ? $_REQUEST['s'] : false;

        $search = esc_sql($search);

       // $do_search = ( $search ) ? $wpdb->prepare("WHERE name LIKE '%s%' OR email LIKE '%s%%' ", $search ,$search) : '';

        $do_search = ( $search ) ? "WHERE name like '%".$search."%' or email like '%".$search."%' or nationality like '%".$search."%'" : '';

        $query = "SELECT * FROM $table_name "."$do_search";

        $data = $wpdb->get_results($query,ARRAY_A);

        return $data;
    }

    /**
     * Define what data to show on each column of the table
     *
     * @param  Array $item        Data
     * @param  String $column_name - Current column name
     *
     * @return Mixed
     */
    public function column_default( $item, $column_name )
    {
        switch( $column_name ) {

            case 'gender':
                $item[$column_name]=($item[$column_name])?'F':'M';
                return $item[ $column_name ];
                break;


            default:
                return $item[ $column_name ];
        }
    }

    /**
     * Allows you to sort the data by the variables set in the $_GET
     *
     * @return Mixed
     */
    private function sort_data( $a, $b )
    {
        // Set defaults
        $orderby = 'name';
        $order = 'asc';

        // If orderby is set, use this as the sort column
        if(!empty($_GET['orderby']))
        {
            $orderby = $_GET['orderby'];
        }

        // If order is set use this as the order
        if(!empty($_GET['order']))
        {
            $order = $_GET['order'];
        }


        $result = strnatcmp( $a[$orderby], $b[$orderby] );

        if($order === 'asc')
        {
            return $result;
        }

        return -$result;
    }


    function column_name($data) {
        $actions = array(

            'delete'    => sprintf('<a href="?page=%s&action=%s&id=%s">Delete</a>',$_REQUEST['page'],'delete',$data['id'])
        );

        return sprintf('%1$s %2$s', $data['name'], $this->row_actions($actions) );
    }

    function get_bulk_actions() {
        $actions = array(
            'bulk-delete'    => 'Delete'
        );
        return $actions;
    }


    function column_cb($data) {
        return sprintf(
            '<input type="checkbox" name="bulk-delete[]" value="%s" />', $data['id']
        );
    }

    /**
     * Delete a customer record.
     *
     * @param int $id record ID
     */
    public static function delete_record( $id ) {
        global $wpdb;

        $wpdb->delete(
            "{$wpdb->prefix}file_downloader",
            [ 'id' => $id ],
            [ '%d' ]
        );
    }




    public function process_bulk_action() {

        //Detect when a bulk action is being triggered...
        if ( 'delete' === $this->current_action() ) {

            self::delete_record( absint( $_GET['id'] ) );

            wp_redirect( esc_url( remove_query_arg(array( 'action', 'id')) ) );
            //exit;

        }

        // If the delete bulk action is triggered
        if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' )
            || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' )
        ) {

            $delete_ids = esc_sql( $_POST['bulk-delete'] );

            // loop over the array of record IDs and delete them
            foreach ( $delete_ids as $id ) {
                self::delete_record( $id );

            }

            wp_redirect( esc_url( add_query_arg() ) );
            //exit;
        }
    }


}
