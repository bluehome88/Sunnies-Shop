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

/****************** Start Brand list Slider Js *******************************************/
$(document).ready(function(){

  var rtlVal = false;
    if ($('body').hasClass('lang-rtl')) {
    var rtlVal = true;
  }
  $('.tbcmsbrandlist-slider .tbbrandlist-slider-content-box').owlCarousel({
    loop: true,
    dots: false,
    nav: false,
    rtl: rtlVal,
    responsive: {
      0: { items: 1},
      320:{ items: 1, slideBy: 1},
      400:{ items: 2, slideBy: 1},
      768:{ items: 3, slideBy: 1},
      992:{ items: 4, slideBy: 1},
      1200:{ items: 5, slideBy: 1},
      1600:{ items: 5, slideBy: 1},
      1800:{ items: 5, slideBy: 1}
    },
  });
  // $('.tbcmsbrandlist-slider .tbleft-prev-btn').click(function(e){
  //   e.preventDefault();
  //   $('.tbcmsbrandlist-slider .owl-nav .owl-prev').trigger('click');
  // });

  // $('.tbcmsbrandlist-slider .tbleft-next-btn').click(function(e){
  //   e.preventDefault();
  //   $('.tbcmsbrandlist-slider .owl-nav .owl-next').trigger('click');
  // });


  $('.tbcms-brandlist-pagination-wrapper .tbcmsbrandprev-btn').click(function(e){
    e.preventDefault();
    $('.tbcmsbrandlist-slider .owl-nav .owl-prev').trigger('click');
  });

  $('.tbcms-brandlist-pagination-wrapper .tbcmsbrandnext-btn').click(function(e){
    e.preventDefault();
    $('.tbcmsbrandlist-slider .owl-nav .owl-next').trigger('click');
  });

  


  leftRightBrandListTitleToggle();
  $(window).resize(function(){
    leftRightBrandListTitleToggle();
  });


  function leftRightBrandListTitleToggle()
  {
    $('.tbcmsbrandlist-slider .tbcms-brandlist-pagination-wrapper').insertAfter('.tbcmsbrandlist-slider .tbleft-right-title');

    if(document.body.clientWidth <= 1199 ) {
        $('.tbcmsbrandlist-slider .tbcms-brandlist-pagination-wrapper').insertAfter('.tbcmsbrandlist-slider .tbbrandlist-slider-inner .tbbrandlist-slider-content-box');
    }
    
    $('.tbcmsbrandlist-slider .tbleft-right-title-toggle, .tbcmsbrandlist-slider .tbbrandlist-slider-inner').removeClass('open');
  }


  $('.tbcmsbrandlist-slider .tbleft-right-title-toggle').on('click',function(e){
    e.preventDefault();
    if(document.body.clientWidth <= 1199){
      if($(this).hasClass('open')) {
        $(this).removeClass('open');
        $(this).parent().parent().parent().find('.tbbrandlist-slider-inner').removeClass('open').stop(false).slideUp(500, "swing");
      } else {
        $(this).addClass('open');
        $(this).parent().parent().parent().find('.tbbrandlist-slider-inner').addClass('open').stop(false).slideDown(500, "swing");
      }
    }
    e.stopPropagation();
  });

});

/****************** End Brand list Slider Js *******************************************/