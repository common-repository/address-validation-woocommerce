<?php

	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	class Wf_Enable_Disable {
		//constructor
	public function __construct() {
		add_action('wp_ajax_wf_address_validation_enable_disable',array($this, 'wf_address_validation_send_data'));
		add_action('wp_ajax_nopriv_wf_address_validation_enable_disable',array($this, 'wf_address_validation_send_data'));

	}
	 public function wf_address_validation_send_data(){
		$all_settings = get_option('wf_address_autocomplete_validation_settings');
		$status_enable_disable = $all_settings['wf_address_autocomplete_validation_enable_disable_autocomplete_fields'];
		$phptojs=array(
			"status"  => 'success',
			"status_enable_disable" => $status_enable_disable,
			);

		die(json_encode($phptojs));
	}
}

new Wf_Enable_Disable();
