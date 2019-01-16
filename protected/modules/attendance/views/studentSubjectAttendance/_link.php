<?php
	if(isset($id)){
		echo CHtml::ajaxLink(
			'<span>Update Absent</span>',
			$this->createUrl('studentSubjectAttendance/mark'),
			array(
				'onclick'=>'$("#attendanceDialog").dialog("open");return false;',
				'update'=>'#attendanceDialog',
				'type' =>'GET',
				'data' => array(
					'id' =>$id
				),
				'dataType' => 'text'
			),
			array(
				'id'=>'edit-attendance-'.$id,
				'class'=>'student-timtable-update'
			)
		);
	}
	else{
		echo CHtml::ajaxLink(
			'<span>Mark Absent</span>',
			$this->createUrl('studentSubjectAttendance/mark'),
			array(
				'onclick'=>'$("#attendanceDialog").dialog("open");return false;',
				'update'=>'#attendanceDialog',
				'type' =>'GET',
				'data' => array(
					'timetable_id' =>$timetable_id,
					'student_id' =>$student_id,
					'subject_id'=>$subject_id,
					'weekday_id' =>$weekday_id,
					'date'=>$date
				),
				'dataType' => 'text'
			),
			array(
				'id'=>'edit-attendance-'.time(),
				'class'=>'student-timtable-update'
			)
		);
	}
?>