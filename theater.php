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

  // Get the selected seats from the request
  // $selectedSeats = $_POST['selectedSeats'];

  // Update the database to mark the selected seats as booked
  // $sql = "UPDATE seats SET is_booked = 1 WHERE seat_number IN (".implode(",", $selectedSeats).")";
  // if ($conn->query($sql) === TRUE) {
  //   echo "Seats booked successfully!";
  // } else {
  //   echo "Error updating record: " . $conn->error;
  // }

  $sql = "SELECT seat_number, is_booked FROM seats";
    $result = $conn->query($sql);

  // Close the database connection
  $conn->close();
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

