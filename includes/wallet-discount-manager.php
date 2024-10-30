<?php

add_filter('is_valid_payment_through_wallet', '__return_false');
add_filter('woo_wallet_partial_payment_amount', 'yvk_wallet_discount_manager_partial_payment_amount_callback');
function yvk_wallet_discount_manager_partial_payment_amount_callback($amount)
{
    $cartTotal = WC()->cart->get_cart_contents_total();
    $options = get_option('yvk_wallet_discount_manager_settings');
    $walletDiscountPrice = sanitize_text_field($options['discountValue']);
    $walletPrice = woo_wallet()->wallet->get_wallet_balance(get_current_user_id(), 'edit');
    $allPercentageWalletDiscount = ($walletPrice * $walletDiscountPrice) / 100;

    if ($cartTotal < $allPercentageWalletDiscount) {
        $allPercentageWalletDiscount = $cartTotal;
    }

    if (sanitize_text_field($options['discountType']) == "percentage"){
        return $allPercentageWalletDiscount;
    } elseif (sanitize_text_field($options['discountType']) == "fix"){
        if ($walletPrice > $walletDiscountPrice && $walletDiscountPrice < $cartTotal) {
            return $walletDiscountPrice;
        } 
        elseif ($walletPrice > $walletDiscountPrice && $walletDiscountPrice > $cartTotal) {
            return $cartTotal;
        } 
        elseif ($walletPrice < $walletDiscountPrice && $walletPrice > $cartTotal) {
            return $cartTotal;
        }
        elseif ($walletPrice < $walletDiscountPrice && $walletPrice < $cartTotal) {
            return $walletPrice;
        }
    }
}
?>