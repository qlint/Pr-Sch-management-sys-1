<style type="text/css">
	span.abs{
		width:15px;
		height:15px;
	}
</style>
<?php
$find = StudentAttentance::model()->findAll("date=:x AND student_id=:y AND batch_id=:z", array(':x'=>$year.'-'.$month.'-'.$day,':y'=>$emp_id, ':z'=>$_REQUEST['id']));
$student = Students::model()->findByAttributes(array('id'=>$emp_id));
$holidays = Holidays::model()->findAll();
$holiday_arr=array();
foreach($holidays as $key=>$holiday){
	if(date('Y-m-d',$holiday->start)!=date('Y-m-d',$holiday->end)){
		$date_range = StudentAttentance::model()->createDateRangeArray(date('Y-m-d',$holiday->start),date('Y-m-d',$holiday->end));
		foreach ($date_range as $value) {
    		$holiday_arr[$value] = $holiday->id;
		}
	}
	else{
		$holiday_arr[date('Y-m-d',$holiday->start)] = $holiday->id;
	}
}
$stud_admission_date	= date("Y-m-d", strtotime($student->admission_date));
$today_day = date('d');
$today_month = date('n');
$today_year = date('Y');
$cell_date = date('Y-m-d',strtotime($year.'-'.$month.'-'.$day));
$today_date = date('Y-m-d');

if(count($find)==0){
	if(array_key_exists($cell_date, $holiday_arr)){
		$holiday_now = Holidays::model()->findByAttributes(array('id'=>$holiday_arr[$cell_date]));
	?>
        <span style="display:block; width:100%; height:20px; background:#D63535" class="holidays" title="<?php echo $holiday_now->title; ?>"></span>
    <?php
	}
		
	else if(in_array($cell_date,$days) and !array_key_exists($cell_date, $holiday_arr) and $cell_date >= $student->admission_date and $cell_date <=$today_date){
      	echo CHtml::link(
			'',
			'javascript:void(0);',
			array(
				'class'=>'at_abs open_popup',
				'data-ajax-url'=>$this->createUrl(
					'/teachersportal/default/addnew',
					array(
						'day' =>$day,
						'month'=>$month,
						'year'=>$year,
						'emp_id'=>$emp_id,
						'batch_id'=>$batch_id
					)
				),
				'data-target'=>"#myModal",
				'data-toggle'=>"modal",
				'data-modal-label'=>Yii::t("app", "Mark Leave"),
				'data-modal-description'=>Yii::t("app", "Mark the reason for leave")
			)
		);
	}
	else{
	?>
        <span style="display:block; width:100%; height:15px; background:#F2F2F2"></span>
    <?php
	}	
}
else{
	 $leave_types = StudentLeaveTypes::model()->findByAttributes(array('id'=>$find[0]['leave_type_id'],'status'=>1));
	 if($leave_types!=NULL){
	 	echo CHtml::link(
			'<span class="abs1" style="color:'.$leave_types->colour_code.';text-align:center;padding-top:2px">'.$leave_types->label.'</span>',
			'javascript:void(0);',
			array(
				'class'=>'open_popup',
				'data-ajax-url'=>$this->createUrl(
					'/teachersportal/default/editLeave',
					array(
						'id'=>$find[0]['id'],
						'day' =>$day,
						'month'=>$month,
						'year'=>$year,
						'emp_id'=>$emp_id,
						'batch_id'=>$batch_id
					)
				),
				'data-target'=>"#myModal",
				'data-toggle'=>"modal",
				'data-modal-label'=>Yii::t("app", "Edit Leave"),
				'data-modal-description'=>Yii::t("app", "Edit reason for leave"),
				'title'=>'Reason: '.$find['0']['reason']
        	)
		);
	}
	else{
		 echo CHtml::link(
		 	'<span class="abs" style="text-align:center;padding-top:2px">L</span>',
			'javascript:void(0);',
			array(
				'class'=>'open_popup',
				'data-ajax-url'=>$this->createUrl(
					'/teachersportal/default/editLeave',
					array(
						'id'=>$find[0]['id'],
						'day' =>$day,
						'month'=>$month,
						'year'=>$year,
						'emp_id'=>$emp_id,
						'batch_id'=>$batch_id
					)
				),
				'data-target'=>"#myModal",
				'data-toggle'=>"modal",
				'data-modal-label'=>Yii::t("app", "Edit Leave"),
				'data-modal-description'=>Yii::t("app", "Edit reason for leave"),
				'style'=>'margin:0px',
				'title'=>'Reason: '.$find['0']['reason']		
    		)
		);		
	}
}	
?>