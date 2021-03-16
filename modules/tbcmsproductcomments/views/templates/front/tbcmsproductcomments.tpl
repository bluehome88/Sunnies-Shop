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
<script>
    var tbcmsproductcomments_controller_url = '{$tbcmsproductcomments_controller_url nofilter}';
    var confirm_report_message = '{l s='Are you sure that you want to report this comment?' mod='tbcmsproductcomments' js=1}';
    var secure_key = '{$secure_key}';
    var tbcmsproductcomments_url_rewrite = '{$tbcmsproductcomments_url_rewriting_activated}';
    var tbcmsproductcomment_added = '{l s='Your comment has been added!' mod='tbcmsproductcomments' js=1}';
    var tbcmsproductcomment_added_moderation = '{l s='Your comment has been submitted and will be available once approved by a moderator.' mod='tbcmsproductcomments' js=1}';
    var tbcmsproductcomment_title = '{l s='New comment' mod='tbcmsproductcomments' js=1}';
    var tbcmsproductcomment_ok = '{l s='OK' mod='tbcmsproductcomments' js=1}';
    var moderation_active = {$moderation_active};
</script>
<div class="tab-pane fade in" id="tbcmsproductCommentsBlock" role="tabpanel">
    {* <div class="tbproduct-comment-review-title products-section-title">{l s='Reviews' mod='tbcmsproductcomments'}</div> *}
    <div class="tabs">
        <div class="clearfix pull-right tbReviews">
            {if ($too_early == false AND ($logged OR $allow_guests))}
                <a class="open-comment-form btn btn-primary" href="#new_comment_form">{l s='Write your review' mod='tbcmsproductcomments'}</a>
            {/if}
        </div>
        <div id="new_comment_form_ok" class="alert alert-success" style="display:none;padding:15px 25px"></div>
        <div id="tbcmsproduct_comments_block_tab">
            {if $comments}
                {foreach from=$comments item=comment}
                    {if $comment.content}
                        <div class="comment clearfix">
                            <div class="comment_author">
                                {* <span>{l s='Grade' mod='tbcmsproductcomments'}&nbsp;</span> *}
                                <div class="comment_author_infos">
                                    <strong>{$comment.customer_name|escape:'html':'UTF-8'}</strong><br/>
                                    <em>{dateFormat date=$comment.date_add|escape:'html':'UTF-8' full=0}</em>
                                </div>
                                <div class="star_content clearfix">
                                    {section name="i" start=0 loop=5 step=1}
                                        {if $comment.grade le $smarty.section.i.index}
                                            <div class="star"> <i class='material-icons'>&#xe83a;</i>{*<img src="{$path}star-no.png" alt=""> *}</div>
                                        {else}
                                            <div class="star star_on"><i class='material-icons'>&#xe838;</i> {*<img src="{$path}star.png" alt=""> *}</div>
                                        {/if}
                                    {/section}
                                </div>
                            </div>
                            <div class="comment_details">
                                <h4 class="title_block">{$comment.title}</h4>
                                <p>{$comment.content|escape:'html':'UTF-8'}</p>
                                <ul>
                                    {if $comment.total_advice > 0}
                                        <li>{l s='%1$d out of %2$d people found this review useful.' sprintf=[$comment.total_useful,$comment.total_advice] mod='tbcmsproductcomments'}</li>
                                    {/if}
                                    {if $logged}
                                        {if !$comment.customer_advice}
                                            <li>{l s='Was this comment useful to you?' mod='tbcmsproductcomments'}
                                                <button class="usefulness_btn" data-is-usefull="1" data-id-product-comment="{$comment.id_tbcmsproduct_comment}">{l s='yes' mod='tbcmsproductcomments'}</button>
                                                <button class="usefulness_btn" data-is-usefull="0" data-id-product-comment="{$comment.id_tbcmsproduct_comment}">{l s='no' mod='tbcmsproductcomments'}</button>
                                            </li>
                                        {/if}
                                        {if !$comment.customer_report}
                                            <li><span class="report_btn" data-id-product-comment="{$comment.id_tbcmsproduct_comment}">{l s='Report abuse' mod='tbcmsproductcomments'}</span></li>
                                        {/if}
                                    {/if}
                                </ul>
                                {hook::exec('displayProductComment', $comment) nofilter}
                            </div>
                        </div>
                    {/if}
                {/foreach}
            {else}
                <p class="align_center">{l s='No customer reviews for the moment.' mod='tbcmsproductcomments'}</p>
            {/if}
        </div>
    </div>
    {if isset($product) && $product}
        <!-- Fancybox -->
        <div style="display:none">
            <div id="new_comment_form">
                <form id="id_new_comment_form" action="#">
                    <div class="title">{l s='Write your review' mod='tbcmsproductcomments'}</div>
                    {if isset($product) && $product}
                        <div class="product clearfix">
                            <div class="product_desc">
                                <p class="product_name"><strong>{if isset($product->name)}{$product->name}{elseif isset($product.name)}{$product.name}{/if}</strong></p>
                                {if isset($product->description_short)}{$product->description_short nofilter}{elseif isset($product.description_short)}{$product.description_short nofilter}{/if}
                            </div>
                        </div>
                    {/if}
                    <div class="new_comment_form_content">
                        <div class="tbcmsproduct_comments_title">
                            {l s='Write your review' mod='tbcmsproductcomments'}
                        </div>
                        <div id="new_comment_form_error" class="error" style="display:none;padding:15px 25px">
                            <ul></ul>
                        </div>
                        {if $criterions|@count > 0}
                            <ul id="criterions_list">
                                {foreach from=$criterions item='criterion'}
                                    <li>
                                        <label>{$criterion.name|escape:'html':'UTF-8'}</label>
                                        <div class="star_content">
                                            <input class="star" type="radio" name="criterion[{$criterion.id_tbcmsproduct_comment_criterion|round}]" value="1"/>
                                            <input class="star" type="radio" name="criterion[{$criterion.id_tbcmsproduct_comment_criterion|round}]" value="2"/>
                                            <input class="star" type="radio" name="criterion[{$criterion.id_tbcmsproduct_comment_criterion|round}]" value="3"/>
                                            <input class="star" type="radio" name="criterion[{$criterion.id_tbcmsproduct_comment_criterion|round}]" value="4"/>
                                            <input class="star" type="radio" name="criterion[{$criterion.id_tbcmsproduct_comment_criterion|round}]" value="5" checked="checked"/>
                                        </div>
                                        <div class="clearfix"></div>
                                    </li>
                                {/foreach}
                            </ul>
                        {/if}
                        <label for="comment_title">{l s='Title for your review' mod='tbcmsproductcomments'}<sup class="required">*</sup></label>
                        <input id="comment_title" name="title" type="text" value=""/>
                        <label for="review_content">{l s='Your review' mod='tbcmsproductcomments'}<sup class="required">*</sup></label>
                        <textarea id="review_content" name="content"></textarea>
                        {if $allow_guests == true && !$logged}
                            <label>{l s='Your name' mod='tbcmsproductcomments'}<sup class="required">*</sup></label>
                            <input id="commentCustomerName" name="customer_name" type="text" value=""/>
                        {/if}
                        <div id="new_comment_form_footer">
                            <input id="id_tbcmsproduct_comment_send" name="id_product" type="hidden" value='{$id_tbcmsproduct_comment_form}'/>
                            <p class="fl required"><sup>*</sup> {l s='Required fields' mod='tbcmsproductcomments'}</p>
                            <p class="fr tbreviews-popup-send-btn">
                                <button class="btn btn-primary" id="submitNewMessage" name="submitMessage" type="submit">{l s='Send' mod='tbcmsproductcomments'}</button>&nbsp;
                                {l s='or' mod='tbcmsproductcomments'}&nbsp;
                                <a href="#" onclick="$.fancybox.close();" class="btn btn-primary">
                                    {l s='Cancel' mod='tbcmsproductcomments'}
                                </a>
                            </p>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </form><!-- /end new_comment_form_content -->
            </div>
        </div>
        <!-- End fancybox -->
    {/if}
</div>
{/strip}