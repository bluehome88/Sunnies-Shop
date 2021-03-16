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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2019 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{strip}
{if isset($tbcmsblogposts)}
<div class="footer_blog_area col-sm-12 clearfix">
	{if isset($tbcmsbdp_title)}
		<div class="footer_blog_title">
			<em>{$tbcmsbdp_title}</em>
		</div>
	{/if}
	<div class="footer_blog_post carousel">
		{foreach from=$tbcmsblogposts item=tbcmsblgpst}
		<div class="blog_post">
			<div class="blog_post_left">
				<p>
					{$tbcmsblgpst.post_date|date_format:"<span>%e</span> <span>%b</span>"|escape:'html':'UTF-8'}
				</p>
			</div>
			<div class="blog_post_right">
				<h3 class="post_title">
					<a href="{$tbcmsblgpst.link}">{$tbcmsblgpst.post_title}</a>
				</h3>
				<div class="post_description">
					<p>
						{if isset($tbcmsblgpst.post_excerpt) && !empty($tbcmsblgpst.post_excerpt)}
							{$tbcmsblgpst.post_excerpt|truncate:120:'...'|escape:'html':'UTF-8'}
							<a class="read_more" href="{$tbcmsblgpst.link}"> {l s='Read More >>' mod='tbcmsblogdisplayposts'}</a>
						{else}
							{$tbcmsblgpst.post_content|truncate:120:'...'|escape:'html':'UTF-8'}
							<a class="read_more" href="{$tbcmsblgpst.link}"> {l s='Read More >>' mod='tbcmsblogdisplayposts'}</a>
						{/if}
					</p>
				</div>
			</div>
		</div>
		{/foreach}
	</div>
</div>
{/if}
{/strip}