<style type="text/css">
.pdtab_Con {
    margin: 0;
    padding: 0;
}
</style>
<?php
$this->breadcrumbs=array(
	Yii::t('app','Library')=>array('/library'),
	Yii::t('app','Borrow Book')=>array('/library/borrowBook/create'),
	Yii::t('app','View'),
);
$student_visible_fields   = FormFields::model()->getVisibleFields('Students', 'forStudentProfile');
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top">

 <?php $this->renderPartial('/settings/library_left');?>
 </td>
    <td valign="top">
     <div class="cont_right formWrapper">
<h1><?php echo Yii::t('app','Book Details');?></h1>

<?php $student=Students::model()->findByAttributes(array('id'=>$model->student_id));?>
<div class="table-responsive">
    <table class="table table-bordered mb30" width="100%" cellspacing="0" cellpadding="0">
    <thead>
 <tr>
 <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){ ?>
 <th><?php echo Yii::t('app','Student Name');?></th>
 <?php } ?>
 <th><?php echo Yii::t('app','Book');?></th>
 <th><?php echo Yii::t('app','Issued Date');?></th>
 <th><?php echo Yii::t('app','Due date');?></th>
 <th><?php echo Yii::t('app','Action');?></th>
 </tr>
 </thead>
 <tr>
 <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){ ?>
 <td><?php echo $student->studentFullName("forStudentProfile");?></td>
 <?php } ?>
 <td><?php echo $model->book_name;?></td>
 <td><?php 
 							$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
								if($settings!=NULL)
								{	
									$date1=date($settings->displaydate,strtotime($model->issue_date));
									echo $date1;
		
								}
								
								
 						?></td>
 <td><?php 		
 								if($settings!=NULL)
								{	
									$date2=date($settings->displaydate,strtotime($model->due_date));
									echo $date2;
		
								}
								
 								?></td>
          <td><?php echo CHtml::link(Yii::t('app','Edit'),array('/library/BorrowBook/update','id'=>$model->id)); 
		  			echo ' | ';
					echo CHtml::link(Yii::t('app','Remove'), "#", array("submit"=>array('/library/BorrowBook/remove','id'=>$model->id),'confirm' => Yii::t('app', 'Are you sure?'), 'csrf'=>true));?></td>
 		</tr>
 	</table>
 	</div>
 	</div>
 </td>
 </tr>
 </table>