<?php
// * ====================
// * [BEGIN_COT_EXT]
// * Hooks=ajax
// * [END_COT_EXT]
// * ==================== */

/**
 * Page Reviews plugin - Delete mode
 * Description: Handles deleting a review
 * Filename: inc/pagereviews.delete.php
 * @package pagereviews
 * @version 1.0.1
 * @author webitprof
 * @copyright Copyright (c) 2025
 * @license BSD
 */

defined('COT_CODE') && defined('COT_PLUG') or die('Wrong URL');

require_once cot_langfile('pagereviews', 'plug');
require_once cot_incfile('pagereviews', 'plug');

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = cot_auth('plug', 'pagereviews', 'RWA');

$pageid = cot_import('pageid', 'G', 'INT');
$itemid = cot_import('itemid', 'G', 'INT');
$redirect = cot_import('redirect', 'G', 'TXT');

// Получаем информацию о странице для редиректа
$page_info = $db->query("SELECT page_alias, page_cat FROM $db_pages WHERE page_id = ?", [$pageid])->fetch();
$default_url_params = !empty($page_info['page_alias']) ? ['c' => $page_info['page_cat'], 'al' => $page_info['page_alias']] : ['c' => $page_info['page_cat'], 'id' => $pageid];

cot_block($usr['auth_write']);

if (empty($itemid)) {
    cot_error('pagereviews_error_invalidreview');
    cot_redirect(cot_url('page', $default_url_params, '', true));
}

$sql = $db->query("SELECT * FROM $db_pagereviews WHERE item_id = ? LIMIT 1", [$itemid]);
if ($sql->rowCount() == 0) {
    cot_error('pagereviews_error_invalidreview');
    cot_redirect(cot_url('page', $default_url_params, '', true));
}
$item = $sql->fetch();

cot_block($usr['isadmin'] || $usr['id'] == $item['item_userid']);

$db->delete($db_pagereviews, "item_id = ?", [$itemid]);

// Отправка уведомлений об удалении
$reviewer = $db->query("SELECT user_name FROM $db_users WHERE user_id = ?", [$item['item_userid']])->fetch();
$reviewer_name = $reviewer['user_name'] ?: 'Неизвестный';
$pageinfo = $db->query("SELECT page_title, page_alias, page_cat FROM $db_pages WHERE page_id = ?", [$item['item_pageid']])->fetch();
$subject = $L['pagereviews_deleted_review'] . ": " . htmlspecialchars($item['item_title']);
$body = $L['pagereviews_deleted_review_body'] . "\n\n";
$body .= $L['pagereviews_title'] . ": " . htmlspecialchars($item['item_title']) . "\n";
$body .= $L['pagereviews_text'] . ": " . htmlspecialchars($item['item_text']) . "\n";
$body .= $L['pagereviews_score'] . ": {$item['item_score']}/5\n";
$body .= $L['pagereviews_author'] . ": {$reviewer_name}\n";
$body .= $L['pagereviews_page'] . ": {$pageinfo['page_title']} (" . $cfg['mainurl'] . '/' . cot_url('page', $default_url_params, '', true) . ")\n";
$body .= $L['pagereviews_date'] . ": " . cot_date('datetime_full', $item['item_date']) . "\n";

$superadmins = $db->query("SELECT user_email FROM $db_users WHERE user_maingrp = 5")->fetchAll();
foreach ($superadmins as $admin) {
    if (!empty($admin['user_email'])) {
        cot_mail($admin['user_email'], $subject, $body);
    }
}

cot_message('pagereviews_success_deleted');

$redirect_url = empty($redirect) ? cot_url('page', $default_url_params, '', true) : base64_decode($redirect);
cot_redirect($redirect_url);
exit;
?>