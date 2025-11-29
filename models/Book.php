<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "books".
 *
 * @property int $id
 * @property string $title Название
 * @property int $year Год выпуска
 * @property string|null $description Описание
 * @property string|null $isbn ISBN
 * @property string|null $image Имя файла картинки
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Authors[] $authors
 * @property BookAuthor[] $bookAuthors
 */
class Book extends \yii\db\ActiveRecord
{
    /**
     * @var UploadedFile|null
     */
    public $imageFile;

    /**
     * @var array
     */
    public $authorIds = [];

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%books}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'year', 'isbn'], 'required'],
            [['year'], 'integer', 'min' => 1000, 'max' => date('Y')],
            [['description'], 'string'],
            [['title', 'image'], 'string', 'max' => 255],
            [['isbn'], 'match', 'pattern' => '/^[0-9\-]+$/', 'message' => 'ISBN должен содержать только цифры и дефис'],
            [['isbn'], 'string', 'max' => 20],
            [['isbn'], 'unique'],
            [['authorIds'], 'required', 'message' => 'Выберите хотя бы одного автора.'],
            [['authorIds'], 'each', 'rule' => ['integer']],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'year' => 'Год выпуска',
            'description' => 'Описание',
            'isbn' => 'ISBN',
            'image' => 'Фото обложки',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthors()
    {
        return $this->hasMany(Author::class, ['id' => 'author_id'])->viaTable('book_author', ['book_id' => 'id']);
    }

}
