<?php
$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
if($settings!=NULL)
    $date=$settings->dateformat;
else
    $date = 'dd-mm-yy';
?>
<tr>
    <td align="center"><span id="new-count"><?php echo count($alltransactions) + 1;?></span></td>
    <td height="18">
    	<?php echo $form->hiddenField($transaction,'invoice_id'); ?>
        <?php
        	$this->widget('zii.widgets.jui.CJuiDatePicker',array(
				'model'=>$transaction,
				'attribute'=>'date',
				// additional javascript options for the date picker plugin
				'options'=>array(
					'showAnim'=>'fold',
                    'dateFormat'=>$date,
                    'changeMonth'=> true,
                    'changeYear'=>true,
                    'yearRange'=>'1900:'.(date('Y')+5)
				),
				'htmlOptions'=>array(
					'style'=>'width:80px;',
                    'readonly'=>'readonly'
				),
			));
		?>
    </td>                                            
    <td height="18">
        <?php
            echo $form->dropDownList($transaction,'payment_type', CHtml::listData(FeePaymentTypes::model()->findAllByAttributes(array('is_active'=>1, 'is_gateway'=>0)), 'id', 'type'), array('style'=>'width:80px;', 'prompt'=>Yii::t('app','Payment type')));
        ?>
    </td>
    <td height="18">
        <?php echo $form->textField($transaction,'transaction_id',array('style'=>'width:80px;')); ?>
    </td>
    <td height="18">
        <?php echo $form->textField($transaction,'description',array('style'=>'width:80px;')); ?>
    </td>                          
    <td height="18">
        <?php echo $form->textField($transaction,'amount',array('style'=>'width:50px;')); ?>
    </td>
    <td height="18">
        <a href="javascript:void(0);" id="FeeTransactions_proof" class="input"><?php echo Yii::t('app', 'File'); ?></a>
    </td>
    <td height="18">
        -
    </td>
    <td height="18">
        <?php echo CHtml::submitButton(Yii::t("app", "Add"), array("class"=>"formbut"));?>
    </td>
</tr>