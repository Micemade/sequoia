<?php
if( !isset($_GET['activated'])) {
header("Content-type: text/css; charset: UTF-8");
};
global $of_sequoia;

$custom_login = $of_sequoia['custom_login_page'];

if( $custom_login ) {

	$s_url		= get_stylesheet_directory();
	$logo		= $of_sequoia['site_logo'] ? $of_sequoia['site_logo'] : $s_url.'/img/logo.png';
	$loginback	= $of_sequoia['admin_back_image'] ? $of_sequoia['admin_back_image'] : $s_url.'/img/loginback.png';


	echo '
	.login h1 a {
		margin-top: 55px;
		background-image: none, url("'.$logo.'");
		background-position: center top;
		background-repeat: no-repeat;
		background-size: contain;
		height: 100px;
		width: auto;
	}

	body.login {
		background-image: url("'.$loginback.'");
		background-size: cover;
		background-position: center;
	}
	';

	echo '
	body.interim-login {
		background-image: none!important;
	}
	.interim-login #login_error, .interim-login.login .message{
		margin-right: auto;
		margin-left: auto;
		margin-top: 0;
		background-size: 200px auto;
		height: 100px;
	}

	.mobile #login .message, .mobile #login form, .mobile #login_error, 
	.mobile #login #backtoblog, .mobile #login #nav {
		margin-left: auto;
		width: 250px;
	}

	 .login.mobile h1, .login.mobile h1 a{
		padding-top: 0;
		margin-top: 0;
	}

	#login {
		width: 100%;
		padding: 0;

	}

	.login h1 {
		padding-top: 55px;
	}
	.login.interim-login h1 {
		padding-top: 20px;
	}


	.interim-login.login h1 a {
		margin-top: 0;
	}

	#loginform, .login #login_error, .login #nav, .login .message {
		width: 300px;
		margin-right: auto;
		margin-left: auto;
		margin-top: 0;
		padding: 22px;
		box-shadow: none;
		border: none;	
		background-color: rgba(255,255,255,0.7);
	}

	.login label, .login #backtoblog a, .login #nav a {
		color: #000;
	}

	.login #login_error strong{
		color:red;

	}

	.login .message {
		color: #7ad03a;
	}


	.login #backtoblog {
		display: none;
	}
	';
}
?>