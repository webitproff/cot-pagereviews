<?php
/**
 * Page Reviews plugin - Claim mode
 * Description: Handles submission of complaints about reviews
 * Filename: inc/pagereviews.claim.php
 * @package pagereviews
 * @version 1.0.1
 */

defined('COT_CODE') && defined('COT_PLUG') or die('Wrong URL');

require_once cot_langfile('pagereviews', 'plug');
require_once cot_incfile('pagereviews', 'plug');

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = cot_auth('plug', 'pagereviews', 'RWA');
cot_block($usr['id'] > 0);

$t = new XTemplate(cot_tplfile(['pagereviews', 'claim'], 'plug'));

$itemid = cot_import('itemid', 'G', 'INT');
$pageid = cot_import('pageid', 'G', 'INT');
$redirect = cot_import('redirect', 'G', 'TXT');
$complaint_text = cot_import('complaint_text', 'P', 'TXT');
$action = cot_import('action', 'P', 'ALP');
$complaint_id = cot_import('id', 'G', 'INT');

// Проверка pageid перед запросом
$page_info = [];
if ($pageid) {
    $page_info = $db->query("SELECT page_alias, page_cat FROM $db_pages WHERE page_id = ?", [$pageid])->fetch() ?: [];
}
$default_url_params = !empty($page_info['page_alias']) ? ['c' => $page_info['page_cat'], 'al' => $page_info['page_alias']] : ['c' => $page_info['page_cat'] ?? 'articles', 'id' => $pageid];

// Админ: обработка жалоб
if ($usr['isadmin'] && $_SERVER['REQUEST_METHOD'] == 'POST' && $action && $complaint_id) {
    $complaint = $db->query("SELECT * FROM $db->pagereviews_complaints WHERE complaint_id = ?", [$complaint_id])->fetch();
    if ($complaint) {
        $update_data = [
            'complaint_resolved_date' => (int)$sys['now'],
            'complaint_status' => $action === 'approve' ? 'approved' : 'rejected',
        ];
        if ($action === 'approve') {
            $db->delete($db->pagereviews, "item_id = ?", [$complaint['complaint_itemid']]);
        }
        $db->update($db->pagereviews_complaints, $update_data, "complaint_id = ?", [$complaint_id]);
        cot_message('pagereviews_complaint_processed');
    }
    cot_redirect(cot_url('plug', ['e' => 'pagereviews', 'm' => 'claim'], '', true));
}

// Админ: список жалоб
if ($usr['isadmin'] && empty($itemid) && empty($pageid)) {
    $sql = $db->query("SELECT c.*, r.item_title, r.item_pageid, p.page_title, p.page_cat, p.page_alias, u.user_id, u.user_name 
        FROM $db->pagereviews_complaints AS c
        LEFT JOIN $db->pagereviews AS r ON r.item_id = c.complaint_itemid
        LEFT JOIN $db->pages AS p ON p.page_id = r.item_pageid
        LEFT JOIN $db->users AS u ON u.user_id = c.complaint_userid
        WHERE c.complaint_status = 'pending' ORDER BY c.complaint_date DESC");
    while ($complaint = $sql->fetch()) {
        $url_params = !empty($complaint['page_alias']) ? ['c' => $complaint['page_cat'], 'al' => $complaint['page_alias']] : ['c' => $complaint['page_cat'] ?? 'articles', 'id' => $complaint['item_pageid'] ?? 0];
        $t->assign([
            'COMPLAINT_ROW_ID' => $complaint['complaint_id'],
            'COMPLAINT_ROW_TEXT' => htmlspecialchars($complaint['complaint_text'] ?? ''),
            'COMPLAINT_ROW_USER_NAME' => htmlspecialchars($complaint['user_name'] ?: 'Неизвестный'),

			//'COMPLAINT_ROW_USER_URL' => cot_url('users', ['m' => 'details', 'id' => $complaint['user_id'], 'u' => $complaint['user_name']]),
			'COMPLAINT_ROW_USER_URL' => isset($complaint['user_id']) ? cot_url('users', ['m' => 'details', 'id' => $complaint['user_id'], 'u' => $complaint['user_name']]) : '#',


            'COMPLAINT_ROW_REVIEW_TITLE' => htmlspecialchars($complaint['item_title'] ?: ''),
            'COMPLAINT_ROW_PAGE_TITLE' => htmlspecialchars($complaint['page_title'] ?: 'Без названия'),
            'COMPLAINT_ROW_PAGE_URL' => $complaint['item_pageid'] ? cot_url('page', $url_params, '', true) : '#',
            'COMPLAINT_ROW_APPROVE_URL' => cot_url('plug', ['e' => 'pagereviews', 'm' => 'claim', 'action' => 'approve', 'id' => $complaint['complaint_id']], '', true),
            'COMPLAINT_ROW_REJECT_URL' => cot_url('plug', ['e' => 'pagereviews', 'm' => 'claim', 'action' => 'reject', 'id' => $complaint['complaint_id']], '', true),
        ]);
        $t->parse('MAIN.ADMIN_COMPLAINTS.COMPLAINT_ROW');
    }
    $t->parse('MAIN.ADMIN_COMPLAINTS');
} else {
    // Юзер: подача жалобы
    if (empty($itemid) || empty($pageid)) {
        cot_error('pagereviews_error_invalidreview');
        cot_redirect(cot_url('page', $default_url_params, '', true));
    }

    $sql = $db->query("SELECT r.*, p.page_title FROM $db_pagereviews AS r 
        LEFT JOIN $db_pages AS p ON p.page_id = r.item_pageid 
        WHERE r.item_id = ? AND r.item_pageid = ? LIMIT 1", [$itemid, $pageid]);
    if ($sql->rowCount() == 0) {
        cot_error('pagereviews_error_invalidreview');
        cot_redirect(cot_url('page', $default_url_params, '', true));
    }
    $review = $sql->fetch();

    // Проверка существования жалобы
    $complaint_exists = (bool)$db->query("SELECT COUNT(*) FROM $db_pagereviews_complaints 
        WHERE complaint_itemid = ? AND complaint_userid = ?", [$itemid, $usr['id']])->fetchColumn();

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($complaint_text)) {
        cot_shield_protect();

        cot_check(empty($complaint_text), 'pagereviews_error_empty_complaint_text');
        cot_check($complaint_exists, 'pagereviews_error_complaint_exists');

        if (!cot_error_found()) {
            $rcomplaint = [
                'complaint_userid' => (int)$usr['id'],
                'complaint_itemid' => (int)$itemid,
                'complaint_text' => $complaint_text,
                'complaint_date' => (int)$sys['now'],
                'complaint_status' => 'pending'
            ];
            $db->insert($db_pagereviews_complaints, $rcomplaint);
            $complaint_id = $db->lastInsertId();

            // Уведомления
            $reviewer = $db->query("SELECT user_name FROM $db_users WHERE user_id = ?", [$review['item_userid']])->fetch();
            $reviewer_name = $reviewer['user_name'] ?: 'Неизвестный';
            $complainant = $db->query("SELECT user_name FROM $db_users WHERE user_id = ?", [$usr['id']])->fetch();
            $complainant_name = $complainant['user_name'] ?: 'Неизвестный';

            $subject = $L['pagereviews_new_complaint'] . ": Отзыв #" . $itemid;
            $body = $L['pagereviews_new_complaint_body'] . "\n\n";
            $body .= $L['pagereviews_complaint_id'] . ": " . $complaint_id . "\n";
            $body .= $L['pagereviews_complaint_text'] . ": " . htmlspecialchars($rcomplaint['complaint_text'] ?? '') . "\n";
            $body .= $L['pagereviews_complaint_author'] . ": " . $complainant_name . "\n";
            $body .= $L['pagereviews_maintitle'] . ": " . htmlspecialchars($review['item_title'] ?? '') . "\n";
            $body .= $L['pagereviews_text'] . ": " . htmlspecialchars($review['item_text'] ?? '') . "\n";
            $body .= $L['pagereviews_score'] . ": " . ($review['item_score'] ?? 0) . "/5\n";
            $body .= $L['pagereviews_author'] . ": " . $reviewer_name . "\n";
            $body .= $L['pagereviews_page'] . ": " . ($review['page_title'] ?? 'Без названия') . " (" . $cfg['mainurl'] . '/' . cot_url('page', $default_url_params, '', true) . ")\n";
            $body .= $L['pagereviews_complaint_date'] . ": " . cot_date('datetime_full', $rcomplaint['complaint_date']) . "\n";
            $body .= $L['pagereviews_complaint_approve_url'] . ": " . $cfg['mainurl'] . '/' . cot_url('plug', ['e' => 'pagereviews', 'm' => 'claim', 'action' => 'approve', 'id' => $complaint_id], '', true) . "\n";
            $body .= $L['pagereviews_complaint_reject_url'] . ": " . $cfg['mainurl'] . '/' . cot_url('plug', ['e' => 'pagereviews', 'm' => 'claim', 'action' => 'reject', 'id' => $complaint_id], '', true) . "\n";
            $body .= $L['pagereviews_complaint_admin_url'] . ": " . $cfg['mainurl'] . '/' . cot_url('plug', ['e' => 'pagereviews', 'm' => 'claim'], '', true) . "\n";
            $body .= $L['pagereviews_complaint_direct_url'] . ": " . $cfg['mainurl'] . '/' . cot_url('plug', [
                'e' => 'pagereviews', 'm' => 'claim', 'pageid' => $pageid, 'itemid' => $itemid
            ], '', true) . "\n";
            $body .= $L['pagereviews_complaint_view_url'] . ": " . $cfg['mainurl'] . '/' . cot_url('plug', [
                'e' => 'pagereviews', 'm' => 'claim', 'id' => $complaint_id
            ], '', true) . "\n";
            $review_url = $cfg['mainurl'] . '/' . cot_url('plug', [
                'e' => 'pagereviews',
                'm' => 'main',
                'itemid' => $itemid,
                'pageid' => $pageid
            ], '', true);
            $body .= $L['pagereviews_review_url'] . ": " . $review_url . "\n";

            $superadmins = $db->query("SELECT user_email FROM $db_users WHERE user_maingrp = 5")->fetchAll();
            foreach ($superadmins as $admin) {
                if (!empty($admin['user_email'])) {
                    cot_mail($admin['user_email'], $subject, $body);
                }
            }

            cot_message('pagereviews_complaint_submitted');
            cot_redirect(cot_url('page', $default_url_params, '', true));
        }
    }

    // Назначаем теги для шаблона
    $t->assign([
        'COMPLAINT_FORM_SEND' => cot_url('plug', ['e' => 'pagereviews', 'm' => 'claim', 'itemid' => $itemid, 'pageid' => $pageid, 'redirect' => $redirect]),
        'COMPLAINT_FORM_TEXT' => cot_textarea('complaint_text', '', 5, 50, ['class' => 'form-control', 'required' => 'required']),
        'COMPLAINT_REVIEW_TITLE' => htmlspecialchars($review['item_title'] ?: ''),
        'COMPLAINT_REVIEW_TEXT' => htmlspecialchars($review['item_text'] ?: ''),
        'COMPLAINT_REVIEW_SCORE' => $review['item_score'] ?: 0,
        'COMPLAINT_PAGE_URL' => cot_url('page', $default_url_params, '', true),
        'COMPLAINT_PAGE_TITLE' => htmlspecialchars($review['page_title'] ?: 'Без названия'),
        'COMPLAINT_ALREADY_SUBMITTED' => $complaint_exists ? $L['pagereviews_complaint_already_submitted'] : '',
    ]);

    // Условный парсинг в зависимости от существования жалобы
    if ($complaint_exists) {
        $t->parse('MAIN.COMPLAINT_ALREADY_SUBMITTED');
    } else {
        $t->parse('MAIN.USER_COMPLAINT');
    }
}

cot_display_messages($t);
?>