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
    <div class='tbcms-left-testimonial tbcms-all-testimonial'>
        <div class='tbtestimonial'>

        	{include file='_partials/tbcms-left-column-title.tpl' status=$main_heading.title title=$main_heading.data.title}

            <div class="tbtestimonial-slider-button-wrapper">
                <div class="tbtestimonial-slider-inner">
                    <div class='tbtestimonial-content-box owl-theme owl-carousel'>
                        {foreach $dis_arr_result['data'] as $data}
                        <div class="item tbtestimonial-wrapper-info">
                            <div class="tbtestimonial-inner-content-box">
                                
                                <div class="tbtestimonial-img-content-block">
                                    <div class="tbtestimonial-img-block">
                                        <img src='{$dis_arr_result["path"]}{$data["image"]}' style='width:50px' alt=""/>
                                    </div>
                                    <div class="tbtestimonial-title-des">
                                        <div class="tbtestimonial-title"><a href='{$data["link"]}'>{$data['title']}</a></div>
                                        <div class="tbtestimonial-designation">{$data['designation']}</div>
                                    </div>   
                                </div>
                                <div class='tbtestimonial-info-box'>
                                    <div class="tbtestimonial-dec">{$data['description']}</div>                                 
                                </div>
                               
                            </div>
                        </div>  
                        {/foreach}
                    </div>
                </div>  
            
                <div class='tbcms-testimonial-pagination-wrapper'>
                    <div class="tbcms-testimonial-pagination">
                        <div class="tbcms-testimonial-next-pre-btn tbleft-btn-wrapper">
                            <div class="tbtestimonial-prev tbleft-prev-btn tbcmsprev-btn"><i class='material-icons'>&#xe5c4;</i></div>
                            <div class="tbtestimonial-next tbleft-next-btn tbcmsnext-btn"><i class='material-icons'>&#xe5c8;</i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
{/if}
{/strip}