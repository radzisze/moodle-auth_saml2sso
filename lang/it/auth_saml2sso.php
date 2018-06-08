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
 * Strings for component 'auth_saml2sso', language 'it'.
 *
 * @package auth_saml2sso
 * @author Marco Ferrante, AulaWeb/University of Genoa <staff@aulaweb.unige.it>
 * @copyright 1999 onwards Martin Dougiamas  {@link http://moodle.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
$string['auth_saml2ssodescription']                 = 'Autentica gli utenti tramite SAML 2.0';
$string['pluginname']                               = 'SAML2 SSO Auth';
$string['settings_saml2sso']                        = '';

//label config strings
$string['label_sp_path']                            = 'Percorso librerie SimpleSAMLphp';
$string['label_dual_login']                         = 'Dual login';
$string['label_single_signoff']                     = 'Single Sign Off';
$string['label_idpattr']                            = 'Attributo id IdP';
$string['label_moodle_mapping']                     = 'Campo identificativo';
$string['label_autocreate']                         = 'Creazione automatica utente';
$string['label_authsource']                         = 'Nome sorgente autenticazione SP';
$string['label_logout_url_redir']                   = 'URL di logout';
$string['label_logout']                             = 'Disconnessione';
$string['label_edit_profile']                       = 'L\'utente può modificarsi il profilo?';
$string['label_field_idp_firstname']                = 'Attributo IdP del nome';
$string['label_field_idp_lastname']                 = 'Attributo IdP del cognome';
$string['label_field_idp_fullname']                 = 'Nome completo dall\'IdP?';
$string['label_instructions_title']                 = 'Istruzioni';
$string['label_instructions_p1']                    = '<p>La mappatura è richiesta per i campi:</p><ul><li>Firstname => givenName</li><li>Surname => surname</li><li>Email address: => email</li></ul><p>You can change this in <code>$stringmapping</code> array in <code>auth.php</code></p>';

//_help config strings
$string['help_sp_path']                             = 'Percorso assoluto dell\'installazione di SSP. Es.: /var/www/simplesamlphp/';
$string['help_dual_login']                          = 'Permette all\'utente di accedere direttamente con un account manuale di Moodle. Per bypassare l\'IdP occorre aggiungere il parametro saml=off. Es.: /login/index.php?saml=off';
$string['help_single_signoff']                      = 'Il logout da Moodle attiva anche il logout dall\'IdP e dalla sessione di Single SignOn';
$string['help_idpattr']                             = 'L\'attributo che identifica l\'utente per l\'IdP';
$string['help_moodle_mapping']                      = 'Il campo del profilo Moodle con cui cercare l\'utente. Se \'' .
        get_string('idnumber') . '\', ricordarsi di mapparlo nelle impostazioni più sotto';
$string['help_autocreate']                          = 'Crea l\'utente Moodle all\'accesso se non presente';
$string['help_authsource']                          = 'Nome della sorgente di autenticazione del Service Provider, come registrata in /config/authsources.php';
$string['help_logout_url_redir']                    = 'URL a cui ridirigere dopo il logout. Se non è valido o vuoto, si verrà rediretti alla pagina principale di Moodle. (es.: https://go.to/another/url)';
$string['nouser']                                   = 'Non c\'è un utente Moodle con l\'id restituito e la creazione automatica è disabilitata. L\'id restituito è: ';
$string['help_edit_profile']                        = 'Se gli utenti non possono modifica il proprio profilo, non vedranno il link al profilo';
$string['help_field_idp_firstname']                 = 'Attributo dell\'asserzione restituita dall\'IdP contenente il nome' ;
$string['help_field_idp_lastname']                  = 'Attributo dell\'asserzione restituita dall\'IdP contenente il cognome';
$string['help_field_idp_fullname']                  = 'Il nome completo è restituito dall\'IdP è un campo unico (es. cn)? Se sì, indicarlo sopra in entrambe gli attributi per il nome e il cognome';

//error config strings
$string['error_create_user']                        = 'Errore nella creazione del profilo Moodle. Contattare l\'amministratore.';
$string['error_sp_path']                            = 'Il percorso delle librerie SimpleSAMLphp dev\'essere specificato nella configurazione';
$string['error_idpattr']                            = 'Un attributo id dev\'essere specificato';
$string['error_authsource']                         = 'Una sorgente di autenticazione dev\'essere specificata';
$string['error_field_idp_firstname']                = 'L\'attributo per il nome è obbligatorio';
$string['error_field_idp_lastname']                 = 'L\'attributo per il cognome è obbligatorio';
$string['error_lockconfig_field_map_firstname']     = 'La mappatura del nome è obbligatoria';
$string['error_lockconfig_field_map_lastname']      = 'La mappatura del cognome è obbligatoria';
$string['error_lockconfig_field_map_email']         = 'La mappatura dell\'Indirizzo email è obbligatoria';
$string['error_novalidemailfromidp']                = 'There is no valid e-mail address from Identity Provider';

$string['success_config']                           = 'La configurazione è stata salvata correttamente';

$string['label_profile_settings']                   = 'Attributi SAML e profilo utente';

$string['label_sync_settings']        = 'Sincronizzazione utenti';
$string['label_user_directory']          = 'Origine utenti';
$string['help_user_directory']           = 'Un plugin di autentiazione in grado di elencare gli utenti';
$string['label_takeover_users']       = 'Rileva utenti esistenti';
$string['help_takeover_users']           = '
Se un utente è già registrato con il metodo di autenticazione dell\'origine
utenti viene modificato per autenticarsi con ' . $string['pluginname'] . '.<br/>
Questa opzione, usata all\'installazione, permette di migrare un sistema
che usa l\'autenticazione LDAP (o DB) al Single SignOn SAML<br/>
La conversione viene eseguita da ' . get_string('scheduledtasks', 'tool_task') .
'<br/>Scegliendo No, solo i nuovi utenti useranno il SSO SAML.';
$string['label_verbose_sync']        = 'Mostra report';
$string['help_verbose_sync']         = 'Attiva il report dettagliato';

$string['synctask']        = 'Sincronizzazione utenti';
