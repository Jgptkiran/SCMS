<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Futsal Booking</title>
    <link rel="stylesheet" href="booking.css">
</head>
<body>
    <h1>Futsal Booking Slots</h1>
    <form id="dateForm">
        <label for="bookingDate">Select Date:</label>
        <input type="date" id="bookingDate" name="bookingDate" required>
        <button type="submit">Show Slots</button>
    </form>
    <div id="dateNavigation">
        <button onclick="changeDate(-1)">Previous Day</button>
        <button onclick="changeDate(+1)">Next Day</button>
    </div>
    <table id="bookingTable">
        <thead>
            <tr>
                <th>Time</th>
                <th>Availability</th>
            </tr>
        </thead>
        <tbody>
            <!-- Rows will be added here by JavaScript -->
        </tbody>
    </table>
    <div id="bookingSummary">
        <p>Total Amount: $<span id="totalAmount">0</span></p>
        <button id="continueButton" disabled>Continue</button>
    </div>

    <script>
       document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            const bookingDateInput = document.getElementById('bookingDate');
            bookingDateInput.setAttribute('min', today);
            bookingDateInput.value = today;
            generateTimeSlots(today);
        });

        document.getElementById('dateForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const selectedDate = document.getElementById('bookingDate').value;
            generateTimeSlots(selectedDate);
        });

        const slotPrice = 50; // Price per 30-minute slot
        let selectedSlotsByDate = {};

        function generateTimeSlots(date) {
    const startTime = 9; // 9 AM
    const endTime = 21; // 9 PM
    const tableBody = document.querySelector('#bookingTable tbody');
    tableBody.innerHTML = ''; // Clear previous slots

    fetch(`futsal_get_booked_slots.php?date=${date}`)
        .then(response => response.json())
        .then(data => {
            // Ensure bookedSlots is an array
            const bookedSlots = Array.isArray(data.bookedSlots) ? new Set(data.bookedSlots) : new Set();

            for (let hour = startTime; hour < endTime; hour++) {
                for (let minute = 0; minute < 60; minute += 30) {
                    const nextMinute = minute + 30;
                    const start = `${String(hour).padStart(2, '0')}:${String(minute).padStart(2, '0')}`;
                    const endHour = nextMinute >= 60 ? hour + 1 : hour;
                    const endMinute = nextMinute >= 60 ? nextMinute - 60 : nextMinute;
                    const end = `${String(endHour).padStart(2, '0')}:${String(endMinute).padStart(2, '0')}`;
                    const slot = `${start} - ${end}`;
                    const row = document.createElement('tr');
                    row.innerHTML = `<td>${slot}</td><td class="${bookedSlots.has(slot) ? 'unavailable' : 'available'}" onclick="${bookedSlots.has(slot) ? '' : `toggleSlot(this, '${date}')`}">${slot}</td>`;
                    tableBody.appendChild(row);

                    // Restore selected slots for the current date
                    if (selectedSlotsByDate[date] && selectedSlotsByDate[date].includes(slot)) {
                        row.querySelector('td.available').classList.add('selected');
                    }
                }
            }

            updateSummary();
        })
        .catch(error => {
            console.error('Error fetching booked slots:', error);
        });
}



        function toggleSlot(cell, date) {
            const slot = cell.innerText;
            if (!selectedSlotsByDate[date]) {
                selectedSlotsByDate[date] = [];
            }

            if (cell.classList.contains('selected')) {
                cell.classList.remove('selected');
                selectedSlotsByDate[date] = selectedSlotsByDate[date].filter(s => s !== slot);
            } else {
                cell.classList.add('selected');
                selectedSlotsByDate[date].push(slot);
            }
            updateSummary();
        }

        function updateSummary() {
            let totalAmount = 0;
            for (const date in selectedSlotsByDate) {
                totalAmount += selectedSlotsByDate[date].length * slotPrice;
            }
            document.getElementById('totalAmount').innerText = totalAmount;
            document.getElementById('continueButton').disabled = totalAmount === 0;
        }

        function changeDate(days) {
            const bookingDateInput = document.getElementById('bookingDate');
            const currentDate = new Date(bookingDateInput.value);
            currentDate.setDate(currentDate.getDate() + days);
            const newDate = currentDate.toISOString().split('T')[0];
            bookingDateInput.value = newDate;
            generateTimeSlots(newDate);
        }

        document.getElementById('continueButton').addEventListener('click', function() {
            const selectedDate = document.getElementById('bookingDate').value;
            const queryString = `date=${selectedDate}&slots=${encodeURIComponent(selectedSlotsByDate[selectedDate].join(','))}&amount=${selectedSlotsByDate[selectedDate].length * slotPrice}`;
            window.location.href = `bookingDetails.php?${queryString}`;
        });
    </script>
</body>
</html>
