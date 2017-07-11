<?php

use yii\db\Migration;

/**
 * Handles the creation of table `Likes`.
 */
class m170701_191236_create_Likes_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('Likes', [
            'id' => $this->primaryKey(),
            'post_id' => $this->integer('11')->notNull(),
            'user_id' => $this->integer('11')->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull()
        ]);
        
         $this->addForeignKey('fk_likes_post', 'Likes', 'post_id', 'Post', 'id', 'CASCADE', 'NO ACTION');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk_likes_post', 'Likes');
        $this->dropTable('Likes');
    }
}
