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

$(window).load(function(){
	if ($.cookie("tbcmsnewsletterpopup") != "true") {
		setTimeout(function(){
			$("#tbcmsNewsLetterPopup").modal({show: true});
		}, 3000);
	}
});
$(document).ready(function () {
	if ($.cookie("tbcmsnewsletterpopup") != "true") {
    	var obj = $(document).find('#tbnewsletter-email-subscribe');
		// $("#tbcmsNewsLetterPopup").modal({show: true});
		$("#tbnewsletter-email-subscribe").click(function(){
			var email = $("#tbcmsnewsletterpopupnewsletter-input").val();
			$.ajax({
				type: "POST",
				headers: { "cache-control": "no-cache" },
				async: false,
				url: ajax_path,
				// data: "name=marek&email="+email,
				data: "name=marek&email="+email,
				success: function(data) {
					if (data) {
						$(".tbcmsnewsletterpopupAlert").text(data);
					}
				}
			});
		});
		$('#tbcmsnewsletterpopupnewsletter-input').keypress(function(event){
		  var keycode = (event.keyCode ? event.keyCode : event.which);
		  if (keycode == '13') {
					var email = $("#tbcmsnewsletterpopupnewsletter-input").val();
					$.ajax({
						type: "POST",
						headers: { "cache-control": "no-cache" },
						async: false,
						url: ajax_path,
						data: "name=marek&email="+email,
						success: function(data) {
							if (data) {
								$(".tbcmsnewsletterpopupAlert").text(data);
							}
						}
					});
					event.preventDefault();
		  }
		});
        $(".tbcmsnewsletterpopup_newsletter_dont_show_again").prop("checked") == false;
	}


	$('.tbcmsnewsletterpopup_newsletter_dont_show_again').on('click',function(){
	    if ($(this).is(':checked')) {
	    	$(this).attr('checked', 'checked');
			$.cookie("tbcmsnewsletterpopup", "true");
	    } else {
	    	$(this).removeAttr('checked');
			$.cookie("tbcmsnewsletterpopup", "false");
	    }
	    // $("#tbcmsNewsLetterPopup button.close").trigger('click');
	});

});