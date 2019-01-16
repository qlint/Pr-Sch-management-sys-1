<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'allotment-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><?php echo Yii::t('app','Fields with').' <span class="required">*</span> '.Yii::t('app','are required.');?></p>

	<?php echo $form->errorSummary($model); ?>
<?php
if((Yii::app()->controller->action->id)=='create')
{
$bed_no_1= '';
$floor_no='';
	if(isset($_REQUEST['studentid']) && (!isset($_REQUEST['allotid'])))
	{
		$bed_no	= Allotment::model()->findAll('status=:x ORDER BY id ASC',array(':x'=>'C'));
		if($bed_no==NULL)
			{
				
				$bed_no_1 = '';
				$floor_no='';
				
				
			}
			else
			{
			$bed_no_1=$bed_no[0]['bed_no'];
			
			}
	}
	else if(isset($_REQUEST['studentid']) && (isset($_REQUEST['allotid'])))
	{
		$room=Allotment::model()->findByAttributes(array('id'=>$_REQUEST['allotid']));
		$bed_no_1=$room->bed_no;
	}
}

?>
 <div class="formCon" >
<div class="formConInner">
	<table width="80%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><?php echo $form->labelEx($model,'bed_no'); ?></td>
    <td>&nbsp;</td>
    <td><?php echo $form->textField($model,'bed_no',array('size'=>20,'value'=>$bed_no_1,'readonly'=>true)); ?>
		<?php echo $form->error($model,'bed_no'); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><?php //echo $form->labelEx($model,'floor'); ?></td>
    <td>&nbsp;</td>
    <td><?php //echo $form->textField($model,'floor',array('size'=>20,'value'=>$floor_no)); ?>
		<?php //echo $form->error($model,'floor'); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>

	 <?php echo $form->hiddenField($model,'created',array('value'=>date('Y-m-d'))); ?>
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Allot') : Yii::t('app','Save'),array('class'=>'formbut')); ?>
	</div>
</div>
</div>
<?php $this->endWidget(); ?>

</div><!-- form -->