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

include_once('classes/tbcmspaymenticon_status.class.php');
include_once('classes/tbcmspaymenticon_image_upload.class.php');

class TbcmsPaymentIcon extends Module
{
    public $id_shop_group = '';
    public $id_shop = '';
    public function __construct()
    {
        $this->name = 'tbcmspaymenticon';
        $this->tab = 'front_office_features';
        $this->version = '2.1.9';
        $this->author = 'TemplateBeta';
        $this->need_instance = 0;

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('TemplateBeta - Payment Icon');
        $this->description = $this->l('Its Show Payment Icon on Front Side');

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
        $this->createDefaultData();
        
        return parent::install()
            && $this->registerHook('displayBackOfficeHeader')
            && $this->registerHook('displayHeader')
            && $this->registerHook('displayPaymentIcon');
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
            $tab->name[$lang['id_lang']] = "Payment Icon";
        }
        $tab->id_parent = $parentTab_2->id;
        $tab->module = $this->name;
        $response &= $tab->add();

        return $response;
    }

    public function createDefaultData()
    {
        $num_of_data = 4;
        $this->createVariable();
        $this->createTable();
        $this->insertSmapleData($num_of_data);
    }

    public function createVariable()
    {
        $languages = Language::getLanguages();
        $result = array();
        foreach ($languages as $lang) {
            $result['TBCMSPAYMENTICON_TITLE'][$lang['id_lang']] = 'Main Title';
            $result['TBCMSPAYMENTICON_SUB_DESCRIPTION'][$lang['id_lang']] = 'Sub Description';
            $result['TBCMSPAYMENTICON_DESCRIPTION'][$lang['id_lang']] = 'Description';
            $result['TBCMSPAYMENTICON_IMG'][$lang['id_lang']] = 'demo_main_img.jpg';
        }

        Configuration::updateValue('TBCMSPAYMENTICON_TITLE', $result['TBCMSPAYMENTICON_TITLE']);
        Configuration::updateValue('TBCMSPAYMENTICON_SUB_DESCRIPTION', $result['TBCMSPAYMENTICON_SUB_DESCRIPTION']);
        Configuration::updateValue('TBCMSPAYMENTICON_DESCRIPTION', $result['TBCMSPAYMENTICON_DESCRIPTION']);
        Configuration::updateValue('TBCMSPAYMENTICON_IMG', $result['TBCMSPAYMENTICON_IMG']);
    }


    public function createTable()
    {
        $create_table = array();
        $create_table[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'tbcmspaymenticon` (
                        `id_tbcmspaymenticon` int(11) AUTO_INCREMENT PRIMARY KEY,
                        `position` int(11),
                        `id_shop_group` int(11),
                        `id_shop` int(11),
                        `image` VARCHAR(100),
                        `link` VARCHAR(255),
                        `status` varchar(3)
                    ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

        $create_table[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'tbcmspaymenticon_lang` (
                        `id_tbcmspaymenticon_lang` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                        `id_tbcmspaymenticon` INT NOT NULL,
                        `id_shop_group` int(11),
                        `id_shop` int(11),
                        `id_lang` INT NOT NULL,
                        `title` VARCHAR(255)
                    ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

        foreach ($create_table as $table) {
            Db::getInstance()->execute($table);
        }
    }

    public function insertSmapleData($num_of_data)
    {
        $demo_data = array();
        $languages = Language::getLanguages();

        for ($i = 1; $i<=$num_of_data; $i++) {
            $demo_data[] =  'INSERT INTO 
                            `'._DB_PREFIX_.'tbcmspaymenticon`
                        SET 
                            `id_tbcmspaymenticon` = '.$i.',
                            `position` = '.$i.',
                            `id_shop_group` = '.$this->id_shop_group.',
                            `id_shop` = '.$this->id_shop.',
                            `image` = \'demo_img_'.$i.'.png\',
                            `link` = \'#\',
                            `status` = \'1\';';
            foreach ($languages as $lang) {
                $demo_data[] = 'INSERT INTO
                                `'._DB_PREFIX_.'tbcmspaymenticon_lang`
                            SET
                                `id_tbcmspaymenticon_lang` = NULL,
                                `id_tbcmspaymenticon` = '.$i.',
                                `id_shop_group` = '.$this->id_shop_group.',
                                `id_shop` = '.$this->id_shop.',
                                `id_lang` = '.$lang['id_lang'].',
                                `title` = \'test '.$i.'\';';
            }
        }
        foreach ($demo_data as $data) {
            Db::getInstance()->execute($data);
        }
    }

    public function maxId()
    {
        $select_data = 'SELECT MAX(id_tbcmspaymenticon) as max_id FROM `'._DB_PREFIX_.'tbcmspaymenticon`';

        $ans = Db::getInstance()->executeS($select_data);
        return $ans[0]['max_id'];
    }

    public function selectAllLangIdById($id_tbcmspaymenticon)
    {
        $select_data = 'SELECT 
                            id_lang 
                        FROM 
                            `'._DB_PREFIX_.'tbcmspaymenticon_lang` 
                        WHERE 
                            `id_shop_group` = '.$this->id_shop_group.'
                            AND `id_shop` = '.$this->id_shop.'
                            AND id_tbcmspaymenticon = '.$id_tbcmspaymenticon;


        $ans = Db::getInstance()->executeS($select_data);
        $return = array();
        foreach ($ans as $a) {
            $return[] = $a['id_lang'];
        }
        return $return;
    }

    public function insertData($data)
    {
        $result = array();
        $insert_data = array();
        if ($data['id']) {
            $id = $data['id'];
            $insert_data[] = 'UPDATE 
                                `'._DB_PREFIX_.'tbcmspaymenticon` 
                            SET
                                `image` = \''.$data['image'].'\',
                                `link` = \''.$data['link'].'\',
                                `status` = '.$data['status'].'

                            WHERE
                                `id_shop_group` = '.$this->id_shop_group.'
                                AND `id_shop` = '.$this->id_shop.'
                                AND `id_tbcmspaymenticon` = '.$id.';';
            $result = $this->selectAllLangIdById($id);

            $languages = Language::getLanguages();
            $i = 0;
            foreach ($languages as $lang) {
                if (in_array($lang['id_lang'], $result)) {
                    $insert_data[] = 'UPDATE
                                        `'._DB_PREFIX_.'tbcmspaymenticon_lang`
                                    SET
                                        `title` = \''.$data['lang_info'][$i]['title'].'\'
                                    WHERE
                                            `id_shop_group` = '.$this->id_shop_group.'
                                        AND 
                                            `id_shop` = '.$this->id_shop.'
                                        AND
                                            `id_tbcmspaymenticon` = '.$id.'
                                        AND
                                            `id_lang` = '.$lang['id_lang'].';';
                } else {
                    $insert_data[] = 'INSERT INTO
                                        `'._DB_PREFIX_.'tbcmspaymenticon_lang`
                                    SET
                                        `id_tbcmspaymenticon_lang` = NULL,
                                        `id_tbcmspaymenticon` = '.$id.',
                                        `id_shop_group` = '.$this->id_shop_group.',
                                        `id_shop` = '.$this->id_shop.',
                                        `id_lang` = '.$lang['id_lang'].',
                                        `title` = \''.$data['lang_info'][$i]['title'].'\';';
                }
                $i++;
            }
        } else {
            $max_id = $this->maxId();
            $new_id = $max_id+1;
            $insert_data = array();

            $insert_data[] = 'INSERT INTO 
                                `'._DB_PREFIX_.'tbcmspaymenticon` 
                            SET
                                `id_tbcmspaymenticon` = '.$new_id.',
                                `position` = '.$new_id.',
                                `id_shop_group` = '.$this->id_shop_group.',
                                `id_shop` = '.$this->id_shop.',
                                `image` = \''.$data['image'].'\',
                                `link` = \''.$data['link'].'\',
                                `status` = '.$data['status'].';';

            foreach ($data['lang_info'] as $lang) {
                $insert_data[] = 'INSERT INTO
                                    `'._DB_PREFIX_.'tbcmspaymenticon_lang`
                                SET
                                    `id_tbcmspaymenticon_lang` = NULL,
                                    `id_tbcmspaymenticon` = '.$new_id.',
                                    `id_shop_group` = '.$this->id_shop_group.',
                                    `id_shop` = '.$this->id_shop.',
                                    `id_lang` = '.$lang['id_lang'].',
                                    `title` = \''.$lang['title'].'\';';
            }
        }

        foreach ($insert_data as $data) {
            Db::getInstance()->execute($data);
        }
    }

    public function showAdminData()
    {
        $result = array();
        $return_data = array();
        $default_lang_id = $this->context->language->id;

        $select_data = 'SELECT * FROM `'._DB_PREFIX_.'tbcmspaymenticon`'
            .' WHERE `id_shop_group` = '.$this->id_shop_group.' AND `id_shop` = '.$this->id_shop
            .' ORDER BY `position`;';


        $result['tbcmspaymenticon'] = Db::getInstance()->executeS($select_data);

        $select_data = 'SELECT * FROM `'._DB_PREFIX_.'tbcmspaymenticon_lang`'
            .' WHERE `id_shop_group` = '.$this->id_shop_group.' AND `id_shop` = '.$this->id_shop.';';

        $result['tbcmspaymenticon_lang'] = Db::getInstance()->executeS($select_data);

        foreach ($result['tbcmspaymenticon'] as $key => $data) {
            $return_data[$key]['id'] = $data['id_tbcmspaymenticon'];
            $id = $data['id_tbcmspaymenticon'];

            foreach ($result['tbcmspaymenticon_lang'] as $lang) {
                if ($default_lang_id == $lang['id_lang'] && $id == $lang['id_tbcmspaymenticon']) {
                    // $lang_id = $lang['id_lang'];
                    $return_data[$key]['id_lang'] = $lang['id_lang'];
                    $return_data[$key]['title'] = $lang['title'];
                }
            }

            $return_data[$key]['image'] = $data['image'];
            $return_data[$key]['link'] = $data['link'];
            $return_data[$key]['status'] = $data['status'];
        }
        return $return_data;
    }

    public function showData($id = null)
    {
        $select_data = array();
        $result = array();
        $return_data = array();

        $select_data = '';
        $select_data .= 'SELECT * FROM `'._DB_PREFIX_.'tbcmspaymenticon` 
                WHERE 
                `id_shop_group` = '.$this->id_shop_group
                .' AND `id_shop` = '.$this->id_shop;
        if ($id) {
            $select_data .= ' AND `id_tbcmspaymenticon` = '.$id;
        } else {
            $select_data .= ' ORDER BY `position`';
        }

        $result['tbcmspaymenticon'] = Db::getInstance()->executeS($select_data);

        $select_data = '';
        $select_data .= 'SELECT * FROM `'._DB_PREFIX_.'tbcmspaymenticon_lang`'
            .' WHERE `id_shop_group` = '.$this->id_shop_group.' AND `id_shop` = '.$this->id_shop;
        if ($id) {
            $select_data .= ' AND id_tbcmspaymenticon = '.$id;
        }

        $result['tbcmspaymenticon_lang'] = Db::getInstance()->executeS($select_data);

        foreach ($result['tbcmspaymenticon'] as $key => $data) {
            $return_data[$key]['id'] = $data['id_tbcmspaymenticon'];
            $id = $data['id_tbcmspaymenticon'];
            foreach ($result['tbcmspaymenticon_lang'] as $lang) {
                if ($id == $lang['id_tbcmspaymenticon']) {
                    $lang_id = $lang['id_lang'];
                    $return_data[$key]['lang_info'][$lang_id]['id_lang'] = $lang['id_lang'];
                    $return_data[$key]['lang_info'][$lang_id]['title'] = $lang['title'];
                }
            }
            $return_data[$key]['image'] = $data['image'];
            $return_data[$key]['link'] = $data['link'];
            $return_data[$key]['status'] = $data['status'];
        }

        return $return_data;
    }

    public function showFrontData()
    {
        $cookie = Context::getContext()->cookie;
        $id_lang = $cookie->id_lang;

        $select_data = '
            SELECT 
                mainTable.id_tbcmspaymenticon AS code,
                mainTable.image,
                mainTable.link,
                subTable.title
            FROM 
                `'._DB_PREFIX_.'tbcmspaymenticon` mainTable
            LEFT JOIN
                '._DB_PREFIX_.'tbcmspaymenticon_lang subTable
            ON
                mainTable.id_tbcmspaymenticon = subTable.id_tbcmspaymenticon
            WHERE 
                mainTable.id_shop_group = '.$this->id_shop_group.' 
            AND 
                mainTable.id_shop = '.$this->id_shop.'
            AND 
                mainTable.status = 1
            AND
                subTable.id_lang = '.$id_lang.'
            ORDER BY `position`';
      
        $result = Db::getInstance()->executeS($select_data);
        $result_data = array();
        if (!empty($result)) {
            $result_data = $result;
        }
        return $result_data;
    }

    public function uninstall()
    {
        $this->uninstallTab();
        $this->deleteVariable();
        $this->deleteTable();
        return parent::uninstall();
    }

    public function deleteVariable()
    {
        Configuration::deleteByName('TBCMSPAYMENTICON_TITLE');
        Configuration::deleteByName('TBCMSPAYMENTICON_SUB_DESCRIPTION');
        Configuration::deleteByName('TBCMSPAYMENTICON_DESCRIPTION');
        Configuration::deleteByName('TBCMSPAYMENTICON_IMG');
    }

    public function deleteRecord($id)
    {
        $this->removeImage($id);

        $delete_data = array();

        $delete_data[] = 'DELETE FROM `'._DB_PREFIX_.'tbcmspaymenticon`
            WHERE 
                    `id_shop_group` = '.$this->id_shop_group.'
                AND 
                    `id_shop` = '.$this->id_shop.' 
                AND 
                    `id_tbcmspaymenticon` = '.$id;


        $delete_data[] = 'DELETE FROM `'._DB_PREFIX_.'tbcmspaymenticon_lang` 
            WHERE 
                    `id_shop_group` = '.$this->id_shop_group.'
                AND 
                    `id_shop` = '.$this->id_shop.' 
                AND 
                    id_tbcmspaymenticon = '.$id;


        foreach ($delete_data as $data) {
            Db::getInstance()->execute($data);
        }
    }

    public function removeImage($id)
    {
        $remove_images = array();
        $result = $this->showData($id);

        $remove_images[] = $result[0]['image'];

        foreach ($remove_images as $image) {
            // Match Pattern Which image you Don't want to delete.
            $res = preg_match('/^demo_img_.*$/', $image);
            if (file_exists(dirname(__FILE__).'./views/img/'.$image)
                && $res != '1') {
                @unlink(dirname(__FILE__).'./views/img/'.$image);
                @unlink(dirname(__FILE__).'./views/img/tiny/'.$image);
            }
        }
    }

    public function deleteTable()
    {
        $delete_table = array();
        $delete_table[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.'tbcmspaymenticon`';
        $delete_table[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.'tbcmspaymenticon_lang`';

        foreach ($delete_table as $table) {
            Db::getInstance()->execute($table);
        }
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
        
        $message = $this->postProcess();
        $output = $message
                .$this->renderForm()
                .$this->showAdminResult();
        return $output;
    }

    public function postProcess()
    {
        $message = '';
        $result = array();

        if (Tools::getValue('action')) {
            if (Tools::getValue('action') == 'remove') {
                $id = Tools::getValue('id');
                $this->deleteRecord($id);
                $message .= $this->displayConfirmation($this->l("Record is Deleted."));
            }
        }

        if (Tools::isSubmit('submittbcmsPaymentIconForm')) {
            $old_file = '';
            $no_image_selected = false;
            $result['id'] = '';
            if (Tools::getValue('id')) {
                $result['id'] = Tools::getValue('id');
                $id = Tools::getValue('id');
                $res = $this->showData($id);
                $old_file = $res[0]['image'];
            }

            $tbcms_obj = new TbcmsPaymentIconStatus();
            $show_fields = $tbcms_obj->fieldStatusInformation();
            $result['image'] = '';
            if ($show_fields['image']) {
                if (!empty($_FILES['image']['name'])) {
                    $new_file = $_FILES['image'];
                    $obj_image = new TbcmsPaymentIconImageUpload();
                    $ans = $obj_image->imageUploading($new_file, $old_file);
                    if ($ans['success']) {
                        $result['image'] = $ans['name'];
                    } else {
                        $message .= $ans['error'];
                        $result['image'] = $old_file;
                        if (!Tools::getValue('id')) {
                            $no_image_selected = true;
                        }
                    }
                } else {
                    $result['image'] = $old_file;

                    if (!Tools::getValue('id')) {
                        $message .= $this->displayError($this->l("Please Select Image."));
                        $no_image_selected = true;
                    }
                }
            }
            
            if (!$no_image_selected) {
                $result['link'] = Tools::getValue('link');
                $result['status'] = Tools::getValue('status');

                $languages = Language::getLanguages();
                $i = 0;
                foreach ($languages as $lang) {
                    $result['lang_info'][$i]['id_lang'] = $lang['id_lang'];

                    $tmp = Tools::getValue('title_'.$lang['id_lang']);
                    $result['lang_info'][$i]['title'] = addslashes($tmp);
                    $i++;
                }
                
                $this->insertData($result);
                
                $this->clearCustomSmartyCache('tbcmspaymenticon_display_home.tpl');

                if (Tools::getValue('id')) {
                    $message .= $this->displayConfirmation($this->l("Record is Updated."));
                } else {
                    $message .= $this->displayConfirmation($this->l("Record is Inserted."));
                }
            }
        }

        if (Tools::isSubmit('submittbcmsPaymentIconMainTitleForm')) {
            $languages = Language::getLanguages();
            $obj_image = new TbcmsPaymentIconImageUpload();
            foreach ($languages as $lang) {
                if (!empty($_FILES['TBCMSPAYMENTICON_IMG_'.$lang['id_lang']]['name'])) {
                    $old_file = Configuration::get('TBCMSPAYMENTICON_IMG', $lang['id_lang']);
                    $new_file = $_FILES['TBCMSPAYMENTICON_IMG_'.$lang['id_lang']];
                    $ans = $obj_image->imageUploading($new_file, $old_file);
                    if ($ans['success']) {
                        $result['TBCMSPAYMENTICON_IMG'][$lang['id_lang']] = $ans['name'];
                    } else {
                        $message .= $ans['error'];
                        $result['TBCMSPAYMENTICON_IMG'][$lang['id_lang']] = $old_file;
                    }
                } else {
                    $old_file = Configuration::get('TBCMSPAYMENTICON_IMG', $lang['id_lang']);
                    $result['TBCMSPAYMENTICON_IMG'][$lang['id_lang']] = $old_file;
                }

                $tmp = Tools::getValue('TBCMSPAYMENTICON_TITLE_'.$lang['id_lang']);
                $result['TBCMSPAYMENTICON_TITLE'][$lang['id_lang']] = $tmp;

                $tmp = Tools::getValue('TBCMSPAYMENTICON_SUB_DESCRIPTION_'.$lang['id_lang']);
                $result['TBCMSPAYMENTICON_SUB_DESCRIPTION'][$lang['id_lang']] = $tmp;

                $tmp = Tools::getValue('TBCMSPAYMENTICON_DESCRIPTION_'.$lang['id_lang']);
                $result['TBCMSPAYMENTICON_DESCRIPTION'][$lang['id_lang']] = $tmp;
            }
            Configuration::updateValue('TBCMSPAYMENTICON_IMG', $result['TBCMSPAYMENTICON_IMG']);
            Configuration::updateValue('TBCMSPAYMENTICON_TITLE', $result['TBCMSPAYMENTICON_TITLE']);
            $tmp = $result['TBCMSPAYMENTICON_SUB_DESCRIPTION'];
            Configuration::updateValue('TBCMSPAYMENTICON_SUB_DESCRIPTION', $tmp);

            $tmp = $result['TBCMSPAYMENTICON_DESCRIPTION'];
            Configuration::updateValue('TBCMSPAYMENTICON_DESCRIPTION', $tmp);
            $message .= $this->displayConfirmation($this->l("Main Title is Updated."));
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

        $tbcms_obj = new TbcmsPaymentIconStatus();
        $show_fields = $tbcms_obj->fieldStatusInformation();
        if ($show_fields['main_status']) {
            $form[] = $this->tbcmsPaymentIconMainTitleForm();
        }

        if ($show_fields['record_form']) {
            $form[] = $this->tbcmsPaymentIconForm();
        }

        

        return $helper->generateForm($form);
    }

    protected function tbcmsPaymentIconMainTitleForm()
    {
        $tbcms_obj = new TbcmsPaymentIconStatus();
        $show_fields = $tbcms_obj->fieldStatusInformation();
        $input = array();

        if ($show_fields['main_title']) {
            $input[] = array(
                    'col' => 7,
                    'type' => 'text',
                    'name' => 'TBCMSPAYMENTICON_TITLE',
                    'label' => $this->l('Main Title'),
                    'lang' => true,
                );
        }

        if ($show_fields['main_short_description']) {
            $input[] = array(
                    'col' => 7,
                    'type' => 'text',
                    'name' => 'TBCMSPAYMENTICON_SUB_DESCRIPTION',
                    'label' => $this->l('Short Description'),
                    'lang' => true,
                );
        }

        if ($show_fields['main_description']) {
            $input[] = array(
                    'col' => 7,
                    'type' => 'text',
                    'name' => 'TBCMSPAYMENTICON_DESCRIPTION',
                    'label' => $this->l('Description'),
                    'lang' => true,
                );
        }

        if ($show_fields['main_image']) {
            $input[] = array(
                        'type' => 'image_file',
                        'name' => 'TBCMSPAYMENTICON_IMG',
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
                    'name' => 'submittbcmsPaymentIconMainTitleForm',
                ),
            ),
        );
    }


    protected function tbcmsPaymentIconForm()
    {
        $tbcms_obj = new TbcmsPaymentIconStatus();
        $show_fields = $tbcms_obj->fieldStatusInformation();
        $input = array();

        if (Tools::getValue('action')) {
            if (Tools::getValue('action') == 'edit') {
                $input[] = array(
                        'type' => 'hidden',
                        'name' => 'id',
                    );
            }
        }

        if ($show_fields['image']) {
            $input[] =  array(
                        'col' => 9,
                        'type' => 'tbcmspaymenticon_img',
                        'name' => 'image',
                        'label' => $this->l('Image'),
                    );
        }

        if ($show_fields['title']) {
            $input[] = array(
                    'col' => 7,
                    'type' => 'text',
                    'name' => 'title',
                    'label' => $this->l('Title'),
                    'lang' => true,
                );
        }

        if ($show_fields['link']) {
            $input[] = array(
                    'col' => 7,
                    'type' => 'text',
                    'name' => 'link',
                    'label' => $this->l('Link'),
                    'desc' => $this->l('You Must Write Full Link. Ex:- https://www.demo.com/'),
                );
        }

        if ($show_fields['status']) {
            $input[] =  array(
                        'type' => 'switch',
                        'label' => $this->l('Status'),
                        'name' => 'status',
                        'desc' => $this->l('Hide or Show Icons.'),
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
                'title' => $this->l('Payment Icon'),
                'icon' => 'icon-support',
                ),
                'input' => $input,
                'submit' => array(
                    'title' => $this->l('Save'),
                    'name' => 'submittbcmsPaymentIconForm',
                ),
            ),
        );
    }

    public function hookDisplayBackOfficeHeader()
    {
        $this->context->controller->addJS($this->_path.'views/js/back.js');
        $this->context->controller->addCSS($this->_path.'views/css/back.css');
    }//hookDisplayBackOfficeHeader()
 
    protected function getConfigFormValues()
    {
        $fields = array();
        $languages = Language::getLanguages();
        $fields['id'] = '';
        foreach ($languages as $lang) {
            $fields['title'][$lang['id_lang']] = '';
        }
        $fields['image'] = '';
        $fields['link'] = '';
        $fields['status'] = '';

        if (Tools::getValue('action')) {
            if (Tools::getValue('action') == 'edit') {
                $id = Tools::getValue('id');
                $array_list = $this->showData($id);
                $array_list = $array_list[0];

                $fields['id'] = $id;
                foreach ($languages as $lang) {
                    if (!empty($array_list['lang_info'][$lang['id_lang']])) {
                        $fields['title'][$lang['id_lang']] = $array_list['lang_info'][$lang['id_lang']]['title'];
                    }
                }
                $fields['image'] = $array_list['image'];
                $fields['link'] = $array_list['link'];
                $fields['status'] = $array_list['status'];
            }
        }

        foreach ($languages as $lang) {
            $tmp = Configuration::get('TBCMSPAYMENTICON_TITLE', $lang['id_lang']);
            $fields['TBCMSPAYMENTICON_TITLE'][$lang['id_lang']] = $tmp;

            $tmp = Configuration::get('TBCMSPAYMENTICON_SUB_DESCRIPTION', $lang['id_lang']);
            $fields['TBCMSPAYMENTICON_SUB_DESCRIPTION'][$lang['id_lang']] = $tmp;

            $tmp = Configuration::get('TBCMSPAYMENTICON_DESCRIPTION', $lang['id_lang']);
            $fields['TBCMSPAYMENTICON_DESCRIPTION'][$lang['id_lang']] = $tmp;

            $tmp = Configuration::get('TBCMSPAYMENTICON_IMG', $lang['id_lang']);
            $fields['TBCMSPAYMENTICON_IMG'][$lang['id_lang']] = $tmp;
        }

        $path = _MODULE_DIR_.$this->name."/views/img/";
        $this->context->smarty->assign("path", $path);
        
        return $fields;
    }

    public function showAdminResult()
    {
        $tbcms_obj = new TbcmsPaymentIconStatus();
        $show_fields = $tbcms_obj->fieldStatusInformation();
        $default_lang_id = $this->context->language->id;

        $array_list = $this->showAdminData();

        if (empty($array_list)) {
            return '';
        }

        $this->context->smarty->assign('array_list', $array_list);
        $this->context->smarty->assign('show_fields', $show_fields);
        $this->context->smarty->assign('default_lang_id', $default_lang_id);

        return $this->display(__FILE__, "views/templates/admin/tbcmspaymenticon_manage.tpl");
    }

    public function getArrMainTitle($main_heading, $main_heading_data)
    {
        if (!$main_heading['main_title'] || empty($main_heading_data['title'])) {
            $main_heading['main_title'] = false;
        }
        if (!$main_heading['main_short_description'] || empty($main_heading_data['short_desc'])) {
            $main_heading['main_short_description'] = false;
        }
        if (!$main_heading['main_description'] || empty($main_heading_data['desc'])) {
            $main_heading['main_description'] = false;
        }
        if (!$main_heading['main_image'] || empty($main_heading_data['image'])) {
            $main_heading['main_image'] = false;
        }
        if (!$main_heading['main_title'] &&
            !$main_heading['main_short_description'] &&
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

        $tbcms_obj = new TbcmsPaymentIconStatus();
        $main_heading = $tbcms_obj->fieldStatusInformation();

        if ($main_heading['main_status']) {
            $main_heading_data = array();
            $main_heading_data['title'] = Configuration::get('TBCMSPAYMENTICON_TITLE', $id_lang);
            $main_heading_data['short_desc'] = Configuration::get('TBCMSPAYMENTICON_SUB_DESCRIPTION', $id_lang);
            $main_heading_data['desc'] = Configuration::get('TBCMSPAYMENTICON_DESCRIPTION', $id_lang);
            $main_heading_data['image'] = Configuration::get('TBCMSPAYMENTICON_IMG', $id_lang);
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
    }


    public function hookDisplayTopColumn()
    {
        return $this->hookdisplayPaymentIcon();
    }

    public function hookDisplayFooterBefore()
    {
        return $this->hookdisplayPaymentIcon();
    }

    public function hookdisplayPaymentIcon()
    {
        if (!Cache::isStored('tbcmspaymenticon_display_home.tpl')) {
            $result = $this->showFrontSideResult();
            if ($result) {
                $output = $this->display(__FILE__, 'views/templates/front/display_home.tpl');
            } else {
                $output = '';
            }

            Cache::store('tbcmspaymenticon_display_home.tpl', $output);
        }

        return Cache::retrieve('tbcmspaymenticon_display_home.tpl');
    }
}
