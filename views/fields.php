<?php 
	global $blog_id;
	$global_settings = get_site_option( 'Profile_CCT_global_settings', array() );
	$profile = Profile_CCT::get_object();
	
	//$profile->settings['clone_fields'] = array();
	
	// Add Field
	if ( ! empty($_POST) && check_admin_referer( 'add_profile_field', 'add_profile_fields_field' ) ):
		// Creating a new field.
		$field_label = trim( strip_tags($_POST['label']) );
		$field_clone = trim( strip_tags($_POST['field_clone']) );
		$field_description = trim( strip_tags($_POST['description']) );
		
		// Validate the form input.
		$error = array();
		if ( empty($field_label) ) $error['label'] = "Please fill out the field name.";
		if ( empty($field_clone) ) $error['field_clone'] = "Please select a field to duplicate.";
		if ( empty($field_description) ) $error['description'] = "Please enter a description for the field.";
		
		if ( empty($error) ):
			$field_type = "clone_".strtolower(preg_replace('/[^A-Za-z0-9]+/', '_', $field_label));
			$field = array(
				'type' => $field_type,
				'label' => $field_label,
				'field_clone' => $field_clone,
				'description' => $field_description,
				'blogs' => array(),
			);
			$field['blogs'][$blog_id] = true;
			$global_settings['clone_fields'][] = $field;
			unset($field['blogs']);
			$profile->settings['clone_fields'][$field['type']] = $field;
			
			update_option( 'Profile_CCT_settings', $profile->settings );
			update_site_option( 'Profile_CCT_global_settings', $global_settings );
			
			// Unset these fields in order to empty the form on this page.
			unset($field_label);
			unset($field_clone);
			unset($field_description);
		endif;
	else:
		if ( wp_verify_nonce($_GET['_wpnonce'], 'profile_cct_toggle_field') ):
			$field_index = $_GET['field'];
			$field_action = $_GET['action'];
			$field = $global_settings['clone_fields'][$field_index];
			$blogs = array();
			
			if ( is_array( $global_settings['clone_fields'][$field_index]['blogs'] ) ):
				$blogs = $global_settings['clone_fields'][$field_index]['blogs'];
			elseif ( ! is_array( $global_settings['clone_fields'][$field_index]['blogs'] ) ):
				$blogs_ids = explode(',', $global_settings['clone_fields'][$field_index]['blogs']);
				
				foreach ( $blogs_ids as $id ):
					$id = trim($id);
					if ( ! empty($id) ):
						$blogs[$id] = true;
					endif;
				endforeach;
			endif;
			
			switch ($field_action):
			case 'add':
				$blogs[$blog_id] = true;
				$global_settings['clone_fields'][$field_index]['blogs'] = $blogs;
				unset($field['blogs']);
				$profile->settings['clone_fields'][$field['type']] = $field;
				break;
			case 'remove':
				unset($blogs[$blog_id]);
				unset($profile->settings['clone_fields'][$field['type']]);
				
				if ( empty($blogs) ):
					unset($global_settings['clone_fields'][$field_index]);
					$global_settings['clone_fields'] = array_values(array_filter($global_settings['clone_fields'])); // Reindex the array.
				else:
					$global_settings['clone_fields'][$field_index]['blogs'] = $blogs;
				endif;
				break;
			endswitch;
			
			update_option( 'Profile_CCT_settings', $profile->settings );
			update_site_option( 'Profile_CCT_global_settings', $global_settings );
		endif;
	endif;
	
	/*
	echo '<pre>';
	echo 'POST<br />';
	print_r($_POST);
	echo '<br />Global Settings<br />';
	print_r($global_settings);
	echo '<br />Profile Settings<br />';
	print_r($profile->settings['clone_fields']);
	echo '</pre>';
	*/
	
	// For local 
	/*if ( ! empty($profile->settings['clone_fields']) ):
		foreach ( $profile->settings['clone_fields'] as $local_clone_field ):
			$local_clone_fields[$local_clone_field['type']] = false;
		endforeach;
	else:
		$local_clone_fields = array();
	endif;
	
	// ADD FIELDS 
	if ( !empty($_POST) && check_admin_referer( 'add_profile_field', 'add_profile_fields_field' ) ):
		$error = array();
		
		$field_label = trim( strip_tags($_POST['label']) );
		if ( empty($field_label) ):
			$error['label'] = "Please Fill out the Field Name";
		endif;
		
		$field_clone = trim( strip_tags($_POST['field_clone']) );
		if ( empty($field_clone) ):
			$error['field_clone'] = "Please Select a Field To Duplicate";
		endif;
		
		$field_description = trim( strip_tags($_POST['description']) );
		if ( empty($field_description) ):
			$error['description'] = "Please enter a Description for the Field";
		endif;
		
		$new_type = trim( strip_tags($_POST['field_type']) );
		
		// We want to either add a completly new type or add an existing one to the local array.
		if ( empty($error) || ! empty($new_type) ):
			$type = "clone_".strtolower(preg_replace('/[^A-Za-z0-9]+/', '_', $field_label));
			$field_type = $type;
			$field_count = 1;
			$global_clone_fields = array();
			
			if ( is_array($global_settings['clone_fields']) ):
				$global_count = 0;
				// For global 
				foreach ( $global_settings['clone_fields'] as $clone_field ):
					$global_clone_fields[$clone_field['type']] = true;
					
					// Just adding one to the local array
					if ( $new_type == $clone_field['type'] || ( $type == $clone_field['type'] && $clone_field['field_clone'] == $field_clone ) ):
						$copy_to_local = $clone_field;
						$new_type = $clone_field['type'];
						unset($copy_to_local['blogs']); // local array doesn't need to worry about 
						$global_to_change_count = $global_count;
					endif;
					$global_count++;
				endforeach;
				
				if ( empty($copy_to_local) ): 
					// name can't clash with the global socope
					while ( in_array( $field_type, $global_clone_fields ) ):
						$field_type = $type."_".$field_count;
						$field_count++;
					endwhile;
				endif;
			endif;
			
			// create a new 
			if ( empty( $copy_to_local ) ):
				$new_field = array(
					'type'        => $field_type,
					'label'       => $field_label,
					'field_clone' => $field_clone,
					'description' => $field_description,
				);
			else:
				if ( ! in_array( $field['type'], $local_clone_fields ) ):
					// add a copy of the to the local field
					(array) $profile->settings['clone_fields'][] = $copy_to_local;
					
					update_option( 'Profile_CCT_settings', $profile->settings );
					$global_settings['clone_fields'][$global_to_change_count]['blogs'] .= ",".$blog_id;
					// also update the blogs field in the particular 
					update_site_option( 'Profile_CCT_global_settings', $global_settings );
					
					// remove the errors from the 
					unset($error);
					// make sure that the new clone fields is added to the clone_fields
					$local_clone_fields[$copy_to_local['type']] = true;
					
					$note = "<p class='info'>Now you can add ". $copy_to_local['label']." Field to the <a href=\"".admin_url('edit.php?post_type=profile_cct&page='.PROFILE_CCT_BASEADMIN.'&view=form')."\">form</a>, <a href=\"".admin_url('edit.php?post_type=profile_cct&page='.PROFILE_CCT_BASEADMIN.'&view=page')."\">person page</a> or the <a href=\"".admin_url('edit.php?post_type=profile_cct&page='.PROFILE_CCT_BASEADMIN.'&view=list')."\">list view</a></p>";
				endif;
			endif;
			
			// create a new field add it to global as well as local 
			if ( empty($error) && !empty($new_field) ):
				// add completely new field to the site and global scope
				(array) $profile->settings['clone_fields'][] = $new_field;
				$new_field['blogs'] = $blog_id;
				(array) $global_settings['clone_fields'][] = $new_field;
				update_option( 'Profile_CCT_settings', $profile->settings );
				update_site_option( 'Profile_CCT_global_settings', $global_settings );
				$local_clone_fields[] = $new_field['type'];
				
				$note = "<p class='info'>Now you can add ".$new_field['label']." Field to the <a href=\"".admin_url('edit.php?post_type=profile_cct&page='.PROFILE_CCT_BASENAME.'&view=form')."\">form</a>, <a href=\"".admin_url('edit.php?post_type=profile_cct&page='.PROFILE_CCT_BASENAME.'&view=page')."\">person page</a> or the <a href=\"".admin_url('edit.php?post_type=profile_cct&page='.PROFILE_CCT_BASENAME.'&view=list')."\">list view</a></p>";;
			endif;
		endif;
	endif;
	
	// REMOVE FIELDS
	if ( is_numeric($_GET['remove']) ):
		$field_to_remove = $global_settings['clone_fields'][$_GET['remove']];
		
		if ( wp_verify_nonce($_GET['_wpnonce'], 'profile_cct_remove_field'.$field_to_remove['type']) ):
			$count = 0;
			unset($removal_index);
			unset($local_clone_fields); // We will recreate this.
			$local_clone_fields = array();
			
			foreach ( $profile->settings['clone_fields'] as $field ):
				print_r($field['type']);
				echo ", ";
				if ( $field_to_remove['type'] == $field['type'] ):
					$removal_index = $count;
				else:
					$local_clone_fields[$field['type']] = true;
				endif;
				
				$count++;
			endforeach;
			
			// remove the fields
			if ( is_numeric($removal_index) ):
				unset($profile->settings['clone_fields'][$removal_index]);
				
				// also remove the site from the blogs global array
				$blogs = str_replace( $blog_id, "", $field_to_remove['blogs']);
				$blogs = str_replace(",,", "", $blogs);
				$blogs = ( substr($blogs, -1) == "," ? substr($blogs, 0, -1) : $blogs );
				
				$global_settings['clone_fields'][$_GET['remove']]['blogs'] = $blogs;
				
				update_option( 'Profile_CCT_settings', $profile->settings );
				update_site_option( 'Profile_CCT_global_settings', $global_settings );
			endif;
		endif;
	endif;*/
?>
<h2>Fields Builder</h2>
<?php echo $note; ?>

<h3>Available Fields</h3>
<pre>
<?php print_r($local_clone_fields); ?>
</pre>
<?php if ( is_array( $global_settings['clone_fields'] ) && ! empty( $global_settings['clone_fields'] ) ): ?>
	<table class="widefat">
		<thead>
			<tr>
				<th class="row-title">Name</th>
				<th>Description</th>
				<th>Based on </th>
			</tr>
		</thead>
		<tbody>
			<?php
			$count = 0;
			foreach ( $global_settings['clone_fields'] as $field ):
				$enabled = ( isset( $global_settings['clone_fields'][$count]['blogs'][$blog_id] ) && $global_settings['clone_fields'][$count]['blogs'][$blog_id] == true );
			?>
				<tr class="<?php if ( $count % 2 ) echo 'alternate'; ?> <?php if ( ! $enabled ) echo 'disabled'; ?>">
					<td >
						<?php echo $field['label']; ?>
						<?php //if ( isset( $local_clone_fields[$field['type']] ) && $local_clone_fields[$field['type']] == true ): ?>
						<?php if ( $enabled ): ?>
							<div class="row-actions">
								<span class="trash">
									<a href="?post_type=profile_cct&page=<?php echo PROFILE_CCT_BASEADMIN; ?>&view=fields&field=<?php echo $count; ?>&action=remove&_wpnonce=<?php echo wp_create_nonce('profile_cct_toggle_field'); ?>" class="submitdelete">Delete</a>
								</span>
							</div>
						<?php else: ?>
							<div class="row-actions">
								<a href="?post_type=profile_cct&page=<?php echo PROFILE_CCT_BASEADMIN; ?>&view=fields&field=<?php echo $count; ?>&action=add&_wpnonce=<?php echo wp_create_nonce('profile_cct_toggle_field'); ?>" class="submitadd">Enable</a>
							</div>
						<?php endif; ?>
					</td>
					<td><?php echo $field['description']; ?></td>
					<td>the <em><?php echo $field['field_clone']; ?></em> field</td>
				</tr>
			<?php 
				$count++;
			endforeach;
			?>
		</tbody>
		<tfoot>
			<tr>
				<th class="row-title">Name</th>
				<th>Description</th>
				<th>Based on</th>
			</tr>
		</tfoot>
	</table>
<?php else: ?>
	<p>There are no duplicated fields</p>
<?php endif; ?>
	
<h3>Create a new Field</h3>
<form method="post" action="<?php echo admin_url( 'edit.php?post_type=profile_cct&page='.PROFILE_CCT_BASEADMIN.'&view=fields' ); ?>">
	<?php wp_nonce_field( 'add_profile_field', 'add_profile_fields_field' ); ?>
	<table class="form-table">
		<tr valign="top">
			<th scope="row"><label for="label">Name</label><span class="required">*</span></th>
			<td>
				<input type="text" value="<?php echo esc_attr($field_label); ?>" id="label" name="label" class="all-options"  /> <span class="description">For example: Lab Phone</span>
				<br />
				<?php if (isset($error['label'])) echo "<span class='form-invalid' style='padding:2px;'>".$error['label']."</span>"; ?>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="field_clone">Field To Duplicate</label><span class="required">*</span></th>
			<td>
				<select name="field_clone" id="field_clone" class="all-options">
					<?php foreach(Profile_CCT_Admin::fields_to_clone() as $field_to_clone): ?>
						<option value="<?php echo esc_attr($field_to_clone['type']);?>" <?php selected($field_clone, $field_to_clone['type']); ?>><?php echo esc_attr($field_to_clone['type']);?></option>
					<?php endforeach; ?>
				</select>
				<span class="description">Select the field that you want to mimic in functionality.</span>
				<br />
				<?php if (isset($error['field_clone'])) echo "<span class='form-invalid' style='padding:2px;'>".$error['field_clone']."</span>"; ?>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="description">Description</label><span class="required">*</span></th>
			<td>
				<textarea name="description" id="description" class="large-text" cols="30" rows="5"><?php echo esc_textarea($field_description); ?></textarea>
				<br />
				<span class="description">Describe what this field is used for.</span>
				<br />
				<?php if (isset($error['description'])) echo "<span class='form-invalid' style='padding:2px;'>".$error['description']."</span>"; ?>
			</td>
		</tr>
	</table>
	<input class="button-primary" type="submit" name="Example" value="<?php _e( 'Add Field' ); ?>" /> 
</form>