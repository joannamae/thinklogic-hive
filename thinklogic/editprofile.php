<!-- editprofile.php -->

<?php
  include "config.php";
  session_start();
  if(!isset($_SESSION['username']))
  {
    header('location: login.php');
  }

  // fetch edit data
  if(isset($_GET['edit']))
  {
    $employee_id = $_GET['edit'];

    // employee query
    $employee_query = "SELECT * FROM employees WHERE employee_id='". $employee_id ."'";
    $stmt1 = $db->prepare($employee_query);
    $stmt1->execute();
    while($row = $employee_data = $stmt1->fetch(PDO::FETCH_ASSOC))
    {
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
      
    }

    // employment query
    $employment_query = "SELECT * FROM employment WHERE employee_id='". $employee_id ."'";
    $stmt2 = $db->prepare($employment_query);
    $stmt2->execute();
    $employment_status ='';
    $position='';
    while($row = $stmt2->fetch(PDO::FETCH_ASSOC))
    {
      $employee_id = $row["employee_id"];
      $employment_status = $row["employment_status"];
      $job_title = $row["job_title"];
      $position = $row["position"];
      $shift_id = $row["shift_schedule"];
      $email_address = $row["email_address"];
      $biometric_no = $row["biometric_no"];
      $hiring_date = $row["hiring_date"];
      $basic_salary = $row["basic_salary"];
      $bank = $row["bank"];
      $bank_account = $row["bank_account"];
      $probationary_date = $row['probationary_date'];
      $regularization_date = $row['regularization_date'];
      $termination_date = $row["termination_date"];
    }

    // employee_group query
    $empgroup_query = "SELECT * FROM employee_group 
                        INNER JOIN team ON employee_group.team_id=team.team_id
                        WHERE employee_id='".$employee_id."'";
    $stmt3 = $db->prepare($empgroup_query);
    $stmt3->execute();
    $team = '';  
    while($row = $stmt3->fetch(PDO::FETCH_ASSOC))
    {
      $employee_id = $row["employee_id"];
      $team_id = $row["team_id"];
      $team = $row["team_name"];
    }

    // benefits query
    $benefits_query = "SELECT * FROM benefits WHERE employee_id='".$employee_id."'";
    $stmt4 = $db->prepare($benefits_query);
    $stmt4->execute();
    while($row = $stmt4->fetch(PDO::FETCH_ASSOC))
    {
      $sss_no = $row["sss_no"];
      $tin_no = $row["tin_no"];
      $philhealth_no = $row["philhealth_no"];
      $pagibig_no = $row["pagibig_no"];
    }

    // employee_blob query
    $blob_query = "SELECT * FROM employee_blob WHERE employee_id='".$employee_id."'";
    $stmt5 = $db->prepare($blob_query);
    $stmt5->execute();
    $picture = '';
    while($row = $stmt5->fetch(PDO::FETCH_ASSOC))
    {
      $picture = $row['employee_picture'];
    }

    // emergency contact query
    $emergency_query = "SELECT * FROM emergency_contact WHERE employee_id='".$employee_id."'";
    $stmt6 = $db->prepare($emergency_query);
    $stmt6->execute();
    while($row = $stmt6->fetch(PDO::FETCH_ASSOC))
    {
      $contact_person = $row["contact_person"];
      $contact_number = $row["contact_number"];
    }

    // allowances query
    $allowances_query = "SELECT * FROM allowances WHERE employee_id='".$employee_id."'";
    $stmt7 = $db->prepare($allowances_query);
    $stmt7->execute();
    while($row = $stmt7->fetch(PDO::FETCH_ASSOC))
    {
      $rice_allowance = $row["rice_allowance"];
      $meal_allowance = $row["meal_allowance"];
      $lodging_allowance = $row["rice_allowance"];
      $transpo_allowance = $row["transpo_allowance"];
    }

    // sick leave credits query
    $sick_credits_query = "SELECT * FROM leave_credits WHERE employee_id='".$employee_id."' AND leave_type='Sick'";
    $stmt8 = $db->prepare($sick_credits_query);
    $stmt8->execute();
    while($row = $stmt8->fetch(PDO::FETCH_ASSOC))
    {
      $sick_credits = $row["leave_credits"];
    }

    // vacation leave credits query
    $vacation_credits_query = "SELECT * FROM leave_credits WHERE employee_id='".$employee_id."' AND leave_type='Vacation'";
    $stmt9 = $db->prepare($vacation_credits_query);
    $stmt9->execute();
    while($row = $stmt9->fetch(PDO::FETCH_ASSOC))
    {
      $vacation_credits = $row["leave_credits"];
    }

    $emp_id = $employee_id;
  }
?>

<!DOCTYPE html>
<html lang="en">
  <?php
    include("includes/head.html");
  ?>
  <body class="nav-md">
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
                <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_content">
                    <br />

                    <img src="<?php if($picture != NULL ) echo $picture; else echo 'assets/images/default.jpg' ?>" alt="" class="img-circle profile_img">
                    <br><br>
                    <center>
                    <h3><?php echo $first_name . " " . $last_name ?></h3>
                    </center>
                  </div>
                </div>
                 </div>
                <div class="col-md-12 col-xs-12">
                  <div class="x_panel"> 
                  <?php
                  if(isset($_POST['upload']))
                  {
                    // move and rename 
                    function do_upload()
                    {
                      $type = explode('.', $_FILES["img"]["name"]);
                      $type = strtolower($type[count($type)-1]);
                      $cwd = str_replace('/','\\', getcwd()); 
                      $url = uniqid(rand()).'.'.$type;
                      if(in_array($type, array("jpg", "jpeg", "gif", "png")))
                        if(is_uploaded_file($_FILES["img"]["tmp_name"]))
                          if(move_uploaded_file($_FILES["img"]["tmp_name"],"assets/images/" .$url)){
                            $result = "assets/images/" . $url;
                            return $result;
                          }
                      return "";
                    }

                    $query = "SELECT * FROM employee_blob WHERE employee_id='$employee_id'";
                    $result = $db->query($query)->fetchAll();
                    
                    if(count($result) > 0){
                      $stmt = $db->prepare("UPDATE employee_blob SET employee_picture = ? WHERE employee_id = ?");
                      $stmt->bindParam(1,$url);
                      $stmt->bindParam(2,$employee_id);
                      $url = do_upload();
                      $stmt->execute();
                    } else {
                      $stmt = $db->prepare("INSERT INTO employee_blob (employee_id, employee_picture) VALUES (:emp_id,:image)");
                      $stmt->bindParam(':emp_id',$employee_id);
                      $stmt->bindParam(':image',$url);
                      $url = do_upload();
                      $stmt->execute();
                    }

                    if($employee_id == $_SESSION['employee_id']) 
                      $_SESSION['employee_picture'] = $url;
                   
                    ?>
                    <script>window.location.href="editprofile.php?edit=<?php echo $employee_id?>"</script>
                    <?php
                        }
                    ?> 
                  <br>
                  <center>
                    <form method="post" enctype="multipart/form-data">
                      <input type="file" name="img" style="display:inline;">
                      <input type="submit" name="upload" value="Upload">
                    </form>   
                  </center>
                  <br>
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
                        <li role="presentation" class="active"><a href="#basic" aria-controls="home" role="tab" data-toggle="tab">Basic</a></li>
                        <li role="presentation"><a href="#employment" aria-controls="profile" role="tab" data-toggle="tab">Employment</a></li>
                        <li role="presentation"><a href="#emergency" aria-controls="profile" role="tab" data-toggle="tab">Emergency</a></li>
                        <li role="presentation"><a href="#empgroup" aria-controls="profile" role="tab" data-toggle="tab">Team</a></li>
                        <li role="presentation"><a href="#benefits" aria-controls="messages" role="tab" data-toggle="tab">Benefits</a></li>
                        <li role="presentation"><a href="#allowances" aria-controls="messages" role="tab" data-toggle="tab">Allowances</a></li>
                        <li role="presentation"><a href="#leave" aria-controls="messages" role="tab" data-toggle="tab">Leave Credits</a></li>
                       </ul>
                      </div> 
                      <!-- Tab panes -->
                      <form class="form-horizontal form-label-left" method="POST">
                        <div class="tab-content">
                          <div role="tabpanel" class="tab-pane fade active in" id="basic">
                            <br>
                            <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Employee ID
                              </label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" required="required" class="form-control col-md-7 col-xs-12" readonly value="<?php echo $emp_id; ?>" name="employee_id" />
                              </div>
                            </div>
                            <div class="form-group">
                              <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">First Name</label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <input class="form-control col-md-7 col-xs-12" type="text" value="<?php echo $first_name; ?>"   name="first_name" />
                              </div>
                            </div>
                            <div class="form-group">
                              <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Middle Name</label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <input class="form-control col-md-7 col-xs-12" type="text"  value="<?php echo $middle_name ?>"   name="middle_name" />
                              </div>
                            </div>
                            <div class="form-group">
                              <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Last Name</label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <input class="form-control col-md-7 col-xs-12" type="text" value="<?php echo $last_name ?>"   name="last_name" />
                              </div>
                            </div>
                            <div class="ln_solid"></div>
                            <div class="form-group">
                              <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Gender</label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <select class="select2_single form-control" tabindex="-1" required name="gender">
                                <option <?php if($gender == 'Male') echo 'selected' ?>>Male</option>
                                <option <?php if($gender == 'Female') echo 'selected' ?>>Female</option>
                                </select>
                              </div>
                            </div>
                            <div class="form-group">
                              <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Civil Status</label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <select class="select2_single form-control" tabindex="-1" required name="civil_status">
                                <option <?php if($civil_status == 'Single') echo 'selected' ?>>Single</option>
                                <option <?php if($civil_status == 'Married') echo 'selected' ?>>Married</option>
                                <option <?php if($civil_status == 'Separated') echo 'selected' ?>>Separated</option>
                                <option <?php if($civil_status == 'Dviorced') echo 'selected' ?>>Divorced</option>
                                <option <?php if($civil_status == 'Widowed') echo 'selected' ?>>Widowed</option>
                                </select>
                              </div>
                            </div>
                            <div class="form-group">
                              <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Birthdate</label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="xdisplay_inputx form-group has-feedback">
                                <input type="text" class="form-control has-feedback-left" id="single_cal1" name="birth_date" required value="<?php echo $birth_date; ?>">
                                <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                <span id="inputSuccess2Status" class="sr-only">(success)</span>
                                </div>
                              </div>
                            </div>
                            <div class="form-group">
                              <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Age</label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <input class="form-control col-md-7 col-xs-12" type="text" value="<?php echo $age; ?>"   name="age" />
                              </div>
                            </div>
                            <div class="form-group">
                              <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Contact No.</label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <input class="form-control col-md-7 col-xs-12" type="text" value="<?php echo $contact_no; ?>"  data-inputmask="'mask' : '(9999) 999-9999'" name="contact_no" />
                              </div>
                            </div>
                            <div class="form-group">
                              <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Address</label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <input class="form-control col-md-7 col-xs-12" type="text" value="<?php echo $address; ?>"   name="address" />
                              </div>
                            </div>
                          </div>
                          <div role="tabpanel" class="tab-pane fade" id="employment">
                            <br>
                            <div class="form-group">
                            <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Employment Status </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                             <script type="text/javascript">
                              function CheckEmploymentStatus(val){
                               var element=document.getElementById('status');
                               var element1=document.getElementById('regularization_status');
                               var element2=document.getElementById('termination_status');
                               if(val=='Probationary')
                                 element.style.display='block';
                               else  
                                 element.style.display='none';

                               if(val=='Regular')
                                 element1.style.display='block';
                               else  
                                 element1.style.display='none';

                               if(val=='Terminated')
                                 element2.style.display='block';
                               else  
                                 element2.style.display='none';
                              }
                              </script> 
                            <select class="select2_single form-control" tabindex="-1" name="employment_status" onchange='CheckEmploymentStatus(this.value);'>
                            <option <?php if(!isset($employment_status)) echo 'selected ' ; ?> hidden value =''>None</option>
                            <option <?php if($employment_status == 'Trainee') echo 'selected'; ?>>Trainee</option>
                            <option <?php if($employment_status == 'Probationary') echo 'selected'; ?>>Probationary</option>
                            <option <?php if($employment_status == 'Regular') echo 'selected'; ?>>Regular</option>
                            <option <?php if($employment_status == 'Terminated') echo 'selected'; ?>>Terminated</option>
                            </select>
                            </div>
                            </div>
                            <div class="form-group">
                            <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Job Title</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input class="form-control col-md-7 col-xs-12" type="text" name="job_title" value="<?php if(!isset($job_title)) echo ''; else echo $job_title; ?>" />
                            </div>
                            </div>
                            <div class="form-group">
                            <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Position </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" tabindex="-1" required name="position">
                            <option <?php if(!isset($position)) echo 'selected' ; ?>  hidden value =''>None</option>
                            <option <?php if($position == 'Employee') echo 'selected';?>>Employee</option>
                            <option <?php if($position == 'Supervisor') echo 'selected';?>>Supervisor</option>
                            <option <?php if($position == 'Manager') echo 'selected';?>>Manager</option>
                            </select>
                            </div>
                            </div>
                            <div class="form-group">
                            <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Shift Schedule </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" tabindex="-1" required name="shift_id">
                            <?php if($team_name == NULL) echo "<option selected hidden value=''>Choose schedule</option>"; ?>
                            <?php
                              $shift_query = "SELECT * FROM shift_schedule";
                              $stmt = $db->prepare($shift_query);
                              $stmt->execute();
                              while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
                            ?>
                              <option value="<?php echo $row['shift_id'] ?>" <?php if($shift_id == $row['shift_id']) echo 'selected' ?>><?php echo $row['in1'] . " - " . $row['out2']; ?></option>
                            <?php
                              }
                            ?>
                            </select>
                            </div>
                            </div>
                            <div class="form-group">
                            <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Email Address</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input class="form-control col-md-7 col-xs-12" type="email" name="email_address" value="<?php if(!isset($email_address)) echo ''; else echo $email_address; ?>" />
                            </div>
                            </div>
                            <div class="form-group">
                            <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Biometric No.</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input class="form-control col-md-7 col-xs-12" type="number" name="biometric_no" value="<?php echo $biometric_no; ?>" />
                            </div>
                            </div>
                            <div class="form-group">
                              <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Hiring Date</label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="xdisplay_inputx form-group has-feedback">
                                <input type="text" class="form-control has-feedback-left" id="" readonly name="hiring_date" required value="<?php echo $hiring_date; ?>">
                                <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                <span id="inputSuccess2Status" class="sr-only">(success)</span>
                                </div>
                              </div>
                            </div>
                            <div class="form-group" <?php if($employment_status != 'Probationary' || !isset($employment_status )) echo 'style="display:none;"'; ?> id='status'>
                              <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Probationary Date</label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="xdisplay_inputx form-group has-feedback">
                                <input type="text" class="form-control has-feedback-left" id="single_cal3" name="probationary_date" required value="<?php if($probationary_date != NULL) echo $probationary_date;?>" >
                                <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                <span id="inputSuccess2Status" class="sr-only">(success)</span>
                                </div>
                              </div>
                            </div>
                            <div class="form-group" <?php if($employment_status != 'Regular' || $employment_status == NULL  ) echo 'style="display:none;"'; ?> id='regularization_status'>
                              <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Regularization Date</label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="xdisplay_inputx form-group has-feedback">
                                <input type="text" class="form-control has-feedback-left" id="single_cal4" name="regularization_date"  required value="<?php if($regularization_date != NULL) echo $regularization_date;?>" >
                                <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                <span id="inputSuccess2Status" class="sr-only">(success)</span>
                                </div>
                              </div>
                            </div>
                            <div class="form-group" <?php if($employment_status != 'Terminated' || $employment_status == NULL  ) echo 'style="display:none;"'; ?> id='termination_status'>
                              <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Termination Date</label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="xdisplay_inputx form-group has-feedback">
                                <input type="text" class="form-control has-feedback-left" id="single_cal2" name="termination_date"  required value="<?php if($termination_date != NULL) echo $termination_date;?>" >
                                <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                <span id="inputSuccess2Status" class="sr-only">(success)</span>
                                </div>
                              </div>
                            </div>
                            <div class="form-group">
                            <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Basic Salary</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input class="form-control col-md-7 col-xs-12" type="text" name="basic_salary" value="<?php if(!isset($basic_salary)) echo ''; else echo $basic_salary; ?>" />
                            </div>
                            </div>
                            <div class="form-group">
                            <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Bank</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input class="form-control col-md-7 col-xs-12" type="text" name="bank" value="<?php if(!isset($bank)) echo ''; else echo $bank; ?>" />
                            </div>
                            </div>
                            <div class="form-group">
                            <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Bank Account</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input class="form-control col-md-7 col-xs-12" type="text" name="bank_account" value="<?php if(!isset($bank_account)) echo ''; else echo $bank_account; ?>" />
                            </div>
                            </div>
                          </div>
                          <div role="tabpanel" class="tab-pane fade" id="emergency">
                            <br>
                            <div class="form-group">
                              <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Contact Person</label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <input class="form-control col-md-7 col-xs-12" type="text" value="<?php echo $contact_person; ?>" name="contact_person" />
                              </div>
                            </div>
                            <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-3">Contact Number</label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" class="form-control" name="contact_number" value="<?php echo $contact_number ?>"/>
                              </div>
                            </div>
                          </div>
                          <div role="tabpanel" class="tab-pane fade" id="empgroup">
                            <br>
                            <div class="form-group">
                            <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Team </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" tabindex="-1" required name="team_id">
                            <?php if($team == NULL) echo "<option selected hidden value=''>Choose team</option>"; ?>
                            <?php
                              $emp_query = "SELECT * FROM team";
                              $stmt = $db->prepare($emp_query);
                              $stmt->execute();
                              while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
                            ?>
                              <option value="<?php echo $row['team_id']; ?>" <?php if($team == $row['team_name']) echo 'selected';?>><?php echo $row['team_name']; ?></option>
                            <?php
                              }
                            ?>
                            </select>
                            </div>
                            </div>
                          </div>
                          <div role="tabpanel" class="tab-pane fade" id="benefits">
                            <br>
                            <div class="form-group">
                            <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">SSS No.</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input class="form-control col-md-7 col-xs-12" type="text" name="sss_no" data-inputmask="'mask': '99999999-9'" value="<?php if(!isset($sss_no)) echo ''; else echo $sss_no; ?>" />
                            </div>
                            </div>
                            <div class="form-group">
                            <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">TIN No.</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input class="form-control col-md-7 col-xs-12" type="text" name="tin_no" data-inputmask="'mask': '999-999-999'" value="<?php if(!isset($tin_no)) echo ''; else echo $tin_no; ?>" />
                            </div>
                            </div>
                            <div class="form-group">
                            <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Philhealth No.</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input class="form-control col-md-7 col-xs-12" type="text"  data-inputmask="'mask': '99-999999999-9'" name="philhealth_no" value="<?php if(!isset($philhealth_no)) echo ''; else echo $philhealth_no; ?>" />
                            </div>
                            </div>
                            <div class="form-group">
                            <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Pag-Ibig No</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input class="form-control col-md-7 col-xs-12" type="text"  data-inputmask="'mask': '9999-9999-9999'" name="pagibig_no" value="<?php if(!isset($pagibig_no)) echo ''; else echo $pagibig_no; ?>" />
                            </div>
                            </div>
                          </div>
                          <div role="tabpanel" class="tab-pane fade" id="allowances">
                            <br>
                            <div class="form-group">
                              <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Rice</label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <input class="form-control col-md-7 col-xs-12" type="number" value="<?php echo $rice_allowance; ?>" name="rice_allowance" />
                              </div>
                            </div>
                            <div class="form-group">
                              <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Meal </label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <input class="form-control col-md-7 col-xs-12" type="number" value="<?php echo $meal_allowance; ?>" name="meal_allowance" />
                              </div>
                            </div>
                            <div class="form-group">
                              <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Lodging</label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <input class="form-control col-md-7 col-xs-12" type="number" value="<?php echo $lodging_allowance; ?>" name="lodging_allowance" />
                              </div>
                            </div>
                            <div class="form-group">
                              <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Transportation </label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <input class="form-control col-md-7 col-xs-12" type="number" value="<?php echo $transpo_allowance; ?>" name="transpo_allowance" />
                              </div>
                            </div>
                          </div>
                          <div role="tabpanel" class="tab-pane fade" id="leave">
                            <br>
                            <div class="form-group">
                              <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Sick Leave</label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <input class="form-control col-md-7 col-xs-12" type="text" value="<?php echo $sick_credits; ?>" name="sick_credits" />
                              </div>
                            </div>
                            <div class="form-group">
                              <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Vacation Leave </label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <input class="form-control col-md-7 col-xs-12" type="text" value="<?php echo $vacation_credits; ?>" name="vacation_credits" />
                              </div>
                            </div>
                          </div>
                          <br>
                          <center>
                              <input type="submit" value="Update" class="btn btn-primary" name="update" />
                              <a href="employees.php" class="btn btn-default" >Cancel</a>
                          </center>
                        </div>
                      </form>
                  </div>
                </div>
              </div>
            </div>
            
            <?php 
                    // save update
                    if(isset($_POST['update']))
                    {
                      //employee data
                      $stmt_a = $db->prepare("UPDATE employees 
                                              SET first_name= ?, middle_name= ?,last_name= ?,gender= ?,civil_status=?,
                                              birth_date=?, age=?, contact_no=?, address=? 
                                                WHERE employee_id=?");
                      $stmt_a->bindParam(1, $_POST['first_name']);
                      $stmt_a->bindParam(2, $_POST['middle_name']);
                      $stmt_a->bindParam(3, $_POST['last_name']);
                      $stmt_a->bindParam(4, $_POST['gender']);
                      $stmt_a->bindParam(5, $_POST['civil_status']);
                      $stmt_a->bindParam(6, $_POST['birth_date']);
                      $stmt_a->bindParam(7, $_POST['age']);
                      $stmt_a->bindParam(8, $_POST['contact_no']);
                      $stmt_a->bindParam(9, $_POST['address']);
                      $stmt_a->bindParam(10, $_POST['employee_id']);
                      $stmt_a->execute();

                      // employment data
                      $stmt_b = $db->prepare("UPDATE employment SET employment_status=?, job_title=?, position=?, shift_schedule=?, email_address=?, biometric_no=?, hiring_date=?, basic_salary=?, bank=?, bank_account=? WHERE employee_id=?");  
                      $stmt_b->bindParam(1, $_POST['employment_status']);
                      $stmt_b->bindParam(2, $_POST['job_title']);
                      $stmt_b->bindParam(3, $_POST['position']);
                      $stmt_b->bindParam(4, $_POST['shift_id']);
                      $stmt_b->bindParam(5, $_POST['email_address']);
                      $stmt_b->bindParam(6, $_POST['biometric_no']);
                      $stmt_b->bindParam(7, $_POST['hiring_date']);
                      $stmt_b->bindParam(8, $_POST['basic_salary']);
                      $stmt_b->bindParam(9, $_POST['bank']);
                      $stmt_b->bindParam(10, $_POST['bank_account']);
                      $stmt_b->bindParam(11, $_POST['employee_id']);
                      $stmt_b->execute();

                      if($_POST['employment_status'] == 'Probationary') 
                      {
                        $probationary_date = $_POST['probationary_date'];
                        $stmt_x = $db->prepare("UPDATE employment SET probationary_date=? WHERE employee_id=?");
                        $stmt_x->bindParam(1, $probationary_date);
                        $stmt_x->bindParam(2, $_POST['employee_id']);
                        $stmt_x->execute();
                      } 
                      if($_POST['employment_status'] == 'Regular'){
                        $regularization_date = $_POST['regularization_date'];
                        $stmt_y = $db->prepare("UPDATE employment SET regularization_date=? WHERE employee_id=?");
                        $stmt_y->bindParam(1, $regularization_date);
                        $stmt_y->bindParam(2, $_POST['employee_id']);
                        $stmt_y->execute();
                      }
                      if($_POST['employment_status'] == 'Terminated'){
                        $termination_date = $_POST['termination_date'];
                        $stmt_z = $db->prepare("UPDATE employment SET status='Inactive', termination_date=? WHERE employee_id=?");
                        $stmt_z->bindParam(1, $termination_date);
                        $stmt_z->bindParam(2, $_POST['employee_id']);
                        $stmt_z->execute();
                      }

                      // employee group data
                      $stmt_c = $db->prepare("UPDATE employee_group SET team_id= ? WHERE employee_id=?");
                      $stmt_c->bindParam(1, $_POST['team_id']);
                      $stmt_c->bindParam(2, $_POST['employee_id']);
                      $stmt_c->execute();

                      // benefits data
                      $stmt_d = $db->prepare("UPDATE benefits SET sss_no= ?, tin_no= ?, philhealth_no=?, pagibig_no=? WHERE employee_id=?");
                      $stmt_d->bindParam(1, $_POST['sss_no']);
                      $stmt_d->bindParam(2, $_POST['tin_no']);
                      $stmt_d->bindParam(3, $_POST['philhealth_no']);
                      $stmt_d->bindParam(4, $_POST['pagibig_no']);
                      $stmt_d->bindParam(5, $_POST['employee_id']);
                      $stmt_d->execute();


                      // emergency contact data
                      $stmt_e = $db->prepare("UPDATE emergency_contact SET contact_person= ?, contact_number= ? WHERE employee_id=?");
                      $stmt_e->bindParam(1, $_POST['contact_person']);
                      $stmt_e->bindParam(2, $_POST['contact_number']);
                      $stmt_e->bindParam(3, $_POST['employee_id']);
                      $stmt_e->execute();


                      // allowances data
                      $stmt_f = $db->prepare("UPDATE allowances SET rice_allowance= ?, meal_allowance= ?, lodging_allowance=?, transpo_allowance=? WHERE employee_id=?");
                      $stmt_f->bindParam(1, $_POST['rice_allowance']);
                      $stmt_f->bindParam(2, $_POST['meal_allowance']);
                      $stmt_f->bindParam(3, $_POST['lodging_allowance']);
                      $stmt_f->bindParam(4, $_POST['transpo_allowance']);
                      $stmt_f->bindParam(5, $_POST['employee_id']);
                      $stmt_f->execute();

                      // vacation leave credits data
                      $stmt_g = $db->prepare("UPDATE leave_credits SET leave_credits=? WHERE employee_id=? AND leave_type='Vacation'");
                      $stmt_g->bindParam(1, $_POST['vacation_credits']);
                      $stmt_g->bindParam(2, $_POST['employee_id']);
                      $stmt_g->execute();

                      // sick leave credits data
                      $stmt_h = $db->prepare("UPDATE leave_credits SET leave_credits=? WHERE employee_id=? AND leave_type='Sick'");
                      $stmt_h->bindParam(1, $_POST['sick_credits']);
                      $stmt_h->bindParam(2, $_POST['employee_id']);
                      $stmt_h->execute();

                      // update session
                      if($_POST['employee_id'] == $_SESSION['employee_id'])
                      {
                        //Employee
                        $_SESSION['first_name'] = $_POST['first_name'];
                        $_SESSION['middle_name'] = $_POST['middle_name'];
                        $_SESSION['last_name'] = $_POST['last_name'];
                        $_SESSION['gender'] = $_POST['gender'];
                        $_SESSION['civil_status'] = $_POST['civil_status'];
                        $_SESSION['birth_date'] = $_POST['birth_date'];
                        $_SESSION['age'] = $_POST['age'];
                        $_SESSION['address'] = $_POST['address'];
                        $_SESSION['contact_no'] = $_POST['contact_no'];

                        //Query team
                        $team_query = "SELECT * FROM team WHERE team_id='".$_POST['team_id']."'";
                        $stmt = $db->prepare($team_query);
                        $stmt->execute(array('team_id' => $team_id));
                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                        $team_name = $row['team_name'];

                        //Team
                        $_SESSION['team_name'] = $team_name;
                        //Leave
                      }

                      echo("<meta http-equiv='refresh' content='1'>");
                    }
                  ?>

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