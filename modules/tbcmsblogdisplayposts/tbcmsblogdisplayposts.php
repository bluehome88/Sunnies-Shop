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

include_once _PS_MODULE_DIR_.'tbcmsblog/classes/tbcmspostsclass.php';
include_once _PS_MODULE_DIR_.'tbcmsblog/classes/tbcmscategoryclass.php';
include_once _PS_MODULE_DIR_.'tbcmsblog/tbcmsblog.php';
include_once _PS_MODULE_DIR_.'tbcmsblogdisplayposts/classes/tbcmsblogdisplayposts_image_upload.class.php';
include_once _PS_MODULE_DIR_.'tbcmsblogdisplayposts/classes/tbcmsblogdisplayposts_status.class.php';


class TbcmsBlogDisplayPosts extends Module
{
    public $js_files = array(
        array(
            'key' => 'tbcmsblogdisplayposts',
            'src' => 'tbcmsblogdisplayposts.js',
            'priority' => 50,
            'position' => 'bottom', // bottom or head
            'load_theme' => false,
        ),
    );
    
    public function __construct()
    {
        $this->name = 'tbcmsblogdisplayposts';
        $this->tab = 'front_office_features';
        $this->version = '2.1.9';
        $this->author = 'TemplateBeta';
        $this->bootstrap = true;
        $this->dependencies = array('tbcmsblog');
        parent::__construct();
        $this->displayName = $this->l('TemplateBeta - Blog Post Display');
        $this->description = $this->l('This is child module only display some of posts on your home page.');

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
        $this->module_key = '';

        $this->confirmUninstall = $this->l('Warning: all the data saved in your database will be deleted.'.
            ' Are you sure you want uninstall this module?');
    }
    // For installation service
    
    public function install()
    {
        parent::install();
        $this->installTab();
        if (!$this->registerHook('displayHeader')
            // || !$this->registerHook('displayLeftColumn')) {
            // || !$this->registerHook('displayRightColumn')) {
            || !$this->registerHook('displayHome')) {
            return false;
        }

        $this->createVariable();
        
        return true;
    }

    public function createVariable()
    {
        $languages = Language::getLanguages(false);
        foreach ($languages as $lang) {
            Configuration::updateValue('tbcmsbdp_title_'.$lang['id_lang'], "Our Latest News");
            Configuration::updateValue('tbcmsbdp_subtext_'.$lang['id_lang'], "Mirum est notare quam littera gothica,");

            Configuration::updateValue('tbcmsbdp_description_'.$lang['id_lang'], "A Friendly Organic Store");
            Configuration::updateValue('tbcmsbdp_image_'.$lang['id_lang'], "demo_main_img.jpg");
        }
        Configuration::updateValue('tbcmsbdp_postcount', 6);
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
            $tab->name[$lang['id_lang']] = "Blog on Home Page";
        }
        $tab->id_parent = $parentTab_2->id;
        $tab->module = $this->name;
        $response &= $tab->add();

        return $response;
    }
    
    public function uninstall()
    {
        parent::uninstall();
        $this->uninstallTab();
        return true;
    }

    public function uninstallTab()
    {
        $id_tab = Tab::getIdFromClassName('Admin'.$this->name);
        $tab = new Tab($id_tab);
        $tab->delete();
        return true;
    }

    public function settingForm()
    {
        $tbcms_obj = new TbcmsBlogDisplayPostsStatus();
        $show_fields = $tbcms_obj->fieldStatusInformation();

        $default_lang = (int) Configuration::get('PS_LANG_DEFAULT');
        $this->fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->l('Setting'),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'button',
            ),
        );
        if ($show_fields['main_title']) {
            $this->fields_form[0]['form']['input'][] = array(
                'type' => 'text',
                'label' => $this->l('Title'),
                'name' => 'tbcmsbdp_title',
                'lang' => true,
            );
        }

        if ($show_fields['main_sub_title']) {
            $this->fields_form[0]['form']['input'][] = array(
                'type' => 'text',
                'label' => $this->l('Sub Title'),
                'name' => 'tbcmsbdp_subtext',
                'lang' => true,
            );
        }

        if ($show_fields['main_description']) {
            $this->fields_form[0]['form']['input'][] = array(
                'type' => 'text',
                'label' => $this->l('Description'),
                'name' => 'tbcmsbdp_description',
                'lang' => true,
            );
        }
        
        if ($show_fields['main_image']) {
            $this->fields_form[0]['form']['input'][] = array(
                'type' => 'image_file',
                'label' => $this->l('Image'),
                'name' => 'tbcmsbdp_image',
                'lang' => true,
            );
        }
        

        // $this->fields_form[0]['form']['input'][] = array(
        //     'type' => 'text',
        //     'label' => $this->l('How Many Post You Want To Display'),
        //     'name' => 'tbcmsbdp_postcount',
        // );
        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
        foreach (Language::getLanguages(false) as $lang) {
            $helper->languages[] = array(
                'id_lang' => $lang['id_lang'],
                'iso_code' => $lang['iso_code'],
                'name' => $lang['name'],
                'is_default' => ($default_lang == $lang['id_lang'] ? 1 : 0),
            );
        }
        $helper->toolbar_btn = array(
            'save' => array(
                'desc' => $this->l('Save'),
                'href' => AdminController::$currentIndex . '&configure=' . $this->name . '&save'
                    . $this->name . 'token=' . Tools::getAdminTokenLite('AdminModules'),
            ),
        );
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;
        $helper->title = $this->displayName;
        $helper->show_toolbar = true;
        $helper->toolbar_scroll = true;
        $helper->submit_action = 'save' . $this->name;
        $languages = Language::getLanguages(false);
        foreach ($languages as $lang) {
            $tmp = Configuration::get('tbcmsbdp_title_'.$lang['id_lang']);
            $helper->fields_value['tbcmsbdp_title'][$lang['id_lang']] = $tmp;
            $tmp = Configuration::get('tbcmsbdp_subtext_'.$lang['id_lang']);
            $helper->fields_value['tbcmsbdp_subtext'][$lang['id_lang']] = $tmp;
            $tmp = Configuration::get('tbcmsbdp_description_'.$lang['id_lang']);
            $helper->fields_value['tbcmsbdp_description'][$lang['id_lang']] = $tmp;
            $tmp = Configuration::get('tbcmsbdp_image_'.$lang['id_lang']);
            $helper->fields_value['tbcmsbdp_image'][$lang['id_lang']] = $tmp;
        }
        $helper->fields_value['tbcmsbdp_postcount'] = Configuration::get('tbcmsbdp_postcount');

        $path = _MODULE_DIR_.$this->name."/views/img/";
        $this->context->smarty->assign("path", $path);

        return $helper;
    }
    // All Functional Logic here.
    
    public function getContent()
    {
        $html = '';
        $result = array();
        if (Tools::isSubmit('save' . $this->name)) {
            $languages = Language::getLanguages(false);
            $tbcms_obj = new TbcmsBlogDisplayPostsStatus();
            $show_fields = $tbcms_obj->fieldStatusInformation();
            foreach ($languages as $lang) {
                $obj_image = new TbcmsBlogDisplayPostsImageUpload();

                if ($show_fields['main_image']) {
                    $old_file = Configuration::get('tbcmsbdp_image_'.$lang['id_lang']);
                    if (!empty($_FILES['tbcmsbdp_image_'.$lang['id_lang']]['name'])) {
                        $new_file = $_FILES['tbcmsbdp_image_'.$lang['id_lang']];
                        $ans = $obj_image->imageUploading($new_file, $old_file);
                        // print_r($ans);
                        // exit;
                        if ($ans['success']) {
                            $result['tbcmsbdp_image'][$lang['id_lang']] = $ans['name'];
                            Configuration::updateValue(
                                'tbcmsbdp_image_'.$lang['id_lang'],
                                $ans['name']
                            );
                        } else {
                            $html .= $ans['error'];
                            Configuration::updateValue(
                                'tbcmsbdp_image_'.$lang['id_lang'],
                                $old_file
                            );
                        }
                    } else {
                        Configuration::updateValue(
                            'tbcmsbdp_image_'.$lang['id_lang'],
                            $old_file
                        );
                    }
                }

                Configuration::updateValue(
                    'tbcmsbdp_title_'.$lang['id_lang'],
                    Tools::getValue('tbcmsbdp_title_'.$lang['id_lang'])
                );
                Configuration::updateValue(
                    'tbcmsbdp_subtext_'.$lang['id_lang'],
                    Tools::getValue('tbcmsbdp_subtext_'.$lang['id_lang'])
                );

                Configuration::updateValue(
                    'tbcmsbdp_description_'.$lang['id_lang'],
                    Tools::getValue('tbcmsbdp_description_'.$lang['id_lang'])
                );
            }
            Configuration::updateValue('tbcmsbdp_postcount', Tools::getValue('tbcmsbdp_postcount'));
            $html .= $this->displayConfirmation($this->l('Record is Successfully Updated.'));
        }

        $helper = $this->settingForm();
        $html .= $helper->generateForm($this->fields_form);
        return $html;
    }
    
    public function registerCss()
    {
        if (isset($this->css_files) && !empty($this->css_files)) {
            $theme_name = $this->context->shop->theme_name;
            foreach ($this->css_files as $css_file) {
                if (isset($css_file['key'])
                    && !empty($css_file['key'])
                    && isset($css_file['src'])
                    && !empty($css_file['src'])) {
                    $media = (isset($css_file['media']) && !empty($css_file['media'])) ? $css_file['media'] : 'all';
                    $tmp = $css_file['priority'];
                    $priority = (isset($tmp) && !empty($tmp)) ? $tmp : 50;
                    if (isset($css_file['load_theme']) && ($css_file['load_theme'] == true)) {
                        $this->context->controller->registerStylesheet($css_file['key'], 'themes/'.$theme_name
                            .'/assets/css/'.$css_file['src'], array('media' => $media, 'priority' => $priority));
                    } else {
                        $this->context->controller->registerStylesheet($css_file['key'], 'modules/'.$this->name
                            .'/css/'.$css_file['src'], array('media' => $media, 'priority' => $priority));
                    }
                }
            }
        }
        return true;
    }

    public function registerJs()
    {
        if (isset($this->js_files) && !empty($this->js_files)) {
            foreach ($this->js_files as $js_file) {
                if (isset($js_file['key'])
                    && !empty($js_file['key'])
                    && isset($js_file['src'])
                    && !empty($js_file['src'])) {
                    $tmp = $js_file['position'];
                    $position = (isset($tmp) && !empty($tmp)) ? $tmp : 'bottom';
                    $tmp = $js_file['priority'];
                    $priority = (isset($tmp) && !empty($tmp)) ? $tmp : 50;
                    if (isset($js_file['load_theme']) && ($js_file['load_theme'] == true)) {
                        $this->context->controller->registerJavascript($js_file['key'], _THEME_DIR_.'assets/js/'
                            .$js_file['src'], array('position' => $position, 'priority' => $priority));
                    } else {
                        $this->context->controller->registerJavascript($js_file['key'], 'modules/'.$this->name
                            .'/js/'.$js_file['src'], array('position' => $position, 'priority' => $priority));
                    }
                }
            }
        }
        return true;
    }
    
    // Display Header Hook Execute Functions
    public function hookdisplayHeader($params)
    {
        $this->registerCss();
        $this->registerJs();
        $this->context->controller->addCSS($this->_path.'views/css/front.css');
        $this->context->controller->addJS($this->_path.'views/js/front.js');
    }
    // Display Header Hook Execute Functions

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
        $tbcmsbdp_postcount = Configuration::get('tbcmsbdp_postcount');
        $data = array();
        if (Module::isInstalled('tbcmsblog') && Module::isEnabled('tbcmsblog')) {
            $data = TbcmsPostsClass::getCategoryPosts(0, 1, $tbcmsbdp_postcount, 'post', 'DESC');
        }
        if (empty($data)) {
            Configuration::updateValue('TBCMSBLOGSHOW', '0');
        }

        $cookie = Context::getContext()->cookie;
        $id_lang = $cookie->id_lang;

        $tbcms_obj = new TbcmsBlogDisplayPostsStatus();
        $main_heading = $tbcms_obj->fieldStatusInformation();

        if ($main_heading['main_status']) {
            $main_heading_data = array();
            $main_heading_data['title'] = Configuration::get('tbcmsbdp_title_'.$id_lang);
            $main_heading_data['short_desc'] = Configuration::get('tbcmsbdp_subtext_'.$id_lang);
            $main_heading_data['desc'] = Configuration::get('tbcmsbdp_description_'.$id_lang);
            $main_heading_data['image'] = Configuration::get('image_file_'.$id_lang);
            $main_heading = $this->getArrMainTitle($main_heading, $main_heading_data);
            $main_heading['data'] = $main_heading_data;
        }


        $disArrResult = array();
        $disArrResult['data'] = $data;
        $disArrResult['status'] = empty($disArrResult['data'])?false:true;
        $disArrResult['path'] = _MODULE_DIR_.$this->name."/views/img/";
        $disArrResult['id_lang'] = $id_lang;

        $this->context->smarty->assign('main_heading', $main_heading);
        $this->context->smarty->assign('dis_arr_result', $disArrResult);

        return $disArrResult['status']?true:false;
    }

    public function hookdisplayWrapperBottom($params)
    {
        return $this->hookdisplayHome($params);
    }
    

    public function hookdisplayHome($params)
    {
        $result = $this->showFrontSideResult();
        if (!$result) {
            return false;
        }

        $output = $this->display(__FILE__, '/views/templates/front/tbcmsblogdisplayposts_home.tpl');
        return $output;
    }

    // display blog in left panel
    public function hookdisplayLeftColumn($params)
    {
        $result = $this->showFrontSideResult();
        if (!$result) {
            return false;
        }

        $output = $this->display(__FILE__, '/views/templates/front/tbcmsblogdisplayposts_left.tpl');
        return $output;
    }

    public function hookdisplayRightColumn($params)
    {
        return $this->hookdisplayLeftColumn($params);
    }

    // Display Blog category in Left Column
    // public function hookdisplayLeftColumn($params)
    // {
    //     $final_categories = array();
    //     $categories = TbcmsCategoryClass::getCategories();
    //     $i = 0;
    //     foreach ($categories as $category) {
    //         if (TbcmsPostsClass::getCategoryPostsCount($category['id_tbcmscategory']) > 0) {
    //             $params = array();
    //             $final_categories[$i] = $category;
    //             $params['page_type'] = 'category';
    //             $params['id'] = $category['id_tbcmscategory'];
    //             $params['rewrite'] = $category['link_rewrite'];
    //             $path = TbcmsBlog::tbcmsBlogCategoryLink($params);
    //             $path_2 = TbcmsCategoryClass::getCategoryPath($category['id_tbcmscategory']);
    //             $final_categories[$i]['path'] = $path;
    //             $final_categories[$i]['path_2'] = $path_2;
    //             $i++;
    //         }
    //     }

    //     $this->context->smarty->assign('categories', $final_categories);
    //     return $this->fetch('module:'.$this->name.'/views/templates/front/tbcmsblogdisplaycategory.tpl');
    // }
}
