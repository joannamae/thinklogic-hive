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
                <h3>Payroll</h3>
              </div>

              <div class="title_right">
                
              </div>
            </div>
            
            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Payroll Period <small></small></h2>
                    
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <?php 
                      function number_of_working_days($from, $to) {
                        $workingDays = [1, 2, 3, 4, 5]; # date format = N (1 = Monday, ...)
                        $holidayDays = ['*-12-25', '*-01-01', '2013-12-23']; # variable and fixed holidays

                        $from = new DateTime($from);
                        $to = new DateTime($to);
                        $to->modify('+1 day');
                        $interval = new DateInterval('P1D');
                        $periods = new DatePeriod($from, $interval, $to);

                        $days = 0;
                        foreach ($periods as $period) {
                            if (!in_array($period->format('N'), $workingDays)) continue;
                            if (in_array($period->format('Y-m-d'), $holidayDays)) continue;
                            if (in_array($period->format('*-m-d'), $holidayDays)) continue;
                            $days++;
                        }
                        return $days;
                      }

                      // Variables
                      $period_workingdays =0;
                      $daily_rate =0 ;
                      $hourly_rate =0 ;
                      $basic_salary =0;
                      $meal =0;
                      $transpo =0;
                      $rice =0;
                      $lodging =0;
                      $total_days =0;
                      $work_days =0;
                      $workdays_covered =0;

                      if(isset($_POST['payroll_period']) || isset($_GET['period_start']) || isset($_SESSION['period_end'])){

                        if(isset($_POST['payroll_period'])){
                          $period_start = $_POST['period_start'];
                          $period_end = $_POST['period_end'];
                          $month = $_POST['month'];
                          $year = $_POST['year'];
                        } else {
                          $period_start = $_SESSION['period_start'];
                          $period_end = $_SESSION['period_end'];
                          $month = $_SESSION['month'];
                          $year = $_SESSION['year'];
                        }

                        

                        //echo number_of_working_days($period_start, $period_end);
                        $period_workingdays = number_of_working_days($period_start, $period_end);

                        $myTime = $month . "1" . $year;  // Use whatever date format you want
                        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, 1, 2017); // 31

                        $workDays = 0;

                        while($daysInMonth > 0)
                        {
                          $day = date("D", $myTime); // Sun - Sat
                          if($day != "Sun" && $day != "Sat")
                              $workDays++;

                          $daysInMonth--;
                          $myTime += 86400; // 86,400 seconds = 24 hrs.
                        }

                        //$daily_rate = $_SESSION['basic_salary'] / $workDays;
                        //$work_days = $workDays;
                      }
                    ?>

                    <form class="form-horizontal form-label-left input_mask" method="POST">
                      <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <label for="fullname">Month:</label>
                          <select name="month" class="select2_single form-control" tabindex="-1">
                            <option value="01">January</option>
                            <option value="02">February</option>
                            <option value="03">March</option>
                            <option value="04">April</option>
                            <option value="05">May</option>
                            <option value="06">June</option>
                            <option value="07">July</option>
                            <option value="08">August</option>
                            <option value="09">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                          </select>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <label for="fullname">Year:</label>
                          <select name="year" class="select3_single form-control" tabindex="-1">
                            <option value="2017">2017</option>
                            <option value="2018">2018</option>
                            <option value="2019">2019</option>
                            <option value="2020">2020</option>
                          </select>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <label for="fullname">Start Date:</label>
                          <fieldset>
                          <div class="control-group">
                          <div class="controls">
                          <div class="">
                          <input type="text" class="form-control" id="single_cal3" name="period_start" aria-describedby="inputSuccess2Status3" >
                          </div>
                          </div>
                          </div>
                          </fieldset>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <label for="fullname">End Date:</label>
                          <fieldset>
                          <div class="control-group">
                          <div class="controls">
                          <div class="">
                          <input type="text" class="form-control" id="single_cal2" name="period_end" aria-describedby="inputSuccess2Status3">
                          </div>
                          </div>
                          </div>
                          </fieldset>
                        </div>
                      </div>
                      <br>
                      <div class="form-group">
                      <center>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                          <?php 
                          if(isset($_POST['reset_period'])) {
                            unset($_SESSION['period_start']);
                            unset($_SESSION['period_end']);
                            unset($_SESSION['year']);
                            unset($_SESSION['month']);
                          ?>
                            <script>window.location.href="payroll.php"</script>
                          <?php
                          }
                          ?>
                          <button type="submit" class="btn btn-default" name="reset_period">Reset</button>
                          <button type="submit" class="btn btn-primary" name="payroll_period" id="toggle">Submit</button>
                        </div>
                      </center>
                      </div>
                    </form>

                  </div>
                </div>
              </div>
            </div>

            <!-- Employee Masterlist -->
            <div class="row">
              <div class="clearfix"></div>
              <div class="col-md-12 col-sm-12 col-xs-12" width="708px">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Payroll</h2>
                    
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <div class="table-responsive">
                      <table id="datatable" class="table table-striped jambo_table bulk_action">
                        <thead>
                          <tr class="headings">
                            <th class="column-title">Action</th>
                            <th class="column-title">ID</th>
                            <th class="column-title">Employee Name</th>
                            <th class="column-title">Basic Pay</th>
                            <th class="column-title">Daily Rate</th>
                            <th class="column-title">Hours Worked</th>
                            <th class="column-title">Basic Salary</th>
                            <th class="column-title">TOTAL GROSS</th>
                            <th class="column-title">TOTAL DEDUCTIONS</th>
                            <th class="column-title">NET PAY</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                            if(isset($_POST['payroll_period']) || isset($_SESSION['period_start']) || isset($_SESSION['period_end'])){

                              if(isset($_POST['payroll_period'])){
                                $period_start = $_POST['period_start'];
                                $period_end = $_POST['period_end'];
                                $month = $_POST['month'];
                                $year = $_POST['year'];
                                $_SESSION['period_start'] = $period_start;
                                $_SESSION['period_end'] = $period_end;
                                $_SESSION['month'] = $month;
                                $_SESSION['year'] = $year;
                              } else {
                                $period_start = $_SESSION['period_start'];
                                $period_end = $_SESSION['period_end'];
                                $month = $_SESSION['month'];
                                $year = $_SESSION['year'];
                              }

                              $employee_query = "SELECT * FROM employees 
                                                INNER JOIN employment ON employees.employee_id=employment.employee_id
                                                WHERE employees.employee_id!='1' AND status='Active'";
                              $stmt1 = $db->prepare($employee_query);
                              $stmt1->execute();
                              while ($row = $stmt1->fetch(PDO::FETCH_ASSOC)){
                              ?>
                                <tr>
                                  <?php 
                                    $rowID = $row['employee_id'];
                                    $check_query = $db->prepare("SELECT count(*) FROM payslip WHERE employee_id='$rowID' AND period_start='$period_start' AND period_end='$period_end'");
                                    $check_query->execute();
                                    $result = $check_query->fetchColumn();
                                    if($result == 0){ 
                                  ?>
                                  <!-- <td class="a-center "><input type="checkbox" class="flat" name="table_records"></td> -->
                                  <td class="last"><a href="editpayroll.php?edit_id=<?php echo $rowID; ?>" ><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></td>
                                  <td><?php echo $row["employee_id"]; ?></td>
                                  <td><?php echo $row["first_name"] . " " . $row["last_name"]; ?></td>
                                  <?php
                                  // Query Employment
                                    $employment_query = "SELECT basic_salary FROM employment WHERE employee_id='$rowID'";
                                    $stmt2 = $db->prepare($employment_query);
                                    $stmt2->execute();
                                    while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)){
                                      ?>
                                      <td><?php echo $row["basic_salary"]; ?></td>
                                      <td><?php echo $daily_rate = round($row["basic_salary"] / $workDays, 2); ?></td>
                                      <?php
                                    }

                                  //Query Log (Hours worked)
                                    $log_query = "SELECT * FROM logbook WHERE log_date between '$period_start' AND '$period_end' AND employee_id='$rowID'";
                                    $stmt3 = $db->prepare($log_query);
                                    $stmt3->execute();
                                    $hours_work = 0;
                                    while ($row = $stmt3->fetch(PDO::FETCH_ASSOC)){
                                      $hours_work = $hours_work + $row["total_hours"];
                                    }
                                    ?>    
                                    <td><?php echo $hours_work; ?></td>
                                    <td><?php echo $basic_salary = round(($daily_rate / 8) * $hours_work, 2); ?></td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                  
                                </tr>
                                <?php
                                }
                              }
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
</html><!--  -->k