<?php

/*
Plugin Name: SMSalert
Plugin URI: https://localhost:4444/
Description: A wordpress plugin for sending SMS alerts using Twilio
Version:  0.0.1
Author: Vaisor
*/
// [...]

require_once( __DIR__ . '/lib/Twilio/autoload.php');
use Twilio\Rest\Client;

// [...]
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

require_once(__DIR__ . '/Settings.php');
class SMSalert {
    public static $pluginName = "SMSalert";
    function __construct()
    {
        //add_action( 'init', [$this, 'scheduleSending'] );
        //add_action( 'send_sms_reminder', [$this, 'sendSMSReminder'] );
        add_action("admin_menu", [$this , "registerSendexSmsPage"]);// [...]

        // calls the sending function whenever we try sending messages.
        add_action( 'admin_init', [$this , "send_message"] );
    }
    public function scheduleSending()
    {
        wp_schedule_event( wp_date( 'U', strtotime( gmdate( 'Y-m-d 12:00', strtotime( 'tomorrow' ) ) . ( get_option( 'gmt_offset' ) > 0 ? '-' : '+' ) . absint( get_option( 'gmt_offset' ) ) . ' hours' ) ), 'daily', 'send_sms_reminder' );
    }
    public function sendSMSReminder()
    {

    }

    public function send_message()
    {
        if (!isset($_POST["send_sms_message"])) {
            return;
        }

        $to        = (isset($_POST["numbers"])) ? $_POST["numbers"] : "";
        $sender_id = (isset($_POST["sender"]))  ? $_POST["sender"]  : "";
        $message   = (isset($_POST["message"])) ? $_POST["message"] : "";

        //gets our api details from the database.
        /*if (is_array($api_details) and count($api_details) != 0) {
            $TWILIO_SID = $api_details["api_sid"];
            $TWILIO_TOKEN = $api_details["api_auth_token"];
        }*/
        $TWILIO_SID = get_option('smsalert_api_sid');
        $TWILIO_TOKEN = get_option('smsalert_auth_token');

        try {
            $client = new Client($TWILIO_SID, $TWILIO_TOKEN);
            $response = $client->messages->create(
                $to,
                array(
                    "from" => $sender_id,
                    "body" => $message
                )
            );
            self::DisplaySuccess();
        } catch (Exception $e) {
            self::DisplayError($e->getMessage());
        }
    }
    public static function adminNotice($message, $status = true) {
        $class =  ($status) ? "notice notice-success" : "notice notice-error";
        $message = __( $message, "sample-text-domain" );
        printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) ); 
    }

    public static function DisplayError($message = "Aww!, there was an error.") {
        add_action( 'adminNotices', function() use($message) {
            self::adminNotice($message, false);
        });
    }

    public static function DisplaySuccess($message = "Successful!") {
        add_action( 'adminNotices', function() use($message) {
            self::adminNotice($message, true);
        });
    }

    public function registerSendexSmsPage()
    {
        // Create our settings page as a submenu page.
        add_submenu_page(
            "tools.php", // parent slug
            __("SENDEX SMS PAGE", SMSalert::$pluginName . "-sms"), // page title
            __("SENDEX SMS", SMSalert::$pluginName . "-sms"), // menu title
            "manage_options", // capability
            SMSalert::$pluginName . "-sms", // menu_slug
            [$this, "displaySendexSmsPage"] // callable function
        );
    }
    
    public function displaySendexSmsPage()
    {
        include_once "ToolsTester.php";
    }
}
new SMSalert();
new Settings();