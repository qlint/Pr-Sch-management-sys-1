<div class="pagetab-bg-tag-a">
        <ul style="width:746px;">

         <?php echo '<li>'.CHtml::link(Yii::t('app','SMS Templates'), array('/sms/templates/index'),array('class'=>'')).'</li>';?>

        <?php echo '<li class="active">'.CHtml::link(Yii::t('app','System Generated Templates'), array('/sms/systemtemplates/index'),array('class'=>'active')).'</li>';?>

        
        </ul>
        </div>
