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
<div class="tb-blog-content-wrapper clearfix">
    {block name='page_header_container'}
    <div class="tball-page-top-title">
        <h1 class="tbpage-header-title">{$meta_title}</h1>
    </div>
    {/block}

{if isset($tbcmsblogpost) && !empty($tbcmsblogpost)}
    <div class="kr_blog_post_area">
        <div class="kr_blog_post_inner products">
            {foreach from=$tbcmsblogpost item=tbcmsblgpst}
                <article class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-xs-12 tbblog_post blog_post_{$tbcmsblgpst.post_format}">
                    <div class="blog_post_content tbblog-event-all-content-block">
                        <div class="blog_post_content_top">
                            <div class="post_thumbnail">
                            {block name="tbcmsblog_post_thumbnail"}
                                {if $tbcmsblgpst.post_format == 'video'}
                                    {assign var="postvideos" value=','|explode:$tbcmsblgpst.video}
                                    {if $postvideos|@count > 1 }
                                        {assign var="class" value='carousel'}
                                    {else}
                                        {assign var="class" value=''}
                                    {/if}
                                    {include file="module:tbcmsblog/views/templates/front/default/post-video.tpl" postvideos=$postvideos width='870' height="482" class=$class blog_id=$tbcmsblgpst.id_tbcmsposts}
                                {elseif $tbcmsblgpst.post_format == 'audio'}
                                    {assign var="postaudio" value=','|explode:$tbcmsblgpst.audio}
                                    {if $postaudio|@count > 1 }
                                        {assign var="class" value='carousel'}
                                    {else}
                                        {assign var="class" value=''}
                                    {/if}
                                    {include file="module:tbcmsblog/views/templates/front/default/post-audio.tpl" postaudio=$postaudio class=$class blog_id=$tbcmsblgpst.id_tbcmsposts}
                                {elseif $tbcmsblgpst.post_format == 'gallery'}
                                    {if $tbcmsblgpst.gallery_lists|@count > 1 }
                                        {assign var="class" value='carousel'}
                                    {else}
                                        {assign var="class" value=''}
                                    {/if}
                                    {include file="module:tbcmsblog/views/templates/front/default/post-gallery.tpl" gallery_lists=$tbcmsblgpst.gallery_lists imagesize="medium" class=$class blog_id=$tbcmsblgpst.id_tbcmsposts}
                                {else}
                                <div class="tbnews-event-hoverbtn ">
                                    <img class="img-responsive tbblog-balance-height" src="{$tbcmsblgpst.post_img_medium}" alt="{$tbcmsblgpst.post_title}">
                                    <div class="tbnews-event-overly"></div>
                                    <div class="tbnews-event-buttons">
                                        <a href="{$tbcmsblgpst.link}">
                                            <i class='material-icons'>&#xe8b6;</i>
                                        </a>
                                    </div>
                                </div>    
                                   {* <div class="blog_mask">
                                        <div class="blog_mask_content">
                                            <a class="thumbnail_lightbox" href="{$tbcmsblgpst.post_img_medium}">
                                                <i class='material-icons'>&#xe145;</i>
                                            </a>                                        
                                        </div>
                                    </div> *}
                                {/if}
                            {/block}
                            </div>
                        </div>
                        <div class="post_content">
                           
                            <div class="post_meta clearfix">
                                {* <p class="meta_author">
                                    {l s='Posted by ' mod='tbcmsblog'}
                                    <span>{$tbcmsblgpst.post_author_arr.firstname} {$tbcmsblgpst.post_author_arr.lastname}</span>
                                </p> *}
                                <p class="meta_date">
                                    {* <i class='material-icons'>&#xe916;</i> *}
                                    {$tbcmsblgpst.post_date|date_format:"%d %b %Y"}
                                </p>
                                {* <p class="meta_category">
                                        <a href="{$tbcmsblgpst.category_arr.link}">{$tbcmsblgpst.category_arr.name}</a>
                                </p> *}
                            </div>
                             <h3 class="post_title"><a href="{$tbcmsblgpst.link}">{$tbcmsblgpst.post_title}</a></h3>
                            <div class="post_description tbblog-desc-balance-height">
                                {if isset($tbcmsblgpst.post_excerpt) && !empty($tbcmsblgpst.post_excerpt)}
                                    <p>{$tbcmsblgpst.post_excerpt|truncate:500:'...'|escape:'html':'UTF-8'}</p>
                                {else}
                                    <p>{$tbcmsblgpst.post_content|truncate:400:'...'|escape:'html':'UTF-8'}</p>
                                {/if}
                            </div>
                            <div class="read_more">
                                <a class="more" href="{$tbcmsblgpst.link}">{l s='Continue' mod='tbcmsblog'} <i class="arrow_right"></i></a>
                            </div>
                        </div>
                    </div>
                </article>
            {/foreach}
        </div>
    </div>
{/if}
</div>
{include file="module:tbcmsblog/views/templates/front/default/pagination.tpl"}

{/block}


{block name='breadcrumb'}
<div class="breadcrumb_container">
    <nav data-depth="{$breadcrumb.count+2}" class="breadcrumb">
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