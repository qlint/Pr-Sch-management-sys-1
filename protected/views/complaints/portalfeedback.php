<?php	
$roles	= Rights::getAssignedRoles(Yii::app()->user->Id); 
if(sizeof($roles)==1 and key($roles) == 'student'){
	$this->renderPartial('application.modules.studentportal.views.default.leftside'); 
}
if(sizeof($roles)==1 and key($roles) == 'parent'){
	$this->renderPartial('application.modules.parentportal.views.default.leftside'); 
}
if(sizeof($roles)==1 and key($roles) == 'teacher'){
	$this->renderPartial('application.modules.teachersportal.views.default.leftside'); 
}

$settings	= UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
if($settings == NULL){
	$settings	= UserSettings::model()->findByAttributes(array('user_id'=>1));
}
$feedbacks	= ComplaintFeedback::model()->findAllByAttributes(array('complaint_id'=>$_REQUEST['id']));
$complaint	= Complaints::model()->findByAttributes(array('id'=>$_REQUEST["id"]));
$category	= ComplaintCategories::model()->findByAttributes(array('id'=>$complaint->category_id)); 
?>

<div class="pageheader">
    <div class="col-lg-8">
    	<h2><i class="fa fa-comment"></i><?php echo Yii::t("app",'Complaints');?><span><?php echo Yii::t("app",'Complaint view');?></span></h2>
    </div>    
    <div class="breadcrumb-wrapper">
        <span class="label"><?php echo Yii::t("app",'You are here:');?></span>
        <ol class="breadcrumb">               
        	<li class="active"><?php echo Yii::t("app",'Complaints');?></li>
        </ol>
    </div>    
    <div class="clearfix"></div>
</div>
<div class="contentpanel"> 
	<div class="panel-heading">    
		<h3 class="panel-title"><?php echo Yii::t('app','Complaints');?></h3>


         
    </div>  

<div class="people-item">
<div class="opnsl_headerBox">
        <div class="opnsl_actn_box"> </div>
        <div class="opnsl_actn_box">
        <div class="opnsl_actn_box1">
				<?php
            	echo CHtml::link(Yii::t('app','Register a Complaint'),array('complaints/create','id'=>Yii::app()->user->id),array('class'=>'btn btn-primary'));
				?>
        </div>
        <div class="opnsl_actn_box1">
				<?php
           
				if($complaint->status == 0){
					echo CHtml::link(Yii::t("app",'Close'),array('complaints/close','id'=>$complaint->id),array('class'=>'btn btn-danger','confirm'=>Yii::t('app','Are you sure you want to close this Complaint ?'))); 
				}
				?>
        </div>
        <div class="opnsl_actn_box1">
			<?php
				if($complaint->status == 1){
					echo CHtml::link(Yii::t("app",'Reopen'),array('complaints/reopen','id'=>$complaint->id),array('class'=>'btn btn-success','confirm'=>Yii::t('app','Are you sure you want to reopen this Complaint ?'))); 		
				}
			?>
        </div>
        </div>
        
        </div>
 
    	<div class="row">
            <div class="col-md-4 col-4-reqst">
                <div class="portal-complain-box-one">
                    <table width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tbody>
                            <tr>
                                <td width="150"><?php echo Yii::t('app', 'Category'); ?></td>
                                <td width="30"><strong>:</strong></td>
                                <td>
<?php
									if($category != NULL){
										echo ucfirst($category->category);
									}
									else{
										echo '-';
									}
?>                                 
                                </td>                            
                            </tr>
                            <tr>
                                <td><?php echo Yii::t('app', 'Subject'); ?></td>
                                <td><strong>:</strong></td>
                                <td><?php echo ucfirst($complaint->subject); ?></td>
                            </tr> 
<?php
							if($complaint->status == 1){
?>
								<tr>
                                    <td><?php echo Yii::t('app', 'Closed By'); ?></td>
                                    <td><strong>:</strong></td>
                                    <td>
										<?php 
											$closed_by	= Complaints::model()->getName($complaint->closed_by);
											if($closed_by != NULL){
												echo $closed_by;
											}
											else{
												echo '-';
											}
										?>
                                    </td>
                                </tr>
<?php								
							}
?>                                                       
                            <tr>
                            	<td colspan="3"><?php echo Yii::t('app', 'Complaint'); ?></td>
                            </tr>
                            <tr>
                            	<td colspan="3"><div class="highlight-cmplnt"><?php echo ucfirst($complaint->complaint); ?></div></td>
                            </tr>        
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-8 col-4-reqst">
                <div class="portal-complain-box-two">
                	<div  id="comment"></div>
<?php
					if($feedbacks != NULL){	
?>                
                        <div class="complain-scroll  portl-cmplnt-area" id="comment_box">
                            <table class="" width="100%" cellspacing="0" cellpadding="0" border="0"> 
                                <tbody>
<?php
									foreach($feedbacks as $feedback){
										$commented_by 	= Complaints::model()->getName($feedback->uid);
										if($settings != NULL){
											$commented_date	= date($settings->displaydate, strtotime($feedback->date));										
										}
										else{
											$commented_date	= date('Y-m-d', strtotime($feedback->date));	
										}
										if($feedback->uid == Yii::app()->user->id){										
?>                                
                                            <tr>
                                                <td width="100%">
                                                    <table class="" width="100%" cellspacing="0" cellpadding="0" border="0">
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <div class="icon-left">
                                                                        <div class="chat_bg chat_bg-color">
                                                                            <div class="bottom-cht-hr">
                                                                                <h4><?php echo $commented_by; ?> <span class="green-strip"><?php echo $commented_date; ?></span></h4>
                                                                                <?php	
																					if($complaint->status == 0){ 
																						echo CHtml::link(
																							'<i class="fa fa-pencil"></i>',
																							'javascript:void(0);',
																							array(
																								'class'=>'help_class pull-right open_popup',
																								'data-ajax-url'=>$this->createUrl(
																									'/complaints/update',
																									array(
																										'id' =>$feedback->id,																															
																									)
																								),
																								'data-target'=>"#myModal",
																								'data-toggle'=>"modal",
																								'data-modal-label'=>Yii::t("app", "Update Comment"),
																								'data-modal-description'=>Yii::t("app", "Update your comment"),
																								'title'=>Yii::t('app','Update Comment')
																							)
																						);																						
																						echo CHtml::link(Yii::t('app','<i class="fa fa-trash-o"></i>'), "#", array('submit'=>array('delete','id'=>$feedback->id), 'title'=>'delete','class'=>'pull-right','style'=>'margin-right:7px','confirm'=>Yii::t('app','Are you sure you want to delete this comment ?'), 'csrf'=>true));
																					}
																				?>
                                                                                <!--<a title="edit" class="help_class pull-right" href="#" id="yt1"><i class="fa fa-pencil"></i></a><a title="delete" class="pull-right" style="margin-right:7px" href="#" id="yt2"><i class="fa fa-trash-o"></i></a>                                                                        -->
                                                                                <div class="triangle1-topleft"></div>
                                                                            </div>
                                                                            <p><?php echo ucfirst($feedback->feedback); ?></p>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td width="10"></td>
                                                                <td width=" 20"><img src="images/complaint-icon.png"></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>	
                                                </td>
                                            </tr>
<?php
										}
										else{
?>
											<tr>
                                                <td width="100%">
                                                    <table class="" width="100%" cellspacing="0" cellpadding="0" border="0">
                                                        <tbody><tr>                                                
                                                            <td width=" 20"><img src="images/complaint-icon.png"></td>
                                                            <td width="10"></td>                                                
                                                            <td>
                                                                <div class="icon-left">
                                                                    <div class="chat_bg chat_bg-color-gray">
                                                                        <div class="bottom-cht-hr2">
                                                                            <h4><?php echo $commented_by; ?> <span class="green-strip"><?php echo $commented_date; ?></span></h4>                                                                        
                                                                            <div class="triangle1-topright"></div>
                                                                        </div>
                                                                        <p><?php echo ucfirst($feedback->feedback); ?></p>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody></table>	
                                                </td>
                                            </tr>
<?php											
										}
									}
?>                                            
                                </tbody>
                            </table>    
                        </div>
<?php
					}
					if($complaint->status == 0){
						$form=$this->beginWidget('CActiveForm', array(
							'enableClientValidation'=>true,
							'clientOptions'=>array(
								'validateOnSubmit'=>true,
							),
						));
?>   
                            <div class="complain-chatng-box">
                                <table class="" width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="complaint-textarea-box">
                                                    <label><?php echo Yii::t('app', 'Comments'); ?></label>
<?php                                            
                                                    echo $form->textArea($model,'feedback',array('rows'=>4,'class'=>'form-control','placeholder'=>'Enter your comment here'));  
                                                    echo $form->error($model,'feedback' ); 
?>
                                                    
                                                </div>
                                                <div class="complain-btn">
                                                    <?php echo CHtml::submitButton(Yii::t("app",'Submit'),array('class'=>'btn btn-success')); ?>                                                
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div> 
<?php
						$this->endWidget();
					}
?>                                        
				</div>
			</div>                                                            
            
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function(){  
    $('#comment_box').scrollTop($('#comment_box')[0].scrollHeight);    	               
});

$('.btn-success').click(function(ev){
	$('.complaint-textarea-box').removeClass('success');
});
</script> 


