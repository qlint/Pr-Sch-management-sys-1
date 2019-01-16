<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'paypal-config-_paypals-form',
	'enableAjaxValidation'=>false,
)); ?>
	<div class='formCon'>
		<div class='formConInner'>
			<h3><?php echo Yii::t('app', 'Paypal Settings');?></h3>
			
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="20%"><?php echo $form->labelEx($gateway,'apiusername'); ?></td>
					<td><?php echo $form->textField($gateway,'apiusername'); ?>
				<?php echo $form->error($gateway,'apiusername'); ?></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td><?php echo $form->labelEx($gateway,'apipassword'); ?></td>
					<td><?php echo $form->textField($gateway,'apipassword'); ?>
				<?php echo $form->error($gateway,'apipassword'); ?></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td><?php echo $form->labelEx($gateway,'apisignature'); ?></td>
					<td><?php echo $form->textField($gateway,'apisignature'); ?>
				<?php echo $form->error($gateway,'apisignature'); ?></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td><?php echo $form->labelEx($gateway,'apicurrency'); ?></td>
					<td><?php
					$criteria 				= new CDbCriteria;
			        $criteria->condition 	= 'code<>:val';
			        $criteria->params 		= array(':val'=>"");
			        $list 					= CHtml::listData(Currency::model()->findAll($criteria), 'code', 'code');
			        echo $form->dropDownList($gateway,'apicurrency', $list);
		        ?>
				<?php echo $form->error($gateway,'apicurrency'); ?></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><?php echo CHtml::submitButton(Yii::t('app', 'Save Settings'), array('class'=>'formbut')); ?></td>
				</tr>
				
			</table>

		

			<div class="row">
				
				
			</div>

			<div class="row">
				
				
			</div>
		</div>
	</div>

	<div class="row buttons">
		
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->