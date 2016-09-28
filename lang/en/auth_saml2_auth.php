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
 * Strings for component 'auth_saml2_auth', language 'en'.
 *
 * @package auth_saml2_auth
 * @author Daniel Miranda <daniellopes at gmail.com>
 * @copyright 1999 onwards Martin Dougiamas  {@link http://moodle.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
$string['auth_saml2_authdescription'] = 'Users can login using SAML2 Identity Provider';
$string['pluginname'] = 'SAML2 Auth';

/*
 * label config strings
 */
$string['label_sp_path'] = 'SAML2 Service Provider (SP)';
$string['label_dual_login'] = 'Dual login';
$string['label_single_signoff'] = 'Single Sign Off';
$string['label_username_mapping'] = 'Username mapping';
$string['label_moodle_mapping'] = 'Username checking';
$string['label_autocreate'] = 'Auto create users';
$string['label_entityid'] = 'SP source name';
$string['label_logout_url_redir'] = 'Logout URL';
$string['label_logout'] = 'Click here to logout';
$string['label_edit_profile'] = 'Can user edit profile?';


/**
 * _help config strings
 */
$string['help_sp_path'] = 'Absolute path to SP installation. Ex.: /var/www/simplesamlphp/';
$string['help_dual_login'] = 'Define if users can log-in directly to Moodle. To bypass IdP you should add saml=off parameter. Ex.: /login/index.php?saml=off';
$string['help_single_signoff'] = 'Single Sign Off users from Moodle and IdP?';
$string['help_username_mapping'] = 'Which attribute from IdP should be used for username?';
$string['help_moodle_mapping'] = 'Where to check if the username exists? If using idnumber, remember to mapping in Data mapping below';
$string['help_autocreate'] = 'Allow create new users?';
$string['help_entityid'] = 'SP source name available in /config/authsources.php';
$string['help_logout_url_redir'] = 'URL to redirect users on logout';
$string['nouser'] = 'There\'s no user with the provided Id and auto signup is not allowed. The provided Id is: ';
$string['error_create_user'] = 'A error occured when create a user account. Please, contact the administrator.';
$string['help_edit_profile'] = 'If users cannot edit profile, they won\'t see the link to profile';