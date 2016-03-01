
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>OCIO Dashboard</title>

    <!-- Bootstrap Core CSS - Uses Bootswatch Flatly Theme: http://bootswatch.com/flatly/ -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/jquery-ui.css" />
    <link rel="stylesheet" href="css/leaflet-search.css" />
    <link rel="stylesheet" href="css/leaflet.css" /> 

    <!-- Custom CSS -->   
    <link rel="stylesheet" href="css/main.css" /> 

    <!-- Custom Fonts -->
    <link href="http://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

<style type="text/css">

</style>

<script>
<?php 
  require('models/mapData.php');
  $data = new mapData();
  //$insert = new dummyDataProcess();
  //$insert->insertDataBandwidth();
  //$data->getBandwidth(); 
  ?> 

  var bromiumData = <?php echo $data->getJsonBroDeployOverseas(); ?>;
  var GeoData = <?php echo $data->appendGeoJson(); ?>;
  var bromiumDataLocal = <?php echo $data->getBromiumLocal(); ?>;
  //Not in use yet
  var bandwidthData = <?php echo $data->getBandwidth(); ?>;
</script>

</head>

<body id="page-top" class="index">
     <?php 
     echo "<pre>";
  $data->getBandwidth();
  echo "</pre>";
  ?>
    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#page-top">IRM/DCIO Deployments</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li class="hidden">
                        <a href="#page-top"></a>
                    </li>
                    <li class="page-scroll">
                        <a href="#portfolio">Bromium Domestic Deployments</a>
                    </li>
                    <li class="page-scroll">
                        <a href="#about">Bandwidth</a>
                    </li>
                    <li class="page-scroll">
                        <a href="#portfolioModal1" class="portfolio-link" data-toggle="modal">About</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>

    <!-- Header -->
    <header>
        <div class="intro-text">
        <div class="col-lg-12 text-center">
            <h2>Bromium Global Deployments As of 12/16/2015</h2>
            <hr class="linesHR">
        </div>
        </div>
        <div id="mapdiv" style="width:90%; margin-top: 50px;">
            <div class="legendDiv"></div>
        </div>
        <!-- <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <img class="img-responsive" src="img/profile.png" alt="">
                    <div class="intro-text">
                        
                    </div>
                </div>
            </div>
        </div> -->
    </header>

    <!-- Portfolio Grid Section -->
    <section id="portfolio">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2>Bromium Domestic Deployments</h2>
                    <hr class="linesHR">
                </div>
            </div>
            <div id="chartlabels"></div>

              <svg width="1000" height="350">
                <g class="chart-wrapper" transform="translate(30,20)">
                  <g class="bars"></g>
                  <g class="x axis"></g>
                  <g class="y axis"></g>
                </g>
              </svg>
            
            <!--<div class="row">
                <div class="col-sm-4 portfolio-item">
                    <a href="#portfolioModal1" class="portfolio-link" data-toggle="modal">
                        <div class="caption">
                            <div class="caption-content">
                                <i class="fa fa-search-plus fa-3x">About the Data</i>
                            </div>
                        </div>

                        <img src="img/vistest.jpg" class="img-responsive" alt="">
                    </a>
                </div>
                <div class="col-sm-4 portfolio-item">
                    <a href="#portfolioModal2" class="portfolio-link" data-toggle="modal">
                        <div class="caption">
                            <div class="caption-content">
                                <i class="fa fa-search-plus fa-3x">Data Set 2</i>
                            </div>
                        </div>
                        <img src="img/vistest2.png" class="img-responsive" alt="">
                    </a>
                </div>
                <div class="col-sm-4 portfolio-item">
                    <a href="#portfolioModal3" class="portfolio-link" data-toggle="modal">
                        <div class="caption">
                            <div class="caption-content">
                                <i class="fa fa-search-plus fa-3x">Data Set 3</i>
                            </div>
                        </div>
                        <img src="img/vistest.jpg" class="img-responsive" alt="">
                    </a>
                </div>
                <div class="col-sm-4 portfolio-item">
                    <a href="#portfolioModal4" class="portfolio-link" data-toggle="modal">
                        <div class="caption">
                            <div class="caption-content">
                                <i class="fa fa-search-plus fa-3x">Data Set 4</i>
                            </div>
                        </div>
                        <img src="img/vistest2.png" class="img-responsive" alt="">
                    </a>
                </div>
                <div class="col-sm-4 portfolio-item">
                    <a href="#portfolioModal5" class="portfolio-link" data-toggle="modal">
                        <div class="caption">
                            <div class="caption-content">
                                <i class="fa fa-search-plus fa-3x">Data Set 5</i>
                            </div>
                        </div>
                        <img src="img/vistest.jpg" class="img-responsive" alt="">
                    </a>
                </div>
                <div class="col-sm-4 portfolio-item">
                    <a href="#portfolioModal6" class="portfolio-link" data-toggle="modal">
                        <div class="caption">
                            <div class="caption-content">
                                <i class="fa fa-search-plus fa-3x">Data Set 6</i>
                            </div>
                        </div>
                        <img src="img/vistest2.png" class="img-responsive" alt="">
                    </a>
                </div>-->
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="success" id="about">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2>Bandwidth</h2>
                    <hr class="linesHR">
                </div>
            </div>

            <div class="row tabs-app">
                <h5 id="chartlabelstext">A breakdown of bandwidth capacity by Region and then by Post. </h5>
              <div id="treemap"></div>
            </div>

            <div class="row">
                <!--<div class="col-lg-4 col-lg-offset-2">
                    <p>This is a demo text area. CIO Dashboard is a free to use, open source data visualization tool. CIO Dashboard is a free to use, open source data visualization tool. CIO Dashboard is a free to use, open source data visualization tool. CIO Dashboard is a free to use, open source data visualization tool. CIO Dashboard is a free to use, open source data visualization tool. CIO Dashboard is a free to use, open source data visualization tool.</p>
                </div>
                <div class="col-lg-4">
                    <p>CIO Dashboard is a free to use, open source data visualization tool. CIO Dashboard is a free to use, open source data visualization tool. CIO Dashboard is a free to use, open source data visualization tool. CIO Dashboard is a free to use, open source data visualization tool.</p>
                </div>
                <div class="col-lg-8 col-lg-offset-2 text-center">
                    <a href="#" class="btn btn-lg btn-outline">
                        <i class="fa fa-download"></i> Download Data
                    </a>
                </div>
            </div>-->
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <!--<h2>Embassies</h2>
                    <hr class="star-primary">-->
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="text-center">
        <div class="footer-above">
            <div class="container">
                <div class="row">
                    <div class="footer-col col-md-4">
                        <!--<h3>Location</h3>
                        <p>TBD</p>-->
                    </div>
                    <div class="footer-col col-md-4">
                        <!-- <h3>Security Around the Web</h3>
                        <ul class="list-inline" style="list-style-type: none; float: left;">
                            <li>
                                <a href="#" class="btn-social btn-outline">IT Security News Link 1</a>
                            </li>
                            <li>
                                <a href="#" class="btn-social btn-outline">IT Security News Link 2</a>
                            </li>
                            <li>
                                <a href="#" class="btn-social btn-outline">IT Security News Link 3</a>
                            </li>
                            <li>
                                <a href="#" class="btn-social btn-outline">IT Security News Link 4</a>
                            </li>
                            <li>
                                <a href="#" class="btn-social btn-outline">IT Security News Link 5</a>
                            </li>
                        </ul> -->
                        
                    </div>
                    <div class="footer-col col-md-4">
                        <!-- <h3>About IRM</h3>
                        <p>CIO Dashboard is a free to use, open source data visualization tool created by <a href="http://startbootstrap.com">IRM/OCIO</a>.</p>
                    -->
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-below">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        Brought to you in partnership by <strong>IRM's</strong> 
                        <a href="http://irm.m.state.sbu/sites/ops/sio/Pages/Home.aspx" target="_blank">SIO</a> and the
                         <a href="http://diplopedia.state.gov/index.php?title=Office_of_eDiplomacy_Knowledge_Leadership_Division" target="_blanck">Office of eDiplomacy</a> 
                        
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scroll to Top Button (Only visible on small and extra-small screen sizes) -->
    <div class="scroll-top page-scroll visible-xs visible-sm">
        <a class="btn btn-primary" href="#page-top">
            <i class="fa fa-chevron-up"></i>
        </a>
    </div>

    <!-- Portfolio Modals -->
    <div class="portfolio-modal modal fade" id="portfolioModal1" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-content">
            <div class="close-modal" data-dismiss="modal">
                <div class="lr">
                    <div class="rl">
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        <div class="modal-body">
                            <h2>About the Data</h2>
                            <hr class="star-primary">
                            
                            <p>This is were a description of the data in question can be placed in the near future. </p>
                            <p>This is were a description of the data in question can be placed in the near future. </p>
                            
                            <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--<div class="portfolio-modal modal fade" id="portfolioModal2" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-content">
            <div class="close-modal" data-dismiss="modal">
                <div class="lr">
                    <div class="rl">
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        <div class="modal-body">
                            <h2>Data Set 2</h2>
                            <hr class="star-primary">
                            
                            <p>Use this area of the page to describe your project. The icon above is part of a free icon set by <a href="https://sellfy.com/p/8Q9P/jV3VZ/">Flat Icons</a>. On their website, you can download their free set with 16 icons, or you can purchase the entire set with 146 icons for only $12!</p>
                            <ul class="list-inline item-details">
                                <li>Client:
                                    <strong><a href="http://startbootstrap.com">Start Bootstrap</a>
                                    </strong>
                                </li>
                                <li>Date:
                                    <strong><a href="http://startbootstrap.com">April 2014</a>
                                    </strong>
                                </li>
                                <li>Service:
                                    <strong><a href="http://startbootstrap.com">Web Development</a>
                                    </strong>
                                </li>
                            </ul>
                            <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="portfolio-modal modal fade" id="portfolioModal3" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-content">
            <div class="close-modal" data-dismiss="modal">
                <div class="lr">
                    <div class="rl">
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        <div class="modal-body">
                            <h2>Data Set 3</h2>
                            <hr class="star-primary">
                            
                            <p>Use this area of the page to describe your project. The icon above is part of a free icon set by <a href="https://sellfy.com/p/8Q9P/jV3VZ/">Flat Icons</a>. On their website, you can download their free set with 16 icons, or you can purchase the entire set with 146 icons for only $12!</p>
                            <ul class="list-inline item-details">
                                <li>Client:
                                    <strong><a href="http://startbootstrap.com">Start Bootstrap</a>
                                    </strong>
                                </li>
                                <li>Date:
                                    <strong><a href="http://startbootstrap.com">April 2014</a>
                                    </strong>
                                </li>
                                <li>Service:
                                    <strong><a href="http://startbootstrap.com">Web Development</a>
                                    </strong>
                                </li>
                            </ul>
                            <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="portfolio-modal modal fade" id="portfolioModal4" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-content">
            <div class="close-modal" data-dismiss="modal">
                <div class="lr">
                    <div class="rl">
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        <div class="modal-body">
                            <h2>Data Set 4</h2>
                                

                            <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="portfolio-modal modal fade" id="portfolioModal5" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-content">
            <div class="close-modal" data-dismiss="modal">
                <div class="lr">
                    <div class="rl">
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        <div class="modal-body">
                            <h2>Data Set 5</h2>
                            <hr class="star-primary">
                            
                            <p>Use this area of the page to describe your project. The icon above is part of a free icon set by <a href="https://sellfy.com/p/8Q9P/jV3VZ/">Flat Icons</a>. On their website, you can download their free set with 16 icons, or you can purchase the entire set with 146 icons for only $12!</p>
                            <ul class="list-inline item-details">
                                <li>Client:
                                    <strong><a href="http://startbootstrap.com">Start Bootstrap</a>
                                    </strong>
                                </li>
                                <li>Date:
                                    <strong><a href="http://startbootstrap.com">April 2014</a>
                                    </strong>
                                </li>
                                <li>Service:
                                    <strong><a href="http://startbootstrap.com">Web Development</a>
                                    </strong>
                                </li>
                            </ul>
                            <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="portfolio-modal modal fade" id="portfolioModal6" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-content">
            <div class="close-modal" data-dismiss="modal">
                <div class="lr">
                    <div class="rl">
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        <div class="modal-body">
                            <h2>Data Set 6</h2>
                            <hr class="star-primary">
                            
                            <p>Use this area of the page to describe your project. The icon above is part of a free icon set by <a href="https://sellfy.com/p/8Q9P/jV3VZ/">Flat Icons</a>. On their website, you can download their free set with 16 icons, or you can purchase the entire set with 146 icons for only $12!</p>
                            <ul class="list-inline item-details">
                                <li>Client:
                                    <strong><a href="http://startbootstrap.com">Start Bootstrap</a>
                                    </strong>
                                </li>
                                <li>Date:
                                    <strong><a href="http://startbootstrap.com">April 2014</a>
                                    </strong>
                                </li>
                                <li>Service:
                                    <strong><a href="http://startbootstrap.com">Web Development</a>
                                    </strong>
                                </li>
                            </ul>
                            <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>-->
    </div>

    <!-- jQuery -->
    <script src="js/lib/jquery-2.0.3.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/lib/bootstrap.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
    <script src="js/classie.js"></script>
    <script src="js/cbpAnimatedHeader.js"></script>

    <script type="text/javascript" src="js/lib/d3.v3.js" charset="utf-8"></script>

  <script src="js/lib/jquery-ui-1.10.4.custom.min.js"></script>
  <script src="js/lib/leaflet.js"></script> 
  <script src="js/plugins/leaflet-search.js"></script>
  <script src="js/plugins/leaflet.markercluster-src.js"></script>
  <script src="js/leafletMapMain.js"></script>

    <!-- Contact Form JavaScript - Do Not Delete Yet
    <script src="js/jqBootstrapValidation.js"></script>
    <script src="js/contact_me.js"></script>-->

    <!-- Custom Theme JavaScript -->
    <script>
        /*!
         * Start Bootstrap - Freelancer Bootstrap Theme (http://startbootstrap.com)
         * Code licensed under the Apache License v2.0.
         * For details, see http://www.apache.org/licenses/LICENSE-2.0.
         */

        // jQuery for page scrolling feature - requires jQuery Easing plugin
        $(function() {
            $('body').on('click', '.page-scroll a', function(event) {
                var $anchor = $(this);
                $('html, body').stop().animate({
                    scrollTop: $($anchor.attr('href')).offset().top
                }, 1500, 'easeInOutExpo');
                event.preventDefault();
            });
        });

        // Floating label headings for the contact form
        $(function() {
            $("body").on("input propertychange", ".floating-label-form-group", function(e) {
                $(this).toggleClass("floating-label-form-group-with-value", !! $(e.target).val());
            }).on("focus", ".floating-label-form-group", function() {
                $(this).addClass("floating-label-form-group-with-focus");
            }).on("blur", ".floating-label-form-group", function() {
                $(this).removeClass("floating-label-form-group-with-focus");
            });
        });

        // Highlight the top nav as scrolling occurs
        $('body').scrollspy({
            target: '.navbar-fixed-top'
        })

        // Closes the Responsive Menu on Menu Item Click
        $('.navbar-collapse ul li a').click(function() {
            $('.navbar-toggle:visible').click();
        });
    </script>

  </body>
</html>
