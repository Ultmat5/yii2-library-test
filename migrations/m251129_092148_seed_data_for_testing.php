<?php

use yii\db\Migration;

class m251129_092148_seed_data_for_testing extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Авторы
        $authors = [
            [1, 'Федор Михайлович Достоевский'],
            [2, 'Лев Николаевич Толстой'],
            [3, 'Александр Александрович Блок'],
            [4, 'Всеволод Михайлович Гаршин'],
            [5, 'Иннокентий Федорович Анненский'],
            [6, 'Фёдор Кузьмич Сологуб'],
            [7, 'Вячеслав Иванович Иванов'],
            [8, 'Зинаида Николаевна Гиппиус'],
            [9, 'Иван Алексеевич Бунин'],
            [10, 'Леонид Николаевич Андреев'],
        ];

        $this->batchInsert('{{%authors}}', ['id', 'full_name'], $authors);

        // Книги
        $time = time();
        $books = [];

        // Достоевский
        for ($i = 1; $i <= 5; $i++) {
            $books[] = [$i, "Преступление и наказание. Том $i", 2024, "Описание тома $i", "978-5-0000-000$i", null, $time, $time];
        }
        // Толстой
        for ($i = 6; $i <= 9; $i++) {
            $books[] = [$i, "Война и мир. Часть $i", 2024, "Великая эпопея", "978-5-0000-00$i", null, $time, $time];
        }
        // Блок
        for ($i = 10; $i <= 12; $i++) {
            $books[] = [$i, "Стихи о прекрасной даме $i", 2024, "Поэзия", "978-5-0000-0$i", null, $time, $time];
        }
        // Гаршин
        $books[] = [13, "Красный цветок", 2024, "Рассказ", "978-5-0000-013", null, $time, $time];

        // Бунин
        for ($i = 14; $i <= 18; $i++) {
            $books[] = [$i, "Темные аллеи. Рассказ $i", 2023, "Классика", "978-5-0000-0$i", null, $time, $time];
        }

        // Достоевский + Толстой
        $books[] = [19, "Спор классиков", 2024, "Вымышленная книга", "978-5-0000-999", null, $time, $time];

        $this->batchInsert('{{%books}}', ['id', 'title', 'year', 'description', 'isbn', 'image', 'created_at', 'updated_at'], $books);

        $links = [];

        // Достоевский
        for ($i = 1; $i <= 5; $i++) $links[] = [1, $i];

        // Толстой
        for ($i = 6; $i <= 9; $i++) $links[] = [2, $i];

        // Блок
        for ($i = 10; $i <= 12; $i++) $links[] = [3, $i];

        // Гаршин
        $links[] = [4, 13];

        // Бунин
        for ($i = 14; $i <= 18; $i++) $links[] = [9, $i];

        // Достоевский и Толстой
        $links[] = [1, 19];
        $links[] = [2, 19];

        $this->batchInsert('{{%book_author}}', ['author_id', 'book_id'], $links);
    }

     /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%book_author}}');
        $this->delete('{{%books}}');
        $this->delete('{{%authors}}');
    }

}
