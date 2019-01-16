<div class="table-responsive scrollbox3">
    <table class="table table-invoice">
        <tr>          
          <th  width="30%"  style="text-align:left" height="35"><?php echo Yii::t('app','Subject');?></th>
          <th  width="30%" style="text-align:left" ><?php echo Yii::t('app','Mark');?></th>
        </tr>          
        <?php
        $subjects= Subjects::model()->findAllByAttributes(array('batch_id'=>$batch_id,'elective_group_id'=>0));
        if($subjects!=NULL)
        {
            $i=1;
            foreach ($subjects as $subject)
            {                
        ?>
            <tr>                                
                <td width="50%" style="text-align:left"><?php echo ucfirst($subject->name); ?></td>
                <td width="50%" style="text-align:center"><?php echo CbscExams::getGrades($student_id, $subject->id, $batch_id); ?></td>
            </tr>
        <?php 
            $i++;
            }
        }
        ?>
         
          
    </table>
</div>