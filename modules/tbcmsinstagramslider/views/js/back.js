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

$(document).ready(function()
{
	if ($('#acces_token').val() != undefined && $('#acces_token').val().length != undefined &&  $('#acces_token').val().length > 0 && $('#acces_token').attr('class') !== 'active_save_access'){
		$('#configuration_form_submit_btn').hide();
		$('#acces_token').attr('class','active_save_access');
		$('#configuration_form_submit_btn_save').show();
	} else if ($('#acces_token').val() != undefined && $('#acces_token').val().length == 0){
		$('#configuration_form_submit_btn_save').hide();
		$('#configuration_form_submit_btn').show();
		$('#acces_token').removeClass('active_save_access');
	}
	$('#acces_token').on('keyup',function()
	{
		if ($('#acces_token').val().length != undefined && $('#acces_token').val().length > 0 && $('#acces_token').attr('class') !== 'active_save_access'){
			$('#configuration_form_submit_btn').hide();
			$('#acces_token').attr('class','active_save_access');
			$('#configuration_form_submit_btn_save').show();
		} else if ($('#acces_token').val().length != undefined && $('#acces_token').val().length == 0){
			$('#configuration_form_submit_btn_save').hide();
			$('#configuration_form_submit_btn').show();
			$('#acces_token').removeClass('active_save_access');
		}
	});
});