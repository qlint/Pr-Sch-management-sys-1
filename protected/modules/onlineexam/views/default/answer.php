<?php 	$this->renderPartial('studentleft');?>
<?php   $exam 		= 	OnlineExams::model()->findByAttributes(array('id'=>$_REQUEST['id']));
		$batch		=	Batches::model()->findByAttributes(array('id'=>$exam->batch_id));?>
		
<div class="pageheader">
	<div class="col-lg-8">
        <h2><i class="fa fa-pencil"></i><?php echo Yii::t('app','Online Exam Answer Key'); ?><span><?php echo Yii::t('app','View your Online Exam Answe Key here'); ?></span></h2>
    </div>
        
    <div class="breadcrumb-wrapper">
        <span class="label"><?php echo Yii::t('app','You are here:'); ?></span>
            <ol class="breadcrumb">
            	<li class="active"><?php echo Yii::t('app','Online Exam Answer Key'); ?></li>
        	</ol>
    </div>
   	<div class="clearfix"></div>
</div>
    
<div class="contentpanel">
     <div class="panel-heading">
        <h3 class="panel-title"><?php echo Yii::t('app','Exam Name').' : '.ucfirst($exam->name);?></h3>
        	<div class="btn-demo" style="position:relative; top:-30px; right:3px; float:right;">
                <?php echo CHtml::link(Yii::t('app','Back'),array('/onlineexam/default/list','bid'=>$batch->id),array('class'=>'btn btn-primary'));?>
            </div>
    </div>
    <div class="people-item">
    	<div class="row">
     		<div class="col-md-12">
            	<div class="online-Q-list">
					 <?php if(isset($_REQUEST['page'])){
								$i=($pages->pageSize*$_REQUEST['page'])-9;
							}
							else{
								$i=1;
							}
					 		if($questions){
					 			foreach($questions as $question){ ?>
                                <div class="online-Q-list-bg">
                            		<h3><span><?php echo $i.'.'; ?></span><?php echo ucfirst($question->question); ?></h3>
                                    <div class="Q-answers-block">
										<?php   $question	= 	OnlineExamQuestions::model()->findByAttributes(array('id'=>$question->id)); 
                                                $answer		= 	OnlineExamAnswers::model()->findByAttributes(array('id'=>$question->answer_id));?>
                                            <p><span><?php echo Yii::t('app','Ans :') ?></span>
                                                     <?php if($question->question_type == 2){ // true or false
                                                                if($answer->answer == 0){
                                                                    echo Yii::t('app','False');
                                                                }
                                                                if($answer->answer == 1){
                                                                    echo Yii::t('app','True');
                                                                }
                                                            }
                                                            else{	// multiple,short and multi line
                                                                echo $answer->answer;
                                                            }?>
                                            </p>
                                    </div>
                                    </div>
                    	 <?php $i++;
						 		}
					 	}?>
                </div>
           	 </div>
		</div>
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
    <div class="clear"></div>
</div>
