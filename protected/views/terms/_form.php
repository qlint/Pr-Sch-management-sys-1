<div class="form">
<div class="formConInner">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'terms-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><?php echo Yii::t('app','Fields with'); ?><span class="required">*</span> <?php echo Yii::t('app','are required.') ;?></p>


	<?php //echo $form->errorSummary($model); ?>

	<table width="100%" border="0" cellspacing="0" cellpadding="0">
  	<tr>
		<td><?php echo $form->labelEx($model,'term_id'); ?></td>
      
		<td><?php echo $form->dropDownList($model,'term_id',array('1' => Yii::t('app','Term 1'), '2' => Yii::t('app','Term 2')),array('empty' => Yii::t('app','Select Term'))); ?>
		<?php echo $form->error($model,'term_id'); ?></td>
	</tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
  </tr>
    
   <tr>
		<td><?php echo $form->labelEx($model,'academic_yr_id'); ?></td>
        <?php
                $academic_yrs = AcademicYears::model()->findAll("is_deleted =:x", array(':x'=>0));
                $academic_yr_options = CHtml::listData($academic_yrs,'id','name');
                ?>
                
		<td><?php echo $form->dropDownList($model,'academic_yr_id',$academic_yr_options,array(
                            'style'=>'width:130px ;','empty'=>Yii::t('app','Select')
                            )); ?>
		<?php echo $form->error($model,'academic_yr_id'); ?></td>
	</tr>
     <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
 	 </tr>

	<tr>
		<td><?php echo $form->labelEx($model,'start_date'); ?></td>
		<td><?php
			$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
				if($settings!=NULL)
				{
					$date = $settings->dateformat;
					if($model->start_date!=NULL){
						$model->start_date = date($settings->displaydate, strtotime($model->start_date));
					}
					if($model->end_date!=NULL){
						$model->end_date = date($settings->displaydate, strtotime($model->end_date));
					}										
				}
				else
				$date = 'dd-mm-yy';	
	//echo $form->textField($model,'joining_date',array('size'=>30,'maxlength'=>255));
				$this->widget('zii.widgets.jui.CJuiDatePicker', array(
                        //'name'=>'Students[date_of_birth]',
                        'attribute'=>'start_date',
                        'model'=>$model,
                        // additional javascript options for the date picker plugin
                        'options'=>array(
                        'showAnim'=>'fold',
                        'dateFormat'=>$date,
                        'changeMonth'=> true,
                        'changeYear'=>true,
                        'yearRange'=>'1900:'.(date('Y')+10)
                        ),
                        'htmlOptions'=>array(
                        'style'=>'width:92px;',
						'readonly'=>true,
                        ),
                        ));
	
	 ?>
		<?php echo $form->error($model,'start_date'); ?></td>
	</tr>
    
     <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
 	 </tr>

	<tr>
		<td><?php echo $form->labelEx($model,'end_date'); ?></td>
		<td><?php							
				$this->widget('zii.widgets.jui.CJuiDatePicker', array(
							//'name'=>'Employees[joining_date]',
							'attribute'=>'end_date',
							'model'=>$model,
							
							// additional javascript options for the date picker plugin
							'options'=>array(
								'showAnim'=>'fold',
								'dateFormat'=>$date,
								'changeMonth'=> true,
									'changeYear'=>true,
									'yearRange'=>'1900:'.(date('Y')+10)
							),
							'htmlOptions'=>array(
								//'style'=>'height:20px;'
								//'value' => date('m-d-y'),
								'readonly'=>"readonly"
							),
						))
	
	 ?>
		<?php echo $form->error($model,'end_date'); ?></td>
	</tr>
</table>  

	<div style="padding:20px 0 0 0px;">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Create') : Yii::t('app','Save'),array('class'=>'formbut')); ?>
		</div>

<?php $this->endWidget(); ?>

	</div><!-- form -->
</div>