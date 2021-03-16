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
<div class="tbcmsrightsideofferbanners-one">
    <div class="tbcmstbcmsrightsideofferbanners">
        <div class="tbbanner">
            <div class="tbbanner-wrapper tbone-banner-wrapper-info">
                <div class="tbbanner1">
                    <a href="{$data['TBCMSRIGHTSIDEOFFERBANNER_LINK']}" title="{$data['TBCMSRIGHTSIDEOFFERBANNER_CAPTION'][$language_id]}">
                        <div class="tbbanner-hover-wrapper">
                            <img src="{$path}tiny/{$data['TBCMSRIGHTSIDEOFFERBANNER_IMAGE_NAME']}" data-org-src="{$path}{$data['TBCMSRIGHTSIDEOFFERBANNER_IMAGE_NAME']}" class="tbimage-lazy img-responsive" alt="{if !empty($data['TBCMSRIGHTSIDEOFFERBANNER_CAPTION'][$language_id])}{$data['TBCMSRIGHTSIDEOFFERBANNER_CAPTION'][$language_id]}{else}{l s='Slider Offer Banner' mod='tbcmsrightsideofferbanner'}{/if}" />
                        </div>
                        {* {if !empty($data['TBCMSRIGHTSIDEOFFERBANNER_CAPTION'][$language_id])}
                        <div>
                            {$data['TBCMSRIGHTSIDEOFFERBANNER_CAPTION'][$language_id]}
                        </div>
                        {/if} *}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>  
{/strip}
