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
<div class="tbcmstwoofferbanners-one container-fluid bottom-to-top hb-animate-element">
    <div class="tbcmstbcmstwoofferbanners container">
    	<div class="tbbanner-wrapper tbone-banner-wrapper-info row">
        	<div class="tbbanner1 col-md-6 col-sm-6 col-xs-12">
                <div class="tbofferbanner-two-inner">
                    <a href="{$data['TBCMSTWOOFFERBANNER_LINK']}" {* title="{$data['TBCMSTWOOFFERBANNER_CAPTION'][$language_id]}" *} class="tbbanner-hover-wrapper">
                        <img src="{$path}tiny/{$data['TBCMSTWOOFFERBANNER_IMAGE_NAME']}" data-org-src="{$path}{$data['TBCMSTWOOFFERBANNER_IMAGE_NAME']}" class="tbimage-lazy img-responsive" alt="{l s='Offer Banner' mod='tbcmstwoofferbanner'}" />
                    </a>

                    {if !empty($data['TBCMSTWOOFFERBANNER_CAPTION'][$language_id]) && $data['TBCMSTWOOFFERBANNER_CAPTION_SIDE'] != "none"}
                        <div class="{$data['TBCMSTWOOFFERBANNER_CAPTION_SIDE']} tbbanner-content-wrapper">
                            {$data['TBCMSTWOOFFERBANNER_CAPTION'][$language_id] nofilter}
                        </div>
                    {/if}
                </div>
            </div>

            <div class="tbbanner2 col-md-6 col-sm-6 col-xs-12">
                <div class="tbofferbanner-two-inner">
                    <a href="{$data['TBCMSTWOOFFERBANNER_LINK_2']}" {* title="{$data['TBCMSTWOOFFERBANNER_CAPTION_2'][$language_id]}" *} class="tbbanner-hover-wrapper">
                        <img src="{$path}tiny/{$data['TBCMSTWOOFFERBANNER_IMAGE_NAME_2']}" data-org-src="{$path}{$data['TBCMSTWOOFFERBANNER_IMAGE_NAME_2']}" class="tbimage-lazy img-responsive" alt="{l s='Offer Banner' mod='tbcmstwoofferbanner'}" />
                    </a>

                    {if !empty($data['TBCMSTWOOFFERBANNER_CAPTION_2'][$language_id]) && $data['TBCMSTWOOFFERBANNER_CAPTION_SIDE_2'] != "none"}
                        <div class="{$data['TBCMSTWOOFFERBANNER_CAPTION_SIDE_2']} tbbanner-content-wrapper">
                            {$data['TBCMSTWOOFFERBANNER_CAPTION_2'][$language_id] nofilter}
                        </div>
                    {/if}
                </div>
            </div>
        </div>
    </div>
</div>
{/strip}
