<?php
$this->breadcrumbs=array(
	Yii::t('app','Library')=>array('/library'),	
	Yii::t('app','Due Dates'),
);
$student_visible_fields   = FormFields::model()->getVisibleFields('Students', 'forStudentProfile');
?>
<script language="javascript">
function booklist()
{
	var val=document.getElementById('id').value;
	window.location = "index.php?r=library/settings/settings&id="+val;
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
    <div class="cont_right formWrapper">
    <?php
		if(isset($_REQUEST['id']) && ($_REQUEST['id']!=NULL))
		{
			$sel= $_REQUEST['id'];
		}
		else
		{
			$sel='';
		}		
	?>

<h1><?php echo Yii::t('app','Library Management');?></h1>


<div class="formCon" >
    <div class="formConInner">   
<?php echo '<strong>'.Yii::t('app','Students whose due date will expire on ').'</strong>&nbsp;';
echo CHtml::activeDropDownList($model,'due_date',array('0' => Yii::t('app','All Dues'), '1' => Yii::t('app','1st day'), '2' => Yii::t('app','5th day'), '3' => Yii::t('app','10th day'), '-1' =>Yii::t('app','Expired')),array('prompt'=>Yii::t('app','Select'),'onchange'=>"javascript:booklist();",'id'=>'id','options'=>array($sel=>array('selected'=>true))));
echo '</div></div>';
	?>
    
    <?php
    Yii::app()->clientScript->registerScript(
       'myHideEffect',
       '$(".flash-success").animate({opacity: 1.0}, 3000).fadeOut("slow");',
       CClientScript::POS_READY
    );
?>

<?php if(Yii::app()->user->hasFlash('notification')):?>
    <span class="flash-success" style="color:#F00; padding-left:15px; font-size:12px">
        <?php echo Yii::app()->user->getFlash('notification'); ?>
    </span>
<?php endif; ?>

    
    
  <?php                   
  if(isset($_REQUEST['id']) && ($_REQUEST['id']!=NULL))
	{
	$targetdate=0;
	if($_REQUEST['id']==0)
	{
		$currdate=date('Y-m-d');
		$_REQUEST['id']=0;
		
	}
	
				
	if($_REQUEST['id']==1)
	{
		$currdate=date('Y-m-d');
		$targetdate=date('Y-m-d', strtotime('+1 day',strtotime($currdate)));
		$_REQUEST['id']=1;
		
	}
	if($_REQUEST['id']==2)
	{
		$currdate=date('Y-m-d');
		$targetdate=date('Y-m-d', strtotime('+5 day',strtotime($currdate)));
		$_REQUEST['id']=5;
		
	}
	if($_REQUEST['id']==3)
	{
		$currdate=date('Y-m-d');
		$targetdate=date('Y-m-d', strtotime('+10 day',strtotime($currdate)));
		$_REQUEST['id']=10;
		
	}
	
	if($_REQUEST['id']==-1)
	{
		$currdate=date('Y-m-d');
		//$targetdate=date('Y-m-d', strtotime('+10 day',strtotime($currdate)));
		$_REQUEST['id']=-1;
		
	}
	
	// Customising the table
	if($_REQUEST['id']==0){
		
		$duedate=BorrowBook::model()->findAll('status=:x',array(':x'=>'C'));
	}
	elseif($_REQUEST['id']==-1){
		
		$duedate=BorrowBook::model()->findAll('due_date < CURRENT_DATE() AND status=:y',array(':y'=>'C'));
	}
	else{
		$duedate=BorrowBook::model()->findAll('due_date=:x AND status=:y',array(':x'=>$targetdate,':y'=>'C'));
	}
						
						
								
						?>
<div class="table-responsive">
    <table class="table table-bordered mb30" width="100%" cellspacing="0" cellpadding="0">
    <thead>
 <tr>
        <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){ ?>
        <th><?php echo Yii::t('app','Student Name');?></th>
        <?php } ?>
        <th><?php echo Yii::t('app','ISBN');?></th>
        <th><?php echo Yii::t('app','Book Name');?></th>
        <th><?php echo Yii::t('app','Author');?></th>
        <th><?php echo Yii::t('app','Issue Date');?></th>
        <th><?php echo Yii::t('app','Due Date');?></th>
        <th><?php echo Yii::t('app','Is returned');?></th>
        </tr>
        </thead>
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
		?>
<?php 
if($duedate==NULL)
	{
		echo '<tr><td class="table_nothingFound" colspan="7"><strong>'.Yii::t('app','No data available now.').'</strong></td></tr>';
	}
	else
	{	
		if($year == $current_academic_yr->config_value)
		{
			// Button to send SMS 
			$notification=NotificationSettings::model()->findByAttributes(array('id'=>9));
            if($notification->mail_enabled == '1' or $notification->sms_enabled == '1' or $notification->msg_enabled == '1'){ // Checking if SMS,mail or message is enabled			 
			?>
				<div class="edit_bttns" style="top:73px; right:40px;"> 
					<?php echo CHtml::button(Yii::t('app','Send Reminder'), array('submit' => array('Settings/Sendsms','due_date_id'=>$_REQUEST['id'],'target_date'=>$targetdate),'class'=>'formbut')); ?>
				</div>
		  <?php      
			}
        }
	
		foreach($duedate as $due)
		{
		$bookdetails=Book::model()->findByAttributes(array('id'=>$due->book_id));
		$student=Students::model()->findByAttributes(array('id'=>$due->student_id));
		$author = Author::model()->findByAttributes(array('auth_id'=>$bookdetails->author));
	?>
<tr>
<?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){ ?>
<td align="center"><?php echo $student->studentFullName("forStudentProfile");?></td>
<?php } ?>
<td align="center"><?php echo $bookdetails->isbn;?></td>
<td align="center"><?php echo $bookdetails->title;?></td>
<td align="center"><?php echo $author->author_name;?></td>
<td align="center"><?php 	

							$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
								if($settings!=NULL)
								{	
									$date1=date($settings->displaydate,strtotime($due->issue_date));
									echo $date1;
									
		
								}
								else
								echo $due->issue_date;
								?></td>
<td align="center"><?php 
						if($settings!=NULL)
								{	
									$date1=date($settings->displaydate,strtotime($due->due_date));
									echo $date1;
									
		
								}
								else
								echo $due->due_date;
						?></td>
<td align="center">
<?php 
if($due->status=='R')
{
	echo Yii::t('app','Yes');
}
else
{
	 echo Yii::t('app','No');
	 // echo 'No'.'['.CHtml::link(Yii::t('library','Send Mail'),array('library/settings/remind/','id'=>$due->student_id,'due'=>$_REQUEST['id']),array('confirm'=>'You want to send mail to  '.$student->first_name.'?')).']';
}
?>
</td>
</tr>
<?php }
				} 
				
				 ?>
</table>
<?php } ?>
<?php $this->endWidget(); ?>
</div>
</div>
</div>
</div>
    </td>
  </tr>
</table>