<?php //xAPI Post Options - Admin Menu

// exit if file is called directly
if ( !defined ( 'ABSPATH' ) ) {
	
	exit;
	
}

function xpo_setup_menu(){
         add_menu_page( 
			'xAPI Post Options', //title
			'xAPI Post Options', //menu title
			'manage_options', //required user capability
			'xpo-settings', //url slug
			'xpo_admin_page_content', //callback funtion to display plugin page
			'dashicons-admin-generic',// menu icon
			null// menu priority
		 );		 
}

add_action('admin_menu', 'xpo_setup_menu');