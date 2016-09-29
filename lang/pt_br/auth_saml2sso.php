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
 * Strings for component 'auth_saml2sso', language 'pt_br'.
 *
 * @package auth_saml2sso
 * @author Daniel Miranda <daniellopes at gmail.com>
 * @copyright 1999 onwards Martin Dougiamas  {@link http://moodle.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
$string['auth_saml2ssodescription'] = 'Usuários podem autenticar utilizando um provedor de identidade SAML2';
$string['pluginname'] = 'SAML2 SSO Auth';

/*
 * label config strings
 */
$string['label_sp_path'] = 'Provedor de Serviço (SP) SAML2';
$string['label_dual_login'] = 'Dual login';
$string['label_single_signoff'] = 'Single Sign Off';
$string['label_username_mapping'] = 'Mapear username';
$string['label_moodle_mapping'] = 'Validar username';
$string['label_autocreate'] = 'Auto registro do usuário';
$string['label_entityid'] = 'Nome do provedor de serviço a ser invocado';
$string['label_logout_url_redir'] = 'URL de logout';
$string['label_logout'] = 'Clique aqui para sair';
$string['label_edit_profile'] = 'Usuário pode editar perfil?';
$string['label_field_idp_firstname'] = 'Campo no IdP para o primeiro nome';
$string['label_field_idp_lastname'] = 'Campo no IdP para o sobrenome';
$string['label_field_idp_fullname'] = 'Nome completo no IdP';
$string['label_instructions_title'] = 'Instruções';
$string['label_instructions_p1'] = 'Você deve preencher Nome, Sobrenome e Endereço de email com o nome que você quiser em Mapeamento dos dados';


/**
 * _help config strings
 */
$string['help_sp_path'] = 'Caminho absoluto da instalação do provedor de serviço. Ex.: /var/www/simplesamlphp/';
$string['help_dual_login'] = 'Define se um usuário pode entrar no Moodle diretamente. Para entrar direto no Moodle você deve adicionar o parâmetro saml=off. Ex.: /login/index.php?saml=off';
$string['help_single_signoff'] = 'Fazer logout do usuário no Moodle e no Provedor de Identidade?';
$string['help_username_mapping'] = 'Qual atributo do IdP deve ser usado para username?';
$string['help_moodle_mapping'] = 'Onde validar se o username existe? Se usar o idnumber, lembre de mapear em "Mapeamento de dados" abaixo';
$string['help_autocreate'] = 'Permitir criar novos usuários?';
$string['help_entityid'] = 'Nome da fonte de autenticação no provedor de serviço disponível em /config/authsources.php';
$string['help_logout_url_redir'] = 'URL para redirecionar os usuários ao sairem do Moodle';
$string['nouser'] = 'Não existe um usuário com a identificação informada e a autoinscrição não está habilitada. A identificação informada foi: ';
$string['error_create_user'] = 'Ocorreu um erro ao criar a conta de usuário. Por favor, entre em contato com o administrador.';
$string['help_edit_profile'] = 'Se usuários não podem editar perfil, o link de perfil não é exibido';
$string['help_field_idp_firstname'] = 'Campo no IdP que contém o primeiro nome do usuário';
$string['help_field_idp_lastname'] = 'Campo no IdP que contém o sobrenome do usuário';
$string['help_field_idp_fullname'] = 'O campo com o nome do usuário no IdP é único? Se sim, preencha com o mesmo valor nos campos primeiro nome e sobrenome abaixo';
