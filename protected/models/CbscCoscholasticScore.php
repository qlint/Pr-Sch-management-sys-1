<?php

/**
 * This is the model class for table "cbsc_coscholastic_score".
 *
 * The followings are the available columns in table 'cbsc_coscholastic_score':
 * @property integer $id
 * @property integer $coscholastic_id
 * @property integer $student_id
 * @property string $score
 */
class CbscCoscholasticScore extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return CbscCoscholasticScore the static model class
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
		return 'cbsc_coscholastic_score';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('coscholastic_id, student_id', 'required'),
			array('coscholastic_id, student_id', 'numerical', 'integerOnly'=>true),
			array('score', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, coscholastic_id, student_id, score', 'safe', 'on'=>'search'),
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
			'coscholastic_id' => 'Coscholastic',
			'student_id' => 'Student',
			'score' => 'Score',
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
		$criteria->compare('coscholastic_id',$this->coscholastic_id);
		$criteria->compare('student_id',$this->student_id);
		$criteria->compare('score',$this->score,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}