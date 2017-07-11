<?php

use yii\db\Migration;

/**
 * Handles the creation of table `Members`.
 */
class m170701_193326_create_Members_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('Members', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer('11')->notNull(),
            'community_id' => $this->integer('11')->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull()
        ]);
        
          $this->addForeignKey('fk_members_blogger', 'Members', 'user_id', 'Blogger', 'id', 'CASCADE', 'CASCADE');
          $this->addForeignKey('fk_members_community', 'Members', 'community_id', 'Community', 'id', 'CASCADE', 'CASCADE');
 
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk_members_blogger', 'Members');
        $this->dropForeignKey('fk_members_community', 'Members');
        $this->dropTable('Members');
    }
}
