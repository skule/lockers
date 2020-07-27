<?php
  session_start();
  require 'session.php';
  $title = "Skule™ Lockers Admin | Rental Records";
  include 'navbar.php';
  require '../model/db.php';

  $msg = $msgClass = '';

  // Approve Booking
  if (isset($_POST['update'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $adminId = $_SESSION['admin_id'];
    $sql = "UPDATE `record` SET record_status='approved', record_sub='active', record_approved_by='$adminId' WHERE record_id='$id'";

    if (mysqli_query($conn, $sql)) {
      $msg = "Update Successfull";
      $msgClass = "green";
    } else {
      $msg = "Error updating this recrod";
      $msgClass = "red";
    }
  }

  // Delete form handling
  if (isset($_POST['delete'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $sql = "DELETE FROM `record` WHERE `record_id`='$id'";

    if (mysqli_query($conn, $sql)) {
      $msg = "Delete Successfull";
      $msgClass = "green";
    } else {
      $msg = "Error deleting this recrod";
      $msgClass = "red";
    }
  }
?>

<div class="wrapper">
  <section class="section">
    <div class="container2">
      <?php if($msg != ''): ?>
        <div id="msgBox" class="card-panel <?php echo $msgClass; ?>">
          <span class="white-text"><?php echo $msg; ?></span>
        </div>
      <?php endif ?>
      <h5>Records Records</h5>
      <div class="divider"></div>
      <br>

      <!-- Search field -->
      <div class="row valign">
        <div class="col s12 m6 l6 right">
          <div class="input-field">
            <i class="material-icons prefix">search</i>
            <input type="text" id="search">
            <label for="search">Search</label>
          </div>
        </div>
      </div>
      <!-- Locker table list -->
      <table id="myTable" class="table highlight centered">
        <thead class="blue darken-2 white-text">
          <tr class="myHead">
            <th>Locker Id</th>
            <th>Student Id</th>
            <th>Start</th>
            <th>End</th>
            <th>Price</th>
            <th class="center-align">Status</th>
            <th>Approved by</th>
            <th colspan="2" class="center">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $sql = "SELECT * FROM `record`";
            $result = mysqli_query($conn, $sql);

            while ($row = mysqli_fetch_array($result)):
          ?>
          <tr>
            <td><?php echo $row['locker_id']; ?></td>
            <td><?php echo $row['student_id']; ?></td>
            <td><?php echo $row['record_start']; ?></td>
            <td><?php echo $row['record_end']; ?></td>
            <td><?php echo "$"."".$row['record_price']; ?></td>
            <td><?php echo $row['record_status']; ?></td>
            <td><?php echo $row['record_approved_by']; ?></td>
            <td>
              <form method='POST' action='records.php'>
                <input type='hidden' name='id' value='<?php echo $row['record_id']; ?>'>
                <?php if ($row['record_status'] == 'pending'): ?>
                  <button type='submit' name='update' class='green-text btn1 tooltipped' data-position='right' data-tooltip='Approve'>
                    <i class="fas fa-check"></i>
                  </button>
                <?php else: ?>
                  <button type='submit' name='update' class='blue-text btn1 tooltipped' data-position='right' data-tooltip='Approve' disabled>
                    <i class="fas fa-check"></i>
                  </button>
                <?php endif ?>
              </form>
            </td>
            <td>
              <form method='POST' action='records.php'>
                <input type='hidden' name='id' value='<?php echo $row['record_id']; ?>'>
                <button type='submit' onclick='return confirm(`Delete this record <?php echo $row['record_id']; ?> ?`);' name='delete' class='red-text btn1 tooltipped' data-position='top' data-tooltip='Delete'>
                  <i class='far fa-trash-alt'></i>
                </button>
              </form>
            </td>
          </tr>
          <?php endwhile ?>
        </tbody>
      </table>
    </div>
  </section>
</div>
<?php
  mysqli_close($conn);
  include 'footer.php';
?>
