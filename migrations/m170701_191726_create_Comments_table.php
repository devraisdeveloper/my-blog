<?php

use yii\db\Migration;

/**
 * Handles the creation of table `Comments`.
 */
class m170701_191726_create_Comments_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('Comments', [
            'id' => $this->primaryKey(),
            'post_id' => $this->integer('11')->notNull(),
            'text' => $this->text(),
            'user' => $this->integer('11')->defaultValue(null),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull()
        ]);
        
         $this->addForeignKey('fk_comments_post', 'Comments', 'post_id', 'Post', 'id', 'CASCADE', 'NO ACTION');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk_comments_post', 'Comments');
        $this->dropTable('Comments');
    }
}
