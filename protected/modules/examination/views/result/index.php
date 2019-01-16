<?php
$this->breadcrumbs=array(
	Yii::t('app','Examination')=>array('/examination'),
	Yii::t('app','Result'),
);
 $form=$this->beginWidget('CActiveForm', array(
	'id'=>'result-form',
	'enableAjaxValidation'=>false,
	'method' => 'GET',
	'action'=>CController::createUrl('/examination/result/index')
	
)); ?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top"><?php $this->renderPartial('/default/left_side');?></td>
    <td valign="top"><div class="cont_right">
        <h1><?php echo Yii::t('app','Search Results');?></h1>
        <div class="formCon">
          <div class="formConInner">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="s_search">
              <tr>
                <td width="24%"><?php echo  Yii::t('app','Choose Search Option');?></td>
                <td  width="40%"><?php
                $academic_yrs = AcademicYears::model()->findAll("is_deleted =:x", array(':x'=>0));
                $academic_yr_options = CHtml::listData($academic_yrs,'id','name');
                ?>
                  <?php
                 echo CHtml::dropDownList('search_id','',array('1'=>Yii::t('app','Course'),'2'=>Yii::t('app','Student')),array('prompt'=>Yii::t('app','Select'),'style'=>'width:px;','onchange'=>'getsearch()','id'=>'search_id','options'=>array($_REQUEST['search_id']=>array('selected'=>true))));	?></td>
              </tr>
              <tr>
                <td >&nbsp;</td>
                <td >&nbsp;</td>
                <td >&nbsp;</td>
                <td >&nbsp;</td>
              </tr>
              <?php
            
                if($_REQUEST['search_id']!=NULL and $_REQUEST['search_id']==1)
                {
                    $batch_style = "display:table-row";
                    $course_style = "display:none";
                    
                    
                }
                elseif($_REQUEST['search_id']!=NULL and $_REQUEST['search_id']==2)
                {
                    $batch_style = "display:none";
                    $course_style = "display:table-row";
                    
                    
                }
                else
                {
                    $batch_style = "display:none";
                    $course_style = "display:none";
                }
                ?>
              <tr style=" <?php echo $batch_style; ?> ">
                <td ><?php echo Yii::t('app','Course');?></td>
                <td ><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id");?></td>
                <td ><?php echo Yii::t('app','Exam Name');?></td>
                <td ><?php echo Yii::t('app','Subject');?></td>
              </tr>
              <tr id="batch_data" style=" <?php echo $batch_style; ?> ">
                <?php 
				 
					if($year_id==NULL)
					{
						$current_academic_yr = Configurations::model()->findByPk(35);
						$year_id  = $current_academic_yr->config_value;					
					}
                  $criteria  = new CDbCriteria;                  
                  $criteria ->condition = 'academic_yr_id =:academic_yr';
				  $criteria->params = array(':academic_yr'=>$year_id);
				  $criteria ->compare('is_deleted', 0);                 
                  ?>
                <td width="24%"><?php 
				
					$course_names =CHtml::listData(Courses::model()->findAll($criteria),'id','course_name'); 
					$course_list = CMap::mergeArray(array(0=>Yii::t('app','All')),$course_names);
                    echo CHtml::dropDownList('course','',$course_list,array(
                    'ajax' => array(
                    'type'=>'POST',
                    'url'=>CController::createUrl('/examination/result/batch'),
                    'update'=>'#batch_id',
                    'data'=>'js:{course:$(this).val(), "'.Yii::app()->request->csrfTokenName.'":"'.Yii::app()->request->csrfToken.'"}',),'style'=>'width:150px;','options' => array($course=>array('selected'=>true),)));
                
                ?></td>
                <td width="24%"><?php
					$batch_names =CHtml::listData(Batches::model()->findAll('academic_yr_id=:id AND is_active=:x AND is_deleted=:y AND course_id=:z',array(':id'=>$year_id,':x'=>1,':y'=>0,':z'=>$_REQUEST['course'])),'id','name'); 
					$batch_list = CMap::mergeArray(array(0=>Yii::t('app','All')),$batch_names);
                    echo CHtml::dropDownList('batch_id','',$batch_list,array(
                    'ajax' => array(
                    'type'=>'POST',
                    'url'=>CController::createUrl('/examination/result/group'),
                    'update'=>'#group_id',
                    'data'=>'js:{batch_id:$(this).val(), "'.Yii::app()->request->csrfTokenName.'":"'.Yii::app()->request->csrfToken.'"}',),'style'=>'width:150px;','options' => array($batch=>array('selected'=>true))));
                   ?></td>
                <td width="24%"><?php
					$group_names =CHtml::listData(ExamGroups::model()->findAll('batch_id=:x AND is_published=:y',array(':x'=>$_REQUEST['batch_id'],':y'=>1)),'id','name'); 
					$group_list = CMap::mergeArray(array(0=>Yii::t('app','All')),$group_names);
                    echo CHtml::dropDownList('group_id','',$group_list,array(
                    'ajax' => array(
                    'type'=>'POST',
                    'url'=>CController::createUrl('/examination/result/exam'),
                    'update'=>'#exam_id',
                    'data'=>'js:{group_id:$(this).val(), "'.Yii::app()->request->csrfTokenName.'":"'.Yii::app()->request->csrfToken.'"}',),'style'=>'width:150px;','options' => array($group=>array('selected'=>true))));
                   ?></td>
                <td width="24%"><?php
					$exam_id =$_REQUEST['exam_id'];
					$exams=Exams::model()->findAll('exam_group_id=:x',array(':x'=>$_REQUEST['group_id']));
					$subject_id=array();
					foreach($exams as $exam)
					{
					$subject_id[] = $exam->subject_id;
					}
					$criteria  = new CDbCriteria;
					$criteria ->compare('is_deleted',0);
					$criteria->addInCondition('id',$subject_id);
				
					$exam_names =CHtml::listData(Subjects::model()->findAll($criteria),'id','name'); 
					$exam_list = CMap::mergeArray(array(0=>Yii::t('app','All')),$exam_names);
                    echo CHtml::dropDownList('exam_id','',$exam_list,array(
                    'style'=>'width:150px;','options' =>array($exam_id=>array('selected'=>true))));
                   ?></td>
              </tr>
              <tr id="student_data" style=" <?php echo $course_style; ?> ">
                <td><strong><?php echo Yii::t('app','Name');?></strong></td>
                <td width="44%"><div style="position:relative;" class="exmresult=xbtn" >
                    <?php  $this->widget('zii.widgets.jui.CJuiAutoComplete',
                                    array(
                                    'name'=>'name',
                                    'id'=>'name_widget',
                                    'source'=>$this->createUrl('/site/autocomplete'),
                                    'htmlOptions'=>array('placeholder'=>Yii::t('app','Student Name'),'style'=>'width:80%;'),
                                    'options'=>
                                    array(
                                    'showAnim'=>'fold',
                                    'select'=>"js:function(student, ui) {
                                    $('#id_widget').val(ui.item.id);
                                    
                                    }"
                                    ),
                                    
                                    ));
                                    ?>
                    <?php echo CHtml::hiddenField('student_id','',array('id'=>'id_widget')); ?> <?php echo CHtml::ajaxLink('',array('/site/explorer','widget'=>'1'),array('update'=>'#explorer_handler'),array('id'=>'explorer_student_name','class'=>'exp_but-n'));?> </div>
                  <div id="std_name_error" style="color:#F00"></div></td>
                <td></td>
                <td></td>
              </tr>
            </table>
            <?php echo CHtml::hiddenField('search_button','',array('name'=>'search_button')); ?>
            <div style="margin-top:10px;"><?php echo CHtml::submitButton(Yii::t('app','Search'),array('name'=>'','class'=>'formbut','id'=>'search_btn_id')); ?></div>
          </div>
        </div>
        <div>
          <?php
		if($flag==1)
		{
		$this->renderPartial('/result/_all',array('score'=>$score,
				'lists'=>$list,
				'search_id'=>$search_id,
				'course'=>$course,
				'batch'=>$batch,
				'group'=>$group,
				'exam'=>$exam1,
				'pages' => $pages,
				'item_count'=>$item_count,
				'page_size'=>$page_size,
				 'flag'=>$flag,
				));
		//$this->renderPartial('/result/_all',array('lists'=>$score,'search_id'=>$search_id,'course'=>$course,'batch'=>$batch,'group'=>$group,'exam'=>$exam1));
		
		}
		if($flag==2)
		{    
			$this->renderPartial('/result/studentexam',array('model'=>$model,'student'=>$student,
				'list'=>$list,
				'pages' => $pages,
				'item_count'=>$item_count,
				'page_size'=>$page_size,
				));
		}
		?>
        </div>
        <br />


        
        
        
      </div></td>
  </tr>
</table>
<?php $this->endWidget(); ?>
<script language="javascript">
function getsearch() // Function to get course and update the search form
{
	var serach_id = document.getElementById('search_id').value;	
	window.location= 'index.php?r=examination/result/index&search_id='+serach_id;
	
}

$('#search_btn_id').click(function(ev){
	$('#std_name_error').html('');
	var search_value = $('#search_id').val();
	var student_name = $('#name_widget').val();
	
	if(student_name=='' && search_value == 2){
		$('#std_name_error').html('<?php echo "Cannot be blank"; ?>');
		return false;
	}
});

</script>