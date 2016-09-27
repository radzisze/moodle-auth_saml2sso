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
 * @package auth_saml2_auth
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
class auth_plugin_saml2_auth extends auth_plugin_base {

    // The name of the component. Used by the configuration.
    const COMPONENT_NAME = 'auth_saml2_auth';
    const LEGACY_COMPONENT_NAME = 'auth/saml2_auth';

    /**
     * Config vars
     * @var string
     */
    public $defaults = array(
        'autocreate' => 0,
        'dual_login' => 1,
        'entityid' => '',
        'idpattr' => '',
        'logout_url_redir' => '',
        'mdlattr' => 'username',
        'sp_path' => '',
        'edit_profile' => 0,
    );

    /**
     * Mapping vars
     * @var string
     */
    public static $stringMapping = array(
        'email' => 'email',
        'idnumber' => 'idnumber',
        'firstname' => 'givenName',
        'lastname' => 'surname'
    );

    /**
     * Constructor
     */
    public function __construct() {
        $this->authtype = 'saml2_auth';
        $componentName = (array) get_config(self::COMPONENT_NAME);
        $legacyComponentName = (array) get_config(self::LEGACY_COMPONENT_NAME);
        $this->config = (object) array_merge($this->defaults, $componentName, $legacyComponentName);
        $this->mapping = (object) self::$stringMapping;
    }

    /**
     * @global string $SESSION
     * @return type
     */
    public function loginpage_hook() {
        global $SESSION;

        // Check if dual login is enabled.
        // Can bypass IdP auth.
        // To bypass IdP auth, go to <moodle-url>/login/index.php?saml=off
        if ((int) $this->config->dual_login) {
            $saml = optional_param('saml', 'on', PARAM_TEXT);
            if ($saml == 'off' || isset($SESSION->saml)) {
                $SESSION->saml = 'off';
                return;
            }
        }

        $this->saml2_login();
    }

    /**
     * Called when user hit the logout button
     * Will get the URL from the logged in IdP and then redirect to config
     * logout URL set in plugin config
     */
    public function logoutpage_hook() {
        require_once($this->config->sp_path . 'lib/_autoload.php');

        $auth = new SimpleSAML_Auth_Simple($this->config->entityid);

        $samlLogout = $auth->getLogoutURL($this->config->logout_url_redir);

        require_logout();

        redirect($samlLogout);
    }

    /**
     * Do all the magic during login procedure
     * @global type $DB
     * @global type $USER
     * @global type $CFG
     */
    public function saml2_login() {
        global $DB, $USER, $CFG;
        require_once($this->config->sp_path . 'lib/_autoload.php');

        $auth = new SimpleSAML_Auth_Simple($this->config->entityid);
        $auth->requireAuth();
        $attributes = $auth->getAttributes();

        // Email attribute
        // Here we insure that e-mail returned from identity provider (IdP) is catched
        // whenever it is email or mail attribute name
        $attributes[$this->mapping->email][0] = isset($attributes['email']) ? trim(strtolower($attributes['email'][0])) : trim(strtolower($attributes['mail'][0]));

        // First name attribute
        // Because Moodle has firstname and lastname in distinct fields, we ensure
        //that returned name's person has the apropriated format.
        $attributes[$this->mapping->firstname][0] = strstr($attributes['cn'][0], " ", true) ? mb_strtoupper(trim(strstr($attributes['cn'][0], " ", true)), "UTF-8") : $attributes['cn'][0];

        // Last name attribute
        $attributes[$this->mapping->lastname][0] = strstr($attributes['cn'][0], " ") ? mb_strtoupper(trim(strstr($attributes['cn'][0], " ")), "UTF-8") : $attributes['cn'][0];

        // User Id returned from IdP
        // Will be used to get user from our Moodle database if exists
        $uid = $attributes[$this->config->idpattr][0];

        // Now we check if the Id returned from IdP exists in our Moodle database
        $isuser = $DB->get_record('user', array($this->config->mdlattr => $uid));

        $newuser = false;
        if (!$isuser) {
            // Verify if user can be created
            if ((int) $this->config->autocreate) {
                // Insert new user
                $isuser = create_user_record($uid, '', 'saml2_auth');
                $newuser = true;
            } else {
                //If autocreate is not allowed, show error
                $this->error_page(get_string('nouser', 'auth_saml2_auth') . $uid);
            }
        }

        // We expected that here we have a existing user or a new one
        if ($isuser) {
            $USER = get_complete_user_data('username', $isuser->username);
        } else {
            $this->error_page(get_string('error_create_user', 'auth_saml2_auth'));
        }

        // Map fields that we need to update on every login
        $mapconfig = get_config('auth/saml2_auth');
        $allkeys = array_keys(get_object_vars($mapconfig));
        $touched = false;
        foreach ($allkeys as $key) {
            if (preg_match('/^field_updatelocal_(.+)$/', $key, $match)) {
                $field = $match[1];
                if (!empty($mapconfig->{'field_map_' . $field})) {
                    $attr = $mapconfig->{'field_map_' . $field};
                    $updateonlogin = $mapconfig->{'field_updatelocal_' . $field} === 'onlogin';

                    if ($newuser || $updateonlogin) {
                        $USER->$field = $attributes[$attr][0];
                        $touched = true;
                    }
                }
            }
        }

        if ($touched) {
            require_once($CFG->dirroot . '/user/lib.php');
            user_update_user($USER, false, false);
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
    public function auth_plugin_saml2_auth() {
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
    function user_login($username, $password) {
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
    function user_update_password($user, $newpassword) {
        return false;
    }

    /**
     * 
     * @return boolean
     */
    function prevent_local_passwords() {
        return true;
    }

    /**
     * Returns true if this authentication plugin is 'internal'.
     *
     * @return bool
     */
    function is_internal() {
        return false;
    }

    /**
     * Returns true if this authentication plugin can change the user's
     * password.
     *
     * @return bool
     */
    function can_change_password() {
        return false;
    }

    /**
     * Returns the URL for changing the user's pw, or empty if the default can
     * be used.
     *
     * @return moodle_url
     */
    function change_password_url() {
        return null;
    }

    /**
     * Returns true if plugin allows resetting of internal password.
     *
     * @return bool
     */
    function can_reset_password() {
        return false;
    }

    /**
     * Returns true if plugin can be manually set.
     *
     * @return bool
     */
    function can_be_manually_set() {
        return false;
    }

    /**
     * @return type
     */
    function can_edit_profile() {
        return (int) $this->config->edit_profile;
    }

    /**
     * Prints a form for configuring this authentication plugin.
     *
     * This function is called from admin/auth.php, and outputs a full page with
     * a form for configuring this plugin.
     *
     * @param array $page An object containing all the data for this page.
     */
    function config_form($config, $err, $user_fields) {
        global $CFG, $OUTPUT;
        $config = (object) array_merge($this->defaults, (array) $config);
        include("settings.html");
    }

    /**
     * Processes and stores configuration data for this authentication plugin.
     */
    function process_config($config) {
        foreach ($this->defaults as $key => $value) {
            set_config($key, $config->$key, 'auth_saml2_auth');
        }
        return true;
    }

    /**
     * @global type $PAGE
     * @global type $OUTPUT
     * @global type $SITE
     * @param type $msg
     */
    public function error_page($msg) {
        global $PAGE, $OUTPUT, $SITE;

        require_once($this->config->sp_path . 'lib/_autoload.php');

        $auth = new SimpleSAML_Auth_Simple($this->config->entityid);

        $samlLogout = $auth->getLogoutURL($this->config->logout_url_redir);

        $PAGE->set_course($SITE);
        $PAGE->set_url('/');
        echo $OUTPUT->header();
        echo $OUTPUT->box($msg);
        echo $OUTPUT->box('<a href="' . $samlLogout . '">' . get_string('label_logout', 'auth_saml2_auth') . '</a>');
        echo $OUTPUT->footer();
        exit;
    }

}
