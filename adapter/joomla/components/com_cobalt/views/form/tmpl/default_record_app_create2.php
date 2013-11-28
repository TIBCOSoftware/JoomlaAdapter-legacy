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
 * @copyright Copyright (C) 2012 MintJoomla (http://www.mintjoomla.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined('_JEXEC') or die();
require_once JPATH_ROOT . "/includes/api.php";

$user_profile_id = DeveloperPortalApi::getUserProfileId();

$orgId = 0;
$ownedSubscriptions = array();
if (empty($this->item->id)) {
	$orgIDS = DeveloperPortalApi::getUserOrganization($user_profile->id);
	$orgId = $orgIDS[0];
}else{
	$orgId = DeveloperPortalApi::valueForKeyFromJson($jsonStr, 60, $this->item->id);
	$ownedSubscriptions = DeveloperPortalApi::subscriptionsInApplication($this->item->id);
}
$subscriptions = DeveloperPortalApi::subscriptionsOfOrgnazions($orgId);
$groupedItems = DeveloperPortalApi::classify_subscriptions($subscriptions);
$itemCount = 0;

$products = DeveloperPortalApi::getProductsForUser();
$product_ids_in_application = DeveloperPortalApi::getProductIdsInApplication($this->item->id);
$keys = DeveloperPortalApi::getKeysOfApplication($this->item->id);
$active_key = DeveloperPortalApi::getActiveKeyOfApplication($this->item->id);
?>

<style type="text/css">
    .plans {
        background-color: #F2F2F2;
        padding: 15px 0px 15px 15px;
        width: 98%;
    }

    .plan {
        background-color: #FFFFFF;
        border: 1px solid #CFCFCF;
        display: inline-block;
        height: 91%;
        margin-right: 9px;
        padding: 15px 15px;
        position: relative;
        vertical-align: bottom;
        width: 20%;
    }

    .your-plan {
        background-color: #FFFFCC;
    }

    .your-plan-title {
        background-color: inherit;
        border: 1px solid #CFCFCF;
        font-weight: bold;
        height: 20px;
        left: 20px;
        line-height: 20px;
        position: absolute;
        right: 20px;
        text-align:  center;
        top: -10px;
        z-index: 10000;
    }

    .custom-plan {
        background-color: #CCFFFF;
    }

    .img-place-holder {
        background-color: #F2F2F2;
        display: block;
        height: 100px;
        width: 100%;
    }

    .round-corner-5px {
        -moz-border-radius: 5px;
        -ms-border-radius: 5px;
        -o-border-radius: 5px;
        -webkit-border-radius: 5px;
        border-radius: 5px;
    }

    div#tab-overview div:first-child div{
        float:left;
        width:35%;
        height:auto;
        margin-bottom:15px;
        padding-right:12px;
        overflow:hidden;
        word-wrap: break-word;
    }

    div#tab-overview div:first-child div.d_mid{
        width:15%;
    }

    div#tab-overview div:first-child div span:first-child{
        color:#909090;
    }
    div#tab-overview div:first-child div dt{
        font-weight:normal;
    }

    a.plus, a.minus {
        height: 18px;
        width: 8px;
    }
</style>
<article class="<?php echo $this->appParams->get('pageclass_sfx')?><?php if($this->item->featured) echo ' article-featured' ?>">
	<?php if($this->item->id!=0): ?>
    <div class="container-fluid">

    </div>
	<?php endif?>
	
	<div id="list_panel">
        <h3><?php echo JText::_( 'ADD_PRODUCT_TO_APP_TEXT' );?>
        </h3>
       
			<div id="products-list">
				<?php foreach($groupedItems as $key => $value): ?>
					<div class="inline-documents" style="width:100%;">
						<div class="inline-doc">
							<h2><span id="prod-item-title"><?php echo CobaltApi::getArticleLink($key); ?></span></h2>
							<div class="inline-doc-content">
								<table  class="table app-products-table">
									<thead>
										<tr>
											<th>Plan</th>
											<th>Detail</th>
											<th>Status</th>
											<th>Enabled</th>
										</tr>
									</thead>
									<tbody>
										<?php $row_count=0; ?>
										<?php foreach($value as $item): ?>
											<?php
											$status = DeveloperPortalApi::valueForKeyFromJson("", 78, $item->id);
											$endTime = DeveloperPortalApi::valueForKeyFromJson($item->fields,72);
											if (!empty($endTime)) {
												$curTimestamp = mktime(0,0,0,date("m"),date("d"),date("Y"));
												$desTimestamp = mktime(0,0,0,((int)substr($endTime[0],5,2)),((int)substr($endTime[0],8,2)),((int)substr($endTime[0],0,4)));
												if($curTimestamp-$desTimestamp>0){
													$status = "Expired";
												}
											}
											
											if ($status!='Active') {
												continue;
											}
											
											$plan = DeveloperPortalApi::getRecordById(DeveloperPortalApi::valueForKeyFromJson("", 69, $item->id));
											$limit = DeveloperPortalApi::valueForKeyFromJson($plan->fields,80);
											$burst = DeveloperPortalApi::valueForKeyFromJson($plan->fields,79);
											$ownedFlag = in_array($item->id, $ownedSubscriptions);
											$row_count++;
											?>
								            <?php if($ownedFlag): ?>
											<input type="hidden" name="jform[fields][63][]" value="<?php echo $key; ?>" />
								            <input type="hidden" name="jform[fields][115][]" value="<?php echo $item->id; ?>" />
								            <?php endif; ?>
										<tr>
											<td>
												<?php echo $plan->title; ?>
											</td>
											<td>
												<p><?php echo $limit.' per second<br/>'.$burst.' per day'; ?></p>
											</td>
											<td>
												<?php 
												
												echo $status;
												?>
											</td>
											<td class="actions">
							                    <a class="btn plus" <?php if($ownedFlag) echo 'style="display: none;"'; ?> subscription-id="<?php echo $item->id; ?>" data-product-id="<?php echo $key; ?>">+</a>
							                    <a class="btn minus" <?php if(!$ownedFlag) echo 'style="display: none;"'; ?> subscription-id="<?php echo $item->id; ?>" data-product-id="<?php echo $key; ?>">-</a>
											</td>
										</tr>
										<?php endforeach; ?>
										<?php if ($row_count==0): ?>
											<tr>
												<td colspan='4' class='center'>No available subscription to <?php echo CobaltApi::getArticleLink($key); ?>.</td>
											</tr>
										<?php endif ?>
									</tbody>
								</table>
							</div>
						</div>    
					</div>
				<?php endforeach; ?>
				<div class="clearfix"></div>
				<style>
					.inline-doc h2{
						cursor:pointer;
						padding-left:10px;
						font-size:14px;
					}
				</style>
		    	<script type="text/javascript">
		        (function ($) {
		            $('.inline-doc h2').click(function (e) {
		                if($(this).parent().hasClass("active")) {
		                    $(this).parent().removeClass("active");
		                }else {
		                    $(this).parent().addClass("active");
		                }
		            });
		        })(jQuery);
		    	</script>
			</div>
        <?php foreach($keys as $key): ?>
            <input type="hidden" name="jform[fields][87][]" value="<?php echo $key; ?>" />
        <?php endforeach; ?>
    </div>
</article>

<script type="text/javascript">
    (function($){
        $(function(){
            $("a.plus").live("click",function(){
				$(this).closest('tbody').find('input').next("tr").find(".actions .plus").show().next(".minus").hide();
				$(this).closest('tbody').find('input').remove();
				$(this).closest('tr').before('<input type="hidden" name="jform[fields][115][]" value="' + $(this).attr("subscription-id") + '">');
				$(this).closest('tr').before('<input type="hidden" name="jform[fields][63][]" value="' + $(this).attr("data-product-id") + '">');
                $(this).hide().next(".minus").show();
            });

            $('a.minus').live('click', function() {
            	$('input[name="jform[fields][115][]"][value="' + $(this).attr("subscription-id") + '"]').remove();
				$('input[name="jform[fields][63][]"][value="' + $(this).attr("data-product-id") + '"]').remove();
            	$(this).hide().prev(".plus").show();
            });
        });
		$('#prod-item-title a').attr('href','javascript:void(0)');
    })(jQuery);

</script>
