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
{block name='block_social'}
  <div class="tbcmsfooter-social-icon col-xl-5 col-lg-12 col-md-12 col-sm-12 col-sx-12">

    {* {if Configuration::get('TBCMSCUSTOMSETTING_SOCIAL_ICON_TITLE', $language.id)}
      <div class="tbsocial-title-wrapper">
        <span class="tbsocial-title">
          {Configuration::get('TBCMSCUSTOMSETTING_SOCIAL_ICON_TITLE', $language.id)}
        </span>
      </div>
    {/if} *}

    <ul id="footer_sub_menu_social_icon" class="tbfooter-social-icon-wrapper">
      	{* {foreach from=$social_links item='social_link'}
        	<li class="{$social_link.class}"><a href="{$social_link.url}" target="_blank">{$social_link.label}</a></li>
    	{/foreach} *}


      	{if !empty(Configuration::get('BLOCKSOCIAL_FACEBOOK'))}
          <li class="facebook">
            <a href="{Configuration::get('BLOCKSOCIAL_FACEBOOK')}" target="_blank">
              <span class="social-icon"></span>
              <span class="tbsocial-title">{l s='Facebook' d='Shop.Theme.Catalog'}</span>
            </a>
          </li>
        {/if}

        {if !empty(Configuration::get('BLOCKSOCIAL_TWITTER'))}
          <li class="twitter">
            <a href="{Configuration::get('BLOCKSOCIAL_TWITTER')}" target="_blank">
              <span class="social-icon"></span>
              <span class="tbsocial-title">{l s='Twitter' d='Shop.Theme.Catalog'}</span>
            </a>
          </li>
        {/if}

        {if !empty(Configuration::get('BLOCKSOCIAL_RSS'))}
          <li class="rss">
            <a href="{Configuration::get('BLOCKSOCIAL_RSS')}" target="_blank">
              <span class="social-icon"></span>
              <span class="tbsocial-title">{l s='Rss' d='Shop.Theme.Catalog'}</span>
            </a>
          </li>
        {/if}

        {if !empty(Configuration::get('BLOCKSOCIAL_YOUTUBE'))}
          <li class="youtube">
            <a href="{Configuration::get('BLOCKSOCIAL_YOUTUBE')}" target="_blank">
              <span class="social-icon"></span>
              <span class="tbsocial-title">{l s='Youtube' d='Shop.Theme.Catalog'}</span>
            </a>
          </li>
        {/if}

        {if !empty(Configuration::get('BLOCKSOCIAL_GOOGLE_PLUS'))}
          <li class="googleplus">
            <a href="{Configuration::get('BLOCKSOCIAL_GOOGLE_PLUS')}" target="_blank">
              <span class="social-icon"></span>
              <span class="tbsocial-title">{l s='Google plus' d='Shop.Theme.Catalog'}</span>
            </a>
          </li>
        {/if}

        {if !empty(Configuration::get('BLOCKSOCIAL_PINTEREST'))}
          <li class="pinterest">
            <a href="{Configuration::get('BLOCKSOCIAL_PINTEREST')}" target="_blank">
              <span class="social-icon"></span>
              <span class="tbsocial-title">{l s='Pinterest' d='Shop.Theme.Catalog'}</span>
            </a>
          </li>
        {/if}

        {if !empty(Configuration::get('BLOCKSOCIAL_VIMEO'))}
          <li class="vimeo">
            <a href="{Configuration::get('BLOCKSOCIAL_VIMEO')}" target="_blank">
              <span class="social-icon"></span>
              <span class="tbsocial-title">{l s='Vimeo' d='Shop.Theme.Catalog'}</span>
            </a>
          </li>
        {/if}

        {if !empty(Configuration::get('BLOCKSOCIAL_INSTAGRAM'))}
          <li class="instagram">
            <a href="{Configuration::get('BLOCKSOCIAL_INSTAGRAM')}" target="_blank">
              <span class="social-icon"></span>
              <span class="tbsocial-title">{l s='Instagram' d='Shop.Theme.Catalog'}</span>
            </a>
          </li>
        {/if}

    </ul>
  </div>
{/block}
{/strip}
