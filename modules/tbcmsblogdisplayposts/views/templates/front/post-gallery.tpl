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
{if (isset($gallery) && !empty($gallery))}
    {$tmp = 1}
    {foreach from=$gallery item=galleryimg}
        {if $tmp==1}
            {* <div class="post_meta">
                <div class="meta-author tbnews-event-username">
                    <i class="ion-ios-plus-empty"></i>
                    <p>{$firstname} {$lastname}</p>
                </div>
            </div> *}

            <div class="blog_mask tbblog-image-block">
                <div class="post_thumbnail">
                    <a class="img_content" href="{$link}">
                        <div class='tbnews-event-hoverbtn'>
                            <div class='tbblog-content-img' style='background-image:url({$galleryimg.$imagesize})'>
                            </div>
                            <!-- <div class='tbnews-event-overly'></div> -->
                            <div class='tbnews-event-buttons'>
                                <i class='material-icons'>&#xe8b6;</i>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            {$tmp = 0}
        {/if}
    {/foreach}
{/if}
{/strip}
