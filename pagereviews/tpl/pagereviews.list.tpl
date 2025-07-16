<!-- 
/********************************************************************************
 * File: pagereviews.list.tpl
 * Extension: plugin 'pagereviews'
 * Description: Template for displaying a list of reviews with filters
 * Compatibility: CMF/CMS Cotonti Siena v0.9.26
 * Dependencies: Bootstrap 5.3.3, Font Awesome Free 6.7.2, jQuery 3.6.0
 * Theme: CleanCot  
 * Version: 1.0.1 
 * Created: 07 March 2025 
 * Updated: 12 May 2025 
 * Author: webitprof 
 * License: MIT  
 ********************************************************************************/
-->
<!-- BEGIN: MAIN -->
<div class="container">
    <h2>{PHP.L.pagereviews_all_reviews}</h2>
    {FILE "{PHP.cfg.themes_dir}/{PHP.usr.theme}/warnings.tpl"}

    <!-- Форма поиска и сортировки -->
    <form action="{SEARCH_ACTION_URL}" method="get" class="mb-4">
        <input type="hidden" name="e" value="pagereviews">
        <input type="hidden" name="m" value="list">
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <label for="sq" class="form-label">{PHP.L.pagereviews_search_reviews}</label>
                {SEARCH_SQ}
            </div>
            <div class="col-md-3">
                <label for="c" class="form-label">{PHP.L.pagereviews_category}</label>
                {SEARCH_CAT}
            </div>
            <div class="col-md-3">
                <label for="user" class="form-label">{PHP.L.pagereviews_user}</label>
                {SEARCH_USER}
            </div>
            <div class="col-md-3">
                <label for="sort" class="form-label">{PHP.L.pagereviews_sort}</label>
                {SEARCH_SORTER}
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-search"></i> {PHP.L.pagereviews_search}</button>
            </div>
        </div>
    </form>

    <!-- Дерево категорий -->
    <div class="mb-4">
        <h4>{PHP.L.pagereviews_categories}</h4>
        {CATALOG}
    </div>

    <!-- IF {COT_REVIEWS_COUNT} == 0 -->
    <p>{PHP.L.pagereviews_no_reviews}</p>
    <!-- ELSE -->
    <div class="row">
        <!-- BEGIN: REVIEW_ROW -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">{REVIEW_ROW_TITLE}</h5>
                    <p class="card-text">{REVIEW_ROW_STARS}</p>
                    <p class="card-text">{REVIEW_ROW_TEXT|cot_string_truncate($this, 150)}</p>
                    <p class="card-text">
                        <small class="text-muted">
                            {PHP.L.pagereviews_author}: {REVIEW_ROW_OWNER_NAME}<br>
                            {PHP.L.pagereviews_date}: {REVIEW_ROW_DATE|cot_date('datetime_short', $this)}<br>
                            {PHP.L.pagereviews_page}: <a href="{REVIEW_ROW_PAGE_URL}">{REVIEW_ROW_PAGE_TITLE}</a>
                        </small>
                    </p>
                    <p>
                        <a href="{REVIEW_ROW_COMPLAINT_URL}" class="btn btn-warning btn-sm"><i class="fa-solid fa-exclamation-triangle"></i> {PHP.L.pagereviews_submit_complaint}</a>
                        <!-- IF {REVIEW_ROW_DELETE_URL} -->
                        <a href="{REVIEW_ROW_DELETE_URL}" class="btn btn-danger btn-sm delete-review" data-bs-toggle="modal" data-bs-target="#deleteConfirmModal" data-delete-url="{REVIEW_ROW_DELETE_URL}"><i class="fa-solid fa-trash"></i> {PHP.L.Delete}</a>
                        <!-- IF {REVIEW_ROW_EDIT_URL} -->
                        <a href="{REVIEW_ROW_EDIT_URL}" class="btn btn-primary btn-sm"><i class="fa-solid fa-edit"></i> {PHP.L.Edit}</a>
                        <!-- ENDIF -->
                        <!-- ENDIF -->
                    </p>
                </div>
            </div>
        </div>
        <!-- END: REVIEW_ROW -->
    </div>
	
    <!-- IF {PAGINATION} -->
    <nav aria-label="Page Pagination" class="mt-3">
        <div class="text-center mb-2">{PHP.L.Page} {CURRENT_PAGE} {PHP.L.Of} {TOTAL_PAGES}</div>
        <ul class="pagination justify-content-center">{PREVIOUS_PAGE} {PAGINATION} {NEXT_PAGE}</ul>
    </nav>
    <!-- ENDIF -->	
    <!-- ENDIF -->

    <!-- Modal for delete confirmation -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title fs-5" id="deleteConfirmModalLabel">{PHP.L.pagereviews_delete_confirm}</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>{PHP.L.pagereviews_delete_confirm_message}</p>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-danger" id="confirmDeleteLink"><i class="fa-solid fa-trash"></i> {PHP.L.Delete}</a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i> {PHP.L.Close}</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{PHP.cfg.themes_dir}/cleancot/js/jquery-3.6.0.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Обработка кнопки удаления
    document.querySelectorAll('.delete-review').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            var deleteUrl = this.getAttribute('data-delete-url');
            var confirmModal = document.querySelector('#deleteConfirmModal');
            var confirmLink = confirmModal.querySelector('#confirmDeleteLink');
            confirmLink.setAttribute('href', deleteUrl);
            new bootstrap.Modal(confirmModal).show();
        });
    });
});
</script>
<!-- END: MAIN -->