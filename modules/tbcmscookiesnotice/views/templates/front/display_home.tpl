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
{if !isset($smarty.cookies.cokkie_set)}
<div class='tbcmscookies-notice'> 
	<div class='tbcookies-notice-img-wrapper'>
		<div class="tbcookies-notice-img-inner">
			<div class="tbcookie-content-box">
				<div class='tbcookies-notice-title'>{$dis_arr_result.data.title nofilter}</div>
			</div>
			<div class="tbcoockies-btn-wrapper">
				<a href="{$link->getPageLink('cms',null,null,'id_cms=3')}" class="tbcoockies-btn" rel="noreferrer noopener">Terms &amp; Conditions</a>
				<a href="Javascript:void(0);" class="close-cookie tbcoockies-accept">
					<div class="tbcoockies-accept">
						<span class="tbcookies-text">Accept</span> 
						<i class="material-icons"></i>
					</div>
				</a>
			</div>
		</div>
		{* <div class="tbcookies-notice-icon">
			<button class='close-cookie tbclose-icon'>
				<i class='material-icons'>&#xe5cd;</i>
			</button>
		</div> *}
	</div>
</div>
{/if}
{/strip}
