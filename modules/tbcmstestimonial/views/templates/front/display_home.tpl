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
    <div class='container-fluid tbcmstestimonial tbcms-all-testimonial' style="background-image:url({$dis_arr_result['path']}{$main_heading.data.image})">
        <div class='container tbtestimonial'>

            {*<div class='tbcmstestimonial-main-title-wrapper'>
                {include file='_partials/tbcms-main-title.tpl' main_heading=$main_heading path=$dis_arr_result['path']}
            </div>*}


            <div class="tbtestimonial-slider-inner">
                <div class='tbtestimonial-content-box owl-theme owl-carousel'>
                    {foreach $dis_arr_result['data'] as $data}
                    <div class="item tbtestimonial-wrapper-info">
                        <div class="tbtestimonial-inner-content-box">
                            <div class="tbtestimonial-img-block">
                                <img src='{$dis_arr_result["path"]}{$data["image"]}' style='width:100px' />
                            </div>
                            <div class="tbtestimonial-dec">{$data['description']}</div>
                            <div class='tbtestimonial-info-box'>
                                <div class="tbtestimonial-title-des">
                                    <div class="tbtestimonial-title"><a href='{$data["link"]}'>{$data['title']}</a></div>
                                    <div class="tbtestimonial-designation">{$data['designation']}</div>
                                </div>
                                
                            </div>
                           
                        </div>
                    </div>  
                    {/foreach}
                </div>
            </div>  
        
            {*<div class='tbcms-testimonial-pagination-wrapper'>
                <div class="tbcms-testimonial-pagination">
                    <div class="tbcms-testimonial-next-pre-btn">
                        <div class="tbtestimonial-prev tbcmsprev-btn"><i class='material-icons'>&#xe5c4;</i></div>
                        <div class="tbtestimonial-next tbcmsnext-btn"><i class='material-icons'>&#xe5c8;</i></div>
                    </div>
                </div>
            </div>*}
        </div>
    </div>
{/if}
{/strip}