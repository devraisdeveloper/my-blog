<?php

use yii\db\Migration;

/**
 * Handles the creation of table `Community_Post`.
 */
class m170701_194919_create_Community_Post_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('Community_Post', [
            'id' => $this->primaryKey(),
            'post_id' => $this->integer('11')->notNull(),
            'community_id' => $this->integer('11')->notNull(),
        ]);
        
          $this->addForeignKey('fk_community_post_community', 'Community_Post', 'community_id', 'Community', 'id', 'CASCADE', 'CASCADE');
          $this->addForeignKey('fk_community_post_post', 'Community_Post', 'post_id', 'Post', 'id', 'CASCADE', 'CASCADE');
 
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk_community_post_community', 'Community_Post');
        $this->dropForeignKey('fk_community_post_post', 'Community_Post');
        $this->dropTable('Community_Post');
    }
}
