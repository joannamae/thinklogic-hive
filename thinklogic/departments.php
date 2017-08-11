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
        <script>
          $('#myModal').on('shown.bs.modal', function ()
          {
            $('#myInput').focus()
          })
        </script>

        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Tickets</h3>
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

            <?php 
              if(isset($_POST['adddepartment'])){
                $stmt = $db->prepare("INSERT INTO department (department) VALUES (:Department)");
                $stmt->bindParam(':Department', $Department);

                $Department = $_POST['department'];
                $stmt->execute();
                ?> 
                  <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong>Thank you!</strong> Department successfully created.
                  </div>
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
                      Create Department
                    </button>

                    <!-- Modal -->
                    <div class="modal fade bs-example-modal-md" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                      <div class="modal-dialog modal-md" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Create Department</h4>
                          </div>
                          <div class="modal-body">
                            <form class="form-horizontal form-label-left input_mask" method="POST">
                              <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Department ID</label>
                                <div class="col-md-9 col-sm-9 col-xs-12">
                                  <input type="text" class="form-control" readonly="readonly"/>
                                </div>
                              </div>
                              <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Department</label>
                                <div class="col-md-9 col-sm-9 col-xs-12">
                                  <input type="text" class="form-control" name="department" required>
                                </div>
                              </div>
                              <div class="ln_solid"></div>
                              <div class="pull-right">
                              <input type="submit" value="Submit" class="btn btn-primary" name="adddepartment"/>
                              <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
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


            <!-- Modal -->
            <div class="modal fade bs-example-modal-md" id="ticketInserted" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
              <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Create Ticket</h4>
                  </div>
                  <div class="modal-body">
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
                    <h2>Departments</h2>
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
                            <th class="column-title"><input type="checkbox" id="check-all" class="flat"></th>
                            <th class="column-title no-link last">Action</th>
                            <th class="column-title">ID</th>
                            <th class="column-title">Department</th>
                            <th class="bulk-actions" colspan="12">
                              <a class="antoo" style="color:#fff; font-weight:500;"> ( <span class="action-cnt"> </span> ) <i class="fa fa-chevron-down"></i></a>
                            </th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                              $department_query = "SELECT * FROM department";
                              $stmt = $db->prepare($department_query);
                              $stmt->execute();
                              while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                          ?>
                                <tr>
                                  <td class="a-center "><input type="checkbox" class="flat" name="table_records"></td>
                                  <td class="last"><a href="dashboard.php"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>    <a href="dashboard.php"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>
                                  <td><?php echo $row["department_id"]; ?></td>
                                  <td><?php echo $row["department"]; ?></td>
                                </tr>
                              <?php
                              }
                              ?>
                          </tr>
                        </tbody>
                      </table>
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