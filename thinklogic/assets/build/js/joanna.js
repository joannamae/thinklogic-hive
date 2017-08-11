//leavetracking.php
$(document).ready(function(){
    $('#leave_type').on('change', function() {
      if ( this.value == 'vacation')
      {
        $("#vacation").show();
        $("#sick").hide();
        $("#undertime").hide();
      }
    });
});