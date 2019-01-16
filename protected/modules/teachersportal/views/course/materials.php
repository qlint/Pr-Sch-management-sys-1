<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl;?>/css/prettyPhoto.css" type="text/css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />

<style>
.container {
	background:#FFF;
}
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
	padding:0px 0px 0px 2px;
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

.mailbox-menu-newup{
	-moz-box-shadow:inset 0px 0px 0px 0px #ffffff !important;
	-webkit-box-shadow:inset 0px 0px 0px 0px #ffffff !important ;
	box-shadow:inset 0px 0px 0px 0px #ffffff !important;
	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #1bb4fa), color-stop(1, #0994f0) ) !important;
	background:-moz-linear-gradient( center top, #1bb4fa 5%, #0994f0 100% ) !important;
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#1bb4fa', endColorstr='#0994f0') !important;
	background-color:#1bb4fa !important;
	-moz-border-radius:3px !important;
	-webkit-border-radius:3px !important;
	border-radius:3px !important;
	border:1px solid #0c93d1 !important;
	display:inline-block;
	color:#ffffff !important;
	font-family:arial;
	font-size:12px;
	font-weight:bold;
	padding:8px 14px !important;
	text-decoration:none;
	margin:0px 10px;
	
	/*text-shadow:1px 0px 0px #0664a3;*/
}
.mailbox-menu-newup a{color:#fff !important; text-decoration:none !important; display:block;}

.mailbox-message-subject{
	padding:10px;
}

.mailbox-menu-mangeup{
	-moz-box-shadow:inset 0px 0px 0px 0px #ffffff !important;
	-webkit-box-shadow:inset 0px 0px 0px 0px #ffffff !important ;
	box-shadow:inset 0px 0px 0px 0px #ffffff !important;
	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #1bb4fa), color-stop(1, #0994f0) ) !important;
	background:-moz-linear-gradient( center top, #1bb4fa 5%, #0994f0 100% ) !important;
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#1bb4fa', endColorstr='#0994f0') !important;
	background-color:#1bb4fa !important;
	-moz-border-radius:3px !important;
	-webkit-border-radius:3px !important;
	border-radius:3px !important;
	border:1px solid #0c93d1 !important;
	display:inline-block;
	color:#ffffff !important;
	font-family:arial;
	font-size:12px;
	font-weight:bold;
	padding:8px 14px !important;
	text-decoration:none;

	/*text-shadow:1px 0px 0px #0664a3;*/
}
.mailbox-menu-mangeup a{color:#fff !important; text-decoration:none !important; display:block;}
</style>



<div id="parent_Sect">
	<?php $this->renderPartial('/default/leftside');?> 
    <?php    
	/*$student=Students::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
    $guard = Guardians::model()->findByAttributes(array('id'=>$student->parent_id));
    $settings=UserSettings::model()->findByAttributes(array('user_id'=>1));*/
    ?>
    
    
    <div id="parent_rightSect">
        <div class="parentright_innercon">
        	<?php $this->renderPartial('batch');?>
            <div class="edit_bttns" style="top:100px; right:25px">
                <ul>
                    <li>
                    <?php //echo CHtml::link('<span>'.Yii::t('studentportal','My Courses').'</span>', array('/studentportal/course'),array('class'=>'addbttn last'));?>
                    </li>
                </ul>
            </div>
            
            <!-- Materials Area -->
            <div class="profile_details">
                <form action="" method="post" target="_blank">
                    <div class="inner_new_table">
                        <table width="100%" cellpadding="0" cellspacing="0">
                        <tr>
                            <th width="50"><input id="demo_box_1" class="css-checkbox" type="checkbox" /><label for="demo_box_1" name="demo_lbl_1" class="css-label"></label><input type="hidden" id="isChkd" value="true" /></th>
                            <th><?php echo Yii::t('app', 'Title');?></th>
                            <th><?php echo Yii::t('app', 'File Name');?></th>
                            <th><?php echo Yii::t('app', 'File Type');?></th>
                            <th><?php echo Yii::t('app', 'Posted By');?></th>
                        </tr>
                        <?php
                        if($files!=NULL)
                        {
                            foreach($files as $file)
                            {
                            ?>
                                <tr>
                                    <td width="60"><input type="checkbox" id="demo_box_<?php echo $file->id+1;?>" name="Downfiles[]" class="css-checkbox dl-files" value="<?php echo $file->id;?>" /><label for="demo_box_<?php echo $file->id+1;?>" name="demo_lbl_<?php echo $file->id+1;?>" class="css-label"></label></td>
                                    <td width="300" style="text-align:left; padding-left:30px;">
                                        <?php 
                                        if($file->created_by==Yii::app()->user->Id)
                                            echo '<img src="images/arrow_right.png" style="margin:0px 10px 0px 0px" />';
                                        else
                                            echo '<img src="images/arrow_left.png" style="margin:0px 10px 0px 0px" />';
                                            echo $file->title;
                                        ?>
                                        </td>
                                        <td width="300"><?php echo $file->file;?></td>
                                        <td width="80"><?php $parts	=	explode('/',$file->file_type); echo $parts[1].' file';?></td>
                                        <td width="150"><?php 
                                        if($file->created_by == Yii::app()->user->Id){
                                            echo Yii::t('app', 'You');
                                        }
                                        else
                                        {
                                            $posted_usr	=	Profile::model()->findByAttributes(array('user_id'=>$file->created_by));
                                            echo $posted_usr->firstname.' '.$posted_usr->lastname;
                                        }
                                        ?>
                                    </td>
                                </tr>
                            <?php
                            }
                        }
                        else
                        {
                        ?>
                        <tr>
                            <td colspan="5"><?php echo Yii::t('app', 'No files to download!');?></td>
                        </tr>
                        <?php
                        }
                        ?>
                        </table>
                        <div style="padding:13px 0px;">
                            <input type="submit" onclick="return validateForm();" value="<?php echo Yii::t('app', 'Download');?>" class="formbut" />
                        </div> <!-- END download button div -->
                    </div> <!-- END div class="inner_new_table" -->
                </form>
            </div>
            <!-- END Materials Area -->
            
        </div> <!-- END div class="parentright_innercon" -->
    </div> <!-- END div id="parent_rightSect" -->
    <div class="clear"></div>
</div> <!-- END div id="parent_Sect" -->
<div class="clear"></div>

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
		alert('<?php echo Yii::t('app', 'Select any file');?>');
		return false;
	}
}
</script>