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
            <div class="page-title">
              <div class="title_left">
                <h3>Logbook</h3>
              </div>

              <div class="title_right">
                
              </div>
            </div>
            
            <div class="clearfix"></div>

            <!-- Employee Masterlist -->
            <div class="row">
              <div class="clearfix"></div>
              <div class="col-md-12 col-sm-12 col-xs-12" width="708px">
                <div class="x_panel">
                  <div class="x_content">
                    <div class="table-responsive">
                      <table id="datatable" class="table table-striped jambo_table bulk_action">
                        <thead>
                          <tr class="headings">
                            <th class="column-title">ID</th>
                            <th class="column-title">Day</th>
                            <th class="column-title">Date</th>
                            <th class="column-title">Log-In</th>
                            <th class="column-title">Lunch</th>
                            <th class="column-title">Lunch</th>
                            <th class="column-title">Log-Out</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                              $employee_id = $_SESSION['employee_id'];
                              $logbook_query = "SELECT * FROM logbook WHERE employee_id='$employee_id'" ;
                              $stmt = $db->prepare($logbook_query);
                              $stmt->execute();
                              while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                              ?>
                                <tr>
                                  <td><?php echo $row["log_id"]; ?></td>
                                  <td><?php echo $row["log_day"]; ?></td>
                                  <td><?php echo $row["log_date"]; ?></td>
                                  <td><?php echo $row["in1"]; ?></td>
                                  <td><?php echo $row["out1"]; ?></td>
                                  <td><?php echo $row["in2"]; ?></td>
                                  <td><?php echo $row["out2"]; ?></td>
                                </tr>
                              <?php
                              }
                            ?>
                        </tbody>
                      </table>
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