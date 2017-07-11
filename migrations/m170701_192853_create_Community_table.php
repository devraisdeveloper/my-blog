<?php

use yii\db\Migration;

/**
 * Handles the creation of table `Community`.
 */
class m170701_192853_create_Community_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('Community', [
            'id' => $this->primaryKey(),
            'name' => $this->string('150')->notNull(),
            'total_posts' => $this->integer('11')->notNull(),
            'created_by' => $this->integer('11')->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull()
        ]); 
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('Community');
    }
}
