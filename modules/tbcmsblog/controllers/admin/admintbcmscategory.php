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
*  @copyright 2007-2019 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

class AdmintbcmscategoryController extends ModuleAdminController
{
    public function __construct()
    {
        $this->table = 'tbcmscategory';
        $this->className = 'TbcmsCategoryClass';
        $this->lang = true;
        $this->deleted = false;
        $this->module = 'tbcmsblog';
        $this->explicitSelect = true;
        $this->_defaultOrderBy = 'position';
        $this->allow_export = false;
        $this->_defaultOrderWay = 'DESC';
        $this->bootstrap = true;
        if (Shop::isFeatureActive()) {
            Shop::addTableAssociation($this->table, array('type' => 'shop'));
        }
        parent::__construct();
        $this->fields_list = array(
            'id_tbcmscategory' => array(
                'title' => $this->l('ID'),
                'width' => 100,
                'type' => 'text',
            ),
            'name' => array(
                    'title' => $this->l('Category Name'),
                    'width' => 60,
                    'type' => 'text',
            ),
            'link_rewrite' => array(
                    'title' => $this->l('URL Rewrite'),
                    'width' => 220,
                    'type' => 'text',
            ),
            'position' => array(
                'title' => $this->l('Position'),
                'align' => 'left',
                'position' => 'position',
            ),
            'active' => array(
                'title' => $this->l('Status'),
                'active' => 'status',
                'type' => 'bool',
                'orderby' => false,
            )
        );
        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'icon' => 'icon-trash',
                'confirm' => $this->l('Delete selected items?')
            )
        );
        parent::__construct();
    }
    
    public function init()
    {
        $tmp = Tools::getValue('id_tbcmscategory');
        if (Tools::getIsset('deletetbcmscategory') && !empty($tmp)) {
            $sql = 'SELECT * FROM `'._DB_PREFIX_.'tbcmscategory` WHERE '
            .' `id_tbcmscategory` = '.$tmp;
            $res = Db::getInstance()->executeS($sql);
            if (file_exists(TBCMSBLOG_IMG_DIR.$res[0]['category_img'])) {
                unlink(TBCMSBLOG_IMG_DIR.$res[0]['category_img']);
            }

            $categories = TbcmsImageTypeClass::getAllImageTypes();
            foreach ($categories as $category) {
                if (file_exists(TBCMSBLOG_IMG_DIR.$category['name'].'-'.$res[0]['category_img'])) {
                    unlink(TBCMSBLOG_IMG_DIR.$category['name'].'-'.$res[0]['category_img']);
                }
            }
        }

        parent::init();
        $this->_join = 'LEFT JOIN '._DB_PREFIX_.'tbcmscategory_shop sbp ON '
            .'a.id_tbcmscategory=sbp.id_tbcmscategory '
            .'&& sbp.id_shop IN('.implode(',', Shop::getContextListShopID()).')';
        $this->_select = 'sbp.id_shop';
        $this->_defaultOrderBy = 'a.position';
        $this->_defaultOrderWay = 'DESC';
        if (Shop::isFeatureActive() && Shop::getContext() != Shop::CONTEXT_SHOP) {
            $this->_group = 'GROUP BY a.id_tbcmscategory';
        }
        $this->_where = ' AND a.category_type = "category" ';
        $this->_select = 'a.position position';
    }
    
    public function setMedia()
    {
        parent::setMedia();
        $this->addJqueryUi('ui.widget');
        $this->addJqueryPlugin('tagify');
        $this->addJqueryPlugin('select2');
    }
    
    public function renderForm()
    {
        $id_tbcmscategory = Tools::getValue("id_tbcmscategory");
        $category_img_temp = '';
        if (isset($id_tbcmscategory) && !empty($id_tbcmscategory)) {
            $tbcmscategoryclass = new TbcmsCategoryClass($id_tbcmscategory);
            if (isset($tbcmscategoryclass->category_img) && !empty($tbcmscategoryclass->category_img)) {
                $category_img_temp = '<img src="'.TBCMSBLOG_IMG_URI.$tbcmscategoryclass->category_img
                    .'" height="110" width="auto"><br>';
            }
        }
        $this->fields_form = array(
            'legend' => array(
                'title' => $this->l('JHPTemplate Blog Category'),
            ),
            'input' => array(
                array(
                    'type' => 'hidden',
                    'name' => 'category_type',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Category Name'),
                    'name' => 'name',
                    'id' => 'name', // for copyMeta2friendlyURL compatibility
                    'class' => 'copyMeta2friendlyURL',
                    'desc' => $this->l('Enter Your Category Name'),
                    'lang' => true,
                ),
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Category Description'),
                    'name' => 'description',
                    'autoload_rte' => true,
                    'rows' => 5,
                    'cols' => 40,
                    'lang' => true,
                    'desc' => $this->l('Please Enter Category Description'),
                ),
                array(
                    'type' => 'file',
                    'label' => $this->l('Category Feature Image'),
                    'name' => 'category_img',
                    'desc' => $category_img_temp.$this->l('Please upload category feature image from your computer.'),
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Select Category Group'),
                    'name' => 'category_group',
                    'options' => array(
                        'query' => TbcmsCategoryClass::serializeCategory(),
                        'id' => 'id',
                        'name' => 'name'
                    )
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Meta Title'),
                    'name' => 'title',
                    'desc' => $this->l('Enter Your Category Meta Title for SEO'),
                    'lang' => true,
                ),
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Meta Description'),
                    'name' => 'meta_description',
                    'desc' => $this->l('Enter Your Category Meta Description for SEO'),
                    'lang' => true,
                ),
                array(
                    'type' => 'tags',
                    'label' => $this->l('Meta Keyword'),
                    'name' => 'keyword',
                    'desc' => $this->l('Enter Your Category Meta Keyword for SEO. Seperate by comma(,)'),
                    'lang' => true,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('URL Rewrite'),
                    'name' => 'link_rewrite',
                    'desc' => $this->l('Enter Your Category URL for SEO URL'),
                    'lang' => true,
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Status'),
                    'name' => 'active',
                    'required' => false,
                    'class' => 't',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    )
                )
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right'
            )
        );
        if (Shop::isFeatureActive()) {
            $this->fields_form['input'][] = array(
                'type' => 'shop',
                'label' => $this->l('Shop association:'),
                'name' => 'checkBoxShopAsso',
            );
        }
        if (!($tbcmscategoryclass = $this->loadObject(true))) {
            return;
        }
        if (isset($tbcmscategoryclass->category_type) && !empty($tbcmscategoryclass->category_type)) {
            $this->fields_value['category_type'] = $tbcmscategoryclass->category_type;
        } else {
            $this->fields_value['category_type'] = "category";
        }
        $this->tpl_form_vars = array(
            'active' => $this->object->active,
            'PS_ALLOW_ACCENTED_CHARS_URL', (int)Configuration::get('PS_ALLOW_ACCENTED_CHARS_URL')
        );
        Media::addJsDef(array('PS_ALLOW_ACCENTED_CHARS_URL' => (int)Configuration::get('PS_ALLOW_ACCENTED_CHARS_URL')));
        return parent::renderForm();
    }
    
    public function renderList()
    {
        if (isset($this->_filter) && trim($this->_filter) == '') {
            $this->_filter = $this->original_filter;
        }
        $this->addRowAction('edit');
        $this->addRowAction('delete');
        return parent::renderList();
    }
    
    public function initToolbar()
    {
          parent::initToolbar();
    }
    
    public function processPosition()
    {
        if ($this->tabAccess['edit'] !== '1') {
            $this->errors[] = Tools::displayError('You do not have permission to edit this.');
        } elseif (!Validate::isLoadedObject($object = new TbcmsCategoryClass((int)Tools::getValue(
            $this->identifier,
            Tools::getValue('id_tbcmscategory', 1)
        )))) {
            $this->errors[] = Tools::displayError('An error occurred while updating the status for an object.').' <b>'.
            $this->table.'</b> '.Tools::displayError('(cannot load object)');
        }
        if (!$object->updatePosition((int)Tools::getValue('way'), (int)Tools::getValue('position'))) {
            $this->errors[] = Tools::displayError('Failed to update the position.');
        } else {
            $object->regenerateEntireNtree();
            Tools::redirectAdmin(self::$currentIndex.'&'.$this->table.'Orderby=position&'.$this->table
                .'Orderway=asc&conf=5'.(($id_tbcmscategory = (int)Tools::getValue($this->identifier)) ? ('&'
                    .$this->identifier.'='.$id_tbcmscategory) : '').'&token='
                .Tools::getAdminTokenLite('Admintbcmscategory'));
        }
    }
    
    public function ajaxProcessUpdatePositions()
    {
        $id_tbcmscategory = (int)(Tools::getValue('id'));
        $way = (int)(Tools::getValue('way'));
        $positions = Tools::getValue($this->table);
        if (is_array($positions)) {
            foreach ($positions as $key => $value) {
                $pos = explode('_', $value);
                if ((isset($pos[1]) && isset($pos[2])) && ($pos[2] == $id_tbcmscategory)) {
                    $position = $key + 1;
                    break;
                }
            }
        }
        $tbcmscategoryclass = new TbcmsCategoryClass($id_tbcmscategory);
        if (Validate::isLoadedObject($tbcmscategoryclass)) {
            if (isset($position) && $tbcmscategoryclass->updatePosition($way, $position)) {
                Hook::exec('action'.$this->className.'Update');
                die(true);
            } else {
                die('{"hasError" : true, errors : "Can not update tbcmscategoryclass position"}');
            }
        } else {
            die('{"hasError" : true, "errors" : "This tbcmscategoryclass can not be loaded"}');
        }
    }
}
