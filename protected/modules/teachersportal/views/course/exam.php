<style type="text/css">
.edit_bttns ul li {
    float: right;
    list-style: outside none none;
    margin: 0 0 15px;
}
</style>
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
	<?php $this->renderPartial('/default/leftside');?> 
    <?php    
	$student = Students::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
   /* $guard = Guardians::model()->findByAttributes(array('id'=>$student->parent_id));
    $settings=UserSettings::model()->findByAttributes(array('user_id'=>1));*/
	$exam_groups = ExamGroups::model()->findAllByAttributes(array('batch_id'=>$_REQUEST['id'],'is_published'=>1,'result_published'=>1));
	
	
	$model=new ExamGroups('search');
	//$model_2=new ExamGroups('createdby');
	$model->unsetAttributes();
	$model->batch_id=$_REQUEST['id'];
	
	//var_dump($model->search());exit;
    ?>
    
    <div class="pageheader">
      <h2><i class="fa fa-list-alt"></i> <?php echo Yii::t('app', 'My Course');?> <span><?php echo Yii::t('app', 'View courses here');?></span></h2>
      <div class="breadcrumb-wrapper">
        <span class="label"><?php echo Yii::t('app', 'You are here:');?></span>
        <ol class="breadcrumb">
          <!--<li><a href="index.html">Home</a></li>-->
         <li class="active"><?php echo Yii::t('app', 'Course');?></li>
        </ol>
      </div>
    </div>
    
    <div class="contentpanel">
<div class="col-sm-9 col-lg-12">
<div class="panel panel-default">

 <?php $this->renderPartial('changebatch');?>
<div class="panel-body">
            <?php $this->renderPartial('batch');?>
            <div class="edit_bttns" style="top:100px; right:25px">
                <ul>
                    <li>
                    <?php //echo CHtml::link('<span>'.Yii::t('teachersportal','My Courses').'</span>', array('/teachersportal/course'),array('class'=>'addbttn last'));?>
                    </li>
                </ul>
            </div>
            
            <!-- Examination Area -->
            <br />
            <div class="edit_bttns" style="z-index:10000;">
    <ul >
    <li>
    <?php echo CHtml::link(Yii::t('app','Create New'), array('/teachersportal/course/create','id'=>$_REQUEST['id'])); ?>
    </li>
    </ul>
    </div>
          <div style="padding:20px 0;">
           
          
    
     <div class="table-responsive">
        <?php
		
		 if($exam_groups->created_by==1)
		{
			$template_action='{update}{delete}{manage}';
		}
		else
		{
			$template_action='{manage}';
		}
		
		 $this->widget('zii.widgets.grid.CGridView', array(
         'id' => 'exam-groups-grid',
         'dataProvider' => $model->search(),
		// 'template'=>$data->created_by,
		 'pager'=>array('cssFile'=>Yii::app()->baseUrl.'/css/formstyle.css'),
 	     'cssFile' => Yii::app()->baseUrl . '/css/formstyle.css',
		
         
         'htmlOptions'=>array('class'=>'grid-view clear'),
          'columns' => array(
          	
		
		
		
		array('header'=>Yii::t('app', 'Name'),
                    'value'=>'$data->name',
                    
                ),
		
		'exam_type',
		
		/*array('header'=>'Subjects',
                    'value'=>array($model,'subjectname'),
                    'name'=> 'subject_id',
                ),*/
                

		array(
            'name'=>'is_published',
            'value'=>'$data->is_published ? Yii::t("app", "Yes") : Yii::t("app", "No")'
        ),
		array(
            'name'=>'result_published',
            'value'=>'$data->result_published ? Yii::t("app", "Yes") : Yii::t("app", "No")'
        ),
		//echo CHtml::link(Yii::t('Batch','Create New'), array('/teachersportal/course/create','id'=>$_REQUEST['id']))
		
		array(
		
		'class'=>'CButtonColumn',
		
		//'template'=> $data->created_by==Yii::app()->user->id ? "{update}{delete}{manage}" : "{manage}",
		'template'=>  "{update}{delete}{manage}",
		
		'buttons' => array(
               'manage' => array( //the name {reply} must be same
                 'label' => 'Manage', // text label of the button
                   'url' => 'CHtml::normalizeUrl(array("/teachersportal/exams/create",exam_group_id=>$data->id,id=>$_REQUEST[id]))', //Your URL According to your wish
                    //  'imageUrl' => Yii::app()->baseUrl . '/images/reply_mail_icon.png', // image URL of the button. If not set or false, a text link is used, The image must be 16X16 pixels
                   ),
				/* array(
               'update' => array( //the name {reply} must be same
                 'label' => 'Update', // text label of the button
                   'url' => 'CHtml::normalizeUrl(array("/teachersportal/exams/update",ids=>$data->id,id=>$_REQUEST[id]))', //Your URL According to your wish
                    //  'imageUrl' => Yii::app()->baseUrl . '/images/reply_mail_icon.png', // image URL of the button. If not set or false, a text link is used, The image must be 16X16 pixels
                   ), */ 
				   
				'update' =>array(
					'visible' => '$data->created_by==Yii::app()->user->id',
					'url' => 'CHtml::normalizeUrl(array("/teachersportal/course/update",ids=>$data->id,id=>$_REQUEST[id]))',
					
				),
				'delete' => array(
					'visible' => '$data->created_by==Yii::app()->user->id'
				)
               ),
		),
		
		
		
		
		/*array(
            'name'=>'',
            'value'=>'Edit'
			
        ),
		array(
            'name'=>'',
            'value'=>'Delete'
			
        ),
		
		array(
            'name'=>'Manage Exam',
            'value'=>'Manage'
			
        ),*/
		
		
		
    ),
           'afterAjaxUpdate'=>'js:function(id,data){$.bind_crud()}'

                                            ));


   ?> 
            
            
            
            
            
            
            
            
            
          
            
          </div>    <!-- END Examination Area -->
        </div> <!-- END div class="parentright_innercon" -->
    </div>
    </div>
    </div>
    </div> <!-- END div id="parent_rightSect" -->
    <div class="clear"></div>

<div class="clear"></div>

<script>
	$(".mcbrow").click(function(){
  		$(".portdtab_Con").toggle();
	});
</script>