<?php
/**
 * Page Reviews plugin - Main mode
 * Description: Displays a single review on a separate page
 * Filename: inc/pagereviews.main.php
 * @package pagereviews
 * @version 1.0.1
 * @author webitprof
 * @copyright Copyright (c) 2025
 * @license BSD
 */

defined('COT_CODE') && defined('COT_PLUG') or die('Wrong URL');

require_once cot_langfile('pagereviews', 'plug');
require_once cot_incfile('pagereviews', 'plug');
require_once cot_incfile('users', 'module');

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = cot_auth('plug', 'pagereviews', 'RWA');

$t = new XTemplate(cot_tplfile(['pagereviews', 'main'], 'plug'));

$itemid = cot_import('itemid', 'G', 'INT');
$pageid = cot_import('pageid', 'G', 'INT');
$redirect = cot_import('redirect', 'G', 'TXT');

// Получаем информацию о странице
$page_info = $db->query("SELECT page_alias, page_cat, page_title FROM $db_pages WHERE page_id = ?", [$pageid])->fetch();
if (!$page_info) {
    cot_die_message(404, TRUE);
}
$default_url_params = !empty($page_info['page_alias']) ? ['c' => $page_info['page_cat'], 'al' => $page_info['page_alias']] : ['c' => $page_info['page_cat'], 'id' => $pageid];
$base_url = cot_url('page', $default_url_params, '', true);

// Проверяем отзыв
if (empty($itemid)) {
    cot_error('pagereviews_error_invalidreview');
    cot_redirect($base_url);
}

$sql = $db->query("SELECT r.*, u.* FROM $db_pagereviews AS r 
    LEFT JOIN $db_users AS u ON u.user_id = r.item_userid 
    WHERE r.item_id = ? AND r.item_pageid = ? LIMIT 1", [$itemid, $pageid]);
if ($sql->rowCount() == 0) {
    cot_error('pagereviews_error_invalidreview');
    cot_redirect($base_url);
}
$item = $sql->fetch();

$complaint_exists = (bool)$db->query("SELECT COUNT(*) FROM $db_pagereviews_complaints 
    WHERE complaint_itemid = ? AND complaint_userid = ?", [$item['item_id'], $usr['id']])->fetchColumn();
	
	
// Подготовка данных
$reviewer_name = $item['user_name'] ?: $L['pagereviews_author unknown'];
$stars = cot_generate_stars_page($item['item_score']);
// Проверка, является ли текущий пользователь автором отзыва
$is_own_review = ($item['item_userid'] == $usr['id']);


// Генерация тегов пользователя
$t->assign(cot_generate_usertags($item, 'REVIEW_AUTHOR_', $L['pagereviews_author unknown']));

// URL для действий
$edit_url = ($usr['isadmin'] || $usr['id'] == $item['item_userid']) ? 
    cot_url('plug', [
        'e' => 'pagereviews',
        'm' => 'edit',
        'pageid' => $pageid,
        'itemid' => $itemid,
        'redirect' => $redirect ?: base64_encode($base_url)
    ]) : '';
$delete_url = ($usr['isadmin'] || $usr['id'] == $item['item_userid']) ? 
    cot_url('plug', [
        'e' => 'pagereviews',
        'm' => 'delete',
        'pageid' => $pageid,
        'itemid' => $itemid,
        'redirect' => $redirect ?: base64_encode($base_url)
    ]) : '';
$complaint_url = ($usr['id'] > 0 && !$is_own_review && !$complaint_exists) ? 
    cot_url('plug', [
        'e' => 'pagereviews',
        'm' => 'claim',
        'pageid' => $pageid,
        'itemid' => $itemid,
        'redirect' => $redirect ?: base64_encode($base_url)
    ]) : '';
/* $complaint_url = ($usr['id'] > 0 && !$is_own_review) ? 
    cot_url('plug', [
        'e' => 'pagereviews',
        'm' => 'claim',
        'pageid' => $pageid,
        'itemid' => $itemid,
        'redirect' => $redirect ?: base64_encode($base_url)
    ]) : ''; */
	
/* $complaint_url = ($usr['id'] > 0) ? 
    cot_url('plug', [
        'e' => 'pagereviews',
        'm' => 'claim',
        'pageid' => $pageid,
        'itemid' => $itemid,
        'redirect' => $redirect ?: base64_encode($base_url)
    ]) : ''; */

// Передаём данные в шаблон
$t->assign([
    'REVIEW_ID' => $item['item_id'],
    'REVIEW_TITLE' => htmlspecialchars($item['item_title']),
    'REVIEW_TEXT' => htmlspecialchars($item['item_text']),
    'REVIEW_SCORE' => $item['item_score'],
    'REVIEW_STARS' => $stars,
    'REVIEW_AUTHOR' => htmlspecialchars($reviewer_name), // Сохраняем для обратной совместимости
    'REVIEW_DATE' => cot_date('datetime_medium', $item['item_date']),
    'REVIEW_PAGE_URL' => $base_url,
    'REVIEW_PAGE_TITLE' => htmlspecialchars($page_info['page_title']),
    'REVIEW_AUTHOR_URL' => cot_url('users', ['m' => 'details', 'id' => $item['item_userid']]),
    'REVIEW_EDIT_URL' => $edit_url,
    'REVIEW_DELETE_URL' => $delete_url,
    'REVIEW_COMPLAINT_URL' => $complaint_url,
    'REVIEW_BACK_URL' => $redirect ? base64_decode($redirect) : $base_url,
	'REVIEW_COMPLAINT_EXISTS' => $complaint_exists,
	'REVIEW_IS_OWN_REVIEW' => $is_own_review,
]);

cot_display_messages($t);
?>