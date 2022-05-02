@extends('layouts.master')
@section('content')
    <section class="container content   ">
        <div class="row">
            <h4 class="page-title border-bottom">Mark List</h4>
        </div>
        <div class="row mb-2">


            <div class="col-md-6 ">
                <a  class="btn btn-primary float-end student-modal-button add-button " href="/"
                ><i class="icon-list "></i> Students
                </a>
            </div>
            <div class="col-md-6">
                <button type="button" class="btn btn-primary float-end student-modal-button add-button float-right"
                        id="add_student_mark"
                        data-toggle="modal"
                        data-target="#mark_modal"><i class="icon-plus "></i> Evaluate
                </button>
            </div>
        </div>
        <div class="row ">

            <div class="table-responsive">
                <table class="table" id="mark_list_table" style="width:100%">
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Maths</th>
                        <th>Science</th>
                        <th>History</th>
                        <th>Term</th>
                        <th>Total Marks</th>
                        <th>Evaluator</th>
                        <th>ReportingTeacher</th>
                        <th>Created On</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

        </div>
    </section>
    <!-- ------------------- STUDENT MARK MODAL ---------------------- -->
    <div class="modal fade" id="mark_modal" tabindex="-1" role="dialog" data-backdrop="static"
         aria-labelledby="markModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header m-head">
                    <h5 class="modal-title" id="mark-modal-title">Add Score</h5>
                    <button type="button" class="close modal-close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" id="mark_form">
                        <div class="form-group">
                            <input type="hidden" id="marklist_id" name="marklist_id">
                            <label>Student Name</label>
                            <select type="text" required class="form-control" name="student_name" id="student">

                            </select>
                        </div>

                        <div class="form-group">
                            <label>Term</label>
                            <select type="text" required class="form-control" id="term" name="term">
                                <option value="" selected disabled>Select Term</option>
                                <option value="One">One</option>
                                <option value="Two">Two</option>
                            </select>
                        </div>
                        <h4>Subject </h4>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Maths </label>
                                    <input type="number" required class="form-control" name="maths" id="maths">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Science</label>
                                    <input type="number" required class="form-control" name="science" id="science">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>History</label>
                                    <input type="number" required class="form-control" name="history" id="history">
                                </div>
                            </div>
                        </div>


                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger modal-close" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary add-mark" id="add-mark">Save</button>
                            <button type="button" class="btn btn-primary update-mark" id="update-mark">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @endsection

        @section('js')
            <script>
                var userId = "{{ auth()->id() }}";
                $("#update-mark").hide();

                $(document).ready(function () {

                    $('#mark_list_table').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: '{{route('marks.index')}}',
                        columns: [
                            {data: 'marklist_id', name: 'id'},
                            {data: 'name', name: 'name'},
                            {data: 'maths', name: 'maths'},
                            {data: 'science', name: 'science'},
                            {data: 'history', name: 'history'},
                            {data: 'term', name: 'term'},
                            {data: 'total_mark', name: 'total_mark'},
                            {data: 'evaluator', name: 'evaluator'},
                            {data: 'reporting_teacher', name: 'reporting_teacher'},
                            {data: 'created_on', name: 'created_at'},
                            {
                                data: 'action', className: "details-control",
                                render: function (data, tr, row) {
                                    return '<a class="edit-student btn" id="edit_' + row.marklist_id + '" data-toggle="modal"\n' +
                                        '                    data-target="#mark_modal" data-id="' + row.id + '" data-term="' + row.term + '" data-maths="' + row.maths + '" data-science="' +
                                        row.science + '" data-history="' + row.history + '" data-student="' + row.name + '" onclick="editMark(' + row.marklist_id + ')" title="Edit"><i class="icon-edit icon-large"></i></a> ' +
                                        '<button class="btn delete-student" id="xf" onclick="deleteMark(' + row.marklist_id + ')" ><i class="icon-fixed-width icon-trash icon-large"title="Delete"></i></button>'
                                }
                            }
                        ]
                    });
                });

                $('#add_student_mark').on('click', function () {
                    var type = 'add';
                    getStudents(null, type);
                });

                // ----------------------------- GET STUDENT LIST -------------------- //
                function getStudents(selected = null, type) {
                    $.ajax({
                        type: "get",
                        url: "{{ route('students-data') }}",
                        data: {
                            process: type,
                            _token: '{{csrf_token()}}'
                        },
                        success: function (res) {
                            if(selected == null) {
                                $('#student').append($('<option selected disabled >').val('').text('Select Student'));
                            }
                            $.each(res, function (i) {
                                if (res[i].name === selected) {
                                    $('#student').append($('<option selected>').val(res[i].id).text(res[i].name));
                                } else {
                                    $('#student').append($('<option>').val(res[i].id).text(res[i].name));
                                }
                            });

                        }
                    });
                }

                // ---------------- ACTIONS ON MODAL CLOSE -----------------------
                $('.modal-close').on('click', function () {
                    $("#student").empty();
                    document.getElementById('mark_form').reset();
                    $("#mark-modal-title").html('Add Score');
                    $("#add-mark").html('Save');
                    $("#update-mark").hide();
                    $("#add-mark").show();

                });


                // ---------------- MARK ADDING-----------------------
                $('#add-mark').on('click', function () {
                    let formData = new FormData($('#mark_form')[0]);
                    let method = "POST";
                    let url = "{{ route('marks.store') }}";
                    let studentId = $('#student').val();
                    formData.append("_token", '{{csrf_token()}}');

                    formData.set('student_id', studentId);
                    formData.set('evaluator_id', userId);
                    callMarksAjax(formData, method, userId, url);
                });

                // ----------------- SCORE EDIT -----------------------
                function editMark(markListId) {
                    var type = 'edit';
                    $("#update-mark").show();
                    $("#add-mark").hide();
                    $("#mark-modal-title").html('Edit Score');
                    $("#add-mark").html('Update');
                    var markList = $('#edit_' + markListId);
                    var student = markList.attr('data-student');
                    getStudents(student, type);
                    $('#marklist_id').val(markListId);
                    $('#student').val(markList.attr('data-name'));
                    $('#maths').val(markList.attr('data-maths'));
                    $('#science').val(markList.attr('data-science'));
                    $('#history').val(markList.attr('data-history'));
                    $('#term').val(markList.attr('data-term'));
                    $('#update-mark').on('click', function () {

                        var formData = new FormData($('#mark_form')[0]);
                        let method = "POST";
                        let url = "{{ route('marks.store') }}";
                        formData.append("_token", '{{csrf_token()}}');
                        formData.set('marklist_id', markListId);
                        callMarksAjax(formData, method, userId, url);
                    });
                }

                // ----------------- SCORE DELETION -----------------------
                function deleteMark(markListId) {

                    let formData = new FormData($('#mark_form')[0]);
                    let method = "DELETE";
                    let url = "{{ url('marks') }}/" + markListId;
                    formData.append("_token", '{{csrf_token()}}');
                    formData.set('marklist_id', markListId);

                    Swal.fire({
                        title: 'Are you sure?',
                        showDenyButton: true,
                        // showCancelButton: true,
                        confirmButtonText: 'Cancel',
                        denyButtonText: `Delete`,
                    }).then((result) => {
                        if (result.isConfirmed) {

                        } else if (result.isDenied) {
                            callMarksAjax(formData, method, userId, url);
                        }
                    });
                }

                // --------------------------- MARK AJAX FUNCTION ----------------------- //
                function callMarksAjax(formData, method, userId = null, url) {
                    $.ajax({
                        type: method,
                        url: url,
                        data: formData,
                        dataType: 'JSON',
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function (response) {
                            $('#mark_list_table').DataTable().ajax.reload();
                            $('.modal-close').click();
                            document.getElementById("mark_form").reset();
                            toastr.success(response.message);
                        },
                        error: function (response) {

                        }
                    });
                }

            </script>
@endsection