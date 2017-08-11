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
                <h3>Waive List</h3>
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
            <!-- Ticket Tables -->
            <div class="row">
              <div class="clearfix"></div>
              <div class="col-md-12 col-sm-12 col-xs-12" width="708px">
                <div class="x_panel">
                  <div class="x_title">
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
                <?php

                if(isset($_POST['status'])){
                  $stmt1 = $db->prepare("UPDATE waive SET status= ? WHERE waive_id= ?");
                  $stmt1->bindParam(1,$_POST["status"]);
                  $stmt1->bindParam(2,$_POST["waive_id"]);
                  $stmt1->execute();
                  echo "<meta http-equiv='refresh' content='0'>";
                }

                ?>
                <div class="x_content">
                  <div class="table-responsive">
                    <table id="datatable-checkbox" class="table table-striped jambo_table bulk_action">
                      <thead>
                        <tr class="headings">
                          <th class="column-title no-link last" width="18%">Action</th>
                          <th class="column-title" width="12%">Status</th>
                          <th class="column-title" width="12%">Employee ID</th>
                          <th class="column-title" width="18%">Employee Name</th>
                          <th class="column-title">Reason</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php
                        $query = $db->prepare("SELECT * FROM waive WHERE status='Pending'");
                        $query->execute();
                        while ($row = $query->fetch(PDO::FETCH_ASSOC)) { 
                          if($row['status'] == 'Pending'){?>
                          <tr>
                          <td>
                            <form method="POST">
                              <input type="hidden" name="waive_id" value="<?php echo $row["waive_id"]?>" />
                              <input type="hidden" name="log_id" value="<?php echo $row["log_id"]?>" />
                              <button type="submit" name="status" value="Disapproved" class="btn btn-danger btn-xs">Disapprove</button>
                              <button type="submit" name="status" value="Approved" class="btn btn-primary btn-xs">Approve</button>
                            </form>
                          </td>
                          <?php }else{ echo "<td></td>"; } ?>
                          <td><?php echo $row['status'];?></td>
                          <td><?php echo $row['lateEmp_id'];?></td>
                          <td><?php echo $row['lateEmp_name'];?></td>
                          <td><?php echo $row['waive_reason'];?></td>
                        </tr>
                        <?php }
                      ?>
                        
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