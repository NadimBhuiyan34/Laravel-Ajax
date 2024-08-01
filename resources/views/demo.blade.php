<!DOCTYPE html>
<html>

<head>
    <title>Ajax CRUD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
</head>

<body>
    <div class="container">
        <div class="mt-3 text-center">
            <h1>Ajax CRUD</h1><br>
        </div>
        <div class="alert alert-primary" role="alert" id="message" style="display:none;"></div>
        <div class="card mt-3">
            <div class="card-header d-flex">
                <div>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
                        <i class="fa-solid fa-plus"></i> Add New
                    </button>
                </div>
            </div>
            <div class="card-body">
                <table id="example" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Roll</th>
                            <th>Address</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="studentTable">
                        <!-- Student data will be loaded here via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add New Modal -->
        <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add New Student</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="studentForm">
                            <div class="form-group">
                                <label for="name">Name:</label>
                                <textarea class="form-control" id="name" name="name" rows="3"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="roll">Roll:</label>
                                <input type="text" class="form-control" id="roll" name="roll">
                            </div>
                            <div class="form-group">
                                <label for="address">Address:</label>
                                <input type="text" class="form-control" id="address" name="address">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include necessary JS files -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function() {
            $('#example').DataTable();

            function showMessage(message) {
                $('#message').text(message).show();
                setTimeout(() => $('#message').hide(), 3000);
            }

            function loadStudents() {
                $.ajax({
                    url: 'fetch_data.php',
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        var studentTable = $('#studentTable');
                        studentTable.empty();
                        data.forEach(function(student) {
                            var studentRow = `
                            <tr data-id="${student.id}">
                                <td>${student.name}</td>
                                <td>${student.roll}</td>
                                <td>${student.addres}</td>
                                <td class="d-flex gap-2">
                                    <button class="btn btn-danger btn-sm btn-delete" data-id="${student.id}">Delete</button>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal${student.id}">
                                        Edit
                                    </button>
                                    <div class="modal fade" id="editModal${student.id}" tabindex="-1" aria-labelledby="editModalLabel${student.id}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editModalLabel${student.id}">Edit Student</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form id="editForm${student.id}" class="editForm">
                                                        <div class="form-group">
                                                            <label for="name">Name:</label>
                                                            <textarea class="form-control" id="name" name="name" rows="3">${student.name}</textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="roll">Roll:</label>
                                                            <input type="text" class="form-control" id="roll" name="roll" value="${student.roll}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="address">Address:</label>
                                                            <input type="text" class="form-control" id="address" name="address" value="${student.addres}">
                                                            <input type="hidden" name="id" value="${student.id}">
                                                            <input type="hidden" name="edit1" value="1">
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary">Save</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        `;
                            studentTable.append(studentRow);
                        });
                    },
                    error: function(xhr, status, error) {
                        console.log('AJAX request error:', xhr.responseText);
                    }
                });
            }

            loadStudents();

            $('#studentForm').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'fetch_data.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        showMessage(response.message);
                        $('#studentForm')[0].reset();
                        $('#addModal').modal('hide');
                        loadStudents();
                    },
                    error: function(xhr, status, error) {
                        console.log('AJAX request error:', xhr.responseText);
                    }
                });
            });

            $(document).on('click', '.btn-delete', function() {
                var studentId = $(this).data('id');
                $.ajax({
                    url: 'fetch_data.php',
                    type: 'POST',
                    data: { id: studentId },
                    success: function(response) {
                        showMessage(response.message);
                        loadStudents();
                    },
                    error: function(xhr, status, error) {
                        console.log('AJAX request error:', xhr.responseText);
                    }
                });
            });

            $(document).on('submit', '.editForm', function(e) {
                e.preventDefault();
                var form = $(this);
                $.ajax({
                    url: 'fetch_data.php',
                    type: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        showMessage(response.message);
                        loadStudents();
                        $('.modal').modal('hide');
                    },
                    error: function(xhr, status, error) {
                        console.log('AJAX request error:', xhr.responseText);
                    }
                });
            });
        });
    </script>
</body>

</html>
