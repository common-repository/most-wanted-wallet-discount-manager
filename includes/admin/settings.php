<?php
/**
 * Admin Options Page
 * Settings > Wallet Discount Manager
 *
 * @copyright   Copyright (c) 2017, Jeffrey Carandang
 * @since       1.0
 */
// Exit if accessed directly
if (!defined('ABSPATH')) exit;

/**
 * Creates the admin submenu pages under the Settings menu and assigns their
 *
 * @return void
 * @since 1.0
 */

function yvk_wallet_discount_manager_dependancyCheck() {
    $active_plugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );
    if ( in_array( 'woocommerce/woocommerce.php', $active_plugins ) && in_array( 'woo-wallet/woo-wallet.php', $active_plugins ) ) {
        return true;
    } 
    return false;
}

if (!yvk_wallet_discount_manager_dependancyCheck()) {
    $active_plugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );
    if ( !in_array( 'woocommerce/woocommerce.php', $active_plugins )) {
        add_action( 'admin_notices', 'yvk_wallet_discount_manager_admin_notices_woocommerce' );
    }
    if ( !in_array( 'woo-wallet/woo-wallet.php', $active_plugins )) {
        add_action( 'admin_notices', 'yvk_wallet_discount_manager_admin_notices_woo_wallet' );
    }
    
    function yvk_wallet_discount_manager_admin_notices_woocommerce() {
        echo '<div class="error"><p>';
        esc_html_e( 'Wallet Discount Manager plugin requires <a href="https://wordpress.org/plugins/woocommerce/">WooCommerce</a> plugin to be active!' );
        echo '</p></div>';
    }
    function yvk_wallet_discount_manager_admin_notices_woo_wallet() {
        echo '<div class="error"><p>';
        esc_html_e( 'Wallet Discount Manager plugin requires <a href="https://wordpress.org/plugins/woo-wallet/">TeraWallet</a> plugin to be active!' );
        echo '</p></div>';
    }
}

if (!function_exists('yvk_wallet_discount_manager_options_cb')):
    function yvk_wallet_discount_manager_options_cb()
    {
        register_setting('yvk_wallet_discount_manager_settings_group', 'yvk_wallet_discount_manager_settings', 'yvk_wallet_discount_manager_settings_sanitize');
    }
endif;

if (yvk_wallet_discount_manager_dependancyCheck()) {
    if (!function_exists('yvk_wallet_discount_manager_options_link')):
        function yvk_wallet_discount_manager_options_link()
        {
            $iconPath = plugins_url('assets/images/yvk-logo-small.png', __FILE__ );
            add_menu_page(
                __('Wallet Discount Manager', 'yvk-wallet-discount-manager'),
                __('Wallet Discount', 'yvk-wallet-discount-manager'),
                'manage_options',
                'yvk_wallet_discount_manager_plugin_settings',
                'yvk_wallet_discount_manager_options_page',
                $iconPath,
                60
            );
        }
    
        add_action('admin_menu', 'yvk_wallet_discount_manager_options_link');
        add_action('admin_init', 'yvk_wallet_discount_manager_options_cb');
    endif;

    if (!function_exists('yvk_wallet_discount_manager_options_scripts')):
        function yvk_wallet_discount_manager_options_scripts($hook)
        {
            if ('settings_page_yvk_wallet_discount_manager_plugin_settings' == $hook) {
                wp_enqueue_media();
            }
        }

        add_action('admin_enqueue_scripts', 'yvk_wallet_discount_manager_options_scripts');
    endif;
}

/**
 * Options Page
 *
 * Renders the options page contents.
 *
 * @return void
 * @since 1.0
 */
if (!function_exists('yvk_wallet_discount_manager_options_page')):
    function yvk_wallet_discount_manager_options_page()
    {
        $options = get_option('yvk_wallet_discount_manager_settings'); 
        $wallet_discount = false;
        if ( $attribute_taxonomies = wc_get_attribute_taxonomies() ) {
            foreach ( $attribute_taxonomies as $tax ) {
                if ( wc_attribute_taxonomy_name( $tax->attribute_name ) == "pa_wallet_discount" ) {
                    $wallet_discount = true;
                }
            }
        }

        ?>

        <div class="wrap">
            <h2>Wallet Discount Manager 
                <span>
                    <small style="font-size: 9px"><?php esc_html_e('Developed by')?> <a target="_blank" href="https://mostwanted.lk/">MOST_WANTED</a></small>
                </span>
            </h2>
            <hr/>
            <?php if (!yvk_wallet_discount_manager_dependancyCheck()) { ?>
                <h3 style="background: #cccccc; padding: 10px"><?php esc_html_e( 'Dependancy Plugins are not activated') ?> </h3>
                <p><?php esc_html_e( 'To use this plugin,') ?> <a href="https://woocommerce.com/" target="_blank">WooCommerce</a> <?php esc_html_e('and') ?> <a href="https://wordpress.org/plugins/woo-wallet/" target="_blank">TeraWallet</a> <?php esc_html_e('plugins need to be installed') ?></p>
            <?php }?>
            <br/>
        </div>
        <?php if (yvk_wallet_discount_manager_dependancyCheck()) { ?>
            <form method="post" action="options.php" id="yvk-wallet-discount-manager-settings-form">
                <?php settings_fields('yvk_wallet_discount_manager_settings_group'); ?>
                <?php do_settings_sections('yvk_wallet_discount_manager_plugin_settings'); ?>
                <table class="form-table yvk-wallet-discount-manager-settings-table">
                    <tr>
                        <th scope="row">
                            <label><?php esc_html_e('Apply wallet balance as', 'yvk-wallet-discount-manager-styler'); ?></label>
                        </th>
                        <td>
                            <select id="discounttype" name="yvk_wallet_discount_manager_settings[discountType]" value="<?php esc_html(is_array($options) && isset($options['discountType'])) ? $options['discountType'] : ''; ?>">
                                <option value="fix" <?php esc_html(($options['discountType']) == "fix") ? 'selected' : '';?>><?php esc_html_e( 'Fix Price' ) ?></option>
                                <option value="percentage" <?php esc_html(($options['discountType']) == "percentage") ? 'selected' : '';?>><?php esc_html_e( 'Percentage(%)') ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label><?php esc_html_e('Use wallet balance', 'yvk-wallet-discount-manager-styler'); ?></label>
                        </th>
                        <td>
                            <select id="discountfor" name="yvk_wallet_discount_manager_settings[discountFor]" value="<?php  esc_html(is_array($options) && isset($options['discountFor'])) ? $options['discountFor'] : ''; ?>">
                                <option value="all" <?php  esc_html(($options['discountFor']) == "all") ? 'selected' : '';?>><?php esc_html_e( 'Wallet Total') ?></option>
                                <option disabled><?php esc_html_e( 'Each Product (Pro)') ?></option>
                            </select>
                            <span id="all-products-container">
                                <input id="discountvalue" class="label-responsive" style="width: 110px; text-align: right;" type="number" name="yvk_wallet_discount_manager_settings[discountValue]" value="<?php  echo(is_array($options) && isset($options['discountValue'])) ? esc_html_e($options['discountValue']) : ''; ?>"/>
                                <span class="discountUnit"></span>
                            </span>
                        </td>
                    </tr>
                </table>
                <?php
                
                if( function_exists('submit_button')) { submit_button(); } else { ?>
                    <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php esc_html_e( 'Save Changes', 'yvk-wallet-discount-manager-styler' );?>"></p>
                <?php } ?>
            </form>
            <script type="text/javascript">
                jQuery(document).ready(function () {
                    yvk_wallet_discount_manager_updateUnit();

                    function yvk_wallet_discount_manager_updateUnit() {
                        if (jQuery("#discounttype").children("option:selected").val() == 'fix') {
                            jQuery(".discountUnit").html('<?php echo get_woocommerce_currency_symbol(); ?>');
                        }
                        else {
                            jQuery(".discountUnit").html('%');
                        }
                    }

                    jQuery("#discounttype").change(function () {
                        yvk_wallet_discount_manager_updateUnit();
                    });
                });
            </script>
        <?php }
    }
endif;
?>
