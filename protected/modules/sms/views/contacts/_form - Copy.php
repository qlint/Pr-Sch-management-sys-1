<style type="text/css">
.formbut_1 button, input[type="submit"] { 
	background: url("images/fbut-bg.png") repeat-x scroll 0 0 rgba(0, 0, 0, 0)  ;
    border: 1px solid #B58530 !important;}
.errorSummary{ width:85%;}
</style>

<div class="formCon" style="width:97%;">
<div class="formConInner">


<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'contacts-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><?php echo Yii::t('app', 'Fields with');?> <span class="required">*</span> <?php echo Yii::t('app', 'are required.');?></p>

	<?php echo $form->errorSummary($model); ?>
<div class="inputstyle">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="50%"><?php echo $form->labelEx($model,'first_name'); ?></td>
    <td width="50%"><?php echo $form->labelEx($model,'last_name'); ?></td>
  </tr>
  
  <tr>
    <td><?php echo $form->textField($model,'first_name',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'first_name'); ?></td>
    <td><?php echo $form->textField($model,'last_name',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'last_name'); ?></td>
  </tr>
    <tr>
  	<td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><?php echo $form->labelEx($model,'mobile'); ?></td>
    <td><?php echo $form->labelEx($model,'email'); ?></td>
  </tr>
  <tr>
    <td><?php echo $form->textField($model,'mobile',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'mobile'); ?></td>
    <td><?php echo $form->textField($model,'email',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'email'); ?></td>
  </tr>
  
  </tr>
    <tr>
  	<td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  
  <tr>
  	
    <td><?php echo $form->labelEx($model,Yii::t('app','Group')); ?></td>
    <td></td>
  
  </tr>
  
   <tr>
  	
    <td><?php
		$criteria	= new CDbCriteria;
		$criteria->condition	= '`status`=:status';
		$criteria->params		= array(":status"=>1);
		$criteria->order		= '`id` ASC';
        $data 		= CHtml::listData(ContactGroups::model()->findAll($criteria), 'id', 'group_name');
        echo $form->dropDownList(
            $model,
            'group',
            $data,
            array(
				'multiple'=>true,
				'style'=>'height:100px;',
            )
        ); 
        ?>
        <?php echo $form->error($model,'group'); ?></td>
    <td></td>
  
  </tr>
  <tr>
   	<td>
	<div class="but_12" style="margin-top:10px;">
	<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save'),array('class'=>'formbut')); ?>
    </div>
    </td>
    <td></td>
  </tr>
  
</table>
    </div>

<?php $this->endWidget(); ?>

</div>
</div><!-- form -->