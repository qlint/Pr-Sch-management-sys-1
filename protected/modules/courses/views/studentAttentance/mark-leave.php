	
<style>

.popup-input input[type="text"], textArea, select{
	 width:100% !important;
	 box-sizing:border-box;

}
#jobDialog {
    height: auto !important;
}
</style>

<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ui-style.css" />
<div>
	<?php  
        $this->beginWidget('zii.widgets.jui.CJuiDialog',array(
            'id'=>'jobDialog1',
            'options'=>array(
                'title'=>Yii::t('job','Mark Student Attendance'),
                'autoOpen'=>true,
                'modal'=>'true',
                'width'=>'400',
                'height'=>'auto',
                'open'=> 'js:function(event, ui){$(".ui-dialog-titlebar-close").click(function(){$("#student_attendance-form").remove();});}',                
            ),
        )); 
	
?>

<div class="popup-input">
        <div  style="background:none">
            <?php $form=$this->beginWidget('CActiveForm', array(
				'id'=>'student_attendance-form',	
			)); 
			echo $form->hiddenField($model,'student_id',array('value'=>$_REQUEST['id'])); 
			echo $form->hiddenField($model,'batch_id',array('value'=>$_REQUEST['batch_id'])); 
			?>    
            <table width="100%" border="0" cellspacing="0" cellpadding="0"> 
            
           		 <tr>
                    <td>
                        <?php echo $form->labelEx($model,'leave_type_id'); ?>
                        <?php 
						 $leave_type=CHtml::listData(StudentLeaveTypes::model()->findAllByAttributes(array('status'=>1)),'id','name');
						echo $form->dropDownList($model,'leave_type_id',$leave_type,array('empty' => Yii::t('app','Select Leave Type'))); ?>
                    </td>
                </tr>
           		<tr>
                    <td>
                        <?php echo $form->labelEx($model,'date'); ?>
                       <?php 
						$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
						if($settings!=NULL){
							$date=$settings->dateformat;
							$dis_date=$settings->displaydate;
						}
						else{
							$date 		= 'dd-mm-yy';	
							$dis_date	= 'd M Y' ;
						}
						if($model->date ==NULL)
							$model->date	=	date($dis_date,strtotime(date('Y-m-d')));
						
						$this->widget('zii.widgets.jui.CJuiDatePicker', array(
							'model'=>$model, 
							'attribute' => 'date',
							// additional javascript options for the date picker plugin
							'options'=>array(
								'showAnim'=>'fold',
								'dateFormat'=>$date,
								'changeMonth'=> true,
								'changeYear'=>true,
								
							),
							'htmlOptions'=>array(							
								'style'=>'width:100%;',
								'readonly'=>'readonly',
							),
						));?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php echo $form->labelEx($model,'reason'); ?>
                        <?php echo $form->textArea($model,'reason'); ?>
                        <?php echo $form->error($model,'reason'); ?>
                    </td>
                </tr>
                
                
            </table>
            <div style="padding:20px 0 0 0px; text-align:left">
				<?php echo CHtml::ajaxSubmitButton(
                    Yii::t('app','Save'),
                    CHtml::normalizeUrl(array('/courses/studentAttentance/studentLeave','render'=>false)),
                    array(
                        'dataType'=>'json',
                        'success'=>'js: function(data) {
                            if (data.status == "success"){
                                $("#jobDialog").dialog("close");
                                if(data.flag==1){				
                                    window.location.reload();
                                }
                            }
                            else{
                                $(".errorMessage").remove();
                                var errors	= JSON.parse(data.errors);						
                                $.each(errors, function(index, value){
                                    var err	= $("<div class=\"errorMessage\" />").text(value[0]);
                                    err.insertAfter($("#" + index));
                                });
                            }
                        }'
                    ),
                    array(
                        'id'=>'closeJobDialog',
                        'name'=>Yii::t('app','Submit')
                    )
                ); ?>
            </div>
        	<?php $this->endWidget(); ?>
        </div>
    </div>



    
    <?php $this->endWidget('zii.widgets.jui.CJuiDialog');?>
</div>