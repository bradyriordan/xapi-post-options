<?php //xAPI Post Options - Settings Callbacks


// exit if file is called directly
if ( !defined ( 'ABSPATH' ) ) {
	
	exit;
	
}



// callback: login section
function xpo_callback_section_lrs() {
	
	echo '<p>These settings enable you to customize the LRS Details.</p>';
	
}

// callback: login section
function xpo_callback_section_general() {
	
	echo '<p>These settings apply at the site level.</p>';
	
}

// callback: login section
function xpo_callback_section_metabox() {
	
	echo '<p>Select the post types you would like to send xAPI statements from.</p>';
	
}

// callback: text field
function xpo_callback_field_text( $args ) {

	$options = get_option( 'xpo_options', xpo_options_default() );
	
	$id    = isset( $args['id'] )    ? $args['id']    : '';
	$label = isset( $args['label'] ) ? $args['label'] : '';
	
	$value = isset( $options[$id] ) ? sanitize_text_field( $options[$id] ) : '';
	
	echo '<input id="xpo_options_'. $id .'" name="xpo_options['. $id .']" type="text" size="40" value="'. $value .'"><br />';
	echo '<label for="xpo_options_'. $id .'">'. $label .'</label>';

}

// callback: password
function xpo_callback_field_password( $args ) {

	$options = get_option( 'xpo_options', xpo_options_default() );
	
	$id    = isset( $args['id'] )    ? $args['id']    : '';
	$label = isset( $args['label'] ) ? $args['label'] : '';
	
	$value = isset( $options[$id] ) ? sanitize_text_field( $options[$id] ) : '';
	
	echo '<input id="xpo_options_'. $id .'" name="xpo_options['. $id .']" type="password" size="40" value="'. $value .'"><br />';
	echo '<label for="xpo_options_'. $id .'">'. $label .'</label>';

}



// callback: radio field
function xpo_callback_field_radio( $args ) {

$options = get_option( 'xpo_options', xpo_options_default() );
	
	$id    = isset( $args['id'] )    ? $args['id']    : '';
	$label = isset( $args['label'] ) ? $args['label'] : '';
	
	$selected_option = isset( $options[$id] ) ? sanitize_text_field( $options[$id] ) : '';
	
	$radio_options = array(
		
		'enable'  => 'Enable custom styles',
		'disable' => 'Disable custom styles'
		
	);
	
	foreach ( $radio_options as $value => $label ) {
		
		$checked = checked( $selected_option === $value, true, false );
		
		echo '<label><input name="xpo_options['. $id .']" type="radio" value="'. $value .'"'. $checked .'> ';
		echo '<span>'. $label .'</span></label><br />';
		
	}

}



// callback: textarea field
function xpo_callback_field_textarea( $args ) {

	$options = get_option( 'xpo_options', xpo_options_default() );
	
	$id    = isset( $args['id'] )    ? $args['id']    : '';
	$label = isset( $args['label'] ) ? $args['label'] : '';
	
	$allowed_tags = wp_kses_allowed_html( 'post' );
	
	$value = isset( $options[$id] ) ? wp_kses( stripslashes_deep( $options[$id] ), $allowed_tags ) : '';
	
	echo '<textarea id="xpo_options_'. $id .'" name="xpo_options['. $id .']" rows="5" cols="50">'. $value .'</textarea><br />';
	echo '<label for="xpo_options_'. $id .'">'. $label .'</label>';

}

function xpo_callback_field_button( $args ) {

	$options = get_option( 'xpo_options', xpo_options_default() );
	
	$id    = isset( $args['id'] )    ? $args['id']    : '';
	$label = isset( $args['label'] ) ? $args['label'] : '';
	
	$allowed_tags = wp_kses_allowed_html( 'post' );
	
	$value = isset( $options[$id] ) ? wp_kses( stripslashes_deep( $options[$id] ), $allowed_tags ) : '';
	
	echo '<input id="xpo_options_'. $id .'" name="xpo_options['. $id .']" type="button" value="' . $label . '"> ';
	echo '<label for="xpo_options_'. $id .'">'. $label .'</label>';

}


// callback: checkbox field
function xpo_callback_field_checkbox( $args ) {

	$options = get_option( 'xpo_options', xpo_options_default() );
	
	$id    = isset( $args['id'] )    ? $args['id']    : '';
	$label = isset( $args['label'] ) ? $args['label'] : '';
	
	$checked = isset( $options[$id] ) ? checked( $options[$id], 1, false ) : '';
	
	echo '<input id="xpo_options_'. $id .'" name="xpo_options['. $id .']" type="checkbox" value="1"'. $checked .'> ';
	echo '<label for="xpo_options_'. $id .'">'. $label .'</label>';

}


// callback: select field
function xpo_callback_field_select( $args ) {

	$options = get_option( 'xpo_options', xpo_options_default() );
	
	$id    = isset( $args['id'] )    ? $args['id']    : '';
	$label = isset( $args['label'] ) ? $args['label'] : '';
	
	$selected_option = isset( $options[$id] ) ? sanitize_text_field( $options[$id] ) : '';
	
	// select field values
	$select_options_actor = array(
		
		'not-set'   => 'not set',
		'mbox'     => 'mbox',
		'mbox_sha1sum'   => 'mbox_sha1sum',
		'account'      => 'account',
		'openid'    => 'openid',	
		
	);
	
	$select_options_other = array(
		
		'not-set'   => 'Not set',
		'user-id'     => 'User id',
		'username'      => 'Username',
		'email'    => 'Email',
		'anonymous' => 'Anonymous',
		
	);
	
	if($id === 'system_actor'){
		
		$array = $select_options_actor;
		
	} elseif ($id === 'system_actor'){
		
		$array = $select_options_actor;
		
	} else {
		$array = null;
	}
	
	
	echo '<select id="xpo_options_'. $id .'" name="xpo_options['. $id .']">';
	
	foreach ( $array as $value => $option ) {
		
		$selected = selected( $selected_option === $value, true, false );
		
		echo '<option value="'. $value .'"'. $selected .'>'. $option .'</option>';
		
	}
	
	echo '</select> <label for="xpo_options_'. $id .'">'. $label .'</label>';

}


