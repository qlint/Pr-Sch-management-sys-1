<style>
.ui-widget-content{
	 height:auto !important;
	  width: 488px !important;	
}

</style>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'leave-form',	
)); ?>

<p style="padding-left:20px;"><?php echo Yii::t('app','Fields with');?><span class="required"> * </span><?php echo Yii::t('app','are required').'.';?></p>
   
<div class="formCon" style="width:430px; height:auto;">
<div class="formConInner" style="width:400px;">
<div  style="background:none">
	<?php if(isset($_REQUEST['id']) and $_REQUEST['id']){ ?>
		<input type="hidden" value="<?php echo $_REQUEST['id']; ?>" name="id" />
    <?php } ?> 
<table width="100%" border="0" cellspacing="0" cellpadding="0">  
    <tr>
       	<?php echo $form->labelEx($model,Yii::t('app','cancel_reason')); ?>
		<?php echo $form->textField($model,'cancel_reason',array('size'=>60,'maxlength'=>120)); ?>
		<?php echo $form->error($model,'cancel_reason'); ?>
    </tr>
    
    <tr>
        <td>&nbsp;</td>
     </tr>
</table>


	<div style="padding:20px 0 0 0px; text-align:left">
		 <?php	echo CHtml::ajaxSubmitButton(Yii::t('app','Save'),CHtml::normalizeUrl(array('/teachersportal/leaves/cancel','render'=>false)),array('dataType'=>'json','success'=>'js: function(data) {
					if (data.status == "success")
					{
					 $("#jobDialog").dialog("close");
					 if(data.flag==1)
					 {
						 //window.location.href = "'.Yii::app()->request->baseUrl.'/index.php?r=/teachersportal/leaves/index"; 
						 window.location.reload();
					 }
					}
					else{
						$(".errorMessage").remove();
						var errors	= JSON.parse(data.errors);						
						$.each(errors, function(index, value){
							var err	= $("<div class=\"errorMessage\" />").text(value[0]);
							err.insertAfter($("#" + index));
						});
					}
                       
                    }'),array('id'=>'closeJobDialog','name'=>Yii::t('app','Submit'))); ?>
	</div>

<?php $this->endWidget(); ?>
</div>
</div>
</div><!-- form -->
