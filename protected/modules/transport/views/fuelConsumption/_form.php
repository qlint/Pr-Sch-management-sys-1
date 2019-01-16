<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'fuel-consumption-form',
	'enableAjaxValidation'=>false,
)); ?>

<p style="padding-left:20px;"><?php echo Yii::t('app','Fields with');?><span class="required"> * </span><?php echo Yii::t('app','are required.');?></p>


	<?php echo $form->errorSummary($model); ?>
<?php  
if(isset($_REQUEST['id']) && ($_REQUEST['id']!=NULL))
{
	$route=BusLog::model()->findByAttributes(array('id'=>$_REQUEST['id']));
	if($route!=NULL)
	{
		$id=$route->vehicle_id;
	}
?>
 <div class="formCon" >
<div class="formConInner">
<?php echo $form->hiddenField($model,'vehicle_id',array('size'=>20,'value'=>$id)); ?>
<?php echo $form->error($model,'vehicle_id'); ?>
<table width="80%" border="0" cellspacing="0" cellpadding="0">
        <tr>
        	<td colspan="3">&nbsp;</td>
        </tr>
         <tr>
            <td>
               <?php echo $form->labelEx($model,'fuel_consumed'); ?>
               
            </td>
            <td>&nbsp;
            </td>
            <td>
             <?php echo $form->textField($model,'fuel_consumed',array('size'=>20)); ?>
                <?php echo $form->error($model,'fuel_consumed'); ?>
            </td>
        </tr>
        <tr>
        	<td>&nbsp;
            </td>
            <td>&nbsp;
            </td>
            <td>&nbsp;
            </td>
        </tr>
         <tr>
            <td>
               <?php echo $form->labelEx($model,'amount'); ?>
            </td>
            <td>&nbsp;
            </td>
            <td>
             <?php echo $form->textField($model,'amount',array('size'=>20)); ?>
                <?php echo $form->error($model,'amount'); ?>
            </td>
        </tr>
        <tr>
        	<td>&nbsp;
            </td>
            <td>&nbsp;
            </td>
            <td>&nbsp;
            </td>
        </tr>
         <tr>
            <td>
               <?php echo $form->labelEx($model,'consumed_date'); ?>
              
            </td>
            <td>&nbsp;
            </td>
            <td>
            <?php //echo $form->textField($model,'admission_date');
			$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
	if($settings!=NULL)
	{
		$date=$settings->dateformat;
		
		
	}
	else
	$date = 'dd-mm-yy';	
				$this->widget('zii.widgets.jui.CJuiDatePicker', array(
								//'name'=>'Students[admission_date]',
								'model'=>$model,
								'attribute'=>'consumed_date',
								// additional javascript options for the date picker plugin
								'options'=>array(
									'showAnim'=>'fold',
									'dateFormat'=>$date,
									'changeMonth'=> true,
									'changeYear'=>true,
									'yearRange'=>'1900:'
								),
								'htmlOptions'=>array(
									'style'=>'height:20px;'
								),
							));
		 ?>
                <?php echo $form->error($model,'consumed_date'); ?>
            </td>
        </tr>
        <tr>
        	<td>&nbsp;
            </td>
            <td>&nbsp;
            </td>
            <td>&nbsp;
            </td>
        </tr>
        </table>
        </div>
        </div>

<?php } ?>
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Create') : Yii::t('app','Save'),array('class'=>'formbut')); ?>
		
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->