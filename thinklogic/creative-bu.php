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

  <style>
    .hexagon {
    position: relative;
    width: 300px; 
    height: 173.21px;
    margin: 86.60px 0;
    background-image: url(assets/images/c_logo.png);
    background-size: auto 346.4102px;
    background-position: center;
    }

    .hexTop,
    .hexBottom {
    position: absolute;
    z-index: 1;
    width: 212.13px;
    height: 212.13px;
    overflow: hidden;
    -webkit-transform: scaleY(0.5774) rotate(-45deg);
    -ms-transform: scaleY(0.5774) rotate(-45deg);
    transform: scaleY(0.5774) rotate(-45deg);
    background: inherit;
    left: 43.93px;
    }

    /*counter transform the bg image on the caps*/
    .hexTop:after,
    .hexBottom:after {
    content: "";
    position: absolute;
    width: 300.0000px;
    height: 173.20508075688775px;
    -webkit-transform:  rotate(45deg) scaleY(1.7321) translateY(-86.6025px);
    -ms-transform:      rotate(45deg) scaleY(1.7321) translateY(-86.6025px);
    transform:          rotate(45deg) scaleY(1.7321) translateY(-86.6025px);
    -webkit-transform-origin: 0 0;
    -ms-transform-origin: 0 0;
    transform-origin: 0 0;
    background: inherit;
    }

    .hexTop {
    top: -106.0660px;
    }

    .hexTop:after {
    background-position: center top;
    }

    .hexBottom {
    bottom: -106.0660px;
    }

    .hexBottom:after {
    background-position: center bottom;
    }

    .hexagon:after {
    content: "";
    position: absolute;
    top: 0.0000px;
    left: 0;
    width: 300.0000px;
    height: 173.2051px;
    z-index: 2;
    background: inherit;
    }
  </style>

  <body class="nav-md" onload="startTime()">
    <div class="container body">
      <div class="main_container">
        <?php
          include("includes/body-nav.html");
        ?>
        <!-- page content -->
        
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Shift Schedule</h3>
              </div>

              <div class="title_right">
                <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                  <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search">
                    <span class="input-group-btn">
                      <button class="btn btn-default" type="button">Go</button>
                    </span>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="clearfix"></div>

            <div class="row">
              <div class="clearfix"></div>
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_content">
                    <div class="hexagon">
                      <div class="hexTop"></div>
                      <div class="hexBottom"></div>
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