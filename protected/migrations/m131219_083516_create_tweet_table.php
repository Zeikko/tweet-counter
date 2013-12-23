<?php

class m131219_083516_create_tweet_table extends CDbMigration
{

    public function up()
    {
        $this->createTable('tweet', array(
            'id' => 'bigint(20) NOT NULL',
            'search_phrase_id' => 'int(11) NOT NULL',
            'text' => 'varchar(160) NOT NULL',
            'created_at' => 'int(11) NOT NULL',
            'geo_lat' => 'decimal(10,5) NULL',
            'geo_long' => 'decimal(10,5) NULL',
            'user_id' => 'int(10) unsigned NOT NULL',
            'screen_name' => 'varchar(20) NOT NULL',
            'name' => 'varchar(40) DEFAULT NULL',
            'profile_image_url' => 'varchar(200) DEFAULT NULL',
            'PRIMARY KEY (id)',
            'KEY `created_at` (`created_at`)',
            'KEY `user_id` (`user_id`)',
            'KEY `screen_name` (`screen_name`)',
            'KEY `name` (`name`)',
            'KEY `text` (`text`)',
        ));
    }

    public function down()
    {
        $this->dropTable('tweet');
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