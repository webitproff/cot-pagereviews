<?php
/**
 * Page Reviews plugin - List mode
 * Description: Displays a list of reviews with filtering and sorting
 * Filename: inc/pagereviews.list.php
 * @package pagereviews
 * @version 1.0.1
 * @author webitprof
 * @copyright Copyright (c) 2025
 * @license BSD
 */

defined('COT_CODE') && defined('COT_PLUG') or die('Wrong URL');

require_once cot_incfile('pagereviews', 'plug');
require_once cot_langfile('pagereviews', 'plug');

// Устанавливаем мета-заголовок страницы
$metaTitle = Cot::$cfg['plugin']['pagereviews']['metatitletext'] ?: $L['pagereviews_all_reviews_page_title'];
Cot::$out['subtitle'] = $metaTitle;
// Устанавливаем мета-описание страницы
$metaDesc = Cot::$cfg['plugin']['pagereviews']['metadescrtext'] ?: $L['pagereviews_all_reviews_page_metadescrtext'];
Cot::$out['desc'] = $metaDesc;

$sq = cot_import('sq', 'G', 'TXT'); // Поисковая строка
$sort = cot_import('sort', 'G', 'ALP'); // Параметр сортировки
$c = cot_import('c', 'G', 'ALP'); // Категория страницы
$user = cot_import('user', 'G', 'INT'); // Фильтр по пользователю

// Проверка прав доступа
list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = cot_auth('plug', 'pagereviews', 'RWA');

if (!$usr['auth_read']) {
    cot_error('pagereviews_no_access');
    $t = new XTemplate(cot_tplfile(['pagereviews', 'list'], 'plug'));
    cot_display_messages($t);
    return;
}

if (!empty($c)) {
    list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = cot_auth('page', $c);
    if (!$usr['auth_read']) {
        cot_error('pagereviews_no_access_category');
        $t = new XTemplate(cot_tplfile(['pagereviews', 'list'], 'plug'));
        cot_display_messages($t);
        return;
    }
}

$t = new XTemplate(cot_tplfile(['pagereviews', 'list'], 'plug'));

$per_page = (int)($cfg['plugin']['pagereviews']['reviews_per_page'] ?: 10);
list($pg, $d, $durl) = cot_import_pagenav('d', $per_page);
$offset = $d;

$where = [];
if (!empty($sq)) {
    $words = explode(' ', preg_replace("'\s+'", " ", $sq));
    $sqlsearch = '%' . implode('%', $words) . '%';
    $where[] = "(r.item_title LIKE " . $db->quote($sqlsearch) . " OR r.item_text LIKE " . $db->quote($sqlsearch) . ")";
}
if (!empty($c)) {
    $catsub = cot_structure_children('page', $c);
    $where[] = "p.page_cat IN ('" . implode("','", $catsub) . "')";
}
if (!empty($user)) {
    $where[] = "r.item_userid = " . (int)$user;
}
$where_clause = $where ? 'WHERE ' . implode(' AND ', $where) : '';
$total_reviews = (int)$db->query("SELECT COUNT(*) FROM $db_pagereviews AS r 
    LEFT JOIN $db_pages AS p ON p.page_id = r.item_pageid $where_clause")->fetchColumn();

if (
    (!Cot::$cfg['easypagenav'] && $durl > 0 && $per_page > 0 && $durl % $per_page > 0) ||
    ($d > 0 && $d >= $total_reviews)
) {
    cot_redirect(cot_url('plug', ['e' => 'pagereviews', 'm' => 'list', 'sq' => $sq, 'sort' => $sort, 'c' => $c, 'user' => $user], '', true));
}

$order = [];
switch ($sort) {
    case 'dateasc':
        $order[] = 'r.item_date ASC';
        break;
    case 'datedesc':
        $order[] = 'r.item_date DESC';
        break;
    case 'scoreasc':
        $order[] = 'r.item_score ASC';
        break;
    case 'scoredesc':
        $order[] = 'r.item_score DESC';
        break;
    default:
        $order[] = 'r.item_date DESC';
        break;
}
$order_clause = $order ? 'ORDER BY ' . implode(', ', $order) : '';

$sql = $db->query("SELECT r.*, u.*, p.page_title, p.page_alias, p.page_cat 
    FROM $db_pagereviews AS r 
    LEFT JOIN $db_users AS u ON u.user_id = r.item_userid 
    LEFT JOIN $db_pages AS p ON p.page_id = r.item_pageid 
    $where_clause $order_clause LIMIT ? OFFSET ?", [$per_page, $offset]);

$user_options = ['' => $L['pagereviews_all_users']];
$users_sql = $db->query("SELECT DISTINCT u.user_id, u.user_name 
    FROM $db_pagereviews AS r 
    LEFT JOIN $db_users AS u ON u.user_id = r.item_userid 
    WHERE u.user_id IS NOT NULL 
    ORDER BY u.user_name ASC");
while ($u = $users_sql->fetch()) {
    $user_options[$u['user_id']] = htmlspecialchars($u['user_name']);
}

$t->assign([
    'COT_REVIEWS_COUNT' => $total_reviews,
    'PAGE_TITLE' => $L['pagereviews_all_reviews'],
    'SEARCH_ACTION_URL' => cot_url('plug', ['e' => 'pagereviews', 'm' => 'list'], '', true),
    'SEARCH_SQ' => cot_inputbox(
        'text',
        'sq',
        !empty($sq) ? htmlspecialchars($sq) : '',
        'class="schstring form-control"'
    ),
    'SEARCH_SORTER' => cot_selectbox(
        $sort,
        'sort',
        ['', 'dateasc', 'datedesc', 'scoreasc', 'scoredesc'],
        [
            $L['pagereviews_mostrecent'],
            $L['pagereviews_dateasc'],
            $L['pagereviews_datedesc'],
            $L['pagereviews_scoreasc'],
            $L['pagereviews_scoredesc']
        ],
        true,
        ['class' => 'form-select']
    ),
    'SEARCH_USER' => cot_selectbox(
        $user,
        'user',
        array_keys($user_options),
        array_values($user_options),
        true,
        ['class' => 'form-select', 'id' => 'user']
    ),
    'SEARCH_CAT' => cot_pagereviews_selectcat($c, 'c'),
    'CATALOG' => cot_pagereviews_build_structure_tree('', $c, 0),
]);

while ($item = $sql->fetch()) {
    $t->assign(cot_generate_usertags($item, 'REVIEW_ROW_OWNER_', 'Неизвестный'));

    $url_params = !empty($item['page_alias']) ? ['c' => $item['page_cat'], 'al' => $item['page_alias']] : ['c' => $item['page_cat'], 'id' => $item['item_pageid']];
	
	// Проверка, является ли текущий пользователь автором отзыва
	$is_own_review = ($item['item_userid'] == $usr['id']);
	
	// Проверяет, есть ли запись в таблице $db_pagereviews_complaints для данного отзыва (item_id) и текущего пользователя ($usr['id']), чтобы определить, подавал ли пользователь жалобу
	$complaint_exists = (bool)$db->query("SELECT COUNT(*) FROM $db_pagereviews_complaints 
    WHERE complaint_itemid = ? AND complaint_userid = ?", [$item['item_id'], $usr['id']])->fetchColumn();
	
    $redirect = base64_encode(cot_url('plug', ['e' => 'pagereviews', 'm' => 'list', 'd' => $durl], '', true));
    $delete_url = ($usr['isadmin'] || $usr['id'] == $item['item_userid']) ? 
        cot_url('plug', [
            'e' => 'pagereviews',
            'm' => 'delete',
            'pageid' => $item['item_pageid'],
            'itemid' => $item['item_id'],
            'redirect' => $redirect
        ]) : '';
$complaint_url = ($usr['id'] > 0 && !$is_own_review && !$complaint_exists) ? 
    cot_url('plug', [
        'e' => 'pagereviews',
        'm' => 'claim',
        'pageid' => $item['item_pageid'],
        'itemid' => $item['item_id'],
        'redirect' => $redirect
    ]) : '';
/* $complaint_url = ($usr['id'] > 0 && !$is_own_review) ? 
    cot_url('plug', [
        'e' => 'pagereviews',
        'm' => 'claim',
        'pageid' => $item['item_pageid'],
        'itemid' => $item['item_id'],
        'redirect' => $redirect
    ]) : ''; */

    $edit_url = ($usr['isadmin'] || $usr['id'] == $item['item_userid']) ? 
        cot_url('plug', [
            'e' => 'pagereviews',
            'm' => 'edit',
            'pageid' => $item['item_pageid'],
            'itemid' => $item['item_id'],
            'redirect' => $redirect
        ]) : '';
    $review_url = cot_url('plug', [
        'e' => 'pagereviews',
        'm' => 'main',
        'itemid' => $item['item_id'],
        'pageid' => $item['item_pageid']
    ]);

    $t->assign([
        'REVIEW_ROW_ID' => $item['item_id'],
        'REVIEW_ROW_TEXT' => htmlspecialchars($item['item_text']),
        'REVIEW_ROW_TITLE' => htmlspecialchars($item['item_title']),
        'REVIEW_ROW_PAGEID' => $item['item_pageid'],
        'REVIEW_ROW_PAGE_TITLE' => htmlspecialchars($item['page_title']),
        'REVIEW_ROW_PAGE_URL' => cot_url('page', $url_params, '', true),
        'REVIEW_ROW_SCORE' => $item['item_score'],
        'REVIEW_ROW_STARS' => cot_generate_stars_page($item['item_score']),
        'REVIEW_ROW_DATE' => $item['item_date'],
        'REVIEW_ROW_USERID' => $item['item_userid'],
        'REVIEW_ROW_DATE_FORMATTED' => cot_date('Y-m-d\TH:i', $item['item_date']),
        'REVIEW_ROW_DELETE_URL' => $delete_url,
        'REVIEW_ROW_COMPLAINT_URL' => $complaint_url,
        'REVIEW_ROW_EDIT_URL' => $edit_url,
        'REVIEW_ROW_URL' => $review_url,
		'REVIEW_ROW_COMPLAINT_EXISTS' => $complaint_exists,
		'REVIEW_ROW_IS_OWN_REVIEW' => $is_own_review,
    ]);

    if ($usr['id'] == $item['item_userid'] || $usr['isadmin']) {
        $user_options = [];
        if ($usr['isadmin']) {
            $users_sql = $db->query("SELECT user_id, user_name FROM $db_users ORDER BY user_name ASC");
            while ($u = $users_sql->fetch()) {
                $user_options[$u['user_id']] = htmlspecialchars($u['user_name']);
            }
        }

        $date_value = !empty($item['item_date']) ? (int)$item['item_date'] : (int)$sys['now'];
        $edit_url = cot_url('plug', [
            'e' => 'pagereviews',
            'm' => 'edit',
            'pageid' => $item['item_pageid'],
            'itemid' => $item['item_id'],
            'redirect' => base64_encode(cot_url('plug', ['e' => 'pagereviews', 'm' => 'list', 'd' => $durl], '', true))
        ]);

        $owner = $db->query("SELECT * FROM $db_users WHERE user_id = ?", [$item['item_userid']])->fetch();
        $t->assign(cot_generate_usertags($owner ?: [], 'EDIT_FORM_OWNER_', 'Неизвестный'));

        $t->assign([
            'EDIT_FORM_ID' => $item['item_id'],
            'EDIT_FORM_SEND' => $edit_url,
            'EDIT_FORM_TEXT' => cot_textarea('rtext', $item['item_text'], 5, 50, 'class="form-control" required'),
            'EDIT_FORM_TITLE' => cot_inputbox('text', 'rtitle', $item['item_title'], 'class="form-control" maxlength="255" required'),
            'EDIT_FORM_SCORE' => cot_radiobox($item['item_score'], 'rscore', $L['pagereviews_score_values'], $L['pagereviews_score_titles'], 'class="form-check-input" required'),
            'EDIT_FORM_USERID' => $usr['isadmin'] ? cot_selectbox($item['item_userid'], 'ruserid', array_keys($user_options), array_values($user_options), true, 'class="form-control"') : '',
            'EDIT_FORM_DATE' => $usr['isadmin'] ? cot_selectbox_date($date_value, 'long', 'rdate', date('Y', $sys['now']), date('Y', $sys['now']) - 5, false, '', 'class="form-control d-inline-block w-auto"') : '',
            'EDIT_FORM_ITEMID' => cot_inputbox('hidden', 'itemid', $item['item_id']),
            'EDIT_FORM_PAGEID' => cot_inputbox('hidden', 'pageid', $item['item_pageid']),
        ]);

        $t->parse('MAIN.REVIEW_ROW.EDIT_FORM');
    }

    $t->parse('MAIN.REVIEW_ROW');
}

$pagenav = cot_pagenav('plug', ['e' => 'pagereviews', 'm' => 'list', 'sq' => $sq, 'sort' => $sort, 'c' => $c, 'user' => $user], $d, $total_reviews, $per_page, 'd');
$t->assign(cot_generatePaginationTags($pagenav));

cot_display_messages($t);
?>