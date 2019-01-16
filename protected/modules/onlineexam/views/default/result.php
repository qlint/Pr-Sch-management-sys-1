<?php 	
$roles 		= 	Rights::getAssignedRoles(Yii::app()->user->Id);
$exam 		= 	OnlineExams::model()->findByAttributes(array('id'=>$_REQUEST['id']));
$batch		=	Batches::model()->findByAttributes(array('id'=>$exam->batch_id));
if(key($roles) == 'student')
{
	$this->renderPartial('studentleft');
	$student				=	Students::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
	$link    				=   CHtml::link(Yii::t('app','Back'),array('/onlineexam/default/list','bid'=>$batch->id),array('class'=>'btn btn-primary'));
}
if(key($roles) == 'parent')
{
	$this->renderPartial('parentleft');
	$sid     				=   OnlineExams::model()->decryptToken($_REQUEST['sid']);
	$link    				=   CHtml::link(Yii::t('app','Back'),array('/onlineexam/default/exams','bid'=>$batch->id, 'id'=>$sid),array('class'=>'btn btn-primary'));
}
?>	
		
<div class="pageheader">
	<div class="col-lg-8">
        <h2><i class="fa fa-pencil"></i><?php echo Yii::t('app','Online Exam Results'); ?><span><?php echo Yii::t('app','View your Online Exam Results here'); ?></span></h2>
    </div>
        
    <div class="breadcrumb-wrapper">
        <span class="label"><?php echo Yii::t('app','You are here:'); ?></span>
            <ol class="breadcrumb">
            	<li class="active"><?php echo Yii::t('app','Online Exam Results'); ?></li>
        	</ol>
    </div>
   	<div class="clearfix"></div>
</div>
    
<div class="contentpanel">
     <div class="panel-heading">
        <h3 class="panel-title"><?php echo Yii::t('app','Exam Name').' : '.ucfirst($exam->name);?></h3>
<div class="button-bg">
<div class="top-hed-btn-left"> </div>
<div class="top-hed-btn-right">
<ul>
<li>
                <?php echo $link;?>
                </li>
            
            </ul>
            </div>
    </div>
    </div>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'online-exam-form',
)); ?>
    <div class="people-item">
    	<div class="row">
     		<div class="col-md-12">
            <?php if(isset($_REQUEST['page'])){
								$i=($pages->pageSize*$_REQUEST['page'])-9;
							}
							else{
								$i=1;
							}
					 		if($questions){
					 			foreach($questions as $question){
									$answers				= 	OnlineExamAnswers::model()->findAllByAttributes(array('question_id'=>$question->id));
									$stud_ans				= 	OnlineExamStudentAnswers::model()->findByAttributes(array('question_id'=>$question->id));
									
									$correct_ans			= 	OnlineExamAnswers::model()->findByAttributes(array('id'=>$question->answer_id)); ?>
                             
                                    <div class="Question-main-block">
                                        <div class="online-Q-list-bg">
                                            <div class="answer-verify-Q">
                                           
                                                        <h3>
                                                        	<span><?php // echo Yii::t('app','Q.').' ';?></span>
                                                            <?php echo $question->question;?>
                                                        </h3>
                                           
                                            </div>
                                            <?php if($question->question_type == 1){    // for multi choice?>
                                                     <div class="answer-radio highlight answer-fix-icon">
                                                        <ul>
                                                        <?php
														$class1	=	'';
														$class2	=	'';
														if(isset($stud_ans))
														{
															$ans	= 	OnlineExamAnswers::model()->findByAttributes(array('id'=>$stud_ans->ans));
															if($ans->answer == $correct_ans->answer)
																$class1	=	'tick';
															else
																$class2	=	'cross';
															
														}
														?>
                                                            <li class="true-ans <?php echo $class1;?>"><?php echo $correct_ans->answer; ?></li>
                                                            
                                                            <?php if($correct_ans->id != $stud_ans->ans) {
                                                                $wrong_ans = 	OnlineExamAnswers::model()->findByAttributes(array('id'=>$stud_ans->ans));
                                                                ?>
                                                                     <li class="false-ans <?php echo $class2;?>"><?php echo $wrong_ans->answer; ?></li>
                                                            <?php } ?>
                                                  <?php foreach($answers as $answer){ 
												  $ans	= 	OnlineExamAnswers::model()->findByAttributes(array('id'=>$stud_ans->ans));
															if($answer->answer!=$ans->answer and $answer->answer!=$correct_ans->answer){?>
																	<li><?php echo $answer->answer; ?></li>
													  <?php } 
														}?>
                                                        </ul>
                                                    </div>
                                            <?php } ?>
                                            
                                            <?php  if($question->question_type == 2){  // for true or false?>
                                                     <div class="answer-radio highlight answer-fix-icon">
                                                        <ul>
                                                        <?php
														$class1	=	'';
														$class2	=	'';
														if(isset($stud_ans))
														{
															$ans	= 	OnlineExamAnswers::model()->findByAttributes(array('id'=>$stud_ans->ans));
															if($ans->answer == $correct_ans->answer)
																$class1	=	'tick';
															else
																$class2	=	'cross';
															
														}
														?>
                                                            <li class="true-ans <?php echo $class1;?>">
																<?php
																if($correct_ans->answer == 1){
																		   echo Yii::t('app','True');
																	   }
																	   else{
																		   echo Yii::t('app','False');
																	   }?>
                                                            </li>
                                                            <li class="false-ans">
                                                            <?php 
															if(!isset($stud_ans) and $correct_ans->answer == 1)
															{
																echo Yii::t('app','False');
															}else if(!isset($stud_ans) and $correct_ans->answer == 0)
															{
																echo Yii::t('app','True');
															}
															?>
                                                            </li>
                                                            <?php
																if(isset($stud_ans) and $correct_ans->id != $stud_ans->ans) {
                                                                $wrong_ans = 	OnlineExamAnswers::model()->findByAttributes(array('id'=>$stud_ans->ans));
                                                                ?>
                                                                     <li class="false-ans <?php echo $class2;?>">
																	 <?php if($wrong_ans->answer == 1){
																		   echo Yii::t('app','True');
																	   }
																	   else{
																		   echo Yii::t('app','False');
																	   }?></li>
                                                            <?php } ?>
                                                  <?php 
												  
												  foreach($answers as $answer){ 
												  $ans	= 	OnlineExamAnswers::model()->findByAttributes(array('id'=>$stud_ans->ans));
															if(isset($stud_ans->ans) and $answer->answer!=$ans->answer and $answer->answer!=$correct_ans->answer){?>
																	<li class="false-ans"><?php if($wrong_ans->answer == 1){
																		   echo Yii::t('app','True');
																	   }
																	   else{
																		   echo Yii::t('app','False');
																	   }?></li>
													  <?php } 
														}?>
                                                        </ul>
                                                    </div>
                                            <?php } ?>
                                           
                                            </div>
                                             <div class="online-Q-list-action">
                                                <div class="Qstn-actn-ul Qstn-actn-posion-right">
                                                    <ul>
                                                        <li>
                                                            <p><span><?php echo Yii::t('app','Score'); ?></span>
																<?php
																if($stud_ans->ans == $question->answer_id){
																			echo floatval($question->mark);
																	  }
																	  else{
																		  echo '0';
																	  }?>
                                                            </p>
                                                        </li>
                                                    </ul>
                                                </div>
                        					 </div>
                     		<?php 
								} // end foreach?>
							</div>
        			<?php } // end if($questions)
						  else{
							  ?>
                              <div class="No-q-block">
                            
							  <p> <?php echo Yii::t('app','No Multiple Choice and True or False Questions'); ?></p>
							 
                              </div>
						<?php	  
					}?>
            <div class="pagecon">
            <?php                                          
                $this->widget('CLinkPager', array(
                'currentPage'=>$pages->getCurrentPage(),
                'itemCount'=>$item_count,
                'pageSize'=>$page_size,
                'maxButtonCount'=>5,
                'header'=>'',
                'htmlOptions'=>array('class'=>'pagination'),
                ));?>
            </div>
		</div>
        </div>
	</div>
    <div class="clear"></div>
<?php $this->endWidget(); ?>
</div>
