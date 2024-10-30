<?php
/**
 * Install Function
 *
 * @copyright   Copyright (c) 2017, Jeffrey Carandang
 * @since       1.0
*/
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

//add settings link on plugin page
if( !function_exists( 'yvk_wallet_discount_manager_plugin_actions' ) ){
  add_action( 'plugin_action_links_' . plugin_basename( YVK_WALLET_DISCOUNT_MANAGER_PLUGIN_FILE ) , 'yvk_wallet_discount_manager_plugin_actions' );
  function yvk_wallet_discount_manager_plugin_actions($links){
    $links[]  = '<a href="'. esc_url( admin_url( 'options-general.php?page=yvk_wallet_discount_manager_plugin_settings' ) ) .'">' . __( 'Settings', 'yvk_wallet_discount_manager' ) . '</a>';
    return $links;
  }
}

//register default values
if( !function_exists( 'yvk_wallet_discount_manager_register_defaults' ) ){
	register_activation_hook( YVK_WALLET_DISCOUNT_MANAGER_PLUGIN_FILE, 'yvk_wallet_discount_manager_register_defaults' );
  	add_action( 'plugins_loaded', 'yvk_wallet_discount_manager_register_defaults' );
	function yvk_wallet_discount_manager_register_defaults(){
		if( is_admin() ){

			if( !get_option( 'yvk_wallet_discount_manager_installDate' ) ){
				add_option( 'yvk_wallet_discount_manager_installDate', date( 'Y-m-d h:i:s' ) );
			}

		}
	}
}

?>
