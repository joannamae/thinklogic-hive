<!-- Employees.php -->

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
                <h3>Employees</h3>
              </div>

              <div class="title_right">
                
              </div>
            </div>
            
            <div class="clearfix"></div>

            <?php
              if(isset($_POST['addemployee'])){
                $stmt = $db->prepare("INSERT INTO employees (first_name, middle_name, last_name, gender, civil_status, birth_date, age, contact_no, address) VALUES (:First_Name, :Middle_Name, :Last_Name, :Gender, :Civil_Status, :Birth_Date, :Age, :Contact_No, :Address)");

                $stmt->bindParam(':First_Name', $First_Name);
                $stmt->bindParam(':Middle_Name', $Middle_Name);
                $stmt->bindParam(':Last_Name', $Last_Name);
                $stmt->bindParam(':Gender', $Gender);
                $stmt->bindParam(':Civil_Status', $Civil_Status);
                $stmt->bindParam(':Birth_Date', $Birth_Date);
                $stmt->bindParam(':Age', $Age);
                $stmt->bindParam(':Contact_No', $Contact_No);
                $stmt->bindParam(':Address', $Address);
                $First_Name = $_POST['first_name'];
                $Middle_Name = $_POST['middle_name'];
                $Last_Name = $_POST['last_name'];
                $Gender = $_POST['gender'];
                $Civil_Status = $_POST['civil_status'];
                $Birth_Date = $_POST['birthdate'];
                $Age = $_POST['age'];
                $Contact_No = $_POST['contact_no'];
                $Address = $_POST['address'];
                $stmt->execute();

                // get last inserted id
                $employee_query = "SELECT * FROM employees ORDER BY employee_id DESC LIMIT 1";
                $stmt1 = $db->prepare($employee_query);
                $stmt1->execute();
                $stmt1_row = $stmt1->fetch(PDO::FETCH_ASSOC);
                $Employee_ID = $stmt1_row['employee_id'];

                // employee group
                $stmt2 = $db->prepare("INSERT INTO employee_group (employee_id) VALUES (:Employee_ID)");
                $stmt2->bindParam(':Employee_ID', $Employee_ID);
                $stmt2->execute();

                // benefits
                $stmt3 = $db->prepare("INSERT INTO benefits (employee_id) VALUES (:Employee_ID)");
                $stmt3->bindParam(':Employee_ID', $Employee_ID);
                $stmt3->execute();

                // employment
                $stmt4 = $db->prepare("INSERT INTO employment (employee_id) VALUES (:Employee_ID)");
                $stmt4->bindParam(':Employee_ID', $Employee_ID);
                $stmt4->execute();

                //employee_blob
                $stmt5 = $db->prepare("INSERT INTO employee_blob (employee_id,employee_picture) VALUES (:Employee_ID,:employee_picture)");
                $stmt5->bindParam(':employee_picture', $employee_picture);
                $stmt5->bindParam(':Employee_ID', $Employee_ID);
                $employee_picture = 'assets/images/default.jpg';
                $stmt5->execute();

                // emergency contact
                $stmt6 = $db->prepare("INSERT INTO emergency_contact (employee_id) VALUES (:Employee_ID)");
                $stmt6->bindParam(':Employee_ID', $Employee_ID);
                $stmt6->execute();

                // allowances
                $stmt7 = $db->prepare("INSERT INTO allowances (employee_id) VALUES (:Employee_ID)");
                $stmt7->bindParam(':Employee_ID', $Employee_ID);
                $stmt7->execute();

                // vacation leave credits
                $stmt8 = $db->prepare("INSERT INTO leave_credits (employee_id, leave_type, leave_credits) VALUES (:Employee_ID, :Leave_Type, :Leave_Credits)");
                $stmt8->bindParam(':Employee_ID', $Employee_ID);
                $stmt8->bindParam(':Leave_Type', $Leave_Type);
                $stmt8->bindParam(':Leave_Credits', $Leave_Credits);
                $Leave_Type = "Vacation";
                $Leave_Credits = "0";
                $stmt8->execute();

                // sick leave credits
                $stmt9 = $db->prepare("INSERT INTO leave_credits (employee_id, leave_type, leave_credits) VALUES (:Employee_ID, :Leave_Type, :Leave_Credits)");
                $stmt9->bindParam(':Employee_ID', $Employee_ID);
                $stmt9->bindParam(':Leave_Type', $Leave_Type);
                $stmt9->bindParam(':Leave_Credits', $Leave_Credits);
                $Leave_Type = "Sick";
                $Leave_Credits = "0";
                $stmt9->execute();

                // bio break
                $stmt10 = $db->prepare("INSERT INTO bio_break (employee_id, break_remaining, break_date) VALUES (:Employee_ID, :Break_Remaining, :Break_Date");
                $stmt10->bindParam(':Employee_ID', $Employee_ID);
                $stmt10->bindParam(':Break_Remaining', $Break_Remaining);
                $stmt10->bindParam(':Break_Date', $Break_Date);
                $Break_Remaining = "180";
                $Break_Date = date('m/d/Y');
                $stmt10->execute();

                ?>
                  <script>window.location.href="addemployee.php"</script>
                <?php
              }
          ?>

            <div class="row">
              <div class="clearfix"></div>
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_content">
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                      New Employee
                    </button>

                    <!-- Modal -->
                    <div class="modal fade bs-example-modal-md" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                      <div class="modal-dialog modal-md" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Basic Employee Information</h4>
                          </div>
                          <div class="modal-body">
                            <form data-parsley-validate class="form-horizontal form-label-left" method="POST">
                                <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Employee ID</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" class="form-control col-md-7 col-xs-12" readonly="readonly"/>
                                </div>
                                </div>
                                <div class="form-group">
                                <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">First Name <span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                <input class="form-control col-md-7 col-xs-12" type="text" name="first_name" required />
                                </div>
                                </div>
                                <div class="form-group">
                                <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Middle Name  <span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                <input class="form-control col-md-7 col-xs-12" type="text" name="middle_name" required/>
                                </div>
                                </div>
                                <div class="form-group">
                                <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Last Name <span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                <input class="form-control col-md-7 col-xs-12" type="text"  name="last_name" required/>
                                </div>
                                </div>
                                <div class="form-group">
                                <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Gender <span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                <select class="select2_single form-control" tabindex="-1" required name="gender">
                                <option disabled selected></option>
                                <option>Male</option>
                                <option>Female</option>
                                </select>
                                </div>
                                </div>  
                                <div class="form-group">
                                <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Civil Status <span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                <select class="select2_single form-control" tabindex="-1" required name="civil_status">
                                <option disabled selected></option>
                                <option>Single</option>
                                <option>Married</option>
                                <option>Separated</option>
                                <option>Divorced</option>
                                <option>Widowed</option>
                                </select>
                                </div>
                                </div>
                                <div class="form-group">
                                <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Birthdate <span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="xdisplay_inputx form-group has-feedback">
                                <input type="text" class="form-control has-feedback-left" id="single_cal1" name="birthdate" required>
                                <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                <span id="inputSuccess2Status" class="sr-only">(success)</span>
                                </div>
                                </div>
                                </div>
                                <div class="form-group">
                                <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Age <span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                <input class="form-control col-md-7 col-xs-12" type="number" value="" name="age" required />
                                </div>
                                </div>
                                <div class="form-group">
                                <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Contact No. <span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" class="form-control col-md-7 col-xs-12" data-inputmask="'mask' : '(9999) 999-9999'" required name="contact_no">
                                <span class="fa fa-user form-control-feedback right" aria-hidden="true"></span>
                                </div>
                                </div>
                                <div class="form-group">
                                <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Address <span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                <textarea class="form-control" style="" name="address" required></textarea>
                                </div>
                                </div>
                                <br>
                                <center>
                                    <input type="submit" value="Add Employee" class="btn btn-primary" name="addemployee" />
                                </center>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
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
                 
                    <div class="clearfix"></div>
                  </div>

                  <div class="x_content">
                    <div class="table-responsive">
                      <table id="datatable" class="table table-striped jambo_table bulk_action">
                        <thead>
                          <tr class="headings">
                            <!-- <th class="column-title"><input type="checkbox" id="check-all" class="flat"></th> -->
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
                          <?php
                              $employee_query = "SELECT * FROM employees";
                              $tbl_stmt = $db->prepare($employee_query);
                              $tbl_stmt->execute();
                              $i=1;
                              while ($row = $tbl_stmt->fetch(PDO::FETCH_ASSOC)){
                              ?>
                                <tr>
                                  <?php $rowID = $row['employee_id']; ?>
                                  <!-- <td class="a-center "><input type="checkbox" class="flat" name="table_records"></td> -->
                                  <td class="last"><a href="editprofile.php?edit=<?php echo $row['employee_id'] ?>" ><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></td>
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
    </div>
    
  </body>
  <footer>
        <?php
          include("includes/foot.html");
        ?>
  </footer>
</html>