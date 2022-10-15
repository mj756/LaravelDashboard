@extends('layout')
<link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

@section('content')
    <div class="container table-responsive" style="margin: auto;padding: 20px">
        <table class="table data-table">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Gender</th>
                    <th>InsertedOn</th>
                    <th>Dob</th>
                    <th>ProfileImage</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

    <script type="text/javascript">
        function deleteRecord(id, row_index) {
            $.ajax({
                url: "{{ url('/api/user/deleteuser') }}",
                type: 'get',
                data: {
                    "id": id,
                },
                success: function(response) {
                    var i = row_index.parentNode.parentNode.rowIndex;
                    $('.data-table').DataTable().ajax.reload(null, true);
                }
            });
        }

        $(function() {

            var table = $('.data-table').DataTable({
                processing: false,
                serverSide: false,
                ajax: "/user",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'gender',
                        name: 'gender'
                    },
                    {
                        data: 'insertedOn',
                        name: 'insertedOn'
                    },
                    {
                        data: 'dob',
                        name: 'dob'
                    },
                    {
                        data: 'profileImage',
                        name: 'profileImage',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },

                ]
            });

        });
    </script>
@endsection
