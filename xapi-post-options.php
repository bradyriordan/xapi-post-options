<?php
/*
Plugin Name:       xAPI Post Options
Description:       This plugin allows you to send xAPI statement to an LRS from a post. 
Plugin URI:        https://
Contributors:      Brady Riordan
Author:            Brady Riordan
Author URI:        https://bradyriordan.com
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

require_once plugin_dir_path( __FILE__ ) . 'includes/xapi-verbs.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/domEvents.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/settings-callback.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/admin-menu.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/settings-page.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/settings-register.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/settings-callback.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/settings-validate.php';
wp_enqueue_style( 'chosen', plugin_dir_url( __FILE__ ) . '/includes/css/chosen.min.css', array(), null, 'screen' );
wp_enqueue_script( 'jquery', plugin_dir_url( __FILE__ ) . '/includes/js/jquery-3.2.1.min.js', array(), null, true );
wp_enqueue_script( 'chosen', plugin_dir_url( __FILE__ ) . '/includes/js/chosen.jquery.min.js', array(), null, true );
wp_enqueue_script( 'chosen-pronto', plugin_dir_url( __FILE__ ) . '/includes/js/chosen.proto.min.js', array(), null, true );
wp_enqueue_script( 'xpo-chosen', plugin_dir_url( __FILE__ ) . '/includes/js/xpo-chosen.js', array(), null, true );

}

// if( current_user_can('edit_pages') ){
	// wp_enqueue_style( 'xpo-settings', plugin_dir_url ( dirname( __FILE__ ) ) . 'includes/css/chosen.min.css', array(), null, 'screen' );
// }

// include dependencies - admin and public

// default plugin options
function xpo_options_default() {

	return array(
		'system_send_statements'     => false,
		'track_not_logged_in'   => false,
		'system_actor'   => 'Email',		
	);

}


	


// add metabox to post pages
function xpo_custom_fields() {

	// get all post types
	$postTypes = get_post_types(); 

	// get xpo options
	$xpoOptions = get_option( 'xpo_options' );
	
	foreach($postTypes as $type){
		
	// if add metabox is set to yes for post type, add a metabox to that post type
	if (array_key_exists('xpo_add_metabox_' . $type, $xpoOptions)){
		
		add_meta_box(
			'xapi-post-options',          // this is HTML id of the box on edit screen
			'xAPI Post Options',    // title of the box
			'xpo_box_content',   // function to be called to display the checkboxes, see the function below
			$type,        // on which edit screen the box should appear
			'normal',      // part of page where the box should appear
			'default'      // priority of the box
		);
	}	

}}
	
add_action( 'add_meta_boxes', 'xpo_custom_fields' );

// display the metabox
function xpo_box_content() {   

?>
    <input type="checkbox" name="xapi_post_options_send_statements" value="<?php echo esc_attr( get_post_meta($post->ID, 'xapi_post_options_send_statements', true) ); ?>" /> Send xAPI statements from this post <br /><br />
    <label for="dom-events">DOM Events</label><br />
	<select name="dom-events" class="chosen-select">
		<?php 			
			foreach($GLOBALS['domEvents'] as $option){ ?>
			
						<option value="<?php echo strtolower($option); ?>"><?php echo $option; ?></option>					
				
			<?php } ?>
	</select> <br /><br />
		
		<label for="xapi-verbs">xAPI Verbs</label><br />
		<select name="xapi-verbs" class="chosen-select">
			
			<?php 			
			foreach($GLOBALS['xapiVerbs'] as $item){ ?>
				<option value="<?php echo strtolower(str_replace("-", " ", $item)); ?>"><?php echo $item; ?></option>
			<?php } ?>
		</select>
<?php } 

// save data 
function xpo_field_data($post_id) {  

       // now store data in custom fields based on checkboxes selected
    if ( isset( $_POST['xapi_post_options_send_statements'] ) ){
        update_post_meta( $post_id, 'xapi_post_options_send_statements', 1 );
    } else {
        update_post_meta( $post_id, 'xapi_post_options_send_statements', 0 );    
	}
}
add_action( 'save_post', 'xpo_field_data' );






