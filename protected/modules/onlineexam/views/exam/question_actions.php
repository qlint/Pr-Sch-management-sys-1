<div class="online-Q-list-action">
    <div class="Qstn-actn-ul Qstn-actn-posion-left">
        <ul>
            <?php 
            $exam_id    =   $data->exam_id;
            $exists     =   OnlineExamStudentAnswers::model()->exists('exam_id = :exam_id',array('exam_id'=>$exam_id));
            if(!$exists){
            ?>
            <li><?php echo CHtml::link(Yii::t('app','Edit'), array('exam/updateQp','id'=>$data->id,'bid'=>$_REQUEST['bid']), array('title'=>Yii::t('app', 'Edit Question'),'class'=>'Q-edit-icon')); ?></li>
            <li><?php echo CHtml::link(Yii::t('app','Delete'), "#", array("submit"=>array('/onlineexam/exam/deleteQp','id'=>$data->id),'confirm' => Yii::t('app', 'Are you sure?'), 'csrf'=>true, 'class'=>'Q-delete-icon')); ?></li>
            <?php } ?>
            <li><span class="input-icon"><?php echo CHtml::button(Yii::t('app','Show Answer'), array('title'=>Yii::t('app', 'Show Answer'),'class'=>'show-answer Q-show-icon')); ?></span></li>
        </ul>
    </div>
    <div class="Qstn-actn-ul Qstn-actn-posion-right">
        <ul>
            <li><p><span><?php echo ($data->mark)?floatval($data->mark):0; ?></span> <?php echo Yii::t('app','Mark') ?></p></li>
        </ul>
    </div>
</div>