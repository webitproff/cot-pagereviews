<?php
/**
 * Page Reviews plugin - Main mode
 * Description: Displays reviews for a specific page
 * Filename: inc/pagereviews.main.php
 * @package pagereviews
 * @version 1.0.1
 * @author webitprof
 * @copyright Copyright (c) 2025
 * @license BSD
 */

defined('COT_CODE') && defined('COT_PLUG') or die('Wrong URL');

require_once cot_incfile('pagereviews', 'plug');
require_once cot_langfile('pagereviews', 'plug');

$id = cot_import('id', 'G', 'INT');

if (empty($id)) {
    cot_error('pagereviews_error_invalidpage');
    $t = new XTemplate(cot_tplfile(['pagereviews', 'main'], 'plug'));
    cot_display_messages($t);
    $t->parse();
    $t->out();
    return;
}

$t = new XTemplate(cot_tplfile(['pagereviews', 'main'], 'plug'));

// Заглушка: здесь будет логика отображения отзывов для страницы с $id
$t->assign([
    'PAGE_ID' => $id,
    'PAGE_TITLE' => 'Reviews for page ID ' . $id // Заменить на реальный заголовок
]);

cot_display_messages($t);

$t->parse();
$t->out();
?>