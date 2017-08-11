<?php
  include "config.php";
  session_start();
  if(!isset($_SESSION['username']))
  {
    header('location: login.php');
  }

  if(isset($_GET['edit_id'])) {
    // session data
    $period_start = $_SESSION['period_start'];
    $period_end = $_SESSION['period_end'];
    $month = $_SESSION['month'];
    $year = $_SESSION['year'];

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
    

    // employment query
    $employee_id = $_GET['edit_id'];
    $salary_query = $db->prepare("SELECT basic_salary FROM employment WHERE employee_id='$employee_id' LIMIT 1");
    $salary_query->execute();
    $salary_result = $salary_query->fetch(PDO::FETCH_ASSOC); 
    $basic_pay = $salary_result['basic_salary'];
    $daily_rate = round($basic_pay / $workDays, 2);

    //Query Log (Hours worked)
    $log_query = $db->prepare("SELECT * FROM logbook WHERE log_date between '$period_start' AND '$period_end' AND employee_id='$employee_id'");
    $log_query->execute();
    $hours_work = 0;
    $hours_late = 0;
    while ($row = $log_query->fetch(PDO::FETCH_ASSOC)){
      $hours_work += $row["total_hours"];
      $hours_late += $row["total_late"];
    }
    $basic_salary = round(($daily_rate / 8) * $hours_work, 2);
    $total_late = round(($hours_late/60), 2);

   /* Meal Allowance   (Allwnce/no. of wrkng days per mo./8 hrs)*total hrs. wrked)    
    Transpo Allowance  (Allwnce/no. of wrkng days per mo./8 hrs)*total hrs. wrked)    
    Rice Allowance   (Allwnce/no. of wrkng days per mo./8 hrs)*total hrs. wrked)    
    Lodging Allowance  (Allwnce/no. of wrkng days per mo./8 hrs)*total hrs. wrked) */   


    // allowance query
    $allowance_query = $db->prepare("SELECT * FROM allowances WHERE employee_id='$employee_id' LIMIT 1");
    $allowance_query->execute();
    $aq_result = $allowance_query->fetch(PDO::FETCH_ASSOC);
    $meal_allowance = round((($aq_result['meal_allowance']/ $workDays / 8) * $hours_work), 2);
    $transpo_allowance = round((($aq_result['transpo_allowance']/ $workDays / 8) * $hours_work), 2);
    $rice_allowance = round((($aq_result['rice_allowance']/ $workDays / 8) * $hours_work), 2);
    $lodging_allowance = round((($aq_result['lodging_allowance']/ $workDays / 8) * $hours_work), 2);
    $total_allowance = $meal_allowance + $transpo_allowance + $rice_allowance + $lodging_allowance;
  }

  if(isset($_POST['save'])) {

    $stmt = $db->prepare("INSERT INTO payslip (employee_id, period_start, period_end, hours_work, basic_salary, overtime, regular_holiday, special_holiday, incentives, daily_rate, vacation_leave, sick_leave, maternity_leave, paternity_leave, rice, meal, lodging, transpo, late, pagibig, sss, phic, witholding_tax, others, pagibig_loan, sss_loan, cash_advance, total_gross_pay, total_deductions, net_pay) VALUES (:employee_id, :period_start, :period_end, :hours_work, :basic_salary, :overtime, :regular_holiday, :special_holiday, :incentives, :daily_rate, :vacation_leave, :sick_leave, :maternity_leave, :paternity_leave, :rice, :meal, :lodging, :transpo, :late, :pagibig, :sss, :phic, :witholding_tax, :others, :pagibig_loan, :sss_loan, :cash_advance, :total_gross_pay, :total_deductions, :net_pay)");

    $stmt->bindParam(':employee_id', $_GET['edit_id']);
    $stmt->bindParam(':period_start', $_SESSION['period_start']);
    $stmt->bindParam(':period_end', $_SESSION['period_end']);
    $stmt->bindParam(':hours_work', $_POST['hours_work']);
    $stmt->bindParam(':basic_salary', $_POST['basic_salary']);
    $stmt->bindParam(':overtime', $_POST['overtime']);
    $stmt->bindParam(':regular_holiday', $_POST['regular_holiday']);
    $stmt->bindParam(':special_holiday', $_POST['special_holiday']);
    $stmt->bindParam(':daily_rate', $_POST['daily_rate']);
    $stmt->bindParam(':incentives', $_POST['incentives']);
    $stmt->bindParam(':vacation_leave', $_POST['vacation_leave']);
    $stmt->bindParam(':sick_leave', $_POST['sick_leave']);
    $stmt->bindParam(':maternity_leave', $_POST['maternity_leave']);
    $stmt->bindParam(':paternity_leave', $_POST['paternity_leave']);
    $stmt->bindParam(':rice', $_POST['rice']);
    $stmt->bindParam(':meal', $_POST['meal']);
    $stmt->bindParam(':lodging', $_POST['lodging']);
    $stmt->bindParam(':transpo', $_POST['transpo']);
    $stmt->bindParam(':late', $_POST['late']);
    $stmt->bindParam(':pagibig', $_POST['pagibig']);
    $stmt->bindParam(':sss', $_POST['sss']);
    $stmt->bindParam(':phic', $_POST['phic']);
    $stmt->bindParam(':witholding_tax', $_POST['witholding_tax']);
    $stmt->bindParam(':others', $_POST['others']);
    $stmt->bindParam(':pagibig_loan', $_POST['pagibig_loan']);
    $stmt->bindParam(':sss_loan', $_POST['sss_loan']);
    $stmt->bindParam(':cash_advance', $_POST['cash_advance']);
    $stmt->bindParam(':total_gross_pay', $_POST['total_gross_pay']);
    $stmt->bindParam(':total_deductions', $_POST['total_deductions']);
    $stmt->bindParam(':net_pay', $_POST['net_pay']);

    $stmt->execute();
    echo $_POST['total_gross_pay'];
    header("Location:payroll.php");
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
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Payroll Computation</h2>
                    
                    <div class="clearfix"></div>
                  </div>
                  
                  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
                  <script type="text/javascript">
                    $(document).on("change", ".qty1, .qty2", function() {
                      var sum = 0;
                      var deductions = 0;
                      var result = 0;
                      $(".qty1").each(function(){
                          sum += +$(this).val();
                      });
                      $("td.total_gross_pay").text(sum);
                      $(".total_gross_pay").val(Number(Math.round((sum)+'e2')+'e-2').toFixed(2)); 
                      

                      $(".qty2").each(function(){
                          deductions += +$(this).val();
                      });
                      $("td.total_deductions").text(deductions);
                      $(".total_deductions").val(Number(Math.round((deductions)+'e2')+'e-2').toFixed(2));  

                      result = Number(Math.round((sum - deductions)+'e2')+'e-2').toFixed(2);
                       $("td.net_pay").text(result);
                       $(".net_pay").val(result);               
                    });
                  </script>
                  <div class="x_content">
                    <br>
                    <form method="POST">
                      <div class="row">
                        <div class="col-xs-6">
                          <h2>Gross</h2>
                          <br>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Basic Salary</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                              <input class="form-control col-md-7 col-xs-12 qty1" type="text" readonly="readonly" value="<?php echo $basic_salary;?>" name='basic_salary'/>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Overtime</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                              <input class="form-control col-md-7 col-xs-12 qty1" type="text" value="" name='overtime' />
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Regular Holiday</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                              <input class="form-control col-md-7 col-xs-12 qty1" type="text" value="" name='regular_holiday' />
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Special Holiday</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                              <input class="form-control col-md-7 col-xs-12 qty1" type="text" value="" name="special_holiday"/>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Incentives</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                              <input class="form-control col-md-7 col-xs-12 qty1" type="text" value="" name="incentives" />
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Daily Rate</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                              <input class="form-control col-md-7 col-xs-12" type="text" readonly="readonly" value="<?php echo $daily_rate; ?>" name="daily_rate"/>
                            </div>
                          </div>
                        </div>
                        <div class="col-xs-6">
                          <h2>Deductions</h2>
                          <br>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Late</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                              <input class="form-control col-md-7 col-xs-12" type="text" readonly="readonly" value="<?php echo $total_late; ?>" name="late"/>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Pag-Ibig</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                              <input class="form-control col-md-7 col-xs-12 qty2" type="text" value="" name="pagibig"/>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">SSS</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                              <input class="form-control col-md-7 col-xs-12 qty2" type="text" value="" name="sss"/>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">PHIC</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                              <input class="form-control col-md-7 col-xs-12 qty2" type="text" value="" name="phic"/>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Withholding Tax</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                              <input class="form-control col-md-7 col-xs-12 qty2" type="text" value="" name="witholding_tax"/>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Others</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                              <input class="form-control col-md-7 col-xs-12 qty2" type="text" value="" name="others" />
                            </div>
                          </div>
                        </div>
                      </div>
                      <br>
                      <div class="row">
                        <div class="col-xs-6">
                          <h2>Leave Credits</h2>
                          <br>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Vacation Leave</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                              <input class="form-control col-md-7 col-xs-12 qty1" type="text" value="" name="vacation_leave" />
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Sick Leave</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                              <input class="form-control col-md-7 col-xs-12 qty1" type="text" value="" name="sick_leave" />
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Maternity Leave</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                              <input class="form-control col-md-7 col-xs-12 qty1" type="text" value="" name="maternity_leave" />
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Paternity Leave</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                              <input class="form-control col-md-7 col-xs-12 qty1" type="text" value="" name="paternity_leave" />
                            </div>
                          </div>
                        </div>
                        <div class="col-xs-6">
                          <h2>Loans</h2>
                          <br>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Pag-Ibig</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                              <input class="form-control col-md-7 col-xs-12 qty2" type="text" value="" name="pagibig_loan"/>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">SSS</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                              <input class="form-control col-md-7 col-xs-12 qty2" type="text" value="" name="sss_loan"/>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Cash Advances</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                              <input class="form-control col-md-7 col-xs-12 qty2" type="text" value="" name="cash_advance" />
                            </div>
                          </div>
                        </div>
                      </div>
                      <br>
                      <div class="row">
                        <div class="col-xs-6">
                          <h2>Allowances</h2>
                          <br>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Rice</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                              <input class="form-control col-md-7 col-xs-12 qty1" type="text" value="<?php echo $rice_allowance; ?>" name="rice" readonly/>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Meal</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                              <input class="form-control col-md-7 col-xs-12 qty1" type="text" value="<?php echo $meal_allowance; ?>" name="meal" readonly />
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Lodging</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                              <input class="form-control col-md-7 col-xs-12 qty1" type="text" value="<?php echo $lodging_allowance; ?>" name="lodging" readonly />
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Transpo</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                              <input class="form-control col-md-7 col-xs-12 qty1" type="text" value="<?php echo $transpo_allowance; ?>" name="transpo" readonly />
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Total</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                              <input class="form-control col-md-7 col-xs-12" type="text" value="<?php echo $total_allowance; ?>" readonly/>
                            </div>
                          </div>
                        </div>
                      </div>
                      <br><br><br>
                      <div class="row">
                        <div class="col-xs-6">
                          <div class="table-responsive">
                            <table class="table">
                              <tbody>
                                <tr>
                                  <th style="width:50%">Total Gross Pay: </th>
                                  <td class="total_gross_pay"><?php echo $total_allowance + $basic_salary; ?></td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                        </div>
                        <div class="col-xs-6">
                          <div class="table-responsive">
                            <table class="table">
                              <tbody>
                                <tr>
                                  <th style="width:50%">Total Hours Work</th>
                                  <td><?php echo $hours_work;?></td>
                                </tr>
                                <tr>
                                  <th style="width:50%">Total Deductions</th>
                                  <td class="total_deductions"></td>
                                </tr>
                                <tr>
                                  <th style="width:50%">Gross Pay</th>
                                  <td class='total_gross_pay' name="total_gross_pay"><?php echo $total_allowance + $basic_salary; ?></td>
                                </tr>
                                <tr>
                                  <th style="width:50%">Deductions</th>
                                  <td class='total_deductions'></td>
                                </tr>
                                <tr>
                                  <th style="width:50%">NET PAY</th>
                                  <td class="net_pay"><?php echo round((($total_allowance + $basic_salary) - $total_late), 2);?></td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                        </div> 
                      </div>
                      <input type="hidden" name="hours_work" class="hours_work" value="<?php echo $hours_work; ?>"/>
                      <input type="hidden" name="total_deductions" class="total_deductions" value="<?php echo $total_late; ?>"/>
                      <input type="hidden" class="total_gross_pay" name="total_gross_pay" value="<?php echo $total_gross_pay = $total_allowance + $basic_salary; ?>"/>
                      <input type="hidden" name="net_pay" class="net_pay" value="<?php echo round((($total_allowance + $basic_salary) - $total_late), 2);?>"/>
                      <center>
                        <a href="payroll.php" class='btn btn-default'>Cancel</a>
                        <input type="submit" name="save" class='btn btn-primary' value='Save'>
                      </center>
                    </form>
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