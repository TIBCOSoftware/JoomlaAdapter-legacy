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

$item = $this->item;
$params = $this->tmpl_params['record'];
$icons = array();
$category = array();
$author = array();
$details = array();
$started = FALSE;
$i = $o = 0;
$tasks_to_hide = array();
$org_admin_group_id = TibcoTibco::getGroupByOrgAndType($this->item->id, DeveloperPortalApi::USER_TYPE_MANAGER);
$auth_group_ids = $this->user->getAuthorisedGroups();
$path = JRequest::getURI();
$current_user_org_id = TibcoTibco::getCurrentUserOrgId();
if(strpos($path,'userprofile') || strpos($path, 'dashboard')){
  if(!($current_user_org_id == $this->item->id || in_array(7, $auth_group_ids) || in_array(8, $auth_group_ids))){
  	JFactory::getApplication()->enqueueMessage(JText::_('USERPROFILE_CUSTOM_ACCESS_DENIED_ERROR'), 'error');
   return;
  }
}
if(!in_array(8, $auth_group_ids)) {
  $tasks_to_hide = array(DeveloperPortalApi::TASK_ARCHIVE);
}
$membersValue = (object)null;
$contactInfo = (object)null;
$subscriptionsValue = (object)null;
$appsValue = (object)null;
$threshold = (object)null;
$thresholdValue = '99';
if(isset($this->item->fields_by_groups[null])){
	foreach ($this->item->fields_by_groups[null] as $field_id => $field){
		$started = true;
		if ($field->id == 56) {
			$membersValue = $field;
		}else if ($field->id == 20) {
			$contactValue = $field->value;
		}else if ($field->id == 48) {
			$contactInfo = $field;
		}else if ($field->id == 74) {
			$subscriptionsValue = $field;
		}else if ($field->id == 61) {
			$appsValue = $field;
		}else if ($field->id ==130){
			$threshold = $field;
			if (!empty($threshold->value)) {
				$thresholdValue = $threshold->value;
			}
		}
	}
}
?>
<script type="text/javascript">
var hasJoomlaRoot=false;
</script>
<?php $hasJoomlaRoot= JUri::base(true);?>
<?php if($hasJoomlaRoot):?>
<script type="text/javascript">
var hasJoomlaRoot='<?php echo $hasJoomlaRoot;?>';
</script>
<?php endif;?>
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

<article class="<?php echo $this->appParams->get('pageclass_sfx')?><?php if($item->featured) echo ' article-featured' ?>">
	<?php if($params->get('tmpl_core.item_title')):?>
		<?php if($this->type->params->get('properties.item_title')):?>
			<div class="page-header">
				<<?php echo $params->get('tmpl_params.title_tag', 'h1')?>>
					<?php echo $item->title?>
					<?php echo CEventsHelper::showNum('record', $item->id);?>
				</<?php echo $params->get('tmpl_params.title_tag', 'h1')?>>
			</div>
		<?php endif;?>
	<?php endif;?>
	<div class="clearfix"></div>

<style>
.dl-horizontal dd {
	margin-bottom: 10px;
}

.tag_list li {
	display: block;
	float:left;
	list-style-type: none;
	margin-right: 5px;
}
.tag_list li#tag-first {
	line-height: 30px;
}
.tag_list li * {
	margin: 0px;
	padding: 0px;
	line-height: 30px;
}

.tag_list li a {
	color: #000;
	text-decoration: none;
	border: 1px solid #445D83;
	background-color: #F2F8FF;
	border-radius: 8px;
	padding: 5px 10px 5px 10px;
}

.tag_list li a:HOVER {
	color: #000;
	text-decoration: underline;
}

.line-brk {
	margin-left: 0px !important;
}

.width-660 {
  width: 660px;
}

#app-list .org-container{
	margin-top:18px;
}

#app-list .alert-container{
	width:264px;
	float:right;
}

.org-container .left-top .inline-doc-content{
	min-height:150px;
}

.inline-doc-content dl{
	padding:0px 30px;
	margin-bottom:30px;
	height:429px;
	overflow:auto;
}

.inline-doc-content dl dt{
	margin-top:20px;
	padding-right:10px;
	overflow:hidden;
	font-size: 12px;
	font-weight: bold;
	color: #000;
}

.inline-doc-content dl dd{
	margin-top:10px;
	margin-left:0px;
	text-indent:0px;
	font-size: 14px;
	line-height: 18px;
	color: #666;
}

.inline-doc-content dl dd.time{
	font-size: 8px;
	color: #888;
	text-transform:uppercase;
}

td.time span{
	display:inline-block;
	width:40px;
}

td.o-plan-detail span{
	float:left;
	width: 48px;
	height: 27px;
	line-height:27px;
	overflow:hidden;
	margin-right:6px;
	margin-top:5px;
	color:#fff;
	text-transform:uppercase;
	text-align:center;
}

td.o-plan-detail span.line0{
	background-color:#aba000;
}

td.o-plan-detail span.line1{
	background-color:#a3620a;
}

td.o-plan-detail span.line2{
	background-color:#898989;
}

td.o-plan-detail span.line3{
	background-color:#448ccb;
}

.inline-doc{
	padding:0;
}

.inline-doc-content{
	border:0px;
  padding:4%;
}

.inline-doc h2{
	cursor:pointer;
	margin:0px;
	padding:20px 0px;
	background-color: #f6f6f6;
	font-weight:normal;
}

.inline-doc h2 span{
	display:inline-block;
	height:100%;
	padding-left:30px;
	font-size: 25px;
	line-height:25px;
	color: #333;
}

.org-detail-table{
	width:92%;
	border-spacing:20px;
}

.org-detail-table tbody tr{
	margin:15px 0;
}

.org-detail-table tbody tr td{
	font-size: 11px;
	color: #2d2d2d;
	line-height: 20px;
	vertical-align:top;
	padding:5px 0;
}

.org-detail-table tbody tr td:last-child{
	line-height: 15px;
	width:27%;
}

.o-useage{
	width:108px;
	height:12px;
	background-color: #e1e1e1;
	border:1px solid #cbced4;
	float:left;
	margin-top:2px;
	margin-bottom:4px;
}

.o-useage span{
	display:inline-block;
	height:12px;
	line-height:12px;
}

.member-details{
	margin-right:2%;
	width:68%;
	float:left;
}

.contact-info{
	margin-left:0px;
	margin-right:0px;
	width:23%;
	float:left;
}

.contact-info tbody tr td:last-child{
	background-color:#ebebeb;
	line-height:26px;
	padding:0px 5px;
}

th {
	text-align:left;
}

th span{
	width:92%;
	font-size: 10px;
	color: #474747;
	font-weight: bold;
	border-bottom:1px solid #d2d5db;
	display:inline-block;
}

.mem-email{
	max-width:200px;
}

.mem-email a{
        float: left;
}
.mem-email a.btn{
        float: right;margin-right:8%;
}
.org-detail-table.member-details tbody tr td:last-child{
	width: 12%;
}
.mem-email p{
        margin: 0;
}
.menu-bar{
	width:100%;
	height:45px;
	background-color:#eee;
	box-shadow:inset 0 0 20px rgba(0,0,0,.1);
}

button.resync-org{
	/*height:34px;*/
}

.ctrl-org{
  margin: 12px 10px 0px 0px;
}

.menu-bar button{
	margin-top:10px;
	margin-left:10px;
}
<?php echo $params->get('tmpl_params.css');?>
</style>
<?php if(($current_user_org_id == $this->item->id && in_array($org_admin_group_id, $auth_group_ids)) || in_array(7, $auth_group_ids) || in_array(8, $auth_group_ids)): ?>
<div class="menu-bar">
	<?php if(!$this->print):?>
		<?php if($this->user->get('isRoot')):?>
    <button class="btn btn-resync resync-org pull-left" onclick="DeveloperPortal.resync('<?php echo $this->item->id; ?>', 'Organization');"><?php echo JText::_('RESYNC');?></button>
		<div class="hidden"><div id="system-message" class="alert alert-info"><a class="close" data-dismiss="alert">&#215;</a><h4 class="alert-heading"><?php echo JText::_('INFO');?></h4><div><p><?php echo JText::_('RESYNC_COMPLETE');?></p></div></div></div>
    <?php endif;?>
	<?php else:?>
		<div class="pull-right controls">
			<a href="#" class="btn btn-mini" rel="tooltip" data-original-title="<?php echo JText::_('CPRINT');?>" onclick="window.print();return false;"><?php echo HTMLFormatHelper::icon('printer.png');  ?></a>
		</div>
	<?php endif;?>
<div class="pull-right controls ctrl-org">
	<div class="btn-group">
		<?php if($params->get('tmpl_core.item_print')):?>
			<a class="btn btn-mini" rel="tooltip" data-original-title="<?php echo JText::_('CPRINT');?>" onclick="window.open('<?php echo JRoute::_($this->item->url.'&tmpl=component&print=1');?>','win2','status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no'); return false;">
				<?php echo HTMLFormatHelper::icon('printer.png');  ?></a>
		<?php endif;?>

		<?php if($this->user->get('id')):?>
			<?php echo HTMLFormatHelper::bookmark($item, $this->type, $params);?>
			<?php echo HTMLFormatHelper::follow($item, $this->section);?>
			<?php echo HTMLFormatHelper::repost($item, $this->section);?>
			<?php if($item->controls && (in_array(8, $auth_group_ids) || in_array($org_admin_group_id, $auth_group_ids))):?>
    <!--<button data-toggle="dropdown" class="btn"><?php echo JText::_("ACTION_BUTTON_TEXT"); ?></button>-->
	<a href="#" data-toggle="dropdown" class="dropdown-toggle btn-mini">
		<?php echo HTMLFormatHelper::icon('gear.png');  ?></a>
				<ul class="dropdown-menu">
					<?php echo DeveloperPortalApi::list_controls($item->controls, $tasks_to_hide, $this->item->id, $this->item->type_id);?>
				</ul>
			<?php endif;?>
		<?php endif;?>
	</div>
</div>
		</div>
	<?php endif;?>
<div id="app-list">
	<div class="org-container alert-container">
		<div class="inline-doc active">
			<h2>
				<span><?php echo JText::_('DASHBOARD_ALERTS');?></span>
			</h2>
			<div class="inline-doc-content">
				<dl id="alert-msg" orgID="<?php echo $this->item->id; ?>">
					<dt><?php echo JText::_('DASHBOARD_PLACEHOLDER_ALERT');?></dt>
					<dd class="time"><?php echo date('Y-m-d H:i:s'); ?></dd>
				</dl>
			</div>
		</div>
	</div>

	<div class="org-container pull-left width-660">
		<div class="inline-doc active left-top">
			<h2>
				<?php if (isset($appsValue->label)&&isset($appsValue->content['ids'])): ?>
					<span><?php echo $appsValue->label.' ('.count($appsValue->content['ids']).')'; ?></span>
				<?php else: ?>
					<span>Applications</span>
				<?php endif ?>
			</h2>
			<div class="inline-doc-content">
				<table class="org-detail-table">
					<thead>
						<tr>
							<th><span><?php echo JText::_('DASHBOARD_APPLICATIONS');?></span></th>
							<th><span><?php echo JText::_('DASHBOARD_PRODUCT');?></span></th>
							<th><span><?php echo JText::_('DASHBOARD_PLAN');?></span></th>
							<th style="width:140px;"><span><?php echo JText::_('DASHBOARD_USEAGE');?></span></th>
						</tr>
					</thead>
					<tbody>
						<?php if (isset($appsValue->content['list'])): ?>
							<?php foreach ($appsValue->content['list'] as $key => $value): ?>
							<tr style="<?php echo array_search($key, array_keys($appsValue->content['list']))%2==0?'':'background-color:#f6f6f6;'; ?>">
								<td class="app-name" appID="<?php echo $value->id; ?>"><a href="<?php echo JRoute::_('index.php?option=com_cobalt&view=record&id='.$value->id);?>"><?php echo $value->title; ?></a></td>
								<td><?php echo JText::_('DASHBOARD_PLACEHOLDER_LOADING');?></td>
								<td><?php echo JText::_('DASHBOARD_PLACEHOLDER_LOADING');?></td>
								<td></td>
							</tr>
							<?php endforeach; ?>
						<?php endif ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="org-container pull-left width-660">
		<div class="inline-doc active left-top">
			<h2>
				<span><?php echo $subscriptionsValue->label?$subscriptionsValue->label:JFactory::getApplication()->getMenu()->getItem(114)->title; ?></span>
			</h2>
			<div class="inline-doc-content">
				<table class="org-detail-table">
					<thead>
						<tr>
							<th><span><?php echo JText::_('DASHBOARD_SUBSCRIPTION_PRODUCT');?></span></th>
							<th><span><?php echo JText::_('DASHBOARD_SUBSCRIPTION_PERIOD');?></span></th>
							<th style="width:23%;"><span><?php echo JText::_('DASHBOARD_PLAN');?></span></th>
							<th><span><?php echo JText::_('DASHBOARD_USEAGE');?></span></th>
						</tr>
					</thead>
					<tbody>
						<?php if (isset($subscriptionsValue->content['list'])): ?>
							<?php foreach ($subscriptionsValue->content['list'] as $key => $value): ?>
								<tr style="<?php echo array_search($key, array_keys($subscriptionsValue->content['list']))%2==0?'':'background-color:#f6f6f6;'; ?>">
									<td style="max-width:150px;"><a href="<?php echo JRoute::_('index.php?option=com_cobalt&view=record&id='.$value->id);?>"><?php echo $value->title; ?></a></td>
									<td class="time"><?php echo '<span>From</span>'.$value->fields[71][0].'<br/>'.'<span>To</span>'.$value->fields[72][0]; ?></td>
									<td class="o-plan-detail" planID="<?php echo $value->fields[69]; ?>" subID="<?php echo $value->id; ?>">Loading...</td>
									<td><div class="o-useage" subID="<?php echo $value->id; ?>">Loading...</div></td>
								</tr>
							<?php endforeach; ?>
						<?php endif ?>
					</tbody>
				</table>
			</div>
		</div>
    <?php echo $subscriptionsValue->result; ?>
	</div>
	<div class="clearfix"></div>
	<div class="org-container">
		<div class="inline-doc">
			<h2>
				<span><?php echo JText::_('DASHBOARD_STATISTICS');?></span>
			</h2>
			<div class="inline-doc-content">
				<div class="org-detail-table">
					<div id="analytics" style="display:block;">
					<div id="analytics-control" style="display:inline-block; width:100%;">
					<button id="btnShowHideDashboard" title="Toggle Dashboard Display" class="btn btn-small pull-left" style="margin: 1px 5px;">Show dashboard</button>
					<?php if(in_array(7, JFactory::getUser()->groups) || in_array(8, JFactory::getUser()->groups)): //If user is an Administrator or a SuperUser... ?>
					<select id="selectDashboardMode" title="Select Dashboard Type to Display" class="pull-left" style="margin-left:585px;margin-top:-85px; padding: 0; width: 125px; height: 24px;position:absolute;">
					<option value="/ASG/Host">Host</option>
					<option value="/ASG/Partner">Partner</option>
					</select>
					<?php endif;?>
					<div id="btnReloadDashboard" title="Refresh Dashboard" class="icon-refresh pull-left" style="visibility:hidden; margin: 5px; cursor: pointer; display:inline-block"></div>
					</div>
					<div id="analytics-content" style="display: none; clear: both; height:600px;"></div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="org-container">
		<div class="inline-doc active">
			<h2>
				<span><?php echo $membersValue->label; ?></span>
			</h2>
			<div class="inline-doc-content">
				<table class="org-detail-table member-details">
					<thead>
						<tr>
							<th><span><?php echo JText::_('DASHBOARD_NAME');?></span></th>
							<th><span><?php echo JText::_('DASHBOARD_EMAIL');?></span></th>
							<th><span><?php echo JText::_('DASHBOARD_ROLE');?></span></th>
						</tr>
					</thead>
					<tbody>
						<?php if (isset($membersValue->content['list'])): ?>
							<?php foreach ($membersValue->content['list'] as $key => $value): ?>
							<tr>
								<td><a href="<?php echo JRoute::_('index.php?option=com_cobalt&view=record&id=' . $value->id);?>"><?php echo $value->title; ?></a></td>
								<td class="mem-email" id="mem<?php echo $key; ?>"><?php echo $value->fields[102]; ?></td>
								<td><?php  if (is_array($value->fields[88])){
									echo $value->fields[88][0];
								}else {
									echo $value->fields[88];
								}  ?></td>
							</tr>
							<?php endforeach; ?>
						<?php endif ?>
					</tbody>
				</table>
				<table class="org-detail-table contact-info">
					<thead>
						<tr>
							<th><span style="width:100%;"><?php echo JText::_('DASHBOARD_CONTACT');?></span></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><?php echo $contactInfo->content['list'][0]->fields[45].' '.$contactInfo->content['list'][0]->fields[46].'<br/>'.$contactInfo->content['list'][0]->fields[102]; ?><br/><?php echo $contactValue['address']['address1'].' '.$contactValue['address']['zip'].'<br/>'.$contactValue['address']['city'].' '.$contactValue['address']['state'].'<br/>'.($contactValue['contacts']['tel']?"Phone:":"").$contactValue['contacts']['tel']; ?></td>
						</tr>
					</tbody>
				</table>
				<div class="clearfix"></div>
			</div>
		</div>
    <?php echo $membersValue->result; ?>
	</div>
	<div class="clearfix"></div>
</div>

<script>
	(function ($) {
		$("table.table-striped:last").find("tbody tr td:last-child").each(function(i){
			var cloneInfo = $(this).clone();
			cloneInfo.find("script").remove();
			$("td#mem"+i).html(cloneInfo.html());
		});
		$("table.table-striped").remove();
	})(jQuery);
</script>

<!-- <?php if(in_array($params->get('tmpl_params.item_grouping_type', 0), array(1)) && count($this->item->fields_by_groups)):?>
<div class="clearfix"></div>
<div class="tabbable <?php echo $params->get('tmpl_params.tabs_position');  ?>">
	<ul class="nav <?php echo $params->get('tmpl_params.tabs_style', 'nav-tabs');  ?>" id="tabs-list">
		<?php if(isset($this->item->fields_by_groups)):?>
			<?php foreach ($this->item->fields_by_groups as $group_id => $fields) :?>
				<li><a href="#tab-<?php echo $o++?>" data-toggle="tab"> <?php echo HTMLFormatHelper::icon($item->field_groups[$group_id]['icon'])?> <?php echo JText::_($group_id)?></a></li>
			<?php endforeach;?>
		<?php endif;?>
	</ul>
<?php endif;?> -->

	<?php if($started):?>
		<?php total_end($this);?>
	<?php endif;?>
	<?php if(in_array($params->get('tmpl_params.item_grouping_type', 0), array(1))  && count($this->item->fields_by_groups)):?>
		</div>
		<div class="clearfix"></div>
		<br />
	<?php endif;?>

	<?php echo $this->loadTemplate('tags');?>

	<?php if($category || $author || $details || $params->get('tmpl_core.item_rating')): ?>
		<div class="well article-info">
			<div class="row-fluid">
				<?php if($params->get('tmpl_core.item_rating')):?>
					<div class="span2">
						<?php echo $item->rating;?>
					</div>
				<?php endif;?>
				<div class="span<?php echo ($params->get('tmpl_core.item_rating') ? 8 : 10);?>">
					<small>
						<dl class="dl-horizontal user-info">
							<?php if($category):?>
								<?php echo implode(' ', $category);?>
							<?php endif;?>
							<?php if($author):?>
								<dt><?php echo JText::_('Posted');?></dt>
								<dd>
									<?php echo implode(', ', $author);?>
								</dd>
							<?php endif;?>
							<?php if($details):?>
								<dt>Info</dt>
								<dd class="hits">
									<?php echo implode(', ', $details);?>
								</dd>
							<?php endif;?>
						</dl>
					</small>
				</div>
				<?php if($params->get('tmpl_core.item_author_avatar')):?>
					<div class="span2 avatar">
						<img src="<?php echo CCommunityHelper::getAvatar($item->user_id, $params->get('tmpl_core.item_author_avatar_width', 40), $params->get('tmpl_core.item_author_avatar_height', 40));?>" />
					</div>
				<?php endif;?>
			</div>
		</div>
	<?php endif;?>
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

<?php
	$comEmail = JComponentHelper::getComponent('com_emails');
	$spotfire_domain = $comEmail->params->get('spotfire_domain');
	if(!$spotfire_domain){
		$spotfire_domain = '';
	}
	$spotfire_url = rtrim(JURI::root(), "/");
	$spotfire_app_url = $spotfire_url . "/Analytics/";
	$spotfire_script_url = $spotfire_app_url . "GetJavaScriptApi.ashx?Version=3.1";
?>

<script type="text/javascript" src="<?php echo $spotfire_script_url ?>"></script>
<script type="text/javascript">
	if(typeof window.console !== 'object'){
		window.console = {
			log: function(message){alert(message)},
			error: function(message){alert(message)}
		};
	}

	function analyticsErrorHandler(errorCode, description){
		console.error("Error loading analtyics: code(" + errorCode + ")\n\t" + description);
	}

	jQuery(document).ready(function(){
		var appStarted = false,
			analyticsContent = jQuery('#analytics-content'),
			reloadBtn = jQuery('#btnReloadDashboard'),
			showHideBtn = jQuery('#btnShowHideDashboard'),
			dbModeSelect = jQuery('#selectDashboardMode');

		function buildAnalyticsDashboard(){
			var customization = new spotfire.webPlayer.Customization();
			customization.showClose = false;
			var app = new spotfire.webPlayer.Application('<?php echo $spotfire_app_url; ?>', customization);
			app.onError(analyticsErrorHandler);
			return app;
		}

		function loadAnalyticsDashboard(app){
			analyticsContent.css({
				'background-color':'#EFEFEF',
				'left':'0',
				'position':'relative',
				'height':'600',
				'width':'%100',
				'margin': '0'
			});
			app.open(dbModeSelect.length ? dbModeSelect.val():'/ASG/Partner', 'analytics-content', 'partner="anon";');
			appStarted = true;
		}

		function clearAnalyticsDashboard(keepHidden){
			appStarted = false;
			analyticsContent.remove();
			analyticsContent = jQuery('<div/>', { id: 'analytics-content', style: (keepHidden ? 'display:none':'') });
			jQuery('#analytics').append(analyticsContent);
		}

		function reloadAnalyticsDashboard(){
			clearAnalyticsDashboard();
			loadAnalyticsDashboard(buildAnalyticsDashboard());
		}

		//set the document domain according to the configuration setting to enable cross-site, same domain scripting
		try{
			document.domain = '<?php echo $spotfire_domain;?>';
		}
		catch(err){
			analyticsErrorHandler(0, 'Failed setting of analytics domain to "<?php echo $spotfire_domain;?>". Please check your settings and try again. [' + err + ']');
			return;
		}

		//ensure analytics api script was successfully fetched and the expected api element is available
		if(typeof spotfire === 'undefined' || !spotfire){
			analyticsErrorHandler(1, 'Spotfire JavaScript API failed to load.');
			return;
		};

		//show analytics section since the script was successfully fetched
		jQuery('#analytics').show();

		//set the cookie value for the analytics authentication proxy
		document.cookie = 'session-id=<?php echo JSession::getInstance(null,null)->getId(); ?>; path=/';

		reloadBtn.bind('click', reloadAnalyticsDashboard);
		showHideBtn.bind('click', function(){
			if(analyticsContent.is(':hidden')){
				if(!appStarted){
					loadAnalyticsDashboard(buildAnalyticsDashboard());
				}
				analyticsContent.slideDown('slow');
				reloadBtn.css('visibility','visible');
				showHideBtn.text('Hide dashboard');
			}
			else{
				analyticsContent.slideUp('slow');
				reloadBtn.css('visibility','hidden');
				showHideBtn.text('Show dashboard');
			}
		});
		if(dbModeSelect.length){ //only add change handler if the element exists
			dbModeSelect.bind('change', function(){
				if(analyticsContent.is(':hidden')){
					clearAnalyticsDashboard(true);
					appStarted = false;
				}
				else{
					reloadAnalyticsDashboard();
				}
			});
		}
	});

    (function ($) {
        $('.inline-doc h2').click(function (e) {
            if($(this).parent().hasClass("active")) {
                $(this).parent().removeClass("active");
            }else {
                $(this).parent().addClass("active");
            }
        });

		loadProducts($.makeArray($('.app-name')));

		function loadProducts(apps){
			if (apps.length>0) {
				app_ids = [];
				$(apps).each(function(i){
					app_ids.push($(this).attr('appID'));
				});

			  	$.post(
	GLOBAL_CONTEXT_PATH+"index.php?option=com_cobalt&task=ajaxMore.subscriptionsInApp",
				  {'app_ids':app_ids},
				  function(data){
					$(data.result).each(function(row){
						node = $(apps[row]);
					  	products = '';
						plans = '';
						$(this.products).each(function(i){
							products += $(this).attr('productName')+'<br/>';
						});

						$(this.plans).each(function(i){
							jsonObj = JSON.parse($(this).attr('planDetail'));
							plans += jsonObj[80]+'<?php echo JText::_('DASHBOARD_PLAN_UNIT_DAY'); ?><br/>';
						});

						$(this.usage).each(function(i){
							node.next("td").next("td").next("td").append('<div class="o-useage rendered"></div>');
							usageNode = $('.rendered:last-child');
							usageValue = $(this).attr('pct');
							renderUseage(usageNode, usageValue?usageValue:0);
						});

						node.next("td").html(products);
						node.next("td").next("td").html(plans);
					});
					loadPlanDetail($.makeArray($('td.o-plan-detail')));
				  },
			      'json'
			  	).fail(function(){
					loadPlanDetail($.makeArray($('td.o-plan-detail')));
			  	});
			}else {
				loadPlanDetail($.makeArray($('td.o-plan-detail')));
			}
		}

		function loadPlanDetail(subs){
			if (subs.length>0) {
				sub_ids = [];
				plan_ids = [];
				postData = {};
				$(subs).each(function(i){
					sub_ids.push($(this).attr('subID'));
					plan_ids.push($(this).attr('planID'));
				});
				postData.sub_ids = sub_ids;
				if (plan_ids.length>0) {
					postData.plan_ids = plan_ids;
				}
			  	$.post(
	GLOBAL_CONTEXT_PATH+"index.php?option=com_cobalt&task=ajaxMore.subscriptionsInApp",
				  postData,
				  function(data){
					  $(data.result).each(function(){
						$(this.usage).each(function(row){
							node = $(subs[row]);
							usageValue = $(this).attr('pct');
							renderUseage(node.next("td").find("div"), usageValue?usageValue:0);
						});

						$(this.plans).each(function(row){
							if(plan_ids && plan_ids.length>1){
								for(i=0;i<plan_ids.length;i++){
									jsonObj = JSON.parse(this.planDetail);
									plan = jsonObj[80]+'<?php echo JText::_('DASHBOARD_PLAN_UNIT_DAY'); ?><br/>'+jsonObj[79]+'<?php echo JText::_('DASHBOARD_PLAN_UNIT_SECOND'); ?>';
									if(plan_ids[i]==this.id){
										node = $(subs[i]);
									node.html('<span class="line'+(i%4)+'">'+this.title+'</span>'+plan);
									}
									}
						}else {
							jsonObj = JSON.parse(this.planDetail);
							plan = jsonObj[80]+'<?php echo JText::_('DASHBOARD_PLAN_UNIT_DAY'); ?><br/>'+jsonObj[79]+'<?php echo JText::_('DASHBOARD_PLAN_UNIT_SECOND'); ?>';
							node = $(subs[row]);
							node.html('<span class="line'+(row%4)+'">'+this.title+'</span>'+plan);
							}
							});

					  });
					  loadAlerts($('dl#alert-msg'));
				  },
			      'json'
			  	).fail(function(){
					loadAlerts($('dl#alert-msg'));
			  	});
			}else {
				loadAlerts($('dl#alert-msg'));
			}
		}

		function loadAlerts(alertNode){
			org_id = alertNode.attr('orgID');
		  	$.post(
GLOBAL_CONTEXT_PATH+"index.php?option=com_cobalt&task=ajaxMore.alertMessages",
			  {'org_id':org_id},
			  function(data){
				  elements = '';
				  $(data.result).each(function(){
					//  elements += '<dt class="'+renderAlertType(this.log_type)+'">'+this.entity_type+'</dt><dd>'+ (this.event||"")+': '+ (this.event_status||'')+'</dd><dd class="time">'+this.create_time+'</dd>';
					  elements += '<dt class="'+renderAlertType(this.log_type, this.event_status)+'">'+this.entity_type+' : '+ (this.event||"")+'</dt><dd>'+ (this.summary||"")+'</dd><dd class="time">'+this.create_time+'</dd>';
					  });
				  if (elements.length>0) {
				  	alertNode.html(elements);
				  }
			  },
		      'json'
		  	).fail(function(){
				//void
		  	});
		}

		function renderUseage(node, percent){
			var percentage=Math.floor(percent*100)/100;
			var threshold= '<?php echo $thresholdValue; ?>';
			barWidth = node.width()*percentage/100;
			if (percentage<threshold ) {
				bgColor = '6c3';
			}else if (percentage>=threshold && (barWidth < node.width())) {
				bgColor = 'ff0';
			}else{
				bgColor = 'f66';
			}
			node.html('<span style="width:'+barWidth+'px;background:#'+bgColor+';"></span>');
			node.after('<div style="float:left;height:14px;margin-top:2px;margin-left:2px;">'+percentage+'%'+'</div><div class="clearfix"></div>');
		}

		function renderAlertType(typeString, statusString){
			var status=statusString.toLowerCase();
			var matchString = typeString.toLowerCase();
			var returnType = "";
			if (status==='error') {
				returnType = 'error';
			}else if (status==='success' || status==='complete') {
				returnType = 'normal';
			}else if (matchString==='portalalert') {
				returnType = 'alert1';
			}else if(status==='partially completed'){
				returnType = 'alert2';
			}

			return returnType;
		}
		

    })(jQuery);
</script>
<script>
  //reload send mail iframe
  jQuery("[id^='emailmodal']").on('hide', function() {
    var ifr = jQuery(this).find('iframe');
    ifr.attr('src', ifr.attr('src'));
  });
  
  <?php if (strrpos(JURI::current(),"/".JFactory::getApplication()->getMenu()->getItem(140)->alias."/")): ?>
  	jQuery("h1#banner-title-heading").html('<?php echo JFactory::getApplication()->getMenu()->getItem(140)->title; ?>');
  <?php endif ?>
</script>

<?php if(strpos($path,'userprofile') || strpos($path,'dashboard')):?>
<script type="text/javascript">
(function($){
	$(function(){
			var org_links_seletor,search_selector, mem_links;
			if(hasJoomlaRoot){
				 org_links_seletor="table.org-detail-table.member-details td>a[href^='"+hasJoomlaRoot+"\/index.php\/organizations\/']";
				 search_selector=new RegExp('^('+hasJoomlaRoot+'\/?index\.php\/)(organizations)(\/.*)$');
				  mem_links = $("a[href^='"+hasJoomlaRoot+"\/index.php\/organizations\/submit\/']");
				  mem_search_selector=new RegExp("^("+hasJoomlaRoot+"\/?index\.php\/)(organizations)(\/.*)$");
			}else{
				 org_links_seletor="table.org-detail-table.member-details td>a[href^='\/index.php\/organizations\/']";
				 search_selector=new RegExp("^(\/?index\.php\/)(organizations)(\/.*)$");
				 mem_links = $("a[href^='\/index.php\/organizations\/submit\/']");
				 mem_search_selector=new RegExp("^(\/?index\.php\/)(organizations)(\/.*)$");
				} 
			var org_links = $(org_links_seletor);
			org_links.each(function(i,ele){
				
				var new_link = $(ele).attr("href").replace(search_selector,function(m,p1,p2,p3){
						return p1+"dashboard"+p3;});
				$(ele).attr("href",new_link);						
			});
		
			mem_links.each(function(i,ele){
				var new_mem_link = $(ele).attr("href").replace(mem_search_selector,function(m,p1,p2,p3){
				        							return p1+"dashboard"+p3;
									});
				$(ele).attr("href",new_mem_link);						
			});
				
  });})(jQuery);
</script>
<?php endif;?>
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
