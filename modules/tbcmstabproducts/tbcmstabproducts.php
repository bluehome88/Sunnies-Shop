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

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;
use PrestaShop\PrestaShop\Adapter\Category\CategoryProductSearchProvider;
use PrestaShop\PrestaShop\Adapter\BestSales\BestSalesProductSearchProvider;
use PrestaShop\PrestaShop\Adapter\Image\ImageRetriever;
use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;
use PrestaShop\PrestaShop\Core\Product\ProductListingPresenter;
use PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchContext;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchQuery;
use PrestaShop\PrestaShop\Core\Product\Search\SortOrder;

include_once('classes/tbcmstabproducts_image_upload.class.php');
include_once('classes/tbcmstabproducts_status.class.php');

class TbcmsTabProducts extends Module
{
    public function __construct()
    {
        $this->name = 'tbcmstabproducts';
        $this->tab = 'front_office_features';
        $this->version = '2.1.9';
        $this->author = 'TemplateBeta';
        $this->need_instance = 0;

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('TemplateBeta - Tab Product Settings');
        $this->description = $this->l('It is use of Tab Setting in TemplateBeta Theme');

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
        $this->module_key = '';

        $this->confirmUninstall = $this->l('Warning: all the data saved in your database will be deleted.'.
            ' Are you sure you want uninstall this module?');
    }


    public function install()
    {
        $this->installTab();
        $this->createVariable();
        Tools::clearSmartyCache();
        
        return parent::install() &&
            $this->registerHook('displayHeader') &&
            $this->registerHook('displayBackOfficeHeader') &&
            $this->registerHook('displayHome');
    }

    public function createVariable()
    {
        $languages = Language::getLanguages();
        $result = array();

        foreach ($languages as $lang) {
            $result['TBCMSTABPRODUCTS_NEW_PROD_TITLE'][$lang['id_lang']] = 'Latest';
            $result['TBCMSTABPRODUCTS_NEW_PROD_HOME_TITLE'][$lang['id_lang']] = 'Latest Products';
            $result['TBCMSTABPRODUCTS_NEW_PROD_HOME_SUB_TITLE'][$lang['id_lang']] = 'Our New Products';
            $result['TBCMSTABPRODUCTS_NEW_PROD_HOME_DESC'][$lang['id_lang']] = 'Our New Products';
            $result['TBCMSTABPRODUCTS_NEW_PROD_IMAGE'][$lang['id_lang']] = 'new_offer_img_1.jpg';
            $result['TBCMSTABPRODUCTS_NEW_PROD_IMAGE_LINK'][$lang['id_lang']] = '#';
            $result['TBCMSTABPRODUCTS_NEW_PROD_LEFT_TITLE'][$lang['id_lang']] = 'Latest Products';
            $result['TBCMSTABPRODUCTS_NEW_PROD_RIGHT_TITLE'][$lang['id_lang']] = 'Latest Products';

            $result['TBCMSTABPRODUCTS_SPECIAL_PROD_IMAGE'][$lang['id_lang']] = 'special_offer_img_1.jpg';
            $result['TBCMSTABPRODUCTS_SPECIAL_PROD_IMAGE_LINK'][$lang['id_lang']] = '#';
            $result['TBCMSTABPRODUCTS_SPECIAL_PROD_TITLE'][$lang['id_lang']] = 'Special';
            $result['TBCMSTABPRODUCTS_SPECIAL_PROD_HOME_TITLE'][$lang['id_lang']] = 'Special Trend Products';
            $result['TBCMSTABPRODUCTS_SPECIAL_PROD_HOME_SUB_TITLE'][$lang['id_lang']] = 'Our Special Products';
            $result['TBCMSTABPRODUCTS_SPECIAL_PROD_HOME_DESC'][$lang['id_lang']] = 'A Friendly Organic Store';
            $result['TBCMSTABPRODUCTS_SPECIAL_PROD_LEFT_TITLE'][$lang['id_lang']] = 'Special Trend Products';
            $result['TBCMSTABPRODUCTS_SPECIAL_PROD_RIGHT_TITLE'][$lang['id_lang']] = 'Special Trend Products';

            $result['TBCMSTABPRODUCTS_FEATURED_PROD_IMAGE'][$lang['id_lang']] = 'featured_offer_img_1.jpg';
            $result['TBCMSTABPRODUCTS_FEATURED_PROD_IMAGE_LINK'][$lang['id_lang']] = '#';
            $result['TBCMSTABPRODUCTS_FEATURED_PROD_TITLE'][$lang['id_lang']] = 'Featured';
            $result['TBCMSTABPRODUCTS_FEATURED_PROD_HOME_TITLE'][$lang['id_lang']] = 'Featured Products';
            $result['TBCMSTABPRODUCTS_FEATURED_PROD_HOME_SUB_TITLE'][$lang['id_lang']] = 'Our Products';
            $result['TBCMSTABPRODUCTS_FEATURED_PROD_HOME_DESC'][$lang['id_lang']] = 'Our Products';
            $result['TBCMSTABPRODUCTS_FEATURED_PROD_LEFT_TITLE'][$lang['id_lang']] = 'Featured Products';
            $result['TBCMSTABPRODUCTS_FEATURED_PROD_RIGHT_TITLE'][$lang['id_lang']] = 'Featured Products';

            $result['TBCMSTABPRODUCTS_BEST_SELLER_PROD_TITLE'][$lang['id_lang']] = 'Best Seller';
            $result['TBCMSTABPRODUCTS_BEST_SELLER_PROD_HOME_TITLE'][$lang['id_lang']] = 'Best Seller Products';
            $result['TBCMSTABPRODUCTS_BEST_SELLER_PROD_HOME_SUB_TITLE'][$lang['id_lang']] = 'Our Best Seller';
            $result['TBCMSTABPRODUCTS_BEST_SELLER_PROD_HOME_DESC'][$lang['id_lang']] = 'Our Best Seller';
            $result['TBCMSTABPRODUCTS_BEST_SELLER_PROD_IMAGE'][$lang['id_lang']] = 'best_seller_offer_img_1.jpg';
            $result['TBCMSTABPRODUCTS_BEST_SELLER_PROD_IMAGE_LINK'][$lang['id_lang']] = '#';
            $result['TBCMSTABPRODUCTS_BEST_SELLER_PROD_LEFT_TITLE'][$lang['id_lang']] = 'Best Seller Products';
            $result['TBCMSTABPRODUCTS_BEST_SELLER_PROD_RIGHT_TITLE'][$lang['id_lang']] = 'Best Seller Products';

            $result['TBCMSTABPRODUCTS_MAIN_TITLE'][$lang['id_lang']] = 'We Love Trend';
            $result['TBCMSTABPRODUCTS_MAIN_SUB_TITLE'][$lang['id_lang']] = 'Excepteur Sint occaecat';
            $result['TBCMSTABPRODUCTS_MAIN_DESCRIPTION'][$lang['id_lang']] = 'A Friendly Organic Store';
            $result['TBCMSTABPRODUCTS_MAIN_IMAGE'][$lang['id_lang']] = 'main_offer_img_1.jpg';
            $result['TBCMSTABPRODUCTS_MAIN_IMAGE_LINK'][$lang['id_lang']] = '#';
        }

        $tmp = $result['TBCMSTABPRODUCTS_FEATURED_PROD_IMAGE'];
        Configuration::updateValue('TBCMSTABPRODUCTS_FEATURED_PROD_IMAGE', $tmp);
        $tmp = $result['TBCMSTABPRODUCTS_FEATURED_PROD_IMAGE_LINK'];
        Configuration::updateValue('TBCMSTABPRODUCTS_FEATURED_PROD_IMAGE_LINK', $tmp);
        $tmp = $result['TBCMSTABPRODUCTS_FEATURED_PROD_TITLE'];
        Configuration::updateValue('TBCMSTABPRODUCTS_FEATURED_PROD_TITLE', $tmp);
        $tmp = $result['TBCMSTABPRODUCTS_FEATURED_PROD_HOME_TITLE'];
        Configuration::updateValue('TBCMSTABPRODUCTS_FEATURED_PROD_HOME_TITLE', $tmp);
        $tmp = $result['TBCMSTABPRODUCTS_FEATURED_PROD_HOME_SUB_TITLE'];
        Configuration::updateValue('TBCMSTABPRODUCTS_FEATURED_PROD_HOME_SUB_TITLE', $tmp);
        $tmp = $result['TBCMSTABPRODUCTS_FEATURED_PROD_HOME_DESC'];
        Configuration::updateValue('TBCMSTABPRODUCTS_FEATURED_PROD_HOME_DESC', $tmp);

        $tmp = $result['TBCMSTABPRODUCTS_FEATURED_PROD_LEFT_TITLE'];
        Configuration::updateValue('TBCMSTABPRODUCTS_FEATURED_PROD_LEFT_TITLE', $tmp);
        $tmp = $result['TBCMSTABPRODUCTS_FEATURED_PROD_RIGHT_TITLE'];
        Configuration::updateValue('TBCMSTABPRODUCTS_FEATURED_PROD_RIGHT_TITLE', $tmp);
        
        Configuration::updateValue('TBCMSTABPRODUCTS_FEATURED_PROD_NBR', 12);
        Configuration::updateValue('TBCMSTABPRODUCTS_FEATURED_PROD_CAT', 2);
        Configuration::updateValue('TBCMSTABPRODUCTS_FEATURED_PROD_RAND', 1);
        Configuration::updateValue('TBCMSTABPRODUCTS_FEATURED_PROD_TAB', 1);
        Configuration::updateValue('TBCMSTABPRODUCTS_FEATURED_PROD_HOME', 0);
        Configuration::updateValue('TBCMSTABPRODUCTS_FEATURED_PROD_LEFT', 0);
        Configuration::updateValue('TBCMSTABPRODUCTS_FEATURED_PROD_RIGHT', 1);
        Configuration::updateValue('TBCMSTABPRODUCTS_FEATURED_PROD_IMAGE_SIDE', 'left');
        Configuration::updateValue('TBCMSTABPRODUCTS_FEATURED_PROD_IMAGE_STATUS', 0);

        $tmp = $result['TBCMSTABPRODUCTS_NEW_PROD_TITLE'];
        Configuration::updateValue('TBCMSTABPRODUCTS_NEW_PROD_TITLE', $tmp);
        $tmp = $result['TBCMSTABPRODUCTS_NEW_PROD_HOME_TITLE'];
        Configuration::updateValue('TBCMSTABPRODUCTS_NEW_PROD_HOME_TITLE', $tmp);
        $tmp = $result['TBCMSTABPRODUCTS_NEW_PROD_HOME_SUB_TITLE'];
        Configuration::updateValue('TBCMSTABPRODUCTS_NEW_PROD_HOME_SUB_TITLE', $tmp);
        $tmp = $result['TBCMSTABPRODUCTS_NEW_PROD_HOME_DESC'];
        Configuration::updateValue('TBCMSTABPRODUCTS_NEW_PROD_HOME_DESC', $tmp);
        $tmp = $result['TBCMSTABPRODUCTS_NEW_PROD_LEFT_TITLE'];
        Configuration::updateValue('TBCMSTABPRODUCTS_NEW_PROD_LEFT_TITLE', $tmp);
        $tmp = $result['TBCMSTABPRODUCTS_NEW_PROD_RIGHT_TITLE'];
        Configuration::updateValue('TBCMSTABPRODUCTS_NEW_PROD_RIGHT_TITLE', $tmp);
        $tmp = $result['TBCMSTABPRODUCTS_NEW_PROD_IMAGE'];
        Configuration::updateValue('TBCMSTABPRODUCTS_NEW_PROD_IMAGE', $tmp);
        $tmp = $result['TBCMSTABPRODUCTS_NEW_PROD_IMAGE_LINK'];
        Configuration::updateValue('TBCMSTABPRODUCTS_NEW_PROD_IMAGE_LINK', $tmp);
        Configuration::updateValue('TBCMSTABPRODUCTS_NEW_PROD_NBR', 12);
        Configuration::updateValue('TBCMSTABPRODUCTS_NEW_PROD_TAB', 1);
        Configuration::updateValue('TBCMSTABPRODUCTS_NEW_PROD_HOME', 0);
        Configuration::updateValue('TBCMSTABPRODUCTS_NEW_PROD_LEFT', 1);
        Configuration::updateValue('TBCMSTABPRODUCTS_NEW_PROD_RIGHT', 1);
        Configuration::updateValue('TBCMSTABPRODUCTS_NEW_PROD_IMAGE_SIDE', 'left');
        Configuration::updateValue('TBCMSTABPRODUCTS_NEW_PROD_IMAGE_STATUS', 0);

        $tmp = $result['TBCMSTABPRODUCTS_BEST_SELLER_PROD_TITLE'];
        Configuration::updateValue('TBCMSTABPRODUCTS_BEST_SELLER_PROD_TITLE', $tmp);
        $tmp = $result['TBCMSTABPRODUCTS_BEST_SELLER_PROD_HOME_TITLE'];
        Configuration::updateValue('TBCMSTABPRODUCTS_BEST_SELLER_PROD_HOME_TITLE', $tmp);
        $tmp = $result['TBCMSTABPRODUCTS_BEST_SELLER_PROD_HOME_SUB_TITLE'];
        Configuration::updateValue('TBCMSTABPRODUCTS_BEST_SELLER_PROD_HOME_SUB_TITLE', $tmp);
        $tmp = $result['TBCMSTABPRODUCTS_BEST_SELLER_PROD_HOME_DESC'];
        Configuration::updateValue('TBCMSTABPRODUCTS_BEST_SELLER_PROD_HOME_DESC', $tmp);
        $tmp = $result['TBCMSTABPRODUCTS_BEST_SELLER_PROD_LEFT_TITLE'];
        Configuration::updateValue('TBCMSTABPRODUCTS_BEST_SELLER_PROD_LEFT_TITLE', $tmp);
        $tmp = $result['TBCMSTABPRODUCTS_BEST_SELLER_PROD_RIGHT_TITLE'];
        Configuration::updateValue('TBCMSTABPRODUCTS_BEST_SELLER_PROD_RIGHT_TITLE', $tmp);
        $tmp = $result['TBCMSTABPRODUCTS_BEST_SELLER_PROD_IMAGE'];
        Configuration::updateValue('TBCMSTABPRODUCTS_BEST_SELLER_PROD_IMAGE', $tmp);
        $tmp = $result['TBCMSTABPRODUCTS_BEST_SELLER_PROD_IMAGE_LINK'];
        Configuration::updateValue('TBCMSTABPRODUCTS_BEST_SELLER_PROD_IMAGE_LINK', $tmp);
        Configuration::updateValue('TBCMSTABPRODUCTS_BEST_SELLER_PROD_NBR', 12);
        Configuration::updateValue('TBCMSTABPRODUCTS_BEST_SELLER_PROD_TAB', 1);
        Configuration::updateValue('TBCMSTABPRODUCTS_BEST_SELLER_PROD_HOME', 0);
        Configuration::updateValue('TBCMSTABPRODUCTS_BEST_SELLER_PROD_LEFT', 1);
        Configuration::updateValue('TBCMSTABPRODUCTS_BEST_SELLER_PROD_RIGHT', 1);
        Configuration::updateValue('TBCMSTABPRODUCTS_BEST_SELLER_PROD_IMAGE_SIDE', 'left');
        Configuration::updateValue('TBCMSTABPRODUCTS_BEST_SELLER_PROD_IMAGE_STATUS', 0);

        $tmp = $result['TBCMSTABPRODUCTS_SPECIAL_PROD_IMAGE'];
        Configuration::updateValue('TBCMSTABPRODUCTS_SPECIAL_PROD_IMAGE', $tmp);
        $tmp = $result['TBCMSTABPRODUCTS_SPECIAL_PROD_IMAGE_LINK'];
        Configuration::updateValue('TBCMSTABPRODUCTS_SPECIAL_PROD_IMAGE_LINK', $tmp);
        $tmp = $result['TBCMSTABPRODUCTS_SPECIAL_PROD_TITLE'];
        Configuration::updateValue('TBCMSTABPRODUCTS_SPECIAL_PROD_TITLE', $tmp);
        $tmp = $result['TBCMSTABPRODUCTS_SPECIAL_PROD_HOME_TITLE'];
        Configuration::updateValue('TBCMSTABPRODUCTS_SPECIAL_PROD_HOME_TITLE', $tmp);
        $tmp = $result['TBCMSTABPRODUCTS_SPECIAL_PROD_HOME_SUB_TITLE'];
        Configuration::updateValue('TBCMSTABPRODUCTS_SPECIAL_PROD_HOME_SUB_TITLE', $tmp);
        $tmp = $result['TBCMSTABPRODUCTS_SPECIAL_PROD_HOME_DESC'];
        Configuration::updateValue('TBCMSTABPRODUCTS_SPECIAL_PROD_HOME_DESC', $tmp);
        $tmp = $result['TBCMSTABPRODUCTS_SPECIAL_PROD_LEFT_TITLE'];
        Configuration::updateValue('TBCMSTABPRODUCTS_SPECIAL_PROD_LEFT_TITLE', $tmp);
        $tmp = $result['TBCMSTABPRODUCTS_SPECIAL_PROD_RIGHT_TITLE'];
        Configuration::updateValue('TBCMSTABPRODUCTS_SPECIAL_PROD_RIGHT_TITLE', $tmp);
        Configuration::updateValue('TBCMSTABPRODUCTS_SPECIAL_PROD_NBR', 12);
        Configuration::updateValue('TBCMSTABPRODUCTS_SPECIAL_PROD_TAB', 0);
        Configuration::updateValue('TBCMSTABPRODUCTS_SPECIAL_PROD_HOME', 1);
        Configuration::updateValue('TBCMSTABPRODUCTS_SPECIAL_PROD_LEFT', 0);
        Configuration::updateValue('TBCMSTABPRODUCTS_SPECIAL_PROD_RIGHT', 1);
        Configuration::updateValue('TBCMSTABPRODUCTS_SPECIAL_PROD_IMAGE_SIDE', 'left');
        Configuration::updateValue('TBCMSTABPRODUCTS_SPECIAL_PROD_IMAGE_STATUS', 0);
        
        $tmp = $result['TBCMSTABPRODUCTS_MAIN_TITLE'];
        Configuration::updateValue('TBCMSTABPRODUCTS_MAIN_TITLE', $tmp);
        $tmp = $result['TBCMSTABPRODUCTS_MAIN_SUB_TITLE'];
        Configuration::updateValue('TBCMSTABPRODUCTS_MAIN_SUB_TITLE', $tmp);
        $tmp = $result['TBCMSTABPRODUCTS_MAIN_DESCRIPTION'];
        Configuration::updateValue('TBCMSTABPRODUCTS_MAIN_DESCRIPTION', $tmp);
        $tmp = $result['TBCMSTABPRODUCTS_MAIN_IMAGE'];
        Configuration::updateValue('TBCMSTABPRODUCTS_MAIN_IMAGE', $tmp);
        $tmp = $result['TBCMSTABPRODUCTS_MAIN_IMAGE_LINK'];
        Configuration::updateValue('TBCMSTABPRODUCTS_MAIN_IMAGE_LINK', $tmp);
        Configuration::updateValue('TBCMSTABPRODUCTS_MAIN_IMAGE_SIDE', 'left');
        Configuration::updateValue('TBCMSTABPRODUCTS_MAIN_IMAGE_STATUS', 0);
    }

    public function installTab()
    {
        $response = true;

        // First check for parent tab
        $parentTabID = Tab::getIdFromClassName('AdminTemplateBeta');

        if ($parentTabID) {
            $parentTab = new Tab($parentTabID);
        } else {
            $parentTab = new Tab();
            $parentTab->active = 1;
            $parentTab->name = array();
            $parentTab->class_name = "AdminTemplateBeta";
            foreach (Language::getLanguages() as $lang) {
                $parentTab->name[$lang['id_lang']] = "TemplateBeta Extension";
            }
            $parentTab->id_parent = 0;
            $parentTab->module = $this->name;
            $response &= $parentTab->add();
        }
        
        // Check for parent tab2
        $parentTab_2ID = Tab::getIdFromClassName('AdminTemplateBetaModules');
        if ($parentTab_2ID) {
            $parentTab_2 = new Tab($parentTab_2ID);
        } else {
            $parentTab_2 = new Tab();
            $parentTab_2->active = 1;
            $parentTab_2->name = array();
            $parentTab_2->class_name = "AdminTemplateBetaModules";
            foreach (Language::getLanguages() as $lang) {
                $parentTab_2->name[$lang['id_lang']] = "TemplateBeta Configure";
            }
            $parentTab_2->id_parent = $parentTab->id;
            $parentTab_2->module = $this->name;
            $response &= $parentTab_2->add();
        }
        // Created tab
        $tab = new Tab();
        $tab->active = 1;
        $tab->class_name = 'Admin'.$this->name;
        $tab->name = array();
        foreach (Language::getLanguages() as $lang) {
            $tab->name[$lang['id_lang']] = "Tab Products";
        }
        $tab->id_parent = $parentTab_2->id;
        $tab->module = $this->name;
        $response &= $tab->add();

        return $response;
    }
  
    public function uninstall()
    {
        $this->uninstallTab();
        $this->deleteVariable();
        Tools::clearSmartyCache();
        return parent::uninstall();
    }

    public function deleteVariable()
    {
        Configuration::deleteByName('TBCMSTABPRODUCTS_FEATURED_PROD_IMAGE');
        Configuration::deleteByName('TBCMSTABPRODUCTS_FEATURED_PROD_IMAGE_LINK');
        Configuration::deleteByName('TBCMSTABPRODUCTS_FEATURED_PROD_TITLE');
        Configuration::deleteByName('TBCMSTABPRODUCTS_FEATURED_PROD_HOME_TITLE');
        Configuration::deleteByName('TBCMSTABPRODUCTS_FEATURED_PROD_HOME_SUB_TITLE');
        Configuration::deleteByName('TBCMSTABPRODUCTS_FEATURED_PROD_HOME_DESC');
        Configuration::deleteByName('TBCMSTABPRODUCTS_FEATURED_PROD_LEFT_TITLE');
        Configuration::deleteByName('TBCMSTABPRODUCTS_FEATURED_PROD_RIGHT_TITLE');
        Configuration::deleteByName('TBCMSTABPRODUCTS_FEATURED_PROD_NBR');
        Configuration::deleteByName('TBCMSTABPRODUCTS_FEATURED_PROD_CAT');
        Configuration::deleteByName('TBCMSTABPRODUCTS_FEATURED_PROD_RAND');
        Configuration::deleteByName('TBCMSTABPRODUCTS_FEATURED_PROD_TAB');
        Configuration::deleteByName('TBCMSTABPRODUCTS_FEATURED_PROD_IMAGE_SIDE');
        Configuration::deleteByName('TBCMSTABPRODUCTS_FEATURED_PROD_IMAGE_STATUS');
        Configuration::deleteByName('TBCMSTABPRODUCTS_FEATURED_PROD_HOME');
        Configuration::deleteByName('TBCMSTABPRODUCTS_FEATURED_PROD_LEFT');
        Configuration::deleteByName('TBCMSTABPRODUCTS_FEATURED_PROD_RIGHT');

        Configuration::deleteByName('TBCMSTABPRODUCTS_NEW_PROD_TITLE');
        Configuration::deleteByName('TBCMSTABPRODUCTS_NEW_PROD_HOME_TITLE');
        Configuration::deleteByName('TBCMSTABPRODUCTS_NEW_PROD_HOME_SUB_TITLE');
        Configuration::deleteByName('TBCMSTABPRODUCTS_NEW_PROD_HOME_DESC');
        Configuration::deleteByName('TBCMSTABPRODUCTS_NEW_PROD_LEFT_TITLE');
        Configuration::deleteByName('TBCMSTABPRODUCTS_NEW_PROD_RIGHT_TITLE');
        Configuration::deleteByName('TBCMSTABPRODUCTS_NEW_PROD_IMAGE');
        Configuration::deleteByName('TBCMSTABPRODUCTS_NEW_PROD_IMAGE_LINK');
        Configuration::deleteByName('TBCMSTABPRODUCTS_NEW_PROD_IMAGE_SIDE');
        Configuration::deleteByName('TBCMSTABPRODUCTS_NEW_PROD_IMAGE_STATUS');
        Configuration::deleteByName('TBCMSTABPRODUCTS_NEW_PROD_NBR');
        Configuration::deleteByName('TBCMSTABPRODUCTS_NEW_PROD_TAB');
        Configuration::deleteByName('TBCMSTABPRODUCTS_NEW_PROD_HOME');
        Configuration::deleteByName('TBCMSTABPRODUCTS_NEW_PROD_LEFT');
        Configuration::deleteByName('TBCMSTABPRODUCTS_NEW_PROD_RIGHT');

        Configuration::deleteByName('TBCMSTABPRODUCTS_BEST_SELLER_PROD_TITLE');
        Configuration::deleteByName('TBCMSTABPRODUCTS_BEST_SELLER_PROD_HOME_TITLE');
        Configuration::deleteByName('TBCMSTABPRODUCTS_BEST_SELLER_PROD_HOME_SUB_TITLE');
        Configuration::deleteByName('TBCMSTABPRODUCTS_BEST_SELLER_PROD_HOME_DESC');
        Configuration::deleteByName('TBCMSTABPRODUCTS_BEST_SELLER_PROD_LEFT_TITLE');
        Configuration::deleteByName('TBCMSTABPRODUCTS_BEST_SELLER_PROD_RIGHT_TITLE');
        Configuration::deleteByName('TBCMSTABPRODUCTS_BEST_SELLER_PROD_IMAGE');
        Configuration::deleteByName('TBCMSTABPRODUCTS_BEST_SELLER_PROD_IMAGE_LINK');
        Configuration::deleteByName('TBCMSTABPRODUCTS_BEST_SELLER_PROD_IMAGE_SIDE');
        Configuration::deleteByName('TBCMSTABPRODUCTS_BEST_SELLER_PROD_IMAGE_STATUS');
        Configuration::deleteByName('TBCMSTABPRODUCTS_BEST_SELLER_PROD_NBR');
        Configuration::deleteByName('TBCMSTABPRODUCTS_BEST_SELLER_PROD_TAB');
        Configuration::deleteByName('TBCMSTABPRODUCTS_BEST_SELLER_PROD_HOME');
        Configuration::deleteByName('TBCMSTABPRODUCTS_BEST_SELLER_PROD_LEFT');
        Configuration::deleteByName('TBCMSTABPRODUCTS_BEST_SELLER_PROD_RIGHT');

        Configuration::deleteByName('TBCMSTABPRODUCTS_SPECIAL_PROD_IMAGE');
        Configuration::deleteByName('TBCMSTABPRODUCTS_SPECIAL_PROD_IMAGE_LINK');
        Configuration::deleteByName('TBCMSTABPRODUCTS_SPECIAL_PROD_IMAGE_SIDE');
        Configuration::deleteByName('TBCMSTABPRODUCTS_SPECIAL_PROD_IMAGE_STATUS');
        Configuration::deleteByName('TBCMSTABPRODUCTS_SPECIAL_PROD_TITLE');
        Configuration::deleteByName('TBCMSTABPRODUCTS_SPECIAL_PROD_HOME_TITLE');
        Configuration::deleteByName('TBCMSTABPRODUCTS_SPECIAL_PROD_HOME_SUB_TITLE');
        Configuration::deleteByName('TBCMSTABPRODUCTS_SPECIAL_PROD_HOME_DESC');
        Configuration::deleteByName('TBCMSTABPRODUCTS_SPECIAL_PROD_LEFT_TITLE');
        Configuration::deleteByName('TBCMSTABPRODUCTS_SPECIAL_PROD_RIGHT_TITLE');
        Configuration::deleteByName('TBCMSTABPRODUCTS_SPECIAL_PROD_NBR');
        Configuration::deleteByName('TBCMSTABPRODUCTS_SPECIAL_PROD_TAB');
        Configuration::deleteByName('TBCMSTABPRODUCTS_SPECIAL_PROD_HOME');
        Configuration::deleteByName('TBCMSTABPRODUCTS_SPECIAL_PROD_LEFT');
        Configuration::deleteByName('TBCMSTABPRODUCTS_SPECIAL_PROD_RIGHT');

        Configuration::deleteByName('TBCMSTABPRODUCTS_MAIN_TITLE');
        Configuration::deleteByName('TBCMSTABPRODUCTS_MAIN_SUB_TITLE');
        Configuration::deleteByName('TBCMSTABPRODUCTS_MAIN_DESCRIPTION');
        Configuration::deleteByName('TBCMSTABPRODUCTS_MAIN_IMAGE');
        Configuration::deleteByName('TBCMSTABPRODUCTS_MAIN_IMAGE_LINK');
        Configuration::deleteByName('TBCMSTABPRODUCTS_MAIN_IMAGE_SIDE');
        Configuration::deleteByName('TBCMSTABPRODUCTS_MAIN_IMAGE_STATUS');
    }

    public function uninstallTab()
    {
        $id_tab = Tab::getIdFromClassName('Admin'.$this->name);
        $tab = new Tab($id_tab);
        $tab->delete();
        return true;
    }

    public function getContent()
    {
        $this->context->smarty->assign('tab_number', '#fieldset_0');
        $message = $this->postProcess();

        $output = $message.'<div class="tbcmsadmintab-product">'
            .$this->display(__FILE__, "views/templates/admin/index.tpl")
            .$this->renderForm()
            .'</div>';

        return $output;
    }

    public function postProcess()
    {
        $message = '';
        $result = array();
        if (Tools::getIsset('submitTbcmsCustomFormCustomSetting')) {
            $languages = Language::getLanguages();
            foreach ($languages as $lang) {
                $new_file = '';
                if (isset($_FILES['TBCMSTABPRODUCTS_MAIN_IMAGE_'.$lang['id_lang']])) {
                    $new_file = $_FILES['TBCMSTABPRODUCTS_MAIN_IMAGE_'.$lang['id_lang']];
                }
                $old_file = Configuration::get('TBCMSTABPRODUCTS_MAIN_IMAGE', $lang['id_lang']);
                if (!empty($new_file['name'])) {
                    $this->tbcmsobj = new TbcmsTabProductsImageUpload();
                    $return = $this->tbcmsobj->imageUploading($new_file, $old_file);
                    if ($return['success']) {
                        $result['TBCMSTABPRODUCTS_MAIN_IMAGE'][$lang['id_lang']] = $return['name'];
                    } else {
                        $message .= $return['error'];
                        $result['TBCMSTABPRODUCTS_MAIN_IMAGE'][$lang['id_lang']] = $old_file;
                    }
                } else {
                    $result['TBCMSTABPRODUCTS_MAIN_IMAGE'][$lang['id_lang']] = $old_file;
                }

                $tmp = Tools::getValue('TBCMSTABPRODUCTS_MAIN_IMAGE_LINK_'.$lang['id_lang']);
                $result['TBCMSTABPRODUCTS_MAIN_IMAGE_LINK'][$lang['id_lang']] = $tmp;

                $tmp = Tools::getValue('TBCMSTABPRODUCTS_MAIN_TITLE_'.$lang['id_lang']);
                $result['TBCMSTABPRODUCTS_MAIN_TITLE'][$lang['id_lang']] = $tmp;

                $tmp = Tools::getValue('TBCMSTABPRODUCTS_MAIN_SUB_TITLE_'.$lang['id_lang']);
                $result['TBCMSTABPRODUCTS_MAIN_SUB_TITLE'][$lang['id_lang']] = $tmp;

                $tmp = Tools::getValue('TBCMSTABPRODUCTS_MAIN_DESCRIPTION_'.$lang['id_lang']);
                $result['TBCMSTABPRODUCTS_MAIN_DESCRIPTION'][$lang['id_lang']] = $tmp;
            }

            $tmp = $result['TBCMSTABPRODUCTS_MAIN_TITLE'];
            Configuration::updateValue('TBCMSTABPRODUCTS_MAIN_TITLE', $tmp);
            $tmp = $result['TBCMSTABPRODUCTS_MAIN_DESCRIPTION'];
            Configuration::updateValue('TBCMSTABPRODUCTS_MAIN_DESCRIPTION', $tmp);
            $tmp = $result['TBCMSTABPRODUCTS_MAIN_SUB_TITLE'];
            Configuration::updateValue('TBCMSTABPRODUCTS_MAIN_SUB_TITLE', $tmp);
            $tmp = $result['TBCMSTABPRODUCTS_MAIN_IMAGE'];
            Configuration::updateValue('TBCMSTABPRODUCTS_MAIN_IMAGE', $tmp);
            $tmp = $result['TBCMSTABPRODUCTS_MAIN_IMAGE_LINK'];
            Configuration::updateValue('TBCMSTABPRODUCTS_MAIN_IMAGE_LINK', $tmp);
            $tmp = Tools::getValue('TBCMSTABPRODUCTS_MAIN_IMAGE_SIDE');
            Configuration::updateValue('TBCMSTABPRODUCTS_MAIN_IMAGE_SIDE', $tmp);
            $tmp = (int)Tools::getValue('TBCMSTABPRODUCTS_MAIN_IMAGE_STATUS');
            Configuration::updateValue('TBCMSTABPRODUCTS_MAIN_IMAGE_STATUS', $tmp);

            $this->clearCustomSmartyCache('tbcmstabproducts_display_index.tpl');

            $this->context->smarty->assign('tab_number', '#fieldset_0');
            $message .= $this->displayConfirmation($this->l('Record Save Custom Product Setting Successfully'));
        }

        if (Tools::getIsset('submitTbcmsTabFeaturedProdSettings')) {
            $languages = Language::getLanguages();
            foreach ($languages as $lang) {
                $new_file = '';
                if (isset($_FILES['TBCMSTABPRODUCTS_FEATURED_PROD_IMAGE_'.$lang['id_lang']])) {
                    $new_file = $_FILES['TBCMSTABPRODUCTS_FEATURED_PROD_IMAGE_'.$lang['id_lang']];
                }
                $old_file = Configuration::get('TBCMSTABPRODUCTS_FEATURED_PROD_IMAGE', $lang['id_lang']);
                if (!empty($new_file['name'])) {
                    $this->tbcmsobj = new TbcmsTabProductsImageUpload();
                    $return = $this->tbcmsobj->imageUploading($new_file, $old_file);
                    if ($return['success']) {
                        $result['TBCMSTABPRODUCTS_FEATURED_PROD_IMAGE'][$lang['id_lang']] = $return['name'];
                    } else {
                        $message .= $return['error'];
                        $result['TBCMSTABPRODUCTS_FEATURED_PROD_IMAGE'][$lang['id_lang']] = $old_file;
                    }
                } else {
                    $result['TBCMSTABPRODUCTS_FEATURED_PROD_IMAGE'][$lang['id_lang']] = $old_file;
                }

                $tmp = pSQL(Tools::getValue('TBCMSTABPRODUCTS_FEATURED_PROD_IMAGE_LINK_'.$lang['id_lang']));
                $result['TBCMSTABPRODUCTS_FEATURED_PROD_IMAGE_LINK'][$lang['id_lang']] = $tmp;
                $tmp = pSQL(Tools::getValue('TBCMSTABPRODUCTS_FEATURED_PROD_TITLE_'.$lang['id_lang']));
                $result['TBCMSTABPRODUCTS_FEATURED_PROD_TITLE'][$lang['id_lang']] = $tmp;
                $tmp = pSQL(Tools::getValue('TBCMSTABPRODUCTS_FEATURED_PROD_HOME_TITLE_'.$lang['id_lang']));
                $result['TBCMSTABPRODUCTS_FEATURED_PROD_HOME_TITLE'][$lang['id_lang']] = $tmp;
                $tmp = pSQL(Tools::getValue('TBCMSTABPRODUCTS_FEATURED_PROD_HOME_SUB_TITLE_'.$lang['id_lang']));
                $result['TBCMSTABPRODUCTS_FEATURED_PROD_HOME_SUB_TITLE'][$lang['id_lang']] = $tmp;
                $tmp = pSQL(Tools::getValue('TBCMSTABPRODUCTS_FEATURED_PROD_HOME_DESC_'.$lang['id_lang']));
                $result['TBCMSTABPRODUCTS_FEATURED_PROD_HOME_DESC'][$lang['id_lang']] = $tmp;
                $tmp = pSQL(Tools::getValue('TBCMSTABPRODUCTS_FEATURED_PROD_LEFT_TITLE_'.$lang['id_lang']));
                $result['TBCMSTABPRODUCTS_FEATURED_PROD_LEFT_TITLE'][$lang['id_lang']] = $tmp;
                $tmp = pSQL(Tools::getValue('TBCMSTABPRODUCTS_FEATURED_PROD_RIGHT_TITLE_'.$lang['id_lang']));
                $result['TBCMSTABPRODUCTS_FEATURED_PROD_RIGHT_TITLE'][$lang['id_lang']] = $tmp;
            }

            $tmp = $result['TBCMSTABPRODUCTS_FEATURED_PROD_IMAGE'];
            Configuration::updateValue('TBCMSTABPRODUCTS_FEATURED_PROD_IMAGE', $tmp);
            $tmp = $result['TBCMSTABPRODUCTS_FEATURED_PROD_IMAGE_LINK'];
            Configuration::updateValue('TBCMSTABPRODUCTS_FEATURED_PROD_IMAGE_LINK', $tmp);
            $tmp = $result['TBCMSTABPRODUCTS_FEATURED_PROD_TITLE'];
            Configuration::updateValue('TBCMSTABPRODUCTS_FEATURED_PROD_TITLE', $tmp);
            $tmp = $result['TBCMSTABPRODUCTS_FEATURED_PROD_HOME_TITLE'];
            Configuration::updateValue('TBCMSTABPRODUCTS_FEATURED_PROD_HOME_TITLE', $tmp);
            $tmp = $result['TBCMSTABPRODUCTS_FEATURED_PROD_HOME_SUB_TITLE'];
            Configuration::updateValue('TBCMSTABPRODUCTS_FEATURED_PROD_HOME_SUB_TITLE', $tmp);
            $tmp = $result['TBCMSTABPRODUCTS_FEATURED_PROD_HOME_DESC'];
            Configuration::updateValue('TBCMSTABPRODUCTS_FEATURED_PROD_HOME_DESC', $tmp);
            $tmp = $result['TBCMSTABPRODUCTS_FEATURED_PROD_LEFT_TITLE'];
            Configuration::updateValue('TBCMSTABPRODUCTS_FEATURED_PROD_LEFT_TITLE', $tmp);
            $tmp = $result['TBCMSTABPRODUCTS_FEATURED_PROD_RIGHT_TITLE'];
            Configuration::updateValue('TBCMSTABPRODUCTS_FEATURED_PROD_RIGHT_TITLE', $tmp);
            $tmp = (int)Tools::getValue('TBCMSTABPRODUCTS_FEATURED_PROD_NBR');
            Configuration::updateValue('TBCMSTABPRODUCTS_FEATURED_PROD_NBR', $tmp);
            $tmp = (int)Tools::getValue('TBCMSTABPRODUCTS_FEATURED_PROD_CAT');
            Configuration::updateValue('TBCMSTABPRODUCTS_FEATURED_PROD_CAT', $tmp);
            $tmp = (int)Tools::getValue('TBCMSTABPRODUCTS_FEATURED_PROD_RAND');
            Configuration::updateValue('TBCMSTABPRODUCTS_FEATURED_PROD_RAND', $tmp);
            $tmp = (int)Tools::getValue('TBCMSTABPRODUCTS_FEATURED_PROD_TAB');
            Configuration::updateValue('TBCMSTABPRODUCTS_FEATURED_PROD_TAB', $tmp);
            $tmp = Tools::getValue('TBCMSTABPRODUCTS_FEATURED_PROD_IMAGE_SIDE');
            Configuration::updateValue('TBCMSTABPRODUCTS_FEATURED_PROD_IMAGE_SIDE', $tmp);
            $tmp = (int)Tools::getValue('TBCMSTABPRODUCTS_FEATURED_PROD_IMAGE_STATUS');
            Configuration::updateValue('TBCMSTABPRODUCTS_FEATURED_PROD_IMAGE_STATUS', $tmp);
            $tmp = (int)Tools::getValue('TBCMSTABPRODUCTS_FEATURED_PROD_HOME');
            Configuration::updateValue('TBCMSTABPRODUCTS_FEATURED_PROD_HOME', $tmp);
            $tmp = (int)Tools::getValue('TBCMSTABPRODUCTS_FEATURED_PROD_LEFT');
            Configuration::updateValue('TBCMSTABPRODUCTS_FEATURED_PROD_LEFT', $tmp);
            $tmp = (int)Tools::getValue('TBCMSTABPRODUCTS_FEATURED_PROD_RIGHT');
            Configuration::updateValue('TBCMSTABPRODUCTS_FEATURED_PROD_RIGHT', $tmp);

            
            $this->clearCustomSmartyCache('tbcmsfeaturedproducts_display_home.tpl');
            $this->clearCustomSmartyCache('tbcmsfeaturedproducts_display_feature_products.tpl');
            $this->clearCustomSmartyCache('tbcmsfeaturedproducts_display_left.tpl');
            $this->clearCustomSmartyCache('tbcmsfeaturedproducts_display_right.tpl');

            $this->context->smarty->assign('tab_number', '#fieldset_1_1');
            $message .= $this->displayConfirmation($this->l('Record Save Featured Product Setting Successfully'));
        }

        if (Tools::getIsset('submitTbcmsTabNewProdSettings')) {
            $languages = Language::getLanguages();
            foreach ($languages as $lang) {
                $new_file = '';
                if (isset($_FILES['TBCMSTABPRODUCTS_NEW_PROD_IMAGE_'.$lang['id_lang']])) {
                    $new_file = $_FILES['TBCMSTABPRODUCTS_NEW_PROD_IMAGE_'.$lang['id_lang']];
                }
                $old_file = Configuration::get('TBCMSTABPRODUCTS_NEW_PROD_IMAGE', $lang['id_lang']);
                if (!empty($new_file['name'])) {
                    $this->tbcmsobj = new TbcmsTabProductsImageUpload();
                    $return = $this->tbcmsobj->imageUploading($new_file, $old_file);
                    if ($return['success']) {
                        $result['TBCMSTABPRODUCTS_NEW_PROD_IMAGE'][$lang['id_lang']] = $return['name'];
                    } else {
                        $message .= $return['error'];
                        $result['TBCMSTABPRODUCTS_NEW_PROD_IMAGE'][$lang['id_lang']] = $old_file;
                    }
                } else {
                        $result['TBCMSTABPRODUCTS_NEW_PROD_IMAGE'][$lang['id_lang']] = $old_file;
                }

                $tmp = pSQL(Tools::getValue('TBCMSTABPRODUCTS_NEW_PROD_IMAGE_LINK_'.$lang['id_lang']));
                $result['TBCMSTABPRODUCTS_NEW_PROD_IMAGE_LINK'][$lang['id_lang']] = $tmp;
                $tmp = pSQL(Tools::getValue('TBCMSTABPRODUCTS_NEW_PROD_TITLE_'.$lang['id_lang']));
                $result['TBCMSTABPRODUCTS_NEW_PROD_TITLE'][$lang['id_lang']] = $tmp;
                $tmp = pSQL(Tools::getValue('TBCMSTABPRODUCTS_NEW_PROD_HOME_TITLE_'.$lang['id_lang']));
                $result['TBCMSTABPRODUCTS_NEW_PROD_HOME_TITLE'][$lang['id_lang']] = $tmp;
                $tmp = pSQL(Tools::getValue('TBCMSTABPRODUCTS_NEW_PROD_HOME_SUB_TITLE_'.$lang['id_lang']));
                $result['TBCMSTABPRODUCTS_NEW_PROD_HOME_SUB_TITLE'][$lang['id_lang']] = $tmp;
                $tmp = pSQL(Tools::getValue('TBCMSTABPRODUCTS_NEW_PROD_HOME_DESC_'.$lang['id_lang']));
                $result['TBCMSTABPRODUCTS_NEW_PROD_HOME_DESC'][$lang['id_lang']] = $tmp;
                $tmp = pSQL(Tools::getValue('TBCMSTABPRODUCTS_NEW_PROD_LEFT_TITLE_'.$lang['id_lang']));
                $result['TBCMSTABPRODUCTS_NEW_PROD_LEFT_TITLE'][$lang['id_lang']] = $tmp;
                $tmp = pSQL(Tools::getValue('TBCMSTABPRODUCTS_NEW_PROD_RIGHT_TITLE_'.$lang['id_lang']));
                $result['TBCMSTABPRODUCTS_NEW_PROD_RIGHT_TITLE'][$lang['id_lang']] = $tmp;
            }

            $tmp = $result['TBCMSTABPRODUCTS_NEW_PROD_TITLE'];
            Configuration::updateValue('TBCMSTABPRODUCTS_NEW_PROD_TITLE', $tmp);
            $tmp = $result['TBCMSTABPRODUCTS_NEW_PROD_HOME_TITLE'];
            Configuration::updateValue('TBCMSTABPRODUCTS_NEW_PROD_HOME_TITLE', $tmp);
            $tmp = $result['TBCMSTABPRODUCTS_NEW_PROD_HOME_SUB_TITLE'];
            Configuration::updateValue('TBCMSTABPRODUCTS_NEW_PROD_HOME_SUB_TITLE', $tmp);
            $tmp = $result['TBCMSTABPRODUCTS_NEW_PROD_HOME_DESC'];
            Configuration::updateValue('TBCMSTABPRODUCTS_NEW_PROD_HOME_DESC', $tmp);
            $tmp = $result['TBCMSTABPRODUCTS_NEW_PROD_LEFT_TITLE'];
            Configuration::updateValue('TBCMSTABPRODUCTS_NEW_PROD_LEFT_TITLE', $tmp);
            $tmp = $result['TBCMSTABPRODUCTS_NEW_PROD_RIGHT_TITLE'];
            Configuration::updateValue('TBCMSTABPRODUCTS_NEW_PROD_RIGHT_TITLE', $tmp);
            $tmp = $result['TBCMSTABPRODUCTS_NEW_PROD_IMAGE'];
            Configuration::updateValue('TBCMSTABPRODUCTS_NEW_PROD_IMAGE', $tmp);
            $tmp = $result['TBCMSTABPRODUCTS_NEW_PROD_IMAGE_LINK'];
            Configuration::updateValue('TBCMSTABPRODUCTS_NEW_PROD_IMAGE_LINK', $tmp);
            $tmp = Tools::getValue('TBCMSTABPRODUCTS_NEW_PROD_IMAGE_SIDE');
            Configuration::updateValue('TBCMSTABPRODUCTS_NEW_PROD_IMAGE_SIDE', $tmp);
            $tmp = (int)Tools::getValue('TBCMSTABPRODUCTS_NEW_PROD_IMAGE_STATUS');
            Configuration::updateValue('TBCMSTABPRODUCTS_NEW_PROD_IMAGE_STATUS', $tmp);
            $tmp = (int)Tools::getValue('TBCMSTABPRODUCTS_NEW_PROD_NBR');
            Configuration::updateValue('TBCMSTABPRODUCTS_NEW_PROD_NBR', $tmp);
            $tmp = (int)Tools::getValue('TBCMSTABPRODUCTS_NEW_PROD_TAB');
            Configuration::updateValue('TBCMSTABPRODUCTS_NEW_PROD_TAB', $tmp);
            $tmp = (int)Tools::getValue('TBCMSTABPRODUCTS_NEW_PROD_HOME');
            Configuration::updateValue('TBCMSTABPRODUCTS_NEW_PROD_HOME', $tmp);
            $tmp = (int)Tools::getValue('TBCMSTABPRODUCTS_NEW_PROD_LEFT');
            Configuration::updateValue('TBCMSTABPRODUCTS_NEW_PROD_LEFT', $tmp);
            $tmp = (int)Tools::getValue('TBCMSTABPRODUCTS_NEW_PROD_RIGHT');
            Configuration::updateValue('TBCMSTABPRODUCTS_NEW_PROD_RIGHT', $tmp);
            Configuration::updateValue('PS_NB_DAYS_NEW_PRODUCT', (int)Tools::getValue('PS_NB_DAYS_NEW_PRODUCT'));

            $this->clearCustomSmartyCache('tbcmsnewproducts_display_home.tpl');
            $this->clearCustomSmartyCache('tbcmsnewproducts_display_left.tpl');
            $this->clearCustomSmartyCache('tbcmsnewproducts_display_right.tpl');

            $this->context->smarty->assign('tab_number', '#fieldset_2_2');
            $message .= $this->displayConfirmation($this->l('Record Save New Product Setting Successfully'));
        }

        if (Tools::getIsset('submitTbcmsBestSellerProdTabSettings')) {
            $languages = Language::getLanguages();
            foreach ($languages as $lang) {
                $new_file = '';
                if (isset($_FILES['TBCMSTABPRODUCTS_BEST_SELLER_PROD_IMAGE_'.$lang['id_lang']])) {
                    $new_file = $_FILES['TBCMSTABPRODUCTS_BEST_SELLER_PROD_IMAGE_'.$lang['id_lang']];
                }
                $old_file = Configuration::get('TBCMSTABPRODUCTS_BEST_SELLER_PROD_IMAGE', $lang['id_lang']);
                if (!empty($new_file['name'])) {
                    $this->tbcmsobj = new TbcmsTabProductsImageUpload();
                    $return = $this->tbcmsobj->imageUploading($new_file, $old_file);
                    if ($return['success']) {
                        $result['TBCMSTABPRODUCTS_BEST_SELLER_PROD_IMAGE'][$lang['id_lang']] = $return['name'];
                    } else {
                        $message .= $return['error'];
                        $result['TBCMSTABPRODUCTS_BEST_SELLER_PROD_IMAGE'][$lang['id_lang']] = $old_file;
                    }
                } else {
                        $result['TBCMSTABPRODUCTS_BEST_SELLER_PROD_IMAGE'][$lang['id_lang']] = $old_file;
                }
                $tmp = pSQL(Tools::getValue('TBCMSTABPRODUCTS_BEST_SELLER_PROD_IMAGE_LINK_'.$lang['id_lang']));
                $result['TBCMSTABPRODUCTS_BEST_SELLER_PROD_IMAGE_LINK'][$lang['id_lang']] = $tmp;
                $tmp = pSQL(Tools::getValue('TBCMSTABPRODUCTS_BEST_SELLER_PROD_TITLE_'.$lang['id_lang']));
                $result['TBCMSTABPRODUCTS_BEST_SELLER_PROD_TITLE'][$lang['id_lang']] = $tmp;
                $tmp = pSQL(Tools::getValue('TBCMSTABPRODUCTS_BEST_SELLER_PROD_HOME_TITLE_'.$lang['id_lang']));
                $result['TBCMSTABPRODUCTS_BEST_SELLER_PROD_HOME_TITLE'][$lang['id_lang']] = $tmp;
                $tmp = pSQL(Tools::getValue('TBCMSTABPRODUCTS_BEST_SELLER_PROD_HOME_SUB_TITLE_'.$lang['id_lang']));
                $result['TBCMSTABPRODUCTS_BEST_SELLER_PROD_HOME_SUB_TITLE'][$lang['id_lang']] = $tmp;
                $tmp = pSQL(Tools::getValue('TBCMSTABPRODUCTS_BEST_SELLER_PROD_HOME_DESC_'.$lang['id_lang']));
                $result['TBCMSTABPRODUCTS_BEST_SELLER_PROD_HOME_DESC'][$lang['id_lang']] = $tmp;
                $tmp = pSQL(Tools::getValue('TBCMSTABPRODUCTS_BEST_SELLER_PROD_LEFT_TITLE_'.$lang['id_lang']));
                $result['TBCMSTABPRODUCTS_BEST_SELLER_PROD_LEFT_TITLE'][$lang['id_lang']] = $tmp;
                $tmp = pSQL(Tools::getValue('TBCMSTABPRODUCTS_BEST_SELLER_PROD_RIGHT_TITLE_'.$lang['id_lang']));
                $result['TBCMSTABPRODUCTS_BEST_SELLER_PROD_RIGHT_TITLE'][$lang['id_lang']] = $tmp;
            }

            $tmp = $result['TBCMSTABPRODUCTS_BEST_SELLER_PROD_TITLE'];
            Configuration::updateValue('TBCMSTABPRODUCTS_BEST_SELLER_PROD_TITLE', $tmp);
            $tmp = $result['TBCMSTABPRODUCTS_BEST_SELLER_PROD_HOME_TITLE'];
            Configuration::updateValue('TBCMSTABPRODUCTS_BEST_SELLER_PROD_HOME_TITLE', $tmp);
            $tmp = $result['TBCMSTABPRODUCTS_BEST_SELLER_PROD_HOME_SUB_TITLE'];
            Configuration::updateValue('TBCMSTABPRODUCTS_BEST_SELLER_PROD_HOME_SUB_TITLE', $tmp);
            $tmp = $result['TBCMSTABPRODUCTS_BEST_SELLER_PROD_HOME_DESC'];
            Configuration::updateValue('TBCMSTABPRODUCTS_BEST_SELLER_PROD_HOME_DESC', $tmp);
            $tmp = $result['TBCMSTABPRODUCTS_BEST_SELLER_PROD_LEFT_TITLE'];
            Configuration::updateValue('TBCMSTABPRODUCTS_BEST_SELLER_PROD_LEFT_TITLE', $tmp);
            $tmp = $result['TBCMSTABPRODUCTS_BEST_SELLER_PROD_RIGHT_TITLE'];
            Configuration::updateValue('TBCMSTABPRODUCTS_BEST_SELLER_PROD_RIGHT_TITLE', $tmp);
            $tmp = $result['TBCMSTABPRODUCTS_BEST_SELLER_PROD_IMAGE'];
            Configuration::updateValue('TBCMSTABPRODUCTS_BEST_SELLER_PROD_IMAGE', $tmp);
            $tmp = $result['TBCMSTABPRODUCTS_BEST_SELLER_PROD_IMAGE_LINK'];
            Configuration::updateValue('TBCMSTABPRODUCTS_BEST_SELLER_PROD_IMAGE_LINK', $tmp);
            $tmp = Tools::getValue('TBCMSTABPRODUCTS_BEST_SELLER_PROD_IMAGE_SIDE');
            Configuration::updateValue('TBCMSTABPRODUCTS_BEST_SELLER_PROD_IMAGE_SIDE', $tmp);
            $tmp = (int)Tools::getValue('TBCMSTABPRODUCTS_BEST_SELLER_PROD_IMAGE_STATUS');
            Configuration::updateValue('TBCMSTABPRODUCTS_BEST_SELLER_PROD_IMAGE_STATUS', $tmp);
            $tmp = (int)Tools::getValue('TBCMSTABPRODUCTS_BEST_SELLER_PROD_NBR');
            Configuration::updateValue('TBCMSTABPRODUCTS_BEST_SELLER_PROD_NBR', $tmp);
            $tmp = (int)Tools::getValue('TBCMSTABPRODUCTS_BEST_SELLER_PROD_TAB');
            Configuration::updateValue('TBCMSTABPRODUCTS_BEST_SELLER_PROD_TAB', $tmp);
            $tmp = (int)Tools::getValue('TBCMSTABPRODUCTS_BEST_SELLER_PROD_HOME');
            Configuration::updateValue('TBCMSTABPRODUCTS_BEST_SELLER_PROD_HOME', $tmp);
            $tmp = (int)Tools::getValue('TBCMSTABPRODUCTS_BEST_SELLER_PROD_LEFT');
            Configuration::updateValue('TBCMSTABPRODUCTS_BEST_SELLER_PROD_LEFT', $tmp);
            $tmp = (int)Tools::getValue('TBCMSTABPRODUCTS_BEST_SELLER_PROD_RIGHT');
            Configuration::updateValue('TBCMSTABPRODUCTS_BEST_SELLER_PROD_RIGHT', $tmp);

            $this->clearCustomSmartyCache('tbcmsbestsellerproducts_display_home.tpl');
            $this->clearCustomSmartyCache('tbcmsbestsellerproducts_display_left.tpl');
            $this->clearCustomSmartyCache('tbcmsbestsellerproducts_display_right.tpl');


            $this->context->smarty->assign('tab_number', '#fieldset_3_3');
            $message .= $this->displayConfirmation($this->l('Record Save Best Seller Product Setting Successfully'));
        }


        if (Tools::getIsset('submitTbcmsTabSpecialProdSettings')) {
            $languages = Language::getLanguages();
            foreach ($languages as $lang) {
                $new_file = '';
                if (isset($_FILES['TBCMSTABPRODUCTS_SPECIAL_PROD_IMAGE_'.$lang['id_lang']])) {
                    $new_file = $_FILES['TBCMSTABPRODUCTS_SPECIAL_PROD_IMAGE_'.$lang['id_lang']];
                }
                $old_file = Configuration::get('TBCMSTABPRODUCTS_SPECIAL_PROD_IMAGE', $lang['id_lang']);
                if (!empty($new_file['name'])) {
                    $obj_image = new TbcmsTabProductsImageUpload();
                    $return = $obj_image->imageUploading($new_file, $old_file);
                    if ($return['success']) {
                        $result['TBCMSTABPRODUCTS_SPECIAL_PROD_IMAGE'][$lang['id_lang']] = $return['name'];
                    } else {
                        $message .= $return['error'];
                        $result['TBCMSTABPRODUCTS_SPECIAL_PROD_IMAGE'][$lang['id_lang']] = $old_file;
                    }
                } else {
                    $result['TBCMSTABPRODUCTS_SPECIAL_PROD_IMAGE'][$lang['id_lang']] = $old_file;
                }

                $tmp = pSQL(Tools::getValue('TBCMSTABPRODUCTS_SPECIAL_PROD_IMAGE_LINK_'.$lang['id_lang']));
                $result['TBCMSTABPRODUCTS_SPECIAL_PROD_IMAGE_LINK'][$lang['id_lang']] = $tmp;
                $tmp = pSQL(Tools::getValue('TBCMSTABPRODUCTS_SPECIAL_PROD_TITLE_'.$lang['id_lang']));
                $result['TBCMSTABPRODUCTS_SPECIAL_PROD_TITLE'][$lang['id_lang']] = $tmp;
                $tmp = pSQL(Tools::getValue('TBCMSTABPRODUCTS_SPECIAL_PROD_HOME_TITLE_'.$lang['id_lang']));
                $result['TBCMSTABPRODUCTS_SPECIAL_PROD_HOME_TITLE'][$lang['id_lang']] = $tmp;
                $tmp = pSQL(Tools::getValue('TBCMSTABPRODUCTS_SPECIAL_PROD_HOME_SUB_TITLE_'.$lang['id_lang']));
                $result['TBCMSTABPRODUCTS_SPECIAL_PROD_HOME_SUB_TITLE'][$lang['id_lang']] = $tmp;
                $tmp = pSQL(Tools::getValue('TBCMSTABPRODUCTS_SPECIAL_PROD_HOME_DESC_'.$lang['id_lang']));
                $result['TBCMSTABPRODUCTS_SPECIAL_PROD_HOME_DESC'][$lang['id_lang']] = $tmp;
                $tmp = pSQL(Tools::getValue('TBCMSTABPRODUCTS_SPECIAL_PROD_LEFT_TITLE_'.$lang['id_lang']));
                $result['TBCMSTABPRODUCTS_SPECIAL_PROD_LEFT_TITLE'][$lang['id_lang']] = $tmp;
                $tmp = pSQL(Tools::getValue('TBCMSTABPRODUCTS_SPECIAL_PROD_RIGHT_TITLE_'.$lang['id_lang']));
                $result['TBCMSTABPRODUCTS_SPECIAL_PROD_RIGHT_TITLE'][$lang['id_lang']] = $tmp;
            }

            $tmp = $result['TBCMSTABPRODUCTS_SPECIAL_PROD_TITLE'];
            Configuration::updateValue('TBCMSTABPRODUCTS_SPECIAL_PROD_TITLE', $tmp);
            $tmp = $result['TBCMSTABPRODUCTS_SPECIAL_PROD_HOME_TITLE'];
            Configuration::updateValue('TBCMSTABPRODUCTS_SPECIAL_PROD_HOME_TITLE', $tmp);
            $tmp = $result['TBCMSTABPRODUCTS_SPECIAL_PROD_HOME_SUB_TITLE'];
            Configuration::updateValue('TBCMSTABPRODUCTS_SPECIAL_PROD_HOME_SUB_TITLE', $tmp);
            $tmp = $result['TBCMSTABPRODUCTS_SPECIAL_PROD_HOME_DESC'];
            Configuration::updateValue('TBCMSTABPRODUCTS_SPECIAL_PROD_HOME_DESC', $tmp);
            $tmp = $result['TBCMSTABPRODUCTS_SPECIAL_PROD_LEFT_TITLE'];
            Configuration::updateValue('TBCMSTABPRODUCTS_SPECIAL_PROD_LEFT_TITLE', $tmp);
            $tmp = $result['TBCMSTABPRODUCTS_SPECIAL_PROD_RIGHT_TITLE'];
            Configuration::updateValue('TBCMSTABPRODUCTS_SPECIAL_PROD_RIGHT_TITLE', $tmp);
            $tmp = $result['TBCMSTABPRODUCTS_SPECIAL_PROD_IMAGE'];
            Configuration::updateValue('TBCMSTABPRODUCTS_SPECIAL_PROD_IMAGE', $tmp);
            $tmp = $result['TBCMSTABPRODUCTS_SPECIAL_PROD_IMAGE_LINK'];
            Configuration::updateValue('TBCMSTABPRODUCTS_SPECIAL_PROD_IMAGE_LINK', $tmp);
            $tmp = (int)Tools::getValue('TBCMSTABPRODUCTS_SPECIAL_PROD_IMAGE_STATUS');
            Configuration::updateValue('TBCMSTABPRODUCTS_SPECIAL_PROD_IMAGE_STATUS', $tmp);
            $tmp = Tools::getValue('TBCMSTABPRODUCTS_SPECIAL_PROD_IMAGE_SIDE');
            Configuration::updateValue('TBCMSTABPRODUCTS_SPECIAL_PROD_IMAGE_SIDE', $tmp);
            $tmp = (int)Tools::getValue('TBCMSTABPRODUCTS_SPECIAL_PROD_NBR');
            Configuration::updateValue('TBCMSTABPRODUCTS_SPECIAL_PROD_NBR', $tmp);
            $tmp = (int)Tools::getValue('TBCMSTABPRODUCTS_SPECIAL_PROD_TAB');
            Configuration::updateValue('TBCMSTABPRODUCTS_SPECIAL_PROD_TAB', $tmp);
            $tmp = (int)Tools::getValue('TBCMSTABPRODUCTS_SPECIAL_PROD_HOME');
            Configuration::updateValue('TBCMSTABPRODUCTS_SPECIAL_PROD_HOME', $tmp);
            $tmp = (int)Tools::getValue('TBCMSTABPRODUCTS_SPECIAL_PROD_LEFT');
            Configuration::updateValue('TBCMSTABPRODUCTS_SPECIAL_PROD_LEFT', $tmp);
            $tmp = (int)Tools::getValue('TBCMSTABPRODUCTS_SPECIAL_PROD_RIGHT');
            Configuration::updateValue('TBCMSTABPRODUCTS_SPECIAL_PROD_RIGHT', $tmp);

            $this->clearCustomSmartyCache('tbcmsspecialproducts_display_home.tpl');
            $this->clearCustomSmartyCache('tbcmsspecialproducts_display_left.tpl');
            $this->clearCustomSmartyCache('tbcmsspecialproducts_display_right.tpl');

            $this->context->smarty->assign('tab_number', '#fieldset_4_4');
            $message .= $this->displayConfirmation($this->l('Record Save Special Product Setting Successfully'));
        }

        return $message;
    }

    public function clearCustomSmartyCache($cache_id)
    {
        if (Cache::isStored($cache_id)) {
            Cache::clean($cache_id);
        }
    }

    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        $form = array();

        $tbcms_obj = new TbcmsTabProductsStatus();
        $show_fields = $tbcms_obj->fieldStatusInformation();
        if ($show_fields['main_status']) {
            $form[] = $this->tbcmsCustomFormCustomSetting();
        }

        
        $form[] = $this->tbcmsCustomFormFeatureProduct();
        $form[] = $this->tbcmsCustomFormNewProduct();
        $form[] = $this->tbcmsCustomFormBestSellerProduct();
        $form[] = $this->tbcmsCustomFormSpecialProduct();

        return $helper->generateForm($form);
    }

    protected function tbcmsCustomFormCustomSetting()
    {
        $tbcms_obj = new TbcmsTabProductsStatus();
        $show_fields = $tbcms_obj->fieldStatusInformation();
        $input = array();

        if ($show_fields['main_title']) {
            $input[] = array(
                        'col' => 6,
                        'type' => 'text',
                        'name' => 'TBCMSTABPRODUCTS_MAIN_TITLE',
                        'label' => $this->l('Main Title'),
                        'lang' => true,
                    );
        }

        if ($show_fields['main_sub_title']) {
            $input[] = array(
                        'col' => 6,
                        'type' => 'text',
                        'name' => 'TBCMSTABPRODUCTS_MAIN_SUB_TITLE',
                        'label' => $this->l('Sub Title'),
                        'lang' => true,
                    );
        }

        if ($show_fields['main_description']) {
            $input[] = array(
                        'col' => 6,
                        'type' => 'text',
                        'name' => 'TBCMSTABPRODUCTS_MAIN_DESCRIPTION',
                        'label' => $this->l('Description'),
                        'lang' => true,
                    );
        }

        if ($show_fields['main_image']) {
            $input[] = array(
                        'col' => 6,
                        'type' => 'main_file',
                        'name' => 'TBCMSTABPRODUCTS_MAIN_IMAGE',
                        'label' => $this->l('Main Banner'),
                        'lang' => true,
                    );
        }

        if ($show_fields['main_image_side']) {
            $input[] = array(
                        'type' => 'select',
                        'label' => $this->l('Display Banner Side'),
                        'name' => 'TBCMSTABPRODUCTS_MAIN_IMAGE_SIDE',
                        'options' => array(
                            'query' => array(
                                array(
                                    'id_option' => 'left',
                                    'name' => 'Left Side'
                                ),
                                array(
                                    'id_option' => 'right',
                                    'name' => 'Right Side'
                                ),
                            ),
                            'id' => 'id_option',
                            'name' => 'name'
                        )
                    );
        }

        if ($show_fields['main_image_status']) {
            $input[] = array(
                        'type' => 'switch',
                        'label' => $this->l('Display Banner'),
                        'name' => 'TBCMSTABPRODUCTS_MAIN_IMAGE_STATUS',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Yes')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('No')
                            )
                        )
                    );
        }

        if ($show_fields['main_image_link']) {
            $input[] = array(
                        'col' => 6,
                        'type' => 'text',
                        'label' => $this->l('Display Banner Link'),
                        'name' => 'TBCMSTABPRODUCTS_MAIN_IMAGE_LINK',
                        'lang' => true,
                    );
        }


        return array(
            'form' => array(
                'legend' => array(
                'title' => $this->l('Custom Tab Setting'),
                'icon' => 'icon-cogs',
                ),
                'input' => $input,
                'submit' => array(
                    'title' => $this->l('Save'),
                    'name' => 'submitTbcmsCustomFormCustomSetting',
                ),
            ),
        );
    }

    protected function tbcmsCustomFormFeatureProduct()
    {
        $tbcms_obj = new TbcmsTabProductsStatus();
        $show_fields = $tbcms_obj->fieldStatusInformation();
        $input = array();

        if ($show_fields['featured_prod']['tab_title']) {
            $input[] = array(
                        'col' => 6,
                        'type' => 'text',
                        'name' => 'TBCMSTABPRODUCTS_FEATURED_PROD_TITLE',
                        'label' => $this->l('Tab Title'),
                        'lang' => true,
                    );
        }

        if ($show_fields['featured_prod']['home_title']) {
            $input[] = array(
                        'col' => 6,
                        'type' => 'text',
                        'name' => 'TBCMSTABPRODUCTS_FEATURED_PROD_HOME_TITLE',
                        'label' => $this->l('Home Title'),
                        'lang' => true,
                    );
        }

        if ($show_fields['featured_prod']['home_sub_title']) {
            $input[] = array(
                        'col' => 6,
                        'type' => 'text',
                        'name' => 'TBCMSTABPRODUCTS_FEATURED_PROD_HOME_SUB_TITLE',
                        'label' => $this->l('Home Sub Title'),
                        'lang' => true,
                    );
        }

        if ($show_fields['featured_prod']['home_description']) {
            $input[] = array(
                        'col' => 6,
                        'type' => 'text',
                        'name' => 'TBCMSTABPRODUCTS_FEATURED_PROD_HOME_DESC',
                        'label' => $this->l('Home Description'),
                        'lang' => true,
                    );
        }

        if ($show_fields['featured_prod']['left_title']) {
            $input[] = array(
                        'col' => 6,
                        'type' => 'text',
                        'name' => 'TBCMSTABPRODUCTS_FEATURED_PROD_LEFT_TITLE',
                        'label' => $this->l('Left Column Title'),
                        'lang' => true,
                    );
        }

        if ($show_fields['featured_prod']['right_title']) {
            $input[] = array(
                        'col' => 6,
                        'type' => 'text',
                        'name' => 'TBCMSTABPRODUCTS_FEATURED_PROD_RIGHT_TITLE',
                        'label' => $this->l('Right Column Title'),
                        'lang' => true,
                    );
        }

        if ($show_fields['featured_prod']['home_image']) {
            $input[] = array(
                        'col' => 6,
                        'type' => 'featured_file',
                        'name' => 'TBCMSTABPRODUCTS_FEATURED_PROD_IMAGE',
                        'label' => $this->l('Home Featured Products Banner'),
                        'lang' => true,
                    );
        }

        if ($show_fields['featured_prod']['home_image_side']) {
            $input[] = array(
                        'type' => 'select',
                        'label' => $this->l('Display Banner Side'),
                        'name' => 'TBCMSTABPRODUCTS_FEATURED_PROD_IMAGE_SIDE',
                        'options' => array(
                            'query' => array(
                                array(
                                    'id_option' => 'left',
                                    'name' => 'Left Side'
                                ),
                                array(
                                    'id_option' => 'right',
                                    'name' => 'Right Side'
                                ),
                            ),
                            'id' => 'id_option',
                            'name' => 'name'
                        )
                    );
        }

        if ($show_fields['featured_prod']['home_image_status']) {
            $input[] = array(
                        'type' => 'switch',
                        'label' => $this->l('Display Banner'),
                        'name' => 'TBCMSTABPRODUCTS_FEATURED_PROD_IMAGE_STATUS',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Yes')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('No')
                            )
                        )
                    );
        }

        if ($show_fields['featured_prod']['home_image_link']) {
            $input[] = array(
                        'col' => 6,
                        'type' => 'text',
                        'label' => $this->l('Display Banner Link'),
                        'name' => 'TBCMSTABPRODUCTS_FEATURED_PROD_IMAGE_LINK',
                        'lang' => true,
                    );
        }

        if ($show_fields['featured_prod']['num_of_prod']) {
            $input[] = array(
                        'col' => 6,
                        'type' => 'text',
                        'name' => 'TBCMSTABPRODUCTS_FEATURED_PROD_NBR',
                        'label' => $this->l('Number of New Product'),
                    );
        }

        if ($show_fields['featured_prod']['prod_cat']) {
            $input[] = array(
                        'col' => 6,
                        'type' => 'text',
                        'name' => 'TBCMSTABPRODUCTS_FEATURED_PROD_CAT',
                        'label' => $this->l('Category from which to pick products to be displayed'),
                    );
        }

        if ($show_fields['featured_prod']['random_prod']) {
            $input[] = array(
                        'type' => 'switch',
                        'label' => $this->l('Display Randomly Product'),
                        'name' => 'TBCMSTABPRODUCTS_FEATURED_PROD_RAND',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Yes')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('No')
                            )
                        )
                    );
        }

        if ($show_fields['featured_prod']['display_in_tab']) {
            $input[] = array(
                        'type' => 'switch',
                        'label' => $this->l('Display in Tab'),
                        'name' => 'TBCMSTABPRODUCTS_FEATURED_PROD_TAB',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Yes')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('No')
                            )
                        )
                    );
        }

        if ($show_fields['featured_prod']['display_in_home']) {
            $input[] = array(
                        'type' => 'switch',
                        'label' => $this->l('Display in Home Page'),
                        'name' => 'TBCMSTABPRODUCTS_FEATURED_PROD_HOME',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Yes')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('No')
                            )
                        )
                    );
        }

        if ($show_fields['featured_prod']['display_in_left']) {
            $input[] = array(
                        'type' => 'switch',
                        'label' => $this->l('Display in Left Column'),
                        'name' => 'TBCMSTABPRODUCTS_FEATURED_PROD_LEFT',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Yes')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('No')
                            )
                        )
                    );
        }

        if ($show_fields['featured_prod']['display_in_right']) {
            $input[] = array(
                        'type' => 'switch',
                        'label' => $this->l('Display in Right Column'),
                        'name' => 'TBCMSTABPRODUCTS_FEATURED_PROD_RIGHT',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Yes')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('No')
                            )
                        )
                    );
        }


        return array(
            'form' => array(
                'legend' => array(
                'title' => $this->l('Feature Product Tab Setting'),
                'icon' => 'icon-cogs',
                ),
                'input' => $input,
                'submit' => array(
                    'title' => $this->l('Save'),
                    'name' => 'submitTbcmsTabFeaturedProdSettings',
                ),
            ),
        );
    }

    protected function tbcmsCustomFormNewProduct()
    {
        $tbcms_obj = new TbcmsTabProductsStatus();
        $show_fields = $tbcms_obj->fieldStatusInformation();
        $input = array();

        if ($show_fields['new_prod']['tab_title']) {
            $input[] = array(
                        'col' => 6,
                        'type' => 'text',
                        'name' => 'TBCMSTABPRODUCTS_NEW_PROD_TITLE',
                        'label' => $this->l('Tab Title'),
                        'lang' => true,
                    );
        }

        if ($show_fields['new_prod']['home_title']) {
            $input[] = array(
                        'col' => 6,
                        'type' => 'text',
                        'name' => 'TBCMSTABPRODUCTS_NEW_PROD_HOME_TITLE',
                        'label' => $this->l('Home Title'),
                        'lang' => true,
                    );
        }

        if ($show_fields['new_prod']['home_sub_title']) {
            $input[] = array(
                        'col' => 6,
                        'type' => 'text',
                        'name' => 'TBCMSTABPRODUCTS_NEW_PROD_HOME_SUB_TITLE',
                        'label' => $this->l('Home Sub Title'),
                        'lang' => true,
                    );
        }

        if ($show_fields['new_prod']['home_description']) {
            $input[] = array(
                        'col' => 6,
                        'type' => 'text',
                        'name' => 'TBCMSTABPRODUCTS_NEW_PROD_HOME_DESC',
                        'label' => $this->l('Home Description'),
                        'lang' => true,
                    );
        }

        if ($show_fields['new_prod']['left_title']) {
            $input[] = array(
                        'col' => 6,
                        'type' => 'text',
                        'name' => 'TBCMSTABPRODUCTS_NEW_PROD_LEFT_TITLE',
                        'label' => $this->l('Left Column Title'),
                        'lang' => true,
                    );
        }

        if ($show_fields['new_prod']['right_title']) {
            $input[] = array(
                        'col' => 6,
                        'type' => 'text',
                        'name' => 'TBCMSTABPRODUCTS_NEW_PROD_RIGHT_TITLE',
                        'label' => $this->l('Right Column Title'),
                        'lang' => true,
                    );
        }

        if ($show_fields['new_prod']['home_image']) {
            $input[] = array(
                        'col' => 6,
                        'type' => 'new_file',
                        'name' => 'TBCMSTABPRODUCTS_NEW_PROD_IMAGE',
                        'label' => $this->l('Home New Products Banner'),
                        'lang' => true,
                    );
        }

        if ($show_fields['new_prod']['home_image_side']) {
            $input[] = array(
                        'type' => 'select',
                        'label' => $this->l('Display Banner Side'),
                        'name' => 'TBCMSTABPRODUCTS_NEW_PROD_IMAGE_SIDE',
                        'options' => array(
                            'query' => array(
                                array(
                                    'id_option' => 'left',
                                    'name' => 'Left Side'
                                ),
                                array(
                                    'id_option' => 'right',
                                    'name' => 'Right Side'
                                ),
                            ),
                            'id' => 'id_option',
                            'name' => 'name'
                        )
                    );
        }

        if ($show_fields['new_prod']['home_image_status']) {
            $input[] = array(
                        'type' => 'switch',
                        'label' => $this->l('Display Banner'),
                        'name' => 'TBCMSTABPRODUCTS_NEW_PROD_IMAGE_STATUS',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Yes')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('No')
                            )
                        )
                    );
        }


        if ($show_fields['new_prod']['home_image_link']) {
            $input[] = array(
                        'col' => 6,
                        'type' => 'text',
                        'label' => $this->l('Display Banner Link'),
                        'name' => 'TBCMSTABPRODUCTS_NEW_PROD_IMAGE_LINK',
                        'lang' => true,
                    );
        }

        if ($show_fields['new_prod']['num_of_prod']) {
            $input[] = array(
                        'col' => 6,
                        'type' => 'text',
                        'name' => 'TBCMSTABPRODUCTS_NEW_PROD_NBR',
                        'label' => $this->l('Number of Product'),
                    );
        }

        if ($show_fields['new_prod']['num_of_days']) {
            $input[] = array(
                        'col' => 6,
                        'type' => 'text',
                        'name' => 'PS_NB_DAYS_NEW_PRODUCT',
                        'label' => $this->l('Number of days for which the product is considered \'new\''),
                    );
        }

        if ($show_fields['new_prod']['display_in_tab']) {
            $input[] = array(
                        'type' => 'switch',
                        'label' => $this->l('Display in Tab'),
                        'name' => 'TBCMSTABPRODUCTS_NEW_PROD_TAB',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Yes')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('No')
                            )
                        )
                    );
        }

        if ($show_fields['new_prod']['display_in_home']) {
            $input[] = array(
                        'type' => 'switch',
                        'label' => $this->l('Display in Home Page'),
                        'name' => 'TBCMSTABPRODUCTS_NEW_PROD_HOME',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Yes')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('No')
                            )
                        )
                    );
        }

        if ($show_fields['new_prod']['display_in_left']) {
            $input[] = array(
                        'type' => 'switch',
                        'label' => $this->l('Display in Left Column'),
                        'name' => 'TBCMSTABPRODUCTS_NEW_PROD_LEFT',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Yes')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('No')
                            )
                        )
                    );
        }

        if ($show_fields['new_prod']['display_in_right']) {
            $input[] = array(
                        'type' => 'switch',
                        'label' => $this->l('Display in Right Column'),
                        'name' => 'TBCMSTABPRODUCTS_NEW_PROD_RIGHT',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Yes')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('No')
                            )
                        )
                    );
        }


        return array(
            'form' => array(
                'legend' => array(
                'title' => $this->l('New Product Tab Setting'),
                'icon' => 'icon-cogs',
                ),
                'input' => $input,
                'submit' => array(
                    'title' => $this->l('Save'),
                    'name' => 'submitTbcmsTabNewProdSettings',
                ),
            ),
        );
    }

    protected function tbcmsCustomFormBestSellerProduct()
    {
        $tbcms_obj = new TbcmsTabProductsStatus();
        $show_fields = $tbcms_obj->fieldStatusInformation();
        $input = array();

        if ($show_fields['best_seller_prod']['tab_title']) {
            $input[] = array(
                        'col' => 6,
                        'type' => 'text',
                        'name' => 'TBCMSTABPRODUCTS_BEST_SELLER_PROD_TITLE',
                        'label' => $this->l('Tab Title'),
                        'lang' => true,
                    );
        }

        if ($show_fields['best_seller_prod']['home_title']) {
            $input[] = array(
                        'col' => 6,
                        'type' => 'text',
                        'name' => 'TBCMSTABPRODUCTS_BEST_SELLER_PROD_HOME_TITLE',
                        'label' => $this->l('Home Title'),
                        'lang' => true,
                    );
        }

        if ($show_fields['best_seller_prod']['home_sub_title']) {
            $input[] = array(
                        'col' => 6,
                        'type' => 'text',
                        'name' => 'TBCMSTABPRODUCTS_BEST_SELLER_PROD_HOME_SUB_TITLE',
                        'label' => $this->l('Home Sub Title'),
                        'lang' => true,
                    );
        }

        if ($show_fields['best_seller_prod']['home_description']) {
            $input[] = array(
                        'col' => 6,
                        'type' => 'text',
                        'name' => 'TBCMSTABPRODUCTS_BEST_SELLER_PROD_HOME_DESC',
                        'label' => $this->l('Home Description'),
                        'lang' => true,
                    );
        }

        if ($show_fields['best_seller_prod']['left_title']) {
            $input[] = array(
                        'col' => 6,
                        'type' => 'text',
                        'name' => 'TBCMSTABPRODUCTS_BEST_SELLER_PROD_LEFT_TITLE',
                        'label' => $this->l('Left Column Title'),
                        'lang' => true,
                    );
        }

        if ($show_fields['best_seller_prod']['right_title']) {
            $input[] = array(
                        'col' => 6,
                        'type' => 'text',
                        'name' => 'TBCMSTABPRODUCTS_BEST_SELLER_PROD_RIGHT_TITLE',
                        'label' => $this->l('Right Column Title'),
                        'lang' => true,
                    );
        }

        if ($show_fields['best_seller_prod']['home_image']) {
            $input[] = array(
                        'col' => 6,
                        'type' => 'best_seller_file',
                        'name' => 'TBCMSTABPRODUCTS_BEST_SELLER_PROD_IMAGE',
                        'label' => $this->l('Home Best Seller Products Banner'),
                        'lang' => true,
                    );
        }

        if ($show_fields['best_seller_prod']['home_image_side']) {
            $input[] = array(
                        'type' => 'select',
                        'label' => $this->l('Display Banner Side'),
                        'name' => 'TBCMSTABPRODUCTS_BEST_SELLER_PROD_IMAGE_SIDE',
                        'options' => array(
                            'query' => array(
                                array(
                                    'id_option' => 'left',
                                    'name' => 'Left Side'
                                ),
                                array(
                                    'id_option' => 'right',
                                    'name' => 'Right Side'
                                ),
                            ),
                            'id' => 'id_option',
                            'name' => 'name'
                        )
                    );
        }

        if ($show_fields['best_seller_prod']['home_image_status']) {
            $input[] = array(
                        'type' => 'switch',
                        'label' => $this->l('Display Banner'),
                        'name' => 'TBCMSTABPRODUCTS_BEST_SELLER_PROD_IMAGE_STATUS',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Yes')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('No')
                            )
                        )
                    );
        }

        if ($show_fields['best_seller_prod']['home_image_link']) {
            $input[] = array(
                        'col' => 6,
                        'type' => 'text',
                        'label' => $this->l('Display Banner Link'),
                        'name' => 'TBCMSTABPRODUCTS_BEST_SELLER_PROD_IMAGE_LINK',
                        'lang' => true,
                    );
        }

        if ($show_fields['best_seller_prod']['num_of_prod']) {
            $input[] = array(
                        'col' => 6,
                        'type' => 'text',
                        'name' => 'TBCMSTABPRODUCTS_BEST_SELLER_PROD_NBR',
                        'label' => $this->l('Number of Product'),
                    );
        }

        if ($show_fields['best_seller_prod']['display_in_tab']) {
            $input[] = array(
                        'type' => 'switch',
                        'label' => $this->l('Display in Tab'),
                        'name' => 'TBCMSTABPRODUCTS_BEST_SELLER_PROD_TAB',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Yes')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('No')
                            )
                        )
                    );
        }

        if ($show_fields['best_seller_prod']['display_in_home']) {
            $input[] = array(
                        'type' => 'switch',
                        'label' => $this->l('Display in Home Page'),
                        'name' => 'TBCMSTABPRODUCTS_BEST_SELLER_PROD_HOME',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Yes')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('No')
                            )
                        )
                    );
        }

        if ($show_fields['best_seller_prod']['display_in_left']) {
            $input[] = array(
                        'type' => 'switch',
                        'label' => $this->l('Display in Left Column'),
                        'name' => 'TBCMSTABPRODUCTS_BEST_SELLER_PROD_LEFT',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Yes')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('No')
                            )
                        )
                    );
        }

        if ($show_fields['best_seller_prod']['display_in_right']) {
            $input[] = array(
                        'type' => 'switch',
                        'label' => $this->l('Display in Right Column'),
                        'name' => 'TBCMSTABPRODUCTS_BEST_SELLER_PROD_RIGHT',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Yes')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('No')
                            )
                        )
                    );
        }

        return array(
            'form' => array(
                'legend' => array(
                'title' => $this->l('Best Seller Tab Setting'),
                'icon' => 'icon-cogs',
                ),
                'input' => $input,
                'submit' => array(
                    'title' => $this->l('Save'),
                    'name' => 'submitTbcmsBestSellerProdTabSettings',
                ),
            ),
        );
    }

    protected function tbcmsCustomFormSpecialProduct()
    {
        $tbcms_obj = new TbcmsTabProductsStatus();
        $show_fields = $tbcms_obj->fieldStatusInformation();
        $input = array();

        if ($show_fields['special_prod']['tab_title']) {
            $input[] = array(
                        'col' => 6,
                        'type' => 'text',
                        'name' => 'TBCMSTABPRODUCTS_SPECIAL_PROD_TITLE',
                        'label' => $this->l('Tab Title'),
                        'lang' => true,
                    );
        }

        if ($show_fields['special_prod']['home_title']) {
            $input[] = array(
                        'col' => 6,
                        'type' => 'text',
                        'name' => 'TBCMSTABPRODUCTS_SPECIAL_PROD_HOME_TITLE',
                        'label' => $this->l('Home Title'),
                        'lang' => true,
                    );
        }

        if ($show_fields['special_prod']['home_sub_title']) {
            $input[] = array(
                        'col' => 6,
                        'type' => 'text',
                        'name' => 'TBCMSTABPRODUCTS_SPECIAL_PROD_HOME_SUB_TITLE',
                        'label' => $this->l('Home Sub Title'),
                        'lang' => true,
                    );
        }

        if ($show_fields['special_prod']['home_description']) {
            $input[] = array(
                        'col' => 6,
                        'type' => 'text',
                        'name' => 'TBCMSTABPRODUCTS_SPECIAL_PROD_HOME_DESC',
                        'label' => $this->l('Home Description'),
                        'lang' => true,
                    );
        }

        if ($show_fields['special_prod']['left_title']) {
            $input[] = array(
                        'col' => 6,
                        'type' => 'text',
                        'name' => 'TBCMSTABPRODUCTS_SPECIAL_PROD_LEFT_TITLE',
                        'label' => $this->l('Left Column Title'),
                        'lang' => true,
                    );
        }

        if ($show_fields['special_prod']['right_title']) {
            $input[] = array(
                        'col' => 6,
                        'type' => 'text',
                        'name' => 'TBCMSTABPRODUCTS_SPECIAL_PROD_RIGHT_TITLE',
                        'label' => $this->l('Right Column Title'),
                        'lang' => true,
                    );
        }

        if ($show_fields['special_prod']['home_image']) {
            $input[] = array(
                        'col' => 6,
                        'type' => 'special_file',
                        'name' => 'TBCMSTABPRODUCTS_SPECIAL_PROD_IMAGE',
                        'label' => $this->l('Special Products Banner'),
                        'lang' => true,
                    );
        }

        if ($show_fields['special_prod']['home_image_side']) {
            $input[] = array(
                        'type' => 'select',
                        'label' => $this->l('Display Banner Side'),
                        'name' => 'TBCMSTABPRODUCTS_SPECIAL_PROD_IMAGE_SIDE',
                        'options' => array(
                            'query' => array(
                                array(
                                    'id_option' => 'left',
                                    'name' => 'Left Side'
                                ),
                                array(
                                    'id_option' => 'right',
                                    'name' => 'Right Side'
                                ),
                            ),
                            'id' => 'id_option',
                            'name' => 'name'
                        )
                    );
        }

        if ($show_fields['special_prod']['home_image_status']) {
            $input[] = array(
                        'type' => 'switch',
                        'label' => $this->l('Display Banner'),
                        'name' => 'TBCMSTABPRODUCTS_SPECIAL_PROD_IMAGE_STATUS',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Yes')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('No')
                            )
                        )
                    );
        }

        if ($show_fields['special_prod']['home_image_link']) {
            $input[] = array(
                        'col' => 6,
                        'type' => 'text',
                        'label' => $this->l('Display Banner Link'),
                        'name' => 'TBCMSTABPRODUCTS_SPECIAL_PROD_IMAGE_LINK',
                        'lang' => true,
                    );
        }

        if ($show_fields['special_prod']['num_of_prod']) {
            $input[] = array(
                        'col' => 6,
                        'type' => 'text',
                        'name' => 'TBCMSTABPRODUCTS_SPECIAL_PROD_NBR',
                        'label' => $this->l('Number of Product'),
                    );
        }

        if ($show_fields['special_prod']['display_in_tab']) {
            $input[] = array(
                        'type' => 'switch',
                        'label' => $this->l('Display in Tab'),
                        'name' => 'TBCMSTABPRODUCTS_SPECIAL_PROD_TAB',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Yes')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('No')
                            )
                        )
                    );
        }

        if ($show_fields['special_prod']['display_in_home']) {
            $input[] = array(
                        'type' => 'switch',
                        'label' => $this->l('Display in Home Page'),
                        'name' => 'TBCMSTABPRODUCTS_SPECIAL_PROD_HOME',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Yes')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('No')
                            )
                        )
                    );
        }

        if ($show_fields['special_prod']['display_in_left']) {
            $input[] = array(
                        'type' => 'switch',
                        'label' => $this->l('Display in Left Column'),
                        'name' => 'TBCMSTABPRODUCTS_SPECIAL_PROD_LEFT',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Yes')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('No')
                            )
                        )
                    );
        }

        if ($show_fields['special_prod']['display_in_right']) {
            $input[] = array(
                        'type' => 'switch',
                        'label' => $this->l('Display in Right Column'),
                        'name' => 'TBCMSTABPRODUCTS_SPECIAL_PROD_RIGHT',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Yes')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('No')
                            )
                        )
                    );
        }


        return array(
            'form' => array(
                'legend' => array(
                'title' => $this->l('Special Product Tab Setting'),
                'icon' => 'icon-cogs',
                ),
                'input' => $input,
                'submit' => array(
                    'title' => $this->l('Save'),
                    'name' => 'submitTbcmsTabSpecialProdSettings',
                ),
            ),
    
        );
    }

    public function getConfigFormValues()
    {
        $languages = Language::getLanguages();
        $result = array();
        foreach ($languages as $lang) {
            $tmp = Configuration::get('TBCMSTABPRODUCTS_NEW_PROD_TITLE', $lang['id_lang']);
            $result['TBCMSTABPRODUCTS_NEW_PROD_TITLE'][$lang['id_lang']] = $tmp;
            $tmp = Configuration::get('TBCMSTABPRODUCTS_NEW_PROD_HOME_TITLE', $lang['id_lang']);
            $result['TBCMSTABPRODUCTS_NEW_PROD_HOME_TITLE'][$lang['id_lang']] = $tmp;
            $tmp = Configuration::get('TBCMSTABPRODUCTS_NEW_PROD_HOME_SUB_TITLE', $lang['id_lang']);
            $result['TBCMSTABPRODUCTS_NEW_PROD_HOME_SUB_TITLE'][$lang['id_lang']] = $tmp;
            $tmp = Configuration::get('TBCMSTABPRODUCTS_NEW_PROD_HOME_DESC', $lang['id_lang']);
            $result['TBCMSTABPRODUCTS_NEW_PROD_HOME_DESC'][$lang['id_lang']] = $tmp;
            $tmp = Configuration::get('TBCMSTABPRODUCTS_NEW_PROD_LEFT_TITLE', $lang['id_lang']);
            $result['TBCMSTABPRODUCTS_NEW_PROD_LEFT_TITLE'][$lang['id_lang']] = $tmp;
            $tmp = Configuration::get('TBCMSTABPRODUCTS_NEW_PROD_RIGHT_TITLE', $lang['id_lang']);
            $result['TBCMSTABPRODUCTS_NEW_PROD_RIGHT_TITLE'][$lang['id_lang']] = $tmp;
            $tmp = Configuration::get('TBCMSTABPRODUCTS_NEW_PROD_IMAGE', $lang['id_lang']);
            $result['TBCMSTABPRODUCTS_NEW_PROD_IMAGE'][$lang['id_lang']] = $tmp;
            $tmp = Configuration::get('TBCMSTABPRODUCTS_NEW_PROD_IMAGE_LINK', $lang['id_lang']);
            $result['TBCMSTABPRODUCTS_NEW_PROD_IMAGE_LINK'][$lang['id_lang']] = $tmp;
            
            $tmp = Configuration::get('TBCMSTABPRODUCTS_SPECIAL_PROD_IMAGE', $lang['id_lang']);
            $result['TBCMSTABPRODUCTS_SPECIAL_PROD_IMAGE'][$lang['id_lang']] = $tmp;
            $tmp = Configuration::get('TBCMSTABPRODUCTS_SPECIAL_PROD_IMAGE_LINK', $lang['id_lang']);
            $result['TBCMSTABPRODUCTS_SPECIAL_PROD_IMAGE_LINK'][$lang['id_lang']] = $tmp;
            $tmp = Configuration::get('TBCMSTABPRODUCTS_SPECIAL_PROD_TITLE', $lang['id_lang']);
            $result['TBCMSTABPRODUCTS_SPECIAL_PROD_TITLE'][$lang['id_lang']] = $tmp;
            $tmp = Configuration::get('TBCMSTABPRODUCTS_SPECIAL_PROD_HOME_TITLE', $lang['id_lang']);
            $result['TBCMSTABPRODUCTS_SPECIAL_PROD_HOME_TITLE'][$lang['id_lang']] = $tmp;
            $tmp = Configuration::get('TBCMSTABPRODUCTS_SPECIAL_PROD_HOME_SUB_TITLE', $lang['id_lang']);
            $result['TBCMSTABPRODUCTS_SPECIAL_PROD_HOME_SUB_TITLE'][$lang['id_lang']] = $tmp;
            $tmp = Configuration::get('TBCMSTABPRODUCTS_SPECIAL_PROD_HOME_DESC', $lang['id_lang']);
            $result['TBCMSTABPRODUCTS_SPECIAL_PROD_HOME_DESC'][$lang['id_lang']] = $tmp;
            $tmp = Configuration::get('TBCMSTABPRODUCTS_SPECIAL_PROD_LEFT_TITLE', $lang['id_lang']);
            $result['TBCMSTABPRODUCTS_SPECIAL_PROD_LEFT_TITLE'][$lang['id_lang']] = $tmp;
            $tmp = Configuration::get('TBCMSTABPRODUCTS_SPECIAL_PROD_RIGHT_TITLE', $lang['id_lang']);
            $result['TBCMSTABPRODUCTS_SPECIAL_PROD_RIGHT_TITLE'][$lang['id_lang']] = $tmp;

            $tmp = Configuration::get('TBCMSTABPRODUCTS_FEATURED_PROD_IMAGE', $lang['id_lang']);
            $result['TBCMSTABPRODUCTS_FEATURED_PROD_IMAGE'][$lang['id_lang']] = $tmp;
            $tmp = Configuration::get('TBCMSTABPRODUCTS_FEATURED_PROD_IMAGE_LINK', $lang['id_lang']);
            $result['TBCMSTABPRODUCTS_FEATURED_PROD_IMAGE_LINK'][$lang['id_lang']] = $tmp;
            $tmp = Configuration::get('TBCMSTABPRODUCTS_FEATURED_PROD_TITLE', $lang['id_lang']);
            $result['TBCMSTABPRODUCTS_FEATURED_PROD_TITLE'][$lang['id_lang']] = $tmp;
            $tmp = Configuration::get('TBCMSTABPRODUCTS_FEATURED_PROD_HOME_TITLE', $lang['id_lang']);
            $result['TBCMSTABPRODUCTS_FEATURED_PROD_HOME_TITLE'][$lang['id_lang']] = $tmp;
            $tmp = Configuration::get('TBCMSTABPRODUCTS_FEATURED_PROD_HOME_SUB_TITLE', $lang['id_lang']);
            $result['TBCMSTABPRODUCTS_FEATURED_PROD_HOME_SUB_TITLE'][$lang['id_lang']] = $tmp;
            $tmp = Configuration::get('TBCMSTABPRODUCTS_FEATURED_PROD_HOME_DESC', $lang['id_lang']);
            $result['TBCMSTABPRODUCTS_FEATURED_PROD_HOME_DESC'][$lang['id_lang']] = $tmp;
            $tmp = Configuration::get('TBCMSTABPRODUCTS_FEATURED_PROD_LEFT_TITLE', $lang['id_lang']);
            $result['TBCMSTABPRODUCTS_FEATURED_PROD_LEFT_TITLE'][$lang['id_lang']] = $tmp;
            $tmp = Configuration::get('TBCMSTABPRODUCTS_FEATURED_PROD_RIGHT_TITLE', $lang['id_lang']);
            $result['TBCMSTABPRODUCTS_FEATURED_PROD_RIGHT_TITLE'][$lang['id_lang']] = $tmp;

            $tmp = Configuration::get('TBCMSTABPRODUCTS_BEST_SELLER_PROD_TITLE', $lang['id_lang']);
            $result['TBCMSTABPRODUCTS_BEST_SELLER_PROD_TITLE'][$lang['id_lang']] = $tmp;
            $tmp = Configuration::get('TBCMSTABPRODUCTS_BEST_SELLER_PROD_HOME_TITLE', $lang['id_lang']);
            $result['TBCMSTABPRODUCTS_BEST_SELLER_PROD_HOME_TITLE'][$lang['id_lang']] = $tmp;
            $tmp = Configuration::get('TBCMSTABPRODUCTS_BEST_SELLER_PROD_HOME_SUB_TITLE', $lang['id_lang']);
            $result['TBCMSTABPRODUCTS_BEST_SELLER_PROD_HOME_SUB_TITLE'][$lang['id_lang']] = $tmp;
            $tmp = Configuration::get('TBCMSTABPRODUCTS_BEST_SELLER_PROD_HOME_DESC', $lang['id_lang']);
            $result['TBCMSTABPRODUCTS_BEST_SELLER_PROD_HOME_DESC'][$lang['id_lang']] = $tmp;
            $tmp = Configuration::get('TBCMSTABPRODUCTS_BEST_SELLER_PROD_LEFT_TITLE', $lang['id_lang']);
            $result['TBCMSTABPRODUCTS_BEST_SELLER_PROD_LEFT_TITLE'][$lang['id_lang']] = $tmp;
            $tmp = Configuration::get('TBCMSTABPRODUCTS_BEST_SELLER_PROD_RIGHT_TITLE', $lang['id_lang']);
            $result['TBCMSTABPRODUCTS_BEST_SELLER_PROD_RIGHT_TITLE'][$lang['id_lang']] = $tmp;
            $tmp = Configuration::get('TBCMSTABPRODUCTS_BEST_SELLER_PROD_IMAGE', $lang['id_lang']);
            $result['TBCMSTABPRODUCTS_BEST_SELLER_PROD_IMAGE'][$lang['id_lang']] = $tmp;
            $tmp = Configuration::get('TBCMSTABPRODUCTS_BEST_SELLER_PROD_IMAGE_LINK', $lang['id_lang']);
            $result['TBCMSTABPRODUCTS_BEST_SELLER_PROD_IMAGE_LINK'][$lang['id_lang']] = $tmp;

            $tmp = Configuration::get('TBCMSTABPRODUCTS_MAIN_TITLE', $lang['id_lang']);
            $result['TBCMSTABPRODUCTS_MAIN_TITLE'][$lang['id_lang']] = $tmp;
            $tmp = Configuration::get('TBCMSTABPRODUCTS_MAIN_SUB_TITLE', $lang['id_lang']);
            $result['TBCMSTABPRODUCTS_MAIN_SUB_TITLE'][$lang['id_lang']] = $tmp;
            $tmp = Configuration::get('TBCMSTABPRODUCTS_MAIN_DESCRIPTION', $lang['id_lang']);
            $result['TBCMSTABPRODUCTS_MAIN_DESCRIPTION'][$lang['id_lang']] = $tmp;
            $tmp = Configuration::get('TBCMSTABPRODUCTS_MAIN_IMAGE', $lang['id_lang']);
            $result['TBCMSTABPRODUCTS_MAIN_IMAGE'][$lang['id_lang']] = $tmp;
            $tmp = Configuration::get('TBCMSTABPRODUCTS_MAIN_IMAGE_LINK', $lang['id_lang']);
            $result['TBCMSTABPRODUCTS_MAIN_IMAGE_LINK'][$lang['id_lang']] = $tmp;
        }
        
        $path = _MODULE_DIR_.$this->name."/views/img/";
        $this->context->smarty->assign("path", $path);


        $special_prod_img_side = Configuration::get('TBCMSTABPRODUCTS_SPECIAL_PROD_IMAGE_SIDE');
        $special_prod_img_status = Configuration::get('TBCMSTABPRODUCTS_SPECIAL_PROD_IMAGE_STATUS');
        $special_prod_home_sub_title = $result['TBCMSTABPRODUCTS_SPECIAL_PROD_HOME_SUB_TITLE'];

        $featured_prod_home_sub_title = $result['TBCMSTABPRODUCTS_FEATURED_PROD_HOME_SUB_TITLE'];
        $featured_prod_img_side = Configuration::get('TBCMSTABPRODUCTS_FEATURED_PROD_IMAGE_SIDE');
        $featured_prod_img_status = Configuration::get('TBCMSTABPRODUCTS_FEATURED_PROD_IMAGE_STATUS');

        $best_seller_prod_home_title = $result['TBCMSTABPRODUCTS_BEST_SELLER_PROD_HOME_TITLE'];
        $best_seller_prod_home_sub_title = $result['TBCMSTABPRODUCTS_BEST_SELLER_PROD_HOME_SUB_TITLE'];
        $best_seller_prod_right_title = $result['TBCMSTABPRODUCTS_BEST_SELLER_PROD_RIGHT_TITLE'];
        $best_seller_prod_img_side = Configuration::get('TBCMSTABPRODUCTS_BEST_SELLER_PROD_IMAGE_SIDE');
        $best_seller_prod_img_status = Configuration::get('TBCMSTABPRODUCTS_BEST_SELLER_PROD_IMAGE_STATUS');
        $best_seller_prod_right = Configuration::get('TBCMSTABPRODUCTS_BEST_SELLER_PROD_RIGHT');
        $best_seller_prod_left_title = $result['TBCMSTABPRODUCTS_BEST_SELLER_PROD_LEFT_TITLE'];
        $best_seller_prod_home = Configuration::get('TBCMSTABPRODUCTS_BEST_SELLER_PROD_HOME');
        $best_seller_prod_left = Configuration::get('TBCMSTABPRODUCTS_BEST_SELLER_PROD_LEFT');

        $new_prod_image_status = Configuration::get('TBCMSTABPRODUCTS_NEW_PROD_IMAGE_STATUS');
        
        return array(
            'TBCMSTABPRODUCTS_NEW_PROD_TITLE' => $result['TBCMSTABPRODUCTS_NEW_PROD_TITLE'],
            'TBCMSTABPRODUCTS_NEW_PROD_HOME_TITLE' => $result['TBCMSTABPRODUCTS_NEW_PROD_HOME_TITLE'],
            'TBCMSTABPRODUCTS_NEW_PROD_HOME_SUB_TITLE' => $result['TBCMSTABPRODUCTS_NEW_PROD_HOME_SUB_TITLE'],
            'TBCMSTABPRODUCTS_NEW_PROD_HOME_DESC' => $result['TBCMSTABPRODUCTS_NEW_PROD_HOME_DESC'],
            'TBCMSTABPRODUCTS_NEW_PROD_LEFT_TITLE' => $result['TBCMSTABPRODUCTS_NEW_PROD_LEFT_TITLE'],
            'TBCMSTABPRODUCTS_NEW_PROD_RIGHT_TITLE' => $result['TBCMSTABPRODUCTS_NEW_PROD_RIGHT_TITLE'],
            'TBCMSTABPRODUCTS_NEW_PROD_IMAGE' => $result['TBCMSTABPRODUCTS_NEW_PROD_IMAGE'],
            'TBCMSTABPRODUCTS_NEW_PROD_IMAGE_LINK' => $result['TBCMSTABPRODUCTS_NEW_PROD_IMAGE_LINK'],
            'TBCMSTABPRODUCTS_NEW_PROD_IMAGE_SIDE' => Configuration::get('TBCMSTABPRODUCTS_NEW_PROD_IMAGE_SIDE'),
            'TBCMSTABPRODUCTS_NEW_PROD_IMAGE_STATUS' => $new_prod_image_status,
            'TBCMSTABPRODUCTS_NEW_PROD_NBR' => Configuration::get('TBCMSTABPRODUCTS_NEW_PROD_NBR'),
            'TBCMSTABPRODUCTS_NEW_PROD_TAB' => Configuration::get('TBCMSTABPRODUCTS_NEW_PROD_TAB'),
            'TBCMSTABPRODUCTS_NEW_PROD_HOME' => Configuration::get('TBCMSTABPRODUCTS_NEW_PROD_HOME'),
            'TBCMSTABPRODUCTS_NEW_PROD_LEFT' => Configuration::get('TBCMSTABPRODUCTS_NEW_PROD_LEFT'),
            'TBCMSTABPRODUCTS_NEW_PROD_RIGHT' => Configuration::get('TBCMSTABPRODUCTS_NEW_PROD_RIGHT'),
            'PS_NB_DAYS_NEW_PRODUCT' => Configuration::get('PS_NB_DAYS_NEW_PRODUCT'),

            'TBCMSTABPRODUCTS_SPECIAL_PROD_IMAGE' => $result['TBCMSTABPRODUCTS_SPECIAL_PROD_IMAGE'],
            'TBCMSTABPRODUCTS_SPECIAL_PROD_IMAGE_LINK' => $result['TBCMSTABPRODUCTS_SPECIAL_PROD_IMAGE_LINK'],
            'TBCMSTABPRODUCTS_SPECIAL_PROD_IMAGE_SIDE' => $special_prod_img_side,
            'TBCMSTABPRODUCTS_SPECIAL_PROD_IMAGE_STATUS' => $special_prod_img_status,
            'TBCMSTABPRODUCTS_SPECIAL_PROD_TITLE' => $result['TBCMSTABPRODUCTS_SPECIAL_PROD_TITLE'],
            'TBCMSTABPRODUCTS_SPECIAL_PROD_HOME_TITLE' => $result['TBCMSTABPRODUCTS_SPECIAL_PROD_HOME_TITLE'],
            'TBCMSTABPRODUCTS_SPECIAL_PROD_HOME_SUB_TITLE' => $special_prod_home_sub_title,
            'TBCMSTABPRODUCTS_SPECIAL_PROD_HOME_DESC' => $result['TBCMSTABPRODUCTS_SPECIAL_PROD_HOME_DESC'],
            'TBCMSTABPRODUCTS_SPECIAL_PROD_LEFT_TITLE' => $result['TBCMSTABPRODUCTS_SPECIAL_PROD_LEFT_TITLE'],
            'TBCMSTABPRODUCTS_SPECIAL_PROD_RIGHT_TITLE' => $result['TBCMSTABPRODUCTS_SPECIAL_PROD_RIGHT_TITLE'],
            'TBCMSTABPRODUCTS_SPECIAL_PROD_NBR' => Configuration::get('TBCMSTABPRODUCTS_SPECIAL_PROD_NBR'),
            'TBCMSTABPRODUCTS_SPECIAL_PROD_TAB' => Configuration::get('TBCMSTABPRODUCTS_SPECIAL_PROD_TAB'),
            'TBCMSTABPRODUCTS_SPECIAL_PROD_HOME' => Configuration::get('TBCMSTABPRODUCTS_SPECIAL_PROD_HOME'),
            'TBCMSTABPRODUCTS_SPECIAL_PROD_LEFT' => Configuration::get('TBCMSTABPRODUCTS_SPECIAL_PROD_LEFT'),
            'TBCMSTABPRODUCTS_SPECIAL_PROD_RIGHT' => Configuration::get('TBCMSTABPRODUCTS_SPECIAL_PROD_RIGHT'),

            'TBCMSTABPRODUCTS_FEATURED_PROD_IMAGE' => $result['TBCMSTABPRODUCTS_FEATURED_PROD_IMAGE'],
            'TBCMSTABPRODUCTS_FEATURED_PROD_IMAGE_LINK' => $result['TBCMSTABPRODUCTS_FEATURED_PROD_IMAGE_LINK'],
            'TBCMSTABPRODUCTS_FEATURED_PROD_TITLE' => $result['TBCMSTABPRODUCTS_FEATURED_PROD_TITLE'],
            'TBCMSTABPRODUCTS_FEATURED_PROD_HOME_TITLE' => $result['TBCMSTABPRODUCTS_FEATURED_PROD_HOME_TITLE'],
            'TBCMSTABPRODUCTS_FEATURED_PROD_HOME_SUB_TITLE' => $featured_prod_home_sub_title,
            'TBCMSTABPRODUCTS_FEATURED_PROD_HOME_DESC' => $result['TBCMSTABPRODUCTS_FEATURED_PROD_HOME_DESC'],
            'TBCMSTABPRODUCTS_FEATURED_PROD_LEFT_TITLE' => $result['TBCMSTABPRODUCTS_FEATURED_PROD_LEFT_TITLE'],
            'TBCMSTABPRODUCTS_FEATURED_PROD_RIGHT_TITLE' => $result['TBCMSTABPRODUCTS_FEATURED_PROD_RIGHT_TITLE'],
            'TBCMSTABPRODUCTS_FEATURED_PROD_NBR' => Configuration::get('TBCMSTABPRODUCTS_FEATURED_PROD_NBR'),
            'TBCMSTABPRODUCTS_FEATURED_PROD_CAT' => Configuration::get('TBCMSTABPRODUCTS_FEATURED_PROD_CAT'),
            'TBCMSTABPRODUCTS_FEATURED_PROD_RAND' => Configuration::get('TBCMSTABPRODUCTS_FEATURED_PROD_RAND'),
            'TBCMSTABPRODUCTS_FEATURED_PROD_TAB' => Configuration::get('TBCMSTABPRODUCTS_FEATURED_PROD_TAB'),
            'TBCMSTABPRODUCTS_FEATURED_PROD_IMAGE_SIDE' => $featured_prod_img_side,
            'TBCMSTABPRODUCTS_FEATURED_PROD_IMAGE_STATUS' => $featured_prod_img_status,
            'TBCMSTABPRODUCTS_FEATURED_PROD_HOME' => Configuration::get('TBCMSTABPRODUCTS_FEATURED_PROD_HOME'),
            'TBCMSTABPRODUCTS_FEATURED_PROD_LEFT' => Configuration::get('TBCMSTABPRODUCTS_FEATURED_PROD_LEFT'),
            'TBCMSTABPRODUCTS_FEATURED_PROD_RIGHT' => Configuration::get('TBCMSTABPRODUCTS_FEATURED_PROD_RIGHT'),

            'TBCMSTABPRODUCTS_BEST_SELLER_PROD_TITLE' => $result['TBCMSTABPRODUCTS_BEST_SELLER_PROD_TITLE'],
            'TBCMSTABPRODUCTS_BEST_SELLER_PROD_HOME_TITLE' => $best_seller_prod_home_title,
            'TBCMSTABPRODUCTS_BEST_SELLER_PROD_HOME_SUB_TITLE' => $best_seller_prod_home_sub_title,
            'TBCMSTABPRODUCTS_BEST_SELLER_PROD_HOME_DESC' => $result['TBCMSTABPRODUCTS_BEST_SELLER_PROD_HOME_DESC'],
            'TBCMSTABPRODUCTS_BEST_SELLER_PROD_LEFT_TITLE' => $best_seller_prod_left_title,
            'TBCMSTABPRODUCTS_BEST_SELLER_PROD_RIGHT_TITLE' => $best_seller_prod_right_title,
            'TBCMSTABPRODUCTS_BEST_SELLER_PROD_IMAGE' => $result['TBCMSTABPRODUCTS_BEST_SELLER_PROD_IMAGE'],
            'TBCMSTABPRODUCTS_BEST_SELLER_PROD_IMAGE_LINK' => $result['TBCMSTABPRODUCTS_BEST_SELLER_PROD_IMAGE_LINK'],
            'TBCMSTABPRODUCTS_BEST_SELLER_PROD_IMAGE_SIDE' => $best_seller_prod_img_side,
            'TBCMSTABPRODUCTS_BEST_SELLER_PROD_IMAGE_STATUS' => $best_seller_prod_img_status,
            'TBCMSTABPRODUCTS_BEST_SELLER_PROD_NBR' => Configuration::get('TBCMSTABPRODUCTS_BEST_SELLER_PROD_NBR'),
            'TBCMSTABPRODUCTS_BEST_SELLER_PROD_TAB' => Configuration::get('TBCMSTABPRODUCTS_BEST_SELLER_PROD_TAB'),
            'TBCMSTABPRODUCTS_BEST_SELLER_PROD_HOME' => $best_seller_prod_home,
            'TBCMSTABPRODUCTS_BEST_SELLER_PROD_LEFT' => $best_seller_prod_left,
            'TBCMSTABPRODUCTS_BEST_SELLER_PROD_RIGHT' => $best_seller_prod_right,

            'TBCMSTABPRODUCTS_MAIN_TITLE' => $result['TBCMSTABPRODUCTS_MAIN_TITLE'],
            'TBCMSTABPRODUCTS_MAIN_SUB_TITLE' => $result['TBCMSTABPRODUCTS_MAIN_SUB_TITLE'],
            'TBCMSTABPRODUCTS_MAIN_DESCRIPTION' => $result['TBCMSTABPRODUCTS_MAIN_DESCRIPTION'],
            'TBCMSTABPRODUCTS_MAIN_SUB_TITLE' => $result['TBCMSTABPRODUCTS_MAIN_SUB_TITLE'],
            'TBCMSTABPRODUCTS_MAIN_IMAGE' => $result['TBCMSTABPRODUCTS_MAIN_IMAGE'],
            'TBCMSTABPRODUCTS_MAIN_IMAGE_LINK' => $result['TBCMSTABPRODUCTS_MAIN_IMAGE_LINK'],
            'TBCMSTABPRODUCTS_MAIN_IMAGE_SIDE' => Configuration::get('TBCMSTABPRODUCTS_MAIN_IMAGE_SIDE'),
            'TBCMSTABPRODUCTS_MAIN_IMAGE_STATUS' => Configuration::get('TBCMSTABPRODUCTS_MAIN_IMAGE_STATUS')
        );
    }

    public function hookDisplayBackOfficeHeader()
    {
        $this->context->controller->addJS($this->_path.'views/js/back.js');
        $this->context->controller->addCSS($this->_path.'views/css/back.css');
    }

    public function hookdisplayHeader()
    {
        $this->context->controller->addJS($this->_path.'views/js/front.js');
        $this->context->controller->addCSS($this->_path.'views/css/front.css');
    }

    public function showFrontData()
    {
        $tab_product_list = array();

        // display Feature Product
        $status = (int)Configuration::get('TBCMSTABPRODUCTS_FEATURED_PROD_TAB');
        if ($status) {
            $product_info = $this->displayFeaturedProducts();
            $total_prod = count($product_info['product_list']);
            if ($total_prod > 0) {
                $tab_product_list[] = $product_info;
            }
        }
        
        // display New Product
        $status = (int)Configuration::get('TBCMSTABPRODUCTS_NEW_PROD_TAB');
        if ($status) {
            $product_info = $this->displayNewProducts();
            $total_prod = count($product_info['product_list']);
            if ($total_prod > 0) {
                $tab_product_list[] = $product_info;
            }
        }

        // display Best Seller Product
        $status = (int)Configuration::get('TBCMSTABPRODUCTS_BEST_SELLER_PROD_TAB');
        if ($status) {
            $product_info = $this->displayBestSellers();
            $total_prod = count($product_info['product_list']);
            if ($total_prod > 0) {
                $tab_product_list[] = $product_info;
            }
        }

        // display Special Product
        $status = (int)Configuration::get('TBCMSTABPRODUCTS_SPECIAL_PROD_TAB');
        if ($status) {
            $product_info = $this->displaySpecialProducts();
            $total_prod = count($product_info['product_list']);
            if ($total_prod > 0) {
                $tab_product_list[] = $product_info;
            }
        }

        return $tab_product_list;
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

        if (!$main_heading['main_title'] &&
            !$main_heading['main_sub_title'] &&
            !$main_heading['main_description'] &&
            !$main_heading['main_image']) {
            $main_heading['main_status'] = false;
        }
        return $main_heading;
    }

    public function showFrontSideResult()
    {
        $cookie = Context::getContext()->cookie;
        $id_lang = $cookie->id_lang;

        $tbcms_obj = new TbcmsTabProductsStatus();
        $all_prod_status = $tbcms_obj->fieldStatusInformation();

        $main_heading  = array();
        $main_heading['main_status'] = $all_prod_status['main_status'];
        $main_heading['main_title'] = $all_prod_status['main_title'];
        $main_heading['main_sub_title'] = $all_prod_status['main_sub_title'];
        $main_heading['main_description'] = $all_prod_status['main_description'];
        $main_heading['main_image'] = $all_prod_status['main_image'];
        $main_heading['main_image_side'] = $all_prod_status['main_image_side'];
        $main_heading['main_image_status'] = $all_prod_status['main_image_status'];

        if ($main_heading['main_status']) {
            $main_heading_data = array();

            $main_heading_data['title'] = Configuration::get('TBCMSTABPRODUCTS_MAIN_TITLE', $id_lang);
            $main_heading_data['short_desc'] = Configuration::get('TBCMSTABPRODUCTS_MAIN_SUB_TITLE', $id_lang);
            $main_heading_data['desc'] = Configuration::get('TBCMSTABPRODUCTS_MAIN_DESCRIPTION', $id_lang);
            $main_heading_data['image'] = Configuration::get('TBCMSTABPRODUCTS_MAIN_IMAGE', $id_lang);
            $main_heading_data['link'] = Configuration::get('TBCMSTABPRODUCTS_MAIN_IMAGE_LINK', $id_lang);
            $main_heading_data['image_side'] = Configuration::get('TBCMSTABPRODUCTS_MAIN_IMAGE_SIDE');
            $main_heading_data['image_status'] = Configuration::get('TBCMSTABPRODUCTS_MAIN_IMAGE_STATUS');

            $main_heading = $this->getArrMainTitle($main_heading, $main_heading_data);
            $main_heading['data'] = $main_heading_data;
        }

        $disArrResult = array();
        $disArrResult['data'] = $this->showFrontData();
        $disArrResult['status'] = empty($disArrResult['data'])?false:true;
        $disArrResult['path'] = _MODULE_DIR_.$this->name."/views/img/";
        $disArrResult['id_lang'] = $id_lang;

        $this->context->smarty->assign('main_heading', $main_heading);
        $this->context->smarty->assign('dis_arr_result', $disArrResult);

        return $disArrResult['status']?true:false;
    }

    public function hookdisplayHome()
    {
        if (!Cache::isStored('tbcmstabproducts_display_index.tpl')) {
            $result = $this->showFrontSideResult();
            if ($result) {
                $output = $this->display(__FILE__, 'views/templates/front/display_index.tpl');
            } else {
                $output = '';
            }

            Cache::store('tbcmstabproducts_display_index.tpl', $output);
        }

        return Cache::retrieve('tbcmstabproducts_display_index.tpl');
    }

    public function displayFeaturedProducts($num_of_prod = '')
    {
        $category = new Category((int)Configuration::get('TBCMSTABPRODUCTS_FEATURED_PROD_CAT'));

        $searchProvider = new CategoryProductSearchProvider(
            $this->context->getTranslator(),
            $category
        );

        $context = new ProductSearchContext($this->context);

        $query = new ProductSearchQuery();

        $nProducts = (int)Configuration::get('TBCMSTABPRODUCTS_FEATURED_PROD_NBR');
        if (!empty($num_of_prod)) {
            $nProducts = $num_of_prod;
        }

        $query
            ->setResultsPerPage($nProducts)
            ->setPage(1);

        if (Configuration::get('TBCMSTABPRODUCTS_FEATURED_PROD_RAND')) {
            $query->setSortOrder(SortOrder::random());
        } else {
            $query->setSortOrder(new SortOrder('product', 'position', 'asc'));
        }

        $result = $searchProvider->runQuery(
            $context,
            $query
        );

        $assembler = new ProductAssembler($this->context);

        $presenterFactory = new ProductPresenterFactory($this->context);
        $presentationSettings = $presenterFactory->getPresentationSettings();
        $presenter = new ProductListingPresenter(
            new ImageRetriever(
                $this->context->link
            ),
            $this->context->link,
            new PriceFormatter(),
            new ProductColorsRetriever(),
            $this->context->getTranslator()
        );

        $output = array();
        $product_list = array();
       
        $cookie = Context::getContext()->cookie;
        $id_lang = $cookie->id_lang;
        $output['tab_name'] = Configuration::get('TBCMSTABPRODUCTS_FEATURED_PROD_TITLE', $id_lang);

        $output['tab_name_id'] = 'tbcmstab-featured-product';
        $output['tab_name_class_slider'] = 'tbtab-featured-product';
        $output['tab_name_class_pagination'] = 'tbtab-featured';

        $tmp = (int)Configuration::get('TBCMSTABPRODUCTS_FEATURED_PROD_TAB');
        $output['status'] = $tmp;

        foreach ($result->getProducts() as $rawProduct) {
            $product_list[] = $presenter->present(
                $presentationSettings,
                $assembler->assembleProduct($rawProduct),
                $this->context->language
            );
        }

        $output['num_of_prod'] = $nProducts;
        $output['product_list'] = $product_list;

        return $output;
    }

    public function displayNewProducts($num_of_prod = '')
    {
        $newProducts = false;

        $nProducts = (int)Configuration::get('TBCMSTABPRODUCTS_NEW_PROD_NBR');
        if (!empty($num_of_prod)) {
            $nProducts = $num_of_prod;
        }

        if (Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) {
            $newProducts = Product::getNewProducts(
                (int)$this->context->language->id,
                0,
                $nProducts
            );
        }

        $assembler = new ProductAssembler($this->context);

        $presenterFactory = new ProductPresenterFactory($this->context);
        $presentationSettings = $presenterFactory->getPresentationSettings();
        $presenter = new ProductListingPresenter(
            new ImageRetriever(
                $this->context->link
            ),
            $this->context->link,
            new PriceFormatter(),
            new ProductColorsRetriever(),
            $this->context->getTranslator()
        );

        $output = array();
        $product_list = array();

        $cookie = Context::getContext()->cookie;
        $id_lang = $cookie->id_lang;
        $output['tab_name'] = Configuration::get('TBCMSTABPRODUCTS_NEW_PROD_TITLE', $id_lang);

        $output['tab_name_id'] = 'tbcmstab-new-product';
        $output['tab_name_class_slider'] = 'tbtab-new-product';
        $output['tab_name_class_pagination'] = 'tbtab-new';
        
        $tmp = (int)Configuration::get('TBCMSTABPRODUCTS_NEW_PROD_TAB');
        $output['status'] = $tmp;

        if (is_array($newProducts)) {
            foreach ($newProducts as $rawProduct) {
                $product_list[] = $presenter->present(
                    $presentationSettings,
                    $assembler->assembleProduct($rawProduct),
                    $this->context->language
                );
            }
        }

        $output['num_of_prod'] = $nProducts;
        $output['product_list'] = $product_list;

        return $output;
    }

    public function displaySpecialProducts($num_of_prod = '')
    {
        $nProducts = (int)Configuration::get('TBCMSTABPRODUCTS_NEW_PROD_NBR');
        if (!empty($num_of_prod)) {
            $nProducts = $num_of_prod;
        }

        $products = Product::getPricesDrop(
            (int)Context::getContext()->language->id,
            0,
            $nProducts
        );

        $assembler = new ProductAssembler($this->context);

        $presenterFactory = new ProductPresenterFactory($this->context);
        $presentationSettings = $presenterFactory->getPresentationSettings();
        $presenter = new ProductListingPresenter(
            new ImageRetriever(
                $this->context->link
            ),
            $this->context->link,
            new PriceFormatter(),
            new ProductColorsRetriever(),
            $this->context->getTranslator()
        );

        $output = array();
        $product_list = array();

        $cookie = Context::getContext()->cookie;
        $id_lang = $cookie->id_lang;
        $output['tab_name'] = Configuration::get('TBCMSTABPRODUCTS_SPECIAL_PROD_TITLE', $id_lang);

        $output['tab_name_id'] = 'tbcmstab-special-product';
        $output['tab_name_class_slider'] = 'tbtab-special-product';
        $output['tab_name_class_pagination'] = 'tbtab-special';

        $tmp = (int)Configuration::get('TBCMSTABPRODUCTS_SPECIAL_PROD_TAB');
        $output['status'] = $tmp;

        if (is_array($products)) {
            foreach ($products as $rawProduct) {
                $product_list[] = $presenter->present(
                    $presentationSettings,
                    $assembler->assembleProduct($rawProduct),
                    $this->context->language
                );
            }
        }

        $output['num_of_prod'] = $nProducts;
        $output['product_list'] = $product_list;

        return $output;
    }

    public function displayBestSellers($num_of_prod = '')
    {
        $searchProvider = new BestSalesProductSearchProvider(
            $this->context->getTranslator()
        );

        $context = new ProductSearchContext($this->context);

        $query = new ProductSearchQuery();


        $nProducts = (int)Configuration::get('TBCMSTABPRODUCTS_BEST_SELLER_PROD_NBR');
        if (!empty($num_of_prod)) {
            $nProducts = $num_of_prod;
        }

        $query
            ->setResultsPerPage($nProducts)
            ->setPage(1)
        ;

        $query->setSortOrder(SortOrder::random());

        $result = $searchProvider->runQuery(
            $context,
            $query
        );

        $assembler = new ProductAssembler($this->context);

        $presenterFactory = new ProductPresenterFactory($this->context);
        $presentationSettings = $presenterFactory->getPresentationSettings();
        $presenter = new ProductListingPresenter(
            new ImageRetriever(
                $this->context->link
            ),
            $this->context->link,
            new PriceFormatter(),
            new ProductColorsRetriever(),
            $this->context->getTranslator()
        );

        $output = array();
        $product_list = array();
        
        $cookie = Context::getContext()->cookie;
        $id_lang = $cookie->id_lang;
        $output['tab_name'] = Configuration::get('TBCMSTABPRODUCTS_BEST_SELLER_PROD_TITLE', $id_lang);

        $output['tab_name_id'] = 'tbcmstab-best-seller-product';
        $output['tab_name_class_slider'] = 'tbtab-best-seller-product';
        $output['tab_name_class_pagination'] = 'tbtab-best-seller';

        $tmp = (int)Configuration::get('TBCMSTABPRODUCTS_BEST_SELLER_PROD_TAB');
        $output['status'] = $tmp;

        foreach ($result->getProducts() as $rawProduct) {
            $product_list[] = $presenter->present(
                $presentationSettings,
                $assembler->assembleProduct($rawProduct),
                $this->context->language
            );
        }

        $output['num_of_prod'] = $nProducts;
        $output['product_list'] = $product_list;

        return $output;
    }
}
