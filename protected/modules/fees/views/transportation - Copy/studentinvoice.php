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
                <h1><?php echo Yii::t('app','Transportation Fee');?></h1>
				<div class="pdtab_Con" style="padding-top:0px;">
                   <table width="100%" cellspacing="0" cellpadding="0" border="0">
                     <tbody>
                          <tr class="pdtab-h">
                          	<td><?php echo Yii::t('app','Select Month&Year'); ?></td>
                            <td>
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
                             </td>
                          </tr>
                            <tr>
                           		 <td colspan="4" style="padding-top:9px;"><?php echo CHtml::submitButton(Yii::t("app", 'Generate'), array('name'=>'','class'=>'formbut')); ?></td>
                            </tr>
                     </tbody>
                    </table>
                   <?php $this->endWidget();?>
                  
               </div>



