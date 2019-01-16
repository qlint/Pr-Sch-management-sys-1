<style type="text/css">
.button-column {
	font-size: 13px !important;
}
</style>
<?php 
/**
 * Ajax Crud Administration
 * ClassTimings * index.php view file
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
    Yii::t('app','Timetable')=>array('/timetable'),
	Yii::t('app','Manage Class Timings'),
	 
);
?>
<?php  
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('class-timings-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top"><?php $this->renderPartial('/default/left_side');?></td>
    <td valign="top"><div class="cont_right formWrapper">
        <h1><?php echo Yii::t('app','Class Timings'); ?></h1>
        <!--<div class="searchbx_area">
                <div class="searchbx_cntnt">
                <ul>
                <li><a href="#"><img src="images/search_icon.png" width="46" height="43" /></a></li>
                <li><input class="textfieldcntnt"  name="" type="text" /></li>
                </ul>
                </div>
                
                </div>-->
        
        <div class="clear"></div>
        <?php
					$current_academic_yr = Configurations::model()->findByAttributes(array('id'=>35));
					if(Yii::app()->user->year){
						$year = Yii::app()->user->year;
					}
					else{
						$year = $current_academic_yr->config_value;
					}
					$is_create = PreviousYearSettings::model()->findByAttributes(array('id'=>1));
					$is_edit = PreviousYearSettings::model()->findByAttributes(array('id'=>3));
					$is_delete = PreviousYearSettings::model()->findByAttributes(array('id'=>4));
				?>
        <div class="emp_right_contner">
          <div class="emp_tabwrapper">
            <?php $this->renderPartial('/default/tab');?>
            <div class="clear"></div>
            <div class="emp_cntntbx" style="padding-top:10px;">
              <div class="pdf-box">
                <div class="button-bg">
                  <div class="top-hed-btn-left"> </div>
                  <div class="top-hed-btn-right">
                    <ul>
                      <li> <?php echo CHtml::link('<span>'.Yii::t('app','Time Table').'</span>', array('/timetable/weekdays/timetable','id'=>$_REQUEST['id']),array('class'=>'a_tag-btn'));?> </li>
                      <li>
                        <?php
                                                if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_create->settings_value!=0)){
                                                ?>
                        <div class="right" align="left"> <?php echo CHtml::link(Yii::t('app','Create Class Timings'), array('/timetable/classTiming/create','id'=>$_REQUEST['id']),array('class'=>'a_tag-btn')) ?> </div>
                        <?php
                                                }
                                                ?>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
              <div style="width:100%">
                <div>
                  <?php
								
								
								$template = '{class-timings_view}';
								if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_edit->settings_value!=0))
								{
									$template = $template.'{update}';
								}
								
								if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_delete->settings_value!=0))
								{
									$template = $template.'{class-timings_delete}';
								}
								
								if($year != $current_academic_yr->config_value and ($is_create->settings_value==0 or $is_edit->settings_value==0 or $is_delete->settings_value==0))
								{
										
									?>
                  <div>
                    <div class="yellow_bx" style="background-image:none;width:95%;padding-bottom:45px;">
                      <div class="y_bx_head" style="width:95%;">
                        <?php 
													echo Yii::t('app','You are not viewing the current active year. ');
													if($is_create->settings_value==0 and $is_edit->settings_value!=0 and $is_delete->settings_value!=0)
													{ 
														echo Yii::t('app','To create a class timing, enable Create option in Previous Academic Year Settings.');
													}
													elseif($is_create->settings_value!=0 and $is_edit->settings_value==0 and $is_delete->settings_value!=0)
													{
														echo Yii::t('app','To edit the class timing, enable Edit option in Previous Academic Year Settings.');
													}
													elseif($is_create->settings_value!=0 and $is_edit->settings_value!=0 and $is_delete->settings_value==0)
													{
														echo Yii::t('app','To delete the class timing, enable Delete option in Previous Academic Year Settings.');
													}
													else
													{
														echo Yii::t('app','To manage the class timing, enable the required options in Previous Academic Year Settings.');	
													}
												?>
                      </div>
                      <div class="y_bx_list" style="width:95%;">
                        <h1><?php echo CHtml::link(Yii::t('app','Previous Academic Year Settings'),array('/previousYearSettings/create')) ?></h1>
                      </div>
                    </div>
                  </div>
                  <?php	
									}
									?>
                  
                  <!-- action messages -->
                  <?php
								Yii::app()->clientScript->registerScript(
									'myHideEffect',
									'$(".flashMessage").animate({opacity: 1.0}, 3000).fadeOut("slow");',
									CClientScript::POS_READY
								);
								?>
                  <?php
								/* Success Message */
								if(Yii::app()->user->hasFlash('success')): 
								?>
                  <div class="flashMessage" style="background:#FFF; color:#C00; padding-left:200px; font-size:16px"> <?php echo Yii::app()->user->getFlash('success'); ?> </div>
                  <?php endif;
								 /* End Success Message */
								?>
                  <?php
                                //Strings for the delete confirmation dialog.
                                $del_con = Yii::t('app', 'Are you sure you want to delete this class timing?');
                                $del_title=Yii::t('app', 'Delete Confirmation');
                                $del=Yii::t('app', 'Delete');
                                $cancel=Yii::t('app', 'Cancel');
                                ?>
                  <?php
                                $this->widget('zii.widgets.grid.CGridView', array(
                                'id' => 'class-timings-grid',
                                'dataProvider' => $model->search(),
                                'pager'=>array('cssFile'=>Yii::app()->baseUrl.'/css/formstyle.css'),
                                'cssFile' => Yii::app()->baseUrl . '/css/formstyle.css',
                                
                                'htmlOptions'=>array('class'=>'grid-view grid-view-table clear'),
                                'columns' => array(
                                
                                
                                'name',
                               // 'start_time',
								array(	
									'name'=>'start_time',
									'value'=>array($model,'startTime'),
									'filter'=>false
								),
                             //  'end_time',
								array(	
									'name'=>'end_time',
									'value'=>array($model,'endTime'),
									'filter'=>false
								),
                                array(
                                'name'=>'is_break',
                                'value'=>'$data->is_break ? "Yes" : "No"'
                                ),
                                
                                
                                array(
								'header'=>Yii::t('app','Action'),
                                'class' => 'CButtonColumn',
                                'buttons' => array(
									'class-timings_delete' => array(
									 'label' => Yii::t('app', 'Delete'), // text label of the button
									  'url' => '$data->id', // a PHP expression for generating the URL of the button
									  'imageUrl' =>Yii::app()->request->baseUrl .'/js_plugins/ajaxform/images/icons/cross.png', // image URL of the button.   If not set or false, a text link is used
									  'options' => array("class" => "fan_del", 'title' => Yii::t('app', 'Delete')), // HTML options for the button   tag
									  ),
								 'update' => array(
								 'label' => Yii::t('app', 'Update'), // text label of the button
								 'url'=>'Yii::app()->createUrl("timetable/classTiming/update", array("id"=>$_REQUEST["id"],"time_id"=>$data->id))',
								 'imageUrl' =>Yii::app()->request->baseUrl .'/js_plugins/ajaxform/images/icons/pencil.png', // image 
								 
								 'options' => array('title' => Yii::t('app', 'Update')), // HTML options for the    button tag
									),
								 'class-timings_view' => array(
								  'label' => Yii::t('app', 'View'), // text label of the button
								  'url' => '$data->id', // a PHP expression for generating the URL of the button
								  'imageUrl' =>Yii::app()->request->baseUrl .'/js_plugins/ajaxform/images/icons/properties.png', // image URL of the button.   If not set or false, a text link is used
								  'options' => array("class" => "fan_view", 'title' => Yii::t('app', 'View')), // HTML options for the    button tag
									)
								),
                                'template' => $template,
                                ),
                                ),
                                'afterAjaxUpdate'=>'js:function(id,data){$.bind_crud()}'
                                
                                                ));
                                
                                
                                ?>
                </div>
              </div>
            </div>
            <!-- END div class="emp_cntntbx" --> 
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
                url: "<?php echo Yii::app()->request->baseUrl;?>/index.php?r=timetable/classTiming/returnView",
                data:{"id":id,"YII_CSRF_TOKEN":"<?php echo Yii::app()->request->csrfToken;?>"},
                beforeSend : function() {
                    $("#class-timings-grid").addClass("ajax-sending");
                },
                complete : function() {
                    $("#class-timings-grid").removeClass("ajax-sending");
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
                url: "<?php echo Yii::app()->request->baseUrl;?>/index.php?r=timetable/classTiming/returnForm",
                data:{"update_id":id,"YII_CSRF_TOKEN":"<?php echo Yii::app()->request->csrfToken;?>"},
                beforeSend : function() {
                    $("#class-timings-grid").addClass("ajax-sending");
                },
                complete : function() {
                    $("#class-timings-grid").removeClass("ajax-sending");
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
                                $.fn.yiiGridView.update('class-timings-grid', {url:'<?php echo Yii::app()->request->getUrl()?>',data:{"ClassTimings_page":page}});
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
                url: "<?php echo Yii::app()->request->baseUrl;?>/index.php?r=timetable/classTiming/ajax_delete",
                data:{"id":id,"YII_CSRF_TOKEN":"<?php echo Yii::app()->request->csrfToken;?>"},
                    beforeSend : function() {
                    $("#class-timings-grid").addClass("ajax-sending");
                },
                complete : function() {
                    $("#class-timings-grid").removeClass("ajax-sending");
                },
                success: function(data) {
					window.location.reload();
                }//success
            });//ajax
        };//end of deletes

        dialogs[id] =
                        $('<div style="text-align:center;"></div>')
                        .html('<?php echo  $del_con; ?>')
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

    $('#add_class-timings ').bind('click', function() {
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->request->baseUrl;?>/index.php?r=timetable/classTiming/returnForm",
            data:{"batch_id":<?php echo $_GET['id'];?>,"YII_CSRF_TOKEN":"<?php echo Yii::app()->request->csrfToken;?>"},
                beforeSend : function() {
                    $("#class-timings-grid").addClass("ajax-sending");
                },
                complete : function() {
                    $("#class-timings-grid").removeClass("ajax-sending");
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


})//document ready
    
</script> 
