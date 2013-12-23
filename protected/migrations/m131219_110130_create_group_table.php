<?php

class m131219_110130_create_group_table extends CDbMigration
{

    public function up()
    {
        $this->createTable('group', array(
            'id' => 'int(11) NOT NULL AUTO_INCREMENT',
            'name' => 'varchar(64) NOT NULL',
            'PRIMARY KEY (id)',
            'KEY `name` (`name`)',
        ));

        $this->addForeignKey('fk_keyword_group', 'search_phrase', 'group_id', 'group', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk_keyword_group', 'search_phrase');
        $this->dropTable('group');
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