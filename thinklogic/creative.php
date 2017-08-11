<?php
  include "config.php";
  session_start();
  if(!isset($_SESSION['username']))
  {
    header('location: login.php');
  }
?>

<!DOCTYPE html>
<html lang="en">
  <?php
    include("includes/head.html");
  ?>
  <body class="nav-md" onload="startTime()">
    <div class="container body">
      <div class="main_container">
        <?php
          include("includes/body-nav.html");
        ?>
        <!-- page content -->
        
        <div class="right_col" role="main">
          <div class="">
            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel" style="">
                  <div class="x_content">
                    <center><h1>CREATIVE TEAM</h1></center>
                    <br><br>
                    <div class="row">
                      <div class="col-md-12">
                        <!-- David Kalidas -->
                        <div class="col-md-3 col-sm-6 col-xs-12">
                          <div class="pricing">
                            <div class="title" style="height: 237px; background-image: url(assets/images/david.png)">
                            </div>
                            <div class="x_content">
                              <div class="">
                                <div class="pricing_features">
                                  <center>
                                    <h3>DAVID KALIDAS</h3>
                                    <h2>Team Head</h2>
                                    <br>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                                  </center>
                                </div>
                              </div>
                              <div class="pricing_footer">
                                <div class="product_social">
                                  <ul class="list-inline">
                                    <li><a href="#"><i class="fa fa-facebook-square"></i></a>
                                    </li>
                                    <li><a href="#"><i class="fa fa-twitter-square"></i></a>
                                    </li>
                                    <li><a href="#"><i class="fa fa-envelope-square"></i></a>
                                    </li>
                                    <li><a href="#"><i class="fa fa-rss-square"></i></a>
                                    </li>
                                  </ul>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <!-- /David Kalidas -->

                        <!-- Joanna Abrea -->
                        <div class="col-md-3 col-sm-6 col-xs-12">
                          <div class="pricing">
                            <div class="title" style="height: 237px; background-image: url(assets/images/david.png)">
                            </div>
                            <div class="x_content">
                              <div class="">
                                <div class="pricing_features">
                                  <center>
                                    <h3>JOANNA ABREA</h3>
                                    <h2>Web Developer</h2>
                                    <br>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                                  </center>
                                </div>
                              </div>
                              <div class="pricing_footer">
                                <div class="product_social">
                                  <ul class="list-inline">
                                    <li><a href="#"><i class="fa fa-facebook-square"></i></a>
                                    </li>
                                    <li><a href="#"><i class="fa fa-twitter-square"></i></a>
                                    </li>
                                    <li><a href="#"><i class="fa fa-envelope-square"></i></a>
                                    </li>
                                    <li><a href="#"><i class="fa fa-rss-square"></i></a>
                                    </li>
                                  </ul>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <!-- /Joanna Abrea -->

                        <!-- Cylde Valcorza -->
                        <div class="col-md-3 col-sm-6 col-xs-12">
                          <div class="pricing">
                            <div class="title" style="height: 237px; background-image: url(assets/images/david.png)">
                            </div>
                            <div class="x_content">
                              <div class="">
                                <div class="pricing_features">
                                  <center>
                                    <h3>CLYDE VALCORZA</h3>
                                    <h2>Web Developer</h2>
                                    <br>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                                  </center>
                                </div>
                              </div>
                              <div class="pricing_footer">
                                <div class="product_social">
                                  <ul class="list-inline">
                                    <li><a href="#"><i class="fa fa-facebook-square"></i></a>
                                    </li>
                                    <li><a href="#"><i class="fa fa-twitter-square"></i></a>
                                    </li>
                                    <li><a href="#"><i class="fa fa-envelope-square"></i></a>
                                    </li>
                                    <li><a href="#"><i class="fa fa-rss-square"></i></a>
                                    </li>
                                  </ul>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <!-- /Clyde Valcorza -->

                        <!-- Kevin Mabul -->
                        <div class="col-md-3 col-sm-6 col-xs-12">
                          <div class="pricing">
                            <div class="title" style="height: 237px; background-image: url(assets/images/david.png)">
                            </div>
                            <div class="x_content">
                              <div class="">
                                <div class="pricing_features">
                                  <center>
                                    <h3>KEVIN MABUL</h3>
                                    <h2>Content Writer</h2>
                                    <br>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                                  </center>
                                </div>
                              </div>
                              <div class="pricing_footer">
                                <div class="product_social">
                                  <ul class="list-inline">
                                    <li><a href="#"><i class="fa fa-facebook-square"></i></a>
                                    </li>
                                    <li><a href="#"><i class="fa fa-twitter-square"></i></a>
                                    </li>
                                    <li><a href="#"><i class="fa fa-envelope-square"></i></a>
                                    </li>
                                    <li><a href="#"><i class="fa fa-rss-square"></i></a>
                                    </li>
                                  </ul>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <!-- /Kevin Mabul -->
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->
        <?php
          include("includes/body-foot.html");
        ?>
      </div>
    </div>
  </body>
  <footer>
        <?php
          include("includes/foot.html");
        ?>
  </footer>
</html>