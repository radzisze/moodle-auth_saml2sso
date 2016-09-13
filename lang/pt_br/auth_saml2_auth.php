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
 * Strings for component 'auth_saml2_auth', language 'pt_br'.
 *
 * @package auth_saml2_auth
 * @author Daniel Miranda <daniellopes at gmail.com>
 * @copyright 1999 onwards Martin Dougiamas  {@link http://moodle.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
$string['auth_saml2_auth_description'] = 'Usuários podem autenticar utilizando um provedor de identidade SAML2';
$string['pluginname'] = 'SAML2 Auth';

/*
 * label config strings
 */
$string['label_sp_path'] = 'Provedor de Serviço (SP) SAML2';
$string['label_dual_login'] = 'Dual login';
$string['label_mapping'] = 'Mapeamento de campos no Moodle';
$string['label_autocreate'] = 'Auto registro do usuário';
$string['label_entityid'] = 'Nome do provedor de serviço a ser invocado';
$string['label_logout_url_redir'] = 'URL de logout';
$string['label_logout'] = 'Clique aqui para sair';


/**
 * _help config strings
 */
$string['help_sp_path'] = 'Caminho absoluto da instalação do provedor de serviço. Ex.: /var/www/simplesamlphp/';
$string['help_dual_login'] = 'Define se um usuário com conta interna pode autenticar diretamente no Moodle';
$string['help_mapping'] = 'Qual atributo do Moodle deve corresponder com o atributo retornado do IdP?';
$string['help_autocreate'] = 'Permitir criar novos usuários?';
$string['help_entityid'] = 'Nome da fonte de autenticação no provedor de serviço disponível em /config/authsources.php';
$string['help_logout_url_redir'] = 'URL para redirecionar os usuários ao sairem do Moodle';
$string['nouser'] = 'Não existe um usuário com a identificação informada e a autoinscrição não está habilitada. O identificação informada foi: ';
$string['error_create_user'] = 'Ocorreu um erro ao criar a conta de usuário. Por favor, entre em contato com o administrador.';
