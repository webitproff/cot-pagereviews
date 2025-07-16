<!-- BEGIN: MAIN -->
<div class="reviews"> 
{FILE "{PHP.cfg.themes_dir}/{PHP.usr.theme}/warnings.tpl"} 
	<div class="row g-3 justify-content-md-between">
		<div class="col-md-auto col-6">
		  <h3 class="h4 mb-4">{PHP.L.pagereviews_reviews}
			<!-- IF {COT_REVIEWS_COUNT} > 0 -->
			<span class="badge text-bg-primary">{COT_REVIEWS_COUNT}</span>
			<!-- ENDIF -->
		  </h3>
		</div>
		<div class="col-md-auto col-6 ms-md-auto">
		  <a href="{PHP|cot_url('pagereviews', 'a=list')}" class="btn btn-success">{PHP.L.pagereviews_all_reviews}</a>
		</div>
	</div>
	
  <!-- BEGIN: ADD_FORM -->
  <!-- Button trigger modal -->
  <button type="button" class="btn btn-primary btn-sm mb-4" data-bs-toggle="modal" data-bs-target="#review-add-form"> {PHP.L.pagereviews_add_review} </button>
  <!-- Modal -->
  <div class="modal fade" id="review-add-form" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title fs-5" id="exampleModalLabel">{PHP.L.pagereviews_add_review}</h3>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="{ADD_FORM_SEND}" method="post" class="review-add-form">
            <div class="form-group">
              <label>{PHP.L.pagereviews_maintitle}</label> {ADD_FORM_TITLE}
            </div>
            <div class="form-group">
              <label>{PHP.L.pagereviews_text}</label> {ADD_FORM_TEXT}
            </div>
            <div class="form-group">
              <label>{PHP.L.pagereviews_score}</label> {ADD_FORM_SCORE}
            </div>
            <!-- IF {ADD_FORM_USERID} -->
            <div class="form-group">
              <label>{PHP.L.pagereviews_user}</label> {ADD_FORM_USERID}
            </div>
            <!-- ENDIF -->
            <!-- IF {ADD_FORM_DATE} -->
            <div class="form-group">
              <label>{PHP.L.pagereviews_date}</label> {ADD_FORM_DATE}
            </div>
            <!-- ENDIF -->
            <button type="submit" class="btn btn-primary">{PHP.L.Add}</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- END: ADD_FORM -->
  
  <!-- IF {COT_REVIEWS_COUNT} == 0 -->
  <p class="mb-4">{PHP.L.pagereviews_no_reviews}</p>
  <!-- ENDIF -->
  
  <!-- BEGIN: REVIEWS_ROWS -->
  <div class="review p-3 mb-4 rounded-2" style="border: 3px var(--bs-primary-border-subtle) solid">
    <p class="mb-1">
      <span class="review-stars">{REVIEW_ROW_STARS}</span>
      <a href="{REVIEW_ROW_URL}" class=""><strong>{REVIEW_ROW_TITLE}</strong></a>
    </p>
    <p class="mb-1">{REVIEW_ROW_TEXT}</p>
    <div class="d-inline-flex align-items-center">
      <div class="col-md-auto text-center text-md-start">
        <!-- IF {PHP|cot_module_active('files')} -->
        <!-- IF {REVIEW_ROW_AVATAR_ID} > 0 -->
        <img src="{REVIEW_ROW_AVATAR_URL}" alt="{REVIEW_ROW_NICKNAME}" class="rounded-circle" width="50" height="50">
        <!-- ELSE -->
        <img src="{PHP.R.userimg_default_avatar}" alt="{REVIEW_ROW__NICKNAME}" class="rounded-circle" width="50" height="50">
        <!-- ENDIF -->
        <!-- ENDIF -->
      </div>
      <div class="col-md-auto text-center text-md-end">
        <!-- IF {REVIEW_ROW_FULL_NAME} -->
        <h4 class="h6 mt-2 mb-1 ms-2">
          <a class="link-success link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover" href="{REVIEW_ROW_DETAILS_URL}"> {REVIEW_ROW_FULL_NAME} </a>
        </h4>
        <!-- ENDIF -->
        <!-- IF {REVIEW_ROW_OWNER} -->
        <p class="hot-bold mb-1 ms-2">{REVIEW_ROW_OWNER}</p>
        <!-- ENDIF -->
      </div>
    </div>
    <p class="mb-1">{REVIEW_ROW_DATE|cot_date('datetime_medium', $this)}</p>
	
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
<div class="pagination"> {PAGENAV_PREV} {PAGENAV_PAGES} {PAGENAV_NEXT} </div>