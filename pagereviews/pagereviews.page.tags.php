<?php
/**
 * [BEGIN_COT_EXT]
 * Hooks=page.tags
 * Tags=page.tpl:{PAGE_REVIEWS_COUNT},{PAGE_REVIEWS_URL},{PAGE_REVIEWS}
 * [END_COT_EXT]
 */
 
/**
 * page tags for page.tpl
 * Filename: pagereviews.page.tags.php
 * @package pagereviews
 * @version 1.0.0
 * @author webitprof
 * @copyright Copyright (c) 2025
 * @license BSD
 */
 
defined('COT_CODE') or die('Wrong URL.');

require_once cot_incfile('pagereviews', 'plug');

$page_id = (int)$pag['page_id'];
$page_reviews = cot_pagereviews_list($page_id, $cfg['plugin']['pagereviews']['pageall']);
$page_reviews_count = cot::$db->query("SELECT COUNT(*) FROM $db_pagereviews WHERE item_pageid = ?", [$page_id])->fetchColumn();
$page_reviews_url = empty($pag['page_alias'])
    ? cot_url('page', ['c' => $pag['page_cat'], 'id' => $page_id], '', true)
    : cot_url('page', ['c' => $pag['page_cat'], 'al' => $pag['page_alias']], '', true);

$t->assign([
    'PAGE_REVIEWS' => $page_reviews,
    'PAGE_REVIEWS_COUNT' => $page_reviews_count,
    'PAGE_REVIEWS_URL' => $page_reviews_url,
]);
?>