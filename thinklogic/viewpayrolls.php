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
  <body class="nav-md print_body" onload="startTime()">
    <div class="container body">
      <div class="main_container">
        <?php
          include("includes/body-nav.html");
        ?>
        <!-- page content -->
        
        <div class=" col-md-12 col-sm-12 col-xs-12 hidden" width="708px">
          <div class="x_content" id='content' >
            <div class="row" style="padding-bottom: 0px;">
              <?php 

                
                $stmt = $db->prepare("SELECT p.*, e.*, b.*,em.* FROM payslip as p
                                      INNER JOIN employees as e
                                          on p.employee_id = e.employee_id
                                      INNER JOIN benefits as b
                                          on b.employee_id = p.employee_id
                                      INNER JOIN employment as em
                                          on em.employee_id = p.employee_id
                                      ");
                $stmt->execute();
                while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
              ?>
              <div class="col-md-6">
              <table class="table table-bordered" >
                
                <tbody>
                  <tr>
                      <th colspan="4"><center>THINKLOGIC MKTG. CORP.</center></th>
                    </tr>
                    <tr>
                      <th colspan="4"><center><?php echo date('F d, Y', strtotime($row['period_start'])) . ' - ' . date('F d, Y', strtotime($row['period_end'])); ?></center></th>
                    </tr>
                    <br><br>
                    <tr>
                      <th colspan="4"></th>
                    </tr>
                    <tr>
                      <th colspan="1">Employee Name</th>
                      <td colspan="3"><?php echo $row['first_name'] . " " . $row['last_name']  ?></td>
                    </tr>
                    <tr>
                      <th colspan="1">Position</th>
                      <td colspan="3"><?php echo $row['job_title']; ?></td>
                    </tr>
                    <tr>
                      <th colspan="1">TIN</th>
                      <td colspan="3"><?php echo $row['tin_no']; ?></td>
                    </tr>
                    <tr>
                      <th colspan="1">SSS</th>
                      <td colspan="3"><?php echo $row['sss_no']; ?></td>
                    </tr>
                  <tr>
                    <th colspan="4"></th>
                  </tr>
                  <!-- Gross & Deductions -->
                  <tr>
                    <th colspan="2"><center>GROSS</center></th>
                    <th colspan="2"><center>DEDUCTIONS</center></th>
                  </tr>
                  <tr>
                    <th scope="row">Basic Salary</th>
                    <td><?php echo $row['basic_salary']; ?></td>
                    <th scope="row">Late</th>
                    <td><?php echo $row['late']; ?></td>
                  </tr>
                  <tr>
                    <th scope="row">Overtime</th>
                    <td><?php echo $row['overtime'];?></td>
                    <th scope="row">Pag-Ibig</th>
                    <td><?php echo $row['pagibig'];?></td>
                  </tr>
                  <tr>
                    <th scope="row">Regular Holiday</th>
                    <td><?php echo $row['regular_holiday'];?></td>
                    <th scope="row">SSS</th>
                    <td><?php echo $row['sss'];?></td>
                  </tr>
                  <tr>
                    <th scope="row">Special Holiday</th>
                    <td><?php echo $row['special_holiday'];?></td>
                    <th scope="row">PHIC</th>
                    <td><?php echo $row['phic'];?></td>
                  </tr>
                  <tr>
                    <th scope="row">Incentives</th>
                    <td><?php echo $row['incentives'];?></td>
                    <th scope="row">Withholding Tax</th>
                    <td><?php echo $row['witholding_tax'];?></td>
                  </tr>
                  <tr>
                    <th scope="row">Daily Rate</th>
                    <td><?php echo $row['daily_rate'];?></td>
                    <th scope="row">Others</th>
                    <td><?php echo $row['others'];?></td>
                  </tr>
                  <!-- End Gross & Deductions -->
                  <tr>
                    <th colspan="4"></th>
                  </tr>
                  <!-- Leave Credits & Loans -->
                  <tr>
                    <th colspan="2"><center>LEAVE CREDITS</center></th>
                    <th colspan="2"><center>LOANS</center></th>
                  </tr>
                  <tr>
                    <th scope="row">Vacation Leave</th>
                    <td><?php echo $row['vacation_leave'];?></td>
                    <th scope="row">Pag-Ibig</th>
                    <td><?php echo $row['pagibig_loan'];?></td>
                  </tr>
                  <tr>
                    <th scope="row">Sick Leave</th>
                    <td><?php echo $row['sick_leave'];?></td>
                    <th scope="row">SSS</th>
                    <td><?php echo $row['sss_loan'];?></td>
                  </tr>
                  <tr>
                    <th scope="row">Maternity Leave</th>
                    <td><?php echo $row['maternity_leave'];?></td>
                    <th scope="row">Cash Advances</th>
                    <td><?php echo $row['cash_advance'];?></td>
                  </tr>
                  <tr>
                    <th scope="row">Paternity Leave</th>
                    <td><?php echo $row['paternity_leave'];?></td>
                    <td rowspan="8" colspan="2"></td>
                  </tr>
                  <!-- End Leave Credits & Loans -->
                  <tr>
                    <th colspan="2"></th>
                  </tr>
                  <!-- Allowances -->
                  <tr>
                    <th colspan="2"><center>ALLOWANCES</center></th>
                  </tr>
                  <tr>
                    <th scope="row">Rice</th>
                    <td><?php echo $row['rice'];?></td>
                  </tr>
                  <tr>
                    <th scope="row">Meal</th>
                    <td><?php echo $row['meal'];?></td>
                  </tr>
                  <tr>
                    <th scope="row">Lodging</th>
                    <td><?php echo $row['lodging'];?></td>
                  </tr>
                  <tr>
                    <th scope="row">Transportation</th>
                    <td><?php echo $row['transpo'];?></td>
                  </tr>
                  <!-- End Allowances -->
                  <tr>
                    <th colspan="4"></th>
                  </tr>
                  <!-- Bottom Part -->
                  <tr>
                    <th scope="row">Total Gross Pay</th>
                    <td><?php echo $row['total_gross_pay'];?></td>
                    <th scope="row">Total Hours Work</th>
                    <td><?php echo $row['hours_work'];?></td>
                  </tr>
                  <tr>
                    <td colspan="2" rowspan="4"></td>
                    <th scope="row">Total Deductions</th>
                    <td><?php echo $row['total_deductions'];?></td>
                  </tr>
                  <tr>
                    <th scope="row">GROSS PAY</th>
                    <td><?php echo $row['total_gross_pay'];?></td>
                  </tr>
                  <tr>
                    <th scope="row">DEDUCTIONS</th>
                    <td><?php echo $row['total_deductions'];?></td>
                  </tr>
                  <tr>
                    <th scope="row">NET PAY</th>
                    <td>₱ <?php echo $row['net_pay'];?></td>
                  </tr>
                </tbody>
              </table>
              </div>
              <?php 
                }
              ?>
            </div>  
          </div>
        </div>

        <div class="right_col" role="main">
            <div class="page-title">
              <div class="title_left">
                <h3>Payroll</h3>
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
            <button class="btn btn-primary" onclick="printContent('content')" id="print-btn"><i class="fa fa-print" ></i> Print All</button>

            <!-- Employee Masterlist -->
            <div class="row">
              <div class="clearfix"></div>
              <div class="col-md-12 col-sm-12 col-xs-12" width="708px">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Payroll</h2>
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
                    <div class="table-responsive">
                      <table id="datatable" class="table table-striped jambo_table bulk_action">
                        <thead>
                          <tr class="headings">
                            <th class="column-title">Action</th>
                            <th class="column-title">ID</th>
                            <th class="column-title">Period Start</th>
                            <th class="column-title">Period End</th>
                            <th class="column-title">Employee Name</th>
                            <th class="column-title">TOTAL GROSS</th>
                            <th class="column-title">TOTAL DEDUCTIONS</th>
                            <th class="column-title">NET PAY</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                            $stmt = $db->prepare("SELECT * FROM payslip JOIN employees ON payslip.employee_id=employees.employee_id");
                            $stmt->execute();
                            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                          ?>
                            <tr>
                              <!-- <td class="a-center "><input type="checkbox" class="flat" name="table_records"></td> -->
                              <td class="last"><a href="payslip.php?view=<?php echo $row['payslip_id']; ?>" ><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></td>
                              <td><?php echo $row['payslip_id']; ?></td>
                              <td><?php echo $row['period_start']; ?></td>
                              <td><?php echo $row['period_end']; ?></td>
                              <td><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></td>
                              <td><?php echo $row['total_gross_pay']; ?></td>
                              <td><?php echo $row['total_deductions']; ?></td>
                              <td><?php echo $row['total_gross_pay'] - $row['total_deductions']; ?></td>
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
</html><!--  -->k