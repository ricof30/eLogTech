<?= $this->include('header'); ?>
<?= $this->include('sidebar'); ?>
<div class="content">
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4">
            <div class="col-12">
                <div class="bg-secondary rounded h-100 p-4">
                   
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <?php if (session()->getFlashdata('success')): ?>
                                <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                                    <?= session()->getFlashdata('success'); ?>
                                </div>
                            <?php endif; ?>
                            <?php if (session()->getFlashdata('error')): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <?= session()->getFlashdata('error'); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                    <div style="width:120px;height:40px;background-color:white;border-radius:20px">
                        <a href="#" id="addContactLink"> <p class="text-danger text-center pt-2 fw-bold">Add Contact</p></a>
                    </div>
                    <h6 class="mb-4 text-center large">Contact Numbers</h6>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-white">Phone Number</th>
                                    <th scope="col" class="text-white text-center">Action</th>
                                    <th scope="col" class="text-white">Status</th>
                                    <!-- <th scope="col">Delete</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($contact as $con): ?>
                                    <tr>
                                        <td><?= $con['phone_number']; ?></td>
                                        <td class="text-center"> <a data-bs-toggle="modal" data-bs-target="#edit" class="text-info edit" href="#" data-id="<?= $con['id'] ?>" data-phone_number="<?= $con['phone_number'] ?>" data-status=<?= $con['status']?>>Edit</a></td>
                                        <td>
                                         <?php if ($con['status'] == 1):?>
                                              <p class="text-success">Active</p>
                                          <?php endif; ?>
                                        <?php if ($con['status'] == 0):?>
                                            <p class="text-danger">Inactive</p>
                                        <?php endif; ?>
                                        </td>
                                        <!-- <td class="text-danger"> <a  href="delete/<?= $con['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a></td> -->
                                       
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

<!-- modal for adding  -->
<div class="modal fade" id="addContactModal" tabindex="-1" aria-labelledby="addContactModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content border border-primary rounded">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addContactModalLabel">Add Contact</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url('add_contact');?>" method="post">
                    <div class="mb-3">
                        <label for="phoneNumber" class="form-label">Phone Number</label>
                        <input type="text" class="form-control bg-white" name="phoneNumber" placeholder="eg. +639123456789" required>
                    </div>
                    <!-- Add other form fields here if needed -->
                   
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
            </form>
        </div>
    </div>
</div>


<!-- modal for editing -->
<div class="modal fade" id="edit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border border-success rounded">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="exampleModalLabel">Edit Contact</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url('update');?>" method="post">
                    <div class="mb-3">
                        <label for="editPhoneNumber" class="form-label">Phone Number</label>
                        <input type="hidden" name="id" id="edit-id"> 
                        <input type="text" class="form-control bg-white" id="edit-phoneNumber" name="phoneNumber" placeholder="Enter phone number">
                        <br>
                        <select class="form-control" name="status" id="edit-status">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div> 
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="saveChangesBtn">Save changes</button>
            </div>
            </form>
        </div>
    </div>
</div>

<?= $this->include('script'); ?>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.getElementById("addContactLink").addEventListener("click", function () {
            $('#addContactModal').modal('show'); // Show modal when link is clicked
        });
    });
</script>
<script>
   document.addEventListener('DOMContentLoaded', function () {
    var editModal = $('#edit');
    $(document).on('click', '.edit', function (event) {
        event.preventDefault();
        var id = $(this).data('id');
        var phoneNumber = $(this).data('phone_number');
        var status = $(this).data('status');
        $('#edit-id').val(id);
        $('#edit-phoneNumber').val(phoneNumber);
        $('#edit-status').val(status);
        editModal.modal('show');
    });
});
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('.alert').forEach(function(alert) { 
            setTimeout(function() {
                setTimeout(function() {
                    alert.remove(); 
                }); 
            }, 2000);
        });
    });
</script>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<?= $this->include('footer'); ?>
