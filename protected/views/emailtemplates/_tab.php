<div class="emp_tab_nav">
        <ul style="width:746px;">
        <li>
         <?php echo CHtml::link(Yii::t('app','SMS Templates'), array('/sms/templates/index'),array('class'=>''));?>              
        </li>
        <li>
        <?php echo CHtml::link(Yii::t('app','System Generated Templates'), array('/sms/systemtemplates/index'),array('class'=>'active'));?>       
        </li>        
        </ul>
    </div>