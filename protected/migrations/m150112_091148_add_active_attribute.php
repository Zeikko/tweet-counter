<?php

class m150112_091148_add_active_attribute extends CDbMigration
{
	public function up()
	{
            $this->addColumn('group', 'active', 'INT(1) DEFAULT 1');
	}

	public function down()
	{
            $this->dropColumn('group', 'active');
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