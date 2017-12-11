<?php

namespace App\Utils;

class Pagination
{
    const PAGE_FILLER = 2;
    const PAGE_SIZE = 10;
    const PAGE_VIEW = 5;

    /**
     * Generate page number
     *
     * @param int $page Current page
     * @param int $total Entry count
     *
     * @return array $pages
     */
    public static function generate($page, $total)
    {
        $pagination = [];
        $pageTotal = self::total($total);

        if (self::PAGE_VIEW >= $pageTotal) {
            for ($i = 1; $i <= $pageTotal; $i++) {
                $pagination[] = $i;
            }

            return $pagination;
        }

        $start = $page - self::PAGE_FILLER;
        $end = $page + self::PAGE_FILLER;

        if (1 < $start && $end < $pageTotal) {
            for ($i = $start; $i <= $end; $i++) {
                $pagination[] = $i;
            }
        }

        if (1 >= $start) {
            for ($i = 1; $i <= self::PAGE_VIEW; $i++) {
                $pagination[] = $i;
            }
        }

        if ($end >= $pageTotal) {
            for ($i = ($pageTotal - self::PAGE_VIEW) + 1; $i <= $pageTotal; $i++) {
                $pagination[] = $i;
            }
        }

        return $pagination;
    }

    /**
     * Get the total page
     *
     * @param int $total
     *
     * @return int $pageTotal
     */
    public static function total($total)
    {
        return (int) ceil($total / self::PAGE_SIZE);
    }
}

