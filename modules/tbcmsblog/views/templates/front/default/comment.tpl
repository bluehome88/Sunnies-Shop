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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2019 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{strip}
<div class="comment_respond clearfix m_bottom_50 tbcms-blog-inner-page" id="respond">
    <h3 class="comment_reply_title" id="reply-title">
        {l s='Leave a Reply' mod='tbcmsblog'}
        <small>
            <a href="/wp_showcase/wp-supershot/?p=38#respond" id="cancel-comment-reply-link" rel="nofollow" style="display:none;">
                {l s='Cancel reply' mod='tbcmsblog'}
            </a>
        </small>
    </h3>
    <form class="comment_form" method="post" id="tbcmsblogs_commentfrom" data-toggle="validator">
        <div class="form-group tbcmsblogs_message"></div>
        <div class="form-group tbcmsblog_name_parent clearfix">
          <label class="col-xs-12 col-sm-12 col-md-3 col-lg-3" for="tbcmsblog_name">{l s='Your Name' mod='tbcmsblog'}</label>
          <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
            <input type="text" id="tbcmsblog_name" name="tbcmsblog_name" class="form-control tbcmsblog_name" required>
          </div>
        </div>
        <div class="form-group tbcmsblog_email_parent clearfix">
          <label class="col-xs-12 col-sm-12 col-md-3 col-lg-3" for="tbcmsblog_email">{l s='Your Email' mod='tbcmsblog'}</label>
          <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
            <input type="email"  id="tbcmsblog_email" name="tbcmsblog_email" class="form-control tbcmsblog_email" required>
          </div>
        </div>
        <div class="form-group tbcmsblog_website_parent clearfix">
          <label class="col-xs-12 col-sm-12 col-md-3 col-lg-3" for="tbcmsblog_website">{l s='Website Url' mod='tbcmsblog'}</label>
          <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
            <input type="url"  id="tbcmsblog_website" name="tbcmsblog_website" class="form-control tbcmsblog_website">
          </div>
        </div>
        <div class="form-group tbcmsblog_subject_parent clearfix">
          <label class="col-xs-12 col-sm-12 col-md-3 col-lg-3" for="tbcmsblog_subject">{l s='Subject' mod='tbcmsblog'}</label>
          <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
            <input type="text"  id="tbcmsblog_subject" name="tbcmsblog_subject" class="form-control tbcmsblog_subject" required>
          </div>
        </div>
        <div class="form-group tbcmsblog_content_parent clearfix">
          <label class="col-xs-12 col-sm-12 col-md-3 col-lg-3" for="tbcmsblog_content">{l s='Comment' mod='tbcmsblog'}</label>
          <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
            <textarea rows="15" id="tbcmsblog_content" name="tbcmsblog_content" class="form-control tbcmsblog_content" required></textarea>
          </div>
        </div>
        <input type="hidden" class="tbcmsblog_id_parent" id="tbcmsblog_id_parent" name="tbcmsblog_id_parent" value="0">
        <input type="hidden" class="tbcmsblog_id_post" id="tbcmsblog_id_post" name="tbcmsblog_id_post" value="{$tbcmsblogpost.id_tbcmsposts}">
        <div class="tbblob-all-submit-btn">
          <input type="button" class="btn btn-info pull-left tbcmsblog_submit_btn" value="Submit Button">
        </div>
    </form>
</div>
{/strip}


<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>

$('.tbcmsblog_submit_btn').on("click",function(e) {
 e.preventDefault();
  // $('#tbcmsblogs_commentfrom').submit(function(event){
  //   event.preventDefault();
  // });
 var data = new Object();
 $('[id^="tbcmsblog_"]').each(function()
 {
     id = $(this).prop("id").replace("tbcmsblog_", "");
     data[id] = $(this).val();
 });
 function logErrprMessage(element, index, array) {
   $('.tbcmsblogs_message').append('<span class="tbcmsblogs_error">'+element+'</span>');
 }
 function tbcmsremove() {
   $('.tbcmsblogs_error').remove();
   $('.tbcmsblogs_success').remove();
 }
 function logSuccessMessage(element, index, array) {
   $('.tbcmsblogs_message').append('<span class="tbcmsblogs_success">'+element+'</span>');
 }

 $.ajax({
     url: tbcms_base_dir + 'modules/tbcmsblog/ajax.php',
     data: data,
     type:'post',
     dataType: 'json',
     beforeSend: function(){
         tbcmsremove();
         $(".tbcmsblog_submit_btn").val("Please wait..");
         $(".tbcmsblog_submit_btn").addClass("disabled");
     },
     complete: function(){
         $(".tbcmsblog_submit_btn").val("Submit Button");
         $(".tbcmsblog_submit_btn").removeClass("disabled"); 
     },
     success: function(data){
         tbcmsremove();
         if(typeof data.success != 'undefined'){
            data.success.forEach(logSuccessMessage);
            location.reload();
         }
         if(typeof data.error != 'undefined'){
             data.error.forEach(logErrprMessage);
         }
     },
     error: function(data){
         tbcmsremove();
         $('.tbcmsblogs_message').append('<span class="error">Something Wrong ! Please Try Again. </span>');
     },
  }); 
  e.stopPropagation();
});
</script>
