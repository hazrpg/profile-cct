<?php 


// add_action('profile_cct_form','profile_cct_name_field_shell',10,2);

function profile_cct_name_field_shell( $action, $options=null ) {
	
	if( is_object($action) ):
		$post = $action;
		$action = "display";
		$options = $options['args']['options'];
		$data = $options['args']['data'];
	endif;
	
	$field = Profile_CCT::get_object(); // prints "Creating new instance."

	$default_options = array(
		'type'=>'name',
		'label'=>'name',	
		'description'=>'',
		'show'=>array('title','middle','suffix'),
		'show_fields'=>array('title','middle','suffix')
		);
		
	$options = (is_array($options) ? array_merge( $default_options, $options ): $default_options );
	
	$field->start_field($action,$options);
	
	profile_cct_name_field($data,$options);
	
	$field->end_field( $action, $options );
	
}
function profile_cct_name_field( $data, $options ){
	
	extract( $options );
	
	$field = Profile_CCT::get_object();
	
	$show = (is_array($show) ? $show : array());
	$field->input_field( array( 'field_id'=>'title','label'=>'Title', 'size'=>2, 'value'=>$data['title'], 	'type'=>'text', 'show' => in_array("title",$show)) );
	$field->input_field( array( 'field_id'=>'first','label'=>'First', 'size'=>14, 'value'=>$data['first'], 	'type'=>'text', ));
	$field->input_field( array( 'field_id'=>'middle','label'=>'Middle', 'size'=>3,'value'=>$data['middle'], 'type'=>'text', 'show' => in_array("middle",$show) ));
	$field->input_field( array( 'field_id'=>'last','label'=>'Last', 'size'=>19, 'value'=>$data['last'], 	'type'=>'text', ));
	$field->input_field( array( 'field_id'=>'suffix', 'label'=>'Suffix','size'=>3, 'value'=>$data['suffix'],'type'=>'text',  'show' => in_array("suffix",$show)));
	
}

