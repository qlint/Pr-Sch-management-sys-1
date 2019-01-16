<?php
$this->breadcrumbs=array(
	Yii::t('app','Library')=>array('/library'),	
	Yii::t('app','Student Details'),
);
$student_visible_fields   = FormFields::model()->getVisibleFields('Students', 'forStudentProfile');
?>
<script language="javascript">
function booklist()
{
	var val=document.getElementById('id_widget').value;
        if(val!='')
        {
            window.location = "index.php?r=library/borrowBook/studentdetails&id="+val;
        }
        else
            alert("<?php echo Yii::t('app', 'Select Student') ?>")
}

</script>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'borrow-book-form',
	'enableAjaxValidation'=>false,
        
)); ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top">
    <?php $this->renderPartial('/settings/library_left');?>
 </td>
    <td valign="top">
     <div class="cont_right">  
     <h1><?php echo Yii::t('app','View Student Details');?></h1>
     <div class="formCon">
    <div class="formConInner">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                    
                    <td width="40%" class="table-inputtype">
                        <?php 
                        //echo CHtml::dropDownList('Book ID',isset($_REQUEST['id'])? $_REQUEST['id'] : '',CHtml::listData(BorrowBook::model()->findAll(array('group'=>'student_id')),'student_id','studentadm'),array('prompt'=>Yii::t('app','Select'), 'onchange'=>"javascript:booklist();", 'id'=>'student_id'));
                        $student_name   =   '';
                        $student_id     =   '';
                        if(isset($_REQUEST['id']) && $_REQUEST['id']!=NULL)
                        {
                            $Student        =   Students::model()->findByAttributes(array('id'=>$_REQUEST['id'])); 
                            $student_name   =   $Student->studentFullName("forStudentProfile");
                            $student_id     =   $_REQUEST['id'];
                        }
                        $this->widget('zii.widgets.jui.CJuiAutoComplete',
                                    array(
                                    'name'=>'name',
                                    'id'=>'name_widget',  
                                    'value'=>$student_name,
                                    'source'=>$this->createUrl('/site/autoComplete'),
                                    'htmlOptions'=>array('placeholder'=>Yii::t('app','Student Name')),
                                    'options'=>
                                    array(
                                    'showAnim'=>'fold',
                                    'select'=>"js:function(student, ui) 
                                    {
                                        $('#id_widget').val(ui.item.id);                                                                      
                                    }"
                                    ),
                                    
                                    ));                        
                            echo CHtml::hiddenField('student_id',$student_id,array('id'=>'id_widget'));
                        ?>
                    </td>
                    <td width="6%">
                      <?php echo CHtml::ajaxLink('',array('/site/explorer','widget'=>'1'),array('update'=>'#explorer_handler'),array('id'=>'explorer_student_name','class'=>'exp_but-non')); ?>
                    </td>
                    <td> 
  			<div class="row buttons">
                            <?php echo CHtml::button(Yii::t('app','Search'),array('class'=>'formbut','onClick'=>"js:booklist();")); ?>
                        </div>
                    </td>                    
                    </tr>                    
                    </table>
    
                	
                        
                        
                        
                     <?php
                        if(isset($_REQUEST['id']))
						{
							$book=BorrowBook::model()->findAll('student_id=:t2',array(':t2'=>$_REQUEST['id']));
							$student=Students::model()->findByAttributes(array('id'=>$_REQUEST['id']));
							
							
								
						?>
                        </div>
                        </div>
<div class="table-responsive">
    <table class="table table-bordered mb30" width="100%" cellspacing="0" cellpadding="0">
    <thead>
<tr>
<?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){ ?>
<td align="center"><?php echo Yii::t('app','Student Name');?></td>
<?php } ?>
<th<?php echo Yii::t('app','ISBN');?></th>
<th><?php echo Yii::t('app','Book Name');?></th>
<th><?php echo Yii::t('app','Author');?></th>
<th><?php echo Yii::t('app','Issue Date');?></th>
<th><?php echo Yii::t('app','Due Date');?></th>
<th><?php echo Yii::t('app','Is returned');?></th>
</tr>


<?php
if($book!=NULL)
							{
foreach($book as $book_1)
{
	
	
	$bookdetails=Book::model()->findByAttributes(array('id'=>$book_1->book_id));
	$author=Author::model()->findByAttributes(array('auth_id'=>$bookdetails->author));
	$publication=Publication::model()->findByAttributes(array('publication_id'=>$bookdetails->publisher));
	?>
<tr>
<?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){ ?>
<td><?php echo $student->studentFullName("forStudentProfile");?></td>
<?php } ?>
<td><?php echo $bookdetails->isbn;?></td>
<td><?php echo $bookdetails->title;?></td>
<td><?php 
if($author!=NULL)
{
echo CHtml::link($author->author_name,array('/library/authors/authordetails','id'=>$author->auth_id));
}
?></td>
<td><?php 
						$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
								if($settings!=NULL)
								{	
									$date1=date($settings->displaydate,strtotime($book_1->issue_date));
									echo $date1;
		
								}
								else
								echo $book_1->issue_date;
							?></td>
<td align="center"><?php 
							if($settings!=NULL)
								{	
									$date1=date($settings->displaydate,strtotime($book_1->due_date));
									echo $date1;
		
								}
								else
								echo $book_1->due_date;
							?></td>
<td>
<?php 
if($book_1->status=='R')
{
	echo Yii::t('app','Yes');
}
else
{
	echo Yii::t('app','No');
}
?>
</td>
</tr>
<?php }


} 
else
{
        echo '<tr><td colspan="7" class="table_nothingFound"><center>'.Yii::t('app','No data available').'</center></td></tr>';
}
 ?>
</table>
</div>
</div>
</td>
</tr>
</table>
<?php } ?>
<?php $this->endWidget(); ?>