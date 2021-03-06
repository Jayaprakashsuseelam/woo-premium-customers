<?php
/*
   Plugin Name: WooCommerce Premium Customer
   Plugin URI: https://github.com/Jayaprakashsuseelam/woo-premium-customers
   description: WordPress plugin for auto assign premium status to the customers based on WooCommerce orders.
   Version: 1.0.0
   Author: Jayaprakash
   Author URI: https://github.com/jayaprakashsuseelam
*/


/**
 * Function for plugin activation hook
 * @param : global @wpdb
 * @return : activation hook
 */
function premium_customer_activate(){
  global $wpdb;
  $charset_collate = $wpdb->get_charset_collate(); // get/set charset collate on table
  $tablename = $wpdb->prefix."premium_customer";

  // table structure
  $sql = "CREATE TABLE IF NOT EXISTS $tablename (
  id mediumint(11) NOT NULL AUTO_INCREMENT,
  time_frame mediumint(6) NOT NULL,
  order_frequency varchar(80),
  amount varchar(80),
  PRIMARY KEY  (id)
  ) $charset_collate;";

  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
  dbDelta( $sql );
}
register_activation_hook( __FILE__, 'premium_customer_activate' );


/**
 * Function for plugin de activation hook
 * @param : global @wpdb
 * @return : de activation hook
 */
function customer_customer_deactivate(){
  global $wpdb;
  $table_name = $wpdb->prefix."premium_customer";
  $wpdb->query("DROP TABLE IF EXISTS {$table_name}");
}
register_deactivation_hook(__FILE__, 'customer_customer_deactivate'); // Deactivation hook
register_uninstall_hook(__FILE__, 'customer_customer_deactivate'); // Uninstall hook


/**
 * Function for calling settings page
 * @param : global @wpdb
 * @return :
 */
function settings(){
  global $wpdb;
  $tablename = $wpdb->prefix."premium_customer";
  $getVipSettings = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $tablename") ) ;

  require_once plugin_dir_path(__FILE__) . 'templates/settings.php';
}


/**
 * Function for calling WooCommerce orders hook for adding new custom column
 * @param : $columns
 * @return :  $columns['premium_column']
 */
function wc_new_order_column( $columns ) {
    $columns['premium_column'] = 'Premium Customer';
    return $columns;
}
add_filter( 'manage_edit-shop_order_columns', 'wc_new_order_column', 3, 3);


/**
 * Function for adding plugin menu item
 * @param : 
 * @return :  
 */ 
function premium_customer_menu() {
    add_menu_page("WooCommerce Premium Customer", "WooCommerce Premium Customer","manage_options", "premium_customer", "settings","dashicons-awards");
}
add_action("admin_menu", "premium_customer_menu");


/**
 * Function for premium customer checkings and displayings
 * @param : 
 * @return :  
 */ 
function woocommerce_orders_list_updates($column) {
      global $the_order; global $wpdb;
      $tablename = $wpdb->prefix."premium_customer";
      $getVipSettings = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $tablename") );

      if(!empty($getVipSettings)){
      // Get order id
      $order_id = $the_order->id;
      $order = new WC_Order($order_id); // WooCommerce order class - object creation (WooCommerce should be installed)
      $user_id = $order->user_id; // User id

      $time_frame = $getVipSettings->time_frame;
      $order_frequency = $getVipSettings->order_frequency;
      $amount = $getVipSettings->amount;
      $date = date('Y-m-d H:i:s', strtotime("- $time_frame day") );
      $getTotal = get_all_orders_amount($user_id); // Get orders total amount

      $args = array(
        'customer_id' => $user_id,
        'date_query'  => array (
           'after' => $date
         )
      );
      $orders = wc_get_orders($args);
      $total_count = count($orders);

      if( $column == 'premium_column' ) {
        if($order_frequency <= $total_count){
          echo '<img src="'.esc_url( plugins_url( "woo_premium_customer/assets/images/premium.png", dirname(__FILE__))).'" style="width: 50px;" />';
        }elseif($amount <= $getTotal){
          echo '<img src="'.esc_url( plugins_url( "woo_premium_customer/assets/images/premium.png", dirname(__FILE__))).'" style="width: 50px;" />';
        }
      } 
    }
}
add_action( 'manage_shop_order_posts_custom_column' , 'woocommerce_orders_list_updates' );


/**
* Function for fetching all order total amount against a user id
* @param : user_id
* @return : order total (int)
**/
function get_all_orders_amount($user_id){
   $orders = get_posts( array(
        'numberposts' => - 1,
        'meta_key'    => '_customer_user',
        'meta_value'  => $user_id,
        'post_type'   => array( 'shop_order' ),
        'post_status' => array('wc-completed', 'wc-processing', 'wc-on-hold')
    ) );

    $total = 0;
    foreach ( $orders as $customer_order ) {
        $order = wc_get_order( $customer_order );
        $total += $order->get_total();
    }
    return $total;
}

/**
* Function for saving/updating plugin settings
* AJAX call handler function
* @param : global @wpdb
**/
add_action( 'wp_ajax_save_settings', 'save_settings' );
add_action( 'wp_ajax_nopriv_save_settings', 'save_settings' );
function save_settings() {
   global $wpdb;
   $table = $wpdb->prefix."premium_customer";
   $delete_action = $wpdb->query("TRUNCATE TABLE $table");

   if($delete_action){
     $lect_data['time_frame'] = $_POST['time_frame'];
     $lect_data['order_frequency'] = $_POST['order_frequency'];
     $lect_data['amount'] = $_POST['amount'];
     $wpdb->insert($table,$lect_data);
     echo $saved_id = $wpdb->insert_id;
   }else{
     echo 0;
   }
   wp_die();
}
