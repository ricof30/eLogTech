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



class Home extends BaseController
{
    public function index(): string
{
    $waterLevelModel = new WaterLevelModel();
    $waterLevels = $waterLevelModel->findAll();
    
    $monthlyWaterLevels = [];
    foreach ($waterLevels as $level) {
        $month = date('F', strtotime($level['date']));
        if (!isset($monthlyWaterLevels[$month])) {
            $monthlyWaterLevels[$month] = ['total' => 0, '1.00' => 0, '2.00' => 0, '3.00' => 0]; 
        }
        $monthlyWaterLevels[$month]['total']++;
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
    $rainfalls = $rainfallModel->findAll(); 
    $rainfallData = $this->prepareRainfallData($rainfalls); 

    $userModel = new UserModel();
    
    // Assume you have a way to get the current user ID, e.g., from session
    $currentUserId = session()->get('user_id'); // Replace this with your method of retrieving the current user ID
    
    // Fetch the specific user data
    $user = $userModel->find($currentUserId);
    
    $messages = $this->getLastThreeMessages();
    $latestWaterLevel = $this->getLatestWaterLevel();
    
    return view('dashboard', [
        'monthlyWaterLevels' => $monthlyWaterLevels,
        'dailyVoltages' => $dailyVoltages,
        'rainfallData' => $rainfallData,
        'messages' => $messages,
        'latestWaterLevel' => $latestWaterLevel,
        'user' => $user
    ]);
}

   
    public function alertHistory()
    {
        $model = new WaterLevelModel();

        $data['latestWaterLevel'] = $model->findAll();
        $data['pager'] = $model->pager;
        return view('alertHistory', $data);
    }



    private function getLastThreeMessages()
    {
        $model = new ReceiveMessageModel();
        return $model->orderBy('date', 'desc') 
                     ->orderBy('time', 'desc') 
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
    
        return view('message', $data);
    }
    
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
    
    $model = new WaterLevelModel();

    
    $startDate = $this->request->getPost('water_start_date');
    $endDate = $this->request->getPost('water_end_date');

    $data = $model->select('time, date, waterlevel')
                  ->where('date >=', $startDate)
                  ->where('date <=', $endDate)
                  ->findAll();

    
    $excelData = [
        ['Time', 'Date', 'Water Level']
    ];

    foreach ($data as $row) {
        $formattedTime = date('h:i A', strtotime($row['time'])); 
        $formattedDate = date('F j, Y', strtotime($row['date'])); 
        $excelData[] = [$formattedTime, $formattedDate, $row['waterlevel']];
    }

    
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $logoPath = 'assets/img/solarflood.png'; 
    $drawing = new Drawing();
    $drawing->setPath($logoPath);
    $drawing->setHeight(50);
    $drawing->setCoordinates('I1');
    $drawing->setWorksheet($sheet);
    $sheet->setCellValue('J1', 'Barangay Arangin');
    $sheet->setCellValue('J2', 'Flood Monitoring System');

    
    $sheet->mergeCells('B1:C1');

    
    $sheet->fromArray($excelData, null, 'J5');

    
    $sheet->getStyle('H5:L5')->applyFromArray([
        'font' => ['bold' => true],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'CCCCCC']],
    ]);

    
    $lastRow = count($excelData) + 4; 
    $range = 'H5:L' . $lastRow; 
    $sheet->getStyle($range)->applyFromArray([
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
    ]);

    
    
    $sheet->getColumnDimension('J')->setWidth(20);
    $sheet->getColumnDimension('K')->setWidth(20);
    $sheet->getColumnDimension('L')->setWidth(15);

    

    
    $filename = 'water_level_data_' . date('Y-m-d') . '.xlsx';
    $tempFilePath = WRITEPATH . 'uploads/' . $filename;
    $writer = new Xlsx($spreadsheet);
    $writer->save($tempFilePath);

    
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Content-Length: ' . filesize($tempFilePath));
    header('Content-Transfer-Encoding: binary');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Expires: 0');

    
    readfile($tempFilePath);
    exit;
    }

    public function filterRainfall()
    {
          
        $model = new RainfallModel();
        
        $startDate = $this->request->getPost('rain_start_date');
        $endDate = $this->request->getPost('rain_end_date');
            $data = $model->select('date, SUM(rainfall) as total_rainfall, SUM(duration) as duration') 
            ->where('date >=', $startDate)
            ->where('date <=', $endDate)
            ->groupBy('date')
            ->findAll();

        
        $excelData = [
            ['Date', 'Rainfall', 'Duration'] 
        ];
        
        foreach ($data as $row) {
            
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
    
            $excelData[] = [date('F j, Y', strtotime($row['date'])), $row['total_rainfall'], $duration];
        }

        
        $consolidatedData = [];
        foreach ($data as $row) {
            $date = date('F j, Y', strtotime($row['date']));
            if (isset($consolidatedData[$date])) {
                $consolidatedData[$date] += $row['total_rainfall'];
            } else {
                $consolidatedData[$date] = $row['total_rainfall'];
            }
        }

        
        foreach ($consolidatedData as $date => $totalRainfall) {
            $excelData[] = [$date, $totalRainfall];
        }

        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        
        $logoPath = 'assets/img/solarflood.png'; 
        $drawing = new Drawing();
        $drawing->setPath($logoPath);
        $drawing->setHeight(50);
        $drawing->setCoordinates('I1');
        $drawing->setWorksheet($sheet);
        $sheet->setCellValue('J1', 'Barangay Arangin');
        $sheet->setCellValue('J2', 'Flood Monitoring System');

        
        $sheet->mergeCells('B1:C1');

        
        $sheet->fromArray($excelData, null, 'J5');

        
        $sheet->getStyle('H5:L5')->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'CCCCCC']],
        ]);

        
        $lastRow = count($excelData) + 4; 
        $range = 'H5:L' . $lastRow; 
        $sheet->getStyle($range)->applyFromArray([
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);

        
        $sheet->getColumnDimension('I')->setWidth(10);
        $sheet->getColumnDimension('J')->setWidth(20);
        $sheet->getColumnDimension('L')->setWidth(20);

        $filename = 'rainfall_data_' . date('Y-m-d') . '.xlsx';
        $tempFilePath = WRITEPATH . 'uploads/' . $filename;
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFilePath);

        
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($tempFilePath));
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Expires: 0');

        
        readfile($tempFilePath);
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

