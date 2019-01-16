<?php

/**
 * This is the model class for table "sms_settings".
 *
 * The followings are the available columns in table 'sms_settings':
 * @property integer $id
 * @property string $settings_key
 * @property integer $is_enabled
 */
class SmsSettings extends CActiveRecord
{
	public $enable_app;
	//public $enable_news;
	public $enable_std_ad;
	public $enable_std_atn;
	public $enable_emp_apmt;
	public $enable_exm_schedule;
	public $enable_exm_result;
	public $enable_fees;
	public $enable_library;
	/**
	 * Returns the static model of the specified AR class.
	 * @return SmsSettings the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sms_settings';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('is_enabled', 'numerical', 'integerOnly'=>true),
			array('settings_key', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, settings_key, is_enabled', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t("app",'ID'),
			'settings_key' => Yii::t("app",'Settings Key'),
			'is_enabled' => Yii::t("app",'Is Enabled'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('settings_key',$this->settings_key,true);
		$criteria->compare('is_enabled',$this->is_enabled);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	
	public function sendSms($to,$from,$message)
	{
		$mobile			= $to;
                $message		= $message;
                        
                        
                //fetch details from sms gateway settings                           
                $first = SmsGateway::model()->find(array('order'=>'id ASC'));
                if($first)
                {                                                          
                    if($first->method==1)
                    {
                        $method= "GET";
                    }
                    else if($first->method==2)
                    {
                        $method= "POST";
                    }                                       
                    $param= SmsGatewayParameter::model()->findAllByAttributes(array('gateway_id'=>$first->id));
                    foreach ($param as $data)
                    {
                        $string.= $data->name."=".urlencode("$data->value")."&";
                    }
                    
                    $parameters=$string;
                    $parameters= str_replace("mobile_number",$to,$parameters);
                    $parameters= str_replace("message_data",urlencode($message),$parameters);
                    $parameters= rtrim($parameters,"&");                  
                    $url= $first->url;
                }
                else
                {
                    //Change your configurations here.
			//---------------------------------
			$username="naveenn";
			$api_password="openschool1@";
			$sender="ESBLDR";
			$domain="www.bulksmsgateway.in";
			$priority="3";// 1-Normal,2-Priority,3-Marketing
			$method="GET";
			//---------------------------------
                        
                        $username		= urlencode($username);
			$api_password	= urlencode($api_password);
			$sender			= urlencode($sender);
			$message		= urlencode($message);
                        
                        $parameters="user=$username&password=$api_password&mobile=$mobile&message=$message&sender=$sender&type=$priority";
                        
			$url="http://bulksmsgateway.in/sendmessage.php";
                }
            		
			
		
			$ch = curl_init($url);
		
			if($method=="POST")
			{
				curl_setopt($ch, CURLOPT_POST,1);
				curl_setopt($ch, CURLOPT_POSTFIELDS,$parameters);
			}
			else
			{
				$get_url=$url."?".$parameters;
		
				curl_setopt($ch, CURLOPT_POST,0);
				curl_setopt($ch, CURLOPT_URL, $get_url);
			}
		
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1); 
			curl_setopt($ch, CURLOPT_HEADER,0);  // DO NOT RETURN HTTP HEADERS 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);  // RETURN THE CONTENTS OF THE CALL
			$return_val = curl_exec($ch);
			
			
			//http://bulksmsgateway.in/sendmessage.php?user=........&password=.......&mobile=........&message=.......&sender=.......&type=3
		
			if($return_val)
			{
				$counter=SmsCount::model()->findByAttributes(array('date'=>date('Y-m-d')));
				if($counter){
					
					$counter->current=$counter->current+1;
					$counter->save();
				}
				else{
					$counter = new SmsCount;
					$counter->current = 1;
					$counter->date = date('Y-m-d');
					$counter->save();
				}
				
			}
			
		return 1 ;
		
				
		
	
	}
	
	/*public function sendSms($to,$from,$message)
	{
		// Add SMS gateway settings here. See the example below
		
		require_once('/path/to/extensions/twilio/Services/Twilio.php');
 
		$sid = "{{ ACCOUNT SID }}"; // Your Account SID from www.twilio.com/user/account
		$token = "{{  AUTH TOKEN }}"; // Your Auth Token from www.twilio.com/user/account
		 
		$client = new Services_Twilio($sid, $token);
		$message = $client->account->sms_messages->create(
		  '+14158141829', // From a valid Twilio number
		  '+14159352345', // Text this number
		  "Hello world! This is admin, testing our twilio api"
		  
		);	
		
		// Set the retun value from the gateway to $return_val variable. Uncomment the following segment of code after that.	
			
		if($return_val)
		{
			$counter=SmsCount::model()->findByAttributes(array('date'=>date('Y-m-d')));
			if($counter){
				
				$counter->current=$counter->current+1;
				$counter->save();
			}
			else{
				$counter = new SmsCount;
				$counter->current = 1;
				$counter->date = date('Y-m-d');
				$counter->save();
			}
			
		}
			
			return 1 ;
	
	}*/
	
	
	/*protected function utf8_to_unicode($str) {
        $unicode = array();
        $values = array();
        $lookingFor = 1;
        for ($i = 0; $i < strlen($str); $i++) {
            $thisValue = ord($str[$i]);
            if ($thisValue < 128) {
                $number = dechex($thisValue);
                $unicode[] = (strlen($number) == 1) ? '%u000' . $number : "%u00" . $number;
            } else {
                if (count($values) == 0)
                    $lookingFor = ( $thisValue < 224 ) ? 2 : 3;
                $values[] = $thisValue;
                if (count($values) == $lookingFor) {
                    $number = ( $lookingFor == 3 ) ?
                            ( ( $values[0] % 16 ) * 4096 ) + ( ( $values[1] % 64 ) * 64 ) + ( $values[2] % 64 ) :
                            ( ( $values[0] % 32 ) * 64 ) + ( $values[1] % 64
                            );
                    $number = dechex($number);
                    $unicode[] =
                            (strlen($number) == 3) ? "%u0" . $number : "%u" . $number;
                    $values = array();
                    $lookingFor = 1;
                } // if
            } // if
        }
        return implode("", $unicode);
    }*/
	//$hexMessage = str_replace('%u', '',utf8_to_unicode($_message));
	
	protected function utf8ToUnicodeCodePoints($str) {
		if (!mb_check_encoding($str, 'UTF-8')) {
			trigger_error('$str is not encoded in UTF-8, I cannot work like this'); // Not Translated-Rajith
			return false;
		}
		return preg_replace_callback('/./u', function ($m) {
			$ord = ord($m[0]);
			if ($ord <= 127) {
				return sprintf('\u%04x', $ord);
			} else {
				return trim(json_encode($m[0]), '"');
			}
		}, $str);
	}
}