<?php
/**
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
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

include_once _PS_MODULE_DIR_.'tbcmstabproducts/tbcmstabproducts.php';
include_once _PS_MODULE_DIR_.'tbcmstabproducts/classes/tbcmstabproducts_status.class.php';

class TbcmsNewProducts extends Module
{
    public $num_of_prod = 6;
    public function __construct()
    {
        $this->name = 'tbcmsnewproducts';
        $this->tab = 'front_office_features';
        $this->version = '2.1.9';
        $this->author = 'TemplateBeta';
        $this->need_instance = 0;

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('TemplateBeta - New Product');
        $this->description = $this->l('Its Show New Product in Front Side.');

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
        $this->module_key = '';

        $this->confirmUninstall = $this->l('Warning: all the data saved in your database will be deleted.'.
            ' Are you sure you want uninstall this module?');
    }


    public function install()
    {
        return parent::install()
            && $this->registerHook('displayLeftColumn')
            && $this->registerHook('displayRightColumn')
            && $this->registerHook('displayHome');
    }

    public function uninstall()
    {
        return parent::uninstall();
    }

    public function getArrMainTitle($main_heading, $main_heading_data)
    {
        if (!$main_heading['main_title'] || empty($main_heading_data['title'])) {
            $main_heading['main_title'] = false;
        }
        if (!$main_heading['main_sub_title'] || empty($main_heading_data['short_desc'])) {
            $main_heading['main_sub_title'] = false;
        }
        if (!$main_heading['main_description'] || empty($main_heading_data['desc'])) {
            $main_heading['main_description'] = false;
        }
        
        if (!$main_heading['main_image'] || empty($main_heading_data['image'])) {
            $main_heading['main_image'] = false;
        }

        $main_heading['main_image_side'] = $main_heading_data['image_side'];
        $main_heading['main_image_status'] = $main_heading_data['image_status'];

        if (!$main_heading['main_left_title'] || empty($main_heading_data['left_title'])) {
            $main_heading['main_left_title'] = false;
        }

        if (!$main_heading['main_right_title'] || empty($main_heading_data['right_title'])) {
            $main_heading['main_right_title'] = false;
        }

        if (!$main_heading['main_title'] &&
            !$main_heading['main_sub_title'] &&
            !$main_heading['main_description'] &&
            !$main_heading['main_image']) {
            $main_heading['main_status'] = false;
        }
        return $main_heading;
    }

    // if Front side show number of product then pass params otherwise keep it empty
    public function showFrontSideResult($num_of_prod = '')
    {
        $cookie = Context::getContext()->cookie;
        $id_lang = $cookie->id_lang;

        $tbcms_obj = new TbcmsTabProductsStatus();
        $all_prod_status = $tbcms_obj->fieldStatusInformation();
        $main_heading  = array();
        $main_heading['main_status'] = $all_prod_status['new_prod']['main_status'];
        $main_heading['main_title'] = $all_prod_status['new_prod']['home_title'];
        $main_heading['main_sub_title'] = $all_prod_status['new_prod']['home_sub_title'];
        $main_heading['main_description'] = $all_prod_status['new_prod']['home_description'];
        $main_heading['main_image'] = $all_prod_status['new_prod']['home_image'];
        $main_heading['main_image_side'] = $all_prod_status['new_prod']['home_image_side'];
        $main_heading['main_image_status'] = $all_prod_status['new_prod']['home_image_status'];
        $main_heading['main_left_title'] = $all_prod_status['new_prod']['left_title'];
        $main_heading['main_right_title'] = $all_prod_status['new_prod']['right_title'];

        if ($main_heading['main_status']) {
            $main_heading_data = array();

            $tmp = Configuration::get('TBCMSTABPRODUCTS_NEW_PROD_HOME_TITLE', $id_lang);
            $main_heading_data['title'] = $tmp;
            $tmp = Configuration::get('TBCMSTABPRODUCTS_NEW_PROD_HOME_SUB_TITLE', $id_lang);
            $main_heading_data['short_desc'] = $tmp;
            $tmp = Configuration::get('TBCMSTABPRODUCTS_NEW_PROD_HOME_DESC', $id_lang);
            $main_heading_data['desc'] = $tmp;
            $tmp = Configuration::get('TBCMSTABPRODUCTS_NEW_PROD_IMAGE', $id_lang);
            $main_heading_data['image'] = $tmp;
            $tmp = Configuration::get('TBCMSTABPRODUCTS_NEW_PROD_IMAGE_LINK', $id_lang);
            $main_heading_data['link'] = $tmp;
            $tmp = Configuration::get('TBCMSTABPRODUCTS_NEW_PROD_IMAGE_SIDE');
            $main_heading_data['image_side'] = $tmp;
            $tmp = Configuration::get('TBCMSTABPRODUCTS_NEW_PROD_IMAGE_STATUS');
            $main_heading_data['image_status'] = $tmp;
            $tmp = Configuration::get('TBCMSTABPRODUCTS_NEW_PROD_LEFT_TITLE', $id_lang);
            $main_heading_data['left_title'] = $tmp;
            $tmp = Configuration::get('TBCMSTABPRODUCTS_NEW_PROD_RIGHT_TITLE', $id_lang);
            $main_heading_data['right_title'] = $tmp;

            $main_heading = $this->getArrMainTitle($main_heading, $main_heading_data);
            $main_heading['data'] = $main_heading_data;
        }

        $disArrResult = array();

        $tb_obj_prod = new TbcmsTabProducts();
        $disArrResult['data'] = $tb_obj_prod->displayNewProducts($num_of_prod);
        $disArrResult['status'] = empty($disArrResult['data'])?false:true;
        $disArrResult['home_status'] = Configuration::get('TBCMSTABPRODUCTS_NEW_PROD_HOME');
        $disArrResult['left_status'] = Configuration::get('TBCMSTABPRODUCTS_NEW_PROD_LEFT');
        $disArrResult['right_status'] = Configuration::get('TBCMSTABPRODUCTS_NEW_PROD_RIGHT');
        $disArrResult['path'] = _MODULE_DIR_."tbcmstabproducts/views/img/";
        $disArrResult['id_lang'] = $id_lang;
        $link = Context::getContext()->link->getPageLink('new-products');
        $disArrResult['link'] = $link;

        $this->context->smarty->assign('main_heading', $main_heading);
        $this->context->smarty->assign('dis_arr_result', $disArrResult);

        return $disArrResult['status']?true:false;
    }

    public function hookdisplayHome()
    {
        if (!Cache::isStored('tbcmsnewproducts_display_home.tpl')) {
            $result = $this->showFrontSideResult();
            if ($result) {
                $output = $this->display(__FILE__, 'views/templates/front/display_home.tpl');
            } else {
                $output = '';
            }
            Cache::store('tbcmsnewproducts_display_home.tpl', $output);
        }

        return Cache::retrieve('tbcmsnewproducts_display_home.tpl');
    }

    public function hookdisplayLeftColumn()
    {
        if (!Cache::isStored('tbcmsnewproducts_display_left.tpl')) {
            $result = $this->showFrontSideResult($this->num_of_prod);
            if ($result) {
                $output = $this->display(__FILE__, 'views/templates/front/display_left.tpl');
            } else {
                $output = '';
            }
            Cache::store('tbcmsnewproducts_display_left.tpl', $output);
        }

        return Cache::retrieve('tbcmsnewproducts_display_left.tpl');
    }

    public function hookdisplayRightColumn()
    {
        if (!Cache::isStored('tbcmsnewproducts_display_right.tpl')) {
            $result = $this->showFrontSideResult($this->num_of_prod);
            if ($result) {
                $output = $this->display(__FILE__, 'views/templates/front/display_right.tpl');
            } else {
                $output = '';
            }
            Cache::store('tbcmsnewproducts_display_right.tpl', $output);
        }

        return Cache::retrieve('tbcmsnewproducts_display_right.tpl');
    }
}
