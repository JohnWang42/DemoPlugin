<?php
/**
 * Template for displaying table of users retrieved from API
 */
get_header();
?>
    <div class="jw-demo">
        <div class="container">
            <h1>Demo Plugin</h1>
            <table id="jw-demo-users">
                <button onclick="getUsers()">Get All Users</button>
                <input id="userid" type="number">
                <button onclick="getUser(document.getElementById('userid').value)">Get One User</button>
            </table>
        </div>
    </div>
<?php
get_footer();
?>
