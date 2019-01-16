<style type="text/css">
.mark_err{ color:#F00;}
</style>
<div class="formCon" >

<div class="formConInner" >

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'exam-scores-form',
	'enableAjaxValidation'=>false,
)); ?>
<?php 
$exm = CbscExams::model()->findByAttributes(array('id'=>$_REQUEST['examid']));
if($exm!=NULL)
{
$sub = Subjects::model()->findByAttributes(array('id'=>$exm->subject_id));
} ?> 
	
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    	<tr>
            <td>
            <?php echo $form->labelEx($model,'written_exam'); ?></td>
            <td><?php echo $form->textField($model,'written_exam',array('size'=>60,'maxlength'=>255,'class'=>'mark','maxlength'=>4,)); ?>
            <?php echo $form->error($model,'written_exam'); ?></td>
        </tr> 
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>        
        </tr>
        <tr>
            <td>
            <?php echo $form->labelEx($model,'periodic_test'); ?></td>
            <td><?php echo $form->textField($model,'periodic_test',array('size'=>60,'maxlength'=>255,'class'=>'mark','maxlength'=>4,)); ?>
            <?php echo $form->error($model,'periodic_test'); ?></td>
        </tr> 
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>        
        </tr>
        <tr>
            <td>
            <?php echo $form->labelEx($model,'note_book'); ?></td>
            <td><?php echo $form->textField($model,'note_book',array('size'=>60,'maxlength'=>255,'class'=>'mark','maxlength'=>4,)); ?>
            <?php echo $form->error($model,'note_book'); ?></td>
        </tr> 
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>        
        </tr>
        <tr>
            <td>
            <?php echo $form->labelEx($model,'subject_enrichment'); ?></td>
            <td><?php echo $form->textField($model,'subject_enrichment',array('size'=>60,'maxlength'=>255,'class'=>'mark','maxlength'=>4,)); ?>
            <?php echo $form->error($model,'subject_enrichment'); ?></td>
        </tr> 
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>        
        </tr>
        <tr>
            <td>
            <?php echo $form->labelEx($model,'total'); ?></td>
            <td><?php echo $form->textField($model,'total',array('size'=>60,'maxlength'=>255,'readOnly'=>true)); ?>
            <?php echo $form->error($model,'total'); ?></td>
        </tr>
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
$('.mark').change(function(e) {
	var total	=	0;
	if($('#CbscExamScores17_written_exam').val() != ''){
		var m1	= $('#CbscExamScores17_written_exam').val();
		total 	= total+parseFloat(m1);
	}	
	if($('#CbscExamScores17_periodic_test').val() != ''){
		var m2	= $('#CbscExamScores17_periodic_test').val();
		total 	= total+parseFloat(m2);
	}
	if($('#CbscExamScores17_note_book').val() != ''){
		var m3	= $('#CbscExamScores17_note_book').val();
		total 	= total+parseFloat(m3);
	}
	if($('#CbscExamScores17_subject_enrichment').val() != ''){
		var m4	= $('#CbscExamScores17_subject_enrichment').val();
		total 	= total+parseFloat(m4);
	}  
	if(!isNaN(total)){
		$('#CbscExamScores17_total').val(total.toFixed(1));
	}
}); 
</script>
</div></div><!-- form -->