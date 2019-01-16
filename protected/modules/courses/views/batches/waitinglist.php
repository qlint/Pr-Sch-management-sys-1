<style>
	.container
	{
		background:#FFF;
	}
	
	.max_student{ border-left: 3px solid #fff;
    margin: 0 3px;
    padding: 6px 0 6px 3px;
    word-break: break-all;}
	

</style>

<?php 
$batch=Batches::model()->findByAttributes(array('id'=>$_REQUEST['id'])); 
$this->breadcrumbs=array(
	Yii::t('app','Courses')=>array('/courses'),
	html_entity_decode($batch->name) =>array('/courses/batches/batchstudents','id'=>$_REQUEST['id']),
	Yii::t('app','Waitinglist'),
);
?>

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
<?php
Yii::app()->clientScript->registerScript(
	'myHideEffect',
	'$(".info").animate({opacity: 1.0}, 3000).fadeOut("slow");',
	CClientScript::POS_READY
);
?>


<?php 
$batch=Batches::model()->findByAttributes(array('id'=>$_REQUEST['id'])); 
$current_academic_yr = Configurations::model()->findByAttributes(array('id'=>35));
if(Yii::app()->user->year)
{
	$year = Yii::app()->user->year;
}
else
{
	$year = $current_academic_yr->config_value;
}
$is_create = PreviousYearSettings::model()->findByAttributes(array('id'=>1));
$is_insert = PreviousYearSettings::model()->findByAttributes(array('id'=>2));
$is_delete = PreviousYearSettings::model()->findByAttributes(array('id'=>4));
$is_inactive = PreviousYearSettings::model()->findByAttributes(array('id'=>8));
$is_active = PreviousYearSettings::model()->findByAttributes(array('id'=>7));
?>

<div style="background:#FFF;">
    <table width="100%" cellspacing="0" cellpadding="0" border="0">
        <tbody>
            <tr>
                <td valign="top">
					<?php 
					if($batch!=NULL)
                    {
                    ?>
                        <div style="padding:20px;">
                            <div class="clear"></div>
                            <div class="emp_right_contner">
                                <div class="emp_tabwrapper">
									<?php $this->renderPartial('tab');?>
                                    <div class="clear"></div>
                                    <div class="emp_cntntbx" style="padding-top:10px;">
                                    
                                        <?php if(Yii::app()->user->hasFlash('success')):?>
                                        	<div class="info" style="color:#C30; width:800px; height:30px">
                                        	<?php echo Yii::app()->user->getFlash('success'); ?>
                                        	</div>
                                        <?php endif; ?>
                                        
                                   <?php
									Yii::app()->clientScript->registerScript(
									'myHideEffect',
									'$(".flashMessage").animate({opacity: 1.0}, 3000).fadeOut("slow");',
									CClientScript::POS_READY
									);
									?>
									<?php
									/* Success Message */
									if(Yii::app()->user->hasFlash('successMessage')): 
									?>
										<div class="flashMessage" style="background:#FFF; color:#C00; padding-left:200px; font-size:16px">
										<?php echo Yii::app()->user->getFlash('successMessage'); ?>
										</div>
									<?php endif;
									 /* End Success Message */
									?>       
                                        <div class="table_listbx">
											<?php
                                            if(isset($_REQUEST['id']))
                                            {
												
												/*$criteria = new CDbCriteria;
												$criteria->condition = 'batch_id=:batch_id AND status=:status AND is_deleted=:is_deleted';
												$criteria->params[':batch_id'] = $_REQUEST['id'];
												$criteria->params[':status'] = -3;
												$criteria->params[':is_deleted'] = 0;
												$criteria->order = 'id ASC';
												
												$posts=RegisteredStudents::model()->findAll($criteria);*/
												$waitinlistdetails =  WaitinglistStudents::model()->findAllByAttributes(array('batch_id'=>$_REQUEST['id']));
												
                                            	//$posts=Students::model()->findAll("batch_id=:x and is_deleted=:y and is_active=:z", array(':x'=>$_REQUEST['id'],':y'=>'0',':z'=>'1'));
												if($waitinlistdetails!=NULL)
												{
												?>
                                                    <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                                        <tr class="listbxtop_hdng">
                                                            <td ><?php echo Yii::t('app','Sl no.');?></td>
                                                            <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile"))
                                                                { ?>
                                                                <td ><?php echo Yii::t('app','Student Name');?></td> <?php } ?>
                                                           	<td ><?php echo Yii::t('app','Priority');?></td>
                                                            
                                                            
                                                            <?php
															if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and ($is_insert->settings_value!=0 or $is_inactive->settings_value!=0)))
															{
															?>
                                                            <td ><?php echo Yii::t('app','Actions');?></td>
                                                            <?php
															}
															?>
                                                        </tr>
                                                        <?php
                                                        $i=0;
                                                        foreach($waitinlistdetails as $waitinlistdetail)
                                                        {
															$i++;	
																												
															$posts_1 =  Students::model()->findByAttributes(array('id'=>$waitinlistdetail->student_id));
															
															echo '<tr>';
																echo '<td>'.$i.'</td>';	
                                                                                                                                if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile"))
                                                                                                                                {
                                                                                                                                    $name='';
                                                                                                                                    $name=  $posts_1->studentFullName('forStudentProfile');
																echo '<td>'.CHtml::link($name, array('/onlineadmission/admin/view', 'id'=>$posts_1->id)).'</td>';
                                                                                                                                }
																?>
															
																
																
															
                                                            	<td><?php echo $waitinlistdetail->priority;?></td>
                                                                <?php
																if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and ($is_insert->settings_value!=0 or $is_inactive->settings_value!=0)))
																{
																?>
                                                                <td >
                                                                    <div class="opnsl_act_drop_bg">
                                                                        <div  id="<?php echo $i; ?>" class="opnsl_act_but">
																			<?php echo Yii::t('app','Actions');?>
																		</div>
                                                                        <div class="opnsl_act_drop opnsl_act_drop_custom" id="<?php echo $i.'x'; ?>">
                                                                            <div class="but_bg_outer"></div>
                                                                            <div class="but_bg">
                                                                            <div  id="<?php echo $i; ?>" class="act_but_hover">
																				<?php echo Yii::t('app','Close');?>
																			</div>
																		</div>
                                                                        <ul>
                                                                        	<?php
																			if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_insert->settings_value!=0))
																			{
																			?>
                                                                            
                                                                            <li class="add">                                                                            
																				<?php																		   
                                                                                echo CHtml::ajaxLink(
                                                                                    Yii::t('app','Approve').'<span>'.Yii::t('app','Approve the student').'</span>', 
                                                                                    $this->createUrl('/onlineadmission/admin/approve'),
                                                                                    array(
                                                                                        'onclick'=>'$("#jobDialog'.$posts_1->id.'").dialog("open"); return false;',
																						'dataType'=>'json',
																						'success'=>'js:function(data){
																							if(data.status=="success"){
																								$("#jobDialog123'.$posts_1->id.'").html(data.content);
																							}
																						}',
																						'error'=>'js:function(){
																							alert("'.Yii::t("app", "Some problem found").'!");
																							window.location.reload();
																						}',
                                                                                        'type' =>'GET',
                                                                                        'data'=>array(
                                                                                            'id' =>$posts_1->id,
                                                                                            'bid'=>$_REQUEST['id']
                                                                                        ),
                                                                                    ),
                                                                                    array(
                                                                                        'id'=>'showJobDialog'.$posts_1->id,
                                                                                        'class'=>'tt-approved'
                                                                                    )
                                                                                );
                                                                                ?>
                                                                            </li>
                                                                            <?php
																			}
																			?>
                                                                            <?php /*?><li class="add">
																				<?php echo CHtml::link(Yii::t('app','Add Elective').'<span>'.Yii::t('app','for add leave').'</span>', array('#'),array('class'=>'addevntelect','name' => $posts_1->id,'id'=>'add_elective')) ?>
																			</li><?php */?>
                                                                            <?php
																			if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_inactive->settings_value!=0))
																			{
																			?>
                                                                            <li class="edit">
																				<?php echo CHtml::link(Yii::t('app','Edit').'<span>'.Yii::t('app','Edit the priority').'</span>', array('/onlineadmission/waitinglistStudents/manage', 'id'=>$posts_1->id,'from'=>'course')) ?>
																			</li>
                                                                            <?php
																			}
																			?>
                                                                            <?php /*?><li class="pending">
																				<?php echo CHtml::link(Yii::t('app','Make Pending').'<span>'.Yii::t('app','Change the status to pending').'</span>', array('/onlineadmission/waitinglistStudents/makepending', 'id'=>$posts_1->id,'batch_id'=>$_REQUEST['id'],'from'=>'course')) ?>
																			</li><?php */?>
                                                                            <li class="delete">
                                                                            	<?php
																					echo CHtml::link(Yii::t('app','Delete').'<span>'.Yii::t('app','Delete from waiting list').'</span>', "#", array('submit'=>array('/onlineadmission/waitinglistStudents/delete','id'=>$posts_1->id, 'batch_id'=>$_REQUEST['id'], 'from'=>'course'), 'class'=>'tt-delete','confirm'=>Yii::t('app','Are you sure you want to delete this student from waiting list?'), 'csrf'=>true));																					
																				?>																				
																			</li>
                                                                            <!--<li class="edit"><a href="#">Edit Leave<span>for add leave</span></a></li>
                                                                            <li class="delete"><a href="#">Delete Leave<span>for add leave</span></a></li>
                                                                            <li class="add"><a href="#">Add Fees<span>for add leave</span></a></li>
                                                                            <li class="add"><a href="#">Add Report<span>for add leave</span></a></li>-->
                                                                        </ul>
                                                                        </div>
                                                                            <div class="clear"></div>
                                                                        <div id="<?php echo $posts_1->id ?>"></div>
                                                                    </div>
                                                                </td>
                                                                <?php
																}
																?>
                                                            </tr>
                                                            <div  id="<?php echo 'jobDialog123'.$posts_1->id;?>"></div>
                                                        <?php 
														} // END foreach($posts as $posts_1)
                                                        ?>
                                                    </table>
												<?php    	
												} // END if $posts!=NULL
												else
												{
													echo '<br><div class="notifications nt_red" style="padding-top:10px">'.'<i>'.Yii::t('app','No Waiting List Students In This'.' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id")).'</i></div>'; 
													
												}
												
                                            } // END if isset($_REQUEST['id'])
                                            ?>
                                        
                                        </div> <!-- END div class="table_listbx" -->
                                        <br />
                                        
                                        
                                    </div> <!-- END div class="emp_cntntbx" -->
                                </div> <!-- END div class="emp_tabwrapper" -->
                            </div> <!-- END div class="emp_right_contner" -->
                        </div>
                    <?php    	
                    }
                    else
                    {
						echo '<div class="emp_right" style="padding-left:20px; padding-top:50px;">';
							echo '<div class="notifications nt_red">'.'<i>'.Yii::t('app','Nothing Found!!').'</i></div>'; 
						echo '</div>';
                    }
                    ?>
                </td>
            </tr>
        </tbody>
    </table>
</div>


<script>
//CREATE 

$('.addevnt').bind('click', function() {var id = $(this).attr('name');
	$.ajax({
		type: "POST",
		url: "<?php echo Yii::app()->request->baseUrl;?>/index.php?r=students/studentLeave/returnForm",
		data:{"id":id,"YII_CSRF_TOKEN":"<?php echo Yii::app()->request->csrfToken;?>"},
			beforeSend : function() {
				$("#"+$(this).attr('name')).addClass("ajax-sending");
			},
			complete : function() {
				$("#"+$(this).attr('name')).removeClass("ajax-sending");
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



/*//CREATE 


 $('.addevntelect').bind('click', function() {var id = $(this).attr('name');
	$.ajax({
		type: "POST",
	   url: "<?php //echo Yii::app()->request->baseUrl;?>/index.php?r=courses/electiveGroups/returnForm",
		data:{"batch_id":<?php //echo $_GET['id'];?>,"YII_CSRF_TOKEN":"<?php //echo Yii::app()->request->csrfToken;?>"},
			beforeSend : function() {
				$("#"+$(this).attr('name')).addClass("ajax-sending");
			},
			complete : function() {
				$("#"+$(this).attr('name')).removeClass("ajax-sending");
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
});//bind*/


</script>
<script type="text/javascript">
function check(studentId){
	$("[name=batch]").unbind('change');
	$("[name=batch]").change(function(){
		var batchId	= $(this).val();
		$.ajax({
			type: "POST",
			url: <?php echo CJavaScript::encode(Yii::app()->createUrl('students/registration/chechclassavailability'))?>,
			data: {'batchId':batchId},
			success: function(result){
				
				if(result!='nil')
				 {
					$(".newstatus:last-child").show();
					var finalResult = result.split("+");
					$(".newstatus:last-child").text(finalResult[0]);					
					$(".newstatus:last-child").append("<span class='max_student'>"+finalResult[1]+"</span>");
					$(".newstatus:last-child").append("<span class='max_student'>"+finalResult[2]+"</span>");
				 }
				 else
				 {
					 $(".newstatus:last-child").hide();
				 }
				
			}
		});	
	});
	checkclassavailability(studentId);	
}

function checkclassavailability(studentId){
	var batchId	= $("[name=batch]:last").val();
	$.ajax({
		type: "POST",
		url: <?php echo CJavaScript::encode(Yii::app()->createUrl('students/registration/chechclassavailability'))?>,
		data: {'batchId':batchId},
		success: function(result){
			
			 if(result!='nil')
			 {
				$(".newstatus:last-child").show();
				var finalResult = result.split("+");
				$(".newstatus:last-child").text(finalResult[0]);					
				$(".newstatus:last-child").append("<span class='max_student'>"+finalResult[1]+"</span>");
				$(".newstatus:last-child").append("<span class='max_student'>"+finalResult[2]+"</span>");
			 }
			 else
			 {
				 $(".newstatus:last-child").hide();
			 }
		}
	});	
}
</script>
		   



