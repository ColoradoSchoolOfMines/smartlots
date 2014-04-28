<?php
    require 'templates/admin_header.php'
?>
  		<script>
            loadUsers();
        </script>      
        <h1>All Users</h1>

        <table id = "user_table"></table>
        <br><br>
        <a href = "/smartlots/admin/create_account.php">Add New User</a>

<?php
    require 'templates/admin_footer.php'
?>