<style>
    .err_msg
    {
        color: red;
    }
    .option_error { border:  1px solid red !important;  }
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
		language:'<?php echo Yii::app()->language;?>',
		height: 200,
		width : '100%',
		resize_enabled : false,
		enterMode : CKEDITOR.ENTER_BR,
		shiftEnterMode: CKEDITOR.ENTER_P,
		toolbar :
		[

		['Bold','Italic','Underline','Strike','-','Subscript','Superscript','-','SelectAll','RemoveFormat'],

		['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],

		['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],

		]

	};
        //Set for the CKEditor
		$('#OnlineExamQuestions_question').ckeditor(config);
                $('#OnlineExamQuestions_exam_answer').ckeditor(config);

    });


  
</script>
<div class="form-group">
    <p class="note"><?php echo Yii::t("app",'Fields with');?> <span class="required">*</span><?php echo Yii::t("app", 'are required.');?></p>
    <?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'online-question-form',
	'enableAjaxValidation'=>false,
        )); ?>
    <?php 
    if(isset($_REQUEST['qid']) && $_REQUEST['qid']!=NULL)
    {
        
        $model->exam_id = $_REQUEST['qid'];  
        echo $form->hiddenField($model,'exam_id', array('class'=>'form-control')); 
    }
    ?>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <?php echo $form->labelEx($model,'question'); ?>
            <?php echo $form->textArea($model,'question', array('class'=>'form-control')); ?>
            <div class="err_msg" id="question_error"></div>
        </div>
    </div>
</div>

<div class="row">
<div class="col-md-12">	
    <div class="yellow-bg yellow-bg-cnt">
        <div class="row">
            <div class="col-md-12">
                <div class="">	
                    <div class="form-group custom-radioBtn" id="RadioButtonList">
                        <h3> <?php echo $form->labelEx($model,'question_type'); ?></h3>
                        <?php
                        $data= array('1'=>Yii::t('app','Multi choice'), '2'=>Yii::t('app','True/False'), '3'=>Yii::t('app','Short Answer'), '4'=>Yii::t('app','Multi Line'));
                        echo $form->radioButtonList($model, 'question_type',$data, array('class'=>'qp_custom-type','labelOptions'=>array('style'=>'','class'=>'qp_type'),  'separator'=>'  ', ) );                                                                                                                            
                        ?>
                        <div class="err_msg" id="type_error"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="display: none;" id="multi-row">
        <div class="row">
            <div class="col-md-12">
                <div class="mark-bg-inner">
                    <div class="row">    
                        <div class="col-md-12">     
                        <div class="Question-block"> 
                        <h3><?php echo Yii::t('app','Options'); ?></h3>
                        </div>
                        </div>
                        <div class="question-m-blk">
                        <div class="form-group">
                        <div id="choices" >
                            <?php $this->renderPartial('qp_add_question_choices',array('model'=>$model, 'ptrow'=>0));?>
                        </div>
                        <div class="err_msg" id="options_error"></div>
                        <div class="err_msg" id="choice_answer_error"></div>
                        </div>
                        </div>
                        <div class="col-md-12">     
                        <div class="a-btn-block">
                        <a href="javascript:void(0);" title="<?php echo Yii::t("app", "Click to add another choice");?>" id="add-choice" class="a-add-btn">
                        <?php echo Yii::t("app", "Add");?>
                        </a> 
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </div>    
    <div class="row"  style="display: none;" id="type-row">
        <div class="row">
            <div class="col-md-12">
                <div class="mark-bg-inner">
                    <div class="row"> 
                        <div class="col-md-12">
                            <div class="form-group online-q-aType" id="RadioButtonType">
                            <h3><?php echo Yii::t('app','Answer'); ?> *</h3>
                            <?php
                            $data= array('1'=>Yii::t('app','True'), '0'=>Yii::t('app','False'));
                            echo $form->radioButtonList($model, 'type_answer',$data, array('labelOptions'=>array('style'=>''),  'separator'=>'  ', ) );                                                                                                                            
                            ?>
                            <div class="err_msg" id="type_answer_error"></div>
                            </div>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
    </div>                
    <div class="row" style="display: none;" id="multi-line-row">
        <div class="row">
            <div class="col-md-12">
                <div class="mark-bg-inner">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                               <h3> <?php echo $form->labelEx($model,'exam_answer'); ?> *</h3>
                                <?php echo $form->textArea($model,'exam_answer', array('class'=>'form-control')); ?>
                                <div class="err_msg" id="text_answer_error"></div>
                            </div>
                        </div>
                    </div> 
                </div>
            </div>
        </div>            
    </div>     
    <div class="row">
        <div class="col-md-12">
            <div class="mark-bg-inner">
            <?php echo $form->labelEx($model,'mark'); ?> *
                <?php 
                if(isset($model->mark) && $model->mark!='')
                {
                $model->mark= floatval($model->mark);
                }
                
                echo $form->textField($model,'mark', array('class'=>'form-control')); ?>
                <div class="err_msg" id="mark_error"></div>
            </div>     
        </div>
    </div>    
</div>

    <div class="txtfld-col-btn btn-top">
        <div class="txtfld-col-btn-block">
            <div class="buttons">
				<?php echo CHtml::Button(Yii::t("app",'Save'),array('class'=>'btn btn-danger','id'=>'save_btn','data'=>0)); ?>
                <?php echo CHtml::Button(Yii::t("app",'Save and add another'),array('class'=>'btn btn-danger','id'=>'save_btns','data'=>1)); ?>
            </div>
        </div>
    </div>

    
    <?php $this->endWidget(); ?>
</div>



