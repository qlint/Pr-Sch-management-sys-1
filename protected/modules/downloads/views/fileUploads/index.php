<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl;?>/css/prettyPhoto.css" type="text/css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />

<style>
label {margin-right:20px;}
input[type=checkbox].css-checkbox {
	position: absolute; 
	overflow: hidden; 
	clip: rect(0 0 0 0); 
	height:1px; 
	width:1px; 
	margin:-1px; 
	padding:0;
	border:0;
}

input[type=checkbox].css-checkbox + label.css-label {
	/*padding-left:25px;*/
	padding:0px 0px 0px 8px;
	height:18px; 
	display:inline-block;
	line-height:15px;
	background-repeat:no-repeat;
	background-position: 3px 2px;
	font-size:15px;
	vertical-align:middle;
	cursor:pointer;
	color:#4e4e4e;
	display: block;
	/*margin: 12px 15px 12px 0px;*/
	margin:0px 11px;
	font-size:11px;
	font-weight: 600;
	font-family: 'Open Sans', sans-serif;
	text-transform:uppercase;
	
}

input[type=checkbox].css-checkbox:checked + label.css-label {
	background-position: 3px -18px;
}

.css-label{
	background-image: url(images/mail_checkbx_new.png);
}

.up_ma{ position:absolute; top:0px; right:10px;
	font-size:13px;}
	
.up_ma a{ margin:10px;}

.pdtab_Con table td{ padding:2px;}
		
</style>

<?php
$this->breadcrumbs=array(
	Yii::t('app','Downloads')=>array('/downloads'),
	Yii::t('app','File Uploads'),
);
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="80" valign="top" id="port-left">
     <?php $this->renderPartial('/default/left_side');?>
    
    </td>
    <td valign="top">
	<div class="cont_right">
    <h1><?php echo Yii::t('app','File Uploads'); ?> 
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
	$is_create = PreviousYearSettings::model()->findByAttributes(array('id'=>1));
	?>
     <?php
    $roles=Rights::getAssignedRoles(Yii::app()->user->Id);
	foreach($roles as $role)
	{
		if(sizeof($roles)==1 and ($role->name == 'teacher' or $role->name == 'Admin'))
		{
		?>
<?php /*?>		<div class="up_ma">
		<?php 
		 if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_create->settings_value!=0))
		 {
         	echo CHtml::link(Yii::t('app','New Upload'),array('create'),array('class'=>'mailbox-menu-newup'));
		 }
         echo CHtml::link(Yii::t('app','Manage Uploads'),array('admin'),array('class'=>'mailbox-menu-mangeup'));
         ?>
        </div><?php */?>
<div class="button-bg">
<div class="top-hed-btn-left"> </div>
<div class="top-hed-btn-right">
<ul>                                    
<li>
		<?php 
		 if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_create->settings_value!=0))
		 {
         	echo CHtml::link(Yii::t('app','New Upload'),array('create'),array('class'=>'mailbox-menu-mangeup'));
		 }
		 ?>
</li>
<li>
		 <?php 
         echo CHtml::link(Yii::t('app','Manage Uploads'),array('admin'),array('class'=>'mailbox-menu-mangeup'));
         ?>
</li>
                                    
</ul>
</div> 
</div>
        <?php
		}
		
	}
    ?> </h1>   
    
	<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'',
	'action'=>'',
	'htmlOptions'=>array(
    'target'=>'_blank',
	)
)); ?>
    
    <?php /*?><form action="" method="post" target="_blank"><?php */?>
    <div class="os-table tablebx" style="padding-top:0px;">
    <div class="tbl-grd"></div>
    <table width="100%" cellpadding="0" cellspacing="0">
    <tr class="pdtab-h">
    <th width="100" class="tbl-td-left"><input id="demo_box_1" class="css-checkbox" type="checkbox" /><label for="demo_box_1" name="demo_lbl_1" class="css-label"></label><input type="hidden" id="isChkd" value="true" /></th>
    <th align="center" class="tbl-td-left"><?php echo Yii::t('app','Title'); ?></th>
    <th align="center" class="tbl-td-left"><?php echo Yii::t('app','File Name'); ?></th>
    <th align="center" class="tbl-td-left"><?php echo Yii::t('app','File Type'); ?></th>
    <th align="center" class="tbl-td-left"><?php echo Yii::t('app','Posted By'); ?></th>
    </tr>
    <?php
	if($files!=NULL)
	{
		foreach($files as $file){
		?>
		<tr>
		<td width="60" ><input type="checkbox" id="demo_box_<?php echo $file->id+1;?>" name="Downfiles[]" class="css-checkbox dl-files" value="<?php echo $file->id;?>" /><label for="demo_box_<?php echo $file->id+1;?>" name="demo_lbl_<?php echo $file->id+1;?>" class="css-label"></label></td>
		<td width="300" class="download-left-icon">
		<?php 
			if($file->created_by==Yii::app()->user->Id)
				echo '<img src="images/arrow_right.png" />';
			else
				echo '<img src="images/arrow_left.png" />';
			echo $file->title;
		?>
		</td>
		<td width="300"><?php echo $file->file;?></td>
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
	else
	{
	?>
    	<tr>
        	<td colspan="5" align="center"><?php echo Yii::t('app','No files to download!')?></td>
        </tr>
    <?php
	}
    ?>
    </table>
    <div style="padding:13px 0px;">
    	 <input type="submit" onclick="return validateForm();" value="<?php echo Yii::t('app',"Download");?>" class="formbut" />
   <!-- </form>-->
   <?php $this->endWidget(); ?>
    </div>
    </div>
	</div>
    </td>
 </tr>
</table>
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
	var chks	=	$("[type='checkbox'][name='Downfiles[]']:checked");
	if(chks.length==0){
		alert('<?php echo Yii::t('app','Select any file'); ?>');
		return false;
	}
}
</script>