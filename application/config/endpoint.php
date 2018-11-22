<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if(file_exists(FCPATH.'local.txt')) {
	// Local Server
	define('master',   			"https://202.158.125.181:4599/");
	define('master-front',  	"https://202.158.125.181:4566/");
    $config['emailsender']	= "noreplyplease@service-division.com";
    $config['emailfrom']	= "E-Logistic System";
}elseif(file_exists(FCPATH.'dev.txt')) {
	// Development Server
	define('master',   			"https://202.158.125.181:4599/");
	define('master-front',  	"https://202.158.125.181:4566/");
    $config['emailsender']	= "noreplyplease@service-division.com";
    $config['emailfrom']	= "E-Logistic System";
}elseif(file_exists(FCPATH.'stg.txt')) {
	// Staging Server
	define('master',   			"https://202.158.125.181:4599/");
	define('master-front',  	"https://202.158.125.181:4566/");
    $config['emailsender']	= "noreplyplease@service-division.com";
    $config['emailfrom']	= "E-Logistic System";
}else{
	// Production Server
	define('master',   			"https://202.158.125.181:4599/");
	define('master-front',  	"https://202.158.125.181:4566/");
    $config['emailsender']	= "noreplyplease@service-division.com";
    $config['emailfrom']	= "E-Logistic System";
}
//Frontend Website
$config['frontend'] = constant('master-front');

// User photo uploads directory -> use in User.php
$config['storage_user_photo'] = constant('master').'uploads/';

// Endpoint for user basic info -> use in oauth/Resource.php
$config['user_info'] = constant('master').'user/info';

// Endpoint for user login -> use in oauth/PasswordCredential.php
$config['user_login'] = constant('master').'sign/login';

// Endpoint for user logout -> use in Sign.php
$config['user_logout'] = constant('master').'oauth/revokeToken';

// Endpoint for handling logout on net account -> use in Sign.php
$config['netaccount_logout'] = constant('master-front').'logout';