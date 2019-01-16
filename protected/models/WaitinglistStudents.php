<?php

/**
 * This is the model class for table "waitinglist_students".
 *
 * The followings are the available columns in table 'waitinglist_students':
 * @property integer $id
 * @property integer $student_id
 * @property integer $priority
 */
class WaitinglistStudents extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return WaitinglistStudents the static model class
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
		return 'waitinglist_students';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('priority, batch_id', 'required'),
			array('priority','check'),
			array('student_id, priority', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, student_id, priority, name, status', 'safe', 'on'=>'search'),
		);
	}
	
	public function check($attribute,$params)
	{
		
		if((Yii::app()->controller->action->id!='manage' and Yii::app()->controller->id!='WaitinglistStudents') and $this->$attribute!='')
		{
			$result = WaitinglistStudents::model()->findByAttributes(array('priority'=>$_REQUEST['WaitinglistStudents']['priority'],'batch_id'=>$_REQUEST['WaitinglistStudents']['batch_id']));			
			if($result!=NULL)
			{
				$this->addError($attribute,Yii::t("app",'Priority already in use'));
			}
		}
		if((Yii::app()->controller->action->id=='manage' and Yii::app()->controller->id!='WaitinglistStudents') and $this->$attribute!='')
		{
			//$result = WaitinglistStudents::model()->findByAttributes(array('student_id'=>$_REQUEST['id']));	
			$result = WaitinglistStudents::model()->findByAttributes(array('priority'=>$_REQUEST['WaitinglistStudents']['priority'],'batch_id'=>$_REQUEST['WaitinglistStudents']['batch_id']));		
			if($result!=NULL and $result->student_id!=$_REQUEST['id'])
			{				
				$this->addError($attribute,Yii::t("app",'Priority already in use'));
			}	
			
		}
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
			'student_id' => Yii::t("app",'Student'),
			'priority' => Yii::t("app",'Priority'),
			'name' => Yii::t("app",'Name'),
			'batch_id' => Yii::t("app",'Class'),
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
		$criteria->compare('student_id',$this->student_id);
		$criteria->compare('priority',$this->priority);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}