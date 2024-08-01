<!DOCTYPE html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laravel AJAX CRUD</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Laravel AJAX CRUD</h2>
    <div id="message"></div>
    <button id="createNewStudent" class="btn btn-success mb-3">Add Student</button>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Roll</th>
                <th>Address</th>
                <th width="150px">Action</th>
            </tr>
        </thead>
        <tbody id="students-crud">
            <!-- Data will be appended here by AJAX -->
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="studentModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="studentForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="modelHeading"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="student_id" id="student_id">
                    <input type="hidden" name="_method" id="_method" value="POST">
                    <div class="form-group">
                        <label for="name" class="col-form-label">Name:</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="roll" class="col-form-label">Roll:</label>
                        <input type="text" class="form-control" id="roll" name="roll" required>
                    </div>
                    <div class="form-group">
                        <label for="address" class="col-form-label">Address:</label>
                        <input type="text" class="form-control" id="address" name="address" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="saveBtn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script>
$(document).ready(function() {
    // Setup CSRF token for AJAX
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    fetchStudents();

    // Fetch students
    function fetchStudents() {
        $.ajax({
            url: '/fetch-students',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                var rows = '';
                response.forEach(function(student) {
                    rows += '<tr>';
                    rows += '<td>' + student.name + '</td>';
                    rows += '<td>' + student.roll + '</td>';
                    rows += '<td>' + student.address + '</td>';
                    rows += '<td>';
                    rows += '<button class="btn btn-primary btn-sm editStudent" data-id="' + student.id + '">Edit</button>';
                    rows += ' <button class="btn btn-danger btn-sm deleteStudent" data-id="' + student.id + '">Delete</button>';
                    rows += '</td>';
                    rows += '</tr>';
                });
                $('#students-crud').html(rows);
            }
        });
    }

    // Add new student
    $('#createNewStudent').click(function() {
        $('#saveBtn').val("create-student");
        $('#student_id').val('');
        $('#_method').val('POST'); // Ensure POST for create
        $('#studentForm').trigger("reset");
        $('#modelHeading').html("Add New Student");
        $('#studentModal').modal('show');
    });

    // Save student
    $('#studentForm').on('submit', function(event) {
        event.preventDefault();
        var formData = $(this).serialize();
        var method = $('#_method').val(); // Get the method (POST or PUT)

        $.ajax({
            url: '/students' + ($('#student_id').val() ? '/' + $('#student_id').val() : ''),
            type: method,
            data: formData,
            success: function(response) {
                $('#studentForm').trigger("reset");
                $('#studentModal').modal('hide');
                fetchStudents();
                $('#message').html('<div class="alert alert-success">' + response.success + '</div>');
            },
            error: function(response) {
                $('#message').html('<div class="alert alert-danger">' + response.responseJSON.message + '</div>');
            }
        });
    });

    // Edit student
    $('body').on('click', '.editStudent', function() {
        var student_id = $(this).data('id');
        $.get('/students/' + student_id, function(data) {
            $('#modelHeading').html("Edit Student");
            $('#saveBtn').val("edit-student");
            $('#_method').val('PUT'); // Ensure PUT for update
            $('#studentModal').modal('show');
            $('#student_id').val(data.id);
            $('#name').val(data.name);
            $('#roll').val(data.roll);
            $('#address').val(data.address);
        });
    });

    // Delete student
    $('body').on('click', '.deleteStudent', function() {
        var student_id = $(this).data("id");
        var result = confirm("Are you sure you want to delete?");
        if (result) {
            $.ajax({
                url: '/students/' + student_id,
                type: 'DELETE',
                success: function(response) {
                    fetchStudents();
                    $('#message').html('<div class="alert alert-success">' + response.success + '</div>');
                },
                error: function(response) {
                    $('#message').html('<div class="alert alert-danger">' + response.responseJSON.message + '</div>');
                }
            });
        }
    });
});
</script>
</body>
</html>
