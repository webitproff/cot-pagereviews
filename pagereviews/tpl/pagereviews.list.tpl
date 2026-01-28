<!-- BEGIN: MAIN -->
<div class="border-bottom border-secondary py-3 px-3">
    <nav aria-label="breadcrumb">
        <div class="ps-container-breadcrumb">
            <ol class="breadcrumb d-flex mb-0">
                {PHP.L.pagereviews_title}
            </ol>
        </div>
    </nav>
</div>

<div class="min-vh-50 px-2 px-md-3 py-4">
    <div class="row justify-content-center">
        <div class="col-12 container-3xl">
            <h1 class="h3 mb-3">{PHP.L.pagereviews_all_reviews_page_title}</h1>
            <h2 class="h5 mb-4">{PHP.L.pagereviews_all_reviews_page_desc}</h2>
            {FILE "{PHP.cfg.themes_dir}/{PHP.cfg.defaulttheme}/warnings.tpl"}

            <div class="p-3 rounded-2 mb-4" style="border: 5px var(--bs-primary-border-subtle) solid;">
                <form action="{SEARCH_ACTION_URL}" method="get" class="mb-4">
                    <input type="hidden" name="e" value="pagereviews" />
                    <input type="hidden" name="m" value="list" />
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3"><label for="sq" class="form-label">{PHP.L.pagereviews_search_reviews}</label> {SEARCH_SQ}</div>
                        <div class="col-md-3"><label for="c" class="form-label">{PHP.L.pagereviews_category}</label> {SEARCH_CAT}</div>
                        <div class="col-md-3"><label for="user" class="form-label">{PHP.L.pagereviews_user}</label> {SEARCH_USER}</div>
                        <div class="col-md-3"><label for="sort" class="form-label">{PHP.L.pagereviews_sort}</label> {SEARCH_SORTER}</div>
                        <div class="col-md-2"></div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-3 d-none d-sm-block">
                            <!-- IF {PHP.usr.maingrp} == 5 -->
                            <a class="btn btn-warning w-100 w-md-auto" href="{PHP|cot_url('pagereviews', 'm=claim')}"> <i class="fa-solid fa-exclamation-triangle"></i> {PHP.L.pagereviews_complaints} </a>
                            <!-- ENDIF -->
                        </div>
                        <div class="col-12 col-sm-9">
                            <div class="row g-3 justify-content-md-end justify-content-center">
                                <div class="col-md-6 col-12 text-center">
                                    <button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-search"></i> {PHP.L.2wd_StartSearch}</button>
                                </div>
                                <div class="col-md-6 col-12 text-center">
                                    <a class="btn btn-outline-secondary w-100 w-md-auto" href="{PHP|cot_url('pagereviews', 'a=list')}">{PHP.L.2wd_ReserFilter}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="row">
                <div class="col-12 col-lg-8 mx-auto pb-5">
                    <div class="list-group list-group-striped list-group-flush">
                        <!-- BEGIN: REVIEW_ROW -->
                        <li class="list-group-item list-group-item-action">
                            <div class="row g-3">
                                <div class="col-12 col-md-8">
                                    <h5 class="mb-0 fs-6 fw-semibold text-primary-emphasis">
                                        <span class="review-stars">{REVIEW_ROW_STARS}</span>
                                        <span class="ms-2">№{REVIEW_ROW_ID} <a href="{REVIEW_ROW_URL}">{REVIEW_ROW_TITLE}</a> </span>
                                    </h5>
                                    <p class="mb-0">{REVIEW_ROW_TEXT|cot_string_truncate($this, 150)} ...</p>
                                    <p class="mb-1 text-body-secondary text-truncate">{PHP.L.pagereviews_page}: <a href="{REVIEW_ROW_PAGE_URL}">{REVIEW_ROW_PAGE_TITLE}</a></p>
                                </div>
                                <div class="col-12 col-md-4 text-center">
                                    <div class="d-inline-flex align-items-center">
                                        <div class="col-md-auto text-center text-md-start">
                                            <!-- IF {PHP|cot_module_active('files')} -->
                                            <!-- IF {REVIEW_ROW_OWNER_AVATAR_ID} > 0 -->
                                            <img src="{REVIEW_ROW_OWNER_AVATAR_URL}" alt="{REVIEW_ROW_OWNER_NICKNAME}" class="rounded-circle" width="50" height="50" />
                                            <!-- ELSE -->
                                            <img src="{PHP.R.userimg_default_avatar}" alt="{REVIEW_ROW_OWNER_NICKNAME}" class="rounded-circle" width="50" height="50" />
                                            <!-- ENDIF -->
                                            <!-- ENDIF -->
                                        </div>
                                        <div class="col-md-auto text-center text-md-end">
                                            <!-- IF {REVIEW_ROW_OWNER_FULL_NAME} -->
                                            <h4 class="h6 mt-2 mb-1 ms-2">
                                                <a class="link-success link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover" href="{REVIEW_ROW_OWNER_DETAILS_URL}">{REVIEW_ROW_OWNER_FULL_NAME}</a>
                                            </h4>
                                            <!-- ENDIF -->
                                            <!-- IF {REVIEW_ROW_OWNER_NAME} -->
                                            <p class="hot-bold mb-1 ms-2">{REVIEW_ROW_OWNER_NAME}</p>
                                            <!-- ENDIF -->
                                        </div>
                                    </div>
                                    <p class="my-0 text-muted">{REVIEW_ROW_DATE|cot_date('datetime_medium', $this)}</p>
                                </div>
                            </div>
                            <div class="mb-2">
                                <div class="row g-3 justify-content-md-start justify-content-center">
                                    <!-- IF {REVIEW_ROW_DELETE_URL} -->
                                    <div class="col-md-auto col-12 text-center">
                                        <a href="{REVIEW_ROW_DELETE_URL}" class="btn btn-danger btn-sm delete-review" data-bs-toggle="modal" data-bs-target="#deleteConfirmModal" data-delete-url="{REVIEW_ROW_DELETE_URL}">
                                            <i class="fa-solid fa-trash"></i> {PHP.L.Delete}
                                        </a>
                                    </div>
                                    <!-- ENDIF -->
                                    <!-- IF {REVIEW_ROW_EDIT_URL} -->
                                    <div class="col-md-auto col-12 text-center">
                                        <a href="{REVIEW_ROW_EDIT_URL}" class="btn btn-primary btn-sm"> <i class="fa-solid fa-edit"></i> {PHP.L.Edit} </a>
                                    </div>
                                    <!-- ENDIF -->
                                    <!-- IF {REVIEW_ROW_COMPLAINT_URL} -->
                                    <div class="col-md-auto col-12 text-center">
                                        <a href="{REVIEW_ROW_COMPLAINT_URL}" class="btn btn-warning btn-sm"> <i class="fa-solid fa-exclamation-triangle"></i> {PHP.L.pagereviews_submit_complaint} </a>
                                    </div>
                                    <!-- ENDIF -->
                                </div>
                            </div>
                        </li>
                        <!-- END: REVIEW_ROW -->
                        <!-- IF {COT_REVIEWS_COUNT} == 0 -->
                        <div class="alert alert-warning" role="alert">{PHP.L.pagereviews_no_reviews}</div>
                        <!-- ENDIF -->
                    </div>
                    <!-- IF {PAGINATION} -->
                    <nav aria-label="Page Pagination" class="mt-3">
                        <div class="text-center mb-2">{PHP.L.Page} {CURRENT_PAGE} {PHP.L.Of} {TOTAL_PAGES}</div>
                        <ul class="pagination justify-content-center">
                            {PREVIOUS_PAGE} {PAGINATION} {NEXT_PAGE}
                        </ul>
                    </nav>
                    <!-- ENDIF -->
                </div>
                <div class="col-12 col-lg-4 mx-auto pb-5">
                    <!-- IF {CATALOG} -->
                    <div class="card mb-4 border rounded-3">
                        <div class="card-header bg-primary-subtle text-primary-emphasis" style="background-color: var(--bs-primary-bg-subtle);">
                            <h3 class="h5 mb-0">{PHP.L.pagereviews_categories}</h3>
                        </div>
                        <div class="card-body px-0">{CATALOG}</div>
                    </div>
                    <!-- ENDIF -->
                </div>
            </div>
        </div>
    </div>
</div>
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
                <a href="#" class="btn btn-danger" id="confirmDeleteLink"> <i class="fa-solid fa-trash"></i> {PHP.L.Delete} </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i> {PHP.L.Close}</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.delete-review').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            var deleteUrl = this.getAttribute('data-delete-url');
            var confirmModal = document.querySelector('#deleteConfirmModal');
            var confirmLink = confirmModal.querySelector('#confirmDeleteLink');
            confirmLink.setAttribute('href', deleteUrl);
            new bootstrap.Modal(confirmModal, { backdrop: 'static' }).show();
        });
    });
});
</script>

<!-- END: MAIN -->
