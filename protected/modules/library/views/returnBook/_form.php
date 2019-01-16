<?php
$student_visible_fields   = FormFields::model()->getVisibleFields('Students', 'forStudentProfile');
?>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'return-book-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><?php echo Yii::t('app','Fields with');?><span class="required">*</span><?php echo Yii::t('app','are required.');?></p>


	<?php echo $form->errorSummary($model); ?>
<?php

if((Yii::app()->controller->action->id)=='create')
{
	if(isset($_REQUEST['id']) and $_REQUEST['id']!=NULL)
	{
		$bookid  =$_REQUEST['id'];
	$student_details=BorrowBook::model()->findByAttributes(array('student_id'=>$bookid,'status'=>'C'));
	$student=Students::model()->findByAttributes(array('id'=>$bookid));
	$book=Book::model()->findByAttributes(array('id'=>$student_details->book_id));
	}
}

?>
<div class="formCon">
<div class="formConInner">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){ ?>
  <tr>
    <td><?php echo $form->labelEx($model,'student_id'); ?></td>
    <td>&nbsp;</td>
    <td><?php echo $form->textField($model,'student_id',array('value'=>$student->studentFullName("forStudentProfile"))); ?>
    <?php echo $form->hiddenField($model,'student_id',array('value'=>$student_details->student_id));?>
		<?php echo $form->error($model,'student_id'); ?></td>
  </tr>
 <?php } 
 else{
?>
<?php echo $form->hiddenField($model,'student_id',array('value'=>$student_details->student_id));?>
<?php
 }
 ?>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><?php echo $form->labelEx($model,'book_id'); ?></td>
    <td>&nbsp;</td>
    <td><?php echo $form->textField($model,'book_id',array('value'=>$book->title)); ?>
		<?php echo $form->error($model,'book_id'); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><?php echo $form->labelEx($model,'issue_date'); ?></td>
    <td>&nbsp;</td>
    <?php
						$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
								if($settings!=NULL)
								{	
									$date1=date($settings->displaydate,strtotime($student_details->issue_date));
									$date2=date($settings->displaydate,strtotime($model->return_date));
									$format=$settings->dateformat;
		
								}
								else
								{
								$date1 = $student_details->issue_date;
								$date2 = $student_details->return_date;
								$format = 'dd-mm-yy';
								}
	?>
    <td><?php echo $form->textField($model,'issue_date',array('value'=>$date1,'readonly'=>true )); ?>
		<?php //echo $form->error($model,'issue_date'); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><?php echo $form->labelEx($model,'return_date'); ?></td>
    <td>&nbsp;</td>
    <td> <?php //echo $form->textField($model,'admission_date');
 			if(isset($model->return_date) and $model->return_date!=NULL)
				$model->return_date=$date2;
				$this->widget('zii.widgets.jui.CJuiDatePicker', array(
								//'name'=>'Students[admission_date]',
								'model'=>$model,
								'attribute'=>'return_date',
								// additional javascript options for the date picker plugin
								'options'=>array(
									'showAnim'=>'fold',
									'dateFormat'=>$format,
									'changeMonth'=> true,
									'changeYear'=>true,
									'yearRange'=>'1900:'
								),
								'htmlOptions'=>array(
									'style'=>'height:20px;'
								),
							));
		 ?>
		<?php //echo $form->error($model,'return_date'); ?></td>
  </tr>
 
</table>

	<div class="row">
		<?php //echo $form->labelEx($model,'borrow_book_id'); ?>
		<?php echo $form->hiddenField($model,'borrow_book_id',array('value'=>$student_details->id)); ?>
		<?php //echo $form->error($model,'borrow_book_id'); ?>
	</div>

	<div class="row">
		<?php //echo $form->labelEx($model,'created_date'); ?>
		<?php echo $form->hiddenField($model,'created_date',array('value'=>date('Y-m-d'))); ?>
		<?php //echo $form->error($model,'created_date'); ?>
	</div>
    <div class="row">
		<?php //echo $form->labelEx($model,'created_date'); ?>
		<?php echo $form->hiddenField($model,'status',array('value'=>'C')); ?>
		<?php //echo $form->error($model,'created_date'); ?>
	</div>
</div>
</div>
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Create') : Yii::t('app','Save'),array('class'=>'formbut')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->