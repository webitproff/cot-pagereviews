# cot-pagereviews
Page Reviews Plugin for adding reviews to articles
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


