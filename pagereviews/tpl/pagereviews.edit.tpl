<!-- BEGIN: MAIN -->
<div class="container-xl py-4">
    <h1 class="h3 mb-3">{PHP.L.pagereviews_Editing} #{EDIT_FORM_ID}</h1>
    {FILE "{PHP.cfg.themes_dir}/{PHP.usr.theme}/warnings.tpl"}
    <div class="card">
        <div class="card-body">
            <form action="{EDIT_FORM_SEND}" method="post" name="editreview" class="review-edit-form">
                <input type="hidden" name="submit" value="1">
                <div class="mb-3">
                    <label class="form-label">{PHP.L.pagereviews_maintitle}</label>
                    {EDIT_FORM_TITLE}
                </div>
                <div class="mb-3">
                    <label class="form-label">{PHP.L.pagereviews_text}</label>
                    {EDIT_FORM_TEXT}
                </div>
                <div class="mb-3">
                    <label class="form-label">{PHP.L.pagereviews_score}</label>
                    <div>{EDIT_FORM_SCORE}</div>
                </div>
                <!-- IF {EDIT_FORM_USERID} -->
                <div class="mb-3">
                    <label class="form-label">{PHP.L.pagereviews_user}</label>
                    {EDIT_FORM_USERID}
                </div>
                <!-- ENDIF -->
                <!-- IF {EDIT_FORM_DATE} -->
                <div class="mb-3">
                    <label class="form-label">{PHP.L.pagereviews_date}</label>
                    {EDIT_FORM_DATE}
                </div>
                <!-- ENDIF -->
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">{PHP.L.pagereviews_Editing_Save}</button>
                    <a href="{EDIT_FORM_BACK_URL}" class="btn btn-secondary">{PHP.L.Cancel}</a>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- END: MAIN -->