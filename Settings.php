<?php
class Settings {
    function __construct() {
        // Add setting menu item
        //add_action("admin_menu", [$this , "addAdminOption"]);
        // Saves and update settings
        //add_action("admin_init", [$this , 'adminSettingsSave']);

        add_action('woocommerce_settings_tabs_array', [$this, 'createTab'], 50);
        add_action('woocommerce_settings_tabs_smsalert', [$this, 'showOptionsPageCallback']);
        add_action( 'woocommerce_update_options_smsalert', [$this, 'updateOptions'] );
    }
    public function createTab($settings_tabs) {
        $settings_tabs['smsalert'] = 'SMSalert';
        return $settings_tabs;

    }
    public function showOptionsPageCallback()
    {
        woocommerce_admin_fields($this->showOptionsPage());
    }
    public function showOptionsPage()
    {
        $settings = array(
            'section_title' => array(
                'name'     => 'Klucze API',
                'type'     => 'title',
                'desc'     => '',
                'id'       => 'smsalert_api_keys_title'
            ),
            'smsalert_api_sid' => array(
                'name' => 'API SID',
                'type' => 'text',
                'desc' => 'Enter your API SID here',
                'id'   => 'smsalert_api_sid'
            ),
            'smsalert_api_auth_token' => array(
                'name' => 'Auth Token',
                'type' => 'text',
                'desc' => 'Enter your Auth Token here',
                'id'   => 'smsalert_auth_token'
            ),
            'smsalert_api_phone_number' => array(
                'name' => 'Phone Number',
                'type' => 'text',
                'desc' => 'Enter phone number to use for sending SMS',
                'id'   => 'smsalert_phone_number'
            ),
            'smsalert_days_before_field' => array(
                'name' => 'Days before',
                'type' => 'text',
                'desc' => 'How many days before return should the SMS be sent (includes return days threshold)',
                'id'   => 'smsalert_days_before'
            ),
            'smsalert_message_field' => array(
                'name' => 'Message',
                'type' => 'text',
                'desc' => 'What message should be sent',
                'id'   => 'smsalert_message'
            ),
            'section_end' => array(
                'type' => 'sectionend',
                'id'   => 'smsalert_api_keys_section_end'
            )
        );
        return $settings;
    }
    public function updateOptions()
    {
        woocommerce_update_options($this->showOptionsPage());
    }

    public function ShowSettingsPage() {
        ?>
        <form method="POST" action='options.php'>
        <?php
                settings_fields(SMSalert::$pluginName);
                do_settings_sections('sendex-settings-page');
                submit_button();  
        ?>
        </form>
        <?php
    }
    public function addAdminOption()
    {
        add_options_page(
            "SMSalert options page",
            "SMSalert",
            "manage_options",
            SMSalert::$pluginName,
            [$this, "ShowSettingsPage"]
        );
    }
    public function adminSettingsSave()
    {
        register_setting(
            "general",
            SMSalert::$pluginName,
            [$this, "pluginOptionsValidate"]
        );
        add_settings_section(
            "sendex_main",
            "Main Settings",
            [$this, "sectionText"],
            "sendex-settings-page"
        );
        add_settings_field(
            "api_sid",
            "API SID",
            [$this, "settingSid"],
            "sendex-settings-page",
            "sendex_main"
        );
        add_settings_field(
            "api_auth_token",
            "API AUTH TOKEN",
            [$this, "settingToken"],
            "sendex-settings-page",
            "sendex_main"
        );
    }
    public function sectionText()
    {
        echo '<h3 style="text-decoration: underline;">Edit api details</h3>';
    }
    public function settingSid()
    {
        $options = get_option(SMSalert::$pluginName);
        $api_sid = $options['api_sid'] ?? "";
        echo "
            <input
                id='" . SMSalert::$pluginName . "[api_sid]'
                name='" . SMSalert::$pluginName . "[api_sid]'
                size='40'
                type='text'
                value='" . $api_sid . "'
                placeholder='Enter your API SID here'
            />
        ";
    }
    public function settingToken()
    {
        $options = get_option(SMSalert::$pluginName);
        $api_auth_token = $options['api_auth_token'] ?? "";
        echo "
            <input
                id='" . SMSalert::$pluginName . "[api_auth_token]'
                name='" . SMSalert::$pluginName . "[api_auth_token]'
                size='40'
                type='text'
                value='{$api_auth_token}'
                placeholder='Enter your API AUTH TOKEN here'
            />
        ";
    }
    public function pluginOptionsValidate($input)
    {
        $newinput["api_sid"] = trim($input["api_sid"]);
        $newinput["api_auth_token"] = trim($input["api_auth_token"]);
        return $newinput;
    }
}
