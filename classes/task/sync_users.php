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
// along with Moodle. If not, see <http://www.gnu.org/licenses/>.

/**
 * A scheduled task for generic user sync.
 *
 * @package auth_saml2sso
 * @copyright 2018 Marco Ferrante, University of Genoa (I) <marco@csita.unige.it>
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 */
namespace auth_saml2sso\task;

class sync_users extends \core\task\scheduled_task {

    const COMPONENT_NAME = 'auth_saml2sso';
    const AUTHTYPE_NAME = 'saml2sso';

    /**
     * Get a descriptive name for this task (shown to admins).
     *
     * @return string
     */
    public function get_name() {
        return get_string('synctask', self::COMPONENT_NAME);
    }

    /**
     * Run users sync.
     */
    public function execute() {
        if (!is_enabled_auth(self::AUTHTYPE_NAME)) {
            mtrace(self::AUTHTYPE_NAME . ' plugin is disabled, synchronisation stopped');
            return;
        }

        $sync = get_auth_plugin(self::AUTHTYPE_NAME);
        $config = get_config(self::COMPONENT_NAME);
        if ($config->verbose_sync) {
            $trace = new \text_progress_trace();
        }
        else {
            $trace = new \null_progress_trace();
        }
        $update = !empty($config->takeover_users);

        if (empty($config->user_directory)) {
            mtrace('Auth source not set, synchronisation stopped');
            return;
        }

        $sourceplugin = get_auth_plugin($config->user_directory);
        if (empty($sourceplugin)) {
            mtrace('Auth plugin ' . $config->user_directory . ' doesn\'t exists');
            return;
        }

        $sourceplugin->authtype = self::AUTHTYPE_NAME;
        $ref = new \ReflectionMethod($sourceplugin, 'sync_users');
        if ($ref->getNumberOfParameters() == 1) {
            $sourceplugin->sync_users($config->takeover_users);
        }
        elseif ($ref->getNumberOfParameters() == 2) {
            $sourceplugin->sync_users($trace, $config->takeover_users);
        }
        else {
            mtrace('Unhandled sync_user() method in auth plugin ' . $config->user_directory);
        }

    }

}
