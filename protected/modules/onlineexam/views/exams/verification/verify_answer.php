<?php
$this->breadcrumbs=array(
	Yii::t('app','Online Examination') => array('/onlineexam/dashboard'),
	Yii::t('app','Verification'),
);
?>
<style type="text/css">
.nothing-found{
	text-align:center;
	font-style:italic;
}

.score_err { border:  1px solid red !important;  }
</style>
<?php 
$semester_enabled	= Configurations::model()->isSemesterEnabled();
$exam_name= $student_id = $student_name= $exam_id= '';
$set_flag = $question_count = 0;
if((isset($_REQUEST['exam_id']) && ($_REQUEST['exam_id']!=NULL)) && (isset($_REQUEST['id']) && $_REQUEST['id']!=NULL))
{
    
    $exam_id                     =   OnlineExams::model()->decryptToken($_REQUEST['exam_id']); 
    $exam_model =   OnlineExams::model()->findByPk($exam_id);  
    if($exam_model!=NULL)
    {
        $set_flag=1;
        $exam_name  =   $exam_model->name; 
        $exam_batch_id  =   $exam_model->batch_id;
    }
    
    $student_id =  $_REQUEST['id'];
    $student_model  =   Students::model()->findByPk($student_id);           
    $student_name=  $student_model->studentFullName('forStudentProfile'); 
}
$offset=0;
if((isset($_REQUEST['offset']) && ($_REQUEST['offset']!=NULL)))
{
    $offset =   OnlineExams::model()->decryptToken($_REQUEST['offset']);
    $back_offset=  OnlineExams::model()->encryptToken($offset-1); 
}
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top"><?php $this->renderPartial('/default/admin_left');?></td>
    	<td valign="top">
            <div class="cont_right formWrapper">
                <h1><?php echo Yii::t('app','Online Exam Verification');?></h1>
                
            <div class="people-item"> 
                <div class="row">
                    <div class="col-md-12">
                        <div class="online-exam-vrfy light-bg">
                            <h3><span><?php echo Yii::t('app', 'Student Name'); ?> </span>
                            <?php 
                            if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile"))
                            {
                                echo ": ".$student_name;
                            } 
                            ?>
                            </h3>
                            <h3><span><?php echo Yii::t('app', 'Exam Name'); ?> </span><?php echo "&nbsp: ".$exam_name; ?></h3>                        
                            <h3><span><?php echo Yii::t('app', 'Batch'); ?> </span>
                                <?php 
                                $batc 			= Batches::model()->findByAttributes(array('id'=>$exam_model->batch_id,'is_active'=>1,'is_deleted'=>0)); 
								$sem_enabled	= Configurations::model()->isSemesterEnabledForCourse($batc->course_id);
                                if($batc!=NULL)
                                {
                                    $cours = Courses::model()->findByAttributes(array('id'=>$batc->course_id)); 
                                    if(FormFields::model()->isVisible('batch_id','Students','forStudentProfile'))
                                    { echo ": ".$cours->course_name.' / '.$batc->name; } 
                                }
                                else
                                    echo ": "."-";
                                ?>
                            </h3> 
							<?php if($semester_enabled==1 and $sem_enabled==1 and $batc->semester_id!=NULL){
									$semester 	= Semester::model()->findByAttributes(array('id'=>$batc->semester_id)); ?>
							<h3><span><?php echo Yii::t('app', 'Semester'); ?> </span><?php echo "&nbsp: ".ucfirst($semester->name); ?></h3>
							<?php } ?>  
                        </div>
                   </div>
                <?php 
                if($set_flag==1)
                {                                                     
                    $criteria 			=   new CDbCriteria();
                    $criteria->condition            =   'exam_id=:exam_id';
                    $criteria->order		=   'id ASC';                
                    $criteria->limit		=   1;
                    $criteria->offset		=   $offset;
                    $criteria->params 		=   array(':exam_id'=>$exam_id);
                    $criteria->addInCondition("`t`.question_type", array(3,4));
                    $questions			=   OnlineExamQuestions::model()->find($criteria);  
                    if($questions!=NULL)
                    {
                        $question_score='';
                        $exam_score =   OnlineExamScores::model()->findByAttributes(array('student_id'=>$student_id,'exam_id'=>$exam_id,'question_id'=>$questions->id));
                        if($exam_score!=NULL)
                        {
                            $question_score= $exam_score->score;
                        }
                    ?>
                    <div class="col-md-12">                
                        <div class="online-Q-list-bg">
                            <div class="online-Q-list">
                                    <h3><span><?php echo $offset+1; ?>. </span><?php echo $questions->question; ?></h3>
                            </div>
                            <div class="Q-ansr-block">
                                <p>
                                    <?php                                                                
                                    $student_answer_model   =   OnlineExamStudentAnswers::model()->findByAttributes(array('student_id'=>$student_id,'exam_id'=>$exam_id,'question_id'=>$questions->id));
                                    if($student_answer_model!=NULL)
                                    {
                                        echo $student_answer_model->ans;
                                    }
                                    else
                                        echo "-";
                                    ?>                                
                                </p>
                        </div>
                        </div>
                        <div class="answer-verify-ans">
                            <div class="online-ans-list" id="answer-div" style="display:none"> 
                                <h3>
                                    <?php 
                                    $answer_model   =   OnlineExamAnswers::model()->findByPk($questions->answer_id);
                                    if($answer_model!=NULL)
                                    {
                                        echo $answer_model->answer;
                                    }                                
                                    ?>
                                </h3>
                            </div>
                        </div>
                        <div class="online-Q-list-action">
                            <div class="Qstn-actn-ul Qstn-actn-posion-left">
                                <ul>

                                  <li><span class="input-icon"><input title="Show Answer" id="show-answer" class="show-answer Q-show-icon"  type="button" value="Show Answer"></span></li>
                                  <li><?php echo Yii::t('app', 'Enter Score'); ?></li>
                                  <li>
                                      <?php echo CHtml::textField('score',$question_score,array('id'=>'student_eval_score','class'=>'form-control-admarks','placeholder'=>  Yii::t('app', 'Score'))); ?>

                                    </li>
                                    <li>
                                        <span class="input-Btn-style" onclick="saveScore()" style="cursor:pointer">
                                     <?php 
                                            echo CHtml::Button('Save',array('class'=>'q-subbtn'));                                    
                                     ?>
                                     </span>
                                     </li>
                                        <?php if($offset!=0){ ?>
                                            <li id="prev_qp" style="cursor: pointer"><span class="inputbk-icon input-Btn-style"><input  type="button" class="q-subbtn-bk q-subbtn" value="back" /></span></li>
                                        <?php } ?> 
      
                                     
                                    
                                </ul>
                                
                               
                            </div>
                            <?php 
                            $criteria1 			=   new CDbCriteria();
                            $criteria1->condition           =   'exam_id=:exam_id';
                            $criteria1->order		=   'id ASC';                
                            $criteria1->limit		=   1;
                            $criteria1->offset		=   $offset+1;
                            $criteria1->params 		=   array(':exam_id'=>$exam_id);
                            $criteria1->addInCondition("`t`.question_type", array(3,4));
                            $pending_questions              =   OnlineExamQuestions::model()->findAll($criteria1);                          
                            $question_count                 = count($pending_questions);
                            ?>                                                
                            <div class="Qstn-actn-ul Qstn-actn-posion-right">
                                <ul>
                                    <?php /*?><?php if($offset!=0){ ?>
                                    <li id="prev_qp" style="cursor: pointer"><span class="inputbk-icon input-Btn-style"><input  type="button" class="q-subbtn-bk q-subbtn" value="back" /></span></li>
                                    <?php } ?><?php */?>
                                    <li><p><?php echo floatval($questions->mark) ?><span><?php echo Yii::t('app', 'Mark'); ?></span></p></li>
                                </ul>
                            </div>
                        </div>                    
                   </div>
                    <div class="err_msg" id="score_error"></div>
                    <?php
                    }                
                }
                else 
                {
                    ?>
                            <div class="Not-found">
                                    <p><?php echo Yii::t('app','No Result Found'); ?></p>
                            </div>
                        <?php 
                }
                ?> 



                </div>            
            </div>
            </div>
        </td>
    </tr>
</table>        
<script src="<?php echo Yii::app()->baseUrl;?>/js/jquery-ui-1.11.4.js"></script>

<script>
$(document).ready(function()
{
    show_hide();
    scroll_div(".formWrapper")
});

function scroll_div(element){
	$('html,body').animate({
		scrollTop: $(element).offset().top
	},'slow');
}
    
var show_hide	= function(){
	$("#show-answer").unbind("click").bind("click", function(){
		var that			= this,
                $answer_block	= $("#answer-div");
                
		if($answer_block.is(":visible")){
			$answer_block.slideUp('slow');
			$(that).attr('value','Show Answer');
                        $(that).attr('title','Show Answer');
		}
		else{
			$answer_block.slideDown('slow');
			$(that).attr('value','Hide Answer');
                        $(that).attr('title','Hide Answer');
		}
	});
};

function saveScore()
{
     $('.err_msg').hide().text('');
    var error_flag      =   0;
    var $score          =   $('#student_eval_score').val();  
    
    var $student_id     =   "<?php echo $student_id; ?>";
    var $exam_id        =   "<?php echo $exam_id; ?>";
    var $question_id    =   "<?php echo $questions->id; ?>";
    var $offset         =   "<?php echo $offset; ?>";
    var $qp_count       =   "<?php echo $question_count; ?>";
    
    if($score=='')
    {       
        $('#student_eval_score').addClass('score_err');
        error_flag=1;
    }        
    if(isNaN($score) || !$.isNumeric($score) || parseFloat($score) > "<?php echo floatval($questions->mark); ?>" || parseFloat($score) < 0)
    {        
        $('#student_eval_score').addClass('score_err');
        set_error('Mark must be numeric and less than or equal to <?php echo floatval($questions->mark); ?>','score_error');
        error_flag=1;
    }    
    if(error_flag==1)
    {            
        return false;
    }
    
    $.ajax({
		type:'POST',
		url:'<?php echo Yii::app()->createUrl('/onlineexam/exams/addScore');?>',
		data: { student_id:$student_id, exam_id:$exam_id, question_id:$question_id, score:$score, offset:$offset, qp_count: $qp_count ,"<?php echo Yii::app()->request->csrfTokenName;?>":"<?php echo Yii::app()->request->csrfToken;?>" },
		cache:false,
		dataType:"json",
		success: function(response)
                {
                    if(response.status=="success")
                    {
                        location.href   =   response.url;
                    }
                    else{
                        alert("<?php echo Yii::t("app", "Some problem found while save score")?>");
                    }
		},
                error:function(){
                    alert("<?php echo Yii::t("app", "Some problem found while while save score")?>");
                }
	});
    
}

function set_error(msg,id)
{
    $('#'+id).text(msg).show();
    window.setTimeout(function(){ $('.err_msg').hide().text(''); }, 10000);		
}

$('#prev_qp').click(function()
{
    var $student_id     =   "<?php echo $student_id; ?>";
    
    var $exam_id        =   "<?php echo $_REQUEST['exam_id']; ?>";    
    var $offset         =   "<?php echo $back_offset; ?>";
    location.href   =   '<?php echo Yii::app()->createUrl('/onlineexam/exams/verifyAnswer');?>'+'&id='+$student_id+'&exam_id='+$exam_id+'&offset='+$offset;
});

</script>

