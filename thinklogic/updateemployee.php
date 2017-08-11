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
                <h3>Employees</h3>
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

            <!-- Employee Masterlist -->
            <div class="row">
              <div class="clearfix"></div>
              <div class="col-md-12 col-sm-12 col-xs-12" width="708px">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Employee Masterlist</h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li><a href=""><i class="fa fa-plus"></i></a>
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
                            <th class="column-title no-link last">Action</th>
                            <th class="column-title">Employee ID</th>
                            <th class="column-title">First Name</th>
                            <th class="column-title">Middle Name</th>
                            <th class="column-title">Last Name</th>
                            <th class="column-title">Gender</th>
                            <th class="column-title">Civil Status</th>
                            <th class="column-title">Birthdate</th>
                            <th class="column-title">Age</th>
                            <th class="column-title">Address</th>
                            <th class="column-title">Contact No.</th>
                          </tr>
                        </thead>
                        <tbody>
                                <tr>
                                  <td><?php echo $row["employee_id"]; ?></td>
                                  <td><?php echo $row["first_name"]; ?></td>
                                  <td><?php echo $row["middle_name"]; ?></td>
                                  <td><?php echo $row["last_name"]; ?></td>
                                  <td><?php echo $row["gender"]; ?></td>
                                  <td><?php echo $row["civil_status"]; ?></td>
                                  <td><?php echo $row["birth_date"]; ?></td>
                                  <td><?php echo $row["age"]; ?></td>
                                  <td><?php echo $row["address"]; ?></td>
                                  <td><?php echo $row["contact_no"]; ?></td>
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