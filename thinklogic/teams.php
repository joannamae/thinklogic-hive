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
                <h3>Teams</h3>
              </div>

              <div class="title_right">
                
              </div>
            </div>
            
            <div class="clearfix"></div>

            <?php
              if(isset($_POST['addteam'])){
                $stmt = $db->prepare("INSERT INTO team (team_name) VALUES (:Team_Name)");

                $stmt->bindParam(':Team_Name', $Team_Name);

                $Team_Name = $_POST['team_name'];

                $stmt->execute();
                ?>
                  <script>window.location.href="teams.php"</script>
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
                      New Team
                    </button>

                    <!-- Modal -->
                    <div class="modal fade bs-example-modal-md" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                      <div class="modal-dialog modal-md" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Team</h4>
                          </div>
                          <div class="modal-body">
                            <form data-parsley-validate class="form-horizontal form-label-left" method="POST">
                                <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Team ID</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" class="form-control col-md-7 col-xs-12" readonly="readonly"/>
                                </div>
                                </div>
                                <div class="form-group">
                                <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Team Name <span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                <input class="form-control col-md-7 col-xs-12" type="text" name="team_name" required />
                                </div>
                                </div>
                                <br>
                                <center>
                                    <input type="submit" value="Add Team" class="btn btn-primary" name="addteam" />
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
                    <h2>Team Masterlist</h2>
                    
                    <div class="clearfix"></div>
                  </div>

                  <!-- Query for Updating Team -->
                  <?php 
                    if(isset($_POST['update_team']))
                    {
                      $stmt = $db->prepare("UPDATE team SET team_name=? WHERE team_id=?");
                      $stmt->bindParam(1,$_POST['team_name']);
                      $stmt->bindParam(2,$_POST['team_id']);
                      $stmt->execute();
                   ?>
                      <div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <strong>Thank you!</strong> Team successfully updated.
                      </div>
                      <?php
                    }
                  ?>

                  <div class="x_content">
                    <div class="table-responsive">
                      <table id="datatable" class="table table-striped jambo_table bulk_action">
                        <thead>
                          <tr class="headings">
                            <!-- <th class="column-title"><input type="checkbox" id="check-all" class="flat"></th> -->
                            <th class="column-title no-link last">Action</th>
                            <th class="column-title">Team ID</th>
                            <th class="column-title">Team Name</th>
                          </tr>
                        </thead>
                        <tbody>

                          <?php
                              $team_query = "SELECT * FROM team ORDER BY team_id ASC";
                              $stmt = $db->prepare($team_query);
                              $stmt->execute();
                              while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                              ?>
                                <tr>
                                  <?php $rowID = $row['team_id']; ?>
                                  <!-- <td class="a-center "><input type="checkbox" class="flat" name="table_records"></td> -->
                                  <td class="last"><a href="#" data-toggle="modal" data-target="#editModal<?php echo $rowID; ?>" data-sfid="<?php echo $rowID; ?>"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></td>
                                  <td><?php echo $row["team_id"]; ?></td>
                                  <td><?php echo $row["team_name"]; ?></td>
                                </tr>
                              <?php
                              }
                            ?>
                        </tbody>
                      </table>
                    </div>
                  </div>

                  <!-- Modal for Updating Team -->
                  <?php
                    $team_query = "SELECT * FROM team";
                    $stmt = $db->prepare($team_query);
                    $stmt->execute();
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                  ?>
                  <!-- EDIT MODAL -->
                  <div class="modal fade bs-example-modal-md" id="editModal<?php echo $row['team_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                  <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content" style="width: 800px;padding-left:2em;" >
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Edit Team</h4>
                      </div>
                      <div class="modal-body">
                        <div>
                          <form data-parsley-validate class="form-horizontal form-label-left" method="POST">
                            <div class="form-group" style="display:none">
                              <label for="middle-name" class="control-label col-md-4 col-sm-5 col-xs-12">Team ID</label>
                              <div class="col-md-6 col-sm-10 col-xs-12">
                                <input class="form-control col-md-7 col-xs-12" type="text" readonly="readonly" value="<?php echo $row['team_id'] ?>"  name="team_id" />
                              </div>
                            </div>
                            <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Team Name
                              </label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row['team_name']; ?>" name="team_name" />
                              </div>
                            </div>
                            <div class="ln_solid"></div>
                            <div class="form-group">
                              <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                <button class="btn btn-primary" type="reset">Reset</button>
                                <button type="submit" class="btn btn-success" name="update_team">Submit</button>
                              </div>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                  </div>
                  <?php 
                    }
                  ?>
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