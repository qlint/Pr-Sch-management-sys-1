<style>
.formCon label{
	font-size: 12px;
    letter-spacing: .001em;
    color: #666;
    padding: 0px 0px 3px 0px;
    display: inline-block;
}
</style>

<?php
$this->breadcrumbs=array(
	Yii::t('app','Settings')=>array('/configurations'),
	Yii::t('app','Manage Exam Format'),
);?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top">
<div id="othleft-sidebar">
<?php $this->renderPartial('//configurations/left_side');?>
  </div>
 </td>
 <td valign="top">
<div class="cont_right formWrapper">  
<h1><?php echo Yii::t('app','Manage Exam Format');?></h1>

<?php $form=$this->beginWidget('CActiveForm', array(
'id'=>'authentication-form',
//'enableAjaxValidation'=>true,
)); ?>
<?php

	Yii::app()->clientScript->registerScript(
	'myHideEffect',
	'$(".flashMessage").animate({opacity: 1.0}, 3000).fadeOut("slow");',
	CClientScript::POS_READY
	);
	?>
	<?php
	/* Success Message */
	if(Yii::app()->user->hasFlash('successMessage')): 
	?>
		<div class="flashMessage" style="background:#FFF; color:#C00; padding-left:220px; font-size:13px">
		<?php echo Yii::app()->user->getFlash('successMessage'); ?>
		</div>
	<?php endif;
	 /* End Success Message */
?>
<div class="formCon">

<div class="formConInner">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
   <h4><?php echo Yii::t('app','Select Exam Format');?></h4> 	
    		<tr>
        		<td>
					<?php
						echo $form->radioButton($model,'exam_format',array("id"=>"radio-1", 'value'=>"0", "uncheckValue"=>NULL));
						echo CHtml::label('School Level','radio-1');
					?>
								<div id="school_div" style="padding-left:25px;<?php if(!in_array($model->exam_format, array(0, 1, 2))){?> display:none;<?php }?>">
											  <?php
											  
											echo $form->radioButton($model,'exam_format',array("id"=>"radio-4", 'value'=>"1", "uncheckValue"=>NULL));
											echo CHtml::label('Default','radio-4');
											
											echo $form->radioButton($model,'exam_format',array("id"=>"radio-5", 'value'=>"2", "uncheckValue"=>NULL));
											echo CHtml::label('CBSE','radio-5');
                                              ?>
                                      </div>
                                      <?php
								echo $form->radioButton($model,'exam_format',array("id"=>"radio-2", 'value'=>"-1", "uncheckValue"=>NULL));
								echo CHtml::label('Course Level','radio-2');
								
								echo $form->radioButton($model,'exam_format',array("id"=>"radio-3", 'value'=>"-2", "uncheckValue"=>NULL));
								echo CHtml::label('Batch Level','radio-3');
									  ?>
									  
                                </td>
                               
               
        	</tr> 
           
</table>
</div> 
</div>
<?php echo CHtml::submitButton(Yii::t('app','Apply'),array('class'=>'formbut','name'=>'submit')); ?> 
<?php $this->endWidget(); ?>
</div>
</td>
</tr>
</table>

<script type="text/javascript">
	$('input:radio[name="Configurations[exam_format]"]').change(function(e) {
		if($(this).val()==0){
			$('#school_div').show();
			$("#radio-4").prop("checked", true);
		}
		else if($(this).val()!=1 && $(this).val()!=2){
			$('#school_div').hide();
		}
	});
</script>
	

