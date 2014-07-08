<?php
$spotfire_domain = $_POST['domain'];
$user_group = explode('-', $_POST['group']);
$user_session_id = $_POST['sid'];
$root_url = $_POST['rooturl'];

$spotfire_app_url = $root_url."/Analytics/";
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb" lang="en-gb" dir="ltr">
<head>
	<script src="media/jui/js/jquery.min.js" type="text/javascript"></script>
	<link rel="stylesheet" href="templates/nielsen/css/template.css" type="text/css">
	<style>
	body{
		padding:10px;
	}
	
	h3{
		color:red;
	}
	</style>
</head>
<body>
	<?php if(empty($spotfire_domain) || empty($user_session_id) || empty($user_group)):?>
		<h3>Request unavailable.</h3>
	<?php else:?>
		<script type="text/javascript" src="<?php echo $spotfire_app_url; ?>GetJavaScriptApi.ashx?Version=3.1"></script>
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
				var app = new spotfire.webPlayer.Application("<?php echo $spotfire_app_url; ?>", customization);
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
	 			document.domain = "<?php echo $spotfire_domain; ?>";
	 		}
	 		catch(err){
	 			analyticsErrorHandler(0, 'Failed setting of analytics domain to "<?php echo $spotfire_domain; ?>". Please check your settings and try again. [' + err + ']');
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
			document.cookie = 'session-id=<?php echo $user_session_id; ?>; path=/';

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
		
			showHideBtn.click();
		});
		</script>
		
		<div>
			<div id="analytics">
			<div id="analytics-control" style="display:inline-block; width:100%;">
				<?php if(in_array(7, $user_group) || in_array(8, $user_group)): //If user is an Administrator or a SuperUser... ?>
				<select id="selectDashboardMode" title="Select Dashboard Type to Display" class="pull-right" style="margin-bottom:0px;">
				<option value="/ASG/Host">Host</option>
				<option value="/ASG/Partner">Partner</option>
				</select>
				<?php endif;?>
				<button id="btnShowHideDashboard" title="Toggle Dashboard Display" class="btn pull-left" style="display:none;">Show dashboard</button>
				<div id="btnReloadDashboard" title="Refresh Dashboard" class="icon-refresh pull-left" style="visibility:hidden; margin: 7px; cursor: pointer; display:none;"></div>
			</div>
			<div id="analytics-content" style="display: none; clear: both; height:1000px;"></div>
			</div>
		</div>
	<?php endif;?>
	
</body>
</html>