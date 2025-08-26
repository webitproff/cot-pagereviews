# cot-pagereviews
Page Reviews Plugin for adding reviews to articles
## [DEMo](https://abuyfile.com/en/pagereviews) ##

### [описание на русском](https://abuyfile.com/en/market/cotonti/plugs/pagereviews-pl) ###

<img src="https://raw.githubusercontent.com/webitproff/cot-pagereviews/refs/heads/main/pagereviews_001.jpg" alt="Page Reviews Plugin for adding reviews to articles on Cotonti CMF" title="Page Reviews Plugin for adding reviews to articles on Cotonti CMF" />

<img src="https://raw.githubusercontent.com/webitproff/cot-pagereviews/refs/heads/main/pagereviews_002.jpg" alt="Page Reviews Plugin for adding reviews to articles on Cotonti CMF" title="Page Reviews Plugin for adding reviews to articles on Cotonti CMF" />

<img src="https://raw.githubusercontent.com/webitproff/cot-pagereviews/refs/heads/main/pagereviews_003.jpg" alt="Page Reviews Plugin for adding reviews to articles on Cotonti CMF" title="Page Reviews Plugin for adding reviews to articles on Cotonti CMF" />

<img src="https://raw.githubusercontent.com/webitproff/cot-pagereviews/refs/heads/main/pagereviews_004.jpg" alt="Page Reviews Plugin for adding reviews to articles on Cotonti CMF" title="Page Reviews Plugin for adding reviews to articles on Cotonti CMF" />

<img src="https://raw.githubusercontent.com/webitproff/cot-pagereviews/refs/heads/main/pagereviews_005.jpg" alt="Page Reviews Plugin for adding reviews to articles on Cotonti CMF" title="Page Reviews Plugin for adding reviews to articles on Cotonti CMF" />


# Page Reviews Plugin for Cotonti Siena

[![License](https://img.shields.io/badge/license-BSD-blue.svg)](https://opensource.org/licenses/BSD-3-Clause)
[![Version](https://img.shields.io/badge/version-1.0.1-green.svg)](https://github.com/webitproff/cot-pagereviews/releases)
[![Cotonti Siena](https://img.shields.io/badge/Cotonti_Siena-v0.9.26-blue.svg)](https://github.com/Cotonti/Cotonti)

**Page Reviews** is a plugin for the Cotonti Siena CMS (v0.9.26) that enables users to add, edit, delete, and rate reviews for articles created with the `page` module. It includes features for submitting complaints about reviews, filtering and sorting reviews, and integrating seamlessly with Cotonti's page and category system. The plugin supports multilingual interfaces (English and Russian) and provides robust administrative controls.

- **Repository**: [https://github.com/webitproff/cot-pagereviews](https://github.com/webitproff/cot-pagereviews)
- **Author**: webitprof
- **Version**: 1.0.1
- **License**: BSD
- **Release Date**: May 10, 2025

## Table of Contents

- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Template Integration](#template-integration)
  - [page.tpl](#pagetpl)
  - [index.tpl](#indextpl)
  - [page.list.tpl](#pagelisttpl)
  - [users.details.tpl](#usersdetailstpl)
  - [Category Tree in page.list.tpl](#category-tree-in-pagelisttpl)
- [Usage](#usage)
- [Technical Details](#technical-details)
- [Uninstallation](#uninstallation)
- [Limitations and Recommendations](#limitations-and-recommendations)
- [Support](#support)

## Features

The **Page Reviews** plugin enhances Cotonti-based websites by adding interactive review functionality to articles. Key features include:

### Review Management
- **Add Reviews**: Users can submit reviews with a title, text, and a 1–5 star rating. Administrators can specify the author and date.
- **Edit/Delete Reviews**: Authors and administrators can edit or delete reviews. One review per user per article is enforced to prevent spam.
- **Review Display**: Reviews can be displayed on article pages or as a standalone list with pagination.

### Complaint System
- Users can submit complaints about inappropriate reviews, specifying a reason.
- Administrators can view, approve (deleting the review), or reject complaints.
- Email notifications are sent to administrators for new complaints and their resolutions.

### Cotonti Integration
- **Pages and Categories**: Reviews are tied to the `page` module and respect category structures.
- **Template Tags**: Provides tags for article templates (`page.tpl`) to display reviews, counts, and statistics (e.g., `PAGE_REVIEWS`, `REVIEWS_AVG_STARS`).
- **Multilingual Support**: Includes English (`en`) and Russian (`ru`) language files.

### Filtering and Sorting
- **Search**: Filter reviews by title or text.
- **Category and User Filters**: Filter by page category or reviewer.
- **Sorting Options**: Sort by date (ascending/descending), rating (ascending/descending), or recency.
- **Pagination**: Configurable number of reviews per page.

### AJAX Support
- Autocomplete for user names when filtering reviews by author.

### Administration
- Administrators can modify review authors and dates.
- Complaint management interface for reviewing and processing complaints.
- Email notifications for review actions (add, edit, delete) and complaints.

### Customization
- Configurable settings for review display, pagination, and blacklisted categories.
- Customizable meta titles and descriptions for SEO.

## Requirements

- **CMS**: Cotonti Siena v0.9.26
- **PHP**: 8.2 - 8.4
- **Modules**: `page`, `users`
- **Dependencies**: `extrafields`, `uploads`, `forms`
- **Optional**: Bootstrap (for form styling), Select2 (for category/user dropdowns)

## Installation

1. **Download**: Clone or download the repository from [https://github.com/webitproff/cot-pagereviews](https://github.com/webitproff/cot-pagereviews).
2. **Copy Files**: Extract and copy the `pagereviews` folder to the `plugins` directory of your Cotonti installation.
3. **Install Plugin**:
   - Navigate to **Administration Panel** → **Extensions** → **Install** → Select `Page Reviews`.
   - The `setup/pagereviews.install.sql` script will create the required database tables (`cot_pagereviews`, `cot_pagereviews_complaints`).
4. **Verify**: Check an article page (`index.php?r=page&id=X`) to ensure the review form and list are displayed.

## Configuration

Configure the plugin via the Cotonti Administration Panel under **Extensions** → **Page Reviews** → **Configuration**:

- **`pageall` (boolean)**: Display all reviews on the article page (1 = yes, 0 = no).
- **`reviews_per_page` (integer)**: Number of reviews per page in the review list (default: 10).
- **`blacklistcattree` (string)**: Comma-separated list of category codes to exclude from reviews (e.g., `system,unvalidated,info`).
- **`metatitletext` (string)**: Meta title for the reviews list page (default: `All Reviews`).
- **`metadescrtext` (string)**: Meta description for the reviews list page.

Set permissions in **Administration Panel** → **Permissions** → `plug/pagereviews`:
- Guests: Read (`R`)
- Registered Users: Read, Write (`RW`)
- Administrators: Read, Write, Admin (`RWA`)

## Template Integration

The plugin provides template tags for seamless integration into Cotonti templates. Below are examples for common templates, using XTemplate syntax and Bootstrap classes for styling.

### page.tpl

Displays reviews, statistics, and the review submission form on an article page.

```html
<!-- IF {PHP|cot_plugin_active('pagereviews')} -->
<div class="reviews-section">
    <h2>{PHP.L.pagereviews_reviews}</h2>
    
    <!-- Review Statistics -->
    <!-- IF {REVIEWS_TOTAL_COUNT} > 0 -->
    <div class="reviews-stats">
        <p>{PHP.L.pagereviews_pageCountReviewsTotalValue}: {REVIEWS_TOTAL_COUNT}</p>
        <p>{PHP.L.pagereviews_pageAverageRatingValue}: {REVIEWS_AVG_STARS}</p>
        <p>{PHP.L.pagereviews_pageCountStarsTotalValue}: {REVIEWS_STARS_SUMM}</p>
    </div>
    <!-- ELSE -->
    <p>{PHP.L.pagereviews_no_reviews}</p>
    <!-- ENDIF -->
    
    <!-- Review List -->
    <!-- IF {PHP|cot_auth('plug', 'pagereviews', 'R')} -->
    {PAGE_REVIEWS}
    <!-- ENDIF -->
    
    <!-- Add Review Form -->
    <!-- IF {PHP|cot_auth('plug', 'pagereviews', 'W')} AND {PHP.usr.id} > 0 -->
    <div class="add-review-form">
        <h3>{PHP.L.pagereviews_add_review}</h3>
        {PAGE_REVIEWS_FORM}
    </div>
    <!-- ENDIF -->
    
    <!-- Link to All Reviews -->
    <a href="{PAGE_REVIEWS_URL}" class="btn btn-primary">{PHP.L.pagereviews_view_all}</a>
    
    <hr>
</div>
<!-- ENDIF -->
```

- **Tags**:
  - `{REVIEWS_TOTAL_COUNT}`: Total number of reviews.
  - `{REVIEWS_AVG_STARS}`: Average rating (stars).
  - `{REVIEWS_STARS_SUMM}`: Sum of star ratings.
  - `{PAGE_REVIEWS}`: List of reviews with pagination.
  - `{PAGE_REVIEWS_FORM}`: Form for adding a review.
  - `{PAGE_REVIEWS_URL}`: URL to the full reviews list.

### index.tpl

Shows the latest reviews on the homepage.

```html
<!-- IF {PHP|cot_plugin_active('pagereviews')} AND {PHP|cot_auth('plug', 'pagereviews', 'R')} -->
<div class="latest-reviews">
    <h2>{PHP.L.pagereviews_last_index}</h2>
    
    <!-- Latest Reviews -->
    {PHP|cot_pagereviews_last(5)}
    
    <!-- Link to All Reviews -->
    <a href="{PHP|cot_url('plug', 'e=pagereviews&m=list')}" class="btn btn-secondary">{PHP.L.pagereviews_view_all}</a>
    
    <hr>
</div>
<!-- ENDIF -->
```

- **Tags**:
  - `{PHP|cot_pagereviews_last(5)}`: Displays 5 latest reviews.
  - `{PHP|cot_url('plug', 'e=pagereviews&m=list')}`: URL to the reviews list page.

### page.list.tpl

Adds review counts and ratings to a list of articles.

```html
<!-- IF {PHP|cot_plugin_active('pagereviews')} -->
<div class="page-list">
    <!-- BEGIN: PAGES_ROW -->
    <div class="page-item">
        <h3><a href="{PAGES_ROW_URL}">{PAGES_ROW_TITLE}</a></h3>
        <p>{PAGES_ROW_DESC}</p>
        
        <!-- Review Statistics -->
        <!-- IF {PAGES_ROW_REVIEWS_COUNT} > 0 -->
        <div class="page-reviews-stats">
            <p>{PHP.L.pagereviews_pageCountReviewsTotalValue}: {PAGES_ROW_REVIEWS_COUNT}</p>
            <p>{PHP.L.pagereviews_pageAverageRatingValue}: {PAGES_ROW_AVG_STARS}</p>
            <a href="{PAGES_ROW_REVIEWS_URL}" class="btn btn-sm btn-info">{PHP.L.pagereviews_view_all}</a>
        </div>
        <!-- ELSE -->
        <p>{PHP.L.pagereviews_no_reviews}</p>
        <!-- ENDIF -->
    </div>
    <hr>
    <!-- END: PAGES_ROW -->
</div>
<!-- ENDIF -->
```

- **Tags**:
  - `{PAGES_ROW_REVIEWS_COUNT}`: Number of reviews for the article.
  - `{PAGES_ROW_AVG_STARS}`: Average rating for the article.
  - `{PAGES_ROW_REVIEWS_URL}`: URL to the article's reviews.

### users.details.tpl

Displays reviews by a specific user in their profile.

```
<!-- IF {PHP|cot_plugin_active('pagereviews')} -->
<li class="nav-item">
  <a class="nav-link<!-- IF {PHP.tab} == 'pagereviews' --> active<!-- ENDIF -->" href="{USERS_DETAILS_PAGEREVIEWS_URL}#tab_pagereviews" data-bs-toggle="tab" role="tab">{PHP.L.pagereviews_reviews} ({USERS_DETAILS_PAGEREVIEWS_COUNT})</a>
</li>
<!-- ENDIF -->
```

and

```
  <div class="reviews-content tab-pane fade<!-- IF {PHP.tab} == 'pagereviews' --> show active<!-- ENDIF -->" id="tab_pagereviews" role="tabpanel">
    {PAGEREVIEWS}
  </div>
```

for example [users.details.tpl](https://github.com/webitproff/cot-pagereviews/blob/main/users.details.tpl)

### Category Tree in page.list.tpl

Displays a category tree with review counts.

```html
<!-- IF {PHP|cot_plugin_active('pagereviews')} AND {PHP|cot_auth('plug', 'pagereviews', 'R')} -->
<div class="category-tree">
    <h2>{PHP.L.pagereviews_categories}</h2>
    
    <!-- Category Tree -->
    {PHP|cot_pagereviews_build_structure_tree('', {PHP.c})}
    
    <hr>
</div>
<!-- ENDIF -->
```

- **Tags**:
  - `{PHP|cot_pagereviews_build_structure_tree('', {PHP.c})}`: Renders a category tree with review counts.

## Usage

### Adding a Review
- Navigate to an article page (`index.php?r=page&id=X`).
- Authorized users with write permissions (`W`) can fill out the review form (`PAGE_REVIEWS_FORM`) with a title, text, and 1–5 star rating.
- Administrators can additionally set the review author and date.

### Editing/Deleting Reviews
- Authors and administrators can edit (`m=edit`) or delete (`m=delete`) reviews via links provided in the review list or single review view.

### Submitting Complaints
- Users can submit a complaint (`m=claim`) on a review, providing a reason.
- Complaints are stored in `cot_pagereviews_complaints` and trigger email notifications to administrators.

### Managing Complaints
- Administrators access the complaint list (`m=claim`) to approve (deletes the review) or reject complaints.
- Notifications are sent upon complaint resolution.

### Viewing Reviews
- **Single Review**: View a specific review (`m=main`, `itemid=X`, `pageid=Y`).
- **Review List**: Access the full review list (`m=list`) with filters for search (`sq`), category (`c`), user (`user`), and sorting (`sort`).

## Technical Details

### Directory Structure
```
/pagereviews/
├── pagereviews.setup.php                 # Plugin configuration and setup
├── pagereviews.php                       # Entry point, mode selection
├── pagereviews.pagetags.main.php         # Tags for review statistics
├── pagereviews.page.tags.php             # Tags for page.tpl integration
├── pagereviews.input.php                 # Input processing
├── pagereviews.global.php                # Language file inclusion
├── pagereviews.ajax.php                  # AJAX handler for user autocomplete
├── tpl/
│   ├── pagereviews.claim.tpl             # Complaint form template
│   ├── pagereviews.edit.tpl              # Review edit form template
│   ├── pagereviews.last.tpl              # Latest reviews template
│   ├── pagereviews.list.tpl              # Review list template
│   ├── pagereviews.main.tpl              # Single review template
│   ├── pagereviews.page.tpl              # Article page review integration
│   └── pagereviews.tree.tpl              # Category tree template
├── setup/
│   ├── pagereviews.install.sql           # SQL to create tables
│   └── pagereviews.uninstall.sql         # SQL to drop tables
├── lang/
│   ├── pagereviews.ru.lang.php           # Russian localization
│   └── pagereviews.en.lang.php           # English localization
└── inc/
    ├── pagereviews.add.php               # Review addition logic
    ├── pagereviews.claim.php             # Complaint submission and management
    ├── pagereviews.delete.php            # Review deletion logic
    ├── pagereviews.edit.php              # Review editing logic
    ├── pagereviews.functions.php         # Core functions (stars, counts, etc.)
    ├── pagereviews.list.php              # Review list with filtering/sorting
    └── pagereviews.main.php              # Single review display
```

### Database Schema
- **cot_pagereviews**:
  - `item_id` (INT, PK): Review ID
  - `item_userid` (INT): Reviewer ID
  - `item_pageid` (INT): Article ID
  - `item_text` (TEXT): Review text
  - `item_title` (VARCHAR): Review title
  - `item_score` (INT): Rating (1–5)
  - `item_date` (INT): Review date (Unix timestamp)
  - Indexes: `idx_userid`, `idx_pageid`
- **cot_pagereviews_complaints**:
  - `complaint_id` (INT, PK): Complaint ID
  - `complaint_userid` (INT): Complainant ID
  - `complaint_itemid` (INT): Review ID
  - `complaint_text` (TEXT): Complaint text
  - `complaint_date` (INT): Submission date
  - `complaint_resolved_date` (INT): Resolution date
  - `complaint_status` (ENUM): `pending`, `rejected`, `approved`
  - Indexes: `idx_userid`, `idx_itemid`

### Security
- **Permissions**: Uses `cot_auth` for read (`R`), write (`W`), and admin (`A`) access control.
- **Input Validation**: `cot_import` and `cot_check` ensure secure data handling.
- **Spam Protection**: `cot_shield_protect` prevents repetitive actions.
- **Constraints**: Enforces one review per user per article and one complaint per user per review.

### Interface
- **Styling**: Uses Bootstrap classes (`form-control`, `form-select`, `btn`) for forms and buttons.
- **Select2**: Enhances category and user dropdowns.
- **Templates**: Built with XTemplate for flexible rendering.

### Notifications
- Email notifications are sent to administrators (`user_maingrp = 5`) for:
  - New reviews
  - Edited reviews
  - Deleted reviews
  - New complaints
  - Complaint resolutions
- Notifications include details (title, text, rating, author, article, date, URLs).

## Uninstallation

1. In the Cotonti Administration Panel, go to **Extensions** → **Disable** → Select `Page Reviews`.
2. The `setup/pagereviews.uninstall.sql` script will drop the `cot_pagereviews` and `cot_pagereviews_complaints` tables.
3. Delete the `pagereviews` folder from the `plugins` directory.

## Limitations and Recommendations

### Limitations
- Requires user authentication for submitting reviews and complaints.
- One review per user per article.
- No support for nested comments on reviews.

### Recommendations
- Configure `blacklistcattree` to exclude system categories (e.g., `system,unvalidated`).
- Include Bootstrap and Select2 in your theme for optimal UX.
- Monitor complaints regularly via the admin interface (`m=claim`).
- Customize CSS to align review blocks with your site's design.

## Support

- **Issues**: Report bugs or request features at [https://github.com/webitproff/cot-pagereviews/issues](https://github.com/webitproff/cot-pagereviews/issues).
- **Help and Support**: Seek help on the [Forums](https://abuyfile.com/en/forums/custom/plugs).
- **Author**: webitprof
- **License**: BSD

   

> **Attention!** Upon completion of the installation, the conditions outlined in points №4 and №5 of the installation instructions for the [treecatspage](https://github.com/webitproff/cot-treecatspage) plugin must be fulfilled.


---

# cot-pagereviews
Плагин Page Reviews для добавления отзывов к статьям

## [Демо](https://abuyfile.com/ru/pagereviews) ##


# Плагин Page Reviews для Cotonti Siena

[![Лицензия](https://img.shields.io/badge/license-BSD-blue.svg)](https://opensource.org/licenses/BSD-3-Clause)
[![Версия](https://img.shields.io/badge/version-1.0.1-green.svg)](https://github.com/webitproff/cot-pagereviews/releases)
[![Cotonti Siena](https://img.shields.io/badge/Cotonti_Siena-v0.9.26-blue.svg)](https://github.com/Cotonti/Cotonti)

**Page Reviews** — это плагин для CMS Cotonti Siena (v0.9.26), который позволяет пользователям добавлять, редактировать, удалять и оценивать отзывы к статьям, созданным с помощью модуля `page`. Плагин включает функции подачи жалоб на отзывы, фильтрации и сортировки отзывов, а также бесшовную интеграцию с системой страниц и категорий Cotonti. Поддерживает многоязычный интерфейс (английский и русский) и предоставляет мощные инструменты администрирования.

- **Репозиторий**: [https://github.com/webitproff/cot-pagereviews](https://github.com/webitproff/cot-pagereviews)
- **Автор**: webitprof
- **Версия**: 1.0.1
- **Лицензия**: BSD
- **Дата выпуска**: 10 мая 2025

## Содержание

- [Возможности](#возможности)
- [Требования](#требования)
- [Установка](#установка)
- [Настройка](#настройка)
- [Интеграция в шаблоны](#интеграция-в-шаблоны)
  - [page.tpl](#pagetpl)
  - [index.tpl](#indextpl)
  - [page.list.tpl](#pagelisttpl)
  - [users.details.tpl](#usersdetailstpl)
  - [Дерево категорий в page.list.tpl](#дерево-категорий-в-pagelisttpl)
- [Использование](#использование)
- [Технические детали](#технические-детали)
- [Удаление](#удаление)
- [Ограничения и рекомендации](#ограничения-и-рекомендации)
- [Поддержка](#поддержка)

## Возможности

Плагин **Page Reviews** расширяет функциональность сайтов на Cotonti, добавляя интерактивные отзывы к статьям. Основные возможности:

### Управление отзывами
- **Добавление отзывов**: Пользователи могут оставлять отзывы с заголовком, текстом и оценкой от 1 до 5 звезд. Администраторы могут указывать автора и дату.
- **Редактирование/удаление отзывов**: Авторы и администраторы могут редактировать или удалять отзывы. Один пользователь — один отзыв на статью для предотвращения спама.
- **Отображение отзывов**: Отзывы можно показывать на странице статьи или в отдельном списке с пагинацией.

### Система жалоб
- Пользователи могут подавать жалобы на некорректные отзывы, указывая причину.
- Администраторы могут просматривать, одобрять (удаляя отзыв) или отклонять жалобы.
- Уведомления по email отправляются администраторам о новых жалобах и их разрешении.

### Интеграция с Cotonti
- **Страницы и категории**: Отзывы привязаны к модулю `page` и учитывают структуру категорий.
- **Теги шаблонов**: Предоставляет теги для шаблонов статей (`page.tpl`) для отображения отзывов, их количества и статистики (например, `PAGE_REVIEWS`, `REVIEWS_AVG_STARS`).
- **Многоязычность**: Включает файлы локализации на английском (`en`) и русском (`ru`).

### Фильтрация и сортировка
- **Поиск**: Фильтрация отзывов по заголовку или тексту.
- **Фильтры по категориям и пользователям**: Фильтрация по категории страницы или автору отзыва.
- **Параметры сортировки**: Сортировка по дате (по возрастанию/убыванию), рейтингу (по возрастанию/убыванию) или новизне.
- **Пагинация**: Настраиваемое количество отзывов на странице.

### Поддержка AJAX
- Автодополнение имен пользователей при фильтрации отзывов по автору.

### Администрирование
- Администраторы могут изменять автора и дату отзыва.
- Интерфейс управления жалобами для просмотра и обработки.
- Уведомления по email о действиях с отзывами (добавление, редактирование, удаление) и жалобах.

### Настройка
- Настраиваемые параметры отображения отзывов, пагинации и черного списка категорий.
- Настраиваемые мета-заголовки и описания для SEO.

## Требования

- **CMS**: Cotonti Siena v0.9.26
- **PHP**: 8.2 - 8.4
- **Модули**: `page`, `users`
- **Зависимости**: `extrafields`, `uploads`, `forms`
- **Опционально**: Bootstrap (для стилизации форм), Select2 (для выпадающих списков категорий/пользователей)

## Установка

1. **Скачивание**: Клонируйте или скачайте репозиторий с [https://github.com/webitproff/cot-pagereviews](https://github.com/webitproff/cot-pagereviews).
2. **Копирование файлов**: Распакуйте и скопируйте папку `pagereviews` в директорию `plugins` вашей установки Cotonti.
3. **Установка плагина**:
   - Перейдите в **Панель администрирования** → **Расширения** → **Установить** → Выберите `Page Reviews`.
   - Скрипт `setup/pagereviews.install.sql` создаст необходимые таблицы базы данных (`cot_pagereviews`, `cot_pagereviews_complaints`).
4. **Проверка**: Откройте страницу статьи (`index.php?r=page&id=X`), чтобы убедиться, что форма и список отзывов отображаются.

## Настройка

Настройте плагин через **Панель администрирования** → **Расширения** → **Page Reviews** → **Конфигурация**:

- **`pageall` (булево)**: Отображать все отзывы на странице статьи (1 = да, 0 = нет).
- **`reviews_per_page` (целое число)**: Количество отзывов на странице в списке отзывов (по умолчанию: 10).
- **`blacklistcattree` (строка)**: Список кодов категорий, разделенных запятыми, для исключения из отзывов (например, `system,unvalidated,info`).
- **`metatitletext` (строка)**: Мета-заголовок для страницы списка отзывов (по умолчанию: `Все отзывы`).
- **`metadescrtext` (строка)**: Мета-описание для страницы списка отзывов.

Настройте права доступа в **Панель администрирования** → **Права доступа** → `plug/pagereviews`:
- Гости: Чтение (`R`)
- Зарегистрированные пользователи: Чтение, Запись (`RW`)
- Администраторы: Чтение, Запись, Администрирование (`RWA`)

## Интеграция в шаблоны

Плагин предоставляет теги для бесшовной интеграции в шаблоны Cotonti. Ниже приведены примеры для распространенных шаблонов с использованием синтаксиса XTemplate и классов Bootstrap для стилизации.

### page.tpl

Отображает отзывы, статистику и форму добавления отзыва на странице статьи.

```html
<!-- IF {PHP|cot_plugin_active('pagereviews')} -->
<div class="reviews-section">
    <h2>{PHP.L.pagereviews_reviews}</h2>
    
    <!-- Статистика отзывов -->
    <!-- IF {REVIEWS_TOTAL_COUNT} > 0 -->
    <div class="reviews-stats">
        <p>{PHP.L.pagereviews_pageCountReviewsTotalValue}: {REVIEWS_TOTAL_COUNT}</p>
        <p>{PHP.L.pagereviews_pageAverageRatingValue}: {REVIEWS_AVG_STARS}</p>
        <p>{PHP.L.pagereviews_pageCountStarsTotalValue}: {REVIEWS_STARS_SUMM}</p>
    </div>
    <!-- ELSE -->
    <p>{PHP.L.pagereviews_no_reviews}</p>
    <!-- ENDIF -->
    
    <!-- Список отзывов -->
    <!-- IF {PHP|cot_auth('plug', 'pagereviews', 'R')} -->
    {PAGE_REVIEWS}
    <!-- ENDIF -->
    
    <!-- Форма добавления отзыва -->
    <!-- IF {PHP|cot_auth('plug', 'pagereviews', 'W')} AND {PHP.usr.id} > 0 -->
    <div class="add-review-form">
        <h3>{PHP.L.pagereviews_add_review}</h3>
        {PAGE_REVIEWS_FORM}
    </div>
    <!-- ENDIF -->
    
    <!-- Ссылка на все отзывы -->
    <a href="{PAGE_REVIEWS_URL}" class="btn btn-primary">{PHP.L.pagereviews_view_all}</a>
    
    <hr>
</div>
<!-- ENDIF -->
```

- **Теги**:
  - `{REVIEWS_TOTAL_COUNT}`: Общее количество отзывов.
  - `{REVIEWS_AVG_STARS}`: Средний рейтинг (звезды).
  - `{REVIEWS_STARS_SUMM}`: Сумма звездных оценок.
  - `{PAGE_REVIEWS}`: Список отзывов с пагинацией.
  - `{PAGE_REVIEWS_FORM}`: Форма для добавления отзыва.
  - `{PAGE_REVIEWS_URL}`: URL на полный список отзывов.

### index.tpl

Показывает последние отзывы на главной странице.

```html
<!-- IF {PHP|cot_plugin_active('pagereviews')} AND {PHP|cot_auth('plug', 'pagereviews', 'R')} -->
<div class="latest-reviews">
    <h2>{PHP.L.pagereviews_last_index}</h2>
    
    <!-- Последние отзывы -->
    {PHP|cot_pagereviews_last(5)}
    
    <!-- Ссылка на все отзывы -->
    <a href="{PHP|cot_url('plug', 'e=pagereviews&m=list')}" class="btn btn-secondary">{PHP.L.pagereviews_view_all}</a>
    
    <hr>
</div>
<!-- ENDIF -->
```

- **Теги**:
  - `{PHP|cot_pagereviews_last(5)}`: Отображает 5 последних отзывов.
  - `{PHP|cot_url('plug', 'e=pagereviews&m=list')}`: URL на страницу списка отзывов.

### page.list.tpl

Добавляет количество отзывов и рейтинги в список статей.

```html
<!-- IF {PHP|cot_plugin_active('pagereviews')} -->
<div class="page-list">
    <!-- BEGIN: PAGES_ROW -->
    <div class="page-item">
        <h3><a href="{PAGES_ROW_URL}">{PAGES_ROW_TITLE}</a></h3>
        <p>{PAGES_ROW_DESC}</p>
        
        <!-- Статистика отзывов -->
        <!-- IF {PAGES_ROW_REVIEWS_COUNT} > 0 -->
        <div class="page-reviews-stats">
            <p>{PHP.L.pagereviews_pageCountReviewsTotalValue}: {PAGES_ROW_REVIEWS_COUNT}</p>
            <p>{PHP.L.pagereviews_pageAverageRatingValue}: {PAGES_ROW_AVG_STARS}</p>
            <a href="{PAGES_ROW_REVIEWS_URL}" class="btn btn-sm btn-info">{PHP.L.pagereviews_view_all}</a>
        </div>
        <!-- ELSE -->
        <p>{PHP.L.pagereviews_no_reviews}</p>
        <!-- ENDIF -->
    </div>
    <hr>
    <!-- END: PAGES_ROW -->
</div>
<!-- ENDIF -->
```

- **Теги**:
  - `{PAGES_ROW_REVIEWS_COUNT}`: Количество отзывов для статьи.
  - `{PAGES_ROW_AVG_STARS}`: Средний рейтинг статьи.
  - `{PAGES_ROW_REVIEWS_URL}`: URL на отзывы статьи.

### users.details.tpl

Отображает отзывы конкретного пользователя в его профиле.

```html
<!-- IF {PHP|cot_plugin_active('pagereviews')} -->
<li class="nav-item">
  <a class="nav-link<!-- IF {PHP.tab} == 'pagereviews' --> active<!-- ENDIF -->" href="{USERS_DETAILS_PAGEREVIEWS_URL}#tab_pagereviews" data-bs-toggle="tab" role="tab">{PHP.L.pagereviews_reviews} ({USERS_DETAILS_PAGEREVIEWS_COUNT})</a>
</li>
<!-- ENDIF -->
```

и

```html
<div class="reviews-content tab-pane fade<!-- IF {PHP.tab} == 'pagereviews' --> show active<!-- ENDIF -->" id="tab_pagereviews" role="tabpanel">
    {PAGEREVIEWS}
</div>
```

Например, [users.details.tpl](https://github.com/webitproff/cot-pagereviews/blob/main/users.details.tpl)

### Дерево категорий в page.list.tpl

Отображает дерево категорий с количеством отзывов.

```html
<!-- IF {PHP|cot_plugin_active('pagereviews')} AND {PHP|cot_auth('plug', 'pagereviews', 'R')} -->
<div class="category-tree">
    <h2>{PHP.L.pagereviews_categories}</h2>
    
    <!-- Дерево категорий -->
    {PHP|cot_pagereviews_build_structure_tree('', {PHP.c})}
    
    <hr>
</div>
<!-- ENDIF -->
```

- **Теги**:
  - `{PHP|cot_pagereviews_build_structure_tree('', {PHP.c})}`: Формирует дерево категорий с количеством отзывов.

## Использование

### Добавление отзыва
- Перейдите на страницу статьи (`index.php?r=page&id=X`).
- Авторизованные пользователи с правами записи (`W`) могут заполнить форму отзыва (`PAGE_REVIEWS_FORM`) с заголовком, текстом и оценкой от 1 до 5 звезд.
- Администраторы могут дополнительно указать автора и дату отзыва.

### Редактирование/удаление отзывов
- Авторы и администраторы могут редактировать (`m=edit`) или удалять (`m=delete`) отзывы через ссылки в списке отзывов или при просмотре отдельного отзыва.

### Подача жалоб
- Пользователи могут подать жалобу (`m=claim`) на отзыв, указав причину.
- Жалобы сохраняются в таблице `cot_pagereviews_complaints` и инициируют email-уведомления администраторам.

### Управление жалобами
- Администраторы получают доступ к списку жалоб (`m=claim`) для одобрения (удаление отзыва) или отклонения.
- Уведомления отправляются при разрешении жалобы.

### Просмотр отзывов
- **Один отзыв**: Просмотр конкретного отзыва (`m=main`, `itemid=X`, `pageid=Y`).
- **Список отзывов**: Доступ к полному списку отзывов (`m=list`) с фильтрами по поиску (`sq`), категории (`c`), пользователю (`user`) и сортировке (`sort`).

## Технические детали

### Структура директорий
```
/pagereviews/
├── pagereviews.setup.php                 # Конфигурация и настройка плагина
├── pagereviews.php                       # Точка входа, выбор режима
├── pagereviews.pagetags.main.php         # Теги для статистики отзывов
├── pagereviews.page.tags.php             # Теги для интеграции в page.tpl
├── pagereviews.input.php                 # Обработка входных данных
├── pagereviews.global.php                # Подключение языковых файлов
├── pagereviews.ajax.php                  # Обработчик AJAX для автодополнения пользователей
├── tpl/
│   ├── pagereviews.claim.tpl             # Шаблон формы жалобы
│   ├── pagereviews.edit.tpl              # Шаблон формы редактирования отзыва
│   ├── pagereviews.last.tpl              # Шаблон последних отзывов
│   ├── pagereviews.list.tpl              # Шаблон списка отзывов
│   ├── pagereviews.main.tpl              # Шаблон одного отзыва
│   ├── pagereviews.page.tpl              # Интеграция отзывов на страницу статьи
│   └── pagereviews.tree.tpl              # Шаблон дерева категорий
├── setup/
│   ├── pagereviews.install.sql           # SQL для создания таблиц
│   └── pagereviews.uninstall.sql         # SQL для удаления таблиц
├── lang/
│   ├── pagereviews.ru.lang.php           # Русская локализация
│   └── pagereviews.en.lang.php           # Английская локализация
└── inc/
    ├── pagereviews.add.php               # Логика добавления отзыва
    ├── pagereviews.claim.php             # Подача и управление жалобами
    ├── pagereviews.delete.php            # Логика удаления отзыва
    ├── pagereviews.edit.php              # Логика редактирования отзыва
    ├── pagereviews.functions.php         # Основные функции (звезды, подсчет и т.д.)
    ├── pagereviews.list.php              # Список отзывов с фильтрацией/сортировкой
    └── pagereviews.main.php              # Отображение одного отзыва
```

### Схема базы данных
- **cot_pagereviews**:
  - `item_id` (INT, PK): ID отзыва
  - `item_userid` (INT): ID автора отзыва
  - `item_pageid` (INT): ID статьи
  - `item_text` (TEXT): Текст отзыва
  - `item_title` (VARCHAR): Заголовок отзыва
  - `item_score` (INT): Оценка (1–5)
  - `item_date` (INT): Дата отзыва (Unix timestamp)
  - Индексы: `idx_userid`, `idx_pageid`
- **cot_pagereviews_complaints**:
  - `complaint_id` (INT, PK): ID жалобы
  - `complaint_userid` (INT): ID автора жалобы
  - `complaint_itemid` (INT): ID отзыва
  - `complaint_text` (TEXT): Текст жалобы
  - `complaint_date` (INT): Дата подачи
  - `complaint_resolved_date` (INT): Дата разрешения
  - `complaint_status` (ENUM): `pending`, `rejected`, `approved`
  - Индексы: `idx_userid`, `idx_itemid`

### Безопасность
- **Права доступа**: Использует `cot_auth` для контроля доступа на чтение (`R`), запись (`W`) и администрирование (`A`).
- **Валидация данных**: `cot_import` и `cot_check` обеспечивают безопасную обработку данных.
- **Защита от спама**: `cot_shield_protect` предотвращает повторяющиеся действия.
- **Ограничения**: Один отзыв на пользователя на статью и одна жалоба на пользователя на отзыв.

### Интерфейс
- **Стилизация**: Использует классы Bootstrap (`form-control`, `form-select`, `btn`) для форм и кнопок.
- **Select2**: Улучшает выпадающие списки категорий и пользователей.
- **Шаблоны**: Построены на XTemplate для гибкого рендеринга.

### Уведомления
- Email-уведомления отправляются администраторам (`user_maingrp = 5`) для:
  - Новых отзывов
  - Отредактированных отзывов
  - Удаленных отзывов
  - Новых жалоб
  - Разрешения жалоб
- Уведомления содержат детали (заголовок, текст, оценка, автор, статья, дата, URL).

## Удаление

1. В **Панели администрирования** перейдите в **Расширения** → **Отключить** → Выберите `Page Reviews`.
2. Скрипт `setup/pagereviews.uninstall.sql` удалит таблицы `cot_pagereviews` и `cot_pagereviews_complaints`.
3. Удалите папку `pagereviews` из директории `plugins`.

## Ограничения и рекомендации

### Ограничения
- Требуется авторизация пользователя для подачи отзывов и жалоб.
- Один отзыв на пользователя на статью.
- Нет поддержки вложенных комментариев к отзывам.

### Рекомендации
- Настройте `blacklistcattree` для исключения системных категорий (например, `system,unvalidated`).
- Включите Bootstrap и Select2 в вашу тему для оптимального пользовательского опыта.
- Регулярно проверяйте жалобы через интерфейс администратора (`m=claim`).
- Настройте CSS для соответствия блоков отзывов дизайну вашего сайта.

## Поддержка

- **Проблемы**: Сообщайте о багах или запрашивайте функции на [https://github.com/webitproff/cot-pagereviews/issues](https://github.com/webitproff/cot-pagereviews/issues).
- **Помощь и поддержка**: Задавайте вопросы на [форуме](https://abuyfile.com/ru/forums/custom/plugs).
- **Автор**: webitprof
- **Лицензия**: BSD

> **Внимание!** После завершения установки необходимо выполнить условия, указанные в пунктах №4 и №5 инструкции по установке плагина [treecatspage](https://github.com/webitproff/cot-treecatspage).
