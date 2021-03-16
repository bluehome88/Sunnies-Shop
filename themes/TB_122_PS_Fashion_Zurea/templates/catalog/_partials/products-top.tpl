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
<div class="tbleft-penal-product-top clearfix">
    <div id="js-product-list-top" class="products-selection tbcenter-block-product-section tball-block-box-shadows clearfix">
        <div class="col-sm-6 col-md-4 col-lg-4 tb-left-search-totle-product">

            {$tb_page = ["category", "best-sales", "prices-drop", "new-products", "supplier", "manufacturer"]}
            {$left_class = false}

            {if isset($page.body_classes['layout-left-column']) || isset($page.body_classes['layout-both-columns'])}
                {$left_class = true}
            {/if}

            {$right_class = false}
            {if isset($page.body_classes['layout-right-column']) || isset($page.body_classes['layout-both-columns'])}
                {$right_class = true}
            {/if}

            {if in_array($page.page_name, $tb_page) && $left_class == true}
            <div class="tbcms-left-column-wrapper">
                <div class="tb-left-pannal-btn-wrapper" data-toggle="tbtooltip" data-placement="top" data-html="true" title="" data-original-title="left panel">
                    <i class='material-icons grid'>&#xe5c3;</i>
                    <span class="">{l s='View Left Column' d='Shop.Theme.Actions'}</span>
                </div>
            </div>
            {/if}


            {* Start Filter Button *}
            {if $page.page_name== 'category'}
            <div class='tb_search_filter_wrapper'> 
                <div class="tbleft-right-title-wrapper" data-toggle="tbtooltip" data-placement="top" data-html="true" title="" data-original-title="Filter">
                    <i class='material-icons'>&#xe429;</i>
                    {* <div class="tbleft-right-title facet-label">{l s='Filter By' d='Shop.Theme.Actions'}</div> *}
                </div>
            </div>
            {/if}
            {* End Filter Button *}

            <div class="tb-total-product">
                {if $listing.pagination.total_items > 1}
                    <p class="tb-total-product-number">
                        {l s='There are %product_count% products.' d='Shop.Theme.Catalog' sprintf=['%product_count%' => $listing.pagination.total_items]}
                    </p>
                {else if $listing.pagination.total_items > 0}
                    <p class="tb-total-product-number">
                        {l s='There is 1 product.' d='Shop.Theme.Catalog'}
                    </p>
                {/if}
            </div>
        </div>
        <div class="col-sm-6 col-md-4 col-lg-4 tb-grid-list-all-wrapper">
            <div class="tbcmsproduct-grid-list clearfix">
                <button class="tbproduct-view tbproduct-grid" data-toggle="tbtooltip" data-placement="top" data-html="true" title="" data-original-title="Grid">
                    <i class='material-icons grid'>&#xe5c3;</i>
                    <span class="tbgrid-wrapper">{l s='Grid' d='Shop.Theme.Actions'}</span>
                </button>
                <button class="tbproduct-view tbproduct-grid-2" data-toggle="tbtooltip" data-placement="top" data-html="true" title="" data-original-title="Grid 2">
                    <i class='material-icons grid-2'>&#xe5c3;</i>
                    <span class="tbgrid-2-wrapper">{l s='Grid-2' d='Shop.Theme.Actions'}</span>
                </button>
                <button class="tbproduct-view tbproduct-list" data-toggle="tbtooltip" data-placement="top" data-html="true" title="" data-original-title="List">
                    <i class='material-icons list'>&#xe241;</i>
                    <span class="tblist-wrapper">{l s='List' d='Shop.Theme.Actions'}</span>
                </button>
                <button class="tbproduct-view tbproduct-list-2" data-toggle="tbtooltip" data-placement="top" data-html="true" title="" data-original-title="List 2">
                    <i class='material-icons list-2'>&#xe241;</i>
                    <span class="tblist-wrapper">{l s='List-2' d='Shop.Theme.Actions'}</span>
                </button>
                <button class="tbproduct-view tbproduct-catelog" data-toggle="tbtooltip" data-placement="top" data-html="true" title="" data-original-title="Catelog">
                    <i class='material-icons catelog'>&#xe241;</i>
                    <span class="tblist-wrapper">{l s='Catelog' d='Shop.Theme.Actions'}</span>
                </button>
            </div>
        </div>
        <div class="{if in_array($page.page_name, $tb_page) && $right_class == true}col-sm-12 col-md-4 col-lg-4{else}col-sm-12 col-md-4 col-lg-4{/if} tball-page-shortby">
            <div class="row sort-by-row">
                {block name='sort_by'}
                    {include file='catalog/_partials/sort-orders.tpl' sort_orders=$listing.sort_orders}
                {/block}
                {* {if !empty($listing.rendered_facets)}
                <div class="col-sm-3 col-xs-4 hidden-md-up filter-button">
                    <button id="search_filter_toggler" class="btn btn-secondary">
                        {l s='Filter' d='Shop.Theme.Actions'}
                    </button>
                </div>
                {/if} *}
            </div>
        </div>

        
        {if in_array($page.page_name, $tb_page) && $right_class == true}
        <div class="tbcms-right-column-wrapper col-xs-12 col-md-1 col-lg-1">
            <div class="tb-right-pannal-btn-wrapper" data-toggle="tbtooltip" data-placement="top" data-html="true" title="" data-original-title="Right panel">
                <i class='material-icons grid'>&#xe5c3;</i>
                <span class="">{l s='View Right Column' d='Shop.Theme.Actions'}</span>
            </div>
        </div>
        {/if}

        {* <div class="col-sm-12 hidden-md-up text-sm-center showing tbmobile-view-grid-list">
            <div class="tbcmsproduct-grid-list">
                <div class="tbproduct-view tbproduct-grid">
                    <i class='material-icons grid'>&#xe5c3;</i>
                    <span class="tbgrid-wrapper">{l s='Grid' d='Shop.Theme.Actions'}</span>
                </div>
                <div class="tbproduct-view tbproduct-grid-2">
                    <i class='material-icons grid-2'>&#xe5c3;</i>
                    <span class="tbgrid-2-wrapper">{l s='Grid-2' d='Shop.Theme.Actions'}</span>
                </div>
                <div class="tbproduct-view tbproduct-list">
                    <i class='material-icons list'>&#xe241;</i>
                    <span class="tblist-wrapper">{l s='List' d='Shop.Theme.Actions'}</span>
                </div>
                <div class="tbproduct-view tbproduct-list-2">
                    <i class='material-icons list-2'>&#xe241;</i>
                    <span class="tblist-wrapper">{l s='List-2' d='Shop.Theme.Actions'}</span>
                </div>
                <div class="tbproduct-view tbproduct-catelog">
                    <i class='material-icons catelog'>&#xe241;</i>
                    <span class="tblist-wrapper">{l s='Catelog' d='Shop.Theme.Actions'}</span>
                </div>
            </div>
             <span class="tbmobile-view-showing-title">
                {l s='Showing %from%-%to% of %total% item(s)' d='Shop.Theme.Catalog' sprintf=[
                    '%from%' => $listing.pagination.items_shown_from ,
                    '%to%' => $listing.pagination.items_shown_to,
                    '%total%' => $listing.pagination.total_items
                ]}
            </span> 
        </div> *}
    </div>

    {* Start Faceted Search *}
    {hook h='displayFacetedSearchBlock'}
    {* End Faceted Search *}
</div>
{/strip}
