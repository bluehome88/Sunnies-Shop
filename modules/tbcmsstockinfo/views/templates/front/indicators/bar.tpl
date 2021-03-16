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
<div class="tb-indicator tb-bar{if $useProgressiveColors} tb-colors{/if}">
    <div class="tb-outer" data-toggle="tbtooltip" data-placement="top" data-html="true" {if isset($stockLevelStatus)}title="<div class='text-center'>{$stockIndicatorTrans.stockStatus|escape:'html':'UTF-8'}: <b>{$stockLevelStatus|escape:'html':'UTF-8'}</b></div>"{/if} >
        <div class="tb-inner tb-lvl-{$stockLevel|escape:'html':'UTF-8'}"></div>
    </div>
    {if $isItemsDisplayable}
        <div class="tb-items">
            {if ! $hasMixedQty}
                {if $productItems < 0}0{else}{$productItems|escape:'html':'UTF-8'}{/if}
                {$stockIndicatorTrans.items|escape:'html':'UTF-8'}
            {else}
                {$stockIndicatorTrans.mixedItems|escape:'html':'UTF-8'}
            {/if}
        </div>
    {/if}
</div>
{/strip}