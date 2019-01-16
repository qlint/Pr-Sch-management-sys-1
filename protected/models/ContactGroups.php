<?php

/**
 * This is the model class for table "contacts_groups".
 *
 * The followings are the available columns in table 'contacts_groups':
 * @property integer $id
 * @property string $group_name
 * @property integer $created_by
 * @property integer $created_at
 * @property integer $status
 */
class ContactGroups extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return ContactGroups the static model class
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
		return 'contacts_groups';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('group_name, created_by, created_at, status', 'required'),
			array('created_by, status', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, group_name, created_by, created_at, status', 'safe', 'on'=>'search'),
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
			'group_name' => Yii::t("app",'Group Name'),
			'created_by' => Yii::t("app",'Created By'),
			'created_at' => Yii::t("app",'Created At'),
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
		$criteria->compare('group_name',$this->group_name,true);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('created_at',$this->created_at);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	protected function getTotalcontacts(){
		$criteria	= new CDbCriteria;
		$criteria->condition	= '`group_id`=:group_id';
		$criteria->params		= array(":group_id"=>$this->id);
		return ContactsList::model()->count($criteria);
	}
	
	public function getCreatedby() {
		
		$user = Profile::model()->findByAttributes(array('user_id'=>$this->created_by));	
		return $user->firstname.' '.$user->lastname;
	}
	
	public function getStatus(){
		return ' jkhkj ';
	}
}