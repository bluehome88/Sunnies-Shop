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

include_once('classes/tbcmscustomsetting_image_upload.class.php');
include_once('classes/tbcmscustomsetting_status.class.php');
include_once('classes/tbcustomsetting_common_list.class.php');

class TbcmsCustomSetting extends Module
{
    public $id_shop_group = '';
    public $id_shop = '';
    public $hook_linkwidget = 'displayFooterPart1';
    public $is_hook_linkwidget_product = true;
    public $is_hook_linkwidget_our_company = true;
    public function __construct()
    {
        $this->name = 'tbcmscustomsetting';
        $this->tab = 'front_office_features';
        $this->version = '2.1.9';
        $this->author = 'TemplateBeta';
        $this->need_instance = 0;

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('TemplateBeta - Custom Setting');
        $this->description = $this->l('It is use of Custom Setting in TemplateBeta Theme');

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
        $this->module_key = '';

        $this->confirmUninstall = $this->l('Warning: all the data saved in your database will be deleted.'.
            ' Are you sure you want uninstall this module?');

        $this->id_shop_group = (int)Shop::getContextShopGroupID();
        $this->id_shop = (int)Context::getContext()->shop->id;
    }


    public function install()
    {
        $this->installTab();
        $this->createVariable();

        $this->makeInslineStyleSheet();
        $this->makeBodyInslineStyleSheet();
        $this->makeCustomFontStyleSheet();
        Tools::clearSmartyCache();
        
        return parent::install() &&
            $this->registerHook('displayHeader') &&
            $this->registerHook('displayBackOfficeHeader') &&
            // $this->registerHook('displayTopOfferBanner') &&
            $this->registerHook('displayMobileTopOfferText') &&
            $this->registerHook('displayTopOfferText') &&
            // $this->registerHook('displayNav1') &&
            // $this->registerHook('displayNav1sub1') &&
            // $this->registerHook('displayTop') &&
            // $this->registerHook('displayHome') &&
            // $this->registerHook('displayWrapperBottom') &&
            $this->registerHook('displayFooterBefore') &&
            // $this->registerHook('displayFooterAfter') &&
            $this->registerHook('displayDownloadApps') &&
            // $this->registerHook('displayFooterPart1') &&
            // $this->registerHook('displayFooterPart3') &&
            $this->registerHook('displayBackgroundBody') &&
            $this->registerHook('displayCopyRightText');
    }

    public function installTab()
    {
        if (!(int)Tab::getIdFromClassName('AdminTemplateBeta')) {
            $parent_tab = new Tab();
            // Need a foreach for the language
            foreach (Language::getLanguages() as $language) {
                $parent_tab->name[$language['id_lang']] = $this->l('TemplateBeta Extension');
            }
            $parent_tab->class_name = 'AdminTemplateBeta';
            $parent_tab->id_parent = 0; // Home tab
            $parent_tab->module = $this->name;
            $parent_tab->add();
        }
        $tab = new Tab();
        $tab->active = 1;
        foreach (Language::getLanguages() as $language) {
            $tab->name[$language['id_lang']] = $this->l('Custom Setting');
        }
        $tab->class_name = 'Admin'.$this->name;
        $tab->id_parent = (int)Tab::getIdFromClassName('AdminTemplateBeta');
        $tab->module = $this->name;
        $tab->add();
    }

    public function createVariable()
    {
        $this->setLinkWidgetData();
        // Default Variable Change Start
        Configuration::updateValue('PS_NB_DAYS_NEW_PRODUCT', 1000);
        // Default Variable Change End

        $result = array();
        $languages = Language::getLanguages();

        foreach ($languages as $lang) {
            $result['TBCMSCUSTOMSETTING_DOWNLOAD_APPS_TITLE'][$lang['id_lang']] = 'Download Master App Now';
            $result['TBCMSCUSTOMSETTING_DOWNLOAD_APPS_SUB_TITLE'][$lang['id_lang']] = 'Fast, Simple & '
                .'Delightful. All It takes is 30 Seconds to Download.';
            $result['TBCMSCUSTOMSETTING_DOWNLOAD_APPS_DESC'][$lang['id_lang']] = 'Fast, Simple & Delightful. All It '
                .'takes is 30 Seconds to Download.';
            $result['TBCMSCUSTOMSETTING_DOWNLOAD_APPS_APPLE'][$lang['id_lang']] = '#';
            $result['TBCMSCUSTOMSETTING_DOWNLOAD_APPS_GOOLGE'][$lang['id_lang']] = '#';
            $result['TBCMSCUSTOMSETTING_DOWNLOAD_APPS_MICROSOFT'][$lang['id_lang']] = '#';

            $result['TBCMSCUSTOMSETTING_FOOTER_TAB_FEATURED_PROD_TITLE'][$lang['id_lang']] = 'Featured Product';
            $result['TBCMSCUSTOMSETTING_FOOTER_TAB_NEW_PROD_TITLE'][$lang['id_lang']] = 'New Product';
            $result['TBCMSCUSTOMSETTING_FOOTER_TAB_BEST_SELLER_PROD_TITLE'][$lang['id_lang']] = 'Best Product';
            $result['TBCMSCUSTOMSETTING_NEWSLETTER_TITLE'][$lang['id_lang']] = 'Subscribe To Our Newsletter';
            $result['TBCMSCUSTOMSETTING_NEWSLETTER_SHORT_DESC'][$lang['id_lang']] = 'Sign up for our newletter '
                .'to recevie updates an exlusive offers';

            $result['TBCMSCUSTOMSETTING_SOCIAL_ICON_TITLE'][$lang['id_lang']] = 'Social';
            $result['TBCMSCUSTOMSETTING_SOCIAL_ICON_SHORT_DESC'][$lang['id_lang']] = 'Social Icon Short Desc';

            $tmp = 'Brand New Products Upto 60% Off';
            $result['TBCMSCUSTOMSETTING_CUSTOM_TEXT'][$lang['id_lang']] = $tmp;
            $tmp = '© 2019 - Ecommerce software by PrestaShop™';
            $result['TBCMSCUSTOMSETTING_COPY_RIGHT_TEXT'][$lang['id_lang']] = $tmp;
            $result['TBCMSCUSTOMSETTING_COPY_RIGHT_LINK'][$lang['id_lang']] = '#';
        }

        // App Links
        Configuration::updateValue('TBCMSCUSTOMSETTING_DOWNLOAD_APPS_IMAGE', 'demo_img_1.png');
        $tmp = $result['TBCMSCUSTOMSETTING_DOWNLOAD_APPS_TITLE'];
        Configuration::updateValue('TBCMSCUSTOMSETTING_DOWNLOAD_APPS_TITLE', $tmp);
        $tmp = $result['TBCMSCUSTOMSETTING_DOWNLOAD_APPS_SUB_TITLE'];
        Configuration::updateValue('TBCMSCUSTOMSETTING_DOWNLOAD_APPS_SUB_TITLE', $tmp);
        $tmp = $result['TBCMSCUSTOMSETTING_DOWNLOAD_APPS_DESC'];
        Configuration::updateValue('TBCMSCUSTOMSETTING_DOWNLOAD_APPS_DESC', $tmp);
        $tmp = $result['TBCMSCUSTOMSETTING_DOWNLOAD_APPS_APPLE'];
        Configuration::updateValue('TBCMSCUSTOMSETTING_DOWNLOAD_APPS_APPLE', $tmp);
        $tmp = $result['TBCMSCUSTOMSETTING_DOWNLOAD_APPS_GOOLGE'];
        Configuration::updateValue('TBCMSCUSTOMSETTING_DOWNLOAD_APPS_GOOLGE', $tmp);
        $tmp = $result['TBCMSCUSTOMSETTING_DOWNLOAD_APPS_MICROSOFT'];
        Configuration::updateValue('TBCMSCUSTOMSETTING_DOWNLOAD_APPS_MICROSOFT', $tmp);
        Configuration::updateValue('TBCMSCUSTOMSETTING_DOWNLOAD_APPS_STATUS', 1);

        // Main Menu
        Configuration::updateValue('TBCMSCUSTOMSETTING_MAIN_MENU_STICKY', 1);

        // Bottom Sticky
        Configuration::updateValue('TBCMSCUSTOMSETTING_BOTTOM_OPTION', 0);

        // Vertical Menu is Default show
        Configuration::updateValue('TBCMSCUSTOMSETTING_VERTICAL_MENU_OPEN', 0);

        // Copy Right text Footer
        
        $tmp = $result['TBCMSCUSTOMSETTING_CUSTOM_TEXT'];
        Configuration::updateValue('TBCMSCUSTOMSETTING_CUSTOM_TEXT', $tmp);
        Configuration::updateValue('TBCMSCUSTOMSETTING_CUSTOM_TEXT_STATUS', 1);

        $tmp = $result['TBCMSCUSTOMSETTING_COPY_RIGHT_TEXT'];
        Configuration::updateValue('TBCMSCUSTOMSETTING_COPY_RIGHT_TEXT', $tmp);
        $tmp = $result['TBCMSCUSTOMSETTING_COPY_RIGHT_LINK'];
        Configuration::updateValue('TBCMSCUSTOMSETTING_COPY_RIGHT_LINK', $tmp);
        Configuration::updateValue('TBCMSCUSTOMSETTING_COPY_RIGHT_TEXT_STATUS', 1);

        // Create Globle Variables
        Configuration::updateValue('TBCMSCUSTOMSETTING_ADD_CONTAINER', 0);
        Configuration::updateValue('TBCMSCUSTOMSETTING_PAGE_LOADER', 1);
        Configuration::updateValue('TBCMSCUSTOMSETTING_ANIMATION_CSS', 1);
        Configuration::updateValue('TBCMSCUSTOMSETTING_HOVER_IMG', 1);
        Configuration::updateValue('TBCMSCUSTOMSETTING_TAB_PRODUCT_ROW', 0);
        Configuration::updateValue('TBCMSCUSTOMSETTING_PRODUCT_COLOR', 0);
        Configuration::updateValue('TBCMSCUSTOMSETTING_PRODUCT_LIST_VIEW', 'grid');

        // Theme Option
        Configuration::updateValue('TBCMSCUSTOMSETTING_THEME_OPTION', '', true);
        Configuration::updateValue('TBCMSCUSTOMSETTING_THEME_COLOR_1', '#ffffff');
        Configuration::updateValue('TBCMSCUSTOMSETTING_THEME_COLOR_2', '#ffffff');
        Configuration::updateValue('TBCMSCUSTOMSETTING_BACKGROUND_COLOR', '#ffffff');
        Configuration::updateValue('TBCMSCUSTOMSETTING_BACKGROUND_PATTERN', 'pattern1');
        Configuration::updateValue('TBCMSCUSTOMSETTING_BACKGROUND_OLD_PATTERN', 'no_pattern.png');
        Configuration::updateValue('TBCMSCUSTOMSETTING_BACKGROUND_IMAGE_PATTERN_STATUS', 'pattern');
        Configuration::updateValue('TBCMSCUSTOMSETTING_BACKGROUND_STYLE_SHEET', '');
        Configuration::updateValue('TBCMSFRONTSIDE_THEME_SETTING_SHOW', '1');
        Configuration::updateValue('TBCMSCUSTOMSETTING_BACKGROUND_IMAGE_REPEAT', 'repeat');
        Configuration::updateValue('TBCMSCUSTOMSETTING_BACKGROUND_IMAGE_ATTACHMENT', 'fixed');



        Configuration::updateValue('TBCMSCUSTOMSETTING_CUSTOM_FONT_TITLE_COLOR_STATUS', 0);
        Configuration::updateValue('TBCMSCUSTOMSETTING_THEME_FONT_TYPE', 'Cabin');
        Configuration::updateValue('TBCMSCUSTOMSETTING_THEME_FONT_COLOR', '#ffffff');
        Configuration::updateValue('TBCMSCUSTOMSETTING_THEME_FONT_TYPE_2', 'Chivo');
        Configuration::updateValue('TBCMSCUSTOMSETTING_BODY_BACKGROUND_COLOR_STATUS', '0');
        Configuration::updateValue('TBCMSCUSTOMSETTING_BODY_BACKGROUND_IMAGE_PATTERN_STATUS', 'color');
        Configuration::updateValue('TBCMSCUSTOMSETTING_BODY_BACKGROUND_COLOR', '#ffffff');
        Configuration::updateValue('TBCMSCUSTOMSETTING_BODY_BACKGROUND_PATTERN', 'pattern1');
        Configuration::updateValue('TBCMSCUSTOMSETTING_BODY_BACKGROUND_OLD_PATTERN', 'no_pattern.png');
        Configuration::updateValue('TBCMSCUSTOMSETTING_BODY_BACKGROUND_IMAGE_REPEAT', 'repeat');
        Configuration::updateValue('TBCMSCUSTOMSETTING_BODY_BACKGROUND_IMAGE_ATTACHMENT', 'fixed');
        Configuration::updateValue('TBCMSCUSTOMSETTING_BODY_BACKGROUND_STYLE_SHEET', '');
        Configuration::updateValue('TBCMSCUSTOMSETTING_THEME_CUSTOM_FILE_LINK', '', true);


        // Custom Title
        Configuration::updateValue('TBCMSCUSTOMSETTING_FOOTER_TAB_NUM_PROD', '1');
        Configuration::updateValue('TBCMSCUSTOMSETTING_FOOTER_TAB_STATUS', 1);
        $tmp = $result['TBCMSCUSTOMSETTING_FOOTER_TAB_FEATURED_PROD_TITLE'];
        Configuration::updateValue('TBCMSCUSTOMSETTING_FOOTER_TAB_FEATURED_PROD_TITLE', $tmp);
        $tmp = $result['TBCMSCUSTOMSETTING_FOOTER_TAB_NEW_PROD_TITLE'];
        Configuration::updateValue('TBCMSCUSTOMSETTING_FOOTER_TAB_NEW_PROD_TITLE', $tmp);
        $tmp = $result['TBCMSCUSTOMSETTING_FOOTER_TAB_BEST_SELLER_PROD_TITLE'];
        Configuration::updateValue('TBCMSCUSTOMSETTING_FOOTER_TAB_BEST_SELLER_PROD_TITLE', $tmp);
        $tmp = $result['TBCMSCUSTOMSETTING_NEWSLETTER_TITLE'];
        Configuration::updateValue('TBCMSCUSTOMSETTING_NEWSLETTER_TITLE', $tmp);
        $tmp = $result['TBCMSCUSTOMSETTING_NEWSLETTER_SHORT_DESC'];
        Configuration::updateValue('TBCMSCUSTOMSETTING_NEWSLETTER_SHORT_DESC', $tmp);
        $tmp = $result['TBCMSCUSTOMSETTING_SOCIAL_ICON_TITLE'];
        Configuration::updateValue('TBCMSCUSTOMSETTING_SOCIAL_ICON_TITLE', $tmp);
        $tmp = $result['TBCMSCUSTOMSETTING_SOCIAL_ICON_SHORT_DESC'];
        Configuration::updateValue('TBCMSCUSTOMSETTING_SOCIAL_ICON_SHORT_DESC', $tmp);
     
        Configuration::updateValue('CustomThemePath', _THEME_IMG_DIR_);
    }

    public function setLinkWidgetData()
    {
        $hook_id = (int)Hook::getIdByName($this->hook_linkwidget);
        if ($hook_id == 0) {
            $max_register_hook_id = 'SELECT MAX(id_hook) as id FROM  `'._DB_PREFIX_.'hook`;';
            $result = Db::getInstance()->executeS($max_register_hook_id);
            $max_id = $result[0]['id'];
            $hook_id = $max_id + 1;

            $register_hook = 'INSERT INTO `'._DB_PREFIX_.'hook` (`id_hook`, `name`, `title`, `description`, `position`)
                VALUES ('.$hook_id.', \''.$this->hook_linkwidget.'\', \''.$this->hook_linkwidget.'\', \'\', \'1\');';
            Db::getInstance()->execute($register_hook);
        }

        // $hook_id = (int)Hook::getIdByName($this->hook_linkwidget);
        $queries = array();
        $queries[] = 'TRUNCATE TABLE `'._DB_PREFIX_.'link_block`;';
        $queries[] = 'TRUNCATE TABLE `'._DB_PREFIX_.'link_block_lang`;';


        if ($this->is_hook_linkwidget_product == true) {
            $queries[] = 'INSERT INTO `'._DB_PREFIX_.'link_block`
                (`id_link_block`, `id_hook`, `position`, `content`) VALUES
                    (1, '.$hook_id.', 1, \'{"cms":[false],
                        "product":["prices-drop","new-products","best-sales"],"static":[false]}\');';
        }

        if ($this->is_hook_linkwidget_our_company == true) {
            $queries[] = 'INSERT INTO `'._DB_PREFIX_.'link_block` 
                (`id_link_block`, `id_hook`, `position`, `content`) VALUES
                    (2, '.$hook_id.', 2, \'{"cms":["1","2","3","4","5"],
                        "product":[false],"static":["contact","sitemap","stores"]}\');';
        }

        foreach (Language::getLanguages(true, Context::getContext()->shop->id) as $lang) {
            if ($this->is_hook_linkwidget_product == true) {
                $queries[] = 'INSERT INTO `'._DB_PREFIX_.'link_block_lang`
                    (`id_link_block`, `id_lang`, `name`) VALUES
                    (1, '.(int)$lang['id_lang'].', "Products");';
            }

            if ($this->is_hook_linkwidget_our_company == true) {
                $queries[] = 'INSERT INTO `'._DB_PREFIX_.'link_block_lang`
                    (`id_link_block`, `id_lang`, `name`) VALUES
                    (2, '.(int)$lang['id_lang'].', "Our company");';
            }
        }

        foreach ($queries as $query) {
            Db::getInstance()->execute($query);
        }
    }

    public function uninstall()
    {
        Tools::clearSmartyCache();
        $this->uninstallTab();
        $this->deleteVariable();
        return parent::uninstall();
    }

    public function deleteVariable()
    {
        // App Links
        Configuration::deleteByName('TBCMSCUSTOMSETTING_DOWNLOAD_APPS_IMAGE');
        Configuration::deleteByName('TBCMSCUSTOMSETTING_DOWNLOAD_APPS_TITLE');
        Configuration::deleteByName('TBCMSCUSTOMSETTING_DOWNLOAD_APPS_SUB_TITLE');
        Configuration::deleteByName('TBCMSCUSTOMSETTING_DOWNLOAD_APPS_DESC');
        Configuration::deleteByName('TBCMSCUSTOMSETTING_DOWNLOAD_APPS_APPLE');
        Configuration::deleteByName('TBCMSCUSTOMSETTING_DOWNLOAD_APPS_GOOLGE');
        Configuration::deleteByName('TBCMSCUSTOMSETTING_DOWNLOAD_APPS_MICROSOFT');
        Configuration::deleteByName('TBCMSCUSTOMSETTING_DOWNLOAD_APPS_STATUS');

        // Main Menu Sticky
        Configuration::deleteByName('TBCMSCUSTOMSETTING_MAIN_MENU_STICKY');

        // Bottom Sticky
        Configuration::deleteByName('TBCMSCUSTOMSETTING_BOTTOM_OPTION');

        // Vertical Menu is Default show
        Configuration::deleteByName('TBCMSCUSTOMSETTING_VERTICAL_MENU_OPEN');

        // Copy Right text Footer

        Configuration::deleteByName('TBCMSCUSTOMSETTING_CUSTOM_TEXT');
        Configuration::deleteByName('TBCMSCUSTOMSETTING_CUSTOM_TEXT_STATUS');
        Configuration::deleteByName('TBCMSCUSTOMSETTING_COPY_RIGHT_TEXT');
        Configuration::deleteByName('TBCMSCUSTOMSETTING_COPY_RIGHT_LINK');
        Configuration::deleteByName('TBCMSCUSTOMSETTING_COPY_RIGHT_TEXT_STATUS');

        // Create Globle Variables
        Configuration::deleteByName('TBCMSCUSTOMSETTING_ADD_CONTAINER');
        Configuration::deleteByName('TBCMSCUSTOMSETTING_PAGE_LOADER');
        Configuration::deleteByName('TBCMSCUSTOMSETTING_ANIMATION_CSS');
        Configuration::deleteByName('TBCMSCUSTOMSETTING_HOVER_IMG');
        Configuration::deleteByName('TBCMSCUSTOMSETTING_TAB_PRODUCT_ROW');
        Configuration::deleteByName('TBCMSCUSTOMSETTING_PRODUCT_COLOR');
        Configuration::deleteByName('TBCMSCUSTOMSETTING_PRODUCT_LIST_VIEW');


        // Theme Option
        Configuration::deleteByName('TBCMSCUSTOMSETTING_THEME_OPTION');
        Configuration::deleteByName('TBCMSCUSTOMSETTING_THEME_COLOR_1');
        Configuration::deleteByName('TBCMSCUSTOMSETTING_THEME_COLOR_2');
        Configuration::deleteByName('TBCMSCUSTOMSETTING_BACKGROUND_COLOR');
        Configuration::deleteByName('TBCMSCUSTOMSETTING_BACKGROUND_PATTERN');
        Configuration::deleteByName('TBCMSCUSTOMSETTING_BACKGROUND_OLD_PATTERN');
        Configuration::deleteByName('TBCMSCUSTOMSETTING_BACKGROUND_IMAGE_PATTERN_STATUS');
        Configuration::deleteByName('TBCMSCUSTOMSETTING_BACKGROUND_STYLE_SHEET');
        Configuration::deleteByName('TBCMSFRONTSIDE_THEME_SETTING_SHOW');
        Configuration::deleteByName('TBCMSCUSTOMSETTING_BACKGROUND_IMAGE_REPEAT');
        Configuration::deleteByName('TBCMSCUSTOMSETTING_BACKGROUND_IMAGE_ATTACHMENT');
        Configuration::deleteByName('TBCMSCUSTOMSETTING_BODY_BACKGROUND_COLOR_STATUS');
        Configuration::deleteByName('TBCMSCUSTOMSETTING_BODY_BACKGROUND_IMAGE_PATTERN_STATUS');
        Configuration::deleteByName('TBCMSCUSTOMSETTING_BODY_BACKGROUND_COLOR');
        Configuration::deleteByName('TBCMSCUSTOMSETTING_BODY_BACKGROUND_PATTERN');
        Configuration::deleteByName('TBCMSCUSTOMSETTING_BODY_BACKGROUND_OLD_PATTERN');
        Configuration::deleteByName('TBCMSCUSTOMSETTING_BODY_BACKGROUND_IMAGE_REPEAT');
        Configuration::deleteByName('TBCMSCUSTOMSETTING_BODY_BACKGROUND_IMAGE_ATTACHMENT');
        Configuration::deleteByName('TBCMSCUSTOMSETTING_BODY_BACKGROUND_STYLE_SHEET');
        Configuration::deleteByName('TBCMSCUSTOMSETTING_CUSTOM_FONT_TITLE_COLOR_STATUS');
        Configuration::deleteByName('TBCMSCUSTOMSETTING_THEME_FONT_TYPE');
        Configuration::deleteByName('TBCMSCUSTOMSETTING_THEME_FONT_COLOR');
        Configuration::deleteByName('TBCMSCUSTOMSETTING_THEME_FONT_TYPE_2');

        Configuration::deleteByName('TBCMSCUSTOMSETTING_THEME_CUSTOM_FILE_LINK');

        // Footer Tab Product
        Configuration::deleteByName('TBCMSCUSTOMSETTING_FOOTER_TAB_NUM_PROD');
        Configuration::deleteByName('TBCMSCUSTOMSETTING_FOOTER_TAB_STATUS');
        Configuration::deleteByName('TBCMSCUSTOMSETTING_FOOTER_TAB_FEATURED_PROD_TITLE');
        Configuration::deleteByName('TBCMSCUSTOMSETTING_FOOTER_TAB_NEW_PROD_TITLE');
        Configuration::deleteByName('TBCMSCUSTOMSETTING_FOOTER_TAB_BEST_SELLER_PROD_TITLE');
        Configuration::deleteByName('TBCMSCUSTOMSETTING_NEWSLETTER_TITLE');
        Configuration::deleteByName('TBCMSCUSTOMSETTING_NEWSLETTER_SHORT_DESC');
        Configuration::deleteByName('TBCMSCUSTOMSETTING_SOCIAL_ICON_TITLE');
        Configuration::deleteByName('TBCMSCUSTOMSETTING_SOCIAL_ICON_SHORT_DESC');
    }

    public function uninstallTab()
    {
        $id_tab = Tab::getIdFromClassName('Admin'.$this->name);
        $tab = new Tab($id_tab);
        $tab->delete();
        return true;
    }

    public function formShow()
    {
        $tbcms_obj = new TbcmsCustomSettingStatus();
        $show_fields = $tbcms_obj->fieldStatusInformation();

        $this->context->smarty->assign('tab_number', '#fieldset_0');

        if (!$show_fields['form_1']) {
            $this->context->smarty->assign('tab_number', '#fieldset_1_1');
        }

        if (!$show_fields['form_1'] && !$show_fields['form_2']) {
            $this->context->smarty->assign('tab_number', '#fieldset_2_2');
        }

        if (!$show_fields['form_1'] && !$show_fields['form_2'] && !$show_fields['form_3']) {
            $this->context->smarty->assign('tab_number', '#fieldset_3_3');
        }

        $this->context->smarty->assign('show_fields', $show_fields);
    }

    public function getContent()
    {
        
        $message = '';
        // check which form is not show
        $this->formShow();
        $message = $this->postProcess();
        $output = $message
                    .'<div class="tbcmsadmincustom-setting">'
                        .$this->display(__FILE__, 'views/templates/admin/index.tpl')
                        .$this->renderForm()
                    .'</div>';
        return $output;
    }

    public function postProcess()
    {
        $message = '';
        $languages = Language::getLanguages();
        $result = array();

        if (Tools::isSubmit('submitTbcmsThemeOptionForm')) {
            if ($_FILES['tbcmscustomsetting_custom_pattern']) {
                $old_pattern = Configuration::get('TBCMSCUSTOMSETTING_BACKGROUND_OLD_PATTERN');
                $this->obj_image = new TbcmsCustomSettingImageUpload();
                $ans = $this->obj_image->imageUploading($_FILES['tbcmscustomsetting_custom_pattern'], $old_pattern);
                if ($ans['success']) {
                    $file_name = $ans['name'];
                    Configuration::updateValue('TBCMSCUSTOMSETTING_BACKGROUND_OLD_PATTERN', $file_name);
                } else {
                    $old_pattern = Configuration::get('TBCMSCUSTOMSETTING_BACKGROUND_OLD_PATTERN');
                    Configuration::updateValue('TBCMSCUSTOMSETTING_BACKGROUND_OLD_PATTERN', $old_pattern);
                }
            }

            if ($_FILES['tbcmscustomsetting_custom_body_pattern']) {
                $old_pattern = Configuration::get('TBCMSCUSTOMSETTING_BODY_BACKGROUND_OLD_PATTERN');
                $this->obj_image = new TbcmsCustomSettingImageUpload();
                $ans = $this->obj_image->imageUploading(
                    $_FILES['tbcmscustomsetting_custom_body_pattern'],
                    $old_pattern
                );
                if ($ans['success']) {
                    $file_name = $ans['name'];
                    Configuration::updateValue('TBCMSCUSTOMSETTING_BODY_BACKGROUND_OLD_PATTERN', $file_name);
                } else {
                    $old_pattern = Configuration::get('TBCMSCUSTOMSETTING_BODY_BACKGROUND_OLD_PATTERN');
                    Configuration::updateValue('TBCMSCUSTOMSETTING_BODY_BACKGROUND_OLD_PATTERN', $old_pattern);
                }
            }

            $tmp = Tools::getValue('TBCMSFRONTSIDE_THEME_SETTING_SHOW');
            if ($tmp != Configuration::get('TBCMSFRONTSIDE_THEME_SETTING_SHOW')) {
                Configuration::updateValue('TBCMSFRONTSIDE_THEME_SETTING_SHOW', $tmp);

                if (Configuration::get('TBCMSFRONTSIDE_THEME_SETTING_SHOW')) {
                    Module::enableByName('tbcmsthemeoptions');
                } else {
                    Module::disableByName('tbcmsthemeoptions');
                }
            }

            $tmp = Tools::getValue('TBCMSCUSTOMSETTING_BACKGROUND_IMAGE_REPEAT');
            if ($tmp != Configuration::get('TBCMSCUSTOMSETTING_BACKGROUND_IMAGE_REPEAT')) {
                Configuration::updateValue('TBCMSCUSTOMSETTING_BACKGROUND_IMAGE_REPEAT', $tmp);
            }

            $tmp = Tools::getValue('TBCMSCUSTOMSETTING_BACKGROUND_IMAGE_ATTACHMENT');
            if ($tmp != Configuration::get('TBCMSCUSTOMSETTING_BACKGROUND_IMAGE_ATTACHMENT')) {
                Configuration::updateValue('TBCMSCUSTOMSETTING_BACKGROUND_IMAGE_ATTACHMENT', $tmp);
            }

            $tmp = Tools::getValue('TBCMSCUSTOMSETTING_THEME_OPTION');
            if ($tmp != Configuration::get('TBCMSCUSTOMSETTING_THEME_OPTION')) {
                Configuration::updateValue('TBCMSCUSTOMSETTING_THEME_OPTION', $tmp);
            }
            
            $tmp = Tools::getValue('TBCMSCUSTOMSETTING_THEME_COLOR_1');
            if ($tmp != Configuration::get('TBCMSCUSTOMSETTING_THEME_COLOR_1')) {
                Configuration::updateValue('TBCMSCUSTOMSETTING_THEME_COLOR_1', $tmp);
            }

            $tmp = Tools::getValue('TBCMSCUSTOMSETTING_THEME_COLOR_2');
            if ($tmp != Configuration::get('TBCMSCUSTOMSETTING_THEME_COLOR_2')) {
                Configuration::updateValue('TBCMSCUSTOMSETTING_THEME_COLOR_2', $tmp);
            }

            $tmp = Tools::getValue('TBCMSCUSTOMSETTING_BACKGROUND_COLOR');
            if ($tmp != Configuration::get('TBCMSCUSTOMSETTING_BACKGROUND_COLOR')) {
                Configuration::updateValue('TBCMSCUSTOMSETTING_BACKGROUND_COLOR', $tmp);
            }
            
            $tmp = Tools::getValue('tbcmscustomsetting_pattern');
            if ($tmp != Configuration::get('TBCMSCUSTOMSETTING_BACKGROUND_PATTERN')) {
                Configuration::updateValue('TBCMSCUSTOMSETTING_BACKGROUND_PATTERN', $tmp);
            }
            
            $tmp = Tools::getValue('TBCMSCUSTOMSETTING_BACKGROUND_IMAGE_PATTERN_STATUS');
            if ($tmp != Configuration::get('TBCMSCUSTOMSETTING_BACKGROUND_IMAGE_PATTERN_STATUS')) {
                Configuration::updateValue('TBCMSCUSTOMSETTING_BACKGROUND_IMAGE_PATTERN_STATUS', $tmp);
            }

            $tmp = Tools::getValue('TBCMSCUSTOMSETTING_ADD_CONTAINER');
            if ($tmp != Configuration::get('TBCMSCUSTOMSETTING_ADD_CONTAINER')) {
                Configuration::updateValue('TBCMSCUSTOMSETTING_ADD_CONTAINER', $tmp);
            }

            $tmp = Tools::getValue('TBCMSCUSTOMSETTING_BODY_BACKGROUND_COLOR_STATUS');
            if ($tmp != Configuration::get('TBCMSCUSTOMSETTING_BODY_BACKGROUND_COLOR_STATUS')) {
                Configuration::updateValue('TBCMSCUSTOMSETTING_BODY_BACKGROUND_COLOR_STATUS', $tmp);
            }

            $tmp = Tools::getValue('TBCMSCUSTOMSETTING_BODY_BACKGROUND_IMAGE_PATTERN_STATUS');
            if ($tmp != Configuration::get('TBCMSCUSTOMSETTING_BODY_BACKGROUND_IMAGE_PATTERN_STATUS')) {
                Configuration::updateValue('TBCMSCUSTOMSETTING_BODY_BACKGROUND_IMAGE_PATTERN_STATUS', $tmp);
            }

            $tmp = Tools::getValue('TBCMSCUSTOMSETTING_BODY_BACKGROUND_COLOR');
            if ($tmp != Configuration::get('TBCMSCUSTOMSETTING_BODY_BACKGROUND_COLOR')) {
                Configuration::updateValue('TBCMSCUSTOMSETTING_BODY_BACKGROUND_COLOR', $tmp);
            }

            $tmp = Tools::getValue('tbcmscustomsetting_body_pattern');
            if ($tmp != Configuration::get('TBCMSCUSTOMSETTING_BODY_BACKGROUND_PATTERN')) {
                Configuration::updateValue('TBCMSCUSTOMSETTING_BODY_BACKGROUND_PATTERN', $tmp);
            }

            $tmp = Tools::getValue('TBCMSCUSTOMSETTING_BODY_BACKGROUND_IMAGE_REPEAT');
            if ($tmp != Configuration::get('TBCMSCUSTOMSETTING_BODY_BACKGROUND_IMAGE_REPEAT')) {
                Configuration::updateValue('TBCMSCUSTOMSETTING_BODY_BACKGROUND_IMAGE_REPEAT', $tmp);
            }

            $tmp = Tools::getValue('TBCMSCUSTOMSETTING_BODY_BACKGROUND_IMAGE_ATTACHMENT');
            if ($tmp != Configuration::get('TBCMSCUSTOMSETTING_BODY_BACKGROUND_IMAGE_ATTACHMENT')) {
                Configuration::updateValue('TBCMSCUSTOMSETTING_BODY_BACKGROUND_IMAGE_ATTACHMENT', $tmp);
            }

            $tmp = Tools::getValue('TBCMSCUSTOMSETTING_CUSTOM_FONT_TITLE_COLOR_STATUS');
            if ($tmp != Configuration::get('TBCMSCUSTOMSETTING_CUSTOM_FONT_TITLE_COLOR_STATUS')) {
                Configuration::updateValue('TBCMSCUSTOMSETTING_CUSTOM_FONT_TITLE_COLOR_STATUS', $tmp);
            }

            $tmp = Tools::getValue('TBCMSCUSTOMSETTING_THEME_FONT_TYPE');
            if ($tmp != Configuration::get('TBCMSCUSTOMSETTING_THEME_FONT_TYPE')) {
                Configuration::updateValue('TBCMSCUSTOMSETTING_THEME_FONT_TYPE', $tmp);
            }

            $tmp = Tools::getValue('TBCMSCUSTOMSETTING_THEME_FONT_COLOR');
            if ($tmp != Configuration::get('TBCMSCUSTOMSETTING_THEME_FONT_COLOR')) {
                Configuration::updateValue('TBCMSCUSTOMSETTING_THEME_FONT_COLOR', $tmp);
            }

            $tmp = Tools::getValue('TBCMSCUSTOMSETTING_THEME_FONT_TYPE_2');
            if ($tmp != Configuration::get('TBCMSCUSTOMSETTING_THEME_FONT_TYPE_2')) {
                Configuration::updateValue('TBCMSCUSTOMSETTING_THEME_FONT_TYPE_2', $tmp);
            }
            
            $tmp = Tools::getValue('TBCMSCUSTOMSETTING_PAGE_LOADER');
            if ($tmp != Configuration::get('TBCMSCUSTOMSETTING_PAGE_LOADER')) {
                Configuration::updateValue('TBCMSCUSTOMSETTING_PAGE_LOADER', $tmp);
            }
            
            $tmp = Tools::getValue('TBCMSCUSTOMSETTING_ANIMATION_CSS');
            if ($tmp != Configuration::get('TBCMSCUSTOMSETTING_ANIMATION_CSS')) {
                Configuration::updateValue('TBCMSCUSTOMSETTING_ANIMATION_CSS', $tmp);
            }

            $tmp = Tools::getValue('TBCMSCUSTOMSETTING_HOVER_IMG');
            if ($tmp != Configuration::get('TBCMSCUSTOMSETTING_HOVER_IMG')) {
                Configuration::updateValue('TBCMSCUSTOMSETTING_HOVER_IMG', $tmp);
            }

            $tmp = Tools::getValue('TBCMSCUSTOMSETTING_TAB_PRODUCT_ROW');
            if ($tmp != Configuration::get('TBCMSCUSTOMSETTING_TAB_PRODUCT_ROW')) {
                Configuration::updateValue('TBCMSCUSTOMSETTING_TAB_PRODUCT_ROW', $tmp);
            }

            $tmp = Tools::getValue('TBCMSCUSTOMSETTING_PRODUCT_COLOR');
            if ($tmp != Configuration::get('TBCMSCUSTOMSETTING_PRODUCT_COLOR')) {
                Configuration::updateValue('TBCMSCUSTOMSETTING_PRODUCT_COLOR', $tmp);
            }

            $tmp = Tools::getValue('TBCMSCUSTOMSETTING_PRODUCT_LIST_VIEW');
            if ($tmp != Configuration::get('TBCMSCUSTOMSETTING_PRODUCT_LIST_VIEW')) {
                Configuration::updateValue('TBCMSCUSTOMSETTING_PRODUCT_LIST_VIEW', $tmp);
            }

            $tmp = Tools::getValue('TBCMSCUSTOMSETTING_MAIN_MENU_STICKY');
            if ($tmp != Configuration::get('TBCMSCUSTOMSETTING_MAIN_MENU_STICKY')) {
                Configuration::updateValue('TBCMSCUSTOMSETTING_MAIN_MENU_STICKY', $tmp);
            }

            $tmp = Tools::getValue('TBCMSCUSTOMSETTING_BOTTOM_OPTION');
            if ($tmp != Configuration::get('TBCMSCUSTOMSETTING_BOTTOM_OPTION')) {
                Configuration::updateValue('TBCMSCUSTOMSETTING_BOTTOM_OPTION', $tmp);
            }

            $tmp = Tools::getValue('TBCMSCUSTOMSETTING_VERTICAL_MENU_OPEN');
            if ($tmp != Configuration::get('TBCMSCUSTOMSETTING_VERTICAL_MENU_OPEN')) {
                Configuration::updateValue('TBCMSCUSTOMSETTING_VERTICAL_MENU_OPEN', $tmp);
            }

            $this->makeInslineStyleSheet();
            $this->makeBodyInslineStyleSheet();
            $this->makeCustomFontStyleSheet();
            $this->context->smarty->assign('tab_number', '#fieldset_0');
            $message .= $this->displayConfirmation($this->l("Theme Configuration is Updates"));
        }

        if (Tools::isSubmit('submitTbcmsAppLinkForm')) {
            if ($_FILES['TBCMSCUSTOMSETTING_DOWNLOAD_APPS_IMAGE']) {
                $old_img = Configuration::get('TBCMSCUSTOMSETTING_DOWNLOAD_APPS_IMAGE');
                $this->obj_image = new TbcmsCustomSettingImageUpload();
                $ans = $this->obj_image->imageUploading($_FILES['TBCMSCUSTOMSETTING_DOWNLOAD_APPS_IMAGE'], $old_img);
                if ($ans['success']) {
                    $file_name = $ans['name'];
                    Configuration::updateValue('TBCMSCUSTOMSETTING_DOWNLOAD_APPS_IMAGE', $file_name);
                } else {
                    $old_img = Configuration::get('TBCMSCUSTOMSETTING_DOWNLOAD_APPS_IMAGE');
                    Configuration::updateValue('TBCMSCUSTOMSETTING_DOWNLOAD_APPS_IMAGE', $old_img);
                }
            }

            foreach ($languages as $lang) {
                $tmp = Tools::getValue('TBCMSCUSTOMSETTING_DOWNLOAD_APPS_TITLE_'.$lang['id_lang']);
                $result['TBCMSCUSTOMSETTING_DOWNLOAD_APPS_TITLE'][$lang['id_lang']] = $tmp;
                $tmp = Tools::getValue('TBCMSCUSTOMSETTING_DOWNLOAD_APPS_SUB_TITLE_'.$lang['id_lang']);
                $result['TBCMSCUSTOMSETTING_DOWNLOAD_APPS_SUB_TITLE'][$lang['id_lang']] = $tmp;
                $tmp = Tools::getValue('TBCMSCUSTOMSETTING_DOWNLOAD_APPS_DESC_'.$lang['id_lang']);
                $result['TBCMSCUSTOMSETTING_DOWNLOAD_APPS_DESC'][$lang['id_lang']] = $tmp;
                $tmp = Tools::getValue('TBCMSCUSTOMSETTING_DOWNLOAD_APPS_APPLE_'.$lang['id_lang']);
                $result['TBCMSCUSTOMSETTING_DOWNLOAD_APPS_APPLE'][$lang['id_lang']] = $tmp;
                $tmp = Tools::getValue('TBCMSCUSTOMSETTING_DOWNLOAD_APPS_GOOLGE_'.$lang['id_lang']);
                $result['TBCMSCUSTOMSETTING_DOWNLOAD_APPS_GOOLGE'][$lang['id_lang']] = $tmp;
                $tmp = Tools::getValue('TBCMSCUSTOMSETTING_DOWNLOAD_APPS_MICROSOFT_'.$lang['id_lang']);
                $result['TBCMSCUSTOMSETTING_DOWNLOAD_APPS_MICROSOFT'][$lang['id_lang']] = $tmp;
            }

            $tmp = $result['TBCMSCUSTOMSETTING_DOWNLOAD_APPS_TITLE'];
            Configuration::updateValue('TBCMSCUSTOMSETTING_DOWNLOAD_APPS_TITLE', $tmp);
            
            $tmp = $result['TBCMSCUSTOMSETTING_DOWNLOAD_APPS_SUB_TITLE'];
            Configuration::updateValue('TBCMSCUSTOMSETTING_DOWNLOAD_APPS_SUB_TITLE', $tmp);

            $tmp = $result['TBCMSCUSTOMSETTING_DOWNLOAD_APPS_DESC'];
            Configuration::updateValue('TBCMSCUSTOMSETTING_DOWNLOAD_APPS_DESC', $tmp);
            
            $tmp = $result['TBCMSCUSTOMSETTING_DOWNLOAD_APPS_APPLE'];
            Configuration::updateValue('TBCMSCUSTOMSETTING_DOWNLOAD_APPS_APPLE', $tmp);

            $tmp = $result['TBCMSCUSTOMSETTING_DOWNLOAD_APPS_GOOLGE'];
            Configuration::updateValue('TBCMSCUSTOMSETTING_DOWNLOAD_APPS_GOOLGE', $tmp);

            $tmp = $result['TBCMSCUSTOMSETTING_DOWNLOAD_APPS_MICROSOFT'];
            Configuration::updateValue('TBCMSCUSTOMSETTING_DOWNLOAD_APPS_MICROSOFT', $tmp);

            $tmp = Tools::getValue('TBCMSCUSTOMSETTING_DOWNLOAD_APPS_STATUS');
            if ($tmp != Configuration::get('TBCMSCUSTOMSETTING_DOWNLOAD_APPS_STATUS')) {
                Configuration::updateValue('TBCMSCUSTOMSETTING_DOWNLOAD_APPS_STATUS', $tmp);
            }

            $this->context->smarty->assign('tab_number', '#fieldset_1_1');

            $this->clearCustomSmartyCache('tbcmscustomsetting_display_download_app.tpl');

            $message .= $this->displayConfirmation($this->l("App Link is Updated"));
        }

        if (Tools::isSubmit('submitTbcmsCustomTitleForm')) {
            foreach ($languages as $lang) {
                $tmp = Tools::getValue('TBCMSCUSTOMSETTING_CUSTOM_TEXT_'.$lang['id_lang']);
                $result['TBCMSCUSTOMSETTING_CUSTOM_TEXT'][$lang['id_lang']] = $tmp;

                $tmp = Tools::getValue('TBCMSCUSTOMSETTING_COPY_RIGHT_TEXT_'.$lang['id_lang']);
                $result['TBCMSCUSTOMSETTING_COPY_RIGHT_TEXT'][$lang['id_lang']] = $tmp;

                $tmp = Tools::getValue('TBCMSCUSTOMSETTING_COPY_RIGHT_LINK_'.$lang['id_lang']);
                $result['TBCMSCUSTOMSETTING_COPY_RIGHT_LINK'][$lang['id_lang']] = $tmp;

                $tmp = Tools::getValue('TBCMSCUSTOMSETTING_NEWSLETTER_TITLE_'.$lang['id_lang']);
                $result['TBCMSCUSTOMSETTING_NEWSLETTER_TITLE'][$lang['id_lang']] = $tmp;
                $tmp = Tools::getValue('TBCMSCUSTOMSETTING_NEWSLETTER_SHORT_DESC_'.$lang['id_lang']);
                $result['TBCMSCUSTOMSETTING_NEWSLETTER_SHORT_DESC'][$lang['id_lang']] = $tmp;

                $tmp = Tools::getValue('TBCMSCUSTOMSETTING_SOCIAL_ICON_TITLE_'.$lang['id_lang']);
                $result['TBCMSCUSTOMSETTING_SOCIAL_ICON_TITLE'][$lang['id_lang']] = $tmp;
                $tmp = Tools::getValue('TBCMSCUSTOMSETTING_SOCIAL_ICON_SHORT_DESC_'.$lang['id_lang']);
                $result['TBCMSCUSTOMSETTING_SOCIAL_ICON_SHORT_DESC'][$lang['id_lang']] = $tmp;
            }

            
            $tmp = $result['TBCMSCUSTOMSETTING_CUSTOM_TEXT'];
            Configuration::updateValue('TBCMSCUSTOMSETTING_CUSTOM_TEXT', $tmp);

            $tmp = Tools::getValue('TBCMSCUSTOMSETTING_CUSTOM_TEXT_STATUS');
            if ($tmp != Configuration::get('TBCMSCUSTOMSETTING_CUSTOM_TEXT_STATUS')) {
                Configuration::updateValue('TBCMSCUSTOMSETTING_CUSTOM_TEXT_STATUS', $tmp);
            }

            $tmp = $result['TBCMSCUSTOMSETTING_COPY_RIGHT_TEXT'];
            Configuration::updateValue('TBCMSCUSTOMSETTING_COPY_RIGHT_TEXT', $tmp);

            $tmp = $result['TBCMSCUSTOMSETTING_COPY_RIGHT_LINK'];
            Configuration::updateValue('TBCMSCUSTOMSETTING_COPY_RIGHT_LINK', $tmp);

            $tmp = Tools::getValue('TBCMSCUSTOMSETTING_COPY_RIGHT_TEXT_STATUS');
            if ($tmp != Configuration::get('TBCMSCUSTOMSETTING_COPY_RIGHT_TEXT_STATUS')) {
                Configuration::updateValue('TBCMSCUSTOMSETTING_COPY_RIGHT_TEXT_STATUS', $tmp);
            }

            $tmp = $result['TBCMSCUSTOMSETTING_NEWSLETTER_TITLE'];
            Configuration::updateValue('TBCMSCUSTOMSETTING_NEWSLETTER_TITLE', $tmp);

            $tmp = $result['TBCMSCUSTOMSETTING_NEWSLETTER_SHORT_DESC'];
            Configuration::updateValue('TBCMSCUSTOMSETTING_NEWSLETTER_SHORT_DESC', $tmp);

            $tmp = $result['TBCMSCUSTOMSETTING_SOCIAL_ICON_TITLE'];
            Configuration::updateValue('TBCMSCUSTOMSETTING_SOCIAL_ICON_TITLE', $tmp);

            $tmp = $result['TBCMSCUSTOMSETTING_SOCIAL_ICON_SHORT_DESC'];
            Configuration::updateValue('TBCMSCUSTOMSETTING_SOCIAL_ICON_SHORT_DESC', $tmp);

            $this->context->smarty->assign('tab_number', '#fieldset_2_2');

            $this->clearCustomSmartyCache('tbcmscustomsetting_display_copy_right_text.tpl');
            $this->clearCustomSmartyCache('tbcmscustomsetting_displaytopoffertext');

            $message .= $this->displayConfirmation($this->l("Custom Titles are Updated"));
        }

        Tools::clearSmartyCache();
        return $message;
    }

    public function clearCustomSmartyCache($cache_id)
    {
        if (Cache::isStored($cache_id)) {
            Cache::clean($cache_id);
        }
    }

    public function colorLuminance($hex, $percent)
    {
        $hex = preg_replace('/[^0-9a-f]/i', '', $hex);
        $new_hex = '#';
        
        if (Tools::strlen($hex) < 6) {
            $hex = $hex[0] + $hex[0] + $hex[1] + $hex[1] + $hex[2] + $hex[2];
        }
        
        // convert to decimal and change luminosity
        for ($i = 0; $i < 3; $i++) {
            $dec = hexdec(Tools::substr($hex, $i*2, 2));
            $dec = min(max(0, $dec + $dec * $percent), 255);
            $new_hex .= str_pad(dechex($dec), 2, 0, STR_PAD_LEFT);
        }
        
        return $new_hex;
    }


    public function createCustomThemeCss(
        $filename,
        $newfilename,
        $string_to_replace1,
        $replace_with1,
        $string_to_replace2 = null,
        $replace_with2 = null,
        $string_to_replace3 = null,
        $replace_with3 = null
    ) {
        $content= Tools::file_get_contents($filename);
        
        $content_chunks=explode($string_to_replace1, $content);
        $content=implode($replace_with1, $content_chunks);

        if (!empty($string_to_replace2) && !empty($replace_with2)) {
            $content_chunks=explode($string_to_replace2, $content);
            $content=implode($replace_with2, $content_chunks);
        }

        if (!empty($string_to_replace3) && !empty($replace_with3)) {
            $content_chunks=explode($string_to_replace3, $content);
            $content=implode($replace_with3, $content_chunks);
        }

        file_put_contents($newfilename, $content);
    }

    public function makeInslineStyleSheet()
    {
        $style = '';
        if (Configuration::get('TBCMSCUSTOMSETTING_ADD_CONTAINER')) {
            if (Configuration::get('TBCMSCUSTOMSETTING_BACKGROUND_IMAGE_PATTERN_STATUS') == 'color') {
                $color = Configuration::get('TBCMSCUSTOMSETTING_BACKGROUND_COLOR');
                $style = 'background-color:'.$color.';';
            } else {
                $img = '';
                if (Configuration::get('TBCMSCUSTOMSETTING_BACKGROUND_PATTERN') == 'custompattern') {
                    $img = Configuration::get('TBCMSCUSTOMSETTING_BACKGROUND_OLD_PATTERN');
                    $path = _MODULE_DIR_.$this->name."/views/img/".$img;
                } else {
                    $img = Configuration::get('TBCMSCUSTOMSETTING_BACKGROUND_PATTERN').'.png';
                    $path = _THEME_IMG_DIR_."pattern/".$img;
                }

                // $path = _MODULE_DIR_.$this->name."/views/img/".$img;
                $style = 'background-image:url('.$path.');';
            }
        }
        Configuration::updateValue('TBCMSCUSTOMSETTING_BACKGROUND_STYLE_SHEET', $style);

        if (Configuration::get('TBCMSCUSTOMSETTING_THEME_OPTION') == 'theme_custom') {
            // this is Color
            $color_replace_1 = "#maincolor1";
            $color_1 = Configuration::get('TBCMSCUSTOMSETTING_THEME_COLOR_1');

            // This is Gredeant Color
            $color_replace_2 = "#maincolor2";
            $color_new_2 = $this->colorLuminance($color_1, 0.40);

            $ftpThemeDir = _PS_ALL_THEMES_DIR_._THEME_NAME_."/assets/css";
            $filename = $ftpThemeDir."/theme-custom.css";
            $themeCssPath = '/'.Configuration::get('TBCMSCUSTOMSETTING_THEME_OPTION')
            .'_'.$this->id_shop_group.'_'.$this->id_shop.".css";
            $newfilename= $ftpThemeDir.$themeCssPath;

            $this->createCustomThemeCss(
                $filename,
                $newfilename,
                $color_replace_1,
                $color_1,
                $color_replace_2,
                $color_new_2
            );
            //half path for front site.
            Configuration::updateValue('TBCMSCUSTOMSETTING_THEME_CSS_PATH', $themeCssPath);
        }
    }

    public function hookdisplayBackgroundBody()
    {
        $this->makeInslineStyleSheet();
        return Configuration::get('TBCMSCUSTOMSETTING_BACKGROUND_STYLE_SHEET');
    }


    public function makeBodyInslineStyleSheet()
    {
        $style = '';
        if (Configuration::get('TBCMSCUSTOMSETTING_BODY_BACKGROUND_IMAGE_PATTERN_STATUS') == 'color') {
            $color = Configuration::get('TBCMSCUSTOMSETTING_BODY_BACKGROUND_COLOR');
            $style = 'background-color:'.$color.';';
        } else {
            $img = '';
            if (Configuration::get('TBCMSCUSTOMSETTING_BODY_BACKGROUND_PATTERN') == 'custombodypattern') {
                $img = Configuration::get('TBCMSCUSTOMSETTING_BODY_BACKGROUND_PATTERN');
                $path = _MODULE_DIR_.$this->name."/views/img/".$img;
            } else {
                $img = Configuration::get('TBCMSCUSTOMSETTING_BODY_BACKGROUND_PATTERN').'.png';
                $path = _THEME_IMG_DIR_."pattern/".$img;
            }

            // $path = _MODULE_DIR_.$this->name."/views/img/".$img;
            $style = 'background-image:url('.$path.');';
        }
        Configuration::updateValue('TBCMSCUSTOMSETTING_BODY_BACKGROUND_STYLE_SHEET', $style);
    }

    public function hookdisplayBodyBackgroundBody()
    {
        $this->makeBodyInslineStyleSheet();
        return Configuration::get('TBCMSCUSTOMSETTING_BACKGROUND_STYLE_SHEET');
    }


    public function makeCustomFontStyleSheet()
    {
        if (Configuration::get('TBCMSCUSTOMSETTING_CUSTOM_FONT_TITLE_COLOR_STATUS') == 1) {
            if (Configuration::get('TBCMSCUSTOMSETTING_THEME_FONT_TYPE') != '0') {
                $font_replace_1 = "#fontFamily1";
                $font_style_1 = Configuration::get('TBCMSCUSTOMSETTING_THEME_FONT_TYPE');
                $font_link = $this->getFontLinkUsingFontName($font_style_1);
                Configuration::updateValue('TBCMSCUSTOMSETTING_THEME_FONT_TYPE_LINK', $font_link);

                $ftpThemeDir = _PS_ALL_THEMES_DIR_._THEME_NAME_."/assets/css/";
                $filename = $ftpThemeDir."theme-custom-title-font.css";
                $themeCssFontPath = 'theme_custom_title_font_'.$this->id_shop_group.'_'.$this->id_shop.".css";
                $newfilename= $ftpThemeDir.$themeCssFontPath;

                $this->createCustomThemeCss(
                    $filename,
                    $newfilename,
                    $font_replace_1,
                    $font_style_1
                );
                
                $file = _THEME_CSS_DIR_.$themeCssFontPath;
                Configuration::updateValue('TBCMSCUSTOMSETTING_THEME_FONT_TYPE_LINK_URL', $file);
            }

            if (Configuration::get('TBCMSCUSTOMSETTING_THEME_FONT_TYPE_2') != '0') {
                $font_replace_2 = "#fontFamily2";
                $font_style_2 = Configuration::get('TBCMSCUSTOMSETTING_THEME_FONT_TYPE_2');
                $font_link_2 = $this->getFontLinkUsingFontName($font_style_2);
                Configuration::updateValue('TBCMSCUSTOMSETTING_THEME_FONT_TYPE_LINK_2', $font_link_2);

                $ftpThemeDir = _PS_ALL_THEMES_DIR_._THEME_NAME_."/assets/css/";
                $filename = $ftpThemeDir."theme-custom-body-font.css";
                $themeCssFontPath = 'theme_custom_body_font_'.$this->id_shop_group.'_'.$this->id_shop.".css";
                $newfilename= $ftpThemeDir.$themeCssFontPath;

                $this->createCustomThemeCss(
                    $filename,
                    $newfilename,
                    $font_replace_2,
                    $font_style_2
                );

                $file = _THEME_CSS_DIR_.$themeCssFontPath;
                Configuration::updateValue('TBCMSCUSTOMSETTING_THEME_FONT_TYPE_LINK_2_URL', $file);
            }

            if (Configuration::get('TBCMSCUSTOMSETTING_THEME_FONT_TYPE_2') != '') {
                $custom_title_color_replace_1 = "#customTitleColor";
                $custom_title_color_1 = Configuration::get('TBCMSCUSTOMSETTING_THEME_FONT_COLOR');

                $ftpThemeDir = _PS_ALL_THEMES_DIR_._THEME_NAME_."/assets/css/";
                $filename = $ftpThemeDir."theme-custom-title-color.css";
                $themeCssFontPath = 'theme_custom_title_color_'.$this->id_shop_group.'_'.$this->id_shop.".css";
                $newfilename= $ftpThemeDir.$themeCssFontPath;

                $this->createCustomThemeCss(
                    $filename,
                    $newfilename,
                    $custom_title_color_replace_1,
                    $custom_title_color_1
                );

                $file = _THEME_CSS_DIR_.$themeCssFontPath;
                Configuration::updateValue('TBCMSCUSTOMSETTING_THEME_CUSTOM_TITLE_COLOR', $file);
            }
        }
    }

    public function getFontLinkUsingFontName($selected_font_name)
    {
        // $url_font_name = str_replace($font_name, ' ', '+');
        $link = '';
        $obj = new TbcmsCustomSettingCommonList();
        $fonts = $obj->titleFontList();
        foreach ($fonts as $font) {
            if ($selected_font_name == $font['name']) {
                $link = $font['link'] ;
                break;
            }
        }

        return $link;
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

        return $helper->generateForm(array(
            $this->tbcmsThemeOptionForm(),
            $this->tbcmsAppLinkForm(),
            $this->tbcmsFooterProductForm(),
        ));
    }

    protected function tbcmsThemeOptionForm()
    {
        $tbcms_obj = new TbcmsCustomSettingStatus();
        $show_fields = $tbcms_obj->fieldStatusInformation();
        $input = array();

        // This is Theme option information
        if ($show_fields['all_theme_option_info']) {
            $input[] = array(
                        'col' => 9,
                        'type' => 'custom_theme_option',
                        'name' => 'TBCMSCUSTOMSETTING_THEME_OPTION',
                        'label' => $this->l('Theme Options'),
                    );

            $input[] = array(
                        'col' => 9,
                        'type' => 'color',
                        'name' => 'TBCMSCUSTOMSETTING_THEME_COLOR_1',
                        'label' => $this->l('Custom Theme Color'),
                    );

            $input[] = array(
                        'col' => 9,
                        'type' => 'color',
                        'name' => 'TBCMSCUSTOMSETTING_THEME_COLOR_2',
                        'label' => $this->l('Custom Theme Color 2'),
                    );

            $input[] = array(
                        'type' => 'switch',
                        'label' => $this->l('Box Layout'),
                        'name' => 'TBCMSCUSTOMSETTING_ADD_CONTAINER',
                        'desc' => $this->l('Box Layout Show in Front Side'),
                        'is_bool' => true,
                        'class' => 'tbcmsadd-box',
                        'values'    => array(
                            array(
                                'id'    => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Show')
                            ),
                            array(
                                'id'    => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Hide')
                            )
                        )
                    );

            $input[] = array(
                        'type' => 'radio',
                        'label' => $this->l('Background Theme'),
                        'name' => 'TBCMSCUSTOMSETTING_BACKGROUND_IMAGE_PATTERN_STATUS',
                        'desc' => $this->l('Types of Background Styles'),
                        'is_bool' => true,
                        'class' => 'tbcmsbackground-type',
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 'color',
                                'label' => $this->l('Color')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 'pattern',
                                'label' => $this->l('Pattern')
                            )
                        )
                    );

            $input[] = array(
                        'col' => 9,
                        'type' => 'color',
                        'label' => $this->l('Back Ground Theme Color'),
                        'name' => 'TBCMSCUSTOMSETTING_BACKGROUND_COLOR',
                    );

            $input[] = array(
                        'col' => 9,
                        'type' => 'file_upload_3',
                        'name' => 'TBCMSCUSTOMSETTING_BACKGROUND_PATTERN',
                        'label' => $this->l('BackGround Pattern'),
                        'lang' => true,
                    );

            $input[] = array(
                        'col' => 9,
                        'type' => 'select',
                        'name' => 'TBCMSCUSTOMSETTING_BACKGROUND_IMAGE_REPEAT',
                        'label' => $this->l('Background Image Css Repeat'),
                        'desc' => $this->l('Select Your "background-repeat" css Property. Its value "repeat" and'
                            .' "no-repeat". This Option only work with "background-image" not "background-color".'),
                        'options' => array(
                            'query' => array(
                                array(
                                    'id_option' => 'repeat',
                                    'name' => 'Repeat',
                                ),
                                array(
                                    'id_option' => 'no-repeat',
                                    'name' => 'No Repeat',
                                ),
                            ),
                            'id' => 'id_option',
                            'name' => 'name'
                        )
                    );

            $input[] = array(
                        'col' => 9,
                        'type' => 'select',
                        'name' => 'TBCMSCUSTOMSETTING_BACKGROUND_IMAGE_ATTACHMENT',
                        'label' => $this->l('Background Image Css Attachment'),
                        'desc' => $this->l('Select Your "background-attachment" css Property. Its value "fixed" and'
                            .' "unset". This Option only work with "background-image" not "background-color".'),
                        'options' => array(
                            'query' => array(
                                array(
                                    'id_option' => 'fixed',
                                    'name' => 'Fixed',
                                ),
                                array(
                                    'id_option' => 'unset',
                                    'name' => 'Unset',
                                ),
                            ),
                            'id' => 'id_option',
                            'name' => 'name'
                        )
                    );

            $input[] = array(
                        'type' => 'switch',
                        'label' => $this->l('Theme Option Status'),
                        'name' => 'TBCMSFRONTSIDE_THEME_SETTING_SHOW',
                        'desc' => $this->l('Theme Option Show in Front Side'),
                        'is_bool' => true,
                        'values'    => array(
                            array(
                                'id'    => 'active_on',
                                'value' => '1',
                                'label' => $this->l('Show')
                            ),
                            array(
                                'id'    => 'active_off',
                                'value' => '0',
                                'label' => $this->l('Hide')
                            )
                        )
                    );
        }

        if ($show_fields['theme_background_design']) {
            $input[] = array(
                        'type' => 'switch',
                        'label' => $this->l('Body Background Status'),
                        'name' => 'TBCMSCUSTOMSETTING_BODY_BACKGROUND_COLOR_STATUS',
                        'desc' => $this->l('Theme Body background Color Status'),
                        'is_bool' => true,
                        'values'    => array(
                            array(
                                'id'    => 'active_on',
                                'value' => '1',
                                'label' => $this->l('Show')
                            ),
                            array(
                                'id'    => 'active_off',
                                'value' => '0',
                                'label' => $this->l('Hide')
                            )
                        )
                    );

            $input[] = array(
                        'type' => 'radio',
                        'label' => $this->l('Body Background Theme'),
                        'name' => 'TBCMSCUSTOMSETTING_BODY_BACKGROUND_IMAGE_PATTERN_STATUS',
                        'desc' => $this->l('Types of Body Background Styles'),
                        'is_bool' => true,
                        'class' => 'tbcmsbody-background-type',
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 'color',
                                'label' => $this->l('Color')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 'pattern',
                                'label' => $this->l('Pattern')
                            )
                        )
                    );

            $input[] = array(
                        'col' => 9,
                        'type' => 'color',
                        'label' => $this->l('Body Background Color'),
                        'name' => 'TBCMSCUSTOMSETTING_BODY_BACKGROUND_COLOR',
                    );

            $input[] = array(
                        'col' => 9,
                        'type' => 'file_upload_4',
                        'name' => 'TBCMSCUSTOMSETTING_BODY_BACKGROUND_PATTERN',
                        'label' => $this->l('Body BackGround Pattern'),
                        'lang' => true,
                    );

            $input[] = array(
                        'col' => 9,
                        'type' => 'select',
                        'name' => 'TBCMSCUSTOMSETTING_BODY_BACKGROUND_IMAGE_REPEAT',
                        'label' => $this->l('Background Image Css Repeat'),
                        'desc' => $this->l('Select Your "background-repeat" css Property. Its value "repeat" and'
                            .' "no-repeat". This Option only work with "background-image" not "background-color".'),
                        'options' => array(
                            'query' => array(
                                array(
                                    'id_option' => 'repeat',
                                    'name' => 'Repeat',
                                ),
                                array(
                                    'id_option' => 'no-repeat',
                                    'name' => 'No Repeat',
                                ),
                            ),
                            'id' => 'id_option',
                            'name' => 'name'
                        )
                    );

            $input[] = array(
                        'col' => 9,
                        'type' => 'select',
                        'name' => 'TBCMSCUSTOMSETTING_BODY_BACKGROUND_IMAGE_ATTACHMENT',
                        'label' => $this->l('Background Image Css Attachment'),
                        'desc' => $this->l('Select Your "background-attachment" css Property. Its value "fixed" and'
                            .' "unset". This Option only work with "background-image" not "background-color".'),
                        'options' => array(
                            'query' => array(
                                array(
                                    'id_option' => 'fixed',
                                    'name' => 'Fixed',
                                ),
                                array(
                                    'id_option' => 'unset',
                                    'name' => 'Unset',
                                ),
                            ),
                            'id' => 'id_option',
                            'name' => 'name'
                        )
                    );
        }

        if ($show_fields['theme_font_design']) {
            $obj = new TbcmsCustomSettingCommonList();
            $fontList = $obj->titleFontList();
            $inputTitleFontList = array();
            $inputTitleFontList[0]['name'] = 'Custom Font';
            $inputTitleFontList[0]['id_option'] = '0';
            $i = 1;
            foreach ($fontList as $font) {
                $inputTitleFontList[$i]['name'] = $font['name'];
                $inputTitleFontList[$i]['id_option'] = $font['name'];
                $i++;
            }

            $fontList = $obj->bodyFontList();
            $inputBodyFontList = array();
            $inputBodyFontList[0]['name'] = 'Custom Font';
            $inputBodyFontList[0]['id_option'] = '0';
            $i = 1;
            foreach ($fontList as $font) {
                $inputBodyFontList[$i]['name'] = $font['name'];
                $inputBodyFontList[$i]['id_option'] = $font['name'];
                $i++;
            }

            $input[] = array(
                        'type' => 'switch',
                        'label' => $this->l('Custom Font And Color'),
                        'name' => 'TBCMSCUSTOMSETTING_CUSTOM_FONT_TITLE_COLOR_STATUS',
                        'desc' => $this->l('Display Custom Font and Title Color in front Side'),
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Show')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Hide')
                            )
                        )
                    );

            $input[] = array(
                        'col' => 7,
                        'type' => 'select',
                        'name' => 'TBCMSCUSTOMSETTING_THEME_FONT_TYPE',
                        'label' => $this->l('Title Font'),
                        'desc' => $this->l('Select Font of front title.'),
                        'options' => array(
                            'query' => $inputTitleFontList,
                            'id' => 'id_option',
                            'name' => 'name'
                        )
                    );

            $input[] = array(
                        'col' => 9,
                        'type' => 'color',
                        'label' => $this->l('Title Color'),
                        'name' => 'TBCMSCUSTOMSETTING_THEME_FONT_COLOR',
                    );

            $input[] = array(
                        'col' => 7,
                        'type' => 'select',
                        'name' => 'TBCMSCUSTOMSETTING_THEME_FONT_TYPE_2',
                        'label' => $this->l('Other Font'),
                        'desc' => $this->l('Select other Font of Theme.'),
                        'options' => array(
                            'query' => $inputBodyFontList,
                            'id' => 'id_option',
                            'name' => 'name'
                        )
                    );
        }

        if ($show_fields['page_loader']) {
            $input[] = array(
                        'type' => 'switch',
                        'label' => $this->l('Page Loader'),
                        'name' => 'TBCMSCUSTOMSETTING_PAGE_LOADER',
                        'desc' => $this->l('Display Page Loader in Front Side'),
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Show')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Hide')
                            )
                        )
                    );
        }

        if ($show_fields['animation_css']) {
            $input[] = array(
                        'type' => 'switch',
                        'label' => $this->l('Animation Js'),
                        'name' => 'TBCMSCUSTOMSETTING_ANIMATION_CSS',
                        'desc' => $this->l('Display Animation Effect in Front Side'),
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Show')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Hide')
                            )
                        )
                    );
        }

        if ($show_fields['mouse_hover_image']) {
            $input[] = array(
                        'type' => 'switch',
                        'label' => $this->l('Mouse Hover Image'),
                        'name' => 'TBCMSCUSTOMSETTING_HOVER_IMG',
                        'desc' => $this->l('Display Product\'s Other Image When Mosue Hover.'),
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Show')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Hide')
                            )
                        )
                    );
        }

        if (Module::isInstalled('tbcmstabproducts')) {
            if ($show_fields['tab_product_double_row']) {
                $input[] = array(
                            'type' => 'switch',
                            'label' => $this->l('Tab Product Double Row'),
                            'name' => 'TBCMSCUSTOMSETTING_TAB_PRODUCT_ROW',
                            'desc' => $this->l('If True Then Tab Products has Double Row Othewise Its Show in one Row'),
                            'is_bool' => true,
                            'values' => array(
                                array(
                                    'id' => 'active_on',
                                    'value' => 1,
                                    'label' => $this->l('Show')
                                ),
                                array(
                                    'id' => 'active_off',
                                    'value' => 0,
                                    'label' => $this->l('Hide')
                                )
                            )
                        );
            }
        }

        if ($show_fields['product_color']) {
            $input[] = array(
                        'type' => 'switch',
                        'label' => $this->l('Product Color'),
                        'name' => 'TBCMSCUSTOMSETTING_PRODUCT_COLOR',
                        'desc' => $this->l('If True Then Products show Color Othewise not'),
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Show')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Hide')
                            )
                        )
                    );
        }

        if ($show_fields['product_list_view']) {
            $input[] = array(
                        'col' => 7,
                        'type' => 'select',
                        'name' => 'TBCMSCUSTOMSETTING_PRODUCT_LIST_VIEW',
                        'label' => $this->l('Product List View'),
                        'desc' => $this->l('Its show Default View of Product list.'),
                        'options' => array(
                            'query' => array(
                                array(
                                    'id_option' => 'grid',
                                    'name' => 'Grid View'
                                ),
                                array(
                                    'id_option' => 'grid-2',
                                    'name' => 'Grid View 2'
                                ),
                                array(
                                    'id_option' => 'list',
                                    'name' => 'List View'
                                ),
                                array(
                                    'id_option' => 'list-2',
                                    'name' => 'List View 2'
                                ),
                                array(
                                    'id_option' => 'catelog',
                                    'name' => 'Catelog View'
                                ),
                            ),
                            'id' => 'id_option',
                            'name' => 'name'
                        )
                    );
        }

        if (Module::isInstalled('ps_mainmenu')) {
            if ($show_fields['main_menu_sticky']) {
                $input[] = array(
                            'type' => 'switch',
                            'label' => $this->l('Main Menu Sticky Status'),
                            'name' => 'TBCMSCUSTOMSETTING_MAIN_MENU_STICKY',
                            'desc' => $this->l('Display Main Menu as Sticky Of Front Side'),
                            'is_bool' => true,
                            'values' => array(
                                array(
                                    'id' => 'active_on',
                                    'value' => 1,
                                    'label' => $this->l('Show')
                                ),
                                array(
                                    'id' => 'active_off',
                                    'value' => 0,
                                    'label' => $this->l('Hide')
                                )
                            )
                        );
            }
        }

        if ($show_fields['bottom_sticky']) {
            $input[] = array(
                        'type' => 'switch',
                        'label' => $this->l('Bottom Option'),
                        'name' => 'TBCMSCUSTOMSETTING_BOTTOM_OPTION',
                        'desc' => $this->l('Display Bottom Option of Front Side'),
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Show')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Hide')
                            )
                        )
                    );
        }

        if (Module::isInstalled('tbcmsverticalmenu')) {
            if ($show_fields['vertical_menu_open']) {
                $input[] = array(
                            'type' => 'switch',
                            'label' => $this->l('Vertical Menu Open'),
                            'name' => 'TBCMSCUSTOMSETTING_VERTICAL_MENU_OPEN',
                            'desc' => $this->l('Vertical Menu is Open Default in Home Page'),
                            'is_bool' => true,
                            'values' => array(
                                array(
                                    'id' => 'active_on',
                                    'value' => 1,
                                    'label' => $this->l('Show')
                                ),
                                array(
                                    'id' => 'active_off',
                                    'value' => 0,
                                    'label' => $this->l('Hide')
                                )
                            )
                        );
            }
        }
        

        return array(
            'form' => array(
                'legend' => array(
                'title' => $this->l('Theme Option'),
                'icon' => 'icon-cogs',
                ),
                'input' => $input,
                'submit' => array(
                    'title' => $this->l('Save'),
                    'name' => 'submitTbcmsThemeOptionForm',
                ),
            ),
        );
    }

    // App Link Form
    protected function tbcmsAppLinkForm()
    {
        $tbcms_obj = new TbcmsCustomSettingStatus();
        $show_fields = $tbcms_obj->fieldStatusInformation();
        $input = array();

        if ($show_fields['app_main_image']) {
            $input[] = array(
                        'col' => 9,
                        'type' => 'file_upload',
                        'name' => 'TBCMSCUSTOMSETTING_DOWNLOAD_APPS_IMAGE',
                        'label' => $this->l('App Link Image'),
                    );
        }

        if ($show_fields['app_title']) {
            $input[] = array(
                        'col' => 9,
                        'type' => 'text',
                        'name' => 'TBCMSCUSTOMSETTING_DOWNLOAD_APPS_TITLE',
                        'label' => $this->l('App Link Title'),
                        'lang' => true,
                        'desc' => $this->l('Display Title of All App Link in Front Side'),
                    );
        }

        if ($show_fields['app_sub_title']) {
            $input[] = array(
                        'col' => 9,
                        'type' => 'text',
                        'name' => 'TBCMSCUSTOMSETTING_DOWNLOAD_APPS_SUB_TITLE',
                        'label' => $this->l('App Link Sub-Title'),
                        'lang' => true,
                        'desc' => $this->l('Display Sub-title of All App Link in Front Side'),
                    );
        }

        if ($show_fields['app_desc']) {
            $input[] = array(
                        'col' => 9,
                        'type' => 'text',
                        'name' => 'TBCMSCUSTOMSETTING_DOWNLOAD_APPS_DESC',
                        'label' => $this->l('App Link Description'),
                        'lang' => true,
                        'desc' => $this->l('Display Description of All App Link in Front Side'),
                    );
        }

        if ($show_fields['apple_app_link']) {
            $input[] = array(
                        'col' => 9,
                        'type' => 'text',
                        'name' => 'TBCMSCUSTOMSETTING_DOWNLOAD_APPS_APPLE',
                        'label' => $this->l('Apple App Link'),
                        'lang' => true,
                        'desc' => $this->l('Display Apple App in Front Side'),
                    );
        }

        if ($show_fields['google_app_link']) {
            $input[] = array(
                        'col' => 9,
                        'type' => 'text',
                        'name' => 'TBCMSCUSTOMSETTING_DOWNLOAD_APPS_GOOLGE',
                        'label' => $this->l('Google App Link'),
                        'lang' => true,
                        'desc' => $this->l('Display Google Link in Front Side'),
                    );
        }

        if ($show_fields['microsoft_app_link']) {
            $input[] = array(
                        'col' => 9,
                        'type' => 'text',
                        'name' => 'TBCMSCUSTOMSETTING_DOWNLOAD_APPS_MICROSOFT',
                        'label' => $this->l('Microsoft App Link'),
                        'lang' => true,
                        'desc' => $this->l('Display Microsoft Link in Front Side'),
                    );
        }

        if ($show_fields['app_link_status']) {
            $input[] = array(
                        'type' => 'switch',
                        'label' => $this->l('Status'),
                        'name' => 'TBCMSCUSTOMSETTING_DOWNLOAD_APPS_STATUS',
                        'desc' => $this->l('Status of App Link in Front Side'),
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Show')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Hide')
                            )
                        )
                    );
        }

        return array(
            'form' => array(
                'legend' => array(
                'title' => $this->l('App Link'),
                'icon' => 'icon-cloud-upload',
                ),
                'input' => $input,
                'submit' => array(
                    'title' => $this->l('Save'),
                    'name' => 'submitTbcmsAppLinkForm',
                ),
            ),
        );
    }

    // App Link Form
    protected function tbcmsFooterProductForm()
    {
        $tbcms_obj = new TbcmsCustomSettingStatus();
        $show_fields = $tbcms_obj->fieldStatusInformation();
        $input = array();

        // This is Copyright information
        if ($show_fields['copy_right_info']) {
            if ($show_fields['custom_text']) {
                $input[] = array(
                        'col' => 9,
                        'type' => 'text',
                        'name' => 'TBCMSCUSTOMSETTING_CUSTOM_TEXT',
                        'label' => $this->l('Custom Text'),
                        'lang' => true,
                        'desc' => $this->l('Display Custom Text in Front Side'),
                    );
            }

            if ($show_fields['custom_text_status']) {
                $input[] =  array(
                        'type' => 'switch',
                        'label' => $this->l('Custom Text Status'),
                        'name' => 'TBCMSCUSTOMSETTING_CUSTOM_TEXT_STATUS',
                        'desc' => $this->l('Status for Custom Text'),
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Show')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Hide')
                            )
                        )
                    );
            }

            if ($show_fields['copy_right_text']) {
                $input[] = array(
                        'col' => 9,
                        'type' => 'text',
                        'name' => 'TBCMSCUSTOMSETTING_COPY_RIGHT_TEXT',
                        'label' => $this->l('Copy Right Text'),
                        'lang' => true,
                        'desc' => $this->l('Display Copy right Text in Front Side'),
                    );
            }

            if ($show_fields['copy_right_link']) {
                $input[] = array(
                        'col' => 9,
                        'type' => 'text',
                        'name' => 'TBCMSCUSTOMSETTING_COPY_RIGHT_LINK',
                        'label' => $this->l('Copy Right Link'),
                        'lang' => true,
                        'desc' => $this->l('Display Copy right Link in Front Side'),
                    );
            }

            if ($show_fields['copy_right_text_status']) {
                $input[] =  array(
                        'type' => 'switch',
                        'label' => $this->l('Copy Right Text Status'),
                        'name' => 'TBCMSCUSTOMSETTING_COPY_RIGHT_TEXT_STATUS',
                        'desc' => $this->l('Status for Copy Right Text'),
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Show')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Hide')
                            )
                        )
                    );
            }
        }

        // This is Footer tab Information
        if ($show_fields['footer_tab_product_info']) {
            if ($show_fields['footer_tab_featured_prod_title']) {
                $input[] = array(
                        'col' => 9,
                        'type' => 'text',
                        'name' => 'TBCMSCUSTOMSETTING_FOOTER_TAB_FEATURED_PROD_TITLE',
                        'label' => $this->l('Featured Product Title'),
                        'desc' => $this->l('Display Testimonial Title in From Side'),
                        'lang' => true,
                    );
            }

            if ($show_fields['footer_tab_new_prod_title']) {
                $input[] = array(
                        'col' => 9,
                        'type' => 'text',
                        'name' => 'TBCMSCUSTOMSETTING_FOOTER_TAB_NEW_PROD_TITLE',
                        'label' => $this->l('New Product Title'),
                        'desc' => $this->l('Display Testimonial Title in From Side'),
                        'lang' => true,
                    );
            }

            if ($show_fields['footer_tab_best_seller_prod_title']) {
                $input[] = array(
                        'col' => 9,
                        'type' => 'text',
                        'name' => 'TBCMSCUSTOMSETTING_FOOTER_TAB_BEST_SELLER_PROD_TITLE',
                        'label' => $this->l('Best Seller Product Title'),
                        'desc' => $this->l('Display Testimonial Title in From Side'),
                        'lang' => true,
                    );
            }

            if ($show_fields['footer_tab_num_prod']) {
                $input[] = array(
                        'type' => 'select',
                        'label' => $this->l('Number of Product'),
                        'desc' => $this->l('Number of Product which Show in Footer Tab'),
                        'name' => 'TBCMSCUSTOMSETTING_FOOTER_TAB_NUM_PROD',
                        'options' => array(
                            'query' => array(
                                array(
                                    'id_option' => 1,
                                    'name' => '1'
                                ),
                                array(
                                    'id_option' => 2,
                                    'name' => '2'
                                ),
                                array(
                                    'id_option' => 3,
                                    'name' => '3'
                                ),
                                array(
                                    'id_option' => 4,
                                    'name' => '4'
                                ),
                                array(
                                    'id_option' => 5,
                                    'name' => '5'
                                ),
                            ),
                            'id' => 'id_option',
                            'name' => 'name'
                        )
                    );
            }

            if ($show_fields['footer_tab_prod_status']) {
                $input[] = array(
                        'type' => 'switch',
                        'label' => $this->l('Footer Tab Produts Status'),
                        'name' => 'TBCMSCUSTOMSETTING_FOOTER_TAB_STATUS',
                        'desc' => $this->l('Show Footer tab Product in Home Page'),
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Show')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Hide')
                            )
                        )
                    );
            }
        }

        if (Module::isInstalled('ps_emailsubscription')) {
            if ($show_fields['news_letter_title']) {
                $input[] = array(
                        'col' => 9,
                        'type' => 'text',
                        'name' => 'TBCMSCUSTOMSETTING_NEWSLETTER_TITLE',
                        'label' => $this->l('Newsletter Title'),
                        'desc' => $this->l('Display Newsletter Title in From Side'),
                        'lang' => true,
                    );
            }

            if ($show_fields['news_letter_short_desc']) {
                $input[] = array(
                        'col' => 9,
                        'type' => 'text',
                        'name' => 'TBCMSCUSTOMSETTING_NEWSLETTER_SHORT_DESC',
                        'label' => $this->l('Newsletter Short Description'),
                        'desc' => $this->l('Display Newsletter Description in From Side'),
                        'lang' => true,
                    );
            }
        }

        if (Module::isInstalled('ps_socialfollow')) {
            if ($show_fields['social_icon_title']) {
                $input[] = array(
                        'col' => 9,
                        'type' => 'text',
                        'name' => 'TBCMSCUSTOMSETTING_SOCIAL_ICON_TITLE',
                        'label' => $this->l('Social Icon Title'),
                        'desc' => $this->l('Display Social Icon Title in From Side'),
                        'lang' => true,
                    );
            }

            if ($show_fields['social_icon_short_desc']) {
                $input[] = array(
                        'col' => 9,
                        'type' => 'text',
                        'name' => 'TBCMSCUSTOMSETTING_SOCIAL_ICON_SHORT_DESC',
                        'label' => $this->l('Social Icon Short Description'),
                        'desc' => $this->l('Display Social Icon Description in From Side'),
                        'lang' => true,
                    );
            }
        }


        return array(
            'form' => array(
                'legend' => array(
                'title' => $this->l('Custom Titles'),
                'icon' => 'icon-cloud-upload',
                ),
                'input' => $input,
                'submit' => array(
                    'title' => $this->l('Save'),
                    'name' => 'submitTbcmsCustomTitleForm',
                ),
            ),
        );
    }


    protected function getConfigFormValues()
    {
        $fields = array();
        $languages = Language::getLanguages();
        $path = _MODULE_DIR_.$this->name."/views/img/";

        foreach ($languages as $lang) {
            $a = Configuration::get('TBCMSCUSTOMSETTING_DOWNLOAD_APPS_TITLE', $lang['id_lang']);
            $fields['TBCMSCUSTOMSETTING_DOWNLOAD_APPS_TITLE'][$lang['id_lang']] = $a;

            $a = Configuration::get('TBCMSCUSTOMSETTING_DOWNLOAD_APPS_SUB_TITLE', $lang['id_lang']);
            $fields['TBCMSCUSTOMSETTING_DOWNLOAD_APPS_SUB_TITLE'][$lang['id_lang']] = $a;

            $a = Configuration::get('TBCMSCUSTOMSETTING_DOWNLOAD_APPS_DESC', $lang['id_lang']);
            $fields['TBCMSCUSTOMSETTING_DOWNLOAD_APPS_DESC'][$lang['id_lang']] = $a;

            $a = Configuration::get('TBCMSCUSTOMSETTING_DOWNLOAD_APPS_APPLE', $lang['id_lang']);
            $fields['TBCMSCUSTOMSETTING_DOWNLOAD_APPS_APPLE'][$lang['id_lang']] = $a;

            $a = Configuration::get('TBCMSCUSTOMSETTING_DOWNLOAD_APPS_GOOLGE', $lang['id_lang']);
            $fields['TBCMSCUSTOMSETTING_DOWNLOAD_APPS_GOOLGE'][$lang['id_lang']] = $a;

            $a = Configuration::get('TBCMSCUSTOMSETTING_DOWNLOAD_APPS_MICROSOFT', $lang['id_lang']);
            $fields['TBCMSCUSTOMSETTING_DOWNLOAD_APPS_MICROSOFT'][$lang['id_lang']] = $a;
            
            $a = Configuration::get('TBCMSCUSTOMSETTING_CUSTOM_TEXT', $lang['id_lang']);
            $fields['TBCMSCUSTOMSETTING_CUSTOM_TEXT'][$lang['id_lang']] = $a;

            $a = Configuration::get('TBCMSCUSTOMSETTING_COPY_RIGHT_TEXT', $lang['id_lang']);
            $fields['TBCMSCUSTOMSETTING_COPY_RIGHT_TEXT'][$lang['id_lang']] = $a;

            $a = Configuration::get('TBCMSCUSTOMSETTING_COPY_RIGHT_LINK', $lang['id_lang']);
            $fields['TBCMSCUSTOMSETTING_COPY_RIGHT_LINK'][$lang['id_lang']] = $a;

            $a = Configuration::get('TBCMSCUSTOMSETTING_FOOTER_TAB_FEATURED_PROD_TITLE', $lang['id_lang']);
            $fields['TBCMSCUSTOMSETTING_FOOTER_TAB_FEATURED_PROD_TITLE'][$lang['id_lang']] = $a;

            $a = Configuration::get('TBCMSCUSTOMSETTING_FOOTER_TAB_NEW_PROD_TITLE', $lang['id_lang']);
            $fields['TBCMSCUSTOMSETTING_FOOTER_TAB_NEW_PROD_TITLE'][$lang['id_lang']] = $a;

            $a = Configuration::get('TBCMSCUSTOMSETTING_FOOTER_TAB_BEST_SELLER_PROD_TITLE', $lang['id_lang']);
            $fields['TBCMSCUSTOMSETTING_FOOTER_TAB_BEST_SELLER_PROD_TITLE'][$lang['id_lang']] = $a;

            $a = Configuration::get('TBCMSCUSTOMSETTING_NEWSLETTER_TITLE', $lang['id_lang']);
            $fields['TBCMSCUSTOMSETTING_NEWSLETTER_TITLE'][$lang['id_lang']] = $a;

            $a = Configuration::get('TBCMSCUSTOMSETTING_NEWSLETTER_SHORT_DESC', $lang['id_lang']);
            $fields['TBCMSCUSTOMSETTING_NEWSLETTER_SHORT_DESC'][$lang['id_lang']] = $a;

            $a = Configuration::get('TBCMSCUSTOMSETTING_SOCIAL_ICON_TITLE', $lang['id_lang']);
            $fields['TBCMSCUSTOMSETTING_SOCIAL_ICON_TITLE'][$lang['id_lang']] = $a;

            $a = Configuration::get('TBCMSCUSTOMSETTING_SOCIAL_ICON_SHORT_DESC', $lang['id_lang']);
            $fields['TBCMSCUSTOMSETTING_SOCIAL_ICON_SHORT_DESC'][$lang['id_lang']] = $a;
        }
 
        $tmp = Configuration::get('TBCMSCUSTOMSETTING_DOWNLOAD_APPS_IMAGE');
        $fields['TBCMSCUSTOMSETTING_DOWNLOAD_APPS_IMAGE'] = $tmp;

        $tmp = Configuration::get('TBCMSCUSTOMSETTING_DOWNLOAD_APPS_STATUS');
        $fields['TBCMSCUSTOMSETTING_DOWNLOAD_APPS_STATUS'] = $tmp;

        $tmp = Configuration::get('TBCMSCUSTOMSETTING_CUSTOM_TEXT_STATUS');
        $fields['TBCMSCUSTOMSETTING_CUSTOM_TEXT_STATUS'] = $tmp;
        
        $tmp = Configuration::get('TBCMSCUSTOMSETTING_COPY_RIGHT_TEXT_STATUS');
        $fields['TBCMSCUSTOMSETTING_COPY_RIGHT_TEXT_STATUS'] = $tmp;

        $tmp = Configuration::get('TBCMSCUSTOMSETTING_MAIN_MENU_STICKY');
        $fields['TBCMSCUSTOMSETTING_MAIN_MENU_STICKY'] = $tmp;

        $tmp = Configuration::get('TBCMSCUSTOMSETTING_BOTTOM_OPTION');
        $fields['TBCMSCUSTOMSETTING_BOTTOM_OPTION'] = $tmp;

        $tmp = Configuration::get('TBCMSCUSTOMSETTING_VERTICAL_MENU_OPEN');
        $fields['TBCMSCUSTOMSETTING_VERTICAL_MENU_OPEN'] = $tmp;
        
        $tmp = Configuration::get('TBCMSCUSTOMSETTING_FOOTER_TAB_NUM_PROD');
        $fields['TBCMSCUSTOMSETTING_FOOTER_TAB_NUM_PROD'] = $tmp;

        $tmp = Configuration::get('TBCMSCUSTOMSETTING_FOOTER_TAB_STATUS');
        $fields['TBCMSCUSTOMSETTING_FOOTER_TAB_STATUS'] = $tmp;

        $tmp = Configuration::get('TBCMSCUSTOMSETTING_ADD_CONTAINER');
        $fields['TBCMSCUSTOMSETTING_ADD_CONTAINER'] = $tmp;

        $tmp = Configuration::get('TBCMSCUSTOMSETTING_BODY_BACKGROUND_COLOR_STATUS');
        $fields['TBCMSCUSTOMSETTING_BODY_BACKGROUND_COLOR_STATUS'] = $tmp;

        $tmp = Configuration::get('TBCMSCUSTOMSETTING_BODY_BACKGROUND_IMAGE_PATTERN_STATUS');
        $fields['TBCMSCUSTOMSETTING_BODY_BACKGROUND_IMAGE_PATTERN_STATUS'] = $tmp;

        $tmp = Configuration::get('TBCMSCUSTOMSETTING_BODY_BACKGROUND_COLOR');
        $fields['TBCMSCUSTOMSETTING_BODY_BACKGROUND_COLOR'] = $tmp;

        $tmp = Configuration::get('TBCMSCUSTOMSETTING_BODY_BACKGROUND_PATTERN');
        $fields['TBCMSCUSTOMSETTING_BODY_BACKGROUND_PATTERN'] = $tmp;
        $this->context->smarty->assign('body_background_pattern', $tmp);


        $tmp = Configuration::get('TBCMSCUSTOMSETTING_BODY_BACKGROUND_IMAGE_REPEAT');
        $fields['TBCMSCUSTOMSETTING_BODY_BACKGROUND_IMAGE_REPEAT'] = $tmp;

        $tmp = Configuration::get('TBCMSCUSTOMSETTING_BODY_BACKGROUND_IMAGE_ATTACHMENT');
        $fields['TBCMSCUSTOMSETTING_BODY_BACKGROUND_IMAGE_ATTACHMENT'] = $tmp;

        $tmp = Configuration::get('TBCMSCUSTOMSETTING_CUSTOM_FONT_TITLE_COLOR_STATUS');
        $fields['TBCMSCUSTOMSETTING_CUSTOM_FONT_TITLE_COLOR_STATUS'] = $tmp;

        $tmp = Configuration::get('TBCMSCUSTOMSETTING_THEME_FONT_TYPE');
        $fields['TBCMSCUSTOMSETTING_THEME_FONT_TYPE'] = $tmp;

        $tmp = Configuration::get('TBCMSCUSTOMSETTING_THEME_FONT_COLOR');
        $fields['TBCMSCUSTOMSETTING_THEME_FONT_COLOR'] = $tmp;

        $tmp = Configuration::get('TBCMSCUSTOMSETTING_THEME_FONT_TYPE_2');
        $fields['TBCMSCUSTOMSETTING_THEME_FONT_TYPE_2'] = $tmp;

        $tmp = Configuration::get('TBCMSCUSTOMSETTING_PAGE_LOADER');
        $fields['TBCMSCUSTOMSETTING_PAGE_LOADER'] = $tmp;

        $tmp = Configuration::get('TBCMSCUSTOMSETTING_ANIMATION_CSS');
        $fields['TBCMSCUSTOMSETTING_ANIMATION_CSS'] = $tmp;

        $tmp = Configuration::get('TBCMSCUSTOMSETTING_HOVER_IMG');
        $fields['TBCMSCUSTOMSETTING_HOVER_IMG'] = $tmp;

        $tmp = Configuration::get('TBCMSCUSTOMSETTING_TAB_PRODUCT_ROW');
        $fields['TBCMSCUSTOMSETTING_TAB_PRODUCT_ROW'] = $tmp;

        $tmp = Configuration::get('TBCMSCUSTOMSETTING_PRODUCT_COLOR');
        $fields['TBCMSCUSTOMSETTING_PRODUCT_COLOR'] = $tmp;

        $tmp = Configuration::get('TBCMSCUSTOMSETTING_PRODUCT_LIST_VIEW');
        $fields['TBCMSCUSTOMSETTING_PRODUCT_LIST_VIEW'] = $tmp;

        $tmp = Configuration::get('TBCMSCUSTOMSETTING_THEME_OPTION');
        $fields['TBCMSCUSTOMSETTING_THEME_OPTION'] = $tmp;

        $tmp = Configuration::get('TBCMSFRONTSIDE_THEME_SETTING_SHOW');
        $fields['TBCMSFRONTSIDE_THEME_SETTING_SHOW'] = $tmp;

        $tmp = Configuration::get('TBCMSCUSTOMSETTING_BACKGROUND_IMAGE_REPEAT');
        $fields['TBCMSCUSTOMSETTING_BACKGROUND_IMAGE_REPEAT'] = $tmp;

        $tmp = Configuration::get('TBCMSCUSTOMSETTING_BACKGROUND_IMAGE_ATTACHMENT');
        $fields['TBCMSCUSTOMSETTING_BACKGROUND_IMAGE_ATTACHMENT'] = $tmp;

        $tmp = Configuration::get('TBCMSCUSTOMSETTING_THEME_COLOR_1');
        $fields['TBCMSCUSTOMSETTING_THEME_COLOR_1'] = $tmp;

        $tmp = Configuration::get('TBCMSCUSTOMSETTING_THEME_COLOR_2');
        $fields['TBCMSCUSTOMSETTING_THEME_COLOR_2'] = $tmp;

        $tmp = Configuration::get('TBCMSCUSTOMSETTING_BACKGROUND_COLOR');
        $fields['TBCMSCUSTOMSETTING_BACKGROUND_COLOR'] = $tmp;

        $tmp = Configuration::get('TBCMSCUSTOMSETTING_BACKGROUND_PATTERN');
        $fields['TBCMSCUSTOMSETTING_BACKGROUND_PATTERN'] = $tmp;
        $this->context->smarty->assign('background_pattern', $tmp);

        $tmp = Configuration::get('TBCMSCUSTOMSETTING_BACKGROUND_IMAGE_PATTERN_STATUS');
        $fields['TBCMSCUSTOMSETTING_BACKGROUND_IMAGE_PATTERN_STATUS'] = $tmp;

        $tmp = Configuration::get('TBCMSCUSTOMSETTING_BACKGROUND_OLD_PATTERN');
        $this->context->smarty->assign('custom_pattern', $tmp);

        $tmp = Configuration::get('TBCMSCUSTOMSETTING_BODY_BACKGROUND_OLD_PATTERN');
        $this->context->smarty->assign('custom_body_pattern', $tmp);

        $this->context->smarty->assign("front_pattern_path", _THEME_IMG_DIR_);
        $this->context->smarty->assign("path", $path);

        return $fields;
    }
    
    public function hookDisplayBackOfficeHeader()
    {
        // $this->context->controller->addJS(_PS_JS_DIR_.'jquery/jquery-1.11.0.min.js');
        $this->context->controller->addJQueryUI('ui.sortable');
        $this->context->controller->addjqueryPlugin('fancybox');
        $this->context->controller->addJS($this->_path.'views/js/back.js');
        $this->context->controller->addCSS($this->_path.'views/css/back.css');
    }

    public function hookdisplayTopOfferText()
    {
        $cookie = Context::getContext()->cookie;
        $id_lang = $cookie->id_lang;

        $html = '';

        if (!Cache::isStored('tbcmscustomsetting_displaytopoffertext')) {
            if (Configuration::get('TBCMSCUSTOMSETTING_CUSTOM_TEXT_STATUS')) {
                $html .= '<div class="tbheader-nav-offer-text">'
                .'<span>'.Configuration::get('TBCMSCUSTOMSETTING_CUSTOM_TEXT', $id_lang).'</span></div>';
            }
            $output = $html;
            Cache::store('tbcmscustomsetting_displaytopoffertext', $output);
        }

        return Cache::retrieve('tbcmscustomsetting_displaytopoffertext');
    }

    public function hookdisplayNav1()
    {
        return $this->hookdisplayTopOfferText();
    }

    public function hookdisplayNav1sub1()
    {
        return $this->hookdisplayTopOfferText();
    }

    public function hookdisplayMobileTopOfferText()
    {
        return $this->hookdisplayTopOfferText();
    }

    public function hookdisplayHome()
    {
        return $this->hookdisplayDownloadApps();
    }

    public function hookdisplayFooterBefore()
    {
        return $this->hookdisplayDownloadApps();
    }

    
    public function hookdisplayDownloadApps()
    {
        $cookie = Context::getContext()->cookie;
        $id_lang = $cookie->id_lang;
        $data = array();

        if (!Cache::isStored('tbcmscustomsetting_display_download_app.tpl')) {
            if (Configuration::get('TBCMSCUSTOMSETTING_DOWNLOAD_APPS_STATUS')) {
                $path = _MODULE_DIR_.$this->name."/views/img/";
                $tbcms_obj = new TbcmsCustomSettingStatus();
                $show_fields = $tbcms_obj->fieldStatusInformation();

                $data['link_image'] = Configuration::get('TBCMSCUSTOMSETTING_DOWNLOAD_APPS_IMAGE');
                $data['link_title'] = Configuration::get('TBCMSCUSTOMSETTING_DOWNLOAD_APPS_TITLE', $id_lang);
                $data['link_sub_title'] = Configuration::get('TBCMSCUSTOMSETTING_DOWNLOAD_APPS_SUB_TITLE', $id_lang);
                $data['link_desc'] = Configuration::get('TBCMSCUSTOMSETTING_DOWNLOAD_APPS_DESC', $id_lang);
                $data['apple_link'] = Configuration::get('TBCMSCUSTOMSETTING_DOWNLOAD_APPS_APPLE', $id_lang);
                $data['google_link'] = Configuration::get('TBCMSCUSTOMSETTING_DOWNLOAD_APPS_GOOLGE', $id_lang);
                $data['microsoft_link'] = Configuration::get('TBCMSCUSTOMSETTING_DOWNLOAD_APPS_MICROSOFT', $id_lang);
                $this->context->smarty->assign('data', $data);
                $this->context->smarty->assign('path', $path);
                $this->context->smarty->assign('show_fields', $show_fields);
                $output = $this->display(__FILE__, "views/templates/front/display_download_app.tpl");
            } else {
                $output = '';
            }
            Cache::store('tbcmscustomsetting_display_download_app.tpl', $output);
        }

        return Cache::retrieve('tbcmscustomsetting_display_download_app.tpl');
    }


    public function hookdisplayCopyRightText()
    {
        $cookie = Context::getContext()->cookie;
        $id_lang = $cookie->id_lang;

        if (!Cache::isStored('tbcmscustomsetting_display_copy_right_text.tpl')) {
            if (Configuration::get('TBCMSCUSTOMSETTING_COPY_RIGHT_TEXT_STATUS')
                && Configuration::get('TBCMSCUSTOMSETTING_COPY_RIGHT_TEXT', $id_lang)) {
                $tmp = Configuration::get('TBCMSCUSTOMSETTING_COPY_RIGHT_TEXT', $id_lang);
                $this->context->smarty->assign('copy_right_text', $tmp);
                
                $tmp = Configuration::get('TBCMSCUSTOMSETTING_COPY_RIGHT_LINK', $id_lang);
                $this->context->smarty->assign('copy_right_link', $tmp);
                $output = $this->display(__FILE__, "views/templates/front/display_copy_right_text.tpl");
            } else {
                $output = '';
            }
            Cache::store('tbcmscustomsetting_display_copy_right_text.tpl', $output);
        }

        return Cache::retrieve('tbcmscustomsetting_display_copy_right_text.tpl');
    }


    public function hookdisplayHeader()
    {
        $this->context->controller->addjqueryPlugin('fancybox');//blog module

        $this->context->controller->addJS($this->_path.'views/js/isview.js');
        $this->context->controller->addJS($this->_path.'views/js/jquery.balance.js');
        $this->context->controller->addJS($this->_path.'views/js/jquery.elevatezoom.min.js');
        $this->context->controller->addJS($this->_path.'views/js/owl.js');

        $this->context->controller->addJS($this->_path.'views/js/front.js');


        $this->context->controller->addCSS($this->_path.'views/css/front.css');

        if (Configuration::get('TBCMSCUSTOMSETTING_ANIMATION_CSS') == '1') {
            $this->context->controller->addCSS($this->_path.'views/css/animate.css');
        }

        $tbcms_setting = true;
        Media::addJsDef(array('tbcms_setting' => $tbcms_setting));

        $tmp = Configuration::get('TBCMSCUSTOMSETTING_ANIMATION_CSS');
        Media::addJsDef(array('TBCMSCUSTOMSETTING_ANIMATION_CSS' => $tmp));

        $tmp = Configuration::get('TBCMSCUSTOMSETTING_HOVER_IMG');
        Media::addJsDef(array('TBCMSCUSTOMSETTING_HOVER_IMG' => $tmp));

        $tmp = Configuration::get('TBCMSCUSTOMSETTING_MAIN_MENU_STICKY');
        Media::addJsDef(array('TBCMSCUSTOMSETTING_MAIN_MENU_STICKY' => $tmp));

        $tmp = Configuration::get('TBCMSCUSTOMSETTING_BOTTOM_OPTION');
        Media::addJsDef(array('TBCMSCUSTOMSETTING_BOTTOM_OPTION' => $tmp));
        
        $tmp = Configuration::get('TBCMSFRONTSIDE_THEME_SETTING_SHOW');
        Media::addJsDef(array('TBCMSFRONTSIDE_THEME_SETTING_SHOW' => $tmp));

        $tmp = Configuration::get('TBCMSCUSTOMSETTING_VERTICAL_MENU_OPEN');
        Media::addJsDef(array('TBCMSCUSTOMSETTING_VERTICAL_MENU_OPEN' => $tmp));

        $useSSL = ((isset($this->ssl) && $this->ssl && Configuration::get('PS_SSL_ENABLED'))
            || Tools::usingSecureMode()) ? true : false;
        $protocol_content = ($useSSL) ? 'https://' : 'http://';
        $baseDir = $protocol_content.Tools::getHttpHost().__PS_BASE_URI__;
        Media::addJsDef(array('baseDir' => $baseDir));

        $static_token = Tools::getToken(false);
        Media::addJsDef(array('static_token' => $static_token));
    }
}
