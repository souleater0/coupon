<?php
// Get current timestamp
$current_time = time();

// Set start time to 9 AM today
$start_time = strtotime('today 8:00');

// Check if current time is before 4 AM
if (date('G', $current_time) < 4) {
    // End time is 4 AM today
    $end_time = strtotime('tomorrow 4:00');
} else {
    // End time is 4 AM tomorrow
    $end_time = strtotime('tomorrow 4:00');
}

// Format start and end times
$start_time_formatted = date('Y-m-d H:i:s', $start_time);
$end_time_formatted = date('Y-m-d H:i:s', $end_time);

// Output start and end times
echo "Start Time: $start_time_formatted\n";
echo "End Time: $end_time_formatted\n";
?>

