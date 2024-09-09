<?= $this->include('header'); ?>
<?= $this->include('sidebar'); ?>

<div class="content">
<div class="menu-bar">
        <a href="#" class="sidebar-toggler flex-shrink-0">
                        <i class="fa fa-bars"></i>
        </a>
    </div>
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4">
            <div class="col-12">
                <div class="bg-secondary rounded h-100 p-4">
                    <div class="table-responsive">
                        <h4 class="text-center">Alert History</h4>

                        <table id="page" class="table-responsive table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-white text-center">Alert Message</th>
                                    <th class="text-white text-center">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($latestWaterLevel as $level): ?>
                                    <tr>
                                    <?php 
                                       if ($level['waterlevel'] > 3) {
                                        $alertMessage = "High water level: " . $level['waterlevel'] . " meters"; 
                                        } elseif ($level['waterlevel'] > 2 && $level['waterlevel'] <= 3) {
                                            $alertMessage = "Moderate water level: " . $level['waterlevel'] . " meters";
                                        } elseif ($level['waterlevel'] > 0 && $level['waterlevel'] <= 2) {
                                            $alertMessage = "Low water level: " . $level['waterlevel'] . " meter";
                                        } else {
                                            $alertMessage = "Normal water level: " . $level['waterlevel'] . " meters";
                                        }
                                    ?>
                                        <td class="text-center"><?= $alertMessage ?></td>
                                        <td class="text-center"><?= date('F j, Y, g:i a', strtotime($level['date'])) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->include('script'); ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    let table = new DataTable('#page');
</script>
<script>
    const th = document.getElementsByTagName('th');
    const td = document.getElementsByTagName('td');
    for(let i=0; i<th.length;i++){
        th[i].style.textAlign = 'center';
        th[i].style.backgroundColor = 'orange';
    }
    for(let i=0;i<td.length;i++){
        td[i].style.textAlign = 'center';
    }
</script>
   
<!-- <?= $this->include('footer'); ?> -->
