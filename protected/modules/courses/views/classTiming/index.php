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
?><?php
 $this->breadcrumbs=array(
	 Yii::t('app','Manage Class Timings')
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
<style>
.container
{
	background:#FFF;
}
</style>
<div style="background:#FFF;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
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
                
                
                <div class="clear"></div>
                    <div class="emp_right_contner">
                        <div class="emp_tabwrapper">
                        	<?php $this->renderPartial('/batches/tab');?>
                        	<div class="clear"></div>
                            <div class="emp_cntntbx">

<div class="button-bg">
<div class="top-hed-btn-left"> </div>
<div class="top-hed-btn-right">
 <ul>
                                        <li>
                                        <?php echo CHtml::link('<span>'.Yii::t('app','Time Table').'</span>', array('/courses/weekdays/timetable','id'=>$_REQUEST['id']),array('class'=>'a_tag-btn'));?>
                                        </li>
                                        
                                        </ul>
</div> 

</div>
                                
                                
                                <div style="width:100%">
                                    <div>
                                        <h3><?php echo Yii::t('app','Class Timings');?></h3>
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
										$is_edit = PreviousYearSettings::model()->findByAttributes(array('id'=>3));
										$is_delete = PreviousYearSettings::model()->findByAttributes(array('id'=>4));
										
										$template = '{class-timings_view}';
										if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_edit->settings_value!=0))
										{
											$template = $template.'{update}';
										}
										
										if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_delete->settings_value!=0))
										{
											$template = $template.'{class-timings_delete}';
										}
										
										if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_create->settings_value!=0))
										{
										?>
										<div class="" align="left">
                                        	<?php echo CHtml::link(Yii::t('app','Create Class Timings'), array('/courses/classTiming/create','id'=>$_REQUEST['id']),array('class'=>'a_tag-btn')) ?>
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
                                            <div class="flashMessage" style="background:#FFF; color:#C00; padding-left:200px; font-size:16px">
                                            <?php echo Yii::app()->user->getFlash('success'); ?>
                                            </div>
                                        <?php endif;
                                         /* End Success Message */
                                        ?>
                                        
                                        <?php
                                        //Strings for the delete confirmation dialog.
                                        $del_con = Yii::t('app', 'Are you sure you want to delete?');
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
                                             
                                             'htmlOptions'=>array('class'=>'grid-view clear'),
                                              'columns' => array(
                                                    
                                            
                                            'name',
                                             array(            // display 'create_time' using an expression
                                                'name'=>'start_time',
                                                'type'=>'raw',
                                                'value'=>'ClassTimingController::convertTime($data->start_time)',
                                        
                                            ),
                                             array(            // display 'create_time' using an expression
                                                'name'=>'end_time',
                                                'type'=>'raw',
                                                'value'=>'ClassTimingController::convertTime($data->end_time)',
                                        
                                            ),
                                            array(
                                                'name'=>'is_break',
                                                'value'=>'$data->is_break ? "'.Yii::t('app','Yes').'" : "'.Yii::t('app','No').'"'
                                            ),
                                            
                                        
											array(
														   'class' => 'CButtonColumn',
                                                            'header'=>Yii::t('app','Action'),
															'buttons' => array(
																		 'class-timings_delete' => array(
																		 'label' => Yii::t('app', 'Delete'), // text label of the button
																		  'url' => '$data->id', // a PHP expression for generating the URL of the button
																		  'imageUrl' =>Yii::app()->request->baseUrl .'/js_plugins/ajaxform/images/icons/cross.png', // image URL of the button.   If not set or false, a text link is used
																		  'options' => array("class" => "fan_del", 'title' => Yii::t('app', 'Delete')), // HTML options for the button   tag
																		  ),
																		 'update' => array(
																		 'imageUrl' =>Yii::app()->request->baseUrl .'/js_plugins/ajaxform/images/icons/pencil.png',
																		 'label' => Yii::t('app', 'Update'), // text label of the button
																		 'url'=>'Yii::app()->createUrl("courses/classTiming/update", array("id"=>$_REQUEST["id"],"time_id"=>$data->id))',
																		 
																		 'options' => array('class'=>'','title' => Yii::t('app', 'Update')), // HTML options for the    button tag
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
                            </div> <!-- END div class="emp_cntntbx" -->
                        </div> <!-- END div class="emp_tabwrapper" -->
                    </div> <!-- END div class="emp_right_contner" -->
                </div>
            </td>
        </tr>
    </table>
</div>
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
                url: "<?php echo Yii::app()->request->baseUrl;?>/index.php?r=courses/classTiming/returnView",
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
                url: "<?php echo Yii::app()->request->baseUrl;?>/index.php?r=courses/classTiming/returnForm",
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
                url: "<?php echo Yii::app()->request->baseUrl;?>/index.php?r=courses/classTiming/ajax_delete",
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
            url: "<?php echo Yii::app()->request->baseUrl;?>/index.php?r=courses/classTiming/returnForm",
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
