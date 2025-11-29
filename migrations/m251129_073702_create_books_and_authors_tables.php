<?php

use yii\db\Migration;

class m251129_073702_create_books_and_authors_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%authors}}', [
            'id' => $this->primaryKey(),
            'full_name' => $this->string()->notNull()->comment('ФИО Автора'),
        ], $tableOptions);

        $this->createTable('{{%books}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull()->comment('Название'),
            'year' => $this->integer(4)->notNull()->comment('Год выпуска'),
            'description' => $this->text()->comment('Описание'),
            'isbn' => $this->string(20)->unique()->comment('ISBN'),
            'image' => $this->string()->comment('Имя файла картинки'),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('idx-books-year', '{{%books}}', 'year');

        $this->createTable('{{%book_author}}', [
            'book_id' => $this->integer()->notNull(),
            'author_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey('pk-book_author', '{{%book_author}}', ['book_id', 'author_id']);

        $this->addForeignKey(
            'fk-book_author-book_id',
            '{{%book_author}}',
            'book_id',
            '{{%books}}',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-book_author-author_id',
            '{{%book_author}}',
            'author_id',
            '{{%authors}}',
            'id',
            'CASCADE'
        );

        $this->createTable('{{%subscriptions}}', [
            'id' => $this->primaryKey(),
            'author_id' => $this->integer()->notNull(),
            'phone' => $this->string(20)->notNull()->comment('Телефон для СМС'),
            'created_at' => $this->integer(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk-subscriptions-author_id',
            '{{%subscriptions}}',
            'author_id',
            '{{%authors}}',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-unique-subscription',
            '{{%subscriptions}}',
            ['author_id', 'phone'],
            true
        );

        $this->createIndex('idx-subscriptions-author', '{{%subscriptions}}', 'author_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-subscriptions-author_id', '{{%subscriptions}}');
        $this->dropTable('{{%subscriptions}}');

        $this->dropForeignKey('fk-book_author-book_id', '{{%book_author}}');
        $this->dropForeignKey('fk-book_author-author_id', '{{%book_author}}');
        $this->dropTable('{{%book_author}}');

        $this->dropTable('{{%books}}');
        $this->dropTable('{{%authors}}');
    }

}
