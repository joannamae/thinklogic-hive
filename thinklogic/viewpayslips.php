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

            <!-- Payslip Masterlist -->
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
                            <th class="column-title">Payslip ID</th>
                            <th class="column-title">Payroll Period</th>
                            <th class="column-title">Employee Name</th>
                            <th class="column-title">TOTAL GROSS</th>
                            <th class="column-title">TOTAL DEDUCTIONS</th>
                            <th class="column-title">NET PAY</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                            $employee_id = $_SESSION['employee_id'];
                            $payslip_query = "SELECT * FROM payslip WHERE employee_id=:employee_id ORDER BY payslip_id DESC";
                            $stmt = $db->prepare($payslip_query);
                            $stmt->execute(array('employee_id'=>$employee_id));
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                            ?>
                              <tr>
                                <td class="last"><a href="payslip.php?view=<?php echo $row['payslip_id']; ?>" ><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></td>
                                <td><?php echo $row["payslip_id"]; ?></td>
                                <td><?php echo $row["period_start"] . " - " . $row["period_end"]; ?></td>
                                <td>
                                  <?php 
                                  echo $employee_id;
                                  /*
                                  $employee_query = "SELECT * FROM employees WHERE employee_id=:employee_id";
                                  $stmt1 = $db->prepare($employee_query);
                                  $stmt1->execute(array('employee_id'=>$employee_id));
                                  while($row = $stmt1->fetch(PDO::FETCH_ASSOC)){
                                    echo $row['first_name'] . " " . $row['last_name'];
                                  }
                                  */
                                  ?>
                                </td>
                                <td><?php echo $row["total_gross_pay"]; ?></td>
                                <td><?php echo $row["total_deductions"]; ?></td>
                                <td><?php echo $row["net_pay"]; ?></td>
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