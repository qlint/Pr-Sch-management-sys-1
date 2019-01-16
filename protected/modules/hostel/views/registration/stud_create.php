<?php $this->renderPartial('/settings/studentleft');?>

<div class="pageheader">
  <div class="col-lg-8">
    <h2><i class="fa fa-group"></i><?php echo Yii::t('app','Hostel');?><span><?php echo Yii::t('app','View hostel');?> </span></h2>
  </div>
  <div class="col-lg-2"> </div>
  <div class="breadcrumb-wrapper"> <span class="label"><?php echo Yii::t('app','You are here:');?></span>
    <ol class="breadcrumb">
      <li class="active"><?php echo Yii::t('app','hostel');?></li>
    </ol>
  </div>
  <div class="clearfix"></div>
</div>
<div class="contentpanel">
  <div class="panel panel-default">
    <div class="panel-heading"> 
      <!-- panel-btns -->
      <h3 class="panel-title"><?php echo Yii::t('app','Registration');?></h3>
    </div>
  
        <?php echo $this->renderPartial('_form1', array('model'=>$model)); ?>
        <!-- END div class="profile_details" -->
        <div class="clear"></div>
      </div>
      <!-- END div id="parent_rightSect" -->
      <div class="clear"></div>
    </div>

<!-- END div id="parent_Sect" -->
	