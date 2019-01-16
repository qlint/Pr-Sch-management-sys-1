<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'route-devices-form',
	'enableAjaxValidation'=>false,
)); ?>
    <div class="formCon">
        <div class="formConInner">
            <table width="80%" border="0" cellspacing="0" cellpadding="0">
            	<tr>
                    <td width="100">
                        <?php echo $form->label($model,'device_id'); ?>
                    </td>
                    <td>&nbsp;</td>
                    <td>
                    	<b>
                        <?php
							$device	= Devices::model()->findByPk($model->device_id);
                        	echo ($device!=NULL)?$device->device_id:"-";
						?>
                        </b>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td width="100">
                        <?php echo $form->labelEx($model,'route_id'); ?>
                    </td>
                    <td>&nbsp;</td>
                    <td>
                        <?php echo $form->dropDownList($model,'route_id', CHtml::listData(RouteDetails::model()->findAll(), 'id', 'route_name'), array('prompt'=>Yii::t('app', 'Select route'))); ?>
                        <?php echo $form->error($model,'route_id'); ?>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>
                        <?php echo $form->labelEx($model,'status'); ?>
                    </td>
                    <td>&nbsp;</td>
                    <td>
                        <?php echo $form->dropDownList($model,'status',array(0=>Yii::t('app', 'Waiting for approval'), 1=>Yii::t('app', 'Approved')), array('prompt'=>Yii::t('app','Select status'))); ?>
                        <?php echo $form->error($model,'status'); ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Create') : Yii::t('app','Save'),array('class'=>'formbut')); ?>
	</div>
<?php $this->endWidget(); ?>