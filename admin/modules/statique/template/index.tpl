<section class="box nobox">
    <div class="content-body">
        <div class="col-xs-12">
            <!--<div class="row">-->
                <!--<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 no-padding">-->
                    <!--<div class="stats">-->
                        <!--<div class="stats-data">-->
                            <!--<i class="fa fa-home pull-left"></i>-->
                            <!--<div class="stats-details">-->
                                <!--<h3>52</h3>-->
                                <!--<span>Visiteurs</span>-->
                            <!--</div>-->
                        <!--</div>-->
                    <!--</div>-->
                <!--</div>-->


                <!--<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 no-padding">-->
                    <!--<div class="stats">-->
                        <!--<div class="stats-data">-->
                            <!--<i class="fa fa-home pull-left"></i>-->
                            <!--<div class="stats-details">-->
                                <!--<h3>52</h3>-->
                                <!--<span>Visiteurs</span>-->
                            <!--</div>-->
                        <!--</div>-->
                    <!--</div>-->
                <!--</div>-->

            <!--</div>-->
        </div>
    </div>
</section>


<div class="col-xs-12">
    <section class="box">
        <header class="panel-header">
            <h2 class="title pull-left">Statistiques</h2>
            <div class="actions panel_actions pull-right">
                <i class="box_toggle fa fa-chevron-down"></i>
                <i class="box_close fa fa-times"></i>
            </div>
        </header>

        <div class="content-body">
            <div id="embed-api-auth-container"></div>
            <div id="chart-container"></div>
            <div id="view-selector-container"></div>
        </div>
    </section>
</div>

<script>
    (function(w,d,s,g,js,fs){
        g=w.gapi||(w.gapi={

                });g.analytics={q:[],ready:function(f){this.q.push(f);}};
        js=d.createElement(s);fs=d.getElementsByTagName(s)[0];
        js.src='https://apis.google.com/js/platform.js';
        fs.parentNode.insertBefore(js,fs);js.onload=function(){g.load('analytics');};
    }(window,document,'script'));
</script>

<script>

    gapi.analytics.ready(function() {

        /**
         * Authorize the user immediately if the user has already granted access.
         * If no access has been created, render an authorize button inside the
         * element with the ID "embed-api-auth-container".
         */
        gapi.analytics.auth.authorize({
            container: 'embed-api-auth-container',
            clientid: '{google_client_id}'
        });


        /**
         * Create a new ViewSelector instance to be rendered inside of an
         * element with the id "view-selector-container".
         */
        var viewSelector = new gapi.analytics.ViewSelector({
            container: 'view-selector-container'
        });

        // Render the view selector to the page.
        viewSelector.execute();


        /**
         * Create a new DataChart instance with the given query parameters
         * and Google chart options. It will be rendered inside an element
         * with the id "chart-container".
         */
        var dataChart = new gapi.analytics.googleCharts.DataChart({
            query: {
                metrics: 'ga:sessions',
                dimensions: 'ga:date',
                'start-date': '30daysAgo',
                'end-date': 'yesterday'
            },
            chart: {
                container: 'chart-container',
                type: 'LINE',
                options: {
                    width: '100%'
                }
            }
        });


        /**
         * Render the dataChart on the page whenever a new view is selected.
         */
        viewSelector.on('change', function(ids) {
            dataChart.set({query: {ids: ids}}).execute();
        });

    });
</script>