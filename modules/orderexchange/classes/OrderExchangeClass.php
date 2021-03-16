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

class OrderExchangeClass extends ObjectModel
{

    public $id;
    public $id_order_from;
    public $id_order_to = null;
    public $id_order_slip = null;
    public $id_cart_rule = null;
    public $id_cart = 0;
    public $id_carrier = 0;
    public $shipping_cost = 0;
    public $reinject_stock = 1;
    public $create_order_slip = 1;
    public $order_creation = 1;
    public $order_message;
    public $id_order_state;
    public $payment_module_name;
    public $total_shipping_tax_incl;
    public $total_shipping_tax_excl;
    public $total_products_tax_incl;
    public $total_products_tax_excl;
    public $total_tax;
    public $balance;
    public $total_discounts;
    public $active = 0;
    public $date_add;
    public $date_upd;

    public static $definition = array(
        'table' => 'orderexchange',
        'primary' => 'id_orderexchange',
        'fields' => array(
            'id_order_from' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'id_order_to' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'id_order_slip' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'id_cart_rule' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'id_cart' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'id_carrier' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'shipping_cost' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'total_shipping_tax_incl' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'total_shipping_tax_excl' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'total_products_tax_incl' => array('type' => self::TYPE_FLOAT, 'validate' => 'isNegativePrice'),
            'total_products_tax_excl' => array('type' => self::TYPE_FLOAT, 'validate' => 'isNegativePrice'),
            'total_tax' => array('type' => self::TYPE_FLOAT, 'validate' => 'isNegativePrice'),
            'balance' => array('type' => self::TYPE_FLOAT, 'validate' => 'isNegativePrice'),
            'total_discounts' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'reinject_stock' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'create_order_slip' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'order_creation' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'order_message' => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'),
            'id_order_state' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'payment_module_name' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName',),
            'active' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'date_add' => array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat'),
            'date_upd' => array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat'),

        ),
    );

    public function delete()
    {
        $result = parent::delete();
        $orderexchange_details = $this->getProducts();
        foreach ($orderexchange_details as $orderexchange_detail) {
            $this->liberateStock($orderexchange_detail);
        }

        Db::getInstance()->execute(
            'DELETE FROM '._DB_PREFIX_.'orderexchange_detail
            WHERE id_orderexchange='.(int)$this->id
        );
        return $result;
    }

    public function getProducts()
    {
        return Db::getInstance()->executeS(
            'SELECT * FROM '._DB_PREFIX_.'orderexchange_detail
            WHERE id_orderexchange='.(int)$this->id
        );
    }

    public function findDetail($id_order_detail)
    {
        $orderexchange_detail = Db::getInstance()->getRow(
            'SELECT * FROM '._DB_PREFIX_.'orderexchange_detail
            WHERE id_orderexchange='.(int)$this->id.' AND id_order_detail='.(int)$id_order_detail
        );

        if (!$orderexchange_detail) {
            $orderexchange_detail = array(
                'id_orderexchange' => (int)$this->id,
                'reserved_stock' => 0
            );
        }

        return $orderexchange_detail;
    }

    public function saveDetail(&$detail)
    {
        if (isset($detail['id_orderexchange_detail']) && (int)$detail['id_orderexchange_detail']) {
            Db::getInstance()->update(
                'orderexchange_detail',
                $detail,
                'id_orderexchange_detail='.(int)$detail['id_orderexchange_detail'],
                1
            );
        } else {
            Db::getInstance()->insert('orderexchange_detail', $detail);
            $detail['id_orderexchange_detail'] = Db::getInstance()->Insert_ID();
        }
    }

    public function reserveStock(&$orderexchange_detail)
    {
        $reserve = (int)$orderexchange_detail['reserve_stock'];
        $quantity_to_reserve = $reserve ? $orderexchange_detail['product_quantity'] : 0;
        if (($quantity_diff = $quantity_to_reserve - $orderexchange_detail['reserved_stock']) != 0) {
            if (!StockAvailable::dependsOnStock($orderexchange_detail['product_id'])) {
                StockAvailable::updateQuantity(
                    $orderexchange_detail['product_id'],
                    $orderexchange_detail['product_attribute_id'],
                    $quantity_diff * -1
                );
            }
            Product::updateDefaultAttribute($orderexchange_detail['product_id']);

            if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT')) {
                if (StockAvailable::dependsOnStock($orderexchange_detail['product_id'])) {
                        StockAvailable::synchronize($orderexchange_detail['product_id']);
                }
            }

            $orderexchange_detail['reserved_stock'] = $quantity_to_reserve;
        }
    }

    public function liberateStock(&$orderexchange_detail)
    {
        $quantity_to_reinject = $orderexchange_detail['reserved_stock'];
        if ($quantity_to_reinject != 0) {
            if (!StockAvailable::dependsOnStock($orderexchange_detail['product_id'])) {
                StockAvailable::updateQuantity(
                    $orderexchange_detail['product_id'],
                    $orderexchange_detail['product_attribute_id'],
                    $quantity_to_reinject
                );
            }
            Product::updateDefaultAttribute($orderexchange_detail['product_id']);

            if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT')) {
                if (StockAvailable::dependsOnStock($orderexchange_detail['product_id'])) {
                    StockAvailable::synchronize($orderexchange_detail['product_id']);
                }
            }

            $orderexchange_detail['reserved_stock'] = 0;
        }
    }
}
