<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <div class="receipt-container">
            <h2>Receipt</h2>
            <table>
                <tr>
                    <th>Service</th>
                    <th>Amount</th>
                </tr>
                <tr>
                    <td>Airtime</td>
                    <td>$20.00</td>
                </tr>
                <tr>
                    <td>Cable</td>
                    <td>$50.00</td>
                </tr>
                <tr>
                    <td>Electricity</td>
                    <td>$30.00</td>
                </tr>
            </table>
            <p class="total">Total: $100.00</p>
        </div>
    </div>
</div>

<style>
    .receipt-container {
        /* max-width: 400px;
        margin: 20px auto;
        border: 1px solid #ccc;
        padding: 20px;
        border-radius: 10px; */
    }

    .receipt-container h2 {
        text-align: center;
        margin-bottom: 20px;
    }

    .receipt-container table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .receipt-container th,
    .receipt-container td {
        border: 1px solid #ccc;
        padding: 8px;
        text-align: left;
    }

    .receipt-container th {
        background-color: #f2f2f2;
    }

    .receipt-container .total {
        font-weight: bold;
    }

    /* Modal styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .modal-content {
        background-color: #fefefe;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
    }

    .close {
        color: #aaa;
        float: right !important;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }
</style>


<script>
    // Get the modal element
var modal = document.getElementById("myModal");

// Get the button that opens the modal
var btn = document.getElementById("{{ $modal }}");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal
btn.onclick = function() {
  modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
</script>