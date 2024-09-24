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
        add_action( 'init', [$this, 'scheduleSending'] );
        add_action( 'send_sms_reminder', [$this, 'sendSMSReminder'] );
        add_action("admin_menu", [$this , "registerSendexSmsPage"]);// [...]

        // calls the sending function whenever we try sending messages.
        add_action( 'admin_post_submit_sms_test_form', [$this , "send_test_message"] );
    }
    public function scheduleSending()
    {
        if ( false == wp_get_scheduled_event( 'send_sms_reminder' ) ) {
            wp_schedule_event( wp_date( 'U', strtotime( gmdate( 'Y-m-d 12:00', strtotime( 'tomorrow' ) ) . ( get_option( 'gmt_offset' ) > 0 ? '-' : '+' ) . absint( get_option( 'gmt_offset' ) ) . ' hours' ) ), 'daily', 'send_sms_reminder' );
        }
    }
    public function sendSMSReminder()
    {
        $days_before = intval(get_option('smsalert_days_before') ?? -1);
        $message = get_option('smsalert_message') ?? "Your rental is due in " . $days_before . " days. Please return the item(s) to avoid penalties.";
        if ($days_before < 0) return;
        $orders = wc_get_orders(
            array(
                'limit'		=> -1,
                'status'	=> array( 'wc-processing' ),
            )
        );
        if ( empty( $orders ) ) return;
        foreach ( $orders as $order ) {
            $order_id = $order->get_id();
			$order_items = $order->get_items();
            if ( empty( $order_items ) ) continue;
            foreach ( $order_items as $order_item )
            {
                $order_item_type = $order_item->get_type();
                if ( 'line_item' != $order_item_type ) continue;
                $rent_from = $order_item->get_meta( 'wcrp_rental_products_rent_from' );
                if ( empty( $rent_from ) ) continue;
                if ($order_item->get_meta( 'wcrp_rental_products_returned' ) == 'yes') continue;

                $rent_to = $order_item->get_meta( 'wcrp_rental_products_rent_to' );
                $return_days_threshold = $order_item->get_meta( 'wcrp_rental_products_return_days_threshold' );
                $rent_to_inc_return_days = gmdate( 'Y-m-d', strtotime( $rent_to . ' + ' . $return_days_threshold . ' days' ) );
                $rent_to_inc_return_days_minus_days_before = gmdate( 'Y-m-d', strtotime( $rent_to_inc_return_days . ' - ' . $days_before . ' days' ) );

                $current_date = wp_date( 'Y-m-d' );
                error_log($rent_to_inc_return_days_minus_days_before);
                if($current_date != $rent_to_inc_return_days_minus_days_before) continue;
                $this->send_message($order->get_billing_phone(), $message);
                break;
            }
        }
    }

    public function send_message($to, $message)
    {

        $sender_id = get_option('smsalert_phone_number');
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
    public function send_test_message()
    {
        $to        = (isset($_POST["numbers"])) ? $_POST["numbers"] : "";
        $message   = (isset($_POST["message"])) ? $_POST["message"] : "";

        $this->send_message($to, $message);
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
            __("SMS PAGE", SMSalert::$pluginName . "-sms"), // page title
            __("SMS", SMSalert::$pluginName . "-sms"), // menu title
            "manage_options", // capability
            SMSalert::$pluginName . "-sms", // menu_slug
            [$this, "displaySmsTestPage"] // callable function
        );
    }
    
    public function displaySmsTestPage()
    {
        include_once "ToolsTester.php";
    }
}
new SMSalert();
new Settings();