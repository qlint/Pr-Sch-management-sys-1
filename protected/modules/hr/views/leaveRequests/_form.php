<?php
	$settings = UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
	if($settings!=NULL){
		$dateformat		= $settings->dateformat;
		$displaydate	= $settings->displaydate;	
	}else{
		$dateformat 	= 'dd-mm-yy';
		$displaydate	= 'Y-m-d'; 
	}
	
	$form=$this->beginWidget('CActiveForm', array(
		'id'=>'leave-approve-form',	
	));
?>

	<div class="aprovleave-table">
    	<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>           
                <th><?php echo $model->getAttributeLabel('requested_by');?></th>
                <td>
                	<?php
						$employee	= Staff::model()->findByAttributes(array('uid'=>$model->requested_by));
                        echo ($employee!=NULL)?$employee->fullname:'-';
                    ?>
              	</td>
          	</tr>
            <tr>           
                <th><?php echo $model->getAttributeLabel('leave_type_id');?></th>
                <td><?php echo ($model->leaveType!=NULL)?$model->leaveType->type:"-";?></td>
          	</tr>
            <tr>
                <th><?php echo $model->getAttributeLabel('from_date');?></th>
                <td>
                	<?php
						if($settings){
							echo date($settings->displaydate, strtotime($model->from_date));
						}
						else{
							echo date('Y-m-d', $model->from_date);
						}
                    ?>
              	</td>
          	</tr>
            <tr>
                <th><?php echo $model->getAttributeLabel('to_date');?></th>
                <td>
                	<?php
						if($settings){
							echo date($settings->displaydate, strtotime($model->to_date));
						}
						else{
							echo date('Y-m-d', $model->to_date);
						}
                    ?>
              	</td>
          	</tr>
            <tr>
                <th colspan="2"><?php echo $model->getAttributeLabel('reason');?></th>
           	</tr>
            <tr>
                <td colspan="2"><?php echo ucfirst($model->reason);?></td>
          	</tr>
       	</table>
    </div>
    	
	<div style="width:100%">    
    	<table width="100%" border="0" cellspacing="0" cellpadding="0">
        	<?php if(Yii::app()->controller->action->id != 'reject'){ ?>
                <tr>
                    <td style="vertical-align:top;">
                        <div class="popup-leaverqst">
                            <?php echo $form->labelEx($model,'from_date'); ?>
                            <?php
                            $model->from_date	= date($displaydate, strtotime($model->from_date));
                            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                                'attribute'=>'from_date',
                                'model'=>$model,
                                'options'=>array(
                                'showAnim'=>'fold',
                                'dateFormat'=>$dateformat,
                                'changeMonth'=> true,
                                'changeYear'=>true,
                                'yearRange'=>'1970:'
                                ),
                                'htmlOptions'=>array(
                                'readonly'=>"readonly"
                                ),
                            ))?>
                            <?php echo $form->error($model,'from_date'); ?>               
                        </div>
                    </td>                
                    <td style="vertical-align:top;">
                        <div class="popup-leaverqst">
                            <?php echo $form->labelEx($model,'to_date'); ?>
                            <?php
                            $model->to_date		= date($displaydate, strtotime($model->to_date));
                            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                                'attribute'=>'to_date',
                                'model'=>$model,
                                'options'=>array(
                                'showAnim'=>'fold',
                                'dateFormat'=>$dateformat,
                                'changeMonth'=> true,
                                'changeYear'=>true,
                                'yearRange'=>'1970:'
                                ),
                                'htmlOptions'=>array(
                                'readonly'=>"readonly"
                                ),
                            ))?>
                            <?php echo $form->error($model,'to_date'); ?>               
                        </div>
                    </td>
                </tr>
            <?php } ?>    
			<tr>         
                <td width="100%" colspan="2">
                	<div class="popup-leaverqst">
                    	<?php echo $form->labelEx($model,'response'); ?>
						<?php echo $form->textArea($model,'response',array('size'=>20,'maxlength'=>255,'style'=>'width:100%')); ?>
                        <?php echo $form->error($model,'response'); ?>               
                    </div>
             	</td>
            </tr>
     	</table>
        
        <div class="row buttons">
        	<?php
				echo CHtml::ajaxSubmitButton(
					(Yii::app()->controller->action->id=="approve") ? Yii::t('app','Approve') : Yii::t('app','Reject'),
					'',
					array(
						'dataType'=>'json',
						'success'=>'js: function(data) {
							$(".errorMessage").remove();
							if (data.status == "success"){
								window.location.reload();
							}
							else{								
								var errors	= data.errors;
								$.each(errors, function(index, value){
									var err	= $("<div class=\"errorMessage\" />");
									err.html(value[0]);
									err.insertAfter($("#LeaveRequests_" + index));
								});
							}				
						}'
					)
				); ?>
        </div>
  	</div>
<?php $this->endWidget(); ?>