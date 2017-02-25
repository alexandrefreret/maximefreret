<div class="row">

    <!-- BEGIN pagination -->
    <nav aria-label="Page navigation">
        <ul class="pagination">
            <!-- BEGIN page -->
            <li class="{pagination.page.class}"><a href="{topictype_url}{pagination.page.url}?page={pagination.page.i}">{pagination.page.i}</a></li>
            <!-- END page -->
        </ul>
    </nav>
    <!-- END pagination -->

    <!-- BEGIN actualites -->
    <div class="col-md-3 col-sm-4 col-xs-6">
        <!-- BEGIN media -->
        <img src="{path_media}assets/img/news/illustration/{actualites.news_id_encode}/{actualites.media.media_filename}" alt="Image de {actualites.news_label}">
        <!-- END media -->

        <!-- BEGIN no_media -->
        <img src="{path_media}assets/img/placeholder/news.png" alt="Image de {actualites.news_label}">
        <!-- END no_media -->
        <h2>{actualites.news_label}</h2>
        <p>
            {actualites.news_contenu_clean}
        </p>
    </div>
    <!-- END actualites -->
</div>