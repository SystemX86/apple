<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "apple".
 *
 * @property int $id
 * @property int|null $created_at
 * @property int|null $fall_at
 * @property string|null $color
 * @property int|null $status
 * @property int|null $ate
 */
class Apple extends \yii\db\ActiveRecord
{
    const APPLE_HANGING = 1;
    const APPLE_FALL = 2;
    const APPLE_ROTTEN = 3;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'apple';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'fall_at', 'status', 'ate'], 'integer'],
            [['color'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Created At',
            'fall_at' => 'Fall At',
            'color' => 'Color',
            'status' => 'Status',
            'ate' => 'Ate',
        ];
    }
}
