# cot-pagereviews
Page Reviews Plugin for adding reviews to articles
### ver alfa ###

in `page.tpl`

1
```
                    <!-- IF {PHP|cot_plugin_active('pagereviews')} -->
                    <div><span class="small">{PHP.L.pagereviews_pageRatingValue}:</span> <span class="review-stars">{PAGE_REVIEWS_AVG_STARS_HTML}</span></div>
                    <div><span class="small">{PHP.L.pagereviews_pageCountStarsTotalValue}:</span> {PAGE_REVIEWS_STARS_SUMM} </div>
                    <div><span class="small">{PHP.L.pagereviews_pageCountReviewsTotalValue}:</span> {PAGE_REVIEWS_TOTAL_COUNT}</div>
                    <div><span class="small">{PHP.L.pagereviews_pageAverageRatingValue}:</span> {PAGE_REVIEWS_AVG_STARS}</div>
                    <!-- ENDIF -->
```

2
```
            <!-- IF {PHP|cot_plugin_active('pagereviews')} -->
                {PAGE_REVIEWS}
            <hr>
            <!-- ENDIF -->
```
> **Attention!** Upon completion of the installation, the conditions outlined in points №4 and №5 of the installation instructions for the [treecatspage](https://github.com/webitproff/cot-treecatspage) plugin must be fulfilled.

## System Requirements

- **Cotonti Siena**: v.0.9.26
- **PHP**: 8.4 or higher
