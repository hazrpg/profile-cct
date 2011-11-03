<?php 

function profile_cct_address_field_shell( $action, $options ) {
	
	if( is_object($action) ):
		$post = $action;
		$action = "display";
		$options = $options['args']['options'];
		$data = $options['args']['data'];
	endif;
	
	$field = Profile_CCT::get_object(); // prints "Creating new instance."
	
	$default_options = array(
		'type' => 'address',
		'label'=>'address',
		'description'=>'',
		'show'=>array('building-address','room-number','street-1','street-2','city','postal','province','country'),
		'show_fields'=>array('building-address','room-number','street-1','street-2','city','postal','province','country')
		);
	
	$options = (is_array($options) ? array_merge($default_options,$options): $default_options );
	
	$field->start_field($action,$options);
	
	profile_cct_address_field($data,$options);
	
	$field->end_field( $action, $options );

}
function profile_cct_address_field( $data, $options ){

	extract( $options );
	$show = (is_array($show) ? $show : array());
	$field = Profile_CCT::get_object();
	
	$field->input_field( array( 'field_id'=>'building-address', 'label'=>'Building Name', 'size'=>35, 'value'=>$data['building-address'], 'type'=>'text', 'show' => in_array("building-address",$show) ) );
	$field->input_field( array( 'field_id'=>'room-number','label'=>'Room Number', 'size'=>35, 'value'=>$data['room-number'], 'type'=>'text', 'show' => in_array("room-number",$show)) );
	
	$field->input_field( array( 'field_id'=>'street-1','label'=>'Street Address', 'size'=>74, 'value'=>$data['street-1'], 'type'=>'text', 'show' => in_array("street-1",$show)) );
	$field->input_field( array( 'field_id'=>'street-2','label'=>'Address Line 2', 'size'=>74, 'value'=>$data['street-2'], 'type'=>'text', 'show' => in_array("street-2",$show)) );
	
	$field->input_field( array( 'field_id'=>'city','label'=>'City', 'size'=>35, 'value'=>$data['city'], 'type'=>'text', 'show' => in_array("city",$show)) );
	$field->input_field( array( 'field_id'=>'postal','label'=>'Postal / Zip Code', 'size'=>35, 'value'=>$data['postal'], 'type'=>'text', 'show' => in_array("postal",$show)) );
	
	$field->input_field( array( 'field_id'=>'province','label'=>'Province / State /  Region', 'size'=>35, 'value'=>$data['province'], 'type'=>'text', 'show' => in_array("province",$show)) );
	$field->input_field( array( 'field_id'=>'country','label'=>'Country', 'size'=>35, 'value'=>$data['country'], 'all_fields'=>profile_cct_list_of_countries(), 'type'=>'select', 'show' => in_array("country",$show)) );

}


function profile_cct_list_of_countries() {

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