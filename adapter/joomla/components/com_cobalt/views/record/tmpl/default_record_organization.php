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

$path = JRequest::getURI();
/**
 * the comment line is for disabled deleting button
 */
// $canDelRecord = TRUE;
// foreach($this->items as $item){
// 	foreach($item->fields_by_id  as $field_id => $field){
// 		echo $field->type ."<br/>";
// 		if($field->type == 'child' && !empty($field->content['ids'])){
// 			echo "label:".$field->label;
// 			$canDelRecord = FALSE;
// 		}
// 	}
// }

// if(!$canDelRecord){
// 	array_splice($item->controls,5,1);
// }
?>

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

<article class="<?php echo $this->appParams->get('pageclass_sfx')?><?php if($item->featured) echo ' article-featured' ?>">
	<?php if(!$this->print):?>
		<div class="pull-right controls">
			<div class="btn-group">
				<?php if($params->get('tmpl_core.item_print')):?>
					<a class="btn btn-mini" rel="tooltip" data-original-title="<?php echo JText::_('CPRINT');?>" onclick="window.open('<?php echo JRoute::_($this->item->url.'&tmpl=component&print=1');?>','win2','status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no'); return false;">
						<?php echo HTMLFormatHelper::icon('printer.png');  ?></a>
				<?php endif;?>

				<?php if($this->user->get('id')):?>
					<?php echo HTMLFormatHelper::bookmark($item, $this->type, $params);?>
					<?php echo HTMLFormatHelper::follow($item, $this->section);?>
					<?php echo HTMLFormatHelper::repost($item, $this->section);?>
					<?php if($item->controls):?>
						<a href="#" data-toggle="dropdown" class="dropdown-toggle btn btn-mini">
							<?php echo HTMLFormatHelper::icon('gear.png');  ?></a>
						<ul class="dropdown-menu">
							<?php echo DeveloperPortalApi::list_controls($item->controls, $tasks_to_hide, $this->item->id, $this->item->type_id);?>
						</ul>
					<?php endif;?>
				<?php endif;?>
			</div>
		</div>
	<?php else:?>
		<div class="pull-right controls">
			<a href="#" class="btn btn-mini" rel="tooltip" data-original-title="<?php echo JText::_('CPRINT');?>" onclick="window.print();return false;"><?php echo HTMLFormatHelper::icon('printer.png');  ?></a>
		</div>
	<?php endif;?>
    
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
	
	<div id="analytics" style="display:none;">
		<div id="analytics-control" style="display:inline-block; width:100%;">
			<button id="btnShowHideDashboard" title="Toggle Dashboard Display" class="btn btn-small pull-left" style="margin: 1px 5px">Show dashboard</button>
			<?php if(in_array(7, JFactory::getUser()->groups) || in_array(8, JFactory::getUser()->groups)): //If user is an Administrator or a SuperUser... ?>
				<select id="selectDashboardMode" title="Select Dashboard Type to Display" class="pull-left" style="margin: 0; padding: 0; width: 125px; height: 24px;">
					<option value="/ASG/Host">Host</option>
					<option value="/ASG/Partner">Partner</option>
				</select>
			<?php endif;?>
			<div id="btnReloadDashboard" title="Refresh Dashboard" class="icon-refresh pull-left" style="visibility:hidden; margin: 5px; cursor: pointer; display:inline-block"></div>
		</div>
		<div id="analytics-content" style="display: none; clear: both; height:600px;"></div>
	</div>

	<?php if(isset($this->item->fields_by_groups[null])):?>
		<dl class="dl-horizontal fields-list">
			<?php foreach ($this->item->fields_by_groups[null] as $field_id => $field):?>
				<dt id="<?php echo 'dt-'.$field_id; ?>" class="<?php echo $field->fieldclass;?>">
					<?php if($field->params->get('core.show_lable') > 1):?>
						<label id="<?php echo $field->id;?>-lbl">
							<?php echo $field->label; ?>
							<?php if($field->params->get('core.icon')):?>
								<?php echo HTMLFormatHelper::icon($field->params->get('core.icon'));  ?>
							<?php endif;?>
						</label>
						<?php if($field->params->get('core.label_break') > 1):?>
						<?php endif;?>
					<?php endif;?>
				</dt>
				<dd id="<?php echo 'dd-'.$field_id; ?>" class="<?php echo $field->fieldclass;?><?php echo ($field->params->get('core.label_break') > 1 ? ' line-brk' : NULL) ?>">
					<?php echo $field->result; ?>
				</dd>
			<?php endforeach;?>
		</dl>
		<?php unset($this->item->fields_by_groups[null]);?>
	<?php endif;?>

	<?php if(in_array($params->get('tmpl_params.item_grouping_type', 0), array(1)) && count($this->item->fields_by_groups)):?>
	<div class="clearfix"></div>
	<div class="tabbable <?php echo $params->get('tmpl_params.tabs_position');  ?>">
		<ul class="nav <?php echo $params->get('tmpl_params.tabs_style', 'nav-tabs');  ?>" id="tabs-list">
			<?php if(isset($this->item->fields_by_groups)):?>
				<?php foreach ($this->item->fields_by_groups as $group_id => $fields) :?>
					<li><a href="#tab-<?php echo $o++?>" data-toggle="tab"> <?php echo HTMLFormatHelper::icon($item->field_groups[$group_id]['icon'])?> <?php echo JText::_($group_id)?></a></li>
				<?php endforeach;?>
			<?php endif;?>
		</ul>
	<?php endif;?>

	<?php if(isset($this->item->fields_by_groups)):?>
		<?php foreach ($this->item->fields_by_groups as $group_name => $fields) :?>

			<?php $started = true;?>
			<?php group_start($this, $group_name, 'tab-'.$i++);?>
			<dl class="dl-horizontal fields-list fields-group<?php echo $i;?>">
				<?php foreach ($fields as $field_id => $field):?>
					<dt id="<?php echo 'dt-'.$field_id; ?>" class="<?php echo $field->fieldclass;?>">
						<?php if($field->params->get('core.show_lable') > 1):?>
							<label id="<?php echo $field->id;?>-lbl">
								<?php echo $field->label; ?>
								<?php if($field->params->get('core.icon')):?>
									<?php echo HTMLFormatHelper::icon($field->params->get('core.icon'));  ?>
								<?php endif;?>
							</label>
							<?php if($field->params->get('core.label_break') > 1):?>
							<?php endif;?>
						<?php endif;?>
					</dt>
					<dd id="<?php echo 'dd-'.$field_id; ?>" class="<?php echo $field->fieldclass;?><?php echo ($field->params->get('core.label_break') > 1 ? ' line-brk' : NULL) ?>">
						<?php echo $field->result; ?>
					</dd>
				<?php endforeach;?>
			</dl>
			<?php group_end($this);?>
		<?php endforeach;?>
	<?php endif;?>

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
</script>
<script>
  //reload send mail iframe
  jQuery("[id^='emailmodal']").on('hide', function() {
    var ifr = jQuery(this).find('iframe');
    ifr.attr('src', ifr.attr('src'));
  });
</script>

<?php if(strpos($path,'userorganizations')):?>
<script type="text/javascript">
		(function($){
			$(function(){
				$(window).load(function(){
					var org_links = $("table.table-striped h2.record-title>a[href^='\/joomla\/index.php\/organizations\/']");
					org_links.each(function(i,ele){
						var new_link = $(ele).attr("href").replace(/^(\/joomla\/?index\.php\/)(organizations)(\/.*)$/,function(m,p1,p2,p3){
						        							return p1+"userprofile"+p3;
											});
						$(ele).attr("href",new_link);
					});
				})
			});
		})(jQuery);
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

