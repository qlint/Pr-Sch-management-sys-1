<style>
.grid-view table.items th {
	padding: 8px 4px !important;
	width: 98px !important;
}
.grid-view table.items th a {
	font-size: 12px !important;
}
</style>
<?php 
/**
 * Ajax Crud Administration
 * ExamGroups * index.php view file
 * InfoWebSphere {@link http://libkal.gr/infowebsphere}
 * @author  Spiros Kabasakalis <kabasakalis@gmail.com>
 * @link http://reverbnation.com/spiroskabasakalis/
 * @copyright Copyright &copy; 2011-2012 Spiros Kabasakalis
 * @since 1.0
 * @ver 1.3
 * @license The MIT License
 */
?>
<?php
$this->breadcrumbs=array(
	Yii::t('app','Examination')=>array('/examination'),
	Yii::t('app','Exams')=>array('/examination'),

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
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top"><?php $this->renderPartial('/default/left_side');?></td>
    <td valign="top"><div class="cont_right formWrapper">
        <h1><?php echo Yii::t('app','Exams');?></h1>
        <div class="button-bg">
          <div class="top-hed-btn-left"> </div>
          <div class="top-hed-btn-right">
            <ul>
              <li>
                <?php 
                            if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_create->settings_value!=0))
                            {
                            echo CHtml::link('<span>'.Yii::t('app','Create Exam').'</span>', array('#'),array('id'=>'add_exam-groups','class'=>'a_tag-btn')); 
                            }
                            ?>
              </li>
              <li> <?php echo CHtml::link('<span>'.Yii::t('app','Grading Levels').'</span>', array('/examination/gradingLevels','id'=>$_REQUEST['id']),array('class'=>'a_tag-btn')) ?> </li>
            </ul>
          </div>
        </div>
        <?php $this->renderPartial('/default/tab');?>
        <div class="clear"></div>
        <div class="emp_right_contner">
          <div class="emp_tabwrapper">
            <div class="clear"></div>
            <div>
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
              </div>
              <br />
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
                            $del_con = Yii::t('app', 'Are you sure you want to delete this exam?');
                            $del_title=Yii::t('app', 'Delete Confirmation');
                            $del=Yii::t('app', 'Delete');
                            $cancel=Yii::t('app', 'Cancel');
                            ?>
              <?php
							
                            $this->widget('zii.widgets.grid.CGridView', array(
								'id' => 'exam-groups-grid',
								'dataProvider' => $model->search(),
								'pager'=>array('cssFile'=>Yii::app()->baseUrl.'/css/formstyle.css'),
								'cssFile' => Yii::app()->baseUrl . '/css/formstyle.css',
								
								
								'htmlOptions'=>array('class'=>'grid-view clear'),
								'columns' => array(
								
									array(
									
									'class'=>'CLinkColumn',
									'labelExpression'=>'$data->name',
									'urlExpression'=>'Yii::app()->createUrl("/examination/exams/create",array("exam_group_id"=>$data->id,"id"=>$_REQUEST["id"]))',
									'header'=>Yii::t('app','Name'),
									'headerHtmlOptions'=>array('style'=>'color:#FF6600')
									),
									
									//'exam_type',
									array(	
										'name'=>'exam_type',
										'value'=>array($model,'examType'),
										'filter'=>false
									),
									
									array(
									'name'=>'is_published',
									'value'=>'$data->is_published ? Yii::t("app","Yes") : Yii::t("app","No")'
									),
									array(
									'name'=>'result_published',
									'value'=>'$data->result_published ? Yii::t("app","Yes") : Yii::t("app","No")'
									),
									
									
									/*
									'exam_date',
									*/
									
									array(
									'class' => 'CButtonColumn',
									'headerHtmlOptions'=>array('style'=>'font-size:12px; font-weight:bold;'),
									'header'=>Yii::t('app','Action'),
									'buttons' => array(
													 'exam-groups_delete' => array(
													 'label' => Yii::t('app', 'Delete'), // text label of the button
													  'url' => '$data->id', // a PHP expression for generating the URL of the button
													  'imageUrl' =>Yii::app()->request->baseUrl .'/js_plugins/ajaxform/images/icons/cross.png', // image URL of the button.   If not set or false, a text link is used
													  'options' => array("class" => "fan_del", 'title' => Yii::t('admin_exam-groups', 'Delete')), // HTML options for the button   tag
													  ),
													 'exam-groups_update' => array(
													 'label' => Yii::t('app', 'Update'), // text label of the button
													 'url' => '$data->id', // a PHP expression for generating the URL of the button
													 'imageUrl' =>Yii::app()->request->baseUrl .'/js_plugins/ajaxform/images/icons/pencil.png', // image URL of the button.   If not set or false, a text link is used
													 'options' => array("class" => "fan_update", 'title' => Yii::t('admin_exam-groups', 'Update')), // HTML options for the    button tag
														),
													 'exam-groups_view' => array(
													  'label' => Yii::t('app', 'View'), // text label of the button
													  'url' => '$data->id', // a PHP expression for generating the URL of the button
													  'imageUrl' =>Yii::app()->request->baseUrl .'/js_plugins/ajaxform/images/icons/properties.png', // image URL of the button.   If not set or false, a text link is used
													  'options' => array("class" => "fan_view", 'title' => Yii::t('admin_exam-groups', 'View')), // HTML options for the    button tag
														),
														
													),
									'template' => $template,
									
									),
									array(
									'class' => 'CButtonColumn',
									'buttons' => array(
																	 
																		'add' => array(
																		'label' => Yii::t('app','Manage This Exam'), // text label of the button
																		
																		'url'=>'Yii::app()->createUrl("/examination/exams/create", array("exam_group_id"=>$data->id,"id"=>$_REQUEST["id"]))', // a PHP expression for generating the URL of the button
																	  
																		),
																		'date_pub' => array(
																		'label' => Yii::t('app',' / Publish Date'), // text label of the button
																		'visible'=>'$data->is_published !=1',
																		'click'=>'function(){return confirm("'.Yii::t('app','Are you sure you want to publish date?').'");}',
																		'url'=>'Yii::app()->createUrl("/examination/exam/publishdate", array("exam_group_id"=>$data->id,"id"=>$_REQUEST["id"]))', // a PHP expression for generating the URL of the button
																		  
																		),
																		'res_pub' => array(
																		'label' => Yii::t('app',' / Publish Result'), // text label of the button
																		'visible'=>'$data->result_published !=1',
																		'click'=>'function(){return confirm("'.Yii::t('app','Are you sure you want to publish result?').'");}',
																		'url'=>'Yii::app()->createUrl("/examination/exam/publishresult", array("exam_group_id"=>$data->id,"id"=>$_REQUEST["id"]))', // a PHP expression for generating the URL of the button
																	  
																		),
																	),
									'template' => '{add}{date_pub}{res_pub}',
									'header'=>Yii::t('app','Manage'),
									'htmlOptions'=>array('style'=>'width:17%'),
									'headerHtmlOptions'=>array('style'=>'color:#FF6600')
									),
								),
								'afterAjaxUpdate'=>'js:function(id,data){$.bind_crud()}'
                            
                             ));
                             
							
                            ?>
            </div>
          </div>
          <!-- END div class="emp_tabwrapper" --> 
        </div>
        <!-- END div class="emp_right_contner" --> 
      </div>
      <!-- END div class="cont_right formWrapper" --></td>
  </tr>
</table>
<script type="text/javascript">
//document ready
$(function() {

    //declaring the function that will bind behaviors to the gridview buttons,
    //also applied after an ajax update of the gridview.(see 'afterAjaxUpdate' attribute of gridview).
        $. bind_crud= function(){
            
 //VIEW

    $('.fan_view').each(function(index) {
        var id = $(this).attr('href');
        $(this).bind('click', function() {
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->request->baseUrl;?>/index.php?r=examination/exam/returnView",
                data:{"id":id,"YII_CSRF_TOKEN":"<?php echo Yii::app()->request->csrfToken;?>"},
                beforeSend : function() {
                    $("#exam-groups-grid").addClass("ajax-sending");
                },
                complete : function() {
                    $("#exam-groups-grid").removeClass("ajax-sending");
                },
                success: function(data) {
                    $.fancybox(data,
                            {    "transitionIn" : "elastic",
                                "transitionOut" :"elastic",
                                "speedIn"              : 600,
                                "speedOut"         : 200,
                                "overlayShow"  : false,
                                "hideOnContentClick": false
                            });//fancybox
                    //  console.log(data);
                } //success
            });//ajax
            return false;
        });
    });

//UPDATE

    $('.fan_update').each(function(index) {
        var id = $(this).attr('href');
        $(this).bind('click', function() {
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->request->baseUrl;?>/index.php?r=examination/exam/returnForm",
                data:{"update_id":id,"YII_CSRF_TOKEN":"<?php echo Yii::app()->request->csrfToken;?>"},
                beforeSend : function() {
                    $("#exam-groups-grid").addClass("ajax-sending");
                },
                complete : function() {
                    $("#exam-groups-grid").removeClass("ajax-sending");
                },
                success: function(data) {
                    $.fancybox(data,
                            {    "transitionIn"    :  "elastic",
                                 "transitionOut"  : "elastic",
                                 "speedIn"               : 600,
                                 "speedOut"           : 200,
                                 "overlayShow"    : false,
                                 "hideOnContentClick": false,
                                "afterClose":    function() {
                                   var page=$("li.selected  > a").text();
                                $.fn.yiiGridView.update('exam-groups-grid', {url:'<?php echo Yii::app()->request->getUrl()?>',data:{"ExamGroups_page":page}});
                                }//onclosed
                            });//fancybox
                    //  console.log(data);
                } //success
            });//ajax
            return false;
        });
    });


// DELETE

    var deletes = new Array();
    var dialogs = new Array();
    $('.fan_del').each(function(index) {
        var id = $(this).attr('href');
        deletes[id] = function() {
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->request->baseUrl;?>/index.php?r=examination/exam/ajax_delete",
                data:{"id":id,"YII_CSRF_TOKEN":"<?php echo Yii::app()->request->csrfToken;?>"},
                    beforeSend : function() {
                    $("#exam-groups-grid").addClass("ajax-sending");
                },
                complete : function() {
                    $("#exam-groups-grid").removeClass("ajax-sending");
                },
                success: function(data) {
					alert('<?php echo Yii::t('app','Exam deleted successfully');?>');
                    var res = jQuery.parseJSON(data);
                     var page=$("li.selected  > a").text();
                    $.fn.yiiGridView.update('exam-groups-grid', {url:'<?php echo Yii::app()->request->getUrl()?>',data:{"ExamGroups_page":page}});
                }//success
            });//ajax
        };//end of deletes

        dialogs[id] =
                        $('<div style="text-align:center;"></div>')
                        .html('<?php echo addslashes($del_con); ?><br><br>' + '<h2 style="color:#999999"></h2>')
                       .dialog(
                        {
                            autoOpen: false,
                            title: '<?php echo  $del_title; ?>',
                            modal:true,
                            resizable:false,
                            buttons: [
                                {
                                    text: "<?php echo  $del; ?>",
                                    click: function() {
                                                                      deletes[id]();
                                                                      $(this).dialog("close");
                                                                      }
                                },
                                {
                                   text: "<?php echo $cancel; ?>",
                                   click: function() {
                                                                     $(this).dialog("close");
                                                                     }
                                }
                            ]
                        }
                );

        $(this).bind('click', function() {
                                                                      dialogs[id].dialog('open');
                                                                       // prevent the default action, e.g., following a link
                                                                      return false;
                                                                     });
    });//each end

        }//bind_crud end

   //apply   $. bind_crud();
  $. bind_crud();


//CREATE 

    $('#add_exam-groups').bind('click', function() {
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->request->baseUrl;?>/index.php?r=examination/exam/returnForm",
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
