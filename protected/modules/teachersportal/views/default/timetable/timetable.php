<?php Yii::app()->clientScript->registerCoreScript('jquery');?>

<div id="parent_Sect">
	<?php $this->renderPartial('leftside');?> 
    <div class="pageheader">
        <div class="col-lg-8">
        <h2><i class="fa fa-calendar-o"></i><?php echo Yii::t("app", 'Time Table');?><span><?php echo Yii::t("app", 'View your Time Table here');?> </span></h2>
        </div>
        <div class="col-lg-2">
        
                </div>
    
        <div class="breadcrumb-wrapper">
            <span class="label"><?php echo Yii::t("app", 'You are here:');?></span>
                <ol class="breadcrumb">
                <!--<li><a href="index.html">Home</a></li>-->
                
                <li class="active"><?php echo Yii::t("app", 'Time Table');?></li>
            </ol>
        </div>
    
        <div class="clearfix"></div>
    
    </div>
    <div class="contentpanel">
    
    <div class="panel-heading">
    <h3 class="panel-title"><?php echo Yii::t('app','View Time Table'); ?></h3>
    
   
</div>
<div class="clearfix"></div>
<div class="people-item">


<?php $this->renderPartial('/default/employee_tab');?>
	<div class="right_col"  id="req_res123">
    <!--contentArea starts Here--> 
    <?php
		$employee=Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
		$is_classteacher = Batches::model()->findAllByAttributes(array('employee_id'=>$employee->id));
		$stud_flag = 0;
		if($is_classteacher!=NULL){
			$stud_flag = 1;
		}
	  ?>
     <div id="parent_rightSect">        
        	<br />
                <div class="yellow_bx">
                    <?php /*?><div class="y_bx_head" style="font-size:14px;">
                       &nbsp;
                    </div><?php */?>
                    
                    	<h5 class="subtitle"><?php echo Yii::t('app','View My Timetable'); ?></h5>
                        <p><?php echo Yii::t('app','Here you can view the scheduled class timings.'); ?></p>
                    
                    <?php 
					if($stud_flag == 1){
					?>
                    
                    	<h5 class="subtitle"><?php echo Yii::t('app','View Day Timetable'); ?></h5>
                        <p><?php echo Yii::t('app','Here you can view the scheduled class timings in the day wise format.'); ?></p>
                    
                     <div class="yb_timetable">&nbsp;</div>    
                    <?php
					}
					?>
                    <div class="yb_teacher_timetable">&nbsp;</div>
                     
       			</div>
		</div>
	</div>
	 <div class="clear"></div>
</div>
</div>
</div>
