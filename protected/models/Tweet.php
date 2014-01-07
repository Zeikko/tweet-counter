<?php

/**
 * This is the model class for table "tweet".
 *
 * The followings are the available columns in table 'tweet':
 * @property string $id
 * @property string $text
 * @property integer $created_at
 * @property string $geo_lat
 * @property string $geo_long
 * @property string $user_id
 * @property string $screen_name
 * @property string $name
 * @property string $profile_image_url
 */
class Tweet extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tweet';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, text, created_at, user_id, screen_name', 'required'),
			array('created_at, retweet_count, favorite_count', 'numerical', 'integerOnly'=>true),
			array('id, screen_name', 'length', 'max'=>20),
			array('text', 'length', 'max'=>160),
			array('geo_lat, geo_long, user_id', 'length', 'max'=>10),
			array('name', 'length', 'max'=>40),
			array('profile_image_url', 'length', 'max'=>200),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, text, created_at, geo_lat, geo_long, user_id, screen_name, name, profile_image_url', 'safe', 'on'=>'search'),
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
			'text' => 'Text',
			'created_at' => 'Created At',
			'geo_lat' => 'Geo Lat',
			'geo_long' => 'Geo Long',
			'user_id' => 'User',
			'screen_name' => 'Screen Name',
			'name' => 'Name',
			'profile_image_url' => 'Profile Image Url',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('text',$this->text,true);
		$criteria->compare('created_at',$this->created_at);
		$criteria->compare('geo_lat',$this->geo_lat,true);
		$criteria->compare('geo_long',$this->geo_long,true);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('screen_name',$this->screen_name,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('profile_image_url',$this->profile_image_url,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Tweet the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public function getDataFromJson($json) {
            $this->attributes = $json;
            $this->created_at = strtotime($json['created_at']);
            $this->user_id = $json['user']['id'];
            $this->screen_name = $json['user']['screen_name'];
            $this->name = $json['user']['name'];
            $this->profile_image_url = $json['user']['profile_image_url'];
        }
}
