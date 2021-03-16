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
<article class="tbleft-right-product-slider">
	<div class="thumbnail-container">
		<div class="tbproduct-wrapper">
			{block name='product_thumbnail'}
				<div class="tbproduct-image col-xl-4 col-lg-4 col-md-3 col-sm-3 col-xs-3">
					{if $product.cover}
						<a href="{$product.url}" class="thumbnail product-thumbnail">
              				<img src = "{$product.cover.bySize.small_default.url}" alt = "{if !empty($product.cover.legend)}{$product.cover.legend}{else}{$product.name}{/if}" data-full-size-image-url = "{$product.cover.large.url}">
							{if isset($product.images.0.bySize.small_default.url) && empty($product.images.0.cover)}
				              	<img class="tbproduct-hover-img" src="{$product.images.0.bySize.small_default.url}" alt="{$product.name}">
				            {elseif isset($product.images.1.bySize.small_default.url) && empty($product.images.1.cover)}
				              	<img class="tbproduct-hover-img" src="{$product.images.1.bySize.small_default.url}" alt="{$product.name}">
				            {/if}
						</a>
					{else}
						<a href="{$product.url}" class="thumbnail product-thumbnail">
							<img src = "{$urls.no_picture_image.bySize.small_default.url}" />
						</a>
					{/if}
				</div>
			{/block}
			<div class="product-description col-xl-8 col-lg-8 col-md-9 col-sm-9 col-xs-9">
				{block name='product_name'}
					<div class="tbproduct-name">
						<div class="product-title">
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
						{if $product.show_price}
							<div class="product-price-and-shipping">
								<span itemprop="price" class="price">{$product.price}</span>
								{if $product.has_discount}
									<span class="regular-price">{$product.regular_price}</span>
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
								{hook h='displayProductPriceBlock' product=$product type='unit_price'}
								{hook h='displayProductPriceBlock' product=$product type='weight'}
							</div>
						{/if}
					</div>
				{/block}
			</div>
		</div>
	</div>
</article>
{/block}
{/strip}