<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "subscriptions".
 *
 * @property int $id
 * @property int $author_id
 * @property string $phone Телефон для СМС
 * @property int|null $created_at
 *
 * @property Authors $author
 */
class Subscription extends \yii\db\ActiveRecord
{

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'updatedAtAttribute' => false,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%subscriptions}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['author_id', 'phone'], 'required'],
            [['author_id', 'created_at'], 'integer'],
            [['phone'], 'trim'],
            [
                ['phone'],
                'match',
                'pattern' => '/^7\d{10}$/',
                'message' => 'Телефон должен быть в формате 79001234567 (только цифры, без +)'
            ],
            [['author_id', 'phone'], 'unique', 'targetAttribute' => ['author_id', 'phone'], 'message' => 'Вы уже подписаны на этого автора.'],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => Author::class, 'targetAttribute' => ['author_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'author_id' => 'Автор',
            'phone' => 'Телефон',
            'created_at' => 'Дата подписки',
        ];
    }

    /**
     * Gets query for [[Author]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(Author::class, ['id' => 'author_id']);
    }

}
