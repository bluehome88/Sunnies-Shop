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

<div class="tbcms-slider-offerbanner-wrapper container-fluid">
    <div class="tbcmsmain-slider-wrapper container" data-speed='{$main_slider_js.speed}' data-pause-hover='{$main_slider_js.pause}'>
        <div class='tbcms-main-slider'>
            <div class='tb-main-slider'>
                <div id='tbmain-slider' class="owl-theme owl-carousel">
                	{$i = 1}
                	{foreach $data as $slide}
                        <div class='item'>
                			{if empty($slide['btn_caption']) || $slide['class_name'] == 'tbmain-slider-contant-none'}
                				<a href="{$slide['url']}">
                			{/if}
            					<img class="tbmain-slider-img" src='{$slide["image_url"]}' alt='{l s="Main Slider" mod="tbcmsslider"}' title='#tbmain-slider-img-{$i}'>
                			{if empty($slide['btn_caption']) || $slide['class_name'] == 'tbmain-slider-contant-none'}
            					</a> 
                			{/if}

                            {if $slide['image_url'] && $slide['class_name'] != 'tbmain-slider-contant-none'}
                                {if $slide['title'] || $slide['description']}
                                    <div id='tbmain-slider-img-{$i}' class="tbmain-slider-content-inner">
                                        <div class='tbmain-slider-contant {$slide["class_name"]}'>
                                            <h2 class="tbmain-slider-title animated">{$slide['title']}</h2>
                                            <div class="tbmain-slider-info animated">{$slide['description'] nofilter}</div> 
                                            {if !empty($slide['btn_caption'])}
                                                <div class="tbmain-slider-btn">
                                                    <a href='{$slide["url"]}' class="tbmain-slider-button animated">{$slide['btn_caption']}</a>
                                                </div> 
                                            {/if}
                                        </div>
                                    </div> 
                                {/if}
                            {/if}
                        </div>

        				{$i = $i + 1}
            		{/foreach} 
            	</div> 

                <div class="tbmain-slider-next-pre-btn">
                    <div class="tbcmsmain-prev tbcmssliderprev-btn">
                        <i class='material-icons'>&#xe5cb;</i>
                    </div>
                    <div class="tbcmsmain-next tbcmsslidernext-btn">
                        <i class='material-icons'>&#xe5cc;</i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{/strip}
