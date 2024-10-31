<?php
 
/**
 * Plugin Name: Per Product Shipping
 * Plugin URI: http://www.youtotech.com
 * Description: Per Product Custom Shipping Method for WooCommerce
 * Version: 1.0.0
 * Author: Rahul Goswami
 * Author URI: https://www.linkedin.com/in/rahul-goswami-85b96548
 * License: GPL-3.0+
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Domain Path: /lang
 * Text Domain: per-product-shipping
 */
 
if ( ! defined( 'WPINC' ) ) {
 
    die;
 
}
 
/*
 * Check if WooCommerce is active
 */
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
 
    function per_product_shipping_method() {
        if ( ! class_exists( 'Per_Product_Shipping_Method' ) ) {
            class Per_Product_Shipping_Method extends WC_Shipping_Method {
                /**
                 * Constructor for your shipping class
                 *
                 * @access public
                 * @return void
                 */
                public function __construct() {
                    $this->id                 = 'per-product-shipping'; 
                    $this->method_title       = __( 'Per Product Shipping', 'per-product-shipping' );  
                    $this->method_description = __( 'Custom Shipping Method for WooCommerce', 'per-product-shipping' ); 
 
                    $this->init();
 
                    $this->enabled = isset( $this->settings['enabled'] ) ? $this->settings['enabled'] : 'yes';
                    $this->title = isset( $this->settings['title'] ) ? $this->settings['title'] : __( 'Per Product Shipping', 'per-product-shipping' );
                }
 
                /**
                 * Init your settings
                 *
                 * @access public
                 * @return void
                 */
                function init() {
                    // Load the settings API
                    $this->init_form_fields(); 
                    $this->init_settings(); 
 
                    // Save settings in admin if you have any defined
                    add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
                }
 
                /**
                 * Define settings field for this shipping
                 * @return void 
                 */
                function init_form_fields() { 
 
                    // We will add our settings here
 
                }
 
                /**
                 * This function is used to calculate the shipping cost. Within this function we can check for weights, dimensions and other parameters.
                 *
                 * @access public
                 * @param mixed $package
                 * @return void
                 */
                public function calculate_shipping( $package ) {
                    
                    // We will add the cost, rate and logics in here

global $woocommerce;

if($woocommerce->customer->get_billing_country() != 'IN')
{

            if($woocommerce->cart->cart_contents_count >= 2 && $woocommerce->customer->get_billing_country() != 'IN')
        {
            $cost = ($woocommerce->cart->cart_contents_count-1)*400+1500;
        }
        elseif ($woocommerce->cart->cart_contents_count <=1 && $woocommerce->customer->get_billing_country() != 'IN') {
           $cost = 1500;
        }
        

    $rate = array(
        'id' => $this->id,
        'label' => 'Per Product Shipping',
        'cost' => $cost,
        'calc_tax' => 'per_order'
    );

    // Register the rate
    $this->add_rate( $rate );
} // check if current country is IN
                    
                }
            }
        }
    }
 
    add_action( 'woocommerce_shipping_init', 'per_product_shipping_method' );
 
    function add_per_product_shipping_method( $methods ) {
        $methods[] = 'Per_Product_Shipping_Method';
        return $methods;
    }
 
    add_filter( 'woocommerce_shipping_methods', 'add_per_product_shipping_method' );
}