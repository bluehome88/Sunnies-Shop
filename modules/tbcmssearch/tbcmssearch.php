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

class TbcmsSearch extends Module
{
    private $templateFile;
    private $options;
    private $optionsCount = 0;

    public function __construct()
    {
        $this->name = 'tbcmssearch';
        $this->tab = 'front_office_features';
        $this->author = 'TemplateBeta';
        $this->version = '2.1.9';
        $this->need_instance = 0;

        parent::__construct();

        $this->displayName = 'TemplateBeta - Quick Search';
        $this->description = 'Adds a quick search field to your website.';

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
        $this->module_key = '';

        $this->confirmUninstall = $this->l('Warning: all the data saved in your database will be deleted.'.
            ' Are you sure you want uninstall this module?');
    }

    public function install()
    {
        return parent::install()
            && $this->registerHook('displayNavSearchBlock')
            && $this->registerHook('displaySearch')
            && $this->registerHook('displayMobileSearchBlock')
            && $this->registerHook('displayHeader');
    }

    public function hookdisplayHeader()
    {
        $this->context->controller->addJqueryUI('ui.autocomplete');
        $this->context->controller->registerJavascript('modules-tbcmssearch', 'modules/'
            .$this->name.'/views/js/tbcmssearch.js', array('position' => 'bottom', 'priority' => 150));
        $this->context->controller->addCSS($this->_path.'views/css/front.css');
    }

    protected function generateCategoriesMenu($categories)
    {
        $html = '';
        static $n = 0;
        foreach ($categories as $category) {
            if ($category['level_depth'] > 1 && $category['level_depth'] <= 3) {
                $OptionaChar ='';
                for ($i=1; $i<$n; $i++) {
                    $OptionaChar .= "--";
                }
                $this->options[$this->optionsCount++] = array('id_category' => $category['id_category'],
                                                 'name' => $OptionaChar.' '.$category["name"]);
            }
            if (isset($category['children']) && !empty($category['children'])) {
                $n++;
                $html .= $this->generateCategoriesMenu($category['children']);
                $n--;
            }
        }
    }

    public function getAllCategories()
    {
        $cookie = Context::getContext()->cookie;
        $id_lang = $cookie->id_lang;

        $tmp = Category::getNestedCategories(null, $id_lang);
        if (empty($this->options)) {
            $this->generateCategoriesMenu($tmp);
        }
        $this->context->smarty->assign('options', $this->options);

        $tmp = $this->context->link->getPageLink('search', null, null, null, false, null, true);
        $this->context->smarty->assign('search_controller_url', $tmp);
    }

    public function hookdisplayNavSearchBlock()
    {
        $this->getAllCategories();
        return $this->display(__FILE__, "views/templates/front/display_search.tpl");
    }

    public function hookdisplaySearch()
    {
        $this->getAllCategories();
        return $this->display(__FILE__, "views/templates/front/display_search.tpl");
    }

    public function hookdisplayMobileSearchBlock()
    {
        $this->getAllCategories();
        return $this->display(__FILE__, "views/templates/front/display_mobile_search.tpl");
    }
}
