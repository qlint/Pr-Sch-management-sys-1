<?php
$batch=Batches::model()->findByAttributes(array('id'=>$_REQUEST['id'])); 
$this->breadcrumbs=array(
	Yii::t('app','Courses')=>array('/courses'),
	html_entity_decode($batch->name)=>array('/courses/batches/batchstudents','id'=>$_REQUEST['id']),
	Yii::t('app','Settings'),
);
?>
<div style="background:#FFF;">
<table width="100%" cellspacing="0" cellpadding="0" border="0">
  <tbody><tr>
   
    <td valign="top">
<div style="padding:20px;">
    <!--<div class="searchbx_area">
    <div class="searchbx_cntnt">
    	<ul>
        <li><a href="#"><img src="images/search_icon.png" width="46" height="43" /></a></li>
        <li><input class="textfieldcntnt"  name="" type="text" /></li>
        </ul>
    </div>
    
    </div>-->
    
    
        
    <!--<div class="edit_bttns">
    <ul>
    <li>
    <a href="#" class=" edit last">Edit</a>    </li>
    </ul>
    </div>-->
    
    
    <div class="clear"></div>
    <div class="emp_right_contner">
    <div class="emp_tabwrapper">
    <?php $this->renderPartial('/batches/tab');?>
    
    <div class="clear"></div>
    <div class="emp_cntntbx" style="padding-top:10px;">
    
    <div class="setbx_con">
    	<div class="setbx" style="width:100%">
    	<div class="setbx_top" style="width:100%">
    	<h1><?php echo Yii::t('app','General Settings');?></h1>
    	</div>
    	<div class="setbx_bot" >
    		<ul>
    			<?php /*?><li><a class="icon1" href="#">Add Batch Admins<span>Admins &amp; Class Teachers</span></a></li>
    			<li><a class="icon2" href="#">Add New Event<span>Admins &amp; Class Teachers</span></a></li><?php */?>
    			<li>
                <div class="set_icon"><i class="fa fa-external-link"></i></div>
                <?php echo CHtml::link(Yii::t('app','Promote').' '. Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").'<span>'.Yii::t('app','Promoting').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").'</span>', array('batches/promote','id'=>$_REQUEST['id']),
												array('id'=>'add_exam-groups','class'=>'')) ?></li>
                <?php /*?><li><a class="icon4" href="#">Copy Batch Settings<span>Admins &amp; Class Teachers</span></a></li><?php */?>
    		</ul>
    	</div>
    	<div class="clear"></div>
    	</div>
    <div class="clear"></div>
    	<div class="setbx" style="width:100%">
    	<div class="setbx_top" style="width:100%">
    	<h1><?php echo Yii::t('app','Subject Settings');?></h1>
    	</div>
    	<div class="setbx_bot">
   			<ul>
    			<?php /*?><li><?php echo CHtml::link(Yii::t('app','Add a Default Subject').'<span>'.Yii::t('app','Admins &amp; Class Teachers').'</span>', array('/courses/defaultsubjects','id'=>$_REQUEST['id']),array('id'=>'add_exam-groups','class'=>'icon5')) ?></li><?php */?>
    			<li>
				<div class="set_icon"><i class="fa fa-book"></i></div>
				<?php echo CHtml::link(Yii::t('app','Add Subject to').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").'<span>'.Yii::t('app','Add Subjects to').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").'</span>', array('/courses/subject','id'=>$_REQUEST['id']),array('id'=>'add_exam-groups','class'=>'')) ?></li>
    			<?php /*?><li><a class="icon7" href="#">Associate Subject to Employee<span>Admins &amp; Class Teachers</span></a></li><?php */?>
    		</ul>
    	</div>
    	<div class="clear"></div>
    	</div>
        <div class="clear"></div>
        <div class="setbx" style="width:100%">
    	<div class="setbx_top" style="width:100%">
    	<h1><?php echo Yii::t('app','Assessments Settings');?></h1>
    	</div>
    	<div class="setbx_bot">
    		<ul>
    			<li>
                <div class="set_icon"><i class="fa fa-edit"></i></div>
                <?php echo CHtml::link(Yii::t('app','New Examination').'<span>'.Yii::t('app','New Examination').'</span>', array('/examination/exam','id'=>$_REQUEST['id']),
									array('id'=>'add_exam-groups','class'=>'')) ?>                
                </li>
          	<?php if(ExamFormat::model()->getExamformat($_REQUEST['id'])== 1){ ?>
               
    			<li>
				<div class="set_icon"><i class="fa fa-line-chart"></i></div>
				<?php echo CHtml::link(Yii::t('app','New Grading Level').'<span>'.Yii::t('app','New Grading Levels').'</span>', 
											array('/examination/gradingLevels','id'=>$_REQUEST['id']),array('class'=>''));?></li>
    			<li>
				<div class="set_icon"><i class="fa fa-bar-chart-o"></i></div>
				<?php echo CHtml::link(Yii::t('app','Set Default Grading Levels').'<span>'.Yii::t('app','Set Default Grading Levels').'</span>',
				 							array('/examination/gradingLevels/default','id'=>$_REQUEST['id']),array('class'=>'','confirm'=>Yii::t('app','Are You Sure? All custom settings will be deleted.')));?></li>
           <?php } ?>
               <?php /*?> <li>
				<div class="set_icon"><i class="fa fa-paste"></i></div>
				<?php echo CHtml::link(Yii::t('app','Manage Exam Score').'<span>'.Yii::t('app','Manage Exam Scores').'</span>', 
											array('/courses/exam','id'=>$_REQUEST['id']),array('id'=>'add_exam-groups','class'=>'')) ?></li><?php */?>
               <?php /*?> <li><a class="icon12" href="#">Generate Report Cards<span>Admins &amp; Class Teachers</span></a></li><?php */?>
    		</ul>
    	</div>
    	<div class="clear"></div>
    	</div>
        <div class="clear"></div>
        <div class="setbx" style="width:100%">
    	<div class="setbx_top" style="width:100%">
    	<h1><?php echo Yii::t('app','Time Table or Attendance Settings');?></h1>
    	</div>
    	<div class="setbx_bot">
    		<ul>
    			<li>
                
                <div class="set_icon"><i class="fa fa-gears"></i></div>
                <?php echo CHtml::link(Yii::t('app','Set Week Days').'<span>'.Yii::t('app','Set Week Days').'</span>', 
									array('/courses/weekdays','id'=>$_REQUEST['id']),array('class'=>''));?>
                </li>
    			<li>
                <div class="set_icon"><i class="fa fa-clock-o"></i></div>
                <?php echo CHtml::link(Yii::t('app','Set Class Timings').'<span>'.Yii::t('app','ClassTimings and TimeTable').'</span>', 
									array('/courses/classTiming','id'=>$_REQUEST['id']),array('class'=>''));?>
    			<li>
                <div class="set_icon"><i class="fa fa-calendar"></i></div>
                <?php echo CHtml::link(Yii::t('app','View Timetable').'<span>'.Yii::t('app','View/Publish Timetable').'</span>', array('/courses/weekdays/timetable','id'=>$_REQUEST['id']),array('class'=>''));?>
                </li>
                <li>
                <div class="set_icon"><i class="fa fa-bars"></i></div>
				<?php echo CHtml::link(Yii::t('app','Attendance Register').'<span>'.Yii::t('app','Mark Attendance').'</span>', array('/courses/studentAttentance','id'=>$_REQUEST['id']),array('class'=>''));?>
                </li>
               <?php /*?> <li>
                <?php //echo CHtml::link(Yii::t('app','Attendance Report').'<span>'.Yii::t('app','Mark Attendance').'</span>', array('/courses/studentAttentance','id'=>$_REQUEST['id']),array('class'=>'icon17'));?>
                </li>
                <li>
                <?php //echo CHtml::link(Yii::t('app','Mark Attendance').'<span>'.Yii::t('app','Mark Attendance').'</span>', array('/courses/studentAttentance','id'=>$_REQUEST['id']),array('class'=>'icon18'));?>
                </li><?php */?>
    		</ul>
    	</div>
    	<div class="clear"></div>
    	</div>
    <div class="clear"></div>
    </div>
    </div>
    </div>
    
    </div>
    </div>
</td>
</tr>
</tbody
></table>
</div>
<script>
//CREATE EXAM

    $('#add_exam-groups').bind('click', function() {
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->request->baseUrl;?>/index.php?r=courses/exam/returnForm",
            data:{"batch_id":<?php echo $_GET['id'];?>,"YII_CSRF_TOKEN":"<?php echo Yii::app()->request->csrfToken;?>"},
                beforeSend : function() {
                    $("#exam-groups-grid").addClass("ajax-sending");
                },
                complete : function() {
                    $("#exam-groups-grid").removeClass("ajax-sending");
                },
            success: function(data) {
                $.fancybox(data,
                        {    "transitionIn"      : "elastic",
                            "transitionOut"   : "elastic",
                            "speedIn"                : 600,
                            "speedOut"            : 200,
                            "overlayShow"     : false,
                            "hideOnContentClick": false,
                            "afterClose":    function() {
                                   var page=$("li.selected  > a").text();
                                $.fn.yiiGridView.update('exam-groups-grid', {url:'<?php echo Yii::app()->request->getUrl()?>',data:{"ExamGroups_page":page}});
                            } //onclosed function
                        });//fancybox
            } //success
        });//ajax
        return false;
    });//bind


})//document ready
    
</script>

