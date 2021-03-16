/**
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
 */

 versionCompare = function(left, right) {
    if (typeof left + typeof right != 'stringstring')
        return false;

    var a = left.split('.')
    ,   b = right.split('.')
    ,   i = 0, len = Math.max(a.length, b.length);

    for (; i < len; i++) {
        if ((a[i] && !b[i] && parseInt(a[i]) > 0) || (parseInt(a[i]) > parseInt(b[i]))) {
            return 1;
        } else if ((b[i] && !a[i] && parseInt(b[i]) > 0) || (parseInt(a[i]) < parseInt(b[i]))) {
            return -1;
        }
    }

    return 0;
}

function hideElement( elem )
{
	if(versionCompare( _PS_VERSION_, '1.6' )==-1)
	{
		elem.closest('.margin-form').addClass('hide');
		elem.closest('.margin-form').prev().addClass('hide');
	}
	else
		elem.closest('.form-group').addClass('hide');
}

function showElement( elem )
{
	if(versionCompare( _PS_VERSION_, '1.6' )==-1)
	{
		elem.closest('.margin-form').removeClass('hide');
		elem.closest('.margin-form').prev().removeClass('hide');
	}
	else
		elem.closest('.form-group').removeClass('hide');
}

function disableButtons()
{
	if(versionCompare( _PS_VERSION_, '1.6' )==-1)
		$('#desc-orderexchange-save-wait,#desc-orderexchange-save').hide();
	else
		$('.panel-footer button').prop('disabled', true);
}

function enableButtons()
{
	if(versionCompare( _PS_VERSION_, '1.6' )==-1)
		$('#desc-orderexchange-save-wait,#desc-orderexchange-save').show();
	else
		$('.panel-footer button').prop('disabled', false);
}

var current_line = {};
var lines = {};

var total_products = 0;
var total_products_wt = 0;
var total_shipping = 0;
var total_shipping_wt = 0;
var total_tax = 0;

var hide_order_creation_form = false;
var free_shipping = 0;
var total_balance = 0;
var products_tax = 0;
var shipping_tax = 0;
var id_carrier = 0;

function getRowInfos( element )
{
	var _current = $(element).closest('tr');
	if( _current.hasClass('product-line-row') )
		_current = _current.next('tr');

	var id_order_detail = parseInt(_current.data('id-order-detail'));
	if( typeof(lines[id_order_detail])=='undefined' )
	{
		current_line = {
			row : _current,
			id_order_detail : id_order_detail,
			id_product : parseInt(_current.find('.id_product_selected').val()),
			id_product_attribute : parseInt(_current.find('.id_product_attribute_selected').val()),
			search_id_product : parseInt(_current.find('.search_product_list').val()),
			search_name_product : _current.find('.search_product_list :selected').html(),
			product_unit_price : parseFloat(_current.find('.product_unit_price').val()),
			return_unit_price : parseFloat(_current.prev('tr').find('.return_quantity').data('order-price')),
			return_unit_price_wt : parseFloat(_current.prev('tr').find('.return_quantity').data('order-price-wt')),
			tax_rate : parseFloat(_current.find('.product_unit_price').data('tax-rate')),
			qty : parseInt(_current.find('.cart_quantity').val()),
			return_qty : parseInt(_current.prev('tr').find('.return_quantity').val()),
			reserve_stock : parseInt(_current.find('.reserve_stock').val())
		}
		current_line.search_id_product_attribute =  parseInt(_current.find('.ipa_'+current_line.search_id_product).val());
		current_line.search_name_product_attribute =  _current.find('.ipa_'+current_line.search_id_product+' :selected').html();
		current_line.total_price = current_line.product_unit_price * current_line.qty;
		current_line.total_price_return = current_line.return_unit_price * current_line.return_qty;
		current_line.balance = current_line.total_price - current_line.total_price_return;

		if(parseInt(current_line.search_id_product_attribute))
		{
			current_line.product_price = parseFloat(_current.find('.ipa_'+current_line.search_id_product+' :selected').data('price'));
			current_line.product_quantity = parseInt(_current.find('.ipa_'+current_line.search_id_product+' :selected').data('quantity'));
		}
		else
		{
			current_line.product_price = parseFloat(_current.find('.search_product_list :selected').data('price'));
			current_line.product_quantity = parseInt(_current.find('.search_product_list :selected').data('quantity'));
		}

		lines[id_order_detail] = current_line;
	}
	current_line = lines[id_order_detail];
}

function saveCurrentRow()
{
	if( current_line.product_quantity >= current_line.qty )
			current_line.reserve_stock = 0;

	lines[current_line.id_order_detail] = current_line;
	updateLineDisplay( current_line );
}


function saveRowSearch(line)
{
	line.search_id_product = parseInt(line.row.find('.search_product_list').val());
	line.search_name_product = line.row.find('.search_product_list :selected').html();
	line.search_id_product_attribute =  parseInt(line.row.	find('.ipa_'+line.search_id_product).val());
	line.search_name_product_attribute =  line.row.find('.ipa_'+line.search_id_product+' :selected').html();

	if(parseInt(line.search_id_product_attribute))
	{
		line.product_price = parseFloat(line.row.find('.ipa_'+line.search_id_product+' :selected').data('price'));
		line.product_quantity = parseInt(line.row.find('.ipa_'+line.search_id_product+' :selected').data('quantity'));
	}
	else
	{
		line.product_price = parseFloat(line.row.find('.search_product_list :selected').data('price'));
		line.product_quantity = parseInt(line.row.find('.search_product_list :selected').data('quantity'));
	}
	lines[line.id_order_detail] = line;
}

function saveRow(line)
{
	if( line.product_quantity >= line.qty )
		line.reserve_stock = 0;

	lines[line.id_order_detail] = line;
	updateLineDisplay( line );
}

function deleteRow(line, force_close)
{
	if (line.row.find('.selected_product').is(':visible') || force_close)
	{
		var chk = line.row.prev('tr').find('input[name^=orderDetailBox]');
		if (chk.is(':checked'))
			chk.prop('checked', false);

		$('#product_exchange_'+line.id_order_detail).slideUp();
		line.row.prev('tr').find('.product_quantity_show').removeClass('hide');
		line.row.prev('tr').find('.product_quantity, .product_price, .product_qty, .total_product_wrapper').addClass('hide');
	}
	delete lines[line.id_order_detail];
}


function updateLineDisplay( line )
{
	showLoader();
	line.row.find('.id_product_selected').val(line.id_product);
	line.row.find('.id_product_attribute_selected').val(line.id_product_attribute);
	line.row.find('.product_unit_price').val(ps_round(line.product_unit_price, priceDisplayPrecision));
	line.row.find('.product_unit_price').data('tax-rate', line.tax_rate);
	line.row.find('.cart_quantity').val(line.qty);
	line.row.find('.product_name').html(line.search_name_product);
	line.row.find('.product_attributes').html(line.search_name_product_attribute);
	parseFloat(line.balance) < 0 ? line.row.find('.balance').removeClass('badge-success').addClass('badge-warning') : line.row.find('.balance').removeClass('badge-warning').addClass('badge-success');
	line.row.find('.total').html(formatCurrency(parseFloat(line.total_price), currency_format, currency_sign, currency_blank));
	line.row.find('.balance').html(formatCurrency(parseFloat(line.balance), currency_format, currency_sign, currency_blank));
	line.row.find('.available_quantity').html(line.product_quantity);
	line.product_quantity >= line.qty ? line.row.find('.reserve_stock_wrapper').removeClass('hide') : line.row.find('.reserve_stock_wrapper').addClass('hide');
	line.reserve_stock ? line.row.find('.reserve_stock').prop('checked', true) : line.row.find('.reserve_stock').prop('checked', false);
	removeLoader();
}

function getRowInfosByOrderDetail(id_order_detail) {
	var child = $('tr#product_exchange_'+id_order_detail).find('td').first();
	return getRowInfos(child);
}

function loadOrder(id_order)
{
	var id_orderexchange = parseInt($('#id_orderexchange').val());
	disableButtons();
	showLoader();
	$.ajax({
		type:"POST",
		url: 'ajax-tab.php' + '?rand=' + new Date().getTime(),
		async: true,
		dataType: "html",
		data : {
			ajax: "1",
			controller: 'AdminOrderExchange',
			token: token,
			action: "getProducts",
			id_order: id_order,
			id_orderexchange: id_orderexchange
		},
		success : function(html)
		{

			$('#products').html(html);
			showElement($('#products'));
			lines = {};
			$.each( $('#products').find('input[name^=orderDetailBox]:checked'), function(){
				getRowInfos(this);
			});

			if ($("#id_order_from").prop("tagName") == 'SELECT')
				$.ajax({
					type:"POST",
					url: 'ajax-tab.php' + '?rand=' + new Date().getTime(),
					async: false,
					dataType: "json",
					data : {
						ajax: "1",
						controller: 'AdminCarts',
						token: token_admincarts,
						action: "getSummary",
						id_cart: id_cart,
						id_customer: id_customer,
						id_address_delivery: id_address_delivery,
						id_address_invoice: id_address_invoice
					},
					success : function(res)
					{
						updateFromSummary(res);
						enableButtons();
						removeLoader();
					}
				});
			else
				enableButtons();


			$('.search_product_input').typeWatch({
				captureLength: 2,
				highlight: false,
				wait: 100,
				callback: function() {
					current_input_search = $(this.el);
					searchProducts();
				}
			});
			removeLoader();
		}
	});
}

$(document).ready( function() {

	if( $('#orderexchange_form').length )
	{
		if(versionCompare( _PS_VERSION_, '1.6' )==-1)
		{
			$('#products, #order_creation_form, #summary_part').parent().removeClass('margin-form');
			$('#orderexchange_form legend img').hide();

			$('#desc-orderexchange-save-wait').click(function(e){
				e.preventDefault();
				$('#orderexchange_form_submit_btn').prop('name', 'submitAddorderexchangeAndWait').click();
			});
		}

		var $products = $('#products');
		free_shipping = $('#shipping_cost_off').is(':checked') ? 1 : 0;

		if( $("#id_order_from").prop("tagName") == 'SELECT')
		{
			$("#id_order_from").chosen({
				 search_contains : true,
				 width: "90%"
			});
			removeLoader();
			$('#id_order_from').on('change', function() {
				var id_order = parseInt($(this).val());
				if( id_order > 0)
					loadOrder(id_order);
				else
				{
					$('#products').html('');
					hideElement($('#products'));
					disableButtons();
				}
			}).trigger('change');
		}
		else
		{
			var id_order = parseInt($('#id_order_from').val());
			loadOrder(id_order);
		}

		$products.delegate('input[name^=orderDetailBox]', 'click', function(){
			getRowInfos(this);
			current_line.row.find('.id_product_selected').val('');
			current_line.row.find('.id_product_attribute_selected').val('');
			current_line.row.find('.search_product').removeClass('hide');
			current_line.row.find('.selected_product').addClass('hide');
				current_line.row.find('.selected_product, .product_price, .product_qty, .total_product_wrapper').addClass('hide');

			if( $(this).is(':checked') )
			{
				current_line.row.prev('tr').find('.product_quantity_show').addClass('hide');
				current_line.row.prev('tr').find('.product_quantity').removeClass('hide');
				current_line.row.slideDown();
			}
			else
			{
				if (isNaN(current_line.id_product))
					deleteRow(current_line, true);
				else
					updateQty(current_line.id_product, current_line.id_product_attribute, 0, current_line.qty * -1, function(){
						deleteRow(current_line, true);
					});
			}

		});

		$products.delegate('.search_product .btn-primary', 'click', function(){
			getRowInfos(this);
			saveRowSearch(current_line);
			updateQty(current_line.search_id_product, current_line.search_id_product_attribute, 0, current_line.qty, function(){
				current_line.id_product = current_line.search_id_product;
				current_line.id_product_attribute = current_line.search_id_product_attribute;
				current_line.row.find('.search_product').addClass('hide');
				current_line.row.find('.selected_product, .product_price, .product_qty, .total_product_wrapper').removeClass('hide');
				saveCurrentRow();
			});
		});

		$products.delegate('.selected_product .btn-default', 'click', function(){
			getRowInfos(this);
			updateQty(current_line.id_product, current_line.id_product_attribute, 0, current_line.qty * -1, function(){
				current_line.id_product = null;
				current_line.id_product_attribute = null;
				current_line.row.find('.selected_product, .product_price, .product_qty, .total_product_wrapper').addClass('hide');
				current_line.row.find('.search_product').removeClass('hide');
				saveCurrentRow();
			});
		});

		$('input[name=order_creation]').change( function() {
			if (parseInt($(this).val())==1)
			{
				$('#order_creation_0_form').hide();
				$('#order_creation_1_form').slideDown();
			}
			else
			{
				$('#order_creation_1_form').hide();
				$('#order_creation_0_form').slideDown();
			}

		});

		$('input[name=shipping_cost]').click(function(el){
			free_shipping = ( $(this).val() )==1 ? 0 : 1;
			showLoader();
			$.ajax({
				type:"POST",
				url: 'ajax-tab.php' + '?rand=' + new Date().getTime(),
				async: true,
				dataType: "json",
				data : {
					ajax: "1",
					controller: 'AdminCarts',
					token: token_admincarts,
					action: "getSummary",
					id_cart: id_cart,
					id_customer: id_customer
				},
				success : function(res)
				{
					updateFromSummary(res);
					removeLoader();
				}
			});
		});

		$products.delegate('.increaseqty_product, .decreaseqty_product', 'click', function(e) {
			e.preventDefault();
			getRowInfos(this);
			var sign = '';
			if ($(this).hasClass('decreaseqty_product'))
				sign = '-';
			updateQty(current_line.id_product, current_line.id_product_attribute, 0, sign+1);
		});

		$products.delegate('.cart_quantity', 'change', function(e) {
			e.preventDefault();
			getRowInfos(this);

			if ($(this).val() != current_line.qty)
				updateQty(current_line.id_product, current_line.id_product_attribute, 0, $(this).val() - current_line.qty);
		});

		$products.delegate('.product_unit_price', 'change', function(e) {
			e.preventDefault();
			getRowInfos(this);
			var new_price = $(this).val();
			if( use_tax )
				new_price = new_price / (1 + current_line.tax_rate/ 100);
			updateProductPrice(current_line.id_product, current_line.id_product_attribute, new_price);
		});

		$products.delegate('.return_quantity', 'change', function(e) {
			e.preventDefault();
			getRowInfos(this);
			current_line.return_qty = parseInt($(this).val());
			current_line.total_price_return = current_line.return_unit_price * current_line.return_qty;
			current_line.balance = current_line.total_price_return - current_line.total_price;
			saveCurrentRow();
			updateDisplay();
		});

		$products.delegate('.search_product_list', 'change', function(e) {
			getRowInfos(this);
			current_line.search_id_product = parseInt($(this).val());
			displayProductAttributes();
		});

	}

});

function updateQty(id_product, id_product_attribute, id_customization, qty, callback )
{
	showLoader();
	$.ajax({
		type:"POST",
		url: 'ajax-tab.php' + '?rand=' + new Date().getTime(),
		async: true,
		dataType: "json",
		data : {
			ajax: "1",
			controller: 'AdminCarts',
			token: token_admincarts,
			action: "updateQty",
			id_product: id_product,
			id_product_attribute: id_product_attribute,
			id_customization: id_customization,
			qty: qty,
			id_customer: id_customer,
			id_cart: id_cart
		},
		success : function(res)
		{
			if ( !('errors' in res) || !res.errors.length && callback)
				callback();
			updateFromSummary(res);
			removeLoader();
		}
	});
}

function updateProductPrice(id_product, id_product_attribute, new_price)
{
	showLoader();
	$.ajax({
		type:"POST",
		url: 'ajax-tab.php' + '?rand=' + new Date().getTime(),
		async: true,
		dataType: "json",
		data : {
			ajax: "1",
			controller: 'AdminCarts',
			token: token_admincarts,
			action: "updateProductPrice",
			id_cart: id_cart,
			id_product: id_product,
			id_product_attribute: id_product_attribute,
			id_customer: id_customer,
			price: new Number(new_price).toFixed(4).toString()
			},
		success : function(res)
		{
			updateFromSummary(res);
			removeLoader();
		}
	});
}

function displayErrors(jsonSummary)
{
	var errors = '';
	$.each(jsonSummary.errors, function() {
		errors += this + '<br />';
	});
	$('#display_err').removeClass('hide');
	$('#display_err').html(errors);
}

function updateFromSummary(jsonSummary)
{
	id_cart = parseInt(jsonSummary.id_cart);
	$('#id_cart').val(id_cart);

	if ( 'errors' in jsonSummary && jsonSummary.errors.length)
	{
		displayErrors(jsonSummary);
	}
	else
	{
		$('#display_err').addClass('hide');
		$.each(lines, function(id_order_detail, line) {
			var line_is_found = false;
			$.each(jsonSummary.summary.products, function(index, product) {
				if( parseInt(line.id_product) == parseInt(product.id_product) && ( parseInt(line.id_product_attribute) == parseInt(product.id_product_attribute) || (isNaN(parseInt(line.id_product_attribute)) && parseInt(product.id_product_attribute)==0) ) )
				{
					line.qty = product.quantity;
					line.product_unit_price = use_tax ? product.price_wt :formatedNumberToFloat(product.price, currency_format, currency_sign);
					line.product_unit_price = use_tax ? product.price_wt :formatedNumberToFloat(product.price, currency_format, currency_sign);
					line.total_price = use_tax ? product.total_wt : formatedNumberToFloat(product.total, currency_format, currency_sign);
					line.balance = line.total_price - line.total_price_return ;
					line.tax_rate = product.rate;
					line_is_found = true;
					saveRow(line);
					return false;
				}
			});
			if (!line_is_found)
				deleteRow(line, false);
		});

		id_carrier = parseInt(jsonSummary.summary.carrier.id);
		hide_order_creation_form = (!jsonSummary.summary.products.length || !jsonSummary.delivery_option_list) ? true : false;
		updateDeliveryOptionList(jsonSummary.delivery_option_list);
//		free_shipping = jsonSummary.free_shipping;

		total_products = parseFloat(jsonSummary.summary.total_products);
		total_products_wt = parseFloat(jsonSummary.summary.total_products_wt);
		if( free_shipping == 1 )
		{
			total_shipping = total_shipping_wt = 0;
		}
		else
		{
			total_shipping = parseFloat(jsonSummary.summary.total_shipping_tax_exc);
			total_shipping_wt = parseFloat(jsonSummary.summary.total_shipping);
		}
		total_tax = parseFloat(jsonSummary.summary.total_tax);
		updateDisplay();
	}
}

function updateDisplay()
{
	if (hide_order_creation_form)
	{
		$('#total_shipping, #order_creation_form').addClass('hide');
		hideElement( $('#free_shipping, #delivery_option, input[name=shipping_cost]') );
		disableButtons();
	}
	else
	{
		$('#total_shipping, #order_creation_form').removeClass('hide');
		showElement( $('#free_shipping, #delivery_option, input[name=shipping_cost]') );
		enableButtons();
	}

	if (free_shipping == 1)
	{
		$('#free_shipping').attr('checked', true);
		$('#total_shipping').addClass('hide');
	}
	else
	{
		$('#free_shipping_off').attr('checked', true);
		$('#total_shipping').removeClass('hide');
	}

	var total_discount = 0;
	$.each(lines, function(id_order_detail, line) {
		total_discount -= line.return_unit_price_wt * line.return_qty;
	});

	if (Math.abs(total_discount) >= total_products_wt + total_shipping_wt || Math.abs(total_discount) <= total_products_wt  )
	{
		$('#discount_exclude_shipping').addClass('hide');
		var total_balance = total_products_wt + total_shipping_wt + total_discount;
		var total_discount_display = formatCurrency( total_discount, currency_format, currency_sign, currency_blank );
	}
	else
	{
		$('#discount_exclude_shipping').removeClass('hide');
		var total_balance = total_shipping_wt;
		var total_discount_display = formatCurrency( total_products_wt *-1, currency_format, currency_sign, currency_blank )+'<br/><small class="text-muted">'+formatCurrency( total_discount + total_products_wt, currency_format, currency_sign, currency_blank )+'</small>';
	}

	$('#total_products .amount').html( formatCurrency( use_tax ? total_products_wt : total_products, currency_format, currency_sign, currency_blank ));
	$('#total_shipping .amount').html(formatCurrency( use_tax ? total_shipping_wt : total_shipping, currency_format, currency_sign, currency_blank));
	$('#total_taxes .amount').html( formatCurrency( total_tax, currency_format, currency_sign, currency_blank ));
	$('#total_discount .amount').html( total_discount_display );
	$('#total_order .badge').html(formatCurrency( total_balance, currency_format, currency_sign, currency_blank));



	if( total_balance > 0 ) {
		$('#total_order .badge').removeClass('badge-warning').addClass('badge-success');
		$('#order_creation_0').closest('.radio').removeClass('hide');
		$('#payment_module_name').closest('.form-group').removeClass('hide');
		if ($('#order_creation_0').is(':checked'))
			$('#order_creation_0_form').show();
	} else {
		$('#total_order .badge').removeClass('badge-success').addClass('badge-warning');
		$('#order_creation_0').closest('.radio').addClass('hide');
		$('#payment_module_name').closest('.form-group').addClass('hide');
		$('#order_creation_0_form').hide();
		$('#order_creation_1').click();
	}
}

function updateDeliveryOption()
{
	showLoader();
	$.ajax({
		type:"POST",
		url: 'ajax-tab.php' + '?rand=' + new Date().getTime(),
		dataType: "json",
		data : {
			ajax: "1",
			controller: 'AdminCarts',
			token: token_admincarts,
			action: "updateDeliveryOption",
			delivery_option: $('#delivery_option option:selected').val(),
			gift:0,
			gift_message: '',
			recyclable: 0,
			id_customer: id_customer,
			id_cart: id_cart
		},
		success : function(res)
		{
			updateFromSummary(res);
			removeLoader();
		}
	});
}

function updateDeliveryOptionList(delivery_option_list)
	{
		var html = '';
		if (delivery_option_list.length > 0)
		{
			$.each(delivery_option_list, function() {
				html += '<option value="'+this.key+'" '+((id_carrier == parseInt(this.key)) ? 'selected="selected"' : '')+'>'+this.name+'</option>';
			});
			$('#carrier_form').show();
			$('#delivery_option').html(html);
			$('#carriers_err').hide();
			$("button[name=\"submitAddOrder\"]").removeAttr("disabled");
		}
		else
		{
			$('#carrier_form').hide();
			$('#carriers_err').show().html("{l s='No carrier can be applied to this order'}");
			$("button[name=\"submitAddOrder\"]").attr("disabled", "disabled");
		}
	}


var currentRequest = null;
function searchProducts()
{
	current_product_search = current_input_search.val();
	getRowInfos(current_input_search);
	currentRequest = $.ajax({
		type: 'POST',
		headers: { "cache-control": "no-cache" },
		url: 'ajax-tab.php' + '?rand=' + new Date().getTime(),
		dataType: 'json',
		beforeSend : function()    {
	        if(currentRequest != null)
	            currentRequest.abort();
	    },
		data: {
			controller: 'AdminOrderExchange',
			token: token,
			action: 'searchProducts',
			product_search: current_product_search,
			use_tax: use_tax
		},
		success : function(res)
		{
			var products_found = '';
			var attributes_html = '';
			stock = {};

			if (res.found)
			{
				current_line.row.find('.search_products_err').hide();
				current_line.row.find('.search_products_results').show();

				$.each(res.products, function() {
					products_found += '<option value="' + this.id_product + '" data-price="'+this.price+'" data-quantity="'+this.quantity+'">' + this.name + (this.combinations.length == 0 ? ' - ' + this.formatted_price : '') + '</option>';
					attributes_html += '<select class="id_product_attribute ipa_' + this.id_product + '" name="products_attributes_' + this.id_product + '['+current_line.id_order_detail+']" style="display:none">';
					$.each(this.combinations, function() {
						attributes_html += '<option ' + (this.default_on == 1 ? 'selected="selected"' : '') + ' value="' + this.id_product_attribute + '" data-price="'+this.price+'" data-quantity="'+this.quantity+'">' + this.attributes + ' - ' + this.formatted_price + '</option>';
					});
					attributes_html += '</select>';
				});

				current_line.row.find('.search_product_list').html(products_found);
				current_line.row.find('.search_attributes_list').html(attributes_html);
				current_line.search_id_product = parseInt(current_line.row.find('.search_product_list').val());
				displayProductAttributes();
			}
			else
			{
				current_line.row.find('.search_products_results').hide();
				current_line.row.find('.search_products_err').html(res.notfound);
				current_line.row.find('.search_products_err').show();
			}
		}
	});
}

function displayProductAttributes()
{
	if ( current_line.row.find('.ipa_' + current_line.search_id_product + ' option').length === 0)
		current_line.row.find('.search_attributes_list_wrapper').addClass('hide');
	else
	{
		current_line.row.find('.search_attributes_list_wrapper').removeClass('hide');
		current_line.row.find('.id_product_attribute').hide();
		current_line.row.find('.ipa_' + current_line.search_id_product).show();
	}
}

var loadLevel = 0;
function showLoader()
{
	loadLevel ++;
	$('#mask').delay(350).fadeIn(200);
}

function removeLoader()
{
	loadLevel --;
	if (loadLevel<=0)
	{
		$('#mask').delay(350).fadeOut(200);
		loadLevel = 0;
	}

}