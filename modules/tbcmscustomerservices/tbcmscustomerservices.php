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

include_once('classes/tbcmscustomer_services_image_upload.class.php');
include_once('classes/tbcmscustomer_service_status.class.php');

class TbcmsCustomerServices extends Module
{
    public function __construct()
    {
        $this->name = 'tbcmscustomerservices';
        $this->tab = 'front_office_features';
        $this->version = '2.1.9';
        $this->author = 'TemplateBeta';
        $this->need_instance = 0;

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('TemplateBeta - Customer Services');
        $this->description = $this->l('Its Show Customer Services in Front Side');

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
        $this->module_key = '';

        $this->confirmUninstall = $this->l('Warning: all the data saved in your database will be deleted.'.
            ' Are you sure you want uninstall this module?');
    }

    public function install()
    {
        $this->installTab();
        $this->crateVariable();
        Tools::clearSmartyCache();

        return parent::install()
            && $this->registerHook('displayHeader')
            // && $this->registerHook('displayHome');
            // && $this->registerHook('displayWrapperBottom');
            // && $this->registerHook('displayLeftColumn');
            // && $this->registerHook('displayRightColumn');
            // && $this->registerHook('displayCustomerServices');
            // && $this->registerHook('displayWrapperTop');
            // && $this->registerHook('displayTopColumn');
            && $this->registerHook('displayFooterBefore');
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
            $tab->name[$lang['id_lang']] = "Customer Services";
        }
        $tab->id_parent = $parentTab_2->id;
        $tab->module = $this->name;
        $response &= $tab->add();

        return $response;
    }


    public function crateVariable()
    {
        $result = array();
        $languages = Language::getLanguages();

        foreach ($languages as $lang) {
            $result['TBCMSCUSTOMERSERVICES_TITLE'][$lang['id_lang']] = 'Our Services';
            $result['TBCMSCUSTOMERSERVICES_SUB_DESCRIPTION'][$lang['id_lang']] = 'Sub Description';
            $result['TBCMSCUSTOMERSERVICES_DESCRIPTION'][$lang['id_lang']] = 'A Friendly Organic Store';
            $result['TBCMSCUSTOMERSERVICES_IMG'][$lang['id_lang']] = 'demo_main_img.jpg';

            $result['TBCMSCUSTOMERSERVICES_SERVICES_1_IMAGE'][$lang['id_lang']] = 'demo_img_1.png';
            $result['TBCMSCUSTOMERSERVICES_SERVICES_1_CAPTION'][$lang['id_lang']] = 'Free Shipping';
            $result['TBCMSCUSTOMERSERVICES_SERVICES_1_DESC'][$lang['id_lang']] = 'On Order over $150';

            $result['TBCMSCUSTOMERSERVICES_SERVICES_2_IMAGE'][$lang['id_lang']] = 'demo_img_2.png';
            $result['TBCMSCUSTOMERSERVICES_SERVICES_2_CAPTION'][$lang['id_lang']] = 'Trustpay';
            $result['TBCMSCUSTOMERSERVICES_SERVICES_2_DESC'][$lang['id_lang']] = '100% Payment Protection';

            $result['TBCMSCUSTOMERSERVICES_SERVICES_3_IMAGE'][$lang['id_lang']] = 'demo_img_3.png';
            $result['TBCMSCUSTOMERSERVICES_SERVICES_3_CAPTION'][$lang['id_lang']] = 'Support 24/7';
            $result['TBCMSCUSTOMERSERVICES_SERVICES_3_DESC'][$lang['id_lang']] = 'Got A Question?';

            $result['TBCMSCUSTOMERSERVICES_SERVICES_4_IMAGE'][$lang['id_lang']] = 'demo_img_4.png';
            $result['TBCMSCUSTOMERSERVICES_SERVICES_4_CAPTION'][$lang['id_lang']] = 'Shop on The Go';
            $result['TBCMSCUSTOMERSERVICES_SERVICES_4_DESC'][$lang['id_lang']] = 'Download the App';

            $result['TBCMSCUSTOMERSERVICES_SERVICES_5_IMAGE'][$lang['id_lang']] = 'demo_img_5.png';
            $result['TBCMSCUSTOMERSERVICES_SERVICES_5_CAPTION'][$lang['id_lang']] = 'Best Prices & Offers';
            $result['TBCMSCUSTOMERSERVICES_SERVICES_5_DESC'][$lang['id_lang']] = 'Got a Question?';

            $result['TBCMSCUSTOMERSERVICES_SERVICES_6_IMAGE'][$lang['id_lang']] = 'demo_img_6.png';
            $result['TBCMSCUSTOMERSERVICES_SERVICES_6_CAPTION'][$lang['id_lang']] = 'Wide Assortment';
            $result['TBCMSCUSTOMERSERVICES_SERVICES_6_DESC'][$lang['id_lang']] = 'Download the app';
        }
        Configuration::updateValue('TBCMSCUSTOMERSERVICES_TITLE', $result['TBCMSCUSTOMERSERVICES_TITLE']);
        $tmp = $result['TBCMSCUSTOMERSERVICES_SUB_DESCRIPTION'];
        Configuration::updateValue('TBCMSCUSTOMERSERVICES_SUB_DESCRIPTION', $tmp);
        Configuration::updateValue(
            'TBCMSCUSTOMERSERVICES_DESCRIPTION',
            $result['TBCMSCUSTOMERSERVICES_DESCRIPTION']
        );
        Configuration::updateValue('TBCMSCUSTOMERSERVICES_IMG', $result['TBCMSCUSTOMERSERVICES_IMG']);

        // Services 1
        $tmp = $result['TBCMSCUSTOMERSERVICES_SERVICES_1_IMAGE'];
        Configuration::updateValue('TBCMSCUSTOMERSERVICES_SERVICES_1_IMAGE', $tmp);
        $tmp = $result['TBCMSCUSTOMERSERVICES_SERVICES_1_CAPTION'];
        Configuration::updateValue('TBCMSCUSTOMERSERVICES_SERVICES_1_CAPTION', $tmp);
        $tmp = $result['TBCMSCUSTOMERSERVICES_SERVICES_1_DESC'];
        Configuration::updateValue('TBCMSCUSTOMERSERVICES_SERVICES_1_DESC', $tmp);
        Configuration::updateValue('TBCMSCUSTOMERSERVICES_SERVICES_1_STATUS', 1);

        // Services 2
        $tmp = $result['TBCMSCUSTOMERSERVICES_SERVICES_2_IMAGE'];
        Configuration::updateValue('TBCMSCUSTOMERSERVICES_SERVICES_2_IMAGE', $tmp);
        $tmp = $result['TBCMSCUSTOMERSERVICES_SERVICES_2_CAPTION'];
        Configuration::updateValue('TBCMSCUSTOMERSERVICES_SERVICES_2_CAPTION', $tmp);
        $tmp = $result['TBCMSCUSTOMERSERVICES_SERVICES_2_DESC'];
        Configuration::updateValue('TBCMSCUSTOMERSERVICES_SERVICES_2_DESC', $tmp);
        Configuration::updateValue('TBCMSCUSTOMERSERVICES_SERVICES_2_STATUS', 1);

        // Services 3
        $tmp = $result['TBCMSCUSTOMERSERVICES_SERVICES_3_IMAGE'];
        Configuration::updateValue('TBCMSCUSTOMERSERVICES_SERVICES_3_IMAGE', $tmp);
        $tmp = $result['TBCMSCUSTOMERSERVICES_SERVICES_3_CAPTION'];
        Configuration::updateValue('TBCMSCUSTOMERSERVICES_SERVICES_3_CAPTION', $tmp);
        $tmp = $result['TBCMSCUSTOMERSERVICES_SERVICES_3_DESC'];
        Configuration::updateValue('TBCMSCUSTOMERSERVICES_SERVICES_3_DESC', $tmp);
        Configuration::updateValue('TBCMSCUSTOMERSERVICES_SERVICES_3_STATUS', 1);

        // Services 4
        $tmp = $result['TBCMSCUSTOMERSERVICES_SERVICES_4_IMAGE'];
        Configuration::updateValue('TBCMSCUSTOMERSERVICES_SERVICES_4_IMAGE', $tmp);
        $tmp = $result['TBCMSCUSTOMERSERVICES_SERVICES_4_CAPTION'];
        Configuration::updateValue('TBCMSCUSTOMERSERVICES_SERVICES_4_CAPTION', $tmp);
        $tmp = $result['TBCMSCUSTOMERSERVICES_SERVICES_4_DESC'];
        Configuration::updateValue('TBCMSCUSTOMERSERVICES_SERVICES_4_DESC', $tmp);
        Configuration::updateValue('TBCMSCUSTOMERSERVICES_SERVICES_4_STATUS', 1);

        $tmp = $result['TBCMSCUSTOMERSERVICES_SERVICES_5_IMAGE'];
        Configuration::updateValue('TBCMSCUSTOMERSERVICES_SERVICES_5_IMAGE', $tmp);
        $tmp = $result['TBCMSCUSTOMERSERVICES_SERVICES_5_CAPTION'];
        Configuration::updateValue('TBCMSCUSTOMERSERVICES_SERVICES_5_CAPTION', $tmp);
        $tmp = $result['TBCMSCUSTOMERSERVICES_SERVICES_5_DESC'];
        Configuration::updateValue('TBCMSCUSTOMERSERVICES_SERVICES_5_DESC', $tmp);
        Configuration::updateValue('TBCMSCUSTOMERSERVICES_SERVICES_5_STATUS', 1);


        $tmp = $result['TBCMSCUSTOMERSERVICES_SERVICES_6_IMAGE'];
        Configuration::updateValue('TBCMSCUSTOMERSERVICES_SERVICES_6_IMAGE', $tmp);
        $tmp = $result['TBCMSCUSTOMERSERVICES_SERVICES_6_CAPTION'];
        Configuration::updateValue('TBCMSCUSTOMERSERVICES_SERVICES_6_CAPTION', $tmp);
        $tmp = $result['TBCMSCUSTOMERSERVICES_SERVICES_6_DESC'];
        Configuration::updateValue('TBCMSCUSTOMERSERVICES_SERVICES_6_DESC', $tmp);
        Configuration::updateValue('TBCMSCUSTOMERSERVICES_SERVICES_6_STATUS', 1);
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
        Configuration::deleteByName('TBCMSCUSTOMERSERVICES_TITLE');
        Configuration::deleteByName('TBCMSCUSTOMERSERVICES_SUB_DESCRIPTION');
        Configuration::deleteByName('TBCMSCUSTOMERSERVICES_DESCRIPTION');
        Configuration::deleteByName('TBCMSCUSTOMERSERVICES_IMG');


        // Services 1
        Configuration::deleteByName('TBCMSCUSTOMERSERVICES_SERVICES_1_IMAGE');
        Configuration::deleteByName('TBCMSCUSTOMERSERVICES_SERVICES_1_CAPTION');
        Configuration::deleteByName('TBCMSCUSTOMERSERVICES_SERVICES_1_DESC');
        Configuration::deleteByName('TBCMSCUSTOMERSERVICES_SERVICES_1_STATUS');

        // Services 2
        Configuration::deleteByName('TBCMSCUSTOMERSERVICES_SERVICES_2_IMAGE');
        Configuration::deleteByName('TBCMSCUSTOMERSERVICES_SERVICES_2_CAPTION');
        Configuration::deleteByName('TBCMSCUSTOMERSERVICES_SERVICES_2_DESC');
        Configuration::deleteByName('TBCMSCUSTOMERSERVICES_SERVICES_2_STATUS');

        // Services 3
        Configuration::deleteByName('TBCMSCUSTOMERSERVICES_SERVICES_3_IMAGE');
        Configuration::deleteByName('TBCMSCUSTOMERSERVICES_SERVICES_3_CAPTION');
        Configuration::deleteByName('TBCMSCUSTOMERSERVICES_SERVICES_3_DESC');
        Configuration::deleteByName('TBCMSCUSTOMERSERVICES_SERVICES_3_STATUS');

        // Services 4
        Configuration::deleteByName('TBCMSCUSTOMERSERVICES_SERVICES_4_IMAGE');
        Configuration::deleteByName('TBCMSCUSTOMERSERVICES_SERVICES_4_CAPTION');
        Configuration::deleteByName('TBCMSCUSTOMERSERVICES_SERVICES_4_DESC');
        Configuration::deleteByName('TBCMSCUSTOMERSERVICES_SERVICES_4_STATUS');

        Configuration::deleteByName('TBCMSCUSTOMERSERVICES_SERVICES_5_IMAGE');
        Configuration::deleteByName('TBCMSCUSTOMERSERVICES_SERVICES_5_CAPTION');
        Configuration::deleteByName('TBCMSCUSTOMERSERVICES_SERVICES_5_DESC');
        Configuration::deleteByName('TBCMSCUSTOMERSERVICES_SERVICES_5_STATUS');

        Configuration::deleteByName('TBCMSCUSTOMERSERVICES_SERVICES_6_IMAGE');
        Configuration::deleteByName('TBCMSCUSTOMERSERVICES_SERVICES_6_CAPTION');
        Configuration::deleteByName('TBCMSCUSTOMERSERVICES_SERVICES_6_DESC');
        Configuration::deleteByName('TBCMSCUSTOMERSERVICES_SERVICES_6_STATUS');
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
        
        $message = '';
        $message = $this->postProcess();
        
        return $message.$this->renderForm();
    }

    public function postProcess()
    {
        $message = '';
        $languages = Language::getLanguages();
        $result = array();
        
        if (Tools::isSubmit('submitTbcmsCustomerServicesForm')) {
            $obj_image = new TbcmsCustomerServicesImageUpload();
            foreach ($languages as $lang) {
                if (!empty($_FILES['TBCMSCUSTOMERSERVICES_SERVICES_1_IMAGE_'.$lang['id_lang']]['name'])) {
                    $old_file = Configuration::get('TBCMSCUSTOMERSERVICES_SERVICES_1_IMAGE', $lang['id_lang']);
                    $new_file = $_FILES['TBCMSCUSTOMERSERVICES_SERVICES_1_IMAGE_'.$lang['id_lang']];
                    $ans = $obj_image->imageUploading($new_file, $old_file);
                    if ($ans['success']) {
                        $result['TBCMSCUSTOMERSERVICES_SERVICES_1_IMAGE'][$lang['id_lang']] = $ans['name'];
                    } else {
                        $message .= $ans['error'];
                        $result['TBCMSCUSTOMERSERVICES_SERVICES_1_IMAGE'][$lang['id_lang']] = $old_file;
                    }
                } else {
                    $old_file = Configuration::get('TBCMSCUSTOMERSERVICES_SERVICES_1_IMAGE', $lang['id_lang']);
                    $result['TBCMSCUSTOMERSERVICES_SERVICES_1_IMAGE'][$lang['id_lang']] = $old_file;
                }

                if (!empty($_FILES['TBCMSCUSTOMERSERVICES_SERVICES_2_IMAGE_'.$lang['id_lang']]['name'])) {
                    $old_file = Configuration::get('TBCMSCUSTOMERSERVICES_SERVICES_2_IMAGE', $lang['id_lang']);
                    $new_file = $_FILES['TBCMSCUSTOMERSERVICES_SERVICES_2_IMAGE_'.$lang['id_lang']];
                    $ans = $obj_image->imageUploading($new_file, $old_file);
                    if ($ans['success']) {
                        $result['TBCMSCUSTOMERSERVICES_SERVICES_2_IMAGE'][$lang['id_lang']] = $ans['name'];
                    } else {
                        $message .= $ans['error'];
                        $result['TBCMSCUSTOMERSERVICES_SERVICES_2_IMAGE'][$lang['id_lang']] = $old_file;
                    }
                } else {
                    $old_file = Configuration::get('TBCMSCUSTOMERSERVICES_SERVICES_2_IMAGE', $lang['id_lang']);
                    $result['TBCMSCUSTOMERSERVICES_SERVICES_2_IMAGE'][$lang['id_lang']] = $old_file;
                }

                if (!empty($_FILES['TBCMSCUSTOMERSERVICES_SERVICES_3_IMAGE_'.$lang['id_lang']]['name'])) {
                    $old_file = Configuration::get('TBCMSCUSTOMERSERVICES_SERVICES_3_IMAGE', $lang['id_lang']);
                    $new_file = $_FILES['TBCMSCUSTOMERSERVICES_SERVICES_3_IMAGE_'.$lang['id_lang']];
                    $ans = $obj_image->imageUploading($new_file, $old_file);
                    if ($ans['success']) {
                        $result['TBCMSCUSTOMERSERVICES_SERVICES_3_IMAGE'][$lang['id_lang']] = $ans['name'];
                    } else {
                        $message .= $ans['error'];
                        $result['TBCMSCUSTOMERSERVICES_SERVICES_3_IMAGE'][$lang['id_lang']] = $old_file;
                    }
                } else {
                    $old_file = Configuration::get('TBCMSCUSTOMERSERVICES_SERVICES_3_IMAGE', $lang['id_lang']);
                    $result['TBCMSCUSTOMERSERVICES_SERVICES_3_IMAGE'][$lang['id_lang']] = $old_file;
                }

                if (!empty($_FILES['TBCMSCUSTOMERSERVICES_SERVICES_4_IMAGE_'.$lang['id_lang']]['name'])) {
                    $old_file = Configuration::get('TBCMSCUSTOMERSERVICES_SERVICES_4_IMAGE', $lang['id_lang']);
                    $new_file = $_FILES['TBCMSCUSTOMERSERVICES_SERVICES_4_IMAGE_'.$lang['id_lang']];
                    $ans = $obj_image->imageUploading($new_file, $old_file);
                    if ($ans['success']) {
                        $result['TBCMSCUSTOMERSERVICES_SERVICES_4_IMAGE'][$lang['id_lang']] = $ans['name'];
                    } else {
                        $message .= $ans['error'];
                        $result['TBCMSCUSTOMERSERVICES_SERVICES_4_IMAGE'][$lang['id_lang']] = $old_file;
                    }
                } else {
                    $old_file = Configuration::get('TBCMSCUSTOMERSERVICES_SERVICES_4_IMAGE', $lang['id_lang']);
                    $result['TBCMSCUSTOMERSERVICES_SERVICES_4_IMAGE'][$lang['id_lang']] = $old_file;
                }

                if (!empty($_FILES['TBCMSCUSTOMERSERVICES_SERVICES_5_IMAGE_'.$lang['id_lang']]['name'])) {
                    $old_file = Configuration::get('TBCMSCUSTOMERSERVICES_SERVICES_5_IMAGE', $lang['id_lang']);
                    $new_file = $_FILES['TBCMSCUSTOMERSERVICES_SERVICES_5_IMAGE_'.$lang['id_lang']];
                    $ans = $obj_image->imageUploading($new_file, $old_file);
                    if ($ans['success']) {
                        $result['TBCMSCUSTOMERSERVICES_SERVICES_5_IMAGE'][$lang['id_lang']] = $ans['name'];
                    } else {
                        $message .= $ans['error'];
                        $result['TBCMSCUSTOMERSERVICES_SERVICES_5_IMAGE'][$lang['id_lang']] = $old_file;
                    }
                } else {
                    $old_file = Configuration::get('TBCMSCUSTOMERSERVICES_SERVICES_5_IMAGE', $lang['id_lang']);
                    $result['TBCMSCUSTOMERSERVICES_SERVICES_5_IMAGE'][$lang['id_lang']] = $old_file;
                }


                if (!empty($_FILES['TBCMSCUSTOMERSERVICES_SERVICES_6_IMAGE_'.$lang['id_lang']]['name'])) {
                    $old_file = Configuration::get('TBCMSCUSTOMERSERVICES_SERVICES_6_IMAGE', $lang['id_lang']);
                    $new_file = $_FILES['TBCMSCUSTOMERSERVICES_SERVICES_6_IMAGE_'.$lang['id_lang']];
                    $ans = $obj_image->imageUploading($new_file, $old_file);
                    if ($ans['success']) {
                        $result['TBCMSCUSTOMERSERVICES_SERVICES_6_IMAGE'][$lang['id_lang']] = $ans['name'];
                    } else {
                        $message .= $ans['error'];
                        $result['TBCMSCUSTOMERSERVICES_SERVICES_6_IMAGE'][$lang['id_lang']] = $old_file;
                    }
                } else {
                    $old_file = Configuration::get('TBCMSCUSTOMERSERVICES_SERVICES_6_IMAGE', $lang['id_lang']);
                    $result['TBCMSCUSTOMERSERVICES_SERVICES_6_IMAGE'][$lang['id_lang']] = $old_file;
                }


                $tmp = Tools::getValue('TBCMSCUSTOMERSERVICES_SERVICES_1_CAPTION_'.$lang['id_lang']);
                $result['TBCMSCUSTOMERSERVICES_SERVICES_1_CAPTION'][$lang['id_lang']] = $tmp;
                $tmp = Tools::getValue('TBCMSCUSTOMERSERVICES_SERVICES_1_DESC_'.$lang['id_lang']);
                $result['TBCMSCUSTOMERSERVICES_SERVICES_1_DESC'][$lang['id_lang']] = $tmp;

                $tmp = Tools::getValue('TBCMSCUSTOMERSERVICES_SERVICES_2_CAPTION_'.$lang['id_lang']);
                $result['TBCMSCUSTOMERSERVICES_SERVICES_2_CAPTION'][$lang['id_lang']] = $tmp;
                $tmp = Tools::getValue('TBCMSCUSTOMERSERVICES_SERVICES_2_DESC_'.$lang['id_lang']);
                $result['TBCMSCUSTOMERSERVICES_SERVICES_2_DESC'][$lang['id_lang']] = $tmp;

                $tmp = Tools::getValue('TBCMSCUSTOMERSERVICES_SERVICES_3_CAPTION_'.$lang['id_lang']);
                $result['TBCMSCUSTOMERSERVICES_SERVICES_3_CAPTION'][$lang['id_lang']] = $tmp;
                $tmp = Tools::getValue('TBCMSCUSTOMERSERVICES_SERVICES_3_DESC_'.$lang['id_lang']);
                $result['TBCMSCUSTOMERSERVICES_SERVICES_3_DESC'][$lang['id_lang']] = $tmp;

                $tmp = Tools::getValue('TBCMSCUSTOMERSERVICES_SERVICES_4_CAPTION_'.$lang['id_lang']);
                $result['TBCMSCUSTOMERSERVICES_SERVICES_4_CAPTION'][$lang['id_lang']] = $tmp;
                $tmp = Tools::getValue('TBCMSCUSTOMERSERVICES_SERVICES_4_DESC_'.$lang['id_lang']);
                $result['TBCMSCUSTOMERSERVICES_SERVICES_4_DESC'][$lang['id_lang']] = $tmp;

                $tmp = Tools::getValue('TBCMSCUSTOMERSERVICES_SERVICES_5_CAPTION_'.$lang['id_lang']);
                $result['TBCMSCUSTOMERSERVICES_SERVICES_5_CAPTION'][$lang['id_lang']] = $tmp;
                $tmp = Tools::getValue('TBCMSCUSTOMERSERVICES_SERVICES_5_DESC_'.$lang['id_lang']);
                $result['TBCMSCUSTOMERSERVICES_SERVICES_5_DESC'][$lang['id_lang']] = $tmp;

                $tmp = Tools::getValue('TBCMSCUSTOMERSERVICES_SERVICES_6_CAPTION_'.$lang['id_lang']);
                $result['TBCMSCUSTOMERSERVICES_SERVICES_6_CAPTION'][$lang['id_lang']] = $tmp;
                $tmp = Tools::getValue('TBCMSCUSTOMERSERVICES_SERVICES_6_DESC_'.$lang['id_lang']);
                $result['TBCMSCUSTOMERSERVICES_SERVICES_6_DESC'][$lang['id_lang']] = $tmp;
            }

            $tmp = $result['TBCMSCUSTOMERSERVICES_SERVICES_1_IMAGE'];
            Configuration::updateValue('TBCMSCUSTOMERSERVICES_SERVICES_1_IMAGE', $tmp);
            $tmp = $result['TBCMSCUSTOMERSERVICES_SERVICES_1_CAPTION'];
            Configuration::updateValue('TBCMSCUSTOMERSERVICES_SERVICES_1_CAPTION', $tmp);
            $tmp = $result['TBCMSCUSTOMERSERVICES_SERVICES_1_DESC'];
            Configuration::updateValue('TBCMSCUSTOMERSERVICES_SERVICES_1_DESC', $tmp);
            $tmp = Tools::getValue('TBCMSCUSTOMERSERVICES_SERVICES_1_STATUS');
            Configuration::updateValue('TBCMSCUSTOMERSERVICES_SERVICES_1_STATUS', $tmp);

            $tmp = $result['TBCMSCUSTOMERSERVICES_SERVICES_2_IMAGE'];
            Configuration::updateValue('TBCMSCUSTOMERSERVICES_SERVICES_2_IMAGE', $tmp);
            $tmp = $result['TBCMSCUSTOMERSERVICES_SERVICES_2_CAPTION'];
            Configuration::updateValue('TBCMSCUSTOMERSERVICES_SERVICES_2_CAPTION', $tmp);
            $tmp = $result['TBCMSCUSTOMERSERVICES_SERVICES_2_DESC'];
            Configuration::updateValue('TBCMSCUSTOMERSERVICES_SERVICES_2_DESC', $tmp);
            $tmp = Tools::getValue('TBCMSCUSTOMERSERVICES_SERVICES_2_STATUS');
            Configuration::updateValue('TBCMSCUSTOMERSERVICES_SERVICES_2_STATUS', $tmp);

            $tmp = $result['TBCMSCUSTOMERSERVICES_SERVICES_3_IMAGE'];
            Configuration::updateValue('TBCMSCUSTOMERSERVICES_SERVICES_3_IMAGE', $tmp);
            $tmp = $result['TBCMSCUSTOMERSERVICES_SERVICES_3_CAPTION'];
            Configuration::updateValue('TBCMSCUSTOMERSERVICES_SERVICES_3_CAPTION', $tmp);
            $tmp = $result['TBCMSCUSTOMERSERVICES_SERVICES_3_DESC'];
            Configuration::updateValue('TBCMSCUSTOMERSERVICES_SERVICES_3_DESC', $tmp);
            $tmp = Tools::getValue('TBCMSCUSTOMERSERVICES_SERVICES_3_STATUS');
            Configuration::updateValue('TBCMSCUSTOMERSERVICES_SERVICES_3_STATUS', $tmp);

            $tmp = $result['TBCMSCUSTOMERSERVICES_SERVICES_4_IMAGE'];
            Configuration::updateValue('TBCMSCUSTOMERSERVICES_SERVICES_4_IMAGE', $tmp);
            $tmp = $result['TBCMSCUSTOMERSERVICES_SERVICES_4_CAPTION'];
            Configuration::updateValue('TBCMSCUSTOMERSERVICES_SERVICES_4_CAPTION', $tmp);
            $tmp = $result['TBCMSCUSTOMERSERVICES_SERVICES_4_DESC'];
            Configuration::updateValue('TBCMSCUSTOMERSERVICES_SERVICES_4_DESC', $tmp);
            $tmp = Tools::getValue('TBCMSCUSTOMERSERVICES_SERVICES_4_STATUS');
            Configuration::updateValue('TBCMSCUSTOMERSERVICES_SERVICES_4_STATUS', $tmp);

            $tmp = $result['TBCMSCUSTOMERSERVICES_SERVICES_5_IMAGE'];
            Configuration::updateValue('TBCMSCUSTOMERSERVICES_SERVICES_5_IMAGE', $tmp);
            $tmp = $result['TBCMSCUSTOMERSERVICES_SERVICES_5_CAPTION'];
            Configuration::updateValue('TBCMSCUSTOMERSERVICES_SERVICES_5_CAPTION', $tmp);
            $tmp = $result['TBCMSCUSTOMERSERVICES_SERVICES_5_DESC'];
            Configuration::updateValue('TBCMSCUSTOMERSERVICES_SERVICES_5_DESC', $tmp);
            $tmp = Tools::getValue('TBCMSCUSTOMERSERVICES_SERVICES_5_STATUS');
            Configuration::updateValue('TBCMSCUSTOMERSERVICES_SERVICES_5_STATUS', $tmp);

            $tmp = $result['TBCMSCUSTOMERSERVICES_SERVICES_6_IMAGE'];
            Configuration::updateValue('TBCMSCUSTOMERSERVICES_SERVICES_6_IMAGE', $tmp);
            $tmp = $result['TBCMSCUSTOMERSERVICES_SERVICES_6_CAPTION'];
            Configuration::updateValue('TBCMSCUSTOMERSERVICES_SERVICES_6_CAPTION', $tmp);
            $tmp = $result['TBCMSCUSTOMERSERVICES_SERVICES_6_DESC'];
            Configuration::updateValue('TBCMSCUSTOMERSERVICES_SERVICES_6_DESC', $tmp);
            $tmp = Tools::getValue('TBCMSCUSTOMERSERVICES_SERVICES_6_STATUS');
            Configuration::updateValue('TBCMSCUSTOMERSERVICES_SERVICES_6_STATUS', $tmp);

            $this->clearCustomSmartyCache('tbcmscustomerservices_display_home.tpl');
            $this->clearCustomSmartyCache('tbcmscustomerservices_display_left_column.tpl');
            $message .= $this->displayConfirmation($this->l("Customer Services is Updated"));
        }

        if (Tools::isSubmit('submittbcmsCustomerServicesMainTitleForm')) {
            $languages = Language::getLanguages();
            $obj_image = new TbcmsCustomerServicesImageUpload();
            foreach ($languages as $lang) {
                if (!empty($_FILES['TBCMSCUSTOMERSERVICES_IMG_'.$lang['id_lang']]['name'])) {
                    $old_file = Configuration::get('TBCMSCUSTOMERSERVICES_IMG', $lang['id_lang']);
                    $new_file = $_FILES['TBCMSCUSTOMERSERVICES_IMG_'.$lang['id_lang']];
                    $ans = $obj_image->imageUploading($new_file, $old_file);
                    if ($ans['success']) {
                        $result['TBCMSCUSTOMERSERVICES_IMG'][$lang['id_lang']] = $ans['name'];
                    } else {
                        $message .= $ans['error'];
                        $result['TBCMSCUSTOMERSERVICES_IMG'][$lang['id_lang']] = $old_file;
                    }
                } else {
                    $old_file = Configuration::get('TBCMSCUSTOMERSERVICES_IMG', $lang['id_lang']);
                    $result['TBCMSCUSTOMERSERVICES_IMG'][$lang['id_lang']] = $old_file;
                }

                $tmp = Tools::getValue('TBCMSCUSTOMERSERVICES_TITLE_'.$lang['id_lang']);
                $result['TBCMSCUSTOMERSERVICES_TITLE'][$lang['id_lang']] = $tmp;

                $tmp = Tools::getValue('TBCMSCUSTOMERSERVICES_SUB_DESCRIPTION_'.$lang['id_lang']);
                $result['TBCMSCUSTOMERSERVICES_SUB_DESCRIPTION'][$lang['id_lang']] = $tmp;

                $tmp = Tools::getValue('TBCMSCUSTOMERSERVICES_DESCRIPTION_'.$lang['id_lang']);
                $result['TBCMSCUSTOMERSERVICES_DESCRIPTION'][$lang['id_lang']] = $tmp;
            }
            Configuration::updateValue('TBCMSCUSTOMERSERVICES_IMG', $result['TBCMSCUSTOMERSERVICES_IMG']);
            Configuration::updateValue('TBCMSCUSTOMERSERVICES_TITLE', $result['TBCMSCUSTOMERSERVICES_TITLE']);
            $tmp = $result['TBCMSCUSTOMERSERVICES_SUB_DESCRIPTION'];
            Configuration::updateValue('TBCMSCUSTOMERSERVICES_SUB_DESCRIPTION', $tmp);

            $tmp = $result['TBCMSCUSTOMERSERVICES_DESCRIPTION'];
            Configuration::updateValue('TBCMSCUSTOMERSERVICES_DESCRIPTION', $tmp);
            $message .= $this->displayConfirmation($this->l("Main Title is Updated."));
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

        $tbcms_obj = new TbcmsCustomerServicesStatus();
        $show_fields = $tbcms_obj->fieldStatusInformation();
        if ($show_fields['main_status']) {
            $form[] = $this->tbcmsCustomerServicesMainTitleForm();
        }

        if ($show_fields['record_form']) {
            $form[] = $this->tbcmsCustomerServicesForm();
        }

        return $helper->generateForm($form);
    }

    protected function tbcmsCustomerServicesMainTitleForm()
    {
        $tbcms_obj = new TbcmsCustomerServicesStatus();
        $show_fields = $tbcms_obj->fieldStatusInformation();
        $input = array();

        if ($show_fields['main_title']) {
            $input[] = array(
                    'col' => 7,
                    'type' => 'text',
                    'name' => 'TBCMSCUSTOMERSERVICES_TITLE',
                    'label' => $this->l('Main Title'),
                    'lang' => true,
                );
        }

        if ($show_fields['main_sub_title']) {
            $input[] = array(
                    'col' => 7,
                    'type' => 'text',
                    'name' => 'TBCMSCUSTOMERSERVICES_SUB_DESCRIPTION',
                    'label' => $this->l('Short Description'),
                    'lang' => true,
                );
        }

        if ($show_fields['main_description']) {
            $input[] = array(
                    'col' => 7,
                    'type' => 'text',
                    'name' => 'TBCMSCUSTOMERSERVICES_DESCRIPTION',
                    'label' => $this->l('Description'),
                    'lang' => true,
                );
        }

        if ($show_fields['main_image']) {
            $input[] = array(
                        'type' => 'image_file',
                        'name' => 'TBCMSCUSTOMERSERVICES_IMG',
                        'label' => $this->l('Image'),
                );
        }


        return array(
            'form' => array(
                'legend' => array(
                'title' => $this->l('Main Title'),
                'icon' => 'icon-support',
                ),
                'input' => $input,
                'submit' => array(
                    'title' => $this->l('Save'),
                    'name' => 'submittbcmsCustomerServicesMainTitleForm',
                ),
            ),
        );
    }

    protected function tbcmsCustomerServicesForm()
    {
        $tbcms_obj = new TbcmsCustomerServicesStatus();
        $result = $tbcms_obj->fieldStatusInformation();
        $input = array();

        for ($i=1; $i<=$result['num_services']; $i++) {
            if ($result['image_upload'] == 1) {
                $input[] = array(
                        'type' => 'image_file',
                        'name' => 'TBCMSCUSTOMERSERVICES_SERVICES_'.$i.'_IMAGE',
                        'label' => $this->l('Services Image '.$i),
                    );
            }

            $input[] = array(
                        'col' => 9,
                        'type' => 'text',
                        'name' => 'TBCMSCUSTOMERSERVICES_SERVICES_'.$i.'_CAPTION',
                        'label' => $this->l('Services Caption '.$i),
                        'lang' => true,
                        'desc' => $this->l('Display Caption of Service '.$i),
                    );

            $input[] = array(
                        'col' => 9,
                        'type' => 'text',
                        'name' => 'TBCMSCUSTOMERSERVICES_SERVICES_'.$i.'_DESC',
                        'label' => $this->l('Services Description '.$i),
                        'lang' => true,
                        'desc' => $this->l('Display Description of Service '.$i),
                    );

            $input[] =  array(
                        'type' => 'switch',
                        'label' => $this->l('Service Status '.$i),
                        'name' => 'TBCMSCUSTOMERSERVICES_SERVICES_'.$i.'_STATUS',
                        'desc' => $this->l('Hide or Show Service '.$i),
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
                'title' => $this->l('Services'),
                'icon' => 'icon-support',
                ),
                'input' => $input,
                'submit' => array(
                    'title' => $this->l('Save'),
                    'name' => 'submitTbcmsCustomerServicesForm',
                ),
            ),
        );
    }

 
    protected function getConfigFormValues()
    {
        $fields = array();
        $languages = Language::getLanguages();

        foreach ($languages as $lang) {
            $a = Configuration::get('TBCMSCUSTOMERSERVICES_TITLE', $lang['id_lang']);
            $fields['TBCMSCUSTOMERSERVICES_TITLE'][$lang['id_lang']] = $a;
            $a = Configuration::get('TBCMSCUSTOMERSERVICES_SUB_DESCRIPTION', $lang['id_lang']);
            $fields['TBCMSCUSTOMERSERVICES_SUB_DESCRIPTION'][$lang['id_lang']] = $a;
            $a = Configuration::get('TBCMSCUSTOMERSERVICES_DESCRIPTION', $lang['id_lang']);
            $fields['TBCMSCUSTOMERSERVICES_DESCRIPTION'][$lang['id_lang']] = $a;
            $a = Configuration::get('TBCMSCUSTOMERSERVICES_IMG', $lang['id_lang']);
            $fields['TBCMSCUSTOMERSERVICES_IMG'][$lang['id_lang']] = $a;


            $a = Configuration::get('TBCMSCUSTOMERSERVICES_SERVICES_1_IMAGE', $lang['id_lang']);
            $fields['TBCMSCUSTOMERSERVICES_SERVICES_1_IMAGE'][$lang['id_lang']] = $a;
            $a = Configuration::get('TBCMSCUSTOMERSERVICES_SERVICES_1_CAPTION', $lang['id_lang']);
            $fields['TBCMSCUSTOMERSERVICES_SERVICES_1_CAPTION'][$lang['id_lang']] = $a;
            $a = Configuration::get('TBCMSCUSTOMERSERVICES_SERVICES_1_DESC', $lang['id_lang']);
            $fields['TBCMSCUSTOMERSERVICES_SERVICES_1_DESC'][$lang['id_lang']] = $a;

            $a = Configuration::get('TBCMSCUSTOMERSERVICES_SERVICES_2_IMAGE', $lang['id_lang']);
            $fields['TBCMSCUSTOMERSERVICES_SERVICES_2_IMAGE'][$lang['id_lang']] = $a;
            $a = Configuration::get('TBCMSCUSTOMERSERVICES_SERVICES_2_CAPTION', $lang['id_lang']);
            $fields['TBCMSCUSTOMERSERVICES_SERVICES_2_CAPTION'][$lang['id_lang']] = $a;
            $a = Configuration::get('TBCMSCUSTOMERSERVICES_SERVICES_2_DESC', $lang['id_lang']);
            $fields['TBCMSCUSTOMERSERVICES_SERVICES_2_DESC'][$lang['id_lang']] = $a;

            $a = Configuration::get('TBCMSCUSTOMERSERVICES_SERVICES_3_IMAGE', $lang['id_lang']);
            $fields['TBCMSCUSTOMERSERVICES_SERVICES_3_IMAGE'][$lang['id_lang']] = $a;
            $a = Configuration::get('TBCMSCUSTOMERSERVICES_SERVICES_3_CAPTION', $lang['id_lang']);
            $fields['TBCMSCUSTOMERSERVICES_SERVICES_3_CAPTION'][$lang['id_lang']] = $a;
            $a = Configuration::get('TBCMSCUSTOMERSERVICES_SERVICES_3_DESC', $lang['id_lang']);
            $fields['TBCMSCUSTOMERSERVICES_SERVICES_3_DESC'][$lang['id_lang']] = $a;

            $a = Configuration::get('TBCMSCUSTOMERSERVICES_SERVICES_4_IMAGE', $lang['id_lang']);
            $fields['TBCMSCUSTOMERSERVICES_SERVICES_4_IMAGE'][$lang['id_lang']] = $a;
            $a = Configuration::get('TBCMSCUSTOMERSERVICES_SERVICES_4_CAPTION', $lang['id_lang']);
            $fields['TBCMSCUSTOMERSERVICES_SERVICES_4_CAPTION'][$lang['id_lang']] = $a;
            $a = Configuration::get('TBCMSCUSTOMERSERVICES_SERVICES_4_DESC', $lang['id_lang']);
            $fields['TBCMSCUSTOMERSERVICES_SERVICES_4_DESC'][$lang['id_lang']] = $a;

            $a = Configuration::get('TBCMSCUSTOMERSERVICES_SERVICES_5_IMAGE', $lang['id_lang']);
            $fields['TBCMSCUSTOMERSERVICES_SERVICES_5_IMAGE'][$lang['id_lang']] = $a;
            $a = Configuration::get('TBCMSCUSTOMERSERVICES_SERVICES_5_CAPTION', $lang['id_lang']);
            $fields['TBCMSCUSTOMERSERVICES_SERVICES_5_CAPTION'][$lang['id_lang']] = $a;
            $a = Configuration::get('TBCMSCUSTOMERSERVICES_SERVICES_5_DESC', $lang['id_lang']);
            $fields['TBCMSCUSTOMERSERVICES_SERVICES_5_DESC'][$lang['id_lang']] = $a;

            $a = Configuration::get('TBCMSCUSTOMERSERVICES_SERVICES_6_IMAGE', $lang['id_lang']);
            $fields['TBCMSCUSTOMERSERVICES_SERVICES_6_IMAGE'][$lang['id_lang']] = $a;
            $a = Configuration::get('TBCMSCUSTOMERSERVICES_SERVICES_6_CAPTION', $lang['id_lang']);
            $fields['TBCMSCUSTOMERSERVICES_SERVICES_6_CAPTION'][$lang['id_lang']] = $a;
            $a = Configuration::get('TBCMSCUSTOMERSERVICES_SERVICES_6_DESC', $lang['id_lang']);
            $fields['TBCMSCUSTOMERSERVICES_SERVICES_6_DESC'][$lang['id_lang']] = $a;
        }

        $tmp = Configuration::get('TBCMSCUSTOMERSERVICES_SERVICES_1_STATUS');
        $fields['TBCMSCUSTOMERSERVICES_SERVICES_1_STATUS'] = $tmp;

        $tmp = Configuration::get('TBCMSCUSTOMERSERVICES_SERVICES_2_STATUS');
        $fields['TBCMSCUSTOMERSERVICES_SERVICES_2_STATUS'] = $tmp;

        $tmp = Configuration::get('TBCMSCUSTOMERSERVICES_SERVICES_3_STATUS');
        $fields['TBCMSCUSTOMERSERVICES_SERVICES_3_STATUS'] = $tmp;

        $tmp = Configuration::get('TBCMSCUSTOMERSERVICES_SERVICES_4_STATUS');
        $fields['TBCMSCUSTOMERSERVICES_SERVICES_4_STATUS'] = $tmp;

        $tmp = Configuration::get('TBCMSCUSTOMERSERVICES_SERVICES_5_STATUS');
        $fields['TBCMSCUSTOMERSERVICES_SERVICES_5_STATUS'] = $tmp;

        $tmp = Configuration::get('TBCMSCUSTOMERSERVICES_SERVICES_6_STATUS');
        $fields['TBCMSCUSTOMERSERVICES_SERVICES_6_STATUS'] = $tmp;

        $path = _MODULE_DIR_.$this->name."/views/img/";
        $this->context->smarty->assign("path", $path);

        return $fields;
    }

    public function showFrontData()
    {
        $cookie = Context::getContext()->cookie;
        $id_lang = $cookie->id_lang;

        $result = array();

        $tmp = Configuration::get('TBCMSCUSTOMERSERVICES_SERVICES_1_IMAGE', $id_lang);
        $result['service_1']['image'] = $tmp;
        $tmp = Configuration::get('TBCMSCUSTOMERSERVICES_SERVICES_1_CAPTION', $id_lang);
        $result['service_1']['title'] = $tmp;
        $tmp = Configuration::get('TBCMSCUSTOMERSERVICES_SERVICES_1_DESC', $id_lang);
        $result['service_1']['desc'] = $tmp;
        $result['service_1']['status'] = Configuration::get('TBCMSCUSTOMERSERVICES_SERVICES_1_STATUS');

        $tmp = Configuration::get('TBCMSCUSTOMERSERVICES_SERVICES_2_IMAGE', $id_lang);
        $result['service_2']['image'] = $tmp;
        $tmp = Configuration::get('TBCMSCUSTOMERSERVICES_SERVICES_2_CAPTION', $id_lang);
        $result['service_2']['title'] = $tmp;
        $tmp = Configuration::get('TBCMSCUSTOMERSERVICES_SERVICES_2_DESC', $id_lang);
        $result['service_2']['desc'] = $tmp;
        $result['service_2']['status'] = Configuration::get('TBCMSCUSTOMERSERVICES_SERVICES_2_STATUS');

        $tmp = Configuration::get('TBCMSCUSTOMERSERVICES_SERVICES_3_IMAGE', $id_lang);
        $result['service_3']['image'] = $tmp;
        $tmp = Configuration::get('TBCMSCUSTOMERSERVICES_SERVICES_3_CAPTION', $id_lang);
        $result['service_3']['title'] = $tmp;
        $tmp = Configuration::get('TBCMSCUSTOMERSERVICES_SERVICES_3_DESC', $id_lang);
        $result['service_3']['desc'] = $tmp;
        $result['service_3']['status'] = Configuration::get('TBCMSCUSTOMERSERVICES_SERVICES_3_STATUS');

        $tmp = Configuration::get('TBCMSCUSTOMERSERVICES_SERVICES_4_IMAGE', $id_lang);
        $result['service_4']['image'] = $tmp;
        $tmp = Configuration::get('TBCMSCUSTOMERSERVICES_SERVICES_4_CAPTION', $id_lang);
        $result['service_4']['title'] = $tmp;
        $tmp = Configuration::get('TBCMSCUSTOMERSERVICES_SERVICES_4_DESC', $id_lang);
        $result['service_4']['desc'] = $tmp;
        $result['service_4']['status'] = Configuration::get('TBCMSCUSTOMERSERVICES_SERVICES_4_STATUS');

        $tmp = Configuration::get('TBCMSCUSTOMERSERVICES_SERVICES_5_IMAGE', $id_lang);
        $result['service_5']['image'] = $tmp;
        $tmp = Configuration::get('TBCMSCUSTOMERSERVICES_SERVICES_5_CAPTION', $id_lang);
        $result['service_5']['title'] = $tmp;
        $tmp = Configuration::get('TBCMSCUSTOMERSERVICES_SERVICES_5_DESC', $id_lang);
        $result['service_5']['desc'] = $tmp;
        $result['service_5']['status'] = Configuration::get('TBCMSCUSTOMERSERVICES_SERVICES_5_STATUS');


        $tmp = Configuration::get('TBCMSCUSTOMERSERVICES_SERVICES_6_IMAGE', $id_lang);
        $result['service_6']['image'] = $tmp;
        $tmp = Configuration::get('TBCMSCUSTOMERSERVICES_SERVICES_6_CAPTION', $id_lang);
        $result['service_6']['title'] = $tmp;
        $tmp = Configuration::get('TBCMSCUSTOMERSERVICES_SERVICES_6_DESC', $id_lang);
        $result['service_6']['desc'] = $tmp;
        $result['service_6']['status'] = Configuration::get('TBCMSCUSTOMERSERVICES_SERVICES_6_STATUS');

        return $result;
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

        $tbcms_obj = new TbcmsCustomerServicesStatus();
        $main_heading = $tbcms_obj->fieldStatusInformation();

        if ($main_heading['main_status']) {
            $main_heading_data = array();
            $main_heading_data['title'] = Configuration::get('TBCMSCUSTOMERSERVICES_TITLE', $id_lang);
            $main_heading_data['short_desc'] = Configuration::get('TBCMSCUSTOMERSERVICES_SUB_DESCRIPTION', $id_lang);
            $main_heading_data['desc'] = Configuration::get('TBCMSCUSTOMERSERVICES_DESCRIPTION', $id_lang);
            $main_heading_data['image'] = Configuration::get('TBCMSCUSTOMERSERVICES_IMG', $id_lang);
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

    public function hookdisplayHeader()
    {
        $this->context->controller->addJS($this->_path.'views/js/front.js');
        $this->context->controller->addCSS($this->_path.'views/css/front.css');
    }//hookDisplayHeader()


    public function hookdisplayWrapperTop()
    {
        return $this->hookDisplayHome();
    }

    public function hookdisplayCustomerServices()
    {
        return $this->hookDisplayHome();
    }

    public function hookdisplayFooterBefore()
    {
        return $this->hookDisplayHome();
    }

    public function hookdisplayWrapperBottom()
    {
        return $this->hookDisplayHome();
    }


    public function hookdisplayHome()
    {
        if (!Cache::isStored('tbcmscustomerservices_display_home.tpl')) {
            $output = $this->showFrontSideResult();
            if ($output) {
                $output = $this->display(__FILE__, 'views/templates/front/display_home.tpl');
            } else {
                $output = '';
            }
            Cache::store('tbcmscustomerservices_display_home.tpl', $output);
        }

        return Cache::retrieve('tbcmscustomerservices_display_home.tpl');
    }

    public function hookdisplayRightColumn()
    {
        return $this->hookdisplayLeftColumn();
    }


    public function hookdisplayLeftColumn()
    {
        if (!Cache::isStored('tbcmscustomerservices_display_left_column.tpl')) {
            $output = $this->showFrontSideResult();
            if ($output) {
                $output = $this->display(__FILE__, 'views/templates/front/display_left_column.tpl');
            } else {
                $output = '';
            }
            Cache::store('tbcmscustomerservices_display_left_column.tpl', $output);
        }

        return Cache::retrieve('tbcmscustomerservices_display_left_column.tpl');
    }
}
