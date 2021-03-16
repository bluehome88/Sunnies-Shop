{**
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
*}

{strip}
{if isset($prev_page_value) && $prev_page_value}
    <link rel="prev" href="{$prev_page_value|escape:'htmlall':'UTF-8'}">
{/if}
{if isset($next_page_value) && $next_page_value}
<link rel="next" href="{$next_page_value|escape:'htmlall':'UTF-8'}">
{/if}


<!-- Module TbcmsInfiniteScroll for PRODUCTS -->
{if isset($tb_options)}
<script>
var tb_params = {
    product_wrapper : "{$tb_options.product_wrapper|escape:'htmlall':'UTF-8'}",
    product_elem : "{$tb_options.product_elem|escape:'htmlall':'UTF-8'}",
    pagination_wrapper : "{$tb_options.pagination_wrapper|escape:'htmlall':'UTF-8'}",
    next_button : "{$tb_options.next_button|escape:'htmlall':'UTF-8'}",
    views_buttons : "{$tb_options.views_buttons|escape:'htmlall':'UTF-8'}",
    selected_view : "{$tb_options.selected_view|escape:'htmlall':'UTF-8'}",
    method : "{$tb_options.method|escape:'htmlall':'UTF-8'}",
    button_start_page : "{$tb_options.button_start_page|escape:'htmlall':'UTF-8'}",
    button_n_pages : "{$tb_options.button_n_pages|escape:'htmlall':'UTF-8'}",
    active_with_layered : "{$tb_options.active_with_layered|escape:'htmlall':'UTF-8'}",
    loader : "<div id=\"tb-loader\"><p>{$tb_texts.loading_text|escape:'htmlall':'UTF-8'}</p></div>",
    loader_prev : "<div id=\"tb-loader\"><p>{$tb_texts.loading_prev_text|escape:'htmlall':'UTF-8'}</p></div>",
    button : "<button id=\"tb-button-load-products\">{$tb_texts.button_text|escape:'htmlall':'UTF-8'}</button>",
    back_top_button : "<div id=\"tb-back-top-wrapper\"><p>{$tb_texts.end_text|escape:'htmlall':'UTF-8'} <a href=\"#\" class=\"tb-back-top-link\">{$tb_texts.go_top_text|escape:'htmlall':'UTF-8'}</a></p></div>",
    tbcmsinfinitescrollqv_enabled : "{$tb_options.tbcmsinfinitescrollqv_enabled|escape:'htmlall':'UTF-8'}",
    has_facetedSearch : "{$tb_options.has_facetedSearch|escape:'htmlall':'UTF-8'}",
    ps_16 : "{$tb_options.ps_16|escape:'htmlall':'UTF-8'}"
}

// -----------------------------------------------------------
// HOOK CUSTOM
// - After next products displayed
// function tb_hook_after_display_products() {

    // ---------------
    // CUSTOMIZE HERE
    // ---------------

// }
</script>
{/if}
{/strip}
