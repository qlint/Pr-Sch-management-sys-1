<style>
.nobr br{
	display:none !important;
}
</style>
<script>
function checkVal(){
	
	var elective=$("#elective_id").val();
	var group=$("#elective_group_id").val();
	if(elective=="" || group==""){
		alert("<?php echo Yii::t('app', 'Please select Elective Group and Elective');?>");
		return false;
	}
	if(elective==0){
		alert("<?php echo Yii::t('app', 'Please select an Elective!');?>");
		return false;
	}
	
	if($('input[name="sid[]"]:checked').length==0){
		alert("<?php echo Yii::t('app', 'Please select atleast one student!');?>");
		return false;
	}
	
	confirm("<?php echo Yii::t('app', 'Are you sure you want to save this elective?')?>");
		
}
 <?php
Yii::app()->clientScript->registerScript(
   'myHideEffect',
   '$(".info").animate({opacity: 1.0}, 3000).fadeOut("slow");',
   CClientScript::POS_READY
);
?>
</script>
	<?php $this->renderPartial('/default/leftside');?> 
    <?php    
	/*$student=Students::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
    $guard = Guardians::model()->findByAttributes(array('id'=>$student->parent_id));
    $settings=UserSettings::model()->findByAttributes(array('user_id'=>1));*/
    ?>
   <?php $this->beginWidget('CActiveForm'); ?>  
    <div class="pageheader">
      <h2><i class="fa fa-list-alt"></i><?php echo Yii::t('app', 'My Course');?> <span><?php echo Yii::t('app', 'View courses here');?></span></h2>
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
          <?php $this->renderPartial('changebatch');?>
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
                                <!-- Subjects Grid -->
                                <div class="clear"></div>
                                        <div class="emp_cntntbx" style="padding-top:10px;">
                                            <div class="c_subbutCon" align="right" style="width:100%; height:40px; position:relative">
                                                <div class="edit_bttns" style="top:0px; right:-6px">
                                                    <ul>
                                                        <?php
                                                        if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_insert->settings_value!=0))
                                                        {
                                                        ?>
                                                         <li>
                                                         <?php 
                                                            echo CHtml::link('<span>'.Yii::t('app','Add Students').'</span>', array('/teachersportal/course/elective','id'=>$_REQUEST['id']),array('class'=>'addbttn last'));
                                                        }
                                                        ?>
                                                         </li>
                                                    </ul>
                                                    <div class="clear"></div>
                                                </div> <!-- END div class="edit_bttns" -->
                                        </div> <!-- END div class="c_subbutCon" -->
                                    <!-- END Subjects Grid -->
                                    <div>
                                    <?php
									if(isset($_REQUEST['id']))
                            {
                                
                                $criteria 	= 	new CDbCriteria;
                                $criteria->condition='batch_id=:x and status=:y';
                                $criteria->params=array(':x'=>$_REQUEST['id'],':y'=>'1');
                                $criteria->order	='`id` desc';
                                $total 		=	StudentElectives::model()->count($criteria);
                                $pages 		= 	new CPagination($total);
                                $pages->setPageSize(Yii::app()->params['listPerPage']);
                                $pages->applyLimit($criteria);  // the trick is here!
                                $posts 	= 	StudentElectives::model()->findAll($criteria);
                                $item_count =	$total;
                                $page_size 	=	Yii::app()->params['listPerPage'];
                                
								// $posts=StudentElectives::model()->findAll("batch_id=:x and status=:y", array(':x'=>$_REQUEST['id'],':y'=>'1'));
                                if($posts!=NULL)
                                {
                                ?>
                                    <table class="table table-bordered mb30" width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr class="listbxtop_hdng">
                                            <td class="listbx_subhdng"><?php echo Yii::t('app','Sl no.');?></td>
                                            <?php if(Configurations::model()->rollnoSettingsMode() != 2)
											{?>
                                			  <td class="listbx_subhdng"><?php echo Yii::t('app','Roll No');?></td><?php } ?>
                                            <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile"))
                                            { ?>
                                            <td class="listbx_subhdng"><?php echo Yii::t('app','Student Name');?></td><?php } ?>
											 <?php if(Configurations::model()->rollnoSettingsMode() != 1){?>   
                                            <td class="listbx_subhdng"><?php echo Yii::t('app','Admission Number');?></td>
											<?php } ?>
                                            <td class="listbx_subhdng"><?php echo Yii::t('app','Elective Group');?></td>
                                            <td class="listbx_subhdng"><?php echo Yii::t('app','Elective');?></td>
                                        </tr>
                                        <?php
                                        $i=0;
                                        $elective_flag = 0;
                                        foreach($posts as $posts_1)
                                        {
											                                       
                                            $student = Students::model()->findByAttributes(array('id'=>$posts_1->student_id,'is_deleted'=>'0','is_active'=>'1'));
											if($student)
                                            {
                                                $elective_flag =1;
                                                $elective = Electives::model()->findByAttributes(array('id'=>$posts_1->elective_id));
                                                $group = ElectiveGroups::model()->findByAttributes(array('id'=>$elective ->elective_group_id));
                                                $i++;
                                                echo '<tr>';
												if($_REQUEST['page']){
													$num= $i + ($_REQUEST['page']-1)*10;
													echo '<td>'.$num.'</td>';	
												}else{
													echo '<td>'.$i.'</td>';
												}
												
											$batch_student=BatchStudents::model()->findByAttributes(array('student_id'=>$student->id, 'batch_id'=>$student->batch_id, 'status'=>1));
											  if(Configurations::model()->rollnoSettingsMode() != 2){?>
                                  				<td><?php if($batch_student!=NULL and $batch_student->roll_no!=0){
								  				echo $batch_student->roll_no;
								  				}
												else{
												echo '-';
											}?>
                                 			 </td> 
                                 			 <?php } 
                                               if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile"))
                                            {
                                                   $name='';
                                                   $name=  $student->studentFullName('forStudentProfile');
                                                echo '<td>'.CHtml::link($name, array('/teachersportal/course/students', 'student_id'=>$student->id)).'</td>';
                                            }
											 if(Configurations::model()->rollnoSettingsMode() != 1){  
													echo '<td>'.$student->admission_no.'</td>';
											 }
												?>
                                                <td><?php echo $group->name;?></td>
                                                <td><?php echo $elective->name;?></td>
                                            <?php 
                                            }
                                            else
                                            {
                                                continue;
                                            }
                                        }
                                        if($elective_flag==0)
                                        {
                                        ?>
                                        <tr>
                                            <td colspan="6" align="center">
                                                <?php echo Yii::t('app','No students have choosen the electives!')?>
                                            </td>
                                        </tr>
                                        <?php
                                        }
                                        ?>
                                    </table>
                                <?php    	
								}
								 else
                                {
                                    echo '<br><div class="notifications nt_red" style="padding-top:10px">'.'<i>'.Yii::t('app','No elective has been chosen for the').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").'</i></div>'; 
                                                    
                                }
							}
									?>
                                    </div>
                                </div> 
                            </div>
                        </div>
                    </div>
                </div> <!-- END div class="parentright_innercon" -->
        <div class="clear"></div>
    </div> <!-- END div id="parent_Sect" -->
</div>

<?php $this->endWidget(); ?>
<div class="clear"></div>

