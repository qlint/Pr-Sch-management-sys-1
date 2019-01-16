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
		height: 200,
		width : '95%',
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
    if(isset($_REQUEST['id']) && $_REQUEST['id']!=NULL)
    {
        $model->id = $_REQUEST['id'];  
        echo $form->hiddenField($model,'id', array('class'=>'form-control'));
    }
    echo $form->hiddenField($model,'exam_id', array('class'=>'form-control'));
    $type1_status= 'none';
    $type2_status= 'none';
    $type3_status= 'none';
    if($model->question_type==1)
    {
        $type1_status ='block';
    }
    else if($model->question_type==2)
    {
        $type2_status ='block';
    }
    else if(in_array($model->question_type, array(3,4)))
    {
        $type3_status ='block';
    }
        
    ?>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <?php echo $form->labelEx($model,'question'); ?>
                    <?php echo $form->textArea($model,'question', array('class'=>'form-control')); ?>
                    <div class="err_msg" id="question_error"></div>
                </div>
            </div>
        </div>
     <br>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group online-q-aType" id="RadioButtonList">
                    <?php echo $form->labelEx($model,'question_type'); ?><br>
                    <?php
                        $data= array('1'=>Yii::t('app','Multi choice'), '2'=>Yii::t('app','True/False'), '3'=>Yii::t('app','Short Answer'), '4'=>Yii::t('app','Multi Line'));
                        echo $form->radioButtonList($model, 'question_type',$data, array('labelOptions'=>array('style'=>'','class'=>'qp_type'),  'separator'=>'  ', ) );                                                                                                                            
                    ?>
                    <div class="err_msg" id="type_error"></div>
                </div>
            </div>
        </div>
    
    <div class="row" style="display: <?php echo $type1_status; ?>" id="multi-row"><br>
            <div class="col-sm-4">
                <div class="form-group">  
                    <?php 
                    $choice_array= array();
                    $index_array= array();
                    if($model->question_type==1)
                    {
                    $answer	= 	OnlineExamAnswers::model()->findAllByAttributes(array('question_id'=>$model->id));
                    if($answer!=NULL)
                    {
                        foreach($answer as $option)
                        {
                            $index_array[]=$option->id; 
                            $choice_array[$option->id]= $option->answer;
                        }                        
                    }
                    $choice_answer_id = array_search($model->answer_id, ($index_array));                    
                    }
                   ?><br>
                    
                    <div class="Question-block"> 
                        <?php echo Yii::t('app','Options'); ?>
                    </div>
                   
                    <div id="choices" >
                        <?php $this->renderPartial('add_question_choices',array('model'=>$model, 'ptrow'=>0));?>
                    </div>
                    
                    <div class="err_msg" id="options_error"></div>
                    <div class="err_msg" id="choice_answer_error"></div>
                    <div class="section-add">
                        <a href="javascript:void(0);" title="<?php echo Yii::t("app", "Click to add another choice");?>" id="add-choice" class="addicon">
                         <?php echo Yii::t("app", "Add");?>
                        </a> 
                    </div>
                </div>
            </div>
        </div>
     
        </div>
     
        <div class="row"  style="display: <?php echo $type2_status; ?>" id="type-row">
            <br>
            <div class="col-sm-12">
                <div class="form-group online-q-aType" id="RadioButtonType">
                    <?php echo Yii::t('app','Answer'); ?> *<br>
                    <?php
                    if($model->question_type==2)
                    {
                        $answer_model	= 	OnlineExamAnswers::model()->findByPk($model->answer_id);
                        if($answer_model!=NULL)
                        {                                   
                           $model->type_answer= $answer_model->answer; 
                        }
                    }
                        $data= array('1'=>Yii::t('app','True'), '0'=>Yii::t('app','False'));
                        echo $form->radioButtonList($model, 'type_answer',$data, array('labelOptions'=>array('style'=>''),  'separator'=>'  ', ) );                                                                                                                            
                    ?>
                    <div class="err_msg" id="type_answer_error"></div>
                </div>
            </div>
        </div>                 
      
       <div class="row" style="display: <?php echo $type3_status; ?>" id="multi-line-row"> <br>
            <div class="col-sm-12">
                <div class="form-group">
                    <?php 
                    if(in_array($model->question_type, array(3,4)))
                    {
                        $answer_model	= 	OnlineExamAnswers::model()->findByPk($model->answer_id);
                        if($answer_model!=NULL)
                        {                                   
                           $model->exam_answer= $answer_model->answer; 
                        }
                    }
                    ?>
                    <?php echo $form->labelEx($model,'exam_answer'); ?> *
                    <?php echo $form->textArea($model,'exam_answer', array('class'=>'form-control')); ?>
                    <div class="err_msg" id="text_answer_error"></div>
                </div>
            </div>
        </div>
        
        <div class="row"> <br>
            <div class="col-sm-4">
                <div class="form-group">
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
        <br>
        <div class="buttons">
            <?php echo CHtml::Button(Yii::t("app",'Submit'),array('class'=>'btn btn-danger','id'=>'save_btn')); ?>        
	</div>
    
    <?php $this->endWidget(); ?>
</div>



