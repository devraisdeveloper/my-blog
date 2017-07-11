<?php

use yii\db\Migration;

/**
 * Handles the creation of table `Post`.
 */
class m170619_182540_create_Post_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('Post', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer('11')->notNull(),
            'picture' => $this->string('255')->notNull(),
            'title' => $this->string('100')->notNull(),
            'text' => $this->text()->notNull(),
            'total_likes' => $this->integer('11'),
            'total_comments' => $this->integer('11'),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull()
        ]);
        
        $this->addForeignKey('fk_post_blogger', 'Post', 'user_id', 'Blogger', 'id', 'CASCADE', 'NO ACTION');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk_post_blogger', 'Post');
        $this->dropTable('Post');
    }
}
