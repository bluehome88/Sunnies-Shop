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
<div class="tbcmscross-selling-product container-fluid">
    <div class='tbcross-selling-product-wrapper-box container'>
        <div class="tbcross-selling-product-all-box">
            <div class="tbcross-selling-product-content">
                <div class="tball-block-box-shadows">
                    <div class="tbcross-selling-main-title-wrapper">
                        <div class='tbcmsmain-title-wrapper'>
                            <div class="tbcms-main-title">
                                <div class='tbmain-title'>
                                    <h2>{l s='Customers who bought this product also bought:' d='Shop.Theme.Catalog'}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                        
                    <div class="tbcross-selling-product">
                        <div class="products owl-theme owl-carousel tbcross-selling-product-wrapper tbproduct-wrapper-content-box">
                            {foreach $products as $product}
                                {include file="catalog/_partials/miniatures/product.tpl" product=$product tb_product_type="cross_selling_product"}
                            {/foreach}
                        </div>
                    </div>
                </div>
            </div>
            
            <div class='tbcross-selling-pagination-wrapper tb-pagination-wrapper'>
                <div class="tbfeature-pagination">
                    <div class="tbcmscross-selling-pagination">
                        <div class="tbcmscross-selling-next-pre-btn tbcms-next-pre-btn">
                            <div class="tbcmscross-selling-prev tbcmsprev-btn" data-parent="tbcmscross-selling-product">
                                <i class='material-icons'>&#xe5c4;</i>
                            </div>
                            <div class="tbcmscross-selling-next tbcmsnext-btn" data-parent="tbcmscross-selling-product">
                                <i class='material-icons'>&#xe5c8;</i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{/strip}
