<div class="container se_panel">
    <div class="row">
        <div class="col-sm-12"> 
            <div class=" se_header"> 	      
				<?php $logo	= Logo::model()->findAll();?>
                <div class="col-sm-4 se_logo">
					<?php 
						if($logo != NULL){
							echo '<img src="'.Yii::app()->request->baseUrl.'/uploadedfiles/school_logo/'.$logo[0]->photo_file_name.'" alt="'.$logo[0]->photo_file_name.'" border="0" height="55" />';
						}
					?>
                </div>
                <div class="col-sm-8 se_head"> <h2><?php echo Yii::t('app','Student Enrolment - Student Information'); ?></h2></div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="cnt_bg">           
                <div class="col-md-4"><?php $this->renderPartial('_leftside'); ?></div>                
                <div class="col-md-8 col-p">
					<?php $this->renderPartial('_wizard');?>                
                    <?php $this->renderPartial('_step1', array('model'=>$model,'course'=>$course,'batch_id'=>$batch_id));?>
                </div>
            </div>
        </div> 
    </div>
        
    <div class="signup-footer">    
    	© <?php echo date('Y').'  '.Yii::app()->params['app_name']; ?> <?php echo Yii::t('app', 'All rights reserved');?>    
    </div>        
</div>