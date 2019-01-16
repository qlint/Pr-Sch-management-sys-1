<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'room-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><?php echo Yii::t('app','Fields with').'&nbsp;';?><span class="required">*</span> <?php echo '&nbsp;'.Yii::t('app','are required.');?></p>

	<?php echo $form->errorSummary($model); ?>
 
<?php

$floor=Floor::model()->findByAttributes(array('id'=>$_REQUEST['id']));

if($floor!=NULL)
{
	
	$cnt=$floor->no_of_rooms;
	for($i=1;$i<=$cnt;$i++)
	{
		
		?>
     <div class="formCon" style="margin-bottom:8px; border:1px #e6e8e9 solid;">
<div class="formConInner"> 
       <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><?php echo $form->labelEx($model,'room_no'); ?></td>
    <th>&nbsp;</th>
    <td><?php echo $form->textField($model,'room_no['.($i-1).']',array('size'=>20, 'value'=>((isset($_POST['Room']['room_no'][($i-1)]))?$_POST['Room']['room_no'][($i-1)]:""))); ?></td>
  
    <td><?php echo $form->labelEx($model,'no_of_bed'); ?></td>
    <td>&nbsp;</td>
    <td><?php echo $form->textField($model,'no_of_bed['.($i-1).']',array('size'=>20, 'value'=>((isset($_POST['Room']['no_of_bed'][($i-1)]))?$_POST['Room']['no_of_bed'][($i-1)]:""))); ?></td>
  </tr>
   
</table>
  </div></div>
        <?php
	}
?>
<?php
if(isset($_REQUEST['id']) and ($_REQUEST['id']!=NULL) and (isset($_REQUEST['hostel_id']) and ($_REQUEST['hostel_id']!=NULL)))
{
 echo $form->hiddenField($model,'floor',array('value'=>$_REQUEST['id'],'readonly'=>true)); ?>
<?php echo $form->hiddenField($model,'hostel_id',array('value'=>$_REQUEST['hostel_id'],'readonly'=>true)); 

}
?>
	<div >
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Create') : Yii::t('app','Save'),array('class'=>'formbut')); ?>
	</div>


<?php }
?>
    <?php $this->endWidget(); ?>
</div><!-- form -->