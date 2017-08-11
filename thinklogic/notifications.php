<?php
  // EMPLOYEE
  if($_SESSION['access_id']=='1'){
    // Select Notifications
    $notif_query = "SELECT * FROM notifications WHERE notification_status='Open'";
    $stmt = $db->prepare($notif_query);
    $stmt->execute(array());
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
      $notification_id = $row['notification_id'];
      $leave_id = $row['leave_id'];
      $ticket_id = $row['ticket_id'];
      $notification_status = $row['notification_status'];

      // Approved & Disapproved Leave Notifications Query
      $leave_query = "SELECT * FROM leave_application WHERE leave_id='".$leave_id."' AND leave_status!='Open' AND employee_id='".$_SESSION['employee_id']."'";
      $stmt1 = $db->prepare($leave_query);
      $stmt1->execute(array());
      while ($row = $stmt1->fetch(PDO::FETCH_ASSOC)){
        ?>
        <li>
          <form method="POST">
          <a href="">
            <input type="text" style="display:none" name="leave_id" value="<?php echo $row['leave_id']?>"/>
            <span class="image"><img src="assets/images/img.jpg" alt="Profile Image" /></span>
            <span>
              <span><?php echo $row['employee_name']; ?></span>
              <span class="time">ID #: <?php echo $row['leave_id']; ?></span>
            </span>
            <span class="message">
              Leave application has been <?php echo $row['leave_status']; ?> by <?php echo $row['updated_by']; ?>
              <input type="submit" value="View" class="btn btn-link btn-xs" name="readnotif_leave" />
            </span>
          </a>
          </form>
        </li>
        <?php
      }

      // Approved, Disapproved, Completed - Resolved & Completed - Unresolved Tickets
      $ticket_query = "SELECT * FROM ticket_request WHERE ticket_id='".$ticket_id."' AND ticket_status!='Assigned' AND ticket_status!='Open' AND requestor_id='".$_SESSION['employee_id']."'";
      $stmt2 = $db->prepare($ticket_query);
      $stmt2->execute(array());
      while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)){
        ?>
        <li>
          <form method="POST">
            <a href="">
              <input type="text" style="display:none" name="ticket_id" value="<?php echo $row['ticket_id']?>"/>
              <span class="image"><img src="assets/images/img.jpg" alt="Profile Image" /></span>
              <span>
                <span><?php echo $row['ticket_requestor']; ?></span>
                <span class="time">ID #: <?php echo $row['ticket_id']; ?></span>
              </span>
              <span class="message">
                Ticket request has been <?php echo $row['ticket_status']; ?> by <?php echo $row['updated_by']; ?>
                <input type="submit" value="View" class="btn btn-link btn-xs" name="readnotif_ticket" />
              </span>
            </a>
          </form>
        </li>
        <?php
      }

      // Assigned Tickets
      $ticket_query = "SELECT * FROM ticket_request WHERE ticket_id='".$ticket_id."' AND ticket_status='Assigned' AND requestor_id='".$_SESSION['employee_id']."'";
      $stmt2 = $db->prepare($ticket_query);
      $stmt2->execute(array());
      while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)){
        ?>
        <li>
          <form method="POST">
            <a href="#">
              <input type="text" style="display:none" name="ticket_id" value="<?php echo $row['ticket_id']?>"/>
              <span class="image"><img src="assets/images/img.jpg" alt="Profile Image" /></span>
              <span>
                <span><?php echo $row['ticket_requestor']; ?></span>
                <span class="time">ID #: <?php echo $row['ticket_id']; ?></span>
              </span>
              <span class="message">
                Ticket request has been <?php echo $row['ticket_status']; ?> to <?php echo $row['ticket_assignment']; ?>
                <input type="submit" value="View" class="btn btn-link btn-xs" name="readnotif_ticket" />
              </span>
            </a>
          </form>
        </li>
        <?php
      }

      // Assigned to User Tickets
      $ticket_assignment = $_SESSION['first_name'] . " " . $_SESSION['last_name'];
      $ticket_query = "SELECT * FROM ticket_request WHERE ticket_id='".$ticket_id."' AND ticket_assignment='".$ticket_assignment."'";
      $stmt2 = $db->prepare($ticket_query);
      $stmt2->execute(array());
      while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)){
        ?>
        <li>
          <form method="POST">
            <a href="#">
              <input type="text" style="display:none" name="ticket_id" value="<?php echo $row['ticket_id']?>"/>
              <span class="image"><img src="assets/images/img.jpg" alt="Profile Image" /></span>
              <span>
                <span><?php echo $row['ticket_requestor']; ?></span>
                <span class="time">ID #: <?php echo $row['ticket_id']; ?></span>
              </span>
              <span class="message">
                Ticket request has been <?php echo $row['ticket_status']; ?> to you by <?php echo $row['updated_by']; ?>
                <input type="submit" value="View" class="btn btn-link btn-xs" name="readnotif" />
              </span>
            </a>
          </form>
        </li>
        <?php
      }

      /***********************PHP FOR UPDATING NOTIFICATION STATUS**************************/
      if(isset($_POST['readnotif_leave'])){
        $leave_id = $_POST["leave_id"];
        $stmt3 = $db->prepare("UPDATE notifications SET notification_status='Read' WHERE leave_id=$leave_id");
        $stmt3->execute();
        ?>
        <script>window.location.href="leavetracking.php"</script>
        <?php 
      }
      else if(isset($_POST['readnotif_ticket'])){
        $ticket_id = $_POST["ticket_id"];
        $stmt3 = $db->prepare("UPDATE notifications SET notification_status='Read' WHERE ticket_id=$ticket_id");
        $stmt3->execute();
        ?>
        <script>window.location.href="tickettracking.php"</script>
        <?php
      }
      else if(isset($_POST['readnotif'])){
        $ticket_id = $_POST["ticket_id"];
        $stmt3 = $db->prepare("UPDATE notifications SET notification_status='Read' WHERE ticket_id=$ticket_id");
        $stmt3->execute();
        ?>
        <script>window.location.href="tasks.php"</script>
        <?php
      }
    } 
  }
?>

<?php
  // SUPERVISOR
  if($_SESSION['access_id']=='2'){
    // Select Notifications
    $notif_query = "SELECT * FROM notifications WHERE notification_status='Open'";
    $stmt = $db->prepare($notif_query);
    $stmt->execute(array());
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
      $notification_id = $row['notification_id'];
      $leave_id = $row['leave_id'];
      $ticket_id = $row['ticket_id'];
      $notification_status = $row['notification_status'];

      // Open Leave Notifications Query for Approval
      $leave_query = "SELECT * FROM leave_application WHERE leave_id='".$leave_id."' AND leave_status='Open' AND leave_team='".$_SESSION['team_name']."'";
      $stmt1 = $db->prepare($leave_query);
      $stmt1->execute(array());
      while ($row = $stmt1->fetch(PDO::FETCH_ASSOC)){
        ?>
        <li>
          <form method="POST">
            <a href="#" >
              <span class="image"><img src="assets/images/img.jpg" alt="Profile Image" /></span>
              <span>
                <input type="text" style="display:none" name="leave_id" value="<?php echo $row['leave_id']?>"/>
                <span><?php echo $row['employee_name']; ?></span>
                <span class="time">ID #: <?php echo $row['leave_id']; ?></span>
              </span>
              <span class="message">
                Leave application is <?php echo $row['leave_status']; ?>.
                <input type="submit" value="View" class="btn btn-link btn-xs" name="readleave" />
              </span>
            </a>
          </form>
        </li>
        <?php
      }

      // Approved & Disapproved Leave Notifications Query
      $leave_query = "SELECT * FROM leave_application WHERE leave_id='".$leave_id."' AND leave_status!='Open' AND employee_id='".$_SESSION['employee_id']."'";
      $stmt1 = $db->prepare($leave_query);
      $stmt1->execute(array());
      while ($row = $stmt1->fetch(PDO::FETCH_ASSOC)){
        ?>
        <li>
          <form method="POST">
            <a href="#" >
              <span class="image"><img src="assets/images/img.jpg" alt="Profile Image" /></span>
              <span>
                <input type="text" style="display:none" name="leave_id" value="<?php echo $row['leave_id']?>"/>
                <span><?php echo $row['employee_name']; ?></span>
                <span class="time">ID #: <?php echo $row['leave_id']; ?></span>
              </span>
              <span class="message">
                Leave application has been <?php echo $row['leave_status']; ?> by <?php echo $row['updated_by']; ?>
                <input type="submit" value="View" class="btn btn-link btn-xs" name="readnotif" />
              </span>
            </a>
          </form>
        </li>
        <?php
      }

      // Open Ticket Notifications Query for Approval
      $ticket_query = "SELECT * FROM ticket_request WHERE ticket_id='".$ticket_id."' AND ticket_status='Open' AND requestor_team='".$_SESSION['team_name']."'";
      $stmt2 = $db->prepare($ticket_query);
      $stmt2->execute(array());
      while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)){
        ?>
        <li>
          <form method="POST">
            <a href="#">
              <span class="image"><img src="assets/images/img.jpg" alt="Profile Image" /></span>
              <span>
                <input type="text" style="display:none" name="ticket_id" value="<?php echo $row['ticket_id']?>"/>
                <span><?php echo $row['ticket_requestor']; ?></span>
                <span class="time">ID #: <?php echo $row['ticket_id']; ?></span>
              </span>
              <span class="message">
                Ticket request is <?php echo $row['ticket_status']; ?>
                <input type="submit" value="View" class="btn btn-link btn-xs" name="readnotif" />
              </span>
            </a>
          </form>
        </li>
        <?php
      }

      // Approved, Disapproved, Completed-Resolved & Completed-Unresolved Tickets
      $ticket_query = "SELECT * FROM ticket_request WHERE ticket_id='".$ticket_id."' AND ticket_status!='Assigned' AND ticket_status!='Open' AND requestor_id='".$_SESSION['employee_id']."'";
      $stmt2 = $db->prepare($ticket_query);
      $stmt2->execute(array());
      while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)){
        ?>
        <li>
          <form method="POST">
            <a href="#">
              <span class="image"><img src="assets/images/img.jpg" alt="Profile Image" /></span>
              <span>
                <input type="text" style="display:none" name="ticket_id" value="<?php echo $row['ticket_id']?>"/>
                <span><?php echo $row['ticket_requestor']; ?></span>
                <span class="time">ID #: <?php echo $row['ticket_id']; ?></span>
              </span>
              <span class="message">
                Ticket request has been <?php echo $row['ticket_status']; ?> by <?php echo $row['updated_by']; ?>
                <input type="submit" value="View" class="btn btn-link btn-xs" name="readnotif_ticket" />
              </span>
            </a>
          </form>
        </li>
        <?php
      }

      // Assigned Ticket Requests
      $ticket_query = "SELECT * FROM ticket_request WHERE ticket_id='".$ticket_id."' AND ticket_status='Assigned' AND requestor_id='".$_SESSION['employee_id']."'";
      $stmt2 = $db->prepare($ticket_query);
      $stmt2->execute(array());
      while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)){
        ?>
        <li>
          <form method="POST">
            <a href="#">
              <span class="image"><img src="assets/images/img.jpg" alt="Profile Image" /></span>
              <span>
                <input type="text" style="display:none" name="ticket_id" value="<?php echo $row['ticket_id']?>"/>
                <span><?php echo $row['ticket_requestor']; ?></span>
                <span class="time">ID #: <?php echo $row['ticket_id']; ?></span>
              </span>
              <span class="message">
                Ticket request has been <?php echo $row['ticket_status']; ?> to <?php echo $row['ticket_assignment']; ?>
                <input type="submit" value="View" class="btn btn-link btn-xs" name="readnotif_ticket" />
              </span>
            </a>
          </form>
        </li>
        <?php
      }

      // Approve to Team Concern for Assignment Tickets
      $ticket_query = "SELECT * FROM ticket_request WHERE ticket_id='".$ticket_id."' AND ticket_status='Approved' AND ticket_team='".$_SESSION['team_name']."'";
      $stmt2 = $db->prepare($ticket_query);
      $stmt2->execute(array());
      while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)){
        ?>
        <li>
          <form method="POST">
            <a href="#">
              <span class="image"><img src="assets/images/img.jpg" alt="Profile Image" /></span>
              <span>
                <input type="text" style="display:none" name="ticket_id" value="<?php echo $row['ticket_id']?>"/>
                <span><?php echo $row['ticket_requestor']; ?></span>
                <span class="time">ID #: <?php echo $row['ticket_id']; ?></span>
              </span>
              <span class="message">
                Ticket request needs ticket assignment.
                <input type="submit" value="View" class="btn btn-link btn-xs" name="readnotif" />
              </span>
            </a>
          </form>
        </li>
        <?php
      }

      // Assigned to User Tickets
      $ticket_assignment = $_SESSION['first_name'] . " " . $_SESSION['last_name'];
      $ticket_query = "SELECT * FROM ticket_request WHERE ticket_id='".$ticket_id."' AND ticket_assignment='".$ticket_assignment."'";
      $stmt2 = $db->prepare($ticket_query);
      $stmt2->execute(array());
      while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)){
        ?>
        <li>
          <form method="POST">
            <a href="#">
              <span class="image"><img src="assets/images/img.jpg" alt="Profile Image" /></span>
              <span>
                <input type="text" style="display:none" name="ticket_id" value="<?php echo $row['ticket_id']?>"/>
                <span><?php echo $row['ticket_requestor']; ?></span>
                <span class="time">ID #: <?php echo $row['ticket_id']; ?></span>
              </span>
              <span class="message">
                Ticket request has been <?php echo $row['ticket_status']; ?> to you by <?php echo $row['updated_by']; ?>.
                <input type="submit" value="View" class="btn btn-link btn-xs" name="readnotif_ticket" />
              </span>
            </a>
          </form>
        </li>
        <?php
      }

      /***********************PHP FOR UPDATING NOTIFICATION STATUS**************************/
      if(isset($_POST['readnotif_leave'])){
        $leave_id = $_POST["leave_id"];
        $stmt3 = $db->prepare("UPDATE notifications SET notification_status='Read' WHERE leave_id=$leave_id");
        $stmt3->execute();
        ?>
        <script>window.location.href="leavetracking.php"</script>
        <?php 
      }
      else if(isset($_POST['readnotif_ticket'])){
        $ticket_id = $_POST["ticket_id"];
        $stmt3 = $db->prepare("UPDATE notifications SET notification_status='Read' WHERE ticket_id=$ticket_id");
        $stmt3->execute();
        ?>
        <script>window.location.href="tickettracking.php"</script>
        <?php
      }
      else if(isset($_POST['readnotif'])){
        $ticket_id = $_POST["ticket_id"];
        $stmt3 = $db->prepare("UPDATE notifications SET notification_status='Read' WHERE ticket_id=$ticket_id");
        $stmt3->execute();
        ?>
        <script>window.location.href="tasks.php"</script>
        <?php
      }
      else if(isset($_POST['readleave'])){
        $leave_id = $_POST["leave_id"];
        $stmt3 = $db->prepare("UPDATE notifications SET notification_status='Read' WHERE leave_id=$leave_id");
        $stmt3->execute();
        ?>
        <script>window.location.href="inbox.php"</script>
        <?php
      }
    }
  }
?>

<?php
  // MANAGER & ADMINISTRATOR
  if($_SESSION['access_id']=='3' OR $_SESSION['access_id']=='4'){
    // Select Notifications
    $notif_query = "SELECT * FROM notifications WHERE notification_status='Open'";
    $stmt = $db->prepare($notif_query);
    $stmt->execute(array());
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
      $notification_id = $row['notification_id'];
      $leave_id = $row['leave_id'];
      $ticket_id = $row['ticket_id'];
      $log_id = $row['log_id'];
      $notification_status = $row['notification_status'];

      // Open Leave Notifications Query for Approval
      $leave_query = "SELECT * FROM leave_application WHERE leave_id='".$leave_id."' AND leave_status='Open'";
      $stmt1 = $db->prepare($leave_query);
      $stmt1->execute(array());
      while ($row = $stmt1->fetch(PDO::FETCH_ASSOC)){
        ?>
        <li>
          <form method="POST">
            <a href="#" >
              <span class="image"><img src="assets/images/img.jpg" alt="Profile Image" /></span>
              <span>
                <input type="text" style="display:none" name="leave_id" value="<?php echo $row['leave_id']?>"/>
                <span><?php echo $row['employee_name']; ?></span>
                <span class="time">ID #: <?php echo $row['leave_id']; ?></span>
              </span>
              <span class="message">
                Leave application is <?php echo $row['leave_status']; ?>.
                <input type="submit" value="View" class="btn btn-link btn-xs" name="readleave" />
              </span>
            </a>
          </form>
        </li>
        <?php
      }

      // Approved & Disapproved Leave Notifications Query
      $leave_query = "SELECT * FROM leave_application WHERE leave_id='".$leave_id."' AND leave_status!='Open'";
      $stmt1 = $db->prepare($leave_query);
      $stmt1->execute(array());
      while ($row = $stmt1->fetch(PDO::FETCH_ASSOC)){
        ?>
        <li>
          <form method="POST">
            <a href="#" >
              <span class="image"><img src="assets/images/img.jpg" alt="Profile Image" /></span>
              <span>
                <input type="text" style="display:none" name="leave_id" value="<?php echo $row['leave_id']?>"/>
                <span><?php echo $row['employee_name']; ?></span>
                <span class="time">ID #: <?php echo $row['leave_id']; ?></span>
              </span>
              <span class="message">
                Leave application has been <?php echo $row['leave_status']; ?> by <?php echo $row['updated_by']; ?>
                <input type="submit" value="View" class="btn btn-link btn-xs" name="readleave" />
              </span>
            </a>
          </form>
        </li>
        <?php
      }

      // Open Ticket Notifications Query for Approval
      $ticket_query = "SELECT * FROM ticket_request WHERE ticket_id='".$ticket_id."' AND ticket_status='Open'";
      $stmt2 = $db->prepare($ticket_query);
      $stmt2->execute(array());
      while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)){
        ?>
        <li>
          <form method="POST">
            <a href="#">
              <span class="image"><img src="assets/images/img.jpg" alt="Profile Image" /></span>
              <span>
                <input type="text" style="display:none" name="ticket_id" value="<?php echo $row['ticket_id']?>"/>
                <span><?php echo $row['ticket_requestor']; ?></span>
                <span class="time">ID #: <?php echo $row['ticket_id']; ?></span>
              </span>
              <span class="message">
                Ticket request is <?php echo $row['ticket_status']; ?>
                <input type="submit" value="View" class="btn btn-link btn-xs" name="readnotif" />
              </span>
            </a>
          </form>
        </li>
        <?php
      }

      // Approved, Disapproved, Completed-Resolved & Completed-Unresolved Tickets
      $ticket_query = "SELECT * FROM ticket_request WHERE ticket_id='".$ticket_id."' AND ticket_status!='Open' AND ticket_status!='Assigned'";
      $stmt2 = $db->prepare($ticket_query);
      $stmt2->execute(array());
      while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)){
        ?>
        <li>
          <form method="POST">
            <a href="#">
              <span class="image"><img src="assets/images/img.jpg" alt="Profile Image" /></span>
              <span>
                <input type="text" style="display:none" name="ticket_id" value="<?php echo $row['ticket_id']?>"/>
                <span><?php echo $row['ticket_requestor']; ?></span>
                <span class="time">ID #: <?php echo $row['ticket_id']; ?></span>
              </span>
              <span class="message">
                Ticket request has been <?php echo $row['ticket_status']; ?> by <?php echo $row['updated_by']; ?>
                <input type="submit" value="View" class="btn btn-link btn-xs" name="readnotif_ticket" />
              </span>
            </a>
          </form>
        </li>
        <?php
      }

      // Assigned Ticket Requests
      $ticket_query = "SELECT * FROM ticket_request WHERE ticket_id='".$ticket_id."' AND ticket_status='Assigned'";
      $stmt2 = $db->prepare($ticket_query);
      $stmt2->execute(array());
      while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)){
        ?>
        <li>
          <form method="POST">
            <a href="#">
              <span class="image"><img src="assets/images/img.jpg" alt="Profile Image" /></span>
              <span>
                <input type="text" style="display:none" name="ticket_id" value="<?php echo $row['ticket_id']?>"/>
                <span><?php echo $row['ticket_requestor']; ?></span>
                <span class="time">ID #: <?php echo $row['ticket_id']; ?></span>
              </span>
              <span class="message">
                Ticket request has been <?php echo $row['ticket_status']; ?> to <?php echo $row['ticket_assignment']; ?>
                <input type="submit" value="View" class="btn btn-link btn-xs" name="readnotif_ticket" />
              </span>
            </a>
          </form>
        </li>
        <?php
      }

      // Approve to Team Concern for Assignment Tickets
      $ticket_query = "SELECT * FROM ticket_request WHERE ticket_id='".$ticket_id."' AND ticket_status='Approved'";
      $stmt2 = $db->prepare($ticket_query);
      $stmt2->execute(array());
      while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)){
        ?>
        <li>
          <form method="POST">
            <a href="#">
              <span class="image"><img src="assets/images/img.jpg" alt="Profile Image" /></span>
              <span>
                <input type="text" style="display:none" name="ticket_id" value="<?php echo $row['ticket_id']?>"/>
                <span><?php echo $row['ticket_requestor']; ?></span>
                <span class="time">ID #: <?php echo $row['ticket_id']; ?></span>
              </span>
              <span class="message">
                Ticket request needs ticket assignment.
                <input type="submit" value="View" class="btn btn-link btn-xs" name="readnotif" />
              </span>
            </a>
          </form>
        </li>
        <?php
      }

      // Assigned to User Tickets
      $ticket_assignment = $_SESSION['first_name'] . " " . $_SESSION['last_name'];
      $ticket_query = "SELECT * FROM ticket_request WHERE ticket_id='".$ticket_id."' AND ticket_assignment='".$ticket_assignment."'";
      $stmt2 = $db->prepare($ticket_query);
      $stmt2->execute(array());
      while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)){
        ?>
        <li>
          <form method="POST">
            <a href="#">
              <span class="image"><img src="assets/images/img.jpg" alt="Profile Image" /></span>
              <span>
                <input type="text" style="display:none" name="ticket_id" value="<?php echo $row['ticket_id']?>"/>
                <span><?php echo $row['ticket_requestor']; ?></span>
                <span class="time">ID #: <?php echo $row['ticket_id']; ?></span>
              </span>
              <span class="message">
                Ticket request has been <?php echo $row['ticket_status']; ?> to you by <?php echo $row['updated_by']; ?>.
                <input type="submit" value="View" class="btn btn-link btn-xs" name="readnotif_ticket" />
              </span>
            </a>
          </form>
        </li>
        <?php
      }

      // Pending Waived Lates
      $waive_query = "SELECT * FROM waive WHERE log_id='".$log_id."' AND status='Pending'";
      $stmt2 = $db->prepare($waive_query);
      $stmt2->execute(array());
      while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)){
        ?>
        <li>
          <form method="POST">
            <a href="#">
              <span class="image"><img src="assets/images/img.jpg" alt="Profile Image" /></span>
              <span>
                <input type="text" style="display:none" name="log_id" value="<?php echo $row['log_id']?>"/>
                <span><?php echo $row['lateEmp_name']; ?></span>
                <span class="time">ID #: <?php echo $row['log_id']; ?></span>
              </span>
              <span class="message">
                Waive late approval requested for <?php echo $row['lateEmp_name']; ?>.
                <input type="submit" value="View" class="btn btn-link btn-xs" name="readwaive" />
              </span>
            </a>
          </form>
        </li>
        <?php
      }

      /***********************PHP FOR UPDATING NOTIFICATION STATUS**************************/
      if(isset($_POST['readnotif_leave'])){
        $leave_id = $_POST["leave_id"];
        $stmt3 = $db->prepare("UPDATE notifications SET notification_status='Read' WHERE leave_id=$leave_id");
        $stmt3->execute();
        ?>
        <script>window.location.href="leavetracking.php"</script>
        <?php 
      }
      else if(isset($_POST['readnotif_ticket'])){
        $ticket_id = $_POST["ticket_id"];
        $stmt3 = $db->prepare("UPDATE notifications SET notification_status='Read' WHERE ticket_id=$ticket_id");
        $stmt3->execute();
        ?>
        <script>window.location.href="tickettracking.php"</script>
        <?php
      }
      else if(isset($_POST['readnotif'])){
        $ticket_id = $_POST["ticket_id"];
        $stmt3 = $db->prepare("UPDATE notifications SET notification_status='Read' WHERE ticket_id=$ticket_id");
        $stmt3->execute();
        ?>
        <script>window.location.href="tasks.php"</script>
        <?php
      }
      else if(isset($_POST['readleave'])){
        $leave_id = $_POST["leave_id"];
        $stmt3 = $db->prepare("UPDATE notifications SET notification_status='Read' WHERE leave_id=$leave_id");
        $stmt3->execute();
        ?>
        <script>window.location.href="inbox.php"</script>
        <?php
      }
      else if(isset($_POST['readwaive'])){
        $log_id = $_POST["log_id"];
        $stmt3 = $db->prepare("UPDATE notifications SET notification_status='Read' WHERE log_id=$log_id");
        $stmt3->execute();
        ?>
        <script>window.location.href="waive_list.php"</script>
        <?php
      }
    }
  }
?>