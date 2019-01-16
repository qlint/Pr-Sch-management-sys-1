<script>
$(document).ready(function() {
            //Hide the second level menu
            $('#othleft-sidebar ul li ul').hide();            
            //Show the second level menu if an item inside it active
            $('li.list_active').parent("ul").show();
            
            $('#othleft-sidebar').children('ul').children('li').children('a').click(function () {                    
                
                 if($(this).parent().children('ul').length>0){                  
                    $(this).parent().children('ul').toggle();    
                 }                 
            });                      
        });
</script>

<div id="othleft-sidebar">
     <h1><?php echo Yii::t('app','Online Examination');?></h1>     
    <ul>
        <li class="<?php if(Yii::app()->controller->id=='') { echo "list_active"; } ?>" ><?php echo CHtml::link(Yii::t('app','Dashboard').'<span>'.Yii::t('app','Online Examination List').'</span>',array('/onlineexam/dashboard/'),array('class'=>'exame-result_ico','active'=>(''))); ?></li>  
        <li class="<?php if(Yii::app()->controller->id=='') { echo "list_active"; } ?>" ><?php echo CHtml::link(Yii::t('app','Online Exams').'<span>'.Yii::t('app','Online Examination List').'</span>',array('/onlineexam/exams/'),array('class'=>'exame-result_ico','active'=>(''))); ?></li>  
    </ul>
    <h1><?php echo Yii::t('app','Exam Management');?></h1>       
    <?php			
        $level = Configurations::model()->findByPk(41);
        if($level->config_value !=1)
        {
            $visible 	= true;
            $visible_1	= false;
        }
        else
        {
            $visible 	= false;
            $visible_1	= true;
        } 
        if(isset($_REQUEST['id']) and $_REQUEST['id']!=NULL)
        {
			if(Yii::app()->controller->id =='exam' and Yii::app()->controller->action->id=='result')
                    {
						$_REQUEST['id']=$_REQUEST['bid'];
					}
			if(Yii::app()->controller->id =='exam17')
			{
						$_REQUEST['id']=$_REQUEST['id'];
					}
						
            $this->widget('zii.widgets.CMenu',array(
            'encodeLabel'=>false,
            'activateItems'=>true,
            'activeCssClass'=>'list_active',
            'items'=>array(
                        
                            

                            array('label'=>''.Yii::t('app','Co-Scholastic Skills').'<span>'.Yii::t('app','Co-Scholastic for the').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").'</span>', 'url'=>array('/examination/coScholastic','id'=>$_REQUEST['id']) ,'linkOptions'=>array('id'=>'enroll_co','class'=>'gco-schilastic_ico'),
                                    'active'=> ((Yii::app()->controller->id=='coScholastic') && (in_array(Yii::app()->controller->action->id,array('index')))) ? true : false,'visible'=>$visible
                                ),
                            array('label'=>Yii::t('app','Select').' '.Yii::app()->getModule("students")->labelCourseBatch().'<span>'.Yii::t('app','Select').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' '.Yii::t('app','and exam').'</span>', 'url'=>array('/examination/exam','id'=>$_REQUEST['id']),'linkOptions'=>array('class'=>'ne_ico'),
                                    'active'=> ((Yii::app()->controller->id=='exam') && (in_array(Yii::app()->controller->action->id,array('index'))) or (Yii::app()->controller->id=='exams') )  ? true : false
                                ),
								array('label'=>Yii::t('app','Common Exams').'<span>'.Yii::t('app','Create Common Exams').'</span>', 'url'=>array('/examination/commonExams'),'linkOptions'=>array('class'=>'exm-gradebook_ico'),
                                    'active'=> (Yii::app()->controller->id=='commonExams')  ? true : false
                                ),
								array('label'=>''.Yii::t('app','Set grading levels').'<span>'.Yii::t('app','Grading Levels for the').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").'</span>', 'url'=>array('/examination/gradingLevels','id'=>$_REQUEST['id'],) ,'linkOptions'=>array('id'=>'enroll_p','class'=>'gradinglevels_ico'),
                                    'active'=> ((Yii::app()->controller->id=='gradingLevels') && (in_array(Yii::app()->controller->action->id,array('index')))) ? true : false,'visible'=>$visible_1
                                ),
                            ),
              ));
		 
        }
        else
        {
        ?>
            <ul>
                <?php if($visible){ ?>
                <li>
                    <?php echo CHtml::ajaxLink(Yii::t('app','Set grading levels').'<span>'.Yii::t('app','Grading Levels for the').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").'</span>',array('/site/explorer','widget'=>'2','rurl'=>'examination/gradingLevels'),array('update'=>'#explorer_handler'),array('id'=>'explorer_gradingLevels','class'=>'exm-gradebook_ico ')); ?>
                </li>
                <?php } ?>
                <?php if($visible){ ?>
                <li>
                    <?php echo CHtml::ajaxLink(Yii::t('app','Co-Scholastic Skills').'<span>'.Yii::t('app','Co-Scholastic for the').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").'</span>',array('/site/explorer','widget'=>'2','rurl'=>'examination/coScholastic'),array('update'=>'#explorer_handler'),array('id'=>'explorer_coScholastic','class'=>'co-scholastic-skill_ico')); ?>
                </li>
                <?php } ?>
                <li>
                    <?php 
                    if(isset(Yii::app()->controller->module->id) and Yii::app()->controller->module->id=='CBSCExam')
                    {
                        echo CHtml::ajaxLink(Yii::t('app','Select').' '.Yii::app()->getModule("students")->labelCourseBatch().'<span>'.Yii::t('app','Select').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' '.Yii::t('app','and exam').'</span>',array('/site/explorer','widget'=>'2','rurl'=>'CBSCExam/exam'),array('update'=>'#explorer_handler'),array('id'=>'explorer_exam','class'=>'ne_ico')); 
                    }
                    else
                    {
                        echo CHtml::ajaxLink(Yii::t('app','Select').' '.Yii::app()->getModule("students")->labelCourseBatch().'<span>'.Yii::t('app','Select').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' '.Yii::t('app','and exam').'</span>',array('/site/explorer','widget'=>'2','rurl'=>'examination/exam'),array('update'=>'#explorer_handler'),array('id'=>'explorer_exam','class'=>'list-cours_ico')); 
                    }
					?>
              	</li>
                <li class="<?php if(Yii::app()->controller->id=='commonExams') { echo "list_active"; } ?>">
					<?php
					echo CHtml::link(Yii::t('app','Common Exams').'<span>'.Yii::t('app','Create Common Exams').'</span>',array('/examination/commonExams'),array('class'=>'exm-gradebook_ico'));
                    ?>
                </li>
                
                <li class="<?php if(Yii::app()->controller->id=='gradingLevels') { echo "list_active"; } ?>" >
                    <?php echo CHtml::link(Yii::t('app','Default Grading Levels').'<span>'.Yii::t('app','Set Default Grading Level').'</span>',array('gradingLevels/list','key'=>'NULL'),array('class'=>'gradinglevels_ico','active'=>(Yii::app()->controller->id=='gradingLevels'))); ?>
                </li> 
            </ul>

        <?php      
        } 
        ?>
                <?php if(!$visible){ ?>		  
                    <h1><?php echo Yii::t('app','Exam Results');?></h1>     
                    <ul>
                        <li class="<?php if(Yii::app()->controller->id=='result') { echo "list_active"; } ?>" ><?php echo CHtml::link(Yii::t('app','Results').'<span>'.Yii::t('app','Search Default Exam Results').'</span>',array('/examination/result/index'),array('class'=>'exame-result_ico','active'=>(Yii::app()->controller->id=='result'))); ?></li>  
                    </ul>
                <?php } ?>                
                
                <h1><?php echo Yii::t('app','Grade Book');?></h1>     
                <ul>
                    <li class="<?php if(Yii::app()->controller->action->id=='gradebook') { echo "list_active"; } ?>"><?php echo CHtml::link(Yii::t('app','Grade book').'<span>'.Yii::t('app','View Default Exam Grade book').'</span>',array('/examination/exam/gradebook'),array('class'=>'grade-book_ico','active'=>(Yii::app()->controller->id=='exam'))); ?></li>  
                    <?php 
                    if($visible){ ?>
                    <li class="<?php if(Yii::app()->controller->action->id=='cbsc' || Yii::app()->controller->action->id=='view') { echo "list_active"; } ?>"><?php echo CHtml::link(Yii::t('app','CBSE Grade Book').'<span>'.Yii::t('app','View CBSE Grade book').'</span>',array('/examination/exam/cbsc'),array('class'=>'cbscgrad-b_ico','active'=>(Yii::app()->controller->id=='exam'))); ?></li>  
                    <?php } ?>
                </ul>
                <?php
                $year = Yii::app()->user->year;
                $settings_model = CbscExamSettings::model()->findByAttributes(array('academic_yr_id'=>$year));
                if($visible)
                {
                ?>                                
                <ul>                               
                    <h1><?php echo Yii::t('app','Settings');?></h1>
                    <?php   if($settings_model!=NULL){ ?>
                        <li class="<?php if(Yii::app()->controller->id=='exam' and (Yii::app()->controller->action->id=='gradeSettings' or Yii::app()->controller->action->id=='settings')) { echo "list_active"; } ?>"><?php echo CHtml::link(Yii::t('app','CBSE Exam Settings').'<span>'.Yii::t('app','View CBSE Exam Settings').'</span>',array('/CBSCExam/exam/gradeSettings', 'set_id'=>$settings_model->id),array('class'=>'cbscgrad-exmsetng_ico')); ?></li>  
                    <?php } 
                    else{?>                 
                        <li class="<?php if(Yii::app()->controller->id=='exam' and Yii::app()->controller->action->id=='settings') { echo "list_active"; } ?>"><?php echo CHtml::link(Yii::t('app','CBSE Exam Settings').'<span>'.Yii::t('app','View CBSE Exam Settings').'</span>',array('/CBSCExam/exam/settings'),array('class'=>'cbscgrad-exmsetng_ico')); ?></li>                  
                    <?php   }?>    
                </ul>
                <?php } ?>
</div>
        
    
    
<div id="ajax-updated-region">
<?php //echo CHtml::link("name", array('/site/manage', 'xxx' => '', 'yyy' => '')); ?>
<?php //echo CHtml::link("number", array('/site/manage', 'xxx' => '', 'zzz' => '')); ?>
</div>
   
