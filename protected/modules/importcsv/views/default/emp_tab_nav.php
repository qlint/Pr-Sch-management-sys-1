<div class="pagetab-bg-tag-a">
<ul>
  
    <?php     
          if(Yii::app()->controller->action->id=='student')
          {
          echo '<li class="active">'.CHtml::link(Yii::t('app','Student'), array('/importcsv/users/student')).'</li>';
          }
          else
          {
          echo '<li>'.CHtml::link(Yii::t('app','Student'), array('/importcsv/users/student')).'</li>';
          }
    ?>
  
    <?php     
          if(Yii::app()->controller->action->id=='parent')
          {
          echo '<li class="active">'.CHtml::link(Yii::t('app','Parent'), array('/importcsv/users/parent')).'</li>';
          }
          else
          {
          echo '<li>'.CHtml::link(Yii::t('app','Parent'), array('/importcsv/users/parent')).'</li>';
          }
    ?>

 
    <?php     
          if(Yii::app()->controller->action->id=='employee')
          {
          echo '<li class="active">'.CHtml::link(Yii::t('app','Teacher'), array('/importcsv/users/employee')).'</li>';
          }
          else
          {
          echo '<li>'.CHtml::link(Yii::t('app','Teacher'), array('/importcsv/users/employee')).'</li>';
          }
    ?>

</ul>
</div>