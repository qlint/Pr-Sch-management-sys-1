
<?php
$this->breadcrumbs=array(
	Yii::t('app','Examination')=>array('/examination'),
	Yii::t('app','Exam Groups')=>array('/examination'),

);


?>
<?php  
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('exam-groups-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">
        <?php $this->renderPartial('examination.views.default.left_side');?>     
        </td>
        
        <td valign="top">
            <div class="cont_right formWrapper">
                <h1><?php echo Yii::t('app','New Exam');?></h1>
                
                 <?php
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
				$is_edit = PreviousYearSettings::model()->findByAttributes(array('id'=>3));
				$is_delete = PreviousYearSettings::model()->findByAttributes(array('id'=>4));
				
				
				$template = '{exam-groups_view}';
				if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_edit->settings_value!=0))
				{
					$template = $template.'{exam-groups_update}';
				}
				
				if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_delete->settings_value!=0))
				{
					$template = $template.'{exam-groups_delete}';
				}
					
				?>
                
                
                <div class="clear"></div>
                <div class="emp_right_contner">
                    <div class="emp_tabwrapper">
						<?php $this->renderPartial('/default/tab');?>
                        
                        <div class="clear"></div>
                        <div>
                            <div  style="position:relative">
                                <div class="edit_bttns" style="width:auto; max-width:270px; top:-110px; right:-15px;">
                                    <ul>
                                        <li>
                                        <?php 
                                    	if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_create->settings_value!=0))
										{
										echo CHtml::link('<span>'.Yii::t('app','Create Exam').'</span>', array('#'),array('id'=>'add_exam-groups','class'=>'addbttn')); 
										}
										?>
                                        </li>
                                        <li>
                                        <?php echo CHtml::link('<span>'.Yii::t('app','Grading Levels').'</span>', array('/examination/gradingLevels','id'=>$_REQUEST['id']),array('class'=>'addbttn last')) ?>
                                        </li>
                                    </ul>
                                    <div class="clear"></div>
                                </div> <!-- END div class="edit_bttns" -->
                            </div> 
                            
                            <?php 				
							if($year != $current_academic_yr->config_value and ($is_create->settings_value==0 or $is_edit->settings_value==0 or $is_delete->settings_value==0))
							{
							?>
								<div>
									<div class="yellow_bx" style="background-image:none;width:680px;padding-bottom:45px;">
										<div class="y_bx_head" style="width:650px;">
										<?php 
											echo Yii::t('app','You are not viewing the current active year. ');
											if($is_create->settings_value==0 and $is_edit->settings_value!=0 and $is_delete->settings_value!=0)
											{ 
												echo Yii::t('app','To create exam groups, enable Create option in Previous Academic Year Settings.');
											}
											elseif($is_create->settings_value!=0 and $is_edit->settings_value==0 and $is_delete->settings_value!=0)
											{
												echo Yii::t('app','To edit exam groups, enable Edit option in Previous Academic Year Settings.');
											}
											elseif($is_create->settings_value!=0 and $is_edit->settings_value!=0 and $is_delete->settings_value==0)
											{
												echo Yii::t('app','To delete exam groups, enable Delete option in Previous Academic Year Settings.');
											}
											else
											{
												echo Yii::t('app','To manage exam groups, enable the required options in Previous Academic Year Settings.');	
											}
										?>
										</div>
										<div class="y_bx_list" style="width:650px;">
											<h1><?php echo CHtml::link(Yii::t('app','Previous Academic Year Settings'),array('/previousYearSettings/create')) ?></h1>
										</div>
									</div>
								</div><br />
							<?php
							}
							?>
                            
                            <?php
							    	 Yii::app()->clientScript->registerScript(
									 'myHideEffect',
									 '$(".flashMessage").animate({opacity: 1.0}, 3000).fadeOut("slow");',
									 CClientScript::POS_READY
									 );
                            //Strings for the delete confirmation dialog.
                            $del_con = Yii::t('app', 'Are you sure you want to delete this exam-group?');
                            $del_title=Yii::t('app', 'Delete Confirmation');
                            $del=Yii::t('app', 'Delete');
                            $cancel=Yii::t('app', 'Cancel');
                            ?>
                           
                        </div>
                    </div> <!-- END div class="emp_tabwrapper" -->
                </div> <!-- END div class="emp_right_contner" -->
            </div> <!-- END div class="cont_right formWrapper" -->
        </td>
    </tr>
</table>

<script type="text/javascript">
//document ready
$(function() {
	
	//CREATE 

    $('#add_exam-groups').bind('click', function() {
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->request->baseUrl;?>/index.php?r=CBSCExam/examgroups/returnForm",
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

