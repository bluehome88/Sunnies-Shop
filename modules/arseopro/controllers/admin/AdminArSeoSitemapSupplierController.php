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

include_once dirname(__FILE__).'/AdminArSeoSitemapController.php';
include_once dirname(__FILE__).'/../../classes/sitemap/models/ArSeoProSitemapSupplier.php';

class AdminArSeoSitemapSupplierController extends AdminArSeoSitemapController
{
    public function displayViewLink($token, $id, $name)
    {
        $link = Context::getContext()->link->getSupplierLink($id);
        return $this->module->render('_partials/_button.tpl', array(
            'href' => $link,
            'onclick' => '',
            'class' => 'btn btn-default',
            'target' => '_blank',
            'title' => $this->l('View'),
            'icon' => 'icon-search-plus'
        ));
    }
    
    public function getListId()
    {
        return 'sitemap-suppliers';
    }
    
    public function renderTable($params = array())
    {
        $pageSize = isset($params['selected_pagination'])? $params['selected_pagination'] : 20;
        
        $helper = new ArSeoProListHelper();
        $helper->title = $this->l('URL Rewrite Rules for products');
        $helper->actions = array(
            'view'
        );
        $helper->list_id = $this->getListId();
        $helper->identifier = 'id_supplier';
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->module->name.'&id_shop='.Context::getContext()->shop->id.'&id_supplier=';
        $helper->setPagination(array(20, 50, 100));
        
        $totalCount = ArSeoProSitemapSupplier::getCount($params);
        
        if (isset($params['page'])) {
            $totalPages = ceil($totalCount / $pageSize);
            if ($params['page'] > $totalPages) {
                $params['page'] = $totalPages;
            }
        }
        
        $helper->listTotal = $totalCount;
        $helper->currentPage = isset($params['page'])? $params['page'] : 1;
        $helper->module = $this->module;
        $helper->no_link = true;
        $helper->setDefaultPagination($pageSize);
        $helper->filters = isset($params['filter'])? $params['filter'] : array();
        $helper->token = Tools::getAdminTokenLite('AdminArSeoSitemap');
        
        $helper->bulk_actions = array(
            'activate' => array(
                'text' => $this->l('Activate selected'),
                'icon' => 'icon-check',
                'confirm' => $this->l('Activate selected items?'),
                'js_action' => 'arSEO.sitemap.supplier.bulk.activate(); return false'
            ),
            'deactivate' => array(
                'text' => $this->l('Deactivate selected'),
                'icon' => 'icon-remove',
                'confirm' => $this->l('Deactivate selected items?'),
                'js_action' => 'arSEO.sitemap.supplier.bulk.deactivate(); return false'
            )
        );
        
        $list = ArSeoProSitemapSupplier::getAll($params);
        $columns = array(
            'id_supplier' => array(
                'title' => $this->l('#'),
                'filter_key' => 'id',
                'orderby' => false,
            ),
            'name' => array(
                'title' => $this->l('Name'),
                'filter_key' => 'name',
                'orderby' => false,
            )
        );
        if (Shop::isFeatureActive()) {
            $columns['id_shop'] = array(
                'title' => $this->l('Shop'),
                'filter_key' => 'id_shop',
                'callback' => 'shopTableValue',
                'type'  => 'select',
                'orderby' => false,
                'search' => false,
                'list'  => $this->getShopList(),
            );
        }
        $columns['export'] = array(
            'title' => $this->l('Export'),
            'filter_key' => 'export',
            'ajax' => true,
            'orderby' => false,
            'type'  => 'select',
            'list' => $this->yesNoList(),
            'callback' => 'renderSupplierCheckbox'
        );
        return $helper->generateList($list, $columns);
    }
    
    public function ajaxProcessSwitch()
    {
        $id = Tools::getValue('id_sitemap');
        $id_supplier = Tools::getValue('id_supplier');
        $id_shop = Context::getContext()->shop->id;
        $sql = 'SELECT * FROM `' . ArSeoProSitemapSupplier::getTableName() . '` WHERE id_shop=' . (int)$id_shop . ' AND id_supplier=' . (int)$id_supplier;
        if ($row = Db::getInstance()->getRow($sql)) {
            $model = new ArSeoProSitemapSupplier($row['id_sitemap']);
        } else {
            $model = new ArSeoProSitemapSupplier();
            $model->id_supplier = (int)$id_supplier;
            $model->id_shop = (int)$id_shop;
        }
        $model->updated_at = date('Y-m-d H:i:s');
        $model->export = $model->export? 0 : 1;
        die(Tools::jsonEncode(array(
            'success' => $model->save(false),
            'status' => $model->export,
            'text' => $this->l('Status updated')
        )));
    }
    
    public function renderSupplierCheckbox($cellValue, $row)
    {
        $link = Context::getContext()->link->getAdminLink('AdminArSeoSitemapSupplier').'&ajax=1&action=switch&id_sitemap='.$row['id_sitemap'].'&id_supplier='.$row['id_supplier'];
        return $this->renderCheckbox($link, $cellValue, $row);
    }
    
    protected function toggle($export = 1)
    {
        $ids = $this->filterIdList(Tools::getValue('ids'));
        $id_shop = Context::getContext()->shop->id;
        $date = date('Y-m-d H:i:s');
        foreach ($ids as $id) {
            $sql = 'SELECT * FROM `' . ArSeoProSitemapSupplier::getTableName() . '` WHERE id_supplier=' . (int)$id . ' AND id_shop=' . (int)$id_shop;
            if ($row = Db::getInstance()->getRow($sql)) {
                $model = new ArSeoProSitemapSupplier($row['id_sitemap']);
            } else {
                $model = new ArSeoProSitemapSupplier();
            }
            
            $model->id_supplier = (int)$id;
            $model->id_shop = (int)$id_shop;
            $model->export = (int)$export;
            $model->updated_at = $date;
            $model->save();
        }
    }
}
