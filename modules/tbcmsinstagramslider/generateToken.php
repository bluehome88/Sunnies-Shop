<?php
/**
* 2007-2019 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2019 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

// session_start();
include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/../../init.php');

if (Tools::getValue('error')) {
    $status = 'Instagram API: '.Tools::getValue('error');
    Tools::redirectAdmin(Configuration::get('INSTAGRAM_REDIRECT_AFTER_TOKEN').'&status='.$status);
    die;
}

if (Tools::getValue('code')) {
    $status = 'update';
    $insta_client_id = Configuration::get('CS_INS_ID');
    $insta_secret_id = Configuration::get('CS_INS_SECRET_ID');
    $insta_redirect_uri = Context::getContext()->shop->getBaseURL(true)
        .'modules/tbcmsinstagramslider/generateToken.php';
    $insta_auth_code = Tools::getValue('code');

    $url = 'https://api.instagram.com/oauth/access_token';

    $postFields = array(
        'client_id' => $insta_client_id,
        'client_secret' => $insta_secret_id,
        'grant_type' => 'authorization_code',
        'redirect_uri' => $insta_redirect_uri,
        'code' => $insta_auth_code
    );

    $options=array(
        CURLOPT_URL            => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER         => false,
        CURLOPT_SSL_VERIFYPEER    => false,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $postFields
    );

    $ch = curl_init();
    curl_setopt_array($ch, $options);
    $access_token = curl_exec($ch);

    $result = Tools::jsonDecode($access_token);

    if (curl_errno($ch)) {
        $status = 'token_curl_error';
    } elseif (isset($result->error_message) && $result->error_message) {
        $status = $result->error_message;
    } else {
        Configuration::updateValue('CS_INS_CT', $result->access_token);
    }

    curl_close($ch);

    Tools::redirectAdmin(Configuration::get('INSTAGRAM_REDIRECT_AFTER_TOKEN').'&status='.$status);
}

die;
