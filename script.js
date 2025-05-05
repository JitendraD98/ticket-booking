function bookTicket(eventId) {
    const button = event.target || window.event.target;
    button.disabled = true; // disable button immediately to prevent multiple clicks
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "book_ticket.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    alert(response.message);
                    if (response.status === 'success') {
                        // update seats count without reload
                        const seatsSpan = document.getElementById('seats-' + eventId);
                        if (seatsSpan) {
                            let seats = parseInt(seatsSpan.textContent, 10);
                            seats = seats > 0 ? seats - 1 : 0;
                            seatsSpan.textContent = seats;
                            if (seats <= 0) {
                                // Disable button if no seats remain
                                button.disabled = true;
                            }
                        }
                    } else {
                        // Re-enable button if booking failed
                        button.disabled = false;
                    }
                } catch(e) {
                    alert('Unexpected response from server.');
                    button.disabled = false;
                }
            } else {
                alert('Server error. Please try again.');
                button.disabled = false;
            }
        }
    };
    xhr.send("event_id=" + encodeURIComponent(eventId));
}
