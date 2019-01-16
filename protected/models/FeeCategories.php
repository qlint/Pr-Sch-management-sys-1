<?php

/**
 * This is the model class for table "fee_categories".
 *
 * The followings are the available columns in table 'fee_categories':
 * @property string $id
 * @property string $name
 * @property string $description
 * @property string $created_at
 * @property string $last_edited
 * @property integer $created_by
 * @property integer $edited_by
 */
class FeeCategories extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return FeeCategories the static model class
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
		return 'fee_categories';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		if(isset(Yii::app()->controller->module->id) and Yii::app()->controller->module->id=="fees" and Yii::app()->controller->id=="create"){
			return array(
				array('name, created_at, created_by', 'required'),
				array('created_by, edited_by', 'numerical', 'integerOnly'=>true),
				array('name', 'length', 'max'=>250),
				array('academic_year_id,type,description, last_edited, invoice_generated, amount_divided', 'safe'),
				// The following rule is used by search().
				// Please remove those attributes that should not be searched.
				array('id, name, description, created_at, last_edited, created_by, edited_by', 'safe', 'on'=>'search'),
			);
		}
		else{
			return array(
				array('name, start_date, end_date, created_at, created_by', 'required'),
				array('start_date, end_date', 'validDate'),
				array('subscription_type,type,amount_divided', 'safe'),
				// The following rule is used by search().
				// Please remove those attributes that should not be searched.
				array('id, name, description, created_at, last_edited, created_by, edited_by', 'safe', 'on'=>'search'),
			);
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
			'id' => Yii::t('app','ID'),
			'name' => Yii::t('app','Name'),			
			'description' => Yii::t('app','Description'),			
			'subscription_type' => Yii::t('',"Subscription Type"),
			'start_date' => Yii::t('app','Start Date'),
			'end_date' => Yii::t('app','End Date'),
			'created_at' => Yii::t('app','Created At'),
			'last_edited' => Yii::t('app','Last Edited'),
			'created_by' => Yii::t('app','Created By'),
			'edited_by' => Yii::t('app','Edited By'),
			'amount_divided' => Yii::t('app','Divide the fee amount by number of subscriptions'),
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('last_edited',$this->last_edited,true);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('edited_by',$this->edited_by);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function validDate($attribute,$params)
	{	$acyear	= AcademicYears::model()->findByPk($this->academic_year_id);
		if($acyear!=NULL){
			if($attribute=="start_date"){	//start date			
				$ac_start	= strtotime($acyear->start);
				$ac_end		= strtotime($acyear->end);
				$sb_start	= strtotime($this->start_date);
				if($sb_start<$ac_start or $sb_start>$ac_end){
					$this->addError($attribute, Yii::t('app', 'Start date must be within selected academic year'));
				}
			}		
			else if($attribute=="end_date"){	//end date
				$ac_start	= strtotime($acyear->start);
				$ac_end		= strtotime($acyear->end);
				$sb_end		= strtotime($this->end_date);
				if($sb_end>$ac_end or $sb_end<$ac_start){
					$this->addError($attribute, Yii::t('app', 'End date must be within selected academic year'));
				}
				else{
					if(!$this->getError('start_date') and $this->start_date!=NULL){
						$sb_start	= strtotime($this->start_date);
						$sb_end		= strtotime($this->end_date);
						if($sb_start>$sb_end){
							$this->addError($attribute, Yii::t('app', 'End date must be a date after start date'));
						}
					}
				}
			}
		}
	}
}