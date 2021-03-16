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

!function(e,n){"object"==typeof exports&&"undefined"!=typeof module?n(require("jquery")):"function"==typeof define&&define.amd?define(["jquery"],n):n(e.jQuery)}(this,function(w){"use strict";function h(e,n){var t=e.getBoundingClientRect(),o=t.top,r=t.bottom,i=t.left,a=t.right,u=w.extend({tolerance:0,viewport:window},n),c=!1,f=u.viewport.jquery?u.viewport:w(u.viewport);f.length||(console.warn("isInViewport: The viewport selector you have provided matches no element on page."),console.warn("isInViewport: Defaulting to viewport as window"),f=w(window));var s=f.height(),d=f.width(),l=f[0].toString();if(f[0]!==window&&"[object Window]"!==l&&"[object DOMWindow]"!==l){var p=f[0].getBoundingClientRect();o-=p.top,r-=p.top,i-=p.left,a-=p.left,d-=h.scrollBarWidth=h.scrollBarWidth||function(e){var n=w("<div></div>").css({width:"100%"});e.append(n);var t=e.width()-n.width();return n.remove(),t}(f)}return u.tolerance=~~Math.round(parseFloat(u.tolerance)),u.tolerance<0&&(u.tolerance=s+u.tolerance),a<=0||d<=i?c:c=u.tolerance?o<=u.tolerance&&r>=u.tolerance:0<r&&o<=s}function o(e){if(e){var n=e.split(",");return 1===n.length&&isNaN(n[0])&&(n[1]=n[0],n[0]=void 0),{tolerance:n[0]?n[0].trim():void 0,viewport:n[1]?w(n[1].trim()):void 0}}return{}}(w=w&&w.hasOwnProperty("default")?w.default:w).extend(w.expr.pseudos||w.expr[":"],{"in-viewport":w.expr.createPseudo?w.expr.createPseudo(function(n){return function(e){return h(e,o(n))}}):function(e,n,t){return h(e,o(t[3]))}}),w.fn.isInViewport=function(t){return this.filter(function(e,n){return h(n,t)})},w.fn.run=function(e){var t=this;1===arguments.length&&"function"==typeof e&&(e=[e]);if(e instanceof Array)return e.forEach(function(n){"function"!=typeof n?(console.warn("isInViewport: Argument(s) passed to .do/.run should be a function or an array of functions"),console.warn("isInViewport: Ignoring non-function values in array and moving on")):[].slice.call(t).forEach(function(e){return n.call(w(e))})}),this;throw new SyntaxError("isInViewport: Argument(s) passed to .do/.run should be a function or an array of functions")}});