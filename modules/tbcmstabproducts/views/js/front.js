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

var mobileViewSize  = 991;

$(window).load(function(){
 /********************** Start Tab js *****************************/
  $('.tab-index').click(function(e){
    $id = $(this).attr('data-tab-data');
    $paging = $(this).attr('data-tab-paging');
    $('.tab-index').removeClass('active');
    
    $('.tbcmstab-product').removeClass('active');
    $('.tbtab-pagination').removeClass('active');

    $('.tbcmstab-product').hide();
    $('.tbtab-pagination').hide();

    $(this).addClass('active');
    $('#'+$id).addClass('active').fadeIn('500');
    $('.'+$paging+'-pagination').addClass('active').show();
  });

  $('.tbcmstab-product.active').show();
  $('.tbtab-pagination.active').show();

  $('.tbcmstab-title-product .tbtab-pagination-wrapper').insertAfter('.tbcmstab-title-product .tbtab-title');

/********************** End Tab js *****************************/

/****************** Start Tab Product Slider Js *******************************************/
var owlClass = [
  //['slider className','navigation nextClass','navigation prevClass']
  ['.tbtab-featured-product .tbproduct-wrapper-content-box','.tbtab-featured-product-next','.tbtab-featured-product-prev'],
  ['.tbtab-new-product .tbproduct-wrapper-content-box','.tbtab-new-product-next','.tbtab-new-product-prev'],
  ['.tbtab-special-product .tbproduct-wrapper-content-box','.tbtab-special-product-next','.tbtab-special-product-prev'],
  ['.tbtab-best-seller-product .tbproduct-wrapper-content-box','.tbtab-best-seller-product-next','.tbtab-best-seller-product-prev'],
];

var rtlVal = false;
  if ($('body').hasClass('lang-rtl')) {
  var rtlVal = true;
  }


for (var i = 0; i < owlClass.length; i++) {
  if ($(owlClass[i][0]).attr('data-has-image') == 'true') {
     var owl = $(owlClass[i][0]).owlCarousel({
      loop: true,
      dots: false,
      nav: false,
      rtl: rtlVal,
      responsive: {
        0: { items: 1},
        320:{ items: 1, slideBy: 1},
        430:{ items: 2, slideBy: 1},
        576:{ items: 2, slideBy: 1},
        768:{ items: 3, slideBy: 1},
        992:{ items: 3, slideBy: 1},
        1200:{ items: 4, slideBy: 1},
        1400:{ items: 4, slideBy: 1},
        1600:{ items: 4, slideBy: 1},
        1800:{ items: 4, slideBy: 1}
      }
    });
  } else {
    var owl = $(owlClass[i][0]).owlCarousel({
      loop: true,
      dots: false,
      nav: false,
      rtl: rtlVal,
      responsive: {
        0: { items: 1},
        320:{ items: 1, slideBy: 1},
        430:{ items: 2, slideBy: 1},
        576:{ items: 2, slideBy: 1},
        768:{ items: 3, slideBy: 1},
        992:{ items: 3, slideBy: 1},
        1200:{ items: 4, slideBy: 1},
        1400:{ items: 4, slideBy: 1},
        1600:{ items: 4, slideBy: 1},
        1800:{ items: 4, slideBy: 1}
      }
    });
  }

  $(owlClass[i][1]).on('click', function(e){
    e.preventDefault();
    $('#'+$(this).attr('data-parent')+' .owl-nav .owl-next').trigger('click');
  });
  $(owlClass[i][2]).on('click', function(e){
    e.preventDefault();
    $('#'+$(this).attr('data-parent')+' .owl-nav .owl-prev').trigger('click');
  });
}
/****************** End Tab Product Slider Js *******************************************/
/****************** Start Single Products Slider Js *******************************************/
var swiperClass = [
  //['slider className','navigation nextClass','navigation prevClass','paging className']
  ['.tbcmsfeatured-product .tbfeatured-product-wrapper','.tbcmsfeatured-next','.tbcmsfeatured-prev','.tbcmsfeatured-product'],
  ['.tbcmsnew-product .tbnew-product-wrapper','.tbcmsnew-next','.tbcmsnew-prev','.tbcmsnew-product'],
  ['.tbcmsspecial-product .tbspecial-product-wrapper','.tbcmsspecial-next','.tbcmsspecial-prev','.tbcmsspecial-product'],
  ['.tbcmsbest-seller-product .tbbest-seller-product-wrapper','.tbcmsbest-seller-next','.tbcmsbest-seller-prev','.tbcmsbest-seller-product'],
];

var rtlVal = false;
  if ($('body').hasClass('lang-rtl')) {
  var rtlVal = true;
}

for (var i = 0; i < swiperClass.length; i++) {
  if ($(swiperClass[i][0]).attr('data-has-image') == 'true') {
    $(swiperClass[i][0]).owlCarousel({
      loop: true,
      dots: false,
      nav: false,
      rtl: rtlVal,
      responsive: {
        0: { items: 1},
        320:{ items: 1, slideBy: 1},
        430:{ items: 2, slideBy: 1},
        576:{ items: 2, slideBy: 1},
        768:{ items: 3, slideBy: 1},
        992:{ items: 3, slideBy: 1},
        1200:{ items: 4, slideBy: 1},
        1400:{ items: 4, slideBy: 1},
        1600:{ items: 4, slideBy: 1},
        1800:{ items: 4, slideBy: 1}
      },
    });
  } else {
    $(swiperClass[i][0]).owlCarousel({
      loop: true,
      dots: false,
      nav: false,
      rtl: rtlVal,
      responsive: {
        0: { items: 1},
        320:{ items: 1, slideBy: 1},
        430:{ items: 2, slideBy: 1},
        576:{ items: 2, slideBy: 1},
        768:{ items: 3, slideBy: 1},
        992:{ items: 3, slideBy: 1},
        1200:{ items: 4, slideBy: 1},
        1400:{ items: 4, slideBy: 1},
        1600:{ items: 4, slideBy: 1},
        1800:{ items: 4, slideBy: 1}
      },
    });
  }
  

  $(swiperClass[i][1]).on('click', function(e){
    e.preventDefault();
    $('.'+$(this).attr('data-parent')+' .owl-nav .owl-next').trigger('click');
  });
  $(swiperClass[i][2]).on('click', function(e){
    e.preventDefault();
    $('.'+$(this).attr('data-parent')+' .owl-nav .owl-prev').trigger('click');
  });
  $(swiperClass[i][3]+' .tb-pagination-wrapper').insertAfter(swiperClass[i][3]+' .tbcmsmain-title-wrapper .tbcms-main-title');
}


var swiperClass2 = [
  //['slider className','navigation nextClass','navigation prevClass','paging className']
  ['.tbcmsspecial-product .tbspecial-product-wrapper','.tbcmsspecial-next','.tbcmsspecial-prev','.tbcmsspecial-product'],
];

var rtlVal = false;
  if ($('body').hasClass('lang-rtl')) {
  var rtlVal = true;
}

for (var i = 0; i < swiperClass2.length; i++) {
  if ($(swiperClass2[i][0]).attr('data-has-image') == 'true') {
    $(swiperClass2[i][0]).owlCarousel({
      loop: true,
      dots: false,
      nav: false,
      rtl: rtlVal,
      responsive: {
        0: { items: 1},
        320:{ items: 1, slideBy: 1},
        576:{ items: 1, slideBy: 1},
        768:{ items: 1, slideBy: 1},
        992:{ items: 1, slideBy: 1},
        1200:{ items: 1, slideBy: 1},
        1400:{ items: 1, slideBy: 1},
        1600:{ items: 1, slideBy: 1},
        1800:{ items: 1, slideBy: 1}
      },
    });
  } else {
    $(swiperClass2[i][0]).owlCarousel({
      loop: true,
      dots: false,
      nav: false,
      rtl: rtlVal,
      responsive: {
        0: { items: 1},
        320:{ items: 1, slideBy: 1},
        576:{ items: 1, slideBy: 1},
        768:{ items: 1, slideBy: 1},
        992:{ items: 2, slideBy: 1},
        1200:{ items: 2, slideBy: 1},
        1500:{ items: 2, slideBy: 1},
        1600:{ items: 2, slideBy: 1},
        1800:{ items: 2, slideBy: 1}
      },
    });
  }
  

  $(swiperClass2[i][1]).on('click', function(e){
    e.preventDefault();
    $('.'+$(this).attr('data-parent')+' .owl-nav .owl-next').trigger('click');
  });
  $(swiperClass2[i][2]).on('click', function(e){
    e.preventDefault();
    $('.'+$(this).attr('data-parent')+' .owl-nav .owl-prev').trigger('click');
  });
  $(swiperClass2[i][3]+' .tb-pagination-wrapper').insertAfter(swiperClass2[i][3]+' .tbcmsmain-title-wrapper .tbcms-main-title');
}


var sideSlider = [
  // ['Main Parent Class', 'Slider Class', 'navigation nextClass', 'navigation prevClass', 'paging className']
  // [ '.tbcmsleft-best-seller-product', '.tbleft-product-wrapper', '.tbleft-next-btn','.tbleft-prev-btn', '.tbleft-best-seller-product-pagination-wrapper'],
  // [ '.tbcmsleft-featured-product', '.tbleft-product-wrapper', '.tbleft-next-btn','.tbleft-prev-btn', '.tbleft-featured-product-pagination-wrapper'],
  // [ '.tbcmsleft-new-product', '.tbleft-product-wrapper', '.tbleft-next-btn','.tbleft-prev-btn', '.tbleft-new-product-pagination-wrapper'],
  [ '.tbcmsleft-special-product', '.tbleft-product-wrapper', '.tbleft-next-btn','.tbleft-prev-btn', '.tbleft-special-product-pagination-wrapper']
];

  for (var i = 0; i < sideSlider.length; i++) {
      var slider_class = sideSlider[i][0] + ' ' + sideSlider[i][1];
      $(slider_class).owlCarousel({
        loop: false,
        dots: false,
        nav: false,
        responsive: {
          0: { items: 1},
          320:{ items: 1, slideBy: 1},
          576:{ items: 1, slideBy: 1},
          768:{ items: 2, slideBy: 1},
          992:{ items: 3, slideBy: 1},
          1200:{ items: 1, slideBy: 1},
          1400:{ items: 1, slideBy: 1},
          1600:{ items: 1, slideBy: 1},
          1800:{ items: 1, slideBy: 1}
        },
      });

    var side_next_btn = sideSlider[i][0] + ' ' + sideSlider[i][2];
    $(document).on('click', side_next_btn, function(){
      var get_side = $(this).parent().parent().parent().attr('data-custom-column-side');
      var get_parent = $(this).parent().parent().parent().attr('data-parent');
      var set_trigger = '.' + get_parent + '.' + get_side;
      $(set_trigger).find('.owl-nav .owl-next').trigger('click');
    });

    var side_prev_btn = sideSlider[i][0] + ' ' + sideSlider[i][3]; 
    $(document).on('click', side_prev_btn, function(){    
      var get_side = $(this).parent().parent().parent().attr('data-custom-column-side');
      var get_parent = $(this).parent().parent().parent().attr('data-parent');
      var set_trigger = '.' + get_parent + '.' + get_side;
      $(set_trigger).find('.owl-nav .owl-prev').trigger('click');
    });
  }

/****************** End Single Product Sliders Js *******************************************/


  /*************** Start left Right  Product Toggle in Mobile Size ***************************************************/
  leftRightProductTitleToggle();
  $(window).resize(function(){
    
    leftRightProductTitleToggle();
  });
  function leftRightProductTitleToggle()
  {
    $('.tbleft-right-penal-all-block .tbleft-right-title-toggle, .tbleft-right-penal-all-block .tbleft-product-wrapper-info').removeClass('open');
  }


  $('.tbleft-right-penal-all-block .tbleft-right-title-toggle').on('click', function(e){
      e.preventDefault();
      if(document.body.clientWidth <= 1199){
        if($(this).hasClass('open')) {
          $(this).removeClass('open');
          $(this).parent().parent().find('.tbleft-product-wrapper-info').removeClass('open').stop(false).slideUp(500, "swing");
        } else {
          $(this).addClass('open');
          $(this).parent().parent().find('.tbleft-product-wrapper-info').addClass('open').stop(false).slideDown(500, "swing");
        }
      }
      e.stopPropagation();
    });
  /*************** End left Right  Product Toggle in Mobile Size ***************************************************/

});