<script type="text/javascript" src="assets/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="assets/ckeditor/adapters/jquery.js"></script>
<script type="text/javascript">
	$(document).ready(function () {
	var config =
	    {
		height: 300,
		width : '95%',
		resize_enabled : false,
		toolbar :

		[

		['Bold','Italic','Underline','Strike','-','Subscript','Superscript','-','SelectAll','RemoveFormat'],

		['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],

		['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],

	/*	['BidiLtr', 'BidiRtl'],

		['Link','Unlink','Anchor'],

		['Table','HorizontalRule','Smiley','SpecialChar','PageBreak','Iframe','-','Save','NewPage','Preview','-','Templates','-','Cut','Copy','Paste','PasteText','PasteFromWord'],

		'/',

		['Undo','Redo','-','Find','Replace','-','Styles','Format','Font','FontSize'],

		['TextColor','BGColor'],*/

		]

	};
        //Set for the CKEditor
		$('#Message_body').ckeditor(config);

    });
</script>
      <div id="parent_Sect">
        <?php $this->renderPartial('leftside');?> 
        <div id="parent_rightSect">
        	<div class="parentright_innercon">
             <h1><?php echo Yii::t('app','New Message'); ?></h1>
               
                  <div class="form">
                        <?php $form = $this->beginWidget('CActiveForm', array(
                            'id'=>'message-form',
                            'enableAjaxValidation'=>false,
                        )); ?>
                    
                        <?php //echo $form->errorSummary($msg); ?>
                    
                        <div class="row">
                         <?php echo $form->labelEx($message,'receiver_id'); ?>
                          <?php //echo $form->textField($message,'receiver_id',array('size'=>60,'maxlength'=>255)); ?>
                         <?php echo CHtml::textField('receiver', $receiverName,array('size'=>60,'maxlength'=>255)) ?>
                            <?php echo $form->hiddenField($message,'receiver_id'); ?>
                            <?php echo $form->error($message,'receiver_id'); ?>
                        </div>
                    
                        <div class="row">
                            <?php echo $form->labelEx($message,'subject'); ?>
                            <?php echo $form->textField($message,'subject',array('size'=>60,'maxlength'=>255)); ?>
                            <?php echo $form->error($message,'subject'); ?>
                        </div>
                    
                        <div class="row">
                            <?php echo $form->labelEx($message,Yii::t('app','Message')); ?>
                            <?php echo $form->textArea($message,'body',array('class'=>'txtarea')); ?>
                            <?php echo $form->error($message,'body'); ?>
                        </div>
                    
                        <div style="margin-top:10px;">
                            <?php echo CHtml::submitButton(Yii::t('app','Send'),array('class'=>'formbut')); ?>
                        </div>
                    
                        <?php $this->endWidget(); ?>
                    </div>
                       
                 
            </div>
        </div>
        <div class="clear"></div>
      </div>
      <!--innersection ends here-->

