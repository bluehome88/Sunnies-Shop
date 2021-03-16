/**
* 2007-2019 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2019 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

/* global $ */
$(document).ready(function () {
    var $searchWidget = $('.tbcmsheader-search');
    var $searchBox    = $searchWidget.find('input[type=text]');
    // var searchURL     = $searchWidget.attr('data-search-controller-url');
    var searchURL     = baseDir + 'modules/tbcmsproductcompare/ajax.php';

    $(document).on('change','.tbcms-select-category',function(){
        $(this).find('option').removeClass('selected');
        $(this).find('option:selected').addClass('selected');
    });

    $(document).on('focusout','.tbsearch-top-wrapper .tbheader-top-search .tbcmssearch-words', function(){
        var obj = $(this).parent('tbsearch-header-display-wrappper').find('.tbsearch-result');
    });

    $(document).on('keyup','.tbcmsheader-search .tbsearch-header-display-wrappper .tbheader-top-search .tbheader-top-search-wrapper-info-box .tbcmssearch-words',function(){
        var obj = $(this).parent().parent().parent().parent().find('.tbsearch-result');
        obj.html('');
        obj.show();

        var search_words = $(this).val();
        var cat_id = $('.tbcms-select-category').find('.selected').val();

        if (search_words.length != 0) {
            $.ajax({
                type: 'POST',
                url: baseDir + 'modules/tbcmssearch/ajax.php?',
                cache: false,
                data: 'search_words='+ search_words + '&category_id='+ cat_id +' &token=' + static_token,
                success: function(data)
                {
                    obj.html('');
                    obj.append(data);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                }
            });
        }
    });


    $(document).on('click', '.tbsearch-result .tbsearch-dropdown-close', function(){
        $('.tbsearch-result').html('');
        $('.tbcmssearch-words').val('');
    });

    $(document).on('click', '.tbsearch-result .tbsearch-more-search', function(){
         $(this).parent().parent().parent().parent().find('.tbheader-top-search-wrapper button').trigger('click');
    });


    /********************* Start Search DropDown js *****************************************/
    $('#header .tbsearch-top-wrapper .tbsearch-close').hide();
    $(document).on('click','#header .tbsearch-top-wrapper .tbsearch-open',function(){
        removeDefaultDropdown();    
        $('#header .tbsearch-top-wrapper .tbsearch-open').hide();
        $('#header .tbsearch-top-wrapper .tbsearch-close').show();
        $('#header .tbsearch-header-display-wrappper').addClass('open');
        $('body').addClass('tbactive-search');
    });
    $(document).on('click','#header .tbsearch-close',function(){
        $('#header .tbsearch-top-wrapper .tbsearch-open').show();
        $('#header .tbsearch-top-wrapper .tbsearch-close').hide();
        
        $('#header .tbcmssearch-words').val('');
        $(this).parent().parent().parent().find('.tbsearch-result').html('');

        $('#header .tbsearch-header-display-wrappper').removeClass('open');
        $('body').removeClass('tbactive-search');
    });
    /********************* End Search DropDown js *****************************************/


    // close dropdown When open other dropdown in mobile view
    function removeDefaultDropdown()
    {
        // Header My Account Dropdown
        $('#header .tb-account-dropdown').removeClass('open');
        $('#header').find('.tbcms-header-myaccount .tb-myaccount-btn').removeClass('open');
        $('#header').find('.tbcms-header-myaccount .tb-account-dropdown').removeClass('open').hide();

        // Header Search Dropdown
        // $('#header .tbcmsheader-search .tbsearch-open').show();
        // $('#header .tbcmsheader-search .tbsearch-close').hide();
        // $('#header .tbcmsheader-search .tbsearch-header-display-wrappper').removeClass('open');
        // $('body').removeClass('tbactive-search');

        // Header My Account Dropdown
        $('#header .tb-account-dropdown').removeClass('open');
        $('#header').find('.tbcms-header-myaccount .tb-myaccount-btn').removeClass('open');
        $('#header').find('.tbcms-header-myaccount .tb-account-dropdown').removeClass('open').hide();

        if (document.body.clientWidth <= mobileViewSize) {
            // horizontal menu
            $('#tbcms-mobile-view-header .tbmenu-button').removeClass('open');
            $('#tbcmsmobile-horizontal-menu #tb-top-menu').removeClass('open');
        
            // Cart Dropdown
            $('.hexcms-header-cart .tbcmscart-show-dropdown').removeClass('open');

            // Vertical Menu DropDown
            $('.tbcmsvertical-menu .tballcategories-wrapper tbleft-right-title-toggle, .tbcmsvertical-menu .tbverticalmenu-dropdown').removeClass('open');
        } else {
            // Vertical Menu DropDown
            $('.tbcmsvertical-menu .tballcategories-wrapper').removeClass('open');
            $('.tbcmsvertical-menu .tbverticalmenu-dropdown').removeClass('open').removeAttr('style');
        }
    }
    

});
