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
            <!-- Ticket Request -->
            <div class="row">
              <div class="clearfix"></div>
              <div class="col-md-12 col-sm-12 col-xs-12" width="708px">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Ticket Request Form</h2>
                    
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <div>
                          <?php 
                            if(isset($_POST['requestticket'])){
                              $stmt = $db->prepare("INSERT INTO ticket_request (ticket_date, requestor_id, requestor_team, ticket_requestor, ticket_team, ticket_subject, ticket_description, ticket_priority) VALUES (:Ticket_Date, :Requestor_ID, :Requestor_Team, :Ticket_Requestor, :Ticket_Team, :Ticket_Subject, :Ticket_Description, :Ticket_Priority)");

                              $stmt->bindParam(':Ticket_Date', $Ticket_Date);
                              $stmt->bindParam(':Requestor_ID', $Requestor_ID);
                              $stmt->bindParam(':Requestor_Team', $Requestor_Team);
                              $stmt->bindParam(':Ticket_Requestor', $Ticket_Requestor);
                              $stmt->bindParam(':Ticket_Team', $Ticket_Team);
                              $stmt->bindParam(':Ticket_Subject', $Ticket_Subject);
                              $stmt->bindParam(':Ticket_Description', $Ticket_Description);
                              $stmt->bindParam(':Ticket_Priority', $Ticket_Priority);

                              $Ticket_Date = $_POST['ticket_date'];
                              $Requestor_ID = $_POST['requestor_id'];
                              $Requestor_Team = $_POST['requestor_team'];
                              $Ticket_Requestor = $_POST['ticket_requestor'];
                              $Ticket_Team = $_POST['ticket_team'];
                              $Ticket_Subject = $_POST['ticket_subject'];
                              $Ticket_Description = $_POST['ticket_description'];
                              $Ticket_Priority = $_POST['ticket_priority'];
                              $stmt->execute();

                              // Notification
                              $id_query = "SELECT ticket_id FROM ticket_request ORDER BY ticket_id DESC LIMIT 1";
                              $stmt1 = $db->prepare($id_query);
                              $stmt1->execute(array());
                              $row = $stmt1->fetch(PDO::FETCH_ASSOC);
                              $ticket_id = $row['ticket_id'];

                              $stmt2 = $db->prepare("INSERT INTO notifications (ticket_id) VALUES (:Ticket_ID)");
                              $stmt2->bindParam(':Ticket_ID', $ticket_id);
                              $stmt2->execute();

                              ?>
                              <div class="alert alert-success alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <strong>Thank you!</strong> Ticket successfully requested.
                              </div>
                              <?php
                            }
                          ?>
                          <form data-parsley-validate class="form-horizontal form-label-left" method="POST">
                            <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Request Date
                              </label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" required="required" class="form-control col-md-7 col-xs-12" readonly="readonly" value="<?php date_default_timezone_set('Asia/Singapore'); echo date("F j, Y"); ?>" name="ticket_date" />
                              </div>
                            </div>
                            <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Ticket ID
                              </label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" name="ticket_id" class="form-control col-md-7 col-xs-12" readonly="readonly" />
                              </div>
                            </div>
                            <div class="form-group">
                              <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Requestor ID</label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <input class="form-control col-md-7 col-xs-12" type="text" readonly="readonly" value="<?php echo $_SESSION['employee_id'] ?>"   name="requestor_id" />
                              </div>
                            </div>
                            <div class="form-group">
                              <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Requestor Team</label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <input class="form-control col-md-7 col-xs-12" type="text" readonly="readonly" value="<?php echo $_SESSION['team_name'] ?>"   name="requestor_team" />
                              </div>
                            </div>
                            <div class="form-group">
                              <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Requestor</label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <input class="form-control col-md-7 col-xs-12" type="text" readonly="readonly" value="<?php echo $_SESSION['first_name'] . " " . $_SESSION['last_name'] ?>"   name="ticket_requestor" />
                              </div>
                            </div>
                            <div class="form-group">
                              <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Team Concern</label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <select id="ticket_team" class="select2_single form-control" tabindex="-1" name="ticket_team" required>
                                  <option disabled selected></option>
                                  <?php
                                    $team_query = "SELECT * FROM team";
                                    $stmt = $db->prepare($team_query);
                                    $stmt->execute();
                                    while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
                                  ?>
                                    <option value="<?php echo $row['team_name']; ?>"><?php echo $row['team_name']; ?></option>
                                  <?php
                                    }
                                  ?>
                                </select>
                              </div>
                            </div>
                            <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Subject
                              </label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" name="ticket_subject" class="form-control col-md-7 col-xs-12"  />
                              </div>
                            </div>
                            <div class="form-group">
                              <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Description</label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <textarea class="form-control" style="" name="ticket_description" required></textarea>
                              </div>
                            </div>
                            <div class="form-group">
                              <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Priority</label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <select id="ticket_priority" class="select2_single form-control" tabindex="-1" name="ticket_priority" required>
                                  <option disabled selected></option>
                                  <option value="Low">Low</option>
                                  <option value="Moderate">Moderate</option>
                                  <option value="High">High</option>
                                  <option value="Urgent">Urgent</option>
                                </select>
                              </div>
                            </div>
                            <div class="ln_solid"></div>

                            <div class="form-group">
                              <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                <button class="btn btn-primary" type="reset">Reset</button>
                                <button type="submit" class="btn btn-success" name="requestticket">Submit</button>
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