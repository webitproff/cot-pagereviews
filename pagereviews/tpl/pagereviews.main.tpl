<!-- BEGIN: MAIN -->
<div class="border-bottom border-secondary py-3 px-3">
    <nav aria-label="breadcrumb">
        <div class="ps-container-breadcrumb">
            <ol class="breadcrumb d-flex mb-0">
                <li class="breadcrumb-item">
                    <a href="{PHP.cfg.mainurl}">
                        <i class="fa-solid fa-house fa-lg"></i>
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{PHP|cot_url('pagereviews', 'a=list')}" title="{PHP.L.pagereviews_all_reviews_page_title}">{PHP.L.pagereviews_all_reviews_page_title}</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">{PHP.L.pagereviews_review} #{REVIEW_ID}</li>
            </ol>
        </div>
    </nav>
</div>

<div class="min-vh-50 px-2 px-md-3 py-4">
    <div class="row justify-content-center">
        <div class="col-12 container-3xl">
            <h1 class="h3 mb-3">{PHP.L.pagereviews_review} #{REVIEW_ID}</h1>
            {FILE "{PHP.cfg.themes_dir}/{PHP.usr.theme}/warnings.tpl"}
            <div class="row">
                <div class="col-12 col-lg-8 mx-auto mb-5">
                    <div class="card">
                        <div class="card-body">
                            <h2 class="h5 mb-3">{REVIEW_TITLE}</h2>
                            <div class="mb-3">
                                <span class="review-stars">{REVIEW_STARS}</span>
                                <span class="ms-2">{PHP.L.pagereviews_score}: {REVIEW_SCORE}/5</span>
                            </div>
                            <p class="mb-3">{REVIEW_TEXT}</p>
                            <div class="mb-3">
                                <small class="text-muted">
                                    {PHP.L.pagereviews_author}: <a href="{REVIEW_AUTHOR_URL}">{REVIEW_AUTHOR}</a> | {PHP.L.pagereviews_date}: {REVIEW_DATE} | {PHP.L.pagereviews_page}: <a href="{REVIEW_PAGE_URL}">{REVIEW_PAGE_TITLE}</a>
                                </small>
                            </div>
                            <div class="row g-3 justify-content-md-end justify-content-center">
                                <!-- IF {REVIEW_EDIT_URL} -->
                                <div class="col-md-3 col-12 text-center">
                                    <a href="{REVIEW_EDIT_URL}" class="w-100 btn btn-primary btn-sm">
                                        <span class="me-2">
                                            <i class="fa-solid fa-edit"></i>
                                        </span>
                                        {PHP.L.Edit}
                                    </a>
                                </div>
                                <!-- ENDIF -->
                                <!-- IF {REVIEW_DELETE_URL} -->
                                <div class="col-md-3 col-12 text-center">
                                    <a href="{REVIEW_DELETE_URL}" class="btn w-100 btn-danger btn-sm delete-review" data-bs-toggle="modal" data-bs-target="#deleteConfirmModal" data-delete-url="{REVIEW_DELETE_URL}">
                                        <span class="me-2">
                                            <i class="fa-solid fa-trash"></i>
                                        </span>
                                        {PHP.L.Delete}
                                    </a>
                                </div>
                                <!-- ENDIF -->

                                <!-- IF {REVIEW_COMPLAINT_URL} AND !{REVIEW_IS_OWN_REVIEW} -->
                                <div class="col-md-3 col-12 text-center">
                                    <a href="{REVIEW_COMPLAINT_URL}" class="btn w-100 btn-warning btn-sm">
                                        <span class="me-2">
                                            <i class="fa-solid fa-exclamation-triangle"></i>
                                        </span>
                                        {PHP.L.pagereviews_submit_complaint}
                                    </a>
                                </div>
                                <!-- ENDIF -->

                                <div class="col-md-3 col-12 text-center">
                                    <a href="{REVIEW_BACK_URL}" class="btn w-100 btn-secondary btn-sm">
                                        <span class="me-2">
                                            <i class="fa-solid fa-angles-left"></i>
                                        </span>
                                        {PHP.L.Cancel}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4 mx-auto mb-5">
                    <!-- free column -->
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