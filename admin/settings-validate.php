<?php // xAPI Post Options - Settings Validation

// exit if file is called directly
if ( !defined ( 'ABSPATH' ) ) {
	
	exit;
	
}

// validate plugin settings
function xpo_validate_options($input) {
	
	// Send statements system
	if ( ! isset( $input['system_send_statements'] ) ) {
		
		$input['system_send_statements'] = null;
		
	}
	
	// Send statements system
	if ( ! isset( $input['track_not_logged_in'] ) ) {
		
		$input['track_not_logged_in'] = null;
		
	}
	
	// Actor setting
	$select_options = array(
		
		'not-set'   => 'Not Set',
		'user-id'     => 'User ID',
		'full-name'   => 'Full name',
		'username'      => 'Username',
		'email'    => 'Email',
		'anonymous' => 'Anonymous',
		
	);
	
	if ( ! isset( $input['system_actor'] ) ) {
		
		$input['system_actor'] = null;
		
	}	
	
	if ( ! array_key_exists( $input['system_actor'], $select_options ) ) {
		
		$input['system_actor'] = null;
	
	}

	// Add metabox to postype
	$postTypes = get_post_types();
	
	foreach($postTypes as $post){
	// Send statements system
	if ( ! isset( $input['metabox_' . $post] ) ) {
		
		$input['metabox_' . $post] = null;
		
	}
	}

	// LRS Endpoint
	if ( isset( $input['lrs_endpoint'] ) ) {
		
		$input['lrs_endpoint'] = sanitize_text_field( $input['lrs_endpoint'] );
		
	}
	
	// LRS Username
	if ( isset( $input['lrs_username'] ) ) {
		
		$input['lrs_username'] = sanitize_text_field( $input['lrs_username'] );
		
	}
	
	// LRS Password
	if ( isset( $input['lrs_password'] ) ) {
		
		$input['lrs_password'] = sanitize_text_field( $input['lrs_password'] );
		
	}
	
	return $input;
	
}