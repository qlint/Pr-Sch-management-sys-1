<style type="text/css">
.nothing-found{
	text-align:center;
	font-style:italic;
}
</style>
<div id="parent_Sect">
	<?php $this->renderPartial('/default/leftside');?> 
    <?php    
	/*$student=Students::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
    $guard = Guardians::model()->findByAttributes(array('id'=>$student->parent_id));
    $settings=UserSettings::model()->findByAttributes(array('user_id'=>1));*/
    ?>
    
    <div class="pageheader">
      <h2><i class="fa fa-list-alt"></i> <?php echo Yii::t('app', 'My Course');?> <span><?php echo Yii::t('app', 'View courses here');?></span></h2>
      <div class="breadcrumb-wrapper">
        <span class="label"><?php echo Yii::t('app', 'You are here:');?></span>
        <ol class="breadcrumb">
          <!--<li><a href="index.html">Home</a></li>-->
         <li class="active"><?php echo Yii::t('app', 'Course');?></li>
        </ol>
      </div>
    </div>
    
    <div class="contentpanel">
<div class="col-sm-9 col-lg-12">
<div class="panel panel-default">
<?php  $this->renderPartial('changebatch');?>
<div class="panel-body">
    <div id="parent_rightSect">
        <div class="parentright_innercon">
        	<?php $this->renderPartial('batch');?>
            <div class="edit_bttns" style="top:100px; right:25px">
                <ul>
                    <li>
                    <?php //echo CHtml::link('<span>'.Yii::t('studentportal','My Courses').'</span>', array('/studentportal/course'),array('class'=>'addbttn last'));?>
                    </li>
                </ul>
            </div>
            
 <?php $semester_enabled	= Configurations::model()->isSemesterEnabled(); ?>        	
            <!-- Subjects Grid -->
            <div class="list_contner">
                    <div class="clear"></div>                    
                    <br />
                    <div class="tablebx">  
                        <div class="pager" style="margin: 0 20px 10px 0;">
							<?php 
                              $this->widget('CLinkPager', array(
                              'currentPage'=>$pages->getCurrentPage(),
                              'itemCount'=>$item_count,
                              'pageSize'=>$page_size,
                              'maxButtonCount'=>5,
							  'prevPageLabel'=>'< Prev',
                              //'nextPageLabel'=>'My text >',
                              'header'=>'',
                            'htmlOptions'=>array('class'=>'pages'),
                            ));?>
                        </div> <!-- End div class="pagecon" --> 
                        <div class="clear"></div>
                          <div class="table-responsive">                                    
                        <table class="table table-bordered mb30" width="100%" border="0" cellspacing="0" cellpadding="0">
                        <thead>
                            <tr class="tablebx_topbg">
                                <th><?php echo Yii::t('app','Sl. No.');?></th>	
                                <?php  if(Configurations::model()->rollnoSettingsMode() != 2){ ?>
                                <th><?php echo Yii::t('app','Roll No.');?></th>
                               <?php } ?>
                                <?php if(FormFields::model()->isVisible("fullname", "Students", "forTeacherPortal"))
                                {?>
                              <th><?php echo Yii::t('app','Student Name');?></th>
                                <?php } ?>
                                
                                <?php if(FormFields::model()->isVisible('admission_no','Students','forTeacherPortal') and Configurations::model()->rollnoSettingsMode() != 1)
                                {?>
                                <th><?php echo Yii::t('app','Admission No');?></th>
                                <?php } ?>
                                
                                <?php if(FormFields::model()->isVisible('batch_id','Students','forTeacherPortal'))
                                {?>
                                <th><?php echo Yii::app()->getModule("students")->labelCourseBatch();?></th>
                                <?php } ?>
								<?php if($semester_enabled == 1){ ?> 
								<th><?php echo Yii::t('app','Semester');?></th> 
								<?php } ?>   
                                <?php if(FormFields::model()->isVisible('gender','Students','forTeacherPortal'))
                                {?>
                                <th><?php echo Yii::t('app','Gender');?></th>
                                <?php } ?>
                                
                            </tr>
                            </thead>
                            <?php 
                            if(isset($_REQUEST['page']))
                            {
                            	$i=($pages->pageSize*$_REQUEST['page'])-9;
                            }
                            else
                            {
                            	$i=1;
                            }
                            $cls="even";
                            ?>
                            
                            <?php
							if($list)
                    		{ 
								foreach($list as $list_1)
								{
									$batch_student=BatchStudents::model()->findByAttributes(array('student_id'=>$list_1->id, 'batch_id'=>$list_1->batch_id, 'status'=>1)); 	
									
								?>
                               
									<tr class=<?php echo $cls;?>>
									<td><?php echo $i; ?></td>
                                    <?php if(Configurations::model()->rollnoSettingsMode() != 2){?>
                                    <td><?php if($batch_student!=NULL and $batch_student->roll_no!=0){
													echo $batch_student->roll_no;
											  }
												else{
												echo '-';
											}?></td>
                                    <?php } ?>
									<?php if(FormFields::model()->isVisible("fullname", "Students", "forTeacherPortal"))
									{
										 $name= "";
											if(FormFields::model()->isVisible('first_name','Students','forTeacherPortal'))
											{
												$name.= ucfirst($list_1->first_name);
											}
											if(FormFields::model()->isVisible('middle_name','Students','forTeacherPortal'))
											{
												$name.= " ".ucfirst($list_1->middle_name);
											}
											if(FormFields::model()->isVisible('last_name','Students','forTeacherPortal'))
											{
												$name.= " ".ucfirst($list_1->last_name);
											}
										 ?>
									<td><?php echo CHtml::link($name,array('log','student_id'=>$list_1->id,'id'=>$_REQUEST['id']),array('class'=>'')); ?></td>
									<?php } ?>
									
									
									<?php if(FormFields::model()->isVisible('admission_no','Students','forTeacherPortal')  and Configurations::model()->rollnoSettingsMode() != 1)
									{?>
									<td><?php echo $list_1->admission_no ?></td>
									<?php } ?>
									
									<?php
									$batchstudents=BatchStudents::model()->findAllByAttributes(array('student_id'=>$list_1->id,'status'=>1, 'result_status'=>0));
								 if(count($batchstudents)>1){ ?>
									<td><?php echo CHtml::link('View Course Details', array('/teachersportal/course/courses', 'id'=>$list_1->id), array('class'=>'view_Exmintn_atg Exm_aTgColor_b'));?></td> 
								<?php }
								 else{ ?>
			
									<?php if(FormFields::model()->isVisible('batch_id','Students','forTeacherPortal'))
									{?>
									<?php 
									$batc = Batches::model()->findByAttributes(array('id'=>$list_1->batch_id)); 
									if($batc!=NULL)
									{
										$cours = Courses::model()->findByAttributes(array('id'=>$batc->course_id)); ?>
										<td><?php echo $cours->course_name.' / '.$batc->name; ?></td> 
									<?php 
									}
									else{
									?> 
										<td>-</td> 
									<?php 
									}
									?>
									<?php }
								 }?>
								 <?php if($semester_enabled == 1){
										$sem_enabled	= Configurations::model()->isSemesterEnabledForCourse($cours->id); ?> 
									<td>
										<?php 
										if($sem_enabled == 1 and $batc->semester_id!=NULL and count($batchstudents) == 1){
											$semester	= Semester::model()->findByAttributes(array('id'=>$batc->semester_id));
											echo ucfirst($semester->name);
										}
										else{
											echo '-';
										}
										?>
									</td>
									<?php } ?>
									
									
									<?php if(FormFields::model()->isVisible('gender','Students','forTeacherPortal'))
									{?>
									<td>
										<?php 
										if($list_1->gender=='M')
										{
											echo Yii::t('app', 'Male');
										}
										elseif($list_1->gender=='F')
										{
											echo Yii::t('app', 'Female');
										}
										?>
									</td>
									<?php } ?>
									
									</tr>
									<?php
									if($cls=="even")
									{
										$cls="odd" ;
									}
									else
									{
										$cls="even"; 
									}
									$i++;
								} 
							}
							else{
						?>
                        		<tr>
                                	<td colspan="5" class="nothing-found"><?php echo Yii::t('app','Nothing Found'); ?></td>
                                </tr>
                        <?php		
							}
						?>
                       
                        </table>
                         
                        </div>
                        <div class="pager" style="margin: 0 20px 10px 0;">
                        <?php                                          
                          $this->widget('CLinkPager', array(
                          'currentPage'=>$pages->getCurrentPage(),
                          'itemCount'=>$item_count,
                          'pageSize'=>$page_size,
                          'maxButtonCount'=>5,
						  'prevPageLabel'=>'< Prev',
                          //'nextPageLabel'=>'My text >',
                          'header'=>'',
                        'htmlOptions'=>array('class'=>'pages'),
                        ));?>
                        </div> <!-- END div class="pagecon" 2 -->
                        <div class="clear"></div>
                    </div> <!-- END div class="tablebx" -->                    
                </div> 
        </div>
        </div>
        </div>
        </div>
            <!-- END Subjects Grid -->
            
            
            
        </div> <!-- END div class="parentright_innercon" -->
    </div> <!-- END div id="parent_rightSect" -->
    <div class="clear"></div>
</div> <!-- END div id="parent_Sect" -->
<div class="clear"></div>

