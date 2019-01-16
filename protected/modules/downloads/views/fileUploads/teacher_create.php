<!--<style>
.mailbox-menu-newup{
	-moz-box-shadow:inset 0px 0px 0px 0px #ffffff !important;
	-webkit-box-shadow:inset 0px 0px 0px 0px #ffffff !important ;
	box-shadow:inset 0px 0px 0px 0px #ffffff !important;
	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #1bb4fa), color-stop(1, #0994f0) ) !important;
	background:-moz-linear-gradient( center top, #1bb4fa 5%, #0994f0 100% ) !important;
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#1bb4fa', endColorstr='#0994f0') !important;
	background-color:#1bb4fa !important;
	-moz-border-radius:3px !important;
	-webkit-border-radius:3px !important;
	border-radius:3px !important;
	border:1px solid #0c93d1 !important;
	display:inline-block;
	color:#ffffff !important;
	font-family:arial;
	font-size:12px;
	font-weight:bold;
	padding:8px 14px !important;
	text-decoration:none;
	margin:0px 10px;
	
	/*text-shadow:1px 0px 0px #0664a3;*/
}
.mailbox-menu-newup a{color:#fff !important; text-decoration:none !important; display:block;}

.mailbox-message-subject{
	padding:10px;
}

.mailbox-menu-mangeup{
	-moz-box-shadow:inset 0px 0px 0px 0px #ffffff !important;
	-webkit-box-shadow:inset 0px 0px 0px 0px #ffffff !important ;
	box-shadow:inset 0px 0px 0px 0px #ffffff !important;
	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #1bb4fa), color-stop(1, #0994f0) ) !important;
	background:-moz-linear-gradient( center top, #1bb4fa 5%, #0994f0 100% ) !important;
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#1bb4fa', endColorstr='#0994f0') !important;
	background-color:#1bb4fa !important;
	-moz-border-radius:3px !important;
	-webkit-border-radius:3px !important;
	border-radius:3px !important;
	border:1px solid #0c93d1 !important;
	display:inline-block;
	color:#ffffff !important;
	font-family:arial;
	font-size:12px;
	font-weight:bold;
	padding:8px 14px !important;
	text-decoration:none;

	/*text-shadow:1px 0px 0px #0664a3;*/
}
.mailbox-menu-mangeup a{color:#fff !important; text-decoration:none !important; display:block;}


</style>-->

 <?php $this->renderPartial('/default/teacherleft');?>
<div class="pageheader">
      <h2><i class="fa fa-download"></i><?php echo Yii::t('app','Downloads') .'<span>'.Yii::t('app','Downloads here').'</span>';?></h2>
      <div class="breadcrumb-wrapper">
        <span class="label"><?php echo Yii::t('app','You are here:');?></span>
        <ol class="breadcrumb">
          <li class="active"><?php echo Yii::t('app','New Uploads');?></li>
        </ol>
      </div>
</div>

<div class="contentpanel">
	<div class="panel-heading" style="position:relative;">
<?php
$this->breadcrumbs=array(
	Yii::t('app','File Uploads')=>array('index'),
	Yii::t('app','Create'),
);
?>



     <h3 class="panel-title">
    <?php echo Yii::t('app','Create File Uploads'); ?></h3>
    
</div>

<div class="people-item">

    <div class="opnsl_headerBox">
    <div class="opnsl_actn_box"> </div>
        <div class="opnsl_actn_box">
            <div class="opnsl_actn_box1">
				<?php 
                echo CHtml::link(Yii::t('app','All Uploads'),array('index'),array('class'=>'btn btn-primary'));
                ?>
            </div>
            <div class="opnsl_actn_box1">    
				<?php
                echo CHtml::link(Yii::t('app','Manage Uploads'),array('admin'),array('class'=>'btn btn-primary'));
                ?>
            </div>
        </div>
            </div>    
					 <?php echo $this->renderPartial('/fileUploads/teacher_form', array('model'=>$model)); ?>

</div>
</div>