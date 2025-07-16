<?php
/**
 * [BEGIN_COT_EXT]
 * Hooks=pagetags.main
 * [END_COT_EXT]
 */
/**
 * pagereviews plugin
 * Filename: pagereviews.pagetags.main.php
 * @package pagereviews
 * @version 1.0.0
 * @author webitprof
 * @copyright Copyright (c) 2025
 * @license BSD
 */
defined('COT_CODE') or die('Wrong URL.');

require_once cot_incfile('pagereviews', 'plug');

if (is_array($page_data)) {
    $scores = cot_get_pagereview_scores($page_data['page_id']);
    $avg_stars = $scores['total']['count'] > 0 ? round($scores['stars']['summ'] / $scores['total']['count'], 1) : 0;
    
    $temp_array['REVIEWS_STARS_SUMM'] = $scores['stars']['summ'];
    $temp_array['REVIEWS_TOTAL_COUNT'] = $scores['total']['count'];
    $temp_array['REVIEWS_AVG_STARS'] = $avg_stars;
    $temp_array['REVIEWS_AVG_STARS_HTML'] = cot_generate_stars_page(floor($avg_stars));
}
?>