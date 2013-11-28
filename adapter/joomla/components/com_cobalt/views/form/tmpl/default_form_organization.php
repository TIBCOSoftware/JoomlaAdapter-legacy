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
defined('_JEXEC') or die('Restricted access');
require_once JPATH_BASE . "/components/com_cobalt/controllers/ajaxmore.php";
$started = false;
$params = $this->tmpl_params;
if($params->get('tmpl_params.form_grouping_type', 0))
{
	$started = true;
}
$k = 0;
?>
<style>
	.licon {
	 	float: right;
	 	margin-left: 5px;
	}
	.line-brk {
		margin-left: 0px !important;
	}
	.control-group {
		margin-bottom: 10px;
		padding: 8px 0;
		-webkit-transition: all 200ms ease-in-out;
		-moz-transition: all 200ms ease-in-out;
		-o-transition: all 200ms ease-in-out;
		-ms-transition: all 200ms ease-in-out;
		transition: all 200ms ease-in-out;
	}
	.highlight-element {
		-webkit-animation-name: glow;
		-webkit-animation-duration: 1.5s;
		-webkit-animation-iteration-count: 1;
		-webkit-animation-direction: alternate;
		-webkit-animation-timing-function: ease-out;

		-moz-animation-name: glow;
		-moz-animation-duration: 1.5s;
		-moz-animation-iteration-count: 1;
		-moz-animation-direction: alternate;
		-moz-animation-timing-function: ease-out;

		-ms-animation-name: glow;
		-ms-animation-duration: 1.5s;
		-ms-animation-iteration-count: 1;
		-ms-animation-direction: alternate;
		-ms-animation-timing-function: ease-out;
	}
	<?php echo $params->get('tmpl_params.css');?>
@-webkit-keyframes glow {
	0% {
		background-color: #fdd466;
	}
	100% {
		background-color: transparent;
	}
}
@-moz-keyframes glow {
	0% {
		background-color: #fdd466;
	}
	100% {
		background-color: transparent;
	}
}

@-ms-keyframes glow {
	0% {
		background-color: #fdd466;
	}
	100% {
		background-color: transparent;
	}
}
#tabs-list {
  display: none;
}

#tabs-box {
  border-style: none;
}
.form-actions .btn {
  display: none;
}
.form-actions .btn:last-child {
  display: inline-block;
}
.asg-create-org-step2 {
  display: none;
}
</style>

<div class="form-horizontal">
<?php if(in_array($params->get('tmpl_params.form_grouping_type', 0), array(1, 4))):?>
	<div class="tabbable<?php if($params->get('tmpl_params.form_grouping_type', 0) == 4) echo ' tabs-left' ?>">
		<ul class="nav nav-tabs" id="tabs-list">
			<li><a href="#tab-main" data-toggle="tab"><?php echo JText::_($params->get('tmpl_params.tab_main', 'Main'));?></a></li>

			<?php if(isset($this->sorted_fields)):?>
				<?php foreach ($this->sorted_fields as $group_id => $fields) :?>
					<?php if($group_id == 0) continue;?>
					<li><a class="taberlink" href="#tab-<?php echo $group_id?>" data-toggle="tab"><?php echo HTMLFormatHelper::icon($this->field_groups[$group_id]['icon'])?> <?php echo $this->field_groups[$group_id]['name']?></a></li>
				<?php endforeach;?>
			<?php endif;?>

			<?php if(count($this->meta)):?>
				<li><a href="#tab-meta" data-toggle="tab"><?php echo JText::_('Meta Data');?></a></li>
			<?php endif;?>
			<?php if(count($this->core_admin_fields)):?>
				<li><a href="#tab-special" data-toggle="tab"><?php echo JText::_('Special Fields');?></a></li>
			<?php endif;?>
			<?php if(count($this->core_fields)):?>
				<li><a href="#tab-core" data-toggle="tab"><?php echo JText::_('Core Fields');?></a></li>
			<?php endif;?>
		</ul>
<?php endif;?>
	<?php group_start($this, $params->get('tmpl_params.tab_main', 'Main'), 'tab-main');?>
  <div class="asg-create-org-step1">
    <div class="row-fluid">
      <div class="span8">
        <?php if($params->get('tmpl_params.tab_main_descr')):?>
            <?php echo $params->get('tmpl_params.tab_main_descr'); ?>
        <?php endif;?>
        <!-- title goes here -->
        <?php if($this->type->params->get('properties.item_title', 1) == 1):?>
          <div class="control-group odd<?php echo $k = 1 - $k ?>">
            <label id="title-lbl" for="jform_title" class="control-label" >
              <?php if($params->get('tmpl_core.form_title_icon', 1)):?>
                <?php echo HTMLFormatHelper::icon($params->get('tmpl_core.item_icon_title_icon', 'edit.png'));  ?>
              <?php endif;?>

              <?php echo JText::_($this->tmpl_params->get('tmpl_core.form_label_title', 'Title')) ?>
              <span class="pull-right" rel="tooltip" data-original-title="<?php echo JText::_('CREQUIRED')?>">
                <?php echo HTMLFormatHelper::icon('asterisk-small.png');  ?></span>
            </label>
            <div class="controls">
              <div id="field-alert-title" class="alert alert-error" style="display:none"></div>
              <div class="row-fluid">
                <?php echo $this->form->getInput('title'); ?>
              </div>
            </div>
          </div>
        <?php else :?>
          <input type="hidden" name="jform[title]" value="<?php echo htmlentities(!empty($this->item->title) ? $this->item->title : JText::_('CNOTITLE').': '.time(), ENT_COMPAT, 'UTF-8')?>" />
        <?php endif;?>
        <!-- something I don't know -->
        <?php if($this->anywhere) : ?>
          <div class="control-group odd<?php echo $k = 1 - $k ?>">
            <label id="anywhere-lbl" class="control-label" >
              <?php if($params->get('tmpl_core.form_anywhere_icon', 1)):?>
                <?php echo HTMLFormatHelper::icon('document-share.png');  ?>
              <?php endif;?>

              <?php echo JText::_($this->tmpl_params->get('tmpl_core.form_label_anywhere', 'Where to post')) ?>
              <span class="pull-right" rel="tooltip" data-original-title="<?php echo JText::_('CREQUIRED')?>"><?php echo HTMLFormatHelper::icon('asterisk-small.png');  ?></span>
            </label>
            <div class="controls">
              <div id="field-alert-anywhere" class="alert alert-error" style="display:none"></div>
              <?php echo JHTML::_('users.wheretopost', @$this->item); ?>
            </div>
          </div>


          <div class="control-group odd<?php echo $k = 1 - $k ?>">
            <label id="anywherewho-lbl" for="whorepost" class="control-label" >
              <?php if($params->get('tmpl_core.form_anywhere_who_icon', 1)):?>
                <?php echo HTMLFormatHelper::icon('arrow-retweet.png');  ?>
              <?php endif;?>

              <?php echo JText::_($this->tmpl_params->get('tmpl_core.form_label_anywhere_who', 'Who can repost')) ?>
            </label>
            <div class="controls">
              <div id="field-alert-anywhere" class="alert alert-error" style="display:none"></div>
              <?php echo $this->form->getInput('whorepost'); ?>
            </div>
          </div>
        <?php endif;?>
        <!-- category goes here -->
        <?php if(in_array($this->params->get('submission.allow_category'), $this->user->getAuthorisedViewLevels()) && $this->section->categories):?>
          <div class="control-group odd<?php echo $k = 1 - $k ?>">
            <?php if($this->catsel_params->get('tmpl_core.category_label', 0)):?>
              <label id="category-lbl" for="category" class="control-label" >
                <?php if($params->get('tmpl_core.form_category_icon', 1)):?>
                  <?php echo HTMLFormatHelper::icon('category.png');  ?>
                <?php endif;?>

                <?php echo JText::_($this->tmpl_params->get('tmpl_core.form_label_category', 'Category')) ?>

                <?php if(!$this->type->params->get('submission.first_category', 0) && in_array($this->type->params->get('submission.allow_category', 1), $this->user->getAuthorisedViewLevels())) : ?>
                  <span class="pull-right" rel="tooltip" data-original-title="<?php echo JText::_('CREQUIRED')?>"><?php echo HTMLFormatHelper::icon('asterisk-small.png');  ?></span>
                <?php endif;?>
              </label>
            <?php endif;?>
            <div class="controls">
              <div id="field-alert-category" class="alert alert-error" style="display:none"></div>
              <?php echo $this->loadTemplate('category_'.$params->get('tmpl_params.tmpl_category', 'default')); ?>
            </div>
          </div>
        <?php elseif(!empty($this->category->id)):?>
          <div class="control-group odd<?php echo $k = 1 - $k ?>">
            <label id="category-lbl" for="category" class="control-label">
              <?php if($params->get('tmpl_core.form_category_icon', 1)):?>
                <?php echo HTMLFormatHelper::icon('category.png');  ?>
              <?php endif;?>

              <?php echo JText::_($this->tmpl_params->get('tmpl_core.form_label_category', 'Category')) ?>

              <?php if(!$this->type->params->get('submission.first_category', 0) && in_array($this->type->params->get('submission.allow_category', 1), $this->user->getAuthorisedViewLevels())) : ?>
                <span class="pull-right" rel="tooltip" data-original-title="<?php echo JText::_('CREQUIRED')?>"><?php echo HTMLFormatHelper::icon('asterisk-small.png');  ?></span>
              <?php endif;?>
            </label>
            <div class="controls">
              <div id="field-alert-category" class="alert alert-error" style="display:none"></div>
              <?php echo $this->section->name;?>/<?php echo $this->category->path; ?>
            </div>
          </div>
        <?php endif;?>


        <?php if($this->ucategory) : ?>
          <div class="control-group odd<?php echo $k = 1 - $k ?>">
            <label id="ucategory-lbl" for="ucatid" class="control-label" >
              <?php if($params->get('tmpl_core.form_ucategory_icon', 1)):?>
                <?php echo HTMLFormatHelper::icon('category.png');  ?>
              <?php endif;?>

              <?php echo JText::_($this->tmpl_params->get('tmpl_core.form_label_ucategory', 'Category')) ?>

              <span class="pull-right" rel="tooltip" data-original-title="<?php echo JText::_('CREQUIRED')?>"><?php echo HTMLFormatHelper::icon('asterisk-small.png');  ?></span>
            </label>
            <div class="controls">
              <div id="field-alert-ucat" class="alert alert-error" style="display:none"></div>
              <?php echo $this->form->getInput('ucatid'); ?>
            </div>
          </div>
        <?php else:?>
          <?php $this->form->setFieldAttribute('ucatid', 'type', 'hidden'); ?>
          <?php $this->form->setValue('ucatid', null, '0'); ?>
          <?php echo $this->form->getInput('ucatid'); ?>
        <?php endif;?>

        <?php if($this->multirating):?>
          <div class="control-group odd<?php echo $k = 1 - $k ?>">
            <label id="jform_multirating-lbl" class="control-label" for="jform_multirating" ><?php echo strip_tags($this->form->getLabel('multirating'));?></label>
            <div class="controls">
              <?php echo $this->multirating;?>
            </div>
          </div>
        <?php endif;?>
        <!-- description goes here -->
        <?php $description = $this->sorted_fields[0][17]?>
        <div id="fld-<?php echo $description->id;?>" class="control-group odd<?php echo $k = 1 - $k ?> <?php echo 'field-17'; ?> <?php echo $description->fieldclass;?>">
          <?php if($description->params->get('core.show_lable') == 1 || $description->params->get('core.show_lable') == 3):?>
            <label id="lbl-<?php echo $description->id;?>" for="field_<?php echo $description->id;?>" class="control-label <?php echo $description->class;?>" >
              <?php if($description->params->get('core.icon') && $params->get('tmpl_core.item_icon_fields')):?>
                <?php echo HTMLFormatHelper::icon($description->params->get('core.icon'));  ?>
              <?php endif;?>


              <?php if ($description->required): ?>
                <span class="pull-right" rel="tooltip" data-original-title="<?php echo JText::_('CREQUIRED')?>"><?php echo HTMLFormatHelper::icon('asterisk-small.png');  ?></span>
              <?php endif;?>

              <?php if ($description->description):?>
                <span class="pull-right" rel="tooltip" style="cursor: help;"  data-original-title="<?php echo htmlentities(($description->translateDescription ? JText::_($description->description) : $description->description), ENT_COMPAT, 'UTF-8');?>">
                  <?php echo HTMLFormatHelper::icon('question-small-white.png');  ?>
                </span>
              <?php endif;?>

              <?php echo $description->label; ?>

            </label>
            <?php if(in_array($description->params->get('core.label_break'), array(1,3))):?>
              <div style="clear: both;"></div>
            <?php endif;?>
          <?php endif;?>

          <div class="controls<?php if(in_array($description->params->get('core.label_break'), array(1,3))) echo '-full'; ?><?php echo (in_array($description->params->get('core.label_break'), array(1,3)) ? ' line-brk' : NULL) ?><?php echo $description->fieldclass  ?>">
            <div id="field-alert-<?php echo $description->id?>" class="alert alert-error" style="display:none"></div>
            <?php echo $description->result; ?>
          </div>
        </div>
        <!-- email goes here -->
        <?php $email = $this->sorted_fields[0][19]?>
        <div id="fld-<?php echo $email->id;?>" class="control-group odd<?php echo $k = 1 - $k ?> <?php echo 'field-19'; ?> <?php echo $email->fieldclass;?>">
          <?php if($email->params->get('core.show_lable') == 1 || $email->params->get('core.show_lable') == 3):?>
            <label id="lbl-<?php echo $email->id;?>" for="field_<?php echo $email->id;?>" class="control-label <?php echo $email->class;?>" >
              <?php if($email->params->get('core.icon') && $params->get('tmpl_core.item_icon_fields')):?>
                <?php echo HTMLFormatHelper::icon($email->params->get('core.icon'));  ?>
              <?php endif;?>


              <?php if ($email->required): ?>
                <span class="pull-right" rel="tooltip" data-original-title="<?php echo JText::_('CREQUIRED')?>"><?php echo HTMLFormatHelper::icon('asterisk-small.png');  ?></span>
              <?php endif;?>

              <?php if ($email->description):?>
                <span class="pull-right" rel="tooltip" style="cursor: help;"  data-original-title="<?php echo htmlentities(($email->translateDescription ? JText::_($email->description) : $email->description), ENT_COMPAT, 'UTF-8');?>">
                  <?php echo HTMLFormatHelper::icon('question-small-white.png');  ?>
                </span>
              <?php endif;?>

              <?php echo $email->label; ?>

            </label>
            <?php if(in_array($email->params->get('core.label_break'), array(1,3))):?>
              <div style="clear: both;"></div>
            <?php endif;?>
          <?php endif;?>

          <div class="controls<?php if(in_array($email->params->get('core.label_break'), array(1,3))) echo '-full'; ?><?php echo (in_array($email->params->get('core.label_break'), array(1,3)) ? ' line-brk' : NULL) ?><?php echo $email->fieldclass  ?>">
            <div id="field-alert-<?php echo $email->id?>" class="alert alert-error" style="display:none"></div>
            <?php echo $email->result; ?>
          </div>
        </div>

        <!-- contact goes here -->
        <?php $contact = $this->sorted_fields[0][48]?>
        <div id="fld-<?php echo $contact->id;?>" class="control-group odd<?php echo $k = 1 - $k ?> <?php echo 'field-48'; ?> <?php echo $contact->fieldclass;?>">
          <?php if($contact->params->get('core.show_lable') == 1 || $contact->params->get('core.show_lable') == 3):?>
            <label id="lbl-<?php echo $contact->id;?>" for="field_<?php echo $contact->id;?>" class="control-label <?php echo $contact->class;?>" >
              <?php if($contact->params->get('core.icon') && $params->get('tmpl_core.item_icon_fields')):?>
                <?php echo HTMLFormatHelper::icon($contact->params->get('core.icon'));  ?>
              <?php endif;?>


              <?php if ($contact->required): ?>
                <span class="pull-right" rel="tooltip" data-original-title="<?php echo JText::_('CREQUIRED')?>"><?php echo HTMLFormatHelper::icon('asterisk-small.png');  ?></span>
              <?php endif;?>

              <?php if ($contact->description):?>
                <span class="pull-right" rel="tooltip" style="cursor: help;"  data-original-title="<?php echo htmlentities(($contact->translateDescription ? JText::_($contact->description) : $contact->description), ENT_COMPAT, 'UTF-8');?>">
                  <?php echo HTMLFormatHelper::icon('question-small-white.png');  ?>
                </span>
              <?php endif;?>

              <?php echo $contact->label; ?>

            </label>
            <?php if(in_array($contact->params->get('core.label_break'), array(1,3))):?>
              <div style="clear: both;"></div>
            <?php endif;?>
          <?php endif;?>

          <div class="controls<?php if(in_array($contact->params->get('core.label_break'), array(1,3))) echo '-full'; ?><?php echo (in_array($contact->params->get('core.label_break'), array(1,3)) ? ' line-brk' : NULL) ?><?php echo $contact->fieldclass  ?>">
            <div id="field-alert-<?php echo $contact->id?>" class="alert alert-error" style="display:none"></div>
            <?php echo $contact->result; ?>
          </div>
        </div>

        <!-- contact detail goes here -->
        <?php $detail = $this->sorted_fields[0][20]?>
        <div id="fld-<?php echo $detail->id;?>" class="control-group odd<?php echo $k = 1 - $k ?> <?php echo 'field-20'; ?> <?php echo $detail->fieldclass;?>">
          <?php if($detail->params->get('core.show_lable') == 1 || $detail->params->get('core.show_lable') == 3):?>
            <label id="lbl-<?php echo $detail->id;?>" for="field_<?php echo $detail->id;?>" class="control-label <?php echo $detail->class;?>" >
              <?php if($detail->params->get('core.icon') && $params->get('tmpl_core.item_icon_fields')):?>
                <?php echo HTMLFormatHelper::icon($detail->params->get('core.icon'));  ?>
              <?php endif;?>


              <?php if ($detail->required): ?>
                <span class="pull-right" rel="tooltip" data-original-title="<?php echo JText::_('CREQUIRED')?>"><?php echo HTMLFormatHelper::icon('asterisk-small.png');  ?></span>
              <?php endif;?>

              <?php if ($detail->description):?>
                <span class="pull-right" rel="tooltip" style="cursor: help;"  data-original-title="<?php echo htmlentities(($detail->translateDescription ? JText::_($detail->description) : $detail->description), ENT_COMPAT, 'UTF-8');?>">
                  <?php echo HTMLFormatHelper::icon('question-small-white.png');  ?>
                </span>
              <?php endif;?>

              <?php echo $detail->label; ?>

            </label>
            <?php if(in_array($detail->params->get('core.label_break'), array(1,3))):?>
              <div style="clear: both;"></div>
            <?php endif;?>
          <?php endif;?>

          <div class="controls<?php if(in_array($detail->params->get('core.label_break'), array(1,3))) echo '-full'; ?><?php echo (in_array($detail->params->get('core.label_break'), array(1,3)) ? ' line-brk' : NULL) ?><?php echo $detail->fieldclass  ?>">
            <div id="field-alert-<?php echo $detail->id?>" class="alert alert-error" style="display:none"></div>
            <?php echo $detail->result; ?>
          </div>
        </div>

        <!-- members goes here -->
        <?php $members = $this->sorted_fields[0][56]?>
        <div id="fld-<?php echo $members->id;?>" class="control-group odd<?php echo $k = 1 - $k ?> <?php echo 'field-56'; ?> <?php echo $members->fieldclass;?>">
          <?php if($members->params->get('core.show_lable') == 1 || $members->params->get('core.show_lable') == 3):?>
            <label id="lbl-<?php echo $members->id;?>" for="field_<?php echo $members->id;?>" class="control-label <?php echo $members->class;?>" >
              <?php if($members->params->get('core.icon') && $params->get('tmpl_core.item_icon_fields')):?>
                <?php echo HTMLFormatHelper::icon($members->params->get('core.icon'));  ?>
              <?php endif;?>


              <?php if ($members->required): ?>
                <span class="pull-right" rel="tooltip" data-original-title="<?php echo JText::_('CREQUIRED')?>"><?php echo HTMLFormatHelper::icon('asterisk-small.png');  ?></span>
              <?php endif;?>

              <?php if ($members->description):?>
                <span class="pull-right" rel="tooltip" style="cursor: help;"  data-original-title="<?php echo htmlentities(($members->translateDescription ? JText::_($members->description) : $members->description), ENT_COMPAT, 'UTF-8');?>">
                  <?php echo HTMLFormatHelper::icon('question-small-white.png');  ?>
                </span>
              <?php endif;?>

              <?php echo $members->label; ?>

            </label>
            <?php if(in_array($members->params->get('core.label_break'), array(1,3))):?>
              <div style="clear: both;"></div>
            <?php endif;?>
          <?php endif;?>

          <div class="controls<?php if(in_array($members->params->get('core.label_break'), array(1,3))) echo '-full'; ?><?php echo (in_array($members->params->get('core.label_break'), array(1,3)) ? ' line-brk' : NULL) ?><?php echo $members->fieldclass  ?>">
            <div id="field-alert-<?php echo $members->id?>" class="alert alert-error" style="display:none"></div>
            <?php echo $members->result; ?>
          </div>
        </div>

        <!-- subscriptions goes here -->
        <?php $sub = $this->sorted_fields[0][74]?>
        <div id="fld-<?php echo $sub->id;?>" class="control-group odd<?php echo $k = 1 - $k ?> <?php echo 'field-74'; ?> <?php echo $sub->fieldclass;?>">
          <?php if($sub->params->get('core.show_lable') == 1 || $sub->params->get('core.show_lable') == 3):?>
            <label id="lbl-<?php echo $sub->id;?>" for="field_<?php echo $sub->id;?>" class="control-label <?php echo $sub->class;?>" >
              <?php if($sub->params->get('core.icon') && $params->get('tmpl_core.item_icon_fields')):?>
                <?php echo HTMLFormatHelper::icon($sub->params->get('core.icon'));  ?>
              <?php endif;?>


              <?php if ($sub->required): ?>
                <span class="pull-right" rel="tooltip" data-original-title="<?php echo JText::_('CREQUIRED')?>"><?php echo HTMLFormatHelper::icon('asterisk-small.png');  ?></span>
              <?php endif;?>

              <?php if ($sub->description):?>
                <span class="pull-right" rel="tooltip" style="cursor: help;"  data-original-title="<?php echo htmlentities(($sub->translateDescription ? JText::_($sub->description) : $sub->description), ENT_COMPAT, 'UTF-8');?>">
                  <?php echo HTMLFormatHelper::icon('question-small-white.png');  ?>
                </span>
              <?php endif;?>

              <?php echo $sub->label; ?>

            </label>
            <?php if(in_array($sub->params->get('core.label_break'), array(1,3))):?>
              <div style="clear: both;"></div>
            <?php endif;?>
          <?php endif;?>

          <div class="controls<?php if(in_array($sub->params->get('core.label_break'), array(1,3))) echo '-full'; ?><?php echo (in_array($sub->params->get('core.label_break'), array(1,3)) ? ' line-brk' : NULL) ?><?php echo $sub->fieldclass  ?>">
            <div id="field-alert-<?php echo $sub->id?>" class="alert alert-error" style="display:none"></div>
            <?php echo $sub->result; ?>
          </div>
        </div>

        <?php if(MECAccess::allowAccessAuthor($this->type, 'properties.item_can_add_tag', $this->item->user_id) &&
          $this->type->params->get('properties.item_can_view_tag')):?>
          <div class="control-group odd<?php echo $k = 1 - $k ?>">
            <label id="tags-lbl" for="tags" class="control-label" >
              <?php if($params->get('tmpl_core.form_tags_icon', 1)):?>
                <?php echo HTMLFormatHelper::icon('price-tag.png');  ?>
              <?php endif;?>
              <?php echo JText::_($this->tmpl_params->get('tmpl_core.form_label_tags', 'Tags')) ?>
            </label>
            <div class="controls">
              <?php //echo JHtml::_('tags.tagform', $this->section, json_decode($this->item->tags, TRUE), array(), 'jform[tags]'); ?>
              <?php echo $this->form->getInput('tags'); ?>
            </div>
          </div>
        <?php endif;?>
      </div>
      <div class="span4">
        <!-- Thumbnail goes here -->
<!--        --><?php //$thumbnail = $this->sorted_fields[0][121]?>
<!--        <div id="fld---><?php //echo $thumbnail->id;?><!--" class="control-group odd--><?php //echo $k = 1 - $k ?><!-- --><?php //echo 'field-3'; ?><!-- --><?php //echo $thumbnail->fieldclass;?><!--">-->
<!--          --><?php //if($thumbnail->params->get('core.show_lable') == 1 || $thumbnail->params->get('core.show_lable') == 3):?>
<!--            <label id="lbl---><?php //echo $thumbnail->id;?><!--" for="field_--><?php //echo $thumbnail->id;?><!--" class="--><?php //echo $thumbnail->class;?><!--" >-->
<!--              --><?php //if($thumbnail->params->get('core.icon') && $params->get('tmpl_core.item_icon_fields')):?>
<!--                --><?php //echo HTMLFormatHelper::icon($thumbnail->params->get('core.icon'));  ?>
<!--              --><?php //endif;?>
<!--                -->
<!--              Upload product thumbnail-->
<!--              --><?php //if ($thumbnail->required): ?>
<!--                <span rel="tooltip" data-original-title="--><?php //echo JText::_('CREQUIRED')?><!--">--><?php //echo HTMLFormatHelper::icon('asterisk-small.png');  ?><!--</span>-->
<!--              --><?php //endif;?>
<!--              -->
<!--            </label>-->
<!--            --><?php //if(in_array($thumbnail->params->get('core.label_break'), array(1,3))):?>
<!--              <div style="clear: both;"></div>-->
<!--            --><?php //endif;?>
<!--          --><?php //endif;?>
<!---->
<!--          <div class="--><?php //if(in_array($thumbnail->params->get('core.label_break'), array(1,3))) echo '-full'; ?><!----><?php //echo (in_array($thumbnail->params->get('core.label_break'), array(1,3)) ? ' line-brk' : NULL) ?><!----><?php //echo $thumbnail->fieldclass  ?><!--">-->
<!--            <div id="field-alert---><?php //echo $thumbnail->id?><!--" class="alert alert-error" style="display:none"></div>-->
<!--            --><?php //echo $thumbnail->result; ?>
<!--          </div>-->
<!--        </div>-->
      </div>
    </div>
    <div class="control-group">
      <div class="controls">
        <a class="btn btn-primary asg-create-app-next">Next</a>
      </div>
    </div>
  </div>
  <div class="asg-create-org-step2">
    <!-- add products goes here -->
    <?php $products = $this->sorted_fields[0][43]?>
    <div id="fld-<?php echo $products->id;?>" class="control-group odd<?php echo $k = 1 - $k ?> <?php echo 'field-43'; ?> <?php echo $products->fieldclass;?>">
      <?php if($products->params->get('core.show_lable') == 1 || $products->params->get('core.show_lable') == 3):?>
        <label id="lbl-<?php echo $products->id;?>" for="field_<?php echo $products->id;?>" class="control-label <?php echo $products->class;?>" >
          <?php if($products->params->get('core.icon') && $params->get('tmpl_core.item_icon_fields')):?>
            <?php echo HTMLFormatHelper::icon($products->params->get('core.icon'));  ?>
          <?php endif;?>


          <?php if ($products->required): ?>
            <span class="pull-right" rel="tooltip" data-original-title="<?php echo JText::_('CREQUIRED')?>"><?php echo HTMLFormatHelper::icon('asterisk-small.png');  ?></span>
          <?php endif;?>

          <?php if ($products->description):?>
            <span class="pull-right" rel="tooltip" style="cursor: help;"  data-original-title="<?php echo htmlentities(($products->translateDescription ? JText::_($products->description) : $products->description), ENT_COMPAT, 'UTF-8');?>">
              <?php echo HTMLFormatHelper::icon('question-small-white.png');  ?>
            </span>
          <?php endif;?>

          <?php echo $products->label; ?>

        </label>
        <?php if(in_array($products->params->get('core.label_break'), array(1,3))):?>
          <div style="clear: both;"></div>
        <?php endif;?>
      <?php endif;?>

      <div class="controls<?php if(in_array($products->params->get('core.label_break'), array(1,3))) echo '-full'; ?><?php echo (in_array($products->params->get('core.label_break'), array(1,3)) ? ' line-brk' : NULL) ?><?php echo $products->fieldclass  ?>">
        <div id="field-alert-<?php echo $products->id?>" class="alert alert-error" style="display:none"></div>
        <?php echo $products->result; ?>
      </div>
    </div>
    <!-- add apis goes here -->
    <?php $apis = $this->sorted_fields[0][41]?>
    <div id="fld-<?php echo $apis->id;?>" class="control-group odd<?php echo $k = 1 - $k ?> <?php echo 'field-41'; ?> <?php echo $apis->fieldclass;?>">
      <?php if($apis->params->get('core.show_lable') == 1 || $apis->params->get('core.show_lable') == 3):?>
        <label id="lbl-<?php echo $apis->id;?>" for="field_<?php echo $apis->id;?>" class="control-label <?php echo $apis->class;?>" >
          <?php if($apis->params->get('core.icon') && $params->get('tmpl_core.item_icon_fields')):?>
            <?php echo HTMLFormatHelper::icon($apis->params->get('core.icon'));  ?>
          <?php endif;?>


          <?php if ($apis->required): ?>
            <span class="pull-right" rel="tooltip" data-original-title="<?php echo JText::_('CREQUIRED')?>"><?php echo HTMLFormatHelper::icon('asterisk-small.png');  ?></span>
          <?php endif;?>

          <?php if ($apis->description):?>
            <span class="pull-right" rel="tooltip" style="cursor: help;"  data-original-title="<?php echo htmlentities(($apis->translateDescription ? JText::_($apis->description) : $apis->description), ENT_COMPAT, 'UTF-8');?>">
              <?php echo HTMLFormatHelper::icon('question-small-white.png');  ?>
            </span>
          <?php endif;?>

          <?php echo $apis->label; ?>

        </label>
        <?php if(in_array($apis->params->get('core.label_break'), array(1,3))):?>
          <div style="clear: both;"></div>
        <?php endif;?>
      <?php endif;?>

      <div class="controls<?php if(in_array($apis->params->get('core.label_break'), array(1,3))) echo '-full'; ?><?php echo (in_array($apis->params->get('core.label_break'), array(1,3)) ? ' line-brk' : NULL) ?><?php echo $apis->fieldclass  ?>">
        <div id="field-alert-<?php echo $apis->id?>" class="alert alert-error" style="display:none"></div>
        <?php echo $apis->result; ?>
      </div>
    </div>
    <!-- add applications goes here -->
    <?php $applications = $this->sorted_fields[0][61]?>
    <div id="fld-<?php echo $applications->id;?>" class="control-group odd<?php echo $k = 1 - $k ?> <?php echo 'field-61'; ?> <?php echo $applications->fieldclass;?>">
      <?php if($applications->params->get('core.show_lable') == 1 || $applications->params->get('core.show_lable') == 3):?>
        <label id="lbl-<?php echo $applications->id;?>" for="field_<?php echo $applications->id;?>" class="control-label <?php echo $applications->class;?>" >
          <?php if($applications->params->get('core.icon') && $params->get('tmpl_core.item_icon_fields')):?>
            <?php echo HTMLFormatHelper::icon($applications->params->get('core.icon'));  ?>
          <?php endif;?>


          <?php if ($applications->required): ?>
            <span class="pull-right" rel="tooltip" data-original-title="<?php echo JText::_('CREQUIRED')?>"><?php echo HTMLFormatHelper::icon('asterisk-small.png');  ?></span>
          <?php endif;?>

          <?php if ($applications->description):?>
            <span class="pull-right" rel="tooltip" style="cursor: help;"  data-original-title="<?php echo htmlentities(($applications->translateDescription ? JText::_($applications->description) : $applications->description), ENT_COMPAT, 'UTF-8');?>">
              <?php echo HTMLFormatHelper::icon('question-small-white.png');  ?>
            </span>
          <?php endif;?>

          <?php echo $applications->label; ?>

        </label>
        <?php if(in_array($applications->params->get('core.label_break'), array(1,3))):?>
          <div style="clear: both;"></div>
        <?php endif;?>
      <?php endif;?>

      <div class="controls<?php if(in_array($applications->params->get('core.label_break'), array(1,3))) echo '-full'; ?><?php echo (in_array($applications->params->get('core.label_break'), array(1,3)) ? ' line-brk' : NULL) ?><?php echo $applications->fieldclass  ?>">
        <div id="field-alert-<?php echo $applications->id?>" class="alert alert-error" style="display:none"></div>
        <?php echo $applications->result; ?>
      </div>
    </div>
    <div class="control-group">
      <div class="controls">
        <a class="btn asg-create-app-back">Back</a>
      </div>
    </div>
  </div>
  <?php unset($this->sorted_fields[0]);?>


	<?php group_end($this);?>


	<?php if(isset($this->sorted_fields)):?>
		<?php foreach ($this->sorted_fields as $group_id => $fields) :?>
			<?php $started = true;?>
			<?php group_start($this, $this->field_groups[$group_id]['name'], 'tab-'.$group_id);?>
			<?php if(!empty($this->field_groups[$group_id]['descr'])):?>
				<?php echo $this->field_groups[$group_id]['descr'];?>
			<?php endif;?>
			<?php foreach ($fields as $field_id => $field):?>
				<div id="fld-<?php echo $field->id;?>" class="control-group odd<?php echo $k = 1 - $k ?> <?php echo 'field-'.$field_id; ?> <?php echo $field->fieldclass;?>">
					<?php if($field->params->get('core.show_lable') == 1 || $field->params->get('core.show_lable') == 3):?>
						<label id="lbl-<?php echo $field->id;?>" for="field_<?php echo $field->id;?>" class="control-label <?php echo $field->class;?>" >
							<?php if($field->params->get('core.icon') && $params->get('tmpl_core.item_icon_fields')):?>
								<?php echo HTMLFormatHelper::icon($field->params->get('core.icon'));  ?>
							<?php endif;?>
							<?php if ($field->required): ?>
								<span class="pull-right" rel="tooltip" data-original-title="<?php echo JText::_('CREQUIRED')?>"><?php echo HTMLFormatHelper::icon('asterisk-small.png');  ?></span>
							<?php endif;?>

							<?php if ($field->description):?>
								<span class="pull-right" rel="tooltip" style="cursor: help;" data-original-title="<?php echo htmlspecialchars(($field->translateDescription ? JText::_($field->description) : $field->description), ENT_COMPAT, 'UTF-8');?>">
									<?php echo HTMLFormatHelper::icon('question-small-white.png');  ?>
								</span>
							<?php endif;?>
							<?php echo $field->label; ?>
						</label>
						<?php if(in_array($field->params->get('core.label_break'), array(1,3))):?>
							<div style="clear: both;"></div>
						<?php endif;?>
					<?php endif;?>

					<div class="controls<?php if(in_array($field->params->get('core.label_break'), array(1,3))) echo '-full'; ?><?php echo (in_array($field->params->get('core.label_break'), array(1,3)) ? ' line-brk' : NULL) ?><?php echo $field->fieldclass  ?>">
						<div id="field-alert-<?php echo $field->id?>" class="alert alert-error" style="display:none"></div>
						<?php echo $field->result; ?>
					</div>
				</div>
			<?php endforeach;?>
			<?php group_end($this);?>
		<?php endforeach;?>
	<?php endif; ?>

	<?php if(count($this->meta)):?>
		<?php $started = true?>
		<?php group_start($this, JText::_('CSEO'), 'tab-meta');?>
			<?php foreach ($this->meta as $label => $meta_name):?>
				<div class="control-group odd<?php echo $k = 1 - $k ?>">
					<label id="jform_meta_descr-lbl" class="control-label" title="" for="jform_<?php echo $meta_name;?>">
					<?php echo JText::_($label); ?>
					</label>
					<div class="controls">
						<div class="row-fluid">
							<?php echo $this->form->getInput($meta_name); ?>
						</div>
					</div>
				</div>
			<?php endforeach;?>

		<?php group_end($this);?>
	<?php endif;?>



	<?php if(count($this->core_admin_fields)):?>
		<?php $started = true?>
		<?php group_start($this, 'Special Fields', 'tab-special');?>
			<div class="admin">
			<?php foreach($this->core_admin_fields as $key => $field ):?>
				<div class="control-group odd<?php echo $k = 1 - $k ?>">
					<label id="jform_<?php echo $field?>-lbl" class="control-label" for="jform_<?php echo $field?>" ><?php echo strip_tags($this->form->getLabel($field));?></label>
					<div class="controls field-<?php echo $field;  ?>">
						<?php echo $this->form->getInput($field); ?>
					</div>
				</div>
			<?php endforeach;?>
			</div>
		<?php group_end($this);?>
	<?php endif;?>

	<?php if(count($this->core_fields)):?>
		<?php group_start($this, 'Core Fields', 'tab-core');?>
		<?php foreach($this->core_fields as $key => $field ):?>
			<div class="control-group odd<?php echo $k = 1 - $k ?>">
				<label id="jform_<?php echo $field?>-lbl" class="control-label" for="jform_<?php echo $field?>" >
					<?php if($params->get('tmpl_core.form_'.$field.'_icon', 1)):?>
						<?php echo HTMLFormatHelper::icon('core-'.$field.'.png');  ?>
					<?php endif;?>
					<?php echo strip_tags($this->form->getLabel($field));?>
				</label>
				<div class="controls">
					<?php echo $this->form->getInput($field); ?>
				</div>
			</div>
		<?php endforeach;?>
		<?php group_end($this);?>
	<?php endif;?>

	<?php if($started):?>
		<?php total_end($this);?>
	<?php endif;?>
	<br />
</div>

<script type="text/javascript">
	<?php if(in_array($params->get('tmpl_params.form_grouping_type', 0), array(1,4))):?>
		jQuery('#tabs-list a:first').tab('show');
	<?php elseif(in_array($params->get('tmpl_params.form_grouping_type', 0), array(2))):?>
		jQuery('#tab-main').collapse('show');
	<?php endif;?>
</script>

<script>
  (function(jQuery) {
    jQuery('.asg-create-app-next').on('click', function() {
      var tab = jQuery(this).parent().parent().parent();
      tab.hide();
      tab.next().show();
      jQuery('.asg-create-app-guide .active').removeClass('active').next().addClass('active');
      if (jQuery('.asg-create-app-guide .active').is(':last-child')) {
        jQuery('.form-actions .btn').show();
      }
    });
    jQuery('.asg-create-app-back').on('click', function() {
      var tab = jQuery(this).parent().parent().parent();
      tab.hide();
      tab.prev().show();
      jQuery('.asg-create-app-guide .active').removeClass('active').prev().addClass('active');
      jQuery('.form-actions .btn').hide();
      jQuery('.form-actions .btn:last-child').show();
    });
  }(jQuery));
</script>




<?php
function group_start($data, $label, $name)
{
	static $start = false;
	switch ($data->tmpl_params->get('tmpl_params.form_grouping_type', 0))
	{
		//tab
		case 4:
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
            if($name != 'tab-main') {
                echo "<legend>{$label}</legend>";
            }
		break;
	}
}

function group_end($data)
{
	switch ($data->tmpl_params->get('tmpl_params.form_grouping_type', 0))
	{
		case 4:
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
	switch ($data->tmpl_params->get('tmpl_params.form_grouping_type', 0))
	{
		//tab
		case 4:
		case 1:
			echo '</div></div>';
		break;
		case 2:
			echo '</div>';
		break;
	}
}
