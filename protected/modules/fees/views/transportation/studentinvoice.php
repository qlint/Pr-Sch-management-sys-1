<?php $this->breadcrumbs=array(
	Yii::t('app','Transport')=>array('/transport'),
	Yii::t('app','Transport Fee Management')=>array('/transport/transportation/viewall'),
	Yii::t('app','viewall'),
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
                <h1><?php echo Yii::t('app','Generate Invoice');?></h1>
                <div class="formCon">
                    <div class="formConInner">
                        <div class="txtfld-col-box">
                            <div class="txtfld-col">
                                <label ><?php echo Yii::t('app','Select Month'); ?></label>
                                <?php $form=$this->beginWidget('CActiveForm', array(
                                    'id'=>'invoices-form',
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
                                  
                            </div>
                           <div class="txtfld-col">
                            <br />
                             <?php echo CHtml::submitButton(Yii::t("app", 'Generate'), array('name'=>'','class'=>'formbut')); ?>
                            </div>
                        </div>
                     <?php $this->endWidget(); ?>
                    </div>
                </div>
                
                
                
                
                
                




