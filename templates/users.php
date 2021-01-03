<?php
/**
 * Template for displaying table of users retrieved from API
 */
get_header();
?>
    <div class="jw-demo">
        <div class="container">
            <h1>Demo Plugin</h1>
            <div id="jw-msg-box" class="hidden"></div>
            <div class="table-wrapper">
                <div id="jw-demo-modal" class="jw-modal hidden">
                    <div class="jw-loading">
                        Loading, please wait
                        <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
                    </div>
                </div>
                <table id="jw-demo-users">
                </table>
            </div>
        </div>
    </div>
<?php
get_footer();
?>
