<?php
/*
Plugin Name:       xAPI Post Options
Description:       This plugin facilitates sending xAPI statements to an LRS from a post in Wordpress. 
Plugin URI:        https://
Contributors:      Team-Wordpress-Plugin Spring 2019, Brady Riordan, Bob Robinson, Helena Smith, Eric Brott, Amy Parent
Author:            Team-Wordpress-Plugin Spring 2019
Author URI:        https://url.com
Tags:              xAPI, eLearning, LRS
Version:           1.0
Stable tag:        1.0
Requires at least: 4.5
Tested up to:      4.8
Text Domain:       xapi-post-options
Domain Path:       /languages
License:           GPL v2 or later
License URI:       https://www.gnu.org/licenses/gpl-2.0.txt

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 
2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
with this program. If not, visit: https://www.gnu.org/licenses/
*/

// register the meta box

// exit if file is called directly
if ( !defined ( 'ABSPATH' ) ) {
	
	exit;
	
}

// include admin dependencies
if( is_admin() ) {

require_once plugin_dir_path( __FILE__ ) . 'includes/metabox/xapi-verbs.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/metabox/dom-events.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/metabox/metabox.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/settings-callback.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/admin-menu.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/settings-page.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/settings-register.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/settings-callback.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/settings-validate.php';wp_enqueue_style( 'style', plugin_dir_url( __FILE__ ) . '/includes/css/style.css', array(), null, 'screen' );
wp_enqueue_style( 'chosen', plugin_dir_url( __FILE__ ) . '/includes/css/chosen.min.css', array(), null, 'screen' );
wp_enqueue_script( 'jquery', plugin_dir_url( __FILE__ ) . '/includes/js/jquery-3.2.1.min.js', array(), null, true );
wp_enqueue_script( 'chosen', plugin_dir_url( __FILE__ ) . '/includes/js/chosen.jquery.min.js', array(), null, true );
wp_enqueue_script( 'chosen-pronto', plugin_dir_url( __FILE__ ) . '/includes/js/chosen.proto.min.js', array(), null, true );
wp_enqueue_script( 'xpo-chosen', plugin_dir_url( __FILE__ ) . '/includes/js/xpo-chosen.js', array(), null, true );

}

// include dependencies - admin and public
require_once plugin_dir_path( __FILE__ ) . 'includes/TinCanPHP-master/autoload.php';

// default plugin options
function xpo_options_default() {

	return array(
		'system_send_statements'     => false,
		'track_not_logged_in'   => false,
		'system_actor'   => 'mailto',		
	);

}


/******************************************

------------ LRS CREDENTIALS -------------

*******************************************/



// JS for LRS Connection
// On change, set LRS Connection to 0

add_action( 'wp_ajax_xpo_change_lrs_connection', 'xpo_change_lrs_connection' );

function xpo_change_lrs_connection(){
	// Get most up to date options
	$xpo_options = xpo_get_xpo_options();
	// If the option has been set, set it to 0
	if (array_key_exists("lrs_connection", $xpo_options)){
		$xpo_options['lrs_connection'] = 0;
	}
	// If new endpoint is different than what's stored in db, save it to the db and change lrs_connection to 0
	if ($xpo_options['lrs_endpoint'] != $_POST['lrs_creds'][0]){
		$xpo_options['lrs_endpoint'] = $_POST['lrs_creds'][0];
		$xpo_options['lrs_connection'] = 0;
	}
	// If new username is different than what's stored in db, save it to the db and change lrs_connection to 0
	if ($xpo_options['lrs_username'] != $_POST['lrs_creds'][1]){
		$xpo_options['lrs_username'] = $_POST['lrs_creds'][1];
		$xpo_options['lrs_connection'] = 0;
	}
	// If new password is different than what's stored in db, save it to the db and change lrs_connection to 0
	if ($xpo_options['lrs_password'] != $_POST['lrs_creds'][2]){
		$xpo_options['lrs_password'] = $_POST['lrs_creds'][2];
		$xpo_options['lrs_connection'] = 0;
	}
	
	
	// Save options
	update_option( 'xpo_options', $xpo_options );
	print "Test LRS Connection to make sure your credentials are correct!\n";
	wp_die();
}


// Admin footer JS
add_action( 'admin_footer', 'xpo_admin_js' );

function xpo_admin_js() { ?>
	<script type="text/javascript" >
	jQuery("#xpo_options_lrs_connection").click(function($) {

		var data = {
			'action': 'xpo_send_test_statement'			
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function(response) {
			// if statement sent successfully checkmark persists
			if (response == "Statement sent successfully!\n" ){
				document.getElementById("xpo_options_lrs_connection").checked = true;				
			// if statement is unsuccessful, checkmark disappears 
			} else {
				document.getElementById("xpo_options_lrs_connection").checked = false;
				
			}
			console.log('xAPI Post Options Plugin: ' + response);
			xpo_display_lrs_response(response); // put it on the screen, too --BobRob
		});
	});	
	
	jQuery("#xpo_options_lrs_endpoint, #xpo_options_lrs_username, #xpo_options_lrs_password").on('keyup',function($) {
		// If any LRS input fields change, fire change lrs function
		var lrs_endpoint = document.getElementById("xpo_options_lrs_endpoint").value;
		var lrs_username = document.getElementById("xpo_options_lrs_username").value;
		var lrs_password = document.getElementById("xpo_options_lrs_password").value;
		var data = {		
			'action': 'xpo_change_lrs_connection',
			'lrs_creds': [lrs_endpoint, lrs_username, lrs_password]			
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function(response) {
			document.getElementById("xpo_options_lrs_connection").checked = false;
			console.log('xAPI Post Options Plugin: ' + response);
			xpo_display_lrs_response(response); // put it on the screen, too --BobRob
		});
	});
	
	function xpo_display_lrs_response(response){
		// Display a message after the test lrs function is sent 
		if (response == "Statement sent successfully!\n" ){							
			console.log("success!");
			jQuery( "#lastlrstest" ).empty();
			jQuery( "#lastlrstest" ).append( "<p>Success! Your LRS credentials are correct.</p>" );
		} else {			
			console.log("not success!");
			jQuery( "#lastlrstest" ).empty();
			jQuery( "#lastlrstest" ).append( response );
		}
	}
	
	</script> <?php
}	

//Test statement
add_action( 'wp_ajax_xpo_send_test_statement', 'xpo_send_test_statement' );

			
function xpo_send_test_statement(){		
	$xpo_options = xpo_get_xpo_options();	 	  
	   
	
	$lrs = new TinCan\RemoteLRS(
		$xpo_options['lrs_endpoint'],			
		// xAPI version
		'1.0.1',
		$xpo_options['lrs_username'],
		$xpo_options['lrs_password']
	);

	$actor = new TinCan\Agent(
		[ 'mbox' => 'mailto:xpoptions@wordpress.com']
	);
	$verb = new TinCan\Verb(
		[ 'id' => 'http://activitystrea.ms/schema/1.0/confirm' ]
	);
	$activity = new TinCan\Activity(
		[ 'id' => 'http://xpo.com/test-connection' ]
	);
	$statement = new TinCan\Statement(
		[
			'actor' => $actor,
			'verb'  => $verb,
			'object' => $activity,
		]
	);

	$response = $lrs->saveStatement($statement);
	if ($response->success) {
		print "Statement sent successfully!\n";
		// Get most up to date options
		$xpo_options = xpo_get_xpo_options();
		// If the option has already been added set it to 1
		if (array_key_exists("lrs_connection", $xpo_options)){
		  $xpo_options['lrs_connection'] = 1;
		// If the option hasn't been added, add it, and set it to 1
		} else {
		  $xpo_options += array('lrs_connection' => 1);
		}			
		// Save options
		update_option( 'xpo_options', $xpo_options );
	} else {
		print "Error statement not sent: " . $response->content . "\n";
	}
	  
	wp_die();
}


/******************************************

------------ HELPER FUNCTIONS -------------

*******************************************/


	
// get most up to date options
function xpo_get_xpo_options(){
	if (get_option('xpo_options') != NULL) {
		$xpo_options = get_option('xpo_options');
		return $xpo_options;
	} else {
		return false;
	}
}
	
// check if send statement is set on the current post
function xpo_send_statement_post($post_id){
	if (get_post_meta( $post_id, $key = 'xpo_send_statements', $single = true ) == 1) {
		return true;
	} else {
		return false;
	}
}	
	
// check if send statements is set in the plugin settings at the site level
function xpo_send_statements_site(){
	$xpo_options = xpo_get_xpo_options();
	if ($xpo_options['system_send_statements'] == true) {
		return true;
	} else {
		return false;
	}
}
	
// check if lrs connection is active
function xpo_lrs_connection(){
	$xpo_options = xpo_get_xpo_options();
	if ($xpo_options['lrs_connection'] == 1) {
		return true;
	} else {
		return false;
	}
}
	
		
// check page trigger type
function xpo_statement_trigger($post_id){
	if (get_post_meta( $post_id, $key = 'xpo_trigger-type', $single = true ) == 0) {
		return 'page-view';
	} else {
		return 'page-interaction';
	}
}
	
// check that mandatory fields have been filled in the current post
function xpo_mandatory_fields_post($post_id){
	// Add keys of all mandatory fields except actor - that is set at the site level
	$mandatory_fields = array('xpo_trigger-type', 'xpo_element-id', 'xpo_dom-event', 'xpo_verb-id', 'xpo_object-id');
	// Variable for final return value
	$result = NULL;
	// Iterate over each value of array
	foreach ($mandatory_fields as $field){			
		// If the value is either not set or blank set result to false array and stop the loop
		$meta = get_post_meta( $post_id, $key = $field, $single = true );
		if ( $meta == '' || $meta == NULL ){
			$result = false;
			break;
		} else {
			$result = true;
		}			
	}
	return $result;
}
	
//function for checking if user is a guest and track guests is off	
function xpo_guest_tracking(){
	$xpo_options = xpo_get_xpo_options();
	if( !is_user_logged_in() AND $xpo_options['track_not_logged_in'] == 1 ){
		return true;
	} else if ( is_user_logged_in() ) {
		return true;
	} else {
		return false;
	}		
}
	
// function to return actor
function xpo_get_actor($current_user){
	$xpo_options = xpo_get_xpo_options();
	$system_actor = $xpo_options['system_actor'];
	$return_actor = '';
	switch ($system_actor){
		case "not-set":
			$return_actor = [ 'mbox' => 'mailto:' . $current_user -> user_email ];
			break;
		case "mbox":
			$return_actor = [ 'mbox' => 'mailto:' . $current_user -> user_email ];
			break;
		case "mbox_sha1sum":
			$hex_return_actor_string = 'mailto:' . $current_user -> user_email;
			$hex_return_actor = sha1( $hex_return_actor_string );
			$return_actor = [ 'mbox_sha1sum' => $hex_return_actor ];
			break;
		//case "account":
			// Need to look at this, not accurate
			// $return_actor = [ 'account' => ['homePage' => get_site_url(), 'name' => $current_user -> user_login ] ];
			// break;
		//case "openid":
			// Need to look at this, not accurate
			// $return_actor = [ 'openid' => ['name' => $current_user -> display_name, 'openid' => $current_user -> user_openid ] ];
			// break;
		default:
			$return_actor = [ 'mbox' => 'mailto:' . $current_user -> user_email ];
			break;
	}
	if($current_user AND $current_user->display_name) {
		$return_actor['name'] = $current_user->display_name;
	}
	return $return_actor;
}

// function to return verb
function xpo_get_verb($post_id){		
	$verb_id = get_post_meta($post_id, $key = 'xpo_verb-id', $single = true);
	$verb_display = get_post_meta($post_id, $key = 'xpo_verb-display', $single = true);		
	$return_verb = [ 'id' => $verb_id, 'display' => ['en-US' => $verb_display ] ];
	return $return_verb;
}

// function to return activity
function xpo_get_activity($post_id) {
	$activity_id = get_post_meta($post_id, $key = 'xpo_object-id', $single = true);
	$activity_description = get_post_meta($post_id, $key = 'xpo_object-description', $single = true);
	$activity_type = get_post_meta($post_id, $key = 'xpo_object-type', $single = true);
	$activity_name = get_post_meta($post_id, $key = 'xpo_object-name', $single = true);
	$activity_extensions = get_post_meta($post_id, $key = 'xpo_object-extensions', $single = true);		
	$return_activity = new TinCan\Activity();
	$return_activity->setId($activity_id);
	$return_activity->setDefinition([]);
	$return_activity->getDefinition()->getName()->set('en-US', $activity_name);
	$return_activity->getDefinition()->getDescription()->set('en-US', $activity_description);		
	return $return_activity;
}
	
	
//function to return context	
//function to return result
//function to return stored
//function to return authority
//function to return version



/******************************************

------------ SEND STATEMENT -------------

*******************************************/

	

// Add ajax url for public pages
add_action('wp_head', 'xpo_ajaxurl');

function xpo_ajaxurl() {

   echo '<script type="text/javascript">
           var ajaxurl = "' . admin_url('admin-ajax.php') . '";
         </script>';
		 
}


// Ajax action for admin
add_action( 'wp_ajax_xpo_send_statement', 'xpo_send_statement' );
		
// Ajax action for public
add_action( 'wp_ajax_nopriv_xpo_send_statement', 'xpo_send_statement' );

// real xAPI statement
function xpo_send_statement(){
	$current_user_id = get_current_user_id();
	$current_user = get_user_by('id', $current_user_id);
	$xpo_options = xpo_get_xpo_options();	
	$post_id = $_POST['post_id'];
	
	
	$lrs = new TinCan\RemoteLRS(
		$xpo_options['lrs_endpoint'],
		'1.0.1',
		$xpo_options['lrs_username'],
		$xpo_options['lrs_password']
	);

	$actor = new TinCan\Agent(
		xpo_get_actor($current_user)
	);
	$verb = new TinCan\Verb(
		xpo_get_verb($post_id)
	);
	$activity = xpo_get_activity($post_id);
		
	$statement = new TinCan\Statement(
		[
			'actor' => $actor,
			'verb'  => $verb,
			'object' => $activity,
		]
	);

	$response = $lrs->saveStatement($statement);
	if ($response->success) {
		print "Statement sent successfully!\n";			
	}
	else {
		print "Error statement not sent: " . $response->content . "\n";
	}
	
	wp_die();
}

// Add event listeners on public pages
add_action( 'wp_footer', 'xpo_add_event_listeners');

function xpo_add_event_listeners() {
	
	$post_id = get_the_ID();
	$element_id = get_post_meta( $post_id, $key = 'xpo_element-id', $single = true );
	$statement_trigger = xpo_statement_trigger($post_id);
	$dom_event = get_post_meta( $post_id, $key = 'xpo_dom-event', $single = true );	 
	
	if( xpo_send_statements_site() AND xpo_send_statement_post($post_id) AND xpo_lrs_connection() AND xpo_mandatory_fields_post($post_id) AND xpo_guest_tracking() ) {				
		
		?>
		<script type="text/javascript">
		  
			function send_real_xapi_statement(){	
			  
				var data = {
					'action': 'xpo_send_statement',
					'post_id': '<?php echo $post_id; ?>'
				};
				
				jQuery.post(ajaxurl, data, function(response) {				
					if (response == "Statement sent successfully!\n" ){
						alert(response);
					} else {						
						alert(response);
					}
					console.log('xAPI Post Options Plugin: ' + response);					
				});
			}		  
			  
		</script>
		<?php
		if ($statement_trigger == 'page-view'){
			?>
			<script type="text/javascript">			  
				window.addEventListener('load', send_real_xapi_statement() );		  
			</script><?php
		} else if ($statement_trigger == 'page-interaction') {
		
		?>		
		<script type="text/javascript">							
			document.getElementById("<?php echo $element_id ?>").addEventListener("<?php echo $dom_event ?>", function(){send_real_xapi_statement()} );
		</script>
		<?php
		}
	}
}	
	
