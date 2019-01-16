<style>
	.items td{
		 text-align:center;
	}
</style>
<div id="parent_Sect">
	<?php $this->renderPartial('/default/leftside');?> 
    <?php    
	$student = Students::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
    if($_REQUEST['id']!="" && ExamFormat::model()->getExamformat($_REQUEST['id'])== 2){ 
		$model=new CbscExamGroup17('search');
		$exam_groups = CbscExamGroup17::model()->findAllByAttributes(array('batch_id'=>$_REQUEST['id'],'date_published'=>1,'result_published'=>1));
	
	}
	else{
		$model=new ExamGroups('search');
		$exam_groups = ExamGroups::model()->findAllByAttributes(array('batch_id'=>$_REQUEST['id'],'is_published'=>1,'result_published'=>1));
	}
	
	$model->unsetAttributes();
	$model->batch_id=$_REQUEST['id'];
	
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
            <!-- Examination Area -->
        <div class="table-responsive">
 <?php if($_REQUEST['id']!="" && ExamFormat::model()->getExamformat($_REQUEST['id'])== 2){ ?>		   
        <?php
		
		 $this->widget('zii.widgets.grid.CGridView', array(
         'id' => 'exam-groups-grid',
         'dataProvider' => $model->search(),
		 'pager'=>array('cssFile'=>Yii::app()->baseUrl.'/css/formstyle.css'),
 	     'cssFile' => Yii::app()->baseUrl . '/css/formstyle.css',
         'htmlOptions'=>array('class'=>'grid-view clear'),
          'columns' => array(
		  
		
		array('header'=>Yii::t('app', 'Name'),
                    'value'=>'$data->name',
                    
                ),
		
		
		array('header'=>Yii::t('app', 'Subjects'),
                    'value'=>array($model,'subjectname'),
                    'name'=> 'subject_id',
                ),
                

		array(
            'name'=>'date_published',
            'value'=>'$data->date_published ? Yii::t("app", "Yes") : Yii::t("app", "No")'
        ),
		array(
            'name'=>'result_published',
            'value'=>'$data->result_published ? Yii::t("app", "Yes") : Yii::t("app", "No")'
        ),
    ),
           'afterAjaxUpdate'=>'js:function(id,data){$.bind_crud()}')); 
    	 ?> 
		   
<?php } 
	else{
    	  $this->widget('zii.widgets.grid.CGridView', array(
         'id' => 'exam-groups-grid',
         'dataProvider' => $model->search(),
		 'pager'=>array('cssFile'=>Yii::app()->baseUrl.'/css/formstyle.css'),
 	     'cssFile' => Yii::app()->baseUrl . '/css/formstyle.css',
         'htmlOptions'=>array('class'=>'grid-view clear'),
          'columns' => array(
		  
		
		array('header'=>Yii::t('app', 'Name'),
                    'value'=>'$data->name',
                    
                ),
		array(
				'header'=>Yii::t('app', 'Assessment Type'),
				'name'=> 'exam_type',),
		
		array('header'=>Yii::t('app', 'Subjects'),
                    'value'=>array($model,'subjectname'),
                    'name'=> 'subject_id',
                ),
                

		array(
            'name'=>'is_published',
            'value'=>'$data->is_published ? Yii::t("app", "Yes") : Yii::t("app", "No")'
        ),
		array(
            'name'=>'result_published',
            'value'=>'$data->result_published ? Yii::t("app", "Yes") : Yii::t("app", "No")'
        ),
    ),
           'afterAjaxUpdate'=>'js:function(id,data){$.bind_crud()}')); ?> 
<?php } ?>
         
        </div>    
        <div class="clear"></div>
        </div>    <!-- END Examination Area -->
        </div> <!-- END div class="parentright_innercon" -->
    </div>
    </div> <!-- END div id="parent_rightSect" -->
    
</div> <!-- END div id="parent_Sect" -->
<div class="clear"></div>

<script>
	$(".mcbrow").click(function(){
  		$(".portdtab_Con").toggle();
	});
</script>