{**
 * 2007-2019 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
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
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2019 PrestaShop SA
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}
{strip}
{extends file=$layout}

{block name='head_seo' prepend}
  <link rel="canonical" href="{$product.canonical_url}">
{/block}

{block name='head' append}
  <meta property="og:type" content="product">
  <meta property="og:url" content="{$urls.current_url}">
  <meta property="og:title" content="{$page.meta.title}">
  <meta property="og:site_name" content="{$shop.name}">
  <meta property="og:description" content="{$page.meta.description}">
  <meta property="og:image" content="{$product.cover.large.url}">
  <meta property="product:pretax_price:amount" content="{$product.price_tax_exc}">
  <meta property="product:pretax_price:currency" content="{$currency.iso_code}">
  <meta property="product:price:amount" content="{$product.price_amount}">
  <meta property="product:price:currency" content="{$currency.iso_code}">
  {if isset($product.weight) && ($product.weight != 0)}
  <meta property="product:weight:value" content="{$product.weight}">
  <meta property="product:weight:units" content="{$product.weight_unit}">
  {/if}
{/block}

{block name='content'}
  <div id="main" itemscope itemtype="https://schema.org/Product">
    <meta itemprop="url" content="{$product.url}">
    <div class="tbproduct-page-wrapper">
      <div class="tbprduct-image-info-wrapper clearfix">
        <div class="col-md-6 tb-product-page-image">
          {block name='page_content_container'}
            <div class="page-content" id="content">
              {block name='page_content'}
                {block name='product_flags'}
                  <ul class="product-flags tbproduct-online-new-wrapper">
                    {foreach from=$product.flags item=flag}
                      {if $flag.type == 'online-only' || $flag.type == 'new'}
                        <li class="product-flag {$flag.type}">{$flag.label}</li>
                      {/if}
                    {/foreach}
                  </ul>
                  <ul class="product-flags tbproduct-sale-pack-wrapper">
                    {foreach from=$product.flags item=flag}
                      {if $flag.type == 'on-sale' || $flag.type == 'pack'}
                        <li class="product-flag {$flag.type}">{$flag.label}</li>
                      {/if}
                    {/foreach}
                  </ul>
                {/block}

                {block name='product_cover_thumbnails'}
                  {include file='catalog/_partials/product-cover-thumbnails.tpl'}
                {/block}
                <div class="scroll-box-arrows">
                  <i class="material-icons left">&#xE314;</i>
                  <i class="material-icons right">&#xE315;</i>
                </div>
              {/block}
            </div>
          {/block}
          </div>
          <div class="col-md-6 tb-product-page-content">
            {block name='page_header_container'}
              {block name='page_header'}
                <h1 class="h1" itemprop="name">{block name='page_title'}{$product.name}{/block}</h1>
              {/block}
            {/block}
            
            {* Start Product Comment *}
            {hook h='displayReviewProductList' product=$product}
            {* End Product Comment *}

            {block name='product_prices'}
              {include file='catalog/_partials/product-prices.tpl'}
            {/block}

            {* {if !empty($product.specific_prices.from) && !empty($product.specific_prices.to) && $product.specific_prices.from != '0000-00-00 00:00:00' && $product.specific_prices.to != '0000-00-00 00:00:00'}
              {include file='catalog/_partials/miniatures/product-timer.tpl' timer=$product.specific_prices.to}
            {/if} *}

            {block name='product_description_short'}
              <div id="product-description-short-{$product.id}" itemprop="description" class="tbproduct-page-decs">{$product.description_short nofilter}</div>
            {/block}

            {block name='product_availability'}
              {if $product.show_availability && $product.availability_message}
                <span id="product-availability">
                  {if $product.availability == 'available'}
                    <i class="material-icons rtl-no-flip product-available">&#xE5CA;</i>
                  {elseif $product.availability == 'last_remaining_items'}
                    <i class="material-icons product-last-items">&#xE002;</i>
                  {else}
                    <i class="material-icons product-unavailable">&#xE14B;</i>
                  {/if}
                  {$product.availability_message}
                </span>
              {/if}
            {/block}

            <div class="product-information tbproduct-special-desc">

              {if $product.is_customizable && count($product.customizations.fields)}
                {block name='product_customization'}
                  {include file="catalog/_partials/product-customization.tpl" customizations=$product.customizations}
                {/block}
              {/if}


              <div class="product-actions">
                {block name='product_buy'}
                  <form action="{$urls.pages.cart}" method="post" id="add-to-cart-or-refresh">
                    <input type="hidden" name="token" value="{$static_token}">
                    <input type="hidden" name="id_product" value="{$product.id}" id="product_page_product_id">
                    <input type="hidden" name="id_customization" value="{$product.id_customization}" id="product_customization_id">


                    {block name='product_variants'}
                      {include file='catalog/_partials/product-variants.tpl'}
                    {/block}

                    {block name='product_pack'}
                      {if $packItems}
                        <div class="product-pack">
                          <p class="h4">{l s='This pack contains' d='Shop.Theme.Catalog'}</p>
                          {foreach from=$packItems item="product_pack"}
                            {block name='product_miniature'}
                              {include file='catalog/_partials/miniatures/pack-product.tpl' product=$product_pack}
                            {/block}
                          {/foreach}
                      </div>
                      {/if}
                    {/block}

                    {block name='product_discounts'}
                      {include file='catalog/_partials/product-discounts.tpl'}
                    {/block}

                    {block name='product_add_to_cart'}
                      {include file='catalog/_partials/product-add-to-cart.tpl'}
                    {/block}

                    {block name='product_additional_info'}
                      {include file='catalog/_partials/product-additional-info.tpl'}
                    {/block}

                    {* Input to refresh product HTML removed, block kept for compatibility with themes *}
                    {block name='product_refresh'}{/block}
                  </form>
                {/block}
              </div>
            </div>
            {block name='hook_display_reassurance'}
              {hook h='displayReassurance'}
            {/block}
          </div>
        </div>
        {block name='product_tabs'}
          <div class="tabs tbproduct-description-tab">
            <ul class="nav nav-tabs" role="tablist">
              {if $product.description}
                <li class="nav-item">
                   <a class="nav-link{if $product.description} active{/if}" data-toggle="tab" href="#description" role="tab" aria-controls="description" {if $product.description} aria-selected="true"{/if}>{l s='Description' d='Shop.Theme.Catalog'}</a>
                </li>
              {/if}
              <li class="nav-item">
                <a class="nav-link{if !$product.description} active{/if}" data-toggle="tab" href="#product-details" role="tab" aria-controls="product-details" {if !$product.description} aria-selected="true"{/if}>{l s='Product Details' d='Shop.Theme.Catalog'}</a>
              </li>
              

              {if $product.attachments}
                <li class="nav-item">
                  <a class="nav-link" data-toggle="tab" href="#attachments" role="tab" aria-controls="attachments">{l s='Attachments' d='Shop.Theme.Catalog'}</a>
                </li>
              {/if}
              {foreach from=$product.extraContent item=extra key=extraKey}
                <li class="nav-item">
                  <a class="nav-link" data-toggle="tab" href="#extra-{$extraKey}" role="tab" aria-controls="extra-{$extraKey}">{$extra.title}</a>
                </li>
              {/foreach}

              {* start product comment tab hook *}
              {hook h='displayProductListReviewsTab'}
              {* End product comment tab hook *}
            </ul>

            <div class="tab-content" id="tab-content">
              <div class="tab-pane fade in {if $product.description} active{/if}" id="description" role="tabpanel">
                {block name='product_description'}
                  <div class="product-description">{$product.description nofilter}</div>
                {/block}
              </div>

              {block name='product_details'}
                {include file='catalog/_partials/product-details.tpl'}
              {/block}
             

              {block name='product_attachments'}
                {if $product.attachments}
                  <div class="tab-pane fade in" id="attachments" role="tabpanel">
                     <div class="product-attachments">
                       <p class="h5 text-uppercase">{l s='Download' d='Shop.Theme.Actions'}</p>
                       {foreach from=$product.attachments item=attachment}
                         <div class="attachment">
                           <h4><a href="{url entity='attachment' params=['id_attachment' => $attachment.id_attachment]}">{$attachment.name}</a></h4>
                           <p>{$attachment.description}</p
                           <a href="{url entity='attachment' params=['id_attachment' => $attachment.id_attachment]}">
                             {l s='Download' d='Shop.Theme.Actions'} ({$attachment.file_size_formatted})
                           </a>
                         </div>
                       {/foreach}
                     </div>
                   </div>
                 {/if}
               {/block}

              {foreach from=$product.extraContent item=extra key=extraKey}
                <div class="tab-pane fade in {$extra.attr.class}" id="extra-{$extraKey}" role="tabpanel" {foreach $extra.attr as $key => $val} {$key}="{$val}"{/foreach}>
                  {$extra.content nofilter}
                </div>
              {/foreach}

              {* start product comment tab content hook *}
              {hook h='displayProductListReviewsTabContent' product=$product}
              {* End product comment tab content hook *}
            </div>  
          </div>
        {/block}
      </div>

      {block name='product_accessories'}
        {if $accessories}
          <div class="tbcmslike-product container-fluid">
              <div class='tblike-product-wrapper-box container'>
                  <div class="tblike-product-all-box">
                      <div class="tblike-product-content">
                          <div class="tball-block-box-shadows">
                              <div class="tblike-main-title-wrapper">
                                  <div class='tbcmsmain-title-wrapper'>
                                      <div class="tbcms-main-title">
                                          <div class='tbmain-title'>
                                              <h2>{l s='You might also like' d='Shop.Theme.Catalog'}</h2>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                                  
                              <div class="tblike-product">
                                  <div class="products owl-theme owl-carousel tblike-product-wrapper tbproduct-wrapper-content-box">
                                      {foreach $accessories as $product}
                                          {include file="catalog/_partials/miniatures/product.tpl" product=$product tb_product_type="like_product"}
                                      {/foreach}
                                  </div>
                              </div>
                          </div>
                      </div>
                      
                      <div class='tblike-pagination-wrapper tb-pagination-wrapper'>
                          <div class="tbfeature-pagination">
                              <div class="tbcmslike-pagination">
                                  <div class="tbcmslike-next-pre-btn tbcms-next-pre-btn">
                                      <div class="tbcmslike-prev tbcmsprev-btn" data-parent="tbcmslike-product"><i class='material-icons'>&#xe317;</i></div>
                                      <div class="tbcmslike-next tbcmsnext-btn" data-parent="tbcmslike-product"><i class='material-icons'>&#xe317;</i></div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
        {/if}
      {/block}

      {block name='product_footer'}
        {hook h='displayFooterProduct' product=$product category=$category}
      {/block}

      {block name='product_images_modal'}
        {include file='catalog/_partials/product-images-modal.tpl'}
      {/block}

      {block name='page_footer_container'}
        <footer class="page-footer">
          {block name='page_footer'}
            <!-- Footer content -->
          {/block}
        </footer>
      {/block}
</div>

  {/block}
{/strip}
