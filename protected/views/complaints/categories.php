
<?php 
/**
 * Ajax Crud Administration
 * ComplaintCategories * index.php view file
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
	 Yii::t("app",'Manage Complaint Categories')
);
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top">
<div id="othleft-sidebar">
<?php $leftside = 'mailbox.views.default.left_side'; ?>	

<?php $this->renderPartial($leftside);?>

<?php /*?><?php $this->renderPartial('left_side');?><?php */?>
<?php /*?><?php $this->renderPartial('/default/left_side');?><?php */?>
  </div>
 </td>
 <td valign="top">
<div class="cont_right formWrapper">  

		<h1><?php echo Yii::t('app','Manage Complaint Category'); ?></h1>
  
	 
<div class="button-bg">
<div class="top-hed-btn-right">
<ul>                                    
<li>
</li>
<li>
</li>                                    
</ul>
</div> 
<div class="top-hed-btn-left">
<?php echo CHtml::link('<span>'.Yii::t('app','Create Complaint Category').'</span>', array('#'),array('id'=>'add_complaint-categories','class'=>'a_tag-btn')); ?>
</div>
</div>    
<?php  
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('complaint-categories-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<?php
//Strings for the delete confirmation dialog.
$del_con = Yii::t('app', 'Are you sure you want to delete this Complaint category? Note : It will delete the related complaints also.');
$del_title=Yii::t('app', 'Delete Confirmation');
 $del=Yii::t('app', 'Delete');
 $cancel=Yii::t('app', 'Cancel');
   ?>
<?php
    $this->widget('zii.widgets.grid.CGridView', array(
         'id' => 'complaint-categories-grid',
         'dataProvider' => $model->search(),
		 'pager'=>array('cssFile'=>Yii::app()->baseUrl.'/css/formstyle.css'),
         'cssFile' => Yii::app()->baseUrl . '/css/formstyle.css',
         'htmlOptions'=>array('class'=>'grid-view clear'),
          'columns' => array(
				array(						
					'name'=>'category',
					'value'=>array($model,'categoryName'),								
				),          
			

    			array(
					'header'=>'Actions',
                   'class' => 'CButtonColumn',
                    'buttons' => array(
                                                     'complaint-categories_delete' => array(
                                                     'label' => Yii::t('app', 'Delete'), // text label of the button
                                                      'url' => '$data->id', // a PHP expression for generating the URL of the button
                                                      'imageUrl' =>Yii::app()->request->baseUrl .'/js_plugins/ajaxform/images/icons/cross.png', // image URL of the button.   If not set or false, a text link is used
                                                      'options' => array("class" => "fan_del", 'title' => Yii::t('app', 'Delete')), // HTML options for the button   tag
                                                      ),
                                                     'complaint-categories_update' => array(
                                                     'label' => Yii::t('app', 'Update'), // text label of the button
                                                     'url' => '$data->id', // a PHP expression for generating the URL of the button
                                                     'imageUrl' =>Yii::app()->request->baseUrl .'/js_plugins/ajaxform/images/icons/pencil.png', // image URL of the button.   If not set or false, a text link is used
                                                     'options' => array("class" => "fan_update", 'title' => Yii::t('app', 'Update')), // HTML options for the    button tag
                                                        ),
                                                     'complaint-categories_view' => array(
                                                      'label' => Yii::t('app', 'View'), // text label of the button
                                                      'url' => '$data->id', // a PHP expression for generating the URL of the button
                                                      'imageUrl' =>Yii::app()->request->baseUrl .'/js_plugins/ajaxform/images/icons/properties.png', // image URL of the button.   If not set or false, a text link is used
                                                      'options' => array("class" => "fan_view", 'title' => Yii::t('app', 'View')), // HTML options for the    button tag
                                                        )
                                                    ),
                   'template' => '{complaint-categories_update}{complaint-categories_delete}',
            ),
    ),
           'afterAjaxUpdate'=>'js:function(id,data){$.bind_crud()}'

                                            ));


   ?>
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
                url: "<?php echo Yii::app()->createUrl('complaints/returnView');?>",
                data:{"id":id,"YII_CSRF_TOKEN":"<?php echo Yii::app()->request->csrfToken;?>"},
                beforeSend : function() {
                    $("#complaint-categories-grid").addClass("ajax-sending");
                },
                complete : function() {
                    $("#complaint-categories-grid").removeClass("ajax-sending");
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
                url: "<?php echo Yii::app()->createUrl('complaints/returnForm');?>",
                data:{"update_id":id,"YII_CSRF_TOKEN":"<?php echo Yii::app()->request->csrfToken;?>"},
                beforeSend : function() {
                    $("#complaint-categories-grid").addClass("ajax-sending");
                },
                complete : function() {
                    $("#complaint-categories-grid").removeClass("ajax-sending");
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
									window.location.reload();
                                   /*var page=$("li.selected  > a").text();
                                	$.fn.yiiGridView.update('complaint-categories-grid', {url:'',data:{"ComplaintCategories_page":page}});*/
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
                url: "<?php echo Yii::app()->createUrl('complaints/ajax_delete');?>",
                data:{"id":id,"YII_CSRF_TOKEN":"<?php echo Yii::app()->request->csrfToken;?>"},
                    beforeSend : function() {
                    $("#complaint-categories-grid").addClass("ajax-sending");
                },
                complete : function() {
                    $("#complaint-categories-grid").removeClass("ajax-sending");
                },
                success: function(data) {
					window.location.reload();
                    /*var res = jQuery.parseJSON(data);
                     var page=$("li.selected  > a").text();
                    $.fn.yiiGridView.update('complaint-categories-grid', {url:'',data:{"ComplaintCategories_page":page}});*/
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

    $('#add_complaint-categories ').bind('click', function() {
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('complaints/returnForm');?>",
            data:{"YII_CSRF_TOKEN":"<?php echo Yii::app()->request->csrfToken;?>"},
                beforeSend : function() {
                    $("#complaint-categories-grid").addClass("ajax-sending");
                },
                complete : function() {
                    $("#complaint-categories-grid").removeClass("ajax-sending");
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
                                   /*var page=$("li.selected  > a").text();
                                $.fn.yiiGridView.update('complaint-categories-grid', {url:'',data:{"ComplaintCategories_page":page}});*/
                            } //onclosed function
                        });//fancybox
            } //success
        });//ajax
        return false;
    });//bind


})//document ready
    
</script>
