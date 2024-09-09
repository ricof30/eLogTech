<?php

namespace App\Controllers;
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
    
          // $monthlyWaterLevels = [];
    // foreach ($waterLevels as $level) {
    //     $month = date('F', strtotime($level['date']));
    //     if (!isset($monthlyWaterLevels[$month])) {
    //         $monthlyWaterLevels[$month] = ['total' => 0, '1.00' => 0, '2.00' => 0, '3.00' => 0]; 
    //     }
    //     $monthlyWaterLevels[$month]['total']++;
    //     $monthlyWaterLevels[$month][$level['waterlevel']]++;
    // }// Fetch all water levels and process them

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
        $model = new WaterLevelModel();

        $data['latestWaterLevel'] = $model->findAll();
        // $data['pager'] = $model->pager;

        $userModel = new UserModel();
    
        // Assume you have a way to get the current user ID, e.g., from session
        $currentUserId = session()->get('user_id'); // Replace this with your method of retrieving the current user ID
        
        // Fetch the specific user data
        $user = $userModel->find($currentUserId);
        return view('alertHistory', [
            'latestWaterLevel' => $data['latestWaterLevel'],
         'user' => $user
     ]);}



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



// public function getNotifications()
// {
//     // Fetch the latest water levels
//     $latestWaterLevels = $this->getLatestWaterLevel();
//     $notifications = [];

//     foreach ($latestWaterLevels as $level) {
//         // Add water level to the notifications array
//         $notifications[] = [
//             'waterlevel' => $level['waterlevel']
//         ];
//     }

//     // Return JSON response
//     return $notifications;
// }

// public function fetchNotifications() {
//     // Fetch latest notifications from the database or compute them
//     $notifications = $this->getNotifications(); // Your method to get notifications

//     return $this->response->setJSON($notifications);
// }



// public function getNotifications()
// {
//     if (!$this->request->isAJAX()) {
//         // Return a 404 or some other error if the request is not AJAX
//         return $this->response->setStatusCode(404)->setBody('Not Found');
//     }

//     $latestWaterLevels = $this->getLatestWaterLevel();
//     $notifications = [];

//     foreach ($latestWaterLevels as $level) {
//         if ($level['waterlevel'] > 3) {
//             $notifications[] = ['type' => 'error', 'message' => "High water level: " . $level['waterlevel'] . " meters"];
//         } elseif ($level['waterlevel'] > 2 && $level['waterlevel'] <= 3) {
//             $notifications[] = ['type' => 'warning', 'message' => "Moderate water level: " . $level['waterlevel'] . " meters"];
//         } elseif ($level['waterlevel'] > 0 && $level['waterlevel'] <= 2) {
//             $notifications[] = ['type' => 'info', 'message' => "Low water level: " . $level['waterlevel'] . " meters"];
//         } else {
//             $notifications[] = ['type' => 'success', 'message' => "Normal water level: " . $level['waterlevel'] . " meters"];
//         }FW
//     }

//     return $this->response->setJSON($notifications);
// }




    

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
        
        // Fetch the specific user data
        $user = $userModel->find($currentUserId);
        return view('contact', [
            'contact' => $data['contact'],
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
                    session()->setFlashdata('error', 'Incorrect Password');
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
    $data['sentSMS'] = $sentSMS->findAll();
    $userModel = new UserModel();
    
    // Assume you have a way to get the current user ID, e.g., from session
    $currentUserId = session()->get('user_id'); // Replace this with your method of retrieving the current user ID
    
    // Fetch the specific user data
    $user = $userModel->find($currentUserId);
    return view('sent_message', [
        'sentSMS' => $data['sentSMS'],
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

    $data = $model->select('time, date, waterlevel')
                  ->where('date >=', $startDate)
                  ->where('date <=', $endDate)
                  ->findAll();

    // Create a new PDF instance
    $pdf = new TCPDF();
    $pdf->AddPage();

    // Set PDF metadata
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('eLogTech');
    $pdf->SetTitle('Water Level Data');
    $pdf->SetSubject('Water Level Data');

    // Set header and footer
    $pdf->SetHeaderData('', 0, 'Water Level Data', 'From ' . $startDate . ' to ' . $endDate);
    $pdf->setFooterData(['L' => 'Generated by Your System']);

    // Set font
    // $pdf->SetFont('helvetica', '', 12);

    // Add logo
    // $logoPath = 'assets/img/solarflood.png';
    // $pdf->Image($logoPath, 10, 10, 30, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);

    // Set title
    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->Cell(0, 10, 'Barangay Arangin Flood Monitoring System', 0, 1, 'C');
    // $pdf->Cell(0, 10, 'Flood Monitoring System', 0, 1, 'C');

    // Add table header
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(60, 10, 'Time', 1);
    $pdf->Cell(60, 10, 'Date', 1);
    $pdf->Cell(60, 10, 'Water Level (in meter)', 1);
    $pdf->Ln();

    // Add table data
    $pdf->SetFont('helvetica', '', 10);
    foreach ($data as $row) {
        $formattedTime = date('h:i A', strtotime($row['time']));
        $formattedDate = date('F j, Y', strtotime($row['date']));
        $pdf->Cell(60, 10, $formattedTime, 1);
        $pdf->Cell(60, 10, $formattedDate, 1);
        $pdf->Cell(60, 10, $row['waterlevel'], 1);
        $pdf->Ln();
    }

    // Output the PDF
    $filename = 'water_level_data_' . date('Y-m-d') . '.pdf';
    $pdf->Output($filename, 'D'); // 'D' for download
    exit;
}

// code for Excel filter waterlevel
    // public function filterWaterlevels()
    // {
    
    // $model = new WaterLevelModel();

    
    // $startDate = $this->request->getPost('water_start_date');
    // $endDate = $this->request->getPost('water_end_date');

    // $data = $model->select('time, date, waterlevel')
    //               ->where('date >=', $startDate)
    //               ->where('date <=', $endDate)
    //               ->findAll();

    
    // $excelData = [
    //     ['Time', 'Date', 'Water Level(in meter)']
    // ];

    // foreach ($data as $row) {
    //     $formattedTime = date('h:i A', strtotime($row['time'])); 
    //     $formattedDate = date('F j, Y', strtotime($row['date'])); 
    //     $excelData[] = [$formattedTime, $formattedDate, $row['waterlevel']];
    // }

    
    // $spreadsheet = new Spreadsheet();
    // $sheet = $spreadsheet->getActiveSheet();

    // $logoPath = 'assets/img/solarflood.png'; 
    // $drawing = new Drawing();
    // $drawing->setPath($logoPath);
    // $drawing->setHeight(50);
    // $drawing->setCoordinates('J1');
    // $drawing->setWorksheet($sheet);
    // $sheet->setCellValue('K1', 'Barangay Arangin');
    // $sheet->setCellValue('K2', 'Flood Monitoring System');

    
    // $sheet->mergeCells('B1:C1');

    
    // $sheet->fromArray($excelData, null, 'J5');

    
    // $sheet->getStyle('H5:L5')->applyFromArray([
    //     'font' => ['bold' => true],
    //     'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
    //     'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'CCCCCC']],
    // ]);

    
    // $lastRow = count($excelData) + 4; 
    // $range = 'H5:L' . $lastRow; 
    // $sheet->getStyle($range)->applyFromArray([
    //     'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
    //     'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
    // ]);

    
    // $sheet->getColumnDimension('J')->setWidth(20);
    // $sheet->getColumnDimension('K')->setWidth(20);
    // $sheet->getColumnDimension('L')->setWidth(20);

    

    
    // $filename = 'water_level_data_' . date('Y-m-d') . '.xlsx';
    // $tempFilePath = WRITEPATH . 'uploads/' . $filename;
    // $writer = new Xlsx($spreadsheet);
    // $writer->save($tempFilePath);

    
    // header('Content-Type: application/octet-stream');
    // header('Content-Disposition: attachment; filename="' . $filename . '"');
    // header('Content-Length: ' . filesize($tempFilePath));
    // header('Content-Transfer-Encoding: binary');
    // header('Cache-Control: must-revalidate');
    // header('Pragma: public');
    // header('Expires: 0');

    
    // readfile($tempFilePath);
    // exit;
    // }

    public function filterRainfall()
    {
        $model = new RainfallModel();
    
        $startDate = $this->request->getPost('rain_start_date');
        $endDate = $this->request->getPost('rain_end_date');
    
        // Fetch data from the database
        $data = $model->select('date, SUM(rainfall) as total_rainfall, SUM(duration) as duration') 
            ->where('date >=', $startDate)
            ->where('date <=', $endDate)
            ->groupBy('date')
            ->findAll();
    
        // Create a new PDF instance
        $pdf = new TCPDF();
        $pdf->AddPage();
    
        // Set PDF metadata
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('eLogTech');
        $pdf->SetTitle('Rainfall Data');
        $pdf->SetSubject('Rainfall Data Export');
    
        // Set header and footer
        $pdf->SetHeaderData('', 0, 'Rainfall Data', 'From ' . $startDate . ' to ' . $endDate);
    
        // Set Arial font
        $pdf->SetFont('helvetica', '', 12);
    
        // // Add logo
        // $logoPath = 'assets/img/eLogTech.jpg'; 
        // $pdf->Image($logoPath, 10, 10, 50, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
    
        // Add title
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(0, 10, 'Barangay Arangin Flood Monitoring System', 0, 1, 'C');
        // $pdf->Cell(0, 10, 'Flood Monitoring System', 0, 1, 'C');
    
        // Add table header
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(60, 10, 'Date', 1);
        $pdf->Cell(60, 10, 'Rainfall', 1);
        $pdf->Cell(60, 10, 'Duration', 1);
        $pdf->Ln();
    
        // Add table data
        $pdf->SetFont('helvetica', '', 11);
        foreach ($data as $row) {
            $formattedDate = date('F j, Y', strtotime($row['date']));
    
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
    
            $pdf->Cell(60, 10, $formattedDate, 1);
            $pdf->Cell(60, 10, $row['total_rainfall'], 1);
            $pdf->Cell(60, 10, $duration, 1);
            $pdf->Ln();
        }
    
        // Output the PDF
        $filename = 'rainfall_data_' . date('Y-m-d') . '.pdf';
        $pdf->Output($filename, 'D'); // 'D' for download
        exit;
    }
    

    // public function filterRainfalls()
    // {
          
    //     $model = new RainfallModel();
        
    //     $startDate = $this->request->getPost('rain_start_date');
    //     $endDate = $this->request->getPost('rain_end_date');
    //         $data = $model->select('date, SUM(rainfall) as total_rainfall, SUM(duration) as duration') 
    //         ->where('date >=', $startDate)
    //         ->where('date <=', $endDate)
    //         ->groupBy('date')
    //         ->findAll();

        
    //     $excelData = [
    //         ['Date', 'Rainfall', 'Duration'] 
    //     ];
        
    //     foreach ($data as $row) {
            
    //         $durationInSeconds = $row['duration'] / 1000;
    
            
    //         $duration = '';
    //         if ($durationInSeconds >= 3600) {
    //             $hours = floor($durationInSeconds / 3600);
    //             $minutes = floor(($durationInSeconds % 3600) / 60);
    //             $duration = "{$hours} hours {$minutes} minutes";
    //         } elseif ($durationInSeconds >= 60) {
    //             $minutes = floor($durationInSeconds / 60);
    //             $seconds = $durationInSeconds % 60;
    //             $duration = "{$minutes} minutes {$seconds} seconds";
    //         } else {
    //             $duration = "{$durationInSeconds} seconds";
    //         }
    
    //         $excelData[] = [date('F j, Y', strtotime($row['date'])), $row['total_rainfall'], $duration];
    //     }

        
    //     $consolidatedData = [];
    //     foreach ($data as $row) {
    //         $date = date('F j, Y', strtotime($row['date']));
    //         if (isset($consolidatedData[$date])) {
    //             $consolidatedData[$date] += $row['total_rainfall'];
    //         } else {
    //             $consolidatedData[$date] = $row['total_rainfall'];
    //         }
    //     }

        
    //     foreach ($consolidatedData as $date => $totalRainfall) {
    //         $excelData[] = [$date, $totalRainfall];
    //     }

        
    //     $spreadsheet = new Spreadsheet();
    //     $sheet = $spreadsheet->getActiveSheet();

        
    //     $logoPath = 'assets/img/eLogTech.jpg'; 
    //     $drawing = new Drawing();
    //     $drawing->setPath($logoPath);
    //     $drawing->setHeight(50);
    //     $drawing->setCoordinates('I1');
    //     $drawing->setWorksheet($sheet);
    //     $sheet->setCellValue('J1', 'Barangay Arangin');
    //     $sheet->setCellValue('J2', 'Flood Monitoring System');

        
    //     $sheet->mergeCells('B1:C1');

        
    //     $sheet->fromArray($excelData, null, 'J5');

        
    //     $sheet->getStyle('H5:L5')->applyFromArray([
    //         'font' => ['bold' => true],
    //         'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
    //         'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'CCCCCC']],
    //     ]);

        
    //     $lastRow = count($excelData) + 4; 
    //     $range = 'H5:L' . $lastRow; 
    //     $sheet->getStyle($range)->applyFromArray([
    //         'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
    //         'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
    //     ]);

        
    //     $sheet->getColumnDimension('I')->setWidth(10);
    //     $sheet->getColumnDimension('J')->setWidth(20);
    //     $sheet->getColumnDimension('L')->setWidth(20);

    //     $filename = 'rainfall_data_' . date('Y-m-d') . '.xlsx';
    //     $tempFilePath = WRITEPATH . 'uploads/' . $filename;
    //     $writer = new Xlsx($spreadsheet);
    //     $writer->save($tempFilePath);

        
    //     header('Content-Type: application/octet-stream');
    //     header('Content-Disposition: attachment; filename="' . $filename . '"');
    //     header('Content-Length: ' . filesize($tempFilePath));
    //     header('Content-Transfer-Encoding: binary');
    //     header('Cache-Control: must-revalidate');
    //     header('Pragma: public');
    //     header('Expires: 0');

        
    //     readfile($tempFilePath);
    //     exit;
    // }

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
    
    // public function getLatestStatus()
    // {
    //     $model = new StatusModel();

    //     $sql = "SELECT s.sensor_type, s.status, s.last_update
    //             FROM status s
    //             INNER JOIN (
    //                 SELECT sensor_type, MAX(last_update) as max_timestamp
    //                 FROM status
    //                 GROUP BY component
    //             ) latest ON s.sensor_type = latest.sensor_type AND s.last_update = latest.max_timestamp";

    //     $query = $model->query($sql);

    //     return $query->getResultArray();
    // }

   
    // public function viewStatus()
    // {
    //     $userModel = new UserModel();
    //     $statusModel = new StatusModel();
    //     $getStatus['statuses'] = $statusModel->findAll();
    //     // Assume you have a way to get the current user ID, e.g., from session
    //     $currentUserId = session()->get('user_id'); // Replace this with your method of retrieving the current user ID
        
    //     // Fetch the specific user data
    //     $user = $userModel->find($currentUserId);
    //     return view('status', [
    //         'statuses' => $getStatus['statuses'],
    //         'user' => $user
    //     ]);

    // }

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

    // Fetch the latest statuses
    $getStatus['statuses'] = $builder->get()->getResultArray();
    
    // Fetch the specific user data
    $currentUserId = session()->get('user_id');
    $user = $userModel->find($currentUserId);

    return view('status', [
        'statuses' => $getStatus['statuses'],
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
    



   
    
}

