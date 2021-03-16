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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2019 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*
* Don't forget to prefix your containers with your own identifier
* to avoid any conflicts with others containers.
*/

$(document).ready(function(){
    $(".tball-pattern-show").click(function(){
        $('.tball-pattern-show').removeClass('tbcms_custom_setting_active');
        $(this).addClass('tbcms_custom_setting_active');
        var pattern = $(this).attr('id');
        $(document).find('#tbcmscustomsetting_pattern').val(pattern);
    });

    $(".tball-body-pattern-show").click(function(){
        $('.tball-body-pattern-show').removeClass('tbcms_custom_setting_body_active');
        $(this).addClass('tbcms_custom_setting_body_active');
        var pattern = $(this).attr('id');
        $(document).find('#tbcmscustomsetting_body_pattern').val(pattern);
    });

    var tab_number = $('#tbcmscustom-setting-tab-number').val();
    $('.tbcmsadmincustom-setting').find('.panel').hide();
    $(tab_number).show();


    $('.tbadmincustom-setting-tab').click(function(event){
        var tab_number = $(this).attr('tab-number');
        $('.tbadmincustom-setting-tab').removeClass('tbadmincustom-setting-active');
        $(this).addClass('tbadmincustom-setting-active');
        $('#tbcmscustom-setting-tab-number').val(tab_number);
        $('.tbcmsadmincustom-setting').find('.panel').hide();
        $(tab_number).show();
    });

    $('input[type=radio][name=TBCMSCUSTOMSETTING_BACKGROUND_IMAGE_PATTERN_STATUS]').on('change', function() {

        if($('.tbcmsbackground-type input#active_on[type=radio][name=TBCMSCUSTOMSETTING_BACKGROUND_IMAGE_PATTERN_STATUS]').is(':checked')){
                $(this).closest('.form-group').next().show();
                $(this).closest('.form-group').next().next().hide();
                $(this).closest('.form-group').next().next().next().hide();
                $(this).closest('.form-group').next().next().next().next().hide();
        }else{
                $(this).closest('.form-group').next().hide();
                $(this).closest('.form-group').next().next().show();        
                $(this).closest('.form-group').next().next().next().show();        
                $(this).closest('.form-group').next().next().next().next().show();        
        }
    });

    $('input[type=radio][name=TBCMSCUSTOMSETTING_ADD_CONTAINER]').on('change', function() {
         if ($('#TBCMSCUSTOMSETTING_ADD_CONTAINER_on').is(':checked')) {
            $(this).closest('.form-group').next().show();

            if($('.tbcmsbackground-type input#active_on[type=radio][name=TBCMSCUSTOMSETTING_BACKGROUND_IMAGE_PATTERN_STATUS]').is(':checked')){
                $(this).closest('.form-group').next().next().show();
            }else{
                $(this).closest('.form-group').next().next().next().show();    
                $(this).closest('.form-group').next().next().next().next().show();
                $(this).closest('.form-group').next().next().next().next().next().show();
            }
         }else{
            $(this).closest('.form-group').next().hide();
            $(this).closest('.form-group').next().next().hide();
            $(this).closest('.form-group').next().next().next().hide();
            $(this).closest('.form-group').next().next().next().next().hide();
            $(this).closest('.form-group').next().next().next().next().next().hide();
         }
    });



    $('input[type=radio][name=TBCMSCUSTOMSETTING_BODY_BACKGROUND_COLOR_STATUS]').on('change', function() {
        if ($('#TBCMSCUSTOMSETTING_BODY_BACKGROUND_COLOR_STATUS_on').is(':checked')) {
            $(this).closest('.form-group').next().show();

            if($('.tbcmsbody-background-type  input#active_on[type=radio][name=TBCMSCUSTOMSETTING_BODY_BACKGROUND_IMAGE_PATTERN_STATUS]').is(':checked')){
                $(this).closest('.form-group').next().next().show();
                $(this).closest('.form-group').next().next().next().hide();
                $(this).closest('.form-group').next().next().next().next().hide();
                $(this).closest('.form-group').next().next().next().next().next().hide();
            } else {
                $(this).closest('.form-group').next().next().hide();
                $(this).closest('.form-group').next().next().next().show();
                $(this).closest('.form-group').next().next().next().next().show();
                $(this).closest('.form-group').next().next().next().next().next().show();
            }
        }else{
            $(this).closest('.form-group').next().hide();
            $(this).closest('.form-group').next().next().hide();
            $(this).closest('.form-group').next().next().next().hide();
            $(this).closest('.form-group').next().next().next().next().hide();
            $(this).closest('.form-group').next().next().next().next().next().hide();
        }
    });


    $('input[type=radio][name=TBCMSCUSTOMSETTING_BODY_BACKGROUND_IMAGE_PATTERN_STATUS]').on('change', function() {
        if($('input#active_on[type=radio][name=TBCMSCUSTOMSETTING_BODY_BACKGROUND_IMAGE_PATTERN_STATUS]').is(':checked')){
            $(this).closest('.form-group').next().show();
            $(this).closest('.form-group').next().next().hide();
            $(this).closest('.form-group').next().next().next().hide();
            $(this).closest('.form-group').next().next().next().next().hide();
        }else{
            $(this).closest('.form-group').next().hide();
            $(this).closest('.form-group').next().next().show();        
            $(this).closest('.form-group').next().next().next().show();        
            $(this).closest('.form-group').next().next().next().next().show();        
        }
    });



    $('input[type=radio][name=TBCMSCUSTOMSETTING_CUSTOM_FONT_TITLE_COLOR_STATUS]').on('change', function() {
        if($('#TBCMSCUSTOMSETTING_CUSTOM_FONT_TITLE_COLOR_STATUS_on:checked').is(':checked')){
            console.log('asd');
            $(this).closest('.form-group').next().show();
            $(this).closest('.form-group').next().next().show();
            $(this).closest('.form-group').next().next().next().show();
        }else{
            console.log('zxc');
            $(this).closest('.form-group').next().hide();
            $(this).closest('.form-group').next().next().hide();
            $(this).closest('.form-group').next().next().next().hide();        
        }
    });

    $('input[type=radio][name=TBCMSCUSTOMSETTING_THEME_OPTION]').on('change', function(){
        var val = $(this).val();
        if(val.match(/theme_custom/g)){
            $(this).closest('.form-group').parent().parent().parent().next().show();
            $(this).closest('.form-group').parent().parent().parent().next().next().show();
        }else{
            $(this).closest('.form-group').parent().parent().parent().next().hide();
            $(this).closest('.form-group').parent().parent().parent().next().next().hide();
        }
    });


});
window.onload=function(){
    //theme option
    var val = $('input[type=radio][name=TBCMSCUSTOMSETTING_THEME_OPTION]:checked').val();
    if(typeof val != "undefined"){
            if(val.match(/theme_custom/g)){
                $('input[type=radio][name=TBCMSCUSTOMSETTING_THEME_OPTION]').closest('.form-group').parent().parent().parent().next().show();
                $('input[type=radio][name=TBCMSCUSTOMSETTING_THEME_OPTION]').closest('.form-group').parent().parent().parent().next().next().show();
            }else{
                $('input[type=radio][name=TBCMSCUSTOMSETTING_THEME_OPTION]').closest('.form-group').parent().parent().parent().next().hide();
                $('input[type=radio][name=TBCMSCUSTOMSETTING_THEME_OPTION]').closest('.form-group').parent().parent().parent().next().next().hide();
            }

        //box layout and full layout
        if($('#TBCMSCUSTOMSETTING_ADD_CONTAINER_off').is(':checked')){
            $('#TBCMSCUSTOMSETTING_ADD_CONTAINER_off').closest('.form-group').next().hide();
            $('#TBCMSCUSTOMSETTING_ADD_CONTAINER_off').closest('.form-group').next().next().hide();
            $('#TBCMSCUSTOMSETTING_ADD_CONTAINER_off').closest('.form-group').next().next().next().hide();
            $('#TBCMSCUSTOMSETTING_ADD_CONTAINER_off').closest('.form-group').next().next().next().next().hide();
            $('#TBCMSCUSTOMSETTING_ADD_CONTAINER_off').closest('.form-group').next().next().next().next().next().hide();
        }else{
            //bacground color or patten
            if($('.tbcmsbackground-type input#active_on[type=radio][name=TBCMSCUSTOMSETTING_BACKGROUND_IMAGE_PATTERN_STATUS]').is(':checked')){
                    $('#TBCMSCUSTOMSETTING_ADD_CONTAINER_off').closest('.form-group').next().next().show();
                    $('#TBCMSCUSTOMSETTING_ADD_CONTAINER_off').closest('.form-group').next().next().next().hide();
                    $('#TBCMSCUSTOMSETTING_ADD_CONTAINER_off').closest('.form-group').next().next().next().next().hide();
                    $('#TBCMSCUSTOMSETTING_ADD_CONTAINER_off').closest('.form-group').next().next().next().next().next().hide();
            }else{
                    $('#TBCMSCUSTOMSETTING_ADD_CONTAINER_off').closest('.form-group').next().next().hide();
                    $('#TBCMSCUSTOMSETTING_ADD_CONTAINER_off').closest('.form-group').next().next().next().show(); 
                    $('#TBCMSCUSTOMSETTING_ADD_CONTAINER_off').closest('.form-group').next().next().next().next().show(); 
                    $('#TBCMSCUSTOMSETTING_ADD_CONTAINER_off').closest('.form-group').next().next().next().next().next().show(); 
            }
        }
    }

    if($('#TBCMSCUSTOMSETTING_BODY_BACKGROUND_COLOR_STATUS_off').is(':checked')){
        $('#TBCMSCUSTOMSETTING_BODY_BACKGROUND_COLOR_STATUS_off').closest('.form-group').next().hide();
        $('#TBCMSCUSTOMSETTING_BODY_BACKGROUND_COLOR_STATUS_off').closest('.form-group').next().next().hide();
        $('#TBCMSCUSTOMSETTING_BODY_BACKGROUND_COLOR_STATUS_off').closest('.form-group').next().next().next().hide();
        $('#TBCMSCUSTOMSETTING_BODY_BACKGROUND_COLOR_STATUS_off').closest('.form-group').next().next().next().next().hide();
        $('#TBCMSCUSTOMSETTING_BODY_BACKGROUND_COLOR_STATUS_off').closest('.form-group').next().next().next().next().next().hide();
    }else{
        if($('.tbcmsbody-background-type input#active_on[type=radio][name=TBCMSCUSTOMSETTING_BODY_BACKGROUND_IMAGE_PATTERN_STATUS]').is(':checked')){
            $('#TBCMSCUSTOMSETTING_BODY_BACKGROUND_COLOR_STATUS_off').closest('.form-group').next().show();
            $('#TBCMSCUSTOMSETTING_BODY_BACKGROUND_COLOR_STATUS_off').closest('.form-group').next().next().show();
            $('#TBCMSCUSTOMSETTING_BODY_BACKGROUND_COLOR_STATUS_off').closest('.form-group').next().next().next().hide();
            $('#TBCMSCUSTOMSETTING_BODY_BACKGROUND_COLOR_STATUS_off').closest('.form-group').next().next().next().next().hide();
            $('#TBCMSCUSTOMSETTING_BODY_BACKGROUND_COLOR_STATUS_off').closest('.form-group').next().next().next().next().next().hide();
        } else {
            $('#TBCMSCUSTOMSETTING_BODY_BACKGROUND_COLOR_STATUS_off').closest('.form-group').next().show();
            $('#TBCMSCUSTOMSETTING_BODY_BACKGROUND_COLOR_STATUS_off').closest('.form-group').next().next().hide();
            $('#TBCMSCUSTOMSETTING_BODY_BACKGROUND_COLOR_STATUS_off').closest('.form-group').next().next().next().show(); 
            $('#TBCMSCUSTOMSETTING_BODY_BACKGROUND_COLOR_STATUS_off').closest('.form-group').next().next().next().next().show(); 
            $('#TBCMSCUSTOMSETTING_BODY_BACKGROUND_COLOR_STATUS_off').closest('.form-group').next().next().next().next().next().show(); 
        }
    }



    if($('#TBCMSCUSTOMSETTING_CUSTOM_FONT_TITLE_COLOR_STATUS_on:checked').is(':checked')){
        $('#TBCMSCUSTOMSETTING_CUSTOM_FONT_TITLE_COLOR_STATUS_on').closest('.form-group').next().show();
        $('#TBCMSCUSTOMSETTING_CUSTOM_FONT_TITLE_COLOR_STATUS_on').closest('.form-group').next().next().show();
        $('#TBCMSCUSTOMSETTING_CUSTOM_FONT_TITLE_COLOR_STATUS_on').closest('.form-group').next().next().next().show();
    }else{
        $('#TBCMSCUSTOMSETTING_CUSTOM_FONT_TITLE_COLOR_STATUS_on').closest('.form-group').next().hide();
        $('#TBCMSCUSTOMSETTING_CUSTOM_FONT_TITLE_COLOR_STATUS_on').closest('.form-group').next().next().hide();
        $('#TBCMSCUSTOMSETTING_CUSTOM_FONT_TITLE_COLOR_STATUS_on').closest('.form-group').next().next().next().hide();        
    }
}
