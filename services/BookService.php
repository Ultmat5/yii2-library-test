<?php

namespace app\services;

use app\models\Book;
use app\models\Author;
use app\models\Subscription;
use Yii;
use yii\web\UploadedFile;

class BookService
{
    private $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * @param Book $book
     * @return bool
     */
    public function create(Book $book): bool
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            if ($book->imageFile) {
                $book->image = $this->uploadFile($book->imageFile);
            }

            if (!$book->save(false)) {
                throw new \Exception('Ошибка сохранения: ' . print_r($book->errors, true));
            }

            if (is_array($book->authorIds)) {
                foreach ($book->authorIds as $authorId) {
                    $author = Author::findOne($authorId);
                    if ($author) {
                        $book->link('authors', $author);
                    }
                }
            }

            $transaction->commit();

            $this->notifySubscribers($book);

            return true;

        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::error($e->getMessage());
            $book->addError('title', $e->getMessage());
            return false;
        }
    }

    public function update(Book $book): bool
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            if ($book->imageFile) {

                $oldImage = $book->getOldAttribute('image');
                $book->image = $this->uploadFile($book->imageFile);

                if ($oldImage) {
                    $this->deleteFile($oldImage);
                }
            }

            if (!$book->save(false)) {
                throw new \Exception('Ошибка обновления: ' . print_r($book->errors, true));
            }

            $book->unlinkAll('authors', true);

            if (is_array($book->authorIds)) {
                foreach ($book->authorIds as $authorId) {
                    $author = Author::findOne($authorId);
                    if ($author) {
                        $book->link('authors', $author);
                    }
                }
            }

            $transaction->commit();
            return true;

        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::error($e->getMessage());
            $book->addError('title', $e->getMessage());
            return false;
        }
    }

    protected function notifySubscribers(Book $book)
    {
        try {
            $authorIds = \yii\helpers\ArrayHelper::getColumn($book->getAuthors()->all(), 'id');

            if (empty($authorIds)) return;

            $phones = Subscription::find()
                ->select('phone')
                ->where(['in', 'author_id', $authorIds])
                ->distinct()
                ->column();

            if (empty($phones)) return;

            $message = "Новинка! Вышла книга: " . mb_substr($book->title, 0, 40);

            foreach ($phones as $phone) {
                $this->smsService->send($phone, $message);
            }
        } catch (\Exception $e) {
            Yii::error("Ошибка при рассылке уведомлений: " . $e->getMessage());
        }
    }

    private function uploadFile(UploadedFile $file): string
    {
        $fileName = Yii::$app->security->generateRandomString(10) . '.' . $file->extension;
        $uploadPath = Yii::getAlias('@webroot/uploads/');

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $file->saveAs($uploadPath . $fileName);
        return $fileName;
    }

    private function deleteFile(string $fileName): void
    {
        $filePath = Yii::getAlias('@webroot/uploads/') . $fileName;

        if (is_file($filePath)) {
            unlink($filePath);
        }
    }
}