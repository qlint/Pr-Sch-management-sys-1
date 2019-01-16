<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'bus-log-form',
	'enableAjaxValidation'=>false,
)); ?>
	<p><?php echo Yii::t('app','Fields with');?><span class="required">*</span><?php echo Yii::t('app','are required.');?></p>
	

	<?php echo $form->errorSummary($model); ?>
    <div class="formCon">
        <div class="formConInner">
<div class="text-fild-bg-block">  
				<?php 
                if(isset($_REQUEST['vehicle_id']) and $_REQUEST['vehicle_id'] != NULL)
                {
                    $vehicle = VehicleDetails::model()->findByAttributes(array('id'=>$_REQUEST['vehicle_id']));
                }
                ?>

                    <div class="text-fild-block inputstyle">
                    <span style="float:left;"><label><?php echo Yii::t('app','Select Vehicle');?></label></span> <span class="required">*</span> 

                    <?php $criteria = new CDbCriteria;
                    $criteria->compare('is_deleted',0); ?>
                    <?php echo $form->dropDownList($model,'vehicle_id',CHtml::listData(VehicleDetails::model()->findAll($criteria),'id','vehicle_code'),array('prompt'=>Yii::t('app','Select')));?>
                    <?php echo $form->error($model,'vehicle_id'); ?>

                    </div>

                     <div class="text-fild-block inputstyle">
                    <?php echo $form->labelEx($model,'start_time_reading'); ?>

                    <?php echo $form->textField($model,'start_time_reading',array()); ?>
                    <?php echo $form->error($model,'start_time_reading'); ?>
                      </div>

                  <div class="text-fild-block inputstyle">
<?php echo $form->labelEx($model,'end_time_reading'); ?>

                    <?php echo $form->textField($model,'end_time_reading',array()); ?> 
                    <?php echo $form->error($model,'end_time_reading'); ?>
                     </div>
                     </div>

        </div> <!-- END div class="formConInner" -->
    </div> <!-- END div class="formCon" -->
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Create') : Yii::t('app','Save'),array('class'=>'formbut'));  ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->