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

$(document).ready(function(){

    $(document).on('click','.tbcmsproduct-compare-btn', function(e){
        e.preventDefault();
        var t = $(this);
        var id_product = $(this).attr('data-product-id');
        var comp_val = $(this).attr('data-comp-val');

        // console.log(baseDir + 'modules/tbcmsproductcompare/ajax.php?');
        var parents = $('.tbcompare-wrapper.product_id_' + id_product + ' .tbcmsproduct-compare-btn');
        $.ajax({
            type: 'POST',
            url: baseDir + 'modules/tbcmsproductcompare/ajax.php?',
            cache:false,
            data: 'id_product='+ id_product + '&comp_val='+ comp_val + '&token=' + static_token,
            success: function(data)
            {
                var message;
                var arr_data = data.split('##');
                var notic = arr_data[0];
                var full_notic = arr_data[1];
                var tot_comp_prod = arr_data[2];

                if (notic == 'add_compare_prod') {
                    parents.find('.add').addClass('hide');
                    parents.find('.remove').removeClass('hide');
                    parents.attr('data-comp-val','remove');
                    message = full_notic;
                } else if (notic == 'full_compare_prod') {
                    message = full_notic;
                } else if (notic == 'product_remove') {
                    parents.attr('data-comp-val','add');
                    parents.find('.remove').addClass('hide');
                    parents.find('.add').removeClass('hide');
                    message = full_notic;

                } else {
                    message = 'Connection Error';
                }

                var count = $(document).find('.tbcmscount-compare-product');
                count.each(function(){
                    $(this).find('.count-product').html(tot_comp_prod);
                });
                count.find('.tbsticky-compare').find('.count-product').html('( '+tot_comp_prod+' )');
                
                // Desktop View
                $(document).find('.tbcmsdesktop-view-compare').find('.count-product').html('('+tot_comp_prod+')');
                // Mobile View
                $(document).find('.tbcmsmobile-view-compare').find('.count-product').html('('+tot_comp_prod+')');


                if (!!$.prototype.fancybox)
                    $.fancybox.open([
                        {
                            type: 'inline',
                            autoScale: true,
                            minHeight: 30,
                            content: '<p class="fancybox-error"> ' + full_notic + ' </p>'
                        }
                    ], {
                        padding: 0
                    });
                else 
                    alert(full_notic);

            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
            }
        });
    });

    $('.tbcmsproduct-compare-list').click(function(){
        var t = $(this);
        var id_product = $(this).attr('data-product-id');
        var comp_val = $(this).attr('data-comp-val');
        var parents = $('.tbcms-compare-product-'+id_product);
        $.ajax({
            type: 'POST',
            url: baseDir + 'modules/tbcmsproductcompare/ajax.php?',
            cache:false,
            data: 'id_product='+ id_product + '&comp_val='+ comp_val + '&token=' + static_token,
            success: function(data)
            {
                var message;
                var arr_data = data.split('##');
                var notic = arr_data[0];
                var full_notic = arr_data[1];
                var tot_comp_prod = arr_data[2];

                // console.log(parents);
                if (notic == 'product_remove') {

                    parents.attr('data-comp-val','add');
                    parents.find('.remove').addClass('hide');
                    parents.find('.add').removeClass('hide');
                    message = full_notic;

                } else {
                    message = 'Connection Error';
                }

                if(tot_comp_prod>00) {
                    $('.tbcms-compare-product-'+id_product).hide('slow');
                } else {
                    $('#product_comparison').removeClass('active');
                    $('#no_product_comparison').addClass('active');
                }

                var count = $(document).find('.tbcmscount-compare-product');
                count.each(function(){
                    $(this).find('.count-product').html(tot_comp_prod);
                });
                count.find('.tbsticky-compare').find('.count-product').html('( '+tot_comp_prod+' )');

                // Desktop View
                $(document).find('.tbcmsdesktop-view-compare').find('.count-product').html('('+tot_comp_prod+')');
                // Mobile View
                $(document).find('.tbcmsmobile-view-compare').find('.count-product').html('('+tot_comp_prod+')');



                if (!!$.prototype.fancybox)
                    $.fancybox.open([
                        {
                            type: 'inline',
                            autoScale: true,
                            minHeight: 30,
                            content: '<p class="fancybox-error"> ' + full_notic + ' </p>'
                        }
                    ], {
                        padding: 0
                    });
                else 
                    alert(full_notic);

            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
            }
        });
    })
});
