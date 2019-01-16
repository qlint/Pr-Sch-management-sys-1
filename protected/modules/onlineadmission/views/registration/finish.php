<div class="container se_panel">
        <div class="row">
         	<div class="col-sm-12"> 
            <div class=" se_header">
    
        <?php $logo=Logo::model()->findAll();?>
        	<div class="col-sm-4 se_logo"><?php 
			if($logo!=NULL)
			{
				echo '<img src="'.Yii::app()->request->baseUrl.'/uploadedfiles/school_logo/'.$logo[0]->photo_file_name.'" alt="'.$logo[0]->photo_file_name.'" border="0" height="55" />';
			}
			?></div>
        <div class="col-sm-8 se_head"> <h2><?php echo Yii::t('app','Student Enrollment - THANK YOU'); ?></h2></div>
    </div>
    </div>
    </div>
    <div class="row"> 
        <div class="col-md-12">
            <div class="cnt_bg">       
                <div class="col-md-4">
                	<?php $this->renderPartial('_leftside');?>
                </div><!-- col-sm-6 -->
                <div class="col-md-8 col-p ">
					<?php $this->renderPartial('_wizard');?>
                    <?php 
                    $roles=Rights::getAssignedRoles(Yii::app()->user->Id);
                    $admin = User::model()->findByPk(1);
                    $school = Configurations::model()->findByPk(1); 
                    $student_data	=	Students::model()->findByPk($this->decryptToken($_REQUEST['token']));
                    ?>
                    <div>
                        <div class="wiz_right" style="min-height:500px;"> 
                            <h4 class="text-success"><?php echo Yii::t('app','ENROLLMENT FINISHED'); ?></h4>
                            <?php echo Yii::t('app','The application has been submitted to'); ?> <?php echo $school->config_value.' '.Yii::t('app','for further processing').'.'; ?></h2>
                            <br />
                            <?php echo Yii::t('app','Your application number is').' <strong>'.$student_data->registration_id; ?></strong><br />
                            <?php echo Yii::t('app','A message with the application summary and the Password will be send to the registered email').'.'; ?> 
                            <br /><?php echo Yii::t('app','Please make note of the information as it is needed for all communications with').' '.$school->config_value.' '.Yii::t('app','regarding this application').'.'; ?>				
                            <?php if(Yii::app()->user->id!=NULL and key($roles)!=NULL and (key($roles) == 'parent')) { ?>   
                            <br /><?php echo Yii::t('app','You can also go to the').' '; ?><b><i style="color:#D9534F"><?php echo Yii::t('app','ONLINE ADMISSION'); ?></i></b><?php echo ' '.Yii::t('app','tab in your').' '.$school->config_value.' '.Yii::t('app','account to know the status').'.'; ?>
                            <?php } ?>   
                            <?php echo Yii::t('app','If you have any questions about the application review process, we encourage you to contact us at').' '.$admin->email; ?><br />
                            <?php 
                            if(Yii::app()->user->id==NULL and Yii::app()->session['profile']==NULL){
                            echo CHtml::link(Yii::t('app','Register'), array('/onlineadmission/registration/index'),array('class'=>' btn btn-success pull-right'));
                            }
                            ?>
                        </div>
                	</div>
                </div><!-- col-sm-6 -->        
            </div>    
        </div>    
    </div>

    <!-- row -->
    
    <div class="signup-footer clearfix">     
        © <?php echo date('Y').'  '.Yii::app()->params['app_name']; ?> <?php echo Yii::t('app', 'All rights reserved');?>.
    </div>
</div>