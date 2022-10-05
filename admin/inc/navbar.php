<?

$dash = "";
$users = "";
$reports = "";
$forums = "";
$settings = "";

if (isset($page)) {

  if ($page == "dash") {
    $dash = "active";
  } else if ($page == "users") {
    $users = "active";
  } else if ($page == "reports") {
    $reports = "active";
  } else if ($page == "forums") {
    $forums = "active";
  } else if ($page == "settings") {
    $settings = "active";
  }

}

echo '<nav class="navbar navbar-expand-lg navbar-light bg-light">
<!-- Container wrapper -->
<div class="container-fluid">
  <!-- Toggle button -->
  <button
    class="navbar-toggler"
    type="button"
    data-mdb-toggle="collapse"
    data-mdb-target="#navbarCenteredExample"
    aria-controls="navbarCenteredExample"
    aria-expanded="false"
    aria-label="Toggle navigation"
  >
    <i class="fas fa-bars"></i>
  </button>

  <!-- Collapsible wrapper -->
  <div
    class="collapse navbar-collapse justify-content-center"
    id="navbarCenteredExample"
  >
    <!-- Left links -->
    <ul class="navbar-nav mb-2 mb-lg-0">
      <li class="nav-item">
        <a class="nav-link '.$dash.'" aria-current="page" href="../dash"><i class="fas fa-home me-2"></i>Dashboard</a>
      </li>
      <!-- Navbar dropdown -->
      <li class="nav-item dropdown">
        <a
          class="nav-link dropdown-toggle ' .$users. '"
          href="#"
          id="navbarDropdown"
          role="button"
          data-mdb-toggle="dropdown"
          aria-expanded="false"
        >
        <i class="fas fa-users me-2"></i>Users / Permissions
        </a>
        <!-- Dropdown menu -->
        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
          <li>
            <a class="dropdown-item" href="../users/user_list.php">View Users</a>
          </li>
          <li>
            <a class="dropdown-item" href="../users/user_add.php">Add User</a>
          </li>
          <li>
            <a class="dropdown-item" href="../users/user_list.php?options=banned">Banned Users</a>
          </li>
          <li>
            <a class="dropdown-item" href="../users/perm_list.php">Permissions</a>
          </li>
        </ul>
      </li>
      <li class="nav-item">
        <a class="nav-link '.$reports.'" aria-current="page" href="../reports"><i class="fas fa-ticket-alt me-2"></i>Reports</a>
      </li>
      <li class="nav-item dropdown">
        <a
          class="nav-link dropdown-toggle '. $forums .'"
          href="#"
          id="navbarDropdown"
          role="button"
          data-mdb-toggle="dropdown"
          aria-expanded="false"
        >
        <i class="fas fa-folder me-2"></i>Forums
        </a>
        <!-- Dropdown menu -->
        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
          <li>
            <a class="dropdown-item" href="../forums/categories.php">Categories</a>
          </li>
          <li>
            <a class="dropdown-item" href="../forums/topics.php">Topics</a>
          </li>
        </ul>
      </li>
      <li class="nav-item">
        <a href="../settings" class="nav-link" aria-current="page"><i class="fas fa-cogs me-2"></i>Settings</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" aria-current="page" href="../../">Back to forums</a>
      </li>
    </ul>
    <!-- Left links -->
  </div>
  <!-- Collapsible wrapper -->
</div>
<!-- Container wrapper -->
</nav>';

?>