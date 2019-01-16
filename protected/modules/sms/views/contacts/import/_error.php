<?php
switch($error){
	case 0:
		echo '<span class="error">'.Yii::t('app', 'Invalid request').'</span>';
	break;
	case 1:
	?>
	<h2><?php echo Yii::t('app', 'Not an allowed file format');?></h2>
	<?php
	$allowedformats	= array();
	if(Contacts::model()->import_contacts_config()){
		$import_config		= Contacts::model()->import_contacts_config();
		if($import_config['allowed_file_formats']){
			$allowedformats	= $import_config['allowed_file_formats'];
		}
	}
	
	if(count($allowedformats)>0){
		echo '<p>'.Yii::t('app', 'Allowed file formats : ').'</p>';
		foreach($allowedformats as $format){
			echo '<b>.'.$format.'</b><br/>';
		}
	}
	
	break;
	case 2:
		echo '<span class="error">'.Yii::t('app', 'Please select required fields').'</span>';
	break;
}
?>