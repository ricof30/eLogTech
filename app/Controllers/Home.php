<?php

namespace App\Controllers;
use Config\Database;
use CodeIgniter\RESTful\ResourceController;
use Phpml\Dataset\ArrayDataset;
use Phpml\Classification\KNearestNeighbors;
use Phpml\Metric\Accuracy;
use Phpml\Classification\SVC;
use Phpml\Regression\LeastSquares;
use Phpml\Regression\Ridge;
use Phpml\Regression\SVR;
use Phpml\SupportVectorMachine\Kernel;
use App\Models\ContactModel;
use App\Models\WaterLevelModel;
use App\Models\UserModel;
use App\Models\SentMessageModel;
use App\Models\ReceiveMessageModel;
use App\Models\RainFallModel;
use App\Models\StatusModel;
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



class Home extends BaseController
{
    public function index(): string
    {
        // Initialize the models
        $waterLevelModel = new WaterLevelModel();
        $solarVoltageModel = new SolarVoltageModel();
        $rainfallModel = new RainfallModel();
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
    
        // Fetch all solar voltages and process them
        $solarVoltages = $solarVoltageModel->findAll();
        $dailyVoltages = [];
    
        foreach ($solarVoltages as $voltage) {
            $date = date('Y-m-d', strtotime($voltage['date']));
            if (!isset($dailyVoltages[$date])) {
                $dailyVoltages[$date] = 0;
            }
            $dailyVoltages[$date] += $voltage['voltage'];
        }
    
        // Fetch all rainfalls and prepare rainfall data
        $rainfalls = $rainfallModel->findAll(); 
        $rainfallData = $this->prepareRainfallData($rainfalls);
    
        // Fetch user data based on the current user ID
        $currentUserId = session()->get('user_id'); // Replace this with your method of retrieving the current user ID
        $user = $userModel->find($currentUserId);
    
        // Fetch additional data
        $messages = $this->getLastThreeMessages();
        $latestWaterLevel = $this->getLatestWaterLevel();
        // $notifications = $this->getNotifications(); // Uncomment if notifications are needed
    
        // Return the view with the processed data
        return view('dashboard', [
            'monthlyWaterLevels' => $monthlyWaterLevels,
            'dailyVoltages' => $dailyVoltages,
            'rainfallData' => $rainfallData,
            'messages' => $messages,
            'latestWaterLevel' => $latestWaterLevel,
            'user' => $user
            // 'notifications' => $notifications // Uncomment if notifications are needed
        ]);
    }
    
   
    public function alertHistory()
    {
        {
            $model = new WaterLevelModel();
        
            // Fetch the latest water levels and order by date and time correctly
            $data['latestWaterLevel'] = $model->findAll();
            
            $userModel = new UserModel();
            
            // Get the current user ID from the session or other method
            $currentUserId = session()->get('user_id'); // Make sure the session is initialized
            $messages = $this->getLastThreeMessages();
            $latestWaterLevel = $this->getLatestWaterLevel();
            
            // Fetch the specific user data
            $user = $userModel->find($currentUserId);
            
            // Return the view with the latest water level data and user info
            return view('alertHistory', [
                'latestWaterLevel' => $data['latestWaterLevel'],
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

    public function adminSignin() {
        if ($this->request->getMethod() === 'post') {
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');
    
            $model = new UserModel();
            $user = $model->where('email', $email)->first(); 
    
            if ($user) {
                
                if ($password === $user['password']) {
                    
                    $sessionData = [
                        'user_id' => $user['id'],
                        'email' => $user['email'],
                        'is_logged_in' => true,
                    ];
                    session()->set($sessionData);
    
                    return redirect()->to('/'); 
                } else {
                    session()->setFlashdata('error', 'Incorrect Username and Password');
                    return redirect()->to('/signin');
                }
            } else {
                session()->setFlashdata('error', 'Incorrect Username and Password');
                return redirect()->to('/signin');
            }
        }
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
    $data['sentSMS'] = $sentSMS->orderBy('date', "DESC")->findAll();
    $userModel = new UserModel();
    
    // Assume you have a way to get the current user ID, e.g., from session
    $currentUserId = session()->get('user_id'); // Replace this with your method of retrieving the current user ID
    $messages = $this->getLastThreeMessages();
    $latestWaterLevel = $this->getLatestWaterLevel();
    
    // Fetch the specific user data
    $user = $userModel->find($currentUserId);
    return view('sent_message', [
        'sentSMS' => $data['sentSMS'],
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


    public function updatePhoto()
    {
        $userModel = new UserModel();
    
        // Get user ID from session or other source
        $userId = session()->get('user_id');
    
        // Handle file upload
        $profilePhoto = $this->request->getFile('profilePhoto');
        $currentPhoto = $userModel->find($userId)['image']; // Get current photo name
    
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
    
        // Update the user's photo in the database
        $updateData = [
            'image' => $profilePhotoName,
        ];
    
        $userModel->update($userId, $updateData);
    
        return redirect()->to('/')->with('success', 'Profile photo updated successfully');
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
    



   
    
}
