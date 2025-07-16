<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=ajax
[END_COT_EXT]
==================== */

/**
 * Page Reviews plugin - AJAX handler
 * Description: Handles AJAX requests for the pagereviews plugin
 * Filename: inc/pagereviews.ajax.php
 * @package pagereviews
 * @version 1.0.1
 * @author webitprof
 * @copyright Copyright (c) 2025
 * @license BSD
 */

defined('COT_CODE') && defined('COT_PLUG') or die('Wrong URL');

require_once cot_incfile('pagereviews', 'plug');
require_once cot_langfile('pagereviews', 'plug');

$a = cot_import('a', 'G', 'ALP');

if ($a == 'autocomplete') {
    $term = cot_import('term', 'G', 'TXT');
    $users = [];

    if (!empty($term)) {
        global $db, $db_pagereviews, $db_users;
        $sql = $db->query("SELECT DISTINCT u.user_id, u.user_name 
            FROM $db_users AS u 
            INNER JOIN $db_pagereviews AS r ON r.item_userid = u.user_id 
            WHERE u.user_name LIKE ? 
            ORDER BY u.user_name ASC 
            LIMIT 10", ['%' . $db->escape($term) . '%']);
        
        while ($row = $sql->fetch()) {
            $users[] = [
                'id' => $row['user_id'],
                'label' => htmlspecialchars($row['user_name']),
                'value' => htmlspecialchars($row['user_name'])
            ];
        }
    }

    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode($users);
    exit;
}

cot_error('pagereviews_error_invalid_action');
exit;
?>