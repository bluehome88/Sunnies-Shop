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
{block name='product_miniature_item'}
<article class="item product-miniature js-product-miniature tball-product-wrapper-info-box" data-id-product="{$product.id_product}" data-id-product-attribute="{$product.id_product_attribute}" itemscope itemtype="http://schema.org/Product">
	<div class="thumbnail-container">
		<div class="tbproduct-wrapper grid">
			{block name='product_thumbnail'}
				<div class="tbproduct-image">
					{if $product.cover}
						<a href="{$product.url}" class="thumbnail product-thumbnail">
							<img src = "{$product.cover.bySize['home_default']['url']}" alt = "{if !empty($product.cover.legend)}{$product.cover.legend}{else}{$product.name}{/if}" data-full-size-image-url = "{$product.cover.large.url}" class="tbproduct-defult-img">
							{if isset($product.images.0.bySize['home_default']['url']) && empty($product.images.0.cover)}
								<img class="tbproduct-hover-img" src="{$product.images.0.bySize['home_default']['url']}" alt="{$product.name}">
							{elseif isset($product.images.1.bySize['home_default']['url']) && empty($product.images.1.cover)}
								<img class="tbproduct-hover-img" src="{$product.images.1.bySize['home_default']['url']}" alt="{$product.name}">
							{/if}
						</a>
					{else}
						<a href="{$product.url}" class="thumbnail product-thumbnail">
							<img src = "{$no_picture_image}" />
						</a>
					{/if}
					{block name='product_flags'}
						<ul class="product-flags tbproduct-online-new-wrapper">
							{foreach from=$product.flags item=flag}
								{if $flag.type == 'online-only' || $flag.type == 'new'}
									<li class="product-flag {$flag.type}">{$flag.label}</li>
								{/if}
							{/foreach}
						</ul>
						<ul class="product-flags tbproduct-sale-pack-wrapper">
							{foreach from=$product.flags item=flag}
								{if $flag.type == 'on-sale' || $flag.type == 'pack'}
									<li class="product-flag {$flag.type}">{$flag.label}</li>
								{/if}
							{/foreach}
						</ul>
					{/block}
					<div class='tbproduct-hover-btn'>
						<div class="{* highlighted-informations{if !$product.main_variants} no-variants{/if} *} tbproduct-quick-btn">
							{block name='quick_view'}
								<a class="quick-view" href="#" data-link-action="quickview" data-toggle="tbtooltip" data-placement="top" data-html="true" title="{l s='Quick View' d='Shop.Theme.Actions'}" data-original-title="{l s='Quick View' d='Shop.Theme.Actions'}">
									<div class="tbproduct-quick-icon">
										<i class="material-icons search">&#xE8B6;</i>
									</div>
									<div class="tbproduct-quick-lable">
										{l s='Quick View' d='Shop.Theme.Actions'}
									</div>
								</a>
							{/block}
						</div>
						<div class="tbproduct-cart-btn">
							<form action="{$cart_page_url}" method="post">
								<input type="hidden" name="id_product" value="{$product.id_product}">
								<input type="hidden" name="qty" value="1">
								{if !empty($product.is_customizable) && count($product.customizations.fields)}
								<input type="hidden" name="id_customization" value="{$product.id_customization}" id="product_customization_id">
								{/if}
								<input type="hidden" name="token" value="{$static_token}">
								<button data-button-action="add-to-cart" type="submit" class="btn add-to-cart tbproduct-add-to-cart {if !$product.add_to_cart_url}tbproduct-out-of-stock disable{/if}" title="{if !$product.add_to_cart_url}{l s='Out Of Stock' d='Shop.Theme.Actions'}{else}{l s='Add To Cart' d='Shop.Theme.Actions'}{/if}" {if !$product.add_to_cart_url}disabled{/if} data-toggle="tbtooltip" data-placement="top" data-html="true" data-original-title="{if !$product.add_to_cart_url}{l s='Out Of Stock' d='Shop.Theme.Actions'}{else}{l s='Add To Cart' d='Shop.Theme.Actions'}{/if}">
									<i class='material-icons'>&#xe8cc;</i>
									<span class="tbproduct-add-to-cart-label">{if !$product.add_to_cart_url}{l s='Out Of Stock' d='Shop.Theme.Actions'}{else}{l s='Add To Cart' d='Shop.Theme.Actions'}{/if} </span>
								</button>
							</form>
						</div>
						{hook h='displayProductCompareProductList' product=$product}
						{hook h='displayWishlistBtnProductList' product=$product}
					</div>
					{if !empty($product.specific_prices.from) && !empty($product.specific_prices.to) && $product.specific_prices.from != '0000-00-00 00:00:00' && $product.specific_prices.to != '0000-00-00 00:00:00'}
				  		{include file='catalog/_partials/miniatures/product-timer.tpl' timer=$product.specific_prices.to}
				    {/if}
				</div>
			{/block}	
			<div class="tbproduct-info-box-wrapper">
				<div class="product-description">
					{block name='product_name'}
						<div class="tbproduct-name">
							<div class="product-title" itemprop="name">
								<a href="{$product.url}"><h6>{$product.name}</h6></a>
							</div>
							<div class="tbproduct-cat-name">{$product.category_name}</div>
						</div>
					{/block}
					{* Start Product Comment *}
					{hook h='displayReviewProductList' product=$product}
					{* End Product Comment *}
					{block name='product_price_and_shipping'}
						<div class="tb-product-price">
							<div class="tbproduct-name-price-wrapper">
								{if $product.show_price}
									<div class="product-price-and-shipping">
										<span itemprop="price" class="price">{$product.price}</span>
										{if $product.has_discount}
											{hook h='displayProductPriceBlock' product=$product type="old_price"}
											<span class="sr-only">{l s='Regular price' d='Shop.Theme.Catalog'}</span>
											 {if $product.discount_type === 'percentage'}
												<span class="discount-percentage discount-product tbproduct-discount-price">{$product.discount_percentage}{l s=' off' d='Shop.Theme.Catalog'}</span>
											{elseif $product.discount_type === 'amount'}
												<span class="discount-amount discount-product tbproduct-discount-price">{$product.discount_amount_to_display} {l s=' off' d='Shop.Theme.Catalog'}</span>
											{/if} 
										{/if}
										{hook h='displayProductPriceBlock' product=$product type="before_price"}
										<span class="sr-only">{l s='Price' d='Shop.Theme.Catalog'}</span>
										<span class="regular-price">{$product.regular_price}</span>
										{hook h='displayProductPriceBlock' product=$product type='unit_price'}
										{hook h='displayProductPriceBlock' product=$product type='weight'}
									</div>
								{/if}
							</div>
						</div>
					{/block}
				</div>
				<div class="tb-product-price-info-box">  		
					{* Start Product Stock Indicator *}
					<div class='tbcmsstock-indicator-wraper'>
						{hook h='displayProductListStockIndicator' product=$product}
					</div>
					{* End Product Stock Indicator *}
					{if Configuration::get('TBCMSCUSTOMSETTING_PRODUCT_COLOR') == '1'}
						{block name='product_variants'}
							{if $product.main_variants}
								<div class="tbproduct-color">
								  	{foreach from=$product.main_variants item=color_info}
										<div class='tbproduct-color-wrapper'>
										  	<a href="{$color_info.url}">
											    <div class="tbproduct-color-box-border">
											          <div class='tbporoduct-color-box' style='{if $color_info.html_color_code != ""}background-color: {$color_info.html_color_code};{else}background-image: url({$color_info.texture});{/if}'></div>
										        </div>
											</a>
										</div>
								  	{/foreach}
								</div>
							{/if}
						{/block}
					{/if}
				</div>
			</div>
		</div>
	</div>
</article>
{/block}
{/strip}