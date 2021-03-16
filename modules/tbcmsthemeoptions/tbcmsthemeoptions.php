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

include_once(_PS_MODULE_DIR_.'tbcmscustomsetting/classes/tbcustomsetting_common_list.class.php');

class TbcmsThemeOptions extends Module
{
    public function __construct()
    {
        $this->name = 'tbcmsthemeoptions';
        $this->tab = 'front_office_features';
        $this->version = '2.1.9';
        $this->author = 'TemplateBeta';
        $this->need_instance = 0;

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('TemplateBeta - Theme Options');
        $this->description = $this->l('Its Show Theme Options on Front Side');

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
        $this->module_key = '';

        $this->confirmUninstall = $this->l('Warning: all the data saved in your database will be deleted.'.
            ' Are you sure you want uninstall this module?');
    }


    public function install()
    {
        Configuration::updateValue('TBCMSFRONTSIDE_THEME_SETTING_SHOW', '1');
        return parent::install()
            && $this->registerHook('displayHeader');
            //&& $this->registerHook('displayThemeOptions');
    }

    public function uninstall()
    {
        Configuration::updateValue('TBCMSFRONTSIDE_THEME_SETTING_SHOW', '0');
        return parent::uninstall();
    }


    public function hookdisplayHeader()
    {
        $tmp = $this->context->link->getModuleLink('tbcmsthemeoptions', 'default');
        Media::addJsDef(array('getThemeOptionsLink' => $tmp));

        $this->context->controller->addJS($this->_path.'views/js/jquery.storageapi.min.js');
        $this->context->controller->addJS($this->_path.'views/js/jquery.minicolors.js');
        $this->context->controller->addJS($this->_path.'views/js/bootstrap-toggle.min.js');
        $this->context->controller->addJS($this->_path.'views/js/front.js');

        $this->context->controller->addCSS($this->_path.'views/css/jquery.minicolors.css');
        $this->context->controller->addCSS($this->_path.'views/css/bootstrap-toggle.min.css');
        $this->context->controller->addCSS($this->_path.'views/css/front.css');

        $obj = new TbcmsCustomSettingCommonList();
        $title_font_list = $obj->titleFontList();
        $body_font_list = $obj->bodyFontList();

        $this->context->smarty->assign('title_font_list', $title_font_list);
        $this->context->smarty->assign('body_font_list', $body_font_list);

    }

    /*public function hookdisplayThemeOptions()
    {
        $obj = new TbcmsCustomSettingCommonList();
        $title_font_list = $obj->titleFontList();
        $body_font_list = $obj->bodyFontList();

        $this->context->smarty->assign('title_font_list', $title_font_list);
        $this->context->smarty->assign('body_font_list', $body_font_list);
        $output = $this->display(__FILE__, 'views/templates/front/display_home.tpl');
        return $output;
    }*/
}
