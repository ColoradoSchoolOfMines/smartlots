<?php
    require 'templates/admin_header.php'
?>
        <script>
            setBackground('#C3E1F6');
            loadLicenses(''); loadLicenses('intrada');
        </script>
	    <div id = "image_viewer"></div>
        <h1>All License Plates</h1>
        <table style = "width: 100%;" >
            <tr>
                <th>OpenALPR</th>
                <th>&nbsp;</th>
                <th>Intrada</th>
                <th>&nbsp;</th>
            </tr>
            <tr>
                <td style = "width: 45%">
                    <table id = "license_table"></table>
                </td>
                <td style = "width: 5%">&nbsp;</td>
                <td style = "width: 45%">
                    <table id = "intrada_license_table"></table>
                </td>
                <td style = "width: 5%">&nbsp;</td>
            </tr>
        </table>
        <br><br>
        <a href = "/smartlots/admin/add_image.php">Add new Image</a>
<?php
    require 'templates/admin_footer.php'
?>