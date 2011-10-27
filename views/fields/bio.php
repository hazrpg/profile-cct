<?php 

function profile_cct_bio_field_shell($action,$options) {
	
	$field = Profile_CCT::set(); // prints "Creating new instance."
	
	
	$default_options = array(
		'type' => 'bio',
		'label'=>'bio',
		'description'=>'',
		);
	$options = (is_array($options) ? array_merge($options,$default_options): $default_options );
	
	
	$field->start_field('bio',$action,$options);
	
	profile_cct_bio_field($data,$options);
	
	$field->end_field($options);
	
}
function profile_cct_bio_field( $data, $options ){
	extract( $options );
	$field = Profile_CCT::set();

	$field->input_field( array( 'label'=>'', 'size'=>25, 'row'=>2, 'cols'=>20, 'value'=>$data['bio'], 'type'=>'textarea') );



}
