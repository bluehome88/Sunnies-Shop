{**
 * 2007-2019 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
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
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2019 PrestaShop SA
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}
{strip}
<div class="tbproduct-timer" data-end-time='{$timer}'>
	<div class='tbtimer-wrapper'>
		<div class="tbproduct-timer-icon">
			<i class='material-icons'>&#xe425;</i>
		</div>
		<div class='tbproduct-timer-wrapper'>
			<div class="tbproduct-timer-box tbproduct-time-days">
				<div class="days"></div>
				<div class='tbproduct-timer-line'></div>
				<div class="tbtimer-name">{l s='DAYS' d='Shop.Theme.Actions'}</div>
			</div>
		</div>
		<span class="tbtimer-dot">:</span>
		<div class='tbproduct-timer-wrapper'>
			<div class="tbproduct-timer-box tbproduct-time-hours">
				<div class="hours"></div>
				<div class='tbproduct-timer-line'></div>
				<div class="tbtimer-name">{l s='HOURS' d='Shop.Theme.Actions'}</div>
			</div>
		</div>
		<span class="tbtimer-dot">:</span>
		<div class='tbproduct-timer-wrapper'>
			<div class="tbproduct-timer-box tbproduct-time-minutes">
				<div class="minutes"></div>
				<div class='tbproduct-timer-line'></div>
				<div class="tbtimer-name">{l s='MINS' d='Shop.Theme.Actions'}</div>
			</div>
		</div>
		<span class="tbtimer-dot">:</span>
		<div class='tbproduct-timer-wrapper'>
			<div class="tbproduct-timer-box tbproduct-time-seconds">
				<div class="seconds"></div>
				<div class='tbproduct-timer-line'></div>
				<div class="tbtimer-name">{l s='SECS' d='Shop.Theme.Actions'}</div>
			</div>
		</div>
		<div class="tbproduct-timer-complated"></div>
	</div>
</div>
{/strip}