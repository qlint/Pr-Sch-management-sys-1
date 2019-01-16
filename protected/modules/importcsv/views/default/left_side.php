<div id="othleft-sidebar">
<!--<div class="lsearch_bar">
             	<input type="text" name="" class="lsearch_bar_left" value="Search">
                <input type="button" name="" class="sbut">
                <div class="clear"></div>
  </div>-->
          <h1><?php echo Yii::t('app','Import Module');?></h1>          
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
					array('label'=>''.Yii::t('app','Import').'<span>'.Yii::t('app','Import Details').'</span>', 'url'=>array('/importcsv') ,'linkOptions'=>array('class'=>'import_ico'),
                                   'active'=> (Yii::app()->controller->module->id=='importcsv' and !in_array(Yii::app()->controller->action->id,array('student','parent','employee'))) ? true : false
					    ),                               
					array('label'=>''.Yii::t('app','Create Users').'<span>'.Yii::t('app','New Users').'</span>',  'url'=>array('/importcsv/users/student') ,'linkOptions'=>array('class'=>'import-createuser_ico' ),'active'=> (in_array(Yii::app()->controller->action->id,array('student','parent','employee'))), 'itemOptions'=>array('id'=>'menu_1') 
					       ),
						  
				),
			)); 
			
	