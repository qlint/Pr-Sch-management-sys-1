<?php

/**
 * This is the model class for table "notify_receivers".
 *
 * The followings are the available columns in table 'notify_receivers':
 * @property integer $id
 * @property integer $notify_id
 * @property integer $receiver_id
 * @property integer $role
 * @property integer $status
 */
class NotifyReceivers extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return NotifyReceivers the static model class
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
		return 'notify_receivers';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(			
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, notify_id, receiver_id, role, status', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'notify_id' => 'Notify',
			'receiver_id' => 'Receiver',
			'role' => 'Role',
			'status' => 'Status',
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
		$criteria->compare('notify_id',$this->notify_id);
		$criteria->compare('receiver_id',$this->receiver_id);
		$criteria->compare('role',$this->role);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}