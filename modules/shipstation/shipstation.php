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
  *  @author    ShipStation
  *  @copyright 2019 ShipStation
  *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
  */
  
if (!defined('_PS_VERSION_')) {
    exit;
}
class ShipStation extends Module
{
    public function __construct()
    {
        $this->name = 'shipstation';
        $this->tab = 'shipping_logistics';
        $this->version = '1.0.0';
        $this->author = 'ShipStation';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        $this->bootstrap = true;
        $this->module_key = 'f0b919b30912bc4aae4fc116ab152dc7';
 
        parent::__construct();
 
        $this->displayName = $this->l('ShipStation');
        $this->description = $this->l('Click on the ShipStation icon in the sidebar to get started.');
 
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
 
        if (!Configuration::get('MYMODULE_NAME')) {
            $this->warning = $this->l('No name provided');
        }
    }
    
    public function install()
    {
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }

            return parent::install()
                &&
                 $this->installTab('IMPROVE', 'AdminShipStation', 'ShipStation')
                ;
    }

    public function uninstall()
    {
        if (!parent::uninstall() || !Configuration::deleteByName('landing_page_url')) {
                return false;
        }
        return true;
    }

    public function installTab($parent_class, $class_name, $name)
    {
        $tab = new Tab();
        $tab->name[$this->context->language->id] = $name;
        $tab->class_name = $class_name;
        $tab->id_parent = (int) Tab::getIdFromClassName($parent_class);
        $tab->module = $this->name;
        $tab->active=1;
        $tab->icon='settings';
        return $tab->add();
    }

    public function getContent()
    {
        return $this->display(__FILE__, 'views/templates/admin/configure.tpl');
    }
}
