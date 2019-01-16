<div id="othleft-sidebar">
    <!--<div class="lsearch_bar">
    <input type="text" value="Search" class="lsearch_bar_left" name="">
    <input type="button" class="sbut" name="">
    <div class="clear"></div>
    </div>-->
    
    <h1><?php echo  Yii::t('app','View Timetable'); ?></h1>
    <ul>
		<?php 
		if(isset($_REQUEST['id']) and $_REQUEST['id']!=NULL)
        { 
		?>
			<?php 
            if(Yii::app()->controller->action->id == 'timetable')
            {
            ?>
                <li class="list_active">
                    <?php echo CHtml::link( Yii::t('app','Set Timetable').'<span>'. Yii::t('app','Set And View').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' '.Yii::t('app','wise Timetable').'</span>',array('/timetable/weekdays/timetable','id'=>$_REQUEST['id'],'type'=>'view'),array('class'=>'view-timetable_ico '));
                    ?>
                </li>
            <?php 
			}
            else
            {
			?>
                <li>
                    <?php echo CHtml::link( Yii::t('app','Set Timetable').'<span>'. Yii::t('app','Set And View').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' '.Yii::t('app','wise Timetable').'</span>',array('/timetable/weekdays/timetable','id'=>$_REQUEST['id'],'type'=>'view'),array('class'=>'view-timetable_ico '));?>
                </li>
			<?php
            }?>
         
        <?php 
		}
        else
        {
		?>
            <li>
            	<?php echo CHtml::ajaxLink( Yii::t('app','Set Timetable').'<span>'. Yii::t('app','Set And View').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' '.Yii::t('app','wise Timetable').'</span>',array('/site/explorer','widget'=>'2','rurl'=>'timetable/weekdays/timetable'),array('update'=>'#explorer_handler'),array('id'=>'explorer_timetable','class'=>'view-timetable_ico ')); ?>
            </li>
        <?php 
		}
		?>
         <?php 
            if(Yii::app()->controller->action->id=='fulltimetable')
            {
            ?>
                <li class="list_active">
                    <?php echo CHtml::link( Yii::t('app','View Full Timetable').'<span>'. Yii::t('app','View Full Timetable').'</span>',array('/timetable/weekdays/fulltimetable'),array('class'=>'viewfull-timetable_ico'));
                    ?>
                </li>
            <?php 
			}
            else
            {
			?>
                <li>
                    <?php echo CHtml::link( Yii::t('app','View Full Timetable').'<span>'. Yii::t('app','View Full Timetable').'</span>',array('/timetable/weekdays/fulltimetable'),array('class'=>'viewfull-timetable_ico'));?>
                </li>
			<?php
            }?>
        
         <?php 
            if(Yii::app()->controller->action->id=='index' and (Yii::app()->controller->id=='teachersTimetable' or Yii::app()->controller->id=='flexibleTeachersTimetable'))
            {
            ?>
                <li class="list_active">
                    <?php echo CHtml::link( Yii::t('app','View Teachers Timetable').'<span>'. Yii::t('app','View Teacher Wise Timetable').'</span>',array('/timetable/teachersTimetable/index'),array('class'=>'view-teacher-timetable_ico'));
                    ?>
                </li>
            <?php 
			}
            else
            {
			?>
                <li>
                    <?php echo CHtml::link( Yii::t('app','View Teachers Timetable').'<span>'. Yii::t('app','View Teacher Wise Timetable').'</span>',array('/timetable/teachersTimetable/index'),array('class'=>'view-teacher-timetable_ico'));?>
                </li>
			<?php
            }?>
            
        <h1><?php echo  Yii::t('app','Manage Timetable');?></h1>
        <?php 
		if(isset($_REQUEST['id']) and $_REQUEST['id']!=NULL)
        {
		?>
			<?php 
            if(Yii::app()->controller->action->id=='timetable' and $_REQUEST['type']==NULL)
            {
            ?>
                <li class="list_active">
                    <?php /*?><?php echo CHtml::link( Yii::t('app','Set Timetable').'<span>'. Yii::t('app','Timetable For The').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").'</span>',array('/timetable/weekdays/timetable','id'=>$_REQUEST['id']),array('class'=>'set-timetable_ico'));
                ?><?php */?>
                </li>
            <?php 
            }
            else
            {
            ?>
                <li>
                   <?php /*?> <?php echo CHtml::link( Yii::t('app','Set Timetable').'<span>'. Yii::t('app','Timetable For The').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").'</span>',array('/timetable/weekdays/timetable','id'=>$_REQUEST['id']),array('class'=>'set-timetable_ico'));?><?php */?>
                </li>
            <?php 
            }
            if(Yii::app()->controller->id=='weekdays' and $_REQUEST['type']==NULL and Yii::app()->controller->action->id!='timetable')
            { 
            ?>
                <li class="list_active">
                    <?php echo CHtml::link( Yii::t('app','Set Weekdays').'<span>'. Yii::t('app','Weekdays For The').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").'</span>',array('/timetable/weekdays','id'=>$_REQUEST['id']),array('class'=>'set-weekdays_ico'));
                ?>
                </li>
            <?php 
            }
            else
            {
            ?>
                <li>
                    <?php echo CHtml::link( Yii::t('app','Set Weekdays').'<span>'. Yii::t('app','Weekdays For The').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").'</span>',array('/timetable/weekdays','id'=>$_REQUEST['id']),array('class'=>'set-weekdays_ico'));
            ?>
                </li>
            <?php
            }
            ?>
            <?php 
            if(Yii::app()->controller->id=='classTiming')
            {
            ?>
                <li class="list_active">
                <?php echo CHtml::link( Yii::t('app','Set Class Timing').'<span>'. Yii::t('app','Class Timing For The').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").'</span>',array('/timetable/classTiming','id'=>$_REQUEST['id']),array('class'=>'timtable-classtiming_ico'));
                ?>
                </li>
            
            <?php 
            }
            else
            {
            ?>
                <li>
                    <?php echo CHtml::link( Yii::t('app','Set Class Timing').'<span>'. Yii::t('app','Class Timing For The').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").'</span>',array('/timetable/classTiming','id'=>$_REQUEST['id']),array('class'=>'timtable-classtiming_ico'));
                ?>
                </li>
            <?php 
            }
            ?>
        
            <?php 
            if(Yii::app()->controller->id=='weekdays' and isset($_REQUEST['type']) and  $_REQUEST['type']== 'default')
            {
            ?>
                <li class="list_active">
                    <?php echo CHtml::link( Yii::t('app','Set Default Weekdays').'<span>'. Yii::t('app','Default Weekdays For The Institution').'</span>',array('/timetable/weekdays','type'=>'default','id'=>$_REQUEST['id']),array('class'=>'setdeflt-weekdays_ico')); ?>
                </li>
            <?php
            }
            else
            {
            ?>
                <li> 
                    <?php echo CHtml::link( Yii::t('app','Set Default Weekdays').'<span>'. Yii::t('app','Default Weekdays For The Institution').'</span>',array('/timetable/weekdays','type'=>'default','id'=>$_REQUEST['id']),array('class'=>'setdeflt-weekdays_ico','active'=>Yii::app()->controller->id=='weekdays'));
                ?>
                </li>
            <?php 
            } 
            ?>
            
        <?php 
		}
        else
        {
		?>
            <li>
            <?php /*?><?php 
            echo CHtml::ajaxLink( Yii::t('app','Set Timetable').'<span>'. Yii::t('app','Timetable For The').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").'</span>',array('/site/explorer','widget'=>'2','rurl'=>'timetable/weekdays/timetable'),array('update'=>'#explorer_handler'),array('id'=>'explorer_timetable','class'=>'set-timetable_ico','active'=>Yii::app()->controller->id=='weekdays'));
            ?><?php */?>
            </li>
            
            <li>
            <?php echo CHtml::ajaxLink( Yii::t('app','Set Weekdays').'<span>'. Yii::t('app','Weekdays For The').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").'</span>',array('/site/explorer','widget'=>'2','rurl'=>'timetable/weekdays'),array('update'=>'#explorer_handler'),array('id'=>'explorer_weekdays','class'=>'set-weekdays_ico','active'=>Yii::app()->controller->id=='weekdays')); ?>
            </li>
            
            <li>
            <?php echo CHtml::ajaxLink( Yii::t('app','Set Class Timing').'<span>'. Yii::t('app','Class Timing For The').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").'</span>',array('/site/explorer','widget'=>'2','rurl'=>'timetable/classTiming'),array('update'=>'#explorer_handler'),array('id'=>'explorer_classTiming','class'=>'timtable-classtiming_ico','active'=>Yii::app()->controller->id=='classTiming')); ?>
            </li>
            <?php 
			if(Yii::app()->controller->id=='weekdays' and Yii::app()->controller->action->id!='fulltimetable')
            {
			?>
                <li class="list_active"> 
                	<?php echo CHtml::link( Yii::t('app','Set Default Weekdays').'<span>'. Yii::t('app','Default Weekdays For The Institution').'</span>',array('/timetable/weekdays','type'=>'default'),array('class'=>'setdeflt-weekdays_ico','active'=>Yii::app()->controller->id=='weekdays'));
                ?>
                </li>
            <?php 
			}
            else
            {  ?>
                <li>
                	<?php echo CHtml::link( Yii::t('app','Set Default Weekdays').'<span>'. Yii::t('app','Default Weekdays For The Institution').'</span>',array('/timetable/weekdays','type'=>'default'),array('class'=>'set_dw_ico','active'=>Yii::app()->controller->id=='weekdays'));
                ?>
                </li>
            <?php 
			}
			?>
        <?php 
		}
		?>
    </ul>
</div>