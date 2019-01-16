<style type="text/css">
.mark_err{ color:#F00;}
</style>
<div class="formCon" >

<div class="formConInner" >

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'exam-scores-form',
	'enableAjaxValidation'=>false,
)); ?>

	
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<?php
$subject_cps	=	ExamScoresSplit::model()->findAllByAttributes(array('exam_scores_id'=>$model->id));
	$exm = Exams::model()->findByAttributes(array('id'=>$_REQUEST['examid']));
	if($exm!=NULL)
	{
		$sub = Subjects::model()->findByAttributes(array('id'=>$exm->subject_id));
	}
	$subject_splits	=	SubjectSplit::model()->findAllByAttributes(array('subject_id'=>$sub->id)); 
	$subject_array	=	array();
	foreach($subject_splits as $subject_split){
		$subject_array[]=$subject_split->split_name;
	}
if(count($subject_cps) !=0){
	$k=1;
	foreach($subject_cps as $subject_cp)
	{
		$att			=	'sub_category'.$k;
		$model->$att	=	$subject_cp->mark;
	?>
    <tr> 
        <td><label><?php echo $subject_array[$k-1]?></label></td>
        <td><?php echo $form->textField($model,'sub_category'.$k,array('size'=>7,'maxlength'=>3,'class'=>'mark'.$k)); ?>
        
        <div class="mark_err"></div>
        <?php echo $form->error($model,'sub_category'.$k); ?>
        </td>
    </tr>
     <tr>
    	<td>&nbsp;</td>
        <td>&nbsp;</td>
       
    </tr>
    <?php
	$k++;
	}
}else{
?><td style="display:none"><?php 
	echo $form->textField($model,'sub_category1',array('value'=>0,'size'=>7,'maxlength'=>3,'class'=>'mark1','style'=>'display:none')); 
	?></td><td style="display:none"><?php
	echo $form->textField($model,'sub_category2',array('value'=>0,'size'=>7,'maxlength'=>3,'class'=>'mark2','style'=>'display:none')); ?></td><?php
}?> 
     <tr>
    	<td>&nbsp;</td>
        <td>&nbsp;</td>
       
    </tr>
    <tr>
        <td>
        <?php echo $form->labelEx($model,'marks'); ?></td>
        <td><?php if($subject_cps !=NULL){
			echo $form->textField($model,'marks',array('size'=>7,'maxlength'=>3,'class'=>'total','readonly'=>true)); 
		}else{
			echo $form->textField($model,'marks',array('size'=>7,'maxlength'=>3)); 
		}?>
        
        <div class="mark_err"></div>
        <?php echo $form->error($model,'marks'); ?>
        </td>
    </tr>
		<?php echo $form->hiddenField($model,'grading_level_id'); ?>
		<?php echo $form->error($model,'grading_level_id'); ?>
            <tr>
    	<td>&nbsp;</td>
        <td>&nbsp;</td>
       
    </tr>
	 
	<tr>

	 <td>
		<?php echo $form->labelEx($model,'remarks'); ?></td>
		<td><?php echo $form->textField($model,'remarks',array('size'=>60,'maxlength'=>255)); ?></td>
		<?php echo $form->error($model,'remarks'); ?>
	</tr>
  
    </table>
	 
	<?php echo $form->hiddenField($model,'updated_at',array('value'=>date('Y-m-d'))); ?>
		

	<div class="row buttons" style="padding-top:10px; padding-left:85px;">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Create') : Yii::t('app','Save'),array('class'=>'formbut')); ?>
	</div>

<?php $this->endWidget(); ?>
<script>
$('input[type="text"][name="ExamScores[marks]"]').blur(function(e) {
    if(isNaN($(this).val())){
		$(".mark_err").html("Mark must be an integer");
  //$(this).val('');
 }
});
$('.mark1').change(function(e) {
	var mark_val	= $('.mark2').val();
	var total		= parseInt($(this).val())+parseInt(mark_val);
	if(!isNaN(total)){
		$('.total').val(total);
	}
});
    
$('.mark2').change(function(e) {
  var mark_val 		= $('.mark1').val();
	var total		= parseInt($(this).val())+parseInt(mark_val);
	if(!isNaN(total)){
		$('.total').val(total);
	}
});
</script>
</div></div><!-- form -->