<style>
#status input
{
    float:left;
}
#status label
{
    float:left;
}
.error-brd{
	border-color:#F30 !important;
}

</style>
<div class="formCon">
<div class="formConInner">
<div class="gayteway-box inputstyle"> 
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'gateway-form',
	'enableAjaxValidation'=>false,
)); ?>
 
	<p class="note"><?php echo Yii::t('app','Fields with');?> <span class="required"> * </span><?php echo Yii::t('app','are required');?>.</p>
                        <table width="100%">
                        <tbody>
                        	<tr>
                                <td>
                                <div class="osmst_form_style">                       
                                <?php echo $form->labelEx($model,'name'); ?>
                                <?php echo $form->textField($model,'name',array('maxlength'=>200)); ?>
                                <?php echo $form->error($model,'name'); ?>
                                </div>
                                </td>
                                </tr>
                                <tr><td height="20"></td></tr>
                                <tr>
                                <td>
                                <div class="osmst_form_style">
                                <?php echo $form->labelEx($model,'url'); ?>
                                <?php echo $form->textField($model,'url',array('maxlength'=>200)); ?>
                                <?php echo $form->error($model,'url'); ?>
                                </div>	
                                </td>
                                </tr>
                                <tr><td height="20"></td></tr>
                                <tr>

                                <td>
<?php echo $form->labelEx($model,'method'); ?>
<div class="osmst_form_style">
    <?php 
    if(!isset($model->method))
    {
    $model->method=1;
    }
    echo $form->radioButtonList($model,'method',array('1'=>  Yii::t('app','GET'),'2'=>Yii::t('app','POST')),array(
    'template'=>'{input}{label}',
    'separator'=>'',
    'labelOptions'=>array(
    'style'=> '
    padding-left:13px;
    width: 50px;
    float: left;
    '),
    'style'=>'float:left;',
    
    )    ); ?>
    <?php echo $form->error($model,'url'); ?>
    </div>
                                </td>
                                
                            </tr>
                            <tr><td height="20"></td></tr>
                        </tbody>
                        </table>



    
<?php /*?>    <?php echo CHtml::label('Parameter'); ?><?php */?>

                        <table width="100%">
                            <tr>
                                <td align="center">
                                    <a href="javascript:void(0);" title="<?php echo Yii::t("app", "Click to add another particular");?>" id="add-fee-particular" class="os-lg-btn"> <?php echo Yii::t("app", "Parameter Add");?></a> 
                                </td>
                            </tr>
                            <tr><td height="20"></td></tr>
                        </table>

                    
                    <div id="parameter" >
                            <?php $this->renderPartial('parameter',array('parameter'=>$parameter, 'ptrow'=>0));?>
                    </div>
                    
                    
    <table width="100%">
    <tr><td height="20"></td></tr>
    <tr><td><div class="osmst_form_style"><?php echo $form->labelEx($model,'responds_format'); ?>
    <?php echo $form->textArea($model,'responds_format',array('maxlength'=>200)); ?>
    <?php echo $form->error($model,'responds_format'); ?></div>
    </td>
    </tr>
    <tr><td height="20"></td></tr>
    </table>                   





<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save',array('class'=>'formbut')); ?>


	
<?php $this->endWidget(); ?>
</div>
</div>
</div><!-- form -->
<script>
var add_particular	= function(){
	var ptrow	= parseInt($("#parameter .gateway-parameter").last().attr("data-row")) + 1;
	$.ajax({
		url:'<?php echo Yii::app()->createUrl("/sms/gateway/addParameter");?>',
		type:'GET',
		data:{ptrow:ptrow},
		dataType:"json",
		success: function(response){
			if(response.status=="success"){
				var data	= $(response.data);
				$("#parameter").append(data);
				
				//scroll to new particualr
				$('html,body').animate({
					scrollTop: data.offset().top
				}, 'fast');
				
				setup_actions();
			}
			else{
				alert("<?php echo Yii::t("app", "Can't add particular");?>");
			}
		}
	});
};
var remove_particular	= function(that){
	var particular	= "";
	if($(that).closest('.gateway-parameter').find('input.particular-name[type="text"]').length>0 && $(that).closest('.gateway-parameter').find('input.particular-name[type="text"]').val()!=""){
		particular	= "`" + $(that).closest('.gateway-parameter').find('input.particular-name[type="text"]').val() + "`";
	}
        
      
	if($("#parameter .gateway-parameter").length>1){
		if(confirm("<?php echo Yii::t("app", "Are you sure");?> ?")){
			$(that).closest(".gateway-parameter").remove();
		}
	}
	else{
		alert("<?php echo Yii::t("app", "Can't remove. You need to create atleast one parameter !!");?>");
	}	
};
var setup_actions	= function(){
	
	$(".remove-particular").unbind('click').click(function(e) {
		var that	= this;
		remove_particular(that);
    });
	//add access
	$(".add-particular-access").unbind('click').click(function(e) {
		var that	= this;
		add_access(that);
    });
	
	
	
};

$('#add-fee-particular').click(function(e) {
	add_particular();
});

$("form#gateway-form").submit(function(e) {
	var that	= this;
	var data	= $(that).serialize();
	data	+= "&yid=" + $("#system_yid").val();
	$.ajax({
		url:'<?php echo Yii::app()->createUrl("/sms/gateway/create");?>',
		type:'POST',
		data:data,
		dataType:"json",
		success: function(response){
			$(that).find("input, select").attr('title', '');
			$(that).find("*").removeClass("error-brd");
			if(response.status=="success"){
				window.location.href	= response.redirect;
			}
			else if(response.hasOwnProperty("errors")){
				var errors	= response.errors;
				$.each(errors, function(attribute, earray){
					$.each(earray, function(index, error){
						$('#' + attribute).attr('title', error).addClass("error-brd");
					});										
				});				
			}
			else if(response.hasOwnProperty("message")){
				alert(response.message);
			}
			else{
				alert("<?php echo Yii::t("app", "Some problem found while saving data !!");?>");
			}
		}
	});
	
    return false;
});

setup_actions();
</script>