{**
 * 2015 Adilis
 *
 * With the "Exchange Order" module, quickly manage your exchanged products.
 * In one interface, select the product to be returned and the product that will replace it,
 * confirm the exchange, and the module will take care of it all: create the return, generate
 * a credit and a voucher, and create an order corresponding to the exchange by applying
 * the voucher and requesting payment of the balance from your client if necessary.
 *
 *  @author    Adilis <support@adilis.fr>
 *  @copyright 2015 SAS Adilis
 *  @license   http://www.adilis.fr
 *}

{* Assign product price *}
{if ($order->getTaxCalculationMethod() == $smarty.const.PS_TAX_EXC)}
	{assign var=product_price value=($product['unit_price_tax_excl'] + $product['ecotax'])}
{else}
	{assign var=product_price value=$product['unit_price_tax_incl']}
{/if}
{if isset($product['customized_product_quantity'])}
	{assign var=customized_product_quantity value=$product['customized_product_quantity']}
{else}
	{assign var=customized_product_quantity value=0}
{/if}

{if (int)$product['product_quantity'] - (int)$customized_product_quantity - (int)$product['product_quantity_refunded'] - (int)$product['product_quantity_return'] > 0}
	{assign var=is_available_for_exchange value=true}
{else}
	{assign var=is_available_for_exchange value=false}
{/if}



{if ($product['product_quantity'] > $customized_product_quantity)}
<tr class="product-line-row {if !$product['has_exchange'] && (!$is_available_for_exchange || $orderexchange->active)}product-line-disabled{/if}">
	<td class="text-center">
		{if $is_available_for_exchange && !$orderexchange->active}
			<input type="checkbox" name="orderDetailBox[]" value="{$product['id_order_detail']|intval}" {if $product['has_exchange']}checked="checked"{/if} class="noborder">
		{elseif $product['has_exchange'] && $orderexchange->active}
			<span class="badge badge-success"><big class="icon icon-check fa fa-check"></big></span>
		{else}
			--
		{/if}
	</td>
	<td class="text-center">{if isset($product.image) && $product.image->id}{$product.image_tag}{* HTML ouput, no escape necessary *}{/if}</td>
	<td>
		{$product['product_name']|escape:'htmlall':'UTF-8'}<br />
		{if $product.product_reference}{l s='Reference number:' mod='orderexchange'} {$product.product_reference|escape:'htmlall':'UTF-8'}<br />{/if}
		{if $product.product_supplier_reference}{l s='Supplier reference:' mod='orderexchange'} {$product.product_supplier_reference|escape:'htmlall':'UTF-8'}{/if}
	</td>
	<td class="text-right">{displayPrice price=$product_price currency=$currency->id}</td>
	<td class="text-center">
		{assign var=quantity_left value=((int)$product['product_quantity'] - (int)$customized_product_quantity)}
		{if !$orderexchange->active}
			<span class="product_quantity_show{if $quantity_left > 1} badge{/if}{if $product['has_exchange']} hide{/if}" >{$quantity_left|intval}</span>
			<div class="form-group form-group-fixed product_quantity {if !$product['has_exchange']}hide{/if}">
				<div class="input-group">
					<input type="text" name="return_quantity[{$product['id_order_detail']|intval}]" class="return_quantity" data-order-price-wt="{$product['unit_price_tax_incl']|floatval}" data-order-price="{$product_price|floatval}" value="{$product['exchange']['return_quantity']|intval}">
					<div class="input-group-addon">{$quantity_left|intval}</div>
				</div>
			</div>
		{else}
			<span class="product_quantity_show">{$product['exchange']['return_quantity']|intval}</span>
		{/if}
	</td>
	<td class="text-right">
		{displayPrice price=(Tools::ps_round($product_price, 2) * ($product['product_quantity'] - $product['customizationQuantityTotal'])) currency=$currency->id}
	</td>
	</tr>
	{if $is_available_for_exchange || ($orderexchange->active && $product['has_exchange'])}
	<tr id="product_exchange_{$product['id_order_detail']|intval}" class="odd" {if !$product['has_exchange']}style="display:none;"{/if} data-id-order-detail="{$product['id_order_detail']|intval}">
			<td></td>
			<td class="text-center"><big class="icon icon-exchange fa fa-exchange"></big></td>
			<td>
				<div class="search_product {if $product['has_exchange'] || $orderexchange->active}hide{/if}">
					<div class="" class="form-group">
						<label class="control-label">{l s='Search' mod='orderexchange'} : </label>
						<div class="form-group form-group-fixed form-group-fixed-large">
							<div class="input-group">
								<input type="text" class="search_product_input" value="{$product['exchange']['name']|escape:'htmlall':'UTF-8'}" />
								<span class="input-group-addon"><i class="icon-search fa fa-search"></i></span>
							</div>
						</div>
					</div>

					<div class="search_products_results clearfix">
						<div class="" class="form-group">
							<label class="control-label">{l s='Products' mod='orderexchange'} : </label>
							<select name="products[{$product['id_order_detail']|intval}]" class="search_product_list" class="control-form">
								<option value="{$product['exchange']['id_product']|intval}" data-price="{$product['price']|floatval}"  data-quantity="{$product['quantity']|intval}">{$product['exchange']['name']|escape:'htmlall':'UTF-8'}{if !$product['exchange']['id_product_attribute']} - {displayPrice price=$product['exchange']['formatted_price'] currency=$currency->id}{/if}</option>
							</select>
						</div>
					</div>
					<div class="search_products_results clearfix">
						<div class="search_attributes_list_wrapper {if !$product['exchange']['id_product_attribute']}hide{/if}">
							<label class="control-label">{l s='Combinations' mod='orderexchange'}</label>
							<span class="search_attributes_list">
								{if $product['exchange']['id_product_attribute']}
								<select class="search_attribute_list ipa_{$product['exchange']['id_product']|intval}" name="products_attributes_{$product['exchange']['id_product']|intval}[{$product['id_order_detail']|intval}]">
									{foreach from=$product['exchange']['combinations'] item=combination key=id_product_attribute}
										<option {if $id_product_attribute|intval == $product['exchange']['id_product_attribute']|intval}selected="selected"{/if} value="{$id_product_attribute|intval}" data-price="{$combination['price']|floatval}" data-quantity="{$combination['quantity']|intval}">{$combination['attributes']|escape:'htmlall':'UTF-8'} - {displayPrice price=$combination['formatted_price'] currency=$currency->id}</option>
									{/foreach}
								</select>
								{/if}
							</span>
						</div>
					</div>
					<input type="button" class="btn btn-primary button" value="{l s='Choose this product' mod='orderexchange'}" />
				</div>
				<div class="selected_product {if !$product['has_exchange']}hide{/if}">
					<input type="hidden" name="id_product[{$product['id_order_detail']|intval}]" value="{if $product['has_exchange']}{$product['exchange']['id_product']|intval}{/if}" class="id_product_selected" />
					<input type="hidden" name="id_product_attribute[{$product['id_order_detail']|intval}]" value="{if $product['has_exchange']}{$product['exchange']['id_product_attribute']|intval}{/if}" class="id_product_attribute_selected" />
					<strong class="product_name">{$product['exchange']['name']|escape:'htmlall':'UTF-8'}{if !$product['exchange']['id_product_attribute']} - {displayPrice price=$product['exchange']['formatted_price'] currency=$currency->id}{/if}</strong><br/>
					<span class="product_attributes">
					{foreach from=$product['exchange']['combinations'] item=combination key=id_product_attribute}
						{if $id_product_attribute|intval == $product['exchange']['id_product_attribute']}
							{$combination['attributes']|escape:'htmlall':'UTF-8'}
						{/if}
					{/foreach}
					</span><br/>
					{if !$orderexchange->active}
						<input type="button" class="btn btn-default button" value="{l s='Choose another product' mod='orderexchange'}" />
					{/if}
				</div>
			</td>
			<td class="text-right">
				{if !$orderexchange->active}
					<div class="form-group form-group-fixed product_price {if !$product['has_exchange']}hide{/if}">
						 <div class="input-group">
								<input type="text" name="product_unit_price[{$product['id_order_detail']|intval}]" data-tax-rate="{$product['tax_rate']|floatval}" class="product_unit_price" value="{$product['exchange']['price']|floatval}">
								<div class="input-group-addon">{$currency->sign|escape:'htmlall':'UTF-8'}</div>
							</div>
						</div>
					</div>
					<br/>
					<br/>
				{else}
					{displayPrice price=$product['exchange']['price'] currency=$currency->id}
				{/if}
			</td>
			<td class="text-center">
				{if !$orderexchange->active}
					<div class="product_qty {if !$product['has_exchange']}hide{/if}">
						<div class="input-group fixed-width-md">
							<input type="text"  name="product_quantity[{$product['id_order_detail']|intval}]" class="cart_quantity" value="{(int)$product['exchange']['quantity']|intval}">
							<div class="input-group-btn">
								<a href="#" class="btn btn-default increaseqty_product" rel="{$product['id_order_detail']|intval}">
									<i class="icon-caret-up fa fa-caret-up"></i>
								</a>
								<a href="#" class="btn btn-default decreaseqty_product" rel="{$product['id_order_detail']|intval}">
									<i class="icon-caret-down fa fa-caret-down"></i>
								</a>
							</div>
						</div>
						<span class="reserve_stock_wrapper {if !$product['has_exchange']}hide{/if}">
							<label for="reserve_stock_{$product['id_order_detail']|intval}" class="reserve_stock">
								<input type="checkbox" id="reserve_stock_{$product['id_order_detail']|intval}" name="reserve_stock[{$product['id_order_detail']|intval}]" value="1" {if $product['exchange']['reserve_stock']|intval==1}checked="checked"{/if}>
								{l s='Reserve stock' mod='orderexchange'}
							</label>
						</span>
						{l s='Stock : ' mod='orderexchange'}<span class="available_quantity">{$product['exchange']['available_quantity']|intval}</span>
						<br/>
					</div>
				</div>
				{else}
					{(int)$product['exchange']['quantity']|intval}
				{/if}
			</td>
			<td class="text-right">
				<div class="total_product_wrapper {if !$product['has_exchange']}hide{/if}">
					{math assign="balance" equation='(y*z)-(w*x)' w=$product_price x=$product['exchange']['return_quantity'] y=$product['exchange']['price'] z=$product['exchange']['quantity']}
					{math assign="total" equation='x*z' x=$product['exchange']['price'] z=$product['exchange']['quantity']}
					<span class="total">{displayPrice price=$total currency=$currency->id}</span>
					<br/>
					<small class="balance badge {if $balance < 0}badge-warning{else}badge-success{/if}">{displayPrice price=$balance currency=$currency->id}</small>
				</div>
			</td>
	</tr>
	{/if}
{/if}
