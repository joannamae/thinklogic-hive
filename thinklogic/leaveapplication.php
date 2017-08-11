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
            <div class="clearfix"></div>
              <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <div class="x_panel">
                    <div class="x_title">
                      <h2>Vacation Leave (hours)</h2>
                      
                      <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                      <table class="table table-striped jambo_table bulk_action">
                        <thead>
                          <tr class="headings">
                            <!-- <th class="column-title"><input type="checkbox" id="check-all" class="flat"></th> -->
                            <th class="column-title no-link last">Leave Type</th>
                            <th class="column-title">Leave Credits</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                              $vl_query = "SELECT * FROM leave_credits WHERE leave_type='Vacation' and employee_id='".$_SESSION['employee_id']."'";
                              $stmt = $db->prepare($vl_query);
                              $stmt->execute();
                              $i=1;
                              while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                              ?>
                                <tr>
                                  <td><?php echo $row["leave_type"]; ?></td>
                                  <td><?php echo $row["leave_credits"]; ?></td>
                                </tr>

                              <?php
                              }
                            ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <div class="x_panel">
                    <div class="x_title">
                      <h2>Sick Leave (hours)</h2>
                      
                      <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                      <div class="form-group" style="display:none">
                        <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Employee ID</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input class="form-control col-md-7 col-xs-12" type="text" readonly="readonly" value="<?php echo $_SESSION['employee_id'] ?>"  name="employee_id" />
                        </div>
                      </div>
                      <table class="table table-striped jambo_table bulk_action">
                        <thead>
                          <tr class="headings">
                            <!-- <th class="column-title"><input type="checkbox" id="check-all" class="flat"></th> -->
                            <th class="column-title no-link last">Leave Type</th>
                            <th class="column-title">Leave Credits</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                              $sl_query = "SELECT * FROM leave_credits WHERE leave_type='Sick' and employee_id='".$_SESSION['employee_id']."'";
                              $stmt = $db->prepare($sl_query);
                              $stmt->execute();
                              $i=1;
                              while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                              ?>
                                <tr>
                                  <td><?php echo $row["leave_type"]; ?></td>
                                  <td><?php echo $row["leave_credits"]; ?></td>
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


            <!-- Ticket Tables -->
            <div class="row">
              <div class="clearfix"></div>
              <div class="col-md-12 col-sm-12 col-xs-12" width="708px">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Leave Form</h2>
                    
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <div>
                          <?php 
                            if(isset($_POST['applyleave'])){
                              $stmt = $db->prepare("INSERT INTO leave_application (employee_id, employee_name, leave_team, leave_application, leave_reason, leave_type, leave_date, leave_hours) VALUES (:Employee_ID, :Employee_Name, :Leave_Team, :Leave_Application, :Reason, :Type, :Leave_Date, :Hours)");

                              $stmt->bindParam(':Employee_ID', $Employee_ID);
                              $stmt->bindParam(':Employee_Name', $Employee_Name);
                              $stmt->bindParam(':Leave_Team', $Leave_Team);
                              $stmt->bindParam(':Leave_Application', $Leave_Application);
                              $stmt->bindParam(':Reason', $Reason);
                              $stmt->bindParam(':Type', $Type);
                              $stmt->bindParam(':Leave_Date', $Leave_Date);
                              $stmt->bindParam(':Hours', $Hours);

                              $Employee_ID = $_POST['employee_id'];
                              $Employee_Name = $_POST['employee_name'];
                              $Leave_Team = $_POST['leave_team'];
                              $Leave_Application = $_POST['leave_application'];
                              $Reason = $_POST['leave_reason'];
                              $Type = $_POST['leave_type'];
                              $Leave_Date= $_POST['leave_date'];
                              $Hours = $_POST['leave_hours'];
                              $stmt->execute();

                              // Notification
                              $id_query = "SELECT leave_id FROM leave_application ORDER BY leave_id DESC LIMIT 1";
                              $stmt1 = $db->prepare($id_query);
                              $stmt1->execute(array());
                              $row = $stmt1->fetch(PDO::FETCH_ASSOC);
                              $leave_id = $row['leave_id'];

                              $stmt2 = $db->prepare("INSERT INTO notifications (leave_id) VALUES (:Leave_ID)");
                              $stmt2->bindParam(':Leave_ID', $leave_id);
                              $stmt2->execute();
                              
                              ?>
                              <div class="alert alert-success alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <strong>Thank you!</strong> Leave application successfully created.
                              </div>
                              <?php
                            }
                          ?>
                          <form data-parsley-validate class="form-horizontal form-label-left" method="POST">
                            <div class="form-group" style="display:none">
                              <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Employee ID</label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <input class="form-control col-md-7 col-xs-12" type="text" readonly="readonly" value="<?php echo $_SESSION['employee_id'] ?>"  name="employee_id" />
                              </div>
                            </div>
                            <div class="form-group">
                              <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Leave Type</label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <select id="leave_type" class="select2_single form-control" tabindex="-1" name="leave_type" required>
                                  <option disabled selected></option>
                                  <option value="Vacation">Vacation Leave</option>
                                  <option value="Sick">Sick Leave</option>
                                  <option value="Unpaid">Unpaid Leave</option>
                                </select>
                              </div>
                            </div>
                            <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Application Date
                              </label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" required="required" class="form-control col-md-7 col-xs-12" readonly="readonly" value="<?php date_default_timezone_set('Asia/Singapore'); echo date("F j, Y"); ?>" name="leave_application" />
                              </div>
                            </div>
                            <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Leave Application ID
                              </label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" name="leave_id" class="form-control col-md-7 col-xs-12" readonly="readonly" />
                              </div>
                            </div>
                            <div class="form-group">
                              <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Employee</label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <input class="form-control col-md-7 col-xs-12" type="text" readonly="readonly" value="<?php echo $_SESSION['first_name'] . " " . $_SESSION['last_name'] ?>"   name="employee_name" />
                              </div>
                            </div>
                            <div class="form-group">
                              <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Team</label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <input class="form-control col-md-7 col-xs-12" type="text"  readonly="readonly" value="<?php echo $_SESSION['team_name']; ?>"   name="leave_team" />
                              </div>
                            </div>
                            <div class="form-group">
                              <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Reason for Request</label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <textarea class="form-control" style="" name="leave_reason" required></textarea>
                              </div>
                            </div>
                            <div class="ln_solid"></div>
                            <div class="form-group">
                              <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Inclusive Date and Time</label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <fieldset>
                                  <div class="col-md-12 control-group">
                                    <div class="controls">
                                      <div class="input-prepend input-group">
                                        <span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                                        <input type="text" name="leave_date" id="reservation-time" class="form-control" value=""/>
                                      </div>
                                    </div>
                                  </div>
                                </fieldset>
                              </div>
                            </div>
                            <div class="form-group">
                              <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">No. of Working Hours</label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <fieldset>
                                  <div class="col-md-12 control-group">
                                    <div class="controls">
                                      <div class="input-prepend input-group">
                                        <span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-clock-o"></i></span>
                                        <input type="text" class="form-control" required  name="leave_hours">
                                      </div>
                                    </div>
                                  </div>
                                </fieldset>
                              </div>
                            </div>
                            <div class="ln_solid"></div>

                            <div class="form-group">
                              <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                <button class="btn btn-primary" type="reset">Reset</button>
                                <button type="submit" class="btn btn-success" name="applyleave">Submit</button>
                              </div>
                            </div>
                          </form>
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