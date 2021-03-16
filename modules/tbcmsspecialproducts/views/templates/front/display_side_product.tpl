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
<div class="tbcmsleft-special-product tbleft-right-penal-all-block tbleft-right-penal-product-block tball-block-box-shadows bottom-to-top hb-animate-element {$custom_column_side}">
    <div class="tbleft-special-product">

        {include file='_partials/tbcms-left-column-title.tpl' status=$main_title_status title=$main_title}
        
        <div class="tbleft-product-wrapper-info">
            <div class="tbleft-special-wrapper tbleft-product-context-box">
                <div class="products tbleft-product-wrapper wow zoomIn owl-theme owl-carousel">
                    {foreach $products as $product}
                        {include file="catalog/_partials/miniatures/product.tpl" product=$product tb_product_type=$tb_product_type}
                    {/foreach}
                </div>
        

                <div class="tball-product-bottom-link-block">
                    <a class="all-product-link" href="{$link}">
                        {l s='All Special Products' mod='tbcmsspecialproducts'}<i class='material-icons'>&#xe315;</i>
                    </a>
                </div>
            </div>

            <div class='tbleft-special-product-pagination-wrapper tbside-pagination-wrapper' data-custom-column-side='{$custom_column_side}' data-parent='tbcmsleft-special-product'>
                <div class="tbleft-special-product-pagination tbleft-btn-wrapper">
                    <div class="tbleft-special-product-next-pre-btn">
                        <div class="tbleft-special-product-prev tbleft-prev-btn tbcmsprev-btn"><i class='material-icons'>&#xe5c4;</i></div>
                        <div class="tbleft-special-product-next tbleft-next-btn tbcmsnext-btn"><i class='material-icons'>&#xe5c8;</i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{/strip}
