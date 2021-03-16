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

!function(t){t.fn.balance=function(h){h=t.extend({set_height:!1,same_height:!0,same_width:!1,limit_height:!1,limit_width:!1,max_height:100,max_width:100},h),$maxheight=$maxwidth=0,$class=t(this),$class.each(function(){$maxheight=parseFloat(window.getComputedStyle(this).height)>$maxheight?parseFloat(window.getComputedStyle(this).height):$maxheight,$maxwidth=parseFloat(t(this).width())>$maxwidth?t(this).width():$maxwidth}),h.same_height&&($maxheight=h.limit_height&&h.max_height<=$maxheight?h.max_height:$maxheight,h.set_height?$class.css({height:$maxheight+"px"}):$class.css({"min-height":$maxheight+"px"})),h.same_width&&($maxwidth=h.limit_width&&h.max_width<=$maxwidth?h.max_width:$maxwidth,$class.width($maxwidth))}}(jQuery);