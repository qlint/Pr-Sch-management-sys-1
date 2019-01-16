<style>

.timetable_formats label{
	display:inline;
}
.popup-input input[type="text"], textArea, select{
	 width:100% !important;
	 box-sizing:border-box;

}
.popup-box {
    background-color: #EAF5FD;
    border: 1px solid #a3c5e0;
    margin-top: 10px;
    padding: 9px;
    margin-bottom: 10px;
}
.popup-input table td {
    width: 100%;
    font-size: 12px;
}
.popup-box td label {
    color: #3E719B;
}
#jobDialog {
    height: auto !important;
}
</style>


<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'courses-form',
	//'enableAjaxValidation'=>true,
)); ?>
<div class="popup-input ">
	<p><?php echo Yii::t('app','Fields with');?> <span class="required">*</span> <?php echo Yii::t('app','are required.');?></p>

	<?php echo $form->errorSummary($model); ?>
        
    
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td>
                    <div class="opnsl_inputField_stl">
                        <?php echo $form->labelEx($model,'course_name'); ?>                
                        <?php echo $form->textField($model,'course_name',array('encode'=>false,'size'=>30,'maxlength'=>255,'class'=>'opnsl_inputField')); ?>
                        <?php echo $form->error($model,'course_name'); ?>
                    </div>
                 </td>
            </tr>
            <?php
			   $sem_enabled		=	Configurations::model()->isSemesterEnabled();
			   if($sem_enabled==1)
			   { 
			      $course_val	=	Courses::model()->findByPk($val1);
			      $course_id	=	$course_val->id;
			      $cou_enabled  =	Configurations::model()->isSemesterEnabledForCourse($course_id);
				  if($cou_enabled==1)
				   {
			 ?>
                <tr>
                    <td><?php echo $form->labelEx($model,'semester_enabled'); ?>
                    <?php echo $form->checkBox($model,'semester_enabled'); ?>
                    <?php echo $form->error($model,'semester_enabled'); ?>
                    </td>
                </tr>
                <?php
				   }
				   else
				   {
				 ?>
                     <tr>
                        <td><?php echo $form->labelEx($model,'semester_enabled'); ?>
                        <?php echo $form->checkBox($model,'semester_enabled'); ?>
                        <?php echo $form->error($model,'semester_enabled'); ?>
                        </td>
                    </tr>
			<?php
				   }
               }
             ?>
             
			<?php if(Configurations::model()->timetableConfig()==-1){  // timetable format is selected as course level ?>

                
<tr>
	<td>
        <table class="popup-box" width="100%">
            <tbody>
                <tr>
                    <td>
                        <?php echo $form->labelEx($model,'timetable_format'); ?>
                    </td>
                </tr>
                <tr>
                    <td class="timetable_formats">
                        <?php echo $form->radioButton($model,'timetable_format', array('value'=>1, 'id'=>'timetable_format_1'))." ".CHtml::label(Yii::t('app', 'Fixed Class Timings'), 'timetable_format_1'); ?>
                        	<br />
						<?php echo $form->radioButton($model,'timetable_format', array('value'=>2, 'uncheckValue'=>1, 'id'=>'timetable_format_2'))." ".CHtml::label(Yii::t('app', 'Flexible Class Timings'), 'timetable_format_2'); ?>
                    </td>
                  </tr>
            </tbody>
        </table>
    </td>
</tr>
                
                
            <?php }?>
             
        <tr><td colspan="2">&nbsp;</td></tr>                       
            <?php $level = Configurations::model()->findByPk(41);
            if($level->config_value == -1)
            { ?> 

<tr>
	<td>
        <table class="popup-box" width="100%">
            <tbody>
                <tr>
                    <td>
                        <?php echo $form->labelEx($model,'exam_format'); ?>
                    </td>
                </tr>
                <tr>
                    <td class="timetable_formats">
                        <?php echo $form->radioButton($model, 'exam_format', array('value'=>'1','uncheckValue'=>null))."Default ";
                    echo $form->radioButton($model, 'exam_format', array('value'=>'2','uncheckValue'=>null))." CBSE"; ?>
                    <?php echo $form->error($model,'exam_format'); ?>
                    </td>
                  </tr>
            </tbody>
        </table>
    </td>
</tr>
                
                
                
                
            <?php } ?>
                          
            <tr>            
                <td>
					<?php	echo CHtml::ajaxSubmitButton($model->isNewRecord ? Yii::t('app','Save') : Yii::t('app','Update'),CHtml::normalizeUrl(array('courses/Edit&val1='.$val1,'render'=>false)),array('dataType'=>'json','success'=>'js: function(data) {
						if(data.status=="success"){
							$("#jobDialog11").dialog("close");  window.location.reload();
							alert("'.Yii::t('courses','Course Updated Successfully.').'");
						}else{
						$(".errorMessage").remove();
							var errors	= JSON.parse(data.errors);						
							$.each(errors, function(index, value){
								var err	= $("<div class=\"errorMessage\" />").text(value[0]);
								err.insertAfter($("#" + index));
							});
						}
                    
                    }'),array('id'=>'closeJobDialog12','name'=>'Submit')); ?>
                </td>
            </tr>
            <?php $this->renderPartial('_flash',array('model'=>$model,'id'=>'jobDialog')); ?>
        </table>
</div>	
    
    <?php 
		echo $form->hiddenField($model,'is_deleted'); 
		echo $form->hiddenField($model,'created_at');
		echo $form->hiddenField($model,'updated_at',array('value'=>date('d-m-Y')));
	?>	
<?php $this->endWidget(); ?>

<script>
$("#Courses_exam_format").prop("checked", true);
</script>