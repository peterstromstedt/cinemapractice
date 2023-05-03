<?php

// Retrieve selected seat numbers from POST data
$selectedSeats = explode(',', $_POST['selected-seats']);

// Loop through selected seat numbers and update database
foreach ($selectedSeats as $seatNumber) {
  // Update the 'is_booked' field for this seat number in the database
  echo "booked seat:" . $seatNumber.'<br>';
}

?>