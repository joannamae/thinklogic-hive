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
    include("datediff.php");
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
              <div class="clearfix"></div>
              <!-- Payroll Period -->
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

                      if(isset($_POST['payroll_period'])){
                        $period_start = $_POST['period_start'];
                        $period_end = $_POST['period_end'];
                        $month = $_POST['month'];
                        $year = $_POST['year'];

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

                        $daily_rate = $_SESSION['basic_salary'] / $workDays;
                        $work_days = $workDays;
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
                          <input type="text" class="form-control" id="single_cal3" name="period_start" aria-describedby="inputSuccess2Status3">
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
                        <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-5">
                          <button type="submit" class="btn btn-primary" name="payroll_period" id="toggle">Submit</button>
                        </div>
                      </div>
                    </form>

                  </div>
                </div>
              </div>
              <!-- Details -->
              <div class="col-md-6 col-sm-6 col-xs-12" width="">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Details <small></small></h2>
                    
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <form class="form-horizontal form-label-left input_mask">
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Working Days</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input class="form-control col-md-7 col-xs-12" type="text" readonly="readonly" value="<?php echo $period_workingdays ?>" />
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Basic Salary</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input class="form-control col-md-7 col-xs-12" type="text" readonly="readonly" value="<?php echo $_SESSION['basic_salary'] ?>"/>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Daily Rate</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input class="form-control col-md-7 col-xs-12" type="text" readonly="readonly" value="<?php echo $daily_rate; ?>" />
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Hourly Rate</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <?php 
                            $hourly_rate = $daily_rate / 8; //8-working hours
                          ?>
                          <input class="form-control col-md-7 col-xs-12" readonly="readonly" type="text" value="<?php echo $hourly_rate; ?>" />
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            <div class="clearfix"></div>
            </div>
            
              <!-- Logbook -->
              <div class="row">
                <div class="clearfix"></div>
                <div class="col-md-12 col-sm-12 col-xs-12" width="708px">
                  <div class="x_panel">
                    <div class="x_title">
                      <h2>Logbook <small></small></h2>
                     
                      <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                      <div class="table-responsive">
                        <!-- Variables for computation -->
                        <?php 
                          $total_late = 0;
                          $total_hours = 0;
                          $total_days=0;
                        ?>
                        <table id="datatable" class="table table-striped jambo_table bulk_action">
                          <thead>
                            <tr class="headings">
                              <th class="column-title">Date</th>
                              <th class="column-title">Day</th>
                              <th class="column-title">Log-In</th>
                              <th class="column-title">MBS</th>
                              <th class="column-title">MBE</th>
                              <th class="column-title">LBS</th>
                              <th class="column-title">LBE</th>
                              <th class="column-title">ABS</th>
                              <th class="column-title">ABE</th>
                              <th class="column-title">Log-Out</th>
                              <th class="column-title">Late</th>
                              <th class="column-title">Work Hours</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                              if(isset($_POST['payroll_period'])){
                                $period_start = $_POST['period_start'];
                                $period_end = $_POST['period_end'];

                                $logbook_query = "SELECT * FROM logbook WHERE log_date between '$period_start' AND '$period_end' AND employee_id='".$_SESSION['employee_id']."'";
                                $stmt = $db->prepare($logbook_query);
                                $stmt->execute();
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                ?>
                                  <tr>
                                    <td><?php echo $row["log_date"]; ?></td>
                                    <td><?php echo $row["log_day"]; ?></td>
                                    <td><?php echo $row["in1"]; ?></td>
                                    <td><?php echo $row["break1_start"]; ?></td>
                                    <td><?php echo $row["break1_end"]; ?></td>
                                    <td><?php echo $row["out1"]; ?></td>
                                    <td><?php echo $row["in2"]; ?></td>
                                    <td><?php echo $row["break2_start"]; ?></td>
                                    <td><?php echo $row["break2_end"]; ?></td>
                                    <td><?php echo $row["out2"]; ?></td>
                                    <td><?php echo $row["total_late"]; ?></td>
                                    <td><?php echo $row["total_hours"]; ?></td>

                                    <!-- Compute total hours -->
                                    <?php 
                                      $total_late = $row["total_late"] + $total_late;
                                      $total_hours = $row["total_hours"] + $total_hours;
                                      $total_days++;
                                    ?>
                                  </tr>
                                <?php
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
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Payroll Computation</h2>
                    
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <div class="row">
                      <div class="col-md-6 col-sm-6 col-xs-6">
                        <table class="table table-bordered">
                          <tbody>
                            <tr>
                              <th scope="row">Late</th>
                              <td><?php echo $total_late = $total_late/60; ?></td>
                            </tr>
                            <tr>
                              <th scope="row">Total Work Hours</th>
                              <td><?php echo $total_hours; ?></td>
                            </tr>
                            <tr>
                              <th scope="row">Total # Days Covered</th>
                              <td><?php echo $total_days; ?></td>
                            </tr>
                            <tr>
                              <th scope="row">Input: Basic Pay</th>
                              <td><?php echo $_SESSION['basic_salary']; ?></td>
                            </tr>
                            <?php
                              //Query Allowances
                              $allowance_query = "SELECT * FROM allowances WHERE employee_id='".$_SESSION['employee_id']."'";
                              $stmt = $db->prepare($allowance_query);
                              $stmt->execute();
                              $row = $stmt->fetch(PDO::FETCH_ASSOC);
                              $rice_allowance = $row['rice_allowance'];
                              $meal_allowance = $row['meal_allowance'];
                              $lodging_allowance = $row['lodging_allowance'];
                              $transpo_allowance = $row['transpo_allowance'];
                            ?>
                            <tr>
                              <th scope="row">Input: Meal Allowance</th>
                              <td><?php echo $meal_allowance; ?></td>
                            </tr>
                            <tr>
                              <th scope="row">Input: Transpo Allowance</th>
                              <td><?php echo $transpo_allowance; ?></td>
                            </tr>
                            <tr>
                              <th scope="row">Input: Rice Allowance</th>
                              <td><?php echo $rice_allowance; ?></td>
                            </tr>
                            <tr>
                              <th scope="row">Input: Lodging Allowance</th>
                              <td><?php echo $lodging_allowance; ?></td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                      <div class="col-md-6 col-sm-6 col-xs-6">
                        <table class="table table-bordered">
                          <tbody><tr>
                              <th scope="row">DAILY RATE</th>
                              <td><?php echo $daily_rate; ?></td>
                            </tr>
                            <tr>
                              <th scope="row">HOURLY RATE</th>
                              <td><?php echo $hourly_rate; ?></td>
                            </tr>
                            <tr>
                              <th scope="row">BASIC SALARY</th>
                              <td><?php echo $basic_salary = $hourly_rate * $total_hours; ?></td>
                            </tr>
                            <?php
                              if (!empty($total_days)){
                                ?>
                                <tr>
                                  <th scope="row">Meal Allowance</th>
                                  <td><?php echo $meal = ($meal_allowance / $work_days / 8) * $total_hours; ?></td>
                                </tr>
                                <tr>
                                  <th scope="row">Transpo Allowance</th>
                                  <td><?php echo $transpo = ($transpo_allowance / $work_days / 8) * $total_hours; ?></td>
                                </tr>
                                <tr>
                                  <th scope="row">Rice Allowance</th>
                                  <td><?php echo $rice = ($rice_allowance / $work_days / 8) * $total_hours; ?></td>
                                </tr>
                                <tr>
                                  <th scope="row">Lodging Allowance</th>
                                  <td><?php echo $lodging = ($lodging_allowance / $work_days / 8) * $total_hours; ?></td>
                                </tr>
                                <?php
                              }
                              else if (empty($total_days)){
                                ?>
                                <tr>
                                  <th scope="row">Meal Allowance</th>
                                  <td>0</td>
                                </tr>
                                <tr>
                                  <th scope="row">Transpo Allowance</th>
                                  <td>0</td>
                                </tr>
                                <tr>
                                  <th scope="row">Rice Allowance</th>
                                  <td>0</td>
                                </tr>
                                <tr>
                                  <th scope="row">Lodging Allowance</th>
                                  <td>0</td>
                                </tr>
                                <?php
                              }
                            ?>
                            <tr>
                              <th scope="row" rowspan="3">TOTAL</th>
                              <td><?php echo $total = $basic_salary + $meal + $transpo + $rice + $lodging ?></td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div><br>
                    <div class="row">
                      <div class="col-xs-6">
                        <p class="lead">ESTIMATED TOTAL</p>
                        <div class="table-responsive">
                          <table class="table">
                            <tbody>
                              <tr>
                                <th style="width:50%">Total Gross Pay</th>
                                <td><?php echo $total; ?></td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
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