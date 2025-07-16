<!-- 
/********************************************************************************
 * File: pagereviews.claim.tpl
 * Extension: plugin 'pagereviews'
 * Description: Template for submitting and managing complaints about reviews
 * Compatibility: CMF/CMS Cotonti Siena v0.9.26
 * Dependencies: Bootstrap 5.3.3, Font Awesome Free 6.7.2
 * Theme: CleanCot  
 * Version: 1.0.1 
 * Created: 13 May 2025 
 * Author: webitprof 
 * License: MIT   
 ********************************************************************************/
-->
<!-- BEGIN: MAIN -->

<h1>{PHP.L.pagereviews_complaints}</h1>

<!-- BEGIN: ADMIN_COMPLAINTS -->
<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>{PHP.L.pagereviews_complaint_id}</th>
            <th>{PHP.L.pagereviews_complaint_text}</th>
            <th>{PHP.L.pagereviews_complaint_user}</th>
            <th>{PHP.L.pagereviews_review_title}</th>
            <th>{PHP.L.pagereviews_page}</th>
            <th>{PHP.L.pagereviews_actions}</th>
        </tr>
    </thead>
    <tbody>
        <!-- BEGIN: COMPLAINT_ROW -->
        <tr>
            <td>{COMPLAINT_ROW_ID}</td>
            <td>{COMPLAINT_ROW_TEXT}</td>
            <td>{COMPLAINT_ROW_USER_NAME}</td>
            <td>{COMPLAINT_ROW_REVIEW_TITLE}</td>
            <td><a href="{COMPLAINT_ROW_PAGE_URL}" target="_blank">{COMPLAINT_ROW_PAGE_TITLE}</a></td>
            <td>
                <form method="post" action="{COMPLAINT_ROW_APPROVE_URL}" style="display:inline;">
                    <input type="hidden" name="action" value="approve">
                    <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('{PHP.L.Confirm}?');">
                        {PHP.L.pagereviews_approve}
                    </button>
                </form>
                <form method="post" action="{COMPLAINT_ROW_REJECT_URL}" style="display:inline;">
                    <input type="hidden" name="action" value="reject">
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
        <strong>{PHP.L.pagereviews_review_title}:</strong> {COMPLAINT_REVIEW_TITLE}<br>
        <strong>{PHP.L.pagereviews_score}:</strong> {COMPLAINT_REVIEW_SCORE}/5<br>
        <strong>{PHP.L.pagereviews_review_text}:</strong><br>
        <div class="well">{COMPLAINT_REVIEW_TEXT}</div>
        <strong>{PHP.L.pagereviews_page}:</strong> <a href="{COMPLAINT_PAGE_URL}">{COMPLAINT_PAGE_TITLE}</a>
    </div>

    <form action="{COMPLAINT_FORM_SEND}" method="post">
        <div class="form-group">
            <label for="complaint_text">{PHP.L.pagereviews_complaint_reason}:</label>
            {COMPLAINT_FORM_TEXT}
        </div>
        <button type="submit" class="btn btn-primary">{PHP.L.Submit}</button>
    </form>
</div>
<!-- END: USER_COMPLAINT -->

<!-- END: MAIN -->
