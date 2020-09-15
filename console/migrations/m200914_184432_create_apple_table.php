<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%apple}}`.
 */
class m200914_184432_create_apple_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%apple}}', [
            'id' => $this->primaryKey(),
            'created_at' => $this->integer()->unsigned(),
            'fall_at' => $this->integer()->unsigned(),
            'color' => $this->string(50),
            'status' => $this->smallInteger()->unsigned()->defaultValue(1),
            'ate' =>  $this->smallInteger()->unsigned()->defaultValue(0)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%apple}}');
    }
}
