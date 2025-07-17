<?php
/**
 * Page Reviews plugin
 * Filename: pagereviews.functions.php
 * @package pagereviews
 * @version 1.0.0
 * @author webitprof
 * @copyright Copyright (c) 2025
 * @license BSD
 */
defined('COT_CODE') or die('Wrong URL.');
require_once cot_incfile('forms');

global $db, $db_x, $db_pagereviews, $db_pagereviews_complaints;
global $cot_extrafields, $L, $db_pages;

cot::$db->registerTable('pagereviews');
cot::$db->registerTable('pagereviews_complaints');

$db_pagereviews = $db_x . 'pagereviews';
$db_pagereviews_complaints = $db_x . 'pagereviews_complaints';
/**
 * Генерация звёзд для оценки страницы
 */
function cot_generate_stars_page($score)
{
    $score = (int)$score;
    $stars = '';
    for ($i = 1; $i <= 5; $i++) {
        $stars .= $i <= $score ? '<i class="fa-solid fa-star"></i>' : '<i class="fa-regular fa-star"></i>';
    }
    return $stars;
}



/**
 * Вывод "очков" статьи
 */
function cot_get_pagereview_scores($pageid)
{
    global $db_pagereviews, $db;
    $scores = [
        'total' => ['count' => 0, 'summ' => 0],
        'stars' => ['count' => 0, 'summ' => 0],
    ];
    $sql = $db->query("SELECT COUNT(item_score) AS cnt, item_score FROM $db_pagereviews WHERE item_pageid = ? 
        GROUP BY item_score ORDER BY item_score ASC", [$pageid]);
    while ($scr = $sql->fetch()) {
        $scr['item_score'] = (int)$scr['item_score'];
        $summ = $scr['cnt'] * $scr['item_score'];
        $scores[$scr['item_score']] = ['count' => $scr['cnt'], 'summ' => $summ];
        $scores['total']['count'] += $scr['cnt'];
        $scores['total']['summ'] += $summ;
        if ($scr['item_score'] >= 1 && $scr['item_score'] <= 5) {
            $scores['stars']['count'] += $scr['cnt'];
            $scores['stars']['summ'] += $summ;
        }
    }
    return $scores;
}
/**
 * Форма просмотра отзывов / добавление / редактирование отзыва
 */
function cot_pagereviews_list($pageid, $showall = false)
{
    global $db_pagereviews, $db_users, $db_pages, $db, $L, $usr, $cfg, $sys;
    
    list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = cot_auth('plug', 'pagereviews', 'RWA');
    if (!$usr['auth_read']) {
        return '';
    }

    $t1 = new XTemplate(cot_tplfile(['pagereviews', 'page'], 'plug'));
    require_once cot_langfile('pagereviews', 'plug');

    // Получаем информацию о странице для формирования корректного URL
    $page_info = $db->query("SELECT page_alias, page_cat FROM $db_pages WHERE page_id = ?", [$pageid])->fetch();
    $url_params = !empty($page_info['page_alias']) ? ['c' => $page_info['page_cat'], 'al' => $page_info['page_alias']] : ['c' => $page_info['page_cat'], 'id' => $pageid];

    // Пагинация
    $per_page = (int)($cfg['plugin']['pagereviews']['reviews_per_page'] ?: 10); // Количество отзывов на страницу
    list($pg, $d, $durl) = cot_import_pagenav('d', $per_page); // Определяем текущую страницу пагинации
    $offset = $d; // Сдвиг для запроса к базе данных

    // Формируем базовый URL для текущей страницы статьи (с учетом пагинации)
    $base_url = cot_url('page', $url_params);  // Ссылка на текущую статью
    $base_url_with_page = $base_url . '&d='; // Дополняем базовый URL для пагинации

    // Выбираем все отзывы для текущей страницы с учетом пагинации
    $sql = $db->query("SELECT r.*, u.* FROM $db_pagereviews AS r 
        LEFT JOIN $db_users AS u ON u.user_id = r.item_userid 
        WHERE item_pageid = ? ORDER BY item_date ASC LIMIT ? OFFSET ?", [$pageid, $per_page, $offset]);

    $reviews_count = $sql->rowCount(); // Количество отзывов для текущей страницы
    $total_reviews = $db->query("SELECT COUNT(*) FROM $db_pagereviews WHERE item_pageid = ?", [$pageid])->fetchColumn(); // Общее количество отзывов
    $t1->assign('COT_REVIEWS_COUNT', $total_reviews); // Общее количество отзывов для пагинации

    // Выводим отзывы
    while ($item = $sql->fetch()) {
        // Если пользователь существует, используем его данные; иначе передаем emptyname
        $t1->assign(cot_generate_usertags($item, 'REVIEW_ROW_', 'Неизвестный'));
        $redirect = base64_encode(cot_url('page', array_merge($url_params, ['d' => $durl]), '', true));
        $edit_url = ($usr['isadmin'] || $usr['id'] == $item['item_userid']) ? 
            cot_url('plug', [
                'e' => 'pagereviews',
                'm' => 'edit',
                'pageid' => $pageid,
                'itemid' => $item['item_id'],
                'redirect' => $redirect
            ]) : '';
        $review_url = cot_url('plug', [
            'e' => 'pagereviews',
            'm' => 'main',
            'itemid' => $item['item_id'],
            'pageid' => $pageid
        ]);
        $t1->assign([
            'REVIEW_ROW_ID' => $item['item_id'],
            'REVIEW_ROW_TEXT' => $item['item_text'],
            'REVIEW_ROW_TITLE' => $item['item_title'],
            'REVIEW_ROW_PAGEID' => $item['item_pageid'],
            'REVIEW_ROW_OWNERID' => $item['item_userid'],
            'REVIEW_ROW_OWNER' => cot_build_user($item['item_userid'], htmlspecialchars($item['user_name'] ?? 'Неизвестный')),
            'REVIEW_ROW_SCORE' => $item['item_score'],
            'REVIEW_ROW_STARS' => cot_generate_stars_page($item['item_score']),
            'REVIEW_ROW_DATE' => $item['item_date'],
            'REVIEW_ROW_DELETE_URL' => ($usr['isadmin'] || $usr['id'] == $item['item_userid']) ? 
                cot_url('plug', [
                    'e' => 'pagereviews',
                    'm' => 'delete',
                    'pageid' => $pageid,
                    'itemid' => $item['item_id'],
                    'redirect' => $redirect
                ]) : '',
            'REVIEW_ROW_EDIT_URL' => $edit_url,
            'REVIEW_ROW_URL' => $review_url,
        ]);
        
        $t1->parse('MAIN.REVIEWS_ROWS');
    }

    // Форма добавления отзыва
    if ($usr['auth_write'] && $usr['id'] > 0) {
        cot_display_messages($t1);

        $user_options = [];
        if ($usr['isadmin']) {
            $users_sql = $db->query("SELECT user_id, user_name FROM $db_users ORDER BY user_name ASC");
            while ($u = $users_sql->fetch()) {
                $user_options[$u['user_id']] = htmlspecialchars($u['user_name']);
            }
        }

        $reviews_count = $db->query("SELECT COUNT(*) FROM $db_pagereviews 
            WHERE item_userid = ? AND item_pageid = ?", [$usr['id'], $pageid])->fetchColumn();
        $usr['auth_write'] = ($reviews_count > 0) ? false : $usr['auth_write'];

        if ($usr['auth_write']) {
            $add_url = cot_url('plug', [
                'e' => 'pagereviews',
                'm' => 'add',
                'pageid' => $pageid,
                'redirect' => base64_encode($base_url_with_page . $d)
            ]);

            $t1->assign([
                'ADD_FORM_SEND' => $add_url,
                'ADD_FORM_TEXT' => cot_textarea('rtext', '', 5, 50, 'class="form-control"'),
                'ADD_FORM_TITLE' => cot_inputbox('text', 'rtitle', '', 'class="form-control" maxlength="255"'),
                'ADD_FORM_SCORE' => cot_radiobox(0, 'rscore', $L['pagereviews_score_values'], $L['pagereviews_score_titles'], 'class="form-control"'),
                'ADD_FORM_USERID' => $usr['isadmin'] ? cot_selectbox('', 'ruserid', array_keys($user_options), array_values($user_options), true, 'class="form-control"') : '',
                'ADD_FORM_DATE' => $usr['isadmin'] ? cot_selectbox_date($sys['now'], 'long', 'rdate', date('Y', $sys['now']), date('Y', $sys['now']) - 5, false, '', 'class="form-control d-inline-block w-auto"') : '',
            ]);

            $t1->parse('MAIN.ADD_FORM');
        }
    }

    // Пагинация
    $pagenav = cot_pagenav('page', $url_params, $d, $total_reviews, $per_page, 'd');
    $t1->assign(cot_generatePaginationTags($pagenav));

    $t1->parse('MAIN');
    return $t1->text('MAIN');
}

/**
 * Вывод последних отзывов
 */
function cot_pagereviews_last($count = 0)
{
    global $db_pagereviews, $db_users, $db_pages, $db, $L, $usr, $cfg;
    list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = cot_auth('plug', 'pagereviews', 'RWA');
    if (!$usr['auth_read']) {
        return '';
    }

    $t1 = new XTemplate(cot_tplfile(['pagereviews', 'last'], 'plug'));
    require_once cot_langfile('pagereviews', 'plug');

    $limit = $count > 0 ? "LIMIT ?" : '';
    $sql_params = $count > 0 ? [$count] : [];
    // Выбираем все поля пользователя для генерации тегов
    $sql = $db->query("SELECT r.*, u.*, p.page_title, p.page_alias, p.page_cat 
        FROM $db_pagereviews AS r 
        LEFT JOIN $db_users AS u ON u.user_id = r.item_userid 
        LEFT JOIN $db_pages AS p ON p.page_id = r.item_pageid 
        ORDER BY item_date DESC $limit", $sql_params);

    $reviews_count = $sql->rowCount();
    $t1->assign('COT_REVIEWS_COUNT', $reviews_count);

    while ($item = $sql->fetch()) {
        $t1->assign(cot_generate_usertags($item, 'REVIEW_ROW_OWNER_', 'Неизвестный'));

        $url_params = !empty($item['page_alias']) ? ['c' => $item['page_cat'], 'al' => $item['page_alias']] : ['c' => $item['page_cat'], 'id' => $item['item_pageid']];

        $t1->assign([
            'REVIEW_ROW_ID' => $item['item_id'],
            'REVIEW_ROW_TEXT' => $item['item_text'],
            'REVIEW_ROW_TITLE' => $item['item_title'],
            'REVIEW_ROW_PAGEID' => $item['item_pageid'],
            'REVIEW_ROW_PAGE_TITLE' => htmlspecialchars($item['page_title']),
            'REVIEW_ROW_PAGE_URL' => cot_url('page', $url_params, '', true),
            'REVIEW_ROW_SCORE' => $item['item_score'],
            'REVIEW_ROW_STARS' => cot_generate_stars_page($item['item_score']),
            'REVIEW_ROW_DATE' => $item['item_date'],
        ]);

        $t1->parse('MAIN.REVIEW_ROW');
    }

    $t1->parse('MAIN');
    return $t1->text('MAIN');
}

/**
 * Gets review counts for categories
 */
function cot_get_pagereview_counts_by_categories()
{
    global $db, $db_pagereviews, $db_pages;

    $counts = [];
    $res = $db->query("SELECT p.page_cat, COUNT(r.item_id) as review_count 
        FROM $db_pages AS p 
        LEFT JOIN $db_pagereviews AS r ON r.item_pageid = p.page_id 
        GROUP BY p.page_cat");

    while ($row = $res->fetch()) {
        $counts[$row['page_cat']] = (int)$row['review_count'];
    }

    return $counts;
}


/**
 * Select categories for search form in pagereviews. Используется с Select2 (https://select2.org/)
 *
 * @param string $check Selected category code
 * @param string $name Input name
 * @param string $subcat Subcategory code to limit selection
 * @param bool $hideprivate Hide private categories
 * @return string
 */
function cot_pagereviews_selectcat($check, $name, $subcat = '', $hideprivate = true)
{
    global $structure, $cfg;

    // Загружаем структуру категорий страниц, если она не инициализирована
    if (!isset($structure['page']) || !is_array($structure['page'])) {
        require_once cot_incfile('page', 'module');
        $structure['page'] = cot_structure_build('page');
    }

    // Чёрный список категорий из конфигурации плагина
    $blacklist = explode(',', $cfg['plugin']['pagereviews']['blacklistcattree']);
    $blacklist = array_map('trim', $blacklist); // Удалим лишние пробелы

    // Проверяем, что структура не пуста, иначе инициализируем пустым массивом
    $structure['page'] = is_array($structure['page']) ? $structure['page'] : [];

    // Переменная для накопления всех option'ов
    $options = '';

    // Перебираем все категории в разделе 'page'
    foreach ($structure['page'] as $i => $x) {
        // Пропускаем категории из чёрного списка
        if (in_array($i, $blacklist)) {
            continue;
        }

        // Проверяем, разрешён ли просмотр категории (если нужно скрывать приватные)
        $display = $hideprivate ? cot_auth('page', $i, 'R') : true;

        // Если нужно фильтровать подкатегории, проверяем, входит ли текущая категория в фильтр
        if ($display && !empty($subcat) && isset($structure['page'][$subcat])) {
            // Формируем строку пути родительской категории с точкой на конце
            $mtch = $structure['page'][$subcat]['path'] . ".";
            // Длина этого пути
            $mtchlen = mb_strlen($mtch);
            // Проверяем, что путь текущей категории начинается с пути родителя или совпадает с ним
            $display = (mb_substr($x['path'], 0, $mtchlen) == $mtch || $i === $subcat);
        }

        // Если есть права на чтение категории, она не "all" и подходит по фильтру
        if (cot_auth('page', $i, 'R') && $i !== 'all' && $display) {
            // Считаем глубину категории — количество точек в пути
            $depth = substr_count($x['path'], '.');

            // Определяем, выбрана ли эта категория в данный момент
            $selected = ($i == $check) ? ' selected' : '';

            // Формируем тег option с value, data-depth и текстом
            $options .= '<option value="' . htmlspecialchars($i) . '" data-depth="' . $depth . '"' . $selected . '>' .
                        htmlspecialchars($x['title']) . '</option>';
        }
    }

    // Возвращаем полный select с классом Bootstrap
    return '<select name="' . htmlspecialchars($name) . '" class="form-select">' . $options . '</select>';
}

/**
 * Builds category tree for pagereviews. Используется с Select2 (https://select2.org/)
 *
 * @param string $parent Parent category code, empty for root level (404)
 * @param string|array $selected Selected category code(s) for highlighting (string or array)
 * @param int $level Current level in category hierarchy
 * @param string $template Template file to use (reserved)
 * @return string|bool Rendered HTML for category tree or false if no child elements
 */
function cot_pagereviews_build_structure_tree($parent = '', $selected = '', $level = 0, $template = '')
{
    global $structure, $cfg, $db, $sys, $cot_extrafields, $db_structure, $db_pages, $i18n_notmain, $i18n_locale, $i18n_read;

    // Получаем чёрный список категорий из конфигурации
    $blacklist_cfg = $cfg['plugin']['pagereviews']['blacklistcattree'] ?? '';
    $blacklist = array_map('trim', explode(',', $blacklist_cfg));

    // Получение количества отзывов по категориям
    $review_counts = cot_get_pagereview_counts_by_categories();

    // Загружаем структуру категорий страниц, если она не инициализирована
    if (!isset($structure['page']) || !is_array($structure['page'])) {
        require_once cot_incfile('page', 'module');
        $structure['page'] = cot_structure_build('page');
    }

    // Выполнение плагинов, подключенных к событию pagereviews.tree.first
    /* === Hook === */
    foreach (cot_getextplugins('pagereviews.tree.first') as $pl) {
        include $pl;
    }
    /* ===== */

    // Получение списка подкатегорий
    if (empty($parent)) {
        $i18n_enabled = $i18n_read;
        $children = [];
        $allcat = cot_structure_children('page', '');
        foreach ($allcat as $x) {
            if (mb_substr_count($structure['page'][$x]['path'], '.') == 0 && !in_array($x, $blacklist)) {
                $children[] = $x;
            }
        }
    } else {
        $i18n_enabled = $i18n_read && cot_i18n_enabled($parent);
        $children = !empty($structure['page'][$parent]['subcats']) ? array_filter($structure['page'][$parent]['subcats'], function($cat) use ($blacklist) {
            return !in_array($cat, $blacklist);
        }) : [];
    }

    // Загрузка файла шаблона
    $mskin = cot_tplfile(['pagereviews', 'tree', $template], 'plug');
    $t1 = new XTemplate($mskin);

    // Выполнение плагинов, подключенных к событию pagereviews.tree.main
    /* === Hook === */
    foreach (cot_getextplugins('pagereviews.tree.main') as $pl) {
        include $pl;
    }
    /* ===== */

    if (count($children) == 0) {
        return false;
    }

    // Подсчет общего количества страниц
    $total_count = 0;
    if ($db->tableExists($db_pages)) {
        $result = $db->query("SELECT COUNT(*) AS total FROM $db_pages WHERE page_state = 0")->fetch();
        $total_count = $result['total'] ?? 0;
    }

    $title = '';
    $desc = '';
    $count = 0;
    $icon = '';
    if (!empty($parent) && isset($structure['page'][$parent])) {
        $title = $structure['page'][$parent]['title'];
        $desc = $structure['page'][$parent]['desc'];
        $count = $structure['page'][$parent]['count'];
        $icon = $structure['page'][$parent]['icon'];
    }

    $urlparams = ['e' => 'pagereviews', 'a' => 'list'];
    if (!empty($parent)) {
        $urlparams['c'] = $parent;
    }

    $t1->assign([
        'TITLE' => htmlspecialchars($title),
        'DESC' => $desc,
        'COUNT' => $count,
        'ICON' => $icon,
        'HREF' => cot_url('plug', $urlparams),
        'LEVEL' => $level,
        'TOTAL_COUNT' => $total_count,
    ]);

    $jj = 0;
    /* === Hook - Part1 : Set === */
    $extp = cot_getextplugins('pagereviews.tree.loop');
    /* ===== */

    foreach ($children as $row) {
        if (in_array($row, $blacklist)) {
            continue;
        }

        $jj++;
        $urlparams['c'] = $row;
        $subcats = !empty($structure['page'][$row]['subcats']) ? array_filter($structure['page'][$row]['subcats'], function($cat) use ($blacklist) {
            return !in_array($cat, $blacklist);
        }) : [];
        $review_count = isset($review_counts[$row]) ? $review_counts[$row] : 0;

        $t1->assign([
            'ROW_ID' => $row,
            'ROW_TITLE' => htmlspecialchars($structure['page'][$row]['title']),
            'ROW_DESC' => $structure['page'][$row]['desc'],
            'ROW_COUNT' => $structure['page'][$row]['count'],
            'ROW_ICON' => $structure['page'][$row]['icon'],
            'ROW_REVIEWCOUNT' => $review_count,
            'ROW_HREF' => cot_url('plug', $urlparams),
            'ROW_SELECTED' => ((is_array($selected) && in_array($row, $selected)) || (!is_array($selected) && $row == $selected)) ? 1 : 0,
            'ROW_SUBCAT' => !empty($subcats) ? cot_pagereviews_build_structure_tree($row, $selected, $level + 1, $template) : '',
            'ROW_LEVEL' => $level,
            'ROW_ODDEVEN' => cot_build_oddeven($jj),
            'ROW_JJ' => $jj
        ]);

        foreach ($cot_extrafields[$db_structure] as $exfld) {
            $uname = strtoupper($exfld['field_name']);
            $t1->assign([
                'ROW_' . $uname . '_TITLE' => isset($L['structure_' . $exfld['field_name'] . '_title']) ? $L['structure_' . $exfld['field_name'] . '_title'] : $exfld['field_description'],
                'ROW_' . $uname => cot_build_extrafields_data('structure', $exfld, $structure['page'][$row][$exfld['field_name']]),
                'ROW_' . $uname . '_VALUE' => $structure['page'][$row][$exfld['field_name']],
            ]);
        }

        if ($i18n_enabled && $i18n_notmain) {
            $x_i18n = cot_i18n_get_cat($row, $i18n_locale);
            if ($x_i18n) {
                if (!$cfg['plugin']['i18n']['omitmain'] || $i18n_locale != $cfg['defaultlang']) {
                    $urlparams['l'] = $i18n_locale;
                }
                $t1->assign([
                    'ROW_URL' => cot_url('plug', $urlparams),
                    'ROW_TITLE' => $x_i18n['title'],
                    'ROW_DESC' => $x_i18n['desc'],
                ]);
            }
        }

        /* === Hook - Part2 : Include === */
        foreach ($extp as $pl) {
            include $pl;
        }
        /* ===== */

        $t1->parse('MAIN.CATS');
    }

    if ($jj == 0) {
        return false;
    }

    $t1->parse('MAIN');
    return $t1->text('MAIN');
}
/**
 * Проверяет, есть ли у пользователя отзывы
 *
 * @param int $userid ID пользователя
 * @return bool True, если отзывы есть, иначе false
 */
function cot_pagereviews_user_has_reviews($userid)
{
    global $db, $db_pagereviews;
    $count = $db->query("SELECT COUNT(*) FROM $db_pagereviews WHERE item_userid = ?", [$userid])->fetchColumn();
    return $count > 0;
}

/**
 * Выводит отзывы пользователя
 *
 * @param int $userid ID пользователя
 * @return string HTML с отзывами
 */
function cot_pagereviews_user_reviews($userid)
{
    global $db, $db_pagereviews, $db_users, $db_pages, $L, $usr, $cfg;

    list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = cot_auth('plug', 'pagereviews', 'RWA');
    if (!$usr['auth_read']) {
        return '';
    }

    $t = new XTemplate(cot_tplfile(['pagereviews', 'userdetails'], 'plug'));

    // Пагинация
    $per_page = (int)($cfg['plugin']['pagereviews']['reviews_per_page'] ?: 10);
    list($pg, $d, $durl) = cot_import_pagenav('d', $per_page);
    $offset = $d;

    // Базовый URL для пагинации
    $base_url = cot_url('plug', ['e' => 'pagereviews', 'm' => 'list', 'user' => $userid]);
    $base_url_with_page = $base_url . '&d=';

    // Запрос отзывов пользователя с заголовком страницы
    $sql = $db->query("SELECT r.*, u.*, p.page_alias, p.page_cat, p.page_title 
        FROM $db_pagereviews AS r 
        LEFT JOIN $db_users AS u ON u.user_id = r.item_userid 
        LEFT JOIN $db_pages AS p ON p.page_id = r.item_pageid 
        WHERE r.item_userid = ? 
        ORDER BY r.item_date ASC LIMIT ? OFFSET ?", [$userid, $per_page, $offset]);

    $total_reviews = $db->query("SELECT COUNT(*) FROM $db_pagereviews WHERE item_userid = ?", [$userid])->fetchColumn();
    $t->assign('COT_REVIEWS_COUNT', $total_reviews);

    // Вывод отзывов
    while ($item = $sql->fetch()) {
        $pageid = $item['item_pageid'];
        $url_params = !empty($item['page_alias']) ? ['c' => $item['page_cat'], 'al' => $item['page_alias']] : ['c' => $item['page_cat'], 'id' => $pageid];
        $page_url = cot_url('page', $url_params);

        $t->assign(cot_generate_usertags($item, 'REVIEW_ROW_', 'Неизвестный'));
        $redirect = base64_encode($page_url . '&d=' . $durl);
        $edit_url = ($usr['isadmin'] || $usr['id'] == $item['item_userid']) ? 
            cot_url('plug', [
                'e' => 'pagereviews',
                'm' => 'edit',
                'pageid' => $pageid,
                'itemid' => $item['item_id'],
                'redirect' => $redirect
            ]) : '';
        $review_url = cot_url('plug', [
            'e' => 'pagereviews',
            'm' => 'main',
            'itemid' => $item['item_id'],
            'pageid' => $pageid
        ]);
        $t->assign([
            'REVIEW_ROW_ID' => $item['item_id'],
            'REVIEW_ROW_TEXT' => $item['item_text'],
            'REVIEW_ROW_TITLE' => $item['item_title'],
            'REVIEW_ROW_PAGEID' => $pageid,
            'REVIEW_ROW_OWNERID' => $item['item_userid'],
            'REVIEW_ROW_OWNER' => cot_build_user($item['item_userid'], htmlspecialchars($item['user_name'] ?? 'Неизвестный')),
            'REVIEW_ROW_SCORE' => $item['item_score'],
            'REVIEW_ROW_STARS' => cot_generate_stars_page($item['item_score']),
            'REVIEW_ROW_DATE' => $item['item_date'],
            'REVIEW_ROW_DELETE_URL' => ($usr['isadmin'] || $usr['id'] == $item['item_userid']) ? 
                cot_url('plug', [
                    'e' => 'pagereviews',
                    'm' => 'delete',
                    'pageid' => $pageid,
                    'itemid' => $item['item_id'],
                    'redirect' => $redirect
                ]) : '',
            'REVIEW_ROW_EDIT_URL' => $edit_url,
            'REVIEW_ROW_URL' => $review_url,
            'REVIEW_ROW_PAGE_URL' => $page_url,
            'REVIEW_ROW_PAGE_TITLE' => htmlspecialchars($item['page_title'] ?? 'Без названия')
        ]);

        $t->parse('MAIN.REVIEWS_ROWS');
    }

    // Пагинация
    $pagenav = cot_pagenav('plug', ['e' => 'pagereviews', 'm' => 'list', 'user' => $userid], $d, $total_reviews, $per_page, 'd');
    $t->assign(cot_generatePaginationTags($pagenav));

    $t->parse('MAIN');
    return $t->text('MAIN');
}
?>
