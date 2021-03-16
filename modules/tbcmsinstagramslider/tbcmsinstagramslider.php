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

class TbcmsInstagramSlider extends Module
{
    public function __construct()
    {
        $this->name = 'tbcmsinstagramslider';
        $this->tab = 'front_office_features';
        $this->version = '2.1.9';
        $this->author = 'TemplateBeta';
        $this->need_instance = 0;

        
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('TemplateBeta - Instagram latest photos on Home Page');
        $this->description = $this->l('Display latest published photos from an Instagram account. Use Instagram API.');

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
        $this->module_key = '';

        $this->confirmUninstall = $this->l('Warning: all the data saved in your database will be deleted.'.
            ' Are you sure you want uninstall this module?');
    }

    public function install()
    {
        $languages = Language::getLanguages();
        $result = array();
        foreach ($languages as $lang) {
            $result['CS_TITLE'][$lang['id_lang']] = 'Instagram';
        }
        Configuration::updateValue('CS_TITLE', $result['CS_TITLE']);
        Configuration::updateValue('CS_USER', 'Webvolty Template');
        Configuration::updateValue('CS_INS_ID', 'xxxxxxxxxx');
        Configuration::updateValue('CS_INS_SECRET_ID', 'xxxxxxxxxx');
        Configuration::updateValue('CS_INS_CT', '');
        Configuration::updateValue('CS_NB_IMAGE', 20);
        Configuration::updateValue('CS_SIZE', 640);
        Configuration::updateValue('CS_CACHE_DURATION', 'day');
        Configuration::updateValue('CS_IMAGE_FORMAT', "thumbnail");
        Configuration::updateValue('CS_SIZE_INSTA', 150);

        $this->installTab();

        return parent::install()
            // && $this->registerHook('displayHome')
            // && $this->registerHook('displayWrapperBottom')
            && $this->registerHook('displayFooterPart1')
            && $this->registerHook('displayHeader')
            && $this->registerHook('displayBackOfficeHeader');
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
            $tab->name[$lang['id_lang']] = "Instagram Photos";
        }
        $tab->id_parent = $parentTab_2->id;
        $tab->module = $this->name;
        $response &= $tab->add();

        return $response;
    }

    public function uninstall()
    {
        $this->uninstallTab();
        Configuration::deleteByName('CS_TITLE');
        Configuration::deleteByName('CS_USER');
        Configuration::deleteByName('CS_INS_ID');
        Configuration::deleteByName('CS_INS_SECRET_ID');
        Configuration::deleteByName('CS_INS_CT');
        Configuration::deleteByName('CS_NB_IMAGE');
        Configuration::deleteByName('CS_SIZE');
        Configuration::deleteByName('CS_CACHE_DURATION');
        Configuration::deleteByName('CS_IMAGE_FORMAT');
        Configuration::deleteByName('CS_SIZE_INSTA');
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
        $this->context->controller->addJS($this->_path.'views/js/front.js');
        $this->context->controller->addCSS($this->_path.'views/css/front.css');
        // $this->context->controller->addJqueryPlugin('bxslider');
    }

    public function hookdisplayBackOfficeHeader($params)
    {
        // if (Tools::getValue('configure') && Tools::getValue('configure') == 'tbcmsinstagramslider') {
            $this->context->controller->addJS($this->_path.'views/js/back.js');
        // }
    }

    public function getContent()
    {
        $this->uninstallTab();
        $this->installTab();

        $output = $this->postProcess().$this->getForm();
        return $output;
    }
    
    private function postProcess()
    {
        $output = false;

        if (Tools::getValue('status') && Tools::getValue('status') !== 'update') {
            $output.= $this->displayError(Tools::getValue('status'));
        } elseif (Tools::getValue('status') && Tools::getValue('status') === 'update') {
            $output = $this->displayConfirmation($this->l('Settings updated'));
        }

        if (Tools::isSubmit('subMOD')) {
            $languages = Language::getLanguages();
            $result = array();
            foreach ($languages as $lang) {
                $result['CS_TITLE'][$lang['id_lang']] = Tools::getValue('name_'.$lang['id_lang']);
            }
            Configuration::updateValue('CS_TITLE', $result['CS_TITLE']);

            $user = Tools::getValue('user');
            Configuration::updateValue('CS_USER', $user);
            $insta_id = trim(Tools::getValue('insta_id'));
            if ($insta_id && !empty($insta_id) && ValidateCore::isGenericName($insta_id)) {
                Configuration::updateValue('CS_INS_ID', $insta_id);
            } else {
                $output.= $this->displayError($this->l('Instagram Client Id field is required'));
            }

            $insta_secret_id = trim(Tools::getValue('insta_secret_id'));
            if ($insta_secret_id && !empty($insta_secret_id) && ValidateCore::isGenericName($insta_secret_id)) {
                Configuration::updateValue('CS_INS_SECRET_ID', $insta_secret_id);
            } else {
                $output.= $this->displayError($this->l('Instagram Client SECRET field is required'));
            }

            if (ValidateCore::isInt(Tools::getValue('nb_image'))
                && Tools::getValue('nb_image') >= 1
                && Tools::getValue('nb_image') <= 20) {
                Configuration::updateValue('CS_NB_IMAGE', (int)Tools::getValue('nb_image'));
            } else {
                $output.= $this->displayError($this->l('Number of images field is required and must be'
                    .' between 1 and 20'));
            }

            $image_format = trim(Tools::getValue('image_format'));
            if ($image_format && !empty($image_format) && ValidateCore::isGenericName($image_format)) {
                Configuration::updateValue('CS_IMAGE_FORMAT', $image_format);
                switch (Configuration::get('CS_IMAGE_FORMAT')) {
                    case 'thumbnail':
                        Configuration::updateValue('CS_SIZE_INSTA', 150);
                        break;
                    case 'low_resolution':
                        Configuration::updateValue('CS_SIZE_INSTA', 320);
                        break;
                    case 'standard_resolution':
                        Configuration::updateValue('CS_SIZE_INSTA', 640);
                        break;
                }
            } else {
                $output.= $this->displayError($this->l('Image format field is required'));
            }

            if (ValidateCore::isInt(Tools::getValue('size'))) {
                Configuration::updateValue('CS_SIZE', (int)Tools::getValue('size'));
            } else {
                $output.= $this->displayError($this->l('Size field value must be a number'));
            }

            $cache_duration = trim(Tools::getValue('cache_duration'));
            if ($cache_duration && !empty($cache_duration) && ValidateCore::isGenericName($cache_duration)) {
                Configuration::updateValue('CS_CACHE_DURATION', $cache_duration);
            } else {
                $output.= $this->displayError($this->l('Cache duration field is required'));
            }

            Tools::clearCache(null, 'display_home.tpl', Configuration::get('CS_INS_ID'));
            Tools::clearCache(Context::getContext()->smarty, $this->getTemplatePath('index.tpl'));

            $acces_token = trim(Tools::getValue('acces_token'));
            if ($acces_token && !empty($acces_token) && ValidateCore::isGenericName($acces_token)) {
                Configuration::updateValue('CS_INS_CT', Tools::getValue('acces_token'));
            } elseif (!$output) {
                Configuration::updateValue('INSTAGRAM_REDIRECT_AFTER_TOKEN', $_SERVER['REQUEST_URI']);
                $insta_id = Configuration::get('CS_INS_ID');
                $insta_redirect_uri = Context::getContext()->shop->getBaseURL(true)
                    .'modules/tbcmsinstagramslider/generateToken.php';
                $insta_get_code = 'https://api.instagram.com/oauth/authorize/?client_id='.$insta_id
                    .'&redirect_uri='.$insta_redirect_uri.'&response_type=code';

                Tools::redirect($insta_get_code);
            }

            if (!$output) {
                $output = $this->displayConfirmation($this->l('Settings updated'));
            }
        }

        if ($output) {
            return $output;
        }
    }

    private function getForm()
    {
        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->identifier = $this->identifier;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->languages = $this->context->controller->getLanguages();
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
        $helper->default_form_language = $this->context->controller->default_form_language;
        $helper->allow_employee_form_lang = $this->context->controller->allow_employee_form_lang;
        $helper->title = $this->displayName;
        $languages = Language::getLanguages();

        $result = array();
        foreach ($languages as $lang) {
            $result['CS_TITLE'][$lang['id_lang']] = Configuration::get('CS_TITLE', $lang['id_lang']);
        }
        $helper->fields_value['name'] = $result['CS_TITLE'];
        $helper->fields_value['user'] = Configuration::get('CS_USER');
        $helper->fields_value['insta_id'] = Configuration::get('CS_INS_ID');
        $helper->fields_value['insta_secret_id'] = Configuration::get('CS_INS_SECRET_ID');
        $helper->fields_value['acces_token'] = Configuration::get('CS_INS_CT');
        $helper->fields_value['nb_image'] = Configuration::get('CS_NB_IMAGE');
        $helper->fields_value['size'] = Configuration::get('CS_SIZE');
        $helper->fields_value['cache_duration'] = Configuration::get('CS_CACHE_DURATION');
        $helper->fields_value['image_format'] = Configuration::get('CS_IMAGE_FORMAT');
        $helper->fields_value['generate_token'] = 1;

        $helper->submit_action = 'subMOD';

        # form
        $this->fields_form[] = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->displayName,
                ),
                'description' => $this->l('1/ Before filling in the fields below please')
                    .' <a href="https://instagram.com/developer/register/" target="_blank">'
                    .$this->l('Register your application on Instagram API').'</a>.<br />'
                    .$this->l('2/ For the Valid redirect URIs field please enter this URL')
                    .' (<a href="'.Context::getContext()->shop->getBaseURL(true)
                        .'modules/tbcmsinstagramslider/generateToken.php'.'" >'
                        .Context::getContext()->shop->getBaseURL(true).'modules/tbcmsinstagramslider'
                        .'/generateToken.php'.'</a>) '.$this->l('to automatically generate your Instagram'
                        .' Access Token.').'<br />'.'<a href="https://youtu.be/7BxCr_ELdkk"'
                        .' target="_blank">'.$this->l('Installation and configuration video').'</a>',
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->l('Instagram Title:'),
                        'name' => 'name',
                        'lang' => true,
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Instagram User Name:'),
                        'name' => 'user'
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Instagram Client ID:'),
                        'required' => true,
                        'name' => 'insta_id'
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Instagram Client SECRET:'),
                        'required' => true,
                        'name' => 'insta_secret_id'
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Instagram Access Token:'),
                        'name' => 'acces_token',
                        'desc'  => $this->l('Fill in your access token or leave the field empty'
                            .' to generate one automatically')
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Image number:'),
                        'required' => true,
                        'name' => 'nb_image',
                        'desc'  => $this->l('You can retry 20 pics maximum')
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Image format:'),
                        'name' => 'image_format',
                        'options'  => array(
                            'query' => array(
                                array('id'   => 'thumbnail', 'name' => $this->l('Tiny-Thumbnail (150 X 150)')),
                                array('id'   => 'low_resolution' , 'name' => $this->l('Medium (320 X 320)')),
                                array('id'   => 'standard_resolution', 'name' => $this->l('Large (640 X 640)')),
                            ),
                            'id'    => 'id',
                            'name'  => 'name'
                        )
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Resize size in pixel :'),
                        'name' => 'size',
                        'desc'  => $this->l('Your server need the ImageMagick PHP extension to resize'
                            .' pics (0 to desactivate this option)')
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'cache_duration',
                        'label' => $this->l('Refresh :'),
                        'options' => array(
                            'query' => array(
                                array('id' => 'day', 'name' => $this->l('Each day')),
                                array('id' => 'hour', 'name' => $this->l('Each hour'))
                            ),
                            'id' => 'id',
                            'name' => 'name'
                        )
                    )
                ),
                'buttons' => array(
                    'save-and-stay' => array(
                        'title' => $this->l('Save'),
                        'name' => 'subMOD',
                        'type' => 'submit',
                        'id' => 'configuration_form_submit_btn_save',
                        'class' => 'btn btn-default pull-right',
                        'icon' => 'process-icon-save',
                    ),
                    'submit' => array(
                        'title' => $this->l('Save and generate an access token'),
                        'name' => 'subMOD',
                        'type' => 'submit',
                        'id' => 'configuration_form_submit_btn',
                        'class' => 'btn btn-default pull-right',
                        'icon' => 'process-icon-save',
                    )
                )

            )
        );

        return $helper->generateForm($this->fields_form);
    }

    public function hookdisplayWrapperBottom($params)
    {
        return $this->hookdisplayHome($params);
    }

    public function hookdisplayFooterPart1($params)
    {
        return $this->hookdisplayHome($params);
    }

    public function hookdisplayHome($params)
    {
        $conf = Configuration::getMultiple(array('CS_INS_ID', 'CS_CACHE_DURATION'));

        $cacheIdDate = $conf['CS_CACHE_DURATION'] == 'day' ? date('Ymd') : date('YmdH');
        $cache_array = array($this->name, $conf['CS_INS_ID'], $cacheIdDate, (int)$this->context->language->id);
        $cacheId = implode('|', $cache_array);

        if (Configuration::get('CS_SIZE') > 0 && Configuration::get('CS_IMAGE_FORMAT')) {
            $width_slider = Configuration::get('CS_SIZE');
        } else {
            $width_slider = Configuration::get('CS_SIZE_INSTA');
        }

        Media::addJsDef(array('slider_width' => $width_slider));

        if (!$this->isCached('display_home.tpl', $cacheId)) {
            $this->context->smarty->assign(array(
                'instagram_pics' => $this->getPics(),
                'instagram_resolution' => Configuration::get('CS_IMAGE_FORMAT')
            ));
        }

        $output = $this->display(__FILE__, 'views/templates/front/display_home.tpl', $cacheId);
        return $output;
    }

    public static function getFeed()
    {
        $access_token = Configuration::get('CS_INS_CT');
        $nb = Configuration::get('CS_NB_IMAGE');
        $url = 'https://api.instagram.com/v1/users/self/media/recent/?access_token='.$access_token.'&count='.$nb;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $json = curl_exec($ch);
        curl_close($ch);

        return Tools::jsonDecode($json, true);
    }

    public function getPics()
    {

        $conf = Configuration::getMultiple(array('CS_NB_IMAGE', 'CS_SIZE', 'CS_IMAGE_FORMAT'));

        $instagram_pics = array();
        $values = $this->getFeed();

        if (!$values || (isset($values['meta']['error_message']) && $values['meta']['error_message'])) {
            return array();
        }

        $items = array_slice($values['data'], 0, $conf['CS_NB_IMAGE']);

        foreach ($items as $item) {
            $image= $item['images']['standard_resolution']['url'];

            if ($conf['CS_IMAGE_FORMAT'] && !$conf['CS_SIZE']) {
                $image = $item['images'][$conf['CS_IMAGE_FORMAT']]['url'];
            } elseif ($conf['CS_SIZE']) {
                $image = self::imagickResize($image, 'crop', $conf['CS_SIZE']);
            }

            $instagram_pics[] = array(
                'image' => $image,
                'caption' => isset($item['caption']['text']) ? $item['caption']['text'] : '',
                'link' =>  $item['link'],
            );
        }

        return $instagram_pics;
    }

    public static function imagickResize($image, $type, $width, $height = null)
    {
        if (!class_exists('Imagick')) {
            return $image;
        }
        if (is_null($height)) {
            $height = $width;
        }

        $image_name = md5($image) . '_' . $type . '_' . $width . '_' . $height . '.jpg';
        $image_local = _PS_TMP_IMG_DIR_ . $image_name;

        if (!file_exists($image_local)) {
            copy($image, $image_local);
            if (!file_exists($image_local)) {
                return;
            }
            chmod($image_local, 0755);
            $thumb = new Imagick($image_local);
            if ($type == 'crop') {
                $thumb->cropThumbnailImage($width, $height);
            } elseif ($type == 'resize') {
                $thumb->scaleImage($width, $height, true);
            }
            $thumb->writeImage($image_local);
        }

        $context = Context::getContext();
        return $context->link->getMediaLink(_PS_TMP_IMG_ . $image_name);
    }
}
