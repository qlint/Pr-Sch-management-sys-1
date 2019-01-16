<style type="text/css">
.nothing-found{
	text-align:center;
	font-style:italic;
}
</style>
	<?php $this->renderPartial('/default/leftside');?> 
   
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
 <?php $semester_enabled	= Configurations::model()->isSemesterEnabled(); ?>   
    
    <div class="clearfix"></div>
    <div class="contentpanel">
   
		<!--<div class="col-sm-9 col-lg-12">-->
        <div>
			<div class="panel panel-default">
			<div class="panel-body">
    <div id="parent_rightSect">
    	<div class="pdtab_Con">
        <div class="table-responsive">
         <?php
            $accademic_year = AcademicYears::model()->findAllByAttributes(array('is_deleted'=> 0));
            $acc_arr	= array();
            foreach($accademic_year as $value){
           		 $acc_arr[$value->id]	= ucfirst($value->name);
            }
            if(isset($_REQUEST['acc_id']) and $_REQUEST['acc_id'] != NULL){
           	 	$accademic	= AcademicYears::model()->findByPk(array($_REQUEST['acc_id']));
            }
            else{
            	$accademic	= AcademicYears::model()->findByAttributes(array('is_deleted'=> 0,'status'=>1));
            }
            
            echo Yii::t('app','Viewing Courses of Academic Year');
            if(count($accademic_year) > 1){
            echo CHtml::dropDownList('acc_id','',$acc_arr,array('encode'=>false,'prompt'=>Yii::t("app",'Select Academic Year'),'style'=>'width:190px;','onchange'=>'getday()','class'=>'form-control','id'=>'acc_id','options'=>array($accademic->id=>array('selected'=>true))));
            }
            
			$colspan	= 5;
            ?>
    <table width="100%" cellspacing="0" cellpadding="0" border="0" class="table table-bordered mb30">
        <thead>
            <!--class="cbtablebx_topbg"  class="sub_act"-->
            <tr>
                <th align="center"><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' '.Yii::t('app', 'Name');?></th>
                <th align="center"><?php echo Yii::t('app', 'Course');?></th>
				 <?php if($semester_enabled==1){ 
				 		$colspan	= 6;
				 ?>
                	<th align="center"><?php echo Yii::t('app', 'Semester');?></th>
				 <?php } ?>
                <th align="center"><?php echo Yii::t('app', 'Class Teacher');?></th>
                <th align="center"><?php echo Yii::t('app', 'Start Date');?></th>
                <th align="center"><?php echo Yii::t('app', 'End Date');?></th>
                
            </tr>
            </thead>
            <tbody>                    
           <?php 
		   if($batches_id){
				$courses=array();
				foreach($batches_id as $batch_id)
				{
					$batch				=	Batches::model()->findByAttributes(array('id'=>$batch_id));
					//$courses[] = Courses::model()->findByAttributes(array('id'=>$batch->course_id));            
					$course_coordinator = 	Employees::model()->findByAttributes(array('id'=>$batch->employee_id));
					$course 			= 	Courses::model()->findByAttributes(array('id'=>$batch->course_id));
					$semester			=	Semester::model()->findByAttributes(array('id'=>$batch->semester_id));  ?>
						<tr id="batchrow1" >
							<td style=" padding-left:10px; font-weight:bold;">
								<?php echo CHtml::link(ucfirst($batch->name),array('subjects','id'=>$batch->id),array('class'=>'profile_active'));?>
							</td>
							<td><?php echo ucfirst($course->course_name);?></td>
							<?php if($semester_enabled==1){ ?>
								<td><?php 
									$sem_enabled= Configurations::model()->isSemesterEnabledForCourse($course->id);
									if($sem_enabled==1 and $semester!=NULL)
									{
										echo ($semester->name)?ucfirst($semester->name):'-';
									}else{
										echo "-";
									}?>
								</td>
							<?php }?>
							<td><?php echo Employees::model()->getTeachername($course_coordinator->id);?></td>
							<?php 
							$settings=UserSettings::model()->findByAttributes(array('user_id'=>1));
							if($settings!=NULL)
							{	
								$batch_start_date = date($settings->displaydate,strtotime($batch->start_date));
								$batch_end_date = date($settings->displaydate,strtotime($batch->end_date));
								
							}?>
							<td><?php echo $batch_start_date; ?></td>
							<td><?php echo $batch_end_date; ?></td>                        
						</tr>
				   <?php
				}
		   }
		   else{
		  ?>
          			<tr>
                    	<td colspan="<?php echo $colspan; ?>" class="nothing-found"><?php echo Yii::t('app', 'Nothing Found'); ?></td>
                    </tr>
          <?php 	
		   }
		?>
                                
        </tbody>
    </table>

    </div>
    </div>
</div><!-- table-responsive -->

</div><!-- panel-body -->
</div><!-- panel -->

</div>
    
      
      
      
      
    </div><!-- contentpanel -->
<script>
$('#acc_id').change(function(ev){
var acc_id	= $(this).val();
if(acc_id != ''){
window.location= 'index.php?r=teachersportal/course&acc_id='+acc_id;
}
else{
window.location= 'index.php?r=teachersportal/course';
}
});
</script>
   
   
    
    
    
   