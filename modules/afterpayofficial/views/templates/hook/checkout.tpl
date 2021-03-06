{*
 * This file is part of the official Afterpay module for PrestaShop.
 *
 * @author    Afterpay <integrations@afterpay.com>
 * @copyright 2020 Afterpay
 * @license   proprietary
 *}
<style>
    p.payment_module.Afterpay.ps_version_1-7 {
        margin-left: -5px;
        margin-top: -15px;
        margin-bottom: 0px;
    }
    p.payment_module a.afterpay-checkout {
        background: url('{$ICON|escape:'htmlall':'UTF-8'}') 5px 5px no-repeat #fbfbfb;
        background-size: 80px;
    }
    p.payment_module a.afterpay-checkout.ps_version_1-7 {
        background: none;
    }
    .payment-option img[src*='static.afterpay.com'] {
        height: 25px;
        padding-left: 5px;
        content:url('{$LOGO_BADGE|escape:'htmlall':'UTF-8'}');
    }
    p.payment_module a.afterpay-checkout.ps_version_1-6 {
        background-color: #fbfbfb;
        font-size: 1.3em;
    }
    p.payment_module a.afterpay-checkout.ps_version_1-6:after {
        display: block;
        content: "\f054";
        position: absolute;
        right: 15px;
        margin-top: -11px;
        top: 50%;
        font-family: "FontAwesome";
        font-size: 25px;
        height: 22px;
        width: 14px;
        color: #777;
    }
    p.payment_module a:hover {
        background-color: #f6f6f6;
    }

    #afterpay-method-content {
        color: #7a7a7a;
        border: 1px solid #000;
        margin-bottom: 10px;
    }
    .afterpay-header {
        color: #7a7a7a;
        position: relative;
        text-align: center;
        background-color: #b2fce4;
        padding: 5px 10px 10px 0px;
        overflow: visible;
    }
    .afterpay-header img {
        height: 28px;
    }

    .afterpay-header-img {
        display: inline;
    }

    .afterpay-header-text1 {
        display: inline;
        text-align: center;
        color: black;
        font-weight: bold;
    }
    .afterpay-header-text2 {
        display: inline-block;
        text-align: center;
    }
    .afterpay-checkout-ps1-6-logo {
        height: 45px;
        margin-left: 10px;
        top: 25%;
        position: absolute;
    }
    .afterpay-more-info-text {
        padding: 1em 1em;
        text-align: center;
    }
    .afterpay-more-info {
        text-align: center !important;
    }

    .ps-afterpay-container {
        display: grid;
        max-width: 750px;
        height: auto;
        grid-template-columns: repeat(4, 1fr);
        grid-template-rows: min-content  min-content  min-content;
        grid-template-areas:
      "ap-ps-checkout-header  ap-ps-checkout-header  ap-ps-checkout-header  ap-ps-checkout-header"
      "ap-placement-wrapper ap-placement-wrapper ap-placement-wrapper ap-placement-wrapper"
      "ap-placement-wrapper ap-placement-wrapper ap-placement-wrapper ap-placement-wrapper"
    }

    .ap-ps-checkout-header {
        grid-area: ap-ps-checkout-header ;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        align-content: center;
        background-color: #b2fce4;
        color: #7a7a7a;
        flex-direction: row;
        float: left;
        font-size: .875rem;
        justify-content: center;
        min-height: 35px;
        position: relative;
        text-align: center;
        width: 100%;
        padding: 7px 0 !important;
    }

    .ap-ps-header-row .ap-ps-header-img {
        align-self: center;
        width: 170px;
        max-height: 50px;
        max-width: 170px;
    }

    .ap-row-text {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        flex-direction: column;
    }

    .ap-ps-header-row {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        flex-wrap: nowrap;
        max-height: fit-content;
        justify-content: center;
        align-items: center;
    }

    .ap-row-text .ap-header-text {
        max-height: fit-content;
        align-self: center;
        -webkit-flex-wrap: wrap;
        flex-wrap: wrap;
        margin-bottom: 0 !important;
    }

    .ap-placement-container {
        padding: 7px;
    }


    .ap-ps-placement-wrapper {
        grid-area: ap-placement-wrapper;
        display: inline-flex;
        justify-content: center;
        flex-direction: column;
    }

    .ap-ps-placement-wrapper .ap-ps-checkout-more-info-text {
        margin: 10px;
        text-align: center;
        font-size: .800rem;
    }

    .ap-terms-wrapper{
        display: inline-flex;
        flex-direction: row;
        justify-content: center;
        font-size: 14px;
    }
    .afterpay-terms-link {
        display: inline-block;
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding-bottom: 7px;
        padding-left: 7px;
        padding-right: 7px;
    }

    @media only screen and (max-width: 575px) {

        .ap-ps-header-img {
            align-self: center;
            width: 100px;
        }

        .ap-ps-header-row {
            flex-direction: column;
        }
        .ap-terms-wrapper{
            display: inline-flex;
            flex-direction: column;
            justify-content: center;
            font-size: 12px;
        }
        .afterpay-terms-link {
            display: inline-block;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding-bottom: 7px;
            font-size: 12px;
            padding-left: 5px;
            padding-right: 5px;
        }

    }
</style>
{if $PS_VERSION !== '1-7'}
    <div class="row">
        <div class="col-xs-12 col-md-12">
            <p class="payment_module">
                <a class="afterpay-checkout afterpay-checkout ps_version_{$PS_VERSION|escape:'htmlall':'UTF-8'}" href="{$PAYMENT_URL|escape:'htmlall':'UTF-8'}">
                    {$TITLE|escape:'htmlall':'UTF-8'}
                    <img class="afterpay-checkout-ps{$PS_VERSION|escape:'htmlall':'UTF-8'}-logo" src="{$LOGO_BADGE|escape:'htmlall':'UTF-8'}">
                </a>
            </p>
        </div>
    </div>

{/if}
{if $PS_VERSION === '1-7'}
    <section>
        <div class="payment-method ps_version_{$PS_VERSION|escape:'htmlall':'UTF-8'}" id="afterpay-method">
            <div class="payment-method-content afterpay ps_version_{$PS_VERSION|escape:'htmlall':'UTF-8'}" id="afterpay-method-content">
                <div class="ps-afterpay-container">
                    <div class="ap-ps-checkout-header">
                        <div class="ap-row-text">
                            <div class="ap-ps-header-row">
                                <img class="ap-ps-header-img" src="{$LOGO_BADGE|escape:'htmlall':'UTF-8'}">
                            </div>
                        </div>
                    </div>
                    <div class="ap-ps-placement-wrapper">
                        <div class="ap-more-info-container">
                            <p class="ap-ps-checkout-more-info-text">{$DESCRIPTION|escape:'htmlall':'UTF-8'}</p>
                        </div>
                        <div class="ap-placement-container">
                            <afterpay-placement
                                    data-type="price-table"
                                    data-amount="{$TOTAL_AMOUNT|escape:'htmlall':'UTF-8'}"
                                    data-price-table-theme="white"
                                    data-mobile-view-layout="{$AP_MOBILE_LAYOUT|escape:'htmlall':'UTF-8'}"
                                    data-locale="{$ISO_COUNTRY_CODE|escape:'htmlall':'UTF-8'}"
                                    data-currency="{$CURRENCY|escape:'htmlall':'UTF-8'}">
                            </afterpay-placement>
                        </div>
                        <div class="ap-terms-wrapper">
                            {if $ISO_COUNTRY_CODE == 'es_ES' }
                                <a class="afterpay-terms-link" href="{$TERMS_AND_CONDITIONS_LINK|escape:'htmlall':'UTF-8'}" TARGET="_blank">
                                    {$TERMS_AND_CONDITIONS|escape:'htmlall':'UTF-8'}
                                </a>
                                {if $IS_MOBILE_LAYOUT == "0"}
                                    &nbsp;|&nbsp;
                                {else}
                                    &nbsp;-&nbsp;
                                {/if}
                                <a class="afterpay-terms-link" href="javascript:void(0)" onclick="Afterpay.launchModal('{$ISO_COUNTRY_CODE|escape:'javascript':'UTF-8'}');">
                                    {$MORE_INFO_TEXT|escape:'htmlall':'UTF-8'}
                                </a>

                            {else}
                                <a class="afterpay-terms-link" href="{$TERMS_AND_CONDITIONS_LINK|escape:'htmlall':'UTF-8'}" TARGET="_blank">
                                    {$TERMS_AND_CONDITIONS|escape:'htmlall':'UTF-8'}
                                </a>
                            {/if}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
{/if}