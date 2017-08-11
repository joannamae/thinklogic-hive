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
                <h3>User Profile</h3>
              </div>

              <div class="title_right">
                
              </div>
            </div>
            
            <div class="clearfix"></div>
            <!-- Ticket Tables -->
            <div class="row">
              <div class="clearfix"></div>
              <div class="col-md-4 col-xs-12">
                <div class="x_panel">
                  <div class="x_content">
                    <br />
                    <img src="<?php echo $_SESSION['employee_picture']; ?>" alt="" class="img-circle profile_img">
                    <br><br>
                    <center>
                    <h3><?php echo $_SESSION['first_name'] . " " . $_SESSION['last_name'] ?></h3>
                    </center>
                  </div>
                </div>
              </div>
              <div class="col-md-8 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    
                    <div class="clearfix"></div>
                  </div>

                  <div class="x_content">

                    <div>
                      <!-- Nav tabs -->
                      <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#basic" aria-controls="home" role="tab" data-toggle="tab">Basic Information</a></li>
                        <li role="presentation"><a href="#employment" aria-controls="profile" role="tab" data-toggle="tab">Employment</a></li>
                        <li role="presentation"><a href="#empgroup" aria-controls="profile" role="tab" data-toggle="tab">Employee Group</a></li>
                        <li role="presentation"><a href="#benefits" aria-controls="messages" role="tab" data-toggle="tab">Benefits</a></li>
                      </ul>
                      <!-- Tab panes -->
                      <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade in active" id="basic">
                          <br>
                          <form data-parsley-validate class="form-horizontal form-label-left">
                            <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Employee ID
                              </label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" required="required" class="form-control col-md-7 col-xs-12" readonly="readonly" value="<?php echo $_SESSION['employee_id'];?>" />
                              </div>
                            </div>
                            <div class="form-group">
                              <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">First Name</label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <input class="form-control col-md-7 col-xs-12" type="text" readonly="readonly" value="<?php echo $_SESSION['first_name']; ?>"   name="leave_employee" />
                              </div>
                            </div>
                            <div class="form-group">
                              <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Middle Name</label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <input class="form-control col-md-7 col-xs-12" type="text"  readonly="readonly" value="<?php echo $_SESSION['middle_name']; ?>"   name="leave_department" />
                              </div>
                            </div>
                            <div class="form-group">
                              <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Last Name</label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <input class="form-control col-md-7 col-xs-12" type="text"  readonly="readonly" value="<?php echo $_SESSION['last_name']; ?>"   name="leave_department" />
                              </div>
                            </div>
                            <div class="ln_solid"></div>
                            <div class="form-group">
                              <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Gender</label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <input class="form-control col-md-7 col-xs-12" type="text"  readonly="readonly" value="<?php echo $_SESSION['gender']; ?>"   name="leave_department" />
                              </div>
                            </div>
                            <div class="form-group">
                              <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Civil Status</label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <input class="form-control col-md-7 col-xs-12" type="text"  readonly="readonly" value="<?php echo $_SESSION['civil_status']; ?>"   name="leave_department" />
                              </div>
                            </div>
                            <div class="form-group">
                              <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Birthdate</label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <input class="form-control col-md-7 col-xs-12" type="text"  readonly="readonly" value="<?php echo $_SESSION['birth_date']; ?>"   name="leave_department" />
                              </div>
                            </div>
                            <div class="form-group">
                              <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Age</label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <input class="form-control col-md-7 col-xs-12" type="text"  readonly="readonly" value="<?php echo $_SESSION['age']; ?>"   name="leave_department" />
                              </div>
                            </div>
                            <div class="form-group">
                              <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Contact No.</label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <input class="form-control col-md-7 col-xs-12" type="text"  readonly="readonly" value="<?php echo $_SESSION['contact_no']; ?>"   name="leave_department" />
                              </div>
                            </div>
                            <div class="form-group">
                              <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Address</label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <input class="form-control col-md-7 col-xs-12" type="text"  readonly="readonly" value="<?php echo $_SESSION['address']; ?>"   name="leave_department" />
                              </div>
                            </div>
                          </form>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="employment">
                          <br>
                          <?php 
                            $employment_query = "SELECT * FROM employment WHERE employee_id='".$_SESSION['employee_id']."'";
                            $stmt = $db->prepare($employment_query);
                            $stmt->execute();
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                              $employee_id = $row["employee_id"];
                              $employment_status = $row["employment_status"];
                              $job_title = $row["job_title"];
                              $position = $row["position"];
                              $email_address = $row["email_address"];
                              $biometric_no = $row["biometric_no"];
                              $hiring_date = $row["hiring_date"];
                              $basic_salary = $row["basic_salary"];
                              $bank = $row["bank"];
                              $bank_account = $row["bank_account"];
                            }
                          ?>
                          <form data-parsley-validate class="form-horizontal form-label-left">
                            <div class="form-group">
                            <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Employment Status</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input class="form-control col-md-7 col-xs-12" type="text" name="" value="<?php echo $employment_status; ?>" readonly />
                            </div>
                            </div>
                            <div class="form-group">
                            <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Job Title</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input class="form-control col-md-7 col-xs-12" type="text" name="" value="<?php echo $job_title; ?>" readonly />
                            </div>
                            </div>
                            <div class="form-group">
                            <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Position</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input class="form-control col-md-7 col-xs-12" type="text" name="" value="<?php echo $position; ?>" readonly />
                            </div>
                            </div>
                            <div class="form-group">
                            <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Email Address</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input class="form-control col-md-7 col-xs-12" type="email" name="" value="<?php echo $email_address; ?>" readonly />
                            </div>
                            </div>
                            <div class="form-group">
                            <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Biometric No.</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input class="form-control col-md-7 col-xs-12" type="number" name="" value="<?php echo $biometric_no; ?>" readonly />
                            </div>
                            </div>
                            <div class="form-group">
                            <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Date Hired</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input class="form-control col-md-7 col-xs-12" type="text" name="" value="<?php echo $hiring_date; ?>" readonly />
                            </div>
                            </div>
                            <div class="form-group">
                            <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Basic Salary</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input class="form-control col-md-7 col-xs-12" type="number" name="" value="<?php echo $basic_salary; ?>" readonly />
                            </div>
                            </div>
                            <div class="form-group">
                            <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Bank</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input class="form-control col-md-7 col-xs-12" type="text" name="" value="<?php echo $bank; ?>" readonly />
                            </div>
                            </div>
                            <div class="form-group">
                            <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Bank Account</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input class="form-control col-md-7 col-xs-12" type="number" name="" value="<?php echo $bank_account; ?>" readonly />
                            </div>
                            </div>
                          </form>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="empgroup">
                          <br>
                          <?php 
                            $empgroup_query = "SELECT * FROM employee_group WHERE employee_id='".$_SESSION['employee_id']."'";
                            $stmt = $db->prepare($empgroup_query);
                            $stmt->execute();
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                              $employee_id = $row["employee_id"];
                              $team_name = $row["team_name"];
                            }
                          ?>
                          <form data-parsley-validate class="form-horizontal form-label-left">
                            <div class="form-group">
                            <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Team</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input class="form-control col-md-7 col-xs-12" type="text" name="" value="<?php echo $team_name; ?>" readonly />
                            </div>
                            </div>
                          </form>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="benefits">
                          <br>
                          <?php 
                            $benefits_query = "SELECT * FROM benefits WHERE employee_id='".$_SESSION['employee_id']."'";
                            $stmt = $db->prepare($benefits_query);
                            $stmt->execute();
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                              $employee_id = $row["employee_id"];
                              $sss_no = $row["sss_no"];
                              $tin_no = $row["tin_no"];
                              $philhealth_no = $row["philhealth_no"];
                              $pagibig_no = $row["pagibig_no"];
                            }
                          ?>
                          <form data-parsley-validate class="form-horizontal form-label-left">
                            <div class="form-group">
                            <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">SSS No.</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input class="form-control col-md-7 col-xs-12" type="text" name="" value="<?php echo $sss_no; ?>" readonly />
                            </div>
                            </div>
                            <div class="form-group">
                            <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">TIN No.</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input class="form-control col-md-7 col-xs-12" type="text" name="" value="<?php echo $tin_no; ?>" readonly />
                            </div>
                            </div>
                            <div class="form-group">
                            <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Philhealth No.</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input class="form-control col-md-7 col-xs-12" type="text" name="" value="<?php echo $philhealth_no; ?>" readonly />
                            </div>
                            </div>
                            <div class="form-group">
                            <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Pag-Ibig No</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input class="form-control col-md-7 col-xs-12" type="email" name="" value="<?php echo $pagibig_no; ?>" readonly />
                            </div>
                            </div>
                          </form>
                        </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="clearfix"></div>
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