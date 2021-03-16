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

$sql = array();

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'orderexchange` (
`id_orderexchange` int(11) NOT NULL AUTO_INCREMENT,
  `id_order_from` int(11) NOT NULL,
  `id_order_to` int(11) DEFAULT NULL,
  `id_order_slip` int(11) NOT NULL,
  `id_cart_rule` int(11) NOT NULL,
  `id_cart` int(11) DEFAULT NULL,
  `id_carrier` int(11) NOT NULL,
  `shipping_cost` int(11) DEFAULT NULL,
  `total_shipping_tax_excl` decimal(20,6) NOT NULL,
  `total_shipping_tax_incl` decimal(20,6) NOT NULL,
  `total_products_tax_incl` decimal(20,6) NOT NULL,
  `total_products_tax_excl` decimal(20,6) NOT NULL,
  `total_discounts` decimal(20,6) NOT NULL,
  `total_tax` decimal(20,6) NOT NULL,
  `balance` decimal(20,6) NOT NULL,
  `reinject_stock` int(1) NOT NULL DEFAULT \'1\',
  `create_order_slip` int(1) NOT NULL DEFAULT \'1\',
  `order_creation` tinyint(1) NOT NULL DEFAULT \'1\',
  `order_message` text,
  `id_order_state` int(11) DEFAULT NULL,
  `payment_module_name` varchar(255) DEFAULT NULL,
  `active` int(1) DEFAULT \'0\',
  `date_add` datetime NOT NULL,
  `date_upd` datetime NOT NULL,
    PRIMARY KEY  (`id_orderexchange`),
    KEY `id_order_from` (`id_order_from`),
    KEY `id_order_to` (`id_order_to`),
    KEY `id_cart` (`id_cart`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'orderexchange_detail` (
    `id_orderexchange_detail` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `id_orderexchange` int(10) unsigned NOT NULL,
    `id_order_detail` int(11) NOT NULL,
    `return_quantity` int(10) NOT NULL,
    `product_id` int(10) unsigned NOT NULL,
    `product_attribute_id` int(10) unsigned DEFAULT NULL,
    `product_quantity` int(10) unsigned NOT NULL DEFAULT \'0\',
    `reserve_stock` tinyint(1) NOT NULL DEFAULT \'0\',
    `reserved_stock` int(11) NOT NULL DEFAULT \'0\',
    PRIMARY KEY (`id_orderexchange_detail`),
    UNIQUE KEY `id_orderexchange_2` (`id_orderexchange`,`product_id`, `product_attribute_id`),
    KEY `id_orderexchange` (`id_orderexchange`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';


foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
