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

<div class="panel" id="about_module">
	<h3><i class="icon icon-info"></i> {l s='About this module' mod='orderexchange'}</h3>
	<div class="row clearfix">
		<div class="col-md-12 col-lg-4">
			<img src="{$module_dir|escape:'htmlall':'UTF-8'|escape:'htmlall':'UTF-8'}/views/img/admin/module-teaser-{Context::getContext()->language->iso_code|escape:'htmlall':'UTF-8'}.jpg" alt="{l s='Adilis, web agency' mod='orderexchange'}" height="219" width="600" style="max-width: 100%; height: auto"/>
		</div>
		<div class="col-md-6 col-lg-3 col-lg-offset-1">
			<p>
			<h4>&raquo; {l s='The Author' mod='orderexchange'} :</h4>
			<img src="{$module_dir|escape:'htmlall':'UTF-8'|escape:'htmlall':'UTF-8'}/views/img/admin/logo-adilis.gif" alt="{l s='Adilis, web agency' mod='orderexchange'}" height="54" width="125" style="max-width: 100%; height: auto"/>
			</p>
		</div>
		<div class="col-md-6 col-lg-4">
			<p>
			<h4>&raquo; {l s='The Module' mod='orderexchange'} :</h4>
			<ul class="list-unstyled">
				<li>{l s='Module version' mod='orderexchange'} : {$moduleversion|escape:'htmlall':'UTF-8'}</a></li>
				<li>{l s='Prestashop version' mod='orderexchange'} : {$psversion|escape:'htmlall':'UTF-8'}</a></li>
				<li>{l s='Php version' mod='orderexchange'} : {$phpversion|escape:'htmlall':'UTF-8'}</a></li>
				<li><a href="{$module_dir|escape:'htmlall':'UTF-8'|escape:'htmlall':'UTF-8'}/readme_en.pdf" target="_blank">{l s='English Documentation' mod='orderexchange'}</a></li>
				<li><a href="{$module_dir|escape:'htmlall':'UTF-8'|escape:'htmlall':'UTF-8'}/readme_fr.pdf" target="_blank">{l s='French Documentation' mod='orderexchange'}</a></li>
			</ul>
			</p>
		</div>

	</div>
</div>
