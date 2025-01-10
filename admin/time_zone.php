<?php
// Set timezone to Asia/Manila (Philippine Standard Time, GMT+8)
date_default_timezone_set('Asia/Manila');

// Get local time, date, year, and day of the week
$current_time = date("H:i:s"); // Time in 24-hour format
$midnight_time = strtotime('today midnight');
$next_midnight_time = strtotime('tomorrow midnight');
$current_date = date("Y-m-d"); // Date in YYYY-MM-DD format
$current_MONTH_YEAR  = date("Y-m");
$current_datetime = date("Y-m-d H:i:s");
$current_date_format = date("F j, Y");
$current_time_format = date("h:i A");
$current_year = date("Y"); // Year
$day_of_week = date("N"); // Numeric representation of the day of the week (1 for Monday through 7 for Sunday)
// Define an array to map numeric representation of day to day name
$day_names = array(
    1 => "Monday",
    2 => "Tuesday",
    3 => "Wednesday",
    4 => "Thursday",
    5 => "Friday",
    6 => "Saturday",
    7 => "Sunday"
);
// Get the day name from the array
$day_name = $day_names[$day_of_week];

// echo $current_MONTH_YEAR;
?>