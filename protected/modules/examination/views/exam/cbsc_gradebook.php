<?php
$this->breadcrumbs=array(
	Yii::t('app','Examination')=>array('/examination'),
	Yii::t('app','CBSE Grade Book'),
);

$student_visible_fields   = FormFields::model()->getVisibleFields('Students', 'forStudentProfile');
?>

<script>
function displaytable() // Function to update mode dependent dropdown after selecting batch
{
	var course_id = document.getElementById('cid').value;
	var batch_id = document.getElementById('batchid').value;
	if(course_id == ''& batch_id == '')
	{
		$('#error').html('<?php echo Yii::t('app','select course'); ?>');
		return false;
	}
	else
	{
            var sem='';
            if($("#semester_id").is(":visible")){
                var semester_id = document.getElementById('semester_id').value;
                var sem =   '&sid='+semester_id;
            }
	window.location= 'index.php?r=examination/exam/cbsc&cid='+course_id+'&bid='+batch_id+sem;
	}
}
</script>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top"><?php $this->renderPartial('/default/left_side');?></td>
    <td valign="top"><!-- div class="cont_right" -->      
      <div class="cont_right">
        <h1><?php echo Yii::t('app','CBSE Grade Book');?></h1>
        
        <?php $form=$this->beginWidget('CActiveForm', array(
                    'id'=>'gradebook-form',
                    'enableAjaxValidation'=>false,
                    )); ?>
        <!-- DROP DOWNS -->
        <div class="formCon">
          <div class="formConInner">
          
                    <div class="txtfld-col-box">
            <div class="txtfld-col">
            <?php echo Yii::t('app','Select Course');?>
            <?php
			 
                    $model=new Courses;
                    $criteria = new CDbCriteria;
                    $criteria->compare('is_deleted',0); 
                    $current_academic_yr = Configurations::model()->findByPk(35);
                    $data = Courses::model()->findAllByAttributes(array('is_deleted'=>0,'academic_yr_id'=>$current_academic_yr->config_value),array('order'=>'id DESC'));  
                    echo CHtml::dropDownList('cid',(isset($_REQUEST['cid']))?$_REQUEST['cid']:'',CHtml::listData($data,'id','course_name'),array('prompt'=>Yii::t('app','Select'),'encode'=>false,
                                    'ajax' => array(
                                    'type'=>'POST',
                                    'url'=>CController::createUrl('exam/semesters'),
                                    'dataType'=>'JSON',
                                    'beforeSend'=>'js:function(){

                                                $("#semester_id").find("option").not(":first").remove();
                                                $("#batch_id").find("option").not(":first").remove();                                                                                                
                                                $("#sem_div").hide();
                                    }', 
                                    'success'=>'js:function(response){
                                    if(response.status=="success")
                                    {
                                        if(response.sem_status=="1")
                                        {
                                            $("#sem_div").show();
                                            $("#semester_id").html(response.semester);
                                        }
                                            $("#batchid").html(response.batch);
                                    }

                                    }',
                                    'data'=>'js:{cid:$(this).val(), id:"'.$_REQUEST['id'].'", "'.Yii::app()->request->csrfTokenName.'":"'.Yii::app()->request->csrfToken.'"}',
                                    ),
                                    
                                    //'style'=>'width:170px;',
                                    'id'=>'cid',
                                    'options' => array()));
					
                    ?>
            </div>
                <?php 
                $disp_status='none';
                if(isset($_REQUEST['cid']) && $_REQUEST['cid']!=NULL)
                {
                    $sem_enabled= Configurations::model()->isSemesterEnabledForCourse($_REQUEST['cid']);
                    if($sem_enabled==1)
                    {
                        $disp_status='block';
                    }
                }
                ?>        
            <div class="txtfld-col"  style="display:<?php echo $disp_status; ?>; padding-right: 10px" id="sem_div"> 
            <?php echo Yii::t('app','Select Semester');?>
                <?php   
                    if((isset($_REQUEST['cid']) && $_REQUEST['cid']!=NULL))
                    {
                        $criteria=new CDbCriteria;
                        $criteria->join= 'JOIN semester_courses `sc` ON t.id = `sc`.semester_id';
                        $criteria->condition='`sc`.course_id =:course_id';
                        $criteria->params=array(':course_id'=>$_REQUEST['cid']);
                        $data	= Semester::model()->findAll($criteria);			
                        $data	= CHtml::listData($data, 'id', 'name');
						$data_list = CMap::mergeArray(array(0=>Yii::t('app','Batch without semester')),$data);	
                    }
                    else
                    {
                        $data =  array();
                    }
                    echo CHtml::dropDownList('semester_id',(isset($_REQUEST['sid']))?$_REQUEST['sid']:'',$data_list,array('prompt'=>Yii::t('app','Select'),
													'encode'=>false,
                                                    'ajax' => array(
                                                    'type'=>'POST',
                                                    'url'=>CController::createUrl('exam/batches'),
                                                    'update'=>'#batchid',
                                                    'beforeSend'=>'js:function(){                                                                                               
                                                                $("#batchid").find("option").not(":first").remove();                                                                                                

                                                    }', 
                                                    'data'=>'js:{cid:$("#cid").val(), semester_id:$(this).val(), id:"'.$_REQUEST['id'].'", "'.Yii::app()->request->csrfTokenName.'":"'.Yii::app()->request->csrfToken.'"}',
                                                    ),                                                    
                                                    //'style'=>'width:170px;',
                                                    'id'=>'semester_id',
                                                    'options' => array()));
                    ?>
           </div>
           <div class="txtfld-col"> 
            <?php echo Yii::t('app','Select').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id");?>
                <?php  
                        if(isset($_REQUEST['bid']) and $_REQUEST['bid']!=NULL)
                        {
							
								if(isset($_POST['sid']) && ($_POST['sid']==''))
							{
									$batch_list = CHtml::listData(Batches::model()->findAllByAttributes(array('course_id'=>$_REQUEST['cid'],'is_active'=>1,'is_deleted'=>0)),'id','name');
									echo CHtml::dropDownList('batchid','',$batch_list,array('prompt'=>Yii::t('app','Select').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"),'encode'=>false,'id'=>'batchid','style'=>'width:190px;','onchange'=>'displaytable()', 'options'=>array($_REQUEST['bid']=>array('selected'=>true))));
							}
							else
							{ 
								if($_POST['sid']== 0)
								{
									$batch_list = CHtml::listData(Batches::model()->findAllByAttributes(array('course_id'=>$_REQUEST['cid'],'is_active'=>1,'is_deleted'=>0)),'id','name');
									echo CHtml::dropDownList('batchid','',$batch_list,array('prompt'=>Yii::t('app','Select').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"),'encode'=>false,'id'=>'batchid','style'=>'width:190px;','onchange'=>'displaytable()', 'options'=>array($_REQUEST['bid']=>array('selected'=>true))));
									
								}
								else
								{
										$batch_list = CHtml::listData(Batches::model()->findAllByAttributes(array('course_id'=>$_REQUEST['cid'],'semester_id'=>$_REQUEST['sid'],'is_active'=>1,'is_deleted'=>0)),'id','name');
										echo CHtml::dropDownList('batchid','',$batch_list,array('prompt'=>Yii::t('app','Select').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"),'encode'=>false,'id'=>'batchid','style'=>'width:190px;','onchange'=>'displaytable()', 'options'=>array($_REQUEST['bid']=>array('selected'=>true))));
								}
							}
                        }
                        else
                        {
                                echo CHtml::dropDownList('batchid','',array(),array('prompt'=>Yii::t('app','Select').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"),'encode'=>false,'id'=>'batchid','onchange'=>'displaytable()','style'=>'width:190px;'));
                        }
                                    
                    ?>
           </div>
           </div>
            
          </div>
        </div>
        <?php $this->endWidget(); ?>
        
    <?php   
    if(isset($_REQUEST['cid']) and isset($_REQUEST['bid']) && ExamFormat::model()->getExamformat($_REQUEST['bid'])==2)
    {           
                
		//$students = Students::model()->findAllByAttributes(array('batch_id'=>$_REQUEST['bid']));		
        ?>  
        <?php $this->beginWidget('CActiveForm', array(
				'id'=>'search-form',
				'method'=>'GET',
				'enableAjaxValidation'=>false,
				'action' => Yii::app()->createUrl('examination/exam/cbsc/', array('cid'=>$_REQUEST['cid'],'bid'=>$_REQUEST['bid']))
			)); ?>
        <div class="formCon">
        <div class="formConInner">
        <div class="txtfld-col-box">
<div class="txtfld-col">
<?php echo CHtml::label(Yii::t('app','Student Name')); ?>
<div style="position:relative;"><?php 

                                               $this->widget('zii.widgets.jui.CJuiAutoComplete',
                                                               array(
                                                                 'name'=>'name',
                                                                 'id'=>'name_widget',
                                                                 'source'=>$this->createUrl('/site/autocomplete'),
                                                              
																 'htmlOptions'=>array('placeholder'=>Yii::t('app','Student Name'),'style'=>'width:178px;'),
                                                                 'options'=>
                                                                        array(
                                                                                  'showAnim'=>'fold',
                                                                                  'select'=>"js:function(student, ui) {
                                                                                         $('#id_widget').val(ui.item.id);

                                                                                                        }"
                                                                                       ),
																					   

                                                               ));

                                                                ?>
                       <?php echo CHtml::hiddenField('student_id','',array('id'=>'id_widget')); ?>
                               <?php echo CHtml::ajaxLink('',array('/site/explorer','widget'=>'1'),array('update'=>'#explorer_handler'),array('id'=>'explorer_student_name','class'=>'exp_but-n'));?></div>
</div>
</div>
<div class="text-fild-block-full">
<?php echo CHtml::submitButton( Yii::t('app','Search'),array('name'=>'search','class'=>'formbut')); ?>
</div>
        </div>
        <?php $this->endWidget(); ?>
        
        <?php 
        if(!($students))
        {
                echo '<div class="listhdg" align="center">'.Yii::t('app','No Students Found!!').'</div>';
        }        
        else
        {
            ?>                                    
        
            
        
                <div style="width:97%" class="pdtab_Con">
                   
                    <table width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tbody>
                            <tr class="pdtab-h">
                                <td height="18" align="center"><?php echo Yii::t('app','Admission Number');?></td>
                                <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile"))
                                { ?>
                                <td align="center"><?php echo Yii::t('app','Student Name');?></td>
                                <?php } ?>  
                                <td align="center"><?php echo Yii::t('app','Manage');?></td>                                                                    
                            </tr>
                            <?php 
                            if(isset($_REQUEST['page']))
                            {
                            	$i=($pages->pageSize*$_REQUEST['page'])-9;
                            }
                            else
                            {
                            	$i=1;
                            }
                            $cls="even";
                            ?>

                            <?php foreach($students as $student)
                            { ?>    
                                <tr>
                                <td align="center"><?php echo $student->admission_no;?></td>
                                <?php
                                if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){
                                               ?>
                                <td align="center"><?php echo $student->studentFullName("forStudentProfile"); ?></td>
                                <td align="center"><?php echo CHtml::link(Yii::t("app", "View"), array("result", 'id'=>$student->id,'cid'=>$_REQUEST['cid'],'bid'=>$_REQUEST['bid']));?>
                                                    <?php //echo " | ".CHtml::link(Yii::t('app','Generate PDF'), array('/examination/exam/cbscpdf','id'=>$student->id),array('target'=>"_blank")); ?>
                                </td>
                                </tr>
                                <?php
                                }   
                                $i++;
                            }
                            ?>
                                
                        </tbody>                   		
                    </table>
                    
                    <div class="pagecon">
                                <?php                                          
                                  $this->widget('CLinkPager', array(
                                  'currentPage'=>$pages->getCurrentPage(),
                                  'itemCount'=>$item_count,
                                  'pageSize'=>$page_size,
                                  'maxButtonCount'=>5,
                                  //'nextPageLabel'=>'My text >',
                                  'header'=>'',
                                'htmlOptions'=>array('class'=>'pages'),
                                ));?>

                                </div> <!-- END div class="pagecon" 2 -->
                            <div class="clear"></div>
                </div>

            <?php }?>    
              </div>
            </td>
          
    <?php     
    } 
    else if(isset ($_REQUEST['bid']) && ExamFormat::model()->getExamformat($_REQUEST['bid'])!=2)
    {
        ?>
        <div class="formCon">
            <div class="formConInner">
                <center><?php echo Yii::t("app", "Cannot manage CBSE gradebook for this batch.") ?></center>
            </div>
        </div>
            <?php
    }
    ?>


</tr>
</table>