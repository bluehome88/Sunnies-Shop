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
{extends file='catalog/listing/product-list.tpl'}

{block name='product_list_header'}
		<header class="page-header tbpage-header-title-wrapper">
          <h1 class="tbpage-header-title">{$category.name}</h1>
        </header>
		<div class="block-category card card-block clearfix tb-category-block-wrapper">
			{if $category.image.large.url}
				<div class="tb-category-cover">
					<img src="{$category.image.large.url}" alt="{if !empty($category.image.legend)}{$category.image.legend}{else}{$category.name}{/if}">
				</div>
			{/if}
			<div class="tb-all-page-main-title-wrapper">
				<div class="tb-all-page-main-title">{$category.name}</div>
			</div>
			{if $category.description}
				<div id="category-description" class="text-muted">{$category.description nofilter}</div>
			{/if}
		</div>
		{* <div class="text-sm-center hidden-md-up">
			<h1 class="h1">{$category.name}</h1>
		</div> *}

		{$tb_child_category = Category::getChildren($category['id'], $language.id)}
		{if count($tb_child_category) > 0}
		<div class='tb-category-main-div clearfix'>
			<div class="tb-sub-category-title-wrapper">
				<div class="tb-sub-category-title">{l s='Subcategory' d='Shop.Theme.Catalog'}</div>
			</div>
			{$count_cat = 1}
			{foreach $tb_child_category as $category}
				{if $count_cat <= 5}
				<div class="tb-sub-category-wrapper col-xs-6 col-sm-3 col-md-2 col-lg-2 col-xl-1">
					<div class="tb-category-image">
						<a href="{$link->getCategoryLink($category.id_category, $category.link_rewrite)|escape:'html':'UTF-8'}" title="{$category.name|escape:'html':'UTF-8'}" class="img">              
							{$tmp = {url entity='categoryImage' id=$category.id_category name='category_default'}}
							{if !file_exists({$tmp})}
								<img class="replace-2x" src="{$tmp}" alt="{$category.name|escape:'html':'UTF-8'}"  />
							{/if}
						</a>
					</div>
					<div class="tbcategory-name">
						<a class="category-name" href="{$link->getCategoryLink($category.id_category, $category.link_rewrite)|escape:'html':'UTF-8'}">{$category.name|escape:'html':'UTF-8'}</a>
					</div>
				</div>
				{/if}
				{$count_cat = $count_cat + 1}
			{/foreach}
		</div>
		{/if}
{/block}
{/strip}
