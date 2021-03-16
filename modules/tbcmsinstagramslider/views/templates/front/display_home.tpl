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
{if isset($instagram_pics) && $instagram_pics|count > 0}
<div class=" tbcmsinstagram-slider conatiner-fluid">
	<div class='tbinstagram-slider'>
		
		
	{if !empty(Configuration::get('CS_TITLE', $language.id))}
		<div class="tbinstagram-slider-title-wrapper tbcmsmain-title-wrapper">
			<div class="tbinstagram-slider-title-inner tbcms-main-title">
				<div class="tbinstagram-slider-title-outer tbmain-title" {*data-target="#tbinstagram-slider-inner" data-toggle="collapse"*}>
					<h2 class="tbinstagram-title">{Configuration::get('CS_TITLE', $language.id)}</h2>

					{*<span class="float-xs-right tbfooter-toggle-icon-wrapper">
				        <span class="navbar-toggler collapse-icons tbfooter-toggle-icon">
				          <i class="material-icons add">&#xE313;</i>
				          <i class="material-icons remove">&#xE316;</i>
				        </span>
				    </span>*}
				</div>
			</div>
		</div>
		{/if}

		<div id="tbinstagram-slider-inner">
			<div class='tbinstagram-slider-content-box owl-theme owl-carousel'>
				{$count = 1}
				{foreach $instagram_pics as $pic}
					
						<div class="item tbinstagram-slider-wrapper-info wow zoomIn">
					
						<div class="tbinsta-img-block">
							<a class='tbinsta-img-block-link' href="{$pic.link}" title="{$pic.caption|escape:'html':'UTF-8'}" target="_blank" rel="nofollow">
								<img src="{$pic.image}" alt="{$pic.caption|escape:'html':'UTF-8'}" />
							</a>
						</div>
					
						</div>
						{$count = 0}	
					
					{$count = $count + 1}

				{/foreach}
				{if $count != 1}
					</div>	
				{/if}
			</div>
		</div>	
		<div class='tbcms-instagram-pagination-wrapper'>
			<div class="tbcms-instagram-pagination">
				<div class="tbcms-instagram-next-pre-btn">
				  	<div class="tbinstagram-slider-prev tbcmsprev-btn"><i class='material-icons'>&#xe5cb;</i></div>
				  	<div class="tbinstagram-slider-next tbcmsnext-btn"><i class='material-icons'>&#xe5cc;</i></div>
				</div>
			</div>
	  	</div>

	</div>
</div>	
{/if}
{/strip}