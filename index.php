<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <title>jQuery Ajax</title>
</head>
<body>

<div class="modal" tabindex="-1" role="dialog" id='modal_frm'>
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">User Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="" id='frm'>
          <input type='hidden' name='action' id='action' value='Insert'>
          <input type='hidden' name='id' id='uid' value='0'>
          <div class='form-group'>
              <label for="name">Name</label>
              <input type='text' name='name' id='name' required class='form-control'>
          </div>
          <div class='form-group'>
              <label for="gender">Gender</label>
              <select name="gender" id="gender" required class='form-control'>
                  <option value="">Select</option>
                  <option value="Male">Male</option>
                  <option value="Female">Female</option>
                  <option value="Others">Others</option>
              </select>
          </div>
          <div class='form-group'>
              <label for="contact">Contact</label>
              <input type='text' name='contact' id='contact' required class='form-control'>
          </div>
          <input type='submit' value='Submit' class='btn btn-success'>
        </form>
      </div>
    </div>
  </div>
</div>

<div class='container mt-5'>
    <p class='text-right'><a href="#" class='btn btn-success' id='add_record'>Add Record</a></p>

    <table class='table table-bordered'>
        <thead>
            <th>Name</th>
            <th>Gender</th>
            <th>Contact</th>
            <th>Edit</th>
            <th>Delete</th>
        </thead>
        <tbody id='tbody'>
            <?php
              $con = mysqli_connect("localhost", "root", "", "ajax_crud");
              $sql = "SELECT * FROM users";   // Select data from users table
              $res = $con->query($sql);       // Execute the query and store result in $res
              while($row = $res->fetch_assoc()) {
                  echo "
                      <tr uid='{$row["ID"]}'>
                          <td>{$row["NAME"]}</td>
                          <td>{$row["GENDER"]}</td>
                          <td>{$row["CONTACT"]}</td>
                          <td><a href='#' class='btn btn-primary edit'>Edit</a></td>
                          <td><a href='#' class='btn btn-danger delete'>Delete</a></td>
                      </tr>
                  ";
              }
            ?>
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function(){
        var current_row = null;

        // Show modal for adding new record
        $("#add_record").click(function(){
            $("#modal_frm").modal();
        });

        // Handle form submit (Insert or Update)
        $("#frm").submit(function(event){
            event.preventDefault(); // Prevent page refresh
            $.ajax({
                url: "ajax_action.php",
                type: "POST",
                data: $("#frm").serialize(),
                beforeSend: function(){
                    $("#frm").find("input[type='submit']").val('Loading...');
                },
                success: function(res){
                    if (res) {
                        if ($("#uid").val() == "0") {
                            // Insert new record at the end of the table
                            $("#tbody").append(res);
                        } else {
                            // Update existing record
                            $(current_row).html(res);
                        }
                    } else {
                        alert("Failed. Try Again.");
                    }
                    $("#frm").find("input[type='submit']").val('Submit');
                    clear_input(); // Clear form fields
                    $("#modal_frm").modal('hide'); // Hide modal
                }
            });
        });

        // Handle Edit button click
        $("body").on("click", ".edit", function(event){
            event.preventDefault();
            current_row = $(this).closest("tr"); // Select row to update
            $("#modal_frm").modal();
            var id = $(this).closest("tr").attr("uid");
            var name = $(this).closest("tr").find("td:eq(0)").text();
            var gender = $(this).closest("tr").find("td:eq(1)").text();
            var contact = $(this).closest("tr").find("td:eq(2)").text();

            // Set form values for editing
            $("#action").val("Update");
            $("#uid").val(id);
            $("#name").val(name);
            $("#gender").val(gender);
            $("#contact").val(contact);
        });

        // Handle Delete button click
        $("body").on("click", ".delete", function(event){
          event.preventDefault();
          var id = $(this).closest("tr").attr("uid");
          var cls=$(this);
          $.ajax({
                url: "ajax_action.php",
                type: "POST",
                data: {uid:id,action:'Delete'},
                beforeSend: function(){
                    $(cls).text("loading...");
                },
                success: function(res){
                    if(res){
                        $(cls).closest("tr").remove();
                    }else{
                      alert("Faild Try Again");
                      $(cls).text("Try Again");

                    }
                }
            });
        });


        // Clear input fields after submit
        function clear_input() {
            $("#frm").find(".form-control").val("");
            $("#action").val("Insert");
            $("#uid").val("0");
        }
    });
</script>

</body>
</html>
