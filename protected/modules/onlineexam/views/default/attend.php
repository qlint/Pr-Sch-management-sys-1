<?php 	$this->renderPartial('studentleft');?>
<?php 	$etoken                     =   OnlineExams::model()->decryptToken($_REQUEST['etoken']);
		$exam 						= 	OnlineExams::model()->findByAttributes(array('id'=>$etoken));
		$student					= 	Students::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
		
		$settings					=	UserSettings::model()->findByAttributes(array('user_id'=>1)); 
		$start_date 				= 	date($settings->displaydate,strtotime($exam->start_time)); 
		$start_time 				= 	date($settings->timeformat,strtotime($exam->start_time)); 
		$end_date 					= 	date($settings->displaydate,strtotime($exam->end_time)); 
		$end_time 					= 	date($settings->timeformat,strtotime($exam->end_time));
		
		$to_time 					= 	strtotime($exam->end_time);
		$from_time 					= 	strtotime($exam->start_time);
		$duration 					= 	$exam->duration;
		$hour 						= 	floor($duration/60);
		$mins 						= 	$duration%60;?>
        
<div class="pageheader">
	<div class="col-lg-8">
        <h2><i class="fa fa-pencil"></i><?php echo Yii::t('app','Online Exams'); ?><span><?php echo Yii::t('app','Attend your Online Exam here'); ?></span></h2>
    </div>
        
    <div class="breadcrumb-wrapper">
        <span class="label"><?php echo Yii::t('app','You are here:'); ?></span>
            <ol class="breadcrumb">
           
            <li class="active"><?php echo Yii::t('app','Online Exam'); ?></li>
        </ol>
    </div>
   	<div class="clearfix"></div>
</div>

<div class="contentpanel">
     <div class="panel-heading">
     	<div class="row">
        	<div class="col-md-9">
            	<div class="online-exm-hd">
                    <h3 class="panel-title"><?php echo '<span class="">'.Yii::t('app','Exam Name :').'</span>'.' '.ucfirst($exam->name);?></h3>
                    <h4 class="panel-title"><?php echo'<span class="">' .Yii::t('app','Time :').'</span>'.' '.$start_date.' '.$start_time.' - '.$end_date.' '.$end_time;?></h4>
            	</div>
            </div>
           
            <div class="col-md-3">
            <div class="online-time">
            	<h3 class="panel-title">
					<?php if($mins > 0){
							echo '<span class="">' .Yii::t('app','Total Time :').'</span>'.' '.$hour.' '.'hr'.' '.$mins.' '.'mins';
						  }
						  else{
							echo '<span class="">' .Yii::t('app','Total Time :').'</span>'.' '.$hour.' '.'hr';
						  }?>
                </h3>
                <h4> <?php
						$timezone 	= Timezone::model()->findByAttributes(array('id'=>$settings->timezone));
       					date_default_timezone_set($timezone->timezone);
						
						$attend				= OnlineExamStudents::model()->findByAttributes(array('exam_id'=>$etoken, 'student_id'=>$student->id));
						$exam_start_date	= date("m/d/Y H:i:s", strtotime($attend->exam_start_time));					 					
						$final_time			= date("Y-m-d H:i:s", strtotime("+".$duration." minutes ", strtotime($attend->exam_start_time)));																												
						?>                                                
                    
            	</h4>
                <div id="clockdiv">                    
                    <span class="hours"></span> <?php echo Yii::t('app', 'Hrs'); ?>, <span class="minutes"></span> <?php echo Yii::t('app', 'Mins'); ?>, <span class="seconds"></span> <?php echo Yii::t('app', 'Secs'); ?>                   
                </div>
            </div>
            </div>
        </div>
    </div>
  
    <div class="people-item">
    	<div class="time_out-block">
        	<p id="time_out-block"></p>
        </div>
    
		 <?php 
                $offset					= 	OnlineExams::model()->decryptToken($_REQUEST['offset']);
				$ques_no				=	$offset+1;
				
                $criteria 				= 	new CDbCriteria();
				$criteria->condition 	= 	'exam_id=:exam_id and is_deleted = :is_deleted';
                $criteria->order		=   'question_order ASC';              
                $criteria->limit		= 	1;
                $criteria->offset		=	$offset;
               	$criteria->params 		= 	array(':exam_id'=>$etoken,':is_deleted'=>0);
                $question				= 	OnlineExamQuestions::model()->find($criteria);
				
				$next_offset            =   OnlineExams::model()->encryptToken($offset+1);
				$back_offset 			=   OnlineExams::model()->encryptToken($offset-1);	?>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'online-exam-form',
)); ?>
        <div class="row">
            <div class="col-md-12">
            	 <div class="Question-main-block">
                        <div class="online-Q-list-bg">
                        <div class="answer-verify-Q">
                            <h3><span><?php echo $ques_no.'.'; ?></span><?php echo ucfirst($question->question); ?></h3></div>
                            	<?php $answer		= 	OnlineExamAnswers::model()->findAllByAttributes(array('question_id'=>$question->id));
									  $stud_ans		= 	OnlineExamStudentAnswers::model()->findByAttributes(array('question_id'=>$question->id, 'student_id'=>$student->id));
									  if($stud_ans!=NULL){ 
										  $model		= 	OnlineExamStudentAnswers::model()->findByAttributes(array('question_id'=>$question->id, 'student_id'=>$student->id));
									  }?>
                                    <div class="answer-radio highlight answer-fix-icon">
                                    	<?php  if($question->question_type == 1){ //multi choice?>
                                                    <div class="online-q-aType">
                                                 		<?php echo $form->radioButtonList($model,'ans', CHtml::listData($answer,'id','answer'), array("id"=>"", 'value'=>'', "uncheckValue"=>NULL)); ?>
                                                    </div>
                                         <?php } ?>
                                   
                                       <?php  if($question->question_type == 2){ //true or false
										   			$value = array();
													foreach($answer as  $ans){
														$label	= '';
														if($ans->answer == 1){
															$label	= Yii::t('app', 'True');
														}
														else if($ans->answer == 0){
															$label	= Yii::t('app', 'False');
														}
														$value[$ans->id]	= $label;
													}?>
                                        			<div class="online-q-aType">
										   				<?php echo $form->radioButtonList($model,'ans', $value, array("id"=>"", 'value'=>'', "uncheckValue"=>NULL));?>                              		
                                                    </div>
                                         <?php } ?>
                                        <?php  if($question->question_type == 3 or $question->question_type == 4){ //short , multi line
                                        			echo $form->textArea($model,'ans',array('class'=>'short-answer')); ?>   
                                         <?php } ?>
                                    </div>
                        		</div>
                                <div class="online-Q-list-action">
                                <div class="Qstn-actn-ul Qstn-actn-posion-left">
                                    <ul>           
									<?php 	$criteria 				= 	new CDbCriteria();
                                            $criteria->condition 	= 	'exam_id=:exam_id and is_deleted = :is_deleted';
                                            $criteria->order		=   'id ASC';                
                                            $criteria->limit		= 	1;
                                            $criteria->offset		=	$offset+1;
                                            $criteria->params 		= 	array(':exam_id'=>$etoken,':is_deleted'=>0);
                                            $ques                       = 	OnlineExamQuestions::model()->findAll($criteria);
                                            if($offset!=0){
                                               ?><li><span class="inputback-icon input-Btn-style"><?php echo CHtml::Button('Back',array('onclick'=>'js:onlineexam(1)','class'=>'q-subbtn')); ?> </span></li><?php
                                            }
                                            if(count($ques)>0){ 
                                                ?><li><span class="inputnext-icon input-Btn-style"><?php echo CHtml::Button('Next',array('onclick'=>'js:onlineexam(2)','class'=>'q-subbtn'));?> </span></li><?php
                                            }else{
                                                ?><li><span class="inputsubmit-icon input-Btn-style"><?php echo CHtml::Button('Submit', array('onclick'=>'js:onlineexam(3)','class'=>'q-subbtn', 'confirm'=>'Are you sure you want to submit the exam ?'));?> </span></li><?php
                                            }
                                            ?>  
                                        </ul>
                                  </div>
                            <div class="Qstn-actn-ul Qstn-actn-posion-right">
                                <ul>
                                    <li><p><span><?php echo Yii::t('app', 'Score');?></span><?php echo floatval($question->mark); ?></p></li>
                                </ul>
                            </div>
                        </div>
                       </div>
            		</div>
        		</div>
    		</div>
  <?php $this->endWidget(); ?>
		</div>
    </div> 
    <div class="clear"></div>
</div>

<script type="text/javascript">
function onlineexam(type)
{
	if(type == 2){ 				// for Next
		var datas=$("#online-exam-form").serialize();
		$.ajax({
			type:'POST',
			url:'<?php echo Yii::app()->createUrl('onlineexam/default/attend' , array("etoken"=>$_REQUEST['etoken'], "qid"=>$question->id, "offset"=>$next_offset));?>',
			data:datas,
			cache:false,
			dataType:"json",
			success: function(response){			
				if(response.status=="success"){
					window.location= 'index.php?r=onlineexam/default/attend&etoken='+response.etoken+'&offset='+response.offset;				
				}
			},          
		});
	}
	else if(type == 1){       // for Back
		var etoken	= '<?php echo $_REQUEST['etoken'];  ?>';
		var offset	= '<?php echo $back_offset; ?>';
		window.location= 'index.php?r=onlineexam/default/attend&etoken='+etoken+'&offset='+offset;
	}
	else if(type == 3){ 	// for Submit
		var datas=$("#online-exam-form").serialize();
			$.ajax({
			type:'POST',
			url:'<?php echo Yii::app()->createUrl('onlineexam/default/attend' , array("etoken"=>$_REQUEST['etoken'], "qid"=>$question->id, "offset"=>$next_offset));?>',
			data:datas,
			cache:false,
			dataType:"json",
			success: function(response){			
				if(response.status=="success"){
					window.location= 'index.php?r=onlineexam/default/submit&etoken='+response.etoken+'&offset='+response.offset;
				}
			},          
		});
	}
}
</script>

<script type="text/javascript">
function getTimeRemaining(endtime) {
  var t = Date.parse(endtime) - Date.parse(new Date());
  var seconds = Math.floor((t / 1000) % 60);
  var minutes = Math.floor((t / 1000 / 60) % 60);
  var hours = Math.floor((t / (1000 * 60 * 60)) % 24);
  var days = Math.floor(t / (1000 * 60 * 60 * 24));
  return {
    'total': t,
    'days': days,
    'hours': hours,
    'minutes': minutes,
    'seconds': seconds
  };
}

function initializeClock(id, endtime) {
  var clock = document.getElementById(id);
  //var daysSpan = clock.querySelector('.days');
  var hoursSpan = clock.querySelector('.hours');
  var minutesSpan = clock.querySelector('.minutes');
  var secondsSpan = clock.querySelector('.seconds');

  function updateClock() {
    var t = getTimeRemaining(endtime);

    //daysSpan.innerHTML = t.days;
    hoursSpan.innerHTML = ('0' + t.hours).slice(-2);
    minutesSpan.innerHTML = ('0' + t.minutes).slice(-2);
    secondsSpan.innerHTML = ('0' + t.seconds).slice(-2);

    if (t.total <= 0) {
		clearInterval(timeinterval);
		$('.q-subbtn').attr('disabled', true);
		$('#time_out-block').html('<?php echo Yii::t('app', 'You Are Timed Out!!'); ?>');			
		setInterval(
			function(){ 
				onlineexam(3);
			}, 2000
		);					
		return false;      	
    }
  }

  updateClock();
  var timeinterval = setInterval(updateClock, 1000);
}

var deadline = '<?php echo $final_time; ?>';

initializeClock('clockdiv', deadline);
</script>



