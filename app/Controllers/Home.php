<?php

namespace App\Controllers;
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


class Home extends BaseController
{
    public function index(): string
    {
        $waterLevelModel = new WaterLevelModel();
        $waterLevels = $waterLevelModel->findAll();
    
        // Group water level data by month and calculate average water level
        $monthlyWaterLevels = [];
        foreach ($waterLevels as $level) {
            $month = date('F', strtotime($level['date']));
            if (!isset($monthlyWaterLevels[$month])) {
                $monthlyWaterLevels[$month] = ['total' => 0, '1.00' => 0, '2.00' => 0, '3.00' => 0]; // Initialize counts
            }
            $monthlyWaterLevels[$month]['total']++;
            // Increment counts for each water level
            $monthlyWaterLevels[$month][$level['waterlevel']]++;
        }

        $solarVoltageModel = new SolarVoltageModel();
        $solarVoltages = $solarVoltageModel->findAll();
        $dailyVoltages = [];
        foreach ($solarVoltages as $voltage) {
            $date = date('Y-m-d', strtotime($voltage['date']));
            if (!isset($dailyVoltages[$date])) {
                $dailyVoltages[$date] = 0;
            }
            $dailyVoltages[$date] += $voltage['voltage'];
        }
    
        $rainfallModel = new RainfallModel();
        $rainfalls = $rainfallModel->findAll(); // Fetch all rainfall data from the database
    
        $rainfallData = $this->prepareRainfallData($rainfalls); // Prepare data for the chart
    
        // Fetch last three messages
        $messages = $this->getLastThreeMessages();
   
        $latestWaterLevel = $this->getLatestWaterLevel();
    
        // Pass the data to the view
        return view('dashboard', [
            'monthlyWaterLevels' => $monthlyWaterLevels,
            'dailyVoltages' => $dailyVoltages,
            'rainfallData' => $rainfallData,
            'messages' => $messages,
            'latestWaterLevel' => $latestWaterLevel
             // Include latest water level data
        ]);
    }
   
    public function alertHistory()
    {
        $model = new WaterLevelModel();

        $data['latestWaterLevel'] = $model->paginate(10);
        $data['pager'] = $model->pager;
        return view('alertHistory', $data);
    }



    private function getLastThreeMessages()
    {
        $model = new ReceiveMessageModel();
        return $model->orderBy('date', 'desc') // Assuming 'date' column holds the date of the message
                     ->orderBy('time', 'desc') // Assuming 'time' column holds the time of the message
                     ->limit(3)
                     ->findAll();
    }

    private function getLatestWaterLevel()
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
        return view('contact', $data);
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
        session()->setFlashdata('error', 'Delet failed');
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
            $user = $model->where('email', $email)->first(); // Get the first record matching the email
    
            if ($user) {
                // Compare plain text passwords
                if ($password === $user['password']) {
                    // Set session data
                    $sessionData = [
                        'user_id' => $user['id'],
                        'email' => $user['email'],
                        'is_logged_in' => true,
                    ];
                    session()->set($sessionData);
    
                    return redirect()->to('/'); // Redirect to dashboard or home page
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
    
        return view('message', $data);
    }
    
    
    
    
      // Controller method showMessage()
// public function showMessage($phoneNumber){
//     $model = new ReceiveMessageModel();
//     $data['messages'] = $model->where('phone_number', $phoneNumber)
//                               ->orderBy('date', 'DESC') // Assuming you want to order messages by date
//                               ->findAll(); 
//     return view('show_message', $data);
// }

public function show($phone_number)
{
    $messageModel = new ReceiveMessageModel();
    $messages = $messageModel->where('phone_number', $phone_number)->findAll();

    $data = [
        'phone_number' => $phone_number,
        'messages' => $messages
    ];

    return view('showMessage', $data);
}
        
public function deleteMessage($id)
{
    $model = new ReceiveMessageModel();
    if ($model->delete($id)) {
        session()->setFlashdata('success', 'Message deleted successfully');
    } else {
        session()->setFlashdata('error', 'Failed to delete message');
    }

    // Get the current URL and redirect back to it
    $currentURL = base_url('message/show/' . $id);
    return redirect()->to($currentURL);
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
    // Load model
    $model = new WaterLevelModel();

    // Fetch filtered data based on start and end dates
    $startDate = $this->request->getPost('water_start_date');
    $endDate = $this->request->getPost('water_end_date');

    $data = $model->select('time, date, waterlevel')
                  ->where('date >=', $startDate)
                  ->where('date <=', $endDate)
                  ->findAll();

    // Prepare data for Excel with formatted time and date
    $excelData = [
        ['Time', 'Date', 'Water Level']
    ];

    foreach ($data as $row) {
        $formattedTime = date('h:i A', strtotime($row['time'])); // Format time to 12-hour format
        $formattedDate = date('F j, Y', strtotime($row['date'])); // Format date to Month day, Year
        $excelData[] = [$formattedTime, $formattedDate, $row['waterlevel']];
    }

    // Generate Excel file
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $logoPath = 'assets/img/solarflood.png'; // Replace with the actual path to your logo
    $drawing = new Drawing();
    $drawing->setPath($logoPath);
    $drawing->setHeight(50);
    $drawing->setCoordinates('I1');
    $drawing->setWorksheet($sheet);
    $sheet->setCellValue('J1', 'Barangay Arangin');
    $sheet->setCellValue('J2', 'Flood Monitoring System');

    // Merge cells for the header
    $sheet->mergeCells('B1:C1');

    // Set header and data
    $sheet->fromArray($excelData, null, 'J5');

    // Format headers
    $sheet->getStyle('H5:L5')->applyFromArray([
        'font' => ['bold' => true],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'CCCCCC']],
    ]);

    // Format data rows
    $lastRow = count($excelData) + 4; // Calculate the last row index
    $range = 'H5:L' . $lastRow; // Define the range for applying the border style
    $sheet->getStyle($range)->applyFromArray([
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
    ]);

    // Set column widths
    
    $sheet->getColumnDimension('J')->setWidth(20);
    $sheet->getColumnDimension('K')->setWidth(20);
    $sheet->getColumnDimension('L')->setWidth(15);

    

    // Set the appropriate headers for file download
    $filename = 'water_level_data_' . date('Y-m-d') . '.xlsx';
    $tempFilePath = WRITEPATH . 'uploads/' . $filename;
    $writer = new Xlsx($spreadsheet);
    $writer->save($tempFilePath);

    // Set the appropriate headers for file download
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Content-Length: ' . filesize($tempFilePath));
    header('Content-Transfer-Encoding: binary');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Expires: 0');

    // Read and output the file contents
    readfile($tempFilePath);
    exit;
    }

    public function filterRainfall()
    {
          // Load model
        $model = new RainfallModel();
        // Fetch filtered data based on start and end dates
        $startDate = $this->request->getPost('rain_start_date');
        $endDate = $this->request->getPost('rain_end_date');
            $data = $model->select('date, SUM(rainfall) as total_rainfall, SUM(duration) as duration') // Include SUM(duration) in the select query
            ->where('date >=', $startDate)
            ->where('date <=', $endDate)
            ->groupBy('date')
            ->findAll();

        // Prepare data for Excel
        $excelData = [
            ['Date', 'Rainfall', 'Duration'] // Adjusted headers
        ];
        
        foreach ($data as $row) {
            // Convert duration from milliseconds to seconds
            $durationInSeconds = $row['duration'] / 1000;
    
            // Convert duration to human-readable format (hours, minutes, or seconds)
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
    
            $excelData[] = [date('F j, Y', strtotime($row['date'])), $row['total_rainfall'], $duration];
        }

        // Consolidate data for the same date
        $consolidatedData = [];
        foreach ($data as $row) {
            $date = date('F j, Y', strtotime($row['date']));
            if (isset($consolidatedData[$date])) {
                $consolidatedData[$date] += $row['total_rainfall'];
            } else {
                $consolidatedData[$date] = $row['total_rainfall'];
            }
        }

        // Add consolidated data to excelData
        foreach ($consolidatedData as $date => $totalRainfall) {
            $excelData[] = [$date, $totalRainfall];
        }

        // Generate Excel file
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set logo and header text
        $logoPath = 'assets/img/solarflood.png'; // Replace with the actual path to your logo
        $drawing = new Drawing();
        $drawing->setPath($logoPath);
        $drawing->setHeight(50);
        $drawing->setCoordinates('I1');
        $drawing->setWorksheet($sheet);
        $sheet->setCellValue('J1', 'Barangay Arangin');
        $sheet->setCellValue('J2', 'Flood Monitoring System');

        // Merge cells for the header
        $sheet->mergeCells('B1:C1');

        // Set header and data
        $sheet->fromArray($excelData, null, 'J5');

        // Format headers
        $sheet->getStyle('H5:L5')->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'CCCCCC']],
        ]);

        // Format data rows
        $lastRow = count($excelData) + 4; // Calculate the last row index
        $range = 'H5:L' . $lastRow; // Define the range for applying the border style
        $sheet->getStyle($range)->applyFromArray([
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);

        // Set column widths
        $sheet->getColumnDimension('I')->setWidth(10);
        $sheet->getColumnDimension('J')->setWidth(20);
        $sheet->getColumnDimension('L')->setWidth(20);

        $filename = 'rainfall_data_' . date('Y-m-d') . '.xlsx';
        $tempFilePath = WRITEPATH . 'uploads/' . $filename;
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFilePath);

        // Set the appropriate headers for file download
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($tempFilePath));
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Expires: 0');

        // Read and output the file contents
        readfile($tempFilePath);
        exit;
    }

    public function auth()
    {
        // Check if the user is logged in
        if (!session()->get('is_logged_in')) {
            return redirect()->to('/signin')->with('error', 'You must be logged in to access this page.');
        }

        // Proceed with loading the dashboard view
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
    //     return $model->select('component, status, value, MAX(timestamp) as timestamp')
    //                 ->groupBy('component')
    //                 ->findAll();
    // }
    public function getLatestStatus()
    {
        $model = new StatusModel();

        $sql = "SELECT s.component, s.status, s.value, s.timestamp
                FROM status s
                INNER JOIN (
                    SELECT component, MAX(timestamp) as max_timestamp
                    FROM status
                    GROUP BY component
                ) latest ON s.component = latest.component AND s.timestamp = latest.max_timestamp";

        $query = $model->query($sql);

        return $query->getResultArray();
    }

   
    public function viewStatus()
    {
        $data['statuses'] = $this->getLatestStatus();

        return view('status', $data);
    }
    
    
}

