<?php

/**
 * This is the model class for table "cbsc_co_scholastic".
 *
 * The followings are the available columns in table 'cbsc_co_scholastic':
 * @property integer $id
 * @property integer $batch_id
 * @property string $skill
 * @property string $description
 */
class CbscCoScholastic extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return CbscCoScholastic the static model class
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
		return 'cbsc_co_scholastic';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('batch_id, skill, description', 'required'),
			array('batch_id', 'numerical', 'integerOnly'=>true),
			array('description', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, batch_id, skill, description', 'safe', 'on'=>'search'),
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
			'batch_id' => 'Batch',
			'skill' => 'Skill',
			'description' => 'Description',
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
		$criteria->compare('batch_id',$_REQUEST['id']);
		$criteria->compare('skill',$this->skill,true);
		$criteria->compare('description',$this->description,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        public function getGrades($id)
        {
            $list= array(
                5 =>  Yii::t("app","A+"),
                4 =>  Yii::t("app","A"),
                3 =>  Yii::t("app","B"),
                2 =>  Yii::t("app","C"),
                1 =>  Yii::t("app","D"),
            );
            if(isset($list[$id]))
            {
                return $list[$id];
            }
            else
            {
                return "-";
            }
        }
		function coscholastic($data,$row)
		{	
			$coscholastic = CbscCoScholastic::model()->findByAttributes(array('id'=>$data->id));
			echo ucfirst($coscholastic->skill);
		}
		function description($data,$row)
		{	
			$coscholastic = CbscCoScholastic::model()->findByAttributes(array('id'=>$data->id));
			echo ucfirst($coscholastic->description);
		}
}