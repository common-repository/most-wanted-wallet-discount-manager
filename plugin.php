<?php
/**
 * Plugin Name: MOST_WANTED Wallet Discount Manager
 * Plugin URI: https://modules.mostwanted.lk/
 * Description: The best plugin for manage the WooCommerce wallet usage.
 * Version: 1.0.0
 * Author: MOST_WANTED
 * Author URI: https://mostwanted.lk/
 **/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;
if ( ! class_exists( 'YVK_wallet_discount_manager' ) ) :

/**
* Main YVK_wallet_discount_manager Class.
*
* @since  1.0
*/
final class YVK_wallet_discount_manager {
    /**
     * @var YVK_wallet_discount_manager The one true YVK_wallet_discount_manager
     * @since  1.0
     */
    private static $instance;

    /**
     * Main YVK_wallet_discount_manager Instance.
     *
     * Insures that only one instance of YVK_wallet_discount_manager exists in memory at any one
     * time. Also prevents needing to define globals all over the place.
     *
     * @since  1.0
     * @static
     * @staticvar array $instance
     * @uses YVK_wallet_discount_manager::setup_constants() Setup the constants needed.
     * @uses YVK_wallet_discount_manager::includes() Include the required files.
     * @uses YVK_wallet_discount_manager::load_textdomain() load the language files.
     * @see YVK_wallet_discount_manager()
     * @return object|YVK_wallet_discount_manager The one true YVK_wallet_discount_manager
     */
    public static function instance() {
        if ( ! isset( self::$instance ) && ! ( self::$instance instanceof YVK_wallet_discount_manager ) ) {
            self::$instance = new YVK_wallet_discount_manager;
            self::$instance->setup_constants();
            self::$instance->includes();
        }
        return self::$instance;
    }

    private function setup_constants() {

        // Plugin version.
        if ( ! defined( 'YVK_WALLET_DISCOUNT_MANAGER_PLUGIN_NAME' ) ) {
            define( 'YVK_WALLET_DISCOUNT_MANAGER_PLUGIN_NAME', 'YVK_wallet_discount_manager' );
        }

        // Plugin version.
        if ( ! defined( 'YVK_WALLET_DISCOUNT_MANAGER_VERSION' ) ) {
            define( 'YVK_WALLET_DISCOUNT_MANAGER_VERSION', '1.0.6' );
        }

        // Plugin Folder Path.
        if ( ! defined( 'YVK_WALLET_DISCOUNT_MANAGER_PLUGIN_DIR' ) ) {
            define( 'YVK_WALLET_DISCOUNT_MANAGER_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
        }

        // Plugin Folder URL.
        if ( ! defined( 'YVK_WALLET_DISCOUNT_MANAGER_PLUGIN_URL' ) ) {
            define( 'YVK_WALLET_DISCOUNT_MANAGER_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
        }

        // Plugin Root File.
        if ( ! defined( 'YVK_WALLET_DISCOUNT_MANAGER_PLUGIN_FILE' ) ) {
            define( 'YVK_WALLET_DISCOUNT_MANAGER_PLUGIN_FILE', __FILE__ );
        }
    }

    /**
     * Include required files.
     *
     * @access private
     * @since 1.0
     * @return void
     */
    private function includes() {
        require_once YVK_WALLET_DISCOUNT_MANAGER_PLUGIN_DIR . 'includes/wallet-discount-manager.php';
        if( is_admin() ){
            require_once YVK_WALLET_DISCOUNT_MANAGER_PLUGIN_DIR . 'includes/install.php';
            require_once YVK_WALLET_DISCOUNT_MANAGER_PLUGIN_DIR . 'includes/admin/settings.php';
        }
    }
}
endif; // End if class_exists check.

if( !function_exists( 'YVK_wallet_discount_manager_FN' ) ){
    function YVK_wallet_discount_manager_FN() {
        return YVK_wallet_discount_manager::instance();
    }
    // Get Plugin Running.
    YVK_wallet_discount_manager_FN();
}
?>