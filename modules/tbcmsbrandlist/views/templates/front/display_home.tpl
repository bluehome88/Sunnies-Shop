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
{if $dis_arr_result['status']}
<div class="tbcmsbrandlist-slider container-fluid bottom-to-top hb-animate-element">
	<div class='tbbrandlist-slider container'>
			
        <div class='tbcmsbrandlist-slider-main-title-wrapper'>
			{include file='_partials/tbcms-left-column-title.tpl' status=$main_heading['main_title'] title=$main_heading['data']['title']}
        </div>

		<div class="tbbrandlist-slider-inner row">
			<div class='tbbrandlist-slider-content-box owl-theme owl-carousel'>
				{$count = 1}
				{foreach $dis_arr_result['data'] as $data}
					<div class="item tbbrandlist-slider-wrapper-info wow zoomIn tball-block-box-shadows">
						<div class="tbbrand-img-block">
							<a href="{$data['link']}" title="{$data['title']}">
								<img src="{$dis_arr_result['path']}{$data['image']}" alt="{$data['title']}" />
							</a>
						</div>
						<div class='tbbrandlist-slider-info-box'>
							<div class="tbbrandlist-slider-title">{$data['title']}</div>
						</div>
					</div>	
				{/foreach}
			</div>
		</div>	
	
		<div class='tbcms-brandlist-pagination-wrapper'>
			<div class="tbcms-brandlist-pagination">
				<div class="tbcms-brandlist-wrapper">
				  	<div class="tbcmsbrandprev-btn"><i class='material-icons'>&#xe5cb;</i></div>
				  	<div class="tbcmsbrandnext-btn"><i class='material-icons'>&#xe5cc;</i></div>
				</div>
			</div>
	  	</div>
	</div>
</div>	
{/if}
{/strip}