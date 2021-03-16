{**
* 2007-2019 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2019 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{strip}
{if $tot_tab == 1}
	{$class = "col-lg-12 col-md-12 col-sm-12 col-xs-12"}
{elseif $tot_tab == 2}
	{$class = "col-lg-6  col-md-6 col-sm-6 col-xs-12"}
{elseif $tot_tab == 3}
	{$class = "col-xl-4 col-lg-4 col-md-6 col-sm-6 col-xs-12"}
{else}
	{$class = ""}
{/if}

{$display_prod = Configuration::get('TBCMSCUSTOMSETTING_FOOTER_TAB_NUM_PROD')}



{if Configuration::get('TBCMSCUSTOMSETTING_FOOTER_TAB_STATUS') && $tot_tab > 0}
<div class=" container-fluid tbfooter-product-box-layout">
	<div class="tbcmsmain-all-product tbmain-box-layout-content-wrapper">
	    <div class="container tbmain-all-product">
	    	<div class="tbmain-product-all-wrapper-box">
	    	{if !empty($footer_tab_prod_list.featured_product)}
	    	<div class="{$class} tbcmsmain-featured-product">
	    		<div class="tbfooter-product-title-product"> 
	    			{if Configuration::get('TBCMSCUSTOMSETTING_FOOTER_TAB_FEATURED_PROD_TITLE', $id_lang)}
		    		<div class="tbmain-product-title">
		    			<div class="tbmain-product-title-content">
		    				{Configuration::get('TBCMSCUSTOMSETTING_FOOTER_TAB_FEATURED_PROD_TITLE', $id_lang)}
		    			</div>
		    		</div>
		    		{/if}
		    		<div class="tbmain-all-product-wrapper tbfooter-featured-prod-slider">
		    			<div class="tbmain-featured-product-wrapper">
				    		<div class="tbmain-featured-product-wrapper-info-box">

				    			{$count = 1}
				    			{$new_column = false}

					    		{foreach from=$footer_tab_prod_list.featured_product.product_list item="product"}
					    			{if $new_column == false}
							    		{if $count == 1}
							    			<div class="tbmain-footer-tab-prod-slider col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
							    		{/if}

						            	{* {include file="catalog/_partials/miniatures/footer-product.tpl" product=$product}  *}

							            {if $count == $display_prod}
							    			</div>
							    			{$count = 0}
							    			{$new_column = true}
							    		{/if}
					        			{$count = $count + 1}
						    		{/if}
					            {/foreach}

			 					{if $count != '1'}
						        	</div>
						        {/if}
					    	</div>
		    			</div>
		    			<div class="tbfooter-view-link">
		    				<a href='{$allFeaturedProductLink}' alt="{Configuration::get('TBCMSCUSTOMSETTING_FOOTER_TAB_FEATURED_PROD_TITLE', $id_lang)}" >
		    					<span>{l s=' All Featured Products' mod='tbcmscustomsetting'}</span>
		    					<i class='material-icons'>&#xe315;</i>
		    				</a>
		    			</div>
		    		</div>
	    		</div>
	    	</div>
	    	{/if}


	    	{if !empty($footer_tab_prod_list.new_product.product_list)}
	    	<div class="{$class} tbcmsmain-new-product">
	    		<div class="tbfooter-product-title-product"> 
		    		{if Configuration::get('TBCMSCUSTOMSETTING_FOOTER_TAB_NEW_PROD_TITLE', $id_lang)}
		    		<div class="tbmain-product-title">
		    			<div class="tbmain-product-title-content">
		    				{Configuration::get('TBCMSCUSTOMSETTING_FOOTER_TAB_NEW_PROD_TITLE', $id_lang)}
		    			</div>
		    		</div>
		    		{/if}
		    		<div class="tbmain-all-product-wrapper tbfooter-new-prod-slider">
		    			<div class="tbmain-new-product-wrapper">
			    			<div class="tbmain-new-product-wrapper-info-box">
				    			{$count = 1}
			    				{$new_column = false}

					    		{foreach from=$footer_tab_prod_list.new_product.product_list item="product"}
					    			{if $new_column == false}
							    		{if $count == 1}
							    			<div class="tbmain-footer-tab-prod-slider col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
							    		{/if}

						            	{* {include file="catalog/_partials/miniatures/footer-product.tpl" product=$product }  *}

							            {if $count == $display_prod}
							    			</div>
							    			{$count = 0}
							    			{$new_column = true}
							    		{/if}
						        		{$count = $count + 1}
							    	{/if}
					            {/foreach}

			 					{if $count != '1'}
						        	</div>
						        {/if}
					    	</div>
					    </div>
					    <div class="tbfooter-view-link">
		    				<a href='{$allNewProductsLink}' alt="{Configuration::get('TBCMSCUSTOMSETTING_FOOTER_TAB_NEW_PROD_TITLE', $id_lang)}">
		    					<span>{l s=' All New Products' mod='tbcmscustomsetting'}</span>
		    					<i class='material-icons'>&#xe315;</i>
		    				</a>
		    			</div>
		    		</div>
	    		</div>
	    	</div>
	    	{/if}

	    	{if !empty($footer_tab_prod_list.best_seller.product_list)}
	    	<div class="{$class} tbcmsmain-best-seller-product wow slideInRight">
	    		<div class="tbfooter-product-title-product"> 
		    		{if Configuration::get('TBCMSCUSTOMSETTING_FOOTER_TAB_BEST_SELLER_PROD_TITLE', $id_lang)}
		    		<div class="tbmain-product-title">
		    			<div class="tbmain-product-title-content">
		    				{Configuration::get('TBCMSCUSTOMSETTING_FOOTER_TAB_BEST_SELLER_PROD_TITLE', $id_lang)}
		    			</div>
		    			
		    		</div>
		    		{/if}
		    		<div class="tbmain-all-product-wrapper tbfooter-besr-prod-slider">
		    			<div class="tbmain-best-product-wrapper">
			    			<div class="tbmain-new-product-wrapper-info-box">

			    			{$count = 1}
			    			{$new_column = false}
				    		{foreach from=$footer_tab_prod_list.best_seller.product_list item="product"}	
				    			{if $new_column == false}
						    		{if $count == 1}
						    			<div class="tbmain-footer-tab-prod-slider col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
						    		{/if}

					            	{* {include file="catalog/_partials/miniatures/footer-product.tpl" product=$product }  *}

						            {if $count == $display_prod}
						    			</div>
						    			{$count = 0}
						    			{$new_column = true}
						    		{/if}
				        			{$count = $count + 1}
						    	{/if}
				            {/foreach}
		 					{if $count != '1'}
					        	</div>
					        {/if}
						    </div>
						</div>
						<div class="tbfooter-view-link">
		    				<a href='{$allBestSellersLink}' alt="{Configuration::get('TBCMSCUSTOMSETTING_FOOTER_TAB_BEST_SELLER_PROD_TITLE', $id_lang)}">
		    					<span>{l s=' All Best Products' mod='tbcmscustomsetting'}</span>
		    					<i class='material-icons'>&#xe315;</i>
		    				</a>
		    			</div>
		    		</div>
	    		</div>
	    	</div>
	    	{/if}
	    	</div>
	    </div>
	</div>
</div>
{/if}
{/strip}