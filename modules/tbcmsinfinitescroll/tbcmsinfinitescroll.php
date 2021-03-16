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

class TbcmsInfiniteScroll extends Module
{
    private $html = '';
    private $post_errors = array();
    private $templateFile = null;

    public function __construct()
    {
        $this->name = 'tbcmsinfinitescroll';
        $this->tab = 'front_office_features';
        $this->version = '2.1.9';
        $this->author = 'TemplateBeta';
        $this->need_instance = 0;

        
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('TemplateBeta - Infinite Scroll');
        $this->description = $this->l('Show infinite scroll in product list page instead of the pagination.');

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
        $this->module_key = '';

        $this->confirmUninstall = $this->l('Warning: all the data saved in your database will be deleted.'.
            ' Are you sure you want uninstall this module?');

        $this->templateFile = 'views/templates/front/'.$this->name.'_header.tpl';
    }

    public function install()
    {
        $this->installTab();
        Configuration::updateValue('TB_ACTIVE_CATEGORY', 1);
        Configuration::updateValue('TB_ACTIVE_NEW_PRODUCTS', 1);
        Configuration::updateValue('TB_ACTIVE_PRICES_DROP', 1);
        Configuration::updateValue('TB_ACTIVE_BEST_SALES', 1);
        Configuration::updateValue('TB_ACTIVE_SEARCH', 1);
        Configuration::updateValue('TB_ACTIVE_MANUFACTURER', 1);
        Configuration::updateValue('TB_ACTIVE_SUPPLIER', 1);
        Configuration::updateValue('TB_ACTIVE_LAYERED', 1);
        Configuration::updateValue('TB_METHOD', 0);
        Configuration::updateValue('TB_BUTTON_START_N_PAGE', 1);
        Configuration::updateValue('TB_BUTTON_N_PAGES', 1);
        Configuration::updateValue('TB_PRODUCT_WRAPPER', '#js-product-list .products');
        Configuration::updateValue('TB_PRODUCT_ELEM', '.product-miniature');
        Configuration::updateValue('TB_PAGINATION_WRAPPER', '.pagination .page-list');
        Configuration::updateValue('TB_NEXT_BUTTON', 'a.next');
        Configuration::updateValue('TB_VIEWS_BUTTONS_CHECK', 0);
        Configuration::updateValue('TB_VIEWS_BUTTONS', '');
        Configuration::updateValue('TB_SELECTED_VIEW', '');

        return parent::install()
            && $this->registerHook('header');
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
            $tab->name[$lang['id_lang']] = "Infinite Scroll";
        }
        $tab->id_parent = $parentTab_2->id;
        $tab->module = $this->name;
        $response &= $tab->add();

        return $response;
    }

    public function uninstall()
    {
        $this->uninstallTab();

        Configuration::deleteByName('TB_ACTIVE_CATEGORY');
        Configuration::deleteByName('TB_ACTIVE_NEW_PRODUCTS');
        Configuration::deleteByName('TB_ACTIVE_PRICES_DROP');
        Configuration::deleteByName('TB_ACTIVE_BEST_SALES');
        Configuration::deleteByName('TB_ACTIVE_SEARCH');
        Configuration::deleteByName('TB_ACTIVE_MANUFACTURER');
        Configuration::deleteByName('TB_ACTIVE_SUPPLIER');
        Configuration::deleteByName('TB_ACTIVE_LAYERED');
        Configuration::deleteByName('TB_METHOD');
        Configuration::deleteByName('TB_BUTTON_START_N_PAGE');
        Configuration::deleteByName('TB_BUTTON_N_PAGES');
        Configuration::deleteByName('TB_PRODUCT_WRAPPER');
        Configuration::deleteByName('TB_PRODUCT_ELEM');
        Configuration::deleteByName('TB_PAGINATION_WRAPPER');
        Configuration::deleteByName('TB_NEXT_BUTTON');
        Configuration::deleteByName('TB_VIEWS_BUTTONS_CHECK');
        Configuration::deleteByName('TB_VIEWS_BUTTONS');
        Configuration::deleteByName('TB_SELECTED_VIEW');

        return parent::uninstall();
    }

    public function uninstallTab()
    {
        $id_tab = Tab::getIdFromClassName('Admin'.$this->name);
        $tab = new Tab($id_tab);
        $tab->delete();
        return true;
    }

    public function hookdisplayHeader($params)
    {
        return $this->hookHeader($params);
    }

    public function hookHeader($params)
    {
        // Get the current page
        $page_name = Dispatcher::getInstance()->getController();
        $pages_active = $this->getProductsPageActive();
        $assign = array(
            'tb_texts' => array(
                'loading_prev_text' => $this->l('Loading previous results...'),
                'loading_text' => $this->l('Loading next results...'),
                'button_text' => $this->l('Display more results...'),
                'end_text' => $this->l('No more results to display...'),
                'go_top_text' => $this->l('Back to top')
            )
        );

        // Defines the Next && Prev url for <link rel="prev"> && <link rel="next">
        $current_p = Tools::getIsset('p') ? (int)Tools::getValue('p') : 1;
        $current_url = $this->context->link->getPaginationLink(false, false, false, true);
        $current_url_array = explode('?', $current_url);
        $base_url = $current_url_array[0];

        if ($current_p === 1) {
            $prev_url = $current_url;
            $next_url = $current_url.(isset($current_url_array[1]) ? '&' : '?').'p=2';
        } else {
            // Get all arguments in the url...
            $args = explode('&', $current_url_array[1]);
            $url_without_p = '';

            // ... and remove the "&p="...
            foreach ($args as $v) {
                if (strpos($v, 'p=') === false) {
                    $url_without_p .= $v.'&';
                }
            }
            $url_without_p = trim($url_without_p, '&');
            $url_without_p = $base_url.($url_without_p != '' ? '?' : '').$url_without_p;

            if ($current_p > 2) {
                // ... and add our "&p=" argument
                $prev_url = $url_without_p.'&p='.($current_p - 1);
                $next_url = $url_without_p.'&p='.($current_p + 1);
            } else { // Current : Page 2
                $prev_url = $url_without_p;
                $next_url = $url_without_p.($url_without_p != '' ? '&' : '?').'p='.($current_p + 1);
            }
        }


        // Infini Scroll on PRODUCTS
        if (in_array($page_name, $pages_active)) {
            $initialize = true;

            if ($page_name == 'category' && !Tools::getIsset('id_category')) {
                $initialize = false;
            }

            if ($initialize) {
                // Includes JS && CSS Front files
                if (version_compare(_PS_VERSION_, '1.7', '>')) {
                    $this->context->controller->registerStylesheet(
                        'modules-'.$this->name,
                        'modules/'.$this->name.'/views/css/front.css',
                        array('media' => 'all', 'priority' => 500)
                    );
                    $this->context->controller->registerJavascript(
                        'modules-'.$this->name,
                        'modules/'.$this->name.'/views/js/front.js',
                        array('position' => 'bottom', 'priority' => 500)
                    );
                } else {
                    $this->context->controller->addCSS(($this->_path).'views/css/front.css');
                    $this->context->controller->addJS(($this->_path).'views/js/front.js');
                }

                // Defines the options for the JS
                $assign['tb_options'] = array(
                    'product_wrapper' => Configuration::get('TB_PRODUCT_WRAPPER'),
                    'product_elem' => Configuration::get('TB_PRODUCT_ELEM'),
                    'pagination_wrapper' => Configuration::get('TB_PAGINATION_WRAPPER'),
                    'next_button' => Configuration::get('TB_NEXT_BUTTON'),
                    'views_buttons' => Configuration::get('TB_VIEWS_BUTTONS'),
                    'selected_view' => Configuration::get('TB_SELECTED_VIEW'),
                    'method' => Configuration::get('TB_METHOD') == 1 ? 'button' : 'scroll',
                    'button_start_page' => Configuration::get('TB_BUTTON_START_N_PAGE'),
                    'button_n_pages' => Configuration::get('TB_BUTTON_N_PAGES'),
                    'active_with_layered' => Configuration::get('TB_ACTIVE_LAYERED'),
                    'ps_16' => version_compare(_PS_VERSION_, '1.6', '>='),
                    'has_facetedSearch' => Module::isEnabled('ps_facetedsearch'),
                    'tbcmsinfinitescrollqv_enabled' => Module::isEnabled('tbcmsinfinitescroll_quick_view')
                );
                $assign['prev_page_value'] = $prev_url;
                $assign['next_page_value'] = $next_url;
            }
        }

        $this->smarty->assign($assign);
        return $this->display(__FILE__, $this->templateFile);
    }

    /**
     * Return an array of active pages for Products Infinite Scroll
     */
    public function getProductsPageActive()
    {
        $pages = array();

        if (Configuration::get('TB_ACTIVE_CATEGORY')) {
            $pages[] = 'category';
        }
        if (Configuration::get('TB_ACTIVE_NEW_PRODUCTS')) {
            $pages[] = 'new-products';
            $pages[] = 'newproducts';
        }
        if (Configuration::get('TB_ACTIVE_PRICES_DROP')) {
            $pages[] = 'prices-drop';
            $pages[] = 'pricesdrop';
        }
        if (Configuration::get('TB_ACTIVE_BEST_SALES')) {
            $pages[] = 'best-sales';
            $pages[] = 'bestsales';
        }
        if (Configuration::get('TB_ACTIVE_SEARCH')) {
            $pages[] = 'search';
        }
        if (Configuration::get('TB_ACTIVE_MANUFACTURER')) {
            $pages[] = 'manufacturer';
        }
        if (Configuration::get('TB_ACTIVE_SUPPLIER')) {
            $pages[] = 'supplier';
        }

        return $pages;
    }

    /**
     * Display the admin forms
     */
    public function getContent()
    {
        
        $errors = array();

        // Add CSS && JS for Admin
        $this->context->controller->addCSS(($this->_path).'views/css/tbcmsinfinitescroll-admin.css');
        $this->context->controller->addJS(($this->_path).'views/js/tbcmsinfinitescroll-admin.js');


        $this->html = '<div id="tb-wrapper-settings" class="tb-wrapper-settings">';

        // Form Process
        // Products Forms
        if (Tools::isSubmit('submitTbcmsInfiniteScroll')) {
            Configuration::updateValue('TB_ACTIVE_CATEGORY', Tools::getValue('TB_ACTIVE_CATEGORY'));
            Configuration::updateValue('TB_ACTIVE_NEW_PRODUCTS', Tools::getValue('TB_ACTIVE_NEW_PRODUCTS'));
            Configuration::updateValue('TB_ACTIVE_PRICES_DROP', Tools::getValue('TB_ACTIVE_PRICES_DROP'));
            Configuration::updateValue('TB_ACTIVE_BEST_SALES', Tools::getValue('TB_ACTIVE_BEST_SALES'));
            Configuration::updateValue('TB_ACTIVE_SEARCH', Tools::getValue('TB_ACTIVE_SEARCH'));
            Configuration::updateValue('TB_ACTIVE_MANUFACTURER', Tools::getValue('TB_ACTIVE_MANUFACTURER'));
            Configuration::updateValue('TB_ACTIVE_SUPPLIER', Tools::getValue('TB_ACTIVE_SUPPLIER'));
            Configuration::updateValue('TB_ACTIVE_LAYERED', Tools::getValue('TB_ACTIVE_LAYERED'));
            Configuration::updateValue('TB_METHOD', Tools::getValue('TB_METHOD'));
            Configuration::updateValue('TB_BUTTON_START_N_PAGE', Tools::getValue('TB_BUTTON_START_N_PAGE'));
            Configuration::updateValue('TB_BUTTON_N_PAGES', Tools::getValue('TB_BUTTON_N_PAGES'));

            if (isset($errors) && count($errors)) {
                $this->html .= $this->displayError(implode('<br />', $errors));
            } else {
                $this->html .= $this->displayConfirmation($this->l('Your settings have been successfully updated.'));
            }
        }
        if (Tools::isSubmit('submitTbcmsInfiniteScrollAdvanced')) {
            Configuration::updateValue('TB_PRODUCT_WRAPPER', Tools::getValue('TB_PRODUCT_WRAPPER'));
            Configuration::updateValue('TB_PRODUCT_ELEM', Tools::getValue('TB_PRODUCT_ELEM'));
            Configuration::updateValue('TB_PAGINATION_WRAPPER', Tools::getValue('TB_PAGINATION_WRAPPER'));
            Configuration::updateValue('TB_NEXT_BUTTON', Tools::getValue('TB_NEXT_BUTTON'));
            Configuration::updateValue('TB_VIEWS_BUTTONS_CHECK', Tools::getValue('TB_VIEWS_BUTTONS_CHECK'));
            Configuration::updateValue('TB_VIEWS_BUTTONS', Tools::getValue('TB_VIEWS_BUTTONS'));
            Configuration::updateValue('TB_SELECTED_VIEW', Tools::getValue('TB_SELECTED_VIEW'));

            if (isset($errors) && count($errors)) {
                $this->html .= $this->displayError(implode('<br />', $errors));
            } else {
                $this->html .= $this->displayConfirmation($this->l('Your settings have been successfully updated.'));
            }
        }

        $this->html .= '<div id="tb-settings" class="tb-settings"><div class="tb-settings-inner">';
            $this->html .= '<div id="tb-settings-products" class="tb-settings-content clearfix">';
                $this->displayFormProducts();
                $this->displayFormProductsSelector();
            $this->html .= '</div>';

        $this->html .= '</div></div>';

        $this->html .= '</div>';

        $this->html .= '<script type="text/javascript">var tb_ps_16 = 1 </script>';

        return $this->html;
    }

  
    /**
     * Display the form of the module's PRODUCTS settings
     */
    public function displayFormProducts()
    {
        $on_infinite_scroll = $this->l('Turn on infinite scroll on page');

        $tb_method_desc = $this->l('This allows your customers to view and read your page footer. By default,'.
            ' displaying results is fires by scrolling.');


        $tb_button_start_n_page_desc = $this->l('The button to display next results will be visible from'
            .' the page N. Before, next results will be displayed by scrolling the page.');
        $fields_form_input = array(
            array(
                'type' => 'switch',
                'label' => $on_infinite_scroll.' : category <br />('.$this->l('Category').')',
                'name' => 'TB_ACTIVE_CATEGORY',
                'class' => 'tb-input-active-category t',
                'is_bool' => true,
                'values' => array(
                    array(
                        'id' => 'TB_ACTIVE_CATEGORY_on',
                        'value' => 1,
                        'label' => $this->l('Enabled')
                    ),
                    array(
                        'id' => 'TB_ACTIVE_CATEGORY_off',
                        'value' => 0,
                        'label' => $this->l('Disabled')
                    )
                )
            ),
            array(
                'type' => 'switch',
                'label' => $on_infinite_scroll.' : new-products <br />('.$this->l('New products').')',
                'name' => 'TB_ACTIVE_NEW_PRODUCTS',
                'class' => 'tb-input-active-category t',
                'is_bool' => true,
                'values' => array(
                    array(
                        'id' => 'TB_ACTIVE_NEW_PRODUCTS_on',
                        'value' => 1,
                        'label' => $this->l('Enabled')
                    ),
                    array(
                        'id' => 'TB_ACTIVE_NEW_PRODUCTS_off',
                        'value' => 0,
                        'label' => $this->l('Disabled')
                    )
                )
            ),
            array(
                'type' => 'switch',
                'label' => $on_infinite_scroll.' : prices-drop <br />('.$this->l('Prices drop').')',
                'name' => 'TB_ACTIVE_PRICES_DROP',
                'class' => 'tb-input-active-category t',
                'is_bool' => true,
                'values' => array(
                    array(
                        'id' => 'TB_ACTIVE_PRICES_DROP_on',
                        'value' => 1,
                        'label' => $this->l('Enabled')
                    ),
                    array(
                        'id' => 'TB_ACTIVE_PRICES_DROP_off',
                        'value' => 0,
                        'label' => $this->l('Disabled')
                    )
                )
            ),
            array(
                'type' => 'switch',
                'label' => $on_infinite_scroll.' : best-sales <br />('.$this->l('Best sales').')',
                'name' => 'TB_ACTIVE_BEST_SALES',
                'class' => 'tb-input-active-category t',
                'is_bool' => true,
                'values' => array(
                    array(
                        'id' => 'TB_ACTIVE_BEST_SALES_on',
                        'value' => 1,
                        'label' => $this->l('Enabled')
                    ),
                    array(
                        'id' => 'TB_ACTIVE_BEST_SALES_off',
                        'value' => 0,
                        'label' => $this->l('Disabled')
                    )
                )
            ),
            array(
                'type' => 'switch',
                'label' => $on_infinite_scroll.' : search <br />('.$this->l('Search').')',
                'name' => 'TB_ACTIVE_SEARCH',
                'class' => 'tb-input-active-category t',
                'is_bool' => true,
                'values' => array(
                    array(
                        'id' => 'TB_ACTIVE_SEARCH_on',
                        'value' => 1,
                        'label' => $this->l('Enabled')
                    ),
                    array(
                        'id' => 'TB_ACTIVE_SEARCH_off',
                        'value' => 0,
                        'label' => $this->l('Disabled')
                    )
                )
            ),
            array(
                'type' => 'switch',
                'label' => $on_infinite_scroll.' : manufacturer <br />('.$this->l('Manufacturer').')',
                'name' => 'TB_ACTIVE_MANUFACTURER',
                'class' => 'tb-input-active-category t',
                'is_bool' => true,
                'values' => array(
                    array(
                        'id' => 'TB_ACTIVE_MANUFACTURER_on',
                        'value' => 1,
                        'label' => $this->l('Enabled')
                    ),
                    array(
                        'id' => 'TB_ACTIVE_MANUFACTURER_off',
                        'value' => 0,
                        'label' => $this->l('Disabled')
                    )
                )
            ),
            array(
                'type' => 'switch',
                'label' => $on_infinite_scroll.' : supplier <br />('.$this->l('Supplier').')',
                'name' => 'TB_ACTIVE_SUPPLIER',
                'class' => 'tb-input-active-category t',
                'is_bool' => true,
                'values' => array(
                    array(
                        'id' => 'TB_ACTIVE_SUPPLIER_on',
                        'value' => 1,
                        'label' => $this->l('Enabled')
                    ),
                    array(
                        'id' => 'TB_ACTIVE_SUPPLIER_off',
                        'value' => 0,
                        'label' => $this->l('Disabled')
                    )
                )
            ),
            array(
                'type' => 'switch',
                'label' => $this->l('Turn on infinite scroll with layared module'),
                'name' => 'TB_ACTIVE_LAYERED',
                'class' => 'tb-input-active-category t',
                'desc' => $this->l('show or hide the infinite scroll when a filter is show on the layered module.'),
                'is_bool' => true,
                'values' => array(
                    array(
                        'id' => 'TB_ACTIVE_LAYERED_on',
                        'value' => 1,
                        'label' => $this->l('Enabled')
                    ),
                    array(
                        'id' => 'TB_ACTIVE_LAYERED_off',
                        'value' => 0,
                        'label' => $this->l('Disabled')
                    )
                )
            ),
            array(
                'type' => 'switch',
                'label' => $this->l('Display a button to load next results'),
                'name' => 'TB_METHOD',
                'class' => 'tb-input-method t',
                'desc' => $tb_method_desc,
                'is_bool' => true,
                'values' => array(
                    array(
                        'id' => 'TB_METHOD_on',
                        'value' => 1,
                        'label' => $this->l('Enabled')
                    ),
                    array(
                        'id' => 'TB_METHOD_off',
                        'value' => 0,
                        'label' => $this->l('Disabled')
                    )
                )
            ),
            array(
                'type' => 'text',
                'label' => $this->l('Start displaying the button in the page N'),
                'name' => 'TB_BUTTON_START_N_PAGE',
                'class' => 'tb-input-button-start-page',
                'desc' => $tb_button_start_n_page_desc,
            ),
            array(
                'type' => 'text',
                'label' => $this->l('Display the button every N page'),
                'name' => 'TB_BUTTON_N_PAGES',
                'class' => 'tb-input-button-pages',
                'desc' => $this->l('The button will be displayed only every N pages.'),
            )
        );

        // Specific variable for PS 1.5
        if (version_compare(_PS_VERSION_, '1.6', '<')) {
            $fields_form_input[0]['type'] = 'radio';
            $fields_form_input[1]['type'] = 'radio';
            $fields_form_input[2]['type'] = 'radio';
            $fields_form_input[3]['type'] = 'radio';
            $fields_form_input[4]['type'] = 'radio';
            $fields_form_input[5]['type'] = 'radio';
            $fields_form_input[6]['type'] = 'radio';
            $fields_form_input[7]['type'] = 'radio';
            $fields_form_input[8]['type'] = 'radio';
        }

        $fields_form = array(
            'form' => array(
                'input' => $fields_form_input,
                'submit' => array(
                    'title' => $this->l('Save'),
                    'class' => 'btn btn-default tb-input-submit'
                )
            ),
        );

        // Get default language
        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');

        $helper = new HelperForm();

        // Module, token and currentIndex
        $helper->module = $this;
        $helper->identifier = $this->identifier;
        $helper->table = $this->table;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;

        // Language
        $helper->default_form_language = $default_lang;
        $tmp = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG');
        $helper->allow_employee_form_lang = $tmp ? $tmp : $default_lang;

        // Title and toolbar
        $helper->title = $this->displayName;
        $helper->show_toolbar = false;
        $helper->submit_action = 'submitTbcmsInfiniteScroll';
        $this->fields_form = array();
        $helper->tpl_vars = array(
            'fields_value' => array(
                'TB_ACTIVE_CATEGORY' => Tools::getValue(
                    'TB_ACTIVE_CATEGORY',
                    Configuration::get('TB_ACTIVE_CATEGORY')
                ),
                'TB_ACTIVE_NEW_PRODUCTS' => Tools::getValue(
                    'TB_ACTIVE_NEW_PRODUCTS',
                    Configuration::get('TB_ACTIVE_NEW_PRODUCTS')
                ),
                'TB_ACTIVE_PRICES_DROP' => Tools::getValue(
                    'TB_ACTIVE_PRICES_DROP',
                    Configuration::get('TB_ACTIVE_PRICES_DROP')
                ),
                'TB_ACTIVE_BEST_SALES' => Tools::getValue(
                    'TB_ACTIVE_BEST_SALES',
                    Configuration::get('TB_ACTIVE_BEST_SALES')
                ),
                'TB_ACTIVE_SEARCH' => Tools::getValue(
                    'TB_ACTIVE_SEARCH',
                    Configuration::get('TB_ACTIVE_SEARCH')
                ),
                'TB_ACTIVE_MANUFACTURER' => Tools::getValue(
                    'TB_ACTIVE_MANUFACTURER',
                    Configuration::get('TB_ACTIVE_MANUFACTURER')
                ),
                'TB_ACTIVE_SUPPLIER' => Tools::getValue(
                    'TB_ACTIVE_SUPPLIER',
                    Configuration::get('TB_ACTIVE_SUPPLIER')
                ),
                'TB_ACTIVE_LAYERED' => Tools::getValue(
                    'TB_ACTIVE_LAYERED',
                    Configuration::get('TB_ACTIVE_LAYERED')
                ),
                'TB_METHOD' => Tools::getValue('TB_METHOD', Configuration::get('TB_METHOD')),
                'TB_BUTTON_START_N_PAGE' => Tools::getValue(
                    'TB_BUTTON_START_N_PAGE',
                    Configuration::get('TB_BUTTON_START_N_PAGE')
                ),
                'TB_BUTTON_N_PAGES' => Tools::getValue(
                    'TB_BUTTON_N_PAGES',
                    Configuration::get('TB_BUTTON_N_PAGES')
                ),
            ),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );

        $this->html .= '<h2 class="tb-options-title">'.$this->l('Display Settings')
            .' <span class="tb-title-toggle"></span></h2>';
        $this->html .= $helper->generateForm(array($fields_form));
    }

    /**
     * Display the form of the module's selector PRODUCTS settings
     */
    public function displayFormProductsSelector()
    {
        $tb_next_button_desc = $this->l('Element containing the link to next page of the pagination '
            .'(inside the "Pagination Selector").');
        $fields_form_input = array(
            array(
                'type' => 'text',
                'label' => $this->l('Products List Selector'),
                'name' => 'TB_PRODUCT_WRAPPER',
                'class' => 'tb-input-product-wrapper',
                'desc' => $this->l('Element containing your theme\'s products list.'),
            ),
            array(
                'type' => 'text',
                'label' => $this->l('Product Selector'),
                'name' => 'TB_PRODUCT_ELEM',
                'class' => 'tb-input-product-elem',
                'desc' => $this->l('Element containing a product (inside the "Products List Selector").'),
            ),
            array(
                'type' => 'text',
                'label' => $this->l('Pagination Selector'),
                'name' => 'TB_PAGINATION_WRAPPER',
                'class' => 'tb-input-pagination-wrapper',
                'desc' => $this->l('Element containing your theme\'s products pagination.'),
            ),
            array(
                'type' => 'text',
                'label' => $this->l('Next Page Button Selector'),
                'name' => 'TB_NEXT_BUTTON',
                'class' => 'tb-input-next-button',
                'desc' => $tb_next_button_desc,
            ),
            // array(
            //     'type' => 'switch',
            //     'label' => $this->l('My theme uses different products list views (grid/list)'),
            //     'name' => 'TB_VIEWS_BUTTONS_CHECK',
            //     'class' => 'tb-input-views-button-check t',
            //     // 'desc' => '',
            //     'is_bool' => true,
            //     'values' => array(
            //         array(
            //             'id' => 'TB_VIEWS_BUTTONS_CHECK_on',
            //             'value' => 1,
            //             'label' => $this->l('Enabled')
            //         ),
            //         array(
            //             'id' => 'TB_VIEWS_BUTTONS_CHECK_off',
            //             'value' => 0,
            //             'label' => $this->l('Disabled')
            //         )
            //     )
            // ),
            array(
                'type' => 'text',
                'label' => $this->l('Grid/list views buttons selector'),
                'name' => 'TB_VIEWS_BUTTONS',
                'class' => 'tb-input-views-button',
                'desc' => $this->l('Selector for buttons of the different products list views.'),
            ),
            array(
                'type' => 'text',
                'label' => $this->l('Selected grid/list view button selector'),
                'name' => 'TB_SELECTED_VIEW',
                'class' => 'tb-input-views-button-selected',
                'desc' => $this->l('Selector for the button of the selected view.'),
            )
        );

        // Specific variable for PS 1.5
        if (version_compare(_PS_VERSION_, '1.6', '<')) {
            $fields_form_input[4]['type'] = 'radio';
        }

        $fields_form = array(
            'form' => array(
                'input' => $fields_form_input,
                'submit' => array(
                    'title' => $this->l('Save'),
                    'class' => 'btn btn-default tb-input-submit'
                )
            ),
        );

        // Get default language
        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');

        $helper = new HelperForm();

        // Module, token and currentIndex
        $helper->module = $this;
        $helper->identifier = $this->identifier;
        $helper->table = $this->table;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;

        // Language
        $helper->default_form_language = $default_lang;
        $tmp = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG');
        $helper->allow_employee_form_lang = $tmp ? $tmp : $default_lang;

        // Title and toolbar
        $helper->title = $this->displayName;
        $helper->show_toolbar = false;
        $helper->submit_action = 'submitTbcmsInfiniteScrollAdvanced';
        $this->fields_form = array();
        $helper->tpl_vars = array(
            'fields_value' => array(
                'TB_PRODUCT_WRAPPER' => Tools::getValue(
                    'TB_PRODUCT_WRAPPER',
                    Configuration::get('TB_PRODUCT_WRAPPER')
                ),
                'TB_PRODUCT_ELEM' => Tools::getValue(
                    'TB_PRODUCT_ELEM',
                    Configuration::get('TB_PRODUCT_ELEM')
                ),
                'TB_PAGINATION_WRAPPER' => Tools::getValue(
                    'TB_PAGINATION_WRAPPER',
                    Configuration::get('TB_PAGINATION_WRAPPER')
                ),
                'TB_NEXT_BUTTON' => Tools::getValue('TB_NEXT_BUTTON', Configuration::get('TB_NEXT_BUTTON')),
                'TB_VIEWS_BUTTONS_CHECK' => Tools::getValue(
                    'TB_VIEWS_BUTTONS_CHECK',
                    Configuration::get('TB_VIEWS_BUTTONS_CHECK')
                ),
                'TB_VIEWS_BUTTONS' => Tools::getValue(
                    'TB_VIEWS_BUTTONS',
                    Configuration::get('TB_VIEWS_BUTTONS')
                ),
                'TB_SELECTED_VIEW' => Tools::getValue('TB_SELECTED_VIEW', Configuration::get('TB_SELECTED_VIEW'))
            ),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );

        $this->html .= '<h2 class="tb-options-title with-mgt">'.$this->l('Advanced Settings')
        .' <span class="tb-title-toggle"></span></h2>';
        $this->html .= $helper->generateForm(array($fields_form));
    }
}
