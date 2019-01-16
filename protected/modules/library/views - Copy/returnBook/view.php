<style type="text/css">
.pdtab_Con {
    margin: 0;
    padding: 0;
}
</style>
<?php
$this->breadcrumbs=array(
Yii::t('app','Library')=>array('/library'),
	Yii::t('app','Return Book')=>array('/library/ReturnBook/manage'),
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
<h1><?php echo Yii::t('app','View Return Book'); ?></h1>


<?php


$borrow=BorrowBook::model()->findByAttributes(array('id'=>$model->borrow_book_id));
$book=Book::model()->findByAttributes(array('id'=>$borrow->book_id));
$student=Students::model()->findByAttributes(array('id'=>$borrow->student_id));
?>
<div class="table-responsive">
    <table class="table table-bordered mb30" width="100%" cellspacing="0" cellpadding="0">
    <thead>
 <tr>
 <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){ ?>
 <td align="center"><?php echo Yii::t('app','Student Name'); ?></td>
 <?php } ?>
 <th><?php echo Yii::t('app','Book'); ?></th>
 <th><?php echo Yii::t('app','Issued Date'); ?></th>
 <th><?php echo Yii::t('app','Return date'); ?></th>
 <th><?php echo Yii::t('app','Status'); ?></th>

 </tr>
 </thead>
 <tr>
 <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){ ?>
 <td><?php echo $student->studentFullName("forStudentProfile");?></td>
 <?php } ?>
 <td><?php echo $book->title;?></td>
 <td><?php 
 			$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
								if($settings!=NULL)
								{	
									$date1=date($settings->displaydate,strtotime($model->issue_date));
									
		
								}
								else
								{
									$date1 = $model->issue_date;
									
								}
								echo $date1;
                     ?>
 </td>
 <td><?php 
 					if($settings!=NULL)
								{	
									$date2=date($settings->displaydate,strtotime($model->return_date));
									
		
								}
								else
								{
									$date2 = $model->return_date;
								}
								echo $date2;
					?>
   </td>
  <td><?php 
  if($model->return_date > $borrow->due_date)
  {
	  echo Yii::t('app','Due date expired');
	 // echo Yii::t('app','Due date expired'. ' | '. CHtml::link('Pay Fine',array('/library/ReturnBook/fine','id'=>$borrow->student_id)));
	 
  }
  else
  {
	 echo Yii::t('app','No fine');
	 
	  
  }
  
  ?></td>
 
 </tr>
 </table>
</div>
</div>
</td>
</tr>
</table>


