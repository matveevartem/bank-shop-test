<?php

use yii\db\Migration;

/**
 * Class m240515_111228_create_table_image
 */
class m240515_111228_create_table_image extends Migration
{
    private const TABLE_NAME = "image";

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(static::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'original_name' => $this->string(255)->notNull()->unique(),
            'real_name' => $this->string(255)->notNull()->unique(),
            'extension' => $this->string(4)->notNull(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(static::TABLE_NAME);
    }
}
