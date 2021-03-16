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

 <div class="row col-lg-10 col-lg-offset-1 hide" id="order_creation_form">
	<br/>
	<br/>
	<h3>{l s='Order creation' mod='orderexchange'}</h3>
	<div class="form-group">
		<label class="control-label col-lg-3">
			{l s='Order creation' mod='orderexchange'}
		</label>
		<div class="col-lg-9 ">
			<div class="radio ">
				<label class="t">
					<input type="radio" name="order_creation" id="order_creation_0" value="0" {if $order_creation|intval==0}checked="checked"{/if}>
					{l s='Ask for payment' mod='orderexchange'}
				</label>
			</div>
			<div class="radio ">
				<label class="t">
					<input type="radio" name="order_creation" id="order_creation_1" value="1" {if $order_creation|intval==1}checked="checked"{/if}>
					{l s='Create order immediatly' mod='orderexchange'}
				</label>
			</div>
		</div>
	</div>
	<div id="order_creation_0_form" {if $order_creation|intval==1}style="display:none"{/if}>
		<div class="form-group">
			<label class="control-label col-lg-3" for="order_message">{l s='Customer Message' mod='orderexchange'}</label>
			<div class="col-lg-9">
				<textarea name="order_message" id="order_message" rows="5" cols="45"></textarea>
			</div>
		</div>
	</div>
	<div id="order_creation_1_form" {if $order_creation|intval==0}style="display:none"{/if}>
		<div class="form-group">
			<label class="control-label col-lg-3">{l s='Payment method' mod='orderexchange'}</label>
			<div class="col-lg-9">
				<select name="payment_module_name" id="payment_module_name">
					{if !$PS_CATALOG_MODE}
					{foreach from=$payment_modules item='module'}
						<option value="{$module->name|escape:'htmlall':'UTF-8'}" {if isset($payment_module_name) && $module->name == $payment_module_name}selected="selected"{/if}>{$module->displayName|escape:'htmlall':'UTF-8'}</option>
					{/foreach}
					{else}
						<option value="boorder">{l s='Back-office order' mod='orderexchange'}</option>
					{/if}
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-3">{l s='Order status' mod='orderexchange'}</label>
			<div class="col-lg-9">
				<select name="id_order_state" id="id_order_state" class="chosen">
					{foreach from=$order_states item='order_state'}
						<option value="{$order_state.id_order_state|intval}" {if isset($id_order_state) && $order_state.id_order_state == $id_order_state}selected="selected"{/if}>{$order_state.name|escape:'htmlall':'UTF-8'}</option>
					{/foreach}
				</select>
			</div>
		</div>
	</div>
</div>