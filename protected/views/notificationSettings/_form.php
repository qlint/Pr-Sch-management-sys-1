<style type="text/css">
.msg{ display:inherit;}
</style>

<script type="text/javascript"> /* Checking and unchecking the SMS checkboxes. */
	$(document).ready(function(){
	
	 
	 $("#sms_all").change(function(){ /* Check/Uncheck all SMS functions on enabling/disabling of SMS All */
		  if (this.checked) {
			$('.sms').attr('checked', true);
		  }
		  else{
			$('.sms').attr('checked', false);
		  }
	  });
	  
	  $("#mail_all").change(function(){ /* Check/Uncheck all Mail functions on enabling/disabling of Mail All */
		  if (this.checked) {
			$('.mail').attr('checked', true);
		  }
		  else{
			$('.mail').attr('checked', false);
		  }
	  }); 
	  
	  $("#msg_all").change(function(){ /* Check/Uncheck all Message functions on enabling/disabling of Message All */
		  if (this.checked) {
			$('.msg').attr('checked', true);
		  }
		  else{
			$('.msg').attr('checked', false);
		  }
	  });
	  $("#student_all").change(function(){ /* Check/Uncheck all student functions on enabling/disabling of Student All */
		  if (this.checked) {
			$('.student').attr('checked', true);
		  }
		  else{
			$('.student').attr('checked', false);
		  }
	  });
	  
	  $("#parent_1_all").change(function(){ /* Check/Uncheck all parent 1 functions on enabling/disabling of parent 1 All */
		  if (this.checked) {
			$('.parent_1').attr('checked', true);
		  }
		  else{
			$('.parent_1').attr('checked', false);
		  }
	  });
	  
	  $("#employee_all").change(function(){ /* Check/Uncheck all employee functions on enabling/disabling of employee All */
		  if (this.checked) {
			$('.employee').attr('checked', true);
		  }
		  else{
			$('.employee').attr('checked', false);
		  }
	  });
	  
	  
	  $(".sms").change(function(){ /* Check/Uncheck SMS All on enabling/disabling of SMS */
	  		if($('.sms:checked').size() > 14)
			{
				$('#sms_all').attr('checked', true);
			}
			else
			{
				$('#sms_all').attr('checked', false);
			}
	  });
	  
	  $(".mail").change(function(){ /* Check/Uncheck Mail All on enabling/disabling of Mail */
	  		if($('.mail:checked').size() >15)
			{
				$('#mail_all').attr('checked', true);
			}
			else
			{
				$('#mail_all').attr('checked', false);
			}
	  });
	  
	   $(".msg").change(function(){ /* Check/Uncheck Message All on enabling/disabling of Message */
	  		if($('.msg:checked').size() >11 )
			{
				$('#msg_all').attr('checked', true);
			}
			else
			{
				$('#msg_all').attr('checked', false);
			}
	  });
	  
	  $(".student").change(function(){ /* Check/Uncheck student All on enabling/disabling of student */
	  		if($('.student:checked').size() >9 )
			{
				$('#student_all').attr('checked', true);
			}
			else
			{
				$('#student_all').attr('checked', false);
			}
	  });
	  
	  $(".parent_1").change(function(){ /* Check/Uncheck parent 1 All on enabling/disabling of parent 1 */
	  		if($('.parent_1:checked').size() >8 )
			{
				$('#parent_1_all').attr('checked', true);
			}
			else
			{
				$('#parent_1_all').attr('checked', false);
			}
	  });
	  
	  $(".employee").change(function(){ /* Check/Uncheck employee All on enabling/disabling of employee */
	  		if($('.employee:checked').size() >1 )
			{
				$('#employee_all').attr('checked', true);
			}
			else
			{
				$('#employee_all').attr('checked', false);
			}
	  });
	  
// Disable function
	
 }); 

function check(){
	$('.pdtab_Con tr:not(.pdtab-h)').each(function(index, element) {
		var row = $(this);
		if(row.find(".check_sms:checked").length || row.find(".check_mail:checked").length || row.find(".check_msg:checked").length){
			row.find(".check_role").removeAttr("disabled");
		}
		else{
			row.find(".check_role").prop("disabled",true);
		}
	});
}
 
$(window).load(function(e) {
	check();   
	$(".check_sms,.check_mail,.check_msg").change(function(){
		check();  
	}); 
});
 
 
</script>
<?php // Display Successfull message. 
    Yii::app()->clientScript->registerScript(
       'myHideEffect',
       '$(".flash-success").animate({opacity: 1.0}, 3000).fadeOut("slow");',
       CClientScript::POS_READY
    );
?>
 
<?php if(Yii::app()->user->hasFlash('notification')):?>
    <div class="flash-success" style="color:#F00; padding-left:150px; font-size:12px; font-weight:bold;">
        <?php echo Yii::app()->user->getFlash('notification'); ?>
    </div>
<?php endif; ?>

<div class="form">
<span style="color:#F00"><?php echo '*'.Yii::t('app','Please select recipients (Student, Guardians, Teacher) for SMS / Email / Message function.');?></span>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'notification-settings-form',
	'enableAjaxValidation'=>false,
)); ?>
	<?php echo $form->errorSummary($model); ?>
    
    <?php /*?><div>
        <table width="200px">
            <tr>
                <td class="check">
                  <?php $posts=NotificationSettings::model()->findAll(); 
                        if($posts[0]->is_enabled=='1'){ // Enable SMS for the application
                            echo $form->checkBox($model,'enable_app',array('id'=>'enable_app','checked'=>'true'));
                        }
                        else{
                            echo $form->checkBox($model,'enable_app',array('id'=>'enable_app')); 
                        }?>
                    <?php echo $form->error($model,'enable_app'); ?>
                </td>
                <td><?php echo Yii::t('app','Enable SMS');?></td>
            </tr>
        </table>
	</div><br /><?php */?>
     <?php $posts = NotificationSettings::model()->findAll();?>
    
     
    <div class="pdtab_Con">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tbody>
            <tr class="pdtab-h">
                <td style="padding-left:20px ;"><?php echo Yii::t('app','Function');?></td>
                <td>
                	<div style="float:left; padding-left:5px;">
                    	<?php 
						$sms_count = NotificationSettings::model()->countByAttributes(array('sms_enabled'=>1));
						
						if($sms_count >= 15)
						{
							echo $form->checkBox($model,'sms_all',array('id'=>'sms_all','checked'=>'true','class'=>'check_sms'));
						}
						else
						{
							echo $form->checkBox($model,'sms_all',array('id'=>'sms_all','class'=>'check_sms'));
						}
						?>
					</div>
                    <div style="float:left; padding-top:2px;padding-left:5px; text-align:center;"><?php echo Yii::t('app','SMS');?></div>
				</td>
                <td>
                	<div style="float:left; padding-left:5px;">
                    	<?php 
						$mail_count = NotificationSettings::model()->countByAttributes(array('mail_enabled'=>1));
						if($mail_count >= 16)
						{
							echo $form->checkBox($model,'mail_all',array('id'=>'mail_all','checked'=>'true','class'=>'check_mail'));
						}
						else
						{
							echo $form->checkBox($model,'mail_all',array('id'=>'mail_all','class'=>'check_mail'));
						}
						?>
                    	
					</div>
                    <div style="float:left; padding-top:2px;padding-left:5px;"><?php echo Yii::t('app','Email');?></div>
				</td>
                <td>
                	<div style="float:left; padding-left:5px;">
                        <?php 
						$msg_count = NotificationSettings::model()->countByAttributes(array('msg_enabled'=>1));
						if($msg_count >= 12)
						{
							echo $form->checkBox($model,'msg_all',array('id'=>'msg_all','checked'=>'true','class'=>'check_msg'));
						}
						else
						{
							echo $form->checkBox($model,'msg_all',array('id'=>'msg_all','class'=>'check_msg'));
						}
						?>
					</div>
                    <div style="float:left; padding-top:2px;padding-left:5px;"><?php echo Yii::t('app','Message');?></div></td>
                 
                 <td>
                	<div style="float:left; padding-left:5px;">
                    	<?php 
						$student_count = NotificationSettings::model()->countByAttributes(array('student'=>1));
												
						if($student_count >= 10)
						{
							echo $form->checkBox($model,'student_all',array('id'=>'student_all','checked'=>'true'));
						}
						else
						{
							echo $form->checkBox($model,'student_all',array('id'=>'student_all'));
						}
						?>
                    	
					</div>
                    <div style="float:left; padding-top:2px;padding-left:5px;"><?php echo Yii::t('app','Student');?></div>
				</td> 
                <td>
                	<div style="float:left; padding-left:5px;">
                    	<?php 
						$parent_1_count = NotificationSettings::model()->countByAttributes(array('parent_1'=>1));
						if($parent_1_count >= 9)
						{
							echo $form->checkBox($model,'parent_1_all',array('id'=>'parent_1_all','checked'=>'true'));
						}
						else
						{
							echo $form->checkBox($model,'parent_1_all',array('id'=>'parent_1_all'));
						}
						?>
                    	
					</div>
                    <div style="float:left; padding-top:2px;padding-left:5px;"><?php echo Yii::t('app','Guardian');?></div>
				</td>
                
                 <td>
                	<div style="float:left; padding-left:5px;">
                    	<?php 
						$employee_count = NotificationSettings::model()->countByAttributes(array('employee'=>1));						
						if($employee_count >= 2)
						{
							echo $form->checkBox($model,'employee_all',array('id'=>'employee_all','checked'=>'true'));
						}
						else
						{
							echo $form->checkBox($model,'employee_all',array('id'=>'employee_all'));
						}
						?>
                    	
					</div>
                    <div style="float:left; padding-top:2px;padding-left:5px;"><?php echo Yii::t('app','Teacher');?></div>
				</td>   
            </tr>
<!--Student Admission  -->	            
            <tr>
                <td style="padding-left:20px;"><strong><?php echo Yii::t('app','Student Admission');?></strong></td>
                <td style="text-align:center;">
                	<?php 
					if($posts[2]->sms_enabled=='1')
					{ 
						echo $form->checkBox($model,'sms_std_ad',array('class'=>'sms check_sms','checked'=>'true'));
					}
					else
					{
						echo $form->checkBox($model,'sms_std_ad',array('class'=>'sms check_sms')); 
					}
					?>
                	<?php echo $form->error($model,'sms_std_ad'); ?>
				</td>
                <td style="text-align:center;">
                	<?php 
					if($posts[2]->mail_enabled=='1')
					{ 
						echo $form->checkBox($model,'mail_std_ad',array('class'=>'mail check_mail','checked'=>'true'));
					}
					else
					{
						echo $form->checkBox($model,'mail_std_ad',array('class'=>'mail check_mail')); 
					}
					?>
                	<?php echo $form->error($model,'mail_std_ad'); ?>
				</td>
                <td style="text-align:center;">
                	<?php 
					if($posts[2]->msg_enabled=='1')
					{ 
						echo $form->checkBox($model,'msg_std_ad',array('class'=>'msg check_msg','checked'=>'true'));
					}
					else
					{
						echo $form->checkBox($model,'msg_std_ad',array('class'=>'msg check_msg')); 
					}
					?>
                	<?php echo $form->error($model,'msg_std_ad'); ?>
				</td>         
                <td style="text-align:center;">
                	<?php 
					if($posts[2]->student=='1')
					{ 
						echo $form->checkBox($model,'student_std_ad',array('class'=>'student check_role','checked'=>'true'));
					}
					else
					{
						echo $form->checkBox($model,'student_std_ad',array('class'=>'student check_role')); 
					}
					?>
                	<?php echo $form->error($model,'student_std_ad'); ?>
				</td>
                <td style="text-align:center;">
                	<?php 
					if($posts[2]->parent_1=='1')
					{ 
						echo $form->checkBox($model,'parent_1_std_ad',array('class'=>'parent_1 check_role','checked'=>'true'));
					}
					else
					{
						echo $form->checkBox($model,'parent_1_std_ad',array('class'=>'parent_1 check_role')); 
					}
					?>
                	<?php echo $form->error($model,'parent_1_std_ad'); ?>
				</td>                 
                <td style="text-align:center;"><?php echo "-";?></td>	              
            </tr>
<!-- Student Attendance -->   
            <tr>
                <td style="padding-left:20px;"><strong><?php echo Yii::t('app','Student Attendance');?></strong></td>
                <td style="text-align:center;">
                	<?php 
					if($posts[3]->sms_enabled=='1')
					{ 
						echo $form->checkBox($model,'sms_std_attnd',array('class'=>'sms check_sms','checked'=>'true'));
					}
					else
					{
						echo $form->checkBox($model,'sms_std_attnd',array('class'=>'sms check_sms')); 
					}
					?>
                	<?php echo $form->error($model,'sms_std_attnd'); ?>
				</td>
                <td style="text-align:center;">
                	<?php 
					if($posts[3]->mail_enabled=='1')
					{ 
						echo $form->checkBox($model,'mail_std_attnd',array('class'=>'mail check_mail','checked'=>'true'));
					}
					else
					{
						echo $form->checkBox($model,'mail_std_attnd',array('class'=>'mail check_mail')); 
					}
					?>
                	<?php echo $form->error($model,'mail_std_attnd'); ?>
				</td>
                <td style="text-align:center;">
                	<?php 
					if($posts[3]->msg_enabled=='1')
					{ 
						echo $form->checkBox($model,'msg_std_attnd',array('class'=>'msg check_msg','checked'=>'true'));
					}
					else
					{
						echo $form->checkBox($model,'msg_std_attnd',array('class'=>'msg check_msg')); 
					}
					?>
                	<?php echo $form->error($model,'msg_std_attnd'); ?>
				</td>
                <td style="text-align:center;"><?php echo "-";?></td>
                <td style="text-align:center;">
                	<?php 
					if($posts[3]->parent_1=='1')
					{ 
						echo $form->checkBox($model,'parent_1_std_attnd',array('class'=>'parent_1 check_role','checked'=>'true'));
					}
					else
					{
						echo $form->checkBox($model,'parent_1_std_attnd',array('class'=>'parent_1 check_role')); 
					}
					?>
                	<?php echo $form->error($model,'parent_1_std_attnd'); ?>
				</td>               
            	<td style="text-align:center;"><?php echo "-";?></td>    
            </tr>
 <!-- Teacher Appointment -->            
            <tr>
                <td style="padding-left:20px;"><strong><?php echo Yii::t('app','Teacher Appointment');?></strong></td>
                <td style="text-align:center;">
                	<?php 
					if($posts[4]->sms_enabled=='1')
					{ 
						echo $form->checkBox($model,'sms_emp_apmnt',array('class'=>'sms check_sms','checked'=>'true'));
					}
					else
					{
						echo $form->checkBox($model,'sms_emp_apmnt',array('class'=>'sms check_sms')); 
					}
					?>
                	<?php echo $form->error($model,'sms_emp_apmnt'); ?>
				</td>
                <td style="text-align:center;">
                	<?php 
					if($posts[4]->mail_enabled=='1')
					{ 
						echo $form->checkBox($model,'mail_emp_apmnt',array('class'=>'mail check_mail','checked'=>'true'));
					}
					else
					{
						echo $form->checkBox($model,'mail_emp_apmnt',array('class'=>'mail check_mail')); 
					}
					?>
                	<?php echo $form->error($model,'mail_emp_apmnt'); ?>
				</td>
                <td style="text-align:center;">
                	<?php 
					if($posts[4]->msg_enabled=='1')
					{ 
						echo $form->checkBox($model,'msg_emp_apmnt',array('class'=>'msg check_msg','checked'=>'true'));
					}
					else
					{
						echo $form->checkBox($model,'msg_emp_apmnt',array('class'=>'msg check_msg')); 
					}
					?>
                	<?php echo $form->error($model,'msg_emp_apmnt'); ?>
				</td>             
               	<td style="text-align:center;"><?php echo "-";?></td>
                <td style="text-align:center;"><?php echo "-";?></td>                             
                <td style="text-align:center;">
                	<?php 
					if($posts[4]->employee=='1')
					{ 
						echo $form->checkBox($model,'employee_emp_apmnt',array('class'=>'employee check_role','checked'=>'true'));
					}
					else
					{
						echo $form->checkBox($model,'employee_emp_apmnt',array('class'=>'employee check_role')); 
					}
					?>
                	<?php echo $form->error($model,'employee_emp_apmnt'); ?>
				</td>       
            </tr>
<!-- Exam Schedule -->            
            <tr>
                <td style="padding-left:20px;"><strong><?php echo Yii::t('app','Exam Schedule');?></strong></td>
                <td style="text-align:center;">
                	<?php 
					if($posts[5]->sms_enabled=='1')
					{
						echo $form->checkBox($model,'sms_exm_schedule',array('class'=>'sms check_sms','checked'=>'true'));
					}
					else
					{
						echo $form->checkBox($model,'sms_exm_schedule',array('class'=>'sms check_sms')); 
					}
					?>
                	<?php echo $form->error($model,'sms_exm_schedule'); ?>
				</td>
                <td style="text-align:center;">
                	<?php 
					if($posts[5]->mail_enabled=='1')
					{ 
						echo $form->checkBox($model,'mail_exm_schedule',array('class'=>'mail check_mail','checked'=>'true'));
					}
					else
					{
						echo $form->checkBox($model,'mail_exm_schedule',array('class'=>'mail check_mail')); 
					}
					?>
                	<?php echo $form->error($model,'mail_exm_schedule'); ?>
				</td>
                <td style="text-align:center;">
                	<?php 
					if($posts[5]->msg_enabled=='1')
					{
						echo $form->checkBox($model,'msg_exm_schedule',array('class'=>'msg check_msg','checked'=>'true'));
					}
					else
					{
						echo $form->checkBox($model,'msg_exm_schedule',array('class'=>'msg check_msg')); 
					}
					?>
                	<?php echo $form->error($model,'msg_exm_schedule'); ?>
				</td>            
                <td style="text-align:center;">
                	<?php 
					if($posts[5]->student=='1')
					{ 
						echo $form->checkBox($model,'student_exm_schedule',array('class'=>'student check_role','checked'=>'true'));
					}
					else
					{
						echo $form->checkBox($model,'student_exm_schedule',array('class'=>'student check_role')); 
					}
					?>
                	<?php echo $form->error($model,'student_exm_schedule'); ?>
				</td>
                <td style="text-align:center;"><?php echo "-";?></td>               
                <td style="text-align:center;"><?php echo "-";?></td>           
            </tr>
<!-- Exam Result -->            
            <tr>
                <td style="padding-left:20px;"><strong><?php echo Yii::t('app','Exam Result');?></strong></td>
                <td style="text-align:center;">
                	<?php 
					if($posts[6]->sms_enabled=='1')
					{ 
						echo $form->checkBox($model,'sms_exm_result',array('class'=>'sms check_sms','checked'=>'true'));
					}
					else
					{
						echo $form->checkBox($model,'sms_exm_result',array('class'=>'sms check_sms')); 
					}
					?>
                	<?php echo $form->error($model,'sms_exm_result'); ?>
				</td>
                <td style="text-align:center;">
                	<?php 
					if($posts[6]->mail_enabled=='1')
					{ 
						echo $form->checkBox($model,'mail_exm_result',array('class'=>'mail check_mail','checked'=>'true'));
					}
					else
					{
						echo $form->checkBox($model,'mail_exm_result',array('class'=>'mail check_mail')); 
					}
					?>
                	<?php echo $form->error($model,'mail_exm_result'); ?>
				</td>
                <td style="text-align:center;">
                	<?php 
					if($posts[6]->msg_enabled=='1')
					{
						echo $form->checkBox($model,'msg_exm_result',array('class'=>'msg check_msg','checked'=>'true'));
					}
					else
					{
						echo $form->checkBox($model,'msg_exm_result',array('class'=>'msg check_msg')); 
					}
					?>
                	<?php echo $form->error($model,'msg_exm_result'); ?>
				</td>       
                <td style="text-align:center;">
                	<?php 
					if($posts[6]->student=='1')
					{ 
						echo $form->checkBox($model,'student_exm_result',array('class'=>'student check_role','checked'=>'true'));
					}
					else
					{
						echo $form->checkBox($model,'student_exm_result',array('class'=>'student check_role')); 
					}
					?>
                	<?php echo $form->error($model,'student_exm_result'); ?>
				</td>
                <td style="text-align:center;"><?php echo "-";?></td>                
                <td style="text-align:center;"><?php echo "-";?></td>              
            </tr>
<!-- Fees -->            
            <tr>
                <td style="padding-left:20px;"><strong><?php echo Yii::t('app','Fees');?></strong></td>
                <td style="text-align:center;">
                	<?php 
					if($posts[7]->sms_enabled=='1')
					{ 
						echo $form->checkBox($model,'sms_fees',array('class'=>'sms check_sms','checked'=>'true'));
					}
					else
					{
						echo $form->checkBox($model,'sms_fees',array('class'=>'sms check_sms')); 
					}
					?>
                	<?php echo $form->error($model,'sms_fees'); ?>
				</td>
                <td style="text-align:center;">
                	<?php 
					if($posts[7]->mail_enabled=='1')
					{ 
						echo $form->checkBox($model,'mail_fees',array('class'=>'mail check_mail','checked'=>'true'));
					}
					else
					{
						echo $form->checkBox($model,'mail_fees',array('class'=>'mail check_mail')); 
					}
					?>
                	<?php echo $form->error($model,'mail_fees'); ?>
				</td>
                <td style="text-align:center;">
                	<?php 
					if($posts[7]->msg_enabled=='1')
					{ 
						echo $form->checkBox($model,'msg_fees',array('class'=>'msg check_msg','checked'=>'true'));
					}
					else
					{
						echo $form->checkBox($model,'msg_fees',array('class'=>'msg check_msg')); 
					}
					?>
                	<?php echo $form->error($model,'msg_fees'); ?>
				</td>            
                <td style="text-align:center;"><?php echo "-";?></td>
                <td style="text-align:center;">
                	<?php 
					if($posts[7]->parent_1=='1')
					{ 
						echo $form->checkBox($model,'parent_1_fees',array('class'=>'parent_1 check_role','checked'=>'true'));
					}
					else
					{
						echo $form->checkBox($model,'parent_1_fees',array('class'=>'parent_1 check_role')); 
					}
					?>
                	<?php echo $form->error($model,'parent_1_fees'); ?>
				</td>                 
                <td style="text-align:center;"><?php echo "-";?></td>           
            </tr>
<!-- Library -->            
            <tr>
                <td style="padding-left:20px;"><strong><?php echo Yii::t('app','Library');?></strong></td>
                <td style="text-align:center;">
                	<?php 
					if($posts[8]->sms_enabled=='1')
					{ 
						echo $form->checkBox($model,'sms_library',array('class'=>'sms check_sms','checked'=>'true'));
					}
					else
					{
						echo $form->checkBox($model,'sms_library',array('class'=>'sms check_sms')); 
					}
					?>
                	<?php echo $form->error($model,'sms_library'); ?>
				</td>
                <td style="text-align:center;">
                	<?php 
					if($posts[8]->mail_enabled=='1')
					{ 
						echo $form->checkBox($model,'mail_library',array('class'=>'mail check_mail','checked'=>'true'));
					}
					else
					{
						echo $form->checkBox($model,'mail_library',array('class'=>'mail check_mail')); 
					}
					?>
                	<?php echo $form->error($model,'mail_library'); ?>
				</td>
                <td style="text-align:center;">
                	<?php 
					if($posts[8]->msg_enabled=='1')
					{ 
						echo $form->checkBox($model,'msg_library',array('class'=>'msg check_msg','checked'=>'true'));
					}
					else
					{
						echo $form->checkBox($model,'msg_library',array('class'=>'msg check_msg')); 
					}
					?>
                	<?php echo $form->error($model,'msg_library'); ?>
				</td>                            
                <td style="text-align:center;">
                	<?php 
					if($posts[8]->student=='1')
					{ 
						echo $form->checkBox($model,'student_library',array('class'=>'student check_role','checked'=>'true'));
					}
					else
					{
						echo $form->checkBox($model,'student_library',array('class'=>'student check_role')); 
					}
					?>
                	<?php echo $form->error($model,'student_library'); ?>
				</td>
                <td style="text-align:center;"><?php echo "-";?></td>                
                <td style="text-align:center;"><?php echo "-";?></td>      
            </tr>
<!-- Student Log -->            
            <tr>
                <td style="padding-left:20px;"><strong><?php echo Yii::t('app','Student Log');?></strong></td>
                <td style="text-align:center;">
                	<?php 
					if($posts[10]->sms_enabled=='1')
					{ 
						echo $form->checkBox($model,'sms_student_log',array('class'=>'sms check_sms','checked'=>'true'));
					}
					else
					{
						echo $form->checkBox($model,'sms_student_log',array('class'=>'sms check_sms')); 
					}
					?>
                	<?php echo $form->error($model,'sms_student_log'); ?>
				</td>
                <td style="text-align:center;">
                	<?php 
					if($posts[10]->mail_enabled=='1')
					{ 
						echo $form->checkBox($model,'mail_student_log',array('class'=>'mail check_mail','checked'=>'true'));
					}
					else
					{
						echo $form->checkBox($model,'mail_student_log',array('class'=>'mail check_mail')); 
					}
					?>
                	<?php echo $form->error($model,'mail_student_log'); ?>
				</td>
                <td style="text-align:center;">
                	<?php 
					if($posts[10]->msg_enabled=='1')
					{ 
						echo $form->checkBox($model,'msg_student_log',array('class'=>'msg check_msg','checked'=>'true'));
					}
					else
					{
						echo $form->checkBox($model,'msg_student_log',array('class'=>'msg check_msg')); 
					}
					?>
                	<?php echo $form->error($model,'msg_student_log'); ?>
				</td>             
                <td style="text-align:center;">
                	<?php 
					if($posts[10]->student=='1')
					{ 
						echo $form->checkBox($model,'student_student_log',array('class'=>'student check_role','checked'=>'true'));
					}
					else
					{
						echo $form->checkBox($model,'student_student_log',array('class'=>'student check_role')); 
					}
					?>
                	<?php echo $form->error($model,'student_student_log'); ?>
				</td>
                <td style="text-align:center;">
                	<?php 
					if($posts[10]->parent_1=='1')
					{ 
						echo $form->checkBox($model,'parent_1_student_log',array('class'=>'parent_1 check_role','checked'=>'true'));
					}
					else
					{
						echo $form->checkBox($model,'parent_1_student_log',array('class'=>'parent_1 check_role')); 
					}
					?>
                	<?php echo $form->error($model,'parent_1_student_log'); ?>
				</td>                
                <td style="text-align:center;"><?php echo "-";?></td>     
            </tr>
<!-- User Creation -->            
            <tr>
                <td style="padding-left:20px;"><strong><?php echo Yii::t('app','User Creation');?></strong></td>                
                <td style="text-align:center;"><?php echo "-";?></td>
                <td style="text-align:center;">
                	<?php 
					if($posts[11]->mail_enabled=='1')
					{ 
						echo $form->checkBox($model,'mail_user',array('class'=>'mail check_mail','checked'=>'true'));
					}
					else
					{
						echo $form->checkBox($model,'mail_user',array('class'=>'mail check_mail')); 
					}
					?>
                	<?php echo $form->error($model,'mail_user'); ?>
				</td>
                <td style="text-align:center;">
                	<?php 
					if($posts[11]->msg_enabled=='1')
					{
						echo $form->checkBox($model,'msg_user',array('class'=>'msg check_msg','checked'=>'true'));
					}
					else
					{
						echo $form->checkBox($model,'msg_user',array('class'=>'msg check_msg')); 
					}
					?>
                	<?php echo $form->error($model,'msg_user'); ?>
				</td>       
        		<td style="text-align:center;"><?php echo "-";?></td>                 
                <td style="text-align:center;"><?php echo "-";?></td>                     
                <td style="text-align:center;"><?php echo "-";?></td>            
            </tr>
<!-- Online admission -->            
    		<tr>
                <td style="padding-left:20px;"><strong><?php echo Yii::t('app','Online Admission');?></strong></td>
                <td style="text-align:center;">
                	<?php 
					if($posts[12]->sms_enabled=='1')
					{ 
						echo $form->checkBox($model,'sms_online_admission',array('class'=>'sms check_sms','checked'=>'true'));
					}
					else
					{
						echo $form->checkBox($model,'sms_online_admission',array('class'=>'sms check_sms')); 
					}
					?>
                	<?php echo $form->error($model,'sms_online_admission'); ?>
				</td>
                <td style="text-align:center;">
                	<?php 
					if($posts[12]->mail_enabled=='1')
					{ 
						echo $form->checkBox($model,'mail_online_admission',array('class'=>'mail check_mail','checked'=>'true'));
					}
					else
					{
						echo $form->checkBox($model,'mail_online_admission',array('class'=>'mail check_mail')); 
					}
					?>
                	<?php echo $form->error($model,'mail_online_admission'); ?>
				</td> 
                <td style="text-align:center;"><?php echo "-";?></td>                   
                <td style="text-align:center;">
                	<?php 
					if($posts[12]->student=='1')
					{ 
						echo $form->checkBox($model,'student_online_admission',array('class'=>'student check_role','checked'=>'true'));
					}
					else
					{
						echo $form->checkBox($model,'student_online_admission',array('class'=>'student check_role')); 
					}
					?>
                	<?php echo $form->error($model,'student_online_admission'); ?>
				</td>
                <td style="text-align:center;">
                	<?php 
					if($posts[12]->parent_1=='1')
					{ 
						echo $form->checkBox($model,'parent_1_online_admission',array('class'=>'parent_1 check_role','checked'=>'true'));
					}
					else
					{
						echo $form->checkBox($model,'parent_1_online_admission',array('class'=>'parent_1 check_role')); 
					}
					?>
                	<?php echo $form->error($model,'parent_1_online_admission'); ?>
				</td>                 
                <td style="text-align:center;"><?php echo "-";?></td>       
             </tr> 
<!-- Online Admission Approval -->             
             <tr>
                <td style="padding-left:20px;"><strong><?php echo Yii::t('app','Online Admission Approval');?></strong></td>
                <td style="text-align:center;">
                	<?php 
					if($posts[13]->sms_enabled=='1')
					{ // Enable Fees SMS
						echo $form->checkBox($model,'sms_online_admission_approval',array('class'=>'sms check_sms','checked'=>'true'));
					}
					else
					{
						echo $form->checkBox($model,'sms_online_admission_approval',array('class'=>'sms check_sms')); 
					}
					?>
                	<?php echo $form->error($model,'sms_online_admission_approval'); ?>
				</td>
                <td style="text-align:center;">
                	<?php 
					if($posts[13]->mail_enabled=='1')
					{ // Enable Fees Mail
						echo $form->checkBox($model,'mail_online_admission_approval',array('class'=>'mail check_mail','checked'=>'true'));
					}
					else
					{
						echo $form->checkBox($model,'mail_online_admission_approval',array('class'=>'mail check_mail')); 
					}
					?>
                	<?php echo $form->error($model,'mail_online_admission_approval'); ?>
				</td>
                <td style="text-align:center;">
                	<?php 
					if($posts[13]->msg_enabled=='1')
					{
						echo $form->checkBox($model,'msg_online_admission_approval',array('class'=>'msg check_msg','checked'=>'true'));
					}
					else
					{
						echo $form->checkBox($model,'msg_online_admission_approval',array('class'=>'msg check_msg')); 
					}
					?>
                	<?php echo $form->error($model,'msg_online_admission_approval'); ?>
				</td>
                 <td style="text-align:center;">
                	<?php 
					if($posts[13]->student=='1')
					{ 
						echo $form->checkBox($model,'student_online_admission_approval',array('class'=>'student check_role','checked'=>'true'));
					}
					else
					{
						echo $form->checkBox($model,'student_online_admission_approval',array('class'=>'student check_role')); 
					}
					?>
                	<?php echo $form->error($model,'student_online_admission_approval'); ?>
				</td>
                <td style="text-align:center;">
                	<?php 
					if($posts[13]->parent_1=='1')
					{ 
						echo $form->checkBox($model,'parent_1_online_admission_approval',array('class'=>'parent_1 check_role','checked'=>'true'));
					}
					else
					{
						echo $form->checkBox($model,'parent_1_online_admission_approval',array('class'=>'parent_1 check_role')); 
					}
					?>
                	<?php echo $form->error($model,'parent_1_online_admission_approval'); ?>
				</td>                 
                <td style="text-align:center;"><?php echo "-";?></td> 
            </tr> 
<!-- Application status change -->        
       		   <tr>
                <td style="padding-left:20px;"><strong><?php echo Yii::t('app','Application Status Change');?></strong></td>
                <td style="text-align:center;">
                	<?php 
					if($posts[14]->sms_enabled=='1')
					{ 
						echo $form->checkBox($model,'sms_application_status_change',array('class'=>'sms check_sms','checked'=>'true'));
					}
					else
					{
						echo $form->checkBox($model,'sms_application_status_change',array('class'=>'sms check_sms')); 
					}
					?>
                	<?php echo $form->error($model,'sms_application_status_change'); ?>
				</td>
                <td style="text-align:center;">
                	<?php 
					if($posts[14]->mail_enabled=='1')
					{ 
						echo $form->checkBox($model,'mail_application_status_change',array('class'=>'mail check_mail','checked'=>'true'));
					}
					else
					{
						echo $form->checkBox($model,'mail_application_status_change',array('class'=>'mail check_mail')); 
					}
					?>
                	<?php echo $form->error($model,'mail_application_status_change'); ?>
				</td>
                <td style="text-align:center;"><?php echo "-";?></td>
                <td style="text-align:center;">
                	<?php 
					if($posts[14]->student=='1')
					{ 
						echo $form->checkBox($model,'student_application_status_change',array('class'=>'student check_role','checked'=>'true'));
					}
					else
					{
						echo $form->checkBox($model,'student_application_status_change',array('class'=>'student check_role')); 
					}
					?>
                	<?php echo $form->error($model,'student_application_status_change'); ?>
				</td>
                <td style="text-align:center;">
                	<?php 
					if($posts[14]->parent_1=='1')
					{ 
						echo $form->checkBox($model,'parent_1_application_status_change',array('class'=>'parent_1 check_role','checked'=>'true'));
					}
					else
					{
						echo $form->checkBox($model,'parent_1_application_status_change',array('class'=>'parent_1 check_role')); 
					}
					?>
                	<?php echo $form->error($model,'parent_1_application_status_change'); ?>
				</td>
                 
                <td style="text-align:center;"><?php echo "-";?></td> 
               </tr>
<!-- Hostel -->               
           		<tr>
                <td style="padding-left:20px;"><strong><?php echo Yii::t('app','Hostel');?></strong></td>
                <td style="text-align:center;">
                	<?php 
					if($posts[16]->sms_enabled=='1')
					{
						echo $form->checkBox($model,'sms_hostel',array('class'=>'sms check_sms','checked'=>'true'));
					}
					else
					{
						echo $form->checkBox($model,'sms_hostel',array('class'=>'sms check_sms')); 
					}
					?>
                	<?php echo $form->error($model,'sms_hostel'); ?>
				</td>
                <td style="text-align:center;">
                	<?php 
					if($posts[16]->mail_enabled=='1')
					{ 
						echo $form->checkBox($model,'mail_hostel',array('class'=>'mail check_mail','checked'=>'true'));
					}
					else
					{
						echo $form->checkBox($model,'mail_hostel',array('class'=>'mail check_mail')); 
					}
					?>
                	<?php echo $form->error($model,'mail_hostel'); ?>
				</td>
                <td style="text-align:center;">
                	<?php 
					if($posts[16]->msg_enabled=='1')
					{
						echo $form->checkBox($model,'msg_hostel',array('class'=>'msg check_msg','checked'=>'true'));
					}
					else
					{
						echo $form->checkBox($model,'msg_hostel',array('class'=>'msg check_msg')); 
					}
					?>
                	<?php echo $form->error($model,'msg_hostel'); ?>
				</td>            
                <td style="text-align:center;">
                	<?php 
					if($posts[16]->student=='1')
					{ 
						echo $form->checkBox($model,'student_hostel',array('class'=>'student check_role','checked'=>'true'));
					}
					else
					{
						echo $form->checkBox($model,'student_hostel',array('class'=>'student check_role')); 
					}
					?>
                	<?php echo $form->error($model,'student_hostel'); ?>
				</td>
                <td style="text-align:center;"><?php echo "-";?></td>               
                <td style="text-align:center;"><?php echo "-";?></td>           
            </tr>     
<!-- Public Holiday -->                             
       		   <tr>
                <td style="padding-left:20px;"><strong><?php echo Yii::t('app','Public Holidays');?></strong></td>
                <td style="text-align:center;">
                	<?php 
					if($posts[15]->sms_enabled=='1')
					{
						echo $form->checkBox($model,'sms_public_holidays',array('class'=>'sms check_sms','checked'=>'true'));
					}
					else
					{
						echo $form->checkBox($model,'sms_public_holidays',array('class'=>'sms check_sms')); 
					}
					?>
                	<?php echo $form->error($model,'sms_public_holidays'); ?>
				</td>
                <td style="text-align:center;">
                	<?php 
					if($posts[15]->mail_enabled=='1')
					{ 
						echo $form->checkBox($model,'mail_public_holidays',array('class'=>'mail check_mail','checked'=>'true'));
					}
					else
					{
						echo $form->checkBox($model,'mail_public_holidays',array('class'=>'mail check_mail')); 
					}
					?>
                	<?php echo $form->error($model,'mail_public_holidays'); ?>
				</td>
                <td style="text-align:center;">
                	<?php 
					if($posts[15]->msg_enabled=='1')
					{
						echo $form->checkBox($model,'msg_public_holidays',array('class'=>'msg check_msg','checked'=>'true'));
					}
					else
					{
						echo $form->checkBox($model,'msg_public_holidays',array('class'=>'msg check_msg')); 
					}
					?>
                	<?php echo $form->error($model,'msg_public_holidays'); ?>
				</td>
                <td style="text-align:center;">
                	<?php 
					if($posts[15]->student=='1')
					{ 
						echo $form->checkBox($model,'student_public_holidays',array('class'=>'student check_role','checked'=>'true'));
					}
					else
					{
						echo $form->checkBox($model,'student_public_holidays',array('class'=>'student check_role')); 
					}
					?>
                	<?php echo $form->error($model,'student_public_holidays'); ?>
				</td>
                <td style="text-align:center;">
                	<?php 
					if($posts[15]->parent_1=='1')
					{ 
						echo $form->checkBox($model,'parent_1_public_holidays',array('class'=>'parent_1 check_role','checked'=>'true'));
					}
					else
					{
						echo $form->checkBox($model,'parent_1_public_holidays',array('class'=>'parent_1 check_role')); 
					}
					?>
                	<?php echo $form->error($model,'parent_1_public_holidays'); ?>
				</td>
                 
                <td style="text-align:center;">
                	<?php 
					if($posts[15]->employee=='1')
					{ 
						echo $form->checkBox($model,'employee_public_holidays',array('class'=>'employee check_role','checked'=>'true'));
					}
					else
					{
						echo $form->checkBox($model,'employee_public_holidays',array('class'=>'employee check_role')); 
					}
					?>
                	<?php echo $form->error($model,'employee_public_holidays'); ?>
				</td>
               </tr> 
               
               <tr>
                <td style="padding-left:20px;"><strong><?php echo Yii::t('app','Missing Documents');?></strong></td>
                <td style="text-align:center;">
                	<?php 
					
					if($posts[18]->sms_enabled=='1')
					{ 
						echo $form->checkBox($model,'sms_document',array('class'=>'sms check_sms','checked'=>'true'));
					}
					else
					{
						echo $form->checkBox($model,'sms_document',array('class'=>'sms check_sms')); 
					}
					?>
                	<?php echo $form->error($model,'sms_document'); ?>
				</td>
                <td style="text-align:center;">
                	<?php 
					if($posts[18]->mail_enabled=='1')
					{ 
						echo $form->checkBox($model,'mail_document',array('class'=>'mail check_mail','checked'=>'true'));
					}
					else
					{
						echo $form->checkBox($model,'mail_document',array('class'=>'mail check_mail')); 
					}
					?>
                	<?php echo $form->error($model,'mail_document'); ?>
				</td>
               <td style="text-align:center;"><?php echo "-";?></td>
                <td style="text-align:center;"><?php echo "-";?></td> 
                <td style="text-align:center;">
                	<?php
					
					if($posts[18]->parent_1=='1')
					{ 
						echo $form->checkBox($model,'parent_1_document',array('class'=>'parent_1 check_role','checked'=>'true'));
					}
					else
					{
						echo $form->checkBox($model,'parent_1_document',array('class'=>'parent_1 check_role')); 
					}
					?>
                	<?php echo $form->error($model,'parent_1_document'); ?>
				</td>               
            	<td style="text-align:center;"><?php echo "-";?></td>    
            </tr>  
            
            <tr>
                <td style="padding-left:20px;"><strong><?php echo Yii::t('app','User Login');?></strong></td>
                <td style="text-align:center;">
                	<?php 
					
					if($posts[19]->sms_enabled=='1')
					{ 
						echo $form->checkBox($model,'sms_userlogin',array('class'=>'sms check_sms','checked'=>'true'));
					}
					else
					{
						echo $form->checkBox($model,'sms_userlogin',array('class'=>'sms check_sms')); 
					}
					?>
                	<?php echo $form->error($model,'sms_userlogin'); ?>
				</td>
                <td style="text-align:center;">
                	<?php 
					if($posts[19]->mail_enabled=='1')
					{ 
						echo $form->checkBox($model,'mail_userlogin',array('class'=>'mail check_mail','checked'=>'true'));
					}
					else
					{
						echo $form->checkBox($model,'mail_userlogin',array('class'=>'mail check_mail')); 
					}
					?>
                	<?php echo $form->error($model,'mail_userlogin'); ?>
				</td>
               <td style="text-align:center;"><?php echo "-";?></td>
                <td style="text-align:center;"><?php echo "-";?></td>
                <td style="text-align:center;"><?php echo "-";?></td>
            	<td style="text-align:center;"><?php echo "-";?></td>    
            </tr>  
        </tbody>            
        </table>
        <br />
        <div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Save Settings') : Yii::t('app','Save'),array('class'=>'formbut')); ?>
		</div>
    </div>
<?php $this->endWidget(); ?>

</div><!-- form -->