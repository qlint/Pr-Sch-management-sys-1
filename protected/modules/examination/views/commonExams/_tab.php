<?php 
$exam	= CommonExams::model()->findByPk($_REQUEST['id']);
if($exam!=NULL){
?>
<div class="page-top-block">
    <ul>
        <li>
            <div class="back-btn">            	
                <?php
					if(Yii::app()->request->urlReferrer)
						echo CHtml::link("<span>".Yii::t("app", "Back")."</span>", Yii::app()->request->urlReferrer, array('class'=>'back-bttn'));					
					else
                		echo CHtml::link("<span>".Yii::t("app", "Back")."</span>", array('/examination/commonExams/index'), array('class'=>'back-bttn'));					
				?>
            </div>
        </li>
    </ul>
</div>
<div class="clear"></div>
<div class="formCon">
	<div class="attnd-tab-inner-blk">
        <div class="exam-table">
            <table border="0" cellpadding="0" cellspacing="0p" width="100%">
                <thead>
                    <tr>
                        <th class="course-icon"><?php echo $exam->getAttributeLabel('name'); ?></th>
                        <th class="exam-icon"><?php echo $exam->getAttributeLabel('exam_type'); ?></th>
                        <th class="semester-icon"><?php echo $exam->getAttributeLabel('exam_date'); ?></th>
                    </tr>   
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <?php echo $exam->name;?>
                        </td>
                        <td>
                            <?php echo $exam->exam_type;?>
                        </td>
                        <td>
                            <?php echo $exam->examDate;?>
                        </td>                            
                    </tr>
                </tbody>	
            </table>
		</div>
        
        <div class="exam-table">
            <table border="0" cellpadding="0" cellspacing="0p" width="100%">
                <thead>
                    <tr>
                        <th class="batch-icon"><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"); ?></th>
                    </tr>   
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <?php
								$exam_groups	= ExamGroups::model()->findAllByAttributes(array('common_exam_id'=>$exam->id));
								$model->batches	= (count($exam_groups)>0)?CHtml::listData($exam_groups, 'batch_id', 'batch_id'):array();
								if(count($model->batches)>0){
									foreach($model->batches as $batch_id){										
										echo '<p class="batch-show">'.$exam->getBatchName($batch_id).'</p>';
									}
								}
								else{
									echo '-';
								}
							?>
                        </td>                          
                    </tr>
                </tbody>	
            </table>
		</div>
    </div>
</div>
<?php 
}
?>