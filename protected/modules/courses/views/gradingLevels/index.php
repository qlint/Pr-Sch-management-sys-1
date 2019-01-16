
<?php 
/**
 * Ajax Crud Administration
 * GradingLevels * index.php view file
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
	 Yii::t('app','Manage Grading Levels')
);
?>
<?php  
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('grading-levels-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<div style="background:#FFF; min-height:800px;">
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
    
    
        
    <!--<div class="edit_bttns">
    <ul>
    <li>
    <a class=" edit last" href="#">Edit</a>    </li>
    </ul>
    </div>-->
    
    
    <div class="clear"></div>
    <div class="emp_right_contner">
    <div class="emp_tabwrapper">
     <?php $this->renderPartial('/batches/tab');?>
        
    <div class="clear"></div>
    <div class="emp_cntntbx" style="padding-top:10px;">
    
<div  style="position:relative">
    <div class="edit_bttns" style="width:125px; top:35px; right:-10px;">
    <ul>
    <li>
    <?php echo CHtml::link('<span>'.Yii::t('app','Assessments').'</span>', array('/courses/exam','id'=>$_REQUEST['id']),array('class'=>'addbttn last'));?>
    </li>
    </ul>
    <div class="clear"></div>
    </div>
    </div>
    <div >

<div > 
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
				
				
				$template = '{grading-levels_view}';
				if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_edit->settings_value!=0))
				{
					$template = $template.'{grading-levels_update}';
				}
				
				if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_delete->settings_value!=0))
				{
					$template = $template.'{grading-levels_delete}';
				}
					
				?>

<h3><?php echo Yii::t('app','Grading Levels');?></h3>

<div class="right" align="left">
               
              <?php
			if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_create->settings_value!=0))
			{
				echo CHtml::link(Yii::t('app','Create Grading Levels'), array('#'),array('id'=>'add_grading-levels','class'=>'cbut'));
			}
			?>
            <?php 
			if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_insert->settings_value!=0))
			{
			echo CHtml::link(Yii::t('app','Set Default Grading Levels'), array('gradingLevels/default','id'=>$_REQUEST['id']),array('class'=>'cbut','confirm'=>Yii::t('app','Are You Sure? All custom settings will be deleted.')));
			}
			?>
   </div><div id="success_flash" align="center" style=" color:#F00; display:none;"><h4><?php echo Yii::t('app','Selected Grading Level Deleted Successfully!');?></h4>
 
   </div>

<?php
//Strings for the delete confirmation dialog.
$del_con = Yii::t('app', 'Are you sure you want to delete this grading level?');
$del_title=Yii::t('app', 'Delete Confirmation');
 $del=Yii::t('app', 'Delete');
 $cancel=Yii::t('app', 'Cancel');
 
   ?>
<?php
    $this->widget('zii.widgets.grid.CGridView', array(
         'id' => 'grading-levels-grid',
         'dataProvider' => $model->search(),
        /* 'filter' => CHtml::listData($model->findAll("id=:x", array(':x'=>1)), 'id', 'name'),*/
		 'pager'=>array('cssFile'=>Yii::app()->baseUrl.'/css/formstyle.css'),
 		 'cssFile' => Yii::app()->baseUrl . '/css/formstyle.css',
         'htmlOptions'=>array('class'=>'grid-view clear'),
          'columns' => array(
          		/*'id',*/
		'name',
		/*'batch_id',*/
		'min_score',
		/*'order',
		'is_deleted',*/
		/*
		'created_at',
		'updated_at',
		*/

    array(
                   'class' => 'CButtonColumn',
                    'buttons' => array(
                                                     'grading-levels_delete' => array(
                                                     'label' => Yii::t('app', 'Delete'), // text label of the button
                                                      'url' => '$data->id',
													   // a PHP expression for generating the URL of the button
                                                      'imageUrl' =>Yii::app()->request->baseUrl .'/js_plugins/ajaxform/images/icons/cross.png', // image URL of the button.   If not set or false, a text link is used
													  
                                                      'options' => array("class" => "fan_del",'title' => Yii::t('app', 'Delete')), // HTML options for the button   tag
                                                      ),
                                                     'grading-levels_update' => array(
                                                     'label' => Yii::t('app', 'Update'), // text label of the button
                                                     'url' => '$data->id', // a PHP expression for generating the URL of the button
                                                     'imageUrl' =>Yii::app()->request->baseUrl .'/js_plugins/ajaxform/images/icons/pencil.png', // image URL of the button.   If not set or false, a text link is used
                                                     'options' => array("class" => "fan_update", 'title' => Yii::t('app', 'Update')), // HTML options for the    button tag
                                                        ),
                                                     'grading-levels_view' => array(
                                                      'label' => Yii::t('app', 'View'), // text label of the button
                                                      'url' => '$data->id', // a PHP expression for generating the URL of the button
                                                      'imageUrl' =>Yii::app()->request->baseUrl .'/js_plugins/ajaxform/images/icons/properties.png', // image URL of the button.   If not set or false, a text link is used
                                                      'options' => array("class" => "fan_view", 'title' => Yii::t('app', 'View')), // HTML options for the    button tag
                                                        )
                                                    ),
                   'template' => $template,
				   
				   'header'=>Yii::t('app','Actions'),
				   'headerHtmlOptions'=>array('style'=>'color:#FF6600')
            ),
    ),
           'afterAjaxUpdate'=>'js:function(id,data){$.bind_crud()}'

                                            ));


   ?>
   </div></div></div></div></div></div></td></tr></table></div>
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
                url: "<?php echo Yii::app()->request->baseUrl;?>/index.php?r=courses/gradingLevels/returnView",
                data:{"id":id,"YII_CSRF_TOKEN":"<?php echo Yii::app()->request->csrfToken;?>"},
                beforeSend : function() {
                    $("#grading-levels-grid").addClass("ajax-sending");
                },
                complete : function() {
                    $("#grading-levels-grid").removeClass("ajax-sending");
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
                url: "<?php echo Yii::app()->request->baseUrl;?>/index.php?r=courses/gradingLevels/returnForm",
                data:{"update_id":id,"YII_CSRF_TOKEN":"<?php echo Yii::app()->request->csrfToken;?>"},
                beforeSend : function() {
                    $("#grading-levels-grid").addClass("ajax-sending");
                },
                complete : function() {
                    $("#grading-levels-grid").removeClass("ajax-sending");
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
                                $.fn.yiiGridView.update('grading-levels-grid', {url:'<?php echo Yii::app()->request->getUrl()?>',data:{"GradingLevels_page":page}});
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
                url: "<?php echo Yii::app()->request->baseUrl;?>/index.php?r=courses/gradingLevels/ajax_delete",
                data:{"id":id,"YII_CSRF_TOKEN":"<?php echo Yii::app()->request->csrfToken;?>"},
                    beforeSend : function() {
                    $("#grading-levels-grid").addClass("ajax-sending");
                },
                complete : function() {
                    $("#grading-levels-grid").removeClass("ajax-sending");
                },
                success: function(data) {
					//alert("ttt");
                    var res = jQuery.parseJSON(data);
					 var del=res['msg'];
					
                     var page=$("li.selected  > a").text();
                    $.fn.yiiGridView.update('grading-levels-grid', {url:'<?php echo Yii::app()->request->getUrl()?>',data:{"GradingLevels_page":page}});
                }//success
            });//ajax
        };//end of deletes

        dialogs[id] =
                        $('<div style="text-align:center;"></div>')
                        .html('<?php echo  $del_con; ?><br><br>' + '<h2 style="color:#999999"></h2>')
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
																	$("#success_flash").css("display","block").animate({opacity: 1.0}, 3000).fadeOut("slow");
																	   
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

    $('#add_grading-levels ').bind('click', function() {
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->request->baseUrl;?>/index.php?r=courses/gradingLevels/returnForm",
            data:{"batch_id":<?php echo $_GET['id'];?>,"YII_CSRF_TOKEN":"<?php echo Yii::app()->request->csrfToken;?>"},
                beforeSend : function() {
                    $("#grading-levels-grid").addClass("ajax-sending");
                },
                complete : function() {
                    $("#grading-levels-grid").removeClass("ajax-sending");
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


})//document ready
    
</script>
