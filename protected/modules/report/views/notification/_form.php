<style type="text/css">
	
.formCon input[type="text"], input[type="password"], textArea, select {
    background: none repeat scroll 0 0 #FFFFFF;
    border: 1px solid #C2CFD8;
    border-radius: 2px;
    box-shadow: -1px 1px 2px #D5DBE0 inset;
    padding: 6px;
    width: 260px !important;
}

textArea{ width:350px !important;}

.formbut_yellow button, input[type="submit"] {
    background: url("images/fbut-bg.png") repeat-x scroll 0 0 rgba(0, 0, 0, 0) !important;
    border: 1px solid #B58530 !important;
	
}

.cke_show_borders table.cke_show_border, .cke_show_borders table.cke_show_border > tr > td, .cke_show_borders table.cke_show_border > tr > th, .cke_show_borders table.cke_show_border > tbody > tr > td, .cke_show_borders table.cke_show_border > tbody > tr > th, .cke_show_borders table.cke_show_border > thead > tr > td, .cke_show_borders table.cke_show_border > thead > tr > th, .cke_show_borders table.cke_show_border > tfoot > tr > td, .cke_show_borders table.cke_show_border > tfoot > tr > th{ border:0px solid !important;
background-color:#fff !important;}

</style>
<script type="text/javascript" src="assets/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="assets/ckeditor/adapters/jquery.js"></script>
<script type="text/javascript">
  window.parent.CKEDITOR.tools.callFunction(CKEditorFuncNum, 
    url, errorMessage);
</script>
<script type="text/javascript">
$(document).ready(function () {
	var config =
	    {
		height: 400,
		width : '95%',
		resize_enabled : false,
		toolbar :

		[

		['Bold','Italic','Underline','Strike','-','Subscript','Superscript','-','SelectAll','RemoveFormat'],

		['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],

		['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],

		]

	};
        //Set for the CKEditor
		$('#Message_text').ckeditor(config);

    });


  
</script>


<div class="formCon" style="width:96%;">
<div class="formConInner">
<div class="form" >
<div class="class="listbxtop_hdng">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'sms-templates-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><?php echo Yii::t('app','Fields with').'<span class="required">'.'*'.'</span>'.Yii::t('app','are required.') ;?></p>

	<?php echo $form->errorSummary($model); ?>
    
  
	<div class="row">
		<?php echo $form->labelEx($model,'template'); ?>
		<?php echo $form->textArea($model,'template',array('rows'=>6, 'cols'=>50,'id'=>'Message_text')); ?>
		<?php echo $form->error($model,'template'); ?>
	</div>
   <br />

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Create') : Yii::t('app','Save'),array('class'=>'formbut')); ?>
	</div>

<?php $this->endWidget(); ?>
</div>
</div>
</div>
</div><!-- form -->