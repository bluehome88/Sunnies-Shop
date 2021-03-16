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

 <div class="panel">
	<legend>{l s='Order exchange informations' mod='orderexchange'}</legend>
	<span>{l s='Order exchange' mod='orderexchange'} : </span>
	<a href="{$order_exchange_link|escape:'html':'UTF-8'}" class="text-muted">{$order_exchange_reference|escape:'htmlall':'UTF-8'}</a><br/>

	<span>{l s='Order from' mod='orderexchange'} : </span>
	<a href="{$order_from_link|escape:'html':'UTF-8'}" class=" text-success">{$order_from_reference|escape:'htmlall':'UTF-8'}</a><br/>

	<span>{l s='Carte rule' mod='orderexchange'} : </span>
	<a href="{$cart_rule_link|escape:'html':'UTF-8'}" class="">{$cart_rule_code|escape:'htmlall':'UTF-8'}</a>
	<small> ({displayPrice price=$cart_rule_amount currency=$currency->id}){if $cart_rule_shipping} + {l s='Free shipping' mod='orderexchange'}{/if}</small><br/>

	{if isset($order_slip_reference)}
	<span>{l s='Order Slip' mod='orderexchange'} : </span>
	<a href="{$order_slip_link|escape:'html':'UTF-8'}" class="text-warning">{$order_slip_reference|escape:'htmlall':'UTF-8'}</a>
	<small> ({displayPrice price=$order_slip_amount currency=$currency->id})</small><br/>
	{/if}

	<span>{l s='Exchange Order' mod='orderexchange'} : </span>
	<a href="{$order_to_link|escape:'html':'UTF-8'}" class=" text-danger">{$order_to_reference|escape:'htmlall':'UTF-8'}</a>
	<small> ({displayPrice price=$order_to_amount currency=$currency->id})</small><br/>

</div>