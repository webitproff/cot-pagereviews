<?php
/**
 * Page Reviews plugin - Russian Language file
 * Filename: pagereviews.ru.lang.php
 * @package pagereviews
 * @version 1.0.1
 * @author webitprof
 * @copyright Copyright (c) 2025
 * @license BSD
 */
defined('COT_CODE') or die('Wrong URL.');
global $cfg;
/**
 * Module Configuration
 */
$L['cfg_pageall'] = 'Отображать на странице статьи все отзывы';
$L['cfg_reviews_per_page'] = 'Количество отзывов на странице списка';
$L['pagereviews_title'] = 'Отзывы в статьях';
$L['pagereviews_desc'] = 'Плагин для добавления отзывов к статьям';

/**
 * Interface Labels
 */
$L['pagereviews_pageRatingValue'] = 'Рейтинг на основе отзывов';
$L['pagereviews_pageCountReviewsTotalValue'] = 'Oтзывов всего';
$L['pagereviews_pageCountStarsTotalValue'] = 'Звезд получено';
$L['pagereviews_pageAverageRatingValue'] = 'Средняя оценка';
$L['pagereviews_text'] = 'Текст отзыва';
$L['pagereviews_score'] = 'Оценка';
$L['pagereviews_review'] = 'Отзыв';
$L['pagereviews_reviews'] = 'Отзывы';
$L['pagereviews_no_reviews'] = 'Отзывов пока нет';
$L['pagereviews_add_review'] = 'Добавить отзыв';
$L['pagereviews_edit_review'] = 'Редактировать отзыв';
$L['pagereviews_user'] = 'Пользователь';
$L['pagereviews_maintitle'] = 'Заголовок отзыва';
$L['pagereviews_all_reviews'] = 'Все отзывы';
$L['pagereviews_all_reviews_page_title'] = 'Отзывы к публикациям';
$L['pagereviews_all_reviews_page_desc'] = 'Все оценки и отзывы пользователей о статьях и блогах на <span class="fw-bold">"' . $cfg['maintitle'] . '"</span>, которые были размещены участниками сообщества';
$L['pagereviews_all_reviews_page_metadescrtext'] = 'Все оценки и отзывы пользователей о статьях и блогах, которые были размещены участниками сообщества';
$L['pagereviews_all_users'] = 'Все пользователи';
$L['pagereviews_all_categories'] = 'Все категории';
$L['pagereviews_date'] = 'Дата размещения';
$L['pagereviews_author'] = 'Автор отзыва';
$L['pagereviews_author unknown'] = 'Неизвестный';
$L['pagereviews_page'] = 'Статья';
$L['pagereviews_categories'] = 'Категории';
$L['pagereviews_category'] = 'Категория';
$L['pagereviews_search'] = 'Поиск';
$L['pagereviews_search_reviews'] = 'Поиск отзывов';
$L['pagereviews_search_user'] = 'Поиск по пользователю';
$L['pagereviews_sort'] = 'Сортировка';
$L['pagereviews_mostrecent'] = 'Самые актуальные';
$L['pagereviews_dateasc'] = 'По дате (возрастание)';
$L['pagereviews_datedesc'] = 'По дате (убывание)';
$L['pagereviews_scoreasc'] = 'По рейтингу (возрастание)';
$L['pagereviews_scoredesc'] = 'По рейтингу (убывание)';
$L['pagereviews_last_index'] = 'Последние отзывы к статьям';
$L['pagereviews_view_all'] = 'Посмотреть все отзывы';

/**
 * Complaints Interface
 */
$L['pagereviews_submit_complaint'] = 'Пожаловаться';
$L['pagereviews_complaints'] = 'Жалобы на отзывы';
$L['pagereviews_complaint_already_submitted'] = 'Вы уже написали жалобу на этот отзыв. Администратор получил уведомление и примет решение по жалобе.';
$L['pagereviews_no_complaints'] = 'Нет жалоб';
$L['pagereviews_complaint_id'] = 'ID жалобы';
$L['pagereviews_complaint_text'] = 'Текст жалобы';
$L['pagereviews_complaint_user'] = 'Пожаловался';
$L['pagereviews_complaint_author'] = 'Автор жалобы';
$L['pagereviews_complaint_date'] = 'Дата жалобы';
$L['pagereviews_complaint_reason'] = 'Причина жалобы';
$L['pagereviews_file_complaint'] = 'Пожаловаться на отзыв';
$L['pagereviews_complaint_approve_url'] = 'Ссылка для одобрения жалобы';
$L['pagereviews_complaint_reject_url'] = 'Ссылка для отклонения жалобы';
$L['pagereviews_complaint_direct_url'] = 'Просмотреть жалобу';
$L['pagereviews_complaint_view_url'] = 'Ссылка на жалобу';

/**
 * Actions and Buttons
 */
$L['pagereviews_actions'] = 'Действия';
$L['pagereviews_approve'] = 'Одобрить';
$L['pagereviews_reject'] = 'Отклонить';
$L['pagereviews_submit'] = 'Отправить';
$L['Add'] = 'Добавить';
$L['Edit'] = 'Редактировать';
$L['Delete'] = 'Удалить';
$L['Close'] = 'Закрыть';
$L['Cancel'] = 'Отмена';
$L['pagereviews_Editing'] = 'Редактирование отзыва';
$L['pagereviews_Editing_Save'] = 'Сохранить изменения';
$L['pagereviews_delete_confirm'] = 'Подтверждение удаления';
$L['pagereviews_delete_confirm_message'] = 'Вы уверены, что хотите удалить этот отзыв?';

/**
 * Notifications
 */
$L['pagereviews_new_review'] = 'Новый отзыв';
$L['pagereviews_new_review_body'] = 'Добавлен новый отзыв';
$L['pagereviews_updated_review'] = 'Отзыв обновлён';
$L['pagereviews_updated_review_body'] = 'Отзыв был обновлён';
$L['pagereviews_deleted_review'] = 'Отзыв удалён';
$L['pagereviews_deleted_review_body'] = 'Отзыв был удалён';
$L['pagereviews_new_complaint'] = 'Новая жалоба';
$L['pagereviews_new_complaint_body'] = 'Поступила новая жалоба на отзыв';
$L['pagereviews_complaint_admin_url'] = 'Список жалоб';
$L['pagereviews_review_url'] = 'Ссылка на отзыв';
/**
 * Success Messages
 */
$L['pagereviews_success_added'] = 'Отзыв успешно добавлен';
$L['pagereviews_success_updated'] = 'Отзыв успешно отредактирован';
$L['pagereviews_success_deleted'] = 'Отзыв успешно удалён';
$L['pagereviews_complaint_submitted'] = 'Жалоба успешно отправлена';
$L['pagereviews_complaint_processed'] = 'Жалоба обработана';

/**
 * Error Messages
 */
$L['pagereviews_error_invalidpage'] = 'Указанная статья недействительна';
$L['pagereviews_error_invaliduser'] = 'Выбранный пользователь недействителен';
$L['pagereviews_error_nouserselected'] = 'Пользователь не выбран';
$L['pagereviews_error_exists'] = 'Отзыв уже создан';
$L['pagereviews_error_emptytext'] = 'Отзыв не может быть пустым';
$L['pagereviews_error_emptyscore'] = 'Укажите оценку';
$L['pagereviews_error_invalidreview'] = 'Недействительный отзыв';
$L['pagereviews_err_title'] = 'Заголовок обязателен';
$L['pagereviews_error_no_access'] = 'Доступ запрещён';
$L['pagereviews_no_access_category'] = 'Нет доступа к этой категории';
$L['pagereviews_error_empty_complaint_text'] = 'Текст жалобы не может быть пустым';
$L['pagereviews_error_complaint_exists'] = 'Вы уже подали жалобу на этот отзыв';

/**
 * Score Values and Titles
 */
$L['pagereviews_score_values'] = [1, 2, 3, 4, 5];
$L['pagereviews_score_titles'] = [
    '1 звезда (Крайне отрицательно)',
    '2 звезды (Плохо)',
    '3 звезды (Нейтрально)',
    '4 звезды (Хорошо)',
    '5 звезд (Отлично)'
];
?>