<?= $this->include('header'); ?>
<?= $this->include('sidebar'); ?>

<div class="content" style="background-color:#CDE8E5">
<?= $this->include('navbar');?>
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4">
            <div class="col-12">
                <div class="rounded h-100 p-4" style="background-color: #EEF7FF">
                    <div class="table-responsive">
                        <h4 class="text-center text-dark">User List</h4>

                        <table id="page" class="table-responsive table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-white text-center">Name</th>
                                    <th class="text-white text-center">Email</th>
                                    <th class="text-white text-center">Password</th>
                                    <th class="text-white text-center">Verified</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($list as $users): ?>
                                    <tr class="text-dark">
                                        <td class="text-center"><?= $users['username']; ?></td>
                                        <td class="text-center"><?= $users['email']; ?></td>
                                        <td class="text-center"><?= $users['password']; ?></td>
                                            <?php if($user['is_verified'] == 1):?>
                                                <td class="text-success">Yes</td>
                                            <?php endif;?>
                                            <?php if($user['is_verified'] == 0):?>
                                                <td class="text-center text-danger">No</td>
                                            <?php endif;?>
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
        th[i].style.backgroundColor = 'green';
    }
    for(let i=0;i<td.length;i++){
        td[i].style.textAlign = 'center';
    }
</script>
   
<!-- <?= $this->include('footer'); ?> -->
