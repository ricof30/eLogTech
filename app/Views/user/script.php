 
        <!-- JavaScript Libraries -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="../assets2/lib/easing/easing.min.js"></script>
        <script src="../assets2/lib/waypoints/waypoints.min.js"></script>
        <script src="../assets2/lib/counterup/counterup.min.js"></script>
        <script src="../assets2/lib/owlcarousel/owl.carousel.min.js"></script>
        <script src="../assets2/lib/lightbox/js/lightbox.min.js"></script>
        <script>
    // Automatically close modal on form submission
    document.getElementById('profileForm').addEventListener('submit', function() {
        var profileModal = new bootstrap.Modal(document.getElementById('profileModal'));
        profileModal.hide(); // Close the modal
    });
</script>
<script>
    // Automatically close the modal when the form is submitted
    function closeModal(modalId) {
        var modalElement = document.getElementById(modalId);
        var modal = bootstrap.Modal.getInstance(modalElement);
        modalElement.addEventListener('submit', function () {
            modal.hide(); // Hide the modal
        });
    }
</script>

        <script>
    // Function to validate forms dynamically for both Water Level and Rainfall
    function validateForm(startDateId, endDateId, startDateErrorId, endDateErrorId) {
        const startDate = document.getElementById(startDateId).value;
        const endDate = document.getElementById(endDateId).value;
        let isValid = true;
        // Hide previous error messages
        document.getElementById(startDateErrorId).style.display = 'none';
        document.getElementById(endDateErrorId).style.display = 'none';

        // Check if start date is empty
        if (!startDate) {
            document.getElementById(startDateErrorId).style.display = 'block';
            isValid = false;
        }

        // Check if end date is empty
        if (!endDate) {
            document.getElementById(endDateErrorId).style.display = 'block';
            isValid = false;
        }

        return isValid; // If both dates are valid, the form will be submitted
    }
    // Event listeners to hide the error message when the user selects a date
    document.getElementById('rainStartDate').addEventListener('input', function() {
        if (this.value) {
            document.getElementById('rainStartDateError').style.display = 'none';
        }
    });
    document.getElementById('rainEndDate').addEventListener('input', function() {
        if (this.value) {
            document.getElementById('rainEndDateError').style.display = 'none';
        }
    });
    document.getElementById('waterStartDate').addEventListener('input', function() {
        if (this.value) {
            document.getElementById('waterStartDateError').style.display = 'none';
        }
    });
    document.getElementById('waterEndDate').addEventListener('input', function() {
        if (this.value) {
            document.getElementById('waterEndDateError').style.display = 'none';
        }
    });
</script>
<!-- script for chat features -->
<script>
  // Function to load messages
  function loadMessages() {
    $.ajax({
        url: '<?= base_url("/getMessages") ?>',
        method: 'GET',
        success: function(data) {
            $('#chatBox').html(''); // Clear the chatbox
            images = []; // Reset images array each time

            data.forEach(function(message) {
                let isOwner = message.username === '<?= esc($user['username']); ?>';
                let messageHtml = '';

                // If there's a message, display it
                if (message.message && message.message.trim() !== '') {
                    messageHtml += `<div class="chat-message ${isOwner ? 'owner' : 'other'}">`;
                    if (!isOwner) {
                        messageHtml += `<strong>${message.username}:</strong> `;
                    }
                    messageHtml += `${message.message}</div>`;
                }

                // If there's an image, display it
                if (message.image) {
                    images.push(message.image);

                    messageHtml += `<div class="chat-image ${isOwner ? 'owner' : 'other'}">
                                        <img src="${message.image}" alt="Image" style="max-width: 100%; height: auto; cursor: pointer;"
                                            onclick="openImageModal('${message.image}')">
                                    </div>`;
                }

                $('#chatBox').append(messageHtml);
            });
        }
    });
}


function toggleChatBox() {
    let reportFloodChat = $('#reportFloodChat');

    // Toggle the 'd-none' class to hide/show the element
    if (reportFloodChat.hasClass('d-none')) {
        reportFloodChat.removeClass('d-none'); // Show the chat
    } else {
        reportFloodChat.addClass('d-none'); // Hide the chat
    }
}

function openImageModal(imageUrl) {
    currentImageIndex = images.indexOf(imageUrl);
    $('#modalImage').attr('src', imageUrl);
    $('#downloadImageLink').attr('href', imageUrl);

    // Hide the reportFloodChat when the modal is shown
    $('#reportFloodChat').addClass('d-none');

    // Show the modal
    $('#imageModal').modal('show');
}

function prevImage() {
    currentImageIndex = (currentImageIndex > 0) ? currentImageIndex - 1 : images.length - 1;
    updateImageModal();
}

function nextImage() {
    currentImageIndex = (currentImageIndex < images.length - 1) ? currentImageIndex + 1 : 0;
    updateImageModal();
}

function updateImageModal() {
    $('#modalImage').attr('src', images[currentImageIndex]);
    $('#downloadImageLink').attr('href', images[currentImageIndex]);
}
$('#imageModal').on('hidden.bs.modal', function () {
    $('#reportFloodChat').removeClass('d-none'); 
});

    // Load messages every 2 seconds
    setInterval(loadMessages, 2000);

    // Send message via AJAX
    $('#chatForm').submit(function(e) {
        e.preventDefault();

        var messageContent = $('#message').val().trim();
    var imageContent = $('#image').val();

    if (messageContent === '' && imageContent === '') {
        // Prevent sending if both fields are empty
        alert("Please enter a message or upload an image.");
        return;
    }


        var formData = new FormData(this); // Use FormData to handle file uploads

        $.ajax({
            url: '<?= base_url("/sendMessage") ?>',
            method: 'POST',
            data: formData,
            contentType: false, // Important for file upload
            processData: false, // Important for file upload
            success: function(response) {
                $('#message').val(''); 
                $('#image').val(''); 
                loadMessages(); // Reload the messages after sending
            }
        });
    });

    // Initial load
    loadMessages();
</script>

<script>
    function showImageName() {
    const fileInput = document.getElementById('image');
    // const cameraInput = document.getElementById('camera');
    const imageNameDisplay = document.getElementById('imageName');

    if (fileInput.files.length > 0) {
        const fileName = fileInput.files[0].name; // Get the selected file's name
        imageNameDisplay.textContent = fileName; // Display the file name
    // } else if (cameraInput.files.length > 0) {
    //     const fileName = cameraInput.files[0].name; // Get the camera image's name
    //     imageNameDisplay.textContent = fileName; // Display the file name
    } else {
        imageNameDisplay.textContent = ''; // Clear the display if no file is selected
    }
}

</script>

        <!-- Template Javascript -->
        <script src="../assets/img/js/main.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <?= $this->include('/script');?>
        <?= $this->include('/script1');?>
