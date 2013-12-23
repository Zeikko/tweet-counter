<?php

class m131219_093410_create_search_phrase_table extends CDbMigration
{

    public function up()
    {
        $this->createTable('search_phrase', array(
            'id' => 'int(11) NOT NULL AUTO_INCREMENT',
            'group_id' => 'int(11) NOT NULL',
            'search_phrase' => 'varchar(64) NOT NULL',
            'PRIMARY KEY (id)',
            'KEY `search_phrase` (`search_phrase`)',
        ));

        $this->addForeignKey('fk_tweet_search_phrase', 'tweet', 'search_phrase_id', 'search_phrase', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk_tweet_search_phrase', 'tweet');
        $this->dropTable('search_phrase');
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