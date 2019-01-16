<style>
.table-responsive {

    overflow-x: scroll;
    overflow-y: hidden;
    width: 100%;
}
</style>
        <?php $this->renderPartial('leftside');?>
     <div class="pageheader">
        <div class="col-lg-8">
        <h2><i class="fa fa-dedent"></i><?php echo Yii::t('app','Timetable'); ?><span><?php echo Yii::t('app','View Timetable'); ?> </span></h2>
        </div>
        <div class="col-lg-2">
         </div>
        <div class="breadcrumb-wrapper">
            <span class="label"><?php echo Yii::t('app','You are here:'); ?></span>
                <ol class="breadcrumb">
                <!--<li><a href="index.html">Home</a></li>-->

                <li class="active"><?php echo Yii::t('app','Timetable'); ?></li>
            </ol>
        </div>
        <div class="clearfix"></div>
    </div>
    
<?php
$student	=   Students::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
$batches    = 	BatchStudents::model()->studentBatch($student->id); 
if($_REQUEST['bid'] == NULL){
	if($batches != NULL){
		if(Configurations::model()->timetableFormat($batches[0]['id']) == 1){	
			$this->renderPartial('application.modules.studentportal.views.default.timetable_fixed'); 
		}
		else{ 
			$this->renderPartial('application.modules.studentportal.views.default.timetable_flexible', array('batches'=>$batches, 'student'=>$student)); 
		}
	}
	else{
	?>	
		<div class="contentpanel">
			<div class="people-item">
				<div class="nothing-found"><?php echo Yii::t('app', 'No Active Batches Found'); ?></div>
			</div>
		</div>    
	<?php    
	}
}
else{
	if(Configurations::model()->timetableFormat($_REQUEST['bid']) == 1){	
			$this->renderPartial('application.modules.studentportal.views.default.timetable_fixed'); 
		}
		else{ 
			$this->renderPartial('application.modules.studentportal.views.default.timetable_flexible', array('batches'=>$batches, 'student'=>$student)); 
		}
}
?>

       