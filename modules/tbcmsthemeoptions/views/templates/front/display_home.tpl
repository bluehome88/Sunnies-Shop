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
<div class="tbcmstheme-control">
	<div class="tbtheme-control">
		<div class="tbtheme-control-wrapper">
			<div class="tbthemecontrol-heading">
				<div class="tbtheme-control-title-name">
					{hook h='displayFrontSetting'}
				</div>
				<div class="tbtheme-control-title-name-reset-btn">
					<p>{l s='Theme Option' d='Shop.Theme.Global'}</p>
				</div>
			</div>
			<table>
				
				<tr class="tbselect-theme tball-theme-content">
					<td>
						<div class="tbselect-theme-name">{l s='Theme' d='Shop.Theme.Global'}</div>
						<select class="tbselect-theme-select" id="select_theme">
							<option value=""  data-color="" data-color-two="">{l s='Theme 1' d='Shop.Theme.Global'}</option>
							<option value="theme2" data-color="#fd7d70" data-color-two="#d61e1e">{l s='Theme 2' d='Shop.Theme.Global'}</option>
							<option value="theme3" data-color="#ff9800" data-color-two="#f05a66">{l s='Theme 3' d='Shop.Theme.Global'}</option>
							<option value="theme4" data-color="#48570b" data-color-two="#c7c702">{l s='Theme 4' d='Shop.Theme.Global'}</option>
							<option value="theme_custom" data-color="">{l s='Custom' d='Shop.Theme.Global'}</option>
						</select>
					</td>
				</tr>
				
				<tr class="tbtheme-color-one tball-theme-content">
					<td>
						<div class="tbcolor-theme-name">{l s='Custome Color 1' mod='tbcmsthemeoptions'}</div>
						<div class="tbtheme-color-box">
							<input type="text" id="themecolor1" class="tbtheme-color-box-1" data-control="saturation">
						</div>
					</td>
				</tr>
				
				<!-- <tr class="tbtheme-color-two tball-theme-content">
					<td>
						<div class="tbcolor-two-theme-name">{l s='Custome Color 2' d='Shop.Theme.Global'}</div>
						<div class="tbtheme-color-box">
							<input type="text" id="themecolor2" class="tbtheme-color-box-2" data-control="saturation">
						</div>
					</td>
				</tr> -->
				
				<tr class="tbtheme-box-layout tball-theme-content">
					<td>
						<div class="tbtheme-layout-name">{l s='Box-Layout' d='Shop.Theme.Global'}</div>
						<div class="box tbtheme-option">
							<input type="checkbox" id="box-layout-toggle" class='tbtheme-box-layout-option' />
							<label for="box-layout-toggle" class="tbtheme-option">{* {l s='Toggle' d='Shop.Theme.Global'} *}</label>
						</div>
					</td>
				</tr>

				<tr class="tbtheme-background-patten tball-theme-content">
					<td>
						<div class="tbtheme-background-pattern-name">{l s='Background Pattern' d='Shop.Theme.Global'}</div>
						<div class="tbtheme-all-pattern-wrapper">
							<div class="tbtheme-all-pattern">
								<div id="pattern1" class="tbtheme-pattern-image tbtheme-pattern-image1" data-img="{$urls.img_url}pattern/pattern1.png" style="background-image:url('{$urls.img_url}pattern/tiny/pattern1tiny.png')"></div>
							</div>
							<div class="tbtheme-all-pattern">
								<div id="pattern2" class="tbtheme-pattern-image tbtheme-pattern-image2" data-img="{$urls.img_url}pattern/pattern2.png" style="background-image:url('{$urls.img_url}pattern/tiny/pattern2tiny.png')"></div>
							</div>
							<div class="tbtheme-all-pattern">
								<div id="pattern3" class="tbtheme-pattern-image tbtheme-pattern-image3" data-img="{$urls.img_url}pattern/pattern3.png" style="background-image:url('{$urls.img_url}pattern/tiny/pattern3tiny.png')"></div>
							</div>
							<div class="tbtheme-all-pattern">
								<div id="pattern4" class="tbtheme-pattern-image tbtheme-pattern-image4" data-img="{$urls.img_url}pattern/pattern4.png" style="background-image:url('{$urls.img_url}pattern/tiny/pattern4tiny.png')"></div>
							</div>
							<div class="tbtheme-all-pattern">
								<div id="pattern5" class="tbtheme-pattern-image tbtheme-pattern-image5" data-img="{$urls.img_url}pattern/pattern5.png" style="background-image:url('{$urls.img_url}pattern/tiny/pattern5tiny.png')"></div>
							</div>
							<div class="tbtheme-all-pattern">
								<div id="pattern6" class="tbtheme-pattern-image tbtheme-pattern-image6" data-img="{$urls.img_url}pattern/pattern6.png" style="background-image:url('{$urls.img_url}pattern/tiny/pattern6tiny.png')"></div>
							</div>
							<div class="tbtheme-all-pattern">
								<div id="pattern7" class="tbtheme-pattern-image tbtheme-pattern-image7" data-img="{$urls.img_url}pattern/pattern7.png"  style="background-image:url('{$urls.img_url}pattern/tiny/pattern7tiny.png')"></div>
							</div>
							<div class="tbtheme-all-pattern">
								<div id="pattern8" class="tbtheme-pattern-image tbtheme-pattern-image8" data-img="{$urls.img_url}pattern/pattern8.png" style="background-image:url('{$urls.img_url}pattern/tiny/pattern8tiny.png')"></div>
							</div>
							<div class="tbtheme-all-pattern">
								<div id="pattern9" class="tbtheme-pattern-image tbtheme-pattern-image9" data-img="{$urls.img_url}pattern/pattern9.png" style="background-image:url('{$urls.img_url}pattern/tiny/pattern9tiny.png')"></div>
							</div>
							<div class="tbtheme-all-pattern">
								<div id="pattern10" class="tbtheme-pattern-image tbtheme-pattern-image10" data-img="{$urls.img_url}pattern/pattern10.png"  style="background-image:url('{$urls.img_url}pattern/tiny/pattern10tiny.png')"></div>
							</div>
							<div class="tbtheme-all-pattern">
								<div id="pattern11" class="tbtheme-pattern-image tbtheme-pattern-image11" data-img="{$urls.img_url}pattern/pattern11.png" style="background-image:url('{$urls.img_url}pattern/tiny/pattern11tiny.png')"></div>
							</div>
							<div class="tbtheme-all-pattern">
								<div id="pattern12" class="tbtheme-pattern-image tbtheme-pattern-image12" data-img="{$urls.img_url}pattern/pattern12.png" style="background-image:url('{$urls.img_url}pattern/tiny/pattern12tiny.png')"></div>
							</div>
							<div class="tbtheme-all-pattern">
								<div id="pattern13" class="tbtheme-pattern-image tbtheme-pattern-image13" data-img="{$urls.img_url}pattern/pattern13.png" style="background-image:url('{$urls.img_url}pattern/tiny/pattern13tiny.png')"></div>
							</div>
							<div class="tbtheme-all-pattern">
								<div id="pattern14" class="tbtheme-pattern-image tbtheme-pattern-image14" data-img="{$urls.img_url}pattern/pattern14.png" style="background-image:url('{$urls.img_url}pattern/tiny/pattern14tiny.png')"></div>
							</div>
							<div class="tbtheme-all-pattern">
								<div id="pattern15" class="tbtheme-pattern-image tbtheme-pattern-image15" data-img="{$urls.img_url}pattern/pattern15.png" style="background-image:url('{$urls.img_url}pattern/tiny/pattern15tiny.png')"></div>
							</div>
							<div class="tbtheme-all-pattern">
								<div id="pattern16" class="tbtheme-pattern-image tbtheme-pattern-image16" data-img="{$urls.img_url}pattern/pattern16.png" style="background-image:url('{$urls.img_url}pattern/tiny/pattern16tiny.png')"></div>
							</div>
							<div class="tbtheme-all-pattern">
								<div id="pattern17" class="tbtheme-pattern-image tbtheme-pattern-image17" data-img="{$urls.img_url}pattern/pattern17.png" style="background-image:url('{$urls.img_url}pattern/tiny/pattern17tiny.png')"></div>
							</div>
							<div class="tbtheme-all-pattern">
								<div id="pattern18" class="tbtheme-pattern-image tbtheme-pattern-image18" data-img="{$urls.img_url}pattern/pattern18.png" style="background-image:url('{$urls.img_url}pattern/tiny/pattern18tiny.png')"></div>
							</div>
							<div class="tbtheme-all-pattern">
								<div id="pattern19" class="tbtheme-pattern-image tbtheme-pattern-image19" data-img="{$urls.img_url}pattern/pattern19.png" style="background-image:url('{$urls.img_url}pattern/tiny/pattern19tiny.png')"></div>
							</div>
							<div class="tbtheme-all-pattern">
								<div id="pattern20" class="tbtheme-pattern-image tbtheme-pattern-image20" data-img="{$urls.img_url}pattern/pattern20.png" style="background-image:url('{$urls.img_url}pattern/tiny/pattern20tiny.png')"></div>
							</div>
							<div class="tbtheme-all-pattern">
								<div id="pattern21" class="tbtheme-pattern-image tbtheme-pattern-image21" data-img="{$urls.img_url}pattern/pattern21.png" style="background-image:url('{$urls.img_url}pattern/tiny/pattern21tiny.png')"></div>
							</div>
							<div class="tbtheme-all-pattern">
								<div id="pattern22" class="tbtheme-pattern-image tbtheme-pattern-image22" data-img="{$urls.img_url}pattern/pattern22.png" style="background-image:url('{$urls.img_url}pattern/tiny/pattern22tiny.png')"></div>
							</div>
							<div class="tbtheme-all-pattern">
								<div id="pattern23" class="tbtheme-pattern-image tbtheme-pattern-image23" data-img="{$urls.img_url}pattern/pattern23.png" style="background-image:url('{$urls.img_url}pattern/tiny/pattern23tiny.png')"></div>
							</div>
							<div class="tbtheme-all-pattern">
								<div id="pattern24" class="tbtheme-pattern-image tbtheme-pattern-image24" data-img="{$urls.img_url}pattern/pattern24.png" style="background-image:url('{$urls.img_url}pattern/tiny/pattern24tiny.png')"></div>
							</div>
							<div class="tbtheme-all-pattern">
								<div id="pattern25" class="tbtheme-pattern-image tbtheme-pattern-image25" data-img="{$urls.img_url}pattern/pattern25.png" style="background-image:url('{$urls.img_url}pattern/tiny/pattern25tiny.png')"></div>
							</div>
							<div class="tbtheme-all-pattern">
								<div id="pattern26" class="tbtheme-pattern-image tbtheme-pattern-image26" data-img="{$urls.img_url}pattern/pattern26.png" style="background-image:url('{$urls.img_url}pattern/tiny/pattern26tiny.png')"></div>
							</div>
							<div class="tbtheme-all-pattern">
								<div id="pattern27" class="tbtheme-pattern-image tbtheme-pattern-image27" data-img="{$urls.img_url}pattern/pattern27.png" style="background-image:url('{$urls.img_url}pattern/tiny/pattern27tiny.png')"></div>
							</div>
							<div class="tbtheme-all-pattern">
								<div id="pattern28" class="tbtheme-pattern-image tbtheme-pattern-image28" data-img="{$urls.img_url}pattern/pattern28.png" style="background-image:url('{$urls.img_url}pattern/tiny/pattern28tiny.png')"></div>
							</div>
							<div class="tbtheme-all-pattern">
								<div id="pattern29" class="tbtheme-pattern-image tbtheme-pattern-image29" data-img="{$urls.img_url}pattern/pattern29.png" style="background-image:url('{$urls.img_url}pattern/tiny/pattern29tiny.png')"></div>
							</div>
							<div class="tbtheme-all-pattern">
								<div id="pattern30" class="tbtheme-pattern-image tbtheme-pattern-image30" data-img="{$urls.img_url}pattern/pattern30.png" style="background-image:url('{$urls.img_url}pattern/tiny/pattern30tiny.png')"></div>
							</div>
							<div class="tbtheme-all-pattern">
								<div id="pattern31" class="tbtheme-pattern-image tbtheme-pattern-image31" data-img="{$urls.img_url}pattern/custom-background-img-1.png" style="background-image:url('{$urls.img_url}pattern/tiny/custom-background-img-1tiny.png')"></div>
							</div>
							<div class="tbtheme-all-pattern">
								<div id="pattern32" class="tbtheme-pattern-image tbtheme-pattern-image32" data-img="{$urls.img_url}pattern/custom-background-img-2.png" style="background-image:url('{$urls.img_url}pattern/tiny/custom-background-img-2tiny.png')"></div>
							</div>
							<div class="tbtheme-all-pattern">
								<div id="pattern33" class="tbtheme-pattern-image tbtheme-pattern-image33" data-img="{$urls.img_url}pattern/custom-background-img-3.png" style="background-image:url('{$urls.img_url}pattern/tiny/custom-background-img-3tiny.png')"></div>
							</div>
							<div class="tbtheme-all-pattern">
								<div id="pattern34" class="tbtheme-pattern-image tbtheme-pattern-image34" data-img="{$urls.img_url}pattern/custom-background-img-4.png" style="background-image:url('{$urls.img_url}pattern/tiny/custom-background-img-4tiny.png')"></div>
							</div>
							<div class="tbtheme-all-pattern">
								<div id="pattern35" class="tbtheme-pattern-image tbtheme-pattern-image35" data-img="{$urls.img_url}pattern/custom-background-img-5.png" style="background-image:url('{$urls.img_url}pattern/tiny/custom-background-img-5tiny.png')"></div>
							</div>
							<div class="tbtheme-all-pattern">
								<div id="pattern36" class="tbtheme-pattern-image tbtheme-pattern-image36" data-img="{$urls.img_url}pattern/custom-background-img-6.png" style="background-image:url('{$urls.img_url}pattern/tiny/custom-background-img-6tiny.png')"></div>
							</div>
						</div>

						{* <p class="notice">{l s='Custome background also available in admin.' mod='tbcmsthemeoptions'}</p> *}
					</td>
				</tr>

				<tr class="tbtheme-background-color tball-theme-content">
					<td>
						<div class="tbbgcolor-theme-name">{l s='Background color' d='Shop.Theme.Global'}</div>
						<div class="tbtheme-bgcolor-box">
							<input type="text" id="themebgcolor2" data-control="saturation" class="tbtheme-bgcolor-box-2">
						</div>
					</td>
				</tr>

				<tr class="tbtheme-background-layout tball-theme-content">
					<td>
						<div class="tbtheme-layout-name">{l s='Body Background' d='Shop.Theme.Global'}</div>
						<div class="box tbtheme-option">
							<input type="checkbox" id="body-background-toggle" class='tbtheme-body-background-option' />
							<label for="body-background-toggle" class="tbtheme-body-background">{* {l s='Toggle' d='Shop.Theme.Global'} *}</label>
						</div>
					</td>
				</tr>

				<tr class="tbtheme-body-bgcolor tball-theme-content">
					<td>
						<div class="tbbody-bgcolor-theme-name tbtheme-layout-name">{l s='Body Background Color' d='Shop.Theme.Global'}</div>
						<div class="tbtheme-color-box">
							<input type="text" id="themebodybgcolor" class="tbtheme-bgcolor" data-control="saturation">
						</div>
					</td>
				</tr>

				<tr class="tbtheme-body-background-patten tball-theme-content">
					<td>
						<div class="tbtheme-body-background-pattern-name">{l s='Body Background Pattern' d='Shop.Theme.Global'}</div>
						<div class="tbtheme-all-body-pattern-wrapper">
							<div class="tbtheme-all-body-pattern">
								<div id="bodypattern1" class="tbtheme-body-pattern-image tbtheme-body-pattern-image1" data-img="{$urls.img_url}pattern/pattern1.png" style="background-image:url('{$urls.img_url}pattern/tiny/pattern1tiny.png')"></div>
							</div>
							<div class="tbtheme-all-body-pattern">
								<div id="bodypattern2" class="tbtheme-body-pattern-image tbtheme-body-pattern-image2" data-img="{$urls.img_url}pattern/pattern2.png" style="background-image:url('{$urls.img_url}pattern/tiny/pattern2tiny.png')"></div>
							</div>
							<div class="tbtheme-all-body-pattern">
								<div id="bodypattern3" class="tbtheme-body-pattern-image tbtheme-body-pattern-image3" data-img="{$urls.img_url}pattern/pattern3.png" style="background-image:url('{$urls.img_url}pattern/tiny/pattern3tiny.png')"></div>
							</div>
							<div class="tbtheme-all-body-pattern">
								<div id="bodypattern4" class="tbtheme-body-pattern-image tbtheme-body-pattern-image4" data-img="{$urls.img_url}pattern/pattern4.png" style="background-image:url('{$urls.img_url}pattern/tiny/pattern4tiny.png')"></div>
							</div>
							<div class="tbtheme-all-body-pattern">
								<div id="bodypattern5" class="tbtheme-body-pattern-image tbtheme-body-pattern-image5" data-img="{$urls.img_url}pattern/pattern5.png" style="background-image:url('{$urls.img_url}pattern/tiny/pattern5tiny.png')"></div>
							</div>
							<div class="tbtheme-all-body-pattern">
								<div id="bodypattern6" class="tbtheme-body-pattern-image tbtheme-body-pattern-image6" data-img="{$urls.img_url}pattern/pattern6.png" style="background-image:url('{$urls.img_url}pattern/tiny/pattern6tiny.png')"></div>
							</div>
							<div class="tbtheme-all-body-pattern">
								<div id="bodypattern7" class="tbtheme-body-pattern-image tbtheme-body-pattern-image7" data-img="{$urls.img_url}pattern/pattern7.png"  style="background-image:url('{$urls.img_url}pattern/tiny/pattern7tiny.png')"></div>
							</div>
							<div class="tbtheme-all-body-pattern">
								<div id="bodypattern8" class="tbtheme-body-pattern-image tbtheme-body-pattern-image8" data-img="{$urls.img_url}pattern/pattern8.png" style="background-image:url('{$urls.img_url}pattern/tiny/pattern8tiny.png')"></div>
							</div>
							<div class="tbtheme-all-body-pattern">
								<div id="bodypattern9" class="tbtheme-body-pattern-image tbtheme-body-pattern-image9" data-img="{$urls.img_url}pattern/pattern9.png" style="background-image:url('{$urls.img_url}pattern/tiny/pattern9tiny.png')"></div>
							</div>
							<div class="tbtheme-all-body-pattern">
								<div id="bodypattern10" class="tbtheme-body-pattern-image tbtheme-body-pattern-image10" data-img="{$urls.img_url}pattern/pattern10.png"  style="background-image:url('{$urls.img_url}pattern/tiny/pattern10tiny.png')"></div>
							</div>
							<div class="tbtheme-all-body-pattern">
								<div id="bodypattern11" class="tbtheme-body-pattern-image tbtheme-body-pattern-image11" data-img="{$urls.img_url}pattern/pattern11.png" style="background-image:url('{$urls.img_url}pattern/tiny/pattern11tiny.png')"></div>
							</div>
							<div class="tbtheme-all-body-pattern">
								<div id="bodypattern12" class="tbtheme-body-pattern-image tbtheme-body-pattern-image12" data-img="{$urls.img_url}pattern/pattern12.png" style="background-image:url('{$urls.img_url}pattern/tiny/pattern12tiny.png')"></div>
							</div>
							<div class="tbtheme-all-body-pattern">
								<div id="bodypattern13" class="tbtheme-body-pattern-image tbtheme-body-pattern-image13" data-img="{$urls.img_url}pattern/pattern13.png" style="background-image:url('{$urls.img_url}pattern/tiny/pattern13tiny.png')"></div>
							</div>
							<div class="tbtheme-all-body-pattern">
								<div id="bodypattern14" class="tbtheme-body-pattern-image tbtheme-body-pattern-image14" data-img="{$urls.img_url}pattern/pattern14.png" style="background-image:url('{$urls.img_url}pattern/tiny/pattern14tiny.png')"></div>
							</div>
							<div class="tbtheme-all-body-pattern">
								<div id="bodypattern15" class="tbtheme-body-pattern-image tbtheme-body-pattern-image15" data-img="{$urls.img_url}pattern/pattern15.png" style="background-image:url('{$urls.img_url}pattern/tiny/pattern15tiny.png')"></div>
							</div>
							<div class="tbtheme-all-body-pattern">
								<div id="bodypattern16" class="tbtheme-body-pattern-image tbtheme-body-pattern-image16" data-img="{$urls.img_url}pattern/pattern16.png" style="background-image:url('{$urls.img_url}pattern/tiny/pattern16tiny.png')"></div>
							</div>
							<div class="tbtheme-all-body-pattern">
								<div id="bodypattern17" class="tbtheme-body-pattern-image tbtheme-body-pattern-image17" data-img="{$urls.img_url}pattern/pattern17.png" style="background-image:url('{$urls.img_url}pattern/tiny/pattern17tiny.png')"></div>
							</div>
							<div class="tbtheme-all-body-pattern">
								<div id="bodypattern18" class="tbtheme-body-pattern-image tbtheme-body-pattern-image18" data-img="{$urls.img_url}pattern/pattern18.png" style="background-image:url('{$urls.img_url}pattern/tiny/pattern18tiny.png')"></div>
							</div>
							<div class="tbtheme-all-body-pattern">
								<div id="bodypattern19" class="tbtheme-body-pattern-image tbtheme-body-pattern-image19" data-img="{$urls.img_url}pattern/pattern19.png" style="background-image:url('{$urls.img_url}pattern/tiny/pattern19tiny.png')"></div>
							</div>
							<div class="tbtheme-all-body-pattern">
								<div id="bodypattern20" class="tbtheme-body-pattern-image tbtheme-body-pattern-image20" data-img="{$urls.img_url}pattern/pattern20.png" style="background-image:url('{$urls.img_url}pattern/tiny/pattern20tiny.png')"></div>
							</div>
							<div class="tbtheme-all-body-pattern">
								<div id="bodypattern21" class="tbtheme-body-pattern-image tbtheme-body-pattern-image21" data-img="{$urls.img_url}pattern/pattern21.png" style="background-image:url('{$urls.img_url}pattern/tiny/pattern21tiny.png')"></div>
							</div>
							<div class="tbtheme-all-body-pattern">
								<div id="bodypattern22" class="tbtheme-body-pattern-image tbtheme-body-pattern-image22" data-img="{$urls.img_url}pattern/pattern22.png" style="background-image:url('{$urls.img_url}pattern/tiny/pattern22tiny.png')"></div>
							</div>
							<div class="tbtheme-all-body-pattern">
								<div id="bodypattern23" class="tbtheme-body-pattern-image tbtheme-body-pattern-image23" data-img="{$urls.img_url}pattern/pattern23.png" style="background-image:url('{$urls.img_url}pattern/tiny/pattern23tiny.png')"></div>
							</div>
							<div class="tbtheme-all-body-pattern">
								<div id="bodypattern24" class="tbtheme-body-pattern-image tbtheme-body-pattern-image24" data-img="{$urls.img_url}pattern/pattern24.png" style="background-image:url('{$urls.img_url}pattern/tiny/pattern24tiny.png')"></div>
							</div>
							<div class="tbtheme-all-body-pattern">
								<div id="bodypattern25" class="tbtheme-body-pattern-image tbtheme-body-pattern-image25" data-img="{$urls.img_url}pattern/pattern25.png" style="background-image:url('{$urls.img_url}pattern/tiny/pattern25tiny.png')"></div>
							</div>
							<div class="tbtheme-all-body-pattern">
								<div id="bodypattern26" class="tbtheme-body-pattern-image tbtheme-body-pattern-image26" data-img="{$urls.img_url}pattern/pattern26.png" style="background-image:url('{$urls.img_url}pattern/tiny/pattern26tiny.png')"></div>
							</div>
							<div class="tbtheme-all-body-pattern">
								<div id="bodypattern27" class="tbtheme-body-pattern-image tbtheme-body-pattern-image27" data-img="{$urls.img_url}pattern/pattern27.png" style="background-image:url('{$urls.img_url}pattern/tiny/pattern27tiny.png')"></div>
							</div>
							<div class="tbtheme-all-body-pattern">
								<div id="bodypattern28" class="tbtheme-body-pattern-image tbtheme-body-pattern-image28" data-img="{$urls.img_url}pattern/pattern28.png" style="background-image:url('{$urls.img_url}pattern/tiny/pattern28tiny.png')"></div>
							</div>
							<div class="tbtheme-all-body-pattern">
								<div id="bodypattern29" class="tbtheme-body-pattern-image tbtheme-body-pattern-image29" data-img="{$urls.img_url}pattern/pattern29.png" style="background-image:url('{$urls.img_url}pattern/tiny/pattern29tiny.png')"></div>
							</div>
							<div class="tbtheme-all-body-pattern">
								<div id="bodypattern30" class="tbtheme-body-pattern-image tbtheme-body-pattern-image30" data-img="{$urls.img_url}pattern/pattern30.png" style="background-image:url('{$urls.img_url}pattern/tiny/pattern30tiny.png')"></div>
							</div>
							<div class="tbtheme-all-body-pattern">
								<div id="bodypattern31" class="tbtheme-body-pattern-image tbtheme-body-pattern-image31" data-img="{$urls.img_url}pattern/custom-background-img-1.png" style="background-image:url('{$urls.img_url}pattern/tiny/custom-background-img-1tiny.png')"></div>
							</div>
							<div class="tbtheme-all-body-pattern">
								<div id="bodypattern32" class="tbtheme-body-pattern-image tbtheme-body-pattern-image32" data-img="{$urls.img_url}pattern/custom-background-img-2.png" style="background-image:url('{$urls.img_url}pattern/tiny/custom-background-img-2tiny.png')"></div>
							</div>
							<div class="tbtheme-all-body-pattern">
								<div id="bodypattern33" class="tbtheme-body-pattern-image tbtheme-body-pattern-image33" data-img="{$urls.img_url}pattern/custom-background-img-3.png" style="background-image:url('{$urls.img_url}pattern/tiny/custom-background-img-3tiny.png')"></div>
							</div>
							<div class="tbtheme-all-body-pattern">
								<div id="bodypattern34" class="tbtheme-body-pattern-image tbtheme-body-pattern-image34" data-img="{$urls.img_url}pattern/custom-background-img-4.png" style="background-image:url('{$urls.img_url}pattern/tiny/custom-background-img-4tiny.png')"></div>
							</div>
							<div class="tbtheme-all-body-pattern">
								<div id="bodypattern35" class="tbtheme-body-pattern-image tbtheme-body-pattern-image35" data-img="{$urls.img_url}pattern/custom-background-img-5.png" style="background-image:url('{$urls.img_url}pattern/tiny/custom-background-img-5tiny.png')"></div>
							</div>
							<div class="tbtheme-all-body-pattern">
								<div id="bodypattern36" class="tbtheme-body-pattern-image tbtheme-body-pattern-image36" data-img="{$urls.img_url}pattern/custom-background-img-6.png" style="background-image:url('{$urls.img_url}pattern/tiny/custom-background-img-6tiny.png')"></div>
							</div>
						</div>

						{* <p class="notice">{l s='Custome background also available in admin.' mod='tbcmsthemeoptions'}</p> *}
					</td>
				</tr>

				<!-- <tr class="tbtheme-box-layout tball-theme-content">
					<td>
						<div class="tbtheme-layout-name">{l s='Box-Layout' d='Shop.Theme.Global'}</div>
						<div class="box tbtheme-option">
							<input type="checkbox" id="box-layout-toggle" class='tbtheme-box-layout-option' />
							<label for="box-layout-toggle" class="tbtheme-option">{* {l s='Toggle' d='Shop.Theme.Global'} *}</label>
						</div>
					</td>
				</tr> -->

				<!-- <tr class="tbtheme-body-bgcolor tball-theme-content">
					<td>
						<div class="tbbody-bgcolor-theme-name tbtheme-layout-name">{l s='Body Background Color' d='Shop.Theme.Global'}</div>
						<div class="tbtheme-color-box">
							<input type="text" id="themebodybgcolor" class="tbtheme-bgcolor" data-control="saturation">
						</div>
					</td>
				</tr> -->



				<tr class="tbselect-title-font-1 tball-theme-content">
					<td>
						<div class="tbselect-title-font-1-name tbtheme-layout-name">{l s='Title Font Family' d='Shop.Theme.Global'}</div>
						<select class="tbselect-title-font-1-select" id="select_title_font_1">
							<option value="" data-font-link=''>{l s='Default Font Style' d='Shop.Theme.Global'}</option>
							{foreach $title_font_list as $font}
								<option value="{$font['name']}" data-font-link="{$font['link']}">{$font['name']}</option>
							{/foreach}
						</select>
					</td>
				</tr>

				<tr class="tbtheme-title-color tball-theme-content">
					<td>
						<div class="tbtheme-title-color tbtheme-layout-name">{l s='Title Color' d='Shop.Theme.Global'}</div>
						<div class="tbtheme-color-box">
							<input type="text" id="themeCustomTitleColor" class="tbtheme-custom-title-color" data-control="saturation">
						</div>
					</td>
				</tr>

				<tr class="tbselect-title-font-2 tball-theme-content">
					<td>
						<div class="tbselect-title-font-2-name tbtheme-layout-name">{l s='Theme' d='Shop.Theme.Global'}</div>
						<select class="tbselect-title-font-2-select" id="select_title_font_2">
							<option value="" data-font-link=''>{l s='Default Font Style' d='Shop.Theme.Global'}</option>

							{foreach $title_font_list as $font}
								<option value="{$font['name']}" data-font-link="{$font['link']}">{$font['name']}</option>
							{/foreach}
						</select>
					</td>
				</tr>
				
				<tr class="tbtheme-menu-sticky tball-theme-content">
					<td>
						<div class="tbtheme-menu-sticky-name">
							{l s='Menu Sticky' d='Shop.Theme.Global'}
						</div>
						<div class="box tbtheme-option">
							<input type="checkbox" id="menu-sticky-toggle" class='tbtheme-menu-sticky-option' />
							<label for="menu-sticky-toggle" class="tbtheme-option">{* {l s='Toggle' d='Shop.Theme.Global'} *}</label>
						</div>
					</td>
				</tr>

			</table>
			<button class="tbtheme-control-reset">{l s='Reset' d='Shop.Theme.Global'}</button>
		</div>
		<div class="tbtheme-control-icon">
			<i class='material-icons'>&#xe429;</i>
		</div>
		
	</div>
</div>
{/strip}
