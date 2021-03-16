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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2019 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

// include_once(_PS_MODULE_DIR_.'tbcmssliderofferbanner/tbcmssliderofferbanner.php');
include_once(_PS_MODULE_DIR_.'tbcmsslider/classes/tbcmshomeslide.php');
include_once(_PS_MODULE_DIR_.'tbcmsslider/classes/tbcmsslider_image_upload.class.php');

class TbcmsSlider extends Module implements WidgetInterface
{
    protected $html = '';
    protected $default_width = 779;
    protected $default_speed = 5000;
    protected $default_pause_on_hover = 1;
    protected $default_wrap = 1;
    protected $animation = 1;
    protected $templateFile;

    public function __construct()
    {
        $this->name = 'tbcmsslider';
        $this->tab = 'front_office_features';
        $this->version = '2.1.9';
        $this->author = 'TemplateBeta';
        $this->need_instance = 0;
        $this->secure_key = Tools::encrypt($this->name);
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->getTranslator()->trans(
            'TemplateBeta - Slider',
            array(),
            'Modules.TbcmsSlider.Admin'
        );

        $this->description = $this->getTranslator()->trans(
            'Adds an image slider to your site.',
            array(),
            'Modules.TbcmsSlider.Admin'
        );
        
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
        $this->module_key = '';

        $this->confirmUninstall = $this->l('Warning: all the data saved in your database will be deleted.'.
            ' Are you sure you want uninstall this module?');
        
        $this->templateFile = 'module:tbcmsslider/views/templates/hook/slider.tpl';
    }

    public function install()
    {
        $this->installTab();
        if (parent::install() &&
            $this->registerHook('displayHeader') &&
            $this->registerHook('displayHome') &&
            // $this->registerHook('displayWrapperTop') &&
            // $this->registerHook('displayTopColumn') &&
            $this->registerHook('actionShopDataDuplication')
        ) {
            $shops = Shop::getContextListShopID();
            $shop_groups_list = array();

            foreach ($shops as $shop_id) {
                $shop_group_id = (int)Shop::getGroupFromShop($shop_id, true);

                if (!in_array($shop_group_id, $shop_groups_list)) {
                    $shop_groups_list[] = $shop_group_id;
                }

                $tmp = 'TBCMSSLIDER_SPEED';
                $res = Configuration::updateValue($tmp, $this->default_speed, false, $shop_group_id, $shop_id);

                $tmp = 'TBCMSSLIDER_PAUSE_ON_HOVER';
                $tmp_2 = $this->default_pause_on_hover;
                $res &= Configuration::updateValue($tmp, $tmp_2, false, $shop_group_id, $shop_id);

                $tmp = $this->default_wrap;
                $res &= Configuration::updateValue('TBCMSSLIDER_WRAP', $tmp, false, $shop_group_id, $shop_id);

                $tmp = $this->animation;
                $res &= Configuration::updateValue('TBCMSSLIDER_ANIMATION', $tmp, false, $shop_group_id, $shop_id);
            }

            if (count($shop_groups_list)) {
                foreach ($shop_groups_list as $shop_group_id) {
                    $tmp = 'TBCMSSLIDER_SPEED';
                    $res &= Configuration::updateValue($tmp, $this->default_speed, false, $shop_group_id);

                    $tmp = 'TBCMSSLIDER_PAUSE_ON_HOVER';
                    $res &= Configuration::updateValue($tmp, $this->default_pause_on_hover, false, $shop_group_id);

                    $tmp = 'TBCMSSLIDER_WRAP';
                    $res &= Configuration::updateValue($tmp, $this->default_wrap, false, $shop_group_id);

                    $tmp = 'TBCMSSLIDER_ANIMATION';
                    $res &= Configuration::updateValue($tmp, $this->animation, false, $shop_group_id);
                }
            }

            /* Sets up Global configuration */
            $res &= Configuration::updateValue('TBCMSSLIDER_SPEED', $this->default_speed);
            $res &= Configuration::updateValue('TBCMSSLIDER_PAUSE_ON_HOVER', $this->default_pause_on_hover);
            $res &= Configuration::updateValue('TBCMSSLIDER_WRAP', $this->default_wrap);
            $res &= Configuration::updateValue('TBCMSSLIDER_ANIMATION', $this->animation);

            /* Creates tables */
            $res &= $this->createTables();

            /* Adds samples */
            if ($res) {
                $this->installSamples();
            }

            return (bool)$res;
        }

        return false;
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
            $tab->name[$lang['id_lang']] = "Main Slider";
        }
        $tab->id_parent = $parentTab_2->id;
        $tab->module = $this->name;
        $response &= $tab->add();

        return $response;
    }

    /**
     * Adds samples
     */
    protected function installSamples()
    {
        $languages = Language::getLanguages(false);
        for ($i = 1; $i <= 2; ++$i) {
            $slide = new TbcmsHomeSlide();
            $slide->position = $i;
            $slide->active = 1;
            foreach ($languages as $language) {
                $slide->title[$language['id_lang']] = 'OPPO F7';
                $slide->description[$language['id_lang']] = '<h2>Capture the Real You</h2>
                <p>selfie Expert & Speedy Operational</p>';
                $slide->legend[$language['id_lang']] = 'sample-'.$i;
                $slide->btn_caption[$language['id_lang']] = 'Shop Now';
                $slide->class_name[$language['id_lang']] = 'tbmain-slider-contant-none';
                $slide->url[$language['id_lang']] = '#';
                $slide->image[$language['id_lang']] = 'demo_img_'.$i.'.jpg';
            }
            $slide->add();
        }
    }

    public function uninstall()
    {
        $this->uninstallTab();
        if (parent::uninstall()) {
            $res = $this->deleteTables();

            $this->deleteOfferBannerVariable();
            $res &= Configuration::deleteByName('TBCMSSLIDER_SPEED');
            $res &= Configuration::deleteByName('TBCMSSLIDER_PAUSE_ON_HOVER');
            $res &= Configuration::deleteByName('TBCMSSLIDER_WRAP');
            $res &= Configuration::deleteByName('TBCMSSLIDER_ANIMATION');

            return (bool)$res;
        }
        return false;
    }

    public function deleteOfferBannerVariable()
    {
        Configuration::deleteByName('TBCMSSLIDER_OFFER_BANNER_1');
        Configuration::deleteByName('TBCMSSLIDER_OFFER_BANNER_2');
        Configuration::deleteByName('TBCMSSLIDER_OFFER_BANNER_3');
        Configuration::deleteByName('TBCMSSLIDER_OFFER_BANNER_4');
        Configuration::deleteByName('TBCMSSLIDER_OFFER_BANNER_STATUS');
    }

    public function uninstallTab()
    {
        $id_tab = Tab::getIdFromClassName('Admin'.$this->name);
        $tab = new Tab($id_tab);
        $tab->delete();
        return true;
    }

    /**
     * Creates tables
     */
    protected function createTables()
    {
        /* Slides */
        $res = (bool)Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'tbcmsslider` (
                `id_tbcmsslider_slides` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `id_shop` int(10) unsigned NOT NULL,
                PRIMARY KEY (`id_tbcmsslider_slides`, `id_shop`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
        ');

        /* Slides configuration */
        $res &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'tbcmsslider_slides` (
              `id_tbcmsslider_slides` int(10) unsigned NOT NULL AUTO_INCREMENT,
              `position` int(10) unsigned NOT NULL DEFAULT \'0\',
              `active` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
              PRIMARY KEY (`id_tbcmsslider_slides`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
        ');

        /* Slides lang configuration */
        $res &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'tbcmsslider_slides_lang` (
              `id_tbcmsslider_slides` int(10) unsigned NOT NULL,
              `id_lang` int(10) unsigned NOT NULL,
              `title` varchar(255) NOT NULL,
              `description` text NOT NULL,
              `legend` varchar(255) NOT NULL,
              `url` varchar(255) NOT NULL,
              `btn_caption` varchar(255) NOT NULL,
              `class_name` varchar(255) NOT NULL,
              `image` varchar(255) NOT NULL,
              PRIMARY KEY (`id_tbcmsslider_slides`,`id_lang`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
        ');

        return $res;
    }

    /**
     * deletes tables
     */
    protected function deleteTables()
    {
        $slides = $this->getSlides();
        foreach ($slides as $slide) {
            $to_del = new TbcmsHomeSlide($slide['id_slide']);
            $to_del->delete();
        }

        return Db::getInstance()->execute('
            DROP TABLE IF EXISTS `'._DB_PREFIX_.'tbcmsslider`, `'._DB_PREFIX_.'tbcmsslider_slides`, `'._DB_PREFIX_.
            'tbcmsslider_slides_lang`;
        ');
    }

    public function getContent()
    {
        
        $this->html .= $this->headerHTML();
        if (Tools::isSubmit('submitSlide') || Tools::isSubmit('delete_id_slide') ||
            Tools::isSubmit('submitSlider') ||
            Tools::isSubmit('changeStatus')
        ) {
            if ($this->postValidation()) {
                $this->postProcess();
                $this->html .= $this->renderForm();
                $this->html .= $this->renderList();
            } else {
                $this->html .= $this->renderAddForm();
            }

            $this->clearCustomSmartyCache('tbcmsslider_display_home.tpl');
        } elseif (Tools::isSubmit('addSlide') ||
            (Tools::isSubmit('id_slide') && $this->slideExists((int)Tools::getValue('id_slide')))) {
            if (Tools::isSubmit('addSlide')) {
                $mode = 'add';
            } else {
                $mode = 'edit';
            }

            if ($mode == 'add') {
                if (Shop::getContext() != Shop::CONTEXT_GROUP && Shop::getContext() != Shop::CONTEXT_ALL) {
                    $this->html .= $this->renderAddForm();
                } else {
                    $this->html .= $this->getShopContextError(null, $mode);
                }
            } else {
                $associated_shop_ids = TbcmsHomeSlide::getAssociatedIdsShop((int)Tools::getValue('id_slide'));
                $context_shop_id = (int)Shop::getContextShopID();

                if ($associated_shop_ids === false) {
                    $this->html .= $this->getShopAssociationError((int)Tools::getValue('id_slide'));
                } elseif (Shop::getContext() != Shop::CONTEXT_GROUP && Shop::getContext() != Shop::CONTEXT_ALL
                    && in_array($context_shop_id, $associated_shop_ids)) {
                    if (count($associated_shop_ids) > 1) {
                        $this->html = $this->getSharedSlideWarning();
                    }
                    $this->html .= $this->renderAddForm();
                } else {
                    $shops_name_list = array();
                    foreach ($associated_shop_ids as $shop_id) {
                        $associated_shop = new Shop((int)$shop_id);
                        $shops_name_list[] = $associated_shop->name;
                    }
                    $this->html .= $this->getShopContextError($shops_name_list, $mode);
                }
            }
            $this->clearCustomSmartyCache('tbcmsslider_display_home.tpl');
        } else {
            $this->html .= $this->getWarningMultishopHtml()
                        .$this->getCurrentShopInfoMsg()
                        .$this->renderForm();

            if (Shop::getContext() != Shop::CONTEXT_GROUP && Shop::getContext() != Shop::CONTEXT_ALL) {
                $this->html .= $this->renderList();
            }
        }
        return $this->html;
    }

    public function clearCustomSmartyCache($cache_id)
    {
        if (Cache::isStored($cache_id)) {
            Cache::clean($cache_id);
        }
    }

    protected function postValidation()
    {
        $errors = array();

        /* Validation for Slider configuration */
        if (Tools::isSubmit('submitSlider')) {
            if (!Validate::isInt(Tools::getValue('TBCMSSLIDER_SPEED'))) {
                $errors[] = $this->getTranslator()->trans('Invalid values', array(), 'Modules.TbcmsSlider.Admin');
            }
        } elseif (Tools::isSubmit('changeStatus')) {
            if (!Validate::isInt(Tools::getValue('id_slide'))) {
                $errors[] = $this->getTranslator()->trans('Invalid slide', array(), 'Modules.TbcmsSlider.Admin');
            }
        } elseif (Tools::isSubmit('submitSlide')) {
            /* Checks state (active) */
            if (!Validate::isInt(Tools::getValue('active_slide')) || (Tools::getValue('active_slide') != 0
                && Tools::getValue('active_slide') != 1)) {
                $errors[] = $this->getTranslator()->trans(
                    'Invalid slide state.',
                    array(),
                    'Modules.TbcmsSlider.Admin'
                );
            }
            /* Checks position */
            if (!Validate::isInt(Tools::getValue('position')) || (Tools::getValue('position') < 0)) {
                $tmp = 'Invalid slide position.';
                $errors[] = $this->getTranslator()->trans($tmp, array(), 'Modules.TbcmsSlider.Admin');
            }
            /* If edit : checks id_slide */
            if (Tools::isSubmit('id_slide')) {
                if (!Validate::isInt(Tools::getValue('id_slide')) && !$this->slideExists(Tools::getValue('id_slide'))) {
                    $errors[] = $this->getTranslator()->trans(
                        'Invalid slide ID',
                        array(),
                        'Modules.TbcmsSlider.Admin'
                    );
                }
            }
            /* Checks title/url/legend/description/image */
            $languages = Language::getLanguages(false);
            foreach ($languages as $language) {
                if (Tools::strlen(Tools::getValue('title_' . $language['id_lang'])) > 255) {
                    $tmp = 'The title is too long.';
                    $errors[] = $this->getTranslator()->trans($tmp, array(), 'Modules.TbcmsSlider.Admin');
                }
                if (Tools::strlen(Tools::getValue('legend_' . $language['id_lang'])) > 255) {
                    $tmp = 'The caption is too long.';
                    $errors[] = $this->getTranslator()->trans($tmp, array(), 'Modules.TbcmsSlider.Admin');
                }

                if (Tools::strlen(Tools::getValue('btn_caption_' . $language['id_lang'])) > 255) {
                    $tmp = 'The caption is too long.';
                    $errors[] = $this->getTranslator()->trans($tmp, array(), 'Modules.TbcmsSlider.Admin');
                }
                if (Tools::strlen(Tools::getValue('class_name_' . $language['id_lang'])) > 255) {
                    $tmp = 'The caption is too long.';
                    $errors[] = $this->getTranslator()->trans($tmp, array(), 'Modules.TbcmsSlider.Admin');
                }

                if (Tools::strlen(Tools::getValue('url_' . $language['id_lang'])) > 255) {
                    $tmp = 'The URL is too long.';
                    $errors[] = $this->getTranslator()->trans($tmp, array(), 'Modules.TbcmsSlider.Admin');
                }
                if (Tools::strlen(Tools::getValue('description_' . $language['id_lang'])) > 4000) {
                    $tmp = 'The description is too long.';
                    $errors[] = $this->getTranslator()->trans($tmp, array(), 'Modules.TbcmsSlider.Admin');
                }
                if (Tools::strlen(Tools::getValue('url_' . $language['id_lang'])) > 0
                    && !Validate::isUrl(Tools::getValue('url_' . $language['id_lang']))) {
                    $tmp = 'The URL format is not correct.';
                    $errors[] = $this->getTranslator()->trans($tmp, array(), 'Modules.TbcmsSlider.Admin');
                }
                if (Tools::getValue('image_' . $language['id_lang']) != null
                    && !Validate::isFileName(Tools::getValue('image_' . $language['id_lang']))) {
                    $tmp = 'Invalid filename.';
                    $errors[] = $this->getTranslator()->trans($tmp, array(), 'Modules.TbcmsSlider.Admin');
                }
                if (Tools::getValue('image_old_' . $language['id_lang']) != null
                    && !Validate::isFileName(Tools::getValue('image_old_' . $language['id_lang']))) {
                    $tmp = 'Invalid filename.';
                    $errors[] = $this->getTranslator()->trans($tmp, array(), 'Modules.TbcmsSlider.Admin');
                }
            }

            /* Checks title/url/legend/description for default lang */
            $id_lang_default = (int)Configuration::get('PS_LANG_DEFAULT');
            if (Tools::strlen(Tools::getValue('url_' . $id_lang_default)) == 0) {
                $errors[] = $this->getTranslator()->trans(
                    'The URL is not set.',
                    array(),
                    'Modules.TbcmsSlider.Admin'
                );
            }
            if (!Tools::isSubmit('has_picture') && (!isset($_FILES['image_' . $id_lang_default])
                || empty($_FILES['image_' . $id_lang_default]['tmp_name']))) {
                $tmp = 'The image is not set.';
                $errors[] = $this->getTranslator()->trans($tmp, array(), 'Modules.TbcmsSlider.Admin');
            }
            if (Tools::getValue('image_old_'.$id_lang_default)
                && !Validate::isFileName(Tools::getValue('image_old_'.$id_lang_default))) {
                $tmp = 'The image is not set.';
                $errors[] = $this->getTranslator()->trans($tmp, array(), 'Modules.TbcmsSlider.Admin');
            }
        } elseif (Tools::isSubmit('delete_id_slide') && (!Validate::isInt(Tools::getValue('delete_id_slide'))
            || !$this->slideExists((int)Tools::getValue('delete_id_slide')))) {
            $errors[] = $this->getTranslator()->trans('Invalid slide ID', array(), 'Modules.TbcmsSlider.Admin');
        }

        if (count($errors)) {
            $this->html .= $this->displayError(implode('<br />', $errors));

            return false;
        }

        return true;
    }

    protected function postProcess()
    {
        $errors = array();
        $shop_context = Shop::getContext();

        /* Processes Slider */
        if (Tools::isSubmit('submitSlider')) {
            $shop_groups_list = array();
            $shops = Shop::getContextListShopID();

            foreach ($shops as $shop_id) {
                $shop_group_id = (int)Shop::getGroupFromShop($shop_id, true);

                if (!in_array($shop_group_id, $shop_groups_list)) {
                    $shop_groups_list[] = $shop_group_id;
                }

                $tmp = (int)Tools::getValue('TBCMSSLIDER_SPEED');
                $res = Configuration::updateValue('TBCMSSLIDER_SPEED', $tmp, false, $shop_group_id, $shop_id);

                $tmp = (int)Tools::getValue('TBCMSSLIDER_PAUSE_ON_HOVER');
                $res &= Configuration::updateValue(
                    'TBCMSSLIDER_PAUSE_ON_HOVER',
                    $tmp,
                    false,
                    $shop_group_id,
                    $shop_id
                );

                $tmp = (int)Tools::getValue('TBCMSSLIDER_WRAP');
                $res &= Configuration::updateValue('TBCMSSLIDER_WRAP', $tmp, false, $shop_group_id, $shop_id);

                $tmp = (int)Tools::getValue('TBCMSSLIDER_ANIMATION');
                $res &= Configuration::updateValue('TBCMSSLIDER_ANIMATION', $tmp, false, $shop_group_id, $shop_id);
            }

            /* Update global shop context if needed*/
            switch ($shop_context) {
                case Shop::CONTEXT_ALL:
                    $res &= Configuration::updateValue(
                        'TBCMSSLIDER_SPEED',
                        (int)Tools::getValue('TBCMSSLIDER_SPEED')
                    );

                    $tmp = (int)Tools::getValue('TBCMSSLIDER_PAUSE_ON_HOVER');
                    $res &= Configuration::updateValue('TBCMSSLIDER_PAUSE_ON_HOVER', $tmp);
                    $res &= Configuration::updateValue(
                        'TBCMSSLIDER_WRAP',
                        (int)Tools::getValue('TBCMSSLIDER_WRAP')
                    );
                    $tmp = (int)Tools::getValue('TBCMSSLIDER_ANIMATION');
                    $res &= Configuration::updateValue('TBCMSSLIDER_ANIMATION', $tmp);
                    if (count($shop_groups_list)) {
                        foreach ($shop_groups_list as $shop_group_id) {
                            $tmp = (int)Tools::getValue('TBCMSSLIDER_SPEED');
                            $res &= Configuration::updateValue('TBCMSSLIDER_SPEED', $tmp, false, $shop_group_id);

                            $tmp = 'TBCMSSLIDER_PAUSE_ON_HOVER';
                            $tmp_2 = (int)Tools::getValue('TBCMSSLIDER_PAUSE_ON_HOVER');
                            $res &= Configuration::updateValue($tmp, $tmp_2, false, $shop_group_id);

                            $tmp = (int)Tools::getValue('TBCMSSLIDER_WRAP');
                            $res &= Configuration::updateValue('TBCMSSLIDER_WRAP', $tmp, false, $shop_group_id);

                            $tmp = (int)Tools::getValue('TBCMSSLIDER_ANIMATION');
                            $res &= Configuration::updateValue('TBCMSSLIDER_ANIMATION', $tmp, false, $shop_group_id);
                        }
                    }
                    break;
                case Shop::CONTEXT_GROUP:
                    if (count($shop_groups_list)) {
                        foreach ($shop_groups_list as $shop_group_id) {
                            $tmp = (int)Tools::getValue('TBCMSSLIDER_SPEED');
                            $res &= Configuration::updateValue('TBCMSSLIDER_SPEED', $tmp, false, $shop_group_id);

                            $tmp = 'TBCMSSLIDER_PAUSE_ON_HOVER';
                            $tmp_2 = (int)Tools::getValue('TBCMSSLIDER_PAUSE_ON_HOVER');
                            $res &= Configuration::updateValue($tmp, $tmp_2, false, $shop_group_id);

                            $tmp = (int)Tools::getValue('TBCMSSLIDER_WRAP');
                            $res &= Configuration::updateValue('TBCMSSLIDER_WRAP', $tmp, false, $shop_group_id);

                            $tmp = (int)Tools::getValue('TBCMSSLIDER_ANIMATION');
                            $res &= Configuration::updateValue('TBCMSSLIDER_ANIMATION', $tmp, false, $shop_group_id);
                        }
                    }
                    break;
            }

            $this->clearCustomSmartyCache('tbcmsslider_display_home.tpl');

            if (!$res) {
                $tmp = 'The configuration could not be updated.';
                $tmp_2 = 'Modules.TbcmsSlider.Admin';
                $errors[] = $this->displayError($this->getTranslator()->trans($tmp, array(), $tmp_2));
            } else {
                Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules', true) .
                    '&conf=6&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name);
            }
        } elseif (Tools::isSubmit('changeStatus') && Tools::isSubmit('id_slide')) {
            $slide = new TbcmsHomeSlide((int)Tools::getValue('id_slide'));
            if ($slide->active == 0) {
                $slide->active = 1;
            } else {
                $slide->active = 0;
            }
            $res = $slide->update();
            $this->clearCustomSmartyCache('tbcmsslider_display_home.tpl');

            if ($res) {
                $this->html .= $this->displayConfirmation($this->getTranslator()->trans(
                    'Configuration updated',
                    array(),
                    'Admin.Notifications.Success'
                ));
            } else {
                $this->html .= $this->displayError($this->getTranslator()->trans(
                    'The configuration could not be updated.',
                    array(),
                    'Modules.TbcmsSlider.Admin'
                ));
            }
        } elseif (Tools::isSubmit('submitSlide')) {
            /* Sets ID if needed */
            if (Tools::getValue('id_slide')) {
                $slide = new TbcmsHomeSlide((int)Tools::getValue('id_slide'));
                if (!Validate::isLoadedObject($slide)) {
                    $tmp = $this->getTranslator()->trans('Invalid slide ID', array(), 'Modules.TbcmsSlider.Admin');
                    $this->html .= $this->displayError($tmp);
                    return false;
                }
            } else {
                $slide = new TbcmsHomeSlide();
            }
            /* Sets position */
            $slide->position = (int)Tools::getValue('position');
            /* Sets active */
            $slide->active = (int)Tools::getValue('active_slide');
            /* Sets each langue fields */
            $languages = Language::getLanguages(false);

            foreach ($languages as $language) {
                $slide->title[$language['id_lang']] = Tools::getValue('title_'.$language['id_lang']);
                $slide->url[$language['id_lang']] = Tools::getValue('url_'.$language['id_lang']);
                $slide->legend[$language['id_lang']] = Tools::getValue('legend_'.$language['id_lang']);
                $slide->class_name[$language['id_lang']] = Tools::getValue('class_name_'.$language['id_lang']);
                $slide->btn_caption[$language['id_lang']] = Tools::getValue('btn_caption_'.$language['id_lang']);
                $slide->description[$language['id_lang']] = Tools::getValue('description_'.$language['id_lang']);

                /* Uploads image and sets slide */
                $tmp = Tools::substr(strrchr($_FILES['image_'.$language['id_lang']]['name'], '.'), 1);
                $type = Tools::strtolower($tmp);
                $imagesize = @getimagesize($_FILES['image_'.$language['id_lang']]['tmp_name']);
                $tmp = Tools::substr(strrchr($imagesize['mime'], '/'), 1);
                if (isset($_FILES['image_'.$language['id_lang']]) &&
                    isset($_FILES['image_'.$language['id_lang']]['tmp_name']) &&
                    !empty($_FILES['image_'.$language['id_lang']]['tmp_name']) &&
                    !empty($imagesize) &&
                    in_array(Tools::strtolower($tmp), array('jpg', 'gif', 'jpeg', 'png')) &&
                    in_array($type, array('jpg', 'gif', 'jpeg', 'png'))
                ) {
                    $temp_name = tempnam(_PS_TMP_IMG_DIR_, 'PS');
                    $salt = Tools::substr(sha1(microtime()), 0, 20);
                    $path = dirname(__FILE__).'/views/img/';
                    $file_name = $salt.'_'.$_FILES['image_'.$language['id_lang']]['name'];
                    if ($error = ImageManager::validateUpload($_FILES['image_'.$language['id_lang']])) {
                        $errors[] = $error;
                    } elseif (!$temp_name
                        || !move_uploaded_file($_FILES['image_'.$language['id_lang']]['tmp_name'], $temp_name)) {
                        return false;
                    } elseif (!ImageManager::resize($temp_name, $path.$file_name, null, null, $type)) {
                        $errors[] = $this->displayError($this->getTranslator()->trans(
                            'An error occurred during the image upload process.',
                            array(),
                            'Admin.Notifications.Error'
                        ));
                    }

                    if (file_exists($path.'fileType')) {
                        @unlink($path.'fileType');
                    }

                    if (isset($temp_name)) {
                        @unlink($temp_name);
                    }

                    // Start Remove Old Image
                    $id = Tools::getValue('id_slide');
                    if (isset($id) && !empty($id)) {
                        $query = 'SELECT * FROM '._DB_PREFIX_.'tbcmsslider_slides_lang WHERE'
                            .' id_tbcmsslider_slides = '
                            .Tools::getValue('id_slide') .' AND id_lang = '.$language['id_lang'];
                        $ans = Db::getInstance()->executeS($query);

                        if (isset($ans[0]['image']) && !empty($ans[0]['image'])) {
                            $res = preg_match('/^demo_img_.*$/', $ans[0]['image']);
                            if (file_exists($path.$ans[0]['image']) && $res != '1') {
                                @unlink($path.$ans[0]['image']);
                            }
                        }
                    }
                    // End Remove Old Image
                    
                    $slide->image[$language['id_lang']] = $salt.'_'.$_FILES['image_'.$language['id_lang']]['name'];
                } elseif (Tools::getValue('image_old_'.$language['id_lang']) != '') {
                    $slide->image[$language['id_lang']] = Tools::getValue('image_old_' . $language['id_lang']);
                }
            }

            /* Processes if no errors  */
            if (!$errors) {
                /* Adds */
                if (!Tools::getValue('id_slide')) {
                    if (!$slide->add()) {
                        $errors[] = $this->displayError($this->getTranslator()->trans(
                            'The slide could not be added.',
                            array(),
                            'Modules.TbcmsSlider.Admin'
                        ));
                    }
                } elseif (!$slide->update()) {
                    $tmp = 'The slide could not be updated.';
                    $tmp_2 = 'Modules.TbcmsSlider.Admin';
                    $errors[] = $this->displayError($this->getTranslator()->trans($tmp, array(), $tmp_2));
                }
                $this->clearCustomSmartyCache('tbcmsslider_display_home.tpl');
            }
        } elseif (Tools::isSubmit('delete_id_slide')) {
            $slide = new TbcmsHomeSlide((int)Tools::getValue('delete_id_slide'));

            $file_name = $slide->image;
            foreach ($file_name as $file) {
                $path = dirname(__FILE__).'/views/img/';
                $res = preg_match('/^demo_img_.*$/', $ans[0]['image']);

                if (file_exists($path.$file) && $res != '1') {
                    @unlink($path.$file);
                }
            }

            $res = $slide->delete();
            $this->clearCustomSmartyCache('tbcmsslider_display_home.tpl');
            if (!$res) {
                $this->html .= $this->displayError('Could not delete.');
            } else {
                Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules', true) . '&conf=1&configure='
                    . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name);
            }
        }

        /* Display errors if needed */
        if (count($errors)) {
            $this->html .= $this->displayError(implode('<br />', $errors));
        } elseif (Tools::isSubmit('submitSlide') && Tools::getValue('id_slide')) {
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules', true) . '&conf=4&configure='
                . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name);
        } elseif (Tools::isSubmit('submitSlide')) {
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules', true) . '&conf=3&configure='
                . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name);
        }
    }

    public function hookdisplayHeader($params)
    {
        $this->context->controller->addCSS($this->_path.'views/css/front.css');
        $this->context->controller->addJS($this->_path.'views/js/nivo.js');
        $this->context->controller->addJS($this->_path.'views/js/front.js');

        // $id_shop_group = Shop::getContextShopGroupID();
        // $id_shop = Shop::getContextShopID();
        
        // $tmp_1 = Configuration::get('TBCMSSLIDER_SPEED', null, $id_shop_group, $id_shop);
        // $tmp_2 = Configuration::get('TBCMSSLIDER_PAUSE_ON_HOVER', null, $id_shop_group, $id_shop);
        // $tmp_3 = Configuration::get('TBCMSSLIDER_WRAP', null, $id_shop_group, $id_shop);
        // $tmp_4 = Configuration::get('TBCMSSLIDER_ANIMATION', null, $id_shop_group, $id_shop);

        // Media::addJsDef(array('TBCMSSLIDER_SPEED' => $tmp_1,
        //                     'TBCMSSLIDER_PAUSE_ON_HOVER' => $tmp_2,
        //                     'TBCMSSLIDER_WRAP' => $tmp_3,
        //                     'TBCMSSLIDER_ANIMATION' => $tmp_4
        //                 ));
    }

    public function renderWidget($hookName = null, array $configuration = array())
    {
        $data = $this->getWidgetVariables($hookName, $configuration);

        if (!Cache::isStored('tbcmsslider_display_home.tpl')) {
            if ($data) {
                $offer_banner = '';
                // if (Module::isEnabled('tbcmssliderofferbanner')) {
                //     $obj = new TbcmsSliderOfferBanner();
                //     $offer_banner = $obj->showResult();
                // }
                $this->context->smarty->assign('offer_banner', $offer_banner);
                $this->context->smarty->assign('data', $data['slides']);
                $output = $this->display(__FILE__, "views/templates/front/display_home.tpl");
            } else {
                $output = '';
            }

            Cache::store('tbcmsslider_display_home.tpl', $output);
        }
        return Cache::retrieve('tbcmsslider_display_home.tpl');
    }

    public function getWidgetVariables($hookName = null, array $configuration = array())
    {
        $slides = $this->getSlides(true);

        if (is_array($slides)) {
            foreach ($slides as &$slide) {
                $slide['sizes'] = @getimagesize((dirname(__FILE__) . DIRECTORY_SEPARATOR . 'images' .
                    DIRECTORY_SEPARATOR . $slide['image']));
                if (isset($slide['sizes'][3]) && $slide['sizes'][3]) {
                    $slide['size'] = $slide['sizes'][3];
                }
            }
        }

        $config = $this->getConfigFieldsValues();

        $data_slider_js = array(
            'speed' => $config['TBCMSSLIDER_SPEED'],
            'pause' => $config['TBCMSSLIDER_PAUSE_ON_HOVER'] ? 'true' : 'false',
            'wrap' => $config['TBCMSSLIDER_WRAP'] ? 'true' : 'false',
            'animation' => $config['TBCMSSLIDER_ANIMATION'] ? 'true' : 'false',
        );
        $this->context->smarty->assign('main_slider_js', $data_slider_js);

        $a = array(
                'slides' => $slides,
            );

        return $a;
    }

    private function updateUrl($link)
    {
        if (Tools::substr($link, 0, 7) !== "http://" && Tools::substr($link, 0, 8) !== "https://") {
            $link = "http://" . $link;
        }

        return $link;
    }

    public function hookActionShopDataDuplication($params)
    {
        Db::getInstance()->execute('
            INSERT IGNORE INTO '._DB_PREFIX_.'tbcmsslider (id_tbcmsslider_slides, id_shop)
            SELECT id_tbcmsslider_slides, '.(int)$params['new_id_shop'].'
            FROM '._DB_PREFIX_.'tbcmsslider
            WHERE id_shop = '.(int)$params['old_id_shop']);
        $this->clearCustomSmartyCache('tbcmsslider_display_home.tpl');
    }

    public function headerHTML()
    {
        if (Tools::getValue('controller') != 'AdminModules' && Tools::getValue('configure') != $this->name) {
            return;
        }

        $this->context->controller->addJqueryUI('ui.sortable');
        /* Style & js for fieldset 'slides configuration' */
        $script = '<script type="text/javascript">
            $(function() {
                var $mySlides = $("#slides");
                $mySlides.sortable({
                    opacity: 0.6,
                    cursor: "move",
                    update: function() {
                        var order = $(this).sortable("serialize") + "&action=updateSlidesPosition";
                        $.post("'.$this->context->shop->physical_uri.$this->context->shop->virtual_uri
                        .'modules/'.$this->name.'/classes/tbcmsajaxslider.php?secure_key='
                        .$this->secure_key.'", order);
                        }
                    });
                $mySlides.hover(function() {
                    $(this).css("cursor","move");
                    },
                    function() {
                    $(this).css("cursor","auto");
                });
            });
        </script>';

        return $script;
    }

    public function getNextPosition()
    {
        $tmp = (int)$this->context->shop->id;
        $row = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('
            SELECT MAX(hss.`position`) AS `next_position`
            FROM `'._DB_PREFIX_.'tbcmsslider_slides` hss, `'._DB_PREFIX_.'tbcmsslider` hs
            WHERE hss.`id_tbcmsslider_slides` = hs.`id_tbcmsslider_slides` AND hs.`id_shop` = '.$tmp);

        return (++$row['next_position']);
    }

    public function getSlides($active = null)
    {
        $this->context = Context::getContext();
        $id_shop = $this->context->shop->id;
        $id_lang = $this->context->language->id;

        $query = 'SELECT hs.`id_tbcmsslider_slides` as id_slide, hss.`position`, hss.`active`, hssl.`title`,
            hssl.`url`, hssl.`legend`, hssl.`btn_caption`, hssl.`class_name`, hssl.`description`, hssl.`image`
            FROM '._DB_PREFIX_.'tbcmsslider hs
            LEFT JOIN '._DB_PREFIX_.'tbcmsslider_slides hss ON (hs.id_tbcmsslider_slides'
                .' = hss.id_tbcmsslider_slides)
            LEFT JOIN '._DB_PREFIX_.'tbcmsslider_slides_lang hssl ON (hss.id_tbcmsslider_slides = '
            .'hssl.id_tbcmsslider_slides)
            WHERE id_shop = '.(int)$id_shop.'
            AND hssl.id_lang = '.(int)$id_lang.
            ($active ? ' AND hss.`active` = 1' : ' ').'
            ORDER BY hss.position';
        $slides = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);

        foreach ($slides as &$slide) {
            $slide['image_url'] = $this->context->link->getMediaLink(_MODULE_DIR_.'tbcmsslider/views/img/'
                .$slide['image']);
            // $slide['url'] = $this->updateUrl($slide['url']);
            $slide['url'] = $slide['url'];
        }

        return $slides;
    }

    public function getAllImagesBySlidesId($id_slides, $active = null, $id_shop = null)
    {
        $this->context = Context::getContext();
        $images = array();

        if (!isset($id_shop)) {
            $id_shop = $this->context->shop->id;
        }

        $results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
            SELECT hssl.`image`, hssl.`id_lang`
            FROM '._DB_PREFIX_.'tbcmsslider hs
            LEFT JOIN '._DB_PREFIX_.'tbcmsslider_slides hss ON (hs.id_tbcmsslider_slides'
                .' = hss.id_tbcmsslider_slides)
            LEFT JOIN '._DB_PREFIX_.'tbcmsslider_slides_lang hssl ON (hss.id_tbcmsslider_slides = '
            .'hssl.id_tbcmsslider_slides)
            WHERE hs.`id_tbcmsslider_slides` = '.(int)$id_slides.' AND hs.`id_shop` = '.(int)$id_shop.
            ($active ? ' AND hss.`active` = 1' : ' '));

        foreach ($results as $result) {
            $images[$result['id_lang']] = $result['image'];
        }

        return $images;
    }

    public function displayStatus($id_slide, $active)
    {
        if ((int)$active == 0) {
            $title = $this->getTranslator()->trans('Disabled', array(), 'Admin.Global');
        } else {
            $title = $this->getTranslator()->trans('Enabled', array(), 'Admin.Global');
        }

        $icon = ((int)$active == 0 ? 'icon-remove' : 'icon-check');
        $class = ((int)$active == 0 ? 'btn-danger' : 'btn-success');
        $script = '<a class="btn '.$class.'" href="'.AdminController::$currentIndex.
            '&configure='.$this->name.
                '&token='.Tools::getAdminTokenLite('AdminModules').
                '&changeStatus&id_slide='.(int)$id_slide.'" title="'.$title.'"><i class="'.$icon.'"></i> '.$title
                .'</a>';

        return $script;
    }

    public function slideExists($id_slide)
    {
        $req = 'SELECT hs.`id_tbcmsslider_slides` as id_slide
                FROM `'._DB_PREFIX_.'tbcmsslider` hs
                WHERE hs.`id_tbcmsslider_slides` = '.(int)$id_slide;
        $row = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($req);

        return ($row);
    }

    public function renderList()
    {
        $slides = $this->getSlides();
        foreach ($slides as $key => $slide) {
            $slides[$key]['status'] = $this->displayStatus($slide['id_slide'], $slide['active']);
            $associated_shop_ids = TbcmsHomeSlide::getAssociatedIdsShop((int)$slide['id_slide']);
            if ($associated_shop_ids && count($associated_shop_ids) > 1) {
                $slides[$key]['is_shared'] = true;
            } else {
                $slides[$key]['is_shared'] = false;
            }
        }

        $this->context->smarty->assign(
            array(
                'link' => $this->context->link,
                'slides' => $slides,
                'image_baseurl' => $this->_path.'views/img/'
            )
        );

        return $this->display(__FILE__, 'list.tpl');
    }

    public function renderAddForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->getTranslator()->trans(
                        'Slide information',
                        array(),
                        'Modules.TbcmsSlider.Admin'
                    ),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(
                    array(
                        'type' => 'file_lang',
                        'label' => $this->getTranslator()->trans('Image', array(), 'Admin.Global'),
                        'name' => 'image',
                        'required' => true,
                        'lang' => true,
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->getTranslator()->trans('Title', array(), 'Admin.Global'),
                        'name' => 'title',
                        'lang' => true,
                    ),
                    array(
                        'type' => 'radio_btn',
                        'label' => $this->getTranslator()->trans('Text Alignment', array(), 'Admin.Global'),
                        'name' => 'class_name',
                        'lang' => true,
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->getTranslator()->trans('Button Caption', array(), 'Admin.Global'),
                        'name' => 'btn_caption',
                        'lang' => true,
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->getTranslator()->trans('Target URL', array(), 'Modules.TbcmsSlider.Admin'),
                        'name' => 'url',
                        'required' => true,
                        'lang' => true,
                    ),
                    // array(
                    //     'type' => 'hidden', // This is Not Use in This Theme
                    //     'label' => $this->getTranslator()->trans('Caption', array(), 'Modules.TbcmsSlider.Admin'),
                    //     'name' => 'legend',
                    //     'lang' => true,
                    // ),
                    array(
                        'type' => 'textarea',
                        'label' => $this->getTranslator()->trans('Description', array(), 'Admin.Global'),
                        'name' => 'description',
                        'autoload_rte' => true,
                        'lang' => true,
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->getTranslator()->trans('Enabled', array(), 'Admin.Global'),
                        'name' => 'active_slide',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->getTranslator()->trans('Yes', array(), 'Admin.Global')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->getTranslator()->trans('No', array(), 'Admin.Global')
                            )
                        ),
                    ),
                ),
                'submit' => array(
                    'title' => $this->getTranslator()->trans('Save', array(), 'Admin.Actions'),
                )
            ),
        );

        if (Tools::isSubmit('id_slide') && $this->slideExists((int)Tools::getValue('id_slide'))) {
            $slide = new TbcmsHomeSlide((int)Tools::getValue('id_slide'));
            $fields_form['form']['input'][] = array('type' => 'hidden', 'name' => 'id_slide');
            $fields_form['form']['images'] = $slide->image;

            $has_picture = true;

            foreach (Language::getLanguages(false) as $lang) {
                if (!isset($slide->image[$lang['id_lang']])) {
                    $has_picture &= false;
                }
            }

            if ($has_picture) {
                $fields_form['form']['input'][] = array('type' => 'hidden', 'name' => 'has_picture');
            }
        }

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;

        $tmp = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG');
        $helper->allow_employee_form_lang = $tmp ? $tmp : 0;
        $this->fields_form = array();
        $helper->module = $this;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitSlide';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.
            '&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $language = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->tpl_vars = array(
            'base_url' => $this->context->shop->getBaseURL(),
            'language' => array(
                'id_lang' => $language->id,
                'iso_code' => $language->iso_code
            ),
            'fields_value' => $this->getAddFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
            'image_baseurl' => $this->_path.'views/img/'
        );

        $helper->override_folder = '/';

        $languages = Language::getLanguages(false);

        if (count($languages) > 1) {
            return $this->getMultiLanguageInfoMsg() . $helper->generateForm(array($fields_form));
        } else {
            return $helper->generateForm(array($fields_form));
        }
    }

    public function renderForm()
    {
        // $wrap_lable = $this->getTranslator()->trans('Loop forever', array(), 'Modules.TbcmsSlider.Admin');
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->getTranslator()->trans('Settings', array(), 'Admin.Global'),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->getTranslator()->trans('Speed', array(), 'Modules.TbcmsSlider.Admin'),
                        'name' => 'TBCMSSLIDER_SPEED',
                        'suffix' => 'milliseconds',
                        'class' => 'fixed-width-sm',
                        'desc' => $this->getTranslator()->trans(
                            'The duration of the transition between two slides.',
                            array(),
                            'Modules.TbcmsSlider.Admin'
                        )
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->getTranslator()->trans(
                            'Pause on hover',
                            array(),
                            'Modules.TbcmsSlider.Admin'
                        ),
                        'name' => 'TBCMSSLIDER_PAUSE_ON_HOVER',
                        'desc' => $this->getTranslator()->trans(
                            'Stop sliding when the mouse cursor is over the slideshow.',
                            array(),
                            'Modules.TbcmsSlider.Admin'
                        ),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->getTranslator()->trans('Enabled', array(), 'Admin.Global')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->getTranslator()->trans('Disabled', array(), 'Admin.Global')
                            )
                        ),
                    ),
                    // array(
                    //     'type' => 'switch',
                    //     'label' => $wrap_lable,
                    //     'name' => 'TBCMSSLIDER_WRAP',
                    //     'desc' => $this->getTranslator()->trans(
                    //         'Loop or stop after the last slide.',
                    //         array(),
                    //         'Modules.TbcmsSlider.Admin'
                    //     ),
                    //     'values' => array(
                    //         array(
                    //             'id' => 'active_on',
                    //             'value' => 1,
                    //             'label' => $this->getTranslator()->trans('Enabled', array(), 'Admin.Global')
                    //         ),
                    //         array(
                    //             'id' => 'active_off',
                    //             'value' => 0,
                    //             'label' => $this->getTranslator()->trans('Disabled', array(), 'Admin.Global')
                    //         )
                    //     ),
                    // ),
                    // array(
                    //     'type' => 'switch',
                    //     'label' => $this->getTranslator()->trans(
                    //         'Slider Animation',
                    //         array(),
                    //         'Modules.TbcmsSlider.Admin'
                    //     ),
                    //     'name' => 'TBCMSSLIDER_ANIMATION',
                    //     'desc' => $this->getTranslator()->trans(
                    //         'Show Front Side Slider Animation.',
                    //         array(),
                    //         'Modules.TbcmsSlider.Admin'
                    //     ),
                    //     'values' => array(
                    //         array(
                    //             'id' => 'active_on',
                    //             'value' => 1,
                    //             'label' => $this->getTranslator()->trans('Enabled', array(), 'Admin.Global')
                    //         ),
                    //         array(
                    //             'id' => 'active_off',
                    //             'value' => 0,
                    //             'label' => $this->getTranslator()->trans('Disabled', array(), 'Admin.Global')
                    //         )
                    //     ),
                    // ),
                ),
                'submit' => array(
                    'title' => $this->getTranslator()->trans('Save', array(), 'Admin.Actions'),
                )
            ),
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $tmp = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG');
        $helper->allow_employee_form_lang = $tmp ? $tmp : 0;
        $this->fields_form = array();

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitSlider';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.
            $this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );

        return $helper->generateForm(array($fields_form));
    }

    public function getConfigFieldsValues()
    {
        $id_shop_group = Shop::getContextShopGroupID();
        $id_shop = Shop::getContextShopID();

        $tmp = Configuration::get('TBCMSSLIDER_SPEED', null, $id_shop_group, $id_shop);
        $tmp_2 = Configuration::get('TBCMSSLIDER_PAUSE_ON_HOVER', null, $id_shop_group, $id_shop);
        $tmp_3 = Configuration::get('TBCMSSLIDER_WRAP', null, $id_shop_group, $id_shop);
        $tmp_4 = Configuration::get('TBCMSSLIDER_ANIMATION', null, $id_shop_group, $id_shop);
        return array(
            'TBCMSSLIDER_SPEED' => Tools::getValue('TBCMSSLIDER_SPEED', $tmp),
            'TBCMSSLIDER_PAUSE_ON_HOVER' => Tools::getValue('TBCMSSLIDER_PAUSE_ON_HOVER', $tmp_2),
            'TBCMSSLIDER_WRAP' => Tools::getValue('TBCMSSLIDER_WRAP', $tmp_3),
            'TBCMSSLIDER_ANIMATION' => Tools::getValue('TBCMSSLIDER_ANIMATION', $tmp_4),
        );
    }

    public function getAddFieldsValues()
    {
        $fields = array();

        if (Tools::isSubmit('id_slide') && $this->slideExists((int)Tools::getValue('id_slide'))) {
            $slide = new TbcmsHomeSlide((int)Tools::getValue('id_slide'));
            $fields['id_slide'] = (int)Tools::getValue('id_slide', $slide->id);
        } else {
            $slide = new TbcmsHomeSlide();
        }

        $fields['active_slide'] = Tools::getValue('active_slide', $slide->active);
        $fields['has_picture'] = true;

        $languages = Language::getLanguages(false);

        foreach ($languages as $lang) {
            $fields['image'][$lang['id_lang']] = Tools::getValue('image_'.(int)$lang['id_lang']);
            
            $tmp = Tools::getValue('title_'.(int)$lang['id_lang'], $slide->title[$lang['id_lang']]);
            $fields['title'][$lang['id_lang']] = $tmp;
            
            $tmp = Tools::getValue('url_'.(int)$lang['id_lang'], $slide->url[$lang['id_lang']]);
            $fields['url'][$lang['id_lang']] = $tmp;
            
            $tmp = Tools::getValue('legend_'.(int)$lang['id_lang'], $slide->legend[$lang['id_lang']]);
            $fields['legend'][$lang['id_lang']] = $tmp;

            $tmp = Tools::getValue('btn_caption_'.(int)$lang['id_lang'], $slide->btn_caption[$lang['id_lang']]);
            $fields['btn_caption'][$lang['id_lang']] = $tmp;

            $tmp = Tools::getValue('class_name_'.(int)$lang['id_lang'], $slide->class_name[$lang['id_lang']]);
            $fields['class_name'][$lang['id_lang']] = $tmp;
            
            $tmp = Tools::getValue('description_'.(int)$lang['id_lang'], $slide->description[$lang['id_lang']]);
            $fields['description'][$lang['id_lang']] = $tmp;
        }

        return $fields;
    }

    protected function getMultiLanguageInfoMsg()
    {
        return '<p class="alert alert-warning">'.
                    $this->getTranslator()->trans('Since multiple languages are activated on your shop, please mind'
                        .' to upload your image for each one of them', array(), 'Modules.TbcmsSlider.Admin').
                '</p>';
    }

    protected function getWarningMultishopHtml()
    {
        if (Shop::getContext() == Shop::CONTEXT_GROUP || Shop::getContext() == Shop::CONTEXT_ALL) {
            return '<p class="alert alert-warning">' .
            $this->getTranslator()->trans('You cannot manage slides items from a "All Shops" or a "Group Shop" context,'
                .' select directly the shop you want to edit', array(), 'Modules.TbcmsSlider.Admin') .
            '</p>';
        } else {
            return '';
        }
    }

    protected function getShopContextError($shop_contextualized_name, $mode)
    {
        if (is_array($shop_contextualized_name)) {
            $shop_contextualized_name = implode('<br/>', $shop_contextualized_name);
        }

        if ($mode == 'edit') {
            $tmp = 'You can only edit this slide from the shop(s) context: %s';
            return '<p class="alert alert-danger">' .
            $this->trans($tmp, array($shop_contextualized_name), 'Modules.TbcmsSlider.Admin') .
            '</p>';
        } else {
            $tmp = 'You cannot add slides from a "All Shops" or a "Group Shop" context';
            return '<p class="alert alert-danger">' .
            $this->trans($tmp, array(), 'Modules.TbcmsSlider.Admin') .
            '</p>';
        }
    }

    protected function getShopAssociationError($id_slide)
    {
        return '<p class="alert alert-danger">'.
                        $this->trans(
                            'Unable to get slide shop association information (id_slide: %d)',
                            array((int)$id_slide),
                            'Modules.TbcmsSlider.Admin'
                        ) .
                '</p>';
    }


    protected function getCurrentShopInfoMsg()
    {
        $shop_info = null;

        if (Shop::isFeatureActive()) {
            if (Shop::getContext() == Shop::CONTEXT_SHOP) {
                $tmp = 'The modifications will be applied to shop: %s';
                $shop_info = $this->trans($tmp, array($this->context->shop->name), 'Modules.TbcmsSlider.Admin');
            } elseif (Shop::getContext() == Shop::CONTEXT_GROUP) {
                $tmp = 'The modifications will be applied to this group: %s';
                $shop_info = $this->trans(
                    $tmp,
                    array(Shop::getContextShopGroup()->name),
                    'Modules.TbcmsSlider.Admin'
                );
            } else {
                $tmp = 'The modifications will be applied to all shops and shop groups';
                $shop_info = $this->trans($tmp, array(), 'Modules.TbcmsSlider.Admin');
            }

            return '<div class="alert alert-info">'.
                        $shop_info.
                    '</div>';
        } else {
            return '';
        }
    }

    protected function getSharedSlideWarning()
    {
        return '<p class="alert alert-warning">'.
                    $this->trans('This slide is shared with other shops! All shops associated to this slide will apply'
                        .' modifications made here', array(), 'Modules.TbcmsSlider.Admin').
                '</p>';
    }
}
