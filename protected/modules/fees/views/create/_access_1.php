<?php
	//courses
	//get academic year
	if(Yii::app()->user->year){
		$year 					= Yii::app()->user->year;
	}
	else{
		$current_academic_yr 	= Configurations::model()->findByAttributes(array('id'=>35));
		$year 					= $current_academic_yr->config_value;
	}
	
	$criteria	= new CDbCriteria;
	$criteria->compare("academic_yr_id", $year);
	$criteria->compare("is_deleted", 0);	
	$courses	= Courses::model()->findAll($criteria);
	if(count($courses)>0){
		$courses	= CHtml::listData($courses, "id", "course_name");
	}
	else{
		$courses	= array();
	}
	
	//categories
	$categories	= StudentCategories::model()->findAll();
	if(count($categories)>0){
		$categories	= CHtml::listData($categories, "id", "name");
	}
	else{
		$categories	= array();
	}
?>
<table> 
    <tr>
        <td>
        	<?php echo CHtml::activeDropDownList($access, "[".$ptrow."]course[".$acrow."]", $courses, array("prompt"=>Yii::t('app', "All Courses"), 'class'=>'access-course', 'style'=>'width:120px !important;'));?>
        </td>
        <td>
        	<?php echo CHtml::activeDropDownList($access, "[".$ptrow."]batch[".$acrow."]", array(), array("prompt"=>Yii::t('app', "All")." ".Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"), 'class'=>'access-batch', 'style'=>'width:120px !important;'));?>
        </td>
        <td>
            <?php echo CHtml::activeDropDownList($access, "[".$ptrow."]student_category_id[".$acrow."]", $categories, array("prompt"=>Yii::t('app', "All Categories"), 'style'=>'width:120px !important;'));?>
        </td>        
    </tr>
</table>