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
 * Admin settings and defaults
 *
 * @package auth_saml2sso
 * @copyright  2017 Stephen Bourget
 * @author Daniel Miranda <daniellopes at gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {
    
    $yesno = array(get_string('no'), get_string('yes'));

    $settings->add(new admin_setting_heading(
            'auth_saml2sso/pluginname', 
            new lang_string('settings_saml2sso', 'auth_saml2sso'), 
            new lang_string('auth_saml2ssodescription', 'auth_saml2sso')
        )
    );

    $field_setting = 'button_url';
    $settings->add(new admin_setting_configtext_with_maxlength(
            'auth_saml2sso/'. $field_setting,
            new lang_string('label_' . $field_setting, 'auth_saml2sso'),
            new lang_string('help_' . $field_setting, 'auth_saml2sso'),
            '',
            PARAM_TEXT,
            80,
            255
        )
    );


    $field_setting = 'sp_path';
    $settings->add(new admin_setting_configtext_with_maxlength(
            'auth_saml2sso/'. $field_setting,
            new lang_string('label_' . $field_setting, 'auth_saml2sso'), 
            new lang_string('help_' . $field_setting, 'auth_saml2sso'),
            !empty(getenv('SIMPLESAMLPHP_CONFIG_DIR')) ? dirname(getenv('SIMPLESAMLPHP_CONFIG_DIR')) : '',
            PARAM_TEXT,
            50,
            255
        )
    );
    
    $field_setting = 'dual_login';
    $settings->add(new admin_setting_configselect(
            'auth_saml2sso/' . $field_setting, 
            new lang_string('label_' . $field_setting, 'auth_saml2sso'), 
            new lang_string('help_' . $field_setting, 'auth_saml2sso'), 
            0, 
            $yesno
        )
    );
    
    $field_setting = 'single_signoff';
    $settings->add(new admin_setting_configselect(
            'auth_saml2sso/' . $field_setting, 
            new lang_string('label_' . $field_setting, 'auth_saml2sso'), 
            new lang_string('help_' . $field_setting, 'auth_saml2sso'), 
            0, 
            $yesno
        )
    );
    
    $field_setting = 'idpattr';
    $settings->add(new admin_setting_configtext_with_maxlength(
            'auth_saml2sso/'. $field_setting,
            new lang_string('label_' . $field_setting, 'auth_saml2sso'), 
            new lang_string('help_' . $field_setting, 'auth_saml2sso'),
            '',
            PARAM_TEXT,
            50,
            255
        )
    );
    
    $field_setting = 'moodle_mapping';
    $fields = array(
        'username' => get_string('username'),
        'idnumber' => get_string('idnumber'),
        'email' => get_string('email'),
    );
    $settings->add(new admin_setting_configselect(
            'auth_saml2sso/' . $field_setting, 
            new lang_string('label_' . $field_setting, 'auth_saml2sso'), 
            new lang_string('help_' . $field_setting, 'auth_saml2sso'), 
            0, 
            $fields
        )
    );    
    
    $field_setting = 'autocreate';
    $settings->add(new admin_setting_configselect(
            'auth_saml2sso/' . $field_setting, 
            new lang_string('label_' . $field_setting, 'auth_saml2sso'), 
            new lang_string('help_' . $field_setting, 'auth_saml2sso'), 
            0, 
            $yesno
        )
    );
    
    $field_setting = 'entityid';
    $settings->add(new admin_setting_configtext_with_maxlength(
            'auth_saml2sso/'. $field_setting,
            new lang_string('label_' . $field_setting, 'auth_saml2sso'), 
            new lang_string('help_' . $field_setting, 'auth_saml2sso'),
            '',
            PARAM_TEXT,
            50,
            255
        )
    );
    
    $field_setting = 'logout_url_redir';
    $settings->add(new admin_setting_configtext_with_maxlength(
            'auth_saml2sso/'. $field_setting,
            new lang_string('label_' . $field_setting, 'auth_saml2sso'), 
            new lang_string('help_' . $field_setting, 'auth_saml2sso'),
            '',
            PARAM_URL,
            50,
            255
        )
    );
    
    $field_setting = 'edit_profile';
    $settings->add(new admin_setting_configselect(
            'auth_saml2sso/' . $field_setting, 
            new lang_string('label_' . $field_setting, 'auth_saml2sso'), 
            new lang_string('help_' . $field_setting, 'auth_saml2sso'), 
            0, 
            $yesno
        )
    );
    
    $field_setting = 'field_idp_fullname';
    $settings->add(new admin_setting_configselect(
            'auth_saml2sso/' . $field_setting, 
            new lang_string('label_' . $field_setting, 'auth_saml2sso'), 
            new lang_string('help_' . $field_setting, 'auth_saml2sso'), 
            0, 
            $yesno
        )
    );
    
    $field_setting = 'field_idp_firstname';
    $settings->add(new admin_setting_configtext_with_maxlength(
            'auth_saml2sso/'. $field_setting,
            new lang_string('label_' . $field_setting, 'auth_saml2sso'), 
            new lang_string('help_' . $field_setting, 'auth_saml2sso'),
            '',
            PARAM_TEXT,
            50,
            255
        )
    );
    
    $field_setting = 'field_idp_lastname';
    $settings->add(new admin_setting_configtext_with_maxlength(
            'auth_saml2sso/'. $field_setting,
            new lang_string('label_' . $field_setting, 'auth_saml2sso'), 
            new lang_string('help_' . $field_setting, 'auth_saml2sso'),
            '',
            PARAM_TEXT,
            50,
            255
        )
    );
    
    // Display locking / mapping of profile fields.
    $authplugin = get_auth_plugin('saml2sso');
    display_auth_lock_options(
            $settings, 
            $authplugin->authtype, 
            $authplugin->userfields, 
            new lang_string('auth_fieldlocks_help', 'auth'), 
            true, 
            false,
            $authplugin->get_custom_user_profile_fields()
    );
}
