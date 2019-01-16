<?php

/**
 * This is the model class for table "fee_subscriptions".
 *
 * The followings are the available columns in table 'fee_subscriptions':
 * @property string $id
 * @property string $fee_id
 * @property integer $subscription_type
 * @property string $due_date
 * @property string $created_at
 * @property integer $created_by
 */
class FeeSubscriptions extends CActiveRecord
{
	public $start_date;
	public $end_date;
	public $monthday;
	public $weekday;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return FeeSubscriptions the static model class
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
		return 'fee_subscriptions';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('subscription_type, due_date', 'required'),
			array('subscription_type, created_by', 'numerical', 'integerOnly'=>true),
			array('fee_id', 'length', 'max'=>20),
			array('due_date', 'validDate'),
			array('fee_id, created_at, created_by', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, fee_id, subscription_type, due_date, created_at, created_by', 'safe', 'on'=>'search'),
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
			'id' => Yii::t('app','ID'),
			'fee_id' => Yii::t('app','Fee'),
			'subscription_type' => Yii::t('app','Subscription Type'),
			'due_date' => Yii::t('app','Due Date'),
			'created_at' => Yii::t('app','Created At'),
			'created_by' => Yii::t('app','Created By'),
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('fee_id',$this->fee_id,true);
		$criteria->compare('subscription_type',$this->subscription_type);
		$criteria->compare('due_date',$this->due_date,true);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('created_by',$this->created_by);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function validDate($attribute,$params)
	{	
		if($this->start_date!=NULL && $this->end_date!=NULL){
			$sb_start	= strtotime($this->start_date);
			$sb_end		= strtotime($this->end_date);
			$sb_due		= strtotime($this->due_date);
			if($sb_due<=$sb_start or $sb_due>$sb_end){
				$this->addError($attribute, Yii::t('app', 'Due date must be within selected date range'));
			}
		}
	}
}