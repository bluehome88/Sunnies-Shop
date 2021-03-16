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

<div class="tbfooter-address-wrapper col-xl-3 col-lg-3 col-md-12 col-xs-12 col-sm-12">
	<div class="tbfooter-address">
		<div id="footer_sub_menu_store_info">
			<div class="tbfooter-title-wrapper collapsed" data-target="#tbfooter-address-wrapper" data-toggle="collapse">
				<div class="tbfooter-title">{l s='Address' d='Shop.Theme.Global'}</div>
				<span class="float-xs-right tbfooter-toggle-icon-wrapper">
					<span class="navbar-toggler collapse-icons tbfooter-toggle-icon">
						<i class="material-icons add">&#xE313;</i>
						<i class="material-icons remove">&#xE316;</i>
					</span>
				</span>

			</div>

			<div id="tbfooter-address-wrapper" data-toggle="collapse" class="collapse">
				{hook h='displayFooterLogo'} 
				
				<div class="tbfooter-addresses">
					<div class="tbfooter-address-lable">{l s='Address:' d='Shop.Theme.Global'}</div>
					{$contact_infos.address.formatted nofilter}
				</div>

				

				{if $contact_infos.email}
					<div class="tbfooter-store-link">
					{* [1][/1] is for a HTML tag. *}
					{l
						s='Email us: [1]%email%[/1]'
						sprintf=[
							'[1]' => '<a href="mailto:'|cat:$contact_infos.email|cat:'" class="dropdown">',
							'[/1]' => '</a>',
							'%email%' => $contact_infos.email
						]
						d='Shop.Theme.Global'
					}
				</div>
				{/if}
				{if $contact_infos.phone}
				<div class="tbfooter-store-link-content">
					{* [1][/1] is for a HTML tag. *}
					{l s='Call us: [1]%phone%[/1]'
						sprintf=[
						'[1]' => '<span>',
						'[/1]' => '</span>',
						'%phone%' => $contact_infos.phone
						]
						d='Shop.Theme.Global'
					}
				</div>
				{/if}
				{if $contact_infos.fax}
					<div class="tbfooter-store-link">
					{* [1][/1] is for a HTML tag. *}
					{l
						s='Fax: [1]%fax%[/1]'
						sprintf=[
							'[1]' => '<span>',
							'[/1]' => '</span>',
							'%fax%' => $contact_infos.fax
						]
						d='Shop.Theme.Global'
					}
				</div>
				{/if}
			</div>
		</div>
	</div>
</div>
{/strip}
