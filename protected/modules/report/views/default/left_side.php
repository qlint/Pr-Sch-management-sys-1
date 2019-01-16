<div id="othleft-sidebar">
             <!--<div class="lsearch_bar">
             	<input type="text" value="Search" class="lsearch_bar_left" name="">
                <input type="button" class="sbut" name="">
                <div class="clear"></div>
  </div>-->    <h1><?php echo Yii::t('app','Manage Reports');?></h1>   
                    <?php
                    $level = Configurations::model()->findByPk(41);
                    if($level->config_value !=1){
                        $visible = true;
                    }
                    else{
                        $visible = false;
                    }
					if(Configurations::model()->isSemesterEnabled()){
						$semester_v	=true;
					}
					else{
						$semester_v	=false;
					}
                    
			function t($message, $category = 'cms', $params = array(), $source = null, $language = null) 
{
    return $message;
}

			$this->widget('zii.widgets.CMenu',array(
			'encodeLabel'=>false,
			'activateItems'=>true,
			'activeCssClass'=>'list_active',
			'items'=>array(
					
						    
					
						array('label'=>Yii::t('app','Advanced Report').'<span>'.Yii::t('app','Student advanced report').'</span>', 'url'=>array('/report/default/advancedreport'),'linkOptions'=>array('class'=>'advancereport_ico'),'active'=> (Yii::app()->controller->action->id=='advancedreport')),
		  				 array('label'=>''.'<h1>'.Yii::t('app','Assessment Report').'</h1>'), 
					
						array('label'=>Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' '.Yii::t('app','Assessment Report').'<span>'.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' '.Yii::t('app','wise assessment report').'</span>', 'url'=>array('/report/default/assessment'),'active'=>(Yii::app()->controller->action->id=='assessment' ? true : false),'linkOptions'=>array('class'=>'assessment-report_ico')),
						array('label'=>Yii::t('app','Student Assessment Report').'<span>'.Yii::t('app','Student assessment').'</span>', 'url'=>array('/report/default/studentexam'),'active'=>(Yii::app()->controller->action->id=='studentexam' ? true : false),'linkOptions'=>array('class'=>'student-assessment_ico')),
						
						array('label'=>Yii::t('app','Semester Assessment Reports').'<span>'.Yii::t('app','Semester Assessment Reports').'</span>', 'url'=>array('/report/default/semesterreport'),'active'=>(Yii::app()->controller->action->id=='semesterreport' ? true : false),'linkOptions'=>array('class'=>'set_t_ico'),'visible'=>$semester_v),
						
						array('label'=>Yii::t('app','CBSE Reports').'<span>'.Yii::t('app','CBSE Assesment Report').'</span>', 'url'=>array('/report/default/cbscreport'),'active'=>(Yii::app()->controller->action->id=='cbscreport' or Yii::app()->controller->action->id=='viewreport'),'linkOptions'=>array('class'=>'set_t_ico'),'visible'=>$visible),
						
						
						
						array('label'=>''.'<h1>'.Yii::t('app','Attendance Report').'</h1>'),
						
						array('label'=>Yii::t('app','Teacher Attendance').'<span>'.Yii::t('app','Teacher attendance report').'</span>', 'url'=>array('/report/default/employeeattendance'),'active'=>(Yii::app()->controller->action->id=='employeeattendance' ? true : false),'linkOptions'=>array('class'=>'teacher-attendance_ico'), 'visible'=>((Configurations::model()->teacherAttendanceMode() != 2) ? true : false )),
						
						array('label'=>Yii::t('app','Teacher Subject Wise Attendance').'<span>'.Yii::t('app','Subject wise attendance for teachers').'</span>', 'url'=>array('/report/default/teachersubwise'),'active'=>(Yii::app()->controller->action->id=='teachersubwise' ? true : false),'linkOptions'=>array('class'=>'attendance-register_ico'), 'visible'=>((Configurations::model()->teacherAttendanceMode() != 1) ? true : false )),
						
						array('label'=>Yii::t('app','Student Attendance').'<span>'.Yii::t('app','Student attendance report').'</span>', 'url'=>array('/report/default/studentattendance'),'active'=>(Yii::app()->controller->action->id=='studentattendance' ? true : false),'linkOptions'=>array('class'=>'student-attendance-report_ico'), 'visible'=>((Configurations::model()->studentAttendanceMode() != 2) ? true : false )),
						
						array('label'=>Yii::t('app','Student Subject Wise Attendance').'<span>'.Yii::t('app','Student subject wise attendance report').'</span>', 'url'=>array('/report/default/subwiseattentance'),'active'=>(Yii::app()->controller->action->id=='subwiseattentance' ? true : false),'linkOptions'=>array('class'=>'grade-book_ico'),'visible'=>((Configurations::model()->studentAttendanceMode() != 1) ? true : false )),
						
						array('label'=>Yii::t('app','Attendance Percentage Reminder').'<span>'.Yii::t('app','Attendance percentage report').'</span>', 'url'=>array('/report/default/reminder'),'active'=>(Yii::app()->controller->action->id=='reminder' or Yii::app()->controller->id=='notification' ? true : false),'linkOptions'=>array('class'=>'attandance-precents-report_ico'), 'visible'=>((Configurations::model()->studentAttendanceMode() != 2) ? true : false )),
						
				),
			));	
			?>
            
           <?php /*?>  <ul>
                <?php if($visible	==	1){ ?>
                <li class="<?php if(Yii::app()->controller->id=='semester') { echo "list_active"; } ?>">
                    <?php echo CHtml::link(Yii::t('app','Manage Semesters').'<span>'.Yii::t('app','Semesters for the Courses ').'</span>',array('/courses/semester'),array('class'=>'gradebook_ico','active'=>(Yii::app()->controller->id=='semester'))); ?>
                </li> 
                <?php } ?>
            </ul><?php */?>
		</div>
        <script type="text/javascript">

	$(document).ready(function () {
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