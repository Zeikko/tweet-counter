<?php

class m140107_125952_add_retweets_and_favorites extends CDbMigration
{
	public function up()
	{
            $this->addColumn('tweet', 'retweet_count', 'INT(11)');
            $this->addColumn('tweet', 'favorite_count', 'INT(11)');
	}

	public function down()
	{
            $this->dropColumn('tweet', 'retweet_count');
            $this->dropColumn('tweet', 'favorite_count');
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