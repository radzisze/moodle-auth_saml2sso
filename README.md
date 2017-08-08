# SAML2 Authentication using exists SimpleSAMLphp Service Provider

You'll need the following pre-requirement:

* A working SimpleSAMLphp Service Provider (SP) installation (https://simplesamlphp.org) _working means that the metadata from SP must be registered in Identity Provider (IdP). Can be found in /config/authsources.php_
* The absolute path for the SimpleSAMLphp installation on server
* The authsource name from SP in which your users will authenticate against

There are a couple of related SAML plugins for Moodle. Below are the main diferences between this plugin, named as auth_saml2sso, and the others

The key for this plugin is that you can use your exists Service Provider (SP) without needed to exchange the metadata with the Identity Provider (IdP) for every new Moodle instances. _(for instances in the same host name)_

## The following options can be set in config:

* SimpleSAMLphp installation path
* Dual login (Yes/No) - Can login with manual accounts like admin
* Single Sign Off (Yes/No) - Should we sign off users from Moodle and IdP?
* Username mapping - Which attribute from IdP should be used for username
* Username checking - Where to check if the username exists
* Auto create users - Allow create new users
* SP source name - Generally default-sp in SimpleSAMLphp
* Logout URL to redirect users after logout
* Allow users to edit or not the profile
* Ability to break the full name from IdP into firstname and lastname

To override the authentication and login directly in Moodle (ex.: using admin account), add the saml=off parameter in the URL (ex.: https://my.moodle/login/index.php?saml=off)
