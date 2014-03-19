<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');

if($registration_form = $this->form->getFieldsets()){
	$registration_form_api_user = $registration_form["apiuser"];
	$fields = $this->form->getFieldset($registration_form_api_user->name);
}


$first_name = $fields["jform_apiuser_first_name"];
$last_name = $fields["jform_apiuser_last_name"];
$user_email = $fields["jform_apiuser_user_email"];
$to_agree = $fields["jform_apiuser_to_agree"];

$validate = isset($fields["jform_apiuser_validate"])?$fields["jform_apiuser_validate"]:"";

?>

<style type="text/css">
#member-registration legend{
	border-bottom: none;
}


#jform_apiuser_validate,
img
{
	display: inline-block;
}

#jform_apiuser_validate{
	width:100px;
}
.form-actions{
	background: transparent;
	border: none;
	padding: 0;
}

#member-registration #jform_apiuser_first_name,
#member-registration #jform_apiuser_last_name{
	width:200px;
}
#member-registration .controls{
	margin-left:20px;
}
#member-registration .first-name{
	width:200px;
}
#member-registration #jform_apiuser_user_email{
	width:420px;
}
#member-registration #jform_apiuser_to_agree{
	margin-right:5px;
	margin-top:-1px
}
.form-actions{
	width:160px;
	position:relative;
	top:-100px;
	left:300px;
}
.term {
	width:300px;
	font-size:12px;
}
#member-registration{
	width:500px;
	margin:0 auto;
}
</style>

 <div class="registration<?php echo $this->pageclass_sfx?>">
	<form id="member-registration" action="<?php echo JRoute::_('index.php?option=com_users&task=registration.register'); ?>" method="post" class="form-validate form-horizontal" enctype="multipart/form-data">
		<fieldset aria-invalid="false">
			<legend><?php echo JText::_("PLG_USER_APIUSER_FORM_TITLE"); ?></legend>

			<div class="control-group row-fluid">
						<div class="control span4 first-name">
						<?php echo $first_name->label;?>
						<?php echo $first_name->input;?>
						</div>
						<div class="controls span5">
							<?php echo $last_name->label; ?>
							<?php echo $last_name->input;?>
						</div>
			</div>
			<div class="control-group row-fluid">
				<div class="control span4 email">
				<?php echo $user_email->label;?>
				<?php echo $user_email->input;?>
				</div>
			</div>

			<?php if($validate):?>
			<div class="control-group row-fluid">
				<div class="control span6">
				<?php echo $validate->label;?>
				<?php echo $validate->input;?><img src="<?php echo JURI::root();?>validate.php" id="code" onclick="create_code()"/>
				</div>
			</div>

			<script type="text/javascript">
				function create_code(){
			    document.getElementById('code').src = '<?php echo JURI::root();?>validate.php?'+Math.random()*10000;
				}
			</script>
			<?php endif;?>

			<div class="control-group row-fluid">
				<span><?php echo JText::_("PLG_USER_APIUSER_FIELD_REQUIRED_TIP"); ?></span>
			</div>

			<div class="control-group row-fluid term">
				<!-- <p><?php echo JText::_("PLG_USER_APIUSER_FIELD_TO_AGREE_DESC"); ?><a href="/index.php/2-uncategorised/8-terms-conditions" ><?php echo JText::_("PLG_USER_APIUSER_FIELD_TO_AGREE_LINK_TEXT"); ?></a>.</p>  -->
				<?php $terms=JUri::root()."index.php/2-uncategorised/8-terms-conditions";?>
				<div class="control">
					<label id="jform_apiuser_to_agree-lbl" class="required validate-is_agree" style="margin-right: 5px;" for="jform_apiuser_to_agree"> <?php echo $to_agree->input;?><?php echo JText::_("PLG_USER_APIUSER_FIELD_TO_AGREE_LABEL_PART1")?><a href=<?php echo $terms;?> target="_blank"><?php echo JText::_("PLG_USER_APIUSER_FIELD_TO_AGREE_LINK_TEXT"); ?></a><?php echo JText::_("PLG_USER_APIUSER_FIELD_TO_AGREE_LABEL_PART2")?></label>
				</div>
			</div>


		</fieldset>
		<br/>
		<div class="form-actions">
			<button id=submitButton type="submit" class="btn btn-primary validate"><?php echo JText::_('JREGISTER');?></button>
			<input type="hidden" name="option" value="com_users" />
			<input type="hidden" name="task" value="autoreg.register" />
			<?php echo JHtml::_('form.token');?>
		</div>

	</form>

</div>


<script type="text/javascript">

	window.addEvent('domready', function(){
	   document.formvalidator.setHandler('is_agree', function(value) {
	      return jQuery("#jform_apiuser_to_agree").get(0).checked;
	   });
	   jQuery('#code').click();
	});

</script>

