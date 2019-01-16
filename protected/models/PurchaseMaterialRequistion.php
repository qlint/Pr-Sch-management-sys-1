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
class PurchaseMaterialRequistion extends CActiveRecord
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
			array('department_id, material_id, quantity,', 'required'),
			array('employee_id, department_id, material_id, status, is_issued', 'numerical', 'integerOnly'=>true),
			array('quantity', 'numerical', 'min' => 1, 'max'=>100, 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, employee_id, department_id, material_id, quantity, status, is_issued ,return_reason , return_date', 'safe', 'on'=>'search'),	
			array('return_reason,return_date','required','on'=>'retrunitem'),		
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
			'condition' => '`t`.`type`=1'
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'employee_id' => 'Teacher',
			'return_reason' => 'Reason',
			'return_date' => 'Date',
			'department_id' => 'Department',
			'material_id' => 'Material',
			'quantity' => 'Quantity',
			'status' => 'Status',
			'is_issued' => 'Is Issued',
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
		$criteria->compare('status_pm',$this->status_pm);
		$criteria->compare('status_tchr',$this->status_tchr);
		$criteria->compare('is_issued',$this->is_issued);
		$criteria->compare('is_send',$this->is_send);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}