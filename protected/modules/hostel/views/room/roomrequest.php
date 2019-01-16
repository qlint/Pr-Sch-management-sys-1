<?php
$this->breadcrumbs=array(
	Yii::t('app','Rooms')=>array('/hostel'),
	Yii::t('app','Change'),
);
?>
<?php $this->renderPartial('/settings/studentleft');?>

<div class="pageheader">
  <div class="col-lg-8">
    <h2><i class="fa fa-group"></i><?php echo Yii::t('app','Hostel');?><span><?php echo Yii::t('app','View hostel');?> </span></h2>
  </div>
  <div class="col-lg-2"> </div>
  <div class="breadcrumb-wrapper"> <span class="label"><?php echo Yii::t('app','You are here').':';?></span>
    <ol class="breadcrumb">
      <li class="active"><?php echo Yii::t('app','Room');?></li>
    </ol>
  </div>
  <div class="clearfix"></div>
</div>
<div class="contentpanel">
  <div class="col-sm-9 col-lg-12">
    <div class="panel-heading"> 
      <!-- panel-btns -->
      <h3 class="panel-title"><?php echo Yii::t('app','Room');?></h3>
    </div>
    <div id="parent_Sect">
      <div id="parent_rightSect">
        <div class="people-item">
          <div class="profile_details">
            <?php
$data=Allotment::model()->findByAttributes(array('student_id'=>$_REQUEST['studentid']));
$student=Students::model()->findByAttributes(array('id'=>$_REQUEST['studentid']));
echo Yii::t('app','Your request for room change has been submitted');
?>
          </div>
        </div>
        <div class="clear"></div>
      </div>
      <div class="clear"></div>
    </div>
  </div>
</div>
