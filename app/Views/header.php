<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>e-LogTech</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="../assets/img/eLogTech.jpg" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Roboto:wght@500;700&display=swap" rel="stylesheet"> 
    
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="../assets/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="../assets/lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="../assets/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GTUB5TF8iE8Pyk0d5ttPhU3/1h9WqP1zIF9WlUV00lwNeTOqBNjUorMoQOaP8f6r" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto+Slab:wght@100..900&display=swap" rel="stylesheet">
  <style>
    @media only screen and (min-width:320px) and (max-width:450px) {
  th,td{
    font-size:12px;
  }
  h4{
    font-size:18px;
  }
  .numbers{
        font-family:"Kanit";
        font-size:15px;
        font-style:normal;
    }
        .dropdown-menu {
    left: 35% !important; /* Move the menu to the center horizontally */
    transform: translateX(-53%); /* Adjust the positioning to center the menu */
}
.contact{
  margin-left:65%;
  margin-top:0;
  height:10px;
  width:12px;

}
.contact_title{
  font-size:15px;
  font-family:"Kanit";

}
.signin{
  padding-left: 10px;
  padding-right: 10px;
}
#waterlevel{
  font-size:7px;
}
.piechart{
  height:200px;
}
}
@media only screen and (min-width:500px){
    th,td{
        font-family:"Kanit";
        font-size:100;
        font-style:normal;
    }
    .numbers{
        font-family:"Kanit";
        font-size:20px;
        font-style:normal;
    }
}
@media only screen and (min-width:500px) and (max-width:1280px) {
  th,td{
    font-size:15px;

  }
  h4{
    font-size:18px;
  }

 
}
.alerts_dropdown .dropdown-item:hover {
    background-color: #ADD8E6 !important; /* Keep the same background color on hover */
    color: #000 !important; /* Optional: Change the text color on hover */
}

.profile_dropdown .dropdown-item:hover {
    background-color: #E0FFFF !important; /* Keep the same background color on hover */
    color: #000 !important;
}

/* weather design */
.weather-dashboard {
    padding: 20px;
    background-color: #f0f8ff; /* Light background for contrast */
    border-radius: 10px; /* Rounded corners */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.weather-dashboard h3 {
    text-align: center;
    color: #333;
}

.weather-dashboard h6 {
    margin-top: 20px;
    color: #555;
}

.forecast-container {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap; /* Wrap items to new line if necessary */
}

.forecast-day {
    flex: 1 1 calc(25% - 10px); /* 4 items per row with spacing */
    margin: 5px;
    padding: 15px;
    background-color: #ffffff; /* White background for cards */
    border: 1px solid #ddd; /* Border for cards */
    border-radius: 5px; /* Slightly rounded corners */
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.current-icon, .forecast-icon {
    width: 50px; /* Set icon width */
    height: auto; /* Keep aspect ratio */
}

.forecast-day h6 {
    font-size: 16px;
    margin-bottom: 10px;
}

.forecast-day p {
    margin: 5px 0;
}
/* .weather-dashboard .card {
    transition: transform 0.2s;
}

.weather-dashboard .card:hover {
    transform: scale(1.05);
} */
 /* Chat container */





  </style>


</head>