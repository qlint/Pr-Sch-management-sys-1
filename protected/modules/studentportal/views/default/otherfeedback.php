<?php
var_dump();exit;
$leftside = 'mailbox.views.default.left_side';	
$this->renderPartial($leftside); 
	$feedbacks =ComplaintFeedback::model()->findAllByAttributes(array('complaint_id'=>$_REQUEST['id']));
	$complaint=Complaints::model()->findByAttributes(array('id'=>$_REQUEST["id"]));
	$category=ComplaintCategories::model()->findByAttributes(array('id'=>$complaint->category_id)); 
?>
<div class="pageheader">
        <div class="col-lg-8">
        <h2><i class="fa fa-money"></i><?php echo Yii::t("app",'Complaints');?><span><?php echo Yii::t("app",'Create Complaints here');?></span></h2>
        </div>
        
    
        <div class="breadcrumb-wrapper">
            <span class="label"><?php echo Yii::t("app",'You are here:');?></span>
                <ol class="breadcrumb">
                <!--<li><a href="index.html">Home</a></li>-->
                
                <li class="active"><?php echo Yii::t("app",'Complaints');?></li>
            </ol>
        </div>
    
        <div class="clearfix"></div>
    
    </div>
<div class="contentpanel"> 
	<div class="panel-heading">    
		<h3 class="panel-title"><?php echo Yii::t('app','Complaints');?></h3>
		<div class="btn-demo" style="position:relative; top:-30px; right:3px; float:right;">
			<?php
            	echo CHtml::link(Yii::t('app','Register a Complaint'),array('Complaints/create','id'=>Yii::app()->user->id),array('class'=>'btn btn-primary'));
            ?>
         </div>
    </div>
    <div class="people-item"> 
	
<div class="table-responsive">
	<table width="80%" cellspacing="0" cellpadding="0" border="0">
	<tbody>
		<tr>
			<td width="150"><?php echo Yii::t("app",'Category');?></td>
			<td width="30"><strong>:</strong></td>
			<td><?php if($category->category)
			{
				echo $category->category;?></td>
      <?php }
	  		else
	  		{
				echo Yii::t("app","Category Deleted");
	  		}
	  ?></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td><?php echo Yii::t("app",'Subject');?></td>
			<td><strong>:</strong></td>
			<td><?php echo $complaint->subject;?></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td><?php echo Yii::t("app",'Complaint');?></td>
			<td><strong>:</strong></td>
			<td><?php echo $complaint->complaint;?></td>
		</tr>
	</tbody>
</table>
</div>
<div class="chat_div">
<?php

foreach($feedbacks as $feedback)
	{
		if(isset($feedback))
		{
			$profile=Profile::model()->findByAttributes(array('user_id'=>$feedback->uid));?>



<?php
	if($feedback->uid==1)
	{?>

	
	<div class="chat_two">
		<?php echo ucfirst($profile->firstname).' '.ucfirst($profile->lastname).' : '. ucfirst($feedback->feedback);?>
	<div class="triangle-topleft"></div>
	</div>
    <?php }
	else
	{
		?>
		
    <div class="chat_one">
     <?php echo ucfirst($profile->firstname).' '.ucfirst($profile->lastname).' : '. ucfirst($feedback->feedback);?>
	<div class="triangle-topright"></div>
	</div>

<?php } ?>


<?php
		}
	}
	?>
    
<?php
	  
$form=$this->beginWidget('CActiveForm', array(
'enableClientValidation'=>true,
'clientOptions'=>array(
	'validateOnSubmit'=>true,
),
));
?>

<div class="clearfix"></div>
<?php

echo $form->textArea($model,'feedback',array('rows'=>3,'class'=>'form-control','style'=>'width:70%;', 'cols'=>15,'placeholder'=>'If any comment enter here')); ?>
 
 
 			<?php echo $form->error($model,'feedback' ); ?><br />

<div class="clearfix"></div>
<div class="buttons">
	<?php echo CHtml::submitButton(Yii::t("app",'Submit'),array('class'=>'btn btn-success')); ?>
    <?php
	if($complaint->status == 0 )
	{
		echo CHtml::link(Yii::t("app",'Close'),array('complaints/close','id'=>$complaint->id),array('class'=>'btn btn-primary')); 
	}
	if($complaint->status == 1)
	{
		echo CHtml::link(Yii::t("app",'Reopen'),array('complaints/reopen','id'=>$complaint->id),array('class'=>'btn btn-primary')); 		
	}
?> 
</div>


<?php $this->endWidget(); ?>

    <div class="clearfix"></div>
</div>  
</div> 
 