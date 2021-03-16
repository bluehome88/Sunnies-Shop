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
{if $dis_arr_result.status}
    <div class="tbcmscustomer-services container-fluid bottom-to-top hb-animate-element">
        <div class="tbcustomer-services container">
            <div class="tbservice-inner ">

                {include file='_partials/tbcms-main-title.tpl' main_heading=$main_heading path=$dis_arr_result['path']}

                <div class="tbservice-all-block-wrapper row">
                    <div class="tbservices-all-block">

                        <div class="tb-all-service wrapper card-deck">
                            {if $dis_arr_result.data.service_1.status}
                            <div class="tbservices-center card odd tbservice-payment">
                                <div class="tball-block-box-shadows">
                                    <div class="tbservices-1 tball-services-block">
                                        <div class="tbservices-wrapper">
                                            <div class="tbservices-img-conut">
                                                <div class='tbservices-img'>
                                                    {* <i class='material-icons'>&#xe163;</i> *}
                                                   {* <img src="{$dis_arr_result.path}tiny/{$dis_arr_result.data.service_1.image}" data-org-src="{$dis_arr_result.path}{$dis_arr_result.data.service_1.image}"> *} 
                                                </div>
                                                {* <div class='tbservices-count'>
                                                    {l s='01' mod='tbcmscustomerservices'}
                                                </div> *}
                                            </div>
                                            <div class='tbservices-content-box'>
                                                <div class="tbservices-info">
                                                    <div class="tbservices-title">{$dis_arr_result.data.service_1.title}</div>

                                                    <div class="tbservice-dec">{$dis_arr_result.data.service_1.desc}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {/if}

                            {if $dis_arr_result.data.service_2.status}
                                <div class="tbservices-center card even tbservice-cash-trustpay">
                                    <div class="tball-block-box-shadows">
                                        <div class="tbservices-2 tball-services-block">
                                            <div class="tbservices-wrapper">
                                                <div class="tbservices-img-conut">
                                                    <div class='tbservices-img'>
                                                        {* <i class='material-icons'>&#xe32a;</i> *}
                                                      {* <img src="{$dis_arr_result.path}tiny/{$dis_arr_result.data.service_2.image}" data-org-src="{$dis_arr_result.path}{$dis_arr_result.data.service_2.image}"> *}
                                                    </div>
                                                    {*<div class='tbservices-count'>
                                                        {l s='02' mod='tbcmscustomerservices'}
                                                    </div> *}
                                                </div>
                                                <div class='tbservices-content-box'>
                                                    <div class="tbservices-info">
                                                        <div class="tbservices-title">{$dis_arr_result.data.service_2.title}</div>
                                                        <div class="tbservice-dec">{$dis_arr_result.data.service_2.desc}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            {/if}
                            {if $dis_arr_result.data.service_3.status}
                            <div class="tbservices-center card odd tbservice-supprt">
                                <div class="tball-block-box-shadows">
                                    <div class="tbservices-3 tball-services-block">
                                        <div class="tbservices-wrapper">
                                            <div class="tbservices-img-conut">
                                                <div class='tbservices-img'>
                                                   {* <i class='material-icons'>&#xe8f6;</i> *}
                                                    {* <img src="{$dis_arr_result.path}tiny/{$dis_arr_result.data.service_3.image}" data-org-src="{$dis_arr_result.path}{$dis_arr_result.data.service_3.image}">  *}
                                                </div>
                                                {* <div class='tbservices-count'>
                                                    {l s='03' mod='tbcmscustomerservices'}
                                                </div> *}
                                            </div>
                                            <div class='tbservices-content-box'>
                                                <div class="tbservices-info">
                                                    <div class="tbservices-title">{$dis_arr_result.data.service_3.title}</div>
                                                    <div class="tbservice-dec">{$dis_arr_result.data.service_3.desc}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {/if}

                            {*{if $dis_arr_result.data.service_4.status}
                            <div class="tbservices-center card even tbservice-shopon">
                                <div class="tball-block-box-shadows">
                                    <div class="tbservices-4 tball-services-block">
                                        <div class="tbservices-wrapper">
                                            <div class="tbservices-img-conut">
                                                <div class='tbservices-img'>
                                                     <i class='material-icons'>&#xe558;</i>
                                                     <img src="{$dis_arr_result.path}tiny/{$dis_arr_result.data.service_4.image}" data-org-src="{$dis_arr_result.path}{$dis_arr_result.data.service_4.image}">
                                                </div>
                                                <div class='tbservices-count'>
                                                    {l s='04' mod='tbcmscustomerservices'}
                                                </div>
                                            </div>
                                            <div class='tbservices-content-box'>
                                                <div class="tbservices-info">
                                                    <div class="tbservices-title">{$dis_arr_result.data.service_4.title}</div>
                                                    <div class="tbservice-dec">{$dis_arr_result.data.service_4.desc}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {/if}*}
                        </div>
                    </div>

                   
                </div>
            </div>
        </div>
    </div>
{/if}
{/strip}