

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
