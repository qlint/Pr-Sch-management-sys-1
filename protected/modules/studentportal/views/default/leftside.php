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
        <span></span></h1>
    </div><!-- logopanel -->

<div class="leftpanelinner"> 
	<!-- This is only visible to small devices -->
        <div class="visible-xs hidden-sm hidden-md hidden-lg">   
            <div class="media userlogged">
                <?php $student=Students::model()->findByAttributes(array('uid'=>Yii::app()->user->id)); ?>
            	<?php
				 	if($student->photo_file_name!=NULL)
				 	{ 
					 	$path = Students::model()->getProfileImagePath($student->id);
						echo '<img  src="'.$path.'" alt="'.$student->photo_file_name.'"  width="40" height="41" />';
					}
					elseif($student->gender=='M')
					{
						echo '<img  src="images/portal/p-small-male_img.png" alt='.$student->first_name.' />'; 
					}
					elseif($student->gender=='F')
					{
						echo '<img  src="images/portal/p-small-female_img.png" alt='.$student->first_name.' />';
					}
				?>
                <div class="media-body">
                    <h4><?php echo ucfirst($student->last_name.' '.$student->first_name);?></h4>
                   
                </div>
            </div>
          
            <h5 class="sidebartitle actitle">Account</h5>
            <ul class="nav nav-pills nav-stacked nav-bracket mb30">
              <li> <?php echo CHtml::link('<i class="glyphicon glyphicon-user"></i> '.Yii::t('app','My Account'),array('/studentportal/default/profile'),array('class'=>'profile')); ?> </li>
                <li> <?php echo CHtml::link('<i class="glyphicon glyphicon-cog"></i> '.Yii::t('app','Settings'),array('/user/accountProfile'),array('class'=>'profile')); ?> </li>
                <li> <?php echo CHtml::link('<i class="glyphicon glyphicon-log-out"></i> '.Yii::t('app','Logout'), array('/user/logout'));?> </li>
            </ul>
        </div>
        <?php /*?> <h5 class="sidebartitle"><?php echo Yii::t('app','Navigation'); ?></h5><?php */?>
         <ul class="nav nav-pills nav-stacked nav-bracket">
         	<li>
			 <?php 
				if(Yii::app()->controller->module->id=='studentportal' and  Yii::app()->controller->action->id =='dashboard')
				{
					echo '<li class="active">';
					echo CHtml::link('<i class="fa fa-home"></i> <span>'.Yii::t('app','Dashboard').'</span>',array('/studentportal/default/dashboard'));
					echo '</li>';
				}
				else
				{
					echo '<li>';
					echo CHtml::link('<i class="fa fa-home"></i> <span>'.Yii::t('app','Dashboard').'</span>',array('/studentportal/default/dashboard'));
					echo '</li>';
				}
			?>
		  </li>
          <li>
            <?php
			
			if(Yii::app()->controller->module->id=='portalmailbox' and  Yii::app()->controller->id!='news')
			{
				echo '<li class="active">';
				echo CHtml::link('<i class="fa fa-envelope-o"></i> <span>'.Yii::t('app','Messages').'</span>',array('/portalmailbox'));
				echo '</li>';
			}
			else
			{
				echo '<li>';
				echo CHtml::link('<i class="fa fa-envelope-o"></i> <span>'.Yii::t('app','Messages').'</span>',array('/portalmailbox'));
				echo '</li>';
			}
			?>
           </li>
           <li>
            <?php
            if(ModuleAccess::model()->check('My Account'))
			{
				if(Yii::app()->controller->id=='news')
				{
					echo '<li class="active">';
					echo CHtml::link('<i class="fa fa-newspaper-o"></i> <span>'.Yii::t('app','News').'</span>',array('/portalmailbox/news'));
					echo '</li>';
				}
				else
				{
					echo '<li>';
					echo CHtml::link('<i class="fa fa-newspaper-o"></i> <span>'.Yii::t('app','News').'</span>',array('/portalmailbox/news'));
					echo '</li>';
				}
			?>
           </li>
             <li>
            <?php
				if(Yii::app()->controller->action->id=='event')
				{
					echo '<li class="active">';
					echo CHtml::link('<i class="fa fa-pencil-square-o"></i> <span>'.Yii::t('app','Events').'</span>',array('/dashboard/default/event'));
					echo '</li>';
				}
				else
				{
					echo '<li>';
					echo CHtml::link('<i class="fa fa-pencil-square-o"></i> <span>'.Yii::t('app','Events').'</span>',array('/dashboard/default/event'));
					echo '</li>';
				}
           
			?>
            </li>
            <li>
            <?php
				if(Yii::app()->controller->action->id=='eventlist')
				{
					echo '<li class="active">';
					echo CHtml::link('<i class="fa fa-calendar"></i> <span>'.Yii::t('app','Calendar').'</span>',array('/studentportal/default/eventlist'));
					echo '</li>';
				}
				else
				{
					echo '<li>';
					echo CHtml::link('<i class="fa fa-calendar"></i> <span>'.Yii::t('app','Calendar').'</span>',array('/studentportal/default/eventlist'));
					echo '</li>';
				}
           	}
			?>
            </li>
            <li>
           <?php
           	if(ModuleAccess::model()->check('Downloads'))
			{
			   	if(Yii::app()->controller->module->id=='downloads' || Yii::app()->controller->id=='students')
				{
					echo '<li class="active">';
					echo CHtml::link('<i class="fa fa-download"></i> <span>'.Yii::t('app','Downloads').'</span>',array('/downloads/students'));
					echo '</li>';
				}
				else
				{
					echo '<li>';
					echo CHtml::link('<i class="fa fa-download"></i> <span>'.Yii::t('app','Downloads').'</span>',array('/downloads/students'));
					echo '</li>';
				}
			}
		   ?>
           </li> 
           <li>
           <?php
		   	if(Yii::app()->controller->id == 'default' and (Yii::app()->controller->action->id=='profile' or Yii::app()->controller->action->id=='documentupdate' or Yii::app()->controller->action->id=='editprofile'))
			{
				echo '<li class="active">';
				echo CHtml::link('<i class="fa fa-user"></i> <span>'.Yii::t('app','Profile').'</span>',array('/studentportal/default/profile'));
				echo '</li>';
			}
			else
			{
				echo '<li>';
				echo CHtml::link('<i class="fa fa-user"></i> <span>'.Yii::t('app','Profile').'</span>',array('/studentportal/default/profile'));
				echo '</li>';
			}
		   ?>
           </li>
           <li>
           <?php
           	if(ModuleAccess::model()->check('Courses'))
			{
			   	if(Yii::app()->controller->action->id=='course')
				{
					echo '<li class="active">';
					echo CHtml::link('<i class="fa fa-list-alt"></i> <span>'.Yii::t('app','Course').'</span>',array('/studentportal/default/course'));
					echo '</li>';
				}
				else
				{
					echo '<li>';
					echo CHtml::link('<i class="fa fa-list-alt"></i> <span>'.Yii::t('app','Course').'</span>',array('/studentportal/default/course'));
					echo '</li>';
				}
			}
		   ?>
           </li>   
             <li>
           <?php
           	if(ModuleAccess::model()->check('Attendance'))
			{
			    $model = AttendanceSettings::model()->findByAttributes(array('config_key'=>'type'));
				if($model->config_value == 1){
					if(Yii::app()->controller->action->id=='attendance'  or Yii::app()->controller->action->id=='AbsenceDetails' or Yii::app()->controller->action->id=='subwiseattendance')
					{
						echo '<li class="active">';
						  if(Configurations::model()->studentAttendanceMode() == 2){ // for subjectwise attendance
							echo CHtml::link('<i class="fa fa-file-text"></i> <span>'.Yii::t('app','Attendance').'</span>',array('/studentportal/default/subwiseattendance','id'=>$student->id)); 
						  }
						  else{
							  echo CHtml::link('<i class="fa fa-file-text"></i> <span>'.Yii::t('app','Attendance').'</span>',array('/studentportal/default/attendance'));
						  }
						echo '</li>';
					}
					else
					{
						echo '<li>';
						if(Configurations::model()->studentAttendanceMode() == 2){
							echo CHtml::link('<i class="fa fa-file-text"></i> <span>'.Yii::t('app','Attendance').'</span>',array('/studentportal/default/subwiseattendance')); // for subjectwise attendance
						  }
						  else{
							  echo CHtml::link('<i class="fa fa-file-text"></i> <span>'.Yii::t('app','Attendance').'</span>',array('/studentportal/default/attendance'));
						  }
						echo '</li>';
					}
				}
				else{ 
					if(Yii::app()->controller->action->id=='spAttendance')
					{
						echo '<li class="active">';
						echo CHtml::link('<i class="fa fa-file-text"></i> <span>'.Yii::t('app','Attendance').'</span>',array('/attendance/subjectAttendance/spAttendance'));
						echo '</li>';
					}
					else
					{
						echo '<li>';
						echo CHtml::link('<i class="fa fa-file-text"></i> <span>'.Yii::t('app','Attendance').'</span>',array('/attendance/subjectAttendance/spAttendance'));
						echo '</li>';
					}
				}
			}
		   	
		   ?>
           </li>   
           <li>
           <?php
           	if(ModuleAccess::model()->check('Timetable'))
			{
			   	if(Yii::app()->controller->action->id=='timetable')
				{
					echo '<li class="active">';
					echo CHtml::link('<i class="fa fa-dedent"></i> <span>'.Yii::t('app','Timetable').'</span>',array('/studentportal/default/timetable'));
					echo '</li>';
				}
				else
				{
					echo '<li>';
					echo CHtml::link('<i class="fa fa-dedent"></i> <span>'.Yii::t('app','Timetable').'</span>',array('/studentportal/default/timetable'));
					echo '</li>';
				}
			}
		   ?>
           </li>   
           
<?php $model = Configurations::model()->findByAttributes(array('id'=>38)); 
	if($model->config_value == 1)
	{ ?>
            <li>
           <?php
		   	if(Yii::app()->controller->action->id=='achievements')
			{
				echo '<li class="active">';
				echo CHtml::link('<i class="fa fa-shield"></i> <span>'.Yii::t('app','Achievements').'</span>',array('/studentportal/default/achievements'));
				echo '</li>';
			}
			else
			{
				echo '<li>';
				echo CHtml::link('<i class="fa fa-shield"></i> <span>'.Yii::t('app','Achievements').'</span>',array('/studentportal/default/achievements'));
				echo '</li>';
			}
		   ?>
           </li> 
<?php } ?>              
           <li>
           <?php
		   	if(Yii::app()->controller->action->id=='lognotice')
			{
				echo '<li class="active">';
				echo CHtml::link('<i class="fa fa-sign-in" aria-hidden="true"></i> <span>'.Yii::t('app','Log').'</span>',array('/studentportal/default/lognotice'));
				echo '</li>';
			}
			else
			{
				echo '<li>';
				echo CHtml::link('<i class="fa fa-sign-out" aria-hidden="true"></i><span>'.Yii::t('app','Log').'</span>',array('/studentportal/default/lognotice'));
				echo '</li>';
			}
		   ?>
           </li>     
           <li>
           <?php
           	if(ModuleAccess::model()->check('Examination'))
			{
	            $actions= array('exam', 'cbsc', 'examList', 'examTimetable','semResult','semexamList','cbsc17');
			   	if(Yii::app()->controller->action->id=='exams' or in_array(Yii::app()->controller->action->id,$actions))
				{
					echo '<li class="active">';
					echo CHtml::link('<i class="fa fa-pencil"></i> <span>'.Yii::t('app','Exams').'</span>',array('/studentportal/default/exams'));
					echo '</li>';
				}
				else
				{
					echo '<li>';
					echo CHtml::link('<i class="fa fa-pencil"></i> <span>'.Yii::t('app','Exams').'</span>',array('/studentportal/default/exams'));
					echo '</li>';
				}
			}
		   ?>
           </li>     
              <li>
           <?php
           	if(ModuleAccess::model()->check('Library'))
			{
		   		if(Yii::app()->controller->action->id=='manage' || Yii::app()->controller->action->id=='booksearch' || Yii::app()->controller->action->id=='bookBorrowed' || (Yii::app()->controller->id=='authors' and Yii::app()->controller->action->id=='authordetails'))
				{
					echo '<li class="active">';
					echo CHtml::link('<i class="fa fa-folder-open"></i> <span>'.Yii::t('app','Library').'</span>',array('/library/book/bookBorrowed'));
					echo '</li>';
				}
				else
				{
					echo '<li>';
					echo CHtml::link('<i class="fa fa-folder-open"></i> <span>'.Yii::t('app','Library').'</span>',array('/library/book/bookBorrowed'));
					echo '</li>';
				}
			}
		   ?>
           </li>        
             <li>
           <?php
           	if(ModuleAccess::model()->check('Hostel'))
			{
		   		if((Yii::app()->controller->module->id=='hostel' && Yii::app()->controller->action->id=='index') || Yii::app()->controller->id=='room'|| (Yii::app()->controller->id == 'registration' and Yii::app()->controller->action->id=='create'))
				{
					echo '<li class="active">';
					echo CHtml::link('<i class="fa fa-group"></i> <span>'.Yii::t('app','Hostel').'</span>',array('/hostel'));
					echo '</li>';
				}
				else
				{
					echo '<li>';
					echo CHtml::link('<i class="fa fa-group"></i> <span>'.Yii::t('app','Hostel').'</span>',array('/hostel'));
					echo '</li>';
				}
			}
		   ?>
           </li>
	<?php $model = Configurations::model()->findByAttributes(array('id'=>39));
	if($model->config_value == 1)
	{ ?>
           <li>
           <?php
		   		if(Yii::app()->controller->id == 'complaints')
				{
					echo '<li class="active">';
					echo CHtml::link('<i class="fa fa-comment"></i> <span>'.Yii::t('app','Complaints').'</span>',array('/complaints/feedbacklist'));
					echo '</li>';
				}
				else
				{
		   			echo CHtml::link('<i class="fa fa-comment"></i> <span>'.Yii::t('app','Complaints').'</span>',array('/complaints/feedbacklist'));
				}
		   ?>
           </li>
<?php } ?>            
           <li>
           <?php
		   		if(Yii::app()->controller->id == 'portalThemes')
				{
					echo '<li class="active">';
					echo CHtml::link('<i class="fa fa-tint"></i> <span>'.Yii::t('app','Themes').'</span>',array('/portalThemes'));
					echo '</li>';
				}
				else
				{
		   			echo CHtml::link('<i class="fa fa-tint"></i> <span>'.Yii::t('app','Themes').'</span>',array('/portalThemes'));
				}
		   ?>
           </li>
           <li>
            <?php
			if(ModuleAccess::model()->check('HR')){	// checking whether HR module is enabled
				if(Yii::app()->controller->id=='default' and (Yii::app()->controller->action->id=='requisition' or Yii::app()->controller->action->id=='RequisitionView'or Yii::app()->controller->action->id=='RequisitionUpdate'or Yii::app()->controller->action->id=='requisitionview'))
				{
					echo '<li class="active">';
					echo CHtml::link('<i class="fa fa-compress"></i> <span>'.Yii::t('app','Material Requisition').'</span>',array('/studentportal/default/RequisitionView'));
					echo '</li>';
				}
				else
				{
					echo '<li>';
					echo CHtml::link('<i class="fa fa-compress"></i> <span>'.Yii::t('app','Material Requisition').'</span>',array('/studentportal/default/RequisitionView'));
					echo '</li>';
				}
			}
           
			?>
            </li>
           <li>
           <?php
		   		if(Yii::app()->controller->module->id == 'user')
				{
					echo '<li class="active">';
					echo CHtml::link('<i class="fa fa-gear"></i> <span>'.Yii::t('app','Settings').'</span>',array('/user/accountProfile'));
					echo '</li>';
				}
				else
				{
		   			echo CHtml::link('<i class="fa fa-gear"></i> <span>'.Yii::t('app','Settings').'</span>',array('/user/accountProfile'));
				}
		   ?>
           </li> 
           
           
            
           
           </ul>
</div>
</div>
  