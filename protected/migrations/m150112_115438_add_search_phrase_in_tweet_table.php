<?php

class m150112_115438_add_search_phrase_in_tweet_table extends CDbMigration
{
	public function up()
	{
            $this->createTable('search_phrase_in_tweet', array(
                'id' => 'int(11) NOT NULL AUTO_INCREMENT',
                'search_phrase_id' => 'int(11) NOT NULL',
                'tweet_id' => 'bigint(20) NOT NULL',
                'PRIMARY KEY (id)',
                'KEY `search_phrase_id` (`search_phrase_id`)',
                'KEY `tweet_id` (`tweet_id`)',
            ));
            
            $this->addForeignKey('fk_search_phrase', 'search_phrase_in_tweet', 'search_phrase_id', 'search_phrase', 'id', 'NO ACTION', 'NO ACTION');
            $this->addForeignKey('fk_tweet', 'search_phrase_in_tweet', 'tweet_id', 'tweet', 'id', 'NO ACTION', 'NO ACTION');
            $tweets = Tweet::model()->findAll();
            $transaction = Yii::app()->db->beginTransaction();
            try {
                foreach($tweets as $tweet) {
                    $searchPhraseInTweet = new SearchPhraseInTweet();
                    $searchPhraseInTweet->tweet_id = $tweet->id;
                    $searchPhraseInTweet->search_phrase_id = $tweet->search_phrase_id;
                    $searchPhraseInTweet->save();
                }
                $transaction->commit();
            }
            catch(Exception $e)
            {
               $transaction->rollback();
            }
            
        }

	public function down()
	{
            $this->dropTable('search_phrase_in_tweet');
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}