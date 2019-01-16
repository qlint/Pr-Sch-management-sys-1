<?php

/**
 * This is the model class for table "material_requistion".
 *
 * The followings are the available columns in table 'material_requistion':
 * @property integer $id
 * @property integer $groupstaff_id
 * @property integer $pepartment_id
 * @property integer $material_id
 * @property integer $quantity
 * @property integer $status_hod
 * @property integer $status_pm
 * @property integer $is_issued
 */
class PurchaseSale extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return MaterialRequistion the static model class
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
		return 'purchase_material_requistion';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('employee_id, material_id, quantity, purchaser', 'required'),
			array('return_date, return_reason', 'required', 'on'=>'return'),
			array('return_date', 'compare', 'compareAttribute'=>'issued_date', 'operator'=>'>=', 'on'=>'return'),
			array('employee_id, department_id, material_id, status, is_issued', 'numerical', 'integerOnly'=>true),
			array('quantity', 'checkstock'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, employee_id, department_id, material_id, quantity, status, is_issued ,return_reason , return_date', 'safe', 'on'=>'search'),			
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
	 * @return array default scope.
	 */
	public function defaultScope()
	{
		return array(
			'condition' => '`t`.`type`=2'
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('app', 'ID'),
			'employee_id' => Yii::t('app', 'Puchased By'),
			'department_id' => Yii::t('app', 'Department'),
			'material_id' => Yii::t('app', 'Item Name'),
			'purchaser' => Yii::t('app', 'Purchaser Type'),
			'quantity' => Yii::t('app', 'Quantity'),
			'status' => Yii::t('app', 'Status'),
			'is_issued' => Yii::t('app', 'Is Issued'),
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
		$criteria->compare('employee_id',$this->employee_id);
		$criteria->compare('department_id',$this->pepartment_id);
		$criteria->compare('material_id',$this->material_id);
		$criteria->compare('quantity',$this->quantity);
		$criteria->compare('status',$this->status);
		$criteria->compare('is_issued',$this->is_issued);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function checkstock(){
		if($this->scenario!="return" and isset($this->material_id) and $this->material_id!=NULL and isset($this->quantity) and $this->quantity!=NULL){
			$item	= PurchaseStock::model()->findByAttributes(array('item_id'=>$this->material_id));
			if($item==NULL or $item->quantity < $this->quantity){
				$this->addError('quantity', Yii::t('app', 'Stock not available'));
			}
		}
	}
}