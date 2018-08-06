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
 * Strings for component 'auth_saml2sso', language 'en'.
 *
 * @package auth_saml2sso
 * @author Daniel Miranda <daniellopes at gmail.com>
 * @copyright 1999 onwards Martin Dougiamas  {@link http://moodle.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
$string['auth_saml2ssodescription']                 = 'Users can login using SAML2 Identity Provider';
$string['pluginname']                               = 'SAML2 SSO Auth';
$string['settings_saml2sso']                        = '';

//label config strings
$string['label_button_url']                         = 'Url to button (image)';
$string['label_button_name']                        = 'Button caption';
$string['label_sp_path']                            = 'SimpleSAMLphp library path';
$string['label_dual_login']                         = 'Dual login';
$string['label_single_signoff']                     = 'Single Sign Off';
$string['label_idpattr']                            = 'Username attribute';
$string['label_moodle_mapping']                     = 'Username checking';
$string['label_autocreate']                         = 'Auto create users';
$string['label_authsource']                         = 'SP auth source name';
$string['label_logout_url_redir']                   = 'Logout URL';
$string['label_logout']                             = 'Click here to logout';
$string['label_edit_profile']                       = 'Can user edit profile?';
$string['label_field_idp_firstname']                = 'Firstname field from Identity Provider';
$string['label_field_idp_lastname']                 = 'Lastname field from Identity Provider';
$string['label_field_idp_fullname']                 = 'Full name from Identity Provider';
$string['label_instructions_title']                 = 'Instructions';
$string['label_instructions_p1']                    = '<p>You must fill the following Data mapping fields as:</p><ul><li>Firstname => givenName</li><li>Surname => surname</li><li>Email address: => email</li></ul><p>You can change this in <code>$stringmapping</code> array in <code>auth.php</code></p>';
$string['label_response_email']                     = 'Match Email address';

//_help config strings
$string['help_response_email']                      = 'The IdP/ADFS returns Email or Mail value';
$string['help_button_url']                          = 'Url to an image that will be used as login button. Max 50px high';
$string['help_button_name']                         = 'A caption for the login button';
$string['help_sp_path']                             = 'Absolute path to Service Provider (SP) installation. Ex.: /var/www/simplesamlphp/';
$string['help_dual_login']                          = 'Define if prompt the user with standard Moodle login page';
$string['help_single_signoff']                      = 'Single Sign Off users from Moodle and IdP?';
$string['help_idpattr']                             = 'Which attribute from Identity Provider should be used for username?';
$string['help_moodle_mapping']                      = 'Where to check if the username exists? If using \'' .
        get_string('idnumber') . '\', remember to mapping in Data mapping below';
$string['help_autocreate']                          = 'Allow create new users?';
$string['help_authsource']                          = 'Service Provider authentication source name available in /config/authsources.php SimpleSAMLphp installation';
$string['help_logout_url_redir']                    = 'URL to redirect users on logout. If the URL is invalid or empty, it will redirect to Moodle main page. (ex.: https://goto/another/url)';
$string['nouser']                                   = 'There is no user with the provided Id and auto signup is not allowed. The provided Id is: ';
$string['help_edit_profile']                        = 'If users cannot edit profile, they won\'t see the link to profile';
$string['help_field_idp_firstname']                 = 'Field from Identity Provider containing the user first name' ;
$string['help_field_idp_lastname']                  = 'Field from Identity Provider containing the user last name';
$string['help_field_idp_fullname']                  = 'The username from Identity Provider is a single field? If yes, fill with the same value in the fields firstname and lastname below';

//error config strings
$string['error_create_user']                        = 'A error occured when create a user account. Please, contact the administrator.';
$string['error_sp_path']                            = 'The path to SimpleSAMLphp libraries must be given in config';
$string['error_idpattr']                            = 'A Username mapping attribute must be given';
$string['error_authsource']                           = 'A Service Provider source name must be given';
$string['error_field_idp_firstname']                = 'The Firstname field from Identity Provider is mandatory';
$string['error_field_idp_lastname']                 = 'The lastname field from Identity Provider is mandatory';
$string['error_lockconfig_field_map_firstname']     = 'The First name in Data mapping fields is mandatory';
$string['error_lockconfig_field_map_lastname']      = 'The Surname in Data mapping fields is mandatory';
$string['error_lockconfig_field_map_email']         = 'The Email address in Data mapping fields is mandatory';
$string['error_novalidemailfromidp']                = 'There is no valid e-mail address from Identity Provider';

$string['success_config']                           = 'All the config fields were saved successfully';

$string['label_profile_settings']                   = 'SAML attributes and user profile';

$string['label_dual_login_settings']  = 'Dual login';
$string['label_dual_login_help']   = '
By default, at login users are redirect directly to the IdP or discovery service
configured in the SimpleSAMLphp authentication source.<br />
To perform Moodle standard login, you should add saml=off parameter. Ex.: /login/index.php?saml=off<br />
With dual login, user must choose the authentication method.';
$string['label_sync_settings']        = 'Users sync';
$string['label_sync_settings_help']   = '
SAML IdPs cannot provide a user list suitable to users synchronization,
however they are often backended on a LDAP o DB source able to.
Configure the plugin for the backend authentication source.';
$string['label_user_directory']       = 'User directory';
$string['help_user_directory']        = 'An auth plugin with listing capability';
$string['label_takeover_users']       = 'Takeover existing users';
$string['help_takeover_users']        = '
Existing users belonging to the auth plugin providing user directory
will switch to ' . $string['pluginname'] . ' authentication.<br/>
If used at plugin installation, this option provide a migration
from a Moodle system using LDAP (or DB) authentication to SAML SSO.<br/>
Conversion took place when run as a ' . get_string('scheduledtasks', 'tool_task') .
'<br />Setting \'No\', only new users will use SAML SSO.';
$string['label_verbose_sync']        = 'Show details';
$string['help_verbose_sync']         = 'Enable verbose report';

$string['synctask']        = 'Users sync';
