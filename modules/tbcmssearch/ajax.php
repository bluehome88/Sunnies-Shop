<?php
/**
 * 2007-2019 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
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
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */

require_once(dirname(__FILE__).'/../../config/config.inc.php');
require_once(dirname(__FILE__).'/../../init.php');
require_once(dirname(__FILE__).'/tbcmssearch.php');

// Number of Product to show
$num_of_product = 4;

// Instance of module class for translations
$module = new TbcmsSearch();
$tmp = Tools::getToken(false);
$tmp_2 = Tools::getValue('token');

if (Configuration::get('PS_TOKEN_ENABLE') == 1 and
    strcmp($tmp, $tmp_2)) {
    exit($module->l('invalid token', 'sendwishlist'));
}

$context = Context::getContext();

$result = array();
$search_words = Tools::getValue('search_words');
$category_id = Tools::getValue('category_id');
$cat_id = trim($category_id);

$cookie = Context::getContext()->cookie;
$id_lang = $cookie->id_lang;
$result = Search::find($id_lang, $search_words, 1, $num_of_product);

$return_data = array();

if ($cat_id != "undefined" && $cat_id != "0") {
    foreach ($result['result'] as $product) {
        $all_cat = Product::getProductCategories($product['id_product']);
        if (in_array($cat_id, $all_cat)) {
            $return_data[$product['id_product']] = $product;
            $image = Image::getCover($product['id_product']);
            $img_type = ImageType::getFormattedName('small');
            $tmp = $context->link->getImageLink($product['link_rewrite'], $image['id_image'], $img_type);
            $return_data[$product['id_product']]['cover_image'] = $tmp;
        }
    }
} else {
    foreach ($result['result'] as $product) {
        $return_data[$product['id_product']] = $product;
        $return_data[$product['id_product']]['all_cat'] = Product::getProductCategories($product['id_product']);
        $image = Image::getCover($product['id_product']);
        $img_type = ImageType::getFormattedName('small');
        $tmp = $context->link->getImageLink($product['link_rewrite'], $image['id_image'], $img_type);
        $return_data[$product['id_product']]['cover_image'] = $tmp;
    }
}

$html = '';
// $html .= 'total: search product :- <pre>'.print_r($result['total']).'</pre>';
if (!empty($return_data)) {
    $html .= '<div class=\'tbcmssearch-dropdown\'>';

    $close = $module->l('Close', 'Modules.Search.ajax');
    $html .= '<div class=\'tbsearch-dropdown-close-wrapper\'>
        <div class=\'tbsearch-dropdown-close\'><i class=\'material-icons\'>&#xe5cd;</i></div>
    </div>';
   
    $text = $module->l('Search Result:', 'Modules.Search.ajax');
    $html .= '<div class=\'tbsearch-dropdown-total-wrapper\'>
        <div class=\'tbsearch-dropdown-total\'>'.$text.'('.$result['total'].')'.'</div>
    </div>';


    $html .= '<div class=\'tbsearch-all-dropdown-wrapper\'>';
    $i = 1;
    $show_product = 5;
    foreach ($return_data as $data) {
        if ($i<= $show_product) {
            $prod_img = $data['cover_image'];
            $prod_name = $data['name'];
            $prod_link = $data['link'];

            if (!empty($data['specific_prices'])) {
                $tmp = $data['price'];
                $new_price = Tools::displayPrice($tmp);
                $tmp = $data['price_without_reduction'];
                $old_price = Tools::displayPrice($tmp);
                if ($data['specific_prices']['reduction_type'] == 'percentage') {
                    $reduction = $data['specific_prices']['reduction'] * 100;
                    $prod_reduction = '-'.$reduction.'%';
                } else {
                    $tmp = $data['specific_prices']['reduction'];
                    $prod_reduction = Tools::displayPrice($tmp);
                }

                $prod_price = '<span class=\'price\'>'.$new_price.'</span>
                    <span class=\'regular-price\'>'.$old_price.'</span>
                    <span class=\'discount-percentage discount-product tbproduct-discount-price\'>'
                    .$prod_reduction.'</span>';
            } else {
                $tmp = $data['price'];
                $new_price = Tools::displayPrice($tmp);
                $prod_price = '<div class=\'price\'>'.$new_price.'</div>';
            }

            $html .= '
                <div class=\'tbsearch-dropdown-wrapper clearfix\'>
                    <a href=\''.$prod_link.'\'>
                        <div class=\'tbsearch-dropdown-img-block\'>
                            <img src=\''.$prod_img.'\' alt=\''.$prod_name.'\' />
                        </div>
                        <div class=\'tbsearch-dropdown-content-box\'>
                            <div class=\'tbsearch-dropdown-title\'>'.$prod_name.'</div>
                            <div class=\'product-price-and-shipping\'>'.$prod_price.'</div>
                        </div>
                    </a>
                </div>';
            $i++;
        }
    }
    $html .= '</div>';
   
    // $text = $module->l('Search Result:', 'Modules.Search.ajax');
    // $html .= '<div class=\'tbsearch-dropdown-total\'>'.$text.'('.$result['total'].')'.'</div>';
    
    $more_search = $module->l('More Result', 'Modules.Search.ajax');
    $html .= '<div class=\'tbsearch-more-search-wrapper\'>';
    $html .= '<div class=\'tbsearch-more-search\'>'.$more_search.'</div>';
    $html .= '</div>';


    $html .= '</div>';
}

if (!empty($html)) {
    print_r($html);
}
