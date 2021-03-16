{**
* 2007-2019 PrestaShop 
* 
* NOTICE OF LICENSE 
* * This source file is subject to the Academic Free License (AFL 3.0) 
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
* @author PrestaShop SA <contact@prestashop.com>
* @copyright 2007-2019 PrestaShop SA 
* @license http://opensource.org/licenses/afl-3.0.php Academic Free License (AFL 3.0) 
* International Registered Trademark & Property of PrestaShop SA 
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

{if $dis_arr_result.status}
<div class="tbcmstab-title-product container-fluid bottom-to-top hb-animate-element">
    <div class="tbtab-product-wrapper container">
        <div class="tbtab-product-all-box">
            <div class="tbtab-product-branner">

                <div class="tbtab-product-all-pagination">
                	<div class="tball-block-box-shadows">
                        <div class="tbtab-product">
                            <div class='tbtab-main-title-wrapper'>
                                {include file='_partials/tbcms-main-title.tpl' main_heading=$main_heading path=$dis_arr_result['path']}
                                <div class="tbtab-title-wrapper">
                                    <div class="tbtab-title">
                                        <ul class="tbtabs-products">
                                            {$tmp = true}
                                            {foreach $dis_arr_result.data as $data}
                                                <li class="tbtab-name tab-index {if $tmp}active{/if}" data-tab-data='{$data.tab_name_id}' data-tab-paging='{$data.tab_name_class_pagination}' data-tab-data-slider-class='{$data.tab_name_class_slider}'><span>{$data.tab_name}</span></li>
                                                {$tmp = false}
                                            {/foreach}
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class='tbtab-product-list-wrapper'>
                            {if $image == true && $image_side == 'left'}
                                <div class="tball-product-branner">
                                    <div class="tball-block-box-shadows">
                                        <a href="{$main_heading.data.link}" title="{l s='Tab Product' mod='tbcmstabproducts'}">
                                            <div class="tbbanner-hover-wrapper">
                                                <div class='tbbanner-hover'></div>
                                                <img src="{$dis_arr_result.path}tiny/{$main_heading.data.image}" data-org-src="{$dis_arr_result.path}{$main_heading.data.image}" alt="{l s='Tab Product' mod='tbcmstabproducts'}">
                                                <div class='tbbanner-hover1'></div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            {/if}
                            <div class="tball-product-content  {$col}">
                                {$tmp = true}
                                {foreach $dis_arr_result.data as $tab_products}
                                    <div id="{$tab_products.tab_name_id}" class="tbcmstab-product {if $tmp}active{/if} {$tab_products.tab_name_class_slider} tbcmstab-product-detail">
                                        <div class="products owl-theme owl-carousel tbproduct-wrapper-content-box tball-tab-product-block {$tab_products.tab_name_class_slider}" data-has-image='{if $image == true}true{else}false{/if}'> 
                                            {if Configuration::get('TBCMSCUSTOMSETTING_TAB_PRODUCT_ROW')}
                                                {$count = 1}{* for double row *}
                                                {$double_row = true}
                                                {$single_row = false}
                                            {else}
                                                {$count = 5}{* for single row *}                                                
                                                {$double_row = false}
                                                {$single_row = true}
                                            {/if}

                                            {foreach $tab_products.product_list as $product}
                                                {if $count == '1'}
                                                    <div class="tbtabproduct-main-block item">
                                                    {$double_row = true}
                                                {/if}

                                                {include file="catalog/_partials/miniatures/product.tpl" product=$product tb_product_type='tab_product' tab_slider=true double_row=$double_row}
                                                {$double_row = false}

                                                {if $count == '2'}
                                                    </div>
                                                    {$count = '0'}
                                                {/if}

                                                {$count = $count + 1}
                                            {/foreach}

                                            {if $count != '1' && !$single_row}
                                            </div>
                                            {/if}
                                        </div>

                                        <div class='tbtab-pagination-wrapper tb-pagination-wrapper'>
                                            <div class="{$tab_products.tab_name_class_pagination}-pagination tbtab-pagination {if $tmp}active{/if}"></div>
                                            <div class="{$tab_products.tab_name_class_pagination}-pagination tbtab-pagination {if $tmp}active{/if}">
                                                <div class="{$tab_products.tab_name_class_pagination}-pagination-next-pre-btn tbcms-next-pre-btn">
                                                    <div class="{$tab_products.tab_name_class_slider}-prev tbcmsprev-btn" data-parent="{$tab_products.tab_name_id}">
                                                        <i class='material-icons'>&#xe5c4;</i>
                                                    </div>
                                                    <div class="{$tab_products.tab_name_class_slider}-next tbcmsnext-btn"  data-parent="{$tab_products.tab_name_id}">
                                                        <i class='material-icons'>&#xe5c8;</i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {$tmp = false}
                                {/foreach}
                            </div>
                        </div>
                    </div>
                </div>

                {if $image == true && $image_side == 'right'}
                    <div class="tball-product-branner">
                    	<div class="tball-block-box-shadows">
                            <a href="{$main_heading.data.link}" title="{l s='Tab Product' mod='tbcmstabproducts'}">
                                <div class="tbbanner-hover-wrapper">
                                    <div class='tbbanner-hover'></div>
                                    <img src="{$dis_arr_result.path}tiny/{$main_heading.data.image}" data-org-src="{$dis_arr_result.path}{$main_heading.data.image}" alt="{l s='Tab Product' mod='tbcmstabproducts'}">
                                    <div class='tbbanner-hover1'></div>
                                </div>
                            </a>
                        </div>
                    </div>
                {/if}

            </div>
            
        </div>
    </div>
</div>
{/if}
{/strip}
