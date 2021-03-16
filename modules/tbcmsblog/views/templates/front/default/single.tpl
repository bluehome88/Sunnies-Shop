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
{extends file='page.tpl'}
{block name="page_content"}
	<div class="kr_blog_post_area single">
		<div class="kr_blog_post_inner">
			<article class="blog_post blog_post_{$tbcmsblogpost.post_format} clearfix">
				<div class="blog_post_content">
					<div class="blog_post_content_top col-xl-5 col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="post_thumbnail">
							{if $tbcmsblogpost.post_format == 'video'}
								{assign var="postvideos" value=','|explode:$tbcmsblogpost.video}
								{include file="module:tbcmsblog/views/templates/front/default/post-video.tpl" postvideos=$postvideos width='870' height="482" blog_id=$tbcmsblogpost.id_tbcmsposts}
							{elseif $tbcmsblogpost.post_format == 'gallery'}
								{include file="module:tbcmsblog/views/templates/front/default/post-gallery.tpl" gallery_lists=$tbcmsblogpost.gallery_lists imagesize="medium" blog_id=$tbcmsblogpost.id_tbcmsposts}
							{else}
								<img class="tbcmsblog_img img-responsive" src="{$tbcmsblogpost.post_img_medium}" alt="{$tbcmsblogpost.post_title}">
							{/if}
						</div>
					</div>
					<div class="post_content col-xl-7 col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<h3 class="post_title">{$tbcmsblogpost.post_title}</h3>
						<div class="post_meta clearfix">
							<p class="meta_author">
								{l s='Posted by ' mod='tbcmsblog'}
								<span>{$tbcmsblogpost.post_author_arr.firstname} {$tbcmsblogpost.post_author_arr.lastname}</span>
							</p>
							<p class="meta_date">
								<i class='material-icons'>&#xe916;</i>
								{$tbcmsblogpost.post_date|date_format:"%b %d, %Y"}
							</p>
							<p class="meta_category">
								<a href="{$tbcmsblogpost.category_arr.link}">{$tbcmsblogpost.category_arr.name}</a>
							</p>
						</div>
						<div class="post_description">
							<p>{$tbcmsblogpost.post_content nofilter}</p>
						</div>
					</div>
				</div>
			</article>
		</div>
	</div>
	{if ($tbcmsblogpost.comment_status == 'open') || ($tbcmsblogpost.comment_status == 'close')}
				{include file="module:tbcmsblog/views/templates/front/default/comment-list.tpl"}
	{/if}
	{if (isset($disable_blog_com) && $disable_blog_com == 1) && ($tbcmsblogpost.comment_status == 'open')}
				{include file="module:tbcmsblog/views/templates/front/default/comment.tpl"}
	{/if}
{/block}
{block name='breadcrumb'}
<div class="breadcrumb_container">
	<nav data-depth="{$breadcrumb.count+1}" class="breadcrumb">
		<div class="container">
			<div class="tbcategory-page-title">{$meta_title}</div>
		    <ol itemscope itemtype="http://schema.org/BreadcrumbList">
		      	{foreach from=$breadcrumb.links item=path name=breadcrumb}
			        <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
			          	<a itemprop="item" href="{$path.url}">
			            	<span itemprop="name">{$path.title}</span>
			          	</a>
			          <meta itemprop="position" content="{$smarty.foreach.breadcrumb.iteration}">
			        </li>
		      	{/foreach}
		    </ol>
  		</div>
	</nav>
</div>
{/block}
{/strip}