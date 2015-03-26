<?php

function stripslashes_deep($value) {
  $value = is_array($value) ?
    array_map('stripslashes_deep', $value) :
    stripslashes($value);
  return $value;
}
if (get_magic_quotes_gpc()) {
  $unescaped_post_data = stripslashes_deep($_POST);
} else {
  $unescaped_post_data = $_POST;
}
$form_data = json_decode($unescaped_post_data['data_json']);


/*******************************
  PARSE DATA FROM UNBOUNCE
*******************************/
$email_address 			= $form_data->email[0];
$first_name      = $form_data->first_name[0];
$last_name      = $form_data->last_name[0];
$company_name      = $form_data->company_name[0];
$phone_number      = $form_data->phone_number[0];


//$page_id			= $_POST['page_id'];						//Unbounce Page ID
//$page_url 			= $_POST['page_url'];						//Unbounce Page URL
//$variant 			= $_POST['variant'];     					//Unbounce Page Variant   


/*****************************************
 Testing values
*****************************************/
//$email_adress= 'my@email.com';
//$first_name = 'Carlos';
//$last_name = 'Sura';
//$company_name = 'My Company';
//$phone_number = '3159442556';


/*****************************************
 This value can be changed depending
 on the account type, since is not
 in the form.
*****************************************/
$contact_type = 'Merchant';	


/*****************************************
 These are the fields going to be posted
*****************************************/
$curl_post_data['email'] 			= $email_address;					// Lead Email Address	
$curl_post_data['firstName']       = $first_name;         // Lead First Name
$curl_post_data['lastName']       = $last_name;         // Lead Last Name
$curl_post_data['contactType']	  = $contact_type;	    // Lead Contact Type
$curl_post_data['companyName']	  = $company_name;      // Lead Company Name
//$curl_post_data['dba']	  = $company_name;
//$curl_post_data['companyMainPhone']	  = $phone_number;
$curl_post_data['businessPhone']	  = $phone_number;  // Lead Business Phone

/************************************
  Authentication values for MCAsuite
************************************/
//$service_url = 'http://XXXXXXXX/rest/addDeal/'; // Adds deal
$service_url = 'http://XXXXXXXX/rest/addContact/'; // Adds contact
$app_id = 'XXXXXXXXXXX';
$token = 'XXXXXXXXXXXX';	


 
$curl = curl_init($service_url);
 
 
$headr = array();
$headr[] = 'X_MCASUITE_APP_ID: ' . $app_id;
$headr[] = 'X_MCASUITE_APP_TOKEN: ' . $token;
 
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_post_data);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headr);
$curl_response = curl_exec($curl);
curl_close($curl);
 
// Check out response message, it will be in json format.
echo $curl_response;
 
?>