<?php
    $this->breadcrumbs=array(
            Yii::t('app','Online Examination'),
    );
    
    //all exams
    $criteria               =   new CDbCriteria;
    $criteria->condition    =   'is_deleted=:is_deleted';                        
    $criteria->params[':is_deleted'] = 0;
    $total_exams            =   OnlineExams::model()->count($criteria);
    
    //published exams
    $criteria               =   new CDbCriteria;
    $criteria->condition    =   'is_deleted=:is_deleted AND status=:status';                        
    $criteria->params[':is_deleted']    = 0;
    $criteria->params[':status']        = 3;
    $total_published        =   OnlineExams::model()->count($criteria);    
    
    $criteria               =   new CDbCriteria;
    $criteria->condition    =   'is_deleted=:is_deleted';                        
    $criteria->params[':is_deleted'] = 0;
    $criteria->order        =   'id DESC';
    $criteria->limit        =   10;
    $exams_list             =   OnlineExams::model()->findAll($criteria);
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">        
        	<?php $this->renderPartial('/default/admin_left');?>        
        </td>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td valign="top" width="75%">
                        <div class="cont_right formWrapper">
                            <h1><?php echo Yii::t('app','Online Examination'); ?></h1>                            
                            <div class="overview" style="padding-top:0px;">
                                <div class="overviewbox ovbox1" style="margin-left:0px;">
                                    <h1><strong><?php echo Yii::t('app','Total Exams'); ?></strong></h1>
                                    <div class="ovrBtm">
                                    	<?php echo $total_exams;?>
                                    </div>
                                </div>
                                <div class="overviewbox ovbox2">
                                    <h1><strong><?php echo Yii::t('app','Result Published Exams'); ?></strong></h1>
                                    <div class="ovrBtm">
                                    	<?php echo $total_published;?>
                                    </div>
                                </div>
                                <div class="clear"></div>                            
                            </div>
                            
                            <?php if(Yii::app()->user->hasFlash('error')):?>
                            <div class="status_box" style="width:598px; margin:40px 0 0;">
                                <div class="sb_icon"></div>
                                <span style="color:#FF0D50"><?php echo Yii::app()->user->getFlash('error'); ?></span>
                            </div>
                            <?php endif; ?>
                            
                            <?php if(Yii::app()->user->hasFlash('success')):?>
                            <div class="status_box" style="width:598px; margin:40px 0 0;">
                                <div class="sb_icon"></div>
                                <span style="color:#39934E"><?php echo Yii::app()->user->getFlash('success'); ?></span>
                            </div>
                            <?php endif; ?>
                            
                            <div style="width:97%;" class="pdtab_Con">
                                <div style="font-size:13px; padding:5px 0px">
                                    <strong><?php echo Yii::t('app', 'Recent Exams');?></strong>
                                </div>
                            	<table width="100%" cellspacing="0" cellpadding="0" border="0">
                                      <tbody>
                                      	<tr class="pdtab-h">
                                            <td align="center"><?php echo Yii::t('app', 'Sl No');?></td>                                            
                                            <td height="18" align="center"><?php echo OnlineExams::model()->getAttributeLabel('name');?></td>
                                            <td align="center"><?php echo Yii::t('app', 'Course'); ?></td>
                                            <td align="center"><?php echo OnlineExams::model()->getAttributeLabel('batch_id');?></td>
                                            <td align="center"><?php echo Yii::t('app', 'Status');?></td>
                                            
                                         </tr>
                                         <?php
                                        foreach($exams_list as $key=>$exam){
                                            $class	= '';
                                            if($key%2==1)
                                                $class = 'alt';
                                        ?>
                                        <tr class="<?php echo $class;?>">
                                            <td align="center"><?php echo $key+1;?></td>
                                            <td align="center"><?php echo $exam->name;?></td>
                                            <td align="center">
                                            <?php 
                                                $batch=Batches::model()->findByAttributes(array('id'=>$exam->batch_id,'is_active'=>1,'is_deleted'=>0));                                                
                                                echo ucfirst($batch->course);?></td>
                                            <td align="center"><?php echo $exam->batch;?></td>
                                            <td align="center">
                                                <?php
                                                    switch($exam->status){
                                                        case 0:
                                                            echo Yii::t('app', "Default");
                                                            break;
                                                        case 1:
                                                            echo Yii::t('app', "Open");
                                                            break;
                                                        case 2:
                                                            echo Yii::t('app', "Closed");
                                                            break;
                                                        case 3:
                                                            echo Yii::t('app', "Result Published");
                                                            break;
                                                    }
                                                ?>
                                            </td>
                                            
                                           
                                        </tr>
                                        <?php
                                        }
                                        ?>
                                        <?php
										if(count($exams_list)==0){
										?>
                                        <tr>
                                            <td colspan="6">	
                                                <center>
                                            	<?php echo Yii::t('app', 'No Result Found');?>
                                                </center>
                                            </td>
                                        </tr>
                                        <?php
										}
										?>
                                      </tbody>
                                 </table>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>