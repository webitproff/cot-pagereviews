<?php
/**
 * [BEGIN_COT_EXT]
 * Hooks=users.details.tags
 * [END_COT_EXT]
 */

defined('COT_CODE') or die('Wrong URL');

require_once cot_incfile('pagereviews', 'plug');

global $structure, $db, $db_pagereviews, $db_users, $db_pages, $cfg, $usr, $L;

// Проверка, есть ли $urr
if (empty($urr['user_id'])) {
    return;
}

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = cot_auth('plug', 'pagereviews', 'RWA');

$tab = cot_import('tab', 'G', 'ALP');
$category = ($tab == 'pagereviews') ? cot_import('cat', 'G', 'TXT') : '';
list($pg, $d, $durl) = cot_import_pagenav('dreviews', $cfg['plugin']['pagereviews']['reviews_per_page']);

$t1 = new XTemplate(cot_tplfile(['pagereviews', 'userdetails'], 'plug'));

$where = [];
$order = [];

if ($usr['id'] == 0 || ($usr['id'] != $urr['user_id'] && !$usr['isadmin'])) {
    $where['state'] = "r.item_state=0";
}

if ($category) {
    $where['cat'] = 'p.page_cat=' . $db->quote($category);
}

$where['owner'] = "r.item_userid=" . (int)$urr['user_id'];
$order['date'] = "r.item_date DESC";

// Для подсчета категорий удалим фильтр категории
$wherecount = $where;
unset($wherecount['cat']);

foreach (cot_getextplugins('pagereviews.userdetails.query') as $pl) {
    include $pl;
}

$where = ($where) ? 'WHERE ' . implode(' AND ', $where) : '';
$wherecount = ($wherecount) ? 'WHERE ' . implode(' AND ', $wherecount) : '';
$order = ($order) ? 'ORDER BY ' . implode(', ', $order) : '';

// Чёрный список категорий
$blacklist = array_map('trim', explode(',', $cfg['plugin']['pagereviews']['blacklistcattree'] ?? ''));

// Получаем список всех категорий с количеством отзывов
$sql_reviews_count_cat = $db->query("
    SELECT p.page_cat, COUNT(*) as cat_count
    FROM $db_pagereviews AS r
    INNER JOIN $db_pages AS p ON p.page_id = r.item_pageid
    $wherecount
    GROUP BY p.page_cat
    HAVING cat_count > 0
")->fetchAll();

$categories = [];
foreach ($sql_reviews_count_cat as $row) {
    if (empty($row['page_cat']) || in_array($row['page_cat'], $blacklist)) {
        continue;
    }
    $categories[$row['page_cat']] = $row['cat_count'];
}

// Вывод списка категорий
foreach ($categories as $cat_code => $cat_count) {
    $cat_title = $structure['page'][$cat_code]['title'] ?? $cat_code;
    $t1->assign([
        "REVIEW_CAT_ROW_TITLE" => htmlspecialchars($cat_title),
        "REVIEW_CAT_ROW_ICON" => $structure['page'][$cat_code]['icon'] ?? '',
        "REVIEW_CAT_ROW_URL" => cot_url('users', [
            'm' => 'details',
            'id' => $urr['user_id'],
            'u' => $urr['user_name'],
            'tab' => 'pagereviews',
            'cat' => $cat_code
        ]),
        "REVIEW_CAT_ROW_COUNT_REVIEWS" => $cat_count,
        "REVIEW_CAT_ROW_SELECT" => ($category === $cat_code) ? 'active' : ''
    ]);
    $t1->parse("MAIN.CAT_ROW");
}

// Общее число отзывов
$reviews_count_all = $db->query("
    SELECT COUNT(*) FROM $db_pagereviews AS r
    INNER JOIN $db_pages AS p ON p.page_id = r.item_pageid
    $wherecount
")->fetchColumn();

// Список отзывов
$sqllist = $db->query("
    SELECT r.*, u.*, p.page_alias, p.page_cat, p.page_title
    FROM $db_pagereviews AS r
    LEFT JOIN $db_users AS u ON u.user_id = r.item_userid
    LEFT JOIN $db_pages AS p ON p.page_id = r.item_pageid
    $where $order
    LIMIT $d, " . (int)$cfg['plugin']['pagereviews']['reviews_per_page']
);

$sqllist_rowset = $sqllist->fetchAll();

$reviews_count = $reviews_count_all;

$opt_array = [
    'm' => 'details',
    'id' => $urr['user_id'],
    'u' => $urr['user_name'],
    'tab' => 'pagereviews'
];

if ($category && isset($categories[$category])) {
    $reviews_count = $categories[$category];
    $opt_array['cat'] = $category;
}

// Навигация
$pagenav = cot_pagenav('users', $opt_array, $d, $reviews_count, $cfg['plugin']['pagereviews']['reviews_per_page'], 'dreviews');

$t1->assign([
    "PAGENAV_PAGES" => $pagenav['main'],
    "PAGENAV_PREV" => $pagenav['prev'],
    "PAGENAV_NEXT" => $pagenav['next'],
    "PAGENAV_COUNT" => $reviews_count,
    "COT_REVIEWS_COUNT" => $reviews_count_all,
]);

// Отображение отзывов
foreach ($sqllist_rowset as $item) {
    $pageid = $item['item_pageid'];
    $url_params = !empty($item['page_alias'])
        ? ['c' => $item['page_cat'], 'al' => $item['page_alias']]
        : ['c' => $item['page_cat'], 'id' => $pageid];
    $page_url = cot_url('page', $url_params);
    $page_cat_url = cot_url('page', ['c' => $item['page_cat']]);

    $t1->assign(cot_generate_usertags($item, 'REVIEW_ROW_', 'Неизвестный'));

    $redirect = base64_encode(cot_url('users', array_merge($opt_array, ['dreviews' => $durl]), '', true));
    $edit_url = ($usr['isadmin'] || $usr['id'] == $item['item_userid']) ? cot_url('plug', [
        'e' => 'pagereviews',
        'm' => 'edit',
        'pageid' => $pageid,
        'itemid' => $item['item_id'],
        'redirect' => $redirect
    ]) : '';

    $review_url = cot_url('plug', [
        'e' => 'pagereviews',
        'm' => 'main',
        'itemid' => $item['item_id'],
        'pageid' => $pageid
    ]);

    $delete_url = ($usr['isadmin'] || $usr['id'] == $item['item_userid']) ? cot_url('plug', [
        'e' => 'pagereviews',
        'm' => 'delete',
        'pageid' => $pageid,
        'itemid' => $item['item_id'],
        'redirect' => $redirect
    ]) : '';
    $delete_confirm_url = $delete_url ? cot_confirm_url($delete_url, 'pagereviews') : '';

    $cat_title = $structure['page'][$item['page_cat']]['title'] ?? $item['page_cat'];

    $t1->assign([
        'REVIEW_ROW_ID' => $item['item_id'],
        'REVIEW_ROW_TEXT' => $item['item_text'],
        'REVIEW_ROW_TITLE' => $item['item_title'],
        'REVIEW_ROW_PAGEID' => $pageid,
        'REVIEW_ROW_OWNERID' => $item['item_userid'],
        'REVIEW_ROW_OWNER' => cot_build_user($item['item_userid'], htmlspecialchars($item['user_name'] ?? 'Неизвестный')),
        'REVIEW_ROW_SCORE' => $item['item_score'],
        'REVIEW_ROW_STARS' => cot_generate_stars_page($item['item_score']),
        'REVIEW_ROW_DATE' => $item['item_date'],
        'REVIEW_ROW_DELETE_URL' => $delete_confirm_url,
        'REVIEW_ROW_EDIT_URL' => $edit_url,
        'REVIEW_ROW_URL' => $review_url,
        'REVIEW_ROW_PAGE_URL' => $page_url,
        'REVIEW_ROW_PAGE_TITLE' => htmlspecialchars($item['page_title'] ?? 'Без названия'),
        'REVIEW_ROW_PAGE_CAT' => htmlspecialchars($cat_title),
        'REVIEW_ROW_PAGE_CAT_URL' => $page_cat_url
    ]);
    $t1->parse('MAIN.REVIEWS_ROWS');
}

foreach (cot_getextplugins('pagereviews.userdetails.loop') as $pl) {
    include $pl;
}

$t1->parse("MAIN");

$t->assign([
    "USERS_DETAILS_PAGEREVIEWS_COUNT" => $reviews_count_all,
    "USERS_DETAILS_PAGEREVIEWS_URL" => cot_url('users', 'm=details&id=' . $urr['user_id'] . '&u=' . $urr['user_name'] . '&tab=pagereviews'),
    "PAGEREVIEWS" => $t1->text("MAIN")
]);
