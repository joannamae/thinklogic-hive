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
          include("datediff.php");
        ?>

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="row">
          <!-- Morning Break -->
            <div class="col-md-4 col-sm-4 col-xs-12">
              <div class="x_panel tile fixed_height_320">
                <div class="x_title">
                  <h2>Break 1</h2>
                  <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                      <ul class="dropdown-menu" role="menu">
                        <li><a href="#">Settings 1</a>
                        </li>
                        <li><a href="#">Settings 2</a>
                        </li>
                      </ul>
                    </li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                    </li>
                  </ul>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <div class="row">
                    <div class="col-md-12">
                      <center>
                        <h4>11:00 AM - 11:15 AM</h4>
                        <br>
                        <h3><label id="minutes">00</label>:<label id="seconds">00</label></h3>
                      </center>
                    </div>
                  </div>
                  <br>
                  <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-6">
                          <div class="pull-right">
                            <?php
                              if(isset($_POST['break1_start'])){
                                $log_id=$_POST['log_id'];
                                $break1_start= date("h:i:sa");
                                $stmt = $db->prepare("UPDATE logbook SET break1_start='$break1_start' WHERE log_id='$log_id'");
                                $stmt->execute();

                                ?>
                                <script type="text/javascript">
                                    var minutesLabel = document.getElementById("minutes");
                                    var secondsLabel = document.getElementById("seconds");
                                    var totalSeconds = 0;
                                    setInterval(setTime, 1000);

                                    function setTime()
                                    {
                                        ++totalSeconds;
                                        secondsLabel.innerHTML = pad(totalSeconds%60);
                                        minutesLabel.innerHTML = pad(parseInt(totalSeconds/60));
                                    }

                                    function pad(val)
                                    {
                                        var valString = val + "";
                                        if(valString.length < 2)
                                        {
                                            return "0" + valString;
                                        }
                                        else
                                        {
                                            return valString;
                                        }
                                    }
                                </script>
                                <?php
                              }
                            ?>

                            <form method="POST">
                                <input style="display:none" value="<?php echo $_SESSION['log_id']; ?>" name="log_id" />
                                <input type="submit" value="Start Break" class="btn btn-success" name="break1_start"/>
                            </form>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="pull-left">
                            <?php
                              if(isset($_POST['break1_end'])){
                                $log_id=$_POST['log_id'];
                                $break1_end= date("h:i:sa");
                                $stmt = $db->prepare("UPDATE logbook SET break1_end='$break1_end' WHERE log_id='$log_id'");
                                $stmt->execute();

                                //Query Log Details
                                $timediff = "SELECT * FROM logbook WHERE log_id='$log_id'";
                                $stmt = $db->prepare($timediff);
                                $stmt->execute(array('log_id' => $log_id));
                                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                $break1_start= $row['break1_start'];
                                $break1_end = $row['break1_end'];

                                $dateDiff = dateDiff($break1_end, $break1_start);
                                $stmt = $db->prepare("UPDATE logbook SET break1_total='$dateDiff' WHERE log_id='$log_id'");
                                $stmt->execute();
                              }
                            ?>
                            <form method="POST">
                                <input style="display:none" value="<?php echo $_SESSION['log_id']; ?>" name="log_id" />
                                <input type="submit" value="End Break" class="btn btn-danger" name="break1_end" />
                            </form>
                          </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- Morning Break -->
            <!-- Lunch Break -->
            <div class="col-md-4 col-sm-4 col-xs-12">
              <div class="x_panel tile fixed_height_320">
                <div class="x_title">
                  <h2>1-Hr Break</h2>
                  <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                      <ul class="dropdown-menu" role="menu">
                        <li><a href="#">Settings 1</a>
                        </li>
                        <li><a href="#">Settings 2</a>
                        </li>
                      </ul>
                    </li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                    </li>
                  </ul>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <div class="row">
                    <div class="col-md-12">
                      <center>
                        <h4>03:30 PM - 03:45 PM</h4>
                        <br>
                        <h3><label id="aminutes">00</label>:<label id="aseconds">00</label></h3>
                      </center>
                    </div>
                  </div>
                  <br>
                  <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-6">
                          <div class="pull-right">
                            <?php
                              if(isset($_POST['out1'])){
                                $log_id=$_POST['log_id'];
                                $out1= date("h:i:sa");
                                $stmt = $db->prepare("UPDATE logbook SET out1='$out1' WHERE log_id='$log_id'");
                                $stmt->execute();

                                //Query Log Details
                                $timediff = "SELECT * FROM logbook WHERE log_id='$log_id'";
                                $stmt = $db->prepare($timediff);
                                $stmt->execute(array('log_id' => $log_id));
                                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                $out1 = $row['out1'];

                                if (strtotime($out1) >= strtotime($_SESSION['log_out1'])){
                                  $log_out1 = $_SESSION['log_out1'];
                                  $stmt = $db->prepare("UPDATE logbook SET out1='$log_out1' WHERE log_id='$log_id'");
                                  $stmt->execute();
                                }

                                ?>
                                <script type="text/javascript">
                                    var minutesLabel = document.getElementById("aminutes");
                                    var secondsLabel = document.getElementById("aseconds");
                                    var totalSeconds = 0;
                                    setInterval(setTime, 1000);

                                    function setTime()
                                    {
                                        ++totalSeconds;
                                        secondsLabel.innerHTML = pad(totalSeconds%60);
                                        minutesLabel.innerHTML = pad(parseInt(totalSeconds/60));
                                    }

                                    function pad(val)
                                    {
                                        var valString = val + "";
                                        if(valString.length < 2)
                                        {
                                            return "0" + valString;
                                        }
                                        else
                                        {
                                            return valString;
                                        }
                                    }
                                </script>
                                <?php
                              }
                            ?>
                            <form method="POST">
                                <input style="display:none" value="<?php echo $_SESSION['log_id']; ?>" name="log_id" />
                                <input type="submit" value="Start Break" class="btn btn-success" name="out1" />
                            </form>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="pull-left">
                            <?php
                              if(isset($_POST['in2'])){
                                $log_id=$_POST['log_id'];
                                $in2= date("h:i:sa");
                                $stmt = $db->prepare("UPDATE logbook SET in2='$in2' WHERE log_id='$log_id'");
                                $stmt->execute();

                                //Query Log Details
                                $timediff = "SELECT * FROM logbook WHERE log_id='$log_id'";
                                $stmt2 = $db->prepare($timediff);
                                $stmt2->execute(array('log_id' => $log_id));
                                $row = $stmt2->fetch(PDO::FETCH_ASSOC);
                                $in2 = $row['in2'];

                                if (strtotime($in2) <= strtotime($_SESSION['log_in2'])){
                                  $log_in2 = $_SESSION['log_in2'];
                                  $stmt = $db->prepare("UPDATE logbook SET in2='$log_in2' WHERE log_id='$log_id'");
                                  $stmt->execute();
                                }
                                else if (strtotime($in2) > strtotime($_SESSION['log_in2'])){
                                  $in2_late = dateDiff(strtotime($in2), strtotime($_SESSION['log_in2']));
                                  $stmt = $db->prepare("UPDATE logbook SET in2_late=$in2_late WHERE log_id='$log_id'");
                                  $stmt->execute();
                                }
                              }
                            ?>
                            <form method="POST">
                                <input style="display:none" value="<?php echo $_SESSION['log_id']; ?>" name="log_id" />
                                <input type="submit" value="End Break" class="btn btn-danger" name="in2" />
                            </form>
                          </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- /Lunch Break -->
            <!-- Afternoon Break -->
            <div class="col-md-4 col-sm-4 col-xs-12">
              <div class="x_panel tile fixed_height_320">
                <div class="x_title">
                  <h2>Break 2</h2>
                  <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                      <ul class="dropdown-menu" role="menu">
                        <li><a href="#">Settings 1</a>
                        </li>
                        <li><a href="#">Settings 2</a>
                        </li>
                      </ul>
                    </li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                    </li>
                  </ul>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <div class="row">
                    <div class="col-md-12">
                      <center>
                        <h4>03:30 PM - 03:45 PM</h4>
                        <br>
                        <h3><label id="aminutes">00</label>:<label id="aseconds">00</label></h3>
                      </center>
                    </div>
                  </div>
                  <br>
                  <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-6">
                          <div class="pull-right">
                            <?php
                              if(isset($_POST['break2_start'])){
                                $log_id=$_POST['log_id'];
                                $break2_start= date("h:i:sa");
                                $stmt = $db->prepare("UPDATE logbook SET break2_start='$break2_start' WHERE log_id='$log_id'");
                                $stmt->execute();

                                ?>
                                <script type="text/javascript">
                                    var minutesLabel = document.getElementById("aminutes");
                                    var secondsLabel = document.getElementById("aseconds");
                                    var totalSeconds = 0;
                                    setInterval(setTime, 1000);

                                    function setTime()
                                    {
                                        ++totalSeconds;
                                        secondsLabel.innerHTML = pad(totalSeconds%60);
                                        minutesLabel.innerHTML = pad(parseInt(totalSeconds/60));
                                    }

                                    function pad(val)
                                    {
                                        var valString = val + "";
                                        if(valString.length < 2)
                                        {
                                            return "0" + valString;
                                        }
                                        else
                                        {
                                            return valString;
                                        }
                                    }
                                </script>
                                <?php
                              }
                            ?>
                            <form method="POST">
                                <input style="display:none" value="<?php echo $_SESSION['log_id']; ?>" name="log_id" />
                                <input type="submit" value="Start Break" class="btn btn-success" name="break2_start" />
                            </form>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="pull-left">
                            <?php
                              if(isset($_POST['break2_end'])){
                                $log_id=$_POST['log_id'];
                                $break2_end= date("h:i:sa");
                                $stmt = $db->prepare("UPDATE logbook SET break2_end='$break2_end' WHERE log_id='$log_id'");
                                $stmt->execute();

                                //Query Log Details
                                $timediff = "SELECT * FROM logbook WHERE log_id='$log_id'";
                                $stmt = $db->prepare($timediff);
                                $stmt->execute(array('log_id' => $log_id));
                                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                $break2_start = $row['break2_start'];
                                $break2_end = $row['break2_end'];

                                $dateDiff = dateDiff($break2_end, $break2_start);
                                $stmt = $db->prepare("UPDATE logbook SET break2_total='$dateDiff' WHERE log_id='$log_id'");
                                $stmt->execute();

                              }
                            ?>
                            <form method="POST">
                                <input style="display:none" value="<?php echo $_SESSION['log_id']; ?>" name="log_id" />
                                <input type="submit" value="End Break" class="btn btn-danger" name="break2_end" />
                            </form>
                          </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- /Afternoon Break -->
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