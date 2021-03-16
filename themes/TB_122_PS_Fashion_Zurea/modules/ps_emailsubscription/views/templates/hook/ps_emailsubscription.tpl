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
<div class="tbcms-newsletter-wrapper col-xl-7 col-lg-12 col-md-12 col-sm-12 col-sx-12">
	<div class="tbcms-newsletter-inner">
		<div class="block_newsletter tb-newsletter-wrapeer">
			<div class="tbnewsletter-block clearfix">
				<div class="tbcmsnews-title-wrapper">
					<div class="tbcms-news-title">
						{if Configuration::get('TBCMSCUSTOMSETTING_NEWSLETTER_TITLE', $language.id)}
							<div class="tbnews-title">
								<span id="block-newsletter-label">
									{Configuration::get('TBCMSCUSTOMSETTING_NEWSLETTER_TITLE', $language.id)}
								</span>
							</div>
							{if Configuration::get('TBCMSCUSTOMSETTING_NEWSLETTER_SHORT_DESC', $language.id)}
								<div class="tbnews-desc">
									{Configuration::get('TBCMSCUSTOMSETTING_NEWSLETTER_TITLE', $language.id)}
								</div>
							{/if}
						{/if}
					</div>
				</div>

				<div class="tbnewsletter-input">
					<form action="{$urls.pages.index}#footer" method="post">
						<div class="tbnewsletter-description">
							{if $conditions}
								<p class="alert-description">{$conditions}</p>
							{/if}
							{if $msg}
								<p class="alert {if $nw_error}alert-danger{else}alert-success{/if}">
									{$msg}
								</p>
							{/if}
							{if isset($id_module)}
								{hook h='displayGDPRConsent' id_module=$id_module}
							{/if}
						</div>
						<div class="tbnewsleeter-input-button-wraper">
							<div class="input-wrapper">
								<input name="email" type="email" value="{$value}" placeholder="{l s='Your email address' d='Shop.Forms.Labels'}" aria-labelledby="block-newsletter-label" >
							</div>
							<div class="tbnewsleteer-btn-wrapper">
								<button class='btn btn-primary' name="submitNewsletter" type="submit">
									{* <span class='tbnewslatter-btn-title'>{l s='Subscribe' d='Shop.Theme.Actions'}</span> *}
									<span class='tbnewslatter-btn-title'>{l s='OK' d='Shop.Theme.Actions'}</span>
									{*<i class='material-icons'>&#xe0be;</i>*}
								</button>
							</div>
						</div>
						<input type="hidden" name="action" value="0">
					</form>
				</div>
				
			</div>
			
		</div>
	</div>
	{* {hook h='displaySocialMediaBlock'} *}
</div>


{/strip}
