<?php
/**
 * 2015 Adilis
 *
 * With the "Exchange Order" module, quickly manage your exchanged products.
 * In one interface, select the product to be returned and the product that will replace it,
 * confirm the exchange, and the module will take care of it all: create the return, generate
 * a credit and a voucher, and create an order corresponding to the exchange by applying
 * the voucher and requesting payment of the balance from your client if necessary.
 *
 *  @author    Adilis <support@adilis.fr>
 *  @copyright 2015 SAS Adilis
 *  @license   http://www.adilis.fr
 */

class Orderexchange extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        if (!defined('_PS_VERSION_')) {
            exit;
        }

        require_once( _PS_MODULE_DIR_.'orderexchange/classes/OrderExchangeClass.php' );
        $this->name = 'orderexchange';
        $this->tab = 'administration';
        $this->version = '1.0.9';
        $this->author = 'Adilis';
        $this->need_instance = 0;
        $this->bootstrap = true;
        $this->module_key = '30f9ae2e2b438704a02a570165134788';

        parent::__construct();

        $this->displayName = $this->l('Order exchange return');
        $this->description = $this->l('Quickly create order exchange');
    }

    public function install()
    {
        include(dirname(__FILE__).'/sql/install.php');
        Configuration::updateValue('OE_ORDER_REFERENCE', 'reference');
        return parent::install() &&
            $this->installTab() &&
            $this->registerHook('header') &&
            $this->registerHook('backOfficeHeader') &&
            $this->registerHook('actionValidateOrder') &&
            $this->registerHook('displayAdminOrder');
    }

    public function uninstall()
    {
        include(dirname(__FILE__).'/sql/uninstall.php');
        return parent::uninstall() && $this->uninstallTab();
    }

    public function installTab()
    {
        if (!Tab::getIdFromClassName('AdminOrderExchange')) {
            $tab = new Tab();
            $tab->name = array ( (int)Configuration::get('PS_LANG_DEFAULT') => $this->displayName );
            $tab->class_name = 'AdminOrderExchange';
            $tab->module = $this->name;
            $tab->id_parent = Tab::getIdFromClassName('AdminOrders');
            if (!$tab->save()) {
                return false;
            }
        }
        return true;
    }

    public function uninstallTab()
    {
        if ($id_tab = Tab::getIdFromClassName('AdminOrderExchange')) {
            $tab = new Tab($id_tab);
            if (!$tab->delete()) {
                return false;
            }
        }
        return true;
    }

    public function getContent()
    {
        Tools::redirectAdmin(Link::getAdminLink('AdminOrderExchange'));
    }

    public function hookActionValidateOrder($params)
    {
        if (Validate::isLoadedObject($params['order'])) {
            $id_order_exchange = Db::getInstance()->getValue(
                'SELECT id_orderexchange FROM '._DB_PREFIX_.'orderexchange
                WHERE id_cart='.(int)$params['order']->id_cart
            );
            if ((int)$id_order_exchange) {

                Db::getInstance()->execute(
                    'UPDATE '._DB_PREFIX_.'orderexchange
                    SET id_order_to='.(int)$params['order']->id.'
                    WHERE id_orderexchange='.(int)$id_order_exchange.' LIMIT 1'
                );

                // MondialRelay Compliant ;)
                if (Module::isInstalled('mondialrelay')) {
                    $mondialrelay = Module::getInstanceByName('mondialrelay');
                    if ($mondialrelay->active && Mondialrelay::isMondialRelayCarrier($params['order']->id_carrier)) {
                        $data = array('id_order' => $params['order']->id);
                        Db::getInstance()->update('mr_selected', $data, 'id_cart='.(int)$params['order']->id_cart, 1);
                    }
                }
            }
        }
    }

    public function hookDisplayAdminOrder($params)
    {
        $id_order = (int)$params['id_order'];
        $exchange = Db::getInstance()->getRow(
            'SELECT * FROM '._DB_PREFIX_.'orderexchange
            WHERE id_order_to='.$id_order.' OR id_order_from ='.$id_order
        );
        if ($exchange) {
            $order_from = new Order((int)$exchange['id_order_from']);
            $order_to = new Order((int)$exchange['id_order_to']);
            $cart_rule = new CartRule((int)$exchange['id_cart_rule']);
            $currency = new Currency($order_to->id_currency);
            $order_exchange_link = $this->context->link->getAdminLink('AdminOrderExchange');

            $display = Configuration::get('OE_ORDER_REFERENCE');
            $this->context->smarty->assign(array(
                'order_exchange_reference' => (int)$exchange['id_orderexchange'],
                'order_exchange_link' =>
                    $order_exchange_link.
                    '&updateorderexchange&id_orderexchange='.
                    (int)$exchange['id_orderexchange'],
                'order_from_reference' =>  $display == 'reference' ? $order_from->reference : $order_from->id,
                'order_from_link' =>
                    $this->context->link->getAdminLink('AdminOrders').
                    '&vieworder&id_order='.$order_from->id,
                'order_to_reference' => $display == 'reference' ? $order_to->reference : $order_to->id,
                'order_to_link' =>
                    $this->context->link->getAdminLink('AdminOrders').
                    '&vieworder&id_order='.$order_to->id,
                'order_to_amount' => $order_to->total_paid,
                'cart_rule_code' => $cart_rule->code,
                'cart_rule_link' =>
                    $this->context->link->getAdminLink('AdminCartRules').
                    '&updatecart_rule&id_cart_rule='.$cart_rule->id,
                'cart_rule_amount' => $cart_rule->reduction_amount,
                'cart_rule_shipping' => $cart_rule->free_shipping,
                'currency' => $currency,
            ));

            if ((int)$exchange['id_order_slip']) {
                $order_slip = new OrderSlip((int)$exchange['id_order_slip']);
                $this->context->smarty->assign(array(
                    'order_slip_reference' => $order_slip->id,
                    'order_slip_link' =>
                        $this->context->link->getAdminLink('AdminPdf').
                        '&submitAction=generateOrderSlipPDF&id_order_slip='.$order_slip->id,
                    'order_slip_amount' => $order_slip->amount,
                ));
            }

            return $this->display(__FILE__, 'views/templates/admin/admin_order.tpl');
        }
    }
}
