<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=standalone
[END_COT_EXT]
==================== */

/**
 * Page Reviews plugin
 * Description: Entry point for the pagereviews plugin, selects mode based on request parameters
 * Filename: pagereviews.php
 * @package pagereviews
 * @version 1.0.1
 * @author webitprof
 * @copyright Copyright (c) 2025
 * @license BSD
 */

defined('COT_CODE') && defined('COT_PLUG') or die('Wrong URL');

require_once cot_incfile('pagereviews', 'plug');
require_once cot_incfile('page', 'module');
require_once cot_incfile('users', 'module');
require_once cot_incfile('extrafields');
require_once cot_incfile('uploads');
require_once cot_incfile('forms');

// Mode choice
if (!in_array($m, ['add', 'edit', 'claim', 'delete'])) {
    $itemid = cot_import('itemid', 'G', 'INT');
    $pageid = cot_import('pageid', 'G', 'INT');
    if ($itemid && $pageid) {
        $m = 'main';
    } else {
        $m = 'list';
    }
}

require_once cot_incfile('pagereviews', 'plug', $m);
?>