<?php 

$csrf= "YII_CSRF_TOKEN ".Yii::app()->request->csrfToken;
$this->widget('zii.widgets.grid.CGridView', array(
	'id' => 'install-grid',
	'dataProvider' => $dataProvider,
        'pager'=>array('cssFile'=>Yii::app()->baseUrl.'/css/formstyle.css'),
 	'cssFile' => Yii::app()->baseUrl . '/css/formstyle.css',
        'template'=>"{items}\n{pager}",
	'columns' => array(
		array('name'=>"name",'header'=>Yii::t('app',"Name")),
		array('name'=>'size','header'=>Yii::t('app',"Size")),
		array('name'=>'create_time','header'=>Yii::t('app',"Create Time")),
		array(
			'class' => 'CButtonColumn',
                        'header'=>Yii::t('app','Action'),
			'template' => ' {download} {restore} {Delete}',
			  'buttons'=>array
			    (
			        'download' => array
			        (
                                    'label'=>Yii::t('app','Download'),
			            'url'=>'Yii::app()->createUrl("backup/default/download", array("file"=>$data["name"]))',
			        ),
			        'restore' => array
			        (
                                    'label'=>Yii::t('app','Restore'),
			            'url'=>'Yii::app()->createUrl("backup/default/restore", array("file"=>$data["name"]))',
                                    'click'=>'function(){return confirm("'.Yii::t('app','Are you sure you want to restore?').'");}'
					),
//			        'Delete' => array
//			        (
//                                    'label'=>Yii::t('app','Delete'),
//			            'url'=>'Yii::app()->createUrl("backup/default/delete", array("file"=>$data["name"]))',
//                                    'click'=>'function(){return confirm("'.Yii::t('app','Are you sure you want to delete this item?').'");}'
//			        ),
                              'Delete' => array(
                                  'label'=>Yii::t('app','Delete'),
                                        'url' => 'Yii::app()->createUrl("backup/default/delete", array("file"=>$data["name"]))',
                                        'options' => array(
                                            'confirm' => 'Are you sure you want to delete this item?',
                                            'ajax' => array(
                                                'type' => 'POST',
                                                'dataType'=>'json',
                                                'data'=> array('YII_CSRF_TOKEN' => Yii::app()->request->csrfToken, 'file'=>$data["name"]),
                                                'url' => "js:$(this).attr('href')",
                                                'success' => 'function(data){
                                                    if(data.status=="success"){
                                                        window.location.reload();                                                        
                                                    }else{
                                                        alert(data.message);
                                                    }
                                                }'
                                            ),
                                        ),
                                    ),
			    ),		
		),
		
	),
)); ?>