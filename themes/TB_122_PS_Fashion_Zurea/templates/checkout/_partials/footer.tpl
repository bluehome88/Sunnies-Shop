{**
 * 2007-2019 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
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
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2019 PrestaShop SA
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}

{strip}

{hook h="displayWrapperBottom"}
<div class="tbfooter-before">
        <div class="container-fluid">
            <div class="tb-newsletter-app-wrapper container">
                <div class="row">
                    {block name='hook_footer_before'}
                        {hook h='displayFooterBefore'}
                    {/block}
                </div>
            </div>
        </div>
    </div> 
<div class="footer-container">

    <div class="container tbfooter-logo-link-wrapper">
        <div class='row'>
            {block name='hook_footer_part_1'}
                {hook h='displayFooterPart1'}
            {/block}
        </div>

        {block name='hook_footer_part_2'}
            {hook h='displayFooterPart2'}
        {/block}


        <div class='tbbottom-to-top'>
            <a href="javascript:" class="tbbottom-to-top-icon">
                <span>{l s='Go Top' d='Shop.Theme.Catalog'}</span>
                <i class='material-icons'>&#xe316;</i>
            </a>
        </div>
    </div>

    <div class="tbfooter-copy-payment-icon-wrapper bottom-to-top hb-animate-element">
        <div class="container tbfooter-copy-payment-icon-content">
            <div class="row tbfooter-copy-payment-icon-inner">
                {hook h='displayDownloadApps'}
                {hook h='displayCopyRightText'}
                {hook h='displayPaymentIcon'}
            </div>
        </div>
    </div>
</div>
{/strip}