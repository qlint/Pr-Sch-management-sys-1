<?php
$this->breadcrumbs=array(
	Yii::t('app','Report')=>array('/report'),
	Yii::t('app','CBSE Report'),
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
	window.location= 'index.php?r=report/default/cbscreport&cid='+course_id+'&bid='+batch_id;
	}
}
</script>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top"><?php $this->renderPartial('/default/left_side');?></td>
    <td valign="top"><!-- div class="cont_right" -->      
      <div class="cont_right">
        <h1><?php echo Yii::t('app','CBSE Report');?></h1>
        
        <?php $form=$this->beginWidget('CActiveForm', array(
                    'id'=>'cbscreport-form',
                    'enableAjaxValidation'=>false,
                    )); ?>
        <!-- DROP DOWNS -->
        <div class="formCon">
          <div class="formConInner">
            <table width="90%" border="0" cellspacing="0" cellpadding="0" class="s_search">
              <tr>
                <td>&nbsp;</td>
                <td style="width:200px;"><strong><?php echo Yii::t('app','Select Course');?></strong></td>
                <td>&nbsp;</td>
                
                
                <?php
                    $model=new Courses;
                    $criteria = new CDbCriteria;
                    $criteria->compare('is_deleted',0); ?>
                    <td><?php
                            $current_academic_yr = Configurations::model()->findByPk(35);
                            $data = Courses::model()->findAllByAttributes(array('is_deleted'=>0,'academic_yr_id'=>$current_academic_yr->config_value),array('order'=>'id DESC'));  
									
                            echo CHtml::dropDownList('cid','',CHtml::listData($data,'id','course_name'),array('encode'=>false,'prompt'=>Yii::t('app','Select Course'),'style'=>'width:190px;','options'=>array($_REQUEST['cid']=>array('selected'=>true)),
                            'ajax' => array(
                            'type'=>'POST',
                            'url'=>CController::createUrl('/report/default/batchname'),
                                                                'success' => 'function(data){
                                                                        $("#subjectid").html("<option value=\"\">Select Subject</option>");
                                                                        $("#batchid").html(data); 
                                                                }',

                                                                )));																		
                            ?>
                    </td>
              </tr>
              <tr>
                <td colspan="4">&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td><strong><?php echo Yii::t('app','Select').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id");?></strong></td>
                <td>&nbsp;</td>
                <td><?php  
                        if(isset($_REQUEST['bid']) and $_REQUEST['bid']!=NULL)
                        {
                                $batch_list = CHtml::listData(Batches::model()->findAllByAttributes(array('course_id'=>$_REQUEST['cid'],'is_active'=>1,'is_deleted'=>0)),'id','name');
                                echo CHtml::dropDownList('batchid','',$batch_list,array('encode'=>false,'prompt'=>Yii::t('app','Select').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"),'id'=>'batchid','style'=>'width:190px;','onchange'=>'displaytable()', 'options'=>array($_REQUEST['bid']=>array('selected'=>true))));
                        }
                        else
                        {
                                echo CHtml::dropDownList('batchid','',array(),array('encode'=>false,'prompt'=>Yii::t('app','Select').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"),'id'=>'batchid','onchange'=>'displaytable()','style'=>'width:190px;'));
                        }
                                    
                    ?></td>
              </tr>
              <tr>
                <td colspan="4">&nbsp;</td>
              </tr>                            
            </table>
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
				'action' => Yii::app()->createUrl('report/default/cbscreport/', array('cid'=>$_REQUEST['cid'],'bid'=>$_REQUEST['bid']))
			)); ?>
        <div class="formCon">
        <div class="formConInner">
        <table width="60%" border="0" cellspacing="0" cellpadding="0">                
                 <tr> 
                     <td><?php echo CHtml::label(Yii::t('app','Student Name')); ?></td>
                   <td>&nbsp;</td>
                   <td><div style="position:relative; width:180px" ><?php 

                                               $this->widget('zii.widgets.jui.CJuiAutoComplete',
                                                               array(
                                                                 'name'=>'name',
                                                                 'id'=>'name_widget',
                                                                 'source'=>$this->createUrl('/site/autocomplete'),
                                                                 'htmlOptions'=>array('placeholder'=>''),
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
                              </div></td>
                 </tr>

                 <tr>
                               <td><div style="margin-top:10px;"><?php echo CHtml::submitButton( Yii::t('app','Search'),array('name'=>'search','class'=>'formbut')); ?></div> </td>
                         </tr>   


               </table>
        </div></div>
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
                                <td align="center"><?php echo CHtml::link(Yii::t("app", "View"), array("viewreport", 'id'=>$student->id ,'cid'=>$_REQUEST['cid'],'bid'=>$_REQUEST['bid']));?>
                                 <?php /*?> <?php echo " | ".CHtml::link(Yii::t('app','Generate PDF'), array('/report/default/cbscpdf','id'=>$student->id),array('target'=>"_blank")); ?><?php */?>
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
                <center><?php echo Yii::t("app", "Cannot manage CBSE Report for this batch.") ?></center>
            </div>
        </div>
            <?php
    }
    ?>


</tr>
</table>