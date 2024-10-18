<?php

namespace App\Controllers;
use Config\Database;
use CodeIgniter\RESTful\ResourceController;
use App\Models\ContactModel;
use App\Models\WaterLevelModel;
use App\Models\UserModel;
use App\Models\SentMessageModel;
use App\Models\ReceiveMessageModel;
use App\Models\RainFallModel;
use App\Models\StatusModel;
use App\Models\ReportModel;
use App\Models\SolarVoltageModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\IOFactory;
use TCPDF;
use Google\Client as GoogleClient;
use Google\Service\Oauth2;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\VAPID;




class Home extends BaseController
{

    public $clientID = '416774517161-ve653inuu3v308tbt8nl1blld4hnf4o9.apps.googleusercontent.com'; 
    public $clientSecret = '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'; 
    public $redirectURI = 'https://elogtech.elementfx.com/google-callback'; 

    private $apiKey = '63064c321974f9d7ff589ebd1773b5e6'; 
    private $latitude = '13.3765'; 
    private $longitude = '121.2269'; 

    public function index(): string
    {
        // Initialize the models
        $waterLevelModel = new WaterLevelModel();
        $solarVoltageModel = new SolarVoltageModel();
        $rainfallModel = new RainfallModel();
        $userModel = new UserModel();
    
        // Water level processing
        $waterLevels = $waterLevelModel->findAll();
        $monthlyWaterLevels = [];
        
        foreach ($waterLevels as $level) {
            $month = date('F', strtotime($level['date']));
            $waterLevel = $level['waterlevel'];
    
            if (!isset($monthlyWaterLevels[$month])) {
                $monthlyWaterLevels[$month] = ['total' => 0, 'low' => 0, 'moderate' => 0, 'high' => 0]; 
            }
    
            $monthlyWaterLevels[$month]['total']++;
    
            if ($waterLevel > 2.00 && $waterLevel <= 3.00) {
                $monthlyWaterLevels[$month]['high']++;
            } elseif ($waterLevel > 1.00 && $waterLevel <= 2.00) {
                $monthlyWaterLevels[$month]['moderate']++;
            } elseif ($waterLevel > 0 && $waterLevel <= 1.00) {
                $monthlyWaterLevels[$month]['low']++;
            }
        }
    
        // Solar voltage processing
        $solarVoltages = $solarVoltageModel->findAll();
        $dailyVoltages = [];
    
        foreach ($solarVoltages as $voltage) {
            $date = date('Y-m-d', strtotime($voltage['date']));
            if (!isset($dailyVoltages[$date])) {
                $dailyVoltages[$date] = 0;
            }
            $dailyVoltages[$date] += $voltage['voltage'];
        }
    
        // Rainfall processing
        $rainfalls = $rainfallModel->findAll(); 
        $rainfallData = $this->prepareRainfallData($rainfalls);
    
        // Fetch user data
        $currentUserId = session()->get('user_id'); 
        $user = $userModel->find($currentUserId);
    
        $weatherData = $this->getWeatherData();
    
        // Fetch additional data
        $messages = $this->getLastThreeMessages();
        $latestWaterLevel = $this->getLatestWaterLevel();
    
        // Return the view with the processed data
        return view('dashboard', [
            'monthlyWaterLevels' => $monthlyWaterLevels,
            'dailyVoltages' => $dailyVoltages,
            'rainfallData' => $rainfallData,
            'messages' => $messages,
            'latestWaterLevel' => $latestWaterLevel,
            'user' => $user,
            'weatherData' => $weatherData // Pass weather data to the view
        ]);
    }
    
    // Function to fetch weather data from OpenWeatherMap

    public function getWeatherData()
    {
        $apiKey = 'UtcResU4V8va0tluzhUl49qMGWQ57rAy'; // Replace with your actual API key
        $latitude = '13.3765';
        $longitude = '121.2269';
    
        $endTime = new \DateTime('now', new \DateTimeZone('UTC')); 
        $endTime->modify('+4 days');  // Modify to +3 days to get 4 days including today
        $endTimeString = $endTime->format('Y-m-d\TH:i:s\Z'); // Format for Tomorrow.io API
    
        $url = "https://api.tomorrow.io/v4/timelines?location={$latitude},{$longitude}&fields=temperature,precipitationIntensity,precipitationType,weatherCode,windSpeed,windDirection&units=metric&timesteps=1d&startTime=now&endTime={$endTimeString}&apikey={$apiKey}";
    
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
    
        if (curl_errno($ch)) {
            return ['error' => 'Unable to fetch weather data: ' . curl_error($ch)];
        }
    
        curl_close($ch);
    
        $weatherData = json_decode($response, true);
    
        // Extract the 4-day forecast data (current day + next 3 days)
        $forecast = [];
        if (isset($weatherData['data']['timelines'][0]['intervals'])) {
            foreach ($weatherData['data']['timelines'][0]['intervals'] as $index => $interval) {
                // Get the current date in UTC
                $currentDate = (new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d');
    
                // Format the date from the API response
                $forecastDate = date('Y-m-d', strtotime($interval['startTime']));
    
                // Only include the current day and the next three days
                if ($forecastDate >= $currentDate && count($forecast) < 4) {
                    $forecast[$forecastDate] = [
                        'temperature' => $interval['values']['temperature'],
                        'precipitationIntensity' => $interval['values']['precipitationIntensity'],
                        'precipitationType' => $interval['values']['precipitationType'],
                        'weatherCode' => $interval['values']['weatherCode'],
                        'windSpeed' => $interval['values']['windSpeed'],
                        'windDirection' => $interval['values']['windDirection'],
                    ];
                }
            }
        }
    
        return $forecast;
    }
    
    



    public function googleAuth()
    {
        $client = new GoogleClient();
        $client->setClientId($this->clientID);
        $client->setClientSecret($this->clientSecret);
        $client->setRedirectUri($this->redirectURI);
        $client->addScope('email');
        $client->addScope('profile');

        $authUrl = $client->createAuthUrl();

        return redirect()->to($authUrl); // Redirect to Google's authorization URL
    }

   public function callback()
{
    $client = new GoogleClient();
    $client->setClientId($this->clientID);
    $client->setClientSecret($this->clientSecret);
    $client->setRedirectUri($this->redirectURI);

    $code = $this->request->getVar('code');
    $token = $client->fetchAccessTokenWithAuthCode($code);

    if (!isset($token['error'])) {
        $client->setAccessToken($token['access_token']);
        $oauth2 = new Oauth2($client);
        $userInfo = $oauth2->userinfo->get();

        // Process user information (e.g., save to database)
        $this->loginUser($userInfo);    

        // Redirect to dashboard or wherever you want after successful login
        return redirect()->to('/'); // Make sure this points to your main dashboard
    } else {
        // Handle error
        echo 'Error fetching token';
    }
}

   private function loginUser($userInfo)
{
    $image = 'user logo_2.jpg'; 
    $userModel = new UserModel();
    $existingUser = $userModel->where('email', $userInfo->email)->first();

    if (!$existingUser) {
        // Insert the user into the database if they don't exist
        $userData = [
            'id' => $userInfo->id, // Store Google ID if necessary
            'email' => $userInfo->email,
            'username' => $userInfo->name,
            'image' => $image, // Optional if you want to store profile picture
            'role' => "user"
        ];

        // Insert the user data
        $userModel->insert($userData);
        
        // Get the newly created user
        $existingUser = $userModel->where('email', $userInfo->email)->first(); // Fetch the user again
    }

    // Create the session data after inserting/fetching the user
    session()->set([
        'user_id' => $existingUser['id'],
        'email' => $existingUser['email'],
        'name' => $existingUser['username'],
        'profile_pic' => $userInfo->picture,
        'is_logged_in' => true, // Make sure to set this to indicate the user is logged in
    ]);

    // Log the user information (for debugging)
    echo "<pre>";
    print_r($userInfo);
    echo "</pre>";
}

    
   
    public function alertHistory()
    {
        {
            $model = new WaterLevelModel();
        
            // Fetch the latest water levels and order by date and time correctly
            $data['latestWaterLevel'] = $model->orderBy('date', 'desc') 
                                        ->orderBy('time', 'desc') 
                                        ->limit(5)
                                        ->findAll();
            
            $userModel = new UserModel();
            
            // Get the current user ID from the session or other method
            $currentUserId = session()->get('user_id'); // Make sure the session is initialized
            $messages = $this->getLastThreeMessages();
            $latestWaterLevel = $this->getLatestWaterLevel();
            
            // Fetch the specific user data
            $user = $userModel->find($currentUserId);
            
            // Return the view with the latest water level data and user info
            return view('alertHistory', [
                'latestWaterLevels' => $data['latestWaterLevel'],
                'messages' => $messages,
                'latestWaterLevel' => $latestWaterLevel,
                'user' => $user
            ]);
        }
        
        }



    private function getLastThreeMessages()
    {
        $model = new ReceiveMessageModel();
        return $model->orderBy('date', 'desc') 
                     ->orderBy('time', 'desc') 
                     ->limit(3)
                     ->findAll();
    }

    public function getLatestWaterLevel()
{
    $waterLevelModel = new WaterLevelModel();
    return $waterLevelModel->orderBy('date', 'desc')
                            ->orderBy('time', 'desc')
                          ->limit(5)
                          ->findAll();

}

    private function prepareRainfallData($rainfalls)
    {
        
        $monthlyRainfall = array_fill_keys(range(1, 12), 0);

       
        foreach ($rainfalls as $rainfall) {
            $month = date('n', strtotime($rainfall['date'])); 
            $monthlyRainfall[$month] += $rainfall['rainfall']; 
        }

        $rainfallCounts = array_values($monthlyRainfall);

        return $rainfallCounts;
    }
    

    public function contact()
    {
        $model = new ContactModel();
        $data['contact'] = $model->findAll();
        $userModel = new UserModel();
    
        // Assume you have a way to get the current user ID, e.g., from session
        $currentUserId = session()->get('user_id'); // Replace this with your method of retrieving the current user ID
        $messages = $this->getLastThreeMessages();
        $latestWaterLevel = $this->getLatestWaterLevel();
        
        // Fetch the specific user data
        $user = $userModel->find($currentUserId);
        return view('contact', [
            'contact' => $data['contact'],
            'messages' => $messages,
            'latestWaterLevel' => $latestWaterLevel,
            'user' => $user
        ]);
    }

    public function add_contact(){
        if ($this->request->getMethod() === 'post') {
            $phoneNumber = $this->request->getPost('phoneNumber');
            $model = new ContactModel();
            $data = [
                'phone_number' => $phoneNumber,
            ];
           $model->insert($data);
            session()->setFlashdata('success', 'Added successfully');
            } else { 
                session()->setFlashdata('error', 'Adding failed');
            }
            return redirect()->to('/contact'); 
    }

    
    public function update()
    {
        $id = $this->request->getPost('id');
        $phoneNumber = $this->request->getPost('phoneNumber');
        $status = $this->request->getPost('status');
    
        $model = new ContactModel();
        $data = ['phone_number' => $phoneNumber, 'status' => $status];
    
        if ($model->update($id, $data)) {
            session()->setFlashdata('success', 'Updated successfully');
        } else { 
            session()->setFlashdata('error', 'Update failed');
        }
        return redirect()->to('/contact'); 
    }

    public function delete_contact($id){
        $model = new ContactModel();
      if($model->delete($id)) {
         session()->setFlashdata('success', 'Deleted successfully');
    } else { 
        session()->setFlashdata('error', 'Delete failed');
    }
         return redirect()->to('/contact');
    }

    public function signin(){
        return view('signin');
    }

    public function getUser(){
        $model = new UserModel();
        $currentUserId = session()->get('user_id');
        $messages = $this->getLastThreeMessages();
        $latestWaterLevel = $this->getLatestWaterLevel();
        $user = $model->find($currentUserId);
        $data['user'] = $model->findAll();

        return view('user_list', [
            'list' => $data['user'],
            'messages' => $messages,
            'latestWaterLevel' => $latestWaterLevel,
            'user' => $user
        ]);
    }
    public function adminSignin() {
        if ($this->request->getMethod() === 'post') {
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');
            
            $model = new UserModel();
            $user = $model->where('email', $email)->first();
            
            if ($user) {
                // Check if the user is verified
                if ($user['is_verified'] == 0) {
                    session()->setFlashdata('error', 'Your email is not verified!');
                    return redirect()->to('/signin'); // Redirect to the login page
                }
    
                // Check if the password matches
                if ($password === $user['password']) {
                    if($user['role'] == "user"){
                        $sessionData = [
                            'user_id' => $user['id'],
                            'email' => $user['email'],
                            'is_logged_in' => true,
                            'role' => "user"
                        ];
                        session()->set($sessionData);
        
                        return redirect()->to('/'); 
                    }else{
                        $sessionData = [
                            'user_id' => $user['id'],
                            'email' => $user['email'],
                            'is_logged_in' => true,
                            'role' => "admin"
                        ];
                        session()->set($sessionData);
        
                        return redirect()->to('/dashboard'); 
                    }
                    
                } else {
                    session()->setFlashdata('error', 'Incorrect Username or Password');
                    return redirect()->to('/signin');
                }
            } else {
                session()->setFlashdata('error', 'Email is not registered');
                return redirect()->to('/signin');
            }
        }
    }
    

    public function login()
    {
        $client = new GoogleClient();
        $client->setClientId($this->clientId);
        $client->setClientSecret($this->clientSecret);
        $client->setRedirectUri($this->redirectUri);
        $client->addScope("email");
        $client->addScope("profile");

        // Redirect to Google login page
        $authUrl = $client->createAuthUrl();
        return redirect()->to($authUrl);
    }

public function signUp()
{
    $model = new UserModel();
    $name = $this->request->getPost('name');
    $contact = $this->request->getPost('contact');
    $email = $this->request->getPost('email');
    $password = $this->request->getPost('password');
    $confirm_pass = $this->request->getPost('confirm_pass');

    $user = $model->where('email', $email)->first();
    if ($user) {
        session()->setFlashdata('error', 'Email already registered!');
        return redirect()->to('/signin');
    } else {
        $otp = rand(100000, 999999); 
        $data = [
            'username' => $name,
            'contact' => $contact,
            'email' => $email,
            'password' => $password,  
            'image' => 'user logo_4.jpg',
            'otp' => $otp,  
            'is_verified' => 0, 
            'role' => "user"
        ];

        $model->insert($data);

        if ($this->sendOTPEmail($email, $otp)) {
            session()->setFlashdata('success', 'Registration successful. Please check your email to verify your account.');
            return redirect()->to('/verify_otp?email=' . urlencode($email));
        } else {
            session()->setFlashdata('error', 'Failed to send OTP email. Please try again.');
            return redirect()->back();
        }
    }
}

// Function to send the OTP email using PHPMailer
public function sendOTPEmail($email, $otp)
{
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();                                           // Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                  // Enable SMTP authentication
        $mail->Username   = 'ricofontecilla30@gmail.com';              // SMTP username
        $mail->Password   = 'ngnp jppg dmsm sdyx';                 // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;        // Enable TLS encryption
        $mail->Port       = 587;                                   // TCP port to connect to

        //Recipients
        $mail->setFrom('ricofontecilla30@example.com', 'e-LogTech');
        $mail->addAddress($email);    // Add recipient

        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'OTP for Email Verification';
        $mail->Body    = 'Your OTP for email verification is: <b>' . $otp . '</b>';

        $mail->send();
        return true;
    } catch (Exception $e) {
        log_message('error', 'Mail error: ' . $mail->ErrorInfo);
        return false;
    }
}

public function verifyOTP()
{
    $model = new UserModel();
    $otp = $this->request->getPost('otp');
    $email = $this->request->getPost('email');

    $user = $model->where('email', $email)->first();

    if ($user && $user['otp'] == $otp) {
        // Update the user status to verified
        $model->update($user['id'], ['is_verified' => 1, 'otp' => null]);
        session()->setFlashdata('success', 'Your email has been verified. You can now log in.');
        return redirect()->to('/signin');
    } else {
        session()->setFlashdata('error', 'Invalid OTP. Please try again.');
        return redirect()->back();
    }
}

    
public function otp()
{
    $email = $this->request->getGet('email');  // Retrieve the email from the query string
    return view('otp', ['email' => $email]);   // Pass the email to the view
}

    
    
    

    public function message() {
    
        $messageModel = new ReceiveMessageModel();
        $distinctMessages = $messageModel->select('phone_number, MAX(id) as max_id')
                                        ->groupBy('phone_number')
                                        ->findAll();
    
        $messages = [];
        foreach ($distinctMessages as $message) {
            $latestMessage = $messageModel->where('phone_number', $message['phone_number'])
                                          ->orderBy('id', 'DESC')
                                          ->first();
            $messages[] = $latestMessage;
        }
    
        $data = [
            'distinctMessages' => $messages
        ];
        $userModel = new UserModel();
    
        // Assume you have a way to get the current user ID, e.g., from session
        $currentUserId = session()->get('user_id'); // Replace this with your method of retrieving the current user ID
        
        // Fetch the specific user data
        $user = $userModel->find($currentUserId);
        return view('message', [
            'distinctMessages' => $messages,
            'user' => $user
        ]);
    
    }
    
    public function show()
    {
        $messageModel = new ReceiveMessageModel();
        $phone_number = $this->request->getGet('phone_number'); // Use getGet to retrieve query parameters
        $messages = $messageModel->where('phone_number', $phone_number)->findAll();
    
        $userModel = new UserModel();
        $currentUserId = session()->get('user_id');
        $user = $userModel->find($currentUserId);
    
        return view('showMessage', [
            'phone_number' => $phone_number,
            'messages' => $messages,
            'user' => $user
        ]);
    }
    

public function sentMessage(){
    $sentSMS = new SentMessageModel();
    $data['sentSMS'] = $sentSMS->orderBy('date', 'desc') 
                                ->orderBy('time', 'desc') 
                                ->limit(5)
                                ->findAll();
    $userModel = new UserModel();
    
    // Assume you have a way to get the current user ID, e.g., from session
    $currentUserId = session()->get('user_id'); // Replace this with your method of retrieving the current user ID
    $messages = $this->getLastThreeMessages();
    $latestWaterLevel = $this->getLatestWaterLevel();
    
    // Fetch the specific user data
    $user = $userModel->find($currentUserId);
    return view('sent_message', [
        'sent' => $data['sentSMS'],
        'messages' => $messages,
        'latestWaterLevel' => $latestWaterLevel,
        'user' => $user
    ]);
}
        
public function deleteMessage($id)
{
    $model = new ReceiveMessageModel();
    if ($model->delete($id)) {
        session()->setFlashdata('success', 'Message deleted successfully');
    } else {
        session()->setFlashdata('error', 'Failed to delete message');
    }
    return redirect()->back();
}
public function deleteSentMessage($id)
{
    $model = new SentMessageModel();
    if ($model->delete($id)) {
        session()->setFlashdata('success', 'Message deleted successfully');
    } else {
        session()->setFlashdata('error', 'Failed to delete message');
    }
    return redirect()->to('/sentMessage');
}

    public function deleteByPhoneNumber($phoneNumber)
{
    $model = new ReceiveMessageModel();
    if ($model->where('phone_number', $phoneNumber)->delete()) {
        session()->setFlashdata('success', 'Deleted successfully');
    } else {
        session()->setFlashdata('error', 'Delete failed');
    }
    return redirect()->to('/messages');
}


public function filterWaterlevel()
{
    $model = new WaterLevelModel();

    $startDate = $this->request->getPost('water_start_date');
    $endDate = $this->request->getPost('water_end_date');

    $formattedStartDate = date("F d, Y", strtotime($startDate));
    $formattedEndDate = date("F d, Y", strtotime($endDate));

    $data = $model->select('time, date, waterlevel')
                  ->where('date >=', $startDate)
                  ->where('date <=', $endDate)
                  ->findAll();

    // Calculate summary statistics
    $waterLevels = array_column($data, 'waterlevel');
    $minWaterLevel = min($waterLevels);
    $maxWaterLevel = max($waterLevels);
    $avgWaterLevel = array_sum($waterLevels) / count($waterLevels);

    // Create a new PDF instance
    $pdf = new TCPDF();
    $pdf->AddPage();

    // Set PDF metadata
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('eLogTech');
    $pdf->SetTitle('Water Level Data');
    $pdf->SetSubject('Water Level Data');

    // Add logo and center it with the title
    // $logoPath = 'assets/img/eLogTech.png'; // Adjust the logo path as needed
    // $pdf->Image($logoPath, '', '', 30, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
    // Set the Y position so the image and text align properly
    $pdf->SetY(10);

    // Insert logo
    $logoPath = 'assets/img/eLogTech.png'; // Adjust the logo path as needed
    $pdf->Image($logoPath, 15, 10, 20, 20, 'PNG'); // Logo positioned with a specific width and height

    // Move X position to the right of the logo to place the text
    $pdf->SetXY(40, 15); // Adjust X and Y to position the text beside the logo

    // Add title beside the logo
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->Cell(0, 10, 'eLogTech: Arangin Flood Monitoring System', 0, 1, 'L');

    // Add a line break
    $pdf->Ln(10);

    // Title and Date Range
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(0, 10, 'Water Level Data Report', 0, 1, 'C');
    $pdf->Ln(5); // Add some space


    // Introduction
    $pdf->SetFont('helvetica', '', 12);
    $introduction = "This report summarizes water level measurements from " . $formattedStartDate . " to " . $formattedEndDate . " in Barangay Arangin.";
    $pdf->MultiCell(0, 10, $introduction, 0, 'C');
    $pdf->Ln(5); // Add some space

    // Center the table
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(60, 10, 'Time', 1, 0, 'C'); // Center align header
    $pdf->Cell(60, 10, 'Date', 1, 0, 'C');
    $pdf->Cell(60, 10, 'Water Level (m)', 1, 1, 'C'); // Last parameter '1' to move to next line

    // Add table data and center the text in each cell
    $pdf->SetFont('helvetica', '', 10);
    foreach ($data as $row) {
        $formattedTime = date('h:i A', strtotime($row['time']));
        $formattedDate = date('F j, Y', strtotime($row['date']));
        $pdf->Cell(60, 10, $formattedTime, 1, 0, 'C'); // Center align cell
        $pdf->Cell(60, 10, $formattedDate, 1, 0, 'C');
        $pdf->Cell(60, 10, $row['waterlevel'], 1, 1, 'C');
    }

    // Summary Statistics
    // Add some space
    $pdf->AddPage();
    // Add a line break for space
    $pdf->Ln(5);
    // Set font for headers
    $pdf->SetFont('helvetica', 'B', 12);
    // Summary Statistics Header
    $pdf->Cell(90, 10, 'Summary Statistics:', 0, 0, 'L');
    // Contact Information Header
    $pdf->SetX(110); // Move to the right side
    $pdf->Cell(90, 10, 'Contact Information:', 0, 1, 'L');
    // Set font for body
    $pdf->SetFont('helvetica', '', 10);
    // Minimum Water Level
    $pdf->Cell(90, 10, 'Minimum Water Level: ' . $minWaterLevel . ' m', 0, 0, 'L');
    // Contact Name
    $pdf->SetX(110);
    $pdf->Cell(90, 10, 'eLogTech', 0, 1, 'L');
    // Maximum Water Level
    $pdf->Cell(90, 10, 'Maximum Water Level: ' . $maxWaterLevel . ' m', 0, 0, 'L');
    // Contact Email
    $pdf->SetX(110);
    $pdf->Cell(90, 10, 'Email: ricofontecilla30@gmail.com', 0, 1, 'L');
    // Average Water Level
    $pdf->Cell(90, 10, 'Average Water Level: ' . round($avgWaterLevel, 2) . ' m', 0, 0, 'L');
    // Contact Phone
    $pdf->SetX(110);
    $pdf->Cell(90, 10, 'Phone: 09983664558', 0, 1, 'L');
    

    // Output the PDF
    $filename = 'water_level_data_' . date('Y-m-d') . '.pdf';
    $pdf->Output($filename, 'D'); // 'D' for download
    exit;
}

    public function filterRainfall()
    {
        $model = new RainfallModel();
    
        $startDate = $this->request->getPost('rain_start_date');
        $endDate = $this->request->getPost('rain_end_date');
    
        $formattedStartDate = date("F d, Y", strtotime($startDate));
        $formattedEndDate = date("F d, Y", strtotime($endDate));

        // Fetch data from the database
        $data = $model->select('date, SUM(rainfall) as total_rainfall, SUM(duration) as duration') 
            ->where('date >=', $startDate)
            ->where('date <=', $endDate)
            ->groupBy('date')
            ->findAll();
    
        // Calculate summary statistics
        $totalRainfall = 0;
        $totalDuration = 0;
        foreach ($data as $row) {
            $totalRainfall += $row['total_rainfall'];
            $totalDuration += $row['duration'];
        }
    
        // Create a new PDF instance
        $pdf = new TCPDF();
        $pdf->AddPage();
    
        // Set PDF metadata
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('eLogTech');
        $pdf->SetTitle('Rainfall Data');
        $pdf->SetSubject('Rainfall Data Export');
    
        
    // Add logo and center it with the title
    // $logoPath = 'assets/img/eLogTech.png'; // Adjust the logo path as needed
    // $pdf->Image($logoPath, '', '', 30, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
    // Set the Y position so the image and text align properly
    $pdf->SetY(10);

    // Insert logo
    $logoPath = 'assets/img/eLogTech.png'; // Adjust the logo path as needed
    $pdf->Image($logoPath, 15, 10, 20, 20, 'PNG'); // Logo positioned with a specific width and height

    // Move X position to the right of the logo to place the text
    $pdf->SetXY(40, 15); // Adjust X and Y to position the text beside the logo

    // Add title beside the logo
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->Cell(0, 10, 'eLogTech: Arangin Flood Monitoring System', 0, 1, 'L');

    // Add a line break
    $pdf->Ln(10);

    // Title and Date Range
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(0, 10, 'RAINFALL DATA REPORT', 0, 1, 'C');
    $pdf->Ln(5); // Add some space


    // Introduction
    $pdf->SetFont('helvetica', '', 12);
    $introduction = "This report summarizes rainfall measurements from " . $formattedStartDate . " to " . $formattedEndDate . " in Barangay Arangin.";
    $pdf->MultiCell(0, 10, $introduction, 0, 'C');
    $pdf->Ln(5); // Add some space
        // Add table header
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(47, 10, 'Date', 1, 0, 'C'); // Changed 'Time' to 'Date'
        $pdf->Cell(47, 10, 'Time', 1, 0, 'C');
        $pdf->Cell(47, 10, 'Rainfall', 1, 0, 'C');
        $pdf->Cell(47, 10, 'Duration', 1, 0, 'C');
        $pdf->Ln();
        
        // Add table data
        $pdf->SetFont('helvetica', '', 11);
        foreach ($data as $row) {
            $formattedDate = date('F j, Y', strtotime($row['date']));
            $formattedTime = date('g:i a', strtotime($row['date'])); // Format: 7:42.05 pm
        
            // Convert duration from milliseconds to a readable format
            $durationInSeconds = $row['duration'] / 1000;
            $duration = '';
            if ($durationInSeconds >= 3600) {
                $hours = floor($durationInSeconds / 3600);
                $minutes = floor(($durationInSeconds % 3600) / 60);
                $duration = "{$hours} hours {$minutes} minutes";
            } elseif ($durationInSeconds >= 60) {
                $minutes = floor($durationInSeconds / 60);
                $seconds = $durationInSeconds % 60;
                $duration = "{$minutes} minutes {$seconds} seconds";
            } else {
                $duration = "{$durationInSeconds} seconds";
            }
        
            $pdf->Cell(47, 10, $formattedDate, 1, 0, 'C');
            $pdf->Cell(47, 10, $formattedTime, 1, 0, 'C');
            $pdf->Cell(47, 10, $row['total_rainfall'], 1, 0, 'C');
            $pdf->Cell(47, 10, $duration, 1, 0, 'C');
            $pdf->Ln();
        }
    
      // Add summary statistics under the table
        $pdf->Ln(10); // Add some space

        // Add a line break for space
        $pdf->Ln(5);

        // Set font for headers
        $pdf->SetFont('helvetica', 'B', 12);

        // Summary Statistics Header
        $pdf->Cell(90, 10, 'Summary Statistics:', 0, 0, 'L');

        // Contact Information Header
        $pdf->SetX(110); // Move to the right side
        $pdf->Cell(90, 10, 'Contact Information:', 0, 1, 'L');

        // Set font for body
        $pdf->SetFont('helvetica', '', 10);

        // Total Rainfall
        $pdf->Cell(90, 10, 'Total Rainfall: ' . $totalRainfall , 0, 0, 'L');

        // Contact Name
        $pdf->SetX(110);
        $pdf->Cell(90, 10, 'eLogTech', 0, 1, 'L');

        // Total Duration (convert to readable format first)
        $totalDurationSeconds = $totalDuration / 1000;
        $summaryDuration = '';
        if ($totalDurationSeconds >= 3600) {
            $hours = floor($totalDurationSeconds / 3600);
            $minutes = floor(($totalDurationSeconds % 3600) / 60);
            $summaryDuration = "{$hours} hours {$minutes} minutes";
        } elseif ($totalDurationSeconds >= 60) {
            $minutes = floor($totalDurationSeconds / 60);
            $seconds = $totalDurationSeconds % 60;
            $summaryDuration = "{$minutes} minutes {$seconds} seconds";
        } else {
            $summaryDuration = "{$totalDurationSeconds} seconds";
        }

        // Total Duration
        $pdf->Cell(90, 10, 'Total Duration: ' . $summaryDuration, 0, 0, 'L');

        // Contact Email
        $pdf->SetX(110);
        $pdf->Cell(90, 10, 'Email: ricofontecilla30@gmail.com', 0, 1, 'L');

        // Additional contact information
        $pdf->Cell(90, 10, '', 0, 0, 'L');  // Empty cell for alignment
        $pdf->SetX(110);
        $pdf->Cell(90, 10, 'Phone: 09983664558', 0, 1, 'L');

        // Output the PDF
        $filename = 'rainfall_data_' . date('Y-m-d') . '.pdf';
        $pdf->Output($filename, 'D'); // 'D' for download
        exit;
    }
  
    public function auth()
    {   
        if (!session()->get('is_logged_in')) {
            return redirect()->to('/signin')->with('error', 'You must be logged in to access this page.');
        }
    
        return view('dashboard/index');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/signin');
    }
    
    public function viewStatus()
{
    $userModel = new UserModel();
    $statusModel = new StatusModel();
    
    // Custom query to get the latest status per sensor_type
    $builder = $statusModel->builder();
    $builder->select('sensor_type, status, last_update')
            ->whereIn('sensor_id', function($subquery) {
                // Subquery to get the latest sensor_id for each sensor_type
                $subquery->select('MAX(sensor_id)')
                         ->from('sensors')
                         ->groupBy('sensor_type');
            })
            ->orderBy('last_update', 'DESC'); // Optional: Order by latest updates
            $messages = $this->getLastThreeMessages();
            $latestWaterLevel = $this->getLatestWaterLevel();

    // Fetch the latest statuses
    $getStatus['statuses'] = $builder->get()->getResultArray();
    
    // Fetch the specific user data
    $currentUserId = session()->get('user_id');
    $user = $userModel->find($currentUserId);

    return view('status', [
        'statuses' => $getStatus['statuses'],
        'messages' => $messages,
        'latestWaterLevel' => $latestWaterLevel,
        'user' => $user
    ]);
}


public function updateProfile()
{
    $userModel = new UserModel();

    // Get user ID from session or other source
    $userId = session()->get('user_id');
    $username = $this->request->getPost('username');
    $contact = $this->request->getPost('contact');
    
    // Get current user data
    $user = $userModel->find($userId);

    // Handle file upload
    $profilePhoto = $this->request->getFile('profilePhoto');
    $currentPhoto = $user['image']; // Get current photo name
    
    if ($profilePhoto->isValid() && !$profilePhoto->hasMoved()) {
        // Use the original name of the file
        $profilePhotoName = $profilePhoto->getName();
        $profilePhoto->move(ROOTPATH . 'public/assets/img', $profilePhotoName);

        // Delete old photo if it exists
        if ($currentPhoto && file_exists(ROOTPATH . 'public/assets/img/' . $currentPhoto)) {
            unlink(ROOTPATH . 'public/assets/img/' . $currentPhoto);
        }
    } else {
        // If no new photo uploaded, keep the current one
        $profilePhotoName = $currentPhoto;
    }

    // Update username and contact only if they've changed
    $profileUsername = ($user['username'] != $username) ? $username : $user['username'];
    $profileContact = ($user['contact'] != $contact) ? $contact : $user['contact'];

    // Prepare data for update
    $updateData = [
        'image' => $profilePhotoName,
        'username' => $profileUsername,
        'contact' => $profileContact,
    ];

    // Update the user's information in the database
    $userModel->update($userId, $updateData);
    session()->setFlashdata('success', 'Profile Updated Successfully');
    return redirect()->to('/');
}



    public function receiveData()
    {

        $data = $this->request->getPost();
        
        $water_level = $data['water_level'] ?? null;
    
        if ($water_level !== null) {
            $waterModel = new WaterLevelModel();
            $data = [
                'time' => date('H:i:s'), // Current time
                'date' => date('Y-m-d'), // Current date
                'waterlevel' => $water_level,
            ];
            $waterModel->insert($data);
    
            // Log water level insertion
            log_message('info', 'Water level data inserted: ' . json_encode($data));
        }

        receiveDuration();
        receiveMessage();
        receiveRainStatus();
        receiveUltraSonic();
        return $this->respond(['status' => 'success', 'message' => 'Data received and inserted into the database.']);
    }

    
    public function receiveDuration(){
        $data = $this->request->getPost();

        $rain_duration = $data['duration'] ?? null;

        if ($rain_duration !== null) {
            $rainfallModel = new RainFallModel();
            $data = [
                'date' => date('Y-m-d'),
                'rainfall' => 1, // Use appropriate field for rainfall
                'duration' => $rain_duration, // Set duration as needed
            ];
            $rainfallModel->insert($data);
            
            // Log rainfall insertion
            log_message('info', 'Rainfall data inserted: ' . json_encode($data));
        }
    }

        public function receiveUltraSonic(){
            $data = $this->request->getPost();

            $ultrasonic_status = $data['ultrasonic_status'] ?? null;

            if ($ultrasonic_status !== null) {
                $model = new StatusModel();
                $data = [
                    'sensor_type' => $sensor_type,
                    'status' => $trimmed_status,
                    'last_update' => date('Y-m-d H:i:s'),
                ];
                $model->insert($data);
          
            }
        }

            public function receiveRainStatus(){
                $data = $this->request->getPost();

                $rain_status = $data['rain_status'] ?? null;
    
                if ($rain_status !== null) {
                    $model = new StatusModel();
                    $data = [
                        'sensor_type' => $sensor_type,
                        'status' => $trimmed_status,
                        'last_update' => date('Y-m-d H:i:s'),
                    ];
                    $model->insert($data);
              
                }
            }

            public function receiveMessage(){
                $data = $this->request->getPost();
    
                $message = $data['message'] ?? null;
    
                if ($message !== null) {
                    $model = new SenMessageModel();
                    $data = [
                        'message' => $message,
                        'time' => date('H:i:s'), // Current time
                        'date' => date('Y-m-d'), // Current date
                    ];
                    $model->insert($data);
              
                }
            }
    


    private function updateSensorStatus($sensor_type, $status, $db)
    {
        // Check the current status of the sensor
        $builder = $db->table('sensors');
        $builder->where('sensor_type', $sensor_type);
        $builder->orderBy('last_update', 'DESC');
        $sensor = $builder->get()->getRow();

        $trimmed_status = substr($status, 0, 50); // Trim the status to 50 characters

        if ($sensor) {
            $current_status = $sensor->status;

            if ($current_status !== $trimmed_status) {
                $statusModel = new StatusModel();
                $data = [
                     'sensor_type' => $sensor_type,
                    'status' => $trimmed_status,
                    'last_update' => date('Y-m-d H:i:s'),
                ];
               $model->insert($data);

                // Log status change
                log_message('info', "Sensor {$sensor_type} status changed to {$trimmed_status} and inserted into database.");

                if ($current_status === 'inactive' && $trimmed_status === 'active') {
                    log_message('info', "Sensor {$sensor_type} is now active.");
                } elseif ($current_status === 'active' && $trimmed_status === 'inactive') {
                    log_message('info', "Sensor {$sensor_type} is now inactive.");
                }
            } else {
                log_message('info', "Sensor status remains unchanged: {$sensor_type} - {$trimmed_status}");
            }
        } else {
            // If sensor not found, insert a new record
            $statusModel = new StatusModel();
                $data = [
                     'sensor_type' => $sensor_type,
                    'status' => $trimmed_status,
                    'last_update' => date('Y-m-d H:i:s'),
                ];
               $model->insert($data);
            log_message('info', "Sensor {$sensor_type} not found in the database. Inserted new record with status {$trimmed_status}.");
        }
    }

    public function getWeather()
    {
        // OpenWeatherMap API endpoint with latitude and longitude
        $apiUrl = "https://api.openweathermap.org/data/2.5/weather?lat={$this->latitude}&lon={$this->longitude}&appid={$this->apiKey}&units=metric";

        // Initialize cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        $result = curl_exec($ch);
        curl_close($ch);

        // Decode the JSON response into an array
        $weatherData = json_decode($result, true);

        // Check if the API returned data successfully
        if (isset($weatherData['main'])) {
            // Extract needed weather details
            $data = [
                'location' => 'Arangin, Naujan, Oriental Mindoro',
                'temperature' => $weatherData['main']['temp'],
                'humidity' => $weatherData['main']['humidity'],
                'wind_speed' => $weatherData['wind']['speed'],
                'description' => $weatherData['weather'][0]['description'],
            ];

            // Load the view and pass the weather data
            return view('weather', $data);
        } else {
            // Handle the error (e.g., data not found)
            return view('weather', ['error' => 'Weather data not available for this location.']);
        }
    }

    public function subscribe()
    {
        // Receive the subscription object from frontend
        $json = $this->request->getJSON(true);
        // Store the subscription in a database for future use (not implemented in this example)

        return $this->response->setJSON(['success' => true]);
    }

    public function sendNotification()
    {
        // Example subscription info (retrieve this from your database)
        $subscription = Subscription::create([
            'endpoint' => '<endpoint>',
            'keys' => [
                'p256dh' => '<p256dh>',
                'auth' => '<auth_token>',
            ],
        ]);

        // VAPID keys (generated using WebPush::createVapidKeys)
        $auth = [
            'VAPID' => [
                'subject' => 'mailto:ricofontecilla30@gmail.com',
                'publicKey' => '<your_public_key>',
                'privateKey' => '<your_private_key>',
            ],
        ];

        $webPush = new WebPush($auth);

        $report = $webPush->sendOneNotification(
            $subscription,
            json_encode([
                'title' => 'Flood Alert!',
                'body' => 'Water level is rising. Please take action!',
            ])
        );

        return $this->response->setJSON(['success' => true]);
    }
    public function generateKeys()
    {
        // Generate VAPID keys
        $vapid = VAPID::createVapidKeys();

        // Display the keys
        echo 'Public key: ' . $vapid['publicKey'] . '<br>';
        echo 'Private key: ' . $vapid['privateKey'];
    }

    public function userDashboard(){
        $waterLevelModel = new WaterLevelModel();
        $rainfallModel = new RainfallModel();
        $solarVoltageModel = new SolarVoltageModel();
        $userModel = new UserModel();

        $waterLevels = $waterLevelModel->findAll();
        $monthlyWaterLevels = [];
        
        foreach ($waterLevels as $level) {
            $month = date('F', strtotime($level['date']));
            $waterLevel = $level['waterlevel'];
    
            if (!isset($monthlyWaterLevels[$month])) {
                $monthlyWaterLevels[$month] = ['total' => 0, 'low' => 0, 'moderate' => 0, 'high' => 0]; 
            }
    
            $monthlyWaterLevels[$month]['total']++;
    
            if ($waterLevel > 2.00 && $waterLevel <= 3.00) {
                $monthlyWaterLevels[$month]['high']++;
            } elseif ($waterLevel > 1.00 && $waterLevel <= 2.00) {
                $monthlyWaterLevels[$month]['moderate']++;
            } elseif ($waterLevel > 0 && $waterLevel <= 1.00) {
                $monthlyWaterLevels[$month]['low']++;
            }
        }

         // Solar voltage processing
         $solarVoltages = $solarVoltageModel->findAll();
         $dailyVoltages = [];
     
         foreach ($solarVoltages as $voltage) {
             $date = date('Y-m-d', strtotime($voltage['date']));
             if (!isset($dailyVoltages[$date])) {
                 $dailyVoltages[$date] = 0;
             }
             $dailyVoltages[$date] += $voltage['voltage'];
         }

        $rainfalls = $rainfallModel->findAll(); 
        $rainfallData = $this->prepareRainfallData($rainfalls);
    
        // Fetch user data
        $currentUserId = session()->get('user_id'); 
        $user = $userModel->find($currentUserId);
    
        $weatherData = $this->getWeatherData();
    
        // Fetch additional data
        $messages = $this->getLastThreeMessages();
        $latestWaterLevel = $this->getLatestWaterLevel();
    
        // Return the view with the processed data
        return view('user/dashboard', [
            'rainfallData' => $rainfallData,
            'latestWaterLevel' => $latestWaterLevel,
            'monthlyWaterLevels' => $monthlyWaterLevels,
            'dailyVoltages' => $dailyVoltages,
            'user' => $user,
            'weatherData' => $weatherData // Pass weather data to the view
        ]);
    }

    public function getMessages()
    {
        $reportModel = new ReportModel();
        $messages = $reportModel->orderBy('created_at', 'ASC')->findAll(); // Fetch all messages sorted by time
    
        // Loop through each message to append the correct image URL
        foreach ($messages as &$message) {
            if (!empty($message['image'])) {
                // Prepend the base URL to the image path
                $message['image'] =  base_url('../assets/img/' . $message['image']); 
            }
        }
    
        return $this->response->setJSON($messages); // Return messages (including images) as JSON
    }
    
    
        

        // Send a new message
        public function sendMessage()
        {
            $reportModel = new ReportModel();
        
            $message = $this->request->getPost('message');
            $username = $this->request->getPost('username');
        
            // Handle file upload
            $image = $this->request->getFile('image');
            $imagePath = null; // Default to null if no image is uploaded
        
            if ($image && $image->isValid() && !$image->hasMoved()) {
                $imageName = $image->getName();
                $imagePath = ROOTPATH . 'public/assets/img/' . $imageName;
        
                // Check if the image already exists
                if (!file_exists($imagePath)) {
                    // Move the image to the uploads folder if it doesn't exist
                    $image->move(ROOTPATH . 'public/assets/img', $imageName);
                } else {
                    // If the image already exists, set $imagePath to the existing image name (without moving it)
                    $imagePath = $imageName;
                }
            }
        
            // Prepare the data to insert
            $data = [
                'username' => $username,
                'message' => $message,
                'image' => $imagePath ? basename($imagePath) : null // Save only the image name if uploaded
            ];
        
            // Insert message into the database
            $reportModel->insert($data);
        
            return $this->response->setJSON(['status' => 'Message sent']);
        }
        
        
    
    
    
}
