<div class="formCon">

<div class="formConInner">

<?php
if(isset($_REQUEST['id']) and isset($_REQUEST['elective']))
{
	
		$posts = StudentElectives::model()->findAll("elective_id=:x and status=:y", array(':x'=>$_REQUEST['elective'],':y'=>1));
	
  
  


    ?>
    <?php if($posts!=NULL)
    { ?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'exam-scores-form',
	'enableAjaxValidation'=>false,
)); ?>

	<h3><?php echo Yii::t('app','Enter Exam Scores here:');?></h3>
    <?php echo $form->hiddenField($model,'exam_id',array('value'=>$_REQUEST['examid'])); ?>
	
    <div class="tableinnerlist">
<table width="95%" cellspacing="0" cellpadding="0">
<?php $i=1;
	  $j=0;
	  foreach($posts as $posts_1)
	  { 
	  	$student = Students::model()->findByAttributes(array('id'=>$posts_1->student_id));
	   $checksub = ElectiveScores::model()->findByAttributes(array('exam_id'=>$_REQUEST['examid'],'student_id'=>$student->id));
	   if($checksub==NULL)
	   {
	  if($j==0)
			  {?>
              
              <tr>
              <?php if(FormFields::model()->isVisible("fullname", "Students", "forAdminRegistration"))
                                        { ?>
                                        <th><?php echo Yii::t('app','Student Name');?></th> <?php } ?>
              <th><?php echo Yii::t('app','Marks');?></th>
              <th><?php echo Yii::t('app','Remarks');?></th>
            
              
              </tr>
              
              
              
              <?php $j++;} ?>
	<tr>
		<?php if(FormFields::model()->isVisible("fullname", "Students", "forAdminRegistration"))
                                        { ?>
		<td><?php 
                $name='';
                $name=  $student->studentFullName('forAdminRegistration');
                echo $name;
               // echo $student->first_name.' '.$student->middle_name.' '.$student->last_name; ?>
		
		
		</td>
                                        <?php } ?>

	
		<td><?php echo $form->hiddenField($model,'student_id[]',array('value'=>$posts_1->student_id,'id'=>$posts_1->student_id)); ?>
                    <?php echo $form->textField($model,'marks[]',array('size'=>7,'maxlength'=>3,'id'=>$posts_1->student_id)); ?></td>
        
        <td><?php echo $form->textField($model,'remarks[]',array('size'=>30,'maxlength'=>255,'id'=>$posts_1->student_id)); ?></td>
        
	</tr>	

	
		<?php echo $form->hiddenField($model,'grading_level_id'); ?>
		

	
		<?php //echo $form->hiddenField($model,'is_failed'); ?>
		

	<?php echo $form->hiddenField($model,'created_at',array('value'=>date('Y-m-d')));
		  echo $form->hiddenField($model,'updated_at',array('value'=>date('Y-m-d'))); ?>
		
<?php  $i++;}}?>
	</table>

<br />

</div>

	<div align="left">
		<?php if($i!=1) echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Create') : Yii::t('app','Save'),array('class'=>'formbut')); ?>
	</div>

<?php $this->endWidget(); ?>
<?php }
	else{
		echo '<i>'.Yii::t('app','No Students In This').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").'</i>';
		 } ?>

</div></div><!-- form -->


<?php
$checkscores = ElectiveScores::model()->findByAttributes(array('exam_id'=>$_REQUEST['examid']));
if($checkscores!=NULL)
{?>
   
    
    <?php $model1=new ElectiveScores('search');
	      $model1->unsetAttributes();  // clear any default values
		  if(isset($_GET['examid']))
			$model1->exam_id=$_GET['examid'];
	     
		 
		  ?>
          <h3> <?php echo Yii::t('app','Scores');?></h3>
      <div style="position:relative">    
    <div class="edit_bttns" style="width:250px; top:-10px; right:-123px;">
    <ul>
    <li>
    <?php echo CHtml::link('<span>'.Yii::t('app','Clear All Scores').'</span>', array('electiveScores/deleteall','id'=>$_REQUEST['id'],'examid'=>$_REQUEST['examid'],'elective'=>$_REQUEST['elective']),array('class'=>'addbttn last','confirm'=>'Are You Sure? All Scores will be deleted.'));?>
    </li>
    
    </ul>
    <div class="clear"></div>
    </div>
   </div>
          <?php 
          
          //   <!-- DYNAMIC FIELD ARRAY START -->    
            $new_array=array();
            if(FormFields::model()->isVisible("fullname", "Students", "forAdminRegistration"))
            {
                $new_array[]=array(
                                'header'=>Yii::t('app','Student Name'),
                                'value'=>'$data->gridStudentName(forAdminRegistration)',                                                                                        
                                'name'=> 'firstname',
                                'sortable'=>true,
                        );
            }
            $new_array[]= 'marks';
            $new_array[]= array(
			'header'=>Yii::t('app','Grades'),
			'value'=>array($model,'getgradinglevel'),
			'name'=> 'grading_level_id',
		);
            $new_array[]= 'remarks';
            $new_array[]= array(
			'class'=>'CButtonColumn',
			'buttons' => array(
                                                     
														'update' => array(
                                                        'label' => 'update', // text label of the button
														
                                                        'url'=>'Yii::app()->createUrl("/courses/electiveScores/update", array("sid"=>$data->id,"examid"=>$data->exam_id,"id"=>$_REQUEST["id"],"elective"=>$_REQUEST[elective]))', // a PHP expression for generating the URL of the button
                                                      
                                                        ),
														
                                                    ),
													'template'=>'{update} {delete}',
													'afterDelete'=>'function(){window.location.reload();}'
													
		);
            
          
          //   <!-- DYNAMIC FIELD ARRAY END -->   
                                                
          $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'exam-scores-grid',
	'dataProvider'=>$model1->search(),
	'pager'=>array('cssFile'=>Yii::app()->baseUrl.'/css/formstyle.css'),
 	'cssFile' => Yii::app()->baseUrl . '/css/formstyle.css',
	'columns'=>$new_array,
)); echo '</div></div>';}
	else
{
	echo '<div class="notifications nt_red">'.'<i>'.Yii::t('app','No Scores Updated').'</i></div>'; 
	}?>
	<?php }
	else
    {
	echo '<div class="notifications nt_red">'.'<i>'.Yii::t('app','Nothing Found').'</i></div>'; 
	}?>
	
	
	