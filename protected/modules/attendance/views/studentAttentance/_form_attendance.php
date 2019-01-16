<style>
.ui-widget-content{
height:auto !important;
width: 488px !important;	
}
</style>

<p style="padding-left:20px;"><?php echo Yii::t('app','Fields with');?><span class="required"> * </span><?php echo Yii::t('app','are required').'.';?></p>
<div class="formCon" style="width:430px; height:auto;">
    <div class="formConInner" style="width:400px;">
        <div  style="background:none">
            <?php $form=$this->beginWidget('CActiveForm', array(
				'id'=>'timetable-entries-form',	
			)); ?>
            
            <input type="hidden" value="<?php echo $_REQUEST['student_id']; ?>" name="student_id" />
            <input type="hidden" value="<?php echo $_REQUEST['batch_id']; ?>" name="batch_id" />
            <input type="hidden" value="<?php echo $_REQUEST['date']; ?>" name="date" />                        
            
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td>
                        <?php echo $form->labelEx($model,'reason'); ?>
                        <?php echo $form->textArea($model,'reason'); ?>
                        <?php echo $form->error($model,'reason'); ?>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <?php $data =StudentLeaveTypes::model()->findAll();
                $leave_type=CHtml::listData(StudentLeaveTypes::model()->findAllByAttributes(array('status'=>1)),'id','name');?>
                <tr>
                    <td>
                        <?php echo $form->labelEx($model,'leave_type_id'); ?>
                        <?php echo $form->dropDownList($model,'leave_type_id',$leave_type,array('empty' => Yii::t('app','Select Leave Type'))); ?>
                    </td>
                </tr>
            </table>
            <div style="padding:20px 0 0 0px; text-align:left">
				<?php echo CHtml::ajaxSubmitButton(
                    Yii::t('app','Save'),
                    CHtml::normalizeUrl(array('/courses/studentAttentance/updateDayAttendance','render'=>false)),
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
</div><!-- form -->