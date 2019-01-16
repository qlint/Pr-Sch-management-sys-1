<?php $this->renderPartial('/default/teacherleft');?>    
<?php 	$exam 		= 	OnlineExams::model()->findByAttributes(array('id'=>$_REQUEST['id']));
        $settings	=	UserSettings::model()->findByAttributes(array('user_id'=>1));
        
        $start_date 	= 	date($settings->displaydate,strtotime($exam->start_time)); 
        $start_time 	= 	date($settings->timeformat,strtotime($exam->start_time)); 
        $end_date 	= 	date($settings->displaydate,strtotime($exam->end_time)); 
        $end_time 	= 	date($settings->timeformat,strtotime($exam->end_time));
       
        $time_diff 	=       $exam->duration. " mins";?>
        
<div class="pageheader">
    <div class="col-lg-8">
        <h2><i class="fa fa-pencil"></i><?php echo Yii::t('app','Online Examination'); ?><span><?php echo Yii::t('app','Add questions'); ?></span></h2>
    </div>        
    <div class="breadcrumb-wrapper">
        <span class="label"><?php echo Yii::t('app','You are here:'); ?></span>
            <ol class="breadcrumb">
            <!--<li><a href="index.html">Home</a></li>-->            
            <li class="active"><?php echo Yii::t('app','Online Exam'); ?></li>
        </ol>
    </div>
    <div class="clearfix"></div>
</div>
<?php 
$exam_id='';
if((isset($_REQUEST['id']) && $_REQUEST['id']!=NULL) && (isset($_REQUEST['bid']) && $_REQUEST['bid']!=NULL))
{
    $exam_id= $_REQUEST['id'];
    $criteria 			=   new CDbCriteria();
    $criteria->condition        =   'exam_id=:exam_id AND is_deleted=:is_deleted';
    $criteria->order            =   't.question_order ASC';                                
    $criteria->params 		=   array(':exam_id'=>$_REQUEST['id'],':is_deleted'=>0);
    $questions			=   OnlineExamQuestions::model()->findAll($criteria);            
   ?>   
<?php 
$total_mark=0;
if(isset($questions) && $questions!=NULL){    
    foreach ($questions as $data)
    {       
        $total_mark +=$data->mark;
    }
} ?>
<div class="contentpanel">
<div class="panel-heading" style="position:relative;">
        <div class="clear"></div>
        <h3 class="panel-title"><?php echo Yii::t('app','Exam Questions'); ?></h3>
<div class="button-bg">
<div class="top-hed-btn-left"> </div>
<div class="top-hed-btn-right">
<ul>
<li>
            
            <?php
                    echo CHtml::link(Yii::t('app','Back'),array('/onlineexam/exam','bid'=>$exam->batch_id),array('class'=>'btn btn-primary'));
                ?>
                </li>
                </ul>
                </div>
        </div>
    </div>
     <div class="panel-heading">
     	<div class="row">
            <div class="col-md-10 col-4-reqst">
            	<div class="online-exm-hd">
                    <h3 class="panel-title"><?php echo $exam->name;?></h3>
                    <h4 class="panel-title"><?php echo $start_date.' '.$start_time.' - '.$end_date.' '.$end_time;?></h4>
            	</div>
            </div>
            <div class="col-md-2 col-4-reqst">
            <div class="online-time">
            	<h3 class="panel-title"><?php echo '<span class="">' .Yii::t('app','Total Time').'&nbsp&nbsp&nbsp: </span>'.' '.$time_diff;?></h3>
                <h3 class="panel-title"><?php echo '<span class="">' .Yii::t('app','Total Marks').'&nbsp: </span>'.' '.$total_mark;?></h3>
            </div>
            </div>
        </div>
    </div>
    <div class="people-item">
   
 
        <?php if(isset($questions) && $questions!=NULL){ ?>
        <div class="row" id="qp_questions">
            <?php
            $i=1;
            foreach ($questions as $data)
            {   
                ?> <div class="col-md-12 question_class" question-id="<?php echo $data->id; ?>" id="question_div<?php echo $data->id; ?>"><?php
                $answer	= 	OnlineExamAnswers::model()->findAllByAttributes(array('question_id'=>$data->id));
                if($data->question_type==1){
                ?>
               
                    <div class="online-Q-list-bg" >	
                        <div class="online-Q-list">
                            <h3><span class="qp_number"><?php echo $i; ?>.</span><?php echo $data->question; ?></h3>                    
                            <div class="Q-ansr-block">
                                <?php
                                if($answer!=NULL){
                                    foreach($answer as $ans){?>
                                    <div class="online-q-aType">
                                        <input  type="radio" name="field" value=<?php echo $ans->id; ?>>
                                        <label ><?php echo $ans->answer; ?></label>
                                    </div>
                                    <?php } } ?>                                                
                            </div>       
                        </div>
                    </div>
                    <div class="online-ans-list-action answer-div" style="display: none;">
                        <h3><span><?php echo Yii::t('app','Answer') ?> :</span><?php echo OnlineExamAnswers::model()->getAnswer($data->answer_id); ?></h3>  
                    </div>
                    <?php echo $this->renderPartial('question_actions',array('data'=>$data)); ?>
                
                <?php                
                } 
                else if($data->question_type==2)
                {
                ?>
                
                	<div class="online-Q-list-bg">
                        <div class="online-Q-list">
                            <h3><span class="qp_number"><?php echo $i; ?>.</span><?php echo $data->question; ?></h3>
                            <div class="Q-ansr-block">
                                <div class="online-q-aType">
                                    <input  type="radio" name="field" value="1">
                                    <label ><?php echo Yii::t('app','True'); ?></label>
                                </div>
                                <div class="online-q-aType">
                                    <input  type="radio" name="field" value="0">
                                    <label ><?php echo Yii::t('app','False'); ?></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="online-ans-list-action answer-div" style="display: none">
                        <h3><span><?php echo Yii::t('app','Answer') ?> :</span>
                            <?php 
                                $answer   = OnlineExamAnswers::model()->getAnswer($data->answer_id); 
                                if($answer==1)
                                {
                                    echo Yii::t('app','True');
                                }
                                else
                                    echo Yii::t('app','False');
                            ?>
                        </h3>  
                    </div>
                    <?php echo $this->renderPartial('question_actions',array('data'=>$data)); ?>
                
                <?php                
                } 
                else if($data->question_type==3 || $data->question_type==4)
                {
                ?>
                
                    <div class="online-Q-list-bg">
                        <div class="online-Q-list">
                            <h3><span class="qp_number"><?php echo $i; ?>.</span><?php echo $data->question; ?></h3>                            
                        </div>
                    </div>
                    <div class="online-ans-list-action answer-div" style="display: none;">
                        <h3><span><?php echo Yii::t('app','Answer') ?> :</span><?php echo OnlineExamAnswers::model()->getAnswer($data->answer_id); ?></h3>  
                    </div>
                    <?php echo $this->renderPartial('question_actions',array('data'=>$data)); ?>
                         
                <?php             
                } 
                ?></div><?php
            $i++;
            } ?>
        </div>   
          
        <?php } 
        else
        {
           ?>
            <div class="row">
                <div class="col-md-12">
                    <center><?php echo Yii::t('app','No questions found'); ?></center>
                </div>
            </div>
            <?php 
        }
        ?>
        
    <?php } 
    else
        {
           ?>
            <div class="row">
                <div class="col-md-12">
                    <center><?php echo Yii::t('app','No questions found'); ?></center>
                </div>
            </div>
            <?php 
        }
        ?>
    </div>
</div>

<style>
.placeholder {
  background: #f3f3f3;
  visibility: visible;
  height: auto;
  float:left;
}  
.online-Q-list-bg:hover , .Q-ansr-block:hover
{
    cursor:move;
}
</style>    
<div class="clear"></div>
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl;?>/css/jquery-ui.css">
<script src="<?php echo Yii::app()->baseUrl;?>/js/jquery-ui-1.11.4.js"></script>
<script>
$(document).ready(function () 
{
    show_hide();    
    <?php 
    if(isset($_REQUEST['key']))
    {
    ?>              
    scroll_div("#question_div"+"<?php echo $_REQUEST['key']; ?>")
    <?php
    }
    ?>                
});


$( function() {
    $( "#qp_questions" ).sortable({
        forcePlaceholderSize: true,
        placeholder: 'placeholder',
        update:function(){
			arrange_questions_order();
			save_questions_order();
		}
        
    });
    $( "#qp_questions" ).disableSelection();
  } );
var show_hide	= function(){
	$(".show-answer").unbind("click").bind("click", function(){
		var that			= this,
                $answer_block	= $(that).closest(".col-md-12").find(".answer-div");
                
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

function scroll_div(element){
	$('html,body').animate({
		scrollTop: $(element).offset().top
	},'slow');
}


function arrange_questions_order(){        
	var start	= 1;
	$('#qp_questions .question_class').each(function(index, element) 
        {
            $(element).find('.online-Q-list-bg .online-Q-list .qp_number').text(start + '.');
		start++;
        });
}

function save_questions_order(){
	var questions	= [];
	$('#qp_questions .question_class').each(function(index, element) 
        {
		var question	= {};
		question.order	= index + 1;
		question.id		= $( element ).attr('question-id');
                questions.push(question);
        });
	
	$.ajax({
		type:"POST",
		url:"<?php echo Yii::app()->createUrl('/onlineexam/questions/changeOrder');?>",
		data:{exam_id:<?php echo $exam_id;?>, questions:questions,"<?php echo Yii::app()->request->csrfTokenName;?>":"<?php echo Yii::app()->request->csrfToken;?>"},
		success: function(){
                    
		},
                error:function(){
                alert("<?php echo Yii::t('app', 'There is some problem found while changing position');?>");
            }
	});
}
</script>