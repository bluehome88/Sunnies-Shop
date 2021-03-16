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
!function(i){"function"==typeof define&&define.amd?define(["jquery"],i):"object"==typeof exports?module.exports=i(require("jquery")):i(jQuery)}(function(i){"use strict";function t(i){var t=i.parent();i.removeData("minicolors-initialized").removeData("minicolors-settings").removeProp("size").removeClass("minicolors-input"),t.before(i).remove()}function o(i){var t=i.parent(),o=t.find(".minicolors-panel"),a=i.data("minicolors-settings");!i.data("minicolors-initialized")||i.prop("disabled")||t.hasClass("minicolors-inline")||t.hasClass("minicolors-focus")||(s(),t.addClass("minicolors-focus"),o.stop(!0,!0).fadeIn(a.showSpeed,function(){a.show&&a.show.call(i.get(0))}))}function s(){i(".minicolors-focus").each(function(){var t=i(this),o=t.find(".minicolors-input"),s=t.find(".minicolors-panel"),a=o.data("minicolors-settings");s.fadeOut(a.hideSpeed,function(){a.hide&&a.hide.call(o.get(0)),t.removeClass("minicolors-focus")})})}function a(i,t,o){var s,a,r,e,c=i.parents(".minicolors").find(".minicolors-input"),l=c.data("minicolors-settings"),h=i.find("[class$=-picker]"),d=i.offset().left,p=i.offset().top,u=Math.round(t.pageX-d),g=Math.round(t.pageY-p),m=o?l.animationSpeed:0;t.originalEvent.changedTouches&&(u=t.originalEvent.changedTouches[0].pageX-d,g=t.originalEvent.changedTouches[0].pageY-p),u<0&&(u=0),g<0&&(g=0),u>i.width()&&(u=i.width()),g>i.height()&&(g=i.height()),i.parent().is(".minicolors-slider-wheel")&&h.parent().is(".minicolors-grid")&&(s=75-u,a=75-g,r=Math.sqrt(s*s+a*a),(e=Math.atan2(a,s))<0&&(e+=2*Math.PI),r>75&&(r=75,u=75-75*Math.cos(e),g=75-75*Math.sin(e)),u=Math.round(u),g=Math.round(g)),i.is(".minicolors-grid")?h.stop(!0).animate({top:g+"px",left:u+"px"},m,l.animationEasing,function(){n(c,i)}):h.stop(!0).animate({top:g+"px"},m,l.animationEasing,function(){n(c,i)})}function n(i,t){function o(i,t){var o,s;return i.length&&t?(o=i.offset().left,s=i.offset().top,{x:o-t.offset().left+i.outerWidth()/2,y:s-t.offset().top+i.outerHeight()/2}):null}var s,a,n,e,l,h,d,p=i.val(),g=i.attr("data-opacity"),m=i.parent(),f=i.data("minicolors-settings"),v=m.find(".minicolors-input-swatch"),w=m.find(".minicolors-grid"),y=m.find(".minicolors-slider"),C=m.find(".minicolors-opacity-slider"),k=w.find("[class$=-picker]"),M=y.find("[class$=-picker]"),x=C.find("[class$=-picker]"),I=o(k,w),S=o(M,y),z=o(x,C);if(t.is(".minicolors-grid, .minicolors-slider, .minicolors-opacity-slider")){switch(f.control){case"wheel":e=w.width()/2-I.x,l=w.height()/2-I.y,h=Math.sqrt(e*e+l*l),(d=Math.atan2(l,e))<0&&(d+=2*Math.PI),h>75&&(h=75,I.x=69-75*Math.cos(d),I.y=69-75*Math.sin(d)),a=u(h/.75,0,100),p=b({h:s=u(180*d/Math.PI,0,360),s:a,b:n=u(100-Math.floor(S.y*(100/y.height())),0,100)}),y.css("backgroundColor",b({h:s,s:a,b:100}));break;case"saturation":p=b({h:s=u(parseInt(I.x*(360/w.width()),10),0,360),s:a=u(100-Math.floor(S.y*(100/y.height())),0,100),b:n=u(100-Math.floor(I.y*(100/w.height())),0,100)}),y.css("backgroundColor",b({h:s,s:100,b:n})),m.find(".minicolors-grid-inner").css("opacity",a/100);break;case"brightness":p=b({h:s=u(parseInt(I.x*(360/w.width()),10),0,360),s:a=u(100-Math.floor(I.y*(100/w.height())),0,100),b:n=u(100-Math.floor(S.y*(100/y.height())),0,100)}),y.css("backgroundColor",b({h:s,s:a,b:100})),m.find(".minicolors-grid-inner").css("opacity",1-n/100);break;default:p=b({h:s=u(360-parseInt(S.y*(360/y.height()),10),0,360),s:a=u(Math.floor(I.x*(100/w.width())),0,100),b:n=u(100-Math.floor(I.y*(100/w.height())),0,100)}),w.css("backgroundColor",b({h:s,s:100,b:100}))}r(i,p,g=f.opacity?parseFloat(1-z.y/C.height()).toFixed(2):1)}else v.find("span").css({backgroundColor:p,opacity:g}),c(i,p,g)}function r(i,t,o){var s,a=i.parent(),n=i.data("minicolors-settings"),r=a.find(".minicolors-input-swatch");n.opacity&&i.attr("data-opacity",o),"rgb"===n.format?(s=g(t)?d(t,!0):w(h(t,!0)),o=""===i.attr("data-opacity")?1:u(parseFloat(i.attr("data-opacity")).toFixed(2),0,1),!isNaN(o)&&n.opacity||(o=1),t=i.minicolors("rgbObject").a<=1&&s&&n.opacity?"rgba("+s.r+", "+s.g+", "+s.b+", "+parseFloat(o)+")":"rgb("+s.r+", "+s.g+", "+s.b+")"):(g(t)&&(t=f(t)),t=l(t,n.letterCase)),i.val(t),r.find("span").css({backgroundColor:t,opacity:o}),c(i,t,o)}function e(t,o){var s,a,n,r,e,v,y,C,k,M,x=t.parent(),I=t.data("minicolors-settings"),S=x.find(".minicolors-input-swatch"),z=x.find(".minicolors-grid"),F=x.find(".minicolors-slider"),T=x.find(".minicolors-opacity-slider"),D=z.find("[class$=-picker]"),j=F.find("[class$=-picker]"),q=T.find("[class$=-picker]");switch(g(t.val())?(s=f(t.val()),(e=u(parseFloat(m(t.val())).toFixed(2),0,1))&&t.attr("data-opacity",e)):s=l(h(t.val(),!0),I.letterCase),s||(s=l(p(I.defaultValue,!0),I.letterCase)),a=function(i){var t=function(i){var t={h:0,s:0,b:0},o=Math.min(i.r,i.g,i.b),s=Math.max(i.r,i.g,i.b),a=s-o;t.b=s,t.s=0!==s?255*a/s:0,0!==t.s?i.r===s?t.h=(i.g-i.b)/a:i.g===s?t.h=2+(i.b-i.r)/a:t.h=4+(i.r-i.g)/a:t.h=-1;t.h*=60,t.h<0&&(t.h+=360);return t.s*=100/255,t.b*=100/255,t}(w(i));0===t.s&&(t.h=360);return t}(s),r=I.keywords?i.map(I.keywords.split(","),function(t){return i.trim(t.toLowerCase())}):[],v=""!==t.val()&&i.inArray(t.val().toLowerCase(),r)>-1?l(t.val()):g(t.val())?d(t.val()):s,o||t.val(v),I.opacity&&(n=""===t.attr("data-opacity")?1:u(parseFloat(t.attr("data-opacity")).toFixed(2),0,1),isNaN(n)&&(n=1),t.attr("data-opacity",n),S.find("span").css("opacity",n),C=u(T.height()-T.height()*n,0,T.height()),q.css("top",C+"px")),"transparent"===t.val().toLowerCase()&&S.find("span").css("opacity",0),S.find("span").css("backgroundColor",s),I.control){case"wheel":k=u(Math.ceil(.75*a.s),0,z.height()/2),M=a.h*Math.PI/180,y=u(75-Math.cos(M)*k,0,z.width()),C=u(75-Math.sin(M)*k,0,z.height()),D.css({top:C+"px",left:y+"px"}),C=150-a.b/(100/z.height()),""===s&&(C=0),j.css("top",C+"px"),F.css("backgroundColor",b({h:a.h,s:a.s,b:100}));break;case"saturation":y=u(5*a.h/12,0,150),C=u(z.height()-Math.ceil(a.b/(100/z.height())),0,z.height()),D.css({top:C+"px",left:y+"px"}),C=u(F.height()-a.s*(F.height()/100),0,F.height()),j.css("top",C+"px"),F.css("backgroundColor",b({h:a.h,s:100,b:a.b})),x.find(".minicolors-grid-inner").css("opacity",a.s/100);break;case"brightness":y=u(5*a.h/12,0,150),C=u(z.height()-Math.ceil(a.s/(100/z.height())),0,z.height()),D.css({top:C+"px",left:y+"px"}),C=u(F.height()-a.b*(F.height()/100),0,F.height()),j.css("top",C+"px"),F.css("backgroundColor",b({h:a.h,s:a.s,b:100})),x.find(".minicolors-grid-inner").css("opacity",1-a.b/100);break;default:y=u(Math.ceil(a.s/(100/z.width())),0,z.width()),C=u(z.height()-Math.ceil(a.b/(100/z.height())),0,z.height()),D.css({top:C+"px",left:y+"px"}),C=u(F.height()-a.h/(360/F.height()),0,F.height()),j.css("top",C+"px"),z.css("backgroundColor",b({h:a.h,s:100,b:100}))}t.data("minicolors-initialized")&&c(t,v,n)}function c(i,t,o){var s,a,n,r=i.data("minicolors-settings"),e=i.data("minicolors-lastChange");if(!e||e.value!==t||e.opacity!==o){if(i.data("minicolors-lastChange",{value:t,opacity:o}),r.swatches&&0!==r.swatches.length){for(s=g(t)?d(t,!0):w(t),a=-1,n=0;n<r.swatches.length;++n)if(s.r===r.swatches[n].r&&s.g===r.swatches[n].g&&s.b===r.swatches[n].b&&s.a===r.swatches[n].a){a=n;break}i.parent().find(".minicolors-swatches .minicolors-swatch").removeClass("selected"),-1!==a&&i.parent().find(".minicolors-swatches .minicolors-swatch").eq(n).addClass("selected")}r.change&&(r.changeDelay?(clearTimeout(i.data("minicolors-changeTimeout")),i.data("minicolors-changeTimeout",setTimeout(function(){r.change.call(i.get(0),t,o)},r.changeDelay))):r.change.call(i.get(0),t,o)),i.trigger("change").trigger("input")}}function l(i,t){return"uppercase"===t?i.toUpperCase():i.toLowerCase()}function h(i,t){return(i=i.replace(/^#/g,"")).match(/^[A-F0-9]{3,6}/gi)?3!==i.length&&6!==i.length?"":(3===i.length&&t&&(i=i[0]+i[0]+i[1]+i[1]+i[2]+i[2]),"#"+i):""}function d(i,t){var o=i.replace(/[^\d,.]/g,"").split(",");return o[0]=u(parseInt(o[0],10),0,255),o[1]=u(parseInt(o[1],10),0,255),o[2]=u(parseInt(o[2],10),0,255),o[3]&&(o[3]=u(parseFloat(o[3],10),0,1)),t?o[3]?{r:o[0],g:o[1],b:o[2],a:o[3]}:{r:o[0],g:o[1],b:o[2]}:void 0!==o[3]&&o[3]<=1?"rgba("+o[0]+", "+o[1]+", "+o[2]+", "+o[3]+")":"rgb("+o[0]+", "+o[1]+", "+o[2]+")"}function p(i,t){return g(i)?d(i):h(i,t)}function u(i,t,o){return i<t&&(i=t),i>o&&(i=o),i}function g(i){var t=i.match(/^rgba?[\s+]?\([\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?/i);return!(!t||4!==t.length)}function m(i){return(i=i.match(/^rgba?[\s+]?\([\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?,[\s+]?(\d+(\.\d{1,2})?|\.\d{1,2})[\s+]?/i))&&6===i.length?i[4]:"1"}function f(i){return(i=i.match(/^rgba?[\s+]?\([\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?/i))&&4===i.length?"#"+("0"+parseInt(i[1],10).toString(16)).slice(-2)+("0"+parseInt(i[2],10).toString(16)).slice(-2)+("0"+parseInt(i[3],10).toString(16)).slice(-2):""}function v(t){var o=[t.r.toString(16),t.g.toString(16),t.b.toString(16)];return i.each(o,function(i,t){1===t.length&&(o[i]="0"+t)}),"#"+o.join("")}function b(i){return v(function(i){var t={},o=Math.round(i.h),s=Math.round(255*i.s/100),a=Math.round(255*i.b/100);if(0===s)t.r=t.g=t.b=a;else{var n=a,r=(255-s)*a/255,e=o%60*(n-r)/60;360===o&&(o=0),o<60?(t.r=n,t.b=r,t.g=r+e):o<120?(t.g=n,t.b=r,t.r=n-e):o<180?(t.g=n,t.r=r,t.b=r+e):o<240?(t.b=n,t.r=r,t.g=n-e):o<300?(t.b=n,t.g=r,t.r=r+e):o<360?(t.r=n,t.g=r,t.b=n-e):(t.r=0,t.g=0,t.b=0)}return{r:Math.round(t.r),g:Math.round(t.g),b:Math.round(t.b)}}(i))}function w(i){return{r:(i=parseInt(i.indexOf("#")>-1?i.substring(1):i,16))>>16,g:(65280&i)>>8,b:255&i}}i.minicolors={defaults:{animationSpeed:50,animationEasing:"swing",change:null,changeDelay:0,control:"hue",defaultValue:"",format:"hex",hide:null,hideSpeed:100,inline:!1,keywords:"",letterCase:"lowercase",opacity:!1,position:"bottom left",show:null,showSpeed:100,theme:"default",swatches:[]}},i.extend(i.fn,{minicolors:function(a,n){switch(a){case"destroy":return i(this).each(function(){t(i(this))}),i(this);case"hide":return s(),i(this);case"opacity":return void 0===n?i(this).attr("data-opacity"):(i(this).each(function(){e(i(this).attr("data-opacity",n))}),i(this));case"rgbObject":return function(t){var o,s=i(t).attr("data-opacity");if(g(i(t).val()))o=d(i(t).val(),!0);else{var a=h(i(t).val(),!0);o=w(a)}if(!o)return null;void 0!==s&&i.extend(o,{a:parseFloat(s)});return o}(i(this));case"rgbString":case"rgbaString":return function(t,o){var s,a=i(t).attr("data-opacity");if(g(i(t).val()))s=d(i(t).val(),!0);else{var n=h(i(t).val(),!0);s=w(n)}if(!s)return null;void 0===a&&(a=1);return o?"rgba("+s.r+", "+s.g+", "+s.b+", "+parseFloat(a)+")":"rgb("+s.r+", "+s.g+", "+s.b+")"}(i(this),"rgbaString"===a);case"settings":return void 0===n?i(this).data("minicolors-settings"):(i(this).each(function(){var o=i(this).data("minicolors-settings")||{};t(i(this)),i(this).minicolors(i.extend(!0,o,n))}),i(this));case"show":return o(i(this).eq(0)),i(this);case"value":return void 0===n?i(this).val():(i(this).each(function(){"object"==typeof n&&null!==n?(n.opacity&&i(this).attr("data-opacity",u(n.opacity,0,1)),n.color&&i(this).val(n.color)):i(this).val(n),e(i(this))}),i(this));default:return"create"!==a&&(n=a),i(this).each(function(){!function(t,o){var s,a,n,r,c,l=i('<div class="minicolors" />'),p=i.minicolors.defaults;if(t.data("minicolors-initialized"))return;o=i.extend(!0,{},p,o),l.addClass("minicolors-theme-"+o.theme).toggleClass("minicolors-with-opacity",o.opacity),void 0!==o.position&&i.each(o.position.split(" "),function(){l.addClass("minicolors-position-"+this)});s="rgb"===o.format?o.opacity?"25":"20":o.keywords?"11":"7";t.addClass("minicolors-input").data("minicolors-initialized",!1).data("minicolors-settings",o).prop("size",s).wrap(l).after('<div class="minicolors-panel minicolors-slider-'+o.control+'"><div class="minicolors-slider minicolors-sprite"><div class="minicolors-picker"></div></div><div class="minicolors-opacity-slider minicolors-sprite"><div class="minicolors-picker"></div></div><div class="minicolors-grid minicolors-sprite"><div class="minicolors-grid-inner"></div><div class="minicolors-picker"><div></div></div></div></div>'),o.inline||(t.after('<span class="minicolors-swatch minicolors-sprite minicolors-input-swatch"><span class="minicolors-swatch-color"></span></span>'),t.next(".minicolors-input-swatch").on("click",function(i){i.preventDefault(),t.focus()}));if((r=t.parent().find(".minicolors-panel")).on("selectstart",function(){return!1}).end(),o.swatches&&0!==o.swatches.length)for(r.addClass("minicolors-with-swatches"),a=i('<ul class="minicolors-swatches"></ul>').appendTo(r),c=0;c<o.swatches.length;++c)n=g(n=o.swatches[c])?d(n,!0):w(h(n,!0)),i('<li class="minicolors-swatch minicolors-sprite"><span class="minicolors-swatch-color"></span></li>').appendTo(a).data("swatch-color",o.swatches[c]).find(".minicolors-swatch-color").css({backgroundColor:v(n),opacity:n.a}),o.swatches[c]=n;o.inline&&t.parent().addClass("minicolors-inline");e(t,!1),t.data("minicolors-initialized",!0)}(i(this),n)}),i(this)}}}),i([document]).on("mousedown.minicolors touchstart.minicolors",function(t){i(t.target).parents().add(t.target).hasClass("minicolors")||s()}).on("mousedown.minicolors touchstart.minicolors",".minicolors-grid, .minicolors-slider, .minicolors-opacity-slider",function(t){var o=i(this);t.preventDefault(),i(t.delegateTarget).data("minicolors-target",o),a(o,t,!0)}).on("mousemove.minicolors touchmove.minicolors",function(t){var o=i(t.delegateTarget).data("minicolors-target");o&&a(o,t)}).on("mouseup.minicolors touchend.minicolors",function(){i(this).removeData("minicolors-target")}).on("click.minicolors",".minicolors-swatches li",function(t){t.preventDefault();var o=i(this),s=o.parents(".minicolors").find(".minicolors-input"),a=o.data("swatch-color");r(s,a,m(a)),e(s)}).on("mousedown.minicolors touchstart.minicolors",".minicolors-input-swatch",function(t){var s=i(this).parent().find(".minicolors-input");t.preventDefault(),o(s)}).on("focus.minicolors",".minicolors-input",function(){var t=i(this);t.data("minicolors-initialized")&&o(t)}).on("blur.minicolors",".minicolors-input",function(){var t,o,s,a,n,r=i(this),e=r.data("minicolors-settings");r.data("minicolors-initialized")&&(t=e.keywords?i.map(e.keywords.split(","),function(t){return i.trim(t.toLowerCase())}):[],n=""!==r.val()&&i.inArray(r.val().toLowerCase(),t)>-1?r.val():null===(s=g(r.val())?d(r.val(),!0):(o=h(r.val(),!0))?w(o):null)?e.defaultValue:"rgb"===e.format?e.opacity?d("rgba("+s.r+","+s.g+","+s.b+","+r.attr("data-opacity")+")"):d("rgb("+s.r+","+s.g+","+s.b+")"):v(s),a=e.opacity?r.attr("data-opacity"):1,"transparent"===n.toLowerCase()&&(a=0),r.closest(".minicolors").find(".minicolors-input-swatch > span").css("opacity",a),r.val(n),""===r.val()&&r.val(p(e.defaultValue,!0)),r.val(l(r.val(),e.letterCase)))}).on("keydown.minicolors",".minicolors-input",function(t){var o=i(this);if(o.data("minicolors-initialized"))switch(t.keyCode){case 9:s();break;case 13:case 27:s(),o.blur()}}).on("keyup.minicolors",".minicolors-input",function(){var t=i(this);t.data("minicolors-initialized")&&e(t,!0)}).on("paste.minicolors",".minicolors-input",function(){var t=i(this);t.data("minicolors-initialized")&&setTimeout(function(){e(t,!0)},1)})});