<?php 
//check theme set or not
$header_logo_background="";
$themes= PortalThemes::model()->findByAttributes(array('user_id'=> Yii::app()->user->id));
    if($themes)
    {
        $header_logo_background=$themes->header_logo_background;
    }
    
?>
<div class="leftpanel">
	<div class="logopanel" style="background-color: <?php echo $header_logo_background; ?>">
        <h1><span></span>                
        <?php 
            $filename=  Logo::model()->getLogo();
            if($filename!=NULL)
            {
                echo '<img src="uploadedfiles/school_logo/'.$filename[2].'" alt="'.$filename[2].'" border="0" height="55" class="img-responsive" />';
            }
        ?>
          <!--<img src="images/portal/logo.png" width="190" height="32" />--> 
           <span></span></h1>
    </div><!-- logopanel -->
       
         <div class="leftpanelinner"> 
          <?php 
                    $teacher=Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));                   
                    ?>
                    
                    <!-- This is only visible to small devices -->
        <div class="visible-xs hidden-sm hidden-md hidden-lg">   
            <div class="media userlogged">
                         
              <?php
					 if($teacher->photo_file_name!=NULL)
					 { 
					 	$path = Employees::model()->getProfileImagePath($teacher->id);
						echo '<img  src="'.$path.'" alt="'.$teacher->photo_file_name.'"  width="40" height="41" />';
					}
					elseif($teacher->gender=='M')
					{
						echo '<img  src="images/portal/p-small-male_img.png" alt='.$teacher->first_name.' />'; 
					}
					elseif($teacher->gender=='F')
					{
						echo '<img  src="images/portal/p-small-female_img.png" alt='.$teacher->first_name.' />';
					}
					?>
                <div class="media-body">
                    <h4><?php echo ucfirst($teacher->last_name.' '.$teacher->first_name);?></h4>
                    
                </div>
            </div>
          
            <h5 class="sidebartitle actitle">Account</h5>
            <ul class="nav nav-pills nav-stacked nav-bracket mb30">
            <li> <?php echo CHtml::link('<i class="glyphicon glyphicon-user"></i> '.Yii::t('app','My Account'),array('/teachersportal/default/profile'),array('class'=>'profile')); ?> </li>
                <li> <?php echo CHtml::link('<i class="glyphicon glyphicon-cog"></i> '.Yii::t('app','Settings'),array('/user/accountProfile'),array('class'=>'profile')); ?> </li>
                <li> <?php echo CHtml::link('<i class="glyphicon glyphicon-log-out"></i>'.Yii::t('app','Logout'), array('/user/logout'));?> </li>
            </ul>
            </div>
               
       
        	<h5 class="sidebartitle"><?php echo Yii::t('app','Navigation');?></h5>
      <ul class="nav nav-pills nav-stacked nav-bracket">
            <?php 
			if(Yii::app()->controller->module->id=='teachersportal' and  Yii::app()->controller->action->id =='dashboard')
			{
				echo '<li class="active">';
				echo CHtml::link('<i class="fa fa-home"></i> <span>'.Yii::t('app','Dashboard').'</span>',array('/teachersportal/default/dashboard'));
				echo '</li>';
			}
			else
			{
				echo '<li>';
				echo CHtml::link('<i class="fa fa-home"></i> <span>'.Yii::t('app','Dashboard').'</span>',array('/teachersportal/default/dashboard'));
				echo '</li>';
			}
			?>
            
            <?php
			if(Yii::app()->controller->module->id=='portalmailbox' and  Yii::app()->controller->id!='news')
			
			{	echo '<li class="active">';
				echo CHtml::link('<i class="fa fa-envelope-o"></i> <span>'.Yii::t('app','Messages').'</span>',array('/portalmailbox'),array('class'=>'mssg_active'));
				echo '</li>';
			}
			else
			{	echo '<li>';
				echo CHtml::link('<i class="fa fa-envelope-o"></i> <span>'.Yii::t('app','Messages').'</span>',array('/portalmailbox'),array('class'=>'mssg'));
				echo '</li>';
			}
			?>
            
            <?php
				if(ModuleAccess::model()->check('HR')){	// checking whether HR module is enabled
					if(Yii::app()->controller->id=='leaves'){
						echo '<li class="active">';
						echo CHtml::link('<i class="fa fa-check-square"></i> <span>'.Yii::t('app','Leaves').'</span>',array('/teachersportal/leaves/index'),array('class'=>'news_active'));
						echo '</li>';
					}
					else{
						echo '<li>';
						echo CHtml::link('<i class="fa fa-check-square"></i> <span>'.Yii::t('app','Leaves').'</span>',array('/teachersportal/leaves/index'),array('class'=>'news'));
						echo '</li>';
					}
				}
			?>
            <?php
            if(ModuleAccess::model()->check('My Account'))
			{
				if(Yii::app()->controller->id=='news')
				{
					echo '<li class="active">';
					echo CHtml::link('<i class="fa fa-newspaper-o"></i> <span>'.Yii::t('app','News').'</span>',array('/portalmailbox/news'),array('class'=>'news_active'));
					echo '</li>';
				}
				else
				{
					echo '<li>';
					echo CHtml::link('<i class="fa fa-newspaper-o"></i> <span>'.Yii::t('app','News').'</span>',array('/portalmailbox/news'),array('class'=>'news'));
					echo '</li>';
				}
			?>
            
            <?php
				if(Yii::app()->controller->action->id=='event')
				{
					echo '<li class="active">';
					echo CHtml::link('<i class="fa fa-pencil-square-o"></i> <span>'.Yii::t('app','Events').'</span>',array('/dashboard/default/event'),array('class'=>'attendance_active'));
					echo '</li>';
				}
				else
				{
					echo '<li>';
					echo CHtml::link('<i class="fa fa-pencil-square-o"></i> <span>'.Yii::t('app','Events').'</span>',array('/dashboard/default/event'),array('class'=>'attendance'));
					echo '</li>';
				}
			
          
				if(Yii::app()->controller->action->id=='eventlist')
				{
					echo '<li class="active">';
					echo CHtml::link('<i class="fa fa-calendar"></i> <span>'.Yii::t('app','Calendar').'</span>',array('/teachersportal/default/eventlist'),array('class'=>'attendance_active'));
					echo '</li>';
				}
				else
				{
					echo '<li>';
					echo CHtml::link('<i class="fa fa-calendar"></i> <span>'.Yii::t('app','Calendar').'</span>',array('/teachersportal/default/eventlist'),array('class'=>'attendance'));
					echo '</li>';
				}
           	}
			

            if(ModuleAccess::model()->check('Courses'))
			{
				if(Yii::app()->controller->id=='course')
				{
					echo '<li class="active">';
					echo CHtml::link('<i class="fa fa-list-alt"></i> <span>'.Yii::t('app','My Courses').'</span>',array('/teachersportal/course'),array('class'=>'attendance_active'));
					echo '</li>';
				}
				else
				{
					echo '<li>';
					echo CHtml::link('<i class="fa fa-list-alt"></i> <span>'.Yii::t('app','My Courses').'</span>',array('/teachersportal/course'),array('class'=>'attendance'));
					echo '</li>';
				}
			}
           	
           	if(ModuleAccess::model()->check('Downloads'))
			{
			   	if(Yii::app()->controller->id=='teachers' and Yii::app()->controller->module->id=='downloads')
				{
					echo '<li class="active">';
					echo CHtml::link('<i class="fa fa-download"></i> <span>'.Yii::t('app', 'Downloads').'</span>',array('/downloads/teachers'),array('class'=>'library_active'));
					echo '</li>';
				}
				else
				{
					echo '<li>';
					echo CHtml::link('<i class="fa fa-download"></i> <span>'.Yii::t('app', 'Downloads').'</span>',array('/downloads/teachers'),array('class'=>'library'));
					echo '</li>';
				}
			}
		   
		   	$model = Configurations::model()->findByAttributes(array('id'=>38)); 
			if($model->config_value == 1)
			{ 
			   	if(Yii::app()->controller->id=='default' and Yii::app()->controller->action->id=='achievements')
				{
					echo '<li class="active">';
					echo CHtml::link('<i class="fa fa-shield"></i> <span>'.Yii::t('app','Achievements').'</span>',array('/teachersportal/default/achievements'));
					echo '</li>';
				}
				else
				{
					echo '<li>';
					echo CHtml::link('<i class="fa fa-shield"></i> <span>'.Yii::t('app','Achievements').'</span>',array('/teachersportal/default/achievements'));
					echo '</li>';
				}
		   	}
           
           
          
		   	if((Yii::app()->controller->action->id=='profile' or Yii::app()->controller->action->id=='editprofile' or Yii::app()->controller->action->id=='documentupdate') and Yii::app()->controller->id=='default')
			{
				echo '<li class="active">';
				echo CHtml::link('<i class="fa fa-user"></i> <span>'.Yii::t('app','Profile').'</span>',array('/teachersportal/default/profile'),array('class'=>'profile_active'));
				echo '</li>';
			}
			else
			{
				echo '<li>';
				echo CHtml::link('<i class="fa fa-user"></i> <span>'.Yii::t('app','Profile').'</span>',array('/teachersportal/default/profile'),array('class'=>'profile'));
				echo '</li>';
			}
		  

			if(ModuleAccess::model()->check('Attendance'))
			{
			   	if(Yii::app()->controller->action->id=='attendance' or Yii::app()->controller->action->id=='employeeattendance' or Yii::app()->controller->action->id=='studentattendance' or Yii::app()->controller->action->id=='tpBatches' or Yii::app()->controller->action->id=='tpAttendance' or Yii::app()->controller->action->id=='teachersubwise' or Yii::app()->controller->action->id=='subwiseattendance'  or Yii::app()->controller->action->id=='daily' or Yii::app()->controller->action->id=='day' or Yii::app()->controller->action->id=='StudentDayAttendance' or Yii::app()->controller->action->id=='studentAttendance')
				{
					echo '<li class="active">';					
						echo CHtml::link('<i class="fa fa-file-text"></i> <span>'.Yii::t('app','Attendance').'</span>',array('/teachersportal/default/attendance'),array('class'=>'attendance_active'));					
					echo '</li>';
				}
				else
				{
					echo '<li>';					
						echo CHtml::link('<i class="fa fa-file-text"></i> <span>'.Yii::t('app','Attendance').'</span>',array('/teachersportal/default/attendance'),array('class'=>'attendance'));					
					echo '</li>';
				}
			}
		   
			if(ModuleAccess::model()->check('Timetable'))
			{
			   	if(Yii::app()->controller->action->id=='timetable' or Yii::app()->controller->action->id=='employeetimetable'  or Yii::app()->controller->action->id=='studenttimetable' or Yii::app()->controller->action->id=='daytimetable' or Yii::app()->controller->action->id=='employeeClassTimetable' or Yii::app()->controller->action->id=='employeeFlexibleClassTimetable'or Yii::app()->controller->action->id=='employeeClasstimetable' or Yii::app()->controller->action->id=='employeeFlexibleTimetable')
				{
					echo '<li class="active">';
					echo CHtml::link('<i class="fa fa-calendar-o"></i> <span>'.Yii::t('app','TimeTable').'</span>',array('/teachersportal/default/timetable'),array('class'=>'attendance_active'));
					echo '</li>';
				}
				else
				{
					echo '<li>';
					echo CHtml::link('<i class="fa fa-calendar-o"></i> <span>'.Yii::t('app','TimeTable').'</span>',array('/teachersportal/default/timetable'),array('class'=>'attendance'));
					echo '</li>';
				}
			}
		   
			if(ModuleAccess::model()->check('Examination'))
			{
			   	if(Yii::app()->controller->id == 'exams' or Yii::app()->controller->id == 'examScores' or Yii::app()->controller->id == 'coScholastic' or Yii::app()->controller->module->id=='onlineexam' or Yii::app()->controller->id=='exam17')
				{
					echo '<li class="active">';
					echo CHtml::link('<i class="fa fa-pencil"></i> <span>'.Yii::t('app','Exams').'</span>',array('/teachersportal/exams/index'),array('class'=>'exams_active'));
					echo '</li>';
				}
				else
				{
					echo '<li>';
					echo CHtml::link('<i class="fa fa-pencil"></i> <span>'.Yii::t('app','Exams').'</span>',array('/teachersportal/exams/index'),array('class'=>'exams'));
					echo '</li>';
				}
			}
		   
		   	$model = Configurations::model()->findByAttributes(array('id'=>39));
			if($model->config_value == 1)
			{ 
		   		if(Yii::app()->controller->id == 'complaints')
				{
					echo '<li class="active">';
					echo CHtml::link('<i class="fa fa-comment"></i> <span>'.Yii::t('app','Complaints').'</span>',array('/complaints/feedbacklist'),array('class'=>'complaints_active'));
					echo '</li>';
				}
				else
				{
					echo '<li>';
		   			echo CHtml::link('<i class="fa fa-comment"></i> <span>'.Yii::t('app','Complaints').'</span>',array('/complaints/feedbacklist'),array('class'=>'complaints'));
					echo '</li>';
				}
		  	}

	   		if(Yii::app()->controller->id == 'portalThemes')
			{
				echo '<li class="active">';
				echo CHtml::link('<i class="fa fa-tint"></i> <span>'.Yii::t('app','Themes').'</span>',array('/portalThemes'),array('class'=>'settings_active'));
				echo '</li>';
			}
			else
			{
				echo '<li>';
	   			echo CHtml::link('<i class="fa fa-tint"></i> <span>'.Yii::t('app','Themes').'</span>',array('/portalThemes'),array('class'=>'settings'));
				echo '</li>';
			}
		  

	   		if(Yii::app()->controller->module->id == 'user')
			{
				echo '<li class="active">';
				echo CHtml::link('<i class="fa fa-gear"></i> <span>'.Yii::t('app','Settings').'</span>',array('/user/accountProfile'),array('class'=>'settings_active'));
				echo '</li>';
			}
			else
			{
				echo '<li>';
	   			echo CHtml::link('<i class="fa fa-gear"></i> <span>'.Yii::t('app','Settings').'</span>',array('/user/accountProfile'),array('class'=>'settings'));
				echo '</li>';
			}
			
			if(Yii::app()->controller->id=='materialRequistion' and (Yii::app()->controller->action->id=='purchase' or Yii::app()->controller->action->id=='requestapprove'or Yii::app()->controller->action->id=='requestdisapprove'or Yii::app()->controller->action->id=='sendrequest'))
			{
				echo '<li class="active">';
				echo CHtml::link('<i class="fa fa-shopping-cart"></i> <span>'.Yii::t('app','Purchase').'</span>',array('/teachersportal/materialRequistion/purchase'),array('class'=>'settings_active'));
				echo '</li>';
			}
			else
			{
				echo '<li>';
	   			echo CHtml::link('<i class="fa fa-shopping-cart"></i> <span>'.Yii::t('app','Purchase').'</span>',array('/teachersportal/materialRequistion/purchase'),array('class'=>'settings'));
				echo '</li>';
			}
			
			
			if(Yii::app()->controller->id=='materialRequistion' and (Yii::app()->controller->action->id=='index' or Yii::app()->controller->action->id=='create'or Yii::app()->controller->action->id=='update'))
			{
				echo '<li class="active">';
				echo CHtml::link('<i class="fa fa-outdent"></i> <span>'.Yii::t('app','Request Material').'</span>',array('/teachersportal/materialRequistion/index'),array('class'=>'settings_active'));
				echo '</li>';
			}
			else
			{
				echo '<li>';
	   			echo CHtml::link('<i class="fa fa-outdent"></i> <span>'.Yii::t('app','Request Material').'</span>',array('/teachersportal/materialRequistion/index'),array('class'=>'settings'));
				echo '</li>';
			}
			
			if(ModuleAccess::model()->check('Purchase')){	// checking whether Purchase module is enabled
				if(Yii::app()->controller->id=='salary' or Yii::app()->controller->id=='payslip'){
					echo '<li class="active">';
					echo CHtml::link('<i class="fa fa-file-text-o"></i> <span>'.Yii::t('app','Salary Details').'</span>',array('/teachersportal/salary/index'),array('class'=>'settings_active'));
					echo '</li>';
				}
				else{
					echo '<li>';
					echo CHtml::link('<i class="fa fa-file-text-o" aria-hidden="true"></i><span>'.Yii::t('app','Salary Details').'</span>',array('/teachersportal/salary/index'),array('class'=>'settings'));
					echo '</li>';
				}
			}


		   ?>
            </ul>
       </div><!-- leftpanelinner -->
</div><!-- leftpanel -->