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
	var token = '{$token|escape:'htmlall':'UTF-8'}';
	{if $action == 'new'}
		$(document).ready(function(){
			$('#order_creation_form').parent('.margin-form').addClass('hide');
			$('#order_creation_form').parent('.margin-form').prev().addClass('hide');
			$('#products').parent('.margin-form').addClass('hide');
			$('#products').parent('.margin-form').prev().addClass('hide');
			$('#shipping_cost_on').parent('.margin-form').addClass('hide');
			$('#shipping_cost_on').parent('.margin-form').prev().addClass('hide');
			$('#delivery_option').parent('.margin-form').addClass('hide');
			$('#delivery_option').parent('.margin-form').prev().addClass('hide');
		});
	{/if}
</script>