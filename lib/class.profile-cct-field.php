<?php

/**
 * Profile_CCT_Field class.
 */
class Profile_CCT_Field {

	// options
	var $type;		   // this is the unique field
	var $label;		   // the label is the main label of the field
 	var $description;  // the descript that is displayed to the user when they are entering it

	var $show;			// all the once that are currently shown
	var $show_fields;	// all the possible field that you can toggle
	var $show_link_to;  // enable the functionality
	var $link_to;		// do we offer a link to from this field

	var $class;			// the class of the field
	var $hide_label;    // hide the label if you don't want to display it
	var $width;			// the width of the elemenet in different sizes
	var $text;			// the replacment text for fields like permalink (read more) the text that is suppoed to be there also for  taxonomies

	var $before;		// html to show before the field
	var $after;			// html to show after the field
	var $empty;			// the info to show when the field is empty

	var $url_prefix;    // used by the data field to enable a prefix

	var $show_multiple; // does the field have the option to be replicated
	var $multiple;		// if the field should be replicated

	// data
	var $data;

	// action
	var $action; 	// are we currently creating the form or displaying it

	var $options; 	// save all the options again

	var $counter = 0;

	function __construct ( $options , $data ) {
		$this->options = ( is_array( $options ) ? array_merge( $this->default_options, $options ): $this->default_options );
        
		$this->action        = ( isset( Profile_CCT_Admin::$action ) ? Profile_CCT_Admin::$action : 'edit' );
		$this->page          = ( isset( Profile_CCT_Admin::$page ) ? Profile_CCT_Admin::$page : false );
        
		$this->type          = ( isset( $this->options['type'] ) ? $this->options['type'] : null );
		$this->label         = ( isset( $this->options['label'] ) ? $this->options['label'] : false );
		$this->description   = ( isset( $this->options['description'] ) ? $this->options['description'] : null );
        
		$this->show_link_to  = ( isset( $this->options['show_link_to'] ) ? $this->options['show_link_to'] : false );
		$this->link_to       = ( isset( $this->options['link_to'] ) && $this->options['link_to']  ? true: false );
        
		$this->show          = ( is_array( $this->options['show'] ) ? $this->options['show'] : array() ) ;
		$this->show_fields   = ( is_array( $this->options['show_fields'] ) ? $this->options['show_fields'] : array() ) ;
        
		$this->class         = ( isset( $this->options['class'] ) ? $this->options['class'] : "" );
		$this->hide_label    = ( isset( $this->options['hide_label'] ) && $this->options['hide_label'] ? true: false );
        
		$this->width         = ( isset( $this->options['width'] ) ? $this->options['width'] : false );
		$this->width         = ( 'form' == $this->page ? 'full' : $this->width );
        
		$this->text          = ( isset( $this->options['text'] ) ? $this->options['text'] : false );
        
		$this->before        = ( isset( $this->options['before'] ) ? $this->options['before'] : false );
		$this->after         = ( isset( $this->options['after'] ) ? $this->options['after'] : false );
		$this->empty         = ( isset( $this->options['empty'] ) ? $this->options['empty'] : false );
        
		$this->url_prefix    = ( isset( $this->options['url_prefix'] ) ? $this->options['url_prefix'] : false );
        
		$this->show_multiple = ( isset( $this->options['show_multiple'] ) ? $this->options['show_multiple'] : false );
		$this->multiple      = ( isset( $this->options['multiple'] ) ? $this->options['multiple'] : false );
        
		// start test code
		//$this->page = 'form';
		//error_log("Page: ".Profile_CCT_Admin::$page.", ".print_r($this->page, TRUE));
		// end test code
		
		$this->start_field();
		if ( 'form' != $this->page ):
			$this->display();
		else:
			$this->field();
		endif;
		$this->end_field();
	}

	function start_field() {
		// lets display the start of the field to the user
		// $is_in_form = '??'; // todo: figure out what this means
		if ( 'edit' == $this->action ): ?>
	 		<li class="<?php echo' shell-'.esc_attr( $this->type ); ?> field-item <?php echo $this->class." ".$this->width; ?>" for="cct-<?php echo esc_attr( $this->type ); ?>" data-options="<?php echo esc_attr( $this->serialize( $this->options ) ); ?>" >
			<a href="#edit-field" class="edit">Edit</a>
			<div class="edit-shell" style="display:none;">
				<input type="hidden" name="type" value="<?php echo esc_attr( $this->type ); ?>" />
				<?php
				if ( 'form' == $this->page ):
					$this->input_text( array(
						'size'  => 30,
						'value' => $this->label,
						'class' => 'field-label',
						'name'  => 'label',
						'label' => 'label',
						'before_label' => true,
					) );
				else:
					$this->input_hidden( array(
						'value' => $this->label,
						'name'  => 'label',
					) );
				endif;
                
				if ( isset($this->description) && 'form' == $this->page):
					$this->input_textarea( array(
						'size'         => 10,
						'value'        => $this->description,
						'class'        => 'field-description',
						'name'         => 'description',
						'label'        => 'description' ,
						'before_label' => true,
					) );
				endif;
				
				if ( $this->width && 'form' != $this->page ):
					$this->input_select( array(
						'all_fields'   => array('full', 'half', 'one-third', 'two-third'),
						'class'        => 'field-width',
						'value'        => $this->width,
						'name'         => 'width',
						'label'        => 'select width',
						'before_label' => true,
					) );
				endif;
                
				if ( $this->text && 'form' != $this->page ):
					$this->input_text( array(
						'size'         => 30,
						'value'        => $this->text,
						'class'        => 'field-text',
						'label'        => 'text input',
						'before_label' => true,
					) );
				endif;
				
				if ( isset($this->before) && 'form' != $this->page ):
					$this->input_textarea( array(
						'size'         => 10,
						'value'        => $this->before,
						'class'        => 'field-textarea',
						'name'         => 'before',
						'label'        => 'before html',
						'before_label' => true,
					) );
				endif;
                
				if ( isset($this->after) && 'form' != $this->page ):
					$this->input_textarea( array(
						'size'         => 10,
						'value'        => $this->after,
						'class'        => 'field-textarea',
						'name'         => 'after',
						'label'        => 'after html',
						'before_label' => true,
					) );
				endif;
                
				if ( isset( $this->empty) && 'form' != $this->page ): //
					$this->input_textarea( array(
						'size'         => 10,
						'value'        => $empty,
						'class'        => 'field-textarea',
						'name'         => 'empty',
						'label'        => 'content to be displayed on empty',
						'before_label' => true,
					) );
				endif;
                
				if ( $this->url_prefix && 'form' != $this->page ): // needed for the data field
					$this->input_text( array(
						'value'        => $this->url_prefix,
						'class'        => 'field-url-prefix',
						'name'         => 'url_prefix',
						'label'        => 'url prefix ( http:// )',
						'before_label' => true,
					) );
				endif;
                
				if ( $this->show_fields  ):
					$this->input_multiple( array(
						'all_fields'      => $this->show_fields,
						'class'           => 'field-show',
						'selected_fields' => $this->show,
						'name'            => 'show',
						'label'           => 'show / hide input area',
						'before_label'    => true,
					) );
				endif;
                
				if ( $this->show_multiple && 'form' == $this->page ):
					$this->input_checkbox( array(
						'name'            => 'multiple',
						'class'           => 'field-multiple',
						'value'           => $this->multiple,
						'sub_label'       => 'yes, allow the user to create multiple fields',
						'label'           => 'multiple',
						'before_label'    => true,
					) );
				endif;
                
				if ( $this->show_link_to && 'form' != $this->page ):
					$this->input_checkbox( array(
						'name'         => 'link_to',
						'class'        => 'field-multiple',
						'value'        => $this->link_to,
						'label'        => 'link to profile',
						'sub_label'    => 'wrap the field with a link to the profile page',
						'before_label' => true ,
					) );
				endif;
				?>
				<input type="button" value="Save" class="button save-field-settings" />
				<span class="spinner" style="display:none;"><img src="<?php echo admin_url('/images/wpspin_light.gif'); ?>" alt="spinner" /> saving...</span>
			</div>
		 	<label class="field-title"><?php echo $this->label; ?></label>
		 	<?php
			else: // display the
				echo $this->before;
				?><div class="<?php echo esc_attr( $this->type ); ?> field-item <?php echo $this->class." ".$this->width; ?>"><?php
			endif;
			?>
		 	<div class="field-shell field-shell-<?php echo $this->type; ?>">
			<?php
			if ( isset( $this->description ) ):
				printf('<pre class="description">%s</pre>' ,esc_html( $description )  );
            endif;
	}

	function end_field() {
		$shell_tag  = ( $this->action == 'edit' ? 'li' : 'div');
        
		if( $this->show_multiple ):
			$style_multiple = ( $this->multiple ? 'style="display: inline;"' : 'style="display: none;"' );
            
			if ( 'edit' == $this->action && 'form' == Profile_CCT_Admin::$page ):
				echo '<span class="add-multiple"><a href="#add" '. $style_multiple .' class="button disabled">Add another</a> <em>disabled in preview</em></span>';
			elseif ( $this->multiple && !in_array( Profile_CCT_Admin::$page, array('page', 'list') ) ):
				echo '<a href="#add" class="button add-multiple">Add another</a>'; // todo: make it work without js
			endif;
		endif; ?>
	 	</div></<?php echo $shell_tag; ?>><?php
		if( 'edit' != $this->action ):
			echo $this->after;
        endif;
	}
    
	############################################################################################################
	/* Inputs */

	/**
	 * input_text function.
	 *
	 * @access public
	 * @return void
	 */
	function input_text( $attr ) {
		extract( $this->field_attr( $attr, 'text' ) );
		printf( "<span %s>", $field_shell_attr );
		$this->input_label_before( $id, $label, $before_label );
        
		printf('<input type="text" %s value="%s" />', $field_attr, $value );
        
		$this->input_label_after( $id, $label, $before_label );
		echo "</span>";
	}

	/**
	 * input_hidden function.
	 *
	 * @access public
	 * @return void
	 */
	function input_hidden( $attr ) {
		extract( $this->field_attr( $attr, 'hidden' ) );
		printf( "<span %s>", $field_shell_attr );
		$this->input_label_before( $id, $label, $before_label );
        
		printf('<input type="hidden" %s value="%s" />', $field_attr, $value );
        
		$this->input_label_after( $id, $label, $before_label );
		echo "</span>";
	}

	/**
	 * input_multiple function.
	 *
	 * @access public
	 * @param mixed $attr
	 * @return void
	 */
	function input_multiple( $attr ) {
		extract( $this->field_attr( $attr, 'multiple' ) );
		printf("<div %s>", $field_shell_attr );
		$this->input_label_before( $id, $label, $before_label );
        
		// need to change the name in this case
		$selected_fields = is_array( $selected_fields ) ? $selected_fields : array();
        
		foreach( $all_fields as $field ):
			$this->input_checkbox_raw( in_array( $field, $selected_fields ), $field_attr, $field,  $field ); // produces the checkbox
        endforeach;
        
		$this->input_label_after( $id, $label, $before_label );
		echo "</div>";
	}

	/**
	 * input_checkbox function.
	 *
	 * @access public
	 * @param mixed $attr
	 * @return void
	 */
	function input_checkbox( $attr ) {
		extract( $this->field_attr( $attr, 'checkbox' ) );
		printf( "<div %s>", $field_shell_attr );
		$this->input_label_before( $id, $label, $before_label );
        
		$this->input_checkbox_raw( $value, $field_attr, $sub_label ); // produces the checkbox
        
		$this->input_label_after( $id, $label, $before_label );
		echo "</div>";
	}

	function input_checkbox_raw( $checked, $field_attr, $field, $value = 1 ) { ?>
		<label><input type="checkbox" <?php checked( $checked ); ?> value="<?php echo $value; ?>" <?php echo $field_attr; ?>  /> <?php echo $field; ?></label>
		<?php
	}

	/**
	 * input_select function.
	 *
	 * @access public
	 * @param mixed $attr
	 * @return void
	 */
	function input_select( $attr ) {
		extract( $this->field_attr( $attr, 'select' ) );
		printf("<span %s>", $field_shell_attr );
		$this->input_label_before( $id, $label, $before_label ); ?>
        
		<select id="<?php echo $id; ?>" <?php echo $name; ?> >
			<option value=""></option><!-- this gives us an emty field if the user doesn't select anything -->
            <?php 
			foreach ( $all_fields as $field ):
				printf('<option value="%s" %s>%s</option>', $field, selected( $value, $field , false ), $field );
			endforeach;
            ?>
		</select>
		<?php
        
		$this->input_label_after( $id, $label, $before_label );
		echo "</span>";
	}

	/**
	 * input_textarea function.
	 *
	 * @access public
	 * @param mixed $attr
	 * @return void
	 */
	function input_textarea( $attr ) {
		extract( $this->field_attr( $attr, 'textarea' ) );
		printf( "<span %s>", $field_shell_attr );
		$this->input_label_before( $id, $label, $before_label );
        
		// only dispaly the editor on the Profile edit side
		if( 'edit' == $this->action || $this->multiple ): ?>
			<textarea <?php echo $field_attr; ?>><?php echo esc_html( $value ); ?></textarea> <?php
		else:
			wp_editor( $value, $id, array( 'textarea_name' => $name, 'teeny' => true, 'media_buttons' => false ) );
		endif;
        
		$this->input_label_after( $id, $label, $before_label );
		echo "</span>";
	}

	/**
	 * input_label_before function.
	 *
	 * @access public
	 * @param mixed $id
	 * @param mixed $label
	 * @param mixed $before_label
	 * @return void
	 */
	function input_label_before( $id, $label, $before_label ){
		if ( $before_label ):
			$this->input_label( $id, $label);
        endif;
	}

	/**
	 * input_label_after function.
	 *
	 * @access public
	 * @param mixed $id
	 * @param mixed $label
	 * @param mixed $before_label
	 * @return void
	 */
	function input_label_after( $id, $label, $after_label ){
		if ( !$after_label ):
			$this->input_label( $id, $label);
        endif;
    }

	/**
	 * input_label function.
	 *
	 * @access public
	 * @param mixed $id
	 * @param mixed $label
	 * @return void
	 */
	function input_label( $id, $label) {
		?>
        <label for="<?php echo $id; ?>" ><?php echo $label; ?></label>
        <?php
	}

	############################################################################################################
	/* Display */
	/**
	 * display_shell function.
	 * 
	 * @access public
	 * @param mixed $attr
	 * @return void
	 */
	function display_shell( $attr = array()) {
		extract( $attr );
		$tag = ( isset($tag) ? $tag : 'div' );
		$class_attr = ( isset($class) ? 'class="'.$class.'"' : '' );
        
		printf( '<%s %s>', $tag , $class_attr );
		
		if( $this->link_to ):
			printf( '<a href="%s">', get_permalink() ); // this should always just link to the profile
        endif;
	}
	
	/**
	 * display_end_shell function.
	 * 
	 * @access public
	 * @param mixed $attr
	 * @return void
	 */
	function display_end_shell( $attr = array() ) {
		extract( $attr );
		$tag = ( isset($tag) ? $tag : 'div');
        
		if( $this->link_to ):
			echo '</a>';
        endif;
        
		printf( '</%s>', $tag );
	}
	
	/**
	 * display_text function.
	 * 
	 * @access public
	 * @param mixed $attr
	 * @return void
	 */
	function display_text( $attr ) {
	    /*
		    $attr['class'];
		    $attr['field_id'];
		    $attr['default_text'];
		    $attr['href'];
		    $attr['value'];
		   	$attr['separator'];
		   	$attr['post_separator'];
	    */
	    extract( $attr );
		//error_log("Display Text, Stage 1 (".$this->action.", ".$value.")");
		
		$backup_default = '<p> Lorem ipsum dolor sit amet, consectetur adipiscing elit. In et tempor lorem. Nam eget sapien sit amet risus porttitor pellentesque. Sed vestibulum tellus et quam faucibus vel tristique metus sagittis. Integer neque justo, suscipit sit amet lobortis eu, aliquet imperdiet sapien. Morbi id tellus quis nisl tempor semper.</p><p>Nunc sed diam sit amet augue venenatis scelerisque quis eu ante. Cras mattis auctor turpis, non congue nibh auctor at. Nulla libero ante, dapibus a tristique eu, semper ac odio. Nulla ultrices dui et velit eleifend congue. Mauris vel mauris eu justo lobortis semper. Duis lacinia faucibus nibh, ac sodales leo condimentum id. Suspendisse commodo mattis dui, eu rutrum sapien vehicula a. Proin iaculis sollicitudin lacus vitae commodo.</p>';
		$default_text = ('lorem ipsum' == $default_text ? $backup_default : $default_text);
		
	    $class_attr  = ( isset($class) ? 'class="'.$class.'" ' : '' );
	    $tag         = ( isset($tag) ? $tag : 'span' );
		
	    $value       = ( isset($value) ? $value : $this->data[$field_id] );
	    $display     = ( 'edit' == $this->action ? $default_text : $value );
	  
	    $href_attr   = ( isset($href) ? 'href="'.$href.'" ' : '' );
	    $id          = '';
		
		//if( ! empty( $display ) ):
			//error_log("Display Text, Stage 2");
	    	$this->display_separator( $attr );
			echo " <".$tag." ".$class_attr.$href_attr.">".$display."</".$tag.">";
			$this->display_separator( array( 'separator' => $post_separator, 'class' => $class ) );
		//endif;
	}
	
	function display_separator( $attr ) {
        extract( $attr );
		$separator = ( isset( $separator ) ? '<span class="'.$class.'-separator separator">'.$separator.'</span>' : '' );
		echo $separator;
	}
	
	/**
	 * display_email function.
	 * 
	 * @access public
	 * @param mixed $attr
	 * @return void
	 */
	function display_email( $attr ) {
		if ( empty( $attr['mailto'] ) ):
			$attr['mailto'] = ( 'edit' == $this->action ? $attr['default_text'] : $this->data[$attr['field_id']] );
        endif;
		
		$attr['href'] = 'mailto:'.$attr['mailto'];
		$this->display_link( $attr );
	}
	
	/**
	 * display_link function.
	 * 
	 * @access public
	 * @param mixed $attr
	 * @return void
	 */
	function display_link(  $attr ) {
		// todo: implement maybe_link - this might be a link or not favour the text 
		// todo: implement force_link - this should be a link if not next use the url as the link - example website
		if( empty( $attr['href'] ) )
			$attr['href'] = ( 'edit' == $this->action? $attr['default_text'] : $this->data[$attr['field_id']] );
		
		$attr['tag'] = 'a';
		
		$this->display_text( $attr );
	}
	
	function display_social_link( $attr ) {
		echo "display the link to the profile";
	}
	
	function display_textfield( $attr ) {
		$attr['tag'] = 'div';
		
		// what we want to disipaly should be filtered using the special filter
		$this->display_text( $attr );
	}
	

	############################################################################################################
	/* Helper functions */
	/**
	 * serialize function.
	 * converts the data into a url string
	 * @access public
	 * @param mixed $data
	 * @return void
	 */
	function serialize( $data ) {
        
		foreach($data as $key => $value):
			if ( in_array($key,array("show_fields","show_multiple"))):
				continue;
			elseif ( is_array($value) ):
				foreach($value as $value_data):
					$str[] = urlencode($key."[]")."=".urlencode($value_data);
				endforeach;
			else:
				$str[] = urlencode($key)."=".urlencode($value);
			endif;
		endforeach;
        
		return implode("&",$str);
	}
	
	
	/**
	 * field_attr function.
	 * 
	 * @access public
	 * @param mixed $attr
	 * @param mixed $field_type
	 * @return void
	 */
	function field_attr( $attr, $field_type ) {
		$count = 0; // when should this be happening?
        
		echo ( isset( $attr['separator'] ) ? '<span class="separator">'.esc_html( $attr['separator'] ).'</span>' : '' );
        
		$show 	= ( isset($attr['field_id']) && !in_array( $attr['field_id'], $this->show ) && in_array( $attr['field_id'], $this->show_fields)  ? ' style="display:none;"': '' );  // should this field be displayed
		$needed_attr['id']  = ( isset( $attr['field_id'] ) && $attr['field_id'] ? $attr['field_id'] : 'profile-cct-'.$this->type.'-'.$field_type.'-'.$this->counter ); // todo: show warning
        
		if( $field_type =='multiple' ):
			$name = ( isset($attr['name'])? ' name="'.$attr['name'].'[]"':  ' name="profile_cct['.$this->type.']['.$count.']['.$needed_attr['id'].'][]"');
		//	$textarea_id = 'profile_cct-'.$this->type.'-'.$count.'-'.$needed_attr['id'];
		//	$textarea_name = 'profile_cct['.$this->type.']['.$count.']['.$needed_attr['id'].'][]';
		elseif( $this->multiple ):
			$name = ( isset($name)? ' name="'.$name.'[]"':  ' name="profile_cct['.$this->type.']['.$count.']['.$needed_attr['id'].']"');
		//	$textarea_id = 'profile_cct-'.$this->type.'-'.$count.'-'.$needed_attr['id'];
		//	$textarea_name = 'profile_cct['.$this->type.']['.$count.']['.$needed_attr['id'].']';
		else:
			$name = ( isset($name)? ' name="'.$name.'"': ' name="profile_cct['.$this->type.']['.$needed_attr['id'].']"');
		//	$textarea_id = 'profile_cct-'.$this->type.'-'.$needed_attr['id'];
		//	$textarea_name = 'profile_cct['.$this->type.']['.$needed_attr['id'].']';
		endif;
        
		// <input type="text" value="address" name="label" class="field-label" size="30" id="profile_cct--"> what it should be
        
		// things to be returned
		$needed_attr['before_label'] = ( isset( $attr['before_label'] ) && $attr['before_label'] ? true : false );
		$needed_attr['field_shell_attr'] = ( isset( $attr['field_id'] )? ' class="'.$attr['field_id'].' '.$field_type.'-shell"': '') . $show;
        
		$needed_attr['value'] 	   = ( isset( $attr['value'] )		? $attr['value'] : '' );
		$needed_attr['label']	   = ( isset( $attr['label'] )		? $attr['label'] : '' );
		$needed_attr['sub_label']  = ( isset( $attr['sub_label'] )	? $attr['sub_label'] : '' );
		$needed_attr['name'] 	   = ( isset( $attr['name'] )		? $attr['name'] : '' );
		$needed_attr['all_fields'] = ( isset( $attr['all_fields'] )	? $attr['all_fields'] : '' );
		$needed_attr['selected_fields'] = ( isset( $attr['selected_fields'] )	? $attr['selected_fields'] : '' );;
		$needed_attr['sub_label']  = ( isset( $attr['sub_label'] )	? $attr['sub_label'] : '' );
        
		$size 	= ( isset( $attr['size'] )	? ' size="'.	$attr['size'].	'" '	: '');
		$row 	= ( isset( $attr['row'] )	? ' row="'.		$attr['row'].	'" '	: '');
		$cols 	= ( isset( $attr['cols'] )	? ' cols="'.	$attr['cols'].	'" '	: '');
		$class 	= ( isset( $attr['class'] )	? ' class="'.	$attr['class'].	'" ': ' class="field text"' );
        
		$needed_attr['field_attr'] = 'id="'.$needed_attr['id'].'" '. $name. $class.  $row. $cols. $size.' ';
        
		$this->counter++; // used for each
        
    	return $needed_attr;
        /*
        if( !isset($field_id_class) )
        $field_id_class = ( isset($field_id)? ' class="'.$field_id.' '.$type.'-shell"': '');
    
    
        $size = ( isset($size)? ' size="'.$size.'"': '');
        $row = ( isset($row)? ' row="'.$row.'"': '');
        $cols = ( isset($cols)? ' cols="'.$cols.'"': '');
        $class = ( isset($class)? ' class="'.$class.'"': ' class="field text"');
        $id = ( isset($id)? ' id="'.$id.'"': ' ');
        $separator = (isset($separator) ? '<span class="separator">'.$separator.'</span>': "");
    
        if($type =='multiple'):
            $name = ( isset($name)? ' name="'.$name.'[]"':  ' name="profile_cct['.$field_type.']['.$count.']['.$field_id.'][]"');
        $textarea_id = 'profile_cct-'.$field_type.'-'.$count.'-'.$field_id;
        $textarea_name = 'profile_cct['.$field_type.']['.$count.']['.$field_id.'][]';
        elseif($multiple):
            $name = ( isset($name)? ' name="'.$name.'[]"':  ' name="profile_cct['.$field_type.']['.$count.']['.$field_id.']"');
        $textarea_id = 'profile_cct-'.$field_type.'-'.$count.'-'.$field_id;
        $textarea_name = 'profile_cct['.$field_type.']['.$count.']['.$field_id.']';
        else:
            $name = ( isset($name)? ' name="'.$name.'"': ' name="profile_cct['.$field_type.']['.$field_id.']"');
        $textarea_id = 'profile_cct-'.$field_type.'-'.$field_id;
        $textarea_name = 'profile_cct['.$field_type.']['.$field_id.']';
        endif;
    
        $show = ( isset($show) && !$show ? ' style="display:none;"': '');
        */
	}
    
	/* Time */
	/**
	 * list_of_months function.
	 *
	 * @access public
	 * @return void
	 */
	function list_of_months() {
		return array(
			"January",
			"February",
			"March",
			"April",
			"May",
			"June",
			"July",
			"August",
			"September",
			"October",
			"November",
			"December"
		);
	}

	/**
	 * list_of_years function.
	 *
	 * @access public
	 * @return void
	 */
	function list_of_years($start = 3, $end = -40 ) {
		return range( date("Y")+$start, date("Y")+$end );
	}

	/**
	 * list_of_days function.
	 *
	 * @access public
	 * @return void
	 */
	function list_of_days() {
		return array( "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday" );
	}

	/**
	 * list_of_hours function.
	 *
	 * @access public
	 * @return void
	 */
	function list_of_hours() {
		return range(1, 12);
	}

	/**
	 * list_of_minutes function.
	 *
	 * @access public
	 * @return void
	 */
	function list_of_minutes(){
		return array_merge( array('00','05'), range(10,55,5) );
	}

	/**
	 * list_of_periods function.
	 *
	 * @access public
	 * @return void
	 */
	function list_of_periods() {
		return array( 'AM', 'PM' );
	}

	/**
	 * phone_options function.
	 *
	 * @access public
	 * @return void
	 */
	function phone_options(){
		return array(
			"phone",
			"work phone",
			"mobile",
			"fax",
			"work fax",
			"pager",
			"other",
		);
	}
    
	function project_status(){
		return array( 'Planning', 'Current', 'Completed');
	}
	/* Location */

	/**
	 * list_of_countries function.
	 *
	 * @access public
	 * @return void
	 */
	function list_of_countries() {
		return array(
			"Canada",
			"United States",
			"United Kingdom",
			'---',
			"Afghanistan",
			"Albania",
			"Algeria",
			"Andorra",
			"Angola",
			"Antigua and Barbuda",
			"Argentina",
			"Armenia",
			"Australia",
			"Austria",
			"Azerbaijan",
			"Bahamas",
			"Bahrain",
			"Bangladesh",
			"Barbados",
			"Belarus",
			"Belgium",
			"Belize",
			"Benin",
			"Bhutan",
			"Bolivia",
			"Bosnia and Herzegovina",
			"Botswana",
			"Brazil",
			"Brunei",
			"Bulgaria",
			"Burkina Faso",
			"Burundi",
			"Cambodia",
			"Cameroon",
			"Canada",
			"Cape Verde",
			"Central African Republic",
			"Chad",
			"Chile",
			"China",
			"Colombi",
			"Comoros",
			"Congo (Brazzaville)",
			"Congo",
			"Costa Rica",
			"Cote d'Ivoire",
			"Croatia",
			"Cuba",
			"Cyprus",
			"Czech Republic",
			"Denmark",
			"Djibouti",
			"Dominica",
			"Dominican Republic",
			"East Timor (Timor Timur)",
			"Ecuador",
			"Egypt",
			"El Salvador",
			"Equatorial Guinea",
			"Eritrea",
			"Estonia",
			"Ethiopia",
			"Fiji",
			"Finland",
			"France",
			"Gabon",
			"Gambia, The",
			"Georgia",
			"Germany",
			"Ghana",
			"Greece",
			"Grenada",
			"Guatemala",
			"Guinea",
			"Guinea-Bissau",
			"Guyana",
			"Haiti",
			"Honduras",
			"Hungary",
			"Iceland",
			"India",
			"Indonesia",
			"Iran",
			"Iraq",
			"Ireland",
			"Israel",
			"Italy",
			"Jamaica",
			"Japan",
			"Jordan",
			"Kazakhstan",
			"Kenya",
			"Kiribati",
			"Korea, North",
			"Korea, South",
			"Kuwait",
			"Kyrgyzstan",
			"Laos",
			"Latvia",
			"Lebanon",
			"Lesotho",
			"Liberia",
			"Libya",
			"Liechtenstein",
			"Lithuania",
			"Luxembourg",
			"Macedonia",
			"Madagascar",
			"Malawi",
			"Malaysia",
			"Maldives",
			"Mali",
			"Malta",
			"Marshall Islands",
			"Mauritania",
			"Mauritius",
			"Mexico",
			"Micronesia",
			"Moldova",
			"Monaco",
			"Mongolia",
			"Morocco",
			"Mozambique",
			"Myanmar",
			"Namibia",
			"Nauru",
			"Nepa",
			"Netherlands",
			"New Zealand",
			"Nicaragua",
			"Niger",
			"Nigeria",
			"Norway",
			"Oman",
			"Pakistan",
			"Palau",
			"Panama",
			"Papua New Guinea",
			"Paraguay",
			"Peru",
			"Philippines",
			"Poland",
			"Portugal",
			"Qatar",
			"Romania",
			"Russia",
			"Rwanda",
			"Saint Kitts and Nevis",
			"Saint Lucia",
			"Saint Vincent",
			"Samoa",
			"San Marino",
			"Sao Tome and Principe",
			"Saudi Arabia",
			"Senegal",
			"Serbia and Montenegro",
			"Seychelles",
			"Sierra Leone",
			"Singapore",
			"Slovakia",
			"Slovenia",
			"Solomon Islands",
			"Somalia",
			"South Africa",
			"Spain",
			"Sri Lanka",
			"Sudan",
			"Suriname",
			"Swaziland",
			"Sweden",
			"Switzerland",
			"Syria",
			"Taiwan",
			"Tajikistan",
			"Tanzania",
			"Thailand",
			"Togo",
			"Tonga",
			"Trinidad and Tobago",
			"Tunisia",
			"Turkey",
			"Turkmenistan",
			"Tuvalu",
			"Uganda",
			"Ukraine",
			"United Arab Emirates",
			"United Kingdom",
			"United States",
			"Uruguay",
			"Uzbekistan",
			"Vanuatu",
			"Vatican City",
			"Venezuela",
			"Vietnam",
			"Yemen",
			"Zambia",
			"Zimbabwe"
		);
	}
}