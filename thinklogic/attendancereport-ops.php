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
                <h3>Daily Attendance Report - <?php $date = date("F j, Y"); echo $date = date('m/d/Y' ,strtotime($date)); ?></h3>
              </div>
            </div>
            
            <div class="clearfix"></div>
            <br>
            <button class="btn btn-primary" onclick="printAttendance()" id="print-btn"><i class="fa fa-print" ></i> Print</button>

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
                            <th class="column-title">Employee</th>
                            <th class="column-title">Team</th>
                            <th class="column-title">Attendance</th>
                            <th class="column-title">Log-In</th>
                            <th class="column-title">Break Start</th>
                            <th class="column-title">Break End</th>
                            <th class="column-title">Break Total</th>
                            <th class="column-title">Lunch Start</th>
                            <th class="column-title">Lunch End</th>
                            <th class="column-title">Break Start</th>
                            <th class="column-title">Break End</th>
                            <th class="column-title">Break Total</th>
                            <th class="column-title">Log-Out</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                              $date = date("F j, Y");
                              $date = date('m/d/Y' ,strtotime($date));
                              $employee_query = "SELECT *, logbook.in1 AS log_in1, logbook.out1 AS log_out1, logbook.in2 AS log_in2, logbook.out2 AS log_out2, logbook.employee_id AS log_empid, employees.employee_id AS emp_id
                                                FROM employees
                                                LEFT JOIN logbook ON logbook.employee_id=employees.employee_id AND log_date BETWEEN '$date' AND '$date'
                                                INNER JOIN employment ON employees.employee_id=employment.employee_id
                                                INNER JOIN employee_group ON employees.employee_id=employee_group.employee_id
                                                INNER JOIN shift_schedule ON employment.shift_schedule=shift_schedule.shift_id
                                                WHERE team_name!='Admin' OR team_name!='Sales' OR team_name!='Creative' AND employment.status='Active'";
                              $stmt = $db->prepare($employee_query);
                              $stmt->execute();
                              while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                ?>
                                <tr>
                                  <td><?php echo $row['first_name'] . " " . $row['last_name']; ?></td>
                                  <td><?php echo $row['team_name']; ?></td>
                                  <td>
                                    <?php
                                      if($row["log_in1"] == $row["in1"]){
                                        ?>
                                        <button class="btn btn-primary btn-xs">Present</button>
                                        <?php
                                      }
                                      else if($row["log_in1"] > $row["in1"] OR $row["log_in1"] > $row["in2"] OR $row["log_in2"] > $row["in2"])
                                      {
                                        ?>
                                        <button class="btn btn-success btn-xs">Late</button>
                                        <?php
                                      }
                                      else if($row["log_in1"] == $row["in2"])
                                      {
                                        ?>
                                        <button class="btn btn-warning btn-xs">Half Day</button>
                                        <?php
                                      }
                                      else
                                      {
                                        ?>
                                        <button class="btn btn-danger btn-xs">Absent</button>
                                        <?php
                                      }
                                    ?>
                                  </td>
                                  <td><?php echo $row['log_in1'] ?></td>
                                  <td><?php echo $row['break1_start'] ?></td>
                                  <td><?php echo $row['break1_end'] ?></td>
                                  <td><?php echo $row['break1_total'] ?></td>
                                  <td><?php echo $row['log_out1'] ?></td>
                                  <td><?php echo $row['log_in2'] ?></td>
                                  <td><?php echo $row['break2_start'] ?></td>
                                  <td><?php echo $row['break2_end'] ?></td>
                                  <td><?php echo $row['break2_total'] ?></td>
                                  <td><?php echo $row['log_out2'] ?></td>
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

        <!-- Print Daily Attendance Report -->
        <div id="print_attendance">
          <div id="print" style="display:none">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th colspan="6">Daily Attendace Report</th>                
                </tr>
                <tr>
                  <th colspan="6"><?php $date = date("F j, Y"); echo $date = date('m/d/Y' ,strtotime($date)); ?></th>
                </tr>
                <tr>
                  <th>Employee</th>
                  <th>Attendance</th>
                  <th>Log-In</th>
                  <th>Break Start</th>
                  <th>Break End</th>
                  <th>Break Total</th>
                  <th>Lunch Start</th>
                  <th>Lunch End</th>
                  <th>Break Start</th>
                  <th>Break End</th>
                  <th>Break Total</th>
                  <th>Log-Out</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $date = date("F j, Y");
                  $date = date('m/d/Y' ,strtotime($date));
                  $employee_query = "SELECT *, logbook.in1 AS log_in1, logbook.out1 AS log_out1, logbook.in2 AS log_in2, logbook.out2 AS log_out2, logbook.employee_id AS log_empid, employees.employee_id AS emp_id
                                    FROM employees
                                    LEFT JOIN logbook ON logbook.employee_id=employees.employee_id AND log_date BETWEEN '$date' AND '$date'
                                    INNER JOIN employment ON employees.employee_id=employment.employee_id 
                                    INNER JOIN shift_schedule ON employment.shift_schedule=shift_schedule.shift_id
                                    ORDER BY log_in1 ASC";
                  $stmt = $db->prepare($employee_query);
                  $stmt->execute();
                  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    ?>
                    <tr>
                      <td><?php echo $row['first_name'] . " " . $row['last_name'] ?></td>
                      <td>
                        <?php
                          if($row["log_in1"] == $row["in1"]){
                            ?>
                            Present
                            <?php
                          }
                          else if($row["log_in1"] > $row["in1"])
                          {
                            ?>
                            Late
                            <?php
                          }
                          else if($row["log_in1"] == $row["in2"])
                          {
                            ?>
                            Half Day
                            <?php
                          }
                          else
                          {
                            ?>
                            Absent
                            <?php
                          }
                        ?>
                      </td>
                      <td><?php echo $row['log_in1'] ?></td>
                      <td><?php echo $row['log_out1'] ?></td>
                      <td><?php echo $row['log_in2'] ?></td>
                      <td><?php echo $row['log_out2'] ?></td>

                    </tr>
                    <?php
                  }
                ?>
              </tbody>
            </table>
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