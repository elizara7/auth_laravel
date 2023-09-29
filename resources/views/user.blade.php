@extends('layouts.app');
@section('title', 'User')

@section('content')
    <Table>
        <thead>
            <tr>
                <th>Test</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </Table>
    {{-- add new employee modal start --}}
    <div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="exampleModalLabel" data-bs-backdrop="static"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ajout user</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <input type="hidden" name="user_id" id="user_id">
                <form action="#" method="POST" id="add_user_form" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-4 bg-light">
                        <div class="row">
                            <div class="col-lg">
                                <label for="email">Name</label>
                                <input type="name" name="name" class="form-control" placeholder="Name" required>
                            </div>
                            <div class="col-lg">
                                <label for="email">E-mail</label>
                                <input type="email" name="email" class="form-control" placeholder="E-mail" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg">
                                <label for="email">Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Password" required>
                            </div>
                            <div class="col-lg">
                                <label for="email">Confirmation Password</label>
                                <input type="password" name="cpassword" class="form-control"
                                    placeholder="Confirmation Password" required>
                            </div>
                        </div>
                        <div class="my-2">
                            <label for="phone">Phone</label>
                            <input type="number" name="phone" class="form-control" placeholder="Phone" required>
                        </div>
                        <div class="my-2">
                            <label for="dob">Date de naissance</label>
                            <input type="date" name="dob" class="form-control" placeholder="Date de naissance"
                                required>
                        </div>
                        <div class="row">
                            <div class="col-lg">
                                <label for="gender">Gender</label>
                                <select name="gender" id="gender" class="form-select rounded-0">
                                    <option value="" selected disabled>-Selectionné-</option>
                                    <option value="Homme">Male</option>
                                    <option value="Femme">Femme</option>
                                </select>
                            </div>
                        </div>
                        <div class="my-2">
                            <label for="picture">Select Photo</label>
                            <input type="file" name="picture" class="form-control" required>
                        </div>
                        <div class="mt-2" id="picture">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" id="add_user_btn" class="btn btn-primary">Ajout user</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- add new employee modal end --}}

    {{-- edit employee modal start --}}
    <div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        data-bs-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Employee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="#" method="POST" id="edit_employee_form" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="emp_id" id="emp_id">
                    <input type="hidden" name="emp_avatar" id="emp_avatar">
                    <div class="modal-body p-4 bg-light">
                        <div class="row">
                            <div class="col-lg">
                                <label for="fname">First Name</label>
                                <input type="text" name="fname" id="fname" class="form-control"
                                    placeholder="First Name" required>
                            </div>
                            <div class="col-lg">
                                <label for="lname">Last Name</label>
                                <input type="text" name="lname" id="lname" class="form-control"
                                    placeholder="Last Name" required>
                            </div>
                        </div>
                        <div class="my-2">
                            <label for="email">E-mail</label>
                            <input type="email" name="email" id="email" class="form-control"
                                placeholder="E-mail" required>
                        </div>
                        <div class="my-2">
                            <label for="phone">Phone</label>
                            <input type="tel" name="phone" id="phone" class="form-control"
                                placeholder="Phone" required>
                        </div>
                        <div class="my-2">
                            <label for="post">Post</label>
                            <input type="text" name="post" id="post" class="form-control" placeholder="Post"
                                required>
                        </div>
                        <div class="my-2">
                            <label for="avatar">Select Avatar</label>
                            <input type="file" name="avatar" class="form-control">
                        </div>
                        <div class="mt-2" id="avatar">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" id="edit_employee_btn" class="btn btn-success">Update Employee</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- edit employee modal end --}}


    <div class="container">
        <div class="row my-5">
            <div class="col-lg-12">
                <div class="card shadow">
                    <div class="card-header bg-danger d-flex justify-content-between align-items-center">
                        <h3 class="text-light">Manage Employees</h3>
                        <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#addEmployeeModal"><i
                                class="bi-plus-circle me-2"></i>Add New Employee</button>
                    </div>
                    <div class="card-body" id="show_all_employees">
                        <h1 class="text-center text-secondary my-5">Loading...</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

    <script>
        //fetch all user ajax request
        fetchAllUser();

        function fetchAllUser() {
            $.ajax({
                url: '{{ route('fetchAll') }}',
                method: 'get',
                success: function(res) {
                    $("#show_all_employees").html(res);
                    $("table").DataTable({
                        order: [0, 'desc']
                    });
                }
            });
        }

        // delete employee ajax request
        $(document).on('click', '.deleteIcon', function(e) {
            e.preventDefault();
            let id = $(this).attr('id');
            let csrf = '{{ csrf_token() }}';
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('delete') }}',
                        method: 'delete',
                        data: {
                            id: id,
                            _token: csrf
                        },
                        success: function(res) {
                            console.log(res);
                            Swal.fire(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success'
                            )
                            fetchAllUsers();
                        }
                    });
                }
            })
        });

        // fetch all employees ajax request
        fetchAllEmployees();

        function fetchAllEmployees() {
            $.ajax({
                url: '{{ route('fetchAll') }}',
                method: 'get',
                success: function(res) {
                    $("#show_all_users").html(res);
                    $("table").DataTable({
                        order: [0, 'desc']
                    });
                }
            });
        }

        // add new user ajax request
        $("#add_user_form").submit(function(e) {
            e.preventDefault();
            const fd = new FormData(this);
            $("#add_user_btn").text('Ajouter...');
            $.ajax({
                url: '{{ route('userAjout') }}',
                method: 'post',
                data: fd,
                cache: false,
                processData: false,
                contentType: false,
                success: function(res) {
                    if (res.status == 200) {
                        Swal.fire(
                            'Added!',
                            'User added successfully!',
                            'success'
                        )
                        fetchAllUser();
                    }
                    $("#add_user_btn").text('Ajout user');
                    $("#add_user_form")[0].reset();
                    $("#addEmployeeModal").modal('hide');
                }
            });
        })
    </script>

@endsection
