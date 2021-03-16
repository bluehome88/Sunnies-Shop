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
<div class="post_format_items tbcmsblog-video-slider tbblog-balance-height tbcmsblog-gallery-slider-{$blog_id}" data-slider-id='tbcmsblog-gallery-slider-{$blog_id}'>
	<div class="tbblog-wrapper-slider {if count($postvideos) > 1}owl-theme owl-carousel{/if}">
	{if isset($postvideos) && $postvideos}
	{foreach from=$postvideos item=videourl}
		{$youtube = preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $videourl, $match)}
		{$youtube_id = $match[1]}

		{* {$videourl} *}
		<a href="{$videourl|escape:'htmlall':'UTF-8'}?autoplay=1" class="item swiper-slide various fancybox fancybox.iframe img_content" style='width:100%;'>
			<div class="tbnews-event-hoverbtn">	
				<div class="tbblog-content-img tbblog-balance-height" style="background-image: url('https://img.youtube.com/vi/{$youtube_id}/0.jpg');">
			  	</div>
				<div class="tbnews-event-overly"></div>
			  	<div class="tbnews-event-buttons">
		  			<i class='material-icons'>&#xe038;</i>
			  	</div>
			</div>
			{* <div class="post_meta">
				<div class="meta-author tbnews-event-username">
					<i class='material-icons'>&#xe038;</i>
					<p>{$firstname} {$lastname}</p>
				</div>
			</div> *}
		</a>
	{/foreach}
	{/if}
	</div>
	{if count($postvideos) > 1}
	<div class="tbcmsblog-slider-pagination">
        <div class="tbcmsblog-next-pre-btn">
          	<div class="tbblog-video-slider-prev"><i class='material-icons'>&#xe314;</i></div>
          	<div class="tbblog-video-slider-next"><i class='material-icons'>&#xe315;</i></div>
        </div>
    </div>
    {/if}
</div>

{/strip}
