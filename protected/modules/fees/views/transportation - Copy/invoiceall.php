<?php $this->breadcrumbs=array(
	Yii::t('app','Fees')=>array('/fees'),
	Yii::t('app','Transport Fee Management')=>array('/fees/transportation'),
	Yii::t('app','Generate Invoice'),
);
$settings	= UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
if($settings!=NULL){ 
	$date			=	str_ireplace("d","",$settings->dateformat); 
}else{ 
	$date			=	str_ireplace("d","",$settings->dateformat); 	
}
?>


<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">
        	<?php $this->renderPartial('/default/left_side');?>  
        </td>
        <td valign="top">
            <div class="cont_right">
                <h1><?php echo Yii::t('app','Transportation Fee');?></h1>
                <div class="pdtab_Con" style="padding-top:0px;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr class="pdtab-h">
                            <td><strong><?php echo Yii::t('app','Select Month');?></strong></td>
                            <td>
                                <?php $form=$this->beginWidget('CActiveForm', array(
                                    'id'=>'invoiceall-form',
                                    'enableAjaxValidation'=>false,
                                )); ?>
                                <?php
                                $month	='';
                                $this->widget('ext.EJuiMonthPicker.EJuiMonthPicker', array(
                                    'name' => 'invoice_month',
                                    'value'=>$month,
                                    'options'=>array(
                                        'yearRange'=>'-5:+5',
                                        'dateFormat'=>$date,
                                    ),
                                    'htmlOptions'=>array(
                                        'id' => 'invoice_month',
                                        'readonly'=>true
                                    ),
                                ));  
                                ?>
                               
                            </td>
                        </tr>
                        <tr class="pdtab-h">
                            <td></td>
                            <td>
                            <div style="padding:0px 0 0 0px; text-align:left">
                            <?php echo CHtml::submitButton(Yii::t('app','Generate'),array('id'=>'submit_button_form','class'=>'formbut')); ?>
                            </div>
                            </td>
                        </tr>
                    </table>
                  <?php $this->endWidget(); ?>
                 </div>
             </div>  
        </td>
    </tr>
</table>
				