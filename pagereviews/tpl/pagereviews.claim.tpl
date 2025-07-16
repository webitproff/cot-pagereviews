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
                <li class="breadcrumb-item active" aria-current="page">{PHP.L.pagereviews_complaints}</li>
            </ol>
        </div>
    </nav>
</div>

<div class="min-vh-50 px-2 px-md-3 py-4">
    <div class="row justify-content-center">
        <div class="col-12 container-3xl">
            <!-- BEGIN: ADMIN_COMPLAINTS -->
            <h1>{PHP.L.pagereviews_complaints}</h1>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>{PHP.L.pagereviews_complaint_id}</th>
                        <th>{PHP.L.pagereviews_complaint_text}</th>
                        <th>{PHP.L.pagereviews_complaint_user}</th>
                        <th>{PHP.L.pagereviews_maintitle}</th>
                        <th>{PHP.L.pagereviews_page}</th>
                        <th>{PHP.L.pagereviews_actions}</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- BEGIN: COMPLAINT_ROW -->
                    <tr>
                        <td>{COMPLAINT_ROW_ID}</td>
                        <td>{COMPLAINT_ROW_TEXT}</td>
                        <td><a href="{COMPLAINT_ROW_USER_URL}" target="_blank">{COMPLAINT_ROW_USER_NAME}</a></td>
                        <td>{COMPLAINT_ROW_REVIEW_TITLE}</td>
                        <td><a href="{COMPLAINT_ROW_PAGE_URL}" target="_blank">{COMPLAINT_ROW_PAGE_TITLE}</a></td>
                        <td>
                            <form method="post" action="{COMPLAINT_ROW_APPROVE_URL}" style="display: inline;">
                                <input type="hidden" name="action" value="approve" />
                                <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('{PHP.L.Confirm}?');">
                                    {PHP.L.pagereviews_approve}
                                </button>
                            </form>
                            <form method="post" action="{COMPLAINT_ROW_REJECT_URL}" style="display: inline;">
                                <input type="hidden" name="action" value="reject" />
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('{PHP.L.Confirm}?');">
                                    {PHP.L.pagereviews_reject}
                                </button>
                            </form>
                        </td>
                    </tr>
                    <!-- END: COMPLAINT_ROW -->
                </tbody>
            </table>
            <!-- END: ADMIN_COMPLAINTS -->

            <!-- BEGIN: USER_COMPLAINT -->
            <div class="pagereviews-complaint-form">
                <h2>{PHP.L.pagereviews_file_complaint}</h2>

                <div class="alert alert-info">
                    <strong>{PHP.L.pagereviews_maintitle}:</strong> {COMPLAINT_REVIEW_TITLE}<br />
                    <strong>{PHP.L.pagereviews_score}:</strong> {COMPLAINT_REVIEW_SCORE}/5<br />
                    <strong>{PHP.L.pagereviews_text}:</strong>
                    <div class="well">{COMPLAINT_REVIEW_TEXT}</div>
                    <strong>{PHP.L.pagereviews_page}:</strong> <a href="{COMPLAINT_PAGE_URL}">{COMPLAINT_PAGE_TITLE}</a>
                </div>

                <form action="{COMPLAINT_FORM_SEND}" method="post">
                    <div class="form-group mb-3">
                        <label for="complaint_text "><span class="mb-3 h5 text-danger fw-semibold">{PHP.L.pagereviews_complaint_reason}:</span></label>
                        <div class="mt-3">{COMPLAINT_FORM_TEXT}</div>
                    </div>
                    <div class="form-group mb-3">
                        <button type="submit" class="btn btn-primary">{PHP.L.Submit}</button>
                    </div>
                </form>
            </div>
            <!-- END: USER_COMPLAINT -->

            <!-- BEGIN: COMPLAINT_ALREADY_SUBMITTED -->
            <div class="alert alert-warning">
                {COMPLAINT_ALREADY_SUBMITTED}
            </div>
            <!-- END: COMPLAINT_ALREADY_SUBMITTED -->
        </div>
    </div>
</div>
<!-- END: MAIN -->
