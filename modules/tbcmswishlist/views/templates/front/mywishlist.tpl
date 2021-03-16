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
{extends file='page.tpl'}
{block name='page_title'}
    {l s='New wishlist' mod='tbcmswishlist'}
{/block}
{block name='page_content'}
<div class="col-sm-12 col-xs-12 tbcmswishlist">
    <div id="mywishlist">
        {* {if isset($errors) && $errors}
            <div class="alert alert-danger">
                <p>{if $errors|@count > 1}{l s='There are %d errors' sprintf=$errors|@count mod='tbcmswishlist'}{else}{l s='There is %d error' sprintf=$errors|@count mod='tbcmswishlist'}{/if}</p>
                <ol>
                {foreach from=$errors key=k item=error}
                    <li>{$error|escape:'htmlall':'UTF-8'}</li>
                {/foreach}
                </ol>
                {if isset($smarty.server.HTTP_REFERER) && !strstr($request_uri, 'authentication') && preg_replace('#^https?://[^/]+/#', '/', $smarty.server.HTTP_REFERER) != $request_uri}
                    <p class="lnk"><a class="alert-link" href="#" title="{l s='Back' mod='tbcmswishlist'}">&laquo; {l s='Back' mod='tbcmswishlist'}</a></p>
                {/if}
            </div>
        {/if} *}
        {if $id_customer|intval neq 0}
            <form method="post" class="std" id="form_wishlist">
                <fieldset>
                    <div class="form-group">
                        <input type="hidden" name="token" value="{$token|escape:'html':'UTF-8'}" />
                        <label class="align_right tbwishlist-titel-name" for="name">
                            {l s='Name' mod='tbcmswishlist'}
                        </label>
                        <input type="text" id="name" name="name" class="inputTxt form-control" value="{if isset($smarty.post.name) and $errors|@count > 0}{$smarty.post.name|escape:'html':'UTF-8'}{/if}" />
                    </div>
                    <p class="submit tbwishlist-btn">
                        <button id="submitWishlist" class="btn btn-default button button-medium" type="submit" name="submitWishlist">
                            <span>{l s='Save' mod='tbcmswishlist'}</span>
                        </button>
                    </p>
                </fieldset>
            </form>
            {if $wishlists}
                <div id="block-history" class="block-center table-responsive">
                    <table class="table table-bordered  tbwishlist-info">
                        <thead>
                            <tr>
                                <th class="first_item tbwishlist-name">{l s='Name' mod='tbcmswishlist'}</th>
                                <th class="item mywishlist_first tbwishlist-Qty">{l s='Qty' mod='tbcmswishlist'}</th>
                                <th class="item mywishlist_first tbwishlist-Viewed">{l s='Viewed' mod='tbcmswishlist'}</th>
                                <th class="item mywishlist_second tbwishlist-Created">{l s='Created' mod='tbcmswishlist'}</th>
                                <th class="item mywishlist_second tbwishlist-Direct Link">{l s='Direct Link' mod='tbcmswishlist'}</th>
                                <th class="item mywishlist_second tbwishlist-Default">{l s='Default' mod='tbcmswishlist'}</th>
                                <th class="last_item mywishlist_first tbwishlist-Delete">{l s='Delete' mod='tbcmswishlist'}</th>
                            </tr>
                        </thead>
                        <tbody>
                            {section name=i loop=$wishlists}
                                <tr id="wishlist_{$wishlists[i].id_wishlist|intval}">
                                    <td style="width:200px;" class="tbwishlist-view-name">
                                        <a href="#" onclick="javascript:event.preventDefault();WishlistManage('block-order-detail', '{$wishlists[i].id_wishlist|intval}');">
                                            {$wishlists[i].name|truncate:30:'...'|escape:'htmlall':'UTF-8'}
                                        </a>
                                    </td>
                                    <td class="bold align_center">
                                        {assign var=n value=0}
                                        {foreach from=$nbProducts item=nb name=i}
                                            {if $nb.id_wishlist eq $wishlists[i].id_wishlist}
                                                {assign var=n value=$nb.nbProducts|intval}
                                            {/if}
                                        {/foreach}
                                        {if $n}
                                            {$n|intval|escape:'htmlall':'UTF-8'}
                                        {else}
                                            0
                                        {/if}
                                    </td>
                                    <td>{$wishlists[i].counter|intval|escape:'htmlall':'UTF-8'}</td>
                                    <td>{$wishlists[i].date_add|date_format:"%Y-%m-%d"|escape:'htmlall':'UTF-8'}</td>
                                    <td class="tbwishlist-view-link">
                                        <a href="#" onclick="javascript:event.preventDefault();WishlistManage('block-order-detail', '{$wishlists[i].id_wishlist|intval|escape:"htmlall":"UTF-8"}');">
                                            {l s='View' mod='tbcmswishlist'}
                                        </a>
                                    </td>
                                    <td class="wishlist_default">
                                        {if isset($wishlists[i].default) && $wishlists[i].default == 1}
                                            <p class="is_wish_list_default">
                                                <i class='material-icons'>&#xe834;</i>
                                            </p>
                                        {else}
                                            <a href="#" onclick="javascript:event.preventDefault();(WishlistDefault('wishlist_{$wishlists[i].id_wishlist|intval|escape:'htmlall':'UTF-8'}', '{$wishlists[i].id_wishlist|intval|escape:'htmlall':'UTF-8'}'));">
                                                <i class='material-icons'>&#xe835;</i>
                                            </a>
                                        {/if}
                                    </td>
                                    <td class="wishlist_delete">
                                        <a class="icon" href="#" onclick="javascript:event.preventDefault();return (WishlistDelete('wishlist_{$wishlists[i].id_wishlist|intval|escape:'htmlall':'UTF-8'}', '{$wishlists[i].id_wishlist|intval|escape:'htmlall':'UTF-8'}', '{l s='Do you really want to delete this wishlist ?' mod='tbcmswishlist' js=1}'));">
                                            <i class='material-icons'>&#xe872;</i>
                                        </a>
                                    </td>
                                </tr>
                            {/section}
                        </tbody>
                    </table>
                </div>
                <div id="block-order-detail">&nbsp;</div>
            {/if}
        {/if}
        <div class="footer_links wishlist_footer clearfix tbwishlist-back-btn">
            <a class="btn btn-default button button-small btn-back-to-account" href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}">
                <i class='material-icons'>&#xe314;</i>
                <span>
                    {l s='Back to Your Account' mod='tbcmswishlist'}
                </span>
            </a>
            <a class="btn btn-default button button-small pull-right btn-home" href="{$urls.pages.index|escape:'html':'UTF-8'}">
               <i class='material-icons'>&#xe88a;</i>
                <span>
                    {l s='Home' mod='tbcmswishlist'}
                </span>
            </a>
        </div>
    </div>
</div>
{/block}
{/strip}