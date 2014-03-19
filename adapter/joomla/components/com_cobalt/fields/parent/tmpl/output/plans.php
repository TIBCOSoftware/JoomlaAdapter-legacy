<?php
/* Portions copyright @ 2013, TIBCO Software Inc.
 * All rights reserved.
 */
?>
<?php
    /**
     * Cobalt by MintJoomla
     * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
     * Author Website: http://www.mintjoomla.com/
     *
     * @copyright Copyright (C) 2012 MintJoomla (http://www.mintjoomla.com). All rights reserved.
     * @license   GNU/GPL http://www.gnu.org/copyleft/gpl.html
     */
    defined('_JEXEC') or die();
    require_once JPATH_BASE . "/includes/api.php";
    $appsOfUser = DeveloperPortalApi::getApplicationsForUser();
?>
<?php $fields = $this->content['list'];
    $mainframe = JFactory::getApplication();
    $user_orgs = DeveloperPortalApi::getUserOrganization();
    $user_org = !empty($user_orgs)?ItemsStore::getRecord($user_orgs[0]):null;
?>

<style>
    h5.plan_limit {
        height: 60px;
        padding-top: 5px;
        line-height: 20px;
    }
    
    input.agree_term[type="checkbox"]{
      margin-top: -5px;
    }
    .plan_level_1 {
    }

    .plan_level_2 {
    }

    .plan_level_3 {
    }

    .plan_level_4 {
    }
    .show-msg-box{
        line-height: 200px;
        text-align: center;
        /*display: none;*/
    }
    .show-msg-box-body{
        display: inline;
    }
    input {
      ime-mode: disabled;
    }
</style>

<?php $color = 0; ?>
<div class="plans-list">
<?php if (!empty($this->content['list'])): ?>
  <?php
    $items = $this->content['list'];
    function sortByLevel($a,$b){
      $levelA = $a->fields[39][0];
      $levelB = $b->fields[39][0];

      if ($levelA==$levelB) {
        return 0;
      }

      return ($levelA>$levelB?1:-1);
    }
    usort($items,'sortByLevel');
  ?>
  <?php foreach ($items as $key => $item): ?>
    <?php
      if ($item->fields[39][0]==-1) {continue; }
      $color++;
      $productid = $item->fields[53];
      $product = DeveloperPortalApi::getRecordById($productid);
      $productname=$product->title;
    ?>
    <div class="plan plan_level_<?php echo $key; ?> planHover primary-color<?php echo $color; ?>">
      <!--For request form-->
      <?php if($this->user->id!==0):?>
        <div id="request-plan-form-<?php echo $item->id;?>" class="modal hide fade" role="dialog" aria-hidden="true" width="540px;display:none;">
          <form action="">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
              <h3 id="myModalLabel"><?php echo JText::_("PLAN_REQUEST_BOX_TITLE_CONFIRMATION");?></h3>
            </div>
            <div class="modal-body">
              <div class="span6 pull-left">
                <div class="plan-title">
                  <h4><?php echo JText::_('PLAN_REQUEST_SELECTED_PLAN_TIPS'); ?></h4>
                  <span></span>
                </div>
                <div class="plan-body">
                  <div class="plan-name"><?php echo $item->title; ?></div>
                  <div class="plan-cost"><?php echo $item->fields['120']; ?></div>
                  <div class="plan-description"><?php echo $item->fields['37']; ?></div>
                  <div class="plan-limit"><?php echo $item->fields['80']; ?> <?php echo JText::_('PLAN_DAILY_LIMIT'); ?></div>
                  <div class="plan-burst"><?php echo $item->fields['79']; ?> <?php echo JText::_('PLAN_BURST'); ?></div>
                </div>
                <?php
                $planname = $item->title;
                $body = str_replace("+"," ",urlencode("Hello,\n\n We would like to place a request for a subscription to the following:\n\nProduct: ".$productname."\nPlan: ".$planname."\n\nPlease contact us at your earliest convenience."));
                ?>
              </div>
              <div class="span6 pull-right">
                <?php if(!empty($appsOfUser) && $item->fields[123][0]):?>
                <label><?php echo JText::_("PLAN_REQUEST_ATTACHED_APPS"); ?></label>
                <select class="selected_apps_for_plan" multiple>
                <?php foreach ($appsOfUser as $appItem):?> 
                <option value="<?php echo $appItem;?>"><?php echo ItemsStore::getRecord($appItem)->title;?></option>
                <?php endforeach;?>
                </select>
                <?php endif ?>
                <div class="control-group">
                  <div>
                    <input type="checkbox" name="agree_term" class="agree_term" value="1"/> <?php echo JText::_("PLAN_REQUEST_AGREEMENT_TIPS");?>
                     <a class="termsToggle" style="cursor:pointer;">  <?php echo JText::_("PLAN_REQUEST_PRODUCT_SPECIFIC_TERMS");?></a>
                    <div class="termsBlock" style="display:none;">
                      <?php
                      echo "<h1>Terms & Conditions</h1>";
                      echo CobaltApi::renderField($product,129,"full");
                      ?>
                    </div>
                  </div>
                  <div style="display:none;margin-right:5px;margin-bottom:0;margin-top:10px;" class="alert alert-error"></div>
                  <input type="hidden" name="options" value="com_cobalt">
                  <input type="hidden" name="planId" value="<?php echo $item->id; ?>"/>
                  <input type="hidden" name="isAuto" value="<?php echo $item->fields[123][0];?>"/>
                  <input type="hidden" name="plan" value="<?php echo $item->title; ?>"/>
                  <input type="hidden" name="productId" value="<?php echo $record->id;?>"/>
                  <input type="hidden" name="product" value="<?php echo $record->title;?>"/>
                  <input type="hidden" name="cname" value="<?php echo $record->id;?>"/>
                  <input type="hidden" name="task" value="ajaxMore.requestNormalPlan">
                </div>
                <div class="control-group">
                  <button class="btn cancel-submit-plan"><?php echo JText::_("PLAN_REQUEST_BUTTON_CANCLE"); ?></button>
                  <button class="btn btn-primary submit-plan-request"><?php echo JText::_("PLAN_PLACE_REQUEST"); ?></button>
                </div>
              </div>
            </div>
          </form>
        </div>
      <?php else:?>
      <div style="display:none;">
        <div id="request-plan-form-<?php echo $item->id;?>">
          <div style="height:410px;line-height:410px;width:790px;text-align:center;">
            <?php echo JText::_("JGLOBAL_YOU_MUST_LOGIN_FIRST");?>
          </div>
        </div>
      </div>
      <?php endif;?>
      <?php if (in_array(8, $this->user->get('groups')) || in_array(11, $this->user->get('groups'))): ?>
        <ul class="dropdown-menu" style="top:10%;">
          <li><a href="<?php echo JURI::root(); ?>index.php/subscriptions/submit/6-subscriptions/10-subscription?sub_product_id=<?php echo $item->fields[53]; ?>&sub_plan_id=<?php echo $item->id; ?>&sub_uid=<?php echo $this->user->id; ?>"><img border="0" src="media/mint/icons/16/feed.png" alt="Edit" align="absmiddle"> <?php echo JText::_('PLAN_SUBSCRIBE'); ?></a></li>
        </ul>
      <?php endif; ?>
      <div class="plan-header">
        <h3><a style="word-wrap:break-word;word-break:break-all;" href="<?php echo JRoute::_($item->url);?>"><?php echo $item->title; ?></a>
          <?php if (in_array(8, $this->user->get('groups')) || in_array(11, $this->user->get('groups'))): ?>
            <input data-toggle="dropdown" type="image" src="media/mint/icons/16/gear.png" style="float:right;display:none;" class="gear_p"/>
            <ul class="dropdown-menu" style="top:10%;">
              <li><a href="<?php echo JURI::root(); ?>index.php/subscriptions/submit/6-subscriptions/10-subscription?sub_product_id=<?php echo $item->fields[53]; ?>&sub_plan_id=<?php echo $item->id; ?>&sub_uid=<?php echo $this->user->id; ?>"><img border="0" src="media/mint/icons/16/feed.png" alt="Edit" align="absmiddle"> <?php echo JText::_('PLAN_SUBSCRIBE'); ?></a></li>
            </ul>
          <?php endif; ?>
        </h3>
        <div class="plan-amount"><?php echo $item->fields['120']; ?></div>
      </div>
      <div class="plan-sticker">
        <div class="page-curl"></div>
        <?php echo $item->fields['37']; ?>
        <div
            class="plan-limit"><?php echo $item->fields['80']; ?> <?php echo JText::_('PLAN_DAILY_LIMIT'); ?></div>
        <div class="plan-burst"><?php echo $item->fields['79']; ?> <?php echo JText::_('PLAN_BURST'); ?></div>
      </div>
      <?php
        $planname = $item->title;
        $body = str_replace("+"," ",urlencode("Hello,\n\n We would like to place a request for a subscription to the following:\n\nProduct: ".$productname."\nPlan: ".$planname."\n\nPlease contact us at your earliest convenience."));
      ?>
      <?php if($this->user->id!==0): ?>
        <a class="plan-modal pull-right openapi-send-request" href="#request-plan-form-<?php echo $item->id;?>" data-toggle="modal"><?php echo JText::_('PLAN_REQUEST'); ?></a>
      <?php else:?>
        <a class="pull-right" href="<?php echo JURI::root(); ?>index.php/component/users?return=<?php echo base64_encode(JURI::current());?>&p=1"><?php echo JText::_('JLOGIN');?></a>
      <?php endif; ?>
    </div>
    <?php if ($color == 5): ?>
        <?php $color = 0; ?>
    <?php endif; ?>
  <?php endforeach; ?>
<?php endif; ?>
<?php
  $color++;
  $productid = $item->fields[53];
  $product = DeveloperPortalApi::getRecordById($productid);
  $productname=$product->title;
?>

    <!--Custom plan goes here-->
    <?php if($this->user->id!==0):?>
    <div class="plan custom-plan primary-color<?php echo $color; ?>">
        <div class="plan-header">
            <h3>Custom</h3>
            <div class="plan-amount">CUSTOM</div>
        </div>
        <div class="plan-sticker">
            <div class="page-curl"></div>
            <p><?php echo 'Customize your own plan.'; ?></p>
            <!-- <div class="plan-limit">??? <?php echo JText::_('PLAN_DAILY_LIMIT'); ?></div> -->
            <!-- <div class="plan-burst">??? <?php echo JText::_('PLAN_BURST'); ?></div> -->
        </div>
        <a class="plan-modal pull-right" href="#request-plan-form" data-toggle="modal"><?php echo JText::_('PLAN_REQUEST'); ?></a>
        <?php
            $planname = "Custom";
            $body = str_replace("+"," ",urlencode("Hello,\n\n We would like to place a request for a subscription to the following:\n\nProduct: ".$productname."\nPlan: ".$planname."\n\nPlease contact us at your earliest convenience."));
        ?>
    </div>
    <?php endif; ?>
    <?php if($this->user->id==0 && !count($items)):?>
      <div style="line-height:200px;height:200px;width:100%;text-align:center;">
        <?php echo JText::_("PLAN_NO_AVAILABLE_ITEM"); ?>
      </div>
    <?php endif;?>
    <div class="clearfix"></div>
<br/>

<!--For request form-->
<div id="request-plan-form" class="modal hide fade" role="dialog" aria-hidden="true">
  <form  action="" style="width:790px; margin:0 auto;">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      <h3 id="myModalLabel"><?php echo JText::_("PLAN_REQUEST_BOX_TITLE"); ?></h3>
    </div>
    <div class="modal-body">
      <div class="span11">
        <p><?php echo JText::_("PLAN_REQUEST_BOX_DESC"); ?></p>
        <div class="control-group">
          <label>
            <?php echo JText::_("PLAN_REQUEST_PRODUCT"); ?>
          </label>
          <input type="text" disabled="disabled" value="<?php echo $record->title;?>" />
          <input type="hidden" name="productId" value="<?php echo $record->id;?>"/>
        </div>
        <div class="control-group">
          <label>
            <?php echo JText::_("PLAN_REQUEST_ORGANIZATION"); ?>
          </label>
          <input type="text" disabled="disabled" value="<?php echo is_null($user_org)?'':$user_org->title;?>"/>
          <input type="hidden" name="cname" value="<?php echo $record->id;?>"/>
        </div>
        <div class="control-group">
          <label>
            <?php echo JText::_("PLAN_REQUEST_EMAIL"); ?>
          </label>
          <input type="text" disabled="disabled" value="<?php echo $this->user->email;?>" />
        </div>
        <div class="control-group">
          <div class="row-fluid">
            <div class="span6">
              <label for="dlimit">
                <?php echo JText::_("PLAN_REQUEST_PER_DAY"); ?>
              </label>
              <div class="row-fluid">
                  <input id="rate_limit" name="rate_limit" type="text" />
                  <div style="display:none;margin-right:5px;" class="alert alert-error"></div>
              </div>
            </div>
            <div class="span6">
              <label for="burst">
                <?php echo JText::_("PLAN_REQUEST_PER_SENCONDS"); ?>
              </label>
              <div class="row-fluid">
                  <input id="quota_limit" name="quota_limit" type="text" />
                  <div style="display:none;margin-right:5px;" class="alert alert-error"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="control-group">
          <label for="arequest">
            <?php echo JText::_("PLAN_REQUEST_EXTRA_INFO"); ?>
          </label>
          <textarea id="additional_request" name="additional_request"></textarea>
        </div>
        <div class="control-group">
           <div>
             <input type="checkbox" name="agree_term" class="agree_term" value="1"/> <?php echo JText::_("PLAN_REQUEST_AGREEMENT_TIPS");?>
             <a class="termsToggle" style="cursor:pointer;">Product-specific terms &amp; conditions</a>
             <div class="termsBlock" style="display:none;">
              <?php
              echo "<h1>Terms & Conditions</h1>";
              echo CobaltApi::renderField($product,129,"full");
              ?>
             </div>
           </div>
        </div>
        <div class="control-group">
          <input type="hidden" name="options" value="com_cobalt">
          <input type="hidden" name="task" value="ajaxMore.requestCustomPlan">
          <button class="btn cancel-submit-plan"><?php echo JText::_("PLAN_REQUEST_BUTTON_CANCLE"); ?></button>
          <button class="btn btn-primary submit-plan-request"><?php echo JText::_("PLAN_PLACE_REQUEST"); ?></button>
        </div>
      </div>
    </div>
  </form>
</div>



<!--For Result Tips-->
<div style="display:none;">
  <div id="show-msg-box" class="show-msg-box"><span class="show-msg-box-body"></span></div>
</div>

<?php

    if ($this->show_btn_new) {
        $url = 'index.php?option=com_cobalt&view=form';
        $url .= '&section_id='.$section->id;
        $url .= '&type_id='.$type->id;
        $url .= '&fand='.$record->id;
        $url .= '&field_id='.$this->params->get('params.child_field');
        $url .= '&return='.Url::back();
        $url .= '&Itemid='.$section->params->get('general.category_itemid');

        $links[] = sprintf('<a href="%s" class="btn btn-small">%s</a>', JRoute::_($url), $this->params->get('params.invite_add_more'));
    }

    if ($this->show_btn_all) {
        $url = 'index.php?option=com_cobalt&view=records';
        $url .= '&section_id='.$section->id;
        $url .= '&task=records.filter';
        $url .= '&filter_name[0]=filter_type';
        $url .= '&filter_val[0]='.$type->id;
        $url .= '&filter_name[1]=filter_'.$this->field_key;
        $url .= '&filter_val[1]='.$record->id;
        $url .= '&Itemid='.$section->params->get('general.category_itemid');

        $links[] = sprintf('<a href="%s" class="btn btn-small">%s</a>', JRoute::_($url), $this->params->get('params.invite_view_more'));
    }
?>

<?php if (!empty($links)): ?>
    <?php echo implode(' ', $links); ?>
<?php endif; ?>

<script type="text/javascript">
(function ($) {
    $(".termsToggle").click(function(e) {
      $(this).parent().find(".termsBlock").toggle();
      return false;
    });

        $('.planHover').hover(function (e) {
            $(this).find('.gear_p').show();
        }, function (e) {
            $(this).find('.gear_p').hide();
        });

        //reset form when the page reload
        function resetForm(){
              this.find("input[type!='hidden']:not(':disabled')").val("");
              this.find("textarea").val("");
        }

        //show the tip message, whatever is error message or success message
        function showTip(res, container){
            var tips = res.success == 1 ? res.result.msg : res.error;
            var status_class = res.success == 1 ? "alert-success" : "alert-error";
            container.addClass(status_class);
             container.removeClass('alert-error').addClass(status_class);
             container.find('#myModalLabel').text(tips);
           }


        function sendNotfyForCreatedSubscription(res, message_container){

           //  console.log(res);
            DeveloperPortal.sendCreateNotification(res.result.record_id, DeveloperPortal.PORTAL_OBJECT_TYPE_SUBSCRIPTION, function(data){
              	if (res.result.appIds){
              		sendNotfyForUpdatedApplication(res,message_container);  
                }
                 else{
                 	 showTip(res, message_container);	
                 }
  				  }, function(error){
              showTip({success:false,error:error}, message_container);
            });
          }


          function sendNotfyForUpdatedApplication(res, message_container){
             for(i = 0; i < res.result.appIds.length; i++) {
               var appid=res.result.appIds[i],updatedfields='';
               if(res.result.app_old_subscriptions){
                 updatedfields = {115:res.result.app_old_subscriptions[appid]};
               }else{
                 updatedfields = {115:[]};
               }
            	 DeveloperPortal.sendUpdateNotification(appid, DeveloperPortal.PORTAL_OBJECT_TYPE_APPLICATION,updatedfields,function(data){
               showTip(res, message_container);
              }, function(error){
               showTip({success:false,error:error}, message_container);
              });
            }
          }

        
        $(function(){
          var requestOrigiForm   =  $("#request-plan-form"),
              requestForm        =  $("[id^='request-plan-form']"),
              inputBoxs          =  requestForm.find("input[type!='hidden']:not(':disabled')"),
              submitButton       =  requestForm.find(".submit-plan-request"),
              cancelButton       =  requestForm.find(".cancel-submit-plan");


          resetForm.apply(requestOrigiForm);

          inputBoxs.live('focus', function(){
            var alertBox = $(this).next("div.alert:visible");
            if(alertBox.length){
              alertBox.hide();
              $(this).val("");
            }
          });
          
          submitButton.live("click",function(e){
              var flag                =   true,
                  requestForm         =   $(this).parent().parent().parent().parent().parent(),
                  form_id             =   requestForm.attr("id");
                  dlimit              =   requestForm.find("input[name='rate_limit']"),
                  slimit              =   requestForm.find("input[name='quota_limit']"),
                  data                =   requestForm.find('form').serialize(),
                  sub_data            =   null;
                  product_id          =   requestForm.find("input[name='productId']").val(),
                  plan_id             =   requestForm.find("input[name='planId']").val(),
                  isAuto              =   requestForm.find("input[name='isAuto']").val(),
                  selected_apps_box   =   requestForm.find("select.selected_apps_for_plan"),
                  selected_apps       =   [],
                  agree_term         =  requestForm.find(".agree_term");
              if(!agree_term.get(0).checked){
                console.log(agree_term);
                requestForm.find('.modal-header').addClass('alert-error');
                requestForm.find('#myModalLabel').text("<?php echo JText::_('PLAN_REQUEST_INVALID_AGREEMENT'); ?>")
                flag = false;
              }
              if(dlimit.length && !/^[1-9]\d{0,}$/.test(dlimit.val()))
              {
                console.log(dlimit.length);
                requestForm.find('.modal-header').addClass('alert-error');
                requestForm.find('#myModalLabel').text("<?php echo JText::_('PLAN_REQUEST_INVALID_DLIMIT'); ?>");
                dlimit.next("div.alert").text("<?php echo JText::_('PLAN_REQUEST_INVALID_DLIMIT');?>").slideDown();
                flag = false;
              }

              if(slimit.length && !/^[1-9]\d{0,}$/.test(slimit.val()))
              {
                console.log(slimit.length);
                requestForm.find('.modal-header').addClass('alert-error');
                requestForm.find('#myModalLabel').text("<?php echo JText::_('PLAN_REQUEST_INVALID_DLIMIT'); ?>");
                slimit.next("div.alert").text("<?php echo JText::_('PLAN_REQUEST_INVALID_DLIMIT');?>").slideDown();
                flag = false;
              }

              if(!flag){return flag;}
              submitButton.attr("disabled", true);
              if(isAuto)
              {
                if(selected_apps_box.length){
                  selected_apps_box.find("option:selected").each(function(index, ele){
                    var app_id = parseInt($(ele).val());
                    if(app_id > 0){
                      selected_apps.push(app_id);
                    }
                  });
                  selected_apps = selected_apps.join();
                }

                sub_data = {
                             'product_id':product_id,
                             'plan_id':plan_id
                           };

                if(selected_apps.length){
                  sub_data.selected_apps = selected_apps;
                  requestForm.on("hidden",function(){
                     window.location.reload();
                  });
                }


                $.post('index.php?option=com_cobalt&task=ajaxmore.insertSub',sub_data,
                  function(res){
                      if(res.success == 1){
                        sendNotfyForCreatedSubscription(res, requestForm.find('.modal-header'));
                      }else{
                        showTip(res, requestForm.find('.modal-header'));
                      }
                  },'json');
                return false;
              }
              else
              {
            	requestForm.find('.modal-header').removeClass('alert-error').find('#myModalLabel').text("<?php echo JText::_("PLAN_REQUEST_WAIT_MSG");?>");
                $.post('',data,function(res) {
                  showTip(res, requestForm.find('.modal-header'));
                },'json');
              }
              return false;
          });
          cancelButton.live('click', function(e) {
            e.preventDefault();
            var requestForm = $(this).parent().parent().parent().parent().parent();
            requestForm.modal('hide');
          });
        });

  // Bind keypress event to the Quota Limit and Rate Limit, the max value is 4294967295
  $(document).on('keypress', '#request-plan-form [name="rate_limit"], #request-plan-form [name="quota_limit"]', function(evt) {
    if ((evt.charCode>=48&&evt.charCode<=57) || evt.charCode==0) {
      if (evt.keyCode == 13) {
        return false;
      } else {
        var input = String.fromCharCode(evt.charCode);
        var newVal = parseInt($(this).val() + input);
        if (newVal>4294967295) {
          $(this).val('4294967295');
          return false;
        }
      }
    } else {
      return false;
    }
  });
  $(document).on('paste', '#request-plan-form [name="rate_limit"], #request-plan-form [name="quota_limit"]', function(evt) {
    return false;
  });
  $('[id^="request-plan-form"]').on('hidden.bs.modal', function() {
  	$(".submit-plan-request").attr('disabled',false);
    $(this).find('.modal-header').removeClass('alert-error').removeClass('alert-success');
    if ($(this).attr('id') == "request-plan-form") {
      $(this).find('#myModalLabel').text("<?php echo JText::_("PLAN_REQUEST_BOX_TITLE");?>");
    } else {
      $(this).find('#myModalLabel').text("<?php echo JText::_("PLAN_REQUEST_BOX_TITLE_CONFIRMATION");?>");
    }
  });
})(jQuery);

</script>
</div>