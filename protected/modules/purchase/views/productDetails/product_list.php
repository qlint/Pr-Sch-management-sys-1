<?php
$criteria= new CDbCriteria;
$criteria->condition= 'vendor_id=:id';
$criteria->params= array(':id'=>$_REQUEST['id']);
$dataprovider= new CActiveDataProvider('PurchaseProducts',array('criteria'=>$criteria));
$model = new PurchaseProducts;

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'skill-grid',
	'dataProvider'=>$dataprovider,
	//'filter'=>$model,
	'pager'=>array('cssFile'=>Yii::app()->baseUrl.'/css/formstyle.css'),
 	'cssFile' => Yii::app()->baseUrl . '/css/formstyle.css',
	'columns'=>array(
		array(
			'name'=>'item_id',
			'value'=>array($model,'itemName'),
		),
		'description',
		'price',		
		array(
			'header'=>Yii::t('app','Action'),
			'class'=>'CButtonColumn',
			'deleteConfirmation'=>Yii::t('app','Are you sure?'),
			'afterDelete'=>'function(link,success,data){
			if(success) { // if ajax call was successful !!!
					window.location.reload();
			} else {
				alert("Not deleted");
			}
		}',
			'htmlOptions' => array('style'=>'width:80px;'),			
			'headerHtmlOptions'=>array('style'=>'font-size:12px; font-weight:bold;'),			 
			'template' => '{update}{delete}',
                        'buttons' => array(
                        'delete' => array(
                           'url' => 'Yii::app()->createUrl("purchase/productDetails/delete",array("id"=>$data->id,"vendor_id"=>$_REQUEST["id"]))',
                            
                        ),
                            'update' => array(
                            'url' => 'Yii::app()->createUrl("purchase/productDetails/update",array("eid"=>$data->id,"id"=>$data->vendor_id,"flag"=>1))',
                            
                        ),
							
                           
                    ),
		),
	),
)); ?>

