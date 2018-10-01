<?php //xAPI Post Options - Register Settings


// exit if file is called directly
if ( !defined ( 'ABSPATH' ) ) {
	
	exit;
	
}


function xpo_register_settings() {
	
	register_setting(
		'xpo_options',
		'xpo_options',
		'xpo_callback_validate_options'
	);
	
	
	// Sections
	add_settings_section( 
		'xpo_general_details', 
		'General', 
		'xpo_callback_section_general', 
		'xpo-settings'
	);
	
	add_settings_section( 
		'xpo_add_metabox_to_postype', 
		'Add metabox to post types', 
		'xpo_callback_section_metabox', 
		'xpo-settings'
	);	
	
	add_settings_section( 
		'xpo_lrs_details', 
		'LRS Details', 
		'xpo_callback_section_lrs', 
		'xpo-settings'
	);
	
	// Settings Fields
	add_settings_field(
		'system_send_statements',
		'Send Statements to LRS',
		'xpo_callback_field_checkbox',
		'xpo-settings',
		'xpo_general_details',
		[ 'id' => 'system_send_statements', 'label' => 'Send Statements to LRS' ]
	);
	
	add_settings_field(
		'track_not_logged_in',
		'Track guests',
		'xpo_callback_field_checkbox',
		'xpo-settings',
		'xpo_general_details',
		[ 'id' => 'track_not_logged_in', 'label' => 'Track guests' ]
	);
	
	add_settings_field(
		'system_actor',
		'Actor Setting',
		'xpo_callback_field_select',
		'xpo-settings',
		'xpo_general_details',
		[ 'id' => 'system_actor', 'label' => 'Actor Setting' ]
	);
	
	// Create checkbox for each post type
	$postTypes = get_post_types();
	
	foreach($postTypes as $post){
	if($post == 'custom_css' || $post == "nav_menu_item" || $post == "customize_changeset" || $post == "oembed_cache" || $post == "user_request"){
		continue;
	}
	add_settings_field(	
		'xpo_add_metabox_' . $post,
		$post,
		'xpo_callback_field_checkbox',
		'xpo-settings',
		'xpo_add_metabox_to_postype',
		[ 'id' => 'xpo_add_metabox_' . $post, 'label' => $post ]
	);
	}
	
	add_settings_field(
		'lrs_endpoint',
		'LRS Endpoint',
		'xpo_callback_field_text',
		'xpo-settings',
		'xpo_lrs_details',
		[ 'id' => 'lrs_endpoint', 'label' => 'LRS Endpoint' ]
	);
	
	add_settings_field(
		'lrs_username',
		'LRS Username',
		'xpo_callback_field_text',
		'xpo-settings',
		'xpo_lrs_details',
		[ 'id' => 'lrs_username', 'label' => 'LRS Username' ]
	);
	
	add_settings_field(
		'lrs_password',
		'LRS Password',
		'xpo_callback_field_password',
		'xpo-settings',
		'xpo_lrs_details',
		[ 'id' => 'lrs_password', 'label' => 'LRS Password' ]
	);

}
add_action( 'admin_init', 'xpo_register_settings' );