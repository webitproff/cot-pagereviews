<!-- 
/********************************************************************************
 * File: pagereviews.last.tpl
 * Extention: plugin 'pagereviews'
 * Description: Шаблон последних отзывов на главной странице сайта. В шаблоне index.tpl подключаем через {PHP|cot_pagereviews_last(5)}
 * Compatibility: CMF/CMS Cotonti Siena v0.9.26 (https://github.com/Cotonti/Cotonti)
 * Dependencies: Bootstrap 5.3.3 (https://getbootstrap.com/); Font Awesome Free 6.7.2 (https://fontawesome.com/)
 * Theme: CleanCot  
 * Version: 1.0.2 
 * Created: 07 March 2025 
 * Updated: 12 May 2025 
 * Author: webitproff 
 * Source: https://github.com/webitproff/cot-CleanCot 
 * Demo:  https://cleancot.previewit.work
 * Help and support: https://abuyfile.com/ru/forums/cotonti/original/skins/cleancot
 * License: MIT  
 ********************************************************************************/
-->

<!-- BEGIN: MAIN -->
<div class="latest-reviews">
    <h2>{PHP.L.pagereviews_last_index}</h2>
    <!-- IF {COT_REVIEWS_COUNT} == 0 -->
    <p>{PHP.L.pagereviews_no_reviews}</p>
    <!-- ELSE -->
    <div class="row">
        <!-- BEGIN: REVIEW_ROW -->
        <div class="mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{REVIEW_ROW_TITLE}</h5>
                    <p class="card-text">{REVIEW_ROW_STARS}</p>
                    <p class="card-text">{REVIEW_ROW_TEXT|cot_string_truncate($this, 150)}</p>
                    <p class="card-text">
                        <small class="text-muted">
                            {PHP.L.pagereviews_author}: {REVIEW_ROW_OWNER_FULL_NAME} {REVIEW_ROW_OWNER_NAME}<br>
                            {PHP.L.pagereviews_date}: {REVIEW_ROW_DATE|cot_date('datetime_short', $this)}<br>
                            {PHP.L.pagereviews_page}: <a href="{REVIEW_ROW_PAGE_URL}">{REVIEW_ROW_PAGE_TITLE}</a>
                        </small>
                    </p>
                </div>
            </div>
        </div>
        <!-- END: REVIEW_ROW -->
    </div>
    <!-- ENDIF -->
	<a href="{PHP|cot_url('pagereviews', 'a=list')}">{PHP.L.pagereviews_all_reviews}</a>
</div>
<!-- END: MAIN -->
<div class="last-reviews">
    <h2>{PHP.L.pagereviews_last_index}</h2>
    <!-- BEGIN: REVIEW_ROW -->
    <div class="review">
        <p><strong>{REVIEW_ROW_TITLE}</strong> ({REVIEW_ROW_STARS})</p>
        <p>{REVIEW_ROW_TEXT}</p>
        <p>{PHP.L.pagereviews_author}: {REVIEW_ROW_OWNER_FULL_NAME}</p>
        <p>{PHP.L.pagereviews_page}: <a href="{REVIEW_ROW_PAGE_URL}">{REVIEW_ROW_PAGE_TITLE}</a></p>
        <p>{PHP.L.pagereviews_date}: {REVIEW_ROW_DATE|cot_date('datetime_short', $this)}</p>
    </div>
    <!-- END: REVIEW_ROW -->
</div>
