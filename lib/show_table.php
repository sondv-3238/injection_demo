<?php
function show_table($fullname)
{
  if (session_status() == PHP_SESSION_NONE) {
    session_start();
  }
  require_once('helpers.php');
  $logged_in = logged_in();
  $admin = is_admin();
  ?>
  <table class="table border">
    <tr>
      <th>ID</th>
      <th>FullName</th>
      <th>Age</th>
      <th>Position</th>
      <th>Level</th>
      <th>Notes</th>
      <th>Salary</th>
      <?php
      // if ($logged_in) {
      //   echo ("<th>Shop</th>");
      // }
      if ($admin) {
        echo ("<th>Layoff</th>");
      }
      ?>
    </tr>
    <?php
    require_once('connectdb.php');
    // vulnerability to sql injection
    $query = "SELECT * FROM staff WHERE fullname LIKE '%$fullname%';";
    ?>
    <br>
    <div class="card">
      <div class="card-body">
        Query: <code>
          <?php
          echo ($query);
          ?>
        </code>
      </div>
    </div>
    <br>
    <h3>Staffs</h3>
    <?php
    // mysqli_multi_query is required to demonstrate all possible
    // sql-injection variants. With mysqli_query only the first
    // query statement is performed to prevent sql injection...
    try {
      $db = connectdb();
      $result = mysqli_multi_query($db, $query);
      if ($result) {
        $result = mysqli_use_result($db);
      }
    } catch (Error $e) {
      $result = false;
      echo ('ERROR: ' . $e);
    }
    if ($result) {
      while ($staff = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        echo ('<tr>');
        foreach ($staff as $attr) {
          echo ('<td>' . $attr . '</td>');
        }
        // if ($logged_in) {
        //   $id = $staff['id'];
        //   echo ("
        //     <td>
        //       <form action=\"lib/add_to_cart.php\" method=\"post\">
        //         <button name=\"item\" value=\"$id\" type=\"submit\" class=\"btn px-1 py-0\">
        //           🛒
        //         </button>
        //       </form>
        //     </td>
        //   ");
        // }
        if ($admin) {
          $id = $staff['id'];
          echo ("
            <th>
              <form class=\"form-inline mr-3\" action=\"lib/delete_item.php\" method=\"post\">
                <button name=\"id\" value=\"$id\" type=\"submit\" class=\"btn btn-danger px-1 py-0\">
                  &times;
                </button>
              </form>
            </th>
          ");
        }
        echo ('</tr>');
      }
    } else {
      try {
        echo ('ERROR: ' . mysqli_error($db));
      } catch (Error $e) {
        echo('ERROR: ' . $e);
      } 
    }
    try {
      mysqli_close($db);
    } catch (Error $e) {
      echo('ERROR: ' . $e);
    }
    ?>
  </table>
<?php
}
?>