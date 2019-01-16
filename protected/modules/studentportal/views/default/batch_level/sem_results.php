<?php $this->renderPartial('leftside');?> 

    <?php
    $student=Students::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
	$batchstudents=BatchStudents::model()->findAllByAttributes(array('student_id'=>$student->id,'status'=>1, 'result_status'=>0));
    $exam = ExamScores::model()->findAllByAttributes(array('student_id'=>$student->id));
	//$electives = ElectiveScores::model()->findAll("student_id=:x", array(':x'=>$student->id));

	$student_visible_fields   = FormFields::model()->getVisibleFields('Students', 'forStudentPortal');
    ?>
    
    
   <div class="pageheader">
        <div class="col-lg-8">
        <h2><i class="fa fa-pencil"></i><?php echo Yii::t('app','Exams'); ?><span><?php echo Yii::t('app','View your Exam here'); ?></span></h2>
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
     	<!--<div class="col-sm-9 col-lg-12">-->
        <div>
        	<div class="people-item">
                          <div class="media">
                            <a href="#" class="pull-left">
                                <?php
                     if($student->photo_file_name!=NULL)
                     { 
					 	$path = Students::model()->getProfileImagePath($student->id);
                        echo '<img  src="'.$path.'" alt="'.$student->photo_file_name.'" width="100" height="103" />';
                    }
                    elseif($student->gender=='M')
                    {
                        echo '<img  src="images/portal/prof-img_male.png" alt='.$student->first_name.' width="100" height="103" />'; 
                    }
                    elseif($student->gender=='F')
                    {
                        echo '<img  src="images/portal/prof-img_female.png" alt='.$student->first_name.' width="100" height="103" />';
                    }
                    ?>                            
                            </a>
                            <div class="media-body">
					          <?php
					          if(FormFields::model()->isVisible("fullname", "Students", "forStudentPortal")){
					          ?>
					          <h4 class="person-name"><?php echo $student->studentFullName("forStudentPortal");?></h4>
					          <?php
					          }
					          ?>
					          <div class="text-muted"><strong><?php echo Yii::t('app','Admission No').' :';?></strong> <?php echo $student->admission_no; ?></div>
							  <?php if($batch_student!=NULL and $batch_student->roll_no!=0){ ?>
										<div class="text-muted"><strong><?php echo Yii::t('app','Roll No').' :';?></strong> <?php echo $batch_student->roll_no; ?></div>
							  <?php } ?>
							   <?php if(count($batchstudents)>1){ 
										echo CHtml::link('View Course Details', array('/studentportal/default/course'));
										}
										else{?>	
											  <?php if(in_array('batch_id', $student_visible_fields)){ ?>      
											  <div class="text-muted"><strong><?php echo Yii::t('app','Course').' :';?></strong>
												<?php 
												  $batch = Batches::model()->findByPk($student->batch_id);
												  $semester	= Semester::model()->findByAttributes(array('id'=>$batch->semester_id));
												  echo $batch->course123->course_name;
												  $batch_student=BatchStudents::model()->findByAttributes(array('student_id'=>$student->id, 'batch_id'=>$student->batch_id, 'status'=>1));
												?>
											  </div>          
											  <div class="text-muted"> <strong><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' :';?></strong> <?php echo $batch->name;?></div>
											  <?php } ?>
											   <?php  if($batch->semester_id!=NULL){ ?>
														<div class="text-muted"> <strong><?php echo Yii::t('app','Semester').' :';?></strong> <?php echo $semester->name;?></div>
												<?php } ?>
									<?php } ?>	
					          
                             
					          
					        </div>
                          </div>
                        </div>
                         <!-- END div class="profile_top" -->
                         
                         <div class="panel-heading">
                          <h3 class="panel-title"><?php echo Yii::t('app','Semester Assessment Report');?></h3>
                         </div>
                         <?php
							  $enabled	=	Configurations::model()->isSemesterEnabled();
							  if($enabled==1)
								  {
						?>
                        <div class="people-item">
							<div class="row">
                            	<div class="col-md-4 col-4-reqst">
                                	<?php echo Yii::t('app','Select Semester');?>
                                    <?php 
										$sem_id='';
										if(isset($_GET['sem']))
											$sem_id=$_GET['sem'];
										$semester_data	=array();
										if(isset($student->id) and $student->id!=NULL){
											$stu_batchs=BatchStudents::model()->findAllByAttributes(array("student_id"=>$student->id)); 
											
											$datas=array();
											foreach($stu_batchs as $stu_batch)
											{
												
												$b_id	=	$stu_batch->batch_id;
												//echo  $b_id;exit;
												$batch	=	Batches::model()->findByPk($b_id);
												//var_dump($batch);exit;
												$course =	Courses::model()->findAllByAttributes(array("id"=>$batch->course_id));
												if($course[0][semester_enabled] == 1)
												{
													$sem	=	Semester::model()->findByPk($batch->semester_id);
													
													if(isset($sem) and $sem!=NULL){
													if(!in_array($sem->id,$data))
														$data[]=$sem->id;
													}
												}
											
											}
											$criteria=new CDbCriteria;
											$criteria->addInCondition('id',$data);

											$semester_data	=	CHtml::listData(Semester::model()->findAll($criteria),'id','name');
											
										}
											
										echo CHtml::dropDownList('semester_id','',
												$semester_data,												
												array(
												'prompt'=>Yii::t('app','Select Semester'),'class'=>'form-control',
												'encode'=>false,
												'options' => array($sem_id=>array('selected'=>true)),
													'ajax' => array(
													'type'=>'GET', //request type
													'data'=>array('semid'=>'js:this.value'),
													'url'=>CController::createUrl('selectSemBatch'), //url to call. 
													//'update'=>'#batch_id', //selector to update
													'success' => 'function(data){
														var json = $.parseJSON(data);
														$("#batch_id").html(json.batchvalue);
														if(json.status){
															window.location= "index.php?r=studentportal/default/semResult";
														}
													}',
												))
												); ?>
                                </div>
                                <div class="col-md-4 col-4-reqst">
                                	<?php echo Yii::t('app','Batch');?>
                                     <div>
										<?php 
										$batch_id='';
										
										if(isset($_GET['bid']))
										{
											$batch_id=$_GET['bid'];
											$sem_id=$_GET['sem'];
										}
											
										$batch_data	=array();
										if(isset($_GET['sem']) and $_GET['sem']!=NULL){
											$criteria = new CDbCriteria;
											$criteria->select = 't.id, t.name';
											$criteria->join = ' LEFT JOIN `batch_students` AS `b` ON t.id = b.batch_id';
											$criteria->condition = 't.semester_id = :semester_id AND b.student_id=:student_id';
											$criteria->params = array(":semester_id" => $sem_id,":student_id"=>$student->id);
											$batch_data    =   CHtml::listData(Batches::model()->findAll($criteria),'id','name');
										}
										echo CHtml::dropDownList('batch_id','',$batch_data,	array('encode'=>false,'empty' => Yii::t('app','Select Batch'),
                                    'onchange'=>'getresult()','class'=>'form-control','options' => array($batch_id=>array('selected'=>true))));
                           ?>
									
                                    </div>
                                    
                                </div>
                            </div>
                        
                            
                            <?php 
							if(isset($_REQUEST['bid']) and $_REQUEST['bid']!=NULL)
                			{
							$bid	= $_REQUEST['bid'];
							$sem	= $_REQUEST['sem'];
							$details=	Students::model()->findByAttributes(array('id'=>$student->id,'is_deleted'=>0,'is_active'=>1));
							$batch	=	Batches::model()->findByAttributes(array('id'=>$bid,'is_deleted'=>0));
							$course	=	Courses::model()->findByAttributes(array('id'=>$batch->course_id));
						    $exam_format	 = ExamFormat::model()->getExamformat($bid); // 1=>normal, 2=>cbsc
							?>
                         <div class="table-responsive">
                           <h3><?php echo Yii::t('app','Semester Assessment Report');?></h3>
                          <table class="table table-hover mb30">
                            <tr>
                                <th><?php echo Yii::t('app','Exam Group Name');?></th>                        
                                <th><?php echo Yii::t('app','Action');?></th>
                            </tr>
                           <?php
						   if($exam_format == 1){
                           		$examgroups = ExamGroups::model()->findAll('batch_id=:x',array(':x'=>$batch->id));
						   }
						   else{
							   $examgroups = CbscExamGroup17::model()->findAll('batch_id=:x',array(':x'=>$batch->id));
						   }
						   if($examgroups!=NULL)
						   {
							   $i = 1;
							   foreach($examgroups as $examgroup) 
								{
									echo "<tr>";
									echo "<td>".ucfirst($examgroup->name)."</td>";
									echo "<td>".CHtml::link(Yii::t('app','View Result'),array('default/semexamList', 'id'=>$examgroup->id,'bid'=>$batch_id,'sem'=>$sem))."</td>";
									echo "</tr>";
								}
						   }
						   else
						   {
                          	 echo '<tr><td align="center" colspan="4"><i>'.Yii::t('app','No Exam Goups').'</i></td></tr>';
						   }
						   ?>
                      </table>
            </div> 
            
             <?php
				}
			?>
            <!-- END div class="profile_details" -->
        </div> <!-- END div class="parentright_innercon" -->
    </div> <!-- END div id="parent_rightSect" -->
    <?php
	}
	?>
    <div class="clear"></div>
</div> <!-- END div id="parent_Sect" -->
<script>
function getresult() // Function to get the dependent dropdown after selecting mode
{
	var batch_id = document.getElementById('batch_id').value;
	var semester_id = document.getElementById('semester_id').value;
	window.location= 'index.php?r=studentportal/default/semResult&sem='+semester_id+'&bid='+batch_id;
}
</script>