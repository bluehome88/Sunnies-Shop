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
{foreach $stylesheets.external as $stylesheet}
  <link rel="stylesheet" href="{$stylesheet.uri}" type="text/css" media="{$stylesheet.media}">
{/foreach}

{foreach $stylesheets.inline as $stylesheet}
  <style>
    {$stylesheet.content}
  </style>
{/foreach}



{if Configuration::get('TBCMSCUSTOMSETTING_CUSTOM_FONT_TITLE_COLOR_STATUS') == '1'}
	{if !empty(Configuration::get('TBCMSCUSTOMSETTING_THEME_FONT_TYPE_LINK_URL')) && Configuration::get('TBCMSCUSTOMSETTING_THEME_FONT_TYPE') != '0'}
		<link href="{Configuration::get('TBCMSCUSTOMSETTING_THEME_FONT_TYPE_LINK')}" rel="stylesheet">
		<link href="{Configuration::get('TBCMSCUSTOMSETTING_THEME_FONT_TYPE_LINK_URL')}" rel="stylesheet">
	{/if}

	{if !empty(Configuration::get('TBCMSCUSTOMSETTING_THEME_FONT_TYPE_LINK_2_URL')) && Configuration::get('TBCMSCUSTOMSETTING_THEME_FONT_TYPE') != '0'}
		<link href="{Configuration::get('TBCMSCUSTOMSETTING_THEME_FONT_TYPE_LINK_2')}" rel="stylesheet">
		<link href="{Configuration::get('TBCMSCUSTOMSETTING_THEME_FONT_TYPE_LINK_2_URL')}" rel="stylesheet">
	{/if}

	{if !empty(Configuration::get('TBCMSCUSTOMSETTING_THEME_CUSTOM_TITLE_COLOR'))}
		<link href="{Configuration::get('TBCMSCUSTOMSETTING_THEME_CUSTOM_TITLE_COLOR')}" rel="stylesheet">
	{/if}
{/if}
{/strip}