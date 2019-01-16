<?php
	if(isset($id)){
		echo CHtml::Link(
				'<span>Update Absent</span>',
				'javascript:void(0);',
				array(
					'class'=>'student-timtable-update open_popup',
					'data-ajax-url'=>$this->createUrl(
						'/teachersportal/default/mark',
						array(
							'id' =>$id
						)
					),
					'data-target'=>"#myModal",
					'data-toggle'=>"modal",
					'data-modal-label'=>Yii::t("app", "Update Subject Wise Attendance"),
					'data-modal-description'=>Yii::t("app", "Edit  Leave"),
					'title'=>Yii::t('app','Edit')
				)
			);
	}
	else{
		echo CHtml::Link(
				'<span>Mark Absent</span>',
				'javascript:void(0);',
				array(
					'class'=>'student-timtable-update open_popup',
					'data-ajax-url'=>$this->createUrl(
						'/teachersportal/default/mark',
						array(
							'id' =>$id,
							'timetable_id' =>$timetable_id,
							'student_id' =>$student_id,
							'subject_id'=>$subject_id,
							'weekday_id' =>$weekday_id,
							'date'=>$date,
						)
					),
					'data-target'=>"#myModal",
					'data-toggle'=>"modal",
					'data-modal-label'=>Yii::t("app", "Update Subject Wise Attendance"),
					'data-modal-description'=>Yii::t("app", "Edit  Leave"),
					'title'=>Yii::t('app','Edit')
				)
			);
	}
?>