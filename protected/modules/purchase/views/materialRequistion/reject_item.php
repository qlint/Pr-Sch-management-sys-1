<style>
#jobDialog_comment{
	height:auto !important;
}
.seen-by{    margin: 0px 0px 17px 4px; padding:0px;}
.seen-by li{ list-style:none; display:block; background:url(images/bread-arrow.png) no-repeat left 3px; color:#868686;    padding: 0px 10px;}
.name-icon1{ background:url(images/bread-arrow.png) no-repeat left}
.seen-h4{     border-bottom: 1px solid#ececec;margin-bottom: 5px;}
.seen-h4 h4{ font-size:12px; font-family:Tahoma, Geneva, sans-serif; font-weight:600; color:#444; margin: 0px 0px 5px 4px;}
<?php /*?>.ui-dialog .ui-dialog-title {
    float: left;
    color: #585858;
	font-weight: 300;
    background:url(images/info-icon.png) no-repeat left;
    padding: 3px 31px;
}<?php */?>

.ui-dialog {
    width: 273px !important;
    height: 350px !important;
}
.table-border-new td{ border:1px solid #C0DEF3; padding:7px 5px !important;border-collapse: collapse;}
.table-border-new{ margin-top:15px;}
.ui-dialog-content{ height:300px !important;}

.ui-widget input[type="text"]{
	width:94% !important;	
}

</style>
<script>

$(function() {   
    $("#PurchaseMaterialRequistion_return_date").click(function() {
        $(this).datepicker().datepicker("show");
    });
});
</script>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ui-style.css" />
<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog',array(
	'id'=>'jobDialog',
	'options'=>array(
		'title'=>Yii::t('app','Return issued item '),
		'autoOpen'=>true,
		'modal'=>'true',
		'width'=>'323',
		'height'=>'auto',
		'resizable'=>false,
	),
));
$settings = UserSettings::model()->findByAttributes(array('user_id'=>1));
	if($settings!=NULL){
		$dateformat		= $settings->dateformat;
		$displaydate	= $settings->displaydate;	
	}else{
		$dateformat 	= 'dd-mm-yy';
		$displaydate	= 'Y-m-d'; 
	}
$form=$this->beginWidget('CActiveForm', array('id'=>'leave-approve-form',));
	?>
        <table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td>
                    <div class="popup-leaverqst">
						<?php echo $form->labelEx($model,'return_date'); ?>
                        <?php
						if($model->return_date == '0000-00-00' or $model->return_date ='' or $model->return_date=NULL)
							$model->return_date='';
						else
							$model->return_date	= date($displaydate, strtotime($model->return_date));
							
							$this->widget('zii.widgets.jui.CJuiDatePicker', array(
							'attribute'=>'return_date',                                                        
							'model'=>$model,
							'options'=>array(
								'showAnim'=>'fold',
								'dateFormat'=>$dateformat,
								'changeMonth'=> true,
								'changeYear'=>true,
								'yearRange'=>(date('Y')-5).":".(date('Y')+5)
							),
							'htmlOptions'=>array(
								'readonly'=>true,
								'value'=>''
							),
                        ))?>
                        <?php echo $form->error($model,'return_date'); ?>              
                    </div>
                </td>
            </tr>
        
            <tr>         
                <td width="100%" colspan="2">
                    <div class="popup-leaverqst">
						<?php echo $form->labelEx($model,'return_reason') ?>
                        <?php echo $form->textArea($model,'return_reason',array('size'=>200,'maxlength'=>1000,'style'=>'width:100%')); ?>
                        <?php echo $form->error($model,'return_reason'); ?>              
                    </div>
                </td>
            </tr>
            <tr>
             <td width="100%" colspan="2">
                   <div class="row buttons">
        	<?php
				echo CHtml::ajaxSubmitButton(Yii::t('app','Return'),
					'',
					array(
						'dataType'=>'json',
						'success'=>'js: function(data) {
							$(".errorMessage").remove();
							if (data.status == "success"){
								$("#jobDialog").dialog("close");
								window.location.reload();
							}
							else{								
								var errors	= data.errors;
								$.each(errors, function(index, value){
									var err	= $("<div class=\"errorMessage\" />");
									err.html(value[0]);
									err.insertAfter($("#PurchaseMaterialRequistion_" + index));
								});
							}					
						}'
					), array('id'=>'closeJobDialog')
				); ?>
        </div>
               </td>
            </tr>
        </table>
    <?php $this->endWidget(); ?>

<?php $this->endWidget('zii.widgets.jui.CJuiDialog');?>
