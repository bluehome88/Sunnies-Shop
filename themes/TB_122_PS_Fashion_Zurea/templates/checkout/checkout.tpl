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
<!doctype html>
<html lang="{$language.iso_code}">

  <head>
    {block name='head'}
      {include file='_partials/head.tpl'}
    {/block}
  </head>

  <body id="{$page.page_name}" class="{$page.body_classes|classnames}" {if Configuration::get('TBCMSCUSTOMSETTING_ADD_CONTAINER')} style='{hook h="displayBackgroundBody"}background-repeat:{Configuration::get("TBCMSCUSTOMSETTING_BACKGROUND_IMAGE_REPEAT")};background-attachment:{Configuration::get("TBCMSCUSTOMSETTING_BACKGROUND_IMAGE_ATTACHMENT")};' {/if} data-mouse-hover-img='{Configuration::get("TBCMSCUSTOMSETTING_HOVER_IMG")}' data-menu-sticky='{Configuration::get("TBCMSCUSTOMSETTING_MAIN_MENU_STICKY")}'>

    {block name='hook_after_body_opening_tag'}
      {hook h='displayAfterBodyOpeningTag'}
    {/block}

    {* Start Theme Option *}
    {hook h='displayThemeOptions'}
    {* End Theme Option *}
  
    {* Start page loader *}
    {include file='_partials/tbcms-page-loader.tpl'}
    {* End page loader *}

    <div class="tb-main-div {if Configuration::get('TBCMSCUSTOMSETTING_ADD_CONTAINER')}tb-box-layout container{/if}" {if Configuration::get('TBCMSCUSTOMSETTING_BODY_BACKGROUND_COLOR_STATUS') == '1'}style='{hook h="displayBackgroundBody"}background-repeat:{Configuration::get("TBCMSCUSTOMSETTING_BACKGROUND_IMAGE_REPEAT")};background-attachment:{Configuration::get("TBCMSCUSTOMSETTING_BACKGROUND_IMAGE_ATTACHMENT")};'{/if}>

      <header id="header">
        {block name='header'}
          {include file='checkout/_partials/header.tpl'}
        {/block}
      </header>

        <div id="wrapper" style="margin-top: 0px;">
       {block name='breadcrumb'}
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
                      <a itemprop="item" href="javascript:void(0);"><span itemprop="name">{$page.page_name}</span></a>
                    </li>
                  {/block}
                </ol>
              </nav>
            </div>
          </div>
        {/block}
      </div>


      {block name='notifications'}
        {include file='_partials/notifications.tpl'}
      {/block}

      <div id="wrapper">
        {if $page.page_name == 'index'}
        {hook h="displayWrapperTop"}
        {/if}

        <div class="container">

        {block name='content'}
          <div id="content">
            <div class="row">
              <div class="col-md-12 col-lg-8 tbcheckout-process-left">
                {block name='cart_summary'}
                  {render file='checkout/checkout-process.tpl' ui=$checkout_process}
                {/block}
              </div>
              <div class="col-md-12 col-lg-4 tbcheckout-process-right">

                {block name='cart_summary'}
                  {include file='checkout/_partials/cart-summary.tpl' cart = $cart}
                {/block}

                {hook h='displayReassurance'}
              </div>
            </div>
          </div>
        {/block}
        </div>
      </div>

      <footer id="footer">
        {block name='footer'}
          {include file='checkout/_partials/footer.tpl'}
        {/block}
      </footer>
    
    </div>
  
    {block name='javascript_bottom'}
      {include file="_partials/javascript.tpl" javascript=$javascript.bottom}
    {/block}

    {* Start TBCMS file of javascript *}
      {include file="_partials/tbcms-javascript-files.tpl"}
    {* End TBCMS file of javascript *}

    {block name='hook_before_body_closing_tag'}
      {hook h='displayBeforeBodyClosingTag'}
    {/block}

  </body>

</html>
{/strip}
