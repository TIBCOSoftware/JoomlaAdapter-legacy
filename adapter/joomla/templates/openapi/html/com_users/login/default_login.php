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
$app = JFactory::getApplication();
$menu = $app->getMenu();
$menu->setActive(115);
?>
<div class="login <?php echo $this->pageclass_sfx?>">
	<?php if ($this->params->get('show_page_heading')) : ?>
	<div class="page-header">
		<h1>
			<?php echo $this->escape($this->params->get('page_heading')); ?>
		</h1>
	</div>
	<?php endif; ?>

	<?php if (($this->params->get('logindescription_show') == 1 && str_replace(' ', '', $this->params->get('login_description')) != '') || $this->params->get('login_image') != '') : ?>
	<div class="login-description">
	<?php endif; ?>

		<?php if ($this->params->get('logindescription_show') == 1) : ?>
			<?php echo $this->params->get('login_description'); ?>
		<?php endif; ?>

		<?php if (($this->params->get('login_image') != '')) :?>
			<img src="<?php echo $this->escape($this->params->get('login_image')); ?>" class="login-image" alt="<?php echo JTEXT::_('COM_USER_LOGIN_IMAGE_ALT')?>"/>
		<?php endif; ?>

	<?php if (($this->params->get('logindescription_show') == 1 && str_replace(' ', '', $this->params->get('login_description')) != '') || $this->params->get('login_image') != '') : ?>
	</div>
	<?php endif; ?>

	<form id="login-page-login-form" action="<?php echo JRoute::_('index.php?option=com_users&task=user.login'); ?>" method="post" class="form-horizontal">
        <h1>Login to your <?php echo $app->getCfg('sitename'); ?> Account</h1>
        <div class="description">
            By signing in, you are agreeing to the <a target="_blank" href="index.php?option=com_content&view=article&id=8&catid=2&Itemid=107">Terms & Conditions</a> of this site.
        </div>
		<fieldset>
			<?php foreach ($this->form->getFieldset('credentials') as $field) : ?>
				<?php if (!$field->hidden) : ?>
					<div class="control-group">
						<div class="controls">
                            <div class="control-<?php echo $field->name; ?>"></div>
							<?php echo $field->input; ?>
						</div>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>
			<div class="control-group">
				<div class="controls">
					<button type="submit" class="btn btn-primary"><?php echo JText::_('JLOGIN'); ?></button>
					<?php
    			  $comEmail = JComponentHelper::getComponent('com_emails');
    			  $is_show_ping = $comEmail->params->get('is_show_ping');
	  			  $ping_url = $comEmail->params->get("ping_url");
	  			  if ($is_show_ping && $ping_url) {
  			  		echo '<a style="margin-left: 94px;" href="'.$ping_url.'"><strong>'.JText::_('JLOGINPING').'</strong></a>';
	  			  }
    			?>
				</div>
			</div>
			<input type="hidden" name="return" value="<?php echo base64_encode(JURI::root().'index.php'); ?>" />
			<?php echo JHtml::_('form.token'); ?>
		</fieldset>
	</form>
</div>
<div>
			<a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>">
			<?php echo JText::_('COM_USERS_LOGIN_RESET'); ?></a>
			<a href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>">
			<?php echo JText::_('COM_USERS_LOGIN_REMIND'); ?></a>
</div>
