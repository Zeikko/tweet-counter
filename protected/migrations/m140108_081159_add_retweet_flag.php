<?php

class m140108_081159_add_retweet_flag extends CDbMigration
{
	public function up()
	{
            $this->addColumn('tweet', 'retweet', 'INT(1)');
	}

	public function down()
	{
            $this->dropColumn('tweet', 'retweet');
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