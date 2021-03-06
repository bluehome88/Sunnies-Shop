<?php
/**
 * This file is part of the official Afterpay module for PrestaShop.
 *
 * @author    Afterpay <integrations@afterpay.com>
 * @copyright 2020 Afterpay
 * @license   proprietary
 */

define('_PS_AFTERPAY_DIR', _PS_MODULE_DIR_. 'afterpayofficial');

require _PS_AFTERPAY_DIR.'/vendor/autoload.php';

if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * Class Afterpayofficial
 */
class Afterpayofficial extends PaymentModule
{
    /** Product Name */
    const PRODUCT_PAYMENT_NAME = "Afterpay";

    /**
     * Available currency
     */
    const MODULE_NAME = 'afterpayofficial';

    /**
     * Available currency
     */
    const SIMULATOR_IS_ENABLED = true;

    /**
     * JS CDN URL
     */
    const AFTERPAY_JS_CDN_URL = 'https://js.afterpay.com/afterpay-1.x.js';

    /**
     * @var string
     */
    public $url = 'https://www.afterpay.com';

    /**
     * @var bool
     */
    public $bootstrap = true;

    /** @var string $language */
    public $language;

    /** @var string $description */
    public $description;

    /**
     * Default available countries for the different operational regions
     *
     * @var array
     */
    public $defaultCountriesPerRegion = array(
        'AU' => '["AU", "EN"]',
        'CA' => '["CA", "EN"]',
        'ES' => '["ES", "IT", "FR"]',
        'GB' => '["GB", "EN"]',
        'NZ' => '["NZ", "EN"]',
        'US' => '["US", "EN"]',
    );

    /**
     * Default locale iso country codes per country
     *
     * @var array
     */
    public $defaultIsoCountryCodePerCountry = array(
        'AU' => 'en_AU',
        'CA' => 'en_CA',
        'ES' => 'es_ES',
        'GB' => 'en_GB',
        'NZ' => 'en_NZ',
        'US' => 'en_US',
    );

    /**
     * allowed currency per region
     *
     * @var array
     */
    public $allowedCurrencyPerRegion = array(
        'AU' => 'AUD',
        'CA' => 'CAD',
        'ES' => 'EUR',
        'GB' => 'GBP',
        'NZ' => 'NZD',
        'US' => 'USD',
    );

    /**
     * Default currency per region
     *
     * @var array
     */
    public $defaultLanguagePerCurrency = array(
        'AUD' => 'AU',
        'CAD' => 'CA',
        'GBP' => 'GB',
        'NZD' => 'NZ',
        'USD' => 'US',
    );

    /**
     * Default API Version per region
     *
     * @var array
     */
    public $defaultApiVersionPerRegion = array(
        'AU' => 'v2',
        'CA' => 'v2',
        'ES' => 'v1',
        'GB' => 'v2',
        'NZ' => 'v2',
        'US' => 'v2',
    );

    /**
     * link of terms and conditions per region
     *
     * @var array
     */
    public $termsLinkPerRegion = array(
        'AU' => 'https://www.afterpay.com/en-AU/terms-of-service',
        'CA' => 'https://www.afterpay.com/en-CA/instalment-agreement',
        'NZ' => 'https://www.afterpay.com/en-NZ/terms-of-service',
        'US' => 'https://www.afterpay.com/installment-agreement',
    );

    /**
     * @var null $shippingAddress
     */
    protected $shippingAddress = null;

    /**
     * @var null $billingAddress
     */
    protected $billingAddress = null;

    /**
     * @var array $allowedCountries
     */
    protected $allowedCountries = array();

    /**
     * Afterpay constructor.
     *
     * Define the module main properties so that prestashop understands what are the module requirements
     * and how to manage the module.
     *
     */
    public function __construct()
    {
        $this->name = 'afterpayofficial';
        $this->tab = 'payments_gateways';
        $this->version = '1.1.2';
        $this->author = $this->l('Afterpay');
        $this->currencies = true;
        $this->currencies_mode = 'checkbox';
        $this->module_key = '6fe834f17783ebe4405a9844f0fb9096';
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        $this->displayName = $this->l('Afterpay Payment Gateway');
        $this->description = $this->l('Buy now, pay later. Always interest-free. Reach new customers, ');
        $this->description .= $this->l('increase your conversion rate, recurrency and average order value ofering ');
        $this->description .= $this->l('interest-free installments in your eCommerce.');
        $this->currency = 'EUR';
        $this->currencySymbol = '???';
        $context = Context::getContext();
        if (isset($context->currency)) {
            $this->currency = $context->currency->iso_code;
            $this->currencySymbol = $context->currency->sign;
        }

        parent::__construct();
    }

    /**
     * Configure the variables for Afterpay payment method.
     *
     * @return bool
     */
    public function install()
    {
        if (!extension_loaded('curl')) {
            $this->_errors[] =
                $this->l('You have to enable the cURL extension on your server to install this module');
            return false;
        }
        if (!version_compare(phpversion(), '5.6.0', '>=')) {
            $this->_errors[] = $this->l('The PHP version bellow 5.3.0 is not supported');
            return false;
        }

        $sql_file = dirname(__FILE__) . '/sql/install.sql';
        $this->loadSQLFile($sql_file);

        Configuration::updateValue('AFTERPAY_IS_ENABLED', 0);
        Configuration::updateValue('AFTERPAY_REGION', 'ES');
        Configuration::updateValue('AFTERPAY_PUBLIC_KEY', '');
        Configuration::updateValue('AFTERPAY_SECRET_KEY', '');
        Configuration::updateValue('AFTERPAY_PRODUCTION_SECRET_KEY', '');
        Configuration::updateValue('AFTERPAY_ENVIRONMENT', 1);
        Configuration::updateValue('AFTERPAY_MIN_AMOUNT', null);
        Configuration::updateValue('AFTERPAY_MAX_AMOUNT', null);
        Configuration::updateValue('AFTERPAY_RESTRICTED_CATEGORIES', '');
        Configuration::updateValue('AFTERPAY_ALLOWED_COUNTRIES', '["ES","FR","IT","GB"]');
        Configuration::updateValue('AFTERPAY_CSS_SELECTOR', 'default');
        Configuration::updateValue('AFTERPAY_CSS_SELECTOR_CART', 'default');
        Configuration::updateValue('AFTERPAY_URL_OK', '');
        Configuration::updateValue('AFTERPAY_URL_KO', '');
        Configuration::updateValue('AFTERPAY_LOGS', '');

        $return =  (parent::install()
            && $this->registerHook('paymentOptions')
            && $this->registerHook('displayProductPriceBlock')
            && $this->registerHook('displayOrderConfirmation')
            && $this->registerHook('displayWrapperTop')
            && $this->registerHook('displayExpressCheckout')
            && $this->registerHook('actionOrderStatusUpdate')
            && $this->registerHook('actionOrderSlipAdd')
            && $this->registerHook('actionProductCancel')
            && $this->registerHook('header')
        );

        if ($return && _PS_VERSION_ < "1.7") {
            $this->registerHook('payment');
        }
        if ($return && version_compare(_PS_VERSION_, '1.6.1', 'lt')) {
            $this->registerHook('displayPaymentTop');
        }

        return $return;
    }

    /**
     * Remove the production private api key and remove the files
     *
     * @return bool
     */
    public function uninstall()
    {
        Configuration::deleteByName('AFTERPAY_IS_ENABLED');
        Configuration::deleteByName('AFTERPAY_PUBLIC_KEY');
        Configuration::deleteByName('AFTERPAY_SECRET_KEY');
        Configuration::deleteByName('AFTERPAY_PRODUCTION_SECRET_KEY');
        Configuration::deleteByName('AFTERPAY_ENVIRONMENT');
        Configuration::deleteByName('AFTERPAY_REGION');
        Configuration::deleteByName('AFTERPAY_MIN_AMOUNT');
        Configuration::deleteByName('AFTERPAY_MAX_AMOUNT');
        Configuration::deleteByName('AFTERPAY_RESTRICTED_CATEGORIES');
        Configuration::deleteByName('AFTERPAY_ALLOWED_COUNTRIES');
        Configuration::deleteByName('AFTERPAY_CSS_SELECTOR');
        Configuration::deleteByName('AFTERPAY_CSS_SELECTOR_CART');
        Configuration::deleteByName('AFTERPAY_URL_OK');
        Configuration::deleteByName('AFTERPAY_URL_KO');
        Configuration::deleteByName('AFTERPAY_LOGS');

        $sql_file = dirname(__FILE__).'/sql/uninstall.sql';
        $this->loadSQLFile($sql_file);

        return parent::uninstall();
    }

    /**
     * @param $sql_file
     * @return bool
     */
    public function loadSQLFile($sql_file)
    {
        $sql_content = Tools::file_get_contents($sql_file);
        $sql_content = str_replace('PREFIX_', _DB_PREFIX_, $sql_content);
        $sql_requests = preg_split("/;\s*[\r\n]+/", $sql_content);

        $result = true;
        foreach ($sql_requests as $request) {
            if (!empty($request)) {
                $result &= Db::getInstance()->execute(trim($request));
            }
        }

        return $result;
    }

    /**
     * Check amount of order > minAmount
     * Check valid currency
     * Check API variables are set
     *
     * @param string $product
     * @return bool
     */
    public function isPaymentMethodAvailable()
    {
        $cart = $this->context->cart;
        $totalAmount = $cart->getOrderTotal(true, Cart::BOTH);
        $isEnabled = Configuration::get('AFTERPAY_IS_ENABLED');
        $displayMinAmount = Configuration::get('AFTERPAY_MIN_AMOUNT');
        $displayMaxAmount = Configuration::get('AFTERPAY_MAX_AMOUNT');
        $publicKey = Configuration::get('AFTERPAY_PUBLIC_KEY');
        $secretKey = Configuration::get('AFTERPAY_SECRET_KEY');

        $categoryRestriction = $this->isCartRestricted($this->context->cart);
        $currencyRestriction = $this->isRestrictedByLangOrCurrency();
        return (
            $isEnabled &&
            $totalAmount >= $displayMinAmount &&
            $totalAmount <= $displayMaxAmount &&
            !$currencyRestriction &&
            !$categoryRestriction &&
            $publicKey &&
            $secretKey
        );
    }

    /**
     * @param Cart $cart
     *
     * @return array
     * @throws Exception
     */
    private function getButtonTemplateVars(Cart $cart)
    {
        $currency = new Currency($cart->id_currency);

        return array(
            'button' => '#payment_button',
            'currency_iso' => $currency->iso_code,
            'cart_total' => $cart->getOrderTotal(),
        );
    }

    /**
     * Header hook
     */
    public function hookHeader()
    {
        if (version_compare(_PS_VERSION_, '1.7', 'lt')) {
            $template = $this->hookDisplayWrapperTop();
            if (!empty($template)) {
                echo($template);
            }
        }
        if (Context::getContext()->controller->php_self === 'product') {
            try {
                echo '<!-- APVersion:'. $this->version.
                    ' PS:'._PS_VERSION_.
                    ' Env:'.Configuration::get('AFTERPAY_ENVIRONMENT').
                    ' MId:'.Configuration::get('AFTERPAY_PUBLIC_KEY').
                    ' Region:'.Configuration::get('AFTERPAY_REGION').
                    ' Lang:'.$this->getCurrentLanguageCode().
                    ' Currency:'.$this->currency.
                    ' IsoCode:'.$this->getIsoCountryCode().
                    ' Enabled:'.Configuration::get('AFTERPAY_IS_ENABLED').
                    ' A_Countries:'.Configuration::get('AF  TERPAY_ALLOWED_COUNTRIES').
                    ' R_Cat:'.(string)Configuration::get('AFTERPAY_RESTRICTED_CATEGORIES').
                    ' -->';
            } catch (\Exception $exception) {
                // Continue
            }
        }
    }

    /**
     * @return array
     * @throws Exception
     */
    public function hookPaymentOptions()
    {
        /** @var Cart $cart */
        $cart = $this->context->cart;
        $this->shippingAddress = new Address($cart->id_address_delivery);
        $this->billingAddress = new Address($cart->id_address_invoice);
        $totalAmount = Afterpayofficial::parseAmount($cart->getOrderTotal(true, Cart::BOTH));

        $link = $this->context->link;

        $return = array();
        $this->context->smarty->assign($this->getButtonTemplateVars($cart));
        $templateConfigs = array();
        if ($this->isPaymentMethodAvailable()) {
            $amountWithCurrency = $this->currencySymbol. Afterpayofficial::parseAmount($totalAmount/4);
            $checkoutText = $this->l('Or 4 interest-free payments of') . ' ' . $amountWithCurrency . ' ';
            $checkoutText .= $this->l('with');
            if ($this->isOPC()) {
                $checkoutText = $this->l('4 interest-free payments of') . ' ' . $amountWithCurrency;
            }
            $templateConfigs['TITLE'] = (string) $checkoutText;
            $templateConfigs['ISO_COUNTRY_CODE'] = $this->getIsoCountryCode();
            $templateConfigs['CURRENCY'] = $this->currency;
            $templateConfigs['TOTAL_AMOUNT'] = $totalAmount;
            $description = $this->l('You will be redirected to Afterpay to fill out your payment information.');
            $templateConfigs['DESCRIPTION'] = $description;
            $templateConfigs['TERMS_AND_CONDITIONS'] = $this->l('Terms and conditions');
            $termsLink = $this->termsLinkPerRegion[Configuration::get('AFTERPAY_REGION')];
            $templateConfigs['TERMS_AND_CONDITIONS_LINK'] = $termsLink;
            $templateConfigs['MORE_INFO_TEXT'] = '_hide_';
            $templateConfigs['LOGO_TEXT'] = $this->l("Afterpay");
            $templateConfigs['ICON'] = 'https://static.afterpay.com/app/icon-128x128.png';
            $templateConfigs['LOGO_BADGE'] ='https://static.afterpay.com/logo/compact-badge-afterpay-black-on-mint.svg';
            $templateConfigs['LOGO_OPC'] = Media::getMediaPath(_PS_MODULE_DIR_.$this->name.'/views/img/logo_opc.png');
            $templateConfigs['PAYMENT_URL'] = $link->getModuleLink($this->name, 'payment');
            $mobileViewLayout = Tools::strtolower('four-by-one');
            $isMobileLayout = $this->context->isMobile();
            if ($isMobileLayout) {
                $mobileViewLayout = Tools::strtolower('two-by-two');
            }
            $templateConfigs['AP_MOBILE_LAYOUT'] = $mobileViewLayout;
            $templateConfigs['IS_MOBILE_LAYOUT'] = $isMobileLayout;
            $templateConfigs['PS_VERSION'] = str_replace('.', '-', Tools::substr(_PS_VERSION_, 0, 3));

            $this->context->smarty->assign($templateConfigs);

            $paymentOption = new PrestaShop\PrestaShop\Core\Payment\PaymentOption();
            $uri = $link->getModuleLink($this->name, 'payment');
            $paymentOption
                ->setCallToActionText($templateConfigs['TITLE'])
                ->setAction($uri)
                ->setModuleName(__CLASS__);
            if ($this->isOPC()) {
                $moduleUri = 'module:'.$this->name.'/views/templates/hook/onepagecheckout.tpl';
                $paymentOption
                    ->setAdditionalInformation($this->fetch($moduleUri))
                    ->setLogo($templateConfigs['LOGO_OPC']);
            } else {
                $moduleUri = 'module:'.$this->name.'/views/templates/hook/checkout.tpl';
                $paymentOption
                    ->setAdditionalInformation($this->fetch($moduleUri))
                    ->setLogo($templateConfigs['LOGO_BADGE']);
            }
            $return[] = $paymentOption;
        }

        return $return;
    }

    /**
     * Get the form for editing the BackOffice options of the module
     *
     * @return array
     */
    private function getConfigForm()
    {
        $inputs = array();
        $inputs[] = array(
            'name' => 'AFTERPAY_IS_ENABLED',
            'type' =>  'switch',
            'label' => $this->l('Module is enabled'),
            'prefix' => '<i class="icon icon-key"></i>',
            'class' => 't',
            'required' => true,
            'values'=> array(
                array(
                    'id' => 'AFTERPAY_IS_ENABLED_TRUE',
                    'value' => 1,
                    'label' => $this->l('Yes', get_class($this), null, false),
                ),
                array(
                    'id' => 'AFTERPAY_IS_ENABLED_FALSE',
                    'value' => 0,
                    'label' => $this->l('No', get_class($this), null, false),
                ),
            )
        );
        $query = array(
            array(
                'AFTERPAY_REGION_id' => 'AU',
                'AFTERPAY_REGION_name' => $this->l('Australia')
            ),
            array(
                'AFTERPAY_REGION_id' => 'CA',
                'AFTERPAY_REGION_name' => $this->l('Canada')
            ),
            array(
                'AFTERPAY_REGION_id' => 'NZ',
                'AFTERPAY_REGION_name' => $this->l('New Zealand')
            ),
            array(
                'AFTERPAY_REGION_id' => 'US',
                'AFTERPAY_REGION_name' => $this->l('United States')
            )
        );
        $inputs[] = array(
            'name' => 'AFTERPAY_REGION',
            'type' => 'select',
            'label' => $this->l('API region'),
            'prefix' => '<i class="icon icon-key"></i>',
            'class' => 't',
            'required' => true,
            'options' => array(
                'query' => $query,
                'id' => 'AFTERPAY_REGION_id',
                'name' => 'AFTERPAY_REGION_name'
            )
        );

        $inputs[] = array(
            'name' => 'AFTERPAY_PUBLIC_KEY',
            'suffix' => $this->l('ex: 400101010'),
            'type' => 'text',
            'label' => $this->l('Merchant Id'),
            'prefix' => '<i class="icon icon-key"></i>',
            'col' => 6,
            'required' => true,
        );
        $inputs[] = array(
            'name' => 'AFTERPAY_SECRET_KEY',
            'suffix' => $this->l('128 alphanumeric code'),
            'type' => 'text',
            'size' => 128,
            'label' => $this->l('Secret Key'),
            'prefix' => '<i class="icon icon-key"></i>',
            'col' => 6,
            'required' => true,
        );
        $inputs[] = array(
            'name' => 'AFTERPAY_ENVIRONMENT',
            'type' => 'select',
            'label' => $this->l('API Environment'),
            'prefix' => '<i class="icon icon-key"></i>',
            'class' => 't',
            'required' => true,
            'options' => array(
                'query' => array(
                    array(
                        'AFTERPAY_ENVIRONMENT_id' => 'sandbox',
                        'AFTERPAY_ENVIRONMENT_name' => $this->l('Sandbox')
                    ),
                    array(
                        'AFTERPAY_ENVIRONMENT_id' => 'production',
                        'AFTERPAY_ENVIRONMENT_name' => $this->l('Production')
                    )
                ),
                'id' => 'AFTERPAY_ENVIRONMENT_id',
                'name' => 'AFTERPAY_ENVIRONMENT_name'
            )
        );
        $inputs[] = array(
            'name' => 'AFTERPAY_MIN_AMOUNT',
            'suffix' => $this->l('ex: 0.5'),
            'type' => 'text',
            'label' => $this->l('Min Payment Limit'),
            'col' => 6,
            'disabled' => true,
            'required' => false,
        );
        $inputs[] = array(
            'name' => 'AFTERPAY_MAX_AMOUNT',
            'suffix' => $this->l('ex: 800'),
            'type' => 'text',
            'label' => $this->l('Max Payment Limit'),
            'col' => 6,
            'disabled' => true,
            'required' => false,
        );
        $inputs[] = array(
            'type' => 'categories',
            'label' => $this->l('Restricted Categories'),
            'name' => 'AFTERPAY_RESTRICTED_CATEGORIES',
            'col' => 7,
            'desc' => $this->l('IMPORTANT: Only enable the categories where you DON\'T want to show Clearpay. ') .
                $this->l('By default: UNCHECK ALL'),
            'tree' => array(
                'id' => 'AFTERPAY_RESTRICTED_CATEGORIES',
                'selected_categories' => json_decode(Configuration::get('AFTERPAY_RESTRICTED_CATEGORIES')),
                'root_category' => Category::getRootCategory()->id,
                'use_search' => true,
                'use_checkbox' => true,
            ),
        );
        $inputs[] = array(
            'name' => 'AFTERPAY_CSS_SELECTOR',
            'suffix' => $this->l('The default value is \'default\'.'),
            'desc' => $this->l('This property set the CSS selector needed to show the assets on the product page.') .
                ' ' . $this->l('Only change this value if it doesn\'t appear properly.'),
            'type' => 'text',
            'size' => 128,
            'label' => $this->l('Price CSS Selector'),
            'col' => 8,
            'required' => false,
        );
        $inputs[] = array(
            'name' => 'AFTERPAY_CSS_SELECTOR_CART',
            'suffix' => $this->l('The default value is \'default\'.'),
            'desc' => $this->l('This property set the CSS selector needed to show the assets on the cart page.') .
                ' ' . $this->l('Only change this value if it doesn\'t appear properly.'),
            'type' => 'text',
            'size' => 128,
            'label' => $this->l('Cart Page CSS Selector'),
            'col' => 8,
            'required' => false,
        );
        $inputs[] = array(
            'name' => 'AFTERPAY_LOGS',
            'type' => 'checkbox',
            'label' => $this->l('Debug mode'),
            'desc' => $this->l(
                'You can see these logs on the "Configure -> Advanced Parameters -> Logs" section'
            ),
            'class' => 't',
            'values' => array(
                'query' => array(
                    array(
                        'AFTERPAY_LOGS_id' => 'ACTIVE',
                        'AFTERPAY_LOGS_name' => $this->l('Activate debug logs')
                    )
                ),
                'id' => 'AFTERPAY_LOGS_id',
                'name' => 'AFTERPAY_LOGS_name'
            )
        );


        $return = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs',
                ),
                'input' => $inputs,
                'submit' => array(
                    'title' => $this->l('Save'),
                    'class' => 'btn btn-default pull-right'
                )
            )
        );
        return $return;
    }

    /**
     * Form configuration function
     *
     * @param array $settings
     *
     * @return string
     */
    private function renderForm()
    {
        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;

        $helper->title = $this->displayName;
        $helper->show_toolbar = true;
        $helper->show_toolbar = true;
        $helper->submit_action = 'submit'.$this->name;
        $helper->toolbar_btn = array(
            'save' =>
                array(
                    'desc' => $this->l('Save'),
                    'href' => AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.
                        '&token='.Tools::getAdminTokenLite('AdminModules'),
                ),
            'back' => array(
                'href' => AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->l('Back to list')
            )
        );


        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->fields_value['AFTERPAY_PUBLIC_KEY'] = Configuration::get('AFTERPAY_PUBLIC_KEY');
        $helper->fields_value['AFTERPAY_SECRET_KEY'] = Configuration::get('AFTERPAY_SECRET_KEY');
        $helper->fields_value['AFTERPAY_IS_ENABLED'] = Configuration::get('AFTERPAY_IS_ENABLED');
        $helper->fields_value['AFTERPAY_ENVIRONMENT'] = Configuration::get('AFTERPAY_ENVIRONMENT');
        $helper->fields_value['AFTERPAY_REGION'] = Configuration::get('AFTERPAY_REGION');
        $helper->fields_value['AFTERPAY_MIN_AMOUNT'] = Configuration::get('AFTERPAY_MIN_AMOUNT');
        $helper->fields_value['AFTERPAY_MAX_AMOUNT'] = Configuration::get('AFTERPAY_MAX_AMOUNT');
        $helper->fields_value['AFTERPAY_CSS_SELECTOR'] = Configuration::get('AFTERPAY_CSS_SELECTOR');
        $helper->fields_value['AFTERPAY_CSS_SELECTOR_CART'] = Configuration::get('AFTERPAY_CSS_SELECTOR_CART');
        $helper->fields_value['AFTERPAY_LOGS_ACTIVE'] = Configuration::get('AFTERPAY_LOGS');

        return $helper->generateForm(array($this->getConfigForm()));
    }

    /**
     * Function to update the variables of Afterpay Module in the backoffice of prestashop
     *
     * @return string
     * @throws SmartyException
     */
    public function getContent()
    {
        $settingsKeys = array();
        $settingsKeys[] = 'AFTERPAY_IS_ENABLED';
        $settingsKeys[] = 'AFTERPAY_PUBLIC_KEY';
        $settingsKeys[] = 'AFTERPAY_SECRET_KEY';
        $settingsKeys[] = 'AFTERPAY_ENVIRONMENT';
        $settingsKeys[] = 'AFTERPAY_REGION';
        $settingsKeys[] = 'AFTERPAY_RESTRICTED_CATEGORIES';
        $settingsKeys[] = 'AFTERPAY_CSS_SELECTOR';
        $settingsKeys[] = 'AFTERPAY_CSS_SELECTOR_CART';
        $settingsKeys[] = 'AFTERPAY_LOGS_ACTIVE';

        if (Tools::isSubmit('submit'.$this->name)) {
            foreach ($settingsKeys as $key) {
                if (is_array(Tools::getValue($key))) {
                    $value = json_encode(Tools::getValue($key));
                } else {
                    $value = trim(Tools::getValue($key));
                }
                if ($key === 'AFTERPAY_LOGS_ACTIVE') {
                    Configuration::updateValue('AFTERPAY_LOGS', $value);
                }
                Configuration::updateValue($key, $value);
            }
        }

        $publicKey = Configuration::get('AFTERPAY_PUBLIC_KEY');
        $secretKey = Configuration::get('AFTERPAY_SECRET_KEY');
        $environment = Configuration::get('AFTERPAY_ENVIRONMENT');
        $isEnabled = Configuration::get('AFTERPAY_IS_ENABLED');

        if (empty($publicKey) || empty($secretKey)) {
            $message = $this->displayError($this->l('Merchant Id and Secret Key are mandatory fields'));
        } else {
            $message = $this->displayConfirmation($this->l('All changes have been saved'));
        }

        // auto update configuration price thresholds and allowed countries in background
        if ($isEnabled && !empty($publicKey) && !empty($secretKey) && !empty($environment)) {
            try {
                if (!empty($publicKey) && !empty($secretKey)  && $isEnabled) {
                    $merchantAccount = new Afterpay\SDK\MerchantAccount();
                    $merchantAccount
                        ->setMerchantId($publicKey)
                        ->setSecretKey($secretKey)
                        ->setApiEnvironment($environment)
                        ->setCountryCode(Configuration::get('AFTERPAY_REGION'))
                    ;

                    $apiVersion = $this->getApiVersionPerRegion(Configuration::get('AFTERPAY_REGION'));
                    $getConfigurationRequest = new Afterpay\SDK\HTTP\Request\GetConfiguration();
                    $getConfigurationRequest->setMerchantAccount($merchantAccount);
                    $getConfigurationRequest->setUri("/$apiVersion/configuration?include=activeCountries");
                    $getConfigurationRequest->send();
                    $configuration = $getConfigurationRequest->getResponse()->getParsedBody();

                    if (isset($configuration->message) || is_null($configuration)) {
                        $response = isset($configuration->message) ? $configuration->message : "NULL";

                        $message = $this->displayError(
                            $this->l('Configuration request can not be done with the region and credentials') .
                            $this->l(' provided. Message received: ') . $response
                        );
                        Configuration::updateValue(
                            'AFTERPAY_MIN_AMOUNT',
                            0
                        );
                        Configuration::updateValue(
                            'AFTERPAY_MAX_AMOUNT',
                            0
                        );
                    } else {
                        if (is_array($configuration)) {
                            $configuration = $configuration[0];
                        }
                        $minAmount = 0;
                        if (isset($configuration->minimumAmount)) {
                            $minAmount = $configuration->minimumAmount->amount;
                        }
                        Configuration::updateValue(
                            'AFTERPAY_MIN_AMOUNT',
                            $minAmount
                        );
                        $maxAmount = 0;
                        if (isset($configuration->maximumAmount)) {
                            $maxAmount = $configuration->maximumAmount->amount;
                        }
                        Configuration::updateValue(
                            'AFTERPAY_MAX_AMOUNT',
                            $maxAmount
                        );
                        if (isset($configuration->activeCountries)) {
                            Configuration::updateValue(
                                'AFTERPAY_ALLOWED_COUNTRIES',
                                json_encode($configuration->activeCountries)
                            );
                        } else {
                            $region = Configuration::get('AFTERPAY_REGION');
                            if (!empty($region) and is_string($region)) {
                                Configuration::updateValue(
                                    'AFTERPAY_ALLOWED_COUNTRIES',
                                    $this->getCountriesPerRegion($region)
                                );
                            }
                        }
                    }
                }
            } catch (\Exception $exception) {
                $uri = 'Unable to retrieve URL';
                if (isset($getConfigurationRequest)) {
                    $uri = $getConfigurationRequest->getApiEnvironmentUrl() . $getConfigurationRequest->getUri();
                }
                $message = $this->displayError(
                    $this->l('An error occurred when retrieving configuration from') . ' ' . $uri
                );
            }
        }

        $logo = 'https://static.afterpay.com/icon/afterpay-logo-colour-transparent.svg';
        $tpl = $this->local_path.'views/templates/admin/config-info.tpl';
        $header = $this->l('Afterpay Configuration Panel');
        $button1 = $this->l('Contact us');
        $button2 = $this->l('Getting started');
        $centeredText = '<strong>'. $this->l('1. Before getting started:') . '</strong>' .
            $this->l(' Do you want to know more about Afterpay?') .
            $this->l(' Fill in the following contact form and our sales team will reach out to you.') .
            '<br><br><strong>'. $this->l('2. Getting started:') . '</strong>' .
            $this->l(' Are you ready to integrate Afterpay?') .
            $this->l(' If you have been in contact with our sales team and have signed our contract ') .
            $this->l(' click on the following link to get started.');
        $this->context->smarty->assign(array(
            'header' => $header,
            'button1' => $button1,
            'button2' => $button2,
            'centered_text' => $centeredText,
            'logo' => $logo,
            'form' => '',
            'message' => $message,
            'version' => 'v'.$this->version,
        ));

        return $this->context->smarty->fetch($tpl) . $this->renderForm();
    }

    /**
     * Hook to show payment method, this only applies on prestashop <= 1.6
     *
     * @param $params
     * @return bool | string
     * @throws Exception
     */
    public function hookPayment($params)
    {
        /** @var Cart $cart */
        $cart = $this->context->cart;
        $this->shippingAddress = new Address($cart->id_address_delivery);
        $this->billingAddress = new Address($cart->id_address_invoice);
        $totalAmount = Afterpayofficial::parseAmount($cart->getOrderTotal(true, Cart::BOTH));

        $link = $this->context->link;

        $return = '';
        $this->context->smarty->assign($this->getButtonTemplateVars($cart));
        $templateConfigs = array();
        if ($this->isPaymentMethodAvailable()) {
            $amountWithCurrency = $this->currencySymbol . Afterpayofficial::parseAmount($totalAmount / 4);
            $checkoutText = $this->l('Or 4 interest-free payments of') . ' ' . $amountWithCurrency . ' ';
            $checkoutText .= $this->l('with');
            if ($this->isOPC()) {
                $checkoutText = $this->l('4 interest-free payments of') . ' ' . $amountWithCurrency
                    . $this->l(' with Afterpay');
            }
            $templateConfigs['TITLE'] = $checkoutText;
            $templateConfigs['CURRENCY'] = $this->currency;
            $templateConfigs['MORE_HEADER'] = $this->l('Instant approval decision - 4 interest-free payments of')
                . ' ' . $amountWithCurrency;
            $templateConfigs['TOTAL_AMOUNT'] = $totalAmount;
            $description = $this->l('You will be redirected to Afterpay to fill out your payment information.');
            $templateConfigs['DESCRIPTION'] = $description;
            $templateConfigs['TERMS_AND_CONDITIONS'] = $this->l('Terms and conditions');
            $termsLink = $this->termsLinkPerRegion[Configuration::get('AFTERPAY_REGION')];
            $templateConfigs['TERMS_AND_CONDITIONS_LINK'] = $termsLink;
            $templateConfigs['MORE_INFO_TEXT'] = '_hide_';
            $templateConfigs['LOGO_TEXT'] = $this->l("Afterpay");
            $templateConfigs['ICON'] = 'https://static.afterpay.com/app/icon-128x128.png';
            $templateConfigs['LOGO_BADGE'] ='https://static.afterpay.com/logo/compact-badge-afterpay-black-on-mint.svg';
            $templateConfigs['LOGO_OPC'] = Media::getMediaPath(_PS_MODULE_DIR_.$this->name.'/views/img/logo_opc.png');
            $templateConfigs['PAYMENT_URL'] = $link->getModuleLink($this->name, 'payment');
            $mobileViewLayout = Tools::strtolower('four-by-one');
            $isMobileLayout = $this->context->isMobile();
            if ($isMobileLayout) {
                $mobileViewLayout = Tools::strtolower('two-by-two');
            }
            $templateConfigs['AP_MOBILE_LAYOUT'] = $mobileViewLayout;
            $templateConfigs['IS_MOBILE_LAYOUT'] = $isMobileLayout;
            $templateConfigs['PS_VERSION'] = str_replace('.', '-', Tools::substr(_PS_VERSION_, 0, 3));

            $this->context->smarty->assign($templateConfigs);
            if ($this->isOPC()) {
                $this->checkLogoExists();
                $return .= $this->display(
                    __FILE__,
                    'views/templates/hook/onepagecheckout.tpl'
                );
            } else {
                $return .= $this->display(
                    __FILE__,
                    'views/templates/hook/checkout.tpl'
                );
            }
        }
        return $return;
    }

    /**
     * @param string $templateName
     * @return false|string
     */
    public function templateDisplay($templateName = '')
    {
        $templateConfigs = array();
        if ($templateName === 'cart.tpl') {
            $amount = Afterpayofficial::parseAmount($this->context->cart->getOrderTotal());
            $templateConfigs['AMOUNT'] =  Afterpayofficial::parseAmount($this->context->cart->getOrderTotal()/4);
            $templateConfigs['PRICE_TEXT'] = $this->l('4 interest-free payments of');
            $templateConfigs['MORE_INFO'] = $this->l('FIND OUT MORE');
            $desc1 = $this->l('Buy now. Pay later. No interest.');
            $templateConfigs['DESCRIPTION_TEXT_ONE'] = $desc1;
            $desc2 = $this->l('With Afterpay you can enjoy your purchase now and pay in 4 installments. ');
            $desc2 .= ' ' . $this->l(' No hidden cost. Choose Afterpay as your payment method in the check-out, ');
            $desc2 .= ' ' . $this->l(' fill in a simple form, no documents needed, and pay later.');
            $templateConfigs['DESCRIPTION_TEXT_TWO'] = $desc2;
            $categoryRestriction = $this->isCartRestricted($this->context->cart);
            $simulatorIsEnabled = true;
            $templateConfigs['PRICE_SELECTOR'] = Configuration::get('AFTERPAY_CSS_SELECTOR_CART');
            if ($templateConfigs['PRICE_SELECTOR'] === 'default'|| $templateConfigs['PRICE_SELECTOR'] === '') {
                $templateConfigs['PRICE_SELECTOR'] = '.cart-total .value';
            }
        } else {
            $productId = Tools::getValue('id_product');
            if (!$productId) {
                return false;
            }
            $categoryRestriction = $this->isProductRestricted($productId);
            $amount = Product::getPriceStatic($productId);
            $templateConfigs['AMOUNT'] = $amount;
            $simulatorIsEnabled = self::SIMULATOR_IS_ENABLED;
            $templateConfigs['PRICE_SELECTOR'] = Configuration::get('AFTERPAY_CSS_SELECTOR');
            if ($templateConfigs['PRICE_SELECTOR'] === 'default'|| $templateConfigs['PRICE_SELECTOR'] === '') {
                $templateConfigs['PRICE_SELECTOR'] =
                    '.current-price :not(span.discount,span.regular-price,span.discount-percentage)';
                if (version_compare(_PS_VERSION_, '1.7', 'lt')) {
                    $templateConfigs['PRICE_SELECTOR'] = '#our_price_display';
                }
            }
        }
        $return = '';
        $isEnabled = Configuration::get('AFTERPAY_IS_ENABLED');

        $allowedCountries = json_decode(Configuration::get('AFTERPAY_ALLOWED_COUNTRIES'));
        $language = $this->getCurrentLanguageCode();
        $restrictedByLangOrCurrency = $this->isRestrictedByLangOrCurrency();
        if ($isEnabled &&
            $simulatorIsEnabled &&
            $amount > 0 &&
            ($amount >= Configuration::get('AFTERPAY_MIN_AMOUNT') || $templateName === 'product.tpl') &&
            ($amount <= Configuration::get('AFTERPAY_MAX_AMOUNT')  || $templateName === 'product.tpl') &&
            !$categoryRestriction &&
            !$restrictedByLangOrCurrency
        ) {
            $templateConfigs['PS_VERSION'] = str_replace('.', '-', Tools::substr(_PS_VERSION_, 0, 3));
            $templateConfigs['SDK_URL'] = self::AFTERPAY_JS_CDN_URL;
            $templateConfigs['AFTERPAY_MIN_AMOUNT'] = Configuration::get('AFTERPAY_MIN_AMOUNT');
            $templateConfigs['AFTERPAY_MAX_AMOUNT'] = Configuration::get('AFTERPAY_MAX_AMOUNT');
            $templateConfigs['CURRENCY'] = $this->currency;
            $templateConfigs['ISO_COUNTRY_CODE'] = $this->getIsoCountryCode();
            $templateConfigs['AMOUNT_WITH_CURRENCY'] = $templateConfigs['AMOUNT'] . $this->currencySymbol;
            if ($this->currency === 'GBP') {
                $templateConfigs['AMOUNT_WITH_CURRENCY'] = $this->currencySymbol. $templateConfigs['AMOUNT'];
            }

            $this->context->smarty->assign($templateConfigs);
            $return .= $this->display(
                __FILE__,
                'views/templates/hook/' . $templateName
            );
        } else {
            if ($isEnabled && $templateName === 'product.tpl' && Configuration::get('AFTERPAY_LOGS') === 'on') {
                $logMessage = '';
                if (!$simulatorIsEnabled) {
                    $logMessage .= "Afterpay: Simulator is disabled by 'self::SIMULATOR_IS_ENABLED'. ";
                }
                if (!in_array(Tools::strtoupper($language), $allowedCountries)) {
                    $logMessage .= "Afterpay: Simulator is disabled by the allowedCountries, 
                    current:$language and allowed:" . json_encode($allowedCountries) . '. ';
                }
                if ($categoryRestriction) {
                    $productCategories = json_encode(Product::getProductCategories($productId));
                    $logMessage .= "Afterpay: Simulator is disabled by the Categories restriction, 
                    current:$productCategories."
                    . "and not allowed:". Configuration::get('AFTERPAY_RESTRICTED_CATEGORIES');
                }
                if (Configuration::get('AFTERPAY_LOGS') == 'on' && $logMessage != '') {
                    PrestaShopLogger::addLog($logMessage, 2, null, "Afterpay", 1);
                }
            }
        }

        return $return;
    }

    /**
     * @return bool
     */
    protected function isOPC()
    {
        $supercheckout_enabled = Module::isEnabled('supercheckout');
        $onepagecheckoutps_enabled = Module::isEnabled('onepagecheckoutps');
        $onepagecheckout_enabled = Module::isEnabled('onepagecheckout');

        return ($supercheckout_enabled || $onepagecheckout_enabled || $onepagecheckoutps_enabled);
    }

    /**
     * @param $params
     * @return string
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function hookDisplayProductPriceBlock($params)
    {
        // $params['type'] = weight | price | after_price
        if (isset($params['type']) && $params['type'] === 'after_price' &&
            isset($params['smarty']) && isset($params['smarty']->template_resource) &&
            (
                (
                    version_compare(_PS_VERSION_, '1.7', 'ge') &&
                    strpos($params['smarty']->template_resource, 'product-prices.tpl') !== false
                )
                ||
                (
                    version_compare(_PS_VERSION_, '1.7', 'lt') &&
                    strpos($params['smarty']->template_resource, 'product.tpl') !== false
                )
            )
        ) {
            return $this->templateDisplay('product.tpl');
        }
        if (isset($params['type'])
            && $params['type'] === 'price'
            && version_compare(_PS_VERSION_, '1.6.1', 'lt')
            && strpos($params['smarty']->template_resource, 'product.tpl') !== false
        ) {
            return $this->templateDisplay('product.tpl');
        }
        return '';
    }

    /**
     * @param array $params
     * @return string
     */
    public function hookDisplayExpressCheckout($params)
    {
        return $this->templateDisplay('cart.tpl');
    }

    /**
     * @param array $params
     *
     * @return string
     */
    public function hookDisplayOrderConfirmation($params)
    {
        $paymentMethod = (_PS_VERSION_ < 1.7) ? ($params["objOrder"]->payment) : ($params["order"]->payment);

        if ($paymentMethod == $this->displayName) {
            return $this->display(__FILE__, 'views/templates/hook/payment-return.tpl');
        }

        return null;
    }

    /**
     * @return string
     */
    public function hookDisplayWrapperTop()
    {
        $isDeclined = Tools::getValue('afterpay_declined');
        $isMismatch = Tools::getValue('afterpay_mismatch');
        $referenceId = Tools::getValue('afterpay_reference_id');
        $errorText1 = $this->l('Thanks for confirming your payment, however as your cart has changed we need a new ') .
            $this->l(' confirmation. Please proceed to Afterpay and retry again in a few minutes');
        $errorText2 = $this->l('For more information, please contact the Afterpay Customer Service Team:');
        $declinedText1 = $this->l('We are sorry to inform you that your payment has been declined by Afterpay.');
        $this->context->smarty->assign(array(
            'REFERENCE_ID' => $referenceId,
            'PS_VERSION' => str_replace('.', '-', Tools::substr(_PS_VERSION_, 0, 3)),
            'ERROR_TEXT1' => $errorText1,
            'ERROR_TEXT2' => $errorText2,
            'DECLINED_TEXT1' => $declinedText1,

        ));
        if ($isDeclined == 'true') {
            $return = $this->displayError(
                $this->display(__FILE__, 'views/templates/hook/payment-declined.tpl')
            );
            return $return;
        }
        if ($isMismatch == 'true') {
            $return = $this->displayError(
                $this->display(__FILE__, 'views/templates/hook/payment-error.tpl')
            );
            return $return;
        }
        return null;
    }

    /**
     * @param $params
     * @return string|null
     */
    public function hookDisplayPaymentTop($params)
    {
        if (version_compare(_PS_VERSION_, '1.6.1', 'lt')) {
            return $this->hookDisplayWrapperTop();
        }
        return null;
    }

    /**
     * Hook Action for Order Status Update (handles Refunds)
     * @param $params
     * @return bool
     * @throws \Afterpay\SDK\Exception\InvalidArgumentException
     * @throws \Afterpay\SDK\Exception\NetworkException
     * @throws \Afterpay\SDK\Exception\ParsingException
     */
    public function hookActionOrderStatusUpdate($params)
    {
        $newOrderStatus = null;
        $order = null;
        if (!empty($params) && !empty($params['id_order'])) {
            $order = new Order((int)$params['id_order']);
        }
        if (empty($order) || empty($order->payment) || $order->payment != self::PRODUCT_PAYMENT_NAME) {
            return false;
        }

        if (!empty($params) && !empty($params['newOrderStatus'])) {
            $newOrderStatus = $params['newOrderStatus'];
        }
        if ($newOrderStatus->id == _PS_OS_REFUND_) {
            $afterpayRefund = $this->createRefundObject();
            // ---- needed values ----
            $payments = $order->getOrderPayments();
            $transactionId = $payments[0]->transaction_id;
            $currency = new Currency($order->id_currency);
            $currencyCode = $currency->iso_code;
            // ------------------------
            $afterpayRefund->setOrderId($transactionId);
            $afterpayRefund->setRequestId(Tools::strtoupper(md5(uniqid(rand(), true))));
            $afterpayRefund->setAmount(
                Afterpayofficial::parseAmount($order->total_paid_real),
                $currencyCode
            );
            $afterpayRefund->setMerchantReference($order->id);


            if ($afterpayRefund->send()) {
                if ($afterpayRefund->getResponse()->isSuccessful()) {
                    PrestaShopLogger::addLog(
                        $this->l("Afterpay FullRefund done: ") . Afterpayofficial::parseAmount($order->total_paid_real),
                        1,
                        null,
                        "Afterpay",
                        1
                    );
                    return true;
                }
                $parsedBody = $afterpayRefund->getResponse()->getParsedBody();
                PrestaShopLogger::addLog(
                    $this->l("Afterpay Full Refund Error: ") . $parsedBody->errorCode . '-> ' . $parsedBody->message,
                    3,
                    null,
                    "Afterpay",
                    1
                );
            }
        }
        return false;
    }

    /**
     * Hook Action for Partial Refunds
     * @param array $params
     * since 1.0.0
     */
    public function hookActionOrderSlipAdd($params)
    {
        if (!empty($params) && !empty($params["order"]->id) &&
            !empty($params["order"]->payment) && $params["order"]->payment == self::PRODUCT_PAYMENT_NAME) {
            $order = new Order((int)$params["order"]->id);
        } else {
            return false;
        }
        // ---- needed values ----
        $payments = $order->getOrderPayments();
        $transactionId = $payments[0]->transaction_id;
        $currency = new Currency($order->id_currency);
        $currencyCode = $currency->iso_code;
        $afterpayRefund = $this->createRefundObject();

        $refundProductsList = $params["productList"];
        $refundTotalAmount = 0;
        foreach ($refundProductsList as $item) {
            $refundTotalAmount +=  $item["amount"];
        }
        $refundTotalAmount = Afterpayofficial::parseAmount($refundTotalAmount);

        $afterpayRefund->setOrderId($transactionId);
        $afterpayRefund->setRequestId(Tools::strtoupper(md5(uniqid(rand(), true))));
        $afterpayRefund->setAmount($refundTotalAmount, $currencyCode);
        $afterpayRefund->setMerchantReference($order->id);

        if ($afterpayRefund->send()) {
            if ($afterpayRefund->getResponse()->isSuccessful()) {
                PrestaShopLogger::addLog(
                    $this->l("Afterpay partial Refund done: ") . $refundTotalAmount,
                    1,
                    null,
                    "Afterpay",
                    1
                );
                return true;
            }
            $parsedBody = $afterpayRefund->getResponse()->getParsedBody();
            PrestaShopLogger::addLog(
                $this->l("Afterpay Partial Refund Error: ") . $parsedBody->errorCode . '-> ' . $parsedBody->message,
                3,
                null,
                "Afterpay",
                1
            );
        }
        return false;
    }

    /**
     * Construct the Refunds Object based on the configuration and Refunds type
     * @return Afterpay\SDK\HTTP\Request\CreateRefund
     */
    private function createRefundObject()
    {

        $publicKey = Configuration::get('AFTERPAY_PUBLIC_KEY');
        $secretKey = Configuration::get('AFTERPAY_SECRET_KEY');
        $environment = Configuration::get('AFTERPAY_ENVIRONMENT');

        $merchantAccount = new Afterpay\SDK\MerchantAccount();
        $merchantAccount
            ->setMerchantId($publicKey)
            ->setSecretKey($secretKey)
            ->setApiEnvironment($environment)
            ->setCountryCode(Configuration::get('AFTERPAY_REGION'))
        ;

        $afterpayRefund = new Afterpay\SDK\HTTP\Request\CreateRefund();
        $afterpayRefund->setMerchantAccount($merchantAccount);

        return $afterpayRefund;
    }
    /**
     * Check logo exists in OPC module
     */
    public function checkLogoExists()
    {
        $logoOPC = _PS_MODULE_DIR_ . 'onepagecheckoutps/views/img/payments/afterpay.png';
        if (!file_exists($logoOPC) && is_dir(_PS_MODULE_DIR_ . 'onepagecheckoutps/views/img/payments')) {
            copy(
                _PS_AFTERPAY_DIR . '/views/img/logo_opc.png',
                $logoOPC
            );
        }
    }

    /**
     * Get user language Code
     */
    private function getCurrentLanguageCode()
    {
        $allowedCountries = json_decode(Configuration::get('AFTERPAY_ALLOWED_COUNTRIES'));
        if (is_null($allowedCountries)) {
            return 'NonAccepted';
        }
        $lang = Language::getLanguage($this->context->language->id);
        $langArray = explode("-", $lang['language_code']);
        if (count($langArray) != 2 && isset($lang['locale'])) {
            $langArray = explode("-", $lang['locale']);
        }
        $language = Tools::strtoupper($langArray[count($langArray)-1]);

        if ($this->currency != 'EUR' && in_array(Tools::strtoupper($langArray[0]), $allowedCountries)) {
            return Tools::strtoupper($langArray[0]);
        }

        if (in_array(Tools::strtoupper($language), $allowedCountries)) {
            return $language;
        }

        return 'NonAccepted('.$lang['language_code'].')';
    }

    /**
     * Get user language Id
     */
    private function getCurrentLanguageId()
    {
        $allowedCountries = json_decode(Configuration::get('AFTERPAY_ALLOWED_COUNTRIES'));
        if (is_null($allowedCountries)) {
            return '-1';
        }
        $lang = Language::getLanguage($this->context->language->id);
        $langArray = explode("-", $lang['language_code']);
        if (count($langArray) != 2 && isset($lang['locale'])) {
            $langArray = explode("-", $lang['locale']);
        }
        $language = Tools::strtoupper($langArray[count($langArray)-1]);

        if (in_array(Tools::strtoupper($language), $allowedCountries)) {
            return $this->context->language->id;
        }

        return '-1';
    }

    /**
     * @param $region
     * @return string
     */
    public function getCountriesPerRegion($region = '')
    {
        if (isset($this->defaultCountriesPerRegion[$region])) {
            return $this->defaultCountriesPerRegion[$region];
        }
        return json_encode(array($region));
    }

    /**
     * @param $region
     * @return string
     */
    public function getApiVersionPerRegion($region = '')
    {
        if (isset($this->defaultApiVersionPerRegion[$region])) {
            return $this->defaultApiVersionPerRegion[$region];
        }
        return json_encode(array($region));
    }

    /**
     * @return mixed|string|string[]
     */
    public function getIsoCountryCode()
    {
        if ($this->currency != 'EUR') {
            if (!isset($this->defaultLanguagePerCurrency[$this->currency])) {
                return 'NonAccepted';
            }
            $language = $this->defaultLanguagePerCurrency[$this->currency];
            return $this->defaultIsoCountryCodePerCountry[$language];
        }

        $languageId = $this->getCurrentLanguageId();
        if ($languageId == -1) {
            return 'NonAccepted';
        }

        $language = Language::getLanguage($languageId);

        if (isset($language['locale'])) {
            $language = $language['locale'];
        } else {
            $language = $language['language_code'];
        }
        if (Tools::strlen($language) == 5) {
            $part1 = Tools::substr($language, 0, 2);
            $part2 = Tools::strtoupper(Tools::substr($language, 2, 4));
            $language = $part1 . $part2;
        }
        return str_replace('-', '_', $language);
    }

    /**
     * @param null $amount
     * @return string
     */
    public static function parseAmount($amount = null)
    {
        return number_format(
            round($amount, 2, PHP_ROUND_HALF_UP),
            2,
            '.',
            ''
        );
    }

    /**
     * @param $productId
     * @return bool
     */
    private function isProductRestricted($productId)
    {
        $afterpayRestrictedCategories = json_decode(Configuration::get('AFTERPAY_RESTRICTED_CATEGORIES'));
        if (!is_array($afterpayRestrictedCategories)) {
            return false;
        }
        $productCategories = Product::getProductCategories($productId);
        return (bool) count(array_intersect($productCategories, $afterpayRestrictedCategories));
    }

    /**
     * @param $cart
     * @return bool
     */
    private function isCartRestricted($cart)
    {
        foreach ($cart->getProducts() as $product) {
            if ($this->isProductRestricted($product['id_product'])) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return bool
     */
    private function isRestrictedByLangOrCurrency()
    {
        $language = $this->getCurrentLanguageCode();
        $allowedCountries = json_decode(Configuration::get('AFTERPAY_ALLOWED_COUNTRIES'));
        $return = (in_array(Tools::strtoupper($language), $allowedCountries) &&
            $this->allowedCurrencyPerRegion[Configuration::get('AFTERPAY_REGION')] == $this->currency
        );
        return !$return;
    }
}
