<style type="text/css">
.max_mark_err{ color:#F00;}
.min_mark_err{ color:#F00;}
.time_table_dash table tr td, th{ word-break:normal;}
.grid-view table.items th a{ font-size:11px;}
.ui-datepicker-trigger{
	margin-top:5px;	
}
</style>
<script>
function new_1(id)
{
	var val = document.getElementById('max_mark').value;
	var i = 0;
	if(!isNaN(val))
	{
		$(".max_mark_err").html("");
		for(i=1;i<=id;i++)
		{
			
	    	document.getElementById('CbscExams_maximum_marks_'+i).value = val;
		}
	}
	else
	{
		$(".max_mark_err").html("<?php echo Yii::t('app','Max Mark must be an integer'); ?>");
		return false;
		//alert('failed');
	}
	
	
}
function old_1(id)
{
	var val = document.getElementById('min_mark').value;
	var i = 0;
	if(!isNaN(val))
	{
		$(".min_mark_err").html("");
		for(i=1;i<=id;i++)
		{
			document.getElementById('CbscExams_minimum_marks_'+i).value = val;
		}
	}
	else
	{
		$(".min_mark_err").html("<?php echo Yii::t('app','Min Mark must be an integer'); ?>");
		return false;
		//alert('failed');
	}
	
}
</script>
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

$is_insert = PreviousYearSettings::model()->findByAttributes(array('id'=>2));
$is_edit = PreviousYearSettings::model()->findByAttributes(array('id'=>3));
$is_delete = PreviousYearSettings::model()->findByAttributes(array('id'=>4));

$template = '';
if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_edit->settings_value!=0))
{
	$template = $template.'{update}';
}

if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_delete->settings_value!=0))
{
	$template = $template.'{delete}';
}

if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_insert->settings_value!=0))
{
?>
<div class="formCon">

<div class="formConInner">

<?php 
$check = CbscExamGroups::model()->findByAttributes(array('id'=>$_REQUEST['exam_group_id'],'batch_id'=>$_REQUEST['id']));
if($check!=NULL)
{ ?>
	<?php
if(isset($_REQUEST['id']))
{
	
  $posts=Subjects::model()->findAll("batch_id=:x AND no_exams=:y", array(':x'=>$_REQUEST['id'],':y'=>0))  ;
}


    ?>
    <?php if($posts!=NULL)
  { ?>
  <?php
$form=$this->beginWidget('CActiveForm', array(
	'id'=>'exams-form',
	'enableAjaxValidation'=>false,
)); ?>
<table width="80%" border="0" cellspacing="0" cellpadding="0">
<?php if(!isset($_REQUEST['exam_group_id']))
{?>
  <tr>
    <td><?php echo $form->labelEx($model_1,'name'); ?></td>
    <td><?php echo $form->textField($model_1,'name',array('value'=>$_SESSION['name'])); ?>
		<?php echo $form->error($model_1,'name'); ?></td>
    <td><?php echo $form->labelEx($model_1,'exam_type'); ?></td>
    <td><?php echo $form->textField($model_1,'exam_type',array('value'=>$_SESSION['type'])); ?>
		<?php echo $form->error($model_1,'exam_type'); ?></td>
  </tr>
  <?php }?>
  
    
    
		<?php echo $form->hiddenField($model,'exam_group_id'); ?>
		

 
</table>

    
    
   <h3><?php echo Yii::t('app','Enter exam related details here');?></h3>
    
<div class="exam-table exam-table-line">
<table width="100%" cellspacing="0" cellpadding="0">
                    
                    
                    <?php 
                    if(isset($_REQUEST['id']))
                    {
                    
                        $posts=Subjects::model()->findAll("batch_id=:x AND no_exams=:y AND cbsc_common=:z", array(':x'=>$_REQUEST['id'],':y'=>0,':z'=>0));
                        if(count($posts)!=0)
                        {
                            $c=count($posts);
                            $i=1;
                            $j=0;
                            foreach($posts as $posts_1)
                            {
                                $c--;
                                
                                $checksub = CbscExams::model()->findByAttributes(array('exam_group_id'=>$_REQUEST['exam_group_id'],'subject_id'=>$posts_1->id));
                                if($checksub==NULL)
                                {
                                    if($j==0)
                                    {
                                        echo $form->labelEx($model,'max_mark', array('class'=>'aaaa'));		
                                        echo $form->textField($model,'max_mark',array('id'=>'max_mark', 'class'=>'text-btm','maxlength'=>3,'onblur'=>'new_1('.count($posts).');'));
										?>
                                        <div class="max_mark_err">
                                        </div>
                                        <?php
                                        echo $form->error($model,'max_mark');
                                        echo $form->labelEx($model,'min_mark');
                                        echo $form->textField($model,'min_mark',array('id'=>'min_mark', 'class'=>'text-btm','maxlength'=>3,'onblur'=>'old_1('.count($posts).');'));
										?>
										<div class="min_mark_err">
                                        </div>
                                        <?php
                                        echo $form->error($model,'min_mark');
                                        ?>
                                    
                                    <br /><br />
                                    
                                    <tr>
                                        <th width="22%"><?php echo Yii::t('app','Subject name');?></th>
                                        <th width="13%"><?php echo Yii::t('app','Max Marks');?></th>
                                        <th width="13%"><?php echo Yii::t('app','Min Marks');?></th>
                                        <th width="20%"><?php echo Yii::t('app','Start Time');?></th>
                                        <th width="20%"><?php echo Yii::t('app','End Time');?></th>
                                        <?php /*?><th><?php echo Yii::t('examination','Do not create');?></th><?php */?>
                                    </tr>
                                    
                                    
                                    
                                    <?php $j++;
                                    }
                                    
                                    $settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
									if($settings!=NULL)
									{
										$date=$settings->dateformat;
									}
									else
									{
										$date = 'dd-mm-yy';	
									}
                                    echo '<tr>';
                                        echo '<td>'.$posts_1->name.$form->hiddenField($model,'subject_id['.$i.']',array('value'=>$posts_1->id)).'</td>';
										
                                        echo '<td>'.$form->textField($model,'maximum_marks['.$i.']',array('size'=>3,'maxlength'=>3)).'</td>';
                                        echo '<td>'.$form->textField($model,'minimum_marks['.$i.']',array('size'=>3,'maxlength'=>3)).'</td>';
                                        echo '<td>';
                                        $this->widget('application.extensions.timepicker.timepicker', array(
                                        'model' => $model,
										'options'=>array(
												'dateFormat'=>$date,																															
											),
                                        'name'=>'start_time',
                                        'tabularLevel' => "[".$i."]",
                                            'id'=>'CbscExams_start_time_'.$i,	
                                        
                                        ));
                                        echo '</td>';
                                        echo '<td>';
                                        $this->widget('application.extensions.timepicker.timepicker', array(
                                        'model' => $model,
                                        'options'=>array(
												'dateFormat'=>$date,																															
											),
                                        'name'=>'end_time',
                                        'tabularLevel' => "[".$i."]",
                                            'id'=>'CbscExams_end_time_'.$i,	
                                        
                                        ));
                                        echo '</td>';
                                        
                                        /*echo '<td></td>';*/
                                    
                                    echo '</tr>';
                                    $i++;
									
                                    //echo $form->labelEx($model,'created_at');
                                    echo $form->hiddenField($model,'created_at',array('value'=>date('Y-m-d')));
                                    echo $form->error($model,'created_at');
                                    
                                    //echo $form->labelEx($model,'updated_at');
                                    echo $form->hiddenField($model,'updated_at',array('value'=>date('Y-m-d')));
                                    echo $form->error($model,'updated_at'); 
                                } 
                                
                                
                            }
                            
                        }
                    }
                    ?>
                </table>

<br />

    <!--for eelctive subjects-->
    
    
<?php if($i==1)
	  {
		 
		 echo '<div class="notifications nt_green"><i>'.Yii::t('app','Exams Created For All Subjects').'</i></div>'; 
		
	  }
	  ?>
</div>

<br />



	<div align="left">
		<?php if($i!=1)echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Create') : Yii::t('app','Save'),array('class'=>'formbut')); ?>
	</div>
    
	

<?php $this->endWidget(); ?>
<?php }
	else{
		echo '<i>'.Yii::t('app','No Subjects').'</i>';
		 } ?>
<?php }
else
{
	echo '<i>'.Yii::t('app','No Such Exam Scheduled').'</i>';
	}?>
    </div>
    </div>
<?php
}
?>
   
    
    <?php 
	$checkgroup = CbscExams::model()->findByAttributes(array('exam_group_id'=>$_REQUEST['exam_group_id']));
	if($checkgroup!=NULL)
	{?>
    <div >
    <div >
    <?php $model1=new CbscExams('search');
	      $model1->unsetAttributes();  // clear any default values
		  if(isset($_GET['exam_group_id']))
			$model1->exam_group_id=$_GET['exam_group_id'];
	     
		 
		  ?>
          <h3><?php 
		  echo Yii::t('app','Scheduled Subjects');?></h3>
          <?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'exams-grid',
	'dataProvider'=>$model1->search(),
	'pager'=>array('cssFile'=>Yii::app()->baseUrl.'/css/formstyle.css'),
 	'cssFile' => Yii::app()->baseUrl . '/css/formstyle.css',
	
	'columns'=>array(
		
		array(
		    'name'=>'subject_id',
			'value'=>array($model,'subjectname')
		
		),
		//array('d-m-Y',strtotime(start_time)),
		array(	
				'name'=>'start_time',
				'value'=>array($model,'starttime'),
				'filter'=>false
		),
		array(	
				'name'=>'end_time',
				'value'=>array($model,'endtime'),
				'filter'=>false,
				
		),
		//'start_time',
		//'end_time',
		'maximum_marks',
		'minimum_marks',
		/*'grading_level_id',
		'weightage',
		'event_id',
		'created_at',
		'updated_at',
		*/
		array(
			'header'=>Yii::t('app','Action'),
			'class'=>'CButtonColumn',
			'htmlOptions'=>array('style'=>''),
			'deleteConfirmation'=>Yii::t('app','Are you sure you want to delete this exam?'),
			'buttons' => array(
                                                     
														'update' => array(
                                                        'label' => Yii::t('app','Update'), // text label of the button
														
                                                        'url'=>'Yii::app()->createUrl("CBSCExam/exams/update", array("sid"=>$data->id,"exam_group_id"=>$data->exam_group_id,"id"=>$_REQUEST["id"]))', // a PHP expression for generating the URL of the button
                                                      
                                                        ),
														
                                                    ),
													'template'=>'{update} {delete}',
													'afterDelete'=>'function(){window.location.reload();}'
													
		),
		array(
                   'class' => 'CButtonColumn',
                    'buttons' => array(
                                                     
														'add' => array(
                                                        'label' => Yii::t('app','Exam Score'), // text label of the button
														
                                                        'url'=>'Yii::app()->createUrl("CBSCExam/examScores/create", array("examid"=>$data->id,"id"=>$_REQUEST["id"]))', // a PHP expression for generating the URL of the button
                                                      
                                                        )
                                                    ),
                   'template' => '{add}',
				   'header'=>Yii::t('app','Manage'),
				   'htmlOptions'=>array('style'=>'width:10%'),
				   'headerHtmlOptions'=>array('style'=>'color:#FF6600')
            ),
	),
)); echo '</div></div>';}
else
{
	echo '<div class="notifications nt_red"><i>'.Yii::t('app','Nothing Scheduled').'</i></div>'; 
	}?>

<br />
	<script>
$('input[type="text"][name="CbscExams[maximum_marks][]"], input[type="text"][name="CbscExams[minimum_marks][]"],input[type="text"][name="ElectiveExams[maximum_marks][]"],input[type="text"][name="ElectiveExams[maximum_marks][]"]').blur(function(e) {
    if(isNaN($(this).val())){
  $(this).val('');
 }
});
</script>

<script>

$("form#exams-form").submit(function(e) {
	var textBox = "";
	$("form#exams-form").find('input[type=text]').each(function(){
		textBox += $(this).val();
	});
	
	if (textBox == "") {
		$(".errorMessage").remove();
		alert("<?php echo Yii::t("app", "Fill the details related to exam");?>");
	}
	else{	
		var that	= this;
		var data	= $(that).serialize();
		$(that).find("input[type='submit']").attr("disabled", true);
		$.ajax({
			url:'<?php echo Yii::app()->createUrl("/CBSCExam/exams/create", array("id"=>$_REQUEST['id'], "exam_group_id"=>$_REQUEST['exam_group_id']));?>',
			type:'POST',
			data:data,
			dataType:"json",
			success: function(response){
				$(that).find("input[type='submit']").attr("disabled", false);
				$(".errorMessage").remove();
				if(response.status=="success"){                                    
					window.location.reload();
				}
				else if(response.hasOwnProperty("errors")){
					var errors	= response.errors;
					$.each(errors, function(attribute, earray){
						$.each(earray, function(index, error){
							var error_div	= $("<div class='errorMessage' style='font-weight:100;' />");
							error_div.text(error);
							$('#' + attribute).closest("td").append(error_div);
						});										
					});				
				}
				else if(response.hasOwnProperty("message")){
					alert(response.message);
				}
				else{
					alert("<?php echo Yii::t("app", "Some problem found while saving datass !!");?>");
				}
			},
			error:function(){
				$(that).find("input[type='submit']").attr("disabled", false);
				alert("<?php echo Yii::t("app", "Some problem found while saving data !!");?>");
			}
		});
	}
	return false;
});


</script>