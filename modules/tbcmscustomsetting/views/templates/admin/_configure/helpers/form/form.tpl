{**
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
*}


{extends file="helpers/form/form.tpl"}
{block name="input"}
    {if $input.type == 'file_upload'}
        <div class="col-lg-9">
            <div class="form-group">
                <div class="col-lg-9">
                    <div class="dummyfile input-group">
                        <input id="{$input.name|escape:'htmlall':'UTF-8'}" type="file" name="{$input.name|escape:'htmlall':'UTF-8'}" class="hide-file-upload" />
                        <span class="input-group-addon"><i class="icon-file"></i></span>
                        <input id="{$input.name|escape:'htmlall':'UTF-8'}-name" type="text" class="disabled" name="filename" readonly />
                        <span class="input-group-btn">
                            <button id="{$input.name|escape:'htmlall':'UTF-8'}-selectbutton" type="button" name="submitAddAttachments" class="btn btn-default">
                               <i class="icon-folder-open"></i> {l s='Choose a file' mod='tbcmscustomsetting'}
                            </button>
                        </span>
                    </div>
                </div>
            </div>
            {assign var='width' value='250'}
            {assign var='height' value='275'}
            <div class="form-group">
                <div id="{$input.name|escape:'htmlall':'UTF-8'}-images-thumbnails" class="col-lg-12">
                    <img src="{$path|escape:'htmlall':'UTF-8'}{$fields_value[$input.name]|escape:'htmlall':'UTF-8'}" class="img-thumbnail" />
                    <p class="help-block">{l s='Please Select Image.' mod='tbcmscustomsetting'} (Size:- {$width|escape:'htmlall':'UTF-8'} X {$height|escape:'htmlall':'UTF-8'} )</p>
                </div>
            </div>
            <script>
                $(document).ready(function(){
                    $('#{$input.name|escape:"htmlall":"UTF-8"}-selectbutton').click(function(e){
                        $('#{$input.name|escape:"htmlall":"UTF-8"}').trigger('click');
                    });
                    $('#{$input.name|escape:"htmlall":"UTF-8"}-name').click(function(e){
                        $('#{$input.name|escape:"htmlall":"UTF-8"}').trigger('click');
                    });
                    $('#{$input.name|escape:"htmlall":"UTF-8"}').change(function(e){
                        var val = $(this).val();
                        var file = val.split(/[\\/]/);
                        $('#{$input.name|escape:"htmlall":"UTF-8"}-name').val(file[file.length-1]);
                    });
                });
            </script>
        </div>
    {/if}
    {if $input.type == 'file_upload_2'}
        <div class="col-lg-9">
            {foreach from=$languages item=language}
                {if $languages|count > 1}
                    <div class="translatable-field lang-{$language.id_lang|escape:'htmlall':'UTF-8'}" {if $language.id_lang != $defaultFormLanguage}style="display:none"{/if}>
                {/if}
                <div class="form-group">
                    <div class="col-lg-9">
                        <div class="dummyfile input-group">
                            <input id="{$input.name|escape:'htmlall':'UTF-8'}_{$language.id_lang|escape:'htmlall':'UTF-8'}" type="file" name="tbcmscustomsetting_left_image_name_{$language.id_lang|escape:'htmlall':'UTF-8'}" class="hide-file-upload" />
                            <span class="input-group-addon"><i class="icon-file"></i></span>
                            <input id="{$input.name|escape:'htmlall':'UTF-8'}_{$language.id_lang|escape:'htmlall':'UTF-8'}-name" type="text" class="disabled" name="filename" readonly />
                            <span class="input-group-btn">
                                <button id="{$input.name|escape:'htmlall':'UTF-8'}_{$language.id_lang|escape:'htmlall':'UTF-8'}-selectbutton" type="button" name="submitAddAttachments" class="btn btn-default">
                                    <i class="icon-folder-open"></i>{l s='Choose a file' mod='tbcmscustomsetting'}
                                </button>
                            </span>
                        </div>
                    </div>
                    {if $languages|count > 1}
                        <div class="col-lg-3">
                            <button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">
                                {$language.iso_code|escape:'htmlall':'UTF-8'}
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                {foreach from=$languages item=lang}
                                <li><a href="javascript:hideOtherLanguage({$lang.id_lang|escape:'htmlall':'UTF-8'});" tabindex="-1">{$lang.name}</a></li>
                                {/foreach}
                            </ul>
                        </div>
                    {/if}
                </div>
                {assign var='width' value='450'}
                {assign var='height' value='588'}
                <div class="form-group">
                    <div id="{$input.name|escape:'htmlall':'UTF-8'}-{$language.id_lang|escape:'htmlall':'UTF-8'|escape:'htmlall':'UTF-8'}-images-thumbnails" class="col-lg-12">
                        <img src="{$path|escape:'htmlall':'UTF-8'}{$fields_value[$input.name][$language.id_lang]|escape:'htmlall':'UTF-8'}" class="img-thumbnail" />
                        <p class="help-block">Please Select Image. (Size:- {$width|escape:'htmlall':'UTF-8'} X {$height|escape:'htmlall':'UTF-8'} )</p>
                    </div>
                </div>
                {if $languages|count > 1}
                    </div>
                {/if}
                <script>
                $(document).ready(function(){
                    $('#{$input.name|escape:"htmlall":"UTF-8"}_{$language.id_lang|escape:"htmlall":"UTF-8"}-selectbutton').click(function(e){
                        $('#{$input.name|escape:"htmlall":"UTF-8"}_{$language.id_lang|escape:"htmlall":"UTF-8"}').trigger('click');
                    });
                    $('#{$input.name|escape:"htmlall":"UTF-8"}_{$language.id_lang|escape:"htmlall":"UTF-8"}-name').click(function(e){
                        $('#{$input.name|escape:"htmlall":"UTF-8"}_{$language.id_lang|escape:"htmlall":"UTF-8"}').trigger('click');
                    });
                    $('#{$input.name|escape:"htmlall":"UTF-8"}_{$language.id_lang|escape:"htmlall":"UTF-8"}').change(function(e){
                        var val = $(this).val();
                        var file = val.split(/[\\/]/);
                        $('#{$input.name|escape:"htmlall":"UTF-8"}_{$language.id_lang|escape:"htmlall":"UTF-8"}-name').val(file[file.length-1]);
                    });
                });
            </script>
            {/foreach}
        </div>
    {/if}
    {if $input.type == 'file_upload_3'}
        <div class="col-lg-9">
            <div class="form-group">
                <div class="col-lg-9">
                    <div class="tbcmsall-pattern-show">
                        {$i=1}
                        {while $i <= 30}
                            {$tmp = 'pattern'|cat:$i}
                            <div class="tball-pattern-show {if $background_pattern == $tmp}tbcms_custom_setting_active{/if}" id="pattern{$i}" style="background:url({$front_pattern_path}pattern/pattern{$i}.png)"></div>
                            {$i=$i+1}
                        {/while}
                        <div class="col-lg-12 tball-pattern-custom-pattern" style="padding: 0;">
                            <input type="file" name="tbcmscustomsetting_custom_pattern" title="Add One Custom Pattern">
                            <input type="hidden" id="tbcmscustomsetting_pattern" name="tbcmscustomsetting_pattern" value="{$background_pattern}">
                            {if $custom_pattern}
                                <div class="tball-pattern-show custom_pattern {if $background_pattern == 'custompattern'}tbcms_custom_setting_active{/if}" id="custompattern" style="background:url({$path}{$custom_pattern})"></div>
                            {/if}
                        </div>
                    </div>
                </div>
            </div>
            <p class="help-block">
                {l s='Choose Your Pattern or Update Your Custom Pattern.' mod='tbcmscustomsetting'}
            </p>
        </div>
    {/if}

    {if $input.type == 'file_upload_4'}
        <div class="col-lg-9">
            <div class="form-group">
                <div class="col-lg-9">
                    <div class="tbcmsall-pattern-show">
                        {$i=1}
                        {while $i <= 30}
                            {$tmp = 'pattern'|cat:$i}
                            <div class="tball-body-pattern-show {if $body_background_pattern == $tmp}tbcms_custom_setting_body_active{/if}" id="pattern{$i}" style="background:url({$front_pattern_path}pattern/pattern{$i}.png)"></div>
                            {$i=$i+1}
                        {/while}
                        <div class="col-lg-12 tball-pattern-custom-pattern" style="padding: 0;">
                            <input type="file" name="tbcmscustomsetting_custom_body_pattern" title="Add One Custom Pattern">
                            <input type="hidden" id="tbcmscustomsetting_body_pattern" name="tbcmscustomsetting_body_pattern" value="{$body_background_pattern}">
                            {if $custom_body_pattern}
                                <div class="tball-body-pattern-show custom_body_pattern {if $body_background_pattern == 'custombodypattern'}tbcms_custom_setting_body_active{/if}" id="custombodypattern" style="background:url({$path}{$custom_body_pattern})"></div>
                            {/if}
                        </div>
                    </div>
                </div>
            </div>
            <p class="help-block">
                {l s='Choose Your Pattern or Update Your Custom Pattern.' mod='tbcmscustomsetting'}
            </p>
        </div>
    {/if}
    {if $input.type == 'custom_theme_option'}
        <div class="col-lg-9" id="TBCMSCUSTOMSETTING_THEME_OPTION">
            <div class="form-group">
                <div class="col-lg-12">
                    <input type="radio" id="TBCMSCUSTOMSETTING_THEME_OPTION1" name="TBCMSCUSTOMSETTING_THEME_OPTION" value="" {if $fields_value[$input.name] == ''} checked {/if}> 
                    <div class="color-wrapper theme1">
                        <div class="first"></div>
                    </div><p><label for="TBCMSCUSTOMSETTING_THEME_OPTION1">{l s='Theme 1' mod='tbcmscustomsetting'}</label></p>
                    <!-- <input type="radio" id="TBCMSCUSTOMSETTING_THEME_OPTION2" name="TBCMSCUSTOMSETTING_THEME_OPTION" value="theme2" {if $fields_value[$input.name] == 'theme2'} checked {/if}> 
                    <div class="color-wrapper theme2">
                        <div class="first"></div>
                    </div><p><label for="TBCMSCUSTOMSETTING_THEME_OPTION2">{l s='Theme 2' mod='tbcmscustomsetting'}</label></p>
                    <input type="radio" id="TBCMSCUSTOMSETTING_THEME_OPTION3" name="TBCMSCUSTOMSETTING_THEME_OPTION" value="theme3" {if $fields_value[$input.name] == 'theme3'} checked {/if}>
                    <div class="color-wrapper theme3">
                        <div class="first"></div>
                    </div><p><label for="TBCMSCUSTOMSETTING_THEME_OPTION3">{l s='Theme 3' mod='tbcmscustomsetting'}</label></p>
                    <input type="radio" id="TBCMSCUSTOMSETTING_THEME_OPTION4" name="TBCMSCUSTOMSETTING_THEME_OPTION" value="theme4" {if $fields_value[$input.name] == 'theme4'} checked {/if}>
                    <div class="color-wrapper theme4">
                        <div class="first"></div>
                    </div><p><label for="TBCMSCUSTOMSETTING_THEME_OPTION4">{l s='Theme 4' mod='tbcmscustomsetting'}</label></p> -->
                    <input type="radio" id="TBCMSCUSTOMSETTING_THEME_OPTION_CUSTOM" name="TBCMSCUSTOMSETTING_THEME_OPTION" value="theme_custom" {if $fields_value[$input.name] == 'theme_custom'} checked {/if}>
                    <div class="color-wrapper theme_custom">
                        <div class="first" style="background-color: {Configuration::get('TBCMSCUSTOMSETTING_THEME_COLOR_1')}"></div>
                    </div><p><label for="TBCMSCUSTOMSETTING_THEME_OPTION_CUSTOM">{l s='Custom' mod='tbcmscustomsetting'}</label></p>
                </div>
                    <p class="help-block">
                        {l s='Choose Front Side Theme.' mod='tbcmscustomsetting'}
                    </p>
            </div>
        </div>
    {/if}
    {if $input.type == 'custom_color'}
        <div class="col-lg-9">
            <div class="form-group">
                <div class="col-lg-2">
                    <div class="row">
                        <div class="input-group">
                            <input type="text" data-hex="true" class="color mColorPickerInput mColorPicker" name="TBCMSCUSTOMSETTING_THEME_COLOR_1" value="#0f0010" id="color_0" style="background-color: rgb(255, 255, 255); color: black;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    {/if}
    {$smarty.block.parent}
{/block}
