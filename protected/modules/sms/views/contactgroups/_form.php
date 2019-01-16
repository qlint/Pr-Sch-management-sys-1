

<div class="formCon">
<div class="formConInner">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'contact-groups-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><?php echo Yii::t('app', 'Fields with');?> <span class="required">*</span> <?php echo Yii::t('app', 'are required.');?></p>

	<?php echo $form->errorSummary($model); ?>
<div class="inputstyle">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    	<tr>
        	<td><?php echo $form->labelEx($model,'group_name'); ?>
            <?php echo $form->textField($model,'group_name',array('cols'=>50)); ?>
                <?php echo $form->error($model,'group_name'); ?></td>
        </tr>
        
        <tr>
			<td>
			<div class="but_12" style="margin-top:10px;">
			<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save'), array('class'=>'formbut')); ?>
            </div>
            </td>
        </tr>
    </table>
    </div>
<?php $this->endWidget(); ?>

</div>
</div><!-- form -->