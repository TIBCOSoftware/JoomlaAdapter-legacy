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

require_once JPATH_BASE . "/includes/api.php";
require_once JPATH_BASE . "/components/com_cobalt/library/php/helpers/developerportal.php";
$item = $this->item;
$params = $this->tmpl_params['record'];
$icons = array();
$category = array();
$author = array();
$details = array();
$user_profile_id = DeveloperPortalApi::getUserProfileId();
$orgId = DeveloperPortalApi::valueForKeyFromJson($jsonStr, 60, $this->item->id);
$subscriptions = DeveloperPortalApi::subscriptionsOfOrgnazions($orgId);
$groupedItems = DeveloperPortalApi::classify_subscriptions($subscriptions);
$ownedSubscriptions = DeveloperPortalApi::subscriptionsInApplication($this->item->id);
$started = FALSE;
$i = $o = 0;
$fields = array();
$tasks_to_hide = array();
$old_keys = DeveloperPortalApi::getKeysOfApplication($this->item->id);
$app_info = DeveloperPortalApi::getApplicationInformation($this->item->id);
$app_oauth = DeveloperPortalApi::getAppOAuth($this->item->id);
$user = JFactory::getUser();
$active_key = count($app_oauth) > 0 && $app_oauth[0]->use_oauth == 1 ? DeveloperPortalApi::getActiveOAuthKeyOfApp($this->item->id) : DeveloperPortalApi::getActiveKeyOfApplication($this->item->id);
?>
<style>
<?php echo $params->get('tmpl_params.css');?>
</style>

<?php
if($params->get('tmpl_core.item_categories') && $item->categories_links)
{
	$category[] = sprintf('<dt>%s<dt> <dd>%s<dd>', (count($item->categories_links) > 1 ? JText::_('CCATEGORIES') : JText::_('CCATEGORY')), implode(', ', $item->categories_links));
}
if($params->get('tmpl_core.item_user_categories') && $item->ucatid)
{
	$category[] = sprintf('<dt>%s<dt> <dd>%s<dd>', JText::_('CUCAT'), $item->ucatname_link);
}
if($params->get('tmpl_core.item_author') && $item->user_id)
{
	$a[] = JText::sprintf('CWRITTENBY', CCommunityHelper::getName($item->user_id, $this->section));
	if($params->get('tmpl_core.item_author_filter'))
	{
		$a[] = FilterHelper::filterButton('filter_user', $item->user_id, NULL, JText::sprintf('CSHOWALLUSERREC', CCommunityHelper::getName($item->user_id, $this->section, array('nohtml' => 1))), $this->section);
	}
	$author[] = implode(' ', $a);
}
if($params->get('tmpl_core.item_ctime'))
{
	$author[] = JText::sprintf('CONDATE', JHtml::_('date', $item->created, $params->get('tmpl_core.item_time_format')));
}

if($params->get('tmpl_core.item_mtime'))
{
	$author[] = JText::_('CMTIME').': '.JHtml::_('date', $item->modify, $params->get('tmpl_core.item_time_format'));
}
if($params->get('tmpl_core.item_extime'))
{
	$author[] = JText::_('CEXTIME').': '.($item->expire ? JHtml::_('date', $item->expire, $params->get('tmpl_core.item_time_format')) : JText::_('CNEVER'));
}

if($params->get('tmpl_core.item_type'))
{
	$details[] = sprintf('%s: %s %s', JText::_('CTYPE'), $this->type->name, ($params->get('tmpl_core.item_type_filter') ? FilterHelper::filterButton('filter_type', $item->type_id, NULL, JText::sprintf('CSHOWALLTYPEREC', $this->type->name), $this->section) : NULL));
}
if($params->get('tmpl_core.item_hits'))
{
	$details[] = sprintf('%s: %s', JText::_('CHITS'), $item->hits);
}
if($params->get('tmpl_core.item_comments_num'))
{
	$details[] = sprintf('%s: %s', JText::_('CCOMMENTS'), CommentHelper::numComments($this->type, $this->item));
}
if($params->get('tmpl_core.item_favorite_num'))
{
	$details[] = sprintf('%s: %s', JText::_('CFAVORITED'), $item->favorite_num);
}
if($params->get('tmpl_core.item_follow_num'))
{
	$details[] = sprintf('%s: %s', JText::_('CFOLLOWERS'), $item->subscriptions_num);
}
?>

<style type="text/css">
	.detail_panel{
		overflow:hidden;
		padding:0;
	}

	.detail_panel ul{
		float:left;
		list-style:none;
		margin-bottom:15px;
		margin-left:10px;
		width:35%;
	}

	.detail_panel ul li span:first-child{
		display:inline-block;
		width:80px;
		text-align: left;
	}

</style>

<article class="<?php echo $this->appParams->get('pageclass_sfx')?><?php if($item->featured) echo ' article-featured' ?>">
    <div class="app-details">
	<?php if(!$this->print):?>
		<div class="pull-right controls">
			<div class="btn-group">
				<?php if($this->user->get('id')):?>
       						<button data-toggle="dropdown" class="btn">Action</button>
						<ul class="dropdown-menu" style="margin-left:-90px;">
							<?php if($item->controls):?>
							<?php echo DeveloperPortalApi::list_controls($item->controls, $tasks_to_hide, $this->item->id, $this->item->type_id); ?>
							<?php endif;?>
							<?php if($params->get('tmpl_core.item_print')):?>
								<li><a rel="tooltip" data-original-title="<?php echo JText::_('CPRINT');?>" onclick="window.open('<?php echo JRoute::_($this->item->url.'&tmpl=component&print=1');?>','win2','status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no'); return false;">
									<?php echo HTMLFormatHelper::icon('printer.png').'Print';  ?></a></li>
							<?php endif;?>
							<li><?php echo DeveloperPortalFormatHelper::bookmark_list($item, $this->type, $params);?></li>
							<li><?php echo DeveloperPortalFormatHelper::follow_list($item, $this->section);?></li>
							<li><?php echo HTMLFormatHelper::repost($item, $this->section);?></li>
						</ul>
				<?php endif;?>
                       <button class="btn" <?php echo count($ownedSubscriptions) > 0 ? 'onclick="DeveloperPortal.requestKey(' . $this->item->id . ', ' . makeArrayString($old_keys) . ', ' . count($active_key) . ', function() {window.location.reload();}, function() {window.location.reload();})"' : 'disabled="disabled"'; ?>>Request Key</button>
			</div>
		</div>
	<?php else:?>
		<div class="pull-right controls">
			<a href="#" class="btn btn-mini" rel="tooltip" data-original-title="<?php echo JText::_('CPRINT');?>" onclick="window.print();return false;"><?php echo HTMLFormatHelper::icon('printer.png');  ?></a>
		</div>
	<?php endif;?>
        <h3 class="pull-left">Applications > <?php echo $item->title; ?></h3>
	<div class="clearfix"></div>

        <div class="detail_panel">
            <div class="details_panel_left">
            <div class="app-image-box primary-color<?php echo rand(1,7); ?>">
                <?php echo $item->fields_by_id[119]->result;?>
                <div class="newsflash-title"><?php echo $item->title; ?></div>
            </div>
            <div class="app-basic-details">
                Partner: <span class="value"><?php echo $app_info[0]->org; ?> </span><br/>
                Contact: <span class="value"><?php echo $app_info[0]->contact; ?></span>
            </div>
            </div>
            <div class="details_panel_right">
            <?php if(count($app_oauth) == 0 || $app_oauth[0]->use_oauth == -1): ?>
    			<div class="keyinfo ">
                    <span class="k_title">API key:</span><span id="span_api_key"><?php echo (isset($active_key[0]->apiKey) ? $active_key[0]->apiKey : JText::_("NO_ACTIVE_KEY")); ?></span>
                </div>
            <?php else: ?>
          <div class="clientinfo">
     				<span class="k_title">Client ID:</span><span class="value"><?php echo (isset($active_key[0]->apiKey) ? $active_key[0]->apiKey : JText::_("NO_ACTIVE_KEY")); ?></span><br/>
     				<span class="k_title">Client secret:</span><span class="value"><?php echo (isset($active_key[0]->secret) ? $active_key[0]->secret : JText::_("NO_ACTIVE_KEY")); ?></span><br/>
<!--      				<span class="k_title">Redirect URIs:</span><span class="value">xxxxxx</span><br/>
     				<span class="k_title">Response Type:</span><span class="value">xxxxxx</span><br/>
     				<span class="k_title">Client type:</span><span class="value">xxxxxx</span> -->
     			</div>
     		<?php endif; ?>
    			<div class="app-desc-full">
    				<?php echo $item->fields_by_id[57]->result; ?>
    			</div>
			</div>
        </div>
    </div>
    <!--Scopes list goes here-->
    <?php $scopes = $item->fields_by_id['125'];?>
    <?php if($scopes):?>
      <?php if($scopes->params->get('core.show_lable') > 1):?>
          <?php echo $scopes->label; ?>
          <?php if($scopes->params->get('core.icon')):?>
            <?php echo HTMLFormatHelper::icon($scopes->params->get('core.icon'));  ?>
          <?php endif;?>
      <?php endif;?>
      <?php echo $scopes->result; ?>
    <?php endif;?>

	<div id="list_panel">
		<a id="edit_products" class="btn pull-right" href="<?php echo JURI::root(); ?>index.php/applications?task=form.edit&amp;id=<?php echo $this->item->id; ?>:<?php echo urlencode($this->item->title); ?>">Edit</a>
		<h3>Products used by '<?php echo $item->title; ?>'</h3>
		<div id="products-list">
					<?php foreach($groupedItems as $key => $value): ?>
						<?php if (!in_array($key,$this->item->fields[63])): ?>
							<?php continue;?>
						<?php endif ?>
						<div class="inline-documents" style="width:100%;">
							<div class="inline-doc">
                                <?php
                                    $prod = ItemsStore::getRecord($key);
                                    $prod_url = JRoute::_(Url::record($prod));
                                ?>
								<h2><span id="prod-item-title"><?php echo CobaltApi::getArticleLink($key); ?></span></h2><h3><span><a href="<?php echo $prod_url; ?>">view</a></span></h3>
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
											<?php foreach($value as $item): ?>
												<?php
													$plan = DeveloperPortalApi::getRecordById(DeveloperPortalApi::valueForKeyFromJson("", 69, $item->id));
													$limit = DeveloperPortalApi::valueForKeyFromJson($plan->fields,80);
													$burst = DeveloperPortalApi::valueForKeyFromJson($plan->fields,79);
													$i ++;
												?>
											<tr>
												<td>
													<?php echo $plan->title; ?>
												</td>
												<td>
													<p><?php echo $limit.' per second<br/>'.$burst.' per day'; ?></p>
												</td>
												<td>
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
													echo $status;
													?>
												</td>
												<td>
													<?php echo in_array($item->id,$ownedSubscriptions)?'&radic;':'&nbsp;'; ?>
												</td>
											</tr>
											<?php endforeach; ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
					<?php if (count($this->item->fields[63])==0): ?>
						<div class="inline-doc">
							<h2><span class="prod-item-title"><a href="javascript:void(0);"><?php echo JText::_("EDIT_PRODUCT_NOTE");?></a></span></h2>
						</div>
					<?php endif ?>
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
	</div>


	<?php echo $this->loadTemplate('tags');?>
</article>

<?php if($started):?>
	<script type="text/javascript">
		<?php if(in_array($params->get('tmpl_params.item_grouping_type', 0), array(1))):?>
			jQuery('#tabs-list a:first').tab('show');
		<?php elseif(in_array($params->get('tmpl_params.item_grouping_type', 0), array(2))):?>
			jQuery('#tab-main').collapse('show');
		<?php endif;?>
	</script>
<?php endif;?>

<script type="text/javascript">

    (function($) {
        jQuery('document').ready(function() {
          var editAppLink = jQuery('.app-details .dropdown-menu li a[href*="form.edit"]');
          if(editAppLink.size() > 0) {
              var editAppHref = editAppLink.attr('href'), ret = editAppHref.substr(editAppHref.indexOf('return'));
              jQuery('#edit_products').attr('href', editAppHref + '&' + ret + '#2');
          }
		  $('#prod-item-title a').attr('href','javascript:void(0)');
        });
      if(!$.trim($(".dropdown-menu").text()))
      {
        $("[data-toggle='dropdown']").remove();
      }

      <?php 

      $belong_to_organization = DeveloperPortalApi::getUserOrganization();
      $app_organization = DeveloperPortalApi::getOranizationIdOfApplication($this->item->id);
      if(!(in_array(7, $user->getAuthorisedGroups()) || in_array(8, $user->getAuthorisedGroups())) && !in_array($app_organization, $belong_to_organization)):
      ?>
        $("[data-toggle='dropdown']").remove();
      <?php endif; ?>

    }(jQuery));

</script>

<?php
function group_start($data, $label, $name)
{
	static $start = false;
	switch ($data->tmpl_params['record']->get('tmpl_params.item_grouping_type', 0))
	{
		//tab
		case 1:
			if(!$start)
			{
				echo '<div class="tab-content" id="tabs-box">';
				$start = TRUE;
			}
			echo '<div class="tab-pane" id="'.$name.'">';
			break;
		//slider
		case 2:
			if(!$start)
			{
				echo '<div class="accordion" id="accordion2">';
				$start = TRUE;
			}
			echo '<div class="accordion-group">
				<div class="accordion-heading">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#'.$name.'">
					     '.$label.'
					</a>
				</div>
				<div id="'.$name.'" class="accordion-body collapse">
					<div class="accordion-inner">';
			break;
		// fieldset
		case 3:
			echo "<legend>{$label}</legend>";
		break;
	}
}

function group_end($data)
{
	switch ($data->tmpl_params['record']->get('tmpl_params.item_grouping_type', 0))
	{
		case 1:
			echo '</div>';
		break;
		case 2:
			echo '</div></div></div>';
		break;
	}
}

function total_end($data)
{
	switch ($data->tmpl_params['record']->get('tmpl_params.item_grouping_type', 0))
	{
		//tab
		case 1:
			echo '</div>';
		break;
		case 2:
			echo '</div>';
		break;
	}
}

function makeArrayString($array) {
    $rv = '';
    foreach($array as $index => $item) {
        if($index != 0) {
            $rv .= ", ";
        }
        $rv .= $item;
    }
    return "[" . $rv . "]";
}
