{**
 * 2007-2019 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
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
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2019 PrestaShop SA
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}

{strip}
{if Configuration::get('TBCMSFRONTSIDE_THEME_SETTING_SHOW')}
    <!-- START THEME_CONTROL -->
    <link rel="stylesheet" type="text/css" href="{$urls.css_url}jquery.minicolors.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{$urls.css_url}bootstrap-toggle.min.css" rel="stylesheet">
    <script src="{$urls.js_url}templatebeta/jquery.minicolors.js"></script>
    <script src="{$urls.js_url}templatebeta/bootstrap-toggle.min.js"></script>
    <div class="tbcms-custom-theme"></div>

    <div class="tbcms-custom-font-1"></div>
    <div class="tbcms-custom-font-2"></div>
    <div class="tbcms-custom-color"></div>
    <!-- END THEME_CONTROL -->
{/if}
{if Configuration::get('TBCMSCUSTOMSETTING_THEME_OPTION')}
    <!-- START THEME_CONTROL CUSTOM COLOR CSS -->
    {if Configuration::get('TBCMSCUSTOMSETTING_THEME_OPTION') == 'theme_custom'}
      <link rel="stylesheet" type="text/css" href="{$urls.css_url}{Configuration::get('TBCMSCUSTOMSETTING_THEME_CSS_PATH')}">
    {else}
      <link rel="stylesheet" type="text/css" href="{$urls.css_url}theme/{Configuration::get('TBCMSCUSTOMSETTING_THEME_OPTION')}.css">
    {/if}
      <!-- END THEME_CONTROL CUSTOM COLOR CSS -->
{/if}

{/strip}