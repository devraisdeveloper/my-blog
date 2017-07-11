<?php

use yii\db\Migration;

/**
 * Handles the creation of table `blogger`.
 */
class m170618_204901_create_blogger_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('Blogger', [
            'id' => $this->primaryKey(),
            'name' => $this->string('75')->notNull(),
            'email' => $this->string('150')->notNull()->unique(),
            'username' => $this->string('100')->notNull()->unique(),
            'password' => $this->string('225')->notNull(),
            'authKey' => $this->string('255'),
            'status' => "ENUM('ENABLED', 'DISABLED')",
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('Blogger');
    }
}
