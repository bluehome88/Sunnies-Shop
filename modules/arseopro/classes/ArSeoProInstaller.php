<?php
/**
* 2012-2018 Areama
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@areama.net so we can send you a copy immediately.
*
*  @author    Areama <contact@areama.net>
*  @copyright 2018 Areama
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of Areama
*/

include_once dirname(__FILE__).'/redirects/models/ArSeoProRedirectTable.php';
include_once dirname(__FILE__).'/url/models/ArSeoProRuleTable.php';
include_once dirname(__FILE__).'/sitemap/models/ArSeoProSitemapProduct.php';
include_once dirname(__FILE__).'/sitemap/models/ArSeoProSitemapSupplier.php';
include_once dirname(__FILE__).'/sitemap/models/ArSeoProSitemapManufacturer.php';
include_once dirname(__FILE__).'/sitemap/models/ArSeoProSitemapCms.php';
include_once dirname(__FILE__).'/sitemap/models/ArSeoProSitemapCategory.php';
include_once dirname(__FILE__).'/sitemap/models/ArSeoProSitemapMetaPages.php';
include_once dirname(__FILE__).'/meta/models/ArSeoProMetaTable.php';
include_once dirname(__FILE__).'/canonical/ArSeoProCanonicalProduct.php';
include_once dirname(__FILE__).'/canonical/ArSeoProCanonicalCategory.php';
include_once dirname(__FILE__).'/ArSeoProRobots.php';


/**
 * @property ArSeoPro $module
 */
class ArSeoProInstaller
{
    protected $module = null;
    
    protected $tabs = array(
        'AdminArSeoUrls',
        'AdminArSeoMeta',
        'AdminArSeoRedirects',
        'AdminArSeoRobots',
        'AdminArSeoSitemap',
        'AdminArSeoSitemapProduct',
        'AdminArSeoSitemapSupplier',
        'AdminArSeoSitemapManufacturer',
        'AdminArSeoSitemapCms',
        'AdminArSeoSitemapMeta',
        'AdminArSeoSitemapCategory',
        'AdminArSeo'
    );
    
    protected $hooks = array(
        'actionAdminMetaAfterWriteRobotsFile',
        'displayAdminNavBarBeforeEnd',
        'displayHeader',
        'actionDispatcher',
        'moduleRoutes',
        
        'actionObjectProductAddAfter',
        'actionObjectProductUpdateAfter',
        'actionObjectProductUpdateBefore',
        
        'displayBeforeBodyClosingTag',
        'displayFooter',
        'actionProductSearchAfter',
        'actionProductListModifier',
        
        'actionProductUpdate',
        'actionCategoryUpdate',
        'displayAdminProductsSeoStepBottom',
        'displayBackOfficeCategory',
    );

    public function __construct($module)
    {
        $this->setModule($module);
    }
    
    public function setModule($module)
    {
        $this->module = $module;
    }
    
    public function getModule()
    {
        return $this->module;
    }
    
    public function install()
    {
        Configuration::updateValue('ARSEO_INSTALL_TS', time());
        Configuration::updateValue('ARSEO_SITEMAP_TOKEN', md5(uniqid()));
        $robots = new ArSeoProRobots($this->module);
        return $this->installHook() &&
                $this->installTabs() &&
                $this->installDB() &&
                $this->installDefaults() &&
                $this->installOverrides() &&
                $robots->loadDefaults(true) &&
                $this->clearDefaultRoutes() &&
                $this->module->clearGlobalCache();
    }
    
    public function uninstall()
    {
        return $this->uninstallDB() && $this->uninstallDefaults() && $this->unistallTabs() && $this->restoreDefaultRoutes();
    }
    
    public function unistallTabs()
    {
        foreach ($this->tabs as $tabName) {
            $id_tab = Tab::getIdFromClassName($tabName);
            $tab = new Tab($id_tab);
            $tab->delete();
        }
        return true;
    }
    
    public function uninstallDB()
    {
        Configuration::deleteByName('ARSEO_NFL_TIME');
        Configuration::deleteByName('ARSP_SCHEMA');
        Configuration::deleteByName('ARSEO_INSTALL_TS');
        Configuration::deleteByName('ARSEO_SITEMAP_TOKEN');
        Configuration::deleteByName('ARSEO_SITEMAP_GEN');
        return ArSeoProRedirectTable::uninstallTable() &&
                ArSeoProRuleTable::uninstallTable() &&
                ArSeoProSitemapProduct::uninstallTable() &&
                ArSeoProSitemapSupplier::uninstallTable() &&
                ArSeoProSitemapManufacturer::uninstallTable() &&
                ArSeoProSitemapCms::uninstallTable() &&
                ArSeoProSitemapMetaPages::uninstallTable() &&
                ArSeoProSitemapCategory::uninstallTable() &&
                ArSeoProMetaTable::uninstallTable() &&
                ArSeoProCanonicalProduct::uninstallTable() &&
                ArSeoProCanonicalCategory::uninstallTable();
    }
    
    public function installTabs()
    {
        foreach ($this->tabs as $tabName) {
            $tab = new Tab();
            $tab->active = 1;
            $tab->class_name = $tabName;
            $tab->name = array();
            foreach (Language::getLanguages(true) as $lang) {
                if ($tabName == 'AdminArSeo') {
                    $tab->name[$lang['id_lang']] = 'All in one SEO Pro';
                } else {
                    $tab->name[$lang['id_lang']] = $tabName;
                }
            }
            if ($tabName == 'AdminArSeo') {
                if ($this->module->is17()) {
                    $parentId = Tab::getIdFromClassName('CONFIGURE');
                    $tab->id_parent = $parentId;
                    if (property_exists($tab, 'icon')) {
                        $tab->icon = 'link';
                    }
                } else {
                    $tab->id_parent = 0;
                }
            } else {
                $tab->id_parent = -1;
            }
            $tab->module = $this->module->name;
            $tab->add();
        }
        return true;
    }
    
    public function installHook()
    {
        $res = true;
        foreach ($this->hooks as $hook) {
            $res = $res && $this->module->registerHook($hook);
        }
        return $res;
    }
    
    public function installDB()
    {
        return ArSeoProRedirectTable::installTable() &&
                ArSeoProRuleTable::installTable() &&
                ArSeoProSitemapProduct::installTable() &&
                ArSeoProSitemapSupplier::installTable() &&
                ArSeoProSitemapManufacturer::installTable() &&
                ArSeoProSitemapCms::installTable() &&
                ArSeoProSitemapMetaPages::installTable() &&
                ArSeoProSitemapCategory::installTable() &&
                ArSeoProMetaTable::installTable() &&
                ArSeoProCanonicalProduct::installTable() &&
                ArSeoProCanonicalCategory::installTable();
    }
    
    public function uninstallDefaults()
    {
        foreach ($this->module->getForms() as $model) {
            $model->clearConfig();
        }
        return true;
    }
    
    public function installDefaults()
    {
        foreach ($this->module->getForms() as $model) {
            $model->loadDefaults();
            $model->saveToConfig(false);
        }
        return true;
    }
    
    public function clearDefaultRoutes()
    {
        $dispatcher = Dispatcher::getInstance();
        $defaultRoutes = $dispatcher->default_routes;
        $prefix = 'PS_ROUTE_';
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }
        foreach (array_keys($defaultRoutes) as $rule) {
            $currentRule = Configuration::get($prefix . $rule);
            if ($currentRule) {
                $defaultRoutes[$rule]['rule'] = $currentRule;
            }
            Configuration::deleteByName($prefix . $rule);
        }
        Configuration::updateValue('ARSEO_OLD_ROUTES', Tools::jsonEncode($defaultRoutes));
        return true;
    }
    
    public function restoreDefaultRoutes()
    {
        if ($value = Configuration::get('ARSEO_OLD_ROUTES')) {
            $defaultRoutes = Tools::jsonDecode($value);
            $prefix = 'PS_ROUTE_';
            foreach ($defaultRoutes as $k => $rule) {
                if ($rule->rule) {
                    Configuration::updateValue($prefix . $k, $rule->rule);
                }
            }
        }
        return Configuration::deleteByName('ARSEO_OLD_ROUTES');
    }
    
    public function installOverrides()
    {
        return true;
    }
    
    public function prepareOverrides()
    {
        $override_path = realpath(dirname(__FILE__).'/../override/') . '/';
        
        if ($this->module->is16()) {
            $override_version_path = realpath(dirname(__FILE__).'/../override_versions/1.6.x/') . '/';
            $files_to_copy = Tools::scandir($override_version_path, 'php', '', true);
            if ($files_to_copy) {
                foreach ($files_to_copy as $file) {
                    Tools::copy($override_version_path.$file, $override_path.$file);
                }
            }
        }

        if ($this->module->is17()) {
            if ($this->module->is178x()) {
                $override_version_path = realpath(dirname(__FILE__).'/../override_versions/1.7.8.x/') . '/';
            } else {
                $override_version_path = realpath(dirname(__FILE__).'/../override_versions/1.7.x/') . '/';
            }
            $files_to_copy = Tools::scandir($override_version_path, 'php', '', true);
            
            if ($files_to_copy) {
                foreach ($files_to_copy as $file) {
                    $info = pathinfo($file);
                    if (!is_dir($override_path.$info['dirname'])) {
                        mkdir($override_path.$info['dirname'], 0777, true);
                    }
                    
                    Tools::copy($override_version_path.$file, $override_path.$file);
                }
            }
        }
        return true;
    }
}
