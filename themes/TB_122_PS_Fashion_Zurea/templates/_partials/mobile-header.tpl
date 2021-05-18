{**
 * 2007-2019 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
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
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2019 PrestaShop SA
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}

{strip}
<div id='tbcms-mobile-view-header' class="hidden-lg-up">

	<div class="tbcmsmobile-header-left col-sm-2 col-xs-3">
		<div id='tbcmsmobile-horizontal-menu'></div>
	</div>
	<div id="tbcmsmobile-header-right" class="col-sm-2 col-xs-3"></div>

	<div class="tbcmsmobile-header-center col-sm-4 col-xs-12">
		<div id='tbcmsmobile-header-logo'></div>
	</div>

	<div class='tbheader-account-wrapper col-sm-2 col-xs-3'>
		<div class="tbcms-header-myaccount">
			<div class="tb-header-account">
				<div class="tb-account-wrapper">
					<button class="btn-unstyle tb-myaccount-btn">
						{* <span>{l s='My Account' d='Shop.Theme.Catalog'}</span> *}
						<i class='material-icons'>&#xe7ff;</i>
					</button>
					<ul class="dropdown-menu tb-account-dropdown tb-dropdown">
						<li>{hook h='displayNavWishlistBlock'}</li>
						<li>{hook h='displayNavProductCompareBlock'}</li>
						<li>{hook h='displayNavCustomerSignInBlock'}</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div class='tbheader-account-wrapper col-sm-2 col-xs-3'>
		{hook h='displayNavShoppingCartBlock'}
	</div>	
</div>
{/strip}
