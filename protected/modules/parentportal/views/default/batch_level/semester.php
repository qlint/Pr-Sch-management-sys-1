 <script>
	function getstudent() // Function to see student profile
	{
		var studentid = document.getElementById('studentid').value;
		if(studentid!='')
		{
			window.location= 'index.php?r=parentportal/default/semesters&id='+studentid;	
		}
		else
		{
			window.location= 'index.php?r=parentportal/default/exams';
		}
	}
        
        function result()
        {
            var studentid   =   document.getElementById('studentid').value;
            var $sem_id     =   $('#sem_id').val(); 
            if(studentid!='')
            {
                    window.location= 'index.php?r=parentportal/default/semesters&id='+studentid+'&sem_id='+$sem_id;	
            }
            else
            {
                    window.location= 'index.php?r=parentportal/default/exams';
            }
        }
        
        
</script>
<?php $this->renderPartial('leftside');?> 
<?php   
    
    $student_id= "";
    $user=User::model()->findByAttributes(array('id'=>Yii::app()->user->id));
    $guardian = Guardians::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
    $students = Students::model()->findAllByAttributes(array('parent_id'=>$guardian->id));
    $criteria = new CDbCriteria;		
    $criteria->join = 'LEFT JOIN guardian_list t1 ON t.id = t1.student_id'; 
    $criteria->condition = 't1.guardian_id=:guardian_id and t.is_active=:is_active and is_deleted=:is_deleted';
    $criteria->params = array(':guardian_id'=>$guardian->id,':is_active'=>1,'is_deleted'=>0);
    $wards = Students::model()->findAll($criteria);
    $list= array();                       
    if($wards!=NULL)
    {
        foreach($wards as $ward)
        {
            if($ward->studentFullName('forParentPortal')!=''){
                $list[$ward->id]= $ward->studentFullName('forParentPortal');
            }
        }
    }                   
    if(count($wards)>0)
    {
         $sid=  key($list);               
        if(count($wards)==1) // Single Student 
        {          
            $student = Students::model()->findByAttributes(array('id'=>$sid));
            
        }
        elseif(isset($_REQUEST['id']) and $_REQUEST['id']!=NULL) // If Student ID is set
        {
            $student = Students::model()->findByAttributes(array('id'=>$_REQUEST['id']));
            
        }
        elseif(count($wards)>1) // Multiple Student
        {
            $student = Students::model()->findByAttributes(array('id'=>$sid));
        }

        $student=Students::model()->findByAttributes(array('id'=>$student->id));
        $batches= BatchStudents::model()->findAllByAttributes(array('student_id'=>$student->id));             
        $student_visible_fields   = FormFields::model()->getVisibleFields('Students', 'forParentPortal');
        $student_id= $student->id;  
    }                                                                       
?>
<div class="pageheader">
    <div class="col-lg-8">
      <h2><i class="fa fa-pencil"></i><?php echo Yii::t('app','Exams'); ?><span><?php echo Yii::t('app','View your exams here'); ?></span></h2>
    </div>
    <div class="col-lg-2">        
        <?php
        if(count($wards)>1) // Show drop down only if more than 1 student present
        {
            $student_list = CHtml::listData($students,'id','studentnameforparentportal');
            ?>
            <div class="student_dropdown" style="top:15px;">
            <?php
                echo CHtml::dropDownList('sid','',$list,array('prompt'=>Yii::t('app','Select'),'id'=>'studentid','class'=>'form-control input-sm mb14','style'=>'width:auto;display: inline; margin-left: 7px;','options'=>array($_REQUEST['id']=>array('selected'=>true)),'onchange'=>'getstudent();'));
            ?>
            </div> <!-- END div class="student_dropdown" -->
        <?php
        }
        else
        {
           echo CHtml::hiddenField('studentid',$_REQUEST['id']); 
        }
        ?>
    </div>
   
    <div class="breadcrumb-wrapper">
        <span class="label"><?php echo Yii::t('app','You are here:'); ?></span>
        <ol class="breadcrumb">
          <!--<li><a href="index.html">Home</a></li>-->         
          <li class="active"><?php echo Yii::t('app','Exams'); ?></li>
        </ol>
    </div>     
    <div class="clearfix"></div>      
</div>
    
<div class="contentpanel">

    <?php
        $flag=0;
        if(count($wards)>0)
        {          
            if(isset($student_id) && $student_id!='' && GuardianList::model()->checkRelation($student_id,$guardian->id))
            { 
            ?> 
                <div class="people-item">            
                    <div class="media">
                        <a href="#" class="pull-left">                  
                            <?php
                                if($student->photo_file_name!=NULL)
                                { 
                                                          $path = Students::model()->getProfileImagePath($student->id);
                                  echo '<img  src="'.$path.'" width="100" height="103" class="thumbnail media-object" />';
                                }
                                elseif($student->gender=='M')
                                {
                                    echo '<img  src="images/portal/prof-img_male.png" alt='.$student->first_name.' width="100" height="103" class="thumbnail media-object" />'; 
                                }
                                elseif($student->gender=='F')
                                {
                                    echo '<img  src="images/portal/prof-img_female.png" alt='.$student->first_name.' width="100" height="103" class="thumbnail media-object" />';
                                }
                              ?>
                        </a>
                        <div class="media-body">
                            <?php
								if(FormFields::model()->isVisible("fullname", "Students", "forStudentPortal")){
									?>
									<h4 class="person-name"><?php $name = $student->studentFullName('forParentPortal');
										echo CHtml::link($name,array('/parentportal/default/studentprofile', 'id'=>$student->id));
									?></h4>
									<?php
								}
								?>
								<?php 
								$batchstudents=BatchStudents::model()->findAllByAttributes(array('student_id'=>$student->id,'result_status'=>0));
								if(count($batchstudents)>1){ 
								echo CHtml::link('View Course Details', array('/parentportal/default/course', 'id'=>$student->id));
								}
								else{?>	
									  <?php if(FormFields::model()->isVisible('batch_id','Students','forParentPortal')){?>
									  <div class="text-muted"><strong><?php echo Yii::t('app','Course').' :';?></strong>
										<?php 
										  $batch = Batches::model()->findByPk($batchstudents[0]['batch_id']);
										  $semester	= Semester::model()->findByAttributes(array('id'=>$batch->semester_id));
										  echo ($batch->course123->course_name)?$batch->course123->course_name:"-";
										  $batch_student=BatchStudents::model()->findByAttributes(array('student_id'=>$student->id, 'batch_id'=>$student->batch_id, 'status'=>1));
										?>
									  </div>          
									  <div class="text-muted"> <strong><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' :';?></strong> <?php echo ($batch->name)?$batch->name:"-";?></div>
									  <?php } ?>
									   <?php  if($batch->semester_id!=NULL){ ?>
												<div class="text-muted"> <strong><?php echo Yii::t('app','Semester').' :';?></strong> <?php echo ($semester->name)?$semester->name:"-";?></div>
										<?php } ?>
							<?php } ?>	  
                            <div class="text-muted"><strong><?php echo Yii::t('app','Admission No').' :';?></strong> <?php echo $student->admission_no; ?></div>
                        </div>
              </div>
            </div>
            <div class="panel-heading">
              <!-- panel-btns -->
              <h3 class="panel-title"><?php echo Yii::t('app','Semester Result');?></h3>
            </div>
            <div class="people-item">
            
            <div class="row">   
                <div class="col-md-12">
<div class="button-bg">
<div class="top-hed-btn-left"> </div>
<div class="top-hed-btn-right">
<ul>
                                <li>
                                	<?php echo CHtml::link('<span>'.Yii::t('app','Back').'</span>',array('/parentportal/default/exams'),array('class'=>'btn btn-primary'));?>
                                </li>                            
                            </ul>
                            </div>
                        </div>
                    </div>
 

            <div class="row">
            	<div class="col-md-12 col-4-reqst">
            	<div class="row-pddng bx-style">
            	<div class="col-md-4 col-4-reqst">
 			<?php echo Yii::t('app', 'Select Semester');?>
                <?php               
                    $sem_data = array();
                    $course_array= array();
                    $batch_student  =   BatchStudents::model()->findAllByAttributes(array('student_id'=>$student->id,'academic_yr_id'=>Yii::app()->user->year));
                    if($batch_student!=NULL)
                    {
                        foreach ($batch_student as $data)
                        {
                            $batch_model =  Batches::model()->findByPk($data->batch_id);
                            if($batch_model!=NULL && $batch_model->semester_id!=NULL)
                            {
                                if(!in_array($batch_model->course_id, $course_array))
                                {
                                    $course_array[]= $batch_model->course_id;
                                }
                                $sem_model  =   Semester::model()->findByPk($batch_model->semester_id);
                                if($sem_model!=NULL)
                                {
                                    if(!in_array($sem_model->id,$sem_data))
                                    {
                                        $sem_data[]= $sem_model->id;
                                    }
                                }
                            }
                        }
                        $criteria= new CDbCriteria;
                        $criteria->addInCondition('id',$sem_data);
                        $semester_model= Semester::model()->findAll($criteria);
                        $sem_list	=   CHtml::listData($semester_model, 'id', 'name');	
                        $sem_list       =   CMap::mergeArray(array(0=>Yii::t('app','All')),$sem_list);
                        
                    }                     
                    echo CHtml::dropDownList("sem_id",$_REQUEST['sem_id'], $sem_list, array('empty'=>  Yii::t('app', 'Select'),'class'=>'form-control','onchange'=>'result()'));
                ?>                
                </div>
                
                </div>
            </div>
            </div>  
               
                <?php 
                    if(isset($_REQUEST['sem_id']) && $_REQUEST['sem_id']!=NULL)
                    {                
                        $criteria= new CDbCriteria;
                        $criteria->join= 'JOIN batch_students `bs` ON t.id=`bs`.batch_id';
                        $criteria->condition= '`bs`.student_id=:student_id AND t.academic_yr_id=:academic_yr_id AND t.is_active=:is_active AND t.is_deleted=:is_deleted';
                        $criteria->params= array(':student_id'=>$student_id, ':academic_yr_id'=>Yii::app()->user->year, ':is_active'=>1, ':is_deleted'=>0);
                        if(isset($_REQUEST['sem_id']) && $_REQUEST['sem_id']!=0)
                        {
                            $criteria->condition .= ' AND t.semester_id=:semester_id';
                            $criteria->params[':semester_id']=$_REQUEST['sem_id'];
                        }
                        $batches= Batches::model()->findAll($criteria);
                        //$batches= Batches::model()->findAllByAttributes(array('course_id'=>$course_array,'semester_id'=>$_REQUEST['sem_id'],'is_deleted'=>0,'is_active'=>1));
                        if($batches==NULL)
                        {
                            echo Yii::t('app','No Result Found');	
                        }
                        else
                        {
                        ?>

<div class="button-bg button-bg-none ">
<div class="top-hed-btn-left"> </div>
<div class="top-hed-btn-right">
                                        <?php echo CHtml::link(Yii::t('app','Generate PDF'), array('/parentportal/default/semesterPdf','id'=>$_REQUEST['id'],'sem_id'=>$_REQUEST['sem_id']),array('target'=>"_blank",'class'=>'btn btn-danger pull-right')); ?>                                
                                    </div>                            
                                </div>

                        <?php
						                  
			foreach ($batches as $batch)
                        {
                            ?>
                            <div class="exam-batch-main-block">
			
                            <h3><?php echo ucfirst($batch->name);?></h3>
                            <div class="exam-batch-block">
                                 
                            <?php
							$batch_id = $batch->id;
							if($batch_id!="" && ExamFormat::model()->getExamformat($batch_id)== 2){ //cbsc
								$cbsc_format    = ExamFormat::getCbscformat($batch_id);
                    			if($cbsc_format){
									$ex_group= CbscExamGroup17::model()->findAllByAttributes(array('batch_id'=>$batch->id,'result_published'=>1));
								}else{
                            		$ex_group= CbscExamGroups::model()->findAllByAttributes(array('batch_id'=>$batch->id,'result_published'=>1),array('order'=>'date DESC'));
								}
							}
							else{
								$ex_group= ExamGroups::model()->findAllByAttributes(array('batch_id'=>$batch->id,'result_published'=>1),array('order'=>'exam_date DESC')); 
							}
                            if($ex_group!=NULL)
                            {
                                foreach ($ex_group as $exam_group)
                                {
                                    ?><p><?php echo Yii::t('app','Exam Group').'  :-  '.ucfirst($exam_group->name); ?></p><?php
                                    $exam_arr= array();                                   
                                    $exam_group_id= $exam_group->id;
									if($batch_id!="" && ExamFormat::model()->getExamformat($batch_id)== 2){    //cbsc
										if($cbsc_format){
											$exams= CbscExams17::model()->findAllByAttributes(array('exam_group_id'=>$exam_group_id));
										}else{
											$exams= CbscExams::model()->findAllByAttributes(array('exam_group_id'=>$exam_group_id));
											
										}
									}
									else{
										$exams= Exams::model()->findAllByAttributes(array('exam_group_id'=>$exam_group_id));
									}
									
                                    if($exams!=NULL)
                                    {
                                        foreach ($exams as $exam)
                                        {
                                            $exam_arr[]=$exam->id;
                                        }
                                        $criteria= new CDbCriteria;
                                        $criteria->condition= "student_id=:student_id";
                                        $criteria->params= array(':student_id'=>$student_id);
                                        $criteria->addInCondition('exam_id', $exam_arr);
										if($batch_id!="" && ExamFormat::model()->getExamformat($batch_id)== 2){    //cbsc
										 	$cbsc_format    = ExamFormat::getCbscformat($batch_id);
											if($cbsc_format){
												$exam= CbscExamScores17::model()->findAll($criteria);
											}else{
                                        		$exam= CbscExamScores::model()->findAll($criteria);
											}
										}
										else{
											 $exam= ExamScores::model()->findAll($criteria);
										}
									    
										if(ExamFormat::model()->getExamformat($batch_id)== 2){
										
											$this->renderPartial('batch_level/semester_result17',array('exams'=>$exam,'student'=>$student,'batch'=>$batch,'ex_group_id'=>$exam_group_id));
										}
                                        else if(isset($exam))
                                        {                                  
                                                $this->renderPartial('batch_level/semester_result',array('exam'=>$exam,'student'=>$student,'batch'=>$batch));                                        
                                        }
                                    }
                                    else
                                        echo Yii::t('app','No Result Found');
                                    
                                }
                            }
                            else
                            {
                                echo Yii::t('app','No Exam Groups Found');	
                            }
                            ?></div></div><?php                                                       
                        }
                    }
                    }
                ?>                                             
               
            <!-- END div class="profile_details" -->
           
            
            <?php }
            else
            {
               $flag=1; 
            }
        }
        else
        {
            $flag=1;
        }                                                        
        if($flag==1)
        {
            ?>
            <div class="people-item">
                <center><?php echo Yii::t("app", "No Result Found") ?></center>
            </div>
            <?php
        }
        ?>                
    </div>               
    <div class="clear"></div>
</div> <!-- END div id="parent_Sect" -->
