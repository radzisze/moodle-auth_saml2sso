<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package auth_saml2sso
 * @author Daniel Miranda <daniellopes at gmail.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 * Parts of the code was original made for another moodle plugin available at
 * https://moodle.org/plugins/auth_saml2
 */
defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/authlib.php');

/**
 * Plugin for authentication using SimpleSAMLphp Service Provider
 * For SimpleSAMLphp instructions, go to https://simplesamlphp.org/
 */
class auth_plugin_saml2sso extends auth_plugin_base {

    /**
     * The name of the component. Used by the configuration.
     */
    const COMPONENT_NAME = 'auth_saml2sso';
    /**
     * Legacy name of the component.
     */
    const LEGACY_COMPONENT_NAME = 'auth/saml2sso';

    /**
     * Config vars
     * @var string
     */
    public $defaults = array(
        'sp_path' => '',
        'dual_login' => 1,
        'single_signoff' => 1,
        'idpattr' => '',
        'moodle_mapping' => 'username',
        'autocreate' => 0,
        'authsource' => '',
        'logout_url_redir' => '',
        'edit_profile' => 0,
        'field_idp_fullname' => 1,
        'field_idp_firstname' => 'cn',
        'field_idp_lastname' => 'cn'
    );

    /**
     * Mapping vars
     * @var string
     */
    public static $stringmapping = array(
        'email' => 'email',
        'idnumber' => 'idnumber',
        'firstname' => 'givenName',
        'lastname' => 'surname'
    );

    /**
     * Constructor
     */
    public function __construct() {
        $this->authtype = 'saml2sso';
        $componentName = (array) get_config(self::COMPONENT_NAME);
        $legacyComponentName = (array) get_config(self::LEGACY_COMPONENT_NAME);
        $this->config = (object) array_merge($this->defaults, $componentName, $legacyComponentName);
        if (empty($this->config->authsource)) {
            // Uses old entityid key
            $this->config->authsource = $this->config->entityid;
            debugging('authsource config key empty, using old entityid key', DEBUG_DEVELOPER);
        }
        $this->mapping = (object) self::$stringmapping;
    }

    /**
     * Load SimpleSAMLphp library autoloader
     */
    private function getsspauth() {
        require_once $this->config->sp_path . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . '_autoload.php';

        if (class_exists('\SimpleSAML\Auth\Simple')) {
            return new \SimpleSAML\Auth\Simple($this->config->authsource);
        }
        // Backward compatibility, will be dropped
        // since any version < 1.15.3 is insecure
        return new SimpleSAML_Auth_Simple($this->config->authsource);
    }

    /**
     * Makes the saml2 plugin appear as a idsp on login screen
     * @param string $wantsurl
     * @return array
     * Added by Praxis
     */
    function loginpage_idp_list($wantsurl) {
        $url = '?saml=on';

        if (!empty($this->config->button_url)) {
            $button_path = new moodle_url($this->config->button_url);
        }
        else {
            $button_path =  new moodle_url('/auth/saml2sso/pix/login-btn.png');
        }
        $button_name = 'SAML Login';
        if (!empty($this->config->button_name)) {
            $button_name = new moodle_url($this->config->button_name);
        }

        return [[
            'url' => new moodle_url($url),
            'name' => $button_name,
            'iconurl' => $button_path
        ]];
    }

    /**
     * @global string $SESSION
     * @return type
     */
    public function loginpage_hook() {
        global $SESSION, $CFG;

        $saml = optional_param('saml', 'undefined', PARAM_TEXT);

        // If saml=off, go to default login page regardless any other
        // settings. Useful to administrators to recover from misconfiguration
        if ($saml == 'off'
                || (!empty($SESSION->saml) && $SESSION->saml == 'off')) {
            $SESSION->saml = 'off';
            return;
        }

        // If dual login is disabled, the user is redirect to the IdP
        if (!$this->config->dual_login || $saml == 'on') {
            $SESSION->saml='on';
            $this->saml2_login();
        }
        else {
            $SESSION->saml = 'off';
            return;
        }
    }

    /**
     * Called when user hit the logout button
     * Will get the URL from the logged in IdP if Single Sign Off is setted
     * and then redirect to config logout URL setted up in plugin config
     * If URL is invalid or empty, redirect to Moodle main page
     */
    public function logoutpage_hook() {
        global $CFG, $USER;

        if ($USER->auth != $this->authtype) {
            // SingleLogOut must not be called for user handled by other plugins
            return;
        }

        $urllogout = filter_var($this->config->logout_url_redir, FILTER_VALIDATE_URL, FILTER_FLAG_SCHEME_REQUIRED) ? $this->config->logout_url_redir : $CFG->wwwroot;

        // Check if we need to sign off users from IdP too
        if ((int) $this->config->single_signoff ) {
            $auth = $this->getsspauth();

            $urllogout = $auth->getLogoutURL($urllogout);
        }

        require_logout();

        redirect($urllogout);
    }

    /**
     * Do all the magic during login procedure
     * @global type $DB
     * @global type $USER
     * @global type $CFG
     */
    public function saml2_login() {
        global $DB, $USER, $CFG;

        $auth = $this->getsspauth();
        $auth->requireAuth();
        $attributes = $auth->getAttributes();

        /**
         * Email attribute
         * Here we insure that e-mail returned from identity provider (IdP) is catched
         * whenever it is email or mail attribute name
         */
        if (isset($attributes['email'])) {
            $attributes[$this->mapping->email][0] = core_text::strtolower(trim($attributes['email'][0]));
        } else if (isset($attributes['mail'])) {
            $attributes[$this->mapping->email][0] = core_text::strtolower(trim($attributes['mail'][0]));
        } else if (!$this->config->allow_empty_email) {
            $this->error_page(get_string('error_novalidemailfromidp', self::COMPONENT_NAME));
        }
        // if $this->config->allow_empty_email is true and the IdP don't provide an
        // email address, the user is redirect to the profile page to complete

        /**
         * If the field containing the user's name is a unique field, we need to break
         * into firstname and lastname
         */
        if ((int) $this->config->field_idp_fullname) {
            // First name attribute
            $attributes[$this->mapping->firstname][0] = strstr($attributes[$this->config->field_idp_firstname][0], " ", true) ? core_text::strtoupper(trim(strstr($attributes[$this->config->field_idp_firstname][0], " ", true))) : core_text::strtoupper(trim($attributes[$this->config->field_idp_firstname][0]));
            // Last name attribute
            $attributes[$this->mapping->lastname][0] = strstr($attributes[$this->config->field_idp_lastname][0], " ") ? core_text::strtoupper(trim(strstr($attributes[$this->config->field_idp_lastname][0], " "))) : core_text::strtoupper(trim($attributes[$this->config->field_idp_lastname][0]));
        } else {
            $attributes[$this->mapping->firstname][0] = trim($attributes[$this->config->field_idp_firstname][0]);
            $attributes[$this->mapping->lastname][0] = trim($attributes[$this->config->field_idp_lastname][0]);
        }

        /**
         * User Id returned from IdP
         * Will be used to get user from our Moodle database if exists
         * create_user_record lowercases the username, so we need to lower it here.
         * 
         */
        $uid = trim(core_text::strtolower($attributes[$this->config->idpattr][0]));

        // Now we check if the Id returned from IdP exists in our Moodle database
        $isuser = $DB->get_record('user', array($this->config->moodle_mapping => $uid));

        $newuser = false;
        if (!$isuser) {
            // Verify if user can be created
            if ((int) $this->config->autocreate) {
                // Insert new user
                $isuser = create_user_record($uid, '', $this->authtype);
                $newuser = true;
            } else {
                //If autocreate is not allowed, show error
                $this->error_page(get_string('nouser', self::COMPONENT_NAME) . $uid);
            }
        }

        /**
         * We expected that here we have a existing user or a new one
         */
        if ($isuser) {
            $USER = get_complete_user_data('username', $isuser->username);
        } else {
            $this->error_page(get_string('error_create_user', self::COMPONENT_NAME));
        }

        // Map fields that we need to update on every login
        $mapconfig = get_config(self::COMPONENT_NAME);
        $standardkeys = array_keys(get_object_vars($mapconfig));
        $customkeys = $this->get_custom_user_profile_fields();
        $allkeys = array_merge($standardkeys, $customkeys);

        $touched = false;
        foreach ($allkeys as $key) {
            if (preg_match('/^field_updatelocal_(.+)$/', $key, $match)) {
                $field = $match[1];
                if (!empty($mapconfig->{'field_map_' . $field})) {
                    $attr = $mapconfig->{'field_map_' . $field};
                    $updateonlogin = $mapconfig->{'field_updatelocal_' . $field} === 'onlogin';

                    if ($newuser || $updateonlogin) {
                        // Empty attribute must leave untouched.
                        if (isset($attributes[$attr]) && count($attributes[$attr]) > 0) {
                            $USER->$field = $attributes[$attr][0];
                            $touched = true;
                        }
                    }
                }
            }
        }

        if ($touched) {
            require_once($CFG->dirroot . '/user/lib.php');
            user_update_user($USER, false, false);
            profile_save_data($USER);
        }

        // now we get the URL to where user wanna go previouly
        $urltogo = core_login_get_return_url();

        // and pass to login method
        $this->do_login($urltogo);
    }

    /**
     * Do login will set session and cookie to authenticated user
     * @global type $USER
     * @global type $CFG
     * @param type $urltogo
     */
    public function do_login($urltogo) {
        global $USER, $CFG;
        complete_user_login($USER);
        $USER->loggedin = true;
        $USER->site = $CFG->wwwroot;
        set_moodle_cookie($USER->username);

        // If we are not on the page we want, then redirect to it.
        if (qualified_me() !== $urltogo) {
            redirect($urltogo);
            exit;
        }
    }

    /**
     * Old syntax of class constructor for backward compatibility.
     */
    public function auth_plugin_saml2sso() {
        debugging('Use of class name as constructor is deprecated', DEBUG_DEVELOPER);
        self::__construct();
    }

    /**
     * Returns true if the username and password work or don't exist and false
     * if the user exists and the password is wrong.
     *
     * @param string $username The username
     * @param string $password The password
     * @return bool Authentication success or failure.
     */
    public function user_login($username, $password) {
        return false;
    }

    /**
     * Updates the user's password.
     *
     * called when the user password is updated.
     *
     * @param  object  $user        User table object
     * @param  string  $newpassword Plaintext password
     * @return boolean result
     *
     */
    public function user_update_password($user, $newpassword) {
        return false;
    }

    /**
     * 
     * @return boolean
     */
    public function prevent_local_passwords() {
        return true;
    }

    /**
     * Returns true if this authentication plugin is 'internal'.
     *
     * @return bool
     */
    public function is_internal() {
        return false;
    }

    /**
     * Returns true if this authentication plugin can change the user's
     * password.
     *
     * @return bool
     */
    public function can_change_password() {
        return false;
    }

    /**
     * Returns the URL for changing the user's pw, or empty if the default can
     * be used.
     *
     * @return moodle_url
     */
    public function change_password_url() {
        return null;
    }

    /**
     * Returns true if plugin allows resetting of internal password.
     *
     * @return bool
     */
    public function can_reset_password() {
        return false;
    }

    /**
     * Returns true if plugin can be manually set.
     *
     * @return bool
     */
    public function can_be_manually_set() {
        return false;
    }

    /**
     * @return type
     */
    public function can_edit_profile() {
        return (int) $this->config->edit_profile;
    }

    /**
     * @global type $PAGE
     * @global type $OUTPUT
     * @global type $SITE
     * @param type $msg
     */
    public function error_page($msg) {
        global $PAGE, $OUTPUT, $SITE;

        $auth = $this->getsspauth();

        $samlLogout = $auth->getLogoutURL($this->config->logout_url_redir);

        $PAGE->set_course($SITE);
        $PAGE->set_url('/');
        echo $OUTPUT->header();
        echo $OUTPUT->box($msg);
        echo $OUTPUT->box('<a href="' . $samlLogout . '">' . get_string('label_logout', self::COMPONENT_NAME) . '</a>');
        echo $OUTPUT->footer();
        exit;
    }

    /**
     * Test if settings are correct, print info to output.
     * @author Marco Ferrante <marco at csita.unige.it>
     */
    public function test_settings() {
        global $OUTPUT;

        // NOTE: this is not localised intentionally, admins are supposed to understand English at least a bit...

        if (empty($this->config->sp_path)) {
            echo $OUTPUT->notification('SimpleSAMLphp lib path not set', \core\output\notification::NOTIFY_WARNING);
            return;
        }
        if (!empty(getenv('SIMPLESAMLPHP_CONFIG_DIR')) && $this->config->sp_path != dirname(getenv('SIMPLESAMLPHP_CONFIG_DIR'))) {
            echo $OUTPUT->notification('SimpleSAMLphp lib path differs from the environment default ('
                    . dirname(getenv('SIMPLESAMLPHP_CONFIG_DIR'))
                    . '): it could be fine, but check if the library has been updated', \core\output\notification::NOTIFY_INFO);
        }
        if (!file_exists($this->config->sp_path . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . '_autoload.php') || !file_exists($this->config->sp_path . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php')) {
            echo $OUTPUT->notification('SimpleSAMLphp lib path seems to be invalid', \core\output\notification::NOTIFY_WARNING);
            return;
        }

        require $this->config->sp_path . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . '_autoload.php';
        $sspconfig = SimpleSAML_Configuration::getInstance();
        if (version_compare($sspconfig->getVersion(), '1.15.3') < 0) {
            echo $OUTPUT->notification('SimpleSAMLphp lib seems too old ('
                    . $sspconfig->getVersion() . ') and insecure, please upgrade it', \core\output\notification::NOTIFY_WARNING);
        }
        echo $OUTPUT->notification('SimpleSAMLphp version is ' . $sspconfig->getVersion(), \core\output\notification::NOTIFY_INFO);

        @include $this->config->sp_path . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';
        if ($config['store.type'] == 'phpsession') {
            echo $OUTPUT->notification('It seems SimpleSAMLphp uses default PHP session storage, it could be troublesome: switch to another store.type in config.php', \core\output\notification::NOTIFY_INFO);
        }

        $sourcesnames = array_map(function($source){
            return $source->getAuthId();
        }, \SimpleSAML_Auth_Source::getSourcesOfType('saml:SP'));
        if (empty($this->config->authsource) || !in_array($this->config->authsource, $sourcesnames)) {
            echo $OUTPUT->notification('Invalid authentication source. Available sources: '
                    . implode(', ', $sourcesnames), \core\output\notification::NOTIFY_WARNING);
            return;
        }

        if (!empty($this->config->user_directory)) {
            $plugin = get_auth_plugin($this->config->user_directory);
            if (!$plugin) {
                echo $OUTPUT->notification('Invalid directory plugin \''
                        . $this->config->user_directory . '\'', \core\output\notification::NOTIFY_WARNING);
            }
            if (method_exists($plugin, 'test_settings')) {
                $options[$this->config->user_directory] = get_string('pluginname', 'auth_'.$this->config->user_directory);
                $url = new moodle_url('/auth/test_settings.php', array('sesskey'=>sesskey(), 'auth' => $this->config->user_directory));
                echo $OUTPUT->notification('A sync process with \'' . get_string('pluginname', 'auth_'.$this->config->user_directory)
                        . '\' auth plugin is enable. <a href="' . $url
                        . '">Check its configuration</a>.', \core\output\notification::NOTIFY_INFO);

            }
            else {
                echo $OUTPUT->notification('A sync process with \'' . get_string('pluginname', 'auth_'.$this->config->user_directory)
                        . '\' auth plugin is enable. Please check its configuration too.', \core\output\notification::NOTIFY_INFO);
            }
        }

        echo $OUTPUT->notification('Everything seems ok', \core\output\notification::NOTIFY_SUCCESS);
    }

}
