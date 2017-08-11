<?php
  // EMPLOYEE
  if($_SESSION['access_id']=='1'){
    // Notification Counter Variables
    $notif_count = '0';
    $a = '0';
    $b = '0';
    $c = '0';
    $d = '0';

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
        $a++;
      }

      // Approved, Disapproved, Completed - Resolved & Completed - Unresolved Tickets
      $ticket_query = "SELECT * FROM ticket_request WHERE ticket_id='".$ticket_id."' AND ticket_status!='Assigned' AND ticket_status!='Open' AND requestor_id='".$_SESSION['employee_id']."'";
      $stmt2 = $db->prepare($ticket_query);
      $stmt2->execute(array());
      while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)){
        $b++;
      }

      // Assigned Tickets
      $ticket_query = "SELECT * FROM ticket_request WHERE ticket_id='".$ticket_id."' AND ticket_status='Assigned' AND requestor_id='".$_SESSION['employee_id']."'";
      $stmt2 = $db->prepare($ticket_query);
      $stmt2->execute(array());
      while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)){
        $c++;
      }

      // Assigned to User Tickets
      $ticket_assignment = $_SESSION['first_name'] . " " . $_SESSION['last_name'];
      $ticket_query = "SELECT * FROM ticket_request WHERE ticket_id='".$ticket_id."' AND ticket_assignment='".$ticket_assignment."'";
      $stmt2 = $db->prepare($ticket_query);
      $stmt2->execute(array());
      while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)){
        $d++;
      }
    }
    $notif_count = $a += $b += $c += $d;
    echo $notif_count;
  }

  // SUPERVISOR
  if($_SESSION['access_id']=='2'){
    // Notification Counter Variables
    $notif_count = '0';
    $a = '0';
    $b = '0';
    $c = '0';
    $d = '0';
    $e = '0';
    $f = '0';
    $g = '0';

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
        $a++;
      }

      // Approved & Disapproved Leave Notifications Query
      $leave_query = "SELECT * FROM leave_application WHERE leave_id='".$leave_id."' AND leave_status!='Open' AND employee_id='".$_SESSION['employee_id']."'";
      $stmt1 = $db->prepare($leave_query);
      $stmt1->execute(array());
      while ($row = $stmt1->fetch(PDO::FETCH_ASSOC)){
        $b++;
      }

      // Open Ticket Notifications Query for Approval
      $ticket_query = "SELECT * FROM ticket_request WHERE ticket_id='".$ticket_id."' AND ticket_status='Open' AND requestor_team='".$_SESSION['team_name']."'";
      $stmt2 = $db->prepare($ticket_query);
      $stmt2->execute(array());
      while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)){
        $c++;
      }

      // Approved, Disapproved, Completed-Resolved & Completed-Unresolved Tickets
      $ticket_query = "SELECT * FROM ticket_request WHERE ticket_id='".$ticket_id."' AND ticket_status!='Assigned' AND ticket_status!='Open' AND requestor_id='".$_SESSION['employee_id']."'";
      $stmt2 = $db->prepare($ticket_query);
      $stmt2->execute(array());
      while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)){
        $d++;
      }

      // Assigned Ticket Requests
      $ticket_query = "SELECT * FROM ticket_request WHERE ticket_id='".$ticket_id."' AND ticket_status='Assigned' AND requestor_id='".$_SESSION['employee_id']."'";
      $stmt2 = $db->prepare($ticket_query);
      $stmt2->execute(array());
      while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)){
        $e++;
      }

      // Approve to Team Concern for Assignment Tickets
      $ticket_query = "SELECT * FROM ticket_request WHERE ticket_id='".$ticket_id."' AND ticket_status='Approved' AND ticket_team='".$_SESSION['team_name']."'";
      $stmt2 = $db->prepare($ticket_query);
      $stmt2->execute(array());
      while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)){
        $f++;
      }

      // Assigned to User Tickets
      $ticket_assignment = $_SESSION['first_name'] . " " . $_SESSION['last_name'];
      $ticket_query = "SELECT * FROM ticket_request WHERE ticket_id='".$ticket_id."' AND ticket_assignment='".$ticket_assignment."'";
      $stmt2 = $db->prepare($ticket_query);
      $stmt2->execute(array());
      while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)){
        $g++;
      }
    }

    $notif_count = $a += $b += $c += $d += $e += $f =+ $g;
    echo $notif_count;
  }

  // MANAGER & ADMINISTRATOR
  if($_SESSION['access_id']=='3' OR $_SESSION['access_id']=='4'){
    // Notification Counter Variables
    $notif_count = '0';
    $a = '0';
    $b = '0';
    $c = '0';
    $d = '0';
    $e = '0';
    $f = '0';
    $g = '0';
    $h = '0';

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
        $a++;
      }

      // Approved & Disapproved Leave Notifications Query
      $leave_query = "SELECT * FROM leave_application WHERE leave_id='".$leave_id."' AND leave_status!='Open'";
      $stmt1 = $db->prepare($leave_query);
      $stmt1->execute(array());
      while ($row = $stmt1->fetch(PDO::FETCH_ASSOC)){
        $b++;
      }

      // Open Ticket Notifications Query for Approval
      $ticket_query = "SELECT * FROM ticket_request WHERE ticket_id='".$ticket_id."' AND ticket_status='Open'";
      $stmt2 = $db->prepare($ticket_query);
      $stmt2->execute(array());
      while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)){
        $c++;
      }

      // Approved, Disapproved, Completed-Resolved & Completed-Unresolved Tickets
      $ticket_query = "SELECT * FROM ticket_request WHERE ticket_id='".$ticket_id."' AND ticket_status!='Open' AND ticket_status!='Assigned'";
      $stmt2 = $db->prepare($ticket_query);
      $stmt2->execute(array());
      while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)){
        $d++;
      }

      // Assigned Ticket Requests
      $ticket_query = "SELECT * FROM ticket_request WHERE ticket_id='".$ticket_id."' AND ticket_status='Assigned'";
      $stmt2 = $db->prepare($ticket_query);
      $stmt2->execute(array());
      while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)){
        $e++;
      }

      // Approve to Team Concern for Assignment Tickets
      $ticket_query = "SELECT * FROM ticket_request WHERE ticket_id='".$ticket_id."' AND ticket_status='Approved'";
      $stmt2 = $db->prepare($ticket_query);
      $stmt2->execute(array());
      while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)){
        $f++;
      }

      // Assigned to User Tickets
      $ticket_assignment = $_SESSION['first_name'] . " " . $_SESSION['last_name'];
      $ticket_query = "SELECT * FROM ticket_request WHERE ticket_id='".$ticket_id."' AND ticket_assignment='".$ticket_assignment."'";
      $stmt2 = $db->prepare($ticket_query);
      $stmt2->execute(array());
      while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)){
        $g++;
      }

      // Pending Waived Lates
      $waive_query = "SELECT * FROM waive WHERE log_id='".$log_id."' AND status='Pending'";
      $stmt2 = $db->prepare($waive_query);
      $stmt2->execute(array());
      while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)){
        $h++;
      }
    }


    
    $notif_count = $a += $b += $c += $d += $e += $f += $g += $h;
    echo $notif_count;
  }
?>