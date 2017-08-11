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
            <div class="page-title">
              <div class="title_left">
                <h3>Announcements</h3>
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
                    <h2>Announcements</h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        <ul class="dropdown-menu" role="menu">
                          <li><a href="#">Settings 1</a>
                          </li>
                          <li><a href="#">Settings 2</a>
                          </li>
                        </ul>
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
                            <th class="column-title">Action</th>
                            <th class="column-title">ID</th>
                            <th class="column-title">Title</th>
                            <th class="column-title">Description</th>
                            <th class="column-title">Date Posted</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php 
                            $post_query = $db->prepare("SELECT * FROM announcement");
                            $post_query->execute();
                            while($row=$post_query->fetch(PDO::FETCH_ASSOC)){
                          ?>
                            <tr>
                              <!-- <td class="a-center "><input type="checkbox" class="flat" name="table_records"></td> -->
                              <td class="last"><a href="#" data-toggle="modal" data-target="#<?php echo $row['announcement_id'];?>" ><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></td>
                              <td><?php echo $row['announcement_id']; ?></td>
                              <td><?php echo $row['title']; ?></td>
                              <td><?php echo $row['description']; ?></td>
                              <td><?php echo date('F d, Y', strtotime($row['date_added']));?></td>
                            </tr>
                            <!-- Modal -->
                            <div class="modal fade" id="<?php echo $row['announcement_id'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                              <div class="modal-content">
                                <div class="modal-body">
                                    <p class='pull-right'><small>Date Posted: <?php echo date('F d, Y', strtotime($row['date_added']));?></small></p> 
                                    <b><h2 class="modal-title" id="myModalLabel"><?php echo ucwords($row['title']); ?></h2></b>
                                    <h4><?php echo $row['description']; ?></h4> 
                                    <center><img src="<?php echo $row['image'];?>" class='img-responsive'></center>
                                </div>
                                <div class="modal-footer">
                                  <?php 
                                    if(isset($_POST['delete'])) {
                                      $id = $_POST['id'];
                                      $delete_query = $db->prepare("DELETE FROM announcement WHERE announcement_id='$id'");
                                      $delete_query->execute();
                                    ?>
                                    <script>window.location.href="announcement.php"</script>
                                    <?php 
                                      }
                                    ?>
                                  <form method="POST">
                                    <input type="hidden" name="id" value="<?php echo $row['announcement_id']; ?>">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <input type="submit" name="delete" value="Delete" class="btn btn-primary">
                                  </form>
                                </div>
                              </div>
                            </div>
                            </div>
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
  </body>
  <footer>
        <?php
          include("includes/foot.html");
        ?>
  </footer>
</html><!--  -->k