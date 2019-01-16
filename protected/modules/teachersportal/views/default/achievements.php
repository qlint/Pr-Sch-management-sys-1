<head><meta http-equiv="refresh" content="300"></head>

<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl;?>/css/prettyPhoto.css" type="text/css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />

<?php
$this->breadcrumbs=array(
	Yii::t('app','Achievements'),
);
?>

<?php $this->renderPartial('leftside');?> 

<div class="pageheader">
      <h2><i class="fa fa-shield"></i> <?php echo Yii::t('app','Rewards and Achievements').'<span>'.Yii::t('app','Rewards and Achievements here...').'</span>';?></h2>
      <div class="breadcrumb-wrapper">
        <span class="label"><?php echo Yii::t('app','You are here:');?></span>
        <ol class="breadcrumb">
          <li class="active"><?php echo Yii::t('app','Rewards and Achievements');?></li>
        </ol>
      </div>
</div>

<div class="contentpanel">
<div class="panel-heading" style="position:relative;">


              <!-- panel-btns -->
              <div class="clear"></div>
              <h3 class="panel-title"><?php echo Yii::t('app','Rewards and Achievements'); ?> </h3>
</div>

<div class="people-item">
            
            <div class="table-responsive">
          
                       <!-- Pending Fees Table -->
     <?php
	$valid_image_types = array('image/jpeg','image/png','image/gif','image/gif','image/bmp','image/jpg');
		$employee=Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
	 $documents = Achievements::model()->findAllByAttributes(array('user_id'=>$employee->id,'user_type'=>'2'));
	 ?>           
   
    <table width="100%" cellpadding="0" cellspacing="0"  class="table table-hover mb30">
    <tr>
    <th><?php echo Yii::t('app','Achievement Title'); ?></th>
    <th><?php echo Yii::t('app','Description'); ?></th>
    <th><?php echo Yii::t('app','Document Name'); ?></th>
    <th></th>
    </tr>
    <?php
	if($documents!=NULL)
	{
		foreach($documents as $document){
                    
                    $document_status= DocumentUploads::model()->fileStatus(6, $document->id, $document->file);                    
                    if($document_status==true)
                    {
		?>
		<tr>
		
		<td><?php echo $document->achievement_title;?></td>
		<td><?php  echo $document->description;?></td>
		<td><?php echo $document->doc_title;
		?></td>
        <td>
        <ul class="tt-wrapper">
		
        <li>
       
           <?php echo CHtml::link('<span>'.Yii::t('app','Download').'</span>', array('achievementdownload','id'=>$document->id),array('class'=>'tt-download'));?>
       </li>
      <?php
        if(in_array($document->file_type,$valid_image_types)) 
        {
        
       
				$path = 'uploadedfiles/employee_achievement_document/'.$document->user_id.'/'.$document->file;
				echo '<li><a class="tt-image" href="#"><span style="width:200px;height:170px; left:-30px;"><img  src="'.$path.'" width="170" height="140" /></span></a>	</li>';
			}
	   ?>
       
       </ul>
        </td>
		</tr>
		<?php
		}
                }
	}
	else
	{
	?>
    	<tr>
        	<td colspan="5"><?php echo Yii::t('app','No achievements added!');?></td>
        </tr>
    <?php
	}
    ?>
    </table>
   
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
		alert('Select any file');
		return false;
	}
}
</script>