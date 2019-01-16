<?php

/**
 * This is the model class for table "finance_fee_particulars".
 *
 * The followings are the available columns in table 'finance_fee_particulars':
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $amount
 * @property integer $finance_fee_category_id
 * @property integer $student_category_id
 * @property string $admission_no
 * @property integer $student_id
 * @property integer $is_deleted
 * @property string $created_at
 * @property string $updated_at
 */
class FinanceFeeParticulars extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return FinanceFeeParticulars the static model class
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
		return 'finance_fee_particulars';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('finance_fee_category_id, student_category_id, student_id, is_deleted, amount', 'numerical', 'integerOnly'=>true),
			array('amount', 'match', 'pattern'=>'/([1-9][0-9]*?)(\.[0-9]{2})?/'),
			array('name, admission_no', 'length', 'max'=>25),
			array('amount', 'length', 'max'=>15),
			array('description, created_at, updated_at', 'safe'),
			array('name, amount','required'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, description, amount, finance_fee_category_id, student_category_id, admission_no, student_id, is_deleted, created_at, updated_at', 'safe', 'on'=>'search'),
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
			'name' => Yii::t("app",'Name'),
			'description' => Yii::t("app",'Description'),
			'amount' => Yii::t("app",'Amount'),
			'finance_fee_category_id' => Yii::t("app",'Finance Fee Category'),
			'student_category_id' => Yii::t("app",'Student Category'),
			'admission_no' => Yii::t("app",'Admission No'),
			'student_id' => Yii::t("app",'Student'),
			'is_deleted' => Yii::t("app",'Is Deleted'),
			'created_at' => Yii::t("app",'Created At'),
			'updated_at' => Yii::t("app",'Updated At'),
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('amount',$this->amount,true);
		$criteria->compare('finance_fee_category_id',$this->finance_fee_category_id);
		$criteria->compare('student_category_id',$this->student_category_id);
		$criteria->compare('admission_no',$this->admission_no,true);
		$criteria->compare('student_id',$this->student_id);
		$criteria->compare('is_deleted',$this->is_deleted);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('updated_at',$this->updated_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function amount($data,$row){		
		
		$amount = number_format($data->amount,2);
		return $amount;
	}
	 public function getCategory($data,$row)
	{
		$student_category=StudentCategories::model()->findByAttributes(array('id'=>$data->student_category_id,'is_deleted'=>0));
		if(count($student_category)>0)
		{
			return $student_category->name;
		}
		else
		{
			return '-';
		}
	}
	
	 public function financeCategory($data,$row)
	{
		$fees_category=FinanceFeeCategories::model()->findByAttributes(array('id'=>$data->finance_fee_category_id,'is_deleted'=>0));
		if(count($fees_category)>0)
		{
			return $fees_category->name;
		}
		else{
			return '-';
		}
	}
	public function getCategory_name(){
		
		$fees_category=FinanceFeeCategories::model()->findByAttributes(array('id'=>$this->finance_fee_category_id,'is_deleted'=>0));
		if(count($fees_category)>0)
		{
			return $fees_category->name;
		}
		else{
			return '-';
		}        
    }
	
	public function getStudent_category(){
		
		$student_category=StudentCategories::model()->findByAttributes(array('id'=>$this->student_category_id,'is_deleted'=>0));
		if(count($student_category)>0)
		{
			return $student_category->name;
		}
		else{
			return '-';
		}        
    }
	
	public function getStudent_name(){
		
		$student=Students::model()->findByAttributes(array('admission_no'=>$this->admission_no,'is_deleted'=>0));
		if(count($student)>0)
		{
			return $student->first_name." ".$student->last_name;
		}
		else{
			return '-';
		}        
    }
	
	public function getCreate_at()
	{
		$settings=UserSettings::model()->findByAttributes(array('user_id'=>1));
		$timezone = Timezone::model()->findByAttributes(array('id'=>$settings->timezone));		
		date_default_timezone_set($timezone->timezone);
		$date = date($settings->displaydate,strtotime($this->created_at));	
		$time = date($settings->timeformat,strtotime($this->created_at)); 
		return $date.' '.$time;
		//return date('d-m-Y H:i:s',strtotime($this->created_at));
	}
	public function getUpdate_at()
	{
		$settings=UserSettings::model()->findByAttributes(array('user_id'=>1));
		$timezone = Timezone::model()->findByAttributes(array('id'=>$settings->timezone));		
		date_default_timezone_set($timezone->timezone);
		$date = date($settings->displaydate,strtotime($this->updated_at));	
		$time = date($settings->timeformat,strtotime($this->updated_at)); 
		return $date.' '.$time;
		//return date('d-m-Y H:i:s',strtotime($this->created_at));
	}
}