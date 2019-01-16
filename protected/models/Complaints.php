<?php

/**
 * This is the model class for table "complaints".
 *
 * The followings are the available columns in table 'complaints':
 * @property integer $id
 * @property integer $uid
 * @property integer $category_id
 * @property string $subject
 * @property string $complaint
 * @property string $date
 * @property string $reopened_date
 * @property integer $status
 */
class Complaints extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Complaints the static model class
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
		return 'complaints';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('uid,category_id, subject, complaint, date, viewed, status', 'required'),
			array('uid, category_id,viewed, status', 'numerical', 'integerOnly'=>true),
			array('subject', 'length', 'max'=>120),
			array('complaint', 'length', 'max'=>1024),
			array('reopened_date, closed_by', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, uid, category_id, subject, complaint, date, reopened_date,viewed, status', 'safe', 'on'=>'search'),
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
			'uid' => Yii::t("app",'Uid'),
			'category_id' => Yii::t("app",'Category'),
			'subject' => Yii::t("app",'Subject'),
			'complaint' => Yii::t("app",'Complaint'),
			'date' => Yii::t("app",'Date'),
			'reopened_date' => Yii::t("app",'Reopened Date'),
			'viewed' => Yii::t("app",'Viewed'),
			'status' => Yii::t("app",'Status'),
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
		$criteria->compare('uid',$this->uid);
		$criteria->compare('category_id',$this->category_id);
		$criteria->compare('subject',$this->subject,true);
		$criteria->compare('complaint',$this->complaint,true);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('reopened_date',$this->reopened_date,true);
		$criteria->compare('viewed',$this->viewed,true);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	//Name of the user who closed or created the complaint
	public function getName($uid)
	{		
		$role	= Rights::getAssignedRoles($uid);
		$name	= '';
		if(key($role) == 'student'){
			$model	= Students::model()->findByAttributes(array('uid'=>$uid));
			if($model){
				if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){
					$name	= $model->studentFullName('forStudentProfile');
				}
			}
		}
		else if(key($role) == 'parent'){
			$model	= Guardians::model()->findByAttributes(array('uid'=>$uid));
			if($model){
				if(FormFields::model()->isVisible("fullname", "Guardians", "forStudentProfile")){
					$name	= $model->parentFullName('forStudentProfile');
				}
			}
		}
		else if(key($role) == 'teacher'){
			$model	= Employees::model()->findByAttributes(array('uid'=>$uid));
			if($model){
				$name	= Employees::model()->getTeachername($model->id, 0);
			}
		}
		else{
			$model	= Profile::model()->findByAttributes(array('user_id'=>$uid));
			if($model){
				$name	= ucfirst($model->firstname).' '.ucfirst($model->lastname);
			}
		}
		return $name;
	}
}