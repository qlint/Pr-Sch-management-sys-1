<?php
class GCM {
    function __construct() {
        
    }
	//Sending Push Notification     
	public function send_notification($registatoin_ids, $message) {				
		// API access key from Google API's Console
		define( 'API_ACCESS_KEY','AIzaSyDciMIfjQZWbOxrPzCVO00Zkp1Gt8KfB0c');
		// prep the bundle
		if($message['tag'] == 'inbox'){ //In case of messages
			$msg = array(  
						'title' 			=> $message['title'],
						'message' 			=> strip_tags($message['message']),
						'content' 			=> strip_tags($message['content']),
						'tag' 				=> $message['tag'],
						'timestamp' 		=> $message['timestamp'],
						'sender_email' 		=> $message['sender_email'],
						'sender_name' 		=> $message['sender_name'],
						'conversation_id' 	=> $message['conversion_id'],						
						'subject'			=> $message['subject']		
					);
		}
		else if($message['tag'] == 'student_subjectwise_attendance'){ //In case of student subjectwise attendance
			$msg = array(  
						'title' 			=> $message['title'],
						'message' 			=> strip_tags($message['message']),						
						'tag' 				=> $message['tag'],
						'timestamp' 		=> $message['timestamp'],						
						'sender_name' 		=> $message['sender_name'],						
						'student_id'		=> $message['student_id'],						
						'batch_id'			=> $message['batch_id'],
						'date'				=> $message['date'],
						'class_timing_id'	=> $message['class_timing_id'],						
					);
		}
		else if($message['tag'] == 'daywise_attendance'){
			$msg = array(  
						'title' 			=> $message['title'],
						'message' 			=> strip_tags($message['message']),						
						'tag' 				=> $message['tag'],
						'timestamp' 		=> $message['timestamp'],						
						'sender_name' 		=> $message['sender_name'],						
						'student_id'		=> $message['student_id'],						
						'batch_id'			=> $message['batch_id'],
						'id'				=> $message['id']		
					);
		}
		else if($message['tag'] == 'complaints'){ //In case Complaints
			$msg = array(  
						'title' 			=> $message['title'],
						'message' 			=> strip_tags($message['message']),						
						'tag' 				=> $message['tag'],
						'timestamp' 		=> $message['timestamp'],						
						'sender_name' 		=> $message['sender_name'],																
						'id'				=> $message['id'],	
						'complaint_id'		=> $message['complaint_id'],
						'type'				=> $message['type']						
					);
		}
		else if($message['tag'] == 'logs'){ //In case of Logs
			$msg = array(  
						'title' 			=> $message['title'],
						'message' 			=> strip_tags($message['message']),						
						'tag' 				=> $message['tag'],
						'timestamp' 		=> $message['timestamp'],						
						'sender_name' 		=> $message['sender_name'],						
						'student_id'		=> $message['student_id'],						
						'id'				=> $message['id'],
						'student_name'		=> $message['student_name'],
						'flag'				=> $message['flag']
					);
		}
		else if($message['tag'] == 'news'){ //In case of News		
			$msg	= array(  
						'title' 			=> html_entity_decode(ucfirst($message['title'])),
						'message' 			=> html_entity_decode(ucfirst(strip_tags($message['message']))),
						'tag' 				=> $message['tag'],
						'timestamp' 		=> $message['timestamp'],						
						'sender_name' 		=> $message['sender_name'],
						'content'			=> $message['content'],
						'subject'			=> $message['subject']								
					);
		}
		else if($message['tag'] == 'events'){ //In case of Event Create
			$msg	= array(  
						'title' 			=> html_entity_decode(ucfirst($message['title'])),
						'message' 			=> html_entity_decode(ucfirst(strip_tags($message['message']))),
						'tag' 				=> $message['tag'],
						'timestamp' 		=> $message['timestamp'],												
						'start_date'		=> $message['start_date']								
					);
		}
		else if($message['tag'] == 'cbse_exam'){ //In case of CBSE Exam date publish & result publish
			$msg	= array(  
						'title' 			=> html_entity_decode(ucfirst($message['title'])),
						'message' 			=> html_entity_decode(ucfirst(strip_tags($message['message']))),
						'tag' 				=> $message['tag'],
						'timestamp' 		=> $message['timestamp'],						
						'sender_name' 		=> $message['sender_name'],
						'batch_id'			=> $message['batch_id'],
						'exam_group_id'		=> $message['exam_group_id'],
						'student_id'		=> $message['student_id'],
						'flag'				=> $message['flag'], //Flag => 1 : Date Publish, 2 : Result Publish
						'id'				=> $message['id']
					);
		}
		else if($message['tag'] == 'default_exam'){ //In case of Default Exam date publish & result publish
			$msg	= array(  
						'title' 			=> html_entity_decode(ucfirst($message['title'])),
						'message' 			=> html_entity_decode(ucfirst(strip_tags($message['message']))),
						'tag' 				=> $message['tag'],
						'timestamp' 		=> $message['timestamp'],						
						'sender_name' 		=> $message['sender_name'],
						'batch_id'			=> $message['batch_id'],
						'exam_group_id'		=> $message['exam_group_id'],
						'student_id'		=> $message['student_id'],
						'flag'				=> $message['flag'], //Flag => 1 : Date Publish, 2 : Result Publish
						'id'				=> $message['id']
					);
		}
		else if($message['tag'] == 'teacher_daywise_attendance'){
			$msg = array(  
						'title' 			=> $message['title'],
						'message' 			=> strip_tags($message['message']),						
						'tag' 				=> $message['tag'],
						'timestamp' 		=> $message['timestamp'],						
						'sender_name' 		=> $message['sender_name'],						
						'teacher_id'		=> $message['teacher_id'],	
						'date'				=> $message['date']																		
					);
		}
		else{
			$msg	= array(  
						'title' 			=> html_entity_decode(ucfirst($message['title'])),
						'message' 			=> html_entity_decode(ucfirst(strip_tags($message['message']))),
						'tag' 				=> $message['tag'],
						'timestamp' 		=> $message['timestamp'],
						'sender_email' 		=> $message['sender_email'],
						'sender_name' 		=> $message['sender_name'],
						'conversation_id' 	=> $message['conversion_id']		
					);
		}
								
		$fields	= array(
						'priority'			=> 'high',
						'content_available' => true,
						'registration_ids' 	=> $registatoin_ids,
						'data'				=> $msg,
						'notification'      => array(
													'title'			=> html_entity_decode(ucfirst($message['title'])),
													'text'			=> html_entity_decode(ucfirst(strip_tags($message['message']))),
													"sound"			=> "notification.mp3",
													"click_action"	=> "fcm.activity"				
												)
					);
		
		$headers = array(
						'Authorization: key=' . API_ACCESS_KEY,
						'Content-Type: application/json'
					);
		
		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL,'https://fcm.googleapis.com/fcm/send');
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
		$result = curl_exec($ch );
		curl_close( $ch );			
	}
}
?>