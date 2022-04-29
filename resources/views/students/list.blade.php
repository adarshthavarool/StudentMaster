@extends('layouts.master')
@section('content')
    <section class="container content">
        <div class="row">
            <h4 class="page-title border-bottom">Students</h4>
        </div>

        <div class="row  mb-2">
            <div class="col-md-6 justify-content-start">
                <a  class="btn btn-primary float-end student-modal-button add-button " href="{{ route("marks.list") }}"
                        ><i class="icon-list "></i> Mark List
                </a>
            </div>
            <div class="col-md-6">
                <button type="button" class="btn btn-primary float-end student-modal-button add-button float-right" id="new_student"
                        data-toggle="modal"
                        data-target="#student_modal"
                        data-whatever=""><i class="icon-plus "></i> Add Student
                </button>
            </div>
        </div>
        <div class="row ">

            <div class="table-responsive">
                <table class="table" id="student_listing_table">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Age</th>
                        <th>Gender</th>
                        <th>Reporting Teacher</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

        </div>
    </section>
    <!-- ------------------- CREATE STUDENT MODAL ---------------------- -->
    <div class="modal fade" id="student_modal" tabindex="-1" role="dialog" data-backdrop="static"
         aria-labelledby="studentModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="student-modal-title">Add Student</h5>
                    <button type="button" class="close modal-close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" id="student_form">
                        <div class="form-group">
                            {{--                            <input type="hidden" name="student_id" id="student_id">--}}
                            <label>Student Name</label>
                            <input type="text" required class="form-control" id="student_name" name="name">
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Age</label>
                                    <input type="number" required class="form-control" id="age" name="age">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Gender</label>
                                    <select type="text" required class="form-control" id="gender" name="gender">
                                        <option value="" selected disabled>Select Gender</option>
                                        <option value="Female">Female</option>
                                        <option value="Male">Male</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Reporting Teacher</label>
                            <select type="text" required class="form-control" id="teacher_id" name="teacher_id">

                            </select>
                        </div>
                        {{--                </div>--}}
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger modal-close" data-dismiss="modal">Cancel
                            </button>
                            <button type="button" id="create-student" class="btn btn-primary create_student">Add
                            </button>
                            <button type="button" id="update-student" class="btn btn-primary create_student">Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @endsection

        @section('js')
            <script>
                var userId = "{{ auth()->id() }}";
                $("#update-student").hide();
                $(document).ready(function () {

                    $('#student_listing_table').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: '{{route('students.index')}}',
                        columns: [
                            {data: 'id', className: "details-control"},
                            {data: 'name', className: "details-control"},
                            {data: 'age', className: "details-control"},
                            {data: 'gender', className: "details-control"},
                            {data: 'reporting_teacher', className: "details-control"},
                            {
                                data: 'action', className: "details-control",
                                render: function (data, tr, row) {
                                    {{--return '<a class="btn btn-danger-outline fa fa-trash" href="{{ route('students.destroy')}}/ ' + row.id + '"> </a>'--}}
                                        return '<a class="edit-student btn" id="edit_' + row.id + '" data-toggle="modal"\n' +
                                        '                    data-target="#student_modal" data-id="' + row.id + '" data-name="' + row.name +
                                        '" data-age="' + row.age + '" data-gender="' + row.gender + '" data-teacher="' + row.reporting_teacher +
                                        '" onclick="editStudent(' + row.id + ')"><i class="icon-edit icon-large" title="Edit"></i></a> ' +
                                        '<button class="btn delete-student" id="" onclick="deleteStudent(' + row.id + ')" ><i class="icon-trash icon-large" title="Delete"></i></button>'
                                }
                            }

                        ]
                    });
                });


                $('#new_student').on('click', function () {
                    getTeachers();
                });

                // ----------------------------- GET TEACHERS LIST -------------------- //
                function getTeachers(selected = null) {
                    $.ajax({
                        type: "get",
                        url: "{{ route('teachers-data') }}",
                        data: {
                            _token: '{{csrf_token()}}'
                        },
                        success: function (res) {
                            if(selected == null) {
                                $('#teacher_id').append($('<option selected disabled >').val('').text('Select Teacher'));
                            }
                            $.each(res, function (i) {
                                if (res[i].user.name === selected) {
                                    $('#teacher_id').append($('<option selected>').val(res[i].id).text(res[i].user.name));
                                } else {
                                    $('#teacher_id').append($('<option>').val(res[i].id).text(res[i].user.name));
                                }
                            });

                        }
                    });
                }

                // ---------------- ACTIONS ON MODAL CLOSE -----------------------
                $('.modal-close').on('click', function () {
                    document.getElementById('student_form').reset();
                    $("#student-modal-title").html('Add Student');
                    $("#update-student").hide();
                    $("#create-student").show();
                    $("#teacher_id").empty();

                });


                // ---------------- STUDENT CREATION -----------------------
                $('#create-student').on('click', function () {
                    let formData = new FormData($('#student_form')[0]);
                    let method = "POST";
                    let url = "{{ route('students.store') }}";
                    formData.append("_token", '{{csrf_token()}}');
                    callStudentAjax(formData, method, userId, url);
                });

                // ----------------- STUDENT EDIT -----------------------
                function editStudent(studentId) {
                    $("#update-student").show();
                    $("#create-student").hide();
                    $("#student-modal-title").html('Edit Student');
                    $("#create-student").html('Update');
                    var student = $('#edit_' + studentId);
                    var reportingTeacher = student.attr('data-teacher');
                    getTeachers(reportingTeacher);
                    $('#student_id').val(studentId);
                    $('#student_name').val(student.attr('data-name'));
                    $('#age').val(student.attr('data-age'));
                    $('#gender').val(student.attr('data-gender'));
                    $('#teacher_id option:selected').text(student.attr('data-teacher'));
                    $('#update-student').on('click', function () {

                    var formData = new FormData($('#student_form')[0]);
                    let method = "POST";
                    let url = "{{ route('students.store') }}";
                    formData.append("_token", '{{csrf_token()}}');
                    formData.set('student_id', studentId);
                        callStudentAjax(formData, method, userId, url);
                    });
                }

                // ----------------- STUDENT DELETION -----------------------
                function deleteStudent(studentId) {

                    let formData = new FormData($('#student_form')[0]);
                    let method = "DELETE";
                    let url = "{{ url('students') }}/" + studentId;
                    formData.append("_token", '{{csrf_token()}}');
                    formData.set('student_id', studentId);
                    Swal.fire({
                        title: 'Are you sure?',
                        showDenyButton: true,
                        // showCancelButton: true,
                        confirmButtonText: 'Cancel',
                        denyButtonText: `Delete`,
                    }).then((result) => {
                        if (result.isConfirmed) {

                        } else if (result.isDenied) {
                            callStudentAjax(formData, method, userId, url);

                        }
                    });
                }

                // --------------------------- STUDENT AJAX FUNCTION ----------------------- //
                function callStudentAjax(formData, method, userId = null, url) {
                    $.ajax({
                        type: method,
                        url: url,
                        data: formData,
                        dataType: 'JSON',
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function (response) {
                            console.log(response);
                            console.log(response.message);
                            $('#student_listing_table').DataTable().ajax.reload();
                            $('.modal-close').click();
                            document.getElementById("student_form").reset();
                            toastr.success(response.message);
                        },
                        error: function (response) {
                            // console.log(response.message);
                            // $.each(response.responseJSON.errors, function (index, item) {
                            // });
                        }
                    });
                }


            </script>
@endsection