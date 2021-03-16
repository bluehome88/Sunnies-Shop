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
<div class="tbadmintab-product">
	<div class="tbadmintab-product-all-tabs">
		<div tab-number='#fieldset_0' class="tbadmintab-product-tab {if $tab_number == '#fieldset_0'}tbadmintab-product-active{/if}">{l s='Tab Product Setting' mod='tbcmstabproducts'}</div>
		<div tab-number='#fieldset_1_1' class="tbadmintab-product-tab {if $tab_number == '#fieldset_1_1'}tbadmintab-product-active{/if}">{l s='Featured Product' mod='tbcmstabproducts'}</div>
		<div tab-number='#fieldset_2_2' class="tbadmintab-product-tab {if $tab_number == '#fieldset_2_2'}tbadmintab-product-active{/if}">{l s='New Product' mod='tbcmstabproducts'}</div>
		<div tab-number='#fieldset_3_3' class="tbadmintab-product-tab {if $tab_number == '#fieldset_3_3'}tbadmintab-product-active{/if}">{l s='Best Seller' mod='tbcmstabproducts'}</div>
		<div tab-number='#fieldset_4_4' class="tbadmintab-product-tab {if $tab_number == '#fieldset_4_4'}tbadmintab-product-active{/if}">{l s='Special Product' mod='tbcmstabproducts'}</div>
	</div>
</div>
<div>
	<input type="hidden" name="tbcmstab_product_tab_number" id='tbcmstab-product-tab-number' value="{$tab_number}">
</div>
{/strip}