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

.mailbox-menu-newup a{color:#fff !important; text-decoration:none !important; display:block;}

.mailbox-message-subject{
	padding:10px;
}


.mailbox-menu-mangeup a{color:#fff !important; text-decoration:none !important; display:block;}
.inner_new_table{
	padding: 10px 9px;
}

		
</style>
<?php
$this->breadcrumbs=array(
	Yii::t('app','Downloads')=>array('/downloads'),
	Yii::t('app','File Uploads')=>array('index'),	
	Yii::t('app','View')
);


?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="80" valign="top" id="port-left">
     <?php $this->renderPartial('/default/left_side');?>
    
    </td>
    <td valign="top">
    	<div class="cont_right">
    <h1><?php echo Yii::t('app','View Uploaded File'); ?></h1>

<div class="button-bg">
<div class="top-hed-btn-left"> </div>
<div class="top-hed-btn-right">
<ul>                                    
<li>     	<?php 
	 echo CHtml::link(Yii::t('app','New Upload'),array('create'),array('class'=>'mailbox-menu-mangeup'));
	 ?></li>
<li>     <?php
	 echo CHtml::link(Yii::t('app','Manage Uploads'),array('admin'),array('class'=>'mailbox-menu-mangeup'));
	 ?></li>
                                 
</ul>
</div> 

</div>
     
   <div class="inner_new_table"> 
	
    
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <th><?php echo Yii::t('app','Title'); ?></th>
    <th><?php echo Yii::t('app','Category'); ?></th>
    <th><?php echo Yii::t('app','Placeholder'); ?></th>
    <th><?php echo Yii::t('app','Course'); ?></th>
    <th><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"); ?></th>
    <th><?php echo Yii::t('app','File Name'); ?></th>
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
    	if($model->file)
		{
			echo $model->file;
		}
		else
		{
			echo '-';
		}
	?>
    </td>
  </tr>
</table>
	</div>
</div>
    </td>
  </tr>
</table>