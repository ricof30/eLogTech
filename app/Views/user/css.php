<style>
/* .card:hover {
    transform: scale(1.05); 
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2); 
} */
 *{
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    /* border: 1px solid green; */
    scroll-behavior: smooth;
 }

.weather-dashboard {
    margin-top: 20px;
}

.weather-icons i {
    transition: color 0.3s ease, transform 0.3s ease; /* Smooth transition */
}

.weather-icons i:hover {
    transform: scale(1.2); /* Icon enlarges slightly on hover */
}
.weather-box {
    width: 120px; /* Fixed width for all boxes */
    height: 120px; /* Fixed height for all boxes */
    border: 1px solid #ddd; /* Light border */
    border-radius: 10px; /* Rounded corners */
    background-color: #f8f9fa; /* Light background color */
    text-align: center; /* Center text and content */
    padding: 10px; /* Padding inside the box */
    margin: 0 auto; /* Center box horizontally */
}

.weather-box i {
    font-size: 2rem; /* Adjust icon size */
}

.weather-box p {
    margin: 0; /* Remove margin for better spacing */
}

.weather-box .font-weight-bold {
    margin-top: 0.5rem; /* Add space between value and label */
}


.card {
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    h4 {
        font-size: 1.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .button {
        background-color: #007bff; /* Bootstrap primary color */
        border-color: #007bff;
        transition: background-color 0.3s, border-color 0.3s;
    }

    .btn-primary:hover {
        background-color: #0056b3; /* Darker shade for hover */
        border-color: #0056b3;
    }

    /* Chart background styling */
    #waterlevel, #rainfallChart {
        background: linear-gradient(to right, #83a4d4, #b6fbff); /* Original background */
        border-radius: 20px;
    }
    .filter{
        border-radius: 30px; 
        background-color: orange; 
        padding: 10px 20px; 
        font-family: 'Poppins', sans-serif; 
        border: none; 
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);"
    }

    .chat-box {
    position: fixed;
    bottom: 60px;
    left: 20px;
    width: 350px;
    z-index: 1100;
}

/* Chat message container */

/* Chat container */
.chat-messages {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

/* General message styling */
.chat-message {
    padding: 10px;
    border-radius: 15px;
    max-width: 70%;
    word-wrap: break-word;
    display: inline-block;
}

/* Owner messages (right-aligned) */
.chat-message.owner {
    align-self: flex-end;
    background-color: #007bff;
    color: white;
    text-align: left;
}

/* Other user's messages (left-aligned) */
.chat-message.other {
    align-self: flex-start;
    background-color: #f1f1f1;
    color: black;
    text-align: left;
}

/* Image style within messages */
.chat-message img {
    max-width: 100%;
    height: auto;
    border-radius: 10px;
}

/* Gallery Icon Style */
#uploadIcon {
    background: none;
    border: none;
    padding: 0;
    cursor: pointer;
}

/* Additional styling for message text */
.chat-message p {
    margin: 0;
}


.close {
    position: absolute; /* Positioning the close button */
    top: 10px; /* Adjust as necessary */
    right: 10px; /* Adjust as necessary */
    z-index: 1; /* Keep it on top of other elements */
}


/* Scroll styling for chat container */
#chatBox {
    height: 300px;
    overflow-y: scroll;
    padding: 10px;
}

@media (max-width: 768px) {
    #reportFloodChat {
        width: 90%; /* Ensure the chatbox fits smaller screens */
        bottom: 60px;
        left: 5%; /* Center it more on smaller screens */
    }

    #reportFloodButton {
        bottom: 10px;
        left: 10px; /* Adjust the position for mobile */
    }
}
/* Fullscreen modal */
.modal-fullscreen {
    z-index: 1055; /* Bring modal to the front */
}

/* Fullscreen image */


.modal-content {
    border: none; /* Remove border for a cleaner look */
}

.modal-body {
    position: relative; /* To position arrows */
    padding: 0; /* Remove padding */
}

#modalImage {
    max-height: 100vh; /* Full height image */
    max-width: 100%; /* Ensure it doesn't overflow */
    margin: auto; /* Center the image */
}

#prevImage,
#nextImage {
    background-color: rgba(255, 255, 255, 0.7); /* Semi-transparent background */
    border: none; /* No border */
    border-radius: 50%; /* Round buttons */
    font-size: 30px; /* Increase arrow size */
    color: black; /* Arrow color */
    z-index: 10; /* Above the image */
}

#prevImage:hover,
#nextImage:hover {
    background-color: rgba(255, 255, 255, 1); /* Full background on hover */
}




</style>
