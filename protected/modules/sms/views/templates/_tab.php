<!--<div class="pagetab-bg-tag-a">
        <ul style="width:746px;">
        <li>
         <?php echo CHtml::link(Yii::t('app','SMS Templates'), array('/sms/templates/index'),array('class'=>'active'));?>
              
        </li>
        
        
        
        <li>
        <?php echo CHtml::link(Yii::t('app','System Generated Templates'), array('/sms/systemtemplates/index'),array('class'=>''));?>
       
        </li>
        
        </ul>
    </div>-->
    <div class="pagetab-bg-tag-a">
        <ul>
         <?php echo '<li class="active">'.CHtml::link(Yii::t('app','SMS Templates'), array('/sms/templates/index')).'</li>';?>

        <?php echo  '<li class="">'.CHtml::link(Yii::t('app','System Generated Templates'), array('/sms/systemtemplates/index')).'</li>';?>

        </ul>
    </div>