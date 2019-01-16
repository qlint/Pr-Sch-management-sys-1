
<?php
                    $this->breadcrumbs=array(
                           Yii::t('app','Settings')=>array('/configurations'),
                            Yii::t('app','Backup'),
                    );?>


<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">
        	<?php $this->renderPartial('left_side'); ?>
        </td>
        <td valign="top">
		<div class="cont_right formWrapper">
            
            <h1><?php echo Yii::t('app',ucfirst($this->action->id)); ?></h1>

                <div class="form center-fileupload">


                <?php $form = $this->beginWidget('CActiveForm', array(
                        'id' => 'install-form',
                        'enableAjaxValidation' => true,
                        'htmlOptions'=>array('enctype'=>'multipart/form-data'),
                ));
                ?>
                                <div class="row">
                                <?php echo $form->labelEx($model,'upload_file'); ?>
                                <p class="file-uplaoad">Only .sql format are allowed</p>
                                <label class="fileContainer fileContainer-stl ">
                                 Click here to upload the file !
                                <?php echo $form->fileField($model,'upload_file',array('id'=>'file_backup')); ?>
                               </label>
                                <?php echo $form->error($model,'upload_file'); ?>
                                </div><!-- row -->	
								
								
<div class="file-uplad-btn">
                <?php
                        echo CHtml::submitButton(Yii::t('app', 'Save'),array('id'=>'uploadbtn','class'=>''));
                        $this->endWidget();
                ?>
                </div>
                </div><!-- form -->
			</div>
            
        </td>
    </tr>
</table>

<script>
  $("#file_backup").change(function () {
        var fileExtension = ['sql'];
        if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
            alert('<?php echo Yii::t('app',"Only .sql format are allowed"); ?>');
            $('#file_backup').val('');
        }
    });
        
        
    
</script>
<style>
.fileContainer {
    overflow: hidden;
    position: relative;
}

.fileContainer [type=file] {
    cursor: inherit;
    display: block;
    font-size: 999px;
    filter: alpha(opacity=0);
    min-height: 100%;
    min-width: 100%;
    opacity: 0;
    position: absolute;
    right: 0;
    text-align: right;
    top: 0;
}

/* Example stylistic flourishes */

.fileContainer-stl {
border-radius: 3px;
padding: 12px 19px;
color: #004085;
background-color: #cce5ff;
border: 1px solid #9dbcdb;
font-size: 13px;
font-weight: 600;
display: inline-block;
margin: 12px 0px;
}

.fileContainer [type=file] {
    cursor: pointer;
}


</style>

