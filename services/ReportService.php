<?php

namespace app\services;

use yii\db\Query;

class ReportService
{
    /**
     * @param int $year
     * @return array
     */
    public function getTopAuthorsByYear(int $year): array
    {
        return (new Query())
            ->select(['a.full_name', 'book_count' => 'COUNT(b.id)'])
            ->from(['a' => 'authors'])
            ->innerJoin(['ba' => 'book_author'], 'a.id = ba.author_id')
            ->innerJoin(['b' => 'books'], 'ba.book_id = b.id')
            ->where(['b.year' => $year])
            ->groupBy('a.id')
            ->orderBy(['book_count' => SORT_DESC])
            ->limit(10)
            ->all();
    }
}