<?php
class Settings {
    function __construct() {
        // Add setting menu item
        add_action("admin_menu", [$this , "addAdminOption"]);
        // Saves and update settings
        add_action("admin_init", [$this , 'adminSettingsSave']);
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
    /**
     * Registers and Defines the necessary fields we need.
     *  @since    1.0.0
     */
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

    /**
     * Displays the settings sub header
     *  @since    1.0.0
     */
    public function sectionText()
    {
        echo '<h3 style="text-decoration: underline;">Edit api details</h3>';
    }

    /**
     * Renders the sid input field
     *  @since    1.0.0
     */
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

    /**
     * Renders the auth_token input field
     *
     */
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

    /**
     * Sanitizes all input fields.
     *
     */
    public function pluginOptionsValidate($input)
    {
        $newinput["api_sid"] = trim($input["api_sid"]);
        $newinput["api_auth_token"] = trim($input["api_auth_token"]);
        return $newinput;
    }
}
