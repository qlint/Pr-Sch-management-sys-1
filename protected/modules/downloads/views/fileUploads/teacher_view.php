<style>
label {margin-right:20px;}
input[type=checkbox].css-checkbox {
	position: absolute; 
	overflow: hidden; 
	clip: rect(0 0 0 0); 
	height:1px; 
	width:1px; 
	margin:-1px; 
	padding:0;
	border:0;
}

input[type=checkbox].css-checkbox + label.css-label {
	/*padding-left:25px;*/
	padding:0px 0px 0px 2px;
	height:18px; 
	display:inline-block;
	line-height:15px;
	background-repeat:no-repeat;
	background-position: 3px 2px;
	font-size:15px;
	vertical-align:middle;
	cursor:pointer;
	color:#4e4e4e;
	display: block;
	/*margin: 12px 15px 12px 0px;*/
	margin:0px 11px;
	font-size:11px;
	font-weight: 600;
	font-family: 'Open Sans', sans-serif;
	text-transform:uppercase;
	
}

input[type=checkbox].css-checkbox:checked + label.css-label {
	background-position: 3px -18px;
}

.css-label{
	background-image: url(images/mail_checkbx_new.png);
}

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


		
</style>

<?php $this->renderPartial('/default/left_side');?>
<div class="pageheader">
      <h2><i class="fa fa-arrow-circle-o-down"></i> <?php echo Yii::t('app','Downloads').' <span>'.Yii::t('app','Downloads here').'</span>';?></h2>
      <div class="breadcrumb-wrapper">
        <span class="label"></span><?php echo Yii::t('app','You are here:');?></span>
        <ol class="breadcrumb">
          <li class="active"><?php echo Yii::t('app','Downloads');?></li>
        </ol>
      </div>
</div>

<div class="contentpanel">
	<div class="panel-heading" style="position:relative;">
    <?php
$this->breadcrumbs=array(
	Yii::t('app','File Uploads')=>array('index'),
	$model->title,
);


?>

     <h3 class="panel-title">
    <?php echo Yii::t('app','View Uploaded File'); ?></h3>
    
</div>
<div class="people-item">

                    <div class="opnsl_headerBox">
                    <div class="opnsl_actn_box"> </div>
                        <div class="opnsl_actn_box">
                            <div class="opnsl_actn_box1">	<?php 
	 echo CHtml::link(Yii::t('app','All Uploads'),array('index'),array('class'=>'btn btn-success')); ?></div>
                            <div class="opnsl_actn_box2"><?php 	 echo CHtml::link(Yii::t('app','Manage Uploads'),array('admin'),array('class'=>'btn btn-primary'));
	 ?></div>
                               	</div>
                       		 	
                   	 </div>
            <div class="table-responsive">
 
	
    
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-hidaction table-hover mb30">
  <tr>
    <th width="10%"><?php echo Yii::t('app','Title'); ?></th>
    <th width="10%"><?php echo Yii::t('app','Category'); ?></th>
    <th width="10%"><?php echo Yii::t('app','Placeholder'); ?></th>
    <th width="10%"><?php echo Yii::t('app','Course'); ?></th>
    <th width="10%"><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"); ?></th>
    <th width="20%"><?php echo FileUploads::model()->getAttributeLabel('students'); ?></th> 
    <th width="20%"><?php echo FileUploads::model()->getAttributeLabel('description'); ?></th>        
  </tr>
  <tr>
    <td><?php echo $model->title; ?></td>
    <td>
		<?php 
		$category = FileCategory::model()->findByAttributes(array('id'=>$model->category));
		echo $category->category; ?>
    </td>
    <td>
    	<?php
		if($model->placeholder)
		{
			echo ucfirst($model->placeholder);
		}
		else
		{
			echo 'Public';
		}
		?>
    </td>
    <td>
    	<?php
    	if($model->course)
		{
			$course = Courses::model()->findByAttributes(array('id'=>$model->course));
			if($course->course_name)
			{
				echo $course->course_name;
			}
			else
			{
				echo '-';
			}
		}
		else
		{
			echo '-';
		}
		?>
    </td>
     <td>
    	<?php
    	if($model->batch)
		{
			$batch = Batches::model()->findByAttributes(array('id'=>$model->batch));
			if($batch->name)
			{
				echo $batch->name;
			}
			else
			{
				echo '-';
			}
		}
		else
		{
			echo '-';
		}
		?>
    </td>
    <td>
    	<?php
			echo FileUploads::model()->getStudents($model->id);
		?>
    </td>
    <td>
    	<?php
			if($model->description != NULL){
				echo ucfirst($model->description);
			}
			else{
				echo '-';
			}
		?>
    </td>    
  </tr>
</table>
</div>
</div>
</div>