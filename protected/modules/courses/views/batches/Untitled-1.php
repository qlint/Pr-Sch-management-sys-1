
<?php
$main_arr  = array(1, 2);
$subject = array();

foreach($main_arr as $main){
	if($main == 1){
		$sub_arr  = array(10, 20);
		$new_subject_id = 100; 
	}
	else{
		$sub_arr = array(30, 40);
		$new_subject_id = 200;
	}
	$split  = array(); 
	foreach($sub_arr as $key => $sub){
		if($sub == 10){
			$new = 11;
		}
		if($sub == 20){
			$new = 21;
		}
		if($sub == 30){
			$new = 31;
		}
		if($sub == 40){
			$new = 41;
		}
		$split[$sub] = $new;
	}
	$split['new_subject_id'] 	= $new_subject_id;
	$subject[$main] 			= $split;
}
var_dump($subject);
echo $subject[2]['new_subject_id'];
exit;
?>