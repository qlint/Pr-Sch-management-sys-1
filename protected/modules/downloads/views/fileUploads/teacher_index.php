<head><meta http-equiv="refresh" content="300"></head>

<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl;?>/css/prettyPhoto.css" type="text/css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />




<?php
$this->breadcrumbs=array(
	 Yii::t('app','File Uploads'),
);
?>

     <?php $this->renderPartial('/default/teacherleft');?>
    


<div class="pageheader">
      <h2><i class="fa fa-download"></i><?php echo  Yii::t('app','Downloads') .'<span>'.Yii::t('app','Downloads here').'</span>'?></h2>
      <div class="breadcrumb-wrapper">
        <span class="label"><?php echo Yii::t('app','You are here:');?></span>
        <ol class="breadcrumb">
          <li class="active"><?php echo Yii::t('app','Downloads');?></li>
        </ol>
      </div>
</div>

<div class="contentpanel">
<div class="panel-heading" style="position:relative;">


              <!-- panel-btns -->
              <div class="clear"></div>
              <h3 class="panel-title"><?php echo Yii::t('app','File Uploads'); ?> </h3>
</div>

<div class="people-item">
	<?php
	
		$roles=Rights::getAssignedRoles(Yii::app()->user->Id);
		foreach($roles as $role)
		{
		if(sizeof($roles)==1 and ($role->name == 'teacher' or $role->name == 'Admin'))
		{
		?>
			<div class="opnsl_headerBox">
				<div class="opnsl_actn_box"> </div>
				<div class="opnsl_actn_box"><div class="opnsl_actn_box1">
				<?php 
				echo CHtml::link(Yii::t('app','New Upload'),array('create'),array('class'=>'btn btn-primary',array('style'=>'margin-right:10px;'),));
				?>
				</div>
				<div class="opnsl_actn_box2">
				<?php
				echo CHtml::link(Yii::t('app','Manage Uploads'),array('admin'),array('class'=>'btn btn-primary'));
				?>
				</div>
			</div>
		</div>
		
		<?php
    }
    
    }
    ?> 
            
            <div class="table-responsive">
                       <!-- Pending Fees Table -->
                 <!--<form action="" method="post" target="_blank">-->
  <?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'message-list-form',
	'htmlOptions' => array('target' => '_blank'),
)); ?>
    <table width="100%" cellpadding="0" cellspacing="0" class="table table-bordered mb30">
    <thead>
    <tr>
    <th width="50"><input id="demo_box_1" class="css-checkbox" type="checkbox" /><label for="demo_box_1" name="demo_lbl_1" class="css-label"></label><input type="hidden" id="isChkd" value="true" /></th>
    <th><?php echo Yii::t('app','Title'); ?></th>
    <th><?php echo Yii::t('app','File Name'); ?></th>
    <th><?php echo Yii::t('app','File Type'); ?></th>
    <th><?php echo Yii::t('app','Posted By'); ?></th>
    </tr>
    </thead>
    <?php
	if($files!=NULL)
	{
		foreach($files as $file){
                    $document_status= DocumentUploads::model()->fileStatus(5, $file->id, $file->file);                    
                    if($document_status==true)
                    {
		?>
		<tr>
		<td width="60"><input type="checkbox" id="demo_box_<?php echo $file->id+1;?>" name="Downfiles[]" class="css-checkbox dl-files" value="<?php echo $file->id;?>" /><label for="demo_box_<?php echo $file->id+1;?>" name="demo_lbl_<?php echo $file->id+1;?>" class="css-label"></label></td>
		<td width="300" style="text-align:left; padding-left:10px;">
        
		<?php 
			if($file->created_by==Yii::app()->user->Id)
				echo '<img src="images/arrow_right.png" style="margin:0px 10px 0px 0px" />';
			else
				echo '<img src="images/arrow_left.png" style="margin:0px 10px 0px 0px" />';
			?><?php echo $file->title;
		?>
		
        
		</td>
		<td width="300"><font color="<?php echo $font; ?>"><?php echo $file->file;?></font></td>
		<td width="80"><?php $parts	=	explode('/',$file->file_type); echo $parts[1].' file';?></td>
		<td width="150"><?php 
		if($file->created_by == Yii::app()->user->Id){
			echo 'You';
		}
		else
		{
			$posted_usr	=	Profile::model()->findByAttributes(array('user_id'=>$file->created_by));
			echo $posted_usr->firstname.' '.$posted_usr->lastname;
			
		}
		?></td>
		</tr>
		<?php
		}
                }
	}
	else
	{
	?>
    	<tr>
        	<td colspan="5"><?php echo Yii::t('app','No files to download!');?></td>
        </tr>
    <?php
	}
    ?>
    </table>
    
   <input type="submit" onclick="return validateForm();" value="<?php echo Yii::t('app','Download');?>" class="btn btn-danger" />
<?php $this->endWidget(); ?>
          </div>
           

          </div>
 

</div>



<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function () {
	$('#demo_box_1').change(function(){
		if($('#isChkd').val() == 'true'){
			$('.dl-files').prop('checked', true);			
			$('#isChkd').val('false');
		}
		else{
			$('.dl-files').prop('checked', false);
			$('#isChkd').val('true');
		}
	});
	
	$('.dl-files').change(function(){
		var all = $('input.dl-files').length;
		var checked = $('input.dl-files:checked').length;
		if(all == checked){
			$('#demo_box_1').prop('checked', true);
			$('#isChkd').val('false');
		}else{
			$('#demo_box_1').prop('checked', false);
			$('#isChkd').val('true');
		}
	});
});


function validateForm(){
	setTimeout('window.location.reload()',300);
	var chks	=	$("[type='checkbox'][name='Downfiles[]']:checked");
	if(chks.length==0){
		alert('<?php echo Yii::t('app','Select any file');?>');
		return false;
	}
}
</script>