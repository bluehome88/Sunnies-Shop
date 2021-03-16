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
{if $data}
    <div class="tbcmsmultibanners container-fluid bottom-to-top hb-animate-element">
        <div class="tbmultibanner container">
            <div class='tbmultibanner-wrapper-info row'>
                <div class="tbmultibanner-first-block col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    {assign var='num_of_rec' value='1'}
                    {assign var='TBCMSMULTIBANNER1_IMAGE_NAME' value='TBCMSMULTIBANNER1_IMAGE_NAME_'|cat:$num_of_rec}
                    {assign var='TBCMSMULTIBANNER1_CAPTION' value='TBCMSMULTIBANNER1_CAPTION_'|cat:$num_of_rec}
                    {assign var='TBCMSMULTIBANNER1_CAPTION_SIDE' value='TBCMSMULTIBANNER1_CAPTION_SIDE_'|cat:$num_of_rec}
                    {assign var='TBCMSMULTIBANNER1_LINK' value='TBCMSMULTIBANNER1_LINK_'|cat:$num_of_rec}
                    {if $data[$TBCMSMULTIBANNER1_IMAGE_NAME]}
                    <div class="tbmultibanner-{$num_of_rec|escape:'htmlall':'UTF-8'} tbmultibanner1-wrapper">
                        <a href="{$data[$TBCMSMULTIBANNER1_LINK][$language_id]|escape:'htmlall':'UTF-8'}" class="tbbanner-hover-wrapper">
                            <img src="{$module_dir|escape:'htmlall':'UTF-8'}views/img/tiny/{$data[$TBCMSMULTIBANNER1_IMAGE_NAME][$language_id]|escape:'htmlall':'UTF-8'}" data-org-src="{$module_dir|escape:'htmlall':'UTF-8'}views/img/{$data[$TBCMSMULTIBANNER1_IMAGE_NAME][$language_id]|escape:'htmlall':'UTF-8'}" class="img-responsive" alt="{l s='Multibanner' mod='tbcmsmultibanner1'}" />
                        </a>
                        {if $data[$TBCMSMULTIBANNER1_CAPTION_SIDE][$language_id] != "none"}
                            <div class="{$data[$TBCMSMULTIBANNER1_CAPTION_SIDE][$language_id]|escape:'htmlall':'UTF-8'} tbbanner-content-wrapper">
                                {$data[$TBCMSMULTIBANNER1_CAPTION][$language_id] nofilter}
                            </div>
                        {/if}
                    </div>
                    {/if}
                </div>
                <div class="ttbmultibanner-second-block col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    {assign var='num_of_rec' value='2'}
                    {assign var='TBCMSMULTIBANNER1_IMAGE_NAME' value='TBCMSMULTIBANNER1_IMAGE_NAME_'|cat:$num_of_rec}
                    {assign var='TBCMSMULTIBANNER1_CAPTION' value='TBCMSMULTIBANNER1_CAPTION_'|cat:$num_of_rec}
                    {assign var='TBCMSMULTIBANNER1_CAPTION_SIDE' value='TBCMSMULTIBANNER1_CAPTION_SIDE_'|cat:$num_of_rec}
                    {assign var='TBCMSMULTIBANNER1_LINK' value='TBCMSMULTIBANNER1_LINK_'|cat:$num_of_rec}
                    {if $data[$TBCMSMULTIBANNER1_IMAGE_NAME]}
                    <div class="tbmultibanner-{$num_of_rec|escape:'htmlall':'UTF-8'} tbmultibanner2-wrapper">
                        <a href="{$data[$TBCMSMULTIBANNER1_LINK][$language_id]|escape:'htmlall':'UTF-8'}" class="tbbanner-hover-wrapper"  title="{$data[$TBCMSMULTIBANNER1_CAPTION][$language_id]|escape:'htmlall':'UTF-8'}" >
                            <img src="{$module_dir|escape:'htmlall':'UTF-8'}views/img/tiny/{$data[$TBCMSMULTIBANNER1_IMAGE_NAME][$language_id]|escape:'htmlall':'UTF-8'}" data-org-src="{$module_dir|escape:'htmlall':'UTF-8'}views/img/{$data[$TBCMSMULTIBANNER1_IMAGE_NAME][$language_id]|escape:'htmlall':'UTF-8'}" class="img-responsive" alt="{l s='Multibanner' mod='tbcmsmultibanner1'}" />
                        </a>
                        {if $data[$TBCMSMULTIBANNER1_CAPTION_SIDE][$language_id] != "none"}
                            <div class="{$data[$TBCMSMULTIBANNER1_CAPTION_SIDE][$language_id]|escape:'htmlall':'UTF-8'} tbbanner-content-wrapper">
                                {$data[$TBCMSMULTIBANNER1_CAPTION][$language_id] nofilter}
                            </div>
                        {/if}
                    </div>
                    {/if}
                </div>
                <div class="ttbmultibanner-third-block col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    {assign var='num_of_rec' value='3'}
                    {assign var='TBCMSMULTIBANNER1_IMAGE_NAME' value='TBCMSMULTIBANNER1_IMAGE_NAME_'|cat:$num_of_rec}
                    {assign var='TBCMSMULTIBANNER1_CAPTION' value='TBCMSMULTIBANNER1_CAPTION_'|cat:$num_of_rec}
                    {assign var='TBCMSMULTIBANNER1_CAPTION_SIDE' value='TBCMSMULTIBANNER1_CAPTION_SIDE_'|cat:$num_of_rec}
                    {assign var='TBCMSMULTIBANNER1_LINK' value='TBCMSMULTIBANNER1_LINK_'|cat:$num_of_rec}
                    {if $data[$TBCMSMULTIBANNER1_IMAGE_NAME]}
                    <div class="tbmultibanner-{$num_of_rec|escape:'htmlall':'UTF-8'} tbmultibanner3-wrapper">
                        <a href="{$data[$TBCMSMULTIBANNER1_LINK][$language_id]|escape:'htmlall':'UTF-8'}" class="tbbanner-hover-wrapper"  title="{$data[$TBCMSMULTIBANNER1_CAPTION][$language_id]|escape:'htmlall':'UTF-8'}" >
                            <img src="{$module_dir|escape:'htmlall':'UTF-8'}views/img/tiny/{$data[$TBCMSMULTIBANNER1_IMAGE_NAME][$language_id]|escape:'htmlall':'UTF-8'}" data-org-src="{$module_dir|escape:'htmlall':'UTF-8'}views/img/{$data[$TBCMSMULTIBANNER1_IMAGE_NAME][$language_id]|escape:'htmlall':'UTF-8'}" class="img-responsive" alt="{l s='Multibanner' mod='tbcmsmultibanner1'}" />
                        </a>
                        {if $data[$TBCMSMULTIBANNER1_CAPTION_SIDE][$language_id] != "none"}
                            <div class="{$data[$TBCMSMULTIBANNER1_CAPTION_SIDE][$language_id]|escape:'htmlall':'UTF-8'} tbbanner-content-wrapper">
                                {$data[$TBCMSMULTIBANNER1_CAPTION][$language_id] nofilter}
                            </div>
                        {/if}
                    </div>
                    {/if}
                </div>  
            </div>
        </div>
    </div>  
{/if}
{/strip}
