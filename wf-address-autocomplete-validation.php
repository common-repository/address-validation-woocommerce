<?php
/**
 * @package Address Validation & Google Address Auto Complete Plugin for WooCommerce (Basic)
 */
/*
Plugin Name: Address Validation & Google Address Auto Complete Plugin for WooCommerce (Basic)
Plugin URI: https://www.xadapter.com/product/address-validation-auto-complete-plugin-woocommerce/
Description: Simple and easy to use address validation plugin that will help you to forget the pain of shipping to invalid addresses.
Version: 1.3.2
WC requires at least: 2.6.0
WC tested up to: 3.4
Author: AdaptXY
Author URI: https://adaptxy.com/
License: GPLv2
Text Domain: wf-address-autocomplete-validation
*/
if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly.
}

// Woocommerce active check
if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    add_action( 'admin_notices', 'xa_basic_address_woocommerce_inactive_notice' );
    return;
 }
function xa_basic_address_woocommerce_inactive_notice() {
    ?>
<div id="message" class="error">
    <p>
	<?php	print_r(__( '<b>WooCommerce</b> plugin must be active for <b>Address Validation & Google Address Auto Complete Plugin for WooCommerce (Basic)</b> to work. ', 'wf-address-autocomplete-validation' ) ); ?>
    </p>
</div>
<?php
}

//Function to check whether Premium version is installed or not
function wf_address_validation_basic_pre_activation_check(){
	if ( is_plugin_active('address-validation-and-auto-complete-plugin/wf-address-autocomplete-validation.php') ){
		deactivate_plugins( basename( __FILE__ ) );
		wp_die(__("You already have the Premium version installed in your website. For any issues, kindly raise a ticket via our <a target='_blank' href='https://www.xadapter.com/online-support/'>support</a>.", "wf-address-autocomplete-validation"), "", array('back_link' => 1 ));
	}
	else
	{
		include_once ('includes/wf_db_migration.php');
	}
}
register_activation_hook( __FILE__, 'wf_address_validation_basic_pre_activation_check' );

//check if woocommerce exists
if ( !class_exists( 'woocommerce' ) ) {   
add_action( 'admin_init', 'my_plugin_deactivate' );
if ( ! function_exists( 'my_plugin_deactivate' ) ) {
function my_plugin_deactivate() { 
	  	if ( !class_exists( 'woocommerce' ) )
	  	{
           deactivate_plugins( plugin_basename( __FILE__ ) );
           wp_safe_redirect( admin_url('plugins.php') );
	                       
	  	}
	}}
}
if ( ! function_exists( 'woocommerce_version_check' ) ) {
function woocommerce_version_check(){
	if( ! defined( 'WC_VERSION') )
	{
	    define( 'WC_VERSION', WC()->version );
	}
}
}
add_action( 'admin_init', 'woocommerce_version_check');

if(!class_exists('Wf_Address_Autocomplete_Validation_Setup')){
    
	//Class - To setup the plugin
	class Wf_Address_Autocomplete_Validation_Setup {
		protected $all_settings;
		//constructor
		public function __construct() {
			$this->all_settings = get_option('wf_address_autocomplete_validation_settings');
			$this->wf_address_autocomplete_validation_init();
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'wf_address_autocomplete_validation_plugin_action_links' ) );
		}

		public function wf_get_settings_url(){
			return version_compare(WC()->version, '1.0', '>=') ? "wc-settings" : "woocommerce_settings";
		}
		//to add settings url near plugin under installed plugin
		public function wf_address_autocomplete_validation_plugin_action_links( $links ) {
			$plugin_links = array(
				'<a href="' . admin_url( 'admin.php?page=' . $this->wf_get_settings_url() . '&tab=wf_address_autocomplete_validation' ) . '">' . __( 'Settings', 'wf-address-autocomplete-validation' ) . '</a>',
				'<a href="https://www.xadapter.com/product/address-validation-auto-complete-plugin-woocommerce/" target="_blank">' . __( 'Premium Upgrade', 'wf_estimated_delivery' ) . '</a>',
				'<a href="https://wordpress.org/support/plugin/address-validation-woocommerce" target="_blank">' . __( 'Support', 'wf-address-autocomplete-validation' ) . '</a>',
			);
			return array_merge( $plugin_links, $links );
		}
		//to include the necessary files for plugin
		public function wf_address_autocomplete_validation_init() {
			include_once( 'includes/class-wf-address-autocomplete-validation-settings.php' );
			$this->settings = new Wf_Address_Autocomplete_Validation_Settings();
			$this->settings->Wf_Address_Autocomplete_Validation_Setup();
			$this->enable_autocomplete = isset($this->all_settings[ 'wf_aac_enable_autocomplete' ]) ? $this->all_settings[ 'wf_aac_enable_autocomplete' ] : '';
			$this->enable_address_validation = isset($this->all_settings[ 'xa_address_validation' ]) ? $this->all_settings[ 'xa_address_validation' ] : 'none';
			
			if($this->enable_autocomplete == 'yes' && $this->all_settings['wf_address_autocomplete_validation_google_api_key']){
				include_once( 'includes/class-wf-address-autocomplete.php' );
				new Wf_Address_Autocomplete();
				include_once( 'includes/class-wf-address-php_to_js.php' );
			}
			
			if($this->enable_address_validation === 'easypost'){
				include_once( 'includes/class-wf-easypost-address-validation.php' );
				new Wf_Address_Validation();
                                
                                if(!class_exists('EasyPost\EasyPost')){
                                    include_once( 'includes/lib/easypost.php' );
                                }
				include_once( 'includes/log.php' );
			}
		}		
	}	
}

//Execute only on dashboard or on network dashboard
if(is_admin() || is_network_admin())
{
	include_once ('includes/wf_db_migration.php');		//To transfer Data from previous database to new database
	new Wf_Address_Autocomplete_Validation_Setup();
}

//To execute only on checkout page when visiting any woocommerce site
if( ! function_exists('Wf_Address_Autocomplete_Validation_Setup_call') )
{
	function Wf_Address_Autocomplete_Validation_Setup_call(){
		if(is_checkout())
		{
			new Wf_Address_Autocomplete_Validation_Setup();
		}
	}
}
add_action('woocommerce_checkout_init','Wf_Address_Autocomplete_Validation_Setup_call');

//If Selects 'Sell to specific countries' in woocommerce settings.
add_filter( 'woocommerce_billing_fields', 'xa_filter_state', 10, 1 );
    function xa_filter_state( $address_fields ) {
            $address_fields['billing_state']['class'] = array( 'form-row-wide', 'address-field','update_totals_on_change' );
            return $address_fields;
    }
    
add_filter( 'woocommerce_shipping_fields', 'xa_filter_shipping_state', 10, 1 );
    function xa_filter_shipping_state( $address_fields ) {
            $address_fields['shipping_state']['class'] = array( 'form-row-wide', 'address-field','update_totals_on_change' );
            return $address_fields;
    }