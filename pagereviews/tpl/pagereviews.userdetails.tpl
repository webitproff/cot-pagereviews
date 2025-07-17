<!-- BEGIN: MAIN -->
<div class="reviews"> 
  <!-- BEGIN: REVIEWS_ROWS -->
  <div class="review p-3 mb-4 rounded-2" style="border: 3px var(--bs-primary-border-subtle) solid">
    <p class="mb-1">
      <span class="review-stars">{REVIEW_ROW_STARS}</span>
      <a href="{REVIEW_ROW_URL}" class=""><strong>{REVIEW_ROW_TITLE}</strong></a>
    </p>
    <p class="mb-1">{REVIEW_ROW_TEXT}</p>
    <p class="mb-1">{REVIEW_ROW_DATE|cot_date('datetime_medium', $this)}</p>
	<a href="{REVIEW_ROW_PAGE_URL}">{REVIEW_ROW_PAGE_TITLE}</a>
    <div class="row g-3 justify-content-md-end justify-content-center">
      <!-- IF {REVIEW_ROW_EDIT_URL} -->
      <div class="col-md-auto col-12 text-center">
        <a href="{REVIEW_ROW_EDIT_URL}" class="btn btn-primary btn-sm">{PHP.L.Edit}</a>
      </div>
		<!-- ENDIF -->
      <!-- IF {REVIEW_ROW_DELETE_URL} -->
      <div class="col-md-auto col-12 text-center">
        <a href="{REVIEW_ROW_DELETE_URL}" class="btn btn-danger btn-sm">{PHP.L.Delete}</a>
      </div>
      <!-- ENDIF -->
    </div>
  </div>
  <!-- END: REVIEWS_ROWS -->
  
  <!-- IF {PAGINATION} -->
  <nav aria-label="Page Pagination" class="mt-3">
    <div class="text-center mb-2">{PHP.L.Page} {CURRENT_PAGE} {PHP.L.Of} {TOTAL_PAGES}</div>
    <ul class="pagination justify-content-center">{PREVIOUS_PAGE} {PAGINATION} {NEXT_PAGE}</ul>
  </nav>
  <!-- ENDIF -->
  
</div>
<!-- END: MAIN -->
