{**
* 2007-2019 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http:opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http:www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2019 PrestaShop SA
*  @license   http:opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
{strip}
<div class='tbcmsapp-logo col-xl-4 col-lg-4 col-md-12'>
	<div class='tbapp-logo'>
		<div class="tbapp-logo-content-box">
			<div class="tbapp-logo-link-wrapper">
				<div class="tbapp-logo-content-inner">
					{if $show_fields['app_title']}
						<div class='tbdekstop-footer-all-title-wrapper'>
							<div class='tbfooter-title'><span>{$data.link_title}</span></div>
						</div>
					{/if}
					{if $show_fields['app_sub_title']}
						<div class='tbdekstop-footer-all-sub-title-wrapper'>
							<div class='tbfooter-subtitle'><span>{$data.link_sub_title}</span></div>
						</div>
					{/if}
					{if $show_fields['app_desc']}
						<div class='tbdekstop-footer-all-desc-wrapper'>
							<div class='tbfooter-desc'><span>{$data.link_desc}</span></div>
						</div>
					{/if}
				</div>
				<div class="tbapp-logo-app-wrapper">
					{if $show_fields['apple_app_link']}
						<div class='tbapp-logo-wrapper tbapp-logo-apple'>
							<a href='{$data.apple_link}' title='{l s="Apple App Link" mod="tbcmscustomsetting"}'>
								<div class="tbapp-logo-image"></div>
								{*<img src='{$path}App-logo-1.png' alt='{l s="Apple App Link" mod="tbcmscustomsetting"}'>*}
							</a>
						</div>
					{/if}
					{if $show_fields['google_app_link']}
						<div class='tbapp-logo-wrapper tbapp-logo-google'>
							<a href='{$data.google_link}' title='{l s="Google App Link" mod="tbcmscustomsetting"}'>
								<div class="tbapp-logo-image"></div>
								{*<img src='{$path}App-logo-2.png' alt='{l s="Google App Link" mod="tbcmscustomsetting"}'>*}
							</a>
						</div>
					{/if}
					{if $show_fields['microsoft_app_link']}
						<div class='tbapp-logo-wrapper tbapp-logo-microsoft'>
							<a href='{$data.microsoft_link}' title='{l s="Microsoft App Link" mod="tbcmscustomsetting"}'>
								<div class="tbapp-logo-image"></div>
								{*<img src='{$path}App-logo-3.png' alt='{l s="Microsoft App Link" mod="tbcmscustomsetting"}'>*}
							</a>
						</div>	
					{/if}
				</div>
			</div>
		</div>
	</div>
</div>
{/strip}
