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

 <div class="panel" id="summary_part" style="">
		<div class="panel-heading">
			<i class="icon-align-justify fa fa-align-justify"></i>
			{l s='Summary' mod='orderexchange'}
		</div>

		<div id="cart_summary" class="text-center">
			<div class="row">
				<div class="col-lg-{if isset($order_slip_reference)}3{else}4{/if}">
					<div class="data-focus">
						<span>{l s='Order from' mod='orderexchange'}</span><br/>
						<a href="{$order_from_link|escape:'html':'UTF-8'}" class="size_l text-success">{$order_from_reference|escape:'htmlall':'UTF-8'}</a>
					</div>
				</div>
				<div class="col-lg-{if isset($order_slip_reference)}3{else}4{/if}">
					<div class="data-focus">
						<span>{l s='Carte rule' mod='orderexchange'}</span><br/>
						<a href="{$cart_rule_link|escape:'html':'UTF-8'}" class="size_l">{$cart_rule_code|escape:'htmlall':'UTF-8'}</a><br/>
						<small>{displayPrice price=$cart_rule_amount currency=$currency->id}{if $cart_rule_shipping} + {l s='Free shipping' mod='orderexchange'}{/if}</small>
					</div>
				</div>
				{if isset($order_slip_reference)}
				<div class="col-lg-3">
					<div class="data-focus">
						<span>{l s='Order Slip' mod='orderexchange'}</span><br/>
						<a href="{$order_slip_link|escape:'html':'UTF-8'}" class="size_l text-warning">{$order_slip_reference|escape:'htmlall':'UTF-8'}</a><br/>
						<small>{displayPrice price=$order_slip_amount currency=$currency->id}</small>
					</div>
				</div>
				{/if}
				<div class="col-lg-{if isset($order_slip_reference)}3{else}4{/if}">
					<div class="data-focus">
						<span>{l s='Exchange Order' mod='orderexchange'}</span><br/>
						<a href="{$order_to_link|escape:'html':'UTF-8'}" class="size_l text-danger">{$order_to_reference|escape:'htmlall':'UTF-8'}</a><br/>
						<small>{displayPrice price=$order_to_amount currency=$currency->id}</small>
					</div>
				</div>
			</div>
		</div>
</div>