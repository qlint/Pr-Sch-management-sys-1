<?php

class DefaultController extends RController
{
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'rights', // perform access control for CRUD operations
		);
	}
	
	public function actionIndex()
	{
		$this->render('index');
	}
	
	public function actionSendmail()
	{
		$this->render('sendmail');
	}
        
        public function actionSendmails()
	{
            //registering js files
			Yii::app()->clientScript->registerScriptFile(
				Yii::app()->assetManager->publish(
					Yii::getPathOfAlias('application.modules.notifications.assets') . '/js/ajaxupload/ajaxupload.js'
				)
			);
		
			Yii::app()->clientScript->registerScript('browseActionPath', 'var browseActionPath="' . $this->createUrl('/notifications/default/browsedata') . '"', CClientScript::POS_BEGIN);
		
			Yii::app()->clientScript->registerScriptFile(
				Yii::app()->assetManager->publish(
					Yii::getPathOfAlias('application.modules.notifications.assets') . '/js/ajaxupload/download.js'
				)
			);
		$this->render('new_compose/send_mails');
	}
        
	
	public function actionMailshots()
	{
		$criteria = new CDbCriteria;
	    $criteria->condition = 'is_mailshot=:x';
		$criteria->params = array(':x'=>1); 
		$criteria->order = ('id DESC');
		$total = EmailDrafts::model()->count($criteria);
		$pages = new CPagination($total);
        $pages->setPageSize(15);
        $pages->applyLimit($criteria);  // the trick is here!
		$posts = EmailDrafts::model()->findAll($criteria);
		
		$this->render('mailshots',array(
		'model'=>$posts,
		'pages' => $pages,
		'item_count'=>$total,
		)) ;
	}
	public function actionViewsent()
	{
		$this->render('viewsent');
	}
	
	public function actionSavedraft()
	{
		$batch[] = $_POST['batch'];
	
		$user[] = $_POST['user'];
		if($_REQUEST['id']){
		$draft = EmailDrafts::model()->findByAttributes(array('id'=>$_REQUEST['id']));
		}
		else{ 
		$draft = new EmailDrafts;
		}
		if($_POST['EmailDrafts']['subject']){
			$draft->subject = $_POST['EmailDrafts']['subject'];
		}
		else{
			$draft->subject = "(no subject)";
		}
		$draft->message = $_POST['editor1'];
		 
		
		/*if($_POST['user']=='' || $_POST['editor1']=='')
		{
			
			 if( $_POST['user']=='')
		    {
			  
			  Yii::app()->user->setFlash('usererrorMessage',UserModule::t("Recipient Field cannot be Empty!"));
		    } 
		   if(ltrim($_POST['editor1'])=='')
		     {
			      Yii::app()->user->setFlash('editerrorMessage',UserModule::t("Message Field cannot be Empty!"));
			
		     }
			   $this->redirect(array('sendmail'));
		}
		else
		{*/
		$draft->created_by = Yii::app()->user->id;
		$settings=UserSettings::model()->findByAttributes(array('user_id'=>1));									
		if($settings!=NULL)
		{	
			$timezone = Timezone::model()->findByAttributes(array('id'=>$settings->timezone));
			date_default_timezone_set($timezone->timezone);
		}
		$draft->created_on = date('Y-m-d H:i:s');
		if(isset($_POST['mailshot']))
		$draft->is_mailshot = 1;		
		if($draft->save()){
			
				if($_REQUEST['id']){
					
					$recipients = EmailRecipients::model()->findByAttributes(array('mail_id'=>$_REQUEST['id']));
				}
				else{ 
					$recipients = new EmailRecipients;
					$recipients->mail_id = $draft->id;
				}
			if(isset($user))
			{
				
				if(count($user)==4)
				{
					$recipients->users = 0;
				}
				else
				{
					foreach($user as $users){
						$users1 = implode (',',$users);
					}
					$recipients->users = $users1;
				} 
					$recipients->save();
			}
			
			if(isset($batch)){
				
				if(in_array("0",$batch)){
					$recipients->users = '0';
				}
				else{
					foreach($batch as $batches){
						$batches1 = implode (',',$batches);
					}
					$recipients->batches = $batches1;
				} 
				$recipients->save();
			}
			if(CUploadedFile::getInstancesByName('Attachment')){				
					
					$attachments = CUploadedFile::getInstancesByName('Attachment');
					//$file_name = DocumentUploads::model()->getFileName($attachments);
					if (isset($attachments) && count($attachments) > 0) {
						 $path	=	'uploads/attachments/'.$draft->id.'/';
							if(!is_dir($path)){
								mkdir($path);	
							}					
						  foreach ($attachments as $attachment => $pic) {
							  $file_name = DocumentUploads::model()->getFileName($pic->name);
							  
							   if ($pic->saveAs($path.$file_name)) {
									 $img_add = new EmailAttachments;
                       				 $img_add->file = $file_name; 
                        			 $img_add->mail_id = $draft->id; 
                            		 $img_add->created_by	=	Yii::app()->user->id;
									 $img_add->file_type = $pic->type;	
									 $img_add->save(); 
                  		  		 }
								 else{
                        				echo Yii::t('app', 'Cannot upload!');
                    			 }
						  }
					}
				
			}
			}
			if(isset($_POST['mailshot']))
				$this->redirect(array('mailshots'));
			else
				$this->redirect(array('drafts'));
				
				
				
	//}
				
	}
	
	public function actionDelete() {
		$file = 'uploads/attachments/'.$_REQUEST['mail_id'].'/'.$_REQUEST['name'];
		$criteria = new CDbCriteria;
		$criteria->condition = 'file=:x AND mail_id=:y';
		$criteria->params = array(':x'=>$_REQUEST['name'],':y'=>$_REQUEST['mail_id']); 
		if(unlink($file)) {
			$files = EmailAttachments::model()->find($criteria);
			$files->delete();
		   }
	}	
	
	public function actionDrafts()
	{
		//$model = new EmailDrafts;
		$criteria = new CDbCriteria;
	    $criteria->condition = 'is_mailshot=:x AND status=:y';
		$criteria->params = array(':x'=>0,':y'=>0); 
		$criteria->order = ('id DESC');
		$total = EmailDrafts::model()->count($criteria);
		$pages = new CPagination($total);
        $pages->setPageSize(15);
        $pages->applyLimit($criteria);  // the trick is here!
		$posts = EmailDrafts::model()->findAll($criteria);
		
		$this->render('drafts',array(
		'model'=>$posts,
		'pages' => $pages,
		'item_count'=>$total,
		)) ;
		
		//$this->render('drafts');
	}
	
	public function actionDownload()
	{
		$criteria = new CDbCriteria;
	    $criteria->condition = 'mail_id=:x AND id=:y';
		$criteria->params = array(':x'=>$_REQUEST['mail_id'],':y'=>$_REQUEST['id']); 
		$model = EmailAttachments::model()->find($criteria);
		$file_path = 'uploads/attachments/'.$model->mail_id.'/'.$model->file;
		$file_content = file_get_contents($file_path);
		$model->file = str_replace(' ','',$model->file);
		header("Content-Type: ".$model->file_type);
		header("Content-disposition: attachment; filename=".$model->file);
		header("Pragma: no-cache");
		echo $file_content;
		exit;

	}
	
	public function actionSentmail()
	{
		$criteria = new CDbCriteria;
	    $criteria->condition = 'status=:y';
		$criteria->params = array(':y'=>2); 
		$criteria->order = ('id DESC');
		$total = EmailDrafts::model()->count($criteria);
		$pages = new CPagination($total);
        $pages->setPageSize(15);
        $pages->applyLimit($criteria);  // the trick is here!
		$posts = EmailDrafts::model()->findAll($criteria);
		
		$this->render('sentmail',array(
		'model'=>$posts,
		'pages' => $pages,
		'item_count'=>$total,
		)) ;
	}
	
	public function actionSenddraft()
	{
		$this->render('new_compose/senddraft');
	}
	
	public function actionTest()
	{
		require_once(dirname(__FILE__) .'/../../../extensions/PHPMailer/class.phpmailer.php');
		$email = $_POST['test'];
		if(isset($email)){
			
			$draft = new EmailDrafts;
			
				if($_POST['EmailDrafts']['subject']){
					$draft->subject = $_POST['EmailDrafts']['subject'];
				}
				else{
					$draft->subject = "(".Yii::t('app', "no subject").")";
				}
				
			$draft->message = trim($_POST['editor1']);
			$draft->created_by = Yii::app()->user->id;
			$settings=UserSettings::model()->findByAttributes(array('user_id'=>1));	
			
				if($settings!=NULL)
				{	
				$timezone = Timezone::model()->findByAttributes(array('id'=>$settings->timezone));
				date_default_timezone_set($timezone->timezone);
				}
				
			$draft->created_on = date('Y-m-d H:i:s');
			$draft->status = 3;	
			if($draft->save()){
				if(CUploadedFile::getInstancesByName('Attachment')){
						$attachments = CUploadedFile::getInstancesByName('Attachment');
							if (isset($attachments) && count($attachments) > 0) {
								$path	=	'uploads/attachments/'.$draft->id.'/';
									if(!is_dir($path)){
									mkdir($path);	
									}	
									  foreach ($attachments as $attachment => $pic) {
										   if ($pic->saveAs($path.$pic->name)) {
													 $img_add = new EmailAttachments;
													 $img_add->file = $pic->name; //it might be $img_add->name for you, filename is just what I chose to call it in my model
													 $img_add->mail_id = $draft->id; // this links your picture model to the main model (like your user, or profile model)
													 $img_add->created_by	= Yii::app()->user->id;
													 $img_add->file_type = $pic->type;	
													 $img_add->save(); // DONE
										   }
										  else{
											  echo Yii::t('app', 'Cannot upload!');
										  }
									}
						}
				
				}	
			}	
		$criteria = new CDbCriteria;
		$criteria->compare('mail_id',$draft->id);
		$attachmnts = EmailAttachments::model()->findAll($criteria);
		foreach($attachmnts as $attachmnt){
			$path1 =  'uploads/attachments/'.$attachmnt->mail_id.'/'.$attachmnt->file;	
		}
		$mail = new PHPMailer();
		$mail->AddReplyTo("admin@test.com");
		$mail->SetFrom("admin@test.com");
		$mail->AddAddress($email);
		$mail->Subject = $_POST['EmailDrafts']['subject'];
		$mail->MsgHTML($_POST['editor1']);
		$mail->AddAttachment($path1);
	
			if($mail->Send())
			{
				Yii::app()->user->setFlash('testsuccess', Yii::t('app', "Message sent !"));
				$criteria = new CDbCriteria;
				$criteria->condition = "id=:x";
				$criteria->params = array(':x'=>$draft->id);
				$message = EmailDrafts::model()->find($criteria);
				$message->delete();
				foreach($attachmnts as $attachmnt){
				$target = 'uploads/attachments/'.$attachmnt->mail_id.'/'.$attachmnt->file;	
					if(is_dir($target)){
						$files = glob( $target . '*', GLOB_MARK ); //GLOB_MARK adds a slash to directories returned
						
						foreach( $files as $file )
						{
							delete_files( $file );      
						}
					  
						rmdir( $target );
					} 
					elseif(is_file($target)) {
						unlink( $target );  
					}
				$attachmnts->delete();
				}
			}
			else{
				Yii::app()->user->setFlash('testerror', Yii::t('app', "Message not sent !"));
			}
		}
}
	
	public function actionNewmail()
	{
		$batch[] = $_POST['batch'];
		$user[] = $_POST['user'];
		require_once(dirname(__FILE__) .'/../../../extensions/PHPMailer/class.phpmailer.php');
		$emails = array();
		$teach = array();
		if($_REQUEST['id']){
		$draft = EmailDrafts::model()->findByAttributes(array('id'=>$_REQUEST['id']));
		}
		else{ 
		$draft = new EmailDrafts;
		}
		if($_POST['EmailDrafts']['subject']){
			$draft->subject = $_POST['EmailDrafts']['subject'];
		}
		else{
			$draft->subject = "(".Yii::t('app', "no subject").")";
		}
		$draft->message = trim($_POST['editor1']);
		$draft->created_by = Yii::app()->user->id;
		$draft->status=2;
		$settings=UserSettings::model()->findByAttributes(array('user_id'=>1));									
			if($settings!=NULL)
			{	
			$timezone = Timezone::model()->findByAttributes(array('id'=>$settings->timezone));
			date_default_timezone_set($timezone->timezone);
			}
		$draft->created_on = date('Y-m-d H:i:s');
		if($_POST['user']=='' || ltrim($_POST['editor1'])==''){
			if( $_POST['user']==''){
				Yii::app()->user->setFlash('usererrorMessage',Yii::t('app', "Please select a recipient group!"));
		    } 
			if(ltrim($_POST['editor1'])==''){
			    Yii::app()->user->setFlash('editerrorMessage',Yii::t('app', "Message Field cannot be Empty!"));
			
			}
			if($_POST['batch']==NULL){
                            Yii::app()->user->setFlash('batchselect',Yii::t('app', "Please select a").' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' '.'!');
				//Yii::app()->user->setFlash('batchselect',Yii::t('app', "Please select a batch!"));
			}				
			
		   $this->redirect(array('sendmail'));
		}
		else
		{
		
		if(isset($_POST['mailshot']))
		$draft->is_mailshot = 1;
		if($draft->save()){
			if($_REQUEST['id']){
					$recipients = EmailRecipients::model()->findByAttributes(array('mail_id'=>$_REQUEST['id']));
				}
				else{ 
					$recipients = new EmailRecipients;
					$recipients->mail_id = $draft->id;
				}
			if(isset($user)){
				if(in_array("0",$batch)){
					$recipients->users = '0';
				}
				else{					
					foreach($user as $users){
						$users1 = implode (',',$users);
					}
					$recipients->users = $users1;
				} 
					$recipients->save();	
			}
			if(isset($batch)){
				if(in_array("0",$batch)){
					$recipients->batches = '0';
				}
				else{
					foreach($batch as $batches){
						$batches1 = implode (',',$batches);
					}
					$recipients->batches = $batches1;
				} 
				$recipients->save();
			}
					
			if(CUploadedFile::getInstancesByName('Attachment')){
				
					$attachments = CUploadedFile::getInstancesByName('Attachment');
					if (isset($attachments) && count($attachments) > 0) {
						 $path	=	'uploads/attachments/'.$draft->id.'/';
							if(!is_dir($path)){
								mkdir($path);	
							}					
						  foreach ($attachments as $attachment => $pic) {
							   if ($pic->saveAs($path.$pic->name)) {
									 $img_add = new EmailAttachments;
                       				 $img_add->file = $pic->name; //it might be $img_add->name for you, filename is just what I chose to call it in my model
                        			 $img_add->mail_id = $draft->id; // this links your picture model to the main model (like your user, or profile model)
                            		 $img_add->created_by	=	Yii::app()->user->id;
									 $img_add->file_type = $pic->type;	
									 $img_add->save(); // DONE
                  		  		 }
								 else{
                        				echo Yii::t('app', 'Cannot upload!');
                    			 }
						  }
					}
				
			}
		}
		if(in_array(1,$_POST['user']) && $_POST['batch']==NULL)
		{			
					$employees = Employees::model()->findAll('is_deleted=:x',array(':x'=>0));
					foreach($employees as $employee){
						$emails[] = $employee->email;	
					}
			
		}
		if((in_array(1,$_POST['user']) || in_array(0,$_POST['user'])) && $_POST['batch']!=NULL)
		{			
			foreach($batch as $batches){
					$criteria = new CDbCriteria;
					$criteria->compare('batch_id',$batches);
					$teachers = TimetableEntries::model()->findAll($criteria);
			
				foreach($teachers as  $teacher){
						$criteria = new CDbCriteria;
						$criteria->compare('id',$teacher->employee_id);
						$personal = Employees::model()->findAll($criteria);
			
					foreach($personal as $personals){
							$teach[] = $personals->email;
								}
					}
			}
					$emails[] = array_unique($teach);	
			
		}
		
		if(in_array(2,$_POST['user']) || in_array(0,$_POST['user']))
		{
			foreach($batch as $batches){
				$criteria = new CDbCriteria;
				$criteria->compare('batch_id',$batches);
				$criteria->compare('is_active',1);
				$students1 = Students::model()->findAll($criteria);
				foreach($students1 as $student)
				{
					$criteria = new CDbCriteria;					
					$criteria->addInCondition("id", array($student->parent_id));
					$parents = Guardians::model()->findAll($criteria);
					foreach($parents as $parent){
						$emails[] = $parent->email;
					}

					
				}
				
					
			}		

		} 
		if(in_array(3,$_POST['user']) || in_array(0,$_POST['user']))
		{
			foreach($batch as $batches){
				$criteria = new CDbCriteria;
				$criteria->compare('batch_id',$batches);
				$criteria->compare('is_active',1);
				$students = Students::model()->findAll($criteria);
			}
				foreach($students as $student){
					$emails[] = $student->email;
				}			
		}
		//$admin = Users::model()->findByAttributes(array('id'=>1));
		$mails = array_unique($emails);
		$criteria = new CDbCriteria;
		$criteria->compare('mail_id',$draft->id);
		$attachmnts = EmailAttachments::model()->findAll($criteria);
		foreach($mails as $mail1){
			$mail = new PHPMailer();
			$mail->AddReplyTo('admin@example.com');
			$mail->SetFrom('admin@example.com');
			$mail->AddAddress($mail1);
			$mail->MsgHTML($_POST['editor1']);
			$mail->Subject = $_POST['EmailDrafts']['subject'];
			foreach($attachmnts as $attachmnt){
			$path =  'uploads/attachments/'.$attachmnt->mail_id.'/'.$attachmnt->file;	
			$mail->AddAttachment($path);
			}
			if($mail->Send()){
					Yii::app()->user->setFlash('successMessage', Yii::t('app','Email Sent Successfully'));
			}
		}
		
		
		$this->redirect(array('sendmail'));		
	}

}
public function actionDeletealldrafts()
{
	if(Yii::app()->request->isPostRequest){
	$delete = EmailDrafts::model()->findAllByAttributes(array('is_mailshot'=>0));
		foreach($delete as $delete1)
		{
			$delete1->delete();
		}
		
		$this->redirect(array('default/drafts'));
	}
	else
	{
		throw new CHttpException(404,Yii::t('app','Invalid Request.'));
	}
}
public function actionDeletedraft($id)
{
	if(Yii::app()->request->isPostRequest){
		$criteria = new CDbCriteria();
		$email_draft = EmailDrafts::model()->findByAttributes(array('id'=>$id));
			if($email_draft->delete()){
				$attachments = EmailAttachments::model()->findByAttributes(array('mail_id'=>$email_draft->id));
				if($attachments)
				{
					$attachments->delete();
				}
			
				$this->redirect(array('default/drafts'));
			}
			
	}
	else
	{
		throw new CHttpException(404,Yii::t('app','Invalid Request.'));
	}
}

public function actionDeleteallmailshot()
{
	if(Yii::app()->request->isPostRequest){
		$delete = EmailDrafts::model()->findAllByAttributes(array('is_mailshot'=>1));
			foreach($delete as $delete1)
			{
				$delete1->delete();
			}
			$this->redirect(array('default/mailshots'));
	}
	else
	{
		throw new CHttpException(404,Yii::t('app','Invalid Request.'));
	}
}
public function actionDeletemailshot($id)
{
	if(Yii::app()->request->isPostRequest){
		$criteria = new CDbCriteria();
		$delete = EmailDrafts::model()->findByAttributes(array('id'=>$id));
			if($delete->delete()){
				$this->redirect(array('default/mailshots'));
			}
	}
	else
	{
		throw new CHttpException(404,Yii::t('app','Invalid Request.'));
	}
}
public function actionDeletesent($id)
{
	if(Yii::app()->request->isPostRequest){
		$criteria = new CDbCriteria();
		$delete = EmailDrafts::model()->findByAttributes(array('id'=>$id));
			if($delete->delete()){
				$this->redirect(array('default/sentmail'));
			}
	}
	else
	{
		throw new CHttpException(404,Yii::t('app','Invalid Request.'));
	}
}
public function actionDeleteallsent()
{
	if(Yii::app()->request->isPostRequest){
		$delete = EmailDrafts::model()->findAllByAttributes(array('status'=>2));
			foreach($delete as $delete1)
			{
				$delete1->delete();
			}
			$this->redirect(array('default/sentmail'));
	}
	else
	{
		throw new CHttpException(404,Yii::t('app','Invalid Request.'));
	}
}


        public function actionSearch(){
		$this->renderPartial('new_compose/_searchcontact');
	}
        public function actionGroups(){
		$this->renderPartial('new_compose/_searchgroup');
	}
        public function actionBrowsedata(){
		$response	= array("status"=>"failed");
		$filename	= explode(".", $_FILES['myfile']['name']);
		$fname		= current( $filename );
		$extension	= end( $filename );
		$phonenumbers	= array();
		
		if($extension == "xls"){	// or $extension == "xlsx"
			Yii::import('application.extensions.ExcelReader.*');
			require_once('excel_reader.php');     // include the class
			$path	= $_FILES['myfile']['tmp_name'];

			// creates an object instance of the class, and read the excel file data
			$excel = new PhpExcelReader;
			$excel->read($path);
			
			$nr_sheets 	= count($excel->sheets);       // gets the number of sheets
			if($nr_sheets>0){
				// traverses the number of sheets and sets html table with each sheet data in $excel_data
				$sheet	= $excel->sheets[0];				
				$rows	= $sheet['numRows'];
				$cols	= $sheet['numCols'];
				if($rows>1){
					$fields	= array();
					$x 		= 1;
					$y		= 1;
					while( $y<=$cols ){
						$fields[$y - 1]	= isset($sheet['cells'][$x][$y]) ? str_replace("\s","",$sheet['cells'][$x][$y]) : '';
						$y++;
					}
					$nameindex		= $this->array_search2d("name", $fields);
					$nameindex		= ( $nameindex === false )?false:( $nameindex + 1 );
					$numberindex	= $this->array_search2d("email", $fields);
					$numberindex	= ( $numberindex === false )?false:( $numberindex + 1 );
					
					if( $numberindex === false ){
						$response["message"]	= Yii::t('app', "Excel file must have a field")." `email`";
					}
					else{
						$x++;						
						while($x <= $rows) {					
							if( $nameindex !== false )
								$phonenumbers[$x - 1]["name"]	= isset($sheet['cells'][$x][$nameindex]) ? $sheet['cells'][$x][$nameindex] : '';
							$phonenumbers[$x - 1]["email"]	= isset($sheet['cells'][$x][$numberindex]) ? $sheet['cells'][$x][$numberindex] : '';
							$x++;
						}			
						$response["status"]		= "success";
						$response["email"]	= $phonenumbers;
					}
				}
				else{
					$response["message"]	= Yii::t('app', "No data found");
				}
			}
			else{
				$response["message"]	= Yii::t('app', "No data found");
			}
		}	
		else if ($extension == "csv") {
                    
                   
			$contents	= file_get_contents( $_FILES['myfile']['tmp_name'] );			
			$datas 		= array_map("str_getcsv", preg_split('/\r*\n+|\r+/', $contents));
			
			$start		= 0;
			if(isset($datas[$start])){
				$nameindex		= $this->array_search2d("name", $datas[$start]); //array_search('name', str_replace("\s","",$datas[$start]));
				$numberindex	= $this->array_search2d("email", $datas[$start]); //array_search('number', $datas[$start]);
				
				//echo $numberindex;exit;
				
				if( $numberindex=== false ){
					$response["message"]	= Yii::t('app', "CSV file must have a field")." `email`";					
				}
				else{
					$start++;
					while( $start < count( $datas ) ){
						if( $nameindex )
							$phonenumbers[$start - 1]["name"]	= $datas[$start][$nameindex];
						$phonenumbers[$start - 1]["email"]	= $datas[$start][$numberindex];
						$start++;
					}
					$response["status"]		= "success";
					$response["email"]	= $phonenumbers;
				}
			}
			else{
				$response["message"]	= Yii::t('app', "No datas found");
			}
		}
		else {
			$response["message"]	= Yii::t('app', "Please upload a"). " csv / .xls ".Yii::t('app', "file");
		}
		
		echo json_encode($response);
		Yii::app()->end();
	}
        
        public function actionNewusermail()
	{
            
            //for new receiptients
            $recipient_user="";
            $email_list="";
            $emails = array();
            if(isset($_POST['recipients']) and $_POST['recipients']!=""){
                $recipient_user	= $_POST['recipients'];
		$recipients_data	= explode(',', $recipient_user);
                $email_array=array();
                foreach($recipients_data as $recipient_data)
                {
                        $parts	= explode(':', $recipient_data);
                        if(count($parts)>0)
                        {
                                $name				= (count($parts)>1)?$parts[0]:NULL;
                                $email		= end($parts);
                                $email_array[]=$email; 
                                $emails[]=$email;
                        }
                }
                $email_list= implode(",",$email_array);
              
            }
            
            
		$batch[] = $_POST['batch'];
		$user[] = $_POST['user'];
		require_once(dirname(__FILE__) .'/../../../extensions/PHPMailer/class.phpmailer.php');
		//$emails = array();
		$teach = array();
		if($_REQUEST['id'])
                {
                    $draft = EmailDrafts::model()->findByAttributes(array('id'=>$_REQUEST['id']));
		}
		else
                { 
                    $draft = new EmailDrafts;
		}
		if($_POST['EmailDrafts']['subject']){
			$draft->subject = $_POST['EmailDrafts']['subject'];
		}
		else{
			$draft->subject = "(".Yii::t('app', "no subject").")";
		}
		$draft->message = trim($_POST['editor1']);
		$draft->created_by = Yii::app()->user->id;
		$draft->status=2;
		$settings=UserSettings::model()->findByAttributes(array('user_id'=>1));									
			if($settings!=NULL)
			{	
			$timezone = Timezone::model()->findByAttributes(array('id'=>$settings->timezone));
			date_default_timezone_set($timezone->timezone);
			}
		$draft->created_on = date('Y-m-d H:i:s');
                $flag=0;
                if($email_list=="" && $_POST['user']=='' || ltrim($_POST['editor1'])=='')
                {
                    
                    if($_POST['user']=='' || ltrim($_POST['editor1'])=='')
                    {
                            if( $_POST['user']==''){
                                    Yii::app()->user->setFlash('usererrorMessage',Yii::t('app', "Please select a recipient group!"));
                        } 
                            if(ltrim($_POST['editor1'])==''){
                                Yii::app()->user->setFlash('editerrorMessage',Yii::t('app', "Message Field cannot be Empty!"));

                            }
                            if($_POST['batch']==NULL){
                                Yii::app()->user->setFlash('batchselect',Yii::t('app', "Please select a").' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' '.'!');
                                    //Yii::app()->user->setFlash('batchselect',Yii::t('app', "Please select a batch!"));
                            }				

                       $this->redirect(array('sendmails'));
                    }
                    else
                    {
                        $flag=1;
                    }
                }
                else
                {
                    $flag=1;
                }
                
		if($flag==1)
		{
		
		if(isset($_POST['mailshot']))
		$draft->is_mailshot = 1;
		if($draft->save())
                {
			if($_REQUEST['id'])
                        {
					$recipients = EmailRecipients::model()->findByAttributes(array('mail_id'=>$_REQUEST['id']));
			}
				else{ 
					$recipients = new EmailRecipients;
					$recipients->mail_id = $draft->id;
				}
			if(isset($user))
                        {
				if(in_array("0",$batch))
                                {
					$recipients->users = '0';
				}
				else
                                {					
					foreach($user as $users){
						$users1 = implode (',',$users);
					}
					$recipients->users = $users1;
				} 
                                    $recipients->user_email= $recipient_user;
					$recipients->save();	
			}
			if(isset($batch))
                        {
				if(in_array("0",$batch)){
					$recipients->batches = '0';
				}
				else{
					foreach($batch as $batches){
						$batches1 = implode (',',$batches);
					}
					$recipients->batches = $batches1;
				} 
                                $recipients->user_email= $recipient_user;
				$recipients->save();
			}
                         if(!isset($users))
                       {
                           $recipients->users = NULL;
                       }
                       if(!isset($batch))
                       {
                           $recipients->batches = "1";
                       }
                        $recipients->user_email= $email_list;
                        $recipients->save();
					
			if(CUploadedFile::getInstancesByName('Attachment')){
				
					$attachments = CUploadedFile::getInstancesByName('Attachment');
					if (isset($attachments) && count($attachments) > 0) {
						 $path	=	'uploads/attachments/'.$draft->id.'/';
							if(!is_dir($path)){
								mkdir($path);	
							}					
						  foreach ($attachments as $attachment => $pic) {
							   if ($pic->saveAs($path.$pic->name)) {
									 $img_add = new EmailAttachments;
                       				 $img_add->file = $pic->name; //it might be $img_add->name for you, filename is just what I chose to call it in my model
                        			 $img_add->mail_id = $draft->id; // this links your picture model to the main model (like your user, or profile model)
                            		 $img_add->created_by	=	Yii::app()->user->id;
									 $img_add->file_type = $pic->type;	
									 $img_add->save(); // DONE
                  		  		 }
								 else{
                        				echo Yii::t('app', 'Cannot upload!');
                    			 }
						  }
					}
				
			}
		}
		if(in_array(1,$_POST['user']) && $_POST['batch']==NULL)
		{			
					$employees = Employees::model()->findAll('is_deleted=:x',array(':x'=>0));
					foreach($employees as $employee){
						$emails[] = $employee->email;	
					}
			
		}
		if((in_array(1,$_POST['user']) || in_array(0,$_POST['user'])) && $_POST['batch']!=NULL)
		{			
			foreach($batch as $batches){
					$criteria = new CDbCriteria;
					$criteria->compare('batch_id',$batches);
					$teachers = TimetableEntries::model()->findAll($criteria);
			
				foreach($teachers as  $teacher){
						$criteria = new CDbCriteria;
						$criteria->compare('id',$teacher->employee_id);
						$personal = Employees::model()->findAll($criteria);
			
					foreach($personal as $personals){
							$teach[] = $personals->email;
								}
					}
			}
                        
                        $new_array= array_unique($teach);
                        foreach ($new_array as $data)
                        {
                            $emails[]= $data;
                        }
					//$emails[] = array_unique($teach);	
			
		}
		
		if(in_array(2,$_POST['user']) || in_array(0,$_POST['user']))
		{
			foreach($batch as $batches){
				$criteria = new CDbCriteria;
				$criteria->compare('batch_id',$batches);
				$criteria->compare('is_active',1);
				$students1 = Students::model()->findAll($criteria);
				foreach($students1 as $student)
				{
					$criteria = new CDbCriteria;					
					$criteria->addInCondition("id", array($student->parent_id));
					$parents = Guardians::model()->findAll($criteria);
					foreach($parents as $parent){
						$emails[] = $parent->email;
					}

					
				}
				
					
			}		

		} 
		if(in_array(3,$_POST['user']) || in_array(0,$_POST['user']))
		{
			foreach($batch as $batches){
				$criteria = new CDbCriteria;
				$criteria->compare('batch_id',$batches);
				$criteria->compare('is_active',1);
				$students = Students::model()->findAll($criteria);
			}
				foreach($students as $student){
					$emails[] = $student->email;
				}			
		}
                
                
                
                // $emails= $email_array;
                
                
                //var_dump(array_merge($emails[0],$email_array)); exit;
               // $emails = array_merge($emails,$email_array);
                //var_dump($emails); exit;
		//$admin = Users::model()->findByAttributes(array('id'=>1));
		$mails = array_unique($emails);
               
		$criteria = new CDbCriteria;
		$criteria->compare('mail_id',$draft->id);
		$attachmnts = EmailAttachments::model()->findAll($criteria);
                
                
                
		foreach($mails as $mail1)
                {
			$mail = new PHPMailer();
			$mail->AddReplyTo('admin@example.com');
			$mail->SetFrom('admin@example.com');
			$mail->AddAddress($mail1);
			$mail->MsgHTML($_POST['editor1']);
			$mail->Subject = $_POST['EmailDrafts']['subject'];
			foreach($attachmnts as $attachmnt){
			$path =  'uploads/attachments/'.$attachmnt->mail_id.'/'.$attachmnt->file;	
			$mail->AddAttachment($path);
			}
			if($mail->Send()){
					Yii::app()->user->setFlash('successMessage', Yii::t('app','Email Sent Successfully'));
			}
		}
		
		
		$this->redirect(array('sendmails'));		
	}

}

        public function actionSavenewdraft()
	{
            
            $recipient_user=""; 
            if(isset($_POST['recipients']) and $_POST['recipients']!=""){
                $recipient_user	= $_POST['recipients'];
		$recipients_data	= explode(',', $recipient_user);
                $email_array=array();
                foreach($recipients_data as $recipient_data)
                {
                        $parts	= explode(':', $recipient_data);
                        if(count($parts)>0)
                        {
                                $name				= (count($parts)>1)?$parts[0]:NULL;
                                $email		= end($parts);
                                $email_array[]=$email; 
                        }
                }
                $email_list= implode(",",$email_array);
               
			   
            }
		$batch[] = $_POST['batch'];
		$user[] = $_POST['user']; 
		if($_REQUEST['id']){
		$draft = EmailDrafts::model()->findByAttributes(array('id'=>$_REQUEST['id']));
		}
		else{ 
		$draft = new EmailDrafts;
		}
		if($_POST['EmailDrafts']['subject']){
			$draft->subject = $_POST['EmailDrafts']['subject'];
		}
		else{
			$draft->subject = "(no subject)";
		}
		$draft->message = $_POST['editor1'];
		 
		
		/*if($_POST['user']=='' || $_POST['editor1']=='')
		{
			
			 if( $_POST['user']=='')
		    {
			  
			  Yii::app()->user->setFlash('usererrorMessage',UserModule::t("Recipient Field cannot be Empty!"));
		    } 
		   if(ltrim($_POST['editor1'])=='')
		     {
			      Yii::app()->user->setFlash('editerrorMessage',UserModule::t("Message Field cannot be Empty!"));
			
		     }
			   $this->redirect(array('sendmail'));
		}
		else
		{*/
		$draft->created_by = Yii::app()->user->id;
		$settings=UserSettings::model()->findByAttributes(array('user_id'=>1));									
		if($settings!=NULL)
		{	
			$timezone = Timezone::model()->findByAttributes(array('id'=>$settings->timezone));
			date_default_timezone_set($timezone->timezone);
		}
		$draft->created_on = date('Y-m-d H:i:s');
		if(isset($_POST['mailshot']))
		$draft->is_mailshot = 1;		
		if($draft->save())
                {
			
				if($_REQUEST['id']){
					
					$recipients = EmailRecipients::model()->findByAttributes(array('mail_id'=>$_REQUEST['id']));
				}
				else{ 
					$recipients = new EmailRecipients;
					$recipients->mail_id = $draft->id;
				}
                                
			if(isset($user) and $user!=NULL)
			{
				
				if(count($user)==4)
				{
					$recipients->users = 'a';
				}
				else
				{
					foreach($user as $users){
						$users1 = implode (',',$users);
					}
					$recipients->users = $users1;
				} 
                                
					$recipients->save();
			}
			
			if(isset($batch)){
				
				if(in_array("0",$batch ) or $batch['0']==NULL){
					$recipients->users = 0;
					$recipients->batches = 0;
				}
				else{
					foreach($batch as $batches){
						$batches1 = implode (',',$batches);
					}
					$recipients->batches = $batches1;
				} 
				$recipients->save();
			}
                        
                       if(!isset($users))
                       {
                           $recipients->users = 0;
						 
                       }
                       if(!isset($batch))
                       {
                           $recipients->batches = "1";
                       }
                        $recipients->user_email= $email_list;
                      $recipients->save();
					   
                        
			if(CUploadedFile::getInstancesByName('Attachment')){				
					
					$attachments = CUploadedFile::getInstancesByName('Attachment');
					if (isset($attachments) && count($attachments) > 0) {
						 $path	=	'uploads/attachments/'.$draft->id.'/';
							if(!is_dir($path)){
								mkdir($path);	
							}					
						  foreach ($attachments as $attachment => $pic) {
							  $file_name = DocumentUploads::model()->getFileName($pic->name);
							   if ($pic->saveAs($path.$file_name)) {
									 $img_add = new EmailAttachments;
                       				 $img_add->file = $file_name; 
                        			 $img_add->mail_id = $draft->id; 
                            		 $img_add->created_by	=	Yii::app()->user->id;
									 $img_add->file_type = $pic->type;	
									 $img_add->save(); 
                  		  		 }
								 else{
                        				echo Yii::t('app', 'Cannot upload!');
                    			 }
						  }
					}
				
			}
			}
			if(isset($_POST['mailshot']))
				$this->redirect(array('mailshots'));
			else
				$this->redirect(array('drafts'));
				
				
				
	//}
				
	}
        
        protected function array_search2d($needle, $haystack) {
		for ($i = 0, $l = count($haystack); $i < $l; ++$i) {
			if ($needle==$haystack[$i]) return $i;
		}
		return false;
	}

	
}