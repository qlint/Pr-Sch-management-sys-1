

		<?php $form=$this->beginWidget('CActiveForm', array(
            'id'=>'student-attentance-form',
            'enableAjaxValidation'=>true,
        )); ?>
            <?php echo $form->errorSummary($model); ?>
            <?php echo  CHtml::hiddenField('id',$_REQUEST['id']); ?>
            <?php echo $form->hiddenField($model,'student_id',array('value'=>$emp_id)); ?>
            <?php echo $form->hiddenField($model,'batch_id',array('value'=>$batch_id)); ?>
            <?php echo $form->hiddenField($model,'date',array('value'=>$year.'-'.$month.'-'.$day)); ?>
            
            <div class="row">
                <div class="col-md-12">
                    <div class="model-popup-form">
						<?php $leave_types = CHtml::listData(StudentLeaveTypes::model()->findAllByAttributes(array('status'=>1)),'id','name');?>
                        <?php echo $form->labelEx($model,'leave_type_id'); ?>
                        <?php echo $form->dropDownList($model,'leave_type_id',$leave_types,array('options' => array($model->id=>array('selected'=>true)), 'class'=>'form-control', 'style'=>'width:100%;','empty'=>'Select Leave Type')); ?>
                        <?php echo $form->error($model,'leave_type_id'); ?>
                    </div>
                </div>
            </div>
            <div id="leave" style="color:#F00"></div>
            <div class="row">
                <div class="col-md-12">
                    <div class="model-popup-form">
                        <?php echo $form->labelEx($model,Yii::t('app','reason')); ?>
                        <?php echo $form->textField($model,'reason',array('class'=>'form-control')); ?>
                        <?php echo $form->error($model,'reason'); ?>
                    </div>
                </div>
            </div>
            <div id="msg" style="color:#F00"></div>
            <div class="row buttons">
                <div class="col-md-12">
                     <div class="model-popup-form-btn">
                        <?php
                            echo CHtml::ajaxSubmitButton(
                                Yii::t('app','Save'),
                                CHtml::normalizeUrl(array('/teachersportal/default/editLeave','render'=>false)),
                                array(
                                    'dataType'=>'json',
                                    'success'=>'js: function(data) {
                                        if (data.status == "success"){
                                            window.location.reload();
                                        }
                                        else{
                                            if(data.reason=="")
                                            {
                                                $("#msg").html("'.Yii::t("app","Reason cannot be blank").'");
                                            }
                                            if(data.leave_type=="")
                                            {
                                                $("#leave").html("'.Yii::t("app","Leave type cannot be blank").'");
                                            }
                                        }
                                    }'
                                ),
                                array(
                                    'id'=>'closeJobDialog'.$day.$emp_id,
                                    'class'=>'btn model-save-btn',
                                    'name'=>'save'
                                )
                            );
                        ?>

                        <?php
                            echo CHtml::ajaxSubmitButton(
                                Yii::t('app','Delete'),
                                CHtml::normalizeUrl(array('/teachersportal/default/deleteLeave','render'=>false)),
                                array(
                                    'success'=>'js: function(data) {
                                        window.location.reload();
                                    }'
                                ),
                                array(
                                    'confirm'=>Yii::t('app', 'Are you sure, You want to delete this reason ?'),
                                    'id'=>'closeJobDialog1'.$day.$emp_id,
                                    'class'=>'btn model-delete-btn',
                                    'name'=>'delete'
                                )
                            );
                        ?>
                    </div>
                </div>
            </div>        
        <?php $this->endWidget(); ?>
