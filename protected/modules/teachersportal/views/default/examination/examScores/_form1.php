<div class="formCon" >

<div class="formConInner" >

<h3><?php echo Yii::t('app','Update Exam Scores'); ?></h3>

<?php 
	if($_REQUEST['allexam']==1){
		$actionUrl = CController::createUrl('/teachersportal/default/update',array("id"=>$model->id,"bid"=>$batch_id,"exam_group_id"=>$exam_group_id,"r_flag"=>1,"exam_id"=>$exam_id,'allexam'=>1));
	}
	elseif($_REQUEST['allexam']!=1){
		$actionUrl = CController::createUrl('/teachersportal/default/update',array("id"=>$model->id,"bid"=>$batch_id,"exam_group_id"=>$exam_group_id,"r_flag"=>1,"exam_id"=>$exam_id));
	}
	$form=$this->beginWidget('CActiveForm', array(
	'id'=>'exam-scores-form',
	'action' => $actionUrl,
	'enableAjaxValidation'=>false,
)); ?>

	
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
        	<?php 
			$student = Students::model()->findByAttributes(array('id'=>$model->student_id));
			?>
            <td><?php echo Yii::t('app','Student Name');?></td>
            <td><?php echo ucfirst($student->first_name).' '.ucfirst($student->last_name); ?></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td><?php echo $form->labelEx($model,'marks'); ?></td>
            <td><?php echo $form->textField($model,'marks',array('size'=>7,'maxlength'=>7)); ?></td>
            <?php echo $form->error($model,'marks'); ?>
		</tr>
		<?php echo $form->hiddenField($model,'grading_level_id'); ?>
        <?php echo $form->error($model,'grading_level_id'); ?>
		<tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
         <td><?php echo $form->labelEx($model,'remarks'); ?></td>
         <td><?php echo $form->textField($model,'remarks',array('size'=>60,'maxlength'=>255)); ?></td>
            <?php echo $form->error($model,'remarks'); ?>
        </tr>
    </table>

	<?php echo $form->hiddenField($model,'updated_at',array('value'=>date('Y-m-d'))); ?>
		

	<div class="row buttons" style="padding-top:10px; padding-left:85px;">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save'),array('class'=>'formbut')); ?>
	</div>

<?php $this->endWidget(); ?>

</div></div><!-- form -->