# Moodle Authentication using SimpleSAMLphp Identity Provider
Moodle plugin for authentication using a SimpleSAMLphp Service Provider
## You'll need the following pre-requirement:
* A working SimpleSAMLphp Service Provider (SP) installation (https://simplesamlphp.org) *working means that the metadata from SP must be registered in Identity Provider (IdP). Can be found in /config/authsources.php*
* The absolute path for the SimpleSAMLphp installation on server
* The authsource name from SP in which your users will authenticate against

To override the authentication and login directly in Moodle (ex.: using admin account), add the saml=off parameter in the URL (ex.: https://moodle/login/index.php?saml=off)
