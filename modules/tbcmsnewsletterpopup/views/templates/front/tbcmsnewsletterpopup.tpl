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
{if Configuration::get('TBCMSNEWSLETTERPOPUP_POPUP_STATUS')}
<div id='tbcmsNewsLetterPopup' class='modal fade' tabindex='-1' role='dialog'>
    <div class='tbcmsNewsLetterPopup-i modal-dialog' role='document'>
        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
            <span class="tbnewsletterpopup-button-icon">
                <i class='material-icons'>&#xe5cd;</i>
            </span>
        </button>
        <div class='tbcmsnewsletterpopup' style='{if $show_fields["bg_image"] && Configuration::get("TBCMSNEWSLETTERPOPUP_BG_IMG_STATUS") && Configuration::get("TBCMSNEWSLETTERPOPUP_BG_IMG", $id_lang)}background-image: url({$path}{(Configuration::get("TBCMSNEWSLETTERPOPUP_BG_IMG", $id_lang))});{else}{* background-color:#fff; *}{/if}'>
            <div id='newsletter_block_popup' class='block'>
                <div class='block_content'>
                    {if isset($msg) && $msg}
                    <p class='{if $nw_error}warning_inline{else}success_inline{/if}'>{$msg}</p>
                    {/if}
                    <form method='post'>

                        {if $show_fields['sub_title'] && Configuration::get('TBCMSNEWSLETTERPOPUP_SUB_DESCRIPTION', $id_lang)}
                        <div class='tbcmsnewsletterpopupContent'>
                            {Configuration::get('TBCMSNEWSLETTERPOPUP_SUB_DESCRIPTION', $id_lang)}
                        </div>
                        {/if}

                        {if $show_fields['title'] && Configuration::get('TBCMSNEWSLETTERPOPUP_TITLE', $id_lang)}
                        <div class='newsletter_title'>
                            <h3 class='h3'>{Configuration::get('TBCMSNEWSLETTERPOPUP_TITLE', $id_lang)}</h3>
                        </div>
                        {/if}

                        
                        <div class='tbcmsnewsletterpopupAlert'></div>
                    </form>
                    <div class='tbnewslatter-input-wrapper'>
                        <div class="tbnewsletterpopup-input">
                            <input class='inputNew' id='tbcmsnewsletterpopupnewsletter-input' type='text' name='email' placeholder="{l s='Enter your mail...' mod='tbcmsnewsletterpopup'}" />
                        </div>
                        <div id='tbnewsletter-email-subscribe' class='send-reqest button_unique'>
                            {l s='Start Free Trial' mod='tbcmsnewsletterpopup'}
                            <i class='material-icons'>&#xe5c8;</i>
                        </div>
                    </div>
                    <div class='newsletter_block_popup-bottom d-flex justify-content-center'>
                        <div class='subscribe-bottom'>
                            <input class='tbcmsnewsletterpopup_newsletter_dont_show_again' type='checkbox' id='newsletter-checkbox'>
                            <span class="tbcmsnewsletterpopup_checkbox" for='newsletter-checkbox'></span>
                        </div>
                        <!-- <a class='tbcmsnewsletterpopup_newsletter_dont_show_again' href='Javascript:void(0);'>{l s='No Thanks, Do not show again' mod='tbcmsnewsletterpopup'}</a> -->
                        <label for='newsletter-checkbox'>{l s='No Thanks, Do not show again' mod='tbcmsnewsletterpopup'}</label>
                    </div>
                </div>
            </div>
            <div class='tbnewslatter-popup-img-wrapper'>
                <div class='tbnewslatter-popup-img'>
                    {if $show_fields['image'] && Configuration::get(TBCMSNEWSLETTERPOPUP_IMG_STATUS) && Configuration::get('TBCMSNEWSLETTERPOPUP_IMG', $id_lang)}
                    <img src="{$path}{Configuration::get('TBCMSNEWSLETTERPOPUP_IMG', $id_lang)}" alt="" height="443" width="323">
                    {/if}
                </div>
            </div>
        </div>
    </div>
</div>
{/if}
{/strip}