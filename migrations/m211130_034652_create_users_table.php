<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%users}}`.
 */
class m211130_034652_create_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%users}}', [
            'id' => $this->primaryKey(),
            'login' => $this->string(10)->notNull()->unique(),
            'password' => $this->string(),
            'token' => $this->string(),
            'code' => $this->string(),
        ]);
        $this->batchInsert(
            '{{%users}}',
            ['login', 'password', 'token'],
            [
                ['9231234567', Yii::$app->getSecurity()->generatePasswordHash('password1'), Yii::$app->getSecurity()->generateRandomString()],
                ['9239876543', Yii::$app->getSecurity()->generatePasswordHash('password2'), Yii::$app->getSecurity()->generateRandomString()],
                ['9234567897', Yii::$app->getSecurity()->generatePasswordHash('password3'), Yii::$app->getSecurity()->generateRandomString()]
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%users}}');
    }
}
