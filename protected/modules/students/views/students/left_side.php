<div id="othleft-sidebar">
                    
                    <?php
			function t($message, $category = 'cms', $params = array(), $source = null, $language = null) 
{
    return Yii::t($category, $message, $params, $source, $language);
}

			$this->widget('zii.widgets.CMenu',array(
			'encodeLabel'=>false,
			'activateItems'=>true,
			'activeCssClass'=>'list_active',
			'items'=>array(
					array('label'=>''.Yii::t('app','Set grading levels').'<span>'.Yii::t('app','Manage your Dashboard').'</span>', 'url'=>array('students/manage') ,'linkOptions'=>array('class'=>'menu_0'),
                                   'active'=> ((Yii::app()->controller->id=='besite') && (in_array(Yii::app()->controller->action->id,array('index')))) ? true : false
					    ),                               
					
					array('label'=>''.Yii::t('app','Exam Management').'<span>'.Yii::t('app','Manage your Dashboard').'</span>', 'url'=>'javascript:void(0);','linkOptions'=>array('id'=>'menu_2','class'=>'menu_2'),  'itemOptions'=>array('id'=>'menu_2'),
					       'items'=>array(
						array('label'=>Yii::t('app','New Exam'), 'url'=>array('/beterm/create')),
						
						array('label'=>Yii::t('app','Connect Exams'), 'url'=>array('/beterm/admin'),
							'active'=> ((Yii::app()->controller->id=='beterm') && (in_array(Yii::app()->controller->action->id,array('update','view','admin','index'))) ? true : false)                                                                                           
						      ),
							 
						                                                                                    
					    
					    ),
					       
					    ),
						array('label'=>''.Yii::t('app','Additional Exams').'<span>'.Yii::t('app','Manage your Dashboard').'</span>', 'url'=>array('students/manage') ,'linkOptions'=>array('class'=>'menu_0'),
                                   'active'=> ((Yii::app()->controller->id=='besite') && (in_array(Yii::app()->controller->action->id,array('index')))) ? true : false
					    ), 
							array('label'=>''.Yii::t('app','Exam Wise Report').'<span>'.Yii::t('app','Manage your Dashboard').'</span>', 'url'=>array('students/manage') ,'linkOptions'=>array('class'=>'menu_0'),
                                   'active'=> ((Yii::app()->controller->id=='besite') && (in_array(Yii::app()->controller->action->id,array('index')))) ? true : false
					    ),
						array('label'=>''.Yii::t('app','Subject wise Report').'<span>'.Yii::t('app','Manage your Dashboard').'</span>', 'url'=>array('students/manage') ,'linkOptions'=>array('class'=>'menu_0'),
                                   'active'=> ((Yii::app()->controller->id=='besite') && (in_array(Yii::app()->controller->action->id,array('index')))) ? true : false
					    ),
						array('label'=>''.Yii::t('app','Grouped exam Reports').'<span>'.Yii::t('app','Manage your Dashboard').'</span>', 'url'=>array('students/manage') ,'linkOptions'=>array('class'=>'menu_0'),
                                   'active'=> ((Yii::app()->controller->id=='besite') && (in_array(Yii::app()->controller->action->id,array('index')))) ? true : false
					    ),
						array('label'=>''.Yii::t('app','Archived Student Reports').'<span>'.Yii::t('app','Manage your Dashboard').'</span>', 'url'=>array('students/manage') ,'linkOptions'=>array('class'=>'menu_0'),
                                   'active'=> ((Yii::app()->controller->id=='besite') && (in_array(Yii::app()->controller->action->id,array('index')))) ? true : false
					    ),
					
						
					
					
				),
			)); ?>
		
		</div>
        <script type="text/javascript">

	$(document).ready(function () {
            //Hide the second level menu
            $('#othleft-sidebar ul li ul').hide();            
            //Show the second level menu if an item inside it active
            $('li.list_active').parent("ul").show();
            
            $('#othleft-sidebar').children('ul').children('li').children('a').click(function () {                    
                
                 if($(this).parent().children('ul').length>0){                  
                    $(this).parent().children('ul').toggle();    
                 }
                 
            });
          
            
        });
    </script>

