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
require_once JPATH_ROOT . '/includes/api.php';

$started = false;
$params = $this->tmpl_params;
if($params->get('tmpl_params.form_grouping_type', 0))
{
  $started = true;
}
$k = 0;
$app = JFactory::getApplication();

$folder_format = $this->appParams->get('folder_format',1);
$folder_format = date($folder_format,time());

$api_id = $app->input->getInn("id",0);
$old_operations_of_api = DeveloperPortalApi::getOperationsOfApiByApiId($api_id);


$doc = JFactory::getDocument();
$old_operations_of_api_js = "var api_id=".$api_id.";";
$old_operations_of_api_js .= 'var old_operation_of_api = '.json_encode($old_operations_of_api).';';
$doc->addScriptDeclaration($old_operations_of_api_js);


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
.asg-api-step-title-container {
  height:23px;
  position:relative;
}
.asg-api-step-title-container-line {
  height:10px;
  background:#006699;
  position:relative;
  top:8px;
}
.asg-api-step-title {
  position:absolute;
  background:#FFFFFF;
  padding:0px 5px;
  z-index:999;
}
.asg-api-step-title i {
  font-size:14px;
  color:#006699;
  cursor: pointer;
}
.asg-create-api-step1 {
  height: auto;
  margin-bottom: 15px;
}
.asg-create-api-step2 {
  height: auto;
  margin-bottom: 15px;
}
.asg-create-api-step3 {
  height: auto;
  margin-bottom: 15px;
}
.form-actions .btn:last-child {
  display: inline-block;
}
#fld-145 .btn:active,
#fld-145 .btn.active {
    background-color:  #2E9ACF;
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
  <div class="asg-api-step-title-container"><div class="asg-api-step-title"><i class="icon-chevron-down"></i><?php echo JText::_('CREATE_API_STEP1_DES')?></div><div class="asg-api-step-title-container-line"></div></div>  
  <div class="asg-create-api-step1">
    <div class="row-fluid">
      <div class="span12">
    <?php if($params->get('tmpl_params.tab_main_descr')):?>
        <?php echo $params->get('tmpl_params.tab_main_descr'); ?>
  <?php endif;?>
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
      <?php $description = $this->sorted_fields[0][5]?>
      <div id="fld-<?php echo $description->id;?>" class="control-group odd<?php echo $k = 1 - $k ?> <?php echo 'field-5' ?> <?php echo $description->fieldclass;?>">
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
      <!-- API Type goes here -->
      <?php $api_type = $this->sorted_fields[0][75]?>
      <div id="fld-<?php echo $api_type->id;?>" class="control-group odd<?php echo $k = 1 - $k ?> <?php echo 'field-75' ?> <?php echo $api_type->fieldclass;?>">
        <?php if($api_type->params->get('core.show_lable') == 1 || $api_type->params->get('core.show_lable') == 3):?>
          <label id="lbl-<?php echo $api_type->id;?>" for="field_<?php echo $api_type->id;?>" class="control-label <?php echo $api_type->class;?>" >
            <?php if($api_type->params->get('core.icon') && $params->get('tmpl_core.item_icon_fields')):?>
              <?php echo HTMLFormatHelper::icon($api_type->params->get('core.icon'));  ?>
            <?php endif;?>


            <?php if ($api_type->required): ?>
              <span class="pull-right" rel="tooltip" data-original-title="<?php echo JText::_('CREQUIRED')?>"><?php echo HTMLFormatHelper::icon('asterisk-small.png');  ?></span>
            <?php endif;?>

            <?php if ($api_type->description):?>
              <span class="pull-right" rel="tooltip" style="cursor: help;"  data-original-title="<?php echo htmlentities(($api_type->translateDescription ? JText::_($api_type->description) : $api_type->description), ENT_COMPAT, 'UTF-8');?>">
                <?php echo HTMLFormatHelper::icon('question-small-white.png');  ?>
              </span>
            <?php endif;?>

            <?php echo $api_type->label; ?>

          </label>
          <?php if(in_array($api_type->params->get('core.label_break'), array(1,3))):?>
            <div style="clear: both;"></div>
          <?php endif;?>
        <?php endif;?>

        <div class="controls<?php if(in_array($api_type->params->get('core.label_break'), array(1,3))) echo '-full'; ?><?php echo (in_array($api_type->params->get('core.label_break'), array(1,3)) ? ' line-brk' : NULL) ?><?php echo $api_type->fieldclass  ?>">
          <div id="field-alert-<?php echo $api_type->id?>" class="alert alert-error" style="display:none"></div>
          <?php echo $api_type->result; ?>
        </div>
      </div>

      <!-- API Type goes here -->
      <?php $use_existing_facade = $this->sorted_fields[0][145]?>
      <div id="fld-<?php echo $use_existing_facade->id;?>" class="control-group odd<?php echo $k = 1 - $k ?> <?php echo 'field-145' ?> <?php echo $use_existing_facade->fieldclass;?>">
        <?php if($use_existing_facade->params->get('core.show_lable') == 1 || $use_existing_facade->params->get('core.show_lable') == 3):?>
          <label id="lbl-<?php echo $use_existing_facade->id;?>" for="field_<?php echo $use_existing_facade->id;?>" class="control-label <?php echo $use_existing_facade->class;?>" >
            <?php if($use_existing_facade->params->get('core.icon') && $params->get('tmpl_core.item_icon_fields')):?>
              <?php echo HTMLFormatHelper::icon($use_existing_facade->params->get('core.icon'));  ?>
            <?php endif;?>


            <?php if ($use_existing_facade->required): ?>
              <span class="pull-right" rel="tooltip" data-original-title="<?php echo JText::_('CREQUIRED')?>"><?php echo HTMLFormatHelper::icon('asterisk-small.png');  ?></span>
            <?php endif;?>

            <?php if ($use_existing_facade->description):?>
              <span class="pull-right" rel="tooltip" style="cursor: help;"  data-original-title="<?php echo htmlentities(($use_existing_facade->translateDescription ? JText::_($use_existing_facade->description) : $use_existing_facade->description), ENT_COMPAT, 'UTF-8');?>">
                <?php echo HTMLFormatHelper::icon('question-small-white.png');  ?>
              </span>
            <?php endif;?>

            <?php echo $use_existing_facade->label; ?>

          </label>
          <?php if(in_array($use_existing_facade->params->get('core.label_break'), array(1,3))):?>
            <div style="clear: both;"></div>
          <?php endif;?>
        <?php endif;?>

        <div class="controls<?php if(in_array($use_existing_facade->params->get('core.label_break'), array(1,3))) echo '-full'; ?><?php echo (in_array($use_existing_facade->params->get('core.label_break'), array(1,3)) ? ' line-brk' : NULL) ?><?php echo $use_existing_facade->fieldclass  ?>">
          <div id="field-alert-<?php echo $use_existing_facade->id?>" class="alert alert-error" style="display:none"></div>
          <?php echo $use_existing_facade->result; ?>
        </div>
      </div>

      <!-- Contact email goes here -->
      <?php $contact_email = $this->sorted_fields[0][21]?>
      <div id="fld-<?php echo $contact_email->id;?>" class="control-group odd<?php echo $k = 1 - $k ?> <?php echo 'field-21' ?> <?php echo $contact_email->fieldclass;?>">
        <?php if($contact_email->params->get('core.show_lable') == 1 || $contact_email->params->get('core.show_lable') == 3):?>
          <label id="lbl-<?php echo $contact_email->id;?>" for="field_<?php echo $contact_email->id;?>" class="control-label <?php echo $contact_email->class;?>" >
            <?php if($contact_email->params->get('core.icon') && $params->get('tmpl_core.item_icon_fields')):?>
              <?php echo HTMLFormatHelper::icon($contact_email->params->get('core.icon'));  ?>
            <?php endif;?>


            <?php if ($contact_email->required): ?>
              <span class="pull-right" rel="tooltip" data-original-title="<?php echo JText::_('CREQUIRED')?>"><?php echo HTMLFormatHelper::icon('asterisk-small.png');  ?></span>
            <?php endif;?>

            <?php if ($contact_email->description):?>
              <span class="pull-right" rel="tooltip" style="cursor: help;"  data-original-title="<?php echo htmlentities(($contact_email->translateDescription ? JText::_($contact_email->description) : $contact_email->description), ENT_COMPAT, 'UTF-8');?>">
                <?php echo HTMLFormatHelper::icon('question-small-white.png');  ?>
              </span>
            <?php endif;?>

            <?php echo $contact_email->label; ?>

          </label>
          <?php if(in_array($contact_email->params->get('core.label_break'), array(1,3))):?>
            <div style="clear: both;"></div>
          <?php endif;?>
        <?php endif;?>

        <div class="controls<?php if(in_array($contact_email->params->get('core.label_break'), array(1,3))) echo '-full'; ?><?php echo (in_array($contact_email->params->get('core.label_break'), array(1,3)) ? ' line-brk' : NULL) ?><?php echo $contact_email->fieldclass  ?>">
          <div id="field-alert-<?php echo $contact_email->id?>" class="alert alert-error" style="display:none"></div>
          <?php echo $contact_email->result; ?>
        </div>
      </div>
      <!-- Owner's Organization goes here -->
      <?php $organization = $this->sorted_fields[0][40]?>
      <div id="fld-<?php echo $organization->id;?>" class="control-group odd<?php echo $k = 1 - $k ?> <?php echo 'field-40' ?> <?php echo $organization->fieldclass;?>">
        <?php if($organization->params->get('core.show_lable') == 1 || $organization->params->get('core.show_lable') == 3):?>
          <label id="lbl-<?php echo $organization->id;?>" for="field_<?php echo $organization->id;?>" class="control-label <?php echo $organization->class;?>" >
            <?php if($organization->params->get('core.icon') && $params->get('tmpl_core.item_icon_fields')):?>
              <?php echo HTMLFormatHelper::icon($organization->params->get('core.icon'));  ?>
            <?php endif;?>


            <?php if ($organization->required): ?>
              <span class="pull-right" rel="tooltip" data-original-title="<?php echo JText::_('CREQUIRED')?>"><?php echo HTMLFormatHelper::icon('asterisk-small.png');  ?></span>
            <?php endif;?>

            <?php if ($organization->description):?>
              <span class="pull-right" rel="tooltip" style="cursor: help;"  data-original-title="<?php echo htmlentities(($organization->translateDescription ? JText::_($organization->description) : $organization->description), ENT_COMPAT, 'UTF-8');?>">
                <?php echo HTMLFormatHelper::icon('question-small-white.png');  ?>
              </span>
            <?php endif;?>

            <?php echo $organization->label; ?>

          </label>
          <?php if(in_array($organization->params->get('core.label_break'), array(1,3))):?>
            <div style="clear: both;"></div>
          <?php endif;?>
        <?php endif;?>

        <div class="controls<?php if(in_array($organization->params->get('core.label_break'), array(1,3))) echo '-full'; ?><?php echo (in_array($organization->params->get('core.label_break'), array(1,3)) ? ' line-brk' : NULL) ?><?php echo $organization->fieldclass  ?>">
          <div id="field-alert-<?php echo $organization->id?>" class="alert alert-error" style="display:none"></div>
          <?php echo $organization->result; ?>
        </div>
      </div>
      <!-- Contained in Products goes here -->
      <?php $contained = $this->sorted_fields[0][6]?>
      <div id="fld-<?php echo $contained->id;?>" class="control-group odd<?php echo $k = 1 - $k ?> <?php echo 'field-6' ?> <?php echo $contained->fieldclass;?>">
        <?php if($contained->params->get('core.show_lable') == 1 || $contained->params->get('core.show_lable') == 3):?>
          <label id="lbl-<?php echo $contained->id;?>" for="field_<?php echo $contained->id;?>" class="control-label <?php echo $contained->class;?>" >
            <?php if($contained->params->get('core.icon') && $params->get('tmpl_core.item_icon_fields')):?>
              <?php echo HTMLFormatHelper::icon($contained->params->get('core.icon'));  ?>
            <?php endif;?>


            <?php if ($contained->required): ?>
              <span class="pull-right" rel="tooltip" data-original-title="<?php echo JText::_('CREQUIRED')?>"><?php echo HTMLFormatHelper::icon('asterisk-small.png');  ?></span>
            <?php endif;?>

            <?php if ($contained->description):?>
              <span class="pull-right" rel="tooltip" style="cursor: help;"  data-original-title="<?php echo htmlentities(($contained->translateDescription ? JText::_($contained->description) : $contained->description), ENT_COMPAT, 'UTF-8');?>">
                <?php echo HTMLFormatHelper::icon('question-small-white.png');  ?>
              </span>
            <?php endif;?>

            <?php echo $contained->label; ?>

          </label>
          <?php if(in_array($contained->params->get('core.label_break'), array(1,3))):?>
            <div style="clear: both;"></div>
          <?php endif;?>
        <?php endif;?>

        <div class="controls<?php if(in_array($contained->params->get('core.label_break'), array(1,3))) echo '-full'; ?><?php echo (in_array($contained->params->get('core.label_break'), array(1,3)) ? ' line-brk' : NULL) ?><?php echo $contained->fieldclass  ?>">
          <div id="field-alert-<?php echo $contained->id?>" class="alert alert-error" style="display:none"></div>
          <?php echo $contained->result; ?>
        </div>
      </div>
      <!-- Operations goes here -->
      <?php $operations = $this->sorted_fields[0][31]?>
      <div id="fld-<?php echo $operations->id;?>" class="control-group odd<?php echo $k = 1 - $k ?> <?php echo 'field-31' ?> <?php echo $operations->fieldclass;?>">
        <?php if($operations->params->get('core.show_lable') == 1 || $operations->params->get('core.show_lable') == 3):?>
          <label id="lbl-<?php echo $operations->id;?>" for="field_<?php echo $operations->id;?>" class="control-label <?php echo $operations->class;?>" >
            <?php if($operations->params->get('core.icon') && $params->get('tmpl_core.item_icon_fields')):?>
              <?php echo HTMLFormatHelper::icon($operations->params->get('core.icon'));  ?>
            <?php endif;?>


            <?php if ($operations->required): ?>
              <span class="pull-right" rel="tooltip" data-original-title="<?php echo JText::_('CREQUIRED')?>"><?php echo HTMLFormatHelper::icon('asterisk-small.png');  ?></span>
            <?php endif;?>

            <?php if ($operations->description):?>
              <span class="pull-right" rel="tooltip" style="cursor: help;"  data-original-title="<?php echo htmlentities(($operations->translateDescription ? JText::_($operations->description) : $operations->description), ENT_COMPAT, 'UTF-8');?>">
                <?php echo HTMLFormatHelper::icon('question-small-white.png');  ?>
              </span>
            <?php endif;?>

            <?php echo $operations->label; ?>

          </label>
          <?php if(in_array($operations->params->get('core.label_break'), array(1,3))):?>
            <div style="clear: both;"></div>
          <?php endif;?>
        <?php endif;?>

        <div class="controls<?php if(in_array($operations->params->get('core.label_break'), array(1,3))) echo '-full'; ?><?php echo (in_array($operations->params->get('core.label_break'), array(1,3)) ? ' line-brk' : NULL) ?><?php echo $operations->fieldclass  ?>">
          <div id="field-alert-<?php echo $operations->id?>" class="alert alert-error" style="display:none"></div>
          <?php echo $operations->result; ?>
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
    </div>
  </div>
  <div class="asg-api-step-title-container"><div class="asg-api-step-title"><i class="icon-chevron-down"></i><?php echo JText::_('CREATE_API_STEP2_DES')?></div><div class="asg-api-step-title-container-line"></div></div>  
  <div class="asg-create-api-step2">
    <div class="row-fluid">
      <div class="span12">
      <!-- Upload REST API Spec goes here -->
      <?php $upload_api_spec = $this->sorted_fields[0][23]?>
      <div id="fld-<?php echo $upload_api_spec->id;?>" class="control-group odd<?php echo $k = 1 - $k ?> <?php echo 'field-23' ?> <?php echo $upload_api_spec->fieldclass;?>">
        <?php if($upload_api_spec->params->get('core.show_lable') == 1 || $upload_api_spec->params->get('core.show_lable') == 3):?>
          <label id="lbl-<?php echo $upload_api_spec->id;?>" for="field_<?php echo $upload_api_spec->id;?>" class="control-label <?php echo $upload_api_spec->class;?>" >
            <?php if($upload_api_spec->params->get('core.icon') && $params->get('tmpl_core.item_icon_fields')):?>
              <?php echo HTMLFormatHelper::icon($upload_api_spec->params->get('core.icon'));  ?>
            <?php endif;?>


            <?php if ($upload_api_spec->required): ?>
              <span class="pull-right" rel="tooltip" data-original-title="<?php echo JText::_('CREQUIRED')?>"><?php echo HTMLFormatHelper::icon('asterisk-small.png');  ?></span>
            <?php endif;?>

            <?php if ($upload_api_spec->description):?>
              <span class="pull-right" rel="tooltip" style="cursor: help;"  data-original-title="<?php echo htmlentities(($upload_api_spec->translateDescription ? JText::_($upload_api_spec->description) : $upload_api_spec->description), ENT_COMPAT, 'UTF-8');?>">
                <?php echo HTMLFormatHelper::icon('question-small-white.png');  ?>
              </span>
            <?php endif;?>

            <?php echo $upload_api_spec->label; ?>

          </label>
          <?php if(in_array($upload_api_spec->params->get('core.label_break'), array(1,3))):?>
            <div style="clear: both;"></div>
          <?php endif;?>
        <?php endif;?>

        <div class="controls<?php if(in_array($upload_api_spec->params->get('core.label_break'), array(1,3))) echo '-full'; ?><?php echo (in_array($upload_api_spec->params->get('core.label_break'), array(1,3)) ? ' line-brk' : NULL) ?><?php echo $upload_api_spec->fieldclass  ?>">
          <div id="field-alert-<?php echo $upload_api_spec->id?>" class="alert alert-error" style="display:none"></div>
          <?php echo $upload_api_spec->result; ?>
        </div>
      </div>
          <!-- Upload REST API Spec goes here -->
          <?php $upload_wsdl_spec = $this->sorted_fields[0][127] ?>
          <div id="fld-<?php echo $upload_wsdl_spec->id;?>" class="control-group odd<?php echo $k = 1 - $k ?> <?php echo 'field-127' ?> <?php echo $upload_wsdl_spec->fieldclass;?>">
            <?php if($upload_wsdl_spec->params->get('core.show_lable') == 1 || $upload_wsdl_spec->params->get('core.show_lable') == 3):?>
              <label id="lbl-<?php echo $upload_wsdl_spec->id;?>" for="field_<?php echo $upload_wsdl_spec->id;?>" class="control-label <?php echo $upload_wsdl_spec->class;?>" >
                <?php if($upload_wsdl_spec->params->get('core.icon') && $params->get('tmpl_core.item_icon_fields')):?>
                  <?php echo HTMLFormatHelper::icon($upload_wsdl_spec->params->get('core.icon'));  ?>
                <?php endif;?>


                <?php if ($upload_wsdl_spec->required): ?>
                  <span class="pull-right" rel="tooltip" data-original-title="<?php echo JText::_('CREQUIRED')?>"><?php echo HTMLFormatHelper::icon('asterisk-small.png');  ?></span>
                <?php endif;?>

                <?php if ($upload_wsdl_spec->description):?>
                  <span class="pull-right" rel="tooltip" style="cursor: help;"  data-original-title="<?php echo htmlentities(($upload_wsdl_spec->translateDescription ? JText::_($upload_wsdl_spec->description) : $upload_wsdl_spec->description), ENT_COMPAT, 'UTF-8');?>">
                    <?php echo HTMLFormatHelper::icon('question-small-white.png');  ?>
                  </span>
                <?php endif;?>

                <?php echo $upload_wsdl_spec->label; ?>

              </label>
              <?php if(in_array($upload_wsdl_spec->params->get('core.label_break'), array(1,3))):?>
                <div style="clear: both;"></div>
              <?php endif;?>
            <?php endif;?>

            <div class="controls<?php if(in_array($upload_wsdl_spec->params->get('core.label_break'), array(1,3))) echo '-full'; ?><?php echo (in_array($upload_wsdl_spec->params->get('core.label_break'), array(1,3)) ? ' line-brk' : NULL) ?><?php echo $upload_wsdl_spec->fieldclass  ?>">
              <div id="field-alert-<?php echo $upload_wsdl_spec->id?>" class="alert alert-error" style="display:none"></div>
              <?php echo $upload_wsdl_spec->result; ?>
            </div>
          </div>
      
      
      <!-- Environments Path goes here -->
      <?php $environments = $this->sorted_fields[0][26]?>
      <div id="fld-<?php echo $environments->id;?>" class="control-group odd<?php echo $k = 1 - $k ?> <?php echo 'field-26' ?> <?php echo $environments->fieldclass;?>">
        <?php if($environments->params->get('core.show_lable') == 1 || $environments->params->get('core.show_lable') == 3):?>
          <label id="lbl-<?php echo $environments->id;?>" for="field_<?php echo $environments->id;?>" class="control-label <?php echo $environments->class;?>" >
            <?php if($environments->params->get('core.icon') && $params->get('tmpl_core.item_icon_fields')):?>
              <?php echo HTMLFormatHelper::icon($environments->params->get('core.icon'));  ?>
            <?php endif;?>


            <?php if ($environments->required): ?>
              <span class="pull-right" rel="tooltip" data-original-title="<?php echo JText::_('CREQUIRED')?>"><?php echo HTMLFormatHelper::icon('asterisk-small.png');  ?></span>
            <?php endif;?>

            <?php if ($environments->description):?>
              <span class="pull-right" rel="tooltip" style="cursor: help;"  data-original-title="<?php echo htmlentities(($environments->translateDescription ? JText::_($environments->description) : $environments->description), ENT_COMPAT, 'UTF-8');?>">
                <?php echo HTMLFormatHelper::icon('question-small-white.png');  ?>
              </span>
            <?php endif;?>

            <?php echo $environments->label; ?>

          </label>
          <?php if(in_array($environments->params->get('core.label_break'), array(1,3))):?>
            <div style="clear: both;"></div>
          <?php endif;?>
        <?php endif;?>

        <div class="controls<?php if(in_array($environments->params->get('core.label_break'), array(1,3))) echo '-full'; ?><?php echo (in_array($environments->params->get('core.label_break'), array(1,3)) ? ' line-brk' : NULL) ?><?php echo $environments->fieldclass  ?>">
          <div id="field-alert-<?php echo $environments->id?>" class="alert alert-error" style="display:none"></div>
          <?php echo $environments->result; ?>
        </div>
      </div>


      <!-- Target Environments Path goes here -->
       <input id="sorted_value" type="hidden" value="<?php echo $this->sorted_fields[0][145]->value; ?>">
      <?php $target_environments = $this->sorted_fields[0][147]?>
      <div id="fld-<?php echo $target_environments->id;?>" class="control-group odd<?php echo $k = 1 - $k ?> <?php echo 'field-26' ?> <?php echo $target_environments->fieldclass;?>">
        <?php if($target_environments->params->get('core.show_lable') == 1 || $target_environments->params->get('core.show_lable') == 3):?>
          <label id="lbl-<?php echo $target_environments->id;?>" for="field_<?php echo $target_environments->id;?>" class="control-label <?php echo $target_environments->class;?>" >
            <?php if($target_environments->params->get('core.icon') && $params->get('tmpl_core.item_icon_fields')):?>
              <?php echo HTMLFormatHelper::icon($target_environments->params->get('core.icon'));  ?>
            <?php endif;?>


            <?php if ($target_environments->required): ?>
              <span class="pull-right" rel="tooltip" data-original-title="<?php echo JText::_('CREQUIRED')?>"><?php echo HTMLFormatHelper::icon('asterisk-small.png');  ?></span>
            <?php endif;?>

            <?php if ($target_environments->description):?>
              <span class="pull-right" rel="tooltip" style="cursor: help;"  data-original-title="<?php echo htmlentities(($target_environments->translateDescription ? JText::_($target_environments->description) : $target_environments->description), ENT_COMPAT, 'UTF-8');?>">
                <?php echo HTMLFormatHelper::icon('question-small-white.png');  ?>
              </span>
            <?php endif;?>

            <?php echo $target_environments->label; ?>

          </label>
          <?php if(in_array($target_environments->params->get('core.label_break'), array(1,3))):?>
            <div style="clear: both;"></div>
          <?php endif;?>
        <?php endif;?>

        <div class="controls<?php if(in_array($target_environments->params->get('core.label_break'), array(1,3))) echo '-full'; ?><?php echo (in_array($target_environments->params->get('core.label_break'), array(1,3)) ? ' line-brk' : NULL) ?><?php echo $target_environments->fieldclass  ?>">
          <div id="field-alert-<?php echo $target_environments->id?>" class="alert alert-error" style="display:none"></div>
          <?php echo $target_environments->result; ?>
        </div>
      </div>

      </div>
    </div>
  </div>
  <div class="asg-api-step-title-container"><div class="asg-api-step-title"><i class="icon-chevron-down"></i><?php echo JText::_('CREATE_API_STEP3_DES')?></div><div class="asg-api-step-title-container-line"></div></div>  
  <div class="asg-create-api-step3">
    <div class="row-fluid">
      <div class="span12">
      <!-- Attached documentation file goes here -->
      <?php $doc_file = $this->sorted_fields[0][24]?>
      <div id="fld-<?php echo $doc_file->id;?>" class="control-group odd<?php echo $k = 1 - $k ?> <?php echo 'field-24' ?> <?php echo $doc_file->fieldclass;?>">
        <?php if($doc_file->params->get('core.show_lable') == 1 || $doc_file->params->get('core.show_lable') == 3):?>
          <label id="lbl-<?php echo $doc_file->id;?>" for="field_<?php echo $doc_file->id;?>" class="control-label <?php echo $doc_file->class;?>" >
            <?php if($doc_file->params->get('core.icon') && $params->get('tmpl_core.item_icon_fields')):?>
              <?php echo HTMLFormatHelper::icon($doc_file->params->get('core.icon'));  ?>
            <?php endif;?>


            <?php if ($doc_file->required): ?>
              <span class="pull-right" rel="tooltip" data-original-title="<?php echo JText::_('CREQUIRED')?>"><?php echo HTMLFormatHelper::icon('asterisk-small.png');  ?></span>
            <?php endif;?>

            <?php if ($doc_file->description):?>
              <span class="pull-right" rel="tooltip" style="cursor: help;"  data-original-title="<?php echo htmlentities(($doc_file->translateDescription ? JText::_($doc_file->description) : $doc_file->description), ENT_COMPAT, 'UTF-8');?>">
                <?php echo HTMLFormatHelper::icon('question-small-white.png');  ?>
              </span>
            <?php endif;?>

            <?php echo $doc_file->label; ?>

          </label>
          <?php if(in_array($doc_file->params->get('core.label_break'), array(1,3))):?>
            <div style="clear: both;"></div>
          <?php endif;?>
        <?php endif;?>

        <div class="controls<?php if(in_array($doc_file->params->get('core.label_break'), array(1,3))) echo '-full'; ?><?php echo (in_array($doc_file->params->get('core.label_break'), array(1,3)) ? ' line-brk' : NULL) ?><?php echo $doc_file->fieldclass  ?>">
          <div id="field-alert-<?php echo $doc_file->id?>" class="alert alert-error" style="display:none"></div>
          <?php echo $doc_file->result; ?>
        </div>
      </div>
      <!-- Inline Documentation goes here -->
      <?php $inline_doc = $this->sorted_fields[0][44]?>
      <div id="fld-<?php echo $inline_doc->id;?>" class="control-group odd<?php echo $k = 1 - $k ?> <?php echo 'field-44' ?> <?php echo $inline_doc->fieldclass;?>">
        <?php if($inline_doc->params->get('core.show_lable') == 1 || $inline_doc->params->get('core.show_lable') == 3):?>
          <label id="lbl-<?php echo $inline_doc->id;?>" for="field_<?php echo $inline_doc->id;?>" class="control-label <?php echo $inline_doc->class;?>" >
            <?php if($inline_doc->params->get('core.icon') && $params->get('tmpl_core.item_icon_fields')):?>
              <?php echo HTMLFormatHelper::icon($inline_doc->params->get('core.icon'));  ?>
            <?php endif;?>


            <?php if ($inline_doc->required): ?>
              <span class="pull-right" rel="tooltip" data-original-title="<?php echo JText::_('CREQUIRED')?>"><?php echo HTMLFormatHelper::icon('asterisk-small.png');  ?></span>
            <?php endif;?>

            <?php if ($inline_doc->description):?>
              <span class="pull-right" rel="tooltip" style="cursor: help;"  data-original-title="<?php echo htmlentities(($inline_doc->translateDescription ? JText::_($inline_doc->description) : $inline_doc->description), ENT_COMPAT, 'UTF-8');?>">
                <?php echo HTMLFormatHelper::icon('question-small-white.png');  ?>
              </span>
            <?php endif;?>

            <?php echo $inline_doc->label; ?>

          </label>
          <?php if(in_array($inline_doc->params->get('core.label_break'), array(1,3))):?>
            <div style="clear: both;"></div>
          <?php endif;?>
        <?php endif;?>

        <div class="controls<?php if(in_array($inline_doc->params->get('core.label_break'), array(1,3))) echo '-full'; ?><?php echo (in_array($inline_doc->params->get('core.label_break'), array(1,3)) ? ' line-brk' : NULL) ?><?php echo $inline_doc->fieldclass  ?>">
          <div id="field-alert-<?php echo $inline_doc->id?>" class="alert alert-error" style="display:none"></div>
          <?php echo $inline_doc->result; ?>
        </div>
      </div>
      </div>
    </div>
  </div>
  <?php unset($this->sorted_fields[0]);?>
  <?php group_end($this);?>

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
  (function($) {
    $('.asg-api-step-title-container .asg-api-step-title').on('click', '.icon-chevron-down', function() {
      $(this).removeClass('icon-chevron-down').addClass('icon-chevron-right');
      $(this).parent().parent().next().hide();
    });
    $('.asg-api-step-title-container .asg-api-step-title').on('click', '.icon-chevron-right', function() {
      $(this).removeClass('icon-chevron-right').addClass('icon-chevron-down');
      $(this).parent().parent().next().show();
    });
  }(jQuery));
  
  (function($){
    var specData, fileName,realName, deleted_operation_ids=[],operations_doc1,operations_doc2,is_deleted_operations=false;
    var originSpecName = "<?php echo $this->fields[23]->value[0]['realname'];?>";
    var originalName = '';
    var originalEnvironments = [];
    var originalTargetEnvironments = [];
    var originalCreateProxy = '';
    var currentName = '';
    var currentEnvironments = [];
    var currentTargetEnvironments = [];
    var currentCreateProxy = '';
    var sRecourcePath = '';
    var sAPIType = '';
    var operationsCount   = <?php echo count($old_operations_of_api); ?>;

    $(function(){
        $('.asg-api-step-title-container .asg-api-step-title').on('click', '.icon-chevron-down', function() {
            $(this).removeClass('icon-chevron-down').addClass('icon-chevron-right');
            $(this).parent().parent().next().hide();
        });
        $('.asg-api-step-title-container .asg-api-step-title').on('click', '.icon-chevron-right', function() {
            $(this).removeClass('icon-chevron-right').addClass('icon-chevron-down');
            $(this).parent().parent().next().show();
        });
        operations_doc1 = $('#fld-23 .filename').eq(1).text();
        originalName = $('input[name="jform[title]"]').val();
        originalEnvironments = getEnvironments();
        sRecourcePath = $('input[name="jform[fields][22]"]').val();
        sAPIType = $('select[name="jform[fields][75]"]').val();
        originalTargetEnvironments = getTargetEnvironments();
        originalCreateProxy = $('input:checked[name="jform[fields][145]"]').val();
        sorted_value = $('#sorted_value').val();

        $('#fld-145 button').on('click', function(){
            if(/^bool-n/.test(this.id)) {
              $("#fld-146, #fld-147").hide();
              $('#fld-146 #field_146').val('');
              $('#fld-147 #parent_list147 > div').remove();
            } else {
              $("#fld-146, #fld-147").show();
            }
        });
        
          <?php if(!$this->item->id):?>
            (function initForm(){
              $("#bool-y145").addClass("active btn-success").prev("input").attr("checked", true);
            }());
          <?php else:?>
            if(sorted_value == -1) {
              $("#fld-146, #fld-147").hide();
            } else if(sorted_value == 1){
              $("#fld-146, #fld-147").show();
            }
          <?php endif;?>
    });

    function getEnvironments(){
        return _getSelectedItems('parent_list26');
    }

    function getTargetEnvironments(){
        return _getSelectedItems('parent_list147');
    }

    function _getSelectedItems(id) {
        var envWrap = $("#" + id),
        	targetArray = [];              
        envWrap.find(".list-item").each(function(i,ele){
        targetArray.push($(ele).attr("rel"));
        });

        targetArray = targetArray.sort();
        envWrap.attr("original",targetArray);
        return targetArray.sort();
    }

    Joomla.beforesubmitform = function(fCallback, fErrorback) {
      fileName = $('input[name="jform[fields][23][]"]').val();
      operations_doc2 = $('#fld-23 .filename').eq(1).text();
      realName =  jQuery('#fld-23').find('li.mooupload_readonly div.filename').text();
      var flag = true,
          record_id = parseInt($("#jform_id").val());
          currentName = $('input[name="jform[title]"]').val();
          currentEnvironments = getEnvironments(),
          currentTargetEnvironments = getTargetEnvironments(),
          currentCreateProxy = $('input:checked[name="jform[fields][145]"]').val(),
          sNewResourcePath = $('input[name="jform[fields][22]"]').val(),
          sNewAPIType = $('select[name="jform[fields][75]"]').val();

      if(sRecourcePath !== sNewResourcePath || sAPIType !== sNewAPIType) {
        window.oUpdatedFields = window.oUpdatedFields || {};
        if(sRecourcePath !== sNewResourcePath) {
          window.oUpdatedFields[22] = [sRecourcePath];
        }
        if(sAPIType !== sNewAPIType) {
          window.oUpdatedFields[75] = [sAPIType];
        }
      }
      if(currentName !== originalName) {
          window.oUpdatedFields = window.oUpdatedFields || {};
          window.oUpdatedFields['name'] = originalName;
      }
      if(!DeveloperPortal.arrayEqual(originalTargetEnvironments, currentTargetEnvironments)) {
        window.oUpdatedFields = window.oUpdatedFields || {};
        window.oUpdatedFields[147] = originalTargetEnvironments;
      }

      if( originalCreateProxy !== currentCreateProxy) {
        window.oUpdatedFields = window.oUpdatedFields || {};
        window.oUpdatedFields[145] = [originalCreateProxy];
      }
      if(record_id && !DeveloperPortal.arrayEqual(originalEnvironments, currentEnvironments)) {
        var data = {
                "option"      : "com_cobalt",
                "task"        : "ajaxMore.checkEnvironmentsUsedByProduct",
                "origEnvs"    : originalEnvironments.join(),
                "currEnvs"    : currentEnvironments.join(),
                "record_id"   : record_id
            };

            $.ajax({
              url: '',
              data: data,
              type:'post',
              async: false,
              dataType: 'json'
            }).done(function(res){
              if(!res.success)
              {
                flag = false;
                fErrorback(res.error);
              }
            }).fail(function(){
              flag = false;
              fErrorback();
            });
      }

      if(flag){
          if (originSpecName !== "" && realName !== originSpecName) {
            var oldSpecPath = GLOBAL_CONTEXT_PATH+"<?php echo $this->appParams->get('general_upload',1).'/'.$this->fields[23]->params->get('params',1)->subfolder.'/'.$this->fields[23]->value[0]['fullpath'];?>";
            if(operationsCount > 0 && confirm("<?php echo JText::_('REMOVE_ALL_OPERATION')?>")) {
                $.post(
                    GLOBAL_CONTEXT_PATH + "index.php?option=com_cobalt&task=ajaxMore.archiveOperationsInSpec",
                    {'apiID': '<?php echo $this->item->id;?>'},
                    function (data) {
                        if (data.success) {
                            deleted_operation_ids = data.result;
                            is_deleted_operations = true;
                            fCallback();
                        } else {
                            realName = "";
                            Joomla.showError(["Can't auto created operations now."]);
                        }
                        fCallback();
                    },
                    'json'
                ).fail(function () {
                        fCallback();
                    });
            }else{
                fCallback();
            }
          }else{

              fCallback();

          }
      }

    };  
    
    //operations auto-create
    Joomla.submitform = function(task) {
      DeveloperPortal.submitForm(task, 
        function(nObjectId, sRedirectUrl) {
          if (fileName!==undefined && fileName.length>0 && realName !== originSpecName) {
            var rootPath = GLOBAL_CONTEXT_PATH+"<?php echo $this->appParams->get('general_upload',1).'/'.$this->fields[23]->params->get('params',1)->subfolder.'/'.$folder_format.'/';?>";
            $.post(
              rootPath+fileName,
              {},
              function(data){
                specData = (data && data.apis)?data:{};
                if ($('input[name="jform[fields][22]"]').val()==='' && specData.resourcePath.length>0) {
                  $("#adminForm").append('<input type="hidden" name="jform[fields][22]" value="' + specData.resourcePath + '" />');
                }
                createOperations(specData.apis,nObjectId,sRedirectUrl);
              },
              'json'
            ).error(function(){
				DeveloperPortal.storeErrMsgInCookie(["<?php echo JText::_('CREATE_PRODUCT_UPLOAD_SPEC_ERROR')?>"]);
				window.location.href = GLOBAL_CONTEXT_PATH + 'index.php/apis/edit/'+nObjectId;
            });
          }else{
            if(is_deleted_operations){
              DeveloperPortal.sendUpdateNotification(nObjectId,DeveloperPortal.PORTAL_OBJECT_TYPE_API,{'31':[]},
                                                      function(){
                                                        window.location.href = sRedirectUrl;
                                                      },function(){
                                                        window.location.href = sRedirectUrl;
                                                      });
            }else{
              window.location.href = sRedirectUrl;
            }
          }
        }, 
        function(sRedirectUrl) {
          window.location.href = sRedirectUrl;
        }
      );
    };
   
  
    function createOperations(apis, API_ID, redirectURL){
    var counter = 0;
	
	try{
	    for (var i = 0; i < apis.length; i++) {
	      var api = apis[i];
		  var subcounter = 0;
		  for (var j = 0; j < api.operations.length; j++) {
			  var operation = api.operations[j];
			  var title = operation.nickname;
	          var dForm = $('<form id="keyForm" name="keyForm" enctype="multipart/form-data" method="post" style="display:none;"></form>').appendTo('body'),
	              sAction = GLOBAL_CONTEXT_PATH + 'index.php/apis/submit/2-apis/6?fand=' + API_ID + '&field_id=30',
	        restPath = api.path,
	        method = operation.method,
	        description = api.description,
	            tokenInput = $('input[value="1"]')[0],
	              sIFrameId = 'iframe_submission_for_'+title,
	              dIFrame = $('<iframe id="' + sIFrameId + j + '" name="' + sIFrameId + '"  style="display:none;" />').appendTo('body'),
	              dWindow, sRedirectUrl;
				  
			  if (operation.summary!=null && operation.summary.length>0) {
			  	description += ':'+ operation.summary;
			  }  
                  if ( operation.httpMethod && !method ) {
                      method = operation.httpMethod;
                  }
	          dForm.attr('action', sAction);
	          dForm.attr('target', sIFrameId);
	          dForm.append(
				  '<input type="hidden" name="task" value="form.save" />'+
				  '<input type="hidden" name="' + tokenInput.name + '" value="1" />'+
				  '<input type="hidden" name="jform[title]" value="' + title +'" />'+
				  '<input type="hidden" name="jform[fields][27]" value="' + description + '" />'+
				  '<input type="hidden" name="jform[fields][28]" value="' + restPath + '" />'+
				  '<input type="hidden" name="jform[fields][29]" value="' + method + '" />'+
				  '<input type="hidden" name="jform[fields][30]" value="' + API_ID + '" />'+
				  '<input type="hidden" name="jform[fields][149]" value="' + restPath + '" />'+
				  '<input type="hidden" name="jform[fields][108]" value="' + UUID.generate() + '" />'+
				  '<input type="hidden" name="jform[ucatid]" value="0" />'+
				  '<input type="hidden" name="jform[id]" value="0" />'+
				  '<input type="hidden" name="jform[section_id]" value="2" />'+
				  '<input type="hidden" name="jform[type_id]" value="6" />'+
				  '<input type="hidden" name="jform[published]" value="1" />'
			  );

	          dIFrame.on('load', function(oEvent) {
	              dWindow = dIFrame[0].contentWindow;
	              sRedirectUrl = dWindow.location.href;

	              if(dWindow.location.href == window.location.href) {
	                  var sErrMsg = 'Operation:' + ' of API ' + API_ID + ' is not successfully stored in the database.';
	                  DeveloperPortal.storeErrMsgInCookie(GENERIC_ERROR_MESSAGE);
	                  if ( typeof fErrorback === 'function') {
	                      fErrorback([sErrMsg, GENERIC_ERROR_MESSAGE].join('<br />'));
	                  }
	              }
				  
				  subcounter++;
		          if (subcounter == api.operations.length) {
		          	counter++;
					subcounter = 0;
		          }
				  
		          if(counter == apis.length){
		            DeveloperPortal.sendUpdateNotification(API_ID,DeveloperPortal.PORTAL_OBJECT_TYPE_API,{'31':[]},
		                                                    function(){
		                                                      window.location.href = redirectURL;
		                                                    },function(){
		                                                      window.location.href = redirectURL;
		                                                    });
		          }
	          });
          
	          dForm.submit();
		  }
		  
	        
      
	    }
	}catch(e){
		DeveloperPortal.storeErrMsgInCookie(["<?php echo JText::_('CREATE_PRODUCT_UPLOAD_SPEC_ERROR')?>"]);
		window.location.href = GLOBAL_CONTEXT_PATH + 'index.php/apis/edit/'+API_ID;
	}
  }
  }(jQuery));
  
  function scrollToAttach(){
	jQuery('html, body').animate({
	         scrollTop: jQuery("#lbl-23").offset().top
	     }, 300);
  }
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
