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

class AdminOrderExchangeController extends ModuleAdminController
{
    public $show_page_header_toolbar = true;
    public $multishop_context = Shop::CONTEXT_SHOP;

    public static $cache_product_attributes = array();

    public function __construct()
    {
        require_once(dirname(__FILE__).'/../../orderexchange.php');
        require_once(dirname(__FILE__).'/../../classes/ExchangeOrder.php');

        $this->table = 'orderexchange';
        $this->className = 'OrderExchangeClass';
        $this->identifier = 'id_orderexchange';
        $this->list_id = 'orderexchange';
        $this->bootstrap = true;
        $this->lang = false;
        $this->module_dir = _MODULE_DIR_.'orderexchange';
        $this->module_path = _PS_MODULE_DIR_.'orderexchange';
        $this->module = Module::getInstanceByName('orderexchange');
        $this->specificConfirmDelete = false;

        $this->fields_options = array(
            'general' => array(
                'title' =>    $this->l('Options'),
                'fields' =>    array(
                    'OE_ORDER_REFERENCE' => array(
                        'title' => $this->l('How display orders'),
                        'type' => 'radio',
                        'name' => 'OE_ORDER_REFERENCE',
                        'choices' => array(
                            'reference' => $this->l('Use order reference'),
                            'id' => $this->l('Use order ID')
                        )
                    ),
                ),
                'submit' => array('title' => $this->l('Save'))
            )
        );

        parent::__construct();

        $this->_select = 'of.id_currency, a.id_orderexchange, a.id_order_from, a.id_order_to,
        CONCAT(LEFT(c.`firstname`, 1), \'. \', c.`lastname`) AS `customer`, of.reference, ot.reference as reference_to,
        IF(a.balance>0, 1, 0) badge_success, a.active as validated';

        $this->_join = '
        INNER JOIN `'._DB_PREFIX_.'orders` of ON of.id_order = a.id_order_from
        LEFT JOIN `'._DB_PREFIX_.'orders` ot ON ot.id_order = a.id_order_to
        INNER JOIN `'._DB_PREFIX_.'customer` c ON (c.`id_customer` = of.`id_customer`)';

        $this->bulk_actions = array(
                'delete' => array(
                    'text' => $this->l('Delete selected'),
                    'icon' => 'icon-trash',
                    'confirm' => $this->l('Delete selected items?')
                )
            );

        $order_from_key = Configuration::get('OE_ORDER_REFERENCE') == 'reference' ? 'reference' : 'id_order_from';
        $order_from_to = Configuration::get('OE_ORDER_REFERENCE') == 'reference' ? 'reference_to' : 'id_order_to';

        $this->fields_list = array(
            'id_orderexchange' => array(
                'title' => $this->l('ID'),
                'align' => 'center',
                'class' => 'fixed-width-xs'
            ),
            $order_from_key => array(
                'title' => $this->l('Original order'),
            ),
            'customer' => array(
                'title' => $this->l('Customer'),
                'havingFilter' => true,
            ),
            'balance' => array(
                'title' => $this->l('Total'),
                'align' => 'text-right',
                'type' => 'price',
                'currency' => true,
                'callback' => 'setOrderCurrency',
                'badge_success' => true
            ),
            $order_from_to => array(
                'title' => $this->l('Exchange order'),
            ),
            'active' => array(
                'title' => $this->l('Validated'),
                'activeVisu' => true,
                'type' => 'bool',
                'class' => 'fixed-width-xs',
                'align' => 'center',
                'orderby' => false,
            )
        );

        $this->actions = array(
            'edit',
            'delete'
        );
    }

    public function setMedia()
    {
        parent::setMedia();

        $this->removeChosen();

        $this->context->controller->addJs($this->module_dir.'/views/js/chosen.jquery.min.js');
        $this->context->controller->addJs(array(
            $this->module_dir.'/views/js/back.js'
        ));

        $this->context->controller->addJs(array(
                $this->module_dir.'/views/js/back.js'
            ));

        $this->context->controller->addCss(array(
            $this->module_dir.'/views/css/back.css',
        ));

        if (_PS_VERSION_ < 1.6) {
            $this->context->controller->addCss(array(
                $this->module_dir.'/views/css/chosen.min.css',
                $this->module_dir.'/views/css/font-awesome.min.css',
                $this->module_dir.'/views/css/back15.css',
            ));
        }

        $this->addJqueryPlugin(array('typewatch'));

    }

    public function initContent()
    {
        parent::initContent();

        if ($this->display == 'edit' || $this->display == 'add') {
            if ($this->object->id && Validate::isLoadedObject($this->context->cart)) {
                $this->cleanCart();
            }
        }
    }

    public function initToolbar()
    {
        switch ($this->display) {
            case 'add':
            case 'edit':
                // Default save button - action dynamically handled in javascript
                $this->toolbar_btn['save-wait'] = array(
                    'href' => '#',
                    'class' => 'process-icon-save-and-wait',
                    'desc' => $this->l('Create awaiting exchange')
                );
                $this->toolbar_btn['save'] = array(
                    'href' => '#',
                    'desc' => $this->l('Valid exchange')
                );
                $this->toolbar_btn['back'] = array(
                    'href' => self::$currentIndex.'&token='.$this->token,
                    'desc' => $this->l('Back to list', null, null, false),
                    'icon' => 'process-icon-back'
                );
                //no break
            case 'view':
                // Default cancel button - like old back link
                $back = Tools::safeOutput(Tools::getValue('back', ''));
                if (empty($back)) {
                    $back = self::$currentIndex.'&token='.$this->token;
                }
                if (!Validate::isCleanHtml($back)) {
                    die();
                }
                if (!$this->lite_display) {
                    $this->toolbar_btn['back'] = array(
                        'href' => $back,
                        'desc' => $this->l('Back to list')
                    );
                }
                break;
            case 'options':
                $this->toolbar_btn['save'] = array(
                    'href' => '#',
                    'desc' => $this->l('Save')
                );
                break;
            case 'view':
                break;
            default: // list
                $this->toolbar_btn['new'] = array(
                    'href' => self::$currentIndex.'&add'.$this->table.'&token='.$this->token,
                    'desc' => $this->l('Add new')
                );
        }
    }

    public function initPageHeaderToolbar()
    {
        switch ($this->display) {
            case 'add':
            case 'edit':
                $this->page_header_toolbar_btn['back-to-list'] = array(
                    'href' => self::$currentIndex.'&token='.$this->token,
                    'desc' => $this->l('Back to list', null, null, false),
                    'icon' => 'process-icon-back'
                );
                break;
            default:
                $this->page_header_toolbar_btn['new'] = array(
                    'href' => self::$currentIndex.'&add'.$this->table.'&token='.$this->token,
                    'desc' => $this->l('Add new', null, null, false),
                    'icon' => 'process-icon-new'
                );
        }
        parent::initPageHeaderToolbar();
    }

    protected function loadObject($opt = false)
    {
        $rs = parent::loadObject($opt);
        if ($this->object->id && (int)$this->object->id_cart) {
            $cart = new Cart((int)$this->object->id_cart);
            if (Validate::isLoadedObject($cart)) {
                $this->context->cart = $cart;
                $this->context->customer = new Customer($cart->id_customer);
            }
        }
        return $rs;
    }

    public static function setOrderCurrency($echo)
    {
        return Tools::displayPrice($echo);
    }

    public function renderForm()
    {
        $orders = self::getLastsOrdersWithInvoice();
        $this->fields_value['products'] = '<div id="mask"><div>'.$this->l('Loading, please wait ...').'</div>
</div><div id="products"></div>';
        $this->fields_value['order_creation'] = $this->renderFormOrderCreation();

        $this->fields_form = array(
            'legend' => array( 'title' => $this->l('Orders exchanges') ),
            'input' => array(
                array(
                    'type' => 'hidden',
                    'name' => 'id_cart',
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Select order'),
                    'name' => 'id_order_from',
                    'required' => true,
                    'options' => array(
                        'default' => array( 'value' => null, 'label' => $this->l('Select order') ),
                        'query' => $orders,
                        'id' => 'id_order',
                        'name' => Configuration::get('OE_ORDER_REFERENCE') == 'reference' ? 'reference' : 'id_order'
                    ),
                ),
                array(
                    'type' => 'free',
                    'form_group_class' => 'hide_label',
                    'name' => 'products',
                    'col' => 12,
                    'label' => '&nbsp;'
                ),
            ),
        );

        if (!$this->object->active) {
            $this->fields_form['input'] = array_merge($this->fields_form['input'], array(
                array(
                    'type' => version_compare(_PS_VERSION_, '1.6.0', '>=') ? 'switch' : 'radio',
                    'class' => 't',
                    'label' => $this->l('Add shipping costs'),
                    'name' => 'shipping_cost',
                    'required' => true,
                    'is_bool' => true,
                    'form_group_class' => 'hide',
                    'values' => array(
                        array(
                            'id' => 'shipping_cost_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'shipping_cost_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    )
                ),
                array(
                    'type' => 'select',
                    'form_group_class' => $this->action == 'new' ? 'hide' : '',
                    'name' => 'delivery_option',
                    'form_group_class' => 'hide',
                    'label' => $this->l('Delivery option'),
                    'onchange' => 'updateDeliveryOption();',
                    'options' => array(
                        'query' => array(),
                        'id' => 'id_delivery_option',
                        'name' => 'name'
                    ),
                ),
                array(
                    'type' => version_compare(_PS_VERSION_, '1.6.0', '>=') ? 'switch' : 'radio',
                    'class' => 't',
                    'label' => $this->l('Reinject stock'),
                    'name' => 'reinject_stock',
                    'required' => true,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'reinject_stock_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'reinject_stock_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    )
                ),
                array(
                    'type' => version_compare(_PS_VERSION_, '1.6.0', '>=') ? 'switch' : 'radio',
                    'class' => 't',
                    'label' => $this->l('Create order slip'),
                    'name' => 'create_order_slip',
                    'required' => true,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'create_order_slip_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'create_order_slip_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    )
                ),
                array(
                    'type' => 'free',
                    'name' => 'order_creation',
                    'form_group_class' => 'hide_label',
                    'col' => 12,
                    'label' => '&nbsp;'
                )
            ));
            if (_PS_VERSION_ < 1.6) {
                $this->fields_form['submit'] = array(
                    'title' => $this->l('Valid exchange'),
                    'class' => 'button'
                );
            } else {
                $this->fields_form['buttons'] = array(
                    'save' => array(
                        'title' => $this->l('Valid exchange'),
                        'name' => 'submitAdd'.$this->table,
                        'type' => 'submit',
                        'class' => 'btn btn-primary pull-right',
                        'icon' => 'process-icon-save',
                        'value' => 0
                    ),
                    'save-and-stay' => array(
                        'title' => $this->l('Create awaiting exchange'),
                        'name' => 'submitAdd'.$this->table.'AndWait',
                        'type' => 'submit',
                        'class' => 'btn btn-default pull-right',
                        'icon' => 'process-icon-save',
                        'value' => 0
                    ),
                );
            }
        } else {
            $this->fields_value['exchange_resume'] = $this->renderExchangeResume();
            $this->fields_form['input'] = array_merge($this->fields_form['input'], array(
                array(
                    'type' => 'free',
                    'name' => 'exchange_resume',
                    'form_group_class' => 'hide_label',
                    'col' => 12,
                    'label' => '&nbsp;'
                )
            ));
            $this->fields_form['input'][1] = array(
                    'type' => 'hidden',
                    'name' => 'id_order_from',
            );
        }

        if (_PS_VERSION_ < 1.6) {
            $string = parent::renderForm();
            $string .= $this->renderCompatibility();
        } else {
            $string = parent::renderForm();
        }
        $string .= $this->renderAbout();
        return $string;

    }

    public function renderHelperForm()
    {
        if (!$this->default_form_language) {
            $this->getLanguages();
        }

        if (Tools::getValue('submitFormAjax')) {
            $this->content .= $this->context->smarty->fetch('form_submit_ajax.tpl');
        }
        if ($this->fields_form && is_array($this->fields_form)) {
            if (!$this->multiple_fieldsets) {
                $this->fields_form = array(array('form' => $this->fields_form));
            }
            // For add a fields via an override of $fields_form, use $fields_form_override
            if (is_array($this->fields_form_override) && !empty($this->fields_form_override)) {
                $this->fields_form[0]['form']['input'][] = $this->fields_form_override;
            }

            $helper = new HelperForm($this);
            $helper->base_folder = 'helpers/form16/';
            $this->context->smarty->assign(array(
                'show_cancel_button' => true,
            ));
            $this->setHelperDisplay($helper);
            $helper->fields_value = $this->getFieldsValue($this->object);
            $helper->tpl_vars = $this->tpl_form_vars;
            !is_null($this->base_tpl_form) ? $helper->base_tpl = $this->base_tpl_form : '';
            if ($this->tabAccess['view']) {
                if (Tools::getValue('back')) {
                    $helper->tpl_vars['back'] = Tools::safeOutput(Tools::getValue('back'));
                } else {
                    $helper->tpl_vars['back'] = Tools::safeOutput(
                        Tools::getValue(self::$currentIndex.'&token='.$this->token)
                    );
                }
            }
            $form = $helper->generateForm($this->fields_form);
            return $form;
        }
    }

    public function renderFormOrderCreation()
    {
        $payment_modules = array();
        foreach (PaymentModule::getInstalledPaymentModules() as $p_module) {
            $payment_modules[] = Module::getInstanceById((int)$p_module['id_module']);
        }
        $this->context->smarty->assign(array(
            'payment_modules' => $payment_modules,
            'order_creation' => Tools::getValue('order_creation', $this->object->order_creation),
            'id_order_state' => Tools::getValue('id_order_state', $this->object->id_order_state),
            'payment_module_name' => Tools::getValue('payment_module_name', $this->object->payment_module_name),
            'order_states' => OrderState::getOrderStates((int)Context::getContext()->language->id),
            'PS_CATALOG_MODE' => Configuration::get('PS_CATALOG_MODE'),
        ));
        return $this->context->smarty->fetch($this->module_path.'/views/templates/admin/order_creation.tpl');
    }

    public function renderExchangeResume()
    {
        $order_from = new Order((int)$this->object->id_order_from);
        $order_to = new Order((int)$this->object->id_order_to);
        $cart_rule = new CartRule((int)$this->object->id_cart_rule);
        $currency = new Currency($order_to->id_currency);

        $use_reference = Configuration::get('OE_ORDER_REFERENCE');
        $this->context->smarty->assign(array(
            'order_from_reference' => $use_reference == 'reference' ? $order_from->reference : $order_from->id,
            'order_from_link' =>
                $this->context->link->getAdminLink('AdminOrders').'&vieworder&id_order='.$order_from->id,
            'order_to_reference' => $use_reference == 'reference' ? $order_to->reference : $order_to->id,
            'order_to_link' =>
                $this->context->link->getAdminLink('AdminOrders').'&vieworder&id_order='.$order_to->id,
            'order_to_amount' => $order_to->total_paid,
            'cart_rule_code' => $cart_rule->code,
            'cart_rule_link' =>
                $this->context->link->getAdminLink('AdminCartRules').'&updatecart_rule&id_cart_rule='.$cart_rule->id,
            'cart_rule_amount' => $cart_rule->reduction_amount,
            'cart_rule_shipping' => $cart_rule->free_shipping,
            'currency' => $currency,
        ));

        if ((int)$this->object->id_order_slip) {
            $order_slip = new OrderSlip((int)$this->object->id_order_slip);
            $this->context->smarty->assign(array(
                'order_slip_reference' => $order_slip->id,
                'order_slip_link' =>
                    $this->context->link->getAdminLink('AdminPdf').
                    '&submitAction=generateOrderSlipPDF&id_order_slip='.$order_slip->id,
                'order_slip_amount' => $order_slip->amount,
            ));
        }

        return $this->context->smarty->fetch($this->module_path.'/views/templates/admin/resume.tpl');
    }

    public function renderOptions()
    {
        $string = parent::renderOptions();
        $string .= $this->renderAbout();
        return $string;
    }

    public function renderAbout()
    {
        $this->context->smarty->assign(array(
            'moduleversion' => $this->module->version,
            'module_dir' => $this->module_dir,
            'psversion' => _PS_VERSION_,
            'phpversion' => PHP_VERSION
        ));
        $string = $this->context->smarty->fetch($this->module_path.'/views/templates/admin/about.tpl');
        return $string;
    }

    public function renderCompatibility()
    {
        $this->context->smarty->assign(array(
            'action' => $this->action
        ));
        $string = $this->context->smarty->fetch($this->module_path.'/views/templates/admin/compatibility.tpl');
        return $string;
    }

    public function ajaxProcessgetproducts()
    {
        $id_order = (int)Tools::getValue('id_order');

        $this->loadObject(true);
        $this->object->products = $this->object->getProducts();

        $order = new Order($id_order);
        $customer = new Customer($order->id_customer);
        $products = $this->getProducts($order);

        $this->context->smarty->assign(array(
            'order' => $order,
            'orderexchange' => $this->object,
            'products' => $products,
            'id_address_delivery' => $order->id_address_delivery,
            'id_address_invoice' => $order->id_address_invoice,
            'customer_name' => $customer->firstname.' '.$customer->lastname,
            'display_orders' => Configuration::get('OE_ORDER_REFERENCE'),
            'display_warehouse' => (int)Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT'),
            'currency' => new Currency($order->id_currency),
            'current_id_lang' =>$this->context->language->id,
            'link' =>$this->context->link,
            'current_index' => self::$currentIndex,
        ));

        $this->content = $this->context->smarty->fetch($this->module_path.'/views/templates/admin/products.tpl');
    }

    protected function emptyCart()
    {
        Db::getInstance()->execute(
            'DELETE FROM `'._DB_PREFIX_.'cart_product`
            WHERE `id_cart` = '.(int)$this->context->cart->id
        );
        return $this->context->cart->update();
    }

    protected function cleanCart()
    {
        $exchange_p_array = $this->object->getProducts();
        foreach ($this->context->cart->getProducts() as $cart_product) {
            foreach ($exchange_p_array as $key => $exchange_p) {
                if (
                    $exchange_p['product_id'] == $cart_product['id_product'] &&
                    $exchange_p['product_attribute_id'] == $cart_product['id_product_attribute']
                ) {
                    if ((int)$cart_product['cart_quantity'] != (int)$exchange_p['product_quantity']) {
                        $quantity = abs((int)$cart_product['cart_quantity'] - (int)$exchange_p['product_quantity']);
                        $operator = $cart_product['cart_quantity'] < $exchange_p['product_quantity'] ? 'up' : 'down';
                        $this->context->cart->updateQty(
                            $quantity,
                            $exchange_p['product_id'],
                            $exchange_p['product_attribute_id'],
                            false,
                            $operator
                        );
                    }
                    unset($exchange_p_array[$key]);
                    continue(2);
                }
            }
            $this->context->cart->deleteProduct($cart_product['id_product'], $cart_product['id_product_attribute']);
        }
        foreach ($exchange_p_array as $key => $product) {
            if (Validate::isLoadedObject($product)) {
                $this->context->cart->updateQty(
                    $product['product_quantity'],
                    $product['product_id'],
                    $product['product_attribute_id'],
                    false,
                    'up'
                );
            }
        }
    }

    public static function getLastsOrdersWithInvoice()
    {
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS(
            'SELECT `id_order`, `reference`
            FROM `'._DB_PREFIX_.'orders`
            WHERE date_upd <= NOW() AND date_upd >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
            '.Shop::addSqlRestriction().'
            AND `valid` = 1 ORDER BY date_upd DESC'
        );
    }

    public function removeChosen()
    {
        foreach ($this->js_files as $key => $js_files) {
            if (strpos($js_files, 'jquery.chosen.js') !== false) {
                unset($this->js_files[$key]);
            }
        }
    }

    protected function getProducts($order)
    {
        $products = $order->getProducts();
        $use_tax = (int)$order->getTaxCalculationMethod() == 1 ? 'tax_excl' : 'tax_incl';

        foreach ($products as &$product) {
            $product['has_exchange'] = false;

            foreach ($this->object->products as $exchange) {
                if ((int)$exchange['id_order_detail'] == (int)$product['id_order_detail']) {
                    $product['has_exchange'] = true;
                    $price = $product['unit_price_'.$use_tax];
                    $product['exchange'] = array(
                        'name' => Product::getProductName(
                            (int)$exchange['product_id'],
                            null,
                            $this->context->cookie->id_lang
                        ),
                        'price' => Tools::convertPrice($price, $this->context->currency),
                        'formatted_price' => Tools::displayPrice(
                            Tools::convertPrice($price, $this->context->currency),
                            $this->context->currency
                        ),
                        'tax_rate' => $product['tax_rate'],
                        'id_product' => (int)$exchange['product_id'],
                        'id_product_attribute' => (int)$exchange['product_attribute_id'],
                        'reserve_stock' => (int)$exchange['reserve_stock'],
                        'quantity' => (int)$exchange['product_quantity'],
                        'return_quantity' => (int)$exchange['return_quantity'],
                        'combinations' => self::getProductAttributes((int)$exchange['product_id'], $use_tax)
                    );
                    break;
                }
            }

            if (!$product['has_exchange']) {
                if (isset($product['customized_product_quantity'])) {
                    $quantity = (int)$product['product_quantity'] - (int)$product['customized_product_quantity'];
                } else {
                    $quantity = (int)$product['product_quantity'];
                }
                $price = $product['unit_price_'.$use_tax];
                $product['exchange'] = array(
                    'name' => Product::getProductName(
                        (int)$product['product_id'],
                        null,
                        $this->context->cookie->id_lang
                    ),
                    'price' => Tools::convertPrice($price, $this->context->currency),
                    'formatted_price' => Tools::displayPrice(
                        Tools::convertPrice($price, $this->context->currency),
                        $this->context->currency
                    ),
                    'id_product' => (int)$product['product_id'],
                    'id_product_attribute' => (int)$product['product_attribute_id'],
                    'reserve_stock' => 0,
                    'tax_rate' => $product['tax_rate'],
                    'quantity' => $quantity,
                    'return_quantity' => $quantity,
                    'combinations' => self::getProductAttributes((int)$product['id_product'], $use_tax)
                );
            }

            $product['exchange']['available_quantity'] = StockAvailable::getQuantityAvailableByProduct(
                $product['exchange']['id_product'],
                $product['exchange']['id_product_attribute']
            );

            if ($product['image'] != null) {
                $name = 'product_mini_'.(int)$product['product_id'];
                $name .= (isset($product['product_attribute_id']) ? '_'.(int)$product['product_attribute_id'] : '');
                $name .= '.jpg';
                $product['image_tag'] = ImageManager::thumbnail(
                    _PS_IMG_DIR_.'p/'.$product['image']->getExistingImgPath().'.jpg',
                    $name,
                    45,
                    'jpg'
                );
                if (file_exists(_PS_TMP_IMG_DIR_.$name)) {
                    $product['image_size'] = getimagesize(_PS_TMP_IMG_DIR_.$name);
                } else {
                    $product['image_size'] = false;
                }
            }
        }

        ksort($products);
        return $products;
    }

    public function ajaxProcessSearchProducts()
    {
        $products = Product::searchByName(
            (int)$this->context->language->id,
            Tools::getValue('product_search')
        );
        if ($products) {
            $use_tax = (bool)Tools::getValue('use_tax') ? 'tax_incl' : 'tax_excl';
            foreach ($products as &$product) {
                $price = $product['price_'.$use_tax];
                $product['formatted_price'] = Tools::displayPrice(
                    Tools::convertPrice($price, $this->context->currency),
                    $this->context->currency
                );
                $product['price'] = Tools::convertPrice($price, $this->context->currency);
                $product['combinations'] = self::getProductAttributes((int)$product['id_product'], $use_tax);
            }

            $array = array(
                'products' => $products,
                'found' => true
            );
        } else {
            $array = array('found' => false, 'notfound' => $this->l('No product has been found.'));
        }
        $this->content = trim(Tools::jsonEncode($array));
    }

    public static function getProductAttributes($id_product, $use_tax)
    {
        $context = Context::getContext();

        if (!array_key_exists($id_product, self::$cache_product_attributes)) {
            $combinations = array();
            $product_obj = new Product((int)$id_product, false, (int)$context->language->id);
            $attributes = $product_obj->getAttributesGroups((int)$context->language->id);

            foreach ($attributes as $attribute) {
                $ipa = $attribute['id_product_attribute'];
                if (!isset($combinations[$ipa]['attributes'])) {
                    $combinations[$ipa]['attributes'] = '';
                }
                $combinations[$ipa]['attributes'] .= $attribute['attribute_name'].' - ';
                $combinations[$ipa]['id_product_attribute'] = $ipa;
                $combinations[$ipa]['default_on'] = $attribute['default_on'];
                $combinations[$ipa]['quantity'] = $attribute['quantity'];
                if (!isset($combinations[$ipa]['price'])) {
                    $price = Product::getPriceStatic((int)$id_product, $use_tax == 'tax_incl' ? true : false, $ipa);
                    $combinations[$ipa]['formatted_price'] = Tools::displayPrice(
                        Tools::convertPrice($price, $context->currency),
                        $context->currency
                    );
                    $combinations[$ipa]['price'] = Tools::convertPrice($price, $context->currency);
                }
            }

            foreach ($combinations as &$combination) {
                $combination['attributes'] = rtrim($combination['attributes'], ' - ');
            }

            self::$cache_product_attributes[$id_product] = $combinations;
        }
        return self::$cache_product_attributes[$id_product];
    }

    protected function _childValidation()
    {
        if (!is_array(Tools::getValue('orderDetailBox')) || !count(Tools::getValue('orderDetailBox'))) {
            $this->errors[] = $this->l('No product has been selected for exchange.');
        }
    }

    protected function afterAdd($object)
    {
        $this->afterSave($object);
        if (!count($this->errors)) {
            $this->redirect_after = self::$currentIndex.'&'.$this->identifier.'='.$this->object->id;
            $this->redirect_after .= '&conf=3&update'.$this->table.'&token='.$this->token;
        }
    }

    protected function afterUpdate($object)
    {
        $this->afterSave($object);
        if (!count($this->errors)) {
            $this->redirect_after = self::$currentIndex.'&'.$this->identifier.'='.$this->object->id;
            $this->redirect_after .= '&conf=4&update'.$this->table.'&token='.$this->token;
        }
    }

    protected function afterSave($object)
    {
        $exchanges = Tools::getValue('orderDetailBox');
        $quantities = Tools::getValue('product_quantity');
        $return_quantities = Tools::getValue('return_quantity');
        $products = Tools::getValue('id_product');
        $products_attributes = Tools::getValue('id_product_attribute');
        $reserves_stocks = Tools::getValue('reserve_stock');
        $order_from = new Order((int)$object->id_order_from);

        $products_list = array();
        $total_discounts = 0;
        foreach ($exchanges as $id_order_detail) {
            $exchange_detail = $object->findDetail((int)$id_order_detail);
            if (isset($exchange_detail['id_orderexchange_detail']) &&
                (int)$exchange_detail['id_orderexchange_detail'] &&
                (
                    $exchange_detail['product_id'] != (int)$products[$id_order_detail] ||
                    $exchange_detail['product_attribute_id'] != (int)$products_attributes[$id_order_detail]
                )) {
                $object->liberateStock($exchange_detail);
            }
            $exchange_detail['id_order_detail'] = (int)$id_order_detail;
            $exchange_detail['product_id'] = (int)$products[$id_order_detail];
            $exchange_detail['product_attribute_id'] = (int)$products_attributes[$id_order_detail];
            $exchange_detail['return_quantity'] = (int)$return_quantities[$id_order_detail];
            $exchange_detail['product_quantity'] = (int)$quantities[$id_order_detail];
            $exchange_detail['reserve_stock'] = (int)$reserves_stocks[$id_order_detail];

            $object->reserveStock($exchange_detail);
            $object->saveDetail($exchange_detail);

            $context = Context::getContext();
            if ($context->shop->id != $order_from->id_shop) {
                $context->shop = new Shop((int)$order_from->id_shop);
            }

            $exchange_detail['order_detail'] = new OrderDetail((int)$id_order_detail, null, $context);
            $products_list[] = $exchange_detail;
            $total_discounts +=
                $exchange_detail['order_detail']->unit_price_tax_incl *
                abs($exchange_detail['return_quantity']);
        }

        $sql = 'DELETE FROM '._DB_PREFIX_.'orderexchange_detail WHERE id_orderexchange='.(int)$object->id;
        if (count($exchanges)) {
            $sql .= ' AND id_order_detail NOT IN('.implode(',', $exchanges).')';
        }
        Db::getInstance()->execute($sql);

        $was_active = $object->active;
        $object->active = 1;
        if (Tools::isSubmit('submitAddorderexchangeAndWait')) {
            $object->active = 0;
        }

        $this->loadCart($object);
        $object->id_carrier = (int)Tools::getValue('delivery_option');
        if ($object->shipping_cost) {
            $object->total_shipping_tax_excl = (float)$this->context->cart->getPackageShippingCost(
                (int)$object->id_carrier,
                false,
                null,
                null
            );
            $object->total_shipping_tax_incl = (float)$this->context->cart->getPackageShippingCost(
                (int)$object->id_carrier,
                true,
                null,
                null
            );
        } else {
            $object->total_shipping_tax_excl = $object->total_shipping_tax_incl = 0;
        }

        $object->total_products_tax_incl = (float)$this->context->cart->getOrderTotal(true, Cart::ONLY_PRODUCTS);
        $object->total_products_tax_excl = (float)$this->context->cart->getOrderTotal(false, Cart::ONLY_PRODUCTS);

        $base_total_tax_inc = (float)$this->context->cart->getOrderTotal(true);
        $base_total_tax_exc = (float)$this->context->cart->getOrderTotal(false);

        $total_tax = $base_total_tax_inc - $base_total_tax_exc;

        $object->total_tax = $total_tax;
        $object->total_discounts = $total_discounts;

        if ($object->total_discounts >= $object->total_products_tax_incl + $object->total_shipping_tax_incl ||
        $object->total_discounts <= $object->total_products_tax_incl) {
            $object->balance = (float)Tools::ps_round(
                $object->total_products_tax_incl + $object->total_shipping_tax_incl - $total_discounts,
                2
            );
        } else {
            $object->balance = (float)Tools::ps_round($object->total_shipping_tax_incl, 2);
        }
        $object->update();

        // Soliberte Compliant ;)
        if (Module::isInstalled('soliberte')) {
            $soliberte = Module::getInstanceByName('soliberte');
            if ($soliberte->active) {

                $soliberte_conf_key = array(
                    'SOLIBERTE_DOM_ID',
                    'SOLIBERTE_RDV_ID',
                    'SOLIBERTE_BPR_ID',
                    'SOLIBERTE_CIT_ID',
                    'SOLIBERTE_A2P_ID'
                );
                $id_carrier_so = Configuration::getMultiple($soliberte_conf_key);

                if (in_array($object->id_carrier, $id_carrier_so)) {
                    if (Validate::isLoadedObject($order_from)) {
                        $so_delivery = new SoColissimoDelivery();
                        $so_delivery->id_cart = (int)$order_from->id_cart;
                        $so_delivery->id_customer = (int)$order_from->id_customer;
                        $so_delivery->loadDelivery();
                        $so_delivery->id_cart = (int)$this->context->cart->id;
                        $so_delivery->saveDelivery();
                    }
                } else {
                    $so_delivery = new SoColissimoDelivery();
                    $so_delivery->removeDelivery((int)$this->context->cart->id);
                }
            }
        }

        // SoFlexibilite Compliant ;)
        if (Module::isInstalled('soflexibilite')) {
            $soflexibilite = Module::getInstanceByName('soflexibilite');
            if ($soflexibilite->active) {

                $soflexibilite_conf_key = array(
                    'SOFLEXIBILITE_DOM_ID',
                    'SOFLEXIBILITE_RDV_ID',
                    'SOFLEXIBILITE_BPR_ID',
                    'SOFLEXIBILITE_A2P_ID',
                    'SOFLEXIBILITE_CIT_ID'
                );
                $id_carrier_so = Configuration::getMultiple($soflexibilite_conf_key, null, null, null);

                if (in_array($object->id_carrier, $id_carrier_so)) {
                    if (Validate::isLoadedObject($order_from)) {
                        $so_delivery = new SoFlexibiliteDelivery();
                        $so_delivery->id_cart = (int)$order_from->id_cart;
                        $so_delivery->id_customer = (int)$order_from->id_customer;
                        $so_delivery->loadDelivery();
                        $so_delivery->id_cart = (int)$this->context->cart->id;
                        $so_delivery->saveDelivery();
                    }
                } else {
                    $so_delivery = new SoColissimoFlexibiliteDelivery();
                    $so_delivery->removeDelivery((int)$this->context->cart->id);
                }
            }
        }

        // MondialRelay Compliant ;)
        if (Module::isInstalled('mondialrelay')) {
            $mondialrelay = Module::getInstanceByName('mondialrelay');
            if ($mondialrelay->active) {
                if (Mondialrelay::isMondialRelayCarrier($object->id_carrier)) {
                    if (Validate::isLoadedObject($order_from)) {
                        $relay = Db::getInstance()->getRow(
                            'SELECT * FROM '._DB_PREFIX_.'mr_selected
                            WHERE id_cart='.$order_from->id_cart
                        );
                        if ((int)$relay['id_mr_selected']) {
                            unset(
                                $relay['id_mr_selected'],
                                $relay['id_order'],
                                $relay['url_suivi'],
                                $relay['url_etiquette'],
                                $relay['exp_number']
                            );
                            $relay['id_cart'] = (int)$this->context->cart->id;
                            $relay['MR_poids'] = (float)$this->context->cart->getTotalWeight();
                            $relay['date_add'] = $relay['date_upd'] = date('Y-m-d H:i:s');
                            Db::getInstance()->execute(
                                'DELETE FROM '._DB_PREFIX.'mr_selected
                                WHERE id_cart='.(int)$this->context->cart->id
                            );
                            Db::getInstance()->insert('mr_selected', $relay, true);
                        }
                    }
                } else {
                    Db::getInstance()->execute(
                        'DELETE FROM '._DB_PREFIX.'mr_selected
                        WHERE id_cart='.(int)$this->context->cart->id
                    );
                }
            }
        }

        // Chronopost relay Compliant ;)
        if (Module::isInstalled('chronopost')) {
            $chronopost = Module::getInstanceByName('chronopost');
            if ($chronopost->active) {
                if (Chronopost::isChrono($object->id_carrier)) {
                    if (Validate::isLoadedObject($order_from)) {
                        $relay = Db::getInstance()->getRow(
                            'SELECT * FROM '._DB_PREFIX.'chrono_cart_relais
                            WHERE id_cart='.$order_from->id_cart
                        );
                        if ((int)$relay['id_pr']) {
                            $relay['id_cart'] = (int)$this->context->cart->id;
                            Db::getInstance()->execute(
                                'DELETE FROM '._DB_PREFIX.'chrono_cart_relais
                                WHERE id_cart='.(int)$this->context->cart->id
                            );
                            Db::getInstance()->insert('chrono_cart_relais', $relay, true);
                        }
                    }
                } else {
                    Db::getInstance()->execute(
                        'DELETE FROM '._DB_PREFIX.'chrono_cart_relais
                        WHERE id_cart='.(int)$this->context->cart->id
                    );
                }
            }
        }

        if (!$was_active && $object->active) {
            $this->cleanCart();

            if (Configuration::get('PS_ORDER_RETURN')) {
                foreach ($products_list as $product) {
                    $qty_cancel_product = abs($product['return_quantity']);
                    if (!$qty_cancel_product) {
                        $this->errors[] = $this->l('No quantity has been selected for this product.');
                    }
                    if (($product['order_detail']->product_quantity -
                            $product['order_detail']->product_quantity_refunded -
                            $product['order_detail']->product_quantity_return
                        ) < $qty_cancel_product) {
                        $this->errors[] = $this->l('An invalid quantity was selected for this product.');
                    }
                    if (
                        !$order_from->hasBeenDelivered() ||
                        ($order_from->hasBeenDelivered() && $object->reinject_stock) &&
                        $qty_cancel_product > 0
                    ) {
                        $this->reinjectQuantity($product['order_detail'], $qty_cancel_product);
                    }

                    if (!$order_from->deleteProduct($order_from, $product['order_detail'], $qty_cancel_product)) {
                        $this->errors[] = $this->l('An error occurred while attempting to delete the product.').
                        ' <span class="bold">'.$product['order_detail']->product_name.'</span>';
                    }

                    $order_carrier = new OrderCarrier((int)$order_from->getIdOrderCarrier());
                    if (Validate::isLoadedObject($order_carrier)) {
                        $order_carrier->weight = (float)$order_from->getTotalWeight();
                        if ($order_carrier->update()) {
                            $order_from->weight = sprintf(
                                '%.3f '.Configuration::get('PS_WEIGHT_UNIT'),
                                $order_carrier->weight
                            );
                        }
                    }

                    if (
                        Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT') &&
                        StockAvailable::dependsOnStock($product['order_detail']->product_id)
                    ) {
                        StockAvailable::synchronize($product['order_detail']->product_id);
                    }
                    Hook::exec(
                        'actionProductCancel',
                        array(
                            'order' => $order_from,
                            'id_order_detail' => (int)$product['id_order_detail']
                        ),
                        null,
                        false,
                        true,
                        false,
                        $order_from->id_shop
                    );
                }
            }

            if ($object->create_order_slip && !count($this->errors)) {
                $product_list = array();
                $quantities_list = array();

                if (version_compare(_PS_VERSION_, '1.6.0.11', '>=')) {
                    foreach ($products_list as $product) {
                        $product_list[$product['id_order_detail']] = array(
                            'id_order_detail' => $product['id_order_detail'],
                            'quantity' => abs($product['return_quantity']),
                            'unit_price' => $product['order_detail']->unit_price_tax_excl,
                            'amount' => $product['order_detail']->unit_price_tax_incl*abs($product['return_quantity']),
                        );
                    }

                    if (!OrderSlip::create($order_from, $product_list, false, null, false)) {
                        $this->errors[] = $this->l('A credit slip cannot be generated. ');
                    }
                } else {
                    foreach ($products_list as $product) {
                        $product_list[] = $product['id_order_detail'];
                        $quantities_list[$product['id_order_detail']] = abs($product['return_quantity']);
                    }
                    if (!OrderSlip::createOrderSlip($order_from, $product_list, $quantities_list)) {
                        $this->errors[] = $this->l('A credit slip cannot be generated. ');
                    }
                }

                $object->id_order_slip = self::getLastOrderSlip($order_from->id);
            }

            $cart_rule = new CartRule();
            $languages = Language::getLanguages($order);

            $free_shipping = 0;
            $reduction_amount = $total_discounts;
            if ($object->shipping_cost == 0) {
                $free_shipping = 1;
            } else {
                if ($object->total_discounts >= $object->total_products_tax_incl + $object->total_shipping_tax_incl) {
                    $free_shipping = 1;
                    $reduction_amount = $total_discounts - $object->total_shipping_tax_incl;
                }
            }

            $cart_rule->description = sprintf($this->l('Credit card slip for order #%d'), $order_from->id);
            $cart_rule->code = 'V0C'.(int)$order_from->id_customer.'O'.(int)$order_from->id;
            $cart_rule->quantity = 1;
            $cart_rule->quantity_per_user = 1;
            $cart_rule->id_customer = $order_from->id_customer;
            $cart_rule->date_from = date('Y-m-d H:i:s');
            $cart_rule->date_to = date('Y-m-d H:i:s', time() + (3600 * 24));
            $cart_rule->active = 1;
            $cart_rule->free_shipping = $free_shipping;

            foreach ($languages as $language) {
                $cart_rule->name[$language['id_lang']] = 'V0C'.(int)$order_from->id_customer.'O'.(int)$order_from->id;
            }
            $cart_rule->reduction_amount = $reduction_amount;
            $cart_rule->reduction_tax = true;
            $cart_rule->minimum_amount_currency = $order_from->id_currency;
            $cart_rule->reduction_currency = $order_from->id_currency;

            if (!$cart_rule->add()) {
                $this->errors[] = $this->l('You cannot generate a voucher.');
            }
            $object->id_cart_rule = (int)$cart_rule->id;

            if ($err = $cart_rule->checkValidity($this->context)) {
                $this->errors[] = $err;
            }

            if (!count($this->errors)) {
                if (!$this->context->cart->addCartRule((int)$cart_rule->id)) {
                    $this->errors[] = $this->l('Can\'t add the voucher.');
                }
            }

            foreach ($products_list as $product) {
                $object->liberateStock($product);
            }

            // Create immediatly order
            if ($object->order_creation == 1) {
                if (($module_name = Tools::getValue('payment_module_name')) &&
                    ($id_order_state = Tools::getValue('id_order_state'))
                    && Validate::isModuleName($module_name)) {
                    if ($object->balance <= 0) {
                        $payment_module = new ExchangeOrder();
                    } else {
                        if (!Configuration::get('PS_CATALOG_MODE')) {
                            $payment_module = Module::getInstanceByName($module_name);
                        } else {
                            $payment_module = new BoOrder();
                        }
                    }

                    $this->context->currency = new Currency((int)$this->context->cart->id_currency);
                    $this->context->customer = new Customer((int)$this->context->cart->id_customer);

                    $bad_delivery = (bool)!Address::isCountryActiveById((int)$this->context->cart->id_address_delivery);
                    if (
                        $bad_delivery
                        || !Address::isCountryActiveById((int)$this->context->cart->id_address_invoice)
                    ) {
                        if ($bad_delivery) {
                            $this->errors[] = $this->l('This delivery address country is not active.');
                        } else {
                            $this->errors[] = $this->l('This invoice address country is not active.');
                        }
                    } else {
                        $employee = new Employee((int)Context::getContext()->cookie->id_employee);
                        $payment_module->validateOrder(
                            (int)$this->context->cart->id,
                            (int)$id_order_state,
                            $this->context->cart->getOrderTotal(true, Cart::BOTH),
                            $payment_module->displayName,
                            $this->l('Manual order -- Employee:').' '.
                            Tools::substr($employee->firstname, 0, 1).'. '.$employee->lastname,
                            array(),
                            null,
                            false,
                            $this->context->cart->secure_key
                        );
                        $object->id_order_to = (int)$payment_module->currentOrder;

                        // MondialRelay Compliant ;)
                        if (Module::isInstalled('mondialrelay')) {
                            $mondialrelay = Module::getInstanceByName('mondialrelay');
                            if ($mondialrelay->active && Mondialrelay::isMondialRelayCarrier($object->id_carrier)) {
                                $data = array('id_order' => $object->id_order_to);
                                Db::getInstance()->update(
                                    'mr_selected',
                                    $data,
                                    'id_cart='.(int)$this->context->cart->id,
                                    1
                                );
                            }
                        }

                    }
                }
            } else {
                $id_cart = (int)$this->context->cart->id;
                $order_link_params = 'step=3&recover_cart='.$id_cart;
                $order_link_params .= '&token_cart='.md5(_COOKIE_KEY_.'recover_cart_'.$id_cart);
                $mail_vars = array(
                    '{order_link}' => $this->context->link->getPageLink(
                        'order',
                        false,
                        (int)$this->context->cart->id_lang,
                        $order_link_params
                    ),
                    '{firstname}' => $this->context->customer->firstname,
                    '{lastname}' => $this->context->customer->lastname
                );
                if (!Mail::Send(
                    (int)$this->context->cart->id_lang,
                    'backoffice_order',
                    Mail::l(
                        'Process the payment of your order',
                        (int)$this->context->cart->id_lang
                    ),
                    $mail_vars,
                    $this->context->customer->email,
                    $this->context->customer->firstname.' '.$this->context->customer->lastname,
                    null,
                    null,
                    null,
                    null,
                    _PS_MAIL_DIR_,
                    true,
                    $this->context->cart->id_shop
                )) {
                    $this->errors[] = $this->l('The email cannot be send to your customer.');
                }
            }
            $object->update();
        }
    }

    public static function getLastOrderSlip($id_order)
    {
        return (int)Db::getInstance()->getValue(
            'SELECT id_order_slip
            FROM '._DB_PREFIX_.'order_slip
            WHERE id_order = '.(int)$id_order.' ORDER BY id_order_slip DESC'
        );
    }

    protected function reinjectQuantity($order_detail, $qty_cancel_product, $delete = false)
    {
        $reinjectable_quantity = (int)$order_detail->product_quantity - (int)$order_detail->product_quantity_reinjected;
        $quantity_to_reinject =
            $qty_cancel_product > $reinjectable_quantity ?
            $reinjectable_quantity : $qty_cancel_product;

        $product = new Product(
            $order_detail->product_id,
            false,
            (int)$this->context->language->id,
            (int)$order_detail->id_shop
        );

        if (
            Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT') &&
            $product->advanced_stock_management &&
            $order_detail->id_warehouse != 0
        ) {
            $manager = StockManagerFactory::getManager();
            $movements = StockMvt::getNegativeStockMvts(
                $order_detail->id_order,
                $order_detail->product_id,
                $order_detail->product_attribute_id,
                $quantity_to_reinject
            );
            $left_to_reinject = $quantity_to_reinject;

            foreach ($movements as $movement) {
                if ($left_to_reinject > $movement['physical_quantity']) {
                    $quantity_to_reinject = $movement['physical_quantity'];
                }

                $left_to_reinject -= $quantity_to_reinject;
                if (Pack::isPack((int)$product->id)) {
                    // Gets items
                    if ($product->pack_stock_type == 1 || $product->pack_stock_type == 2 ||
                    ($product->pack_stock_type == 3 && Configuration::get('PS_PACK_STOCK_TYPE') > 0)) {
                        $products_pack = Pack::getItems((int)$product->id, (int)Configuration::get('PS_LANG_DEFAULT'));
                        // Foreach item
                        foreach ($products_pack as $product_pack) {
                            if ($product_pack->advanced_stock_management == 1) {
                                $manager->addProduct(
                                    $product_pack->id,
                                    $product_pack->id_pack_product_attribute,
                                    new Warehouse($movement['id_warehouse']),
                                    $product_pack->pack_quantity * $quantity_to_reinject,
                                    null,
                                    $movement['price_te'],
                                    true
                                );
                            }
                        }
                    }
                    if (
                        $product->pack_stock_type == 0 ||
                        $product->pack_stock_type == 2 ||
                        (
                            $product->pack_stock_type == 3 &&
                            (
                                Configuration::get('PS_PACK_STOCK_TYPE') == 0 ||
                                Configuration::get('PS_PACK_STOCK_TYPE') == 2
                            )
                        )
                    ) {
                        $manager->addProduct(
                            $order_detail->product_id,
                            $order_detail->product_attribute_id,
                            new Warehouse($movement['id_warehouse']),
                            $quantity_to_reinject,
                            null,
                            $movement['price_te'],
                            true
                        );
                    }
                } else {
                    $manager->addProduct(
                        $order_detail->product_id,
                        $order_detail->product_attribute_id,
                        new Warehouse($movement['id_warehouse']),
                        $quantity_to_reinject,
                        null,
                        $movement['price_te'],
                        true
                    );
                }
            }

            $id_product = $order_detail->product_id;
            if ($delete) {
                $order_detail->delete();
            }
            StockAvailable::synchronize($id_product);
        } elseif ($order_detail->id_warehouse == 0) {
            StockAvailable::updateQuantity(
                $order_detail->product_id,
                $order_detail->product_attribute_id,
                $quantity_to_reinject,
                $order_detail->id_shop
            );

            if ($delete) {
                $order_detail->delete();
            }
        } else {
            $this->errors[] = $this->l('This product cannot be re-stocked.');
        }
    }

    public function loadCart($object)
    {
        if (Validate::isLoadedObject($this->context->cart)) {
            return true;
        }
        $cart = new Cart((int)$object->id_cart);
        if (Validate::isLoadedObject($cart)) {
            $this->context->cart = $cart;
            $this->context->customer = new Customer($cart->id_customer);
            return true;
        }
        return false;
    }

    protected function l($string, $class = null, $addslashes = false, $htmlentities = true)
    {
        return $this->module->l($string, 'adminorderexchange');
    }
}
