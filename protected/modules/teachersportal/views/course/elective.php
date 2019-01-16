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
			<div class="panel-body">
				<div class="row">
					<div class="col-sm-4 col-4-reqst">
						<div class="form-group">
							<?php echo Yii::t('app','Select Group'); ?><br />
							<?php 
                                                //$data1 = CHtml::listData(ElectiveGroups::model()->findAll('batch_id=:cid and is_deleted=:x',array(':cid'=>$_REQUEST['id'],':x'=>0)),'id','name');
                                                $data1 = CHtml::listData(ElectiveGroups::model()->findAll(array('order'=>'name ASC','condition'=>'batch_id=:cid and is_deleted=:x','params'=>array(':cid'=>$_REQUEST['id'],':x'=>0))),'id','name');
                                                
                                                echo CHtml::dropDownList('elective_group_id','',$data1,array('prompt'=>Yii::t('app','Select'),'id'=>'elective_group_id',
                                                'ajax' => array(
                                                'type'=>'POST',
                                                'url'=>CController::createUrl('/courses/electives/electivename'),
                                                'update'=>'#elective_id',
                                                'data'=>'js:{elective_group_id:$(this).val(), "'.Yii::app()->request->csrfTokenName.'":"'.Yii::app()->request->csrfToken.'"}',),'class'=>'form-control'));
                                                // echo CHtml::dropDownList('elective_id','',$data1,array('prompt'=>'Select','id'=>'elective_id1')); ?>
                                     
						</div>
					</div>
					<div class="col-sm-4 col-4-reqst">
						<div class="form-group">
							<?php echo Yii::t('app','Select Subject'); ?><br />
							<?php 
                                                
                                                echo CHtml::dropDownList('elective_id','',$data,array('prompt'=>Yii::t('app', 'Select'),'id'=>'elective_id','class'=>'form-control'));
                                                
                                                ?>
						</div>
					</div>
					<div class="col-sm-4 col-4-reqst">
					<br />
						<?php 
												
												if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_insert->settings_value!=0))
												{
													/*	echo CHtml::submitButton(Yii::t('Batch','Save'),
																array('name'=>'elective','id'=>'1','class'=>'add','confirm'=>Yii::t('Batch','Are you sure you want to save?'),'class'=>'formbut'));*/
														echo CHtml::submitButton(Yii::t('app','Save'),
																array('name'=>'elective','id'=>'1','class'=>'add','onclick'=>'return(checkVal())','class'=>'btn btn-success'));
												}
												?>
					</div>
				</div>
			</div>
            <?php if(Yii::app()->user->hasFlash('success')):?>
                <div class="info" style="color:#C30; width:575px; height:30px">
                    <?php echo Yii::app()->user->getFlash('success'); ?>
                </div>
                <?php endif; ?>
                <?php if(Yii::app()->user->hasFlash('error')):?>
                <div class="info" style="color:#C30; width:575px; height:30px">
                    <?php echo Yii::app()->user->getFlash('error'); ?>
                </div>
                <?php endif; ?>
                <?php if(Yii::app()->user->hasFlash('warning')):?>
                <div class="errorSummary" style="width:auto; padding:10px 0px 10px 45px">
                	<div><?php echo Yii::app()->user->getFlash('warning'); ?></div>
                </div><br />
                <?php endif; ?>
            <div class="list_contner" style="padding-top:10px;">
            
                    <div class="clear"></div>
                    <?php 
						if(isset($_REQUEST['id']))
						{
							
							$elec_id = array();
							$electives = StudentElectives::model()->findAllByAttributes(array('batch_id'=>$_REQUEST['id']));
							foreach($electives as $elective){
								$elec_id[] = $elective->student_id;
							}
							$criteria = new CDbCriteria;
							$criteria->condition = 'is_deleted=:is_deleted AND is_active=:is_active';
							$criteria->params[':is_deleted'] = 0;
							$criteria->params[':is_active'] = 1;
							$batch_students = BatchStudents::model()->findAllByAttributes(array('batch_id'=>$_REQUEST['id'],'result_status'=>0));
							if($batch_students)
							{
								$count = count($batch_students);
								$criteria->condition = $criteria->condition.' AND (';
								$i = 1;
								foreach($batch_students as $batch_student)
								{
									
									$criteria->condition = $criteria->condition.' id=:student'.$i;
									$criteria->params[':student'.$i] = $batch_student->student_id;
									if($i != $count)
									{
										$criteria->condition = $criteria->condition.' OR ';
									}
									$i++;
									
								}
								$criteria->condition = $criteria->condition.')';
							}
							else
							{
								$criteria->condition = $criteria->condition.' AND batch_id=:batch_id';
								$criteria->params[':batch_id'] = $_REQUEST['id'];
							}
							
							
							$posts=Students::model()->findAll($criteria);
							//var_dump($criteria);exit;
							if($posts!=NULL)
							{
							}
		?>
           <!-- <div class="table-responsive">
                <table class="table table-bordered mb30">
                    <thead>
                        <tr class="pdtab-h">
                        	<th>checkbox</th>
                           <?php /*?> <?php if(FormFields::model()->isVisible("fullname", "Students", "forTeacherPortal"))
                                {?>
                            <th>Student Name</th>
                            <?php } ?><?php */?>
                            <th>Admintion_no</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="pdtab-h">
                        	<td>checkbox</td>
                            <td>Student Name</td>
                            <td>Admintion_no</td>
                        </tr>
                   </tbody>                    
                </table>
            </div>     -->        
       <div class="tablebx">                                                        
    	<div class="pdtab_Con">
        <div class="table-responsive">                                  
                        <table width="95%" border="0" cellspacing="0" cellpadding="0" class="table table-hover mb30">
							<thead>
                            <tr class="tablebx_topbg">
                                <th>&nbsp;</th>
                                <?php if(FormFields::model()->isVisible("fullname", "Students", "forTeacherPortal"))
                                {?>
                                <th><?php echo Yii::t('app','Student Name');?></th>	
                                <?php } ?>
                                <?php if(FormFields::model()->isVisible('admission_no','Students','forTeacherPortal'))
                                {?>
                                <th><?php echo Yii::t('app','Admission Number');?></th>
                                <?php } ?>
                                <!--<td style="border-right:none;">Task</td>-->
                            </tr>
							</thead>
							<tbody>
                           <tr>
                                <td>
                                <?php $posts1=CHtml::listData($posts, 'id', 'T_fullname');?>
                                <?php
                                echo CHtml::checkBoxList('sid','',$posts1, array('id'=>'1','template' => '{input}{label}</td></tr><tr><td width="10%" class="nobr">','checkAll' => Yii::t('app', 'All'))); ?>
                                </td>
                               
                                
                            </tr>
                           </tbody>
                        </table>
                        </div>
                        </div>
                         <!-- END div class="pagecon" 2 -->
                        <div class="clear"></div>
                    </div> <!-- END div class="tablebx" -->
                    <?php 
					}
                    else
                    {
                    	echo '<div class="listhdg" align="center">'.Yii::t('app','No Active Students In This').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").'</div>';	
                    }?>
                </div>
                <br />

            <!-- END Subjects Grid -->
            
            
            
        
        </div> 
        </div>
        </div>
        </div>
        </div> <!-- END div class="parentright_innercon" -->
    
    <div class="clear"></div>
</div> <!-- END div id="parent_Sect" -->

<?php $this->endWidget(); ?>
<div class="clear"></div>

