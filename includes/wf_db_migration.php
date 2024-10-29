<?php

//Database Migration
	$temp=get_option('wf_address_autocomplete_validation_settings');
	if(! $temp && get_option('wf_aac_enable_autocomplete'))
	{
		$all_settings['wf_aac_enable_autocomplete'] = get_option('wf_aac_enable_autocomplete');
		$all_settings['wf_address_autocomplete_validation_google_api_key'] = get_option('wf_address_autocomplete_validation_google_api_key');
		$all_settings['wf_address_autocomplete_validation_enable_disable_autocomplete_fields'] = get_option('wf_address_autocomplete_validation_enable_disable_autocomplete_fields');
		$enable_validation=get_option('wf_aac_enable_validation');
		$all_settings['xa_address_validation'] = ($enable_validation =='yes') ? 'easypost' : 'none';
		$all_settings['wf_address_autocomplete_validation_easypost_api_key'] = get_option('wf_address_autocomplete_validation_easypost_api_key');
		$all_settings['wf_address_autocomplete_validation_record_log'] = get_option('wf_address_autocomplete_validation_record_log');
		$all_settings['wf_address_autocomplete_validation_enable_address_popup'] = 'yes';
		$all_settings['wf_address_autocomplete_validation_enable_address_popup_css_edit']='.xa-modal-content{margin:auto;background-color:#fff;position:relative;padding:0;outline:0;width:600px}
			.xa-container{padding:0.01em 16px}
			.xa-container:after{content:"";display:table;clear:both}
			.xa-white{color:#000!important;background-color:#fff!important}
			.xa-red{color:#fff!important;background-color:#f44336!important}
			.xa-center{text-align:center!important}
			.xa-btn{border:none;display:inline-block;outline:0;padding:6px 16px;vertical-align:middle;overflow:hidden;text-decoration:none!important;color:red!important;background-color:#000;text-align:center;cursor:pointer;white-space:nowrap}
			.xa-btn:hover{box-shadow:0 8px 16px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19)}
			.xa-btn:hover{background-color: blue !important;}
			.xa-btn:disabled{cursor:not-allowed;opacity:0.3}
			.xa-btn:disabled *{pointer-events:none}
			.xa-btn:disabled:hover{box-shadow:none}
			.xa-btn{float:left}
			.xa-btn{box-shadow:none!important;text-shadow:none!important;background-color:inherit;color:inherit;float:left}
			.xa-btn{display:block;padding:8px 16px}
			.xa-btn{border:none;outline:none;width:100%}
			.xa-btn{-webkit-transition:background-color .3s,color .15s,box-shadow .3s,opacity 0.3s,filter 0.3s;transition:background-color .3s,color .15s,box-shadow .3s,opacity 0.3s,filter 0.3s}
			.xa-round-large{border-radius:8px!important}.xa-round-xlarge{border-radius:16px!important}
			.xa-border{border:1px solid #ccc!important}
			@media (max-width:600px){.xa-modal-content{margin:0 10px;width:auto!important}.xa-modal{padding-top:30px}}
			@media (max-width:768px){.xa-modal-content{width:500px}.xa-modal{padding-top:50px}}
			@media (min-width:993px){.xa-modal-content{width:900px}}
			.xa-closebtn{-webkit-transition:background-color .3s,color .15s,box-shadow .3s,opacity 0.3s,filter 0.3s;transition:background-color .3s,color .15s,box-shadow .3s,opacity 0.3s,filter 0.3s}
			.xa-closebtn{text-decoration:none;float:right;font-size:24px;font-weight:bold;color:inherit}
			.xa-closebtn:hover,.xa-closebtn:focus{color:#000;text-decoration:none;cursor:pointer}
';
		//delete all previous options to clear data duplication
		delete_option('wf_aac_enable_autocomplete');
		delete_option('wf_address_autocomplete_validation_google_api_key');
		delete_option('wf_address_autocomplete_validation_enable_disable_autocomplete_fields');
		delete_option('wf_aac_enable_validation');
		delete_option('wf_address_autocomplete_validation_easypost_api_key');
		delete_option('wf_address_autocomplete_validation_record_log');
		//Update data into new databse 
		update_option('wf_address_autocomplete_validation_settings', $all_settings);
	}
	elseif (empty($temp) || !($temp)) {
		$all_settings['wf_address_autocomplete_validation_enable_address_popup_css_edit']='.xa-modal-content{margin:auto;background-color:#fff;position:relative;padding:0;outline:0;width:600px}
			.xa-container{padding:0.01em 16px}
			.xa-container:after{content:"";display:table;clear:both}
			.xa-white{color:#000!important;background-color:#fff!important}
			.xa-red{color:#fff!important;background-color:#f44336!important}
			.xa-center{text-align:center!important}
			.xa-btn{border:none;display:inline-block;outline:0;padding:6px 16px;vertical-align:middle;overflow:hidden;text-decoration:none!important;color:red!important;background-color:#000;text-align:center;cursor:pointer;white-space:nowrap}
			.xa-btn:hover{box-shadow:0 8px 16px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19)}
			.xa-btn:hover{background-color: blue !important;}
			.xa-btn:disabled{cursor:not-allowed;opacity:0.3}
			.xa-btn:disabled *{pointer-events:none}
			.xa-btn:disabled:hover{box-shadow:none}
			.xa-btn{float:left}
			.xa-btn{box-shadow:none!important;text-shadow:none!important;background-color:inherit;color:inherit;float:left}
			.xa-btn{display:block;padding:8px 16px}
			.xa-btn{border:none;outline:none;width:100%}
			.xa-btn{-webkit-transition:background-color .3s,color .15s,box-shadow .3s,opacity 0.3s,filter 0.3s;transition:background-color .3s,color .15s,box-shadow .3s,opacity 0.3s,filter 0.3s}
			.xa-round-large{border-radius:8px!important}.xa-round-xlarge{border-radius:16px!important}
			.xa-border{border:1px solid #ccc!important}
			@media (max-width:600px){.xa-modal-content{margin:0 10px;width:auto!important}.xa-modal{padding-top:30px}}
			@media (max-width:768px){.xa-modal-content{width:500px}.xa-modal{padding-top:50px}}
			@media (min-width:993px){.xa-modal-content{width:900px}}
			.xa-closebtn{-webkit-transition:background-color .3s,color .15s,box-shadow .3s,opacity 0.3s,filter 0.3s;transition:background-color .3s,color .15s,box-shadow .3s,opacity 0.3s,filter 0.3s}
			.xa-closebtn{text-decoration:none;float:right;font-size:24px;font-weight:bold;color:inherit}
			.xa-closebtn:hover,.xa-closebtn:focus{color:#000;text-decoration:none;cursor:pointer}';
			update_option('wf_address_autocomplete_validation_settings', $all_settings);
	}