<?php

/**
 * This is the model class for table "search_phrase_in_tweet".
 *
 * The followings are the available columns in table 'search_phrase_in_tweet':
 * @property string $id
 * @property integer $search_phrase_id
 * @property string $tweet_id
 *
 * The followings are the available model relations:
 * @property Tweet $tweet
 * @property SearchPhrase $searchPhrase
 */
class SearchPhraseInTweet extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'search_phrase_in_tweet';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('search_phrase_id, tweet_id', 'required'),
			array('search_phrase_id', 'numerical', 'integerOnly'=>true),
			array('tweet_id', 'length', 'max'=>20),
                        array('id', 'length', 'max'=>11),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, search_phrase_id, tweet_id', 'safe', 'on'=>'search'),
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
			'tweet' => array(self::BELONGS_TO, 'Tweet', 'tweet_id'),
			'searchPhrase' => array(self::BELONGS_TO, 'SearchPhrase', 'search_phrase_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'search_phrase_id' => 'Search Phrase',
			'tweet_id' => 'Tweet',
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
		$criteria->compare('search_phrase_id',$this->search_phrase_id);
		$criteria->compare('tweet_id',$this->tweet_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SearchPhraseInTweet the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
