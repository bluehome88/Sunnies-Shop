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
{if $page.page_name == 'cart'}
<div class="breadcrumb">
  <div class="container">
    <div class='tbcategory-page-title'>{$page.page_name}</div>
    <nav data-depth="{$breadcrumb.count}">
      <ol itemscope itemtype="http://schema.org/BreadcrumbList">
        {block name='breadcrumb'}
          {foreach from=$breadcrumb.links item=path name=breadcrumb}
            {block name='breadcrumb_item'}
              <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                <a itemprop="item" href="{$path.url}">
                  <span itemprop="name">{$path.title}</span>
                </a>
                <meta itemprop="position" content="{$smarty.foreach.breadcrumb.iteration}">
              </li>
            {/block}
          {/foreach}
          <li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
            <a itemprop="item" href="javascript:void(0);"><span itemprop="name">{l s='Shopping Cart' d='Shop.Theme.Checkout'}</span></a>
          </li>
        {/block}
      </ol>
    </nav>
  </div>
</div>

{else if $page.page_name == 'order-confirmation'}
<div class="breadcrumb">
  <div class="container">
    <div class='tbcategory-page-title'>{$page.page_name}</div>
    <nav data-depth="{$breadcrumb.count}">
      <ol itemscope itemtype="http://schema.org/BreadcrumbList">
        {block name='breadcrumb'}
          {foreach from=$breadcrumb.links item=path name=breadcrumb}
            {block name='breadcrumb_item'}
              <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                <a itemprop="item" href="{$path.url}">
                  <span itemprop="name">{$path.title}</span>
                </a>
                <meta itemprop="position" content="{$smarty.foreach.breadcrumb.iteration}">
              </li>
            {/block}
          {/foreach}
          <li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
            <a itemprop="item" href="javascript:void(0);"><span itemprop="name">{l s='Shopping Cart' d='Shop.Theme.Checkout'}</span></a>
          </li>
        {/block}
      </ol>
    </nav>
  </div>
</div>
{else}
<div class="breadcrumb">
  <div class="container">
    <div class='tbcategory-page-title'></div>
    <nav data-depth="{$breadcrumb.count}">
      <ol itemscope itemtype="http://schema.org/BreadcrumbList">
        {block name='breadcrumb'}
          {foreach from=$breadcrumb.links item=path name=breadcrumb}
            {block name='breadcrumb_item'}
              <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                <a itemprop="item" href="{$path.url}">
                  <span itemprop="name">{$path.title}</span>
                </a>
                <meta itemprop="position" content="{$smarty.foreach.breadcrumb.iteration}">
              </li>
            {/block}
          {/foreach}
        {/block}
      </ol>
    </nav>
  </div>
</div>
{/if}
{/strip}


