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
<div id="_desktop_cart" class="tbcms-header-cart">
    <div class="blockcart cart-preview {if $cart.products_count > 0}active{else}inactive{/if} tb-header-cart" data-refresh-url="{$refresh_url}">
        <div class="tbheader-cart-wrapper">
            <div class='tbheader-cart-btn-wrapper'>
                <a rel="nofollow" href="JavaScript:void(0);" data-url='{$cart_url}' title='{l s="Cart" d="Shop.Theme.Checkout"}'>
                    <div class="tbcart-icon-text-wrapper">
                        <div class="tb-cart-icon tbheader-right-icon">
                            <i class="material-icons shopping-cart">&#xe8f8;</i>
                        </div> 
                        <div class="tb-cart-cart-inner">
                            <span class="tbcart-lable">{l s='Cart - ' d='Shop.Theme.Checkout'}</span> 
                            <span class="tb-cart-title">{count($cart.products)}</span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="tbcmscart-show-dropdown">
                {if $cart.products_count > 0}
                <div class="tbcart-product-list">
                    <div class="tbcart-product-totle">
                        {l s='Your Cart: ' d='Shop.Theme.Checkout'} {count($cart.products)} {if count($cart.products) == 1}{l s='Item' d='Shop.Theme.Checkout'}{else}{l s='Items' d='Shop.Theme.Checkout'}{/if}
                    </div>

                    <div class="tbcart-product-content-box tbscroll-container">
                        {foreach from=$cart.products item=product}
                        <div class="tbcart-product-wrapper items">
                            {include 'module:ps_shoppingcart/ps_shoppingcart-product-line.tpl' product=$product}
                        </div>
                        {/foreach}
                    </div>
                    <div class="tbcart-product-list-total-info">
                        <div class="tbcart-product-list-subtotal-prod">
                            <span class="tbshoping-cart-subtotal">{l s='Subtotal' d='Shop.Theme.Checkout'}</span>
                            <span class="tbcart-product-price">{$cart.subtotals.products.value}</span>
                        </div>
                       {* <div class="tbcart-product-list-subtotal-shipping">
                            <span class="tbshoping-cart-shipping">{l s='Shipping' d='Shop.Theme.Checkout'}</span>
                            <span class="tbcart-product-price">{$cart.subtotals.shipping.value}</span>
                        </div>
                        <div class="tbcart-product-list-subtotal-tax">
                            <span class="tbshoping-cart-tax">{l s='Tax' d='Shop.Theme.Checkout'}</span>
                            <span class="tbcart-product-price">{$cart.subtotals.tax.value}</span>
                        </div>
                        <div class="tbcart-product-list-subtotal-excluding-text">
                            <span class="ttshoping-cart-total">{l s='Total' d='Shop.Theme.Checkout'}</span>
                            <span class="tbcart-product-price">{$cart.totals.total_excluding_tax.value}</span>
                        </div> *}
                    </div>
                </div>
                <div class="tbcart-product-list-btn-wrapper">
                    <div class="tbcart-product-list-viewcart">
                        <a href="{$cart_url}">{l s='View Cart' d='Shop.Theme.Checkout'}</a>
                    </div>

                    <div class="tbcart-product-list-checkout">
                        <a href="{$link->getPageLink('order', null, $language.id)}">{l s='Process To CheckOut' d='Shop.Theme.Checkout'}</a>
                    </div>
                </div>
                {else}
                <div class="tbcart-no-product">
                    <div class='tbcart-no-product-label'>{l s='No Product Add in Cart' d='Shop.Theme.Checkout'}</div>
                </div>
                {/if}
            </div>

        </div>
    </div>
</div>
{/strip}
