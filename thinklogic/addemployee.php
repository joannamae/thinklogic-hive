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
                <h3>New Employee</h3>
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
            
            <?php 
              $employee_query = "SELECT * FROM employees ORDER BY employee_id DESC LIMIT 1";
              $stmt = $db->prepare($employee_query);
              $stmt->execute(array());
              $row = $stmt->fetch(PDO::FETCH_ASSOC);
              $employee_id = $row['employee_id'];
              $first_name = $row['first_name'];
              $middle_name = $row['middle_name'];
              $last_name = $row['last_name'];
              $gender = $row['gender'];
              $civil_status = $row['civil_status'];
              $birth_date = $row['birth_date'];
              $age = $row['age'];
              $contact_no = $row['contact_no'];
              $address = $row['address'];

            ?>

            <div class="clearfix"></div>
            <!-- Basic Information -->
            <div class="row">
              <div class="clearfix"></div>
              <div class="col-md-4 col-xs-12">
                <div class="x_panel">
                  <div class="x_content">
                    <br />
                    <img src="assets/images/default.jpg" alt="" class="img-circle profile_img">
                    <br><br>
                    <center>
                    <h3><?php echo $row['first_name'] . " " . $row['last_name']; ?></h3>
                    </center>
                  </div>
                </div>
              </div>
              <div class="col-md-8 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Basic Employee Information</h2>
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
                    <form data-parsley-validate class="form-horizontal form-label-left">
                      <div class="col-md-6">
                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-6 col-xs-12" for="first-name">Employee ID
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" required="required" class="form-control col-md-7 col-xs-12" readonly="readonly" value="<?php echo $row['employee_id'];?>" />
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="middle-name" class="control-label col-md-6 col-sm-6 col-xs-12">First Name</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input class="form-control col-md-7 col-xs-12" type="text" readonly="readonly" value="<?php echo 
                          $row['first_name']; ?>"   name="" />
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="middle-name" class="control-label col-md-6 col-sm-6 col-xs-12">Middle Name</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input class="form-control col-md-7 col-xs-12" type="text"  readonly="readonly" value="<?php echo $row['middle_name']; ?>"   name="" />
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="middle-name" class="control-label col-md-6 col-sm-6 col-xs-12">Last Name</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input class="form-control col-md-7 col-xs-12" type="text"  readonly="readonly" value="<?php echo $row['last_name']; ?>"   name="" />
                        </div>
                      </div>
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <label for="middle-name" class="control-label col-md-6 col-sm-6 col-xs-12">Gender</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input class="form-control col-md-7 col-xs-12" type="text"  readonly="readonly" value="<?php echo $row['gender']; ?>"   name="" />
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="middle-name" class="control-label col-md-6 col-sm-6 col-xs-12">Civil Status</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input class="form-control col-md-7 col-xs-12" type="text"  readonly="readonly" value="<?php echo $row['civil_status']; ?>"   name="" />
                        </div>
                      </div>
                      </div>
                      <div class="col-md-6">
                      <div class="form-group">
                        <label for="middle-name" class="control-label col-md-6 col-sm-6 col-xs-12">Birthdate</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input class="form-control col-md-7 col-xs-12" type="text"  readonly="readonly" value="<?php echo $row['birth_date']; ?>"   name="" />
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="middle-name" class="control-label col-md-6 col-sm-6 col-xs-12">Age</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                        <input class="form-control col-md-7 col-xs-12" type="text"  readonly="readonly" value="<?php echo $row['age']; ?>"   name="" />
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="middle-name" class="control-label col-md-6 col-sm-6 col-xs-12">Contact No.</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                        <input class="form-control col-md-7 col-xs-12" type="text"  readonly="readonly" value="<?php echo $row['contact_no']; ?>"   name="" />
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="middle-name" class="control-label col-md-6 col-sm-6 col-xs-12">Address</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                        <input class="form-control col-md-7 col-xs-12" type="text"  readonly="readonly" value="<?php echo $row['address']; ?>"   name="" />
                        </div>
                      </div>
                      </div>
                    </form>
                </div>
              </div>
            </div>
            </div>
            <div class="clearfix"></div>

            <?php
              if(isset($_POST['updateinformation'])){

                $Employee_ID = $_POST['employee_id'];
                //Employment
                $stmt = $db->prepare("UPDATE employment 
                                      SET status='Active', employment_status = :Employment_Status, job_title = :Job_Title, position = :Position, shift_schedule = :Shift_ID, email_address = :Email_Address, biometric_no = :Biometric_No, hiring_date = :Hiring_Date, basic_salary = :Basic_Salary
                                      WHERE employee_id = '$Employee_ID'");

                $stmt->bindParam(':Employment_Status', $Employment_Status);
                $stmt->bindParam(':Job_Title', $Job_Title);
                $stmt->bindParam(':Position', $Position);
                $stmt->bindParam(':Shift_ID', $Shift_ID);
                $stmt->bindParam(':Email_Address', $Email_Address);
                $stmt->bindParam(':Biometric_No', $Biometric_No);
                $stmt->bindParam(':Hiring_Date', $Hiring_Date);
                $stmt->bindParam(':Basic_Salary', $Basic_Salary);

                $Employment_Status = $_POST['employment_status'];
                $Job_Title = $_POST['job_title'];
                $Position = $_POST['position'];
                $Shift_ID = $_POST['shift_id'];
                $Email_Address = $_POST['email_address'];
                $Biometric_No = $_POST['biometric_no'];
                $Hiring_Date = $_POST['date_hired'];
                $Basic_Salary = $_POST['salary'];
               
                $stmt->execute();

                //Employee Group
                $stmt2 = $db->prepare("UPDATE employee_group SET team_name =:Team_Name WHERE employee_id = '$Employee_ID'");
                $stmt2->bindParam(':Team_Name', $Team_Name);
                $Employee_ID = $_POST['employee_id'];
                $Team_Name = $_POST['team'];
                $stmt2->execute();

                //Benefits
                $stmt3 = $db->prepare("UPDATE benefits SET tin_no =:TIN , sss_no=:SSS, philhealth_no=:Philhealth, pagibig_no=:Pagibig WHERE employee_id = '$Employee_ID'"); 
                $stmt3->bindParam(':TIN', $TIN);
                $stmt3->bindParam(':SSS', $SSS);
                $stmt3->bindParam(':Philhealth', $Philhealth);
                $stmt3->bindParam(':Pagibig', $Pagibig);
                $TIN = $_POST['tin_no'];
                $SSS = $_POST['sss_no'];
                $Philhealth = $_POST['philhealth_no'];
                $Pagibig = $_POST['pagibig_no'];
                $stmt3->execute();

                //Emergency Contact
                $stmt4 = $db->prepare("UPDATE emergency_contact SET contact_person=:Contact_Person, contact_number=:Contact_Number WHERE employee_id = '$Employee_ID'");
                $stmt4->bindParam(':Contact_Person', $Contact_Person);
                $stmt4->bindParam(':Contact_Number', $Contact_Number);
                $Contact_Person = $_POST['contact_person'];
                $Contact_Number = $_POST['contact_number'];
                $stmt4->execute();

                //Allowance
                $stmt5 = $db->prepare("UPDATE allowances SET rice_allowance=:Rice_Allowance, meal_allowance=:Meal_Allowance, lodging_allowance=:Lodging_Allowance, transpo_allowance=:Transpo_Allowance WHERE employee_id = '$Employee_ID'");
                $stmt5->bindParam(':Rice_Allowance', $Rice_Allowance);
                $stmt5->bindParam(':Meal_Allowance', $Meal_Allowance);
                $stmt5->bindParam(':Lodging_Allowance', $Lodging_Allowance);
                $stmt5->bindParam(':Transpo_Allowance', $Transpo_Allowance);
                $Rice_Allowance = $_POST['rice_allowance'];
                $Meal_Allowance = $_POST['meal_allowance'];
                $Lodging_Allowance = $_POST['lodging_allowance'];
                $Transpo_Allowance = $_POST['transpo_allowance'];
                $stmt5->execute();

                ?>
                <script>window.location.href="employees.php"</script>
                <?php
              }
            ?>

            <div class="row">
              <div class="clearfix"></div>
              <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Update Information</h2>
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
                    <form data-parsley-validate class="form-horizontal form-label-left" method="POST">
                      <ul class="list-unstyled timeline">
                        <li>
                          <div class="block">
                            <div class="tags">
                              <a href="" class="tag">
                                <span>Employment</span>
                              </a>
                            </div>
                            <div class="block_content">
                                <div class="form-group" style="display:none">
                                  <label class="control-label col-md-6 col-sm-6 col-xs-12" for="first-name">Employee ID
                                  </label>
                                  <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" required="required" class="form-control col-md-7 col-xs-12" readonly="readonly" value="<?php echo $row['employee_id'];?>" name="employee_id" />
                                  </div>
                                </div>
                                <div class="form-group">
                                <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Employment Status <span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                <select class="select2_single form-control" tabindex="-1" required name="employment_status">
                                <option disabled selected></option>
                                <option>Trainee</option>
                                <option>Probationary</option>
                                <option>Regular</option>
                                </select>
                                </div>
                                </div>
                                <div class="form-group">
                                <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Job Title <span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                <input class="form-control col-md-7 col-xs-12" type="text" name="job_title" />
                                </div>
                                </div>
                                <div class="form-group">
                                <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Position <span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                <select class="select2_single form-control" tabindex="-1" required name="position">
                                <option disabled selected></option>
                                <option>Employee</option>
                                <option>Supervisor</option>
                                <option>Manager</option>
                                </select>
                                </div>
                                </div>
                                <div class="form-group">
                                <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Shift Schedule <span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                <select class="select2_single form-control" tabindex="-1" required name="shift_id">
                                <option disabled selected></option>
                                <?php
                                  $shift_query = "SELECT * FROM shift_schedule";
                                  $stmt = $db->prepare($shift_query);
                                  $stmt->execute();
                                  while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
                                ?>
                                  <option value="<?php echo $row['shift_id']; ?>"><?php echo $row['in1'] . " - " . $row['out2']; ?></option>
                                <?php
                                  }
                                ?>
                                <!--
                                <?php
                                  $shift_query = "SELECT * FROM shift_schedule";
                                  $stmt = $db->prepare($shift_query);
                                  $stmt->execute();
                                  while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
                                    echo '<option value='.$row['shift_id'].'>'.$row['shift_start']. " - " .$row['shift_end'].'</option>';
                                  }
                                ?>
                                -->
                                </select>
                                </div>
                                </div>
                                <div class="form-group">
                                <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Email Address <span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                <input class="form-control col-md-7 col-xs-12" type="email" name="email_address" />
                                </div>
                                </div>
                                <div class="form-group">
                                <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Biometric No. <span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                <input class="form-control col-md-7 col-xs-12" type="number" name="biometric_no" required/>
                                </div>
                                </div>
                                <div class="form-group">
                                <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Date Hired <span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="xdisplay_inputx form-group has-feedback">
                                <input type="text" class="form-control has-feedback-left" id="single_cal2" aria-describedby="inputSuccess2Status" name="date_hired" required>
                                <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                <span id="inputSuccess2Status" class="sr-only">(success)</span>
                                </div>
                                </div>
                                </div>
                                <div class="form-group">
                                <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Basic Salary <span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                <input class="form-control col-md-7 col-xs-12" type="number" value="" name="salary" required/>
                                </div>
                                </div>
                            </div>
                          </div>
                        </li>
                        <li>
                          <div class="block">
                            <div class="tags">
                              <a href="" class="tag">
                                <span>Employee Group</span>
                              </a>
                            </div>
                            <div class="block_content">
                                <div class="form-group">
                                <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Team <span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                <select class="select2_single form-control" tabindex="-1" required name="team">
                                <option disabled selected></option>
                                <?php
                                  $emp_query = "SELECT * FROM team";
                                  $stmt = $db->prepare($emp_query);
                                  $stmt->execute();
                                  while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
                                    echo '<option>'.$row['team_name'].'</option>';
                                  }
                                ?>
                                </select>
                                </div>
                                </div>
                            </div>
                          </div>
                        </li>
                        <li>
                          <div class="block">
                            <div class="tags">
                              <a href="" class="tag">
                                <span>Emergency Contact</span>
                              </a>
                            </div>
                            <div class="block_content">
                                <div class="form-group">
                                  <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Contact Person <span class="required">*</span></label>
                                  <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input class="form-control col-md-7 col-xs-12" type="text" value="" name="contact_person" required/>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label class="control-label col-md-3 col-sm-3 col-xs-3">Contact Number <span class="required">*</span></label>
                                  <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" class="form-control" data-inputmask="'mask': '(9999)999-9999'" required name="contact_number"/>
                                    <span class="fa fa-user form-control-feedback right" aria-hidden="true"></span>
                                  </div>
                                </div>
                            </div>
                          </div>
                        </li>
                        <li>
                          <div class="block">
                            <div class="tags">
                              <a href="" class="tag">
                                <span>Benefits</span>
                              </a>
                            </div>
                            <div class="block_content">
                              <!-- Circle
                              <h2 class="title">
                                  <a></a>
                              </h2>
                              -->
                                <div class="form-group">
                                  <label class="control-label col-md-3 col-sm-3 col-xs-3">SSS No. <span class="required">*</span></label>
                                  <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" class="form-control" data-inputmask="'mask': '99999999-9'" required name="sss_no">
                                    <span class="fa fa-user form-control-feedback right" aria-hidden="true"></span>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label class="control-label col-md-3 col-sm-3 col-xs-3">TIN No. <span class="required">*</span></label>
                                  <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" class="form-control" data-inputmask="'mask': '999-999-999'" required name="tin_no">
                                    <span class="fa fa-user form-control-feedback right" aria-hidden="true"></span>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label class="control-label col-md-3 col-sm-3 col-xs-3">Philhealth No. <span class="required">*</span></label>
                                  <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" class="form-control" data-inputmask="'mask': '99-999999999-9'" required name="philhealth_no">
                                    <span class="fa fa-user form-control-feedback right" aria-hidden="true"></span>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label class="control-label col-md-3 col-sm-3 col-xs-3">Pag-Ibig No. <span class="required">*</span></label>
                                  <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" class="form-control" data-inputmask="'mask': '9999-9999-9999'" required name="pagibig_no">
                                    <span class="fa fa-user form-control-feedback right" aria-hidden="true"></span>
                                  </div>
                                </div>
                            </div>
                          </div>
                        </li>
                        <li>
                          <div class="block">
                            <div class="tags">
                              <a href="" class="tag">
                                <span>Allowances</span>
                              </a>
                            </div>
                            <div class="block_content">
                              <div class="form-group">
                                <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Rice Allowance <span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                  <input class="form-control col-md-7 col-xs-12" type="number" value="" name="rice_allowance" required/>
                                </div>
                              </div>
                              <div class="form-group">
                                <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Meal Allowance <span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                  <input class="form-control col-md-7 col-xs-12" type="number" value="" name="meal_allowance" required/>
                                </div>
                              </div>
                              <div class="form-group">
                                <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Lodging Allowance <span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                  <input class="form-control col-md-7 col-xs-12" type="number" value="" name="lodging_allowance" required/>
                                </div>
                              </div>
                              <div class="form-group">
                                <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Transportation Allowance <span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                  <input class="form-control col-md-7 col-xs-12" type="number" value="" name="transpo_allowance" required/>
                                </div>
                              </div>
                            </div>
                          </div>
                        </li>
                      </ul>
                      <br>
                        <center>
                            <input type="submit" value="Update Information" class="btn btn-primary" name="updateinformation" />
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