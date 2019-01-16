<div id="othleft-sidebar">
<!--<div class="lsearch_bar">
             	<input type="text" name="" class="lsearch_bar_left" value="Search">
                <input type="button" name="" class="sbut">
                <div class="clear"></div>
  </div>-->
          <h1><?php echo Yii::t('app','Complaints');?></h1>          
                    <?php
			function t($message, $category = 'cms', $params = array(), $source = null, $language = null) 
{
    return $message;
}

			$this->widget('zii.widgets.CMenu',array(
			'encodeLabel'=>false,
			'activateItems'=>true,
			'activeCssClass'=>'list_active',
			'items'=>array(
					array('label'=>''.Yii::t('app','Category').'<span>'.Yii::t('app','Manage Category').'</span>', 'url'=>array('/complaints/categories') ,'linkOptions'=>array('class'=>'lbook_ico'),
                                   'active'=> (Yii::app()->controller->action->id == 'categories') ? true : false
					    ),        
						array('label'=>''.Yii::t('app','Complaint List').'<span>'.Yii::t('app','Complaint List').'</span>', 'url'=>array('/complaints/index') ,'linkOptions'=>array('class'=>'lbook_ico'),
                                   'active'=> (Yii::app()->controller->action->id == 'index') ? true : false
					    ),                               					
						  
				),
			));
			
            

	