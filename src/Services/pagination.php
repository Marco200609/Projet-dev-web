<?php
namespace App\Services;

function pagination($total, $page, $parpage, $baseUrl, $parametresRequete = [], $index=''): array
{
    unset($parametresRequete['page'.$index], $parametresRequete['parpage'.$index]);
    $totalPages = $parpage == -1 ? 1 : (int) ceil($total / $parpage);
    $startPage = max(1, $page - 2);
    $endPage = min($totalPages, $startPage + 4);

    $links = [];
    for ($p = $startPage; $p <= $endPage; $p++) {
        $params = array_merge($parametresRequete, ['page'.$index => $p, 'parpage'.$index => $parpage]);
        $url = $baseUrl . '?' . http_build_query($params);
        $links[] = [
            'page' => $p,
            'url' => $url,
            'active' => $p == $page,
        ];
    }

    $prevUrl = $page > 1 ? $baseUrl . '?' . http_build_query(array_merge($parametresRequete, ['page'.$index => $page - 1, 'parpage'.$index => $parpage])) : null;
    $nextUrl = $page < $totalPages ? $baseUrl . '?' . http_build_query(array_merge($parametresRequete, ['page'.$index => $page + 1, 'parpage'.$index => $parpage])) : null;

    return [
        'links' => $links,
        'prevUrl' => $prevUrl,
        'nextUrl' => $nextUrl,
        'totalPages' => $totalPages,
        'page' => $page,
        'parpage' => $parpage,
    ];
}
