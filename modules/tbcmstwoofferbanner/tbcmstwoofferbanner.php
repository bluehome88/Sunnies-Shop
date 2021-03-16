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

include_once('classes/tbcmstwoofferbanner_image_upload.class.php');

class TbcmsTwoOfferBanner extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'tbcmstwoofferbanner';
        $this->tab = 'front_office_features';
        $this->version = '2.1.9';
        $this->author = 'TemplateBeta';
        $this->need_instance = 0;

        
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('TemplateBeta - Two Offer Banner');
        $this->description = $this->l('This is Show Two Offer Banner in Front Side');

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
        $this->module_key = '';

        $this->confirmUninstall = $this->l('Warning: all the data saved in your database will be deleted.'.
            ' Are you sure you want uninstall this module?');
    }

    public function install()
    {
        $this->defineVariable();

        $this->installTab();

        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('backOfficeHeader') &&
            $this->registerHook('displayHome');
    }

    public function defineVariable()
    {
        $result = array();
        $languages = Language::getLanguages();

        foreach ($languages as $lang) {
            $result['TBCMSTWOOFFERBANNER_CAPTION'][$lang['id_lang']] = '<h6>motoz<sup>3<sup></h6><h4>World\'s First '
                .'5G-upgradable</h4><div><a href=\'#\'><button>Shop Now</button></a></div>';

            $result['TBCMSTWOOFFERBANNER_CAPTION_2'][$lang['id_lang']] = '<h4>AirPods</h4><h6>Wireless. Effortless.'
                .' Magical.</h6>';
        }

        Configuration::updateValue('TBCMSTWOOFFERBANNER_IMAGE_NAME', 'demo_img_1.jpg');
        Configuration::updateValue('TBCMSTWOOFFERBANNER_CAPTION', $result['TBCMSTWOOFFERBANNER_CAPTION'], true);

        // Default option is :- "left", "center", "right", "none".
        Configuration::updateValue('TBCMSTWOOFFERBANNER_CAPTION_SIDE', 'none');
        Configuration::updateValue('TBCMSTWOOFFERBANNER_LINK', '#');

        Configuration::updateValue('TBCMSTWOOFFERBANNER_IMAGE_NAME_2', 'demo_img_2.jpg');
        Configuration::updateValue('TBCMSTWOOFFERBANNER_CAPTION_2', $result['TBCMSTWOOFFERBANNER_CAPTION_2'], true);

        // Default option is :- "left", "center", "right", "none".
        Configuration::updateValue('TBCMSTWOOFFERBANNER_CAPTION_SIDE_2', 'none');
        Configuration::updateValue('TBCMSTWOOFFERBANNER_LINK_2', '#');
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
            $tab->name[$lang['id_lang']] = "Two Offer Banner";
        }
        $tab->id_parent = $parentTab_2->id;
        $tab->module = $this->name;
        $response &= $tab->add();

        return $response;
    }

    public function uninstall()
    {
        $this->deleteVariable();
        $this->uninstallTab();

        return parent::uninstall();
    }

    public function deleteVariable()
    {
        Configuration::deleteByName('TBCMSTWOOFFERBANNER_IMAGE_NAME');
        Configuration::deleteByName('TBCMSTWOOFFERBANNER_CAPTION');
        Configuration::deleteByName('TBCMSTWOOFFERBANNER_CAPTION_SIDE');
        Configuration::deleteByName('TBCMSTWOOFFERBANNER_LINK');

        Configuration::deleteByName('TBCMSTWOOFFERBANNER_IMAGE_NAME_2');
        Configuration::deleteByName('TBCMSTWOOFFERBANNER_CAPTION_2');
        Configuration::deleteByName('TBCMSTWOOFFERBANNER_CAPTION_SIDE_2');
        Configuration::deleteByName('TBCMSTWOOFFERBANNER_LINK_2');
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
        $messages = '';
        $tmp = array();
        $result = array();
        if (((bool)Tools::isSubmit('submittbcmstwoofferbanner')) == true) {
            $obj_image = new TbcmsTwoOfferBannerImageUpload();
            $languages = Language::getLanguages(false);
            if (!empty($_FILES['TBCMSTWOOFFERBANNER_IMAGE_NAME']['name'])) {
                $old_img_path = Configuration::get('TBCMSTWOOFFERBANNER_IMAGE_NAME');
                $tmp = $_FILES['TBCMSTWOOFFERBANNER_IMAGE_NAME'];
                $ans = $obj_image->imageUploading($tmp, $old_img_path);
                if ($ans['success']) {
                    Configuration::updateValue('TBCMSTWOOFFERBANNER_IMAGE_NAME', $ans['name']);
                } else {
                    $messages .= $result['error'];
                }
            }


            foreach ($languages as $lang) {
                $tmp = Tools::getValue('TBCMSTWOOFFERBANNER_CAPTION_'.$lang['id_lang']);
                $result['TBCMSTWOOFFERBANNER_CAPTION'][$lang['id_lang']] = $tmp;
            }

            $tmp = $result['TBCMSTWOOFFERBANNER_CAPTION'];
            Configuration::updateValue('TBCMSTWOOFFERBANNER_CAPTION', $tmp, true);

            $tmp = Tools::getValue('TBCMSTWOOFFERBANNER_CAPTION_SIDE');
            Configuration::updateValue('TBCMSTWOOFFERBANNER_CAPTION_SIDE', $tmp);

            $tmp = Tools::getValue('TBCMSTWOOFFERBANNER_LINK');
            Configuration::updateValue('TBCMSTWOOFFERBANNER_LINK', $tmp);

            if (!empty($_FILES['TBCMSTWOOFFERBANNER_IMAGE_NAME_2']['name'])) {
                $old_img_path = Configuration::get('TBCMSTWOOFFERBANNER_IMAGE_NAME_2');
                $tmp = $_FILES['TBCMSTWOOFFERBANNER_IMAGE_NAME_2'];
                $ans = $obj_image->imageUploading($tmp, $old_img_path);
                if ($ans['success']) {
                    Configuration::updateValue('TBCMSTWOOFFERBANNER_IMAGE_NAME_2', $ans['name']);
                } else {
                    $messages .= $result['error'];
                }
            }

            foreach ($languages as $lang) {
                $tmp = Tools::getValue('TBCMSTWOOFFERBANNER_CAPTION_2_'.$lang['id_lang']);
                $result['TBCMSTWOOFFERBANNER_CAPTION_2'][$lang['id_lang']] = $tmp;
            }

            $tmp = $result['TBCMSTWOOFFERBANNER_CAPTION_2'];
            Configuration::updateValue('TBCMSTWOOFFERBANNER_CAPTION_2', $tmp, true);

            $tmp = Tools::getValue('TBCMSTWOOFFERBANNER_CAPTION_SIDE_2');
            Configuration::updateValue('TBCMSTWOOFFERBANNER_CAPTION_SIDE_2', $tmp);

            $tmp = Tools::getValue('TBCMSTWOOFFERBANNER_LINK_2');
            Configuration::updateValue('TBCMSTWOOFFERBANNER_LINK_2', $tmp);
            
            $this->clearCustomSmartyCache('tbcmstwoofferbanner_display_home.tpl');

            $messages .= $this->displayConfirmation($this->l("Offer Banner Information is Updated"));
        }

        $output =   $messages.
                    $this->renderForm();

        return $output;
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
        $helper->submit_action = 'submittbcmstwoofferbanner';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    
    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                'title' => $this->l('Settings'),
                'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'col' => 6,
                        'name' => 'TBCMSTWOOFFERBANNER_IMAGE_NAME',
                        'type' => 'file_upload',
                        'label' => $this->l('Image'),
                    ),
                    array(
                        'col' => 6,
                        'name' => 'TBCMSTWOOFFERBANNER_CAPTION',
                        'type' => 'textarea',
                        'lang' => true,
                        'label' => $this->l('Image Caption'),
                        'desc' => $this->l('Enter Image Caption'),
                        'cols' => 40,
                        'rows' => 10,
                        'class' => 'rte',
                        'autoload_rte' => true,
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Display Banner Side'),
                        'name' => 'TBCMSTWOOFFERBANNER_CAPTION_SIDE',
                        'desc' => $this->l('Select Where You Show Text'),
                        'options' => array(
                            'query' => array(
                                array(
                                    'id_option' => 'left',
                                    'name' => 'Left Side'
                                ),
                                array(
                                    'id_option' => 'center',
                                    'name' => 'Center Side'
                                ),
                                array(
                                    'id_option' => 'right',
                                    'name' => 'Right Side'
                                ),
                                array(
                                    'id_option' => 'none',
                                    'name' => 'None'
                                ),
                            ),
                            'id' => 'id_option',
                            'name' => 'name'
                        )
                    ),
                    array(
                        'col' => 6,
                        'name' => 'TBCMSTWOOFFERBANNER_LINK',
                        'type' => 'text',
                        'label' => $this->l('Link'),
                        'desc' => $this->l('Enter Image Link'),
                    ),
                    array(
                        'col' => 6,
                        'name' => 'TBCMSTWOOFFERBANNER_IMAGE_NAME_2',
                        'type' => 'file_upload_2',
                        'label' => $this->l('Image 2'),
                    ),
                    array(
                        'col' => 6,
                        'name' => 'TBCMSTWOOFFERBANNER_CAPTION_2',
                        'type' => 'textarea',
                        'lang' => true,
                        'label' => $this->l('Image Caption 2'),
                        'desc' => $this->l('Enter Image Caption 2'),
                        'cols' => 40,
                        'rows' => 10,
                        'class' => 'rte',
                        'autoload_rte' => true,
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Display Banner Side 2'),
                        'name' => 'TBCMSTWOOFFERBANNER_CAPTION_SIDE_2',
                        'desc' => $this->l('Select Where You Show Text 2'),
                        'options' => array(
                            'query' => array(
                                array(
                                    'id_option' => 'left',
                                    'name' => 'Left Side'
                                ),
                                array(
                                    'id_option' => 'center',
                                    'name' => 'Center Side'
                                ),
                                array(
                                    'id_option' => 'right',
                                    'name' => 'Right Side'
                                ),
                                array(
                                    'id_option' => 'none',
                                    'name' => 'None'
                                ),
                            ),
                            'id' => 'id_option',
                            'name' => 'name'
                        )
                    ),
                    array(
                        'col' => 6,
                        'name' => 'TBCMSTWOOFFERBANNER_LINK_2',
                        'type' => 'text',
                        'label' => $this->l('Link 2'),
                        'desc' => $this->l('Enter Image Link 2'),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }
   
    protected function getConfigFormValues()
    {
        $path = _MODULE_DIR_.$this->name."/views/img/";
        $this->context->smarty->assign("path", $path);
        
        $fields = array();

        $languages = Language::getLanguages();
        foreach ($languages as $lang) {
            $tmp = Configuration::get('TBCMSTWOOFFERBANNER_CAPTION', $lang['id_lang'], true);
            $fields['TBCMSTWOOFFERBANNER_CAPTION'][$lang['id_lang']] = $tmp;

            $tmp = Configuration::get('TBCMSTWOOFFERBANNER_CAPTION_2', $lang['id_lang'], true);
            $fields['TBCMSTWOOFFERBANNER_CAPTION_2'][$lang['id_lang']] = $tmp;
        }

        $tmp = Configuration::get('TBCMSTWOOFFERBANNER_IMAGE_NAME');
        $fields['TBCMSTWOOFFERBANNER_IMAGE_NAME'] = $tmp;

        $tmp = Configuration::get('TBCMSTWOOFFERBANNER_CAPTION_SIDE');
        $fields['TBCMSTWOOFFERBANNER_CAPTION_SIDE'] = $tmp;

        $tmp = Configuration::get('TBCMSTWOOFFERBANNER_LINK');
        $fields['TBCMSTWOOFFERBANNER_LINK'] = $tmp;

        $tmp = Configuration::get('TBCMSTWOOFFERBANNER_IMAGE_NAME_2');
        $fields['TBCMSTWOOFFERBANNER_IMAGE_NAME_2'] = $tmp;

        $tmp = Configuration::get('TBCMSTWOOFFERBANNER_CAPTION_SIDE_2');
        $fields['TBCMSTWOOFFERBANNER_CAPTION_SIDE_2'] = $tmp;

        $tmp = Configuration::get('TBCMSTWOOFFERBANNER_LINK_2');
        $fields['TBCMSTWOOFFERBANNER_LINK_2'] = $tmp;


        return $fields;
    }


    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('module_name') == $this->name) {
            $this->context->controller->addJS($this->_path.'views/js/back.js');
            $this->context->controller->addCSS($this->_path.'views/css/back.css');
        }
    }
   
    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path.'/views/js/front.js');
        $this->context->controller->addCSS($this->_path.'/views/css/front.css');
    }

    public function hookdisplayHome()
    {
        return $this->showResult();
    }

    public function hookdisplayRightColumn()
    {
        return $this->showResult();
    }

    public function showResult()
    {
        $data = array();

        if (!Cache::isStored('tbcmstwoofferbanner_display_home.tpl')) {
            $languages = Language::getLanguages();
            foreach ($languages as $lang) {
                $tmp = Configuration::get('TBCMSTWOOFFERBANNER_CAPTION', $lang['id_lang'], true);
                $data['TBCMSTWOOFFERBANNER_CAPTION'][$lang['id_lang']] = $tmp;

                $tmp = Configuration::get('TBCMSTWOOFFERBANNER_CAPTION_2', $lang['id_lang'], true);
                $data['TBCMSTWOOFFERBANNER_CAPTION_2'][$lang['id_lang']] = $tmp;
            }

            $tmp = Configuration::get('TBCMSTWOOFFERBANNER_IMAGE_NAME');
            $data['TBCMSTWOOFFERBANNER_IMAGE_NAME'] = $tmp;

            $tmp = Configuration::get('TBCMSTWOOFFERBANNER_CAPTION_SIDE');
            $data['TBCMSTWOOFFERBANNER_CAPTION_SIDE'] = $tmp;

            $tmp = Configuration::get('TBCMSTWOOFFERBANNER_LINK');
            $data['TBCMSTWOOFFERBANNER_LINK'] = $tmp;

            $tmp = Configuration::get('TBCMSTWOOFFERBANNER_IMAGE_NAME_2');
            $data['TBCMSTWOOFFERBANNER_IMAGE_NAME_2'] = $tmp;

            $tmp = Configuration::get('TBCMSTWOOFFERBANNER_CAPTION_SIDE_2');
            $data['TBCMSTWOOFFERBANNER_CAPTION_SIDE_2'] = $tmp;

            $tmp = Configuration::get('TBCMSTWOOFFERBANNER_LINK_2');
            $data['TBCMSTWOOFFERBANNER_LINK_2'] = $tmp;
                

            $cookie = Context::getContext()->cookie;
            $id_lang = $cookie->id_lang;

            $this->context->smarty->assign('language_id', $id_lang);
            $this->context->smarty->assign('data', $data);

            $path = _MODULE_DIR_.$this->name."/views/img/";
            $this->context->smarty->assign("path", $path);

            $output = $this->display(__FILE__, "views/templates/front/display_home.tpl");
            Cache::store('tbcmstwoofferbanner_display_home.tpl', $output);
        }

        return Cache::retrieve('tbcmstwoofferbanner_display_home.tpl');
    }
}
