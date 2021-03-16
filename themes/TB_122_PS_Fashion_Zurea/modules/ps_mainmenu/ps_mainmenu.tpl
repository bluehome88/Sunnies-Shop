{*
* 2007-2019 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2019 PrestaShop SA
*  @version  Release: $Revision$
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{strip}
{assign var=_counter value=0}
{function name="menu" nodes=[] depth=0 parent=null}
    {if $nodes|count}
      <ul class="tb-header-menu" {if $depth == 0}id="tb-top-menu"{/if} data-depth="{$depth}">
        {if $depth == 0}
          <li class="{if $page.page_name=='index'}current {/if} tbmain-menu-wrapper ">
            <div class="tbmenu-link-wrapper">
              <a href="{$urls.pages.index}" data-depth="{$depth}">
                <span class="tbmain-menu-text-box">{l s='Home' d='Shop.Theme.Catalog'}</span>
              </a>
            </div>
          </li>
        {/if}
        {foreach from=$nodes item=node}
            <li class="{$node.type}{if $node.current} current {/if} tbmain-menu-wrapper {if $node.children|count}tb-has-child{/if}" id="{$node.page_identifier}" {if $node.children|count}{assign var=_expand_id value=10|mt_rand:100000} data-child-dropdown='top_sub_menu_{$_expand_id}'{/if}>
            {assign var=_counter value=$_counter+1}
            <div class="tbmenu-link-wrapper">
              <a class="{if $depth === 1} tbdropdown-submenu{/if} tbmenu-link" href="{$node.url}" data-depth="{$depth}" {if $node.open_in_new_window} target="_blank" {/if}>                                
                <span class="tbmain-menu-text-box">
                   {$node.label}
                </span>
              </a>
              {if $node.children|count}
                <span class="tbmenu-toggle-icon">
                  <i class='material-icons tbhorizontal-menu-drop-down-icon'>&#xe313;</i>
                </span>
              {/if}
              </div>
              {if $node.children|count}
              <div {if $depth === 0 }  class="tbmain-menu-dropdown tbmain-menu-sub-menu tbmain-menu-mobile-dropdown" {elseif $depth === 1 } class="tbmain-menu-mobile-dropdown"{else}class=""{/if} >
                {menu nodes=$node.children depth=$node.depth parent=$node}
                {if $depth === 0 && $node.children.0.children}
                  {if $node.image_urls}
                    {foreach $node.image_urls as $image}
                      <div class="tbheader-top-menu-img">
                          <img src="{$image}" alt="{$node.label}">
                      </div>
                    {/foreach}
                  {/if}
                {/if}
              </div>
              {/if}
            </li>
        {/foreach}
      </ul>
    {/if}
{/function}

<div id='tbcmsdesktop-main-menu'>
  <div class='tbcmsmain-menu-wrapper' {* id="_desktop_top_menu" *}>
      {menu nodes=$menu.children}
      <div class="clearfix"></div>
      <div class="tbmenu-button-wrapper">
        <div class="tbmenu-button">
          <i class='material-icons'>&#xe5d2;</i>
        </div>
      </div>
  </div>
</div>
{/strip}