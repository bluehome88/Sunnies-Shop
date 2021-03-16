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

class ExchangeOrder extends PaymentModule
{
    public $active = 1;
    public $name = 'exchange_order';

    public function __construct()
    {
        $this->displayName = $this->l('Exchange order');
        if (version_compare(_PS_VERSION_, '1.6.0.11', '<')) {
            $this->context = Context::getContext();
        }
    }
}
