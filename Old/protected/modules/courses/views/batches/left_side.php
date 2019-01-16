<?php Yii::app()->clientScript->registerCoreScript('jquery');

         //IMPORTANT about Fancybox.You can use the newest 2.0 version or the old one
        //If you use the new one,as below,you can use it for free only for your personal non-commercial site.For more info see
		//If you decide to switch back to fancybox 1 you must do a search and replace in index view file for "beforeClose" and replace with 
		//"onClosed"
        // http://fancyapps.com/fancybox/#license
          // FancyBox2
        Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js_plugins/fancybox2/jquery.fancybox.js', CClientScript::POS_HEAD);
        Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/js_plugins/fancybox2/jquery.fancybox.css', 'screen');
         // FancyBox
         //Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js_plugins/fancybox/jquery.fancybox-1.3.4.js', CClientScript::POS_HEAD);
         // Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/js_plugins/fancybox/jquery.fancybox-1.3.4.css','screen');
        //JQueryUI (for delete confirmation  dialog)
         Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js_plugins/jqui1812/js/jquery-ui-1.8.12.custom.min.js', CClientScript::POS_HEAD);
         Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/js_plugins/jqui1812/css/dark-hive/jquery-ui-1.8.12.custom.css','screen');
          ///JSON2JS
         Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js_plugins/json2/json2.js');
       

           //jqueryform js
               Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js_plugins/ajaxform/jquery.form.js', CClientScript::POS_HEAD);
              Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js_plugins/ajaxform/form_ajax_binding.js', CClientScript::POS_HEAD);
              Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/js_plugins/ajaxform/client_val_form.css','screen');  ?>
<div class="emp_cont_left">
    <div class="empleftbx">
    <div class="empimgbx">
    <ul>
           <?php if(isset($_REQUEST['id']))
		   {?>
           <?php $batch=Batches::model()->findByAttributes(array('id'=>$_REQUEST['id'])); ?>
           <?php if($batch!=NULL)
		   {
			   ?>
    
    <li class="img_text">
    	<?php echo '<strong>'.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").'</strong>';?><?php echo $batch->name; ?><br>
        <span><strong><?php echo Yii::t('app','Course: ');?></strong>
        <?php $course=Courses::model()->findByAttributes(array('id'=>$batch->course_id));
		if($course!=NULL)
		   {
			   echo $course->course_name; 
		   }?></span>
      
    </li>
    </ul>
     <div class="clear"></div>
    </div>
    <div class="status_bx">
    	<ul>
        	<li style="border-right:1px #d9e1e7 solid"><span><?php echo count(Students::model()->findAll("batch_id=:x", array(':x'=>$_REQUEST['id']))); ?></span><?php echo Yii::t('app','Students');?></li>
            <li style="border-left:1px #fff solid;border-right:1px #d9e1e7 solid;"><span><?php echo count(Subjects::model()->findAll("batch_id=:x", array(':x'=>$_REQUEST['id']))); ?></span><?php echo Yii::t('app','Subjects');?></li>
            <li style="border-left:1px #fff solid"><span><?php echo count(TimetableEntries::model()->findAll(array('condition'=>'batch_id=:x', 'group'=>'employee_id','params'=>array(':x'=>$_REQUEST['id'])))); ?></span><?php echo Yii::t('app','Teachers');?></li>
        </ul>
     <div class="clear"></div>
    </div>
	<div class="namelist">
    	<ul>
        	<li><?php echo '<strong>'.Yii::t('app','Class Teacher : ').'</strong>';?> 
			<?php $employee=Employees::model()->findByAttributes(array('id'=>$batch->employee_id));
		    if($employee!=NULL)
		    {
			   echo Employees::model()->getTeachername($employee->id); 
		    }?>
            </li>
            <!--<li><strong>Class Teacher:</strong>Mary Symon</li>
            <li><strong>Class Teacher:</strong>Mary Symon</li>-->
        </ul>
     <div class="clear"></div>
    </div>
    <?php $time='nt_red'; ?>
        <?php $week=Weekdays::model()->findByAttributes(array('batch_id'=>$batch->id));
		if($week==NULL)
		{?>
        <div class="notifications nt_green">
    	<?php echo '<span>'.Yii::t('app','Notice').'</span>';?>
        <?php echo Yii::t('app','No').' '. Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' '. Yii::t('app','weekdays are defined.Default Settings Selected.');?><br />
        <?php echo CHtml::link(Yii::t('app','Define Now'), array('/courses/weekdays','id'=>$_REQUEST['id']));?></div>
        <?php } ?>
        <?php $timing=ClassTimings::model()->findByAttributes(array('batch_id'=>$batch->id));
		if($timing==NULL)
		{ $time='nt_gray';?>
        <div class="notifications nt_red">
    	<?php echo '<span>'.Yii::t('app','Notice').'</span>';?>
        <?php echo Yii::t('app','No').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' '. Yii::t('app','Class Timings are defined.');?><br />
        <?php echo CHtml::link(Yii::t('app','Define Now'), array('#'),array('id'=>'add_class-timings-side'));?></div>
        <?php } ?>
        <?php $ttab=TimetableEntries::model()->findByAttributes(array('batch_id'=>$batch->id));
		if($ttab==NULL)
		{?>
        <div class="notifications <?php echo $time; ?>">
    	<?php echo '<span>'.Yii::t('app','Notice').'</span>';?>
       <?php echo Yii::t('app','Time Table Not Created.');?> <br />
        <?php echo CHtml::link(Yii::t('app','Create Now'), array('weekdays/timetable','id'=>$_REQUEST['id']));?></div>
        <?php } ?>
        <?php $sub=Subjects::model()->findByAttributes(array('batch_id'=>$batch->id));
		if($sub==NULL)
		{?>
        <div class="notifications nt_red">
    	<?php echo '<span>'.Yii::t('app','Notice').'</span>';?>
        <?php echo Yii::t('app','No Subjects Added');?>.<br />
        <?php echo CHtml::link(Yii::t('app','Add Now'), array('#'),array('id'=>'add_subjects-side')) ?></div>
        <?php } ?>
    
    <div class="clear"></div>
    <div class="left_emp_navbx">
    <div class="left_emp_nav">
    <h2><?php echo Yii::t('app','Actions');?></h2>
    <ul>
    
    <li><?php echo CHtml::link(Yii::t('app','Add Student'), array('/students/students/create'),array('class'=>'student')) ?></li>
   <?php /*?> <li><?php echo CHtml::link('Add Event', array('#'),array('id'=>'add_events-side','class'=>'addevnt')) ?></li><?php */?>
    <li><?php echo CHtml::link(Yii::t('app','New Subject'), array('#'),array('id'=>'add_subject-name-side','class'=>'newsub')) ?></li>
    <li><?php echo CHtml::link(Yii::t('app','Mark Attendance'), array('/courses/studentAttentance','id'=>$_REQUEST['id']),array('class'=>'mark')) ?></li>
    
   <?php /*?> <li><a class="copy" href="#">Copy Batch Settings<span class="active"></span></a></li><?php */?>
    <li><?php echo CHtml::link(Yii::t('app','Promote').' '. Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"), array('batches/promote','id'=>$_REQUEST['id']),array('class'=>'promote')) ?></li>
    <?php if($batch->is_active=='1')
	{?>
    <li><?php echo CHtml::link(Yii::t('app','Deactivate').' '. Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"), array('batches/deactivate','id'=>$_REQUEST['id']),array('confirm'=>'Are You Sure,Deactivate This'.' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' '.'?','class'=>'deactivate last')) ?></li><?php }
	else
	{ ?>
    <li><?php echo CHtml::link(Yii::t('app','Activate').' '. Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"), array('batches/activate','id'=>$_REQUEST['id']),array('confirm'=>'Are You Sure,Activate This'.' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' '.'?','class'=>'deactivate last')) ?></li><?php }?>
    
    
    </ul>
    </div>
    <div class="clear"></div>
    
    </div>
    
    <?php }}
	else
	{
		exit;
		
	} ?>
   
    </div>
    
    </div>
    <div id="subjects-grid-side"></div>
    <div id="class-timings-grid-side"></div>
    <div id="events-grid-side"></div>
    <div id="subject-name-grid-side"></div>
    <script>
	//CREATE 

    $('#add_subjects-side').bind('click', function() {
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->request->baseUrl;?>/index.php?r=courses/subject/returnForm",
            data:{"batch_id":<?php echo $_GET['id'];?>,"YII_CSRF_TOKEN":"<?php echo Yii::app()->request->csrfToken;?>"},
                beforeSend : function() {
                    $("#subjects-grid-side").addClass("ajax-sending");
                },
                complete : function() {
                    $("#subjects-grid-side").removeClass("ajax-sending");
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
								window.location.reload();
								}  //onclosed function
                        });//fancybox
            } //success
        });//ajax
        return false;
    });//bind
	
	
	//CREATE 

    $('#add_class-timings-side').bind('click', function() {
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->request->baseUrl;?>/index.php?r=courses/classTiming/returnForm",
            data:{"batch_id":<?php echo $_GET['id'];?>,"YII_CSRF_TOKEN":"<?php echo Yii::app()->request->csrfToken;?>"},
                beforeSend : function() {
                    $("#class-timings-grid-side").addClass("ajax-sending");
                },
                complete : function() {
                    $("#class-timings-grid-side").removeClass("ajax-sending");
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
								window.location.reload();
								} //onclosed function
                        });//fancybox
            } //success
        });//ajax
        return false;
    });//bind
	
	//CREATE 

    $('#add_events-side').bind('click', function() {
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->request->baseUrl;?>/index.php?r=courses/events/returnForm",
            data:{"YII_CSRF_TOKEN":"<?php echo Yii::app()->request->csrfToken;?>"},
                beforeSend : function() {
                    $("#events-grid-side").addClass("ajax-sending");
                },
                complete : function() {
                    $("#events-grid-side").removeClass("ajax-sending");
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
								window.location.reload();
								} //onclosed function
                        });//fancybox
            } //success
        });//ajax
        return false;
    });//bind
	
	//CREATE 

    $('#add_subject-name-side').bind('click', function() {
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->request->baseUrl;?>/index.php?r=courses/defaultsubjects/returnForm",
            data:{"YII_CSRF_TOKEN":"<?php echo Yii::app()->request->csrfToken;?>"},
                beforeSend : function() {
                    $("#subject-name-grid-side").addClass("ajax-sending");
                },
                complete : function() {
                    $("#subject-name-grid-side").removeClass("ajax-sending");
                },
            success: function(data) {
                $.fancybox(data,
                        {    "transitionIn"      : "elastic",
                            "transitionOut"   : "elastic",
                            "speedIn"                : 600,
                            "speedOut"            : 200,
                            "overlayShow"     : false,
                            "hideOnContentClick": false,
                            "afterClose":    function() {window.location.reload();} //onclosed function
                        });//fancybox
            } //success
        });//ajax
        return false;
    });//bind
	</script>