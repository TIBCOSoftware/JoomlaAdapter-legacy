<?php
/* Portions copyright © 2013, TIBCO Software Inc.
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
          <div style="display:none;">
            <?php if($this->user->id!==0):?>
             <div id="request-plan-form-<?php echo $item->id;?>">
                 <form  action = "" style="width:790px; margin:0 auto;">
                  <h1><?php echo JText::_("PLAN_REQUEST_BOX_TITLE_CONFIRMATION"); ?></h1>
                  <div class="control-group">
                    <label>
                      <?php echo JText::_("PLAN_REQUEST_PRODUCT"); ?>
                    </label>
                      <input type="text" disabled="disabled" value="<?php echo $record->title;?>" />
                  </div>
                  <div class="control-group">
                    <label>
                      <?php echo JText::_("PLAN_REQUEST_PLAN"); ?>
                    </label>
                      <input type="text" disabled="disabled" value="<?php echo $item->title;?>" />
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
                      <div class="span5">
                        <label for="dlimit">
                          <?php echo JText::_("PLAN_REQUEST_PER_DAY"); ?>
                        </label>
                        <div class="row-fluid">
                            <input  disabled="disabled" name="rate_limit" type="text" value="<?php echo $item->fields['80']; ?>"/>
                            <div style="display:none" class="alert alert-error"></div>
                        </div>
                      </div>
                      <div class="span5">
                        <label for="burst">
                          <?php echo JText::_("PLAN_REQUEST_PER_SENCONDS"); ?>
                        </label>
                        <div class="row-fluid">
                            <input  disabled="disabled" name="quota_limit" type="text" value="<?php echo $item->fields['79']; ?>"/>
                            <div style="display:none" class="alert alert-error"></div>
                        </div>
                      </div>
                    </div>
                  </div>
                     <div class="control-group">
                         <div>
                             <div class="agreeTo">I have read and agree to the</div>
                         <div class="termsToggle" style="cursor:pointer;"
                              onclick="jQuery(this).parent().find('.termsBlock').toggle();">Product-specific terms &amp; conditions</div>
                         <div class="termsBlock" style="display:none;">
                              <?php
                              echo "<h1>Terms & Conditions</h1>";
                              echo CobaltApi::renderField($product,129,"full");
                              ?>
                         </div>
                         </div>
                     </div>
                  <div class="control-group">
                    <?php //pre($item);jexit();?>
                    <input type="hidden" name="options" value="com_cobalt">
                    <input type="hidden" name="planId" value="<?php echo $item->id; ?>"/>
                    <input type="hidden" name="isAuto" value="<?php echo $item->fields[123][0];?>"/>
                    <input type="hidden" name="plan" value="<?php echo $item->title; ?>"/>
                    <input type="hidden" name="productId" value="<?php echo $record->id;?>"/>
                    <input type="hidden" name="product" value="<?php echo $record->title;?>"/>
                    <input type="hidden" name="task" value="ajaxMore.requestNormalPlan">
                    <button class="btn" onclick="SqueezeBox.close();return false;"><?php echo JText::_("PLAN_REQUEST_BUTTON_CANCLE"); ?></button>
                    <button class="btn btn-primary submit-plan-request"><?php echo JText::_("PLAN_REQUEST_BUTTON_SUBMIT"); ?></button>
                  </div>
                 </form>
             </div>
            <?php else:?>
              <div id="request-plan-form-<?php echo $item->id;?>">
                <div style="height:410px;line-height:410px;width:790px;text-align:center;">
                  <?php echo JText::_("JGLOBAL_YOU_MUST_LOGIN_FIRST");?>
                </div>
             </div>
            <?php endif;?>
          </div>
            <?php if (in_array(8, $this->user->get('groups')) || in_array(11, $this->user->get('groups'))): ?>
                <ul class="dropdown-menu" style="top:10%;">
                    <li><a href="<?php echo JURI::root(); ?>index.php/subscriptions/submit/6-subscriptions/10-subscription?sub_product_id=<?php echo $item->fields[53]; ?>&sub_plan_id=<?php echo $item->id; ?>&sub_uid=<?php echo $this->user->id; ?>"><img border="0" src="media/mint/icons/16/feed.png" alt="Edit" align="absmiddle"> <?php echo JText::_('PLAN_SUBSCRIBE'); ?></a></li>
                </ul>
            <?php endif; ?>
            <div class="plan-header">
                <h3><a style="word-wrap:break-word;word-break:break-all;" href="<?php echo JRoute::_($item->url);?>"><?php echo $item->title; ?></a>
                    <?php if (in_array(8, $this->user->get('groups')) || in_array(11, $this->user->get('groups'))): ?>
                        <input data-toggle="dropdown" type="image" src="media/mint/icons/16/gear.png"
                               style="float:right;display:none;" class="gear_p"/>
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
            <a class="plan-modal pull-right openapi-send-request" href="#request-plan-form-<?php echo $item->id;?>"><?php echo JText::_('PLAN_REQUEST'); ?></a>
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
            <div class="plan-limit">??? <?php echo JText::_('PLAN_DAILY_LIMIT'); ?></div>
            <div class="plan-burst">??? <?php echo JText::_('PLAN_BURST'); ?></div>
        </div>
        <a class="plan-modal pull-right" href="#request-plan-form" rel="boxed"><?php echo JText::_('PLAN_REQUEST'); ?></a>
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
<div style="display:none;">
   <div id="request-plan-form">
       <form  action="" style="width:790px; margin:0 auto;">
        <h1><?php echo JText::_("PLAN_REQUEST_BOX_TITLE"); ?></h1>
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
            <div class="span5">
              <label for="dlimit">
                <?php echo JText::_("PLAN_REQUEST_PER_DAY"); ?>
              </label>
              <div class="row-fluid">
                  <input id="rate_limit" name="rate_limit" type="text" />
                  <div style="display:none;margin-right:5px;" class="alert alert-error"></div>
              </div>
            </div>
            <div class="span5">
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
               <div class="termsToggle" style="cursor:pointer;"
                    onclick="jQuery(this).parent().find('.termsBlock').toggle();">Product-specific terms &amp; conditions</div>
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
          <button class="btn" onclick="SqueezeBox.close();return false;"><?php echo JText::_("PLAN_REQUEST_BUTTON_CANCLE"); ?></button>
          <button class="btn btn-primary submit-plan-request"><?php echo JText::_("PLAN_REQUEST_BUTTON_SUBMIT"); ?></button>
        </div>
       </form>
   </div>
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
        alert("hi");
        $(this).parent().find(".termsBlock").toggle();
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
        function showTip(res){
          var tips = res.success == 1 ? res.msg : res.error;
          var tbox = $('#sbox-content'), twidth=tbox.width(), theight=tbox.height()+'px';
          $("<div>").css({
              'text-align':'center',
              'width':twidth,
              'height':theight,
              'line-height':theight
          }).text(tips).replaceAll(tbox.children());

          setTimeout(function(){SqueezeBox.close();},3000);
        }

        function sendNotfyForCreatedSubscription(res){
          // console.log(res);
          DeveloperPortal.sendCreateNotification(res.result.record_id, DeveloperPortal.PORTAL_OBJECT_TYPE_SUBSCRIPTION, function(data){
            showTip(res);
          }, function(error){
            showTip({success:false,error:error});
          });
        }


        $(function(){
          var requestOrigiForm   =  $("#request-plan-form"),
              requestForm        =  $("#sbox-window form"),
              inputBoxs          =  requestForm.find("input[type!='hidden']:not(':disabled')"),
              submitButton       =  requestForm.find(".submit-plan-request");

          resetForm.apply(requestOrigiForm);

          inputBoxs.live('focus', function(){
            var alertBox = $(this).next("div.alert:visible");
            if(alertBox.length){
              alertBox.hide();
              $(this).val("");
            }
          });

          submitButton.live("click",function(){
              var flag            =   true,
                  requestForm     =   $(this).parents("#sbox-window").find("form"),
                  dlimit          =   requestForm.find("input[name='rate_limit']"),
                  slimit          =   requestForm.find("input[name='quota_limit']"),
                  data            =   requestForm.serialize(),
                  product_id      =   requestForm.find("input[name='productId']").val(),
                  plan_id         =   requestForm.find("input[name='planId']").val(),
                  isAuto          =   requestForm.find("input[name='isAuto']").val();

              if(!/^[1-9]\d{0,}$/.test(dlimit.val()))
              {
                dlimit.next("div.alert").text("Please input a number").slideDown();
                flag = false;
              }

              if(!/^[1-9]\d{0,}$/.test(slimit.val()))
              {
                slimit.next("div.alert").text("Please input a number").slideDown();
                flag = false;
              }

              if(!flag){return flag;}

              if(isAuto)
              {
                $.post('index.php?option=com_cobalt&task=ajaxmore.insertSub',{'product_id':product_id,'plan_id':plan_id},
                  function(res){
                      // console.log(result);
                      if(res.success == 1){
                        sendNotfyForCreatedSubscription(res);
                      }else{
                        showTip(res);
                      }
                  },'json');
                return false;
              }
              else
              {
                $.post('',data,showTip,'json');
              }
              return false;
          });
        });
  // Bind keypress event to the Quota Limit and Rate Limit, the max value is 4294967295
  $(document).on('keypress', '#sbox-window [name="rate_limit"], #sbox-window [name="quota_limit"]', function(evt) {
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
  $(document).on('paste', '#sbox-window [name="rate_limit"], #sbox-window [name="quota_limit"]', function(evt) {
    return false;
  });

      	SqueezeBox.assign($('a.plan-modal'), {
      		handler: 'clone',
          size: {x: 900, y: 500}
      	});
})(jQuery);

</script>
</div>