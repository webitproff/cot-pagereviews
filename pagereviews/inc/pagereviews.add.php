<?php


/**
 * Page Reviews plugin - Add mode
 * Description: Handles adding a new review
 * Filename: inc/pagereviews.add.php
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

cot_shield_protect();
cot_block($usr['auth_write']);

$ritem['item_pageid'] = $pageid;
$ritem['item_text'] = cot_import('rtext', 'P', 'TXT');
$ritem['item_score'] = cot_import('rscore', 'P', 'INT');
$ritem['item_title'] = cot_import('rtitle', 'P', 'TXT');
$ritem['item_date'] = (int)$sys['now'];

if ($usr['isadmin']) {
    $selected_userid = cot_import('ruserid', 'P', 'INT');
    if ($selected_userid > 0) {
        $user_exists = $db->query("SELECT user_id FROM $db_users WHERE user_id = ?", [$selected_userid])->fetch();
        cot_check(!$user_exists, 'pagereviews_error_invaliduser');
        $ritem['item_userid'] = $selected_userid;
    } else {
        cot_check(true, 'pagereviews_error_nouserselected');
    }
    $rdate = cot_import_date('rdate', true);
    if ($rdate !== false && $rdate > 0) {
        $ritem['item_date'] = (int)$rdate;
    }
} else {
    $ritem['item_userid'] = (int)$usr['id'];
}

cot_check(empty($pageid), 'pagereviews_error_invalidpage');
$pageinfo = $db->query("SELECT * FROM $db_pages WHERE page_id = ?", [$pageid])->fetch();
cot_block(!empty($pageinfo['page_id']));

$review_exists = (bool)$db->query("SELECT COUNT(*) FROM $db_pagereviews 
    WHERE item_pageid = ? AND item_userid = ?", [$pageid, $ritem['item_userid']])->fetchColumn();

cot_check($review_exists, 'pagereviews_error_exists');
cot_check(empty($ritem['item_text']), 'pagereviews_error_emptytext');
cot_check($ritem['item_score'] < 1 || $ritem['item_score'] > 5, 'pagereviews_error_emptyscore');
cot_check(empty($ritem['item_title']), 'pagereviews_err_title', 'rtitle');

if (!cot_error_found()) {
    $db->insert($db_pagereviews, $ritem);
    $itemid = $db->lastInsertId();

    // Отправка уведомлений
	$review_url = $cfg['mainurl'] . '/' . cot_url('plug', [
		'e' => 'pagereviews',
		'm' => 'main',
		'itemid' => $itemid,
		'pageid' => $ritem['item_pageid']
	], '', true);
    $reviewer = $db->query("SELECT user_name FROM $db_users WHERE user_id = ?", [$ritem['item_userid']])->fetch();
    $reviewer_name = $reviewer['user_name'] ?: 'Неизвестный';
    $subject = $L['pagereviews_new_review'] . ": " . htmlspecialchars($ritem['item_title']);
    $body = $L['pagereviews_new_review_body'] . "\n\n";
    $body .= $L['pagereviews_title'] . ": " . htmlspecialchars($ritem['item_title']) . "\n";
    $body .= $L['pagereviews_text'] . ": " . htmlspecialchars($ritem['item_text']) . "\n";
    $body .= $L['pagereviews_score'] . ": {$ritem['item_score']}/5\n";
    $body .= $L['pagereviews_author'] . ": {$reviewer_name}\n";
    $body .= $L['pagereviews_page'] . ": {$pageinfo['page_title']} (" . $cfg['mainurl'] . '/' . cot_url('page', $default_url_params, '', true) . ")\n";
    $body .= $L['pagereviews_date'] . ": " . cot_date('datetime_full', $ritem['item_date']) . "\n";

	$body .= $L['pagereviews_review_url'] . ": " . $review_url . "\n";
	
    $superadmins = $db->query("SELECT user_email FROM $db_users WHERE user_maingrp = 5")->fetchAll();
    foreach ($superadmins as $admin) {
        if (!empty($admin['user_email'])) {
            cot_mail($admin['user_email'], $subject, $body);
        }
    }

    cot_message('pagereviews_success_added');
}

$redirect_url = empty($redirect) ? cot_url('page', $default_url_params, '', true) : base64_decode($redirect);
cot_redirect($redirect_url);
exit;
?>