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
{block name='header_banner'}
<div class="tbcmsheader-banner">
	{hook h='displayBanner'}
</div>
{/block}

{block name='header_nav'}

{/block}

{block name='header_top'}
<div class="tbcmsdesktop-top-header-wrapper">
	<div class='tbheader-offer-wrapper'>
		<div class="container">
			<div class="row">
				<div class='tbheader-offer-account-inner'>
					<div class='tbheader-offer-wrapper-outer col-xl-5 col-lg-5 col-md-5 col-sm-5 col-xs-12'>
						<div class="tb-nav-text first">{l s='Free Shipping Over $50'}</div>
						<div class="tb-nav-text">{l s='30-day Return Policy'}</div>
						<div class="tb-nav-text">{l s='100% UV-protection'}</div>
							{hook h='displayTopOfferText'}
					</div>
					<div class="col-xl-2 col-lg-2 col-md-12 col-sm-12 hidden-md-down tbcms-header-logo" id="tbcmsdesktop-logo">
						<div class="tb-header-logo">
							<a href="{$urls.base_url}">
							  <img class="logo img-responsive" src="{$shop.logo}" alt="{$shop.name}">
							</a>
						</div>
					</div>
					<div class='tbheader-account-wrapper col-xl-5 col-lg-5 col-md-5 col-sm-5 col-xs-12'>
						<a href="{url entity='Contact Us'}">Help Desk</a>
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
						{hook h='displayNavShoppingCartBlock'}
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="container hidden-md-down">
		<div class='tbheader-navbar-inner tbcmsheader-sticky'>
			<!-- <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 hidden-md-down tbcms-header-logo" id="tbcmsdesktop-logo">
				<div class="tb-header-logo">
					<a href="{$urls.base_url}">
					  <img class="logo img-responsive" src="{$shop.logo}" alt="{$shop.name}">
					</a>
				</div>
			</div> -->

			<div class="tbcmsdesktop-top-header col-xl-11 col-lg-11 col-md-12 col-sm-12">
				<div class="position-static tbcms-header-menu">
					<div class='tbcmsmain-menu-wrapper'>
						{hook h='displayNavMainMenuBlock'}
					</div>
				</div>
			</div>
			<div class="col-xl-1 col-lg-1 col-md-12 col-sm-12 tbcmsheader-nav-right hidden-md-down">
				<div class="tb-search-account-cart-wrapper">
					<div class='tbcmssearch-wrapper' id="_desktop_search">
						{hook h='displayNavSearchBlock'}
					</div>
				</div>
			</div>
			<div class='tbheader-account-wrapper col-xl-1 col-lg-1 col-md-12 col-sm-12 col-xs-12'>
				{hook h='displayNavShoppingCartBlock'}
			</div>
		</div>
	</div>
			
</div>
{hook h='displayNavFullWidth'}
{/block}

{include file='_partials/mobile-header.tpl'}
{/strip}
