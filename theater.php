<?php
  // Connect to the database
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "bio";

  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  $sql = "SELECT seat_number, is_booked FROM seats";
    $result = $conn->query($sql);

  // Close the database connection
  $conn->close();

  // created a database with just a table called seats, and in that table, 
  // 2 columns, seat_number and is_booked so i could loop through it all and present it to the screen



?>




<link rel="stylesheet" href="main.css">

<div class="seat-container">
  <?php
     if ($result->num_rows > 0) {
      // Define number of seats and rows
      $numSeatsPerRow = 5;
      $numRows = ceil($result->num_rows / $numSeatsPerRow);

      // Loop through rows
      for ($rowIndex = 1; $rowIndex <= $numRows; $rowIndex++) {
        echo '<div class="seat-row">';
        
        // Loop through seats for this row
        for ($seatIndex = 1; $seatIndex <= $numSeatsPerRow; $seatIndex++) {
          $seatNumber = (($rowIndex - 1) * $numSeatsPerRow) + $seatIndex;
          $statusClass = 'available';

          // If seat exists in database, mark it as booked
          if ($result->num_rows >= $seatNumber) {
            $result->data_seek($seatNumber - 1);
            $row = $result->fetch_assoc();
            $isBooked = $row['is_booked'];
            $statusClass = $isBooked ? 'booked' : 'available';
          }

          echo '<div class="seat '.$statusClass.'" data-seat-number="'.$seatNumber.'"></div>';
        }

        echo '</div>';
      }
    } else {
      echo "No seats found";
    }
  ?>
</div>
<form method="POST" action="book.php">
  <input type="hidden" name="selected-seats" id="selected-seats">
  <button type="submit">Book Selected Seats</button>
</form>

<script>
  // Get all seat elements
  var seatElements = document.querySelectorAll('.seat');

  // Add event listener to each seat element
  seatElements.forEach(function(seatElement) {
    seatElement.addEventListener('click', function() {
      // Toggle the 'selected' class of the clicked seat
      seatElement.classList.toggle('selected');

      // Update the hidden input field with the selected seat numbers
      var selectedSeatNumbers = [];
      var selectedSeatElements = document.querySelectorAll('.seat.selected');
      selectedSeatElements.forEach(function(selectedSeatElement) {
        selectedSeatNumbers.push(selectedSeatElement.dataset.seatNumber);
      });
      var selectedSeatsInput = document.getElementById('selected-seats');
      selectedSeatsInput.value = selectedSeatNumbers.join(',');
    });
  });
</script>

