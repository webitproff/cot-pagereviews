<?php
/**
 * Page Reviews plugin - English Language file
 * Filename: pagereviews.en.lang.php
 * @package pagereviews
 * @version 1.0.1
 * @author webitprof
 * @copyright Copyright (c) 2025
 * @license BSD
 */
defined('COT_CODE') or die('Wrong URL.');
global $cfg;
/**
 * Module Configuration
 */
$L['cfg_pageall'] = 'Display all reviews on the article page';
$L['cfg_reviews_per_page'] = 'Number of reviews per page in the list';
$L['pagereviews_title'] = 'Reviews for Articles';
$L['pagereviews_desc'] = 'Plugin for adding reviews to articles';

/**
 * Interface Labels
 */
$L['pagereviews_pageRatingValue'] = 'Rating based on reviews';
$L['pagereviews_pageCountReviewsTotalValue'] = 'Total reviews';
$L['pagereviews_pageCountStarsTotalValue'] = 'Total stars received';
$L['pagereviews_pageAverageRatingValue'] = 'Average rating';
$L['pagereviews_text'] = 'Review Text';
$L['pagereviews_score'] = 'Rating';
$L['pagereviews_review'] = 'Review';
$L['pagereviews_reviews'] = 'Reviews';
$L['pagereviews_no_reviews'] = 'No reviews yet';
$L['pagereviews_add_review'] = 'Add Review';
$L['pagereviews_edit_review'] = 'Edit Review';
$L['pagereviews_user'] = 'User';
$L['pagereviews_maintitle'] = 'Review Title';
$L['pagereviews_all_reviews'] = 'All Reviews';
$L['pagereviews_all_reviews_page_title'] = 'Reviews for Publications';
$L['pagereviews_all_reviews_page_desc'] = 'All ratings and reviews by users about articles and blogs on <span class="fw-bold">"' . $cfg['maintitle'] . '"</span>, submitted by community members';
$L['pagereviews_all_reviews_page_metadescrtext'] = 'All ratings and reviews by users about articles and blogs, submitted by community members';
$L['pagereviews_all_users'] = 'All Users';
$L['pagereviews_all_categories'] = 'All Categories';
$L['pagereviews_date'] = 'Date Posted';
$L['pagereviews_author'] = 'Review Author';
$L['pagereviews_author unknown'] = 'Unknown';
$L['pagereviews_page'] = 'Article';
$L['pagereviews_categories'] = 'Categories';
$L['pagereviews_category'] = 'Category';
$L['pagereviews_search'] = 'Search';
$L['pagereviews_search_reviews'] = 'Search Reviews';
$L['pagereviews_search_user'] = 'Search by User';
$L['pagereviews_sort'] = 'Sort';
$L['pagereviews_mostrecent'] = 'Most Recent';
$L['pagereviews_dateasc'] = 'By Date (Ascending)';
$L['pagereviews_datedesc'] = 'By Date (Descending)';
$L['pagereviews_scoreasc'] = 'By Rating (Ascending)';
$L['pagereviews_scoredesc'] = 'By Rating (Descending)';
$L['pagereviews_last_index'] = 'Latest Article Reviews';
$L['pagereviews_view_all'] = 'View All Reviews';

/**
 * Complaints Interface
 */
$L['pagereviews_submit_complaint'] = 'Submit Complaint';
$L['pagereviews_complaints'] = 'Review Complaints';
$L['pagereviews_complaint_already_submitted'] = 'You have already submitted a complaint for this review. The administrator has been notified and will make a decision on the complaint.';
$L['pagereviews_no_complaints'] = 'No Complaints';
$L['pagereviews_complaint_id'] = 'Complaint ID';
$L['pagereviews_complaint_text'] = 'Complaint Text';
$L['pagereviews_complaint_user'] = 'Complainant';
$L['pagereviews_complaint_author'] = 'Complaint Author';
$L['pagereviews_complaint_date'] = 'Complaint Date';
$L['pagereviews_complaint_reason'] = 'Reason for Complaint';
$L['pagereviews_file_complaint'] = 'File a Complaint on Review';
$L['pagereviews_complaint_approve_url'] = 'Complaint Approval Link';
$L['pagereviews_complaint_reject_url'] = 'Complaint Rejection Link';
$L['pagereviews_complaint_direct_url'] = 'View Complaint';
$L['pagereviews_complaint_view_url'] = 'Complaint Link';

/**
 * Actions and Buttons
 */
$L['pagereviews_actions'] = 'Actions';
$L['pagereviews_approve'] = 'Approve';
$L['pagereviews_reject'] = 'Reject';
$L['pagereviews_submit'] = 'Submit';
$L['Add'] = 'Add';
$L['Edit'] = 'Edit';
$L['Delete'] = 'Delete';
$L['Close'] = 'Close';
$L['Cancel'] = 'Cancel';
$L['pagereviews_Editing'] = 'Editing Review';
$L['pagereviews_Editing_Save'] = 'Save Changes';
$L['pagereviews_delete_confirm'] = 'Delete Confirmation';
$L['pagereviews_delete_confirm_message'] = 'Are you sure you want to delete this review?';

/**
 * Notifications
 */
$L['pagereviews_new_review'] = 'New Review';
$L['pagereviews_new_review_body'] = 'A new review has been added';
$L['pagereviews_updated_review'] = 'Review Updated';
$L['pagereviews_updated_review_body'] = 'A review has been updated';
$L['pagereviews_deleted_review'] = 'Review Deleted';
$L['pagereviews_deleted_review_body'] = 'A review has been deleted';
$L['pagereviews_new_complaint'] = 'New Complaint';
$L['pagereviews_new_complaint_body'] = 'A new complaint has been filed on a review';
$L['pagereviews_complaint_admin_url'] = 'Complaints List';
$L['pagereviews_review_url'] = 'Review Link';

/**
 * Success Messages
 */
$L['pagereviews_success_added'] = 'Review successfully added';
$L['pagereviews_success_updated'] = 'Review successfully edited';
$L['pagereviews_success_deleted'] = 'Review successfully deleted';
$L['pagereviews_complaint_submitted'] = 'Complaint successfully submitted';
$L['pagereviews_complaint_processed'] = 'Complaint processed';

/**
 * Error Messages
 */
$L['pagereviews_error_invalidpage'] = 'The specified article is invalid';
$L['pagereviews_error_invaliduser'] = 'The selected user is invalid';
$L['pagereviews_error_nouserselected'] = 'No user selected';
$L['pagereviews_error_exists'] = 'Review already exists';
$L['pagereviews_error_emptytext'] = 'Review text cannot be empty';
$L['pagereviews_error_emptyscore'] = 'Please provide a rating';
$L['pagereviews_error_invalidreview'] = 'Invalid review';
$L['pagereviews_err_title'] = 'Title is required';
$L['pagereviews_error_no_access'] = 'Access denied';
$L['pagereviews_no_access_category'] = 'No access to this category';
$L['pagereviews_error_empty_complaint_text'] = 'Complaint text cannot be empty';
$L['pagereviews_error_complaint_exists'] = 'You have already filed a complaint for this review';

/**
 * Score Values and Titles
 */
$L['pagereviews_score_values'] = [1, 2, 3, 4, 5];
$L['pagereviews_score_titles'] = [
    '1 Star (Very Negative)',
    '2 Stars (Poor)',
    '3 Stars (Neutral)',
    '4 Stars (Good)',
    '5 Stars (Excellent)'
];
?>