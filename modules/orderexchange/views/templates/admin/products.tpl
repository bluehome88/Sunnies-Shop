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

<script type="text/javascript">
	var currency_format = {$currency->format|intval};
	var currency_sign = '{$currency->sign|escape:'htmlall':'UTF-8'}';
	var currency_blank = '{$currency->blank|escape:'htmlall':'UTF-8'}';
	var priceDisplayPrecision = '{if $currency->decimals}2{else}0{/if}';
	var id_customer = {$order->id_customer|intval};
	var id_address_delivery = {$id_address_delivery|intval};
	var id_address_invoice = {$id_address_invoice|intval};
	var id_cart = {$orderexchange->id_cart|intval};
	var token_admincarts = "{getAdminToken tab='AdminCarts'}";
	var use_tax = {if $order->getTaxCalculationMethod()|intval == 1}false{else}true{/if};
</script>

<div id="display_err" class="alert alert-danger hide"></div>
<h2>{l s='Order selected:' mod='orderexchange'} {if $display_orders=='reference'}{$order->reference|escape:'htmlall':'UTF-8'}{else}{$order->id|intval}{/if}, {$customer_name|escape:'htmlall':'UTF-8'}</h2>
<div class="panel">
	<div class="panel-heading">
		<i class="icon-shopping-cart"></i>
		{l s='Products' mod='orderexchange'} <span class="badge">{$products|@count|intval}</span>
	</div>
	{capture "TaxMethod"}
		{if ($order->getTaxCalculationMethod() == $smarty.const.PS_TAX_EXC)}
			{l s='tax excluded.' mod='orderexchange'}
		{else}
			{l s='tax included.' mod='orderexchange'}
		{/if}
	{/capture}
	{if ($order->getTaxCalculationMethod() == $smarty.const.PS_TAX_EXC)}
		<input type="hidden" name="TaxMethod" value="0">
	{else}
		<input type="hidden" name="TaxMethod" value="1">
	{/if}
	<div class="table-responsive">
		<table class="table" id="orderProducts">
			<thead>
				<tr>
					<th>--</th>
					<th></th>
					<th><span class="title_box ">{l s='Product' mod='orderexchange'}</span></th>
					<th class="text-right">
						<span class="title_box ">{l s='Unit Price' mod='orderexchange'}</span>
						<small class="text-muted">{$smarty.capture.TaxMethod|escape:'htmlall':'UTF-8'}</small>
					</th>
					<th class="text-center"><span class="title_box ">{l s='Qty' mod='orderexchange'}</span></th>
					<th class="text-right">
						<span class="title_box ">{l s='Total' mod='orderexchange'}</span>
						<small class="text-muted">{$smarty.capture.TaxMethod|escape:'htmlall':'UTF-8'}</small>
					</th>
				</tr>
			</thead>
			<tbody>
			{foreach from=$products item=product key=k}
				{* Include customized datas partial *}
				{*include file='controllers/orders/_customized_data.tpl'*}
				{* Include product line partial *}
				{include file='./product_line.tpl'}
			{/foreach}
			</tbody>
		</table>
	</div>
	<div class="clear">&nbsp;</div>
	<div class="row">
		<div class="col-xs-6">
			<div class="alert alert-warning">
				{l s='For this customer group, prices are displayed as: [1]%s[/1]' sprintf=[$smarty.capture.TaxMethod] tags=['<strong>'] mod='orderexchange'}
			</div>
			<div class="alert alert-info hide" id="discount_exclude_shipping">
				{l s='Unfortunately, return voucher can not include shipping costs' mod='orderexchange'}
			</div>
		</div>
		<div class="col-xs-6">
			<div class="panel panel-total">
				<div class="table-responsive">
					<table class="table">
						<tr id="total_products">
							<td class="text-right">{l s='Products:' mod='orderexchange'}</td>
							<td class="amount text-right nowrap">
								{if ($order->getTaxCalculationMethod() == $smarty.const.PS_TAX_EXC)}
									{displayPrice price=$orderexchange->total_products_tax_excl currency=$currency->id}
								{else}
									{displayPrice price=$orderexchange->total_products_tax_incl currency=$currency->id}
								{/if}
							</td>
						</tr>
						<tr id="total_shipping" class="{if !$orderexchange->total_shipping_tax_incl|intval}hide{/if}">
							<td class="text-right">{l s='Shipping:' mod='orderexchange'}</td>
							<td class="amount text-right nowrap" >
								{if ($order->getTaxCalculationMethod() == $smarty.const.PS_TAX_EXC)}
									{displayPrice price=$orderexchange->total_shipping_tax_excl currency=$currency->id}
								{else}
									{displayPrice price=$orderexchange->total_shipping_tax_incl currency=$currency->id}
								{/if}
							</td>
						</tr>
						{if ($order->getTaxCalculationMethod() == $smarty.const.PS_TAX_EXC)}
							<tr id="total_taxes">
								<td class="text-right">{l s='Taxes:' mod='orderexchange'}</td>
								<td class="amount text-right nowrap" >{displayPrice price=$orderexchange->total_tax currency=$currency->id}</td>
							</tr>
						{/if}
						<tr id="total_discount">
							<td class="text-right">{l s='Return discount:' mod='orderexchange'}</td>
							<td class="amount text-right nowrap" >
								{if $orderexchange->total_discounts >= $orderexchange->total_products_tax_incl + $orderexchange->total_shipping_tax_incl || $orderexchange->total_discounts <= $orderexchange->total_products_tax_incl}
									{displayPrice price=$orderexchange->total_discounts*-1 currency=$currency->id}
								{else}
									{displayPrice price=$orderexchange->total_products_tax_incl*-1 currency=$currency->id}<br/>
									<small class="text-muted">{displayPrice price=$orderexchange->total_products_tax_incl-$orderexchange->total_discounts currency=$currency->id}</small>
								{/if}
							</td>
						</tr>
						<tr id="total_order">
							<td class="text-right"><strong>{l s='Total balance:' mod='orderexchange'}</strong></td>
							<td class="amount text-right nowrap">
								<span class="badge {if $orderexchange->balance >= 0}badge-success{else}badge-warning{/if}">
									{displayPrice price=$orderexchange->balance currency=$currency->id}
								</span>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
