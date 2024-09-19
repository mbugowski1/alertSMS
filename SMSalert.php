<?php

/*
Plugin Name: SMSalert
Plugin URI: https://localhost:4444/
Description: A wordpress plugin for sending SMS alerts using Twilio
Version:  0.0.1
Author: Vaisor
*/
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

require_once(__DIR__ . '/Settings.php');
class SMSalert {
    public static $pluginName = "SMSalert";
}
new Settings();