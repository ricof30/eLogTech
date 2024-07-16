<?= $this->include('header'); ?>
<?= $this->include('sidebar'); ?>

<div class="content">
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4">
            <div class="col-12">
                <div class="bg-secondary rounded h-100 p-4">
                    <div class="table-responsive">
                        <h4 class="text-center">Water Level History</h4>

                        <table id="page" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-white text-center">Water Level</th>
                                    <th class="text-white text-center">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($latestWaterLevel as $level): ?>
                                    <tr>
                                        <td class="text-center"><?= $level['waterlevel'] ?></td>
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
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#page').DataTable({
            "pagingType": "simple_numbers", // You can choose any pagination style you prefer
            "lengthMenu": [10, 25, 50, 75, 100],
            "language": {
                "paginate": {
                    "previous": "&lt;",
                    "next": "&gt;"
                }
            },
            "columnDefs": [
                { "orderable": false, "targets": 0 }, // Disable sorting on the first column
                { "orderable": false, "targets": 1 }  // Disable sorting on the second column
            ]
        });
    });
</script>

<?= $this->include('footer'); ?>
