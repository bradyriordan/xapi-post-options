<?php //xAPI Post Options - Metabox settings

add_action('add_meta_boxes', 'xpo_metabox_fields');

// add metabox to post pages
function xpo_metabox_fields()
{
	// get all post types and store them in an array
	$postTypes = get_post_types();
	// get xpo options
	$xpoOptions = xpo_get_xpo_options();
	foreach ($postTypes as $type) {
		// if add metabox is set to yes for post type, add a metabox to that post type
		if (array_key_exists('xpo_add_metabox_' . $type, $xpoOptions)) {
			add_meta_box(
				'xapi-post-options',          // HTML id of the box on edit screen
				'xAPI Post Options',    // title of the box
				'xpo_metabox_content',   // function to be called to display the checkboxes, see the function below
				$type,        // Post type to add metabox
				'normal',      // part of page where the box should appear
				'default'      // priority of the box
			);
		}
	}
}


// display the metabox
function xpo_metabox_content()
{
	wp_nonce_field('xpo_save_metabox_nonce', 'xpo_metabox');
	?>
	<input type="checkbox" name="xpo_send_statements" <?php echo (get_post_meta(get_the_ID(), $key = 'xpo_send_statements', $single = true)) ? "checked" : ""; ?> /> Send xAPI statements from this post <br><br>
	<!-- Statement Trigger  -->
	<p style="font-weight:bold;">Statement Trigger</p><br>
	<div class="xpo-float-left xpo-form-container">

		<input type="radio" name="xpo_trigger-type" value="0" <?php echo (get_post_meta(get_the_ID(), $key = 'xpo_trigger-type', $single = true) == 0) ? "checked" : ""; ?>> Page view<br>
		<input type="radio" name="xpo_trigger-type" value="1" <?php echo (get_post_meta(get_the_ID(), $key = 'xpo_trigger-type', $single = true) == 1) ? "checked" : ""; ?>> Page interaction<br><br> </div>

	<div class="xpo-float-left xpo-form-container">
		<label for="element-id">Element ID</label><br>
		<input type="text" name="xpo_element-id" placeholder="Element ID" class="xpo-form-element" value="<?php echo (get_post_meta(get_the_ID(), $key = 'xpo_element-id', $single = true)) ? get_post_meta(get_the_ID(), $key = 'xpo_element-id', $single = true) : ""; ?>"><br>
		<label for="dom-events">DOM Event</label><br>
		<select name="xpo_dom-event" class="chosen-select">
			<?php
			foreach ($GLOBALS['domEvents'] as $option) { ?>
				<option value="<?php echo strtolower($option); ?>" <?php echo (get_post_meta(get_the_ID(), $key = 'xpo_dom-event', $single = true) == strtolower($option)) ? 'selected="selected"' : ""; ?>><?php echo $option; ?></option>
			<?php } ?>
		</select> <br> </div>
	<div class="clear"></div>
	<!--  Actor  -->
	<p style="font-weight:bold;">Actor</p>
	<p>See <a href=" <?php echo admin_url('admin.php?page=xpo-settings') ?>" target="_blank">plugin settings</a></p>
	<!-- Verb  -->
	<p style="font-weight:bold;">Verb</p>
	<div class="xpo-float-left xpo-form-container">
		<label for="xapi-verbs">xAPI Verbs</label><br>
		<select id="xpo_xapi-verbs" name="xpo_xapi-verbs" class="chosen-select">
			<?php
			foreach ($GLOBALS['xapiVerbs'] as $verb => $uri) { ?>
				<option data-verb-uri="<?php echo $uri ?>" value="<?php echo strtolower(str_replace("-", " ", $verb)); ?>" <?php echo (get_post_meta(get_the_ID(), $key = 'xpo_xapi-verbs', $single = true) == strtolower($verb)) ? 'selected="selected"' : ""; ?>><?php echo $verb; ?></option>
			<?php } ?>
		</select> </div>
	<div class="xpo-float-left xpo-form-container">
		<label for="xpo_verb-id">Verb ID</label><br>
		<input type="text" id="xpo_verb-id" name="xpo_verb-id" placeholder="Verb ID" class="xpo-form-element" value="<?php echo (get_post_meta(get_the_ID(), $key = 'xpo_verb-id', $single = true)) ? get_post_meta(get_the_ID(), $key = 'xpo_verb-id', $single = true) : ""; ?>"><br>
		<label for="xpo_verb-display">Verb display</label><br>
		<input type="text" id="xpo_verb-display" name="xpo_verb-display" placeholder="Verb display" class="xpo-form-element" value="<?php echo (get_post_meta(get_the_ID(), $key = 'xpo_verb-display', $single = true)) ? get_post_meta(get_the_ID(), $key = 'xpo_verb-display', $single = true) : ""; ?>"><br><br>
	</div>
	<div class="clear"></div> <!-- Object -->
	<p style="font-weight:bold;">Object</p>
	<div class="xpo-form-container"> <label for="object-id">Object ID</label><br>
		<input type="text" name="xpo_object-id" placeholder="Object ID" class="xpo-form-element" value="<?php echo (get_post_meta(get_the_ID(), $key = 'xpo_object-id', $single = true)) ? get_post_meta(get_the_ID(), $key = 'xpo_object-id', $single = true) : ""; ?>"><br><br> </div>
	<div class="xpo-form-container"> <label for="object-description">Object description</label><br>
		<input type="text" name="xpo_object-description" placeholder="Object description" class="xpo-form-element" value="<?php echo (get_post_meta(get_the_ID(), $key = 'xpo_object-description', $single = true)) ? get_post_meta(get_the_ID(), $key = 'xpo_object-description', $single = true) : ""; ?>"><br><br>
	</div>
	<div class="xpo-form-container"> <label for="object-type">Object type</label><br>
		<input type="text" name="xpo_object-type" placeholder="Object type" class="xpo-form-element" value="<?php echo (get_post_meta(get_the_ID(), $key = 'xpo_object-type', $single = true)) ? get_post_meta(get_the_ID(), $key = 'xpo_object-type', $single = true) : ""; ?>"><br><br>
	</div>
	<div class="xpo-form-container"> <label for="object-name">Object name</label><br>
		<input type="text" name="xpo_object-name" placeholder="Object name" class="xpo-form-element" value="<?php echo (get_post_meta(get_the_ID(), $key = 'xpo_object-name', $single = true)) ? get_post_meta(get_the_ID(), $key = 'xpo_object-name', $single = true) : ""; ?>"><br><br>
	</div>
	<div class="xpo-form-container"> <label for="object-extensions">Object extensions</label><br>

		<textarea rows="10" cols="50" name="xpo_object-extensions" placeholder="Object extensions" class="xpo-form-element"><?php echo (get_post_meta(get_the_ID(), $key = 'xpo_object-extensions', $single = true)) ? get_post_meta(get_the_ID(), $key = 'xpo_object-extensions', $single = true) : ""; ?></textarea><br><br>
	</div>
	<!-- Result -->
	<p style="font-weight:bold;">Other properties</p>
	<div class="xpo-form-container">
		<label for="xpo_context">Context</label><br>
		<textarea rows="10" cols="50" name="xpo_context" placeholder="Context" class="xpo-form-element"><?php echo (get_post_meta(get_the_ID(), $key = 'xpo_context', $single = true)) ? get_post_meta(get_the_ID(), $key = 'xpo_context', $single = true) : ""; ?></textarea><br><br>
	</div>
	<div class="xpo-form-container">
		<label for="xpo_result">Result</label><br>
		<textarea rows="10" cols="50" name="xpo_result" placeholder="Result" class="xpo-form-element"><?php echo (get_post_meta(get_the_ID(), $key = 'xpo_result', $single = true)) ? get_post_meta(get_the_ID(), $key = 'xpo_result', $single = true) : ""; ?></textarea><br><br>
	</div> <!-- Stored -->
	<div class="xpo-form-container">
		<label for="xpo_stored">Stored</label><br>
		<textarea rows="10" cols="50" name="xpo_stored" placeholder="Stored" class="xpo-form-element"><?php echo (get_post_meta(get_the_ID(), $key = 'xpo_stored', $single = true)) ? get_post_meta(get_the_ID(), $key = 'xpo_stored', $single = true) : ""; ?></textarea><br><br>
	</div> <!-- Authority -->
	<div class="xpo-form-container">
		<label for="xpo_authority">Authority</label><br>
		<textarea rows="10" cols="50" name="xpo_authority" placeholder="Authority" class="xpo-form-element"><?php echo (get_post_meta(get_the_ID(), $key = 'xpo_authority', $single = true)) ? get_post_meta(get_the_ID(), $key = 'xpo_authority', $single = true) : ""; ?></textarea><br><br>
	</div> <!-- Version -->
	<div class="xpo-form-container">
		<label for="xpo_version">Version</label><br>
		<textarea rows="10" cols="50" name="xpo_version" placeholder="Version" class="xpo-form-element"><?php echo (get_post_meta(get_the_ID(), $key = 'xpo_version', $single = true)) ? get_post_meta(get_the_ID(), $key = 'xpo_version', $single = true) : ""; ?></textarea><br><br>
	</div> <!-- Attachements -->
	<div class="xpo-form-container">
		<label for="xpo_attachements">Attachements</label><br>
		<textarea rows="10" cols="50" name="xpo_attachements" placeholder="Attachements" class="xpo-form-element"><?php echo (get_post_meta(get_the_ID(), $key = 'xpo_attachements', $single = true)) ? get_post_meta(get_the_ID(), $key = 'xpo_attachements', $single = true) : ""; ?></textarea><br><br>
	</div>

<?
	// save post
}

// Populate verb-display and verb-id inputs from verb dropdown 
add_action('admin_footer', 'xpo_populate_verb_inputs');

function xpo_populate_verb_inputs()
{
	?>
	<script type="text/javascript">
		// When the verb dropdown changes, add the verb id and verb display to inputs 
		$(document).on('change', '#xpo_xapi-verbs', function(evt, params) {
			uri = $(this).find(':selected').data('verb-uri');
			selected = $(this).find(':selected').val();
			if (selected && selected == 'custom verb') {
				$('#xpo_verb-id').val("");
				$('#xpo_verb-display').val("");
			} else {
				if (uri) {
					$('#xpo_verb-id').val(uri);
				}
				if (params && params.selected) {
					$('#xpo_verb-display').val(params.selected);
				}
			}
		});
	</script>
<?php
}


function xpo_save_metabox($post_id)
{
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

	// check noonce      
	if (!isset($_POST['xpo_metabox']) || !wp_verify_nonce($_POST['xpo_metabox'], 'xpo_save_metabox_nonce')) {
		print 'Sorry, your nonce did not verify.';
		exit;
	} else {

		// Checkboxes
		// Add all checkbox field names to this array to save
		$xpo_checkboxes = ['xpo_send_statements'];
		foreach ($xpo_checkboxes as $checkbox) {
			$checkbox_value = $_POST[$checkbox] ? true : false;
			update_post_meta($post_id, 'xpo_send_statements', $checkbox_value);
		}

		// Radios
		// Add all radio field names to this array to save
		$xpo_radios = ['xpo_trigger-type'];
		foreach ($xpo_radios as $radio) {
			if (isset($_POST[$radio])) {
				if ($_POST[$radio] == 0) {
					update_post_meta($post_id, $radio, 0);
				} else if ($_POST[$radio] == 1) {
					update_post_meta($post_id, $radio, 1);
				}
			}
		}

		// Text inputs
		// Add all text input field names to this array to save
		$xpo_text = [
			'xpo_element-id',
			'xpo_dom-event',
			'xpo_xapi-verbs',
			'xpo_verb-id',
			'xpo_verb-display',
			'xpo_object-id',
			'xpo_object-description',
			'xpo_object-type',
			'xpo_object-name',
			'xpo_object-extensions',
			'xpo_context',
			'xpo_result',
			'xpo_stored',
			'xpo_authority',
			'xpo_version',
			'xpo_attachements'
		];

		foreach ($xpo_text as $text) {
			$checkbox_value = $_POST[$checkbox] ? true : false;
			if (isset($_POST[$text])) {
				update_post_meta($post_id, $text, sanitize_text_field($_POST[$text]));
			}
		}
	}
}

add_action('post_updated', 'xpo_save_metabox');
