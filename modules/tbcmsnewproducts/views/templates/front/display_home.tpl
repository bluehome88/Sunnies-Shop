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
{if $main_heading['main_image_status']}
    {$col = 'tbimage-true'}
    {$image = true}
    {if $main_heading['main_image_side'] == 'left'}
        {$image_side = 'left'}
    {else}
        {$image_side = 'right'}
    {/if}
{else}
    {$col = ''}
    {$image = ''}
    {$image_side = ''}
{/if}
{if $dis_arr_result.status && $dis_arr_result.home_status && count($dis_arr_result.data.product_list) > 0}
    <div class="tbcmsnew-product container-fluid bottom-to-top hb-animate-element">
        <div class='tbnew-product-wrapper-box container'>
            <div class="tbnew-product-all-box">
                <div class='tbnew-main-title-wrapper'>
                    {include file='_partials/tbcms-main-title.tpl' main_heading=$main_heading path=$dis_arr_result['path']}
                </div>
                <div class="tbnew-product-offer-banner">
                    {if $image == true && $image_side == 'left'}
                    <div class="tball-product-branner">
                        <a href="{$main_heading.data.link}" title="{l s='New Offer Banner' mod='tbcmsnewproducts'}">
                            <div class="tbbanner-hover-wrapper tball-block-box-shadows">
                                <div class="tbbranner-hover-info-box"></div>
                                <div class='tbbanner-hover'></div>
                                <img src="{$dis_arr_result.path}tiny/{$main_heading.data.image}" data-org-src="{$dis_arr_result.path}{$main_heading.data.image}" alt="{l s='New Offer Banner' mod='tbcmsnewproducts'}">
                                <div class='tbbanner-hover1'></div>
                            </div>
                        </a>
                    </div>
                    {/if}
                    <div class="tbnew-product-content {$col}">
                        <div class="tball-block-box-shadows">
                            <div class="tbnew-product">
                                <div class="products owl-theme owl-carousel tbnew-product-wrapper tbproduct-wrapper-content-box" data-has-image='{if $image == true}true{else}false{/if}'>
                                    {foreach $dis_arr_result.data.product_list as $product}
                                        {include file="catalog/_partials/miniatures/product.tpl" product=$product tb_product_type="new_product"}
                                    {/foreach}
                                </div>
                            </div>
                        </div>
                    </div>
                    {if $image == true && $image_side == 'right'}
                    <div class="tball-product-branner">
                        <a href="{$main_heading.data.link}" title="{l s='New Offer Banner' mod='tbcmsnewproducts'}">
                            <div class="tbbanner-hover-wrapper tball-block-box-shadows">
                                <div class="tbbranner-hover-info-box"></div>
                                <div class='tbbanner-hover'></div>
                                <img src="{$dis_arr_result.path}tiny/{$main_heading.data.image}" data-org-src="{$dis_arr_result.path}{$main_heading.data.image}" alt="{l s='new Offer Banner' mod='tbcmsnewproducts'}">
                                <div class='tbbanner-hover1'></div>
                            </div>
                        </a>
                    </div>
                    {/if}
                    <div class='tbnew-pagination-wrapper tb-pagination-wrapper'>
                        <div class="tbnew-pagination">
                            <div class="tbcmsnew-pagination">
                                <div class="tbcmsnew-next-pre-btn tbcms-next-pre-btn">
                                    <div class="tbcmsnew-prev tbcmsprev-btn" data-parent="tbcmsnew-product"><i class='material-icons'>&#xe5c4;</i></div>
                                    <div class="tbcmsnew-next tbcmsnext-btn" data-parent="tbcmsnew-product"><i class='material-icons'>&#xe5c8;</i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tballproduct-link">
                        <a class="all-product-link" href="{$dis_arr_result.link}">
                            {l s='All New Products' mod='tbcmsnewproducts'} <i class='material-icons'>&#xe315;</i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
{/if}
{/strip}