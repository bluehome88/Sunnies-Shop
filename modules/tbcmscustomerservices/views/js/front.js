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


$(document).ready(function(){
	// if(document.body.clientWidth <= 575){

	// 	$('.tbcmscustomer-services .tbservices-all-block').addClass('owl-theme').addClass('owl-carousel');
	//   	$('.tbcmscustomer-services .tbservices-all-block').owlCarousel({
	// 	    loop: false,
	// 	    dots: false,
	// 	    nav: false,
	// 	    responsive: {
	// 		    0: { items: 1},
	// 			320:{ items: 1, slideBy: 1},
	// 			481:{ items: 2, slideBy: 1},
	// 			575:{ items: 2, slideBy: 1}
	// 	    },
	//   	});
	// 	$(document).on('click','.tbservice-slider-prev',function(e){
	// 	    e.preventDefault();
	// 	    $('.tbcmscustomer-services .owl-nav .owl-prev').trigger('click');
	// 	});

	// 	$(document).on('click','.tbservice-slider-next',function(e){
	// 	    e.preventDefault();
	// 	    $('.tbcmscustomer-services .owl-nav .owl-next').trigger('click');
	// 	});
	// 	$('.tbcmscustomer-services .tbcms-service-pagination-wrapper').insertAfter('.tbservice-inner .tbcmsmain-title-wrapper');
	// }else{
	// 	$('.tbcmscustomer-services .tbservices-all-block').removeClass('owl-theme').removeClass('owl-carousel');
	// }


	leftRightCustomerServiceTitleToggle();
  	$(window).resize(function(){
    	leftRightCustomerServiceTitleToggle();
  	});
  	function leftRightCustomerServiceTitleToggle()
  	{
	    $('.tbcmscustomer-services .tbleft-right-title-toggle, .tbcmscustomer-services .tbleft-customer-services-wrapper-info').removeClass('open');
  	}

  	$('.tbcmscustomer-services .tbleft-right-title-toggle').on('click', function(e){
      	e.preventDefault();
  		if(document.body.clientWidth <= 1199){
	        if($(this).hasClass('open')) {
          		$(this).removeClass('open');
          		$(this).parent().parent().find('.tbleft-customer-services-wrapper-info').removeClass('open').stop(false).slideUp(500, "swing");
        	} else {
          		$(this).addClass('open');
          		$(this).parent().parent().find('.tbleft-customer-services-wrapper-info').addClass('open').stop(false).slideDown(500, "swing");
        	}
      	}
      	e.stopPropagation();
    });
});
