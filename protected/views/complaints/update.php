<style type="text/css">
#partial{
	height:auto !important;
}
.row{
	margin-top:20px;
}
</style>

<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ui-style.css" />


<?php 
$this->beginWidget('zii.widgets.jui.CJuiDialog',array(
                'id'=>'partial',
                'options'=>array(
                    'title'=>Yii::t('app','Update Comment'),
                    'autoOpen'=>true,
                    'modal'=>'true',
					'width'=>'400',
					'height'=>'auto',
					'resizable'=>false,		
                ),
                ));
				
?>

    
    
        <?php $form=$this->beginWidget('CActiveForm', array(
            'id'=>'course_status_form',
            'enableAjaxValidation'=>false,
        )); ?>
        
        <div style="width:90%"> 
        	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                	<td width="90%"><?php echo Yii::t('app','Comment');?> <span class="required">*</span></td>
                </tr>
                <tr>
                	<td>
						<?php 
							echo $form->textArea($model,'feedback',array('cols'=>100,'rows'=>4,'value'=>ucfirst($model->feedback),'id'=>'feedback'.$model->id)); 
							echo $form->hiddenField($model,'id',array('size'=>20,'value'=>$model->id,)); 
						?>
                    	<div id="feedback_error<?php echo $model->id; ?>" style="color:#F00"></div>
                    </td>
                </tr>
            </table>   
        </div>                
        
        <div class="row">
            <?php
            echo CHtml::ajaxSubmitButton(Yii::t('app','Save'),CHtml::normalizeUrl(array('Complaints/display')),array('dataType'=>'json','success'=>'js: 				
                function(data) { 
                        $(".errorMessage").remove();									
                        if(data.status == "success")
                        {
                                //$("#course_status'.$model->id.'").dialog("close");
                                window.location.reload();

                        }
                        else if(data.status=="error")
                        {
                                var errors	= JSON.parse(data.errors);

                                 $.each(errors, function(index, value){
                                        var err	= $("<div class=\"errorMessage\" />").text(value[0]);
                                        err.insertAfter($("#" + index));
                                });										


                        }
                          //window.location.reload();
                }'),array('style'=>'padding:6px; cursor:pointer','id'=>'closeDialog'.$model->id,'name'=>'save')); 
            ?>
        </div>
         <?php $this->endWidget(); ?>
    

<?php $this->endWidget('zii.widgets.jui.CJuiDialog');?>
<script type="text/javascript">
$('#closeDialog<?php echo $model->id; ?>').click(function(ev) {
	var comment = $('#feedback<?php echo $model->id; ?>').val(); 		
	if(comment == '')
	{		   
		$('#feedback_error<?php echo $model->id; ?>').html('<?php echo Yii::t('app','Comment cannot be blank'); ?>');
		return false;
	}
});
</script>


