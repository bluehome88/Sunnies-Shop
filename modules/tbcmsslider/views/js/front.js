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
*  @version  Release: $Revision$
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

jQuery(document).ready(function ($) {
/*************** Start Main Slider Js*****************************************/
    // $(window).on('load', function() {
    //     var tbMainSliderSpeed = $('.tbcmsmain-slider-wrapper').attr('data-speed');
    //     var tbMainSliderPause = '';
    //     if ($('.tbcmsmain-slider-wrapper').attr('data-pause-hover') == 'true') {
    //         tbMainSliderPause = true;
    //     }
    //     $('#tbmain-slider').nivoSlider({
    //           pauseTime: tbMainSliderSpeed,
    //           pauseOnHover: tbMainSliderPause,
    //           controlNav: true,
    //           controlNavThumbs: true,
    //           effect: "random"
    //     });
    // });

    // $('.tbcmsmain-prev').click(function(e){
    //     e.preventDefault();
    //     $('.nivo-directionNav .nivo-prevNav').trigger('click');
    //     $('.tbmain-slider-contant').fadeIn();
    // });

    // $('.tbcmsmain-next').click(function(e){
    //     e.preventDefault();
    //     $('.nivo-directionNav .nivo-nextNav').trigger('click');

    //     $('.tbmain-slider-contant').fadeIn();
    // });


    var tbMainSliderSpeed = $('.tbcmsmain-slider-wrapper').attr('data-speed');
    var tbMainSliderPause = '';
    if ($('.tbcmsmain-slider-wrapper').attr('data-pause-hover') == 'true') {
        tbMainSliderPause = true;
    }

    var rtlVal = false;
        if ($('body').hasClass('lang-rtl')) {
        var rtlVal = true;
    }

    var mainSliderHomePage = $('.tb-main-slider #tbmain-slider');
    mainSliderHomePage.owlCarousel({
      loop: true,
      dots: false,
      nav: false,
      rtl: rtlVal,
      autoplayTimeout: tbMainSliderSpeed,
      autoplayHoverPause: tbMainSliderPause,
      responsive: {
        0: { items: 1},
        320:{ items: 1, slideBy: 1},
        640:{ items: 1, slideBy: 1},
        768:{ items: 1, slideBy: 1},
        1024:{ items: 1, slideBy: 1},
        1399:{ items: 1, slideBy: 1},
      },
    });
    
    $('.tbmain-slider-next-pre-btn .tbcmsmain-prev').click(function(e){
      e.preventDefault();
      $('.tb-main-slider .owl-nav .owl-prev').trigger('click');
    });

    $('.tbmain-slider-next-pre-btn .tbcmsmain-next').click(function(e){
      e.preventDefault();
      $('.tb-main-slider .owl-nav .owl-next').trigger('click');
    });

    // mainSliderHomePage.on('translated.owl.carousel', function(event) {
    //   mainSliderHomePageJs();
    // });

    // function mainSliderHomePageJs()
    // {
    //   var num_index = $('#tbmain-slider .owl-dots').find('.owl-dot.active').index();
    //   $('.tbmain-slider-content-wrapper .tbmain-slider-content-inner').removeClass('active');
    //   $('.tbmain-slider-content-wrapper .tbmain-slider-content-inner').eq(num_index).addClass('active');
    // }

    // $(document).on('click', '.tbmain-slider-content-wrapper .tbmain-slider-content-inner', function(){
    //   var num_index = $(this).attr('data-index');
    //   $('.tbmain-slider-content-wrapper .tbmain-slider-content-inner').removeClass('active');
    //   $(this).addClass('active');
    //   $('#tbmain-slider .owl-dots .owl-dot').eq(num_index).trigger('click')
    // });

    mainSliderHomePageAnimation();
    mainSliderHomePage.on('translated.owl.carousel', function(event) {
      mainSliderHomePageAnimation();
    });
    function mainSliderHomePageAnimation()
    {
        $('#tbmain-slider .owl-item .tbmain-slider-contant').removeClass('show');
        $('#tbmain-slider .owl-item.active .tbmain-slider-contant').addClass('show');
    }

   
/*************** End Main Slider Js*****************************************/
});
