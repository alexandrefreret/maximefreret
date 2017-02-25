<div class="col-xs-12">
    <section class="box">
        <header class="panel-header">
            <h2 class="title pull-left">Toutes les pages</h2>
            <div class="actions panel_actions pull-right">
                <i class="box_toggle fa fa-chevron-down"></i>
                <i class="box_close fa fa-times"></i>
            </div>
        </header>

        <div class="content-body">
            <div class="row">
                <div class="col-xs-12">
                    <!-- BEGIN page -->
                    <div class="post">
                        <h3><a href="{path_media}cms/page/editer/{page.page_label_url},{page.page_id_encode}.html">{page.page_label}</a></h3>

                        <div class="row blog-content">
                            <div class="col-xs-12">
                                <div class="blog-resume">
                                    {page.page_resume}
                                </div>
                            </div>
                        </div>

                        <a href="{path_media}cms/page/editer/{page.page_label_url},{page.page_id_encode}.html" class="button button-bleu">
                            <span>Editer</span> <i class="fa fa-pencil"></i>
                        </a>
                    </div>
                    <hr>
                    <!-- END page -->
                </div>
            </div>

        </div>
    </section>
</div>