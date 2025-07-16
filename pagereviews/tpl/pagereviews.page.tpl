<!-- BEGIN: MAIN -->
<div class="reviews">
{FILE "{PHP.cfg.themes_dir}/{PHP.usr.theme}/warnings.tpl"} 
    <h2>{PHP.L.pagereviews_reviews}</h2>
    <!-- IF {COT_REVIEWS_COUNT} == 0 -->
    <p>{PHP.L.pagereviews_no_reviews}</p>
    <!-- ENDIF -->
    <!-- BEGIN: REVIEWS_ROWS -->
    <div class="review">
        <p>{REVIEW_ROW_STARS} <strong>{REVIEW_ROW_TITLE}</strong></p>
        <p>{REVIEW_ROW_TEXT}</p>
        <p>{PHP.L.pagereviews_author}: {REVIEW_ROW_OWNER} / {REVIEW_ROW_OWNERID} {REVIEW_ROW_NAME}{REVIEW_ROW_FULL_NAME} {REVIEW_ROW_MAIN_GROUP_TITLE} | {PHP.L.pagereviews_date}: {REVIEW_ROW_DATE|cot_date('datetime_short', $this)}</p>
        <!-- IF {REVIEW_ROW_DELETE_URL} -->
        <p><a href="{REVIEW_ROW_DELETE_URL}" class="btn btn-danger">{PHP.L.Delete}</a></p>
        <!-- ENDIF -->
        <!-- BEGIN: EDIT_FORM -->
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#review-edit-form">
            {PHP.L.Edit}
        </button>

        <!-- Modal -->
        <div class="modal fade" id="review-edit-form" tabindex="-1" aria-labelledby="review-edit-form" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title fs-5" id="review-edit-form">{PHP.L.pagereviews_Editing}</h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{EDIT_FORM_SEND}" method="post" class="review-edit-form">
                            <div class="form-group">
                                <label>{PHP.L.pagereviews_maintitle}</label>
                                {EDIT_FORM_TITLE}
                            </div>
                            <div class="form-group">
                                <label>{PHP.L.pagereviews_text}</label>
                                {EDIT_FORM_TEXT}
                            </div>
                            <div class="form-group">
                                <label>{PHP.L.pagereviews_score}</label>
                                {EDIT_FORM_SCORE}
                            </div>
                            <!-- IF {EDIT_FORM_USERID} -->
                            <div class="form-group">
                                <label>{PHP.L.pagereviews_user}</label>
                                {EDIT_FORM_USERID}
                            </div>
                            <!-- ENDIF -->
                            <!-- IF {EDIT_FORM_DATE} -->
                            <div class="form-group">
                                <label>{PHP.L.pagereviews_date}</label>
                                {EDIT_FORM_DATE}
                            </div>
                            <!-- ENDIF -->
                            <button type="submit" class="btn btn-primary">{PHP.L.pagereviews_Editing_Save}</button>
                            <a href="{EDIT_FORM_DELETE_URL}" class="btn btn-danger">{PHP.L.Delete}</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- END: EDIT_FORM -->
    </div>
    <!-- END: REVIEWS_ROWS -->

    <!-- BEGIN: ADD_FORM -->
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#review-add-form">
        {PHP.L.pagereviews_add_review}
    </button>

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
                            <label>{PHP.L.pagereviews_maintitle}</label>
                            {ADD_FORM_TITLE}
                        </div>
                        <div class="form-group">
                            <label>{PHP.L.pagereviews_text}</label>
                            {ADD_FORM_TEXT}
                        </div>
                        <div class="form-group">
                            <label>{PHP.L.pagereviews_score}</label>
                            {ADD_FORM_SCORE}
                        </div>
                        <!-- IF {ADD_FORM_USERID} -->
                        <div class="form-group">
                            <label>{PHP.L.pagereviews_user}</label>
                            {ADD_FORM_USERID}
                        </div>
                        <!-- ENDIF -->
                        <!-- IF {ADD_FORM_DATE} -->
                        <div class="form-group">
                            <label>{PHP.L.pagereviews_date}</label>
                            {ADD_FORM_DATE}
                        </div>
                        <!-- ENDIF -->
                        <button type="submit" class="btn btn-primary">{PHP.L.Add}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END: ADD_FORM -->
</div>
<!-- END: MAIN -->