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
*/

var isCallAjax = false;
$(window).load(function(){ 

function gettbcmsthemeoptions(){
  if(!isCallAjax){
      $.ajax({
          type: 'POST',
          url: getThemeOptionsLink,
          success: function(data){
            $('body').prepend(data);
            $('#themecolor1').minicolors();
            $('#themecolor2').minicolors();
            $('#themebgcolor2').minicolors();
            $('#themebodybgcolor').minicolors();
            $('#themeCustomTitleColor').minicolors();         
            loadJs();
            getCustomSetting();
            getCustomFontSettingOnPageLoad();
          },
          error: function(jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
          }
      });
    }
    isCallAjax = true;
  }
  
  $(window).scroll(function(){
    gettbcmsthemeoptions();
  });
  function getUrlVars(){
      var vars = [], hash;
      var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
      for(var i = 0; i < hashes.length; i++){
          hash = hashes[i].split('=');
          vars.push(hash[0]);
          vars[hash[0]] = hash[1];
      }
      return vars;
    }//function getUrlVars()

    /******start init*******/
    var storage = $.localStorage;
    var cssPath = prestashop.urls.css_url;
    var demo_theme = getUrlVars()["demo-theme"];



  /******end init*******/
  function replaceAll(str, find, replace) {
    return str.replace(new RegExp(find, 'g'), replace);
  }

  function setCustomeTheme(){ 
    if ((storage.get("theme_color") != undefined &&  storage.get("theme_color") != '') || (storage.get("theme_color2") != undefined &&  storage.get("theme_color2") != '')) {
      $('#themecolor1').val(storage.get("theme_color"));
      $('#themecolor2').val(storage.get("theme_color2"));
    $.get( cssPath+"theme-custom.css", function( data ) {
     $('.tbcms-custom-theme').html('');
        data = replaceAll(data,'#maincolor1',storage.get("theme_color"));
        data = replaceAll(data,'#maincolor2',storage.get("theme_color2"));
        $(".tbcms-custom-theme").html( "<style>"+data+"</style>");     
    });
  }
  }
  function setBoxLayout(obj){
      // if($(obj).find('.toggle.btn.btn-default').hasClass('off')){
      if($(obj).prop("checked") == true){
        $('.tb-main-div').addClass('tb-box-layout container');
        $('.tb-main-div').removeClass('tb-full-layout');

        $('.tbtheme-background-patten, .tbtheme-background-color').show();
        if(storage.get("theme-bg-status")){
          $('body').css('background-image',storage.get("theme-bg-pattern"));
          $('body').css('background-color','');
        }else{
          $('body').css('background-color', storage.get("theme-bg-color"));
          $('body').css('background-image', "");
        }          
        storage.set("box-layout", true);//save localStorage
      }else{
        $('.tb-main-div').addClass('tbcms-full-layout');
        $('.tb-main-div').removeClass('tb-box-layout container');

        $('.tbtheme-background-patten, .tbtheme-background-color').hide();
        storage.set("box-layout", false);//save localStorage
      }
    }
    function getPattenSetting(obj){
        $(obj).addClass('active');
        $('.tbtheme-pattern-image').removeClass("active");
        $('.tbtheme-pattern-image').each(function(){
          if ("url("+$(this).attr('data-img')+")" == storage.get("theme-bg-pattern")) {
            $(this).addClass('active');            
          }
        });
        $('body').css('background-image',"url("+storage.get("theme-bg-pattern")+")");
        $('body').css('background-color','');
    }
    function getBgColorSetting(obj){
    $('body').css('background-color', storage.get("theme-bg-color"));
        $('body').css('background-image', "");
        obj.parent().find('.minicolors-swatch-color').css('background-color',storage.get("theme-bg-color"));
    }
  function getCustomSetting(){
    if(!storage.get("themeControl")){
          resetCustomSetting();
      }
      $('.tbselect-theme #select_theme option[value="' + storage.get("theme") + '"]').prop('selected', true);
      setCustomeTheme();
    if($('.tbselect-theme #select_theme').val().match(/theme_custom/g)){
      $('.tbtheme-color-one').show();
      $('.tbtheme-color-two').show();
      $('#themecolor1').parent().find('.minicolors-swatch-color').css('background-color',storage.get("theme_color"));
      $('#themecolor2').parent().find('.minicolors-swatch-color').css('background-color',storage.get("theme_color2"));
    }else{
      $('.tbtheme-color-one').hide();
      $('.tbtheme-color-two').hide();
    }
      if(storage.get("box-layout")){
        var obj = $(".tbtheme-box-layout-option");
        setBoxLayout(obj);
        if(storage.get("theme-bg-status")){
          getPattenSetting($('.tbtheme-pattern-image'));
        }else{
          getBgColorSetting($('#themebgcolor2'));
        }
        obj.trigger('click');
      }else{
        $('.tbtheme-background-patten, .tbtheme-background-color').hide();
      }

      if(storage.get("menu-sticky")){
        $('.tbtheme-menu-sticky-option').trigger('click');
      }

     
  }
  function resetCustomSetting(){
    storage.removeAll();
      storage.set("themeControl", true);
      storage.set("theme", "");
      storage.set("theme_color", "");
      storage.set("theme_color2", "");
      storage.set("box-layout", false);
      storage.set("menu-sticky", true);
      storage.set("themeColor1", '#fff');//default color1
      storage.set("themeColor2", '#fff');//default color1
      storage.set("theme-bg-pattern", "url("+prestashop.urls.img_url+"pattern/pattern14.png)");//save storage
      storage.set("theme-bg-color", '#000');//default color2
      storage.set("theme-bg-status", true);//patten bg dafault on box layout on*/
      storage.set("theme-body-bgcolor-status", false);
      storage.set("theme-body-bgcolor-effect", '');
      storage.set("theme-body-bgcolor", "");
      storage.set("theme-body-bgimage", "");
      storage.set("theme-custom-font-1", "");
      storage.set("theme-custom-font-link-1", "");
      storage.set("theme-custom-font-2", "");
      storage.set("theme-custom-font-link-2", "");
      storage.set("theme-title-color", "");

  }
function getCustomFontSettingOnPageLoad()
    {
      
      if (storage.get("theme-body-bgcolor-status") == true) {
        $('.tbtheme-body-background-option').trigger('click');
        $('.tbtheme-body-bgcolor').show();
        $('.tbtheme-body-background-patten').show();

        if (storage.get("theme-body-bgcolor-effect").trim() == 'color') {

          $('.tb-main-div').css('background-color', storage.get("theme-body-bgcolor"));
          $('#themebodybgcolor').val(storage.get("theme-body-bgcolor"));
          $('#themebodybgcolor').parent().find('.minicolors-swatch-color').css('background-color',storage.get("theme-body-bgcolor"));

        } else{
          $('.tb-main-div').css('background-image',storage.get('theme-body-bgimage'));
          storage.get('theme-body-bgimage');
        }
      } else {
        $('.tbtheme-body-bgcolor').hide();
        $('.tbtheme-body-background-patten').hide();
      }

      setCustomeFontTheme(1);//title font 1
      setCustomeFontTheme(2);//body font 2
      setCustomeFontTheme(3);//title color 3
      
    }
    function setCustomeFontTheme($dataCall){
  var returnData ="";
      /******************Title Font********************/
      if (storage.get("theme-custom-font-1") != undefined && storage.get("theme-custom-font-1") != '' && $dataCall == 1) {
        $('.tbselect-title-font-1-select').val(storage.get("theme-custom-font-1"));
        $.get( cssPath+"theme-custom-title-font.css", function( data ) {
          var link_1 = '';
          data = replaceAll(data,'#fontFamily1', storage.get("theme-custom-font-1"));
          link_1 = '@import url(\''+storage.get("theme-custom-font-link-1")+'\');';
          returnData += link_1+' '+data;
          // $(".tbcms-custom-font").html($(".tbcms-custom-font").html()+"<style>"+returnData+"</style>");
          $('.tbcms-custom-font-1').html("<style>"+returnData+"</style>");
        });
      } else if (storage.get("theme-custom-font-1") == '') {
        $('.tbcms-custom-font-1').html('');
      }
      /******************title color********************/
      if (storage.get("theme-title-color") != undefined &&  storage.get("theme-title-color") != ''  && $dataCall == 3) {
        $.get( cssPath+"theme-custom-title-color.css", function( data ) {
          data = replaceAll(data,'#customTitleColor',storage.get("theme-title-color"));
          returnData = data;
          $('#themeCustomTitleColor').val(storage.get("theme-title-color"));
          $('#themeCustomTitleColor').parent().find('.minicolors-swatch-color').css('background-color',storage.get("theme-title-color"));
          // $(".tbcms-custom-font").html($(".tbcms-custom-font").html()+"<style>"+returnData+"</style>");
          $('.tbcms-custom-color').html('<style>'+returnData+'</style>');
        });
      } else if (storage.get("theme-title-color") == '') {
          $('.tbcms-custom-color').html('');
      }
      /******************Body Font********************/
      if (storage.get("theme-custom-font-2") != undefined && storage.get("theme-custom-font-2") != '' && $dataCall == 2) {
        $('.tbselect-title-font-2-select').val(storage.get("theme-custom-font-2"));
        $.get( cssPath+"theme-custom-body-font.css", function( data ) {
          var link_2 = '';
          data = replaceAll(data,'#fontFamily2',storage.get("theme-custom-font-2"));
          link_2 = '@import url(\''+storage.get("theme-custom-font-link-2")+'\');';
          returnData = link_2+' '+data;
          // $(".tbcms-custom-font").html($(".tbcms-custom-font").html()+"<style>"+returnData+"</style>");
          $('.tbcms-custom-font-2').html('<style>'+returnData+'</style>');
        });
      } else if (storage.get("theme-custom-font-2") == '') {
        $('.tbcms-custom-font-2').html('');
      }
    }
 function loadJs(){
  $(".tbcms-custom-font-1").html('');
    $(".tbcms-custom-font-2").html('');
    $(".tbcms-custom-color").html('');
  $('.tbcmstheme-control .tbtheme-control-icon').click(function(){
    var themeOptionWrapper = $('.tbcmstheme-control .tbtheme-control-wrapper');
    if(themeOptionWrapper.hasClass('open')){
      themeOptionWrapper.removeClass('open');
      $('.tbcmstheme-control').removeClass('open');
    }else{
      themeOptionWrapper.addClass('open');
      $('.tbcmstheme-control').addClass('open');
    }
  });

  $('.tbselect-theme #select_theme').on('change',function(e){
      e.preventDefault();
      var themeVal = $(this).val();
      var themeColorVal = $('option:selected', this).attr('data-color');
      var themeColorVal2 = $('option:selected', this).attr('data-color-two');
      storage.set("theme", themeVal);//save localStorage
      storage.set("theme_color", themeColorVal);//save localStorage
      storage.set("theme_color2", themeColorVal2);//save localStorage
      $('.tbtheme-color-one').hide();
      $('.tbtheme-color-two').hide();
      if(themeVal == ""){
        $('.tbcms-custom-theme').html("");
        $('.minicolors .themecolor1').hide();
        $('.minicolors .themecolor2').hide();
      }else if(themeVal.match(/theme_custom/g)){
          $('.tbtheme-color-one').show();
          $('.tbtheme-color-two').show();
          var theme_color = $('#themecolor1').val();
          var theme_color2 = $('#themecolor2').val();
          storage.set("theme_color", theme_color);
          storage.set("theme_color2", theme_color2);
          setCustomeTheme();
      }else{
          setCustomeTheme();
      }
    });
    
    $('#themecolor1').on('change',function(e){      
      var theme_color = $(this).val();
      storage.set("theme_color", theme_color);
       setCustomeTheme();
    });

    $('#themecolor2').on('change',function(e){
      var theme_color2 = $(this).val();
      storage.set("theme_color2", theme_color2);
      setCustomeTheme();
    });
   
    $('.tbtheme-box-layout-option').on('click',function(e){
      setBoxLayout(this);
    }); 
    $('.tbtheme-pattern-image').on('click',function(e){
      $('.tbtheme-pattern-image').removeClass('active');
        $(this).addClass('active');
        $('body').css('background-image',"url("+$(this).attr('data-img')+")");
        storage.set("theme-bg-pattern", "url("+$(this).attr('data-img')+")");//save localStorage
        $('body').css('background-color','');
        storage.set("theme-bg-status",true);
    });
     $('#themebgcolor2').on('change',function(e){
        $('body').css('background-color', $(this).val());
        $('body').css('background-image', "");     
        storage.set("theme-bg-status", false);
        storage.set("theme-bg-color", $(this).val());
    });

    $('.tbtheme-body-background').on('click', function(){
      if ($('.tbtheme-body-background-option').prop("checked") == true) {
        $('.tbtheme-body-bgcolor').hide();
        $('.tbtheme-body-background-patten').hide();
        $('.tb-main-div').removeAttr('style');
        storage.set("theme-body-bgcolor-status", false);
      } else {
        $('.tbtheme-body-bgcolor').show();
        $('.tbtheme-body-background-patten').show();
        var val = storage.get("theme-body-bgcolor");
        $('.tb-main-div').css('background-color', val);
        storage.set("theme-body-bgcolor-status", true);
      }
    });

    $('#themebodybgcolor').on('change',function(e){
      $('.tb-main-div').removeAttr('style');
        $('.tb-main-div').css('background-color', $(this).val());
        storage.set("theme-body-bgcolor", $(this).val());
        storage.set("theme-body-bgcolor-effect", 'color');
    });

    $('.tbtheme-body-pattern-image').on('click',function(e){
      $('.tbtheme-body-pattern-image').removeClass('active');
        $('.tb-main-div').removeAttr('style');
        $(this).addClass('active');
        // $(this).css('background-image')
        var tmp = $(this).attr('data-img');
        $('.tb-main-div').css('background-image','url('+tmp+')');
        storage.set("theme-body-bgimage", 'url('+tmp+')');//save localStorage
        storage.set("theme-body-bgcolor-effect", 'image');
    });
    

    $('.tbselect-title-font-1 #select_title_font_1').on('change',function(e){
      var font_title = $(this).val();
      var font_link = $(this).find('option:selected').attr('data-font-link');
      storage.set("theme-custom-font-1", font_title);
      storage.set("theme-custom-font-link-1", font_link);
      setCustomeFontTheme(1);
    });

    $('.tbselect-title-font-2 #select_title_font_2').on('change',function(e){
      var font_title = $(this).val();
      var font_link = $(this).find('option:selected').attr('data-font-link');
      storage.set("theme-custom-font-2", font_title);
      storage.set("theme-custom-font-link-2", font_link);
      setCustomeFontTheme(2);
    });

    $('#themeCustomTitleColor').on('change',function(e){
      storage.set("theme-title-color", $(this).val());
      setCustomeFontTheme(3);
    });


    $('.tbtheme-menu-sticky-option').on('click',function(e){
      if($(this).prop("checked") == true){
        storage.set("menu-sticky", true);//save localStorage
      } else {
        storage.set("menu-sticky", false);//save localStorage  
      }
    });

    $('.tbtheme-control-reset').on('click',function(e){
      resetCustomSetting();
      location.reload(); 
    });
  }
});