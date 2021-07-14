/*
* 2007-2019 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
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
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

/******************STRAT loading*************/
	$(window).load(function() {
		$('.tbcms-loading-overlay').fadeOut('slow');
		$(".tbcmsnewsletterpopup").fadeIn("slow");

		/************ Start To Change Left Column Position in Mobile Size *********************************/
		changePositionLeftColumnMobileView();
		$(window).resize(function(){
			changePositionLeftColumnMobileView();
		});
		function changePositionLeftColumnMobileView()
		{
			if (document.body.clientWidth > 1199) {
				$('#left-column').insertBefore('#content-wrapper');
			} else {
				$('#left-column').insertAfter('#content-wrapper');
			}
		}
		/************ End To Change Left Column Position in Mobile Size *********************************/


		/****************** Start Default Left Right Panel Hide *********************************************/
		var showCountPanelLeft = 0;
		var showCountPanelRight = 0;

		if ($('.tbcms-left-column-wrapper').html() != undefined) {
			if ($('#left-column').html() != undefined) {
				$('#left-column').addClass('tb-left-right-panel-hide');
				showCountPanelLeft = 1;
			} else {
				$('.tbcms-left-column-wrapper').remove();	
			}
		} else {
			$('.tbcms-left-column-wrapper').remove();
		}

		if ($('.tbcms-right-column-wrapper').html() != undefined) {
			if ($('#right-column').html() != undefined) {
				$('#right-column').addClass('tb-left-right-panel-hide');
				showCountPanelRight = 1;
			} else {
				$('.tbcms-right-column-wrapper').remove();
				$('.tball-page-shortby').addClass('col-sm-12 col-md-4 col-lg-4');
			}
		} else {
			$('.tbcms-right-column-wrapper').remove();
			$('.tball-page-shortby').addClass('col-sm-12 col-md-4 col-lg-4');
		}

		if (showCountPanelLeft == 1 || showCountPanelRight == 1) {
			$('#content-wrapper').addClass('tbcontent-full-width');
		}

		/****************** End Default Left Right Panel Hide *********************************************/

		if (TBCMSCUSTOMSETTING_HOVER_IMG  !== undefined && TBCMSCUSTOMSETTING_HOVER_IMG  == '0') {
			$('.tbproduct-hover-img').hide();
		}
	});

	/******************END loading*************/
$(window).load(function(){
	// "use strict";

	// set Mobile view size 991
	var mobileViewSize = 991;
		
	// check function is defined or not
	function isFunction(fn){
		return typeof fn === 'function';
	}

	/************ Start close dropdown When open other dropdown in mobile view **************/
	$(window).resize(function(){
		removeDefaultDropdown();
	});
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

		// language Dropdown
		$('.tbcms-header-language .tb-language-btn').removeClass('open');
		$('.tb-language-dropdown').removeClass('open').hide();

		// Currency Dropdown
		$('.tbcms-header-currency .tb-currency-btn').removeClass('open');
		$('.tb-currency-dropdown').removeClass('open').hide();
		$('.modal-backdrop.fade.in').remove();



		if (document.body.clientWidth <= mobileViewSize) {
			// horizontal menu
			$('#tbcms-mobile-view-header .tbmenu-button').removeClass('open');
			$('#tbcmsmobile-horizontal-menu #tb-top-menu').removeClass('open');
		
			// Cart Dropdown
			$('.hexcms-header-cart .tbcmscart-show-dropdown').removeClass('open');

			// Vertical Menu DropDown
      		// $('.tbcmsvertical-menu .tballcategories-wrapper tbleft-right-title-toggle, .tbcmsvertical-menu .tbverticalmenu-dropdown').removeClass('open');	
		} else {
			// Vertical Menu DropDown
			// $('.tbcmsvertical-menu .tballcategories-wrapper').removeClass('open');
   //    		$('.tbcmsvertical-menu .tbverticalmenu-dropdown').removeClass('open').removeAttr('style');
		}
	}
	/************ End close dropdown When open other dropdown in mobile view **************/


	/*********************** Start Product Category page View ******************************/
	// Start Grid View
	function removeClassesOfView(){ 
		$('#products').removeClass('grid grid-2 list list-2 catelog'); 
	}

	$(document).on('click','.tbcmsproduct-grid-list .tbproduct-grid',function(){
		removeClassesOfView();
		$('#products').addClass('grid');
		$('.tbgrid-list-view-product .tbproduct-wrapper.grid').balance();
	});
	// End Grid View

	// Start Grid-2 View
	$(document).on('click','.tbcmsproduct-grid-list .tbproduct-grid-2',function(){
		removeClassesOfView();
		$('#products').addClass('grid-2'); 
		$('.tbgrid-list-view-product .tbproduct-wrapper.grid-2').balance();                        	
	});
	// End Grid-2 View

	// Start List View
	$(document).on('click','.tbcmsproduct-grid-list .tbproduct-list',function(){
		removeClassesOfView();
		$('#products').addClass('list');
		$('.tbgrid-list-view-product .tbproduct-wrapper.list').css('min-height','auto');
	});
	// End List View

	// Start List-2 View
	$(document).on('click','.tbcmsproduct-grid-list .tbproduct-list-2',function(){
		removeClassesOfView();
		$('#products').addClass('list-2');
		$('.tbgrid-list-view-product .tbproduct-wrapper.list-2').css('min-height','auto');
	});
	// End List-2 View

	// Start Catelog View
	$(document).on('click','.tbcmsproduct-grid-list .tbproduct-catelog',function(){
		removeClassesOfView();
		$('#products').addClass('catelog');
		$('.tbgrid-list-view-product .tbproduct-wrapper.catelog').css('min-height','auto');
	});
	// End Catelog View

	/*********************** End Product Category page View ******************************/

	/******************** Start Comman Drop-Down Functions *******************************************/
	var dropDownParentClass = '';
	var dropDownClass = '';
	
	// this function is use Toggle dropdown
	function tbDropDown(parentClass, dropdownClass, closeOtherDropdown) {
		$(document).on('click',parentClass,function(e){
			if ($(dropdownClass).hasClass('open')) {
				$(dropdownClass).removeClass('open').stop(false).slideUp(500, "swing");
				$(parentClass).removeClass('open');
			} else {
				if (closeOtherDropdown == true) {
					removeDefaultDropdown();
				}
				$(dropdownClass).addClass('open').stop(false).slideDown(500, "swing");
				$(parentClass).addClass('open');
			}
			e.stopPropagation();
		});
	}
	//tbDropDown

	/******************** End Drop-Down Functions *******************************************/

	/********************* Start Account DropDown js *****************************************/
	dropDownParentClass = '.tbcms-header-myaccount .tb-myaccount-btn';
	dropDownClass = '.tb-account-dropdown';
	$(dropDownClass).hide();
	tbDropDown(dropDownParentClass, dropDownClass, false);
	/********************* End Account DropDown js *****************************************/

	/********************* Start Language DropDown js *****************************************/
	dropDownParentClass = '.tbcms-header-language .tb-language-btn';
	dropDownClass = '.tb-language-dropdown';
	$(dropDownClass).hide();
	tbDropDown(dropDownParentClass, dropDownClass, true);
	/********************* End Language DropDown js *****************************************/

	/********************* Start Currency DropDown js *****************************************/
	dropDownParentClass = '.tbcms-header-currency .tb-currency-btn';
	dropDownClass = '.tb-currency-dropdown';
	$(dropDownClass).hide();
	tbDropDown(dropDownParentClass, dropDownClass, true);
	/********************* End Currency DropDown js *****************************************/

	/********************* Start Mobile View js *****************************************/
	
	function showView(){
		if (document.body.clientWidth <= mobileViewSize) {//for mobile view
			moveDataInMobileView('#tbcmsdesktop-main-menu', '#tbcmsmobile-horizontal-menu');
		 	moveDataInMobileView('.tbcmsheader-nav-right', '#tbcmsmobile-header-right');
		 	moveDataInMobileView('#tbcmsdesktop-logo', '#tbcmsmobile-header-logo');

		}else{//for desktop view
			moveDataInDesktopView('#tbcmsdesktop-main-menu', '#tbcmsmobile-horizontal-menu');
			moveDataInDesktopView('.tbcmsheader-nav-right', '#tbcmsmobile-header-right');
			moveDataInDesktopView('#tbcmsdesktop-logo', '#tbcmsmobile-header-logo');
		}

	}//showView

	function moveDataInMobileView(desktopClass, mobileClass) {    
		if($(desktopClass).html() != ''){
			$(mobileClass).html($(desktopClass).html());
			$(desktopClass).html('');
		}
	}

	function moveDataInDesktopView(desktopClass, mobileClass) {
		//console.log($(mobileClass).html());
		if($(mobileClass).html() !== ''){
			$(desktopClass).html($(mobileClass).html());      
			$(mobileClass).html('');
		}
	}

	showView();//default landing call for mobile view
	$(window).resize(function(){
		showView();
	});

	/********************* End Mobile View js *****************************************/

	/****************** Start Cart Js *******************************************/
	cartDropDownJs();
	$(window).resize(function(){
		cartDropDownJs();
	});
	function cartDropDownJs() {
		$(document).on('click', '#_desktop_cart .tbheader-cart-wrapper a', function(){
			if (document.body.clientWidth <= mobileViewSize) {
				if ($('#_desktop_cart .tbcmscart-show-dropdown').hasClass('open')) {
					$('#_desktop_cart .tbcmscart-show-dropdown').removeClass('open');
				} else {
					removeDefaultDropdown();
					$('#_desktop_cart .tbcmscart-show-dropdown').addClass('open');
				}
			}
		});

		$(document).on('mouseenter','#_desktop_cart .tbheader-cart-wrapper', function(){
			if (document.body.clientWidth > mobileViewSize) {
				removeDefaultDropdown();
				$('#_desktop_cart .tbcmscart-show-dropdown').addClass('open');
			}
		});

		$(document).on('mouseleave','#_desktop_cart .tbheader-cart-wrapper', function(){
			if (document.body.clientWidth > mobileViewSize) {
				$('#_desktop_cart .tbcmscart-show-dropdown').removeClass('open');
			}
		});
	}//cartDropDownJs
	/****************** End Cart Js *******************************************/

	/****************** Start Product Timer Js *******************************************/
	var result = setInterval(function() {
		$('.tbproduct-timer').each(function() {
			var time = $(this).attr('data-end-time');

			var countDownDate = new Date(time.replace(/ /g, "T") + 'Z');
			var now = new Date().getTime();

			var distance = countDownDate - now;

			var days = Math.floor(distance / (1000 * 60 * 60 * 24));
			var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
			var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
			var seconds = Math.floor((distance % (1000 * 60)) / 1000);

			var data = days + '' + hours + '' + minutes + '' + seconds + '';

			$(this).find('.tbproduct-timer-box .days').html(days);
			$(this).find('.tbproduct-timer-box .hours').html(hours);
			$(this).find('.tbproduct-timer-box .minutes').html(minutes);
			$(this).find('.tbproduct-timer-box .seconds').html(seconds);
			$(this).find('.complated').hide();

			if (distance < 0) {
				clearInterval(x);
				data = "complate";
				$(this).find('.tbproduct-timer-wrapper').hide();
				$(this).find('.tbproduct-timer-complated').show(data);
			}


			// $(".tbtabcategory-all-product-slider .tbproduct-info-box-wrapper .product-description").balance();
		});
	}, 1000);
	/****************** End Product Timer Js *******************************************/

	/********************* Start top-menu js *****************************************/
		
	//*************************start top-menu js *************************/
	$(window).resize(function(){
		$('.tbmain-menu-dropdown').removeClass('open');
	});

	function DesktopMenu(tb){
		if (document.body.clientWidth > mobileViewSize) 
		{
			var obj = tb;
			var extraWidth = 0;
			var new_width_popup = 0;
			var wrapWidthPopup = $(obj).find('.tbmain-menu-sub-menu').outerWidth(true);
			var wrapHeightPopup = $(obj).find('.tbmain-menu-sub-menu').outerHeight(true);				
			var actualWidthPopup = $(obj).find('.tbmain-menu-sub-menu').width();
			extraWidth = wrapWidthPopup - actualWidthPopup;
			var new_width_popup = $(obj).find('.tbmain-menu-sub-menu .top-menu').outerWidth(true);
			var menuWidth = $('.tbcmsmain-menu-wrapper').width();
			var subMenuLiWidth = $(obj).find('.tbmain-menu-sub-menu > ul > li').outerWidth(true);
			var totalSubMenu = $(obj).find('.tbmain-menu-sub-menu > ul > li').length;  
			var popupFinalWidth = (totalSubMenu * subMenuLiWidth)+48;
			var menuTopOffset = $("#tbcmsdesktop-main-menu").offset();//menu top margin or other spacing
			var menuLiHeightOffset  = $(obj).offset();//menu inside top position like table
			var menuLiHeight  = $(obj).outerHeight(true);//line height of menu
			var menuPopopTop  = (menuLiHeightOffset.top - menuTopOffset.top) + menuLiHeight;

			var new_outer_width_popup = new_width_popup + extraWidth;
			if(popupFinalWidth > menuWidth){
				popupFinalWidth = menuWidth+48;
			}
			if(popupFinalWidth > new_outer_width_popup){
				new_outer_width_popup = popupFinalWidth;
			}
			if(wrapHeightPopup >= 400){//auto scroll when popup is bigger
				$(obj).find('.tbmain-menu-sub-menu').addClass('tb-auto-scroll');
			}else{
				$(obj).find('.tbmain-menu-sub-menu').removeClass('tb-auto-scroll');
			}
			var wraper = $('.tbcmsmain-menu-wrapper');
			var wWraper = wraper.outerWidth();
			var posWraper = wraper.offset();
			var pos = $(obj).offset();
			var xLeft = pos.left - posWraper.left;
			if ((xLeft + new_outer_width_popup) > wWraper) xLeft = wWraper - new_outer_width_popup;
			$(obj).find('.tbmain-menu-sub-menu').css('left', xLeft);
			$(obj).find('.tbmain-menu-sub-menu').css('top',menuPopopTop);
			$(obj).find('.tbmain-menu-sub-menu').css('width', popupFinalWidth);
			//$(obj).find('.tbmain-menu-sub-menu').stop(true, true).slideDown(500, 'swing');//show popup
			$(obj).find('.tbmain-menu-sub-menu').addClass('tb-desk-open');
			
		}
	}
	$('.tbmenu-toggle-icon').click(function(){
		DesktopMenu($(this).parent().parent());
	});

	$('.tbcmsmain-menu-wrapper > .tb-header-menu > li').hover(function() {
		DesktopMenu(this);
	},function(){
		$(this).find('.tbmain-menu-sub-menu').removeClass('tb-desk-open');//hide popup
	});

	$(document).on('click','#tbcms-mobile-view-header .tbmenu-button', function(e){
		e.preventDefault();
		if ($('#tbcmsmobile-horizontal-menu #tb-top-menu').hasClass('open')) {
			$('#tbcmsmobile-horizontal-menu #tb-top-menu').removeClass('open');
			$('.modal-backdrop.fade.in').remove();
		} else {
			removeDefaultDropdown();
			$('#tbcmsmobile-horizontal-menu #tb-top-menu').addClass('open');
			if ($('body').find('.modal-backdrop.fade').html() === undefined){
	       		$('body').append('<div class="modal-backdrop fade in"></div>');
          	}
			e.stopPropagation();
		}
	});

	$(document).on('click','.modal-backdrop.fade',function(){
		$('#tbcmsmobile-horizontal-menu #tb-top-menu').removeClass('open');
		$(this).remove();
	});
	
	$(document).on('click','#tbcmsmobile-horizontal-menu #tb-top-menu .tbhorizontal-menu-drop-down-icon', function(e){
		$(this).parent().parent().find('.tbmain-menu-mobile-dropdown').css('display','block');
	});

	$(document).on('click', '#tbcmsmobile-horizontal-menu .tb-has-child .tbmenu-link-wrapper .tbmenu-toggle-icon i', function(){
		if ($(this).hasClass('open')) {
			$(this).removeClass('open');
			$(this).parent().parent().next('.tbmain-menu-mobile-dropdown').removeClass('open').stop(false).slideUp(500, "swing");
		} else {					
			$('#tbcmsmobile-horizontal-menu .tbmain-menu-dropdown').css('left', '0').css('top','0').css('width', '100%');
			$(this).addClass('open');
			$(this).parent().parent().next('.tbmain-menu-mobile-dropdown').addClass('open').stop(false).slideDown(500, "swing");
		}
	});




	//************************************end Top-menu js******************************************************/

	//************************************ Start Product Details page slider ***************************************************/
	var swiperClass = [
	  	//['slider className','navigation nextClass','navigation prevClass','paging className']
	  	['.tbcmslike-product .tblike-product-wrapper','.tbcmslike-next','.tbcmslike-prev','.tbcmslike-product'],
	  	['.tbcmscross-selling-product .tbcross-selling-product-wrapper','.tbcmscross-selling-next','.tbcmscross-selling-prev','.tbcmscross-selling-product'],
	  	['.tbcmssame-category-product .tbsame-category-product-wrapper','.tbcmssame-category-next','.tbcmssame-category-prev','.tbcmssame-category-product'],
	];

	var rtlVal = false;
	  if ($('body').hasClass('lang-rtl')) {
	  var rtlVal = true;
	}

	for (var i = 0; i < swiperClass.length; i++) {
	   	$(swiperClass[i][0]).owlCarousel({
		    loop: true,
		    dots: false,
		    nav: false,
		    rtl: rtlVal,
		    responsive: {
		      	0: { items: 1},
		        320:{ items: 1, slideBy: 1},
		        576:{ items: 2, slideBy: 1},
		        768:{ items: 3, slideBy: 1},
		        992:{ items: 3, slideBy: 1},
		        1200:{ items: 3, slideBy: 1},
		        1500:{ items: 4, slideBy: 1},
		        1600:{ items: 4, slideBy: 1},
		        1800:{ items: 4, slideBy: 1}
		    },
	  	});

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
	//************************************ End Product Details page slider *****************************************************/

	/**************** Start Catelog Quentity Increment Decrement *************************/
	$(document).on('click', '.tbproduct-wrapper.catelog .tbproduct-catalog-btn-wrapper .tbproduct-cart-quentity-increment', function(){
		var obj = $(this).parent().parent().parent().parent();
		var qty = parseInt(obj.find('.tbproduct-cart-quentity').val()) + 1;
		obj.find('.tbproduct-cart-quentity').val(qty);
		obj.parent().find('.tbproduct-cart-catalog-btn form input[name=qty]').val(qty);
	});

	$(document).on('click', '.tbproduct-wrapper.catelog .tbproduct-catalog-btn-wrapper .tbproduct-cart-quentity-decrement', function(){
		var obj = $(this).parent().parent().parent().parent();
		var qty = parseInt(obj.find('.tbproduct-cart-quentity').val()) - 1;
		if (qty >= 1) {
			obj.find('.tbproduct-cart-quentity').val(qty);
			obj.parent().find('.tbproduct-cart-catalog-btn form input[name=qty]').val(qty);
		}
	});

	$(document).on('blur', '.tbproduct-wrapper.catelog .tbproduct-catalog-btn-wrapper .tbproduct-cart-quentity', function(){
		var obj = $(this).parent().parent().parent().parent();
		var qty = parseInt(obj.find('.tbproduct-cart-quentity').val());
		if (qty > 1 && qty != NaN) {
			obj.find('.tbproduct-cart-quentity').val(qty);
			obj.parent().find('.tbproduct-cart-catalog-btn form input[name=qty]').val(qty);
		} else {
			qty = 1;
			obj.find('.tbproduct-cart-quentity').val(qty);
			obj.parent().find('.tbproduct-cart-catalog-btn form input[name=qty]').val(qty);
		}
	});


	/**************** End Catelog Quentity Increment Decrement *************************/

	/******************* Start Footer Toggle ***********************************************/
	$('.footer-container .tbfooter-title-wrapper').on('click',function(e){
		if (document.body.clientWidth > mobileViewSize) {
			e.stopPropagation();
		}
	});
	/******************* End Footer Toggle ***********************************************/

	/******************* Start Menu Sticky Js ********************************************/
	
	menuStickyJs();
	$(document).on('scroll', function(){
		var menu_sticky =  localStorage.getItem('menu-sticky') || 'true';
		if(menu_sticky == 'true'){
			menuStickyJs();
		}else{
			$('.tbcmsheader-sticky').removeClass('sticky');
		}
		bottomTotop();
	});
	
	function menuStickyJs(){
		var checkMenuSticky = $('body').attr('data-menu-sticky');
		if (checkMenuSticky == '1') {
			var startMenuStickyHeight = 450;
			var scrollHeight = $(document).scrollTop();
			if (document.body.clientWidth > mobileViewSize) {
				// Desktop View Sticky
				if (scrollHeight > startMenuStickyHeight) {
					$('.tbcmsheader-sticky').addClass('sticky');
					$('#wrapper').css('margin-top', $('.tbcmsheader-sticky').height()+'px');
				} else{
					$('.tbcmsheader-sticky').removeClass('sticky');
					$('#wrapper').css('margin-top','0px');
				}
			}
		}
	}
	function bottomTotop(){
		var startMenuStickyHeight = 450;
		var scrollHeight = $(document).scrollTop();
		if (scrollHeight > startMenuStickyHeight) {
			$('.tbbottom-to-top').fadeIn('slow');
		} else {
			$('.tbbottom-to-top').fadeOut('slow');
		}
	}
	/******************* End Menu Sticky Js ********************************************/

	/************** Start Filter Search ************************************/
	$(document).on('click','.tb_search_filter_wrapper .tbleft-right-title-wrapper', function(){
		if($('#search_filters .tbserach-filter-wrapper').hasClass('open')) {
			$('#search_filters .tbserach-filter-wrapper').removeClass('open').stop(false).slideUp(500, "swing");;
		} else	{
			$('#search_filters .tbserach-filter-wrapper').addClass('open').stop(false).slideDown(500, "swing");;
		}
	});
	/************** End Filter Search ************************************/
	/************* Start Filter Search Category Js ***********************************/
	$(document).on('click', '#search_filters .tbfilter-search-types-title', function(){
      	if(document.body.clientWidth <= mobileViewSize){
      		var search_type_id = $(this).attr('data-target');
      		if ($(search_type_id).hasClass('open')) {
      			$(this).removeClass('open');
      			$(search_type_id).removeClass('open').stop(false).slideUp(500, "swing");
      		} else {
      			$(this).addClass('open');
      			$(search_type_id).addClass('open').stop(false).slideDown(500, "swing");
      		}
      	}
	});
	/************* End Filter Search Category Js ***********************************/

	/*************** Start Left Right Column Js *************************************************/

	// Left panel hide show.
	$(document).on('click','.tbcms-left-column-wrapper .tb-left-pannal-btn-wrapper',function(e){
		e.preventDefault();
		if($('#left-column').hasClass('open')) {
			$('#left-column').removeClass('open');
			$('.modal-backdrop.fade.in').remove();
		} else {
			$('#left-column').addClass('open');
			$('body').append('<div class="modal-backdrop fade in"></div>');
			e.stopPropagation();
		}
	});
	
	// Right Panel Hide show
	$(document).on('click','.tbcms-right-column-wrapper .tb-right-pannal-btn-wrapper',function(e){
		e.preventDefault();
		if($('#right-column').hasClass('open')) {
			$('#right-column').removeClass('open');
			$('.modal-backdrop.fade.in').remove();
		} else {
			$('#right-column').addClass('open');
			$('body').append('<div class="modal-backdrop fade in"></div>');
			e.stopPropagation();
		}
	});

	$(document).on('click','#left-column .tbleft-column-close-btn, #right-column .tbright-column-close-btn', function(){
		if ($(this).parent().parent().hasClass('open')) {
			$('.tb-left-right-panel-hide').removeClass('open');
			$('.modal-backdrop.fade.in').remove();
		}
	});

	// Left - right Panel Close. 
	$(document).on('click','.modal-backdrop.fade.in', function(){
		if ($('#left-column.tb-left-right-panel-hide, #right-column.tb-left-right-panel-hide').hasClass('open'))  {
			$('#left-column.tb-left-right-panel-hide, #right-column.tb-left-right-panel-hide').removeClass('open');
			$('.modal-backdrop.fade.in').remove();
		}
	});

	/*************** Start Left Right Column Js *************************************************/

	/************** Start Left Column brand list and supplier toggle ***************************/
	leftRightBrandSupplierTitleToggle();
	$(window).resize(function(){
	    $('.tbfilter-brand-list-wrapper .tbfilter-brand-list, .tbfilter-supplier-list-wrapper .tbfilter-supplier-list').removeClass('open');
  	});
  	function leftRightBrandSupplierTitleToggle()
	{
	    $('.tbfilter-brand-list-wrapper .tbleft-right-title-toggle, .tbfilter-supplier-list-wrapper .tbleft-right-title-toggle, .block-categories .tbleft-right-title-toggle').on('click', function(){
	      	if(document.body.clientWidth <= 1199){
	        	if($(this).hasClass('open')) {
		          	$(this).removeClass('open');
		          	$(this).parent().parent().find('.tbside-panel-dropdown').removeClass('open').stop(false).slideUp(500, "swing");
	        	} else {
		          	$(this).addClass('open');
		          	$(this).parent().parent().find('.tbside-panel-dropdown').addClass('open').stop(false).slideDown(500, "swing");
		        }
	    	}
	 	});
	}
	/************** End Left Column brand list and supplier toggle ***************************/

	/******** Start Scroll to Top js ***************************/
	$(document).on('click','.tbbottom-to-top .tbbottom-to-top-icon',function() {      // When arrow is clicked
        scrollToTop();
    });

    function scrollToTop()
    {
      $('body,html').animate({
            scrollTop : 0 // Scroll to top of body
        }, 500);
    }
	/******** End Scroll to Top js ***************************/

	/******************************** tooltop ************************/
	$(function(){
	   'use strict';

	    var popoverConfig = {
	        trigger: 'hover',
	        template: [
	            '<div class="popover tbtooltip" role="tooltip">',
	            '<div class="popover-arrow"></div>',
	            '<h3 class="popover-title"></h3>',
	            //'<div class="popover-content"></div>',
	            '</div>'
	        ].join(''),
	        placement: 'top',
	        container: 'body',
	    };

	    initPopovers();
	    function initPopovers()
	    {
	        $('[data-toggle="tbtooltip"]').popover(popoverConfig);
	    }
	});
	/*************** end tooltip***********************/

	// *****************STRAT ZOOM_PRODUCT**************

	 if(document.body.clientWidth  > 768) {
	      $(".product-cover img").elevateZoom({
	        zoomType: "inner",
			    cursor: "crosshair"
	      }); 
	      $('body').on('mouseenter','.product-cover .js-qv-product-cover', function(){
	        // Remove old instance od EZ
	        $('.zoomContainer').remove();
	        $(this).removeData('elevateZoom');
	        // Update source for images
	        // console.log($(this).attr('src'));
	        $(this).attr('src', $(this).attr('data-image-large-src'));
	        $(this).data('zoom-image', $(this).data('zoom-image'));
	    });
	       $('body').on('click',('.js-qv-product-images .js-thumb'),function(e){
	          e.preventDefault();
	        var img_val = $(this).attr('data-image-large-src');
	          $('.product-cover img').attr('src',img_val);
	          $('.zoomContainer img').attr('src',img_val);
	          $('.zoomWindowContainer div').css("background-image","url("+ $(this).attr('data-image-large-src') +")");
	       });
	  }

	// *****************END ZOOM_PRODUCT**************//




	/****************** Start Tooltip Js **************************/
	$(function(){
   'use strict';
	    var popoverConfig = {
	        trigger: 'hover',
	        template: [
	             '<div class="popover tbtooltip" role="tooltip">',
	            '<div class="popover-arrow"></div>',
	            '<h3 class="popover-title"></h3>',
	            '<div class="popover-content"></div>',
	            '</div>'
	        ].join(''),
	        placement: 'top',
	        container: 'body',
	    };

	    initPopovers();
	    function initPopovers()
	    {
	        $('[data-toggle="tbtooltip"]').popover(popoverConfig);
	    }
	});

	/****************** End Tooltip Js **************************/

	/********************* tab title js ***************************/

	changeTabTitles();
	headerMediaIcons();
	headerMobileMenuPosition();
	$(window).resize(function(){
		changeTabTitles();
		headerMediaIcons();
		headerMobileMenuPosition();
	});

	function headerMobileMenuPosition(){
		if (document.body.clientWidth <= 575) {
	 		$('.tbcmsmobile-header-center').insertBefore('.tbcmsmobile-header-left');
	 	} else {
	 		$('.tbcmsmobile-header-center').insertAfter('.tbcmsmobile-header-right');
	 	}
	}

	function headerMediaIcons()
	{
	 	if (document.body.clientWidth <=1199  && document.body.clientWidth >= 991) {

	 		if ($('.tbcmsdesktop-top-header-box .tbcms-media-display').html() == undefined) {
		 		$('.tbcms-menu-offer-wrapper .tb-offer-cart-wrapper').insertAfter('.tbcmsdesktop-top-header-box .tbcms-menu-offer-wrapper');
			 	$('.tbcmsdesktop-top-header-box .tbcmsheader-nav-right').insertAfter('.tbcmsdesktop-top-header-box .tbcms-menu-offer-wrapper');
			 	$('.tbcmsdesktop-top-header-box .evvtcms-header-serch-menu .tbcmsheader-nav-right, .tbcmsdesktop-top-header-box .evvtcms-header-serch-menu .tb-offer-cart-wrapper').wrapAll('<div class=\'tbcms-media-display hidden-xl-up clearfix\'></div>');
	 		}
	 	} else {
	 		$('.tbcmsdesktop-top-header-box .tb-offer-cart-wrapper').insertAfter('.tbcms-menu-offer-wrapper .tbcms-menu-wrapper');
	 		$('.tbcmsdesktop-top-header-box .tbcmsheader-nav-right').insertAfter('.evvtcms-header-serch-menu-inner .tbcmsheader-search-wrapper');
	 		if ($('.tbcmsdesktop-top-header-box').find('.tbcms-media-display').html() != undefined) {
	 			$('.tbcmsdesktop-top-header-box').find('.tbcms-media-display').remove();
	 		}
	 	}
	}
	
	function changeTabTitles()
	{
	 	if (document.body.clientWidth <= 991 && document.body.clientWidth > 575) {
	 		$('.tbtab-product .tbtab-title-wrapper').insertAfter('.tbtab-product .tbcmsmain-title-wrapper');
	 	} else {
	 		$('.tbtab-product .tbtab-title-wrapper').insertAfter('.tbtab-product .tbcmsmain-title-wrapper .tbcms-main-title');
	 	}
	}

	$(document).on('click','.tbcms-header-menu .tbmain-menu-open', function(){
  		if(document.body.clientWidth >= 768 ) {
			$(this).addClass('open');
			$('.tbcms-header-menu').find('.tbcmsheader-main-menu-wrapper').addClass('open');
			$('body').addClass('tbactive-search');
		}
  	});

	$(document).on('click','.tbcms-header-menu .tbmain-menu-close', function(){
  		if(document.body.clientWidth >= 768 ) {
			$(this).removeClass('open');
			$('.tbcms-header-menu').find('.tbcmsheader-main-menu-wrapper').removeClass('open');
			$('body').removeClass('tbactive-search');
		}
  	});

	//=========================Start Animation==============================
	if (typeof TBCMSCUSTOMSETTING_ANIMATION_CSS !== undefined && TBCMSCUSTOMSETTING_ANIMATION_CSS == '1') {
		function hb_animated_contents() {		
		    $(".hb-animate-element:in-viewport").each(function(i) {
		    	//=============Animation============ 
		        var $this = $(this);
		        if (!$this.hasClass('hb-in-viewport')) {
		            setTimeout(function() {
		                $this.addClass('hb-in-viewport')
		            }, 100 * i)
		        }	        
		    })
		}
		$(document).scroll(function() {
		    hb_animated_contents();
		});
		$(window).load(function() {
		    hb_animated_contents();
		});
	}
	//=========================End Animation==============================	

	$('.tbcmsleft-special-product .tbleft-special-product-pagination-wrapper').insertAfter('.tbcmsleft-special-product .tbleft-right-title');

  	if(document.body.clientWidth <= 1199 ) {
    	$('.tbcmsleft-special-product .tbleft-special-product-pagination-wrapper').insertAfter('.tbcmsleft-special-product .tbleft-special-wrapper');
  	}


  	if ($('header.tbpage-header-title-wrapper h1.tbpage-header-title').html() != undefined) {
  		$('.breadcrumb .tbcategory-page-title').html($('header.tbpage-header-title-wrapper h1.tbpage-header-title').html());
  	}


    $(document).on('click', '.tbspecial-product-img-slider-wrapper a', function(){
        var data_parent = $(this).attr('data-parent');
        var medium_img_src = $(this).find('img').attr('data-src-home-img');
        $('.'+ data_parent + ' .product-thumbnail img').attr('src', medium_img_src);
    });

    $(document).on('click', '.tbspecial-product-img-slider-pagination .tbspecial-product-img-slider-prev', function(){
        var data_parent = $(this).attr('data-parent');
        $('.'+data_parent +' .owl-nav .owl-prev').trigger('click');
    });

    $(document).on('click', '.tbspecial-product-img-slider-pagination .tbspecial-product-img-slider-next', function(){
        var data_parent = $(this).attr('data-parent');
        $('.'+data_parent +' .owl-nav .owl-next').trigger('click');
    });

});//$(window).load(function(){

	

	$(window).load(function() {

	  	function ent_in_viewport_contents(){
			$("img:in-viewport").each(function(i) {
		    	var orgImgPath = $(this).attr('data-org-src');
		    	//==============Load Image===============
		    	if (typeof orgImgPath !== typeof undefined && orgImgPath !== false) {
		    		if (!$(this).hasClass('tb-in-viewport')) {
		    			$(this).attr("src",orgImgPath).addClass('tb-in-viewport');
		    		}
		    	}
			});
		}
		$(document).on('click mouseover', function () {
	    	ent_in_viewport_contents();
		});
		$(document).scroll(function() {
		    ent_in_viewport_contents();
		});
		ent_in_viewport_contents();

		$('.tbcmstab-title-product .tbproduct-wrapper').balance();
		$('.tbmain-slider-content-wrapper .tbmain-slider-content-inner').balance();
		$('.tbcmssame-category-product .tbproduct-wrapper').balance();
		$('.tbcmslike-product .tbproduct-wrapper').balance();
		$('.tbcmscross-selling-product .tbproduct-wrapper').balance();
		$('.tbcmstabcategory-product-slider .tbproduct-wrapper').balance();
		$('.tbcmsfeatured-product .tbproduct-wrapper').balance();
		$('.tbcmsbest-seller-product .tbproduct-wrapper').balance();
		$('.tbcmsnew-product .tbproduct-wrapper').balance();
		$('.tbcmsspecial-product .tbproduct-wrapper').balance();
		$('#products.grid .tbgrid-list-view-product .tbproduct-wrapper.grid').balance();

		if ( $( "#cart" ).length )
		{
		    // setTimeout( function(){ 
		        console.log( "loading spinner hidden");
                $(".tbcms-loading-overlay").toggle().delay(2000);
            // }  , 2000 )
		}
	});