<?php
//$model	=	new FileUploads;
$this->widget('xupload.XUpload', array(
	'url' => Yii::app()->createUrl("downloads/default/upload"),
	'model' => $model,
	'attribute' => 'file',
    'multiple' => true,
));
?>