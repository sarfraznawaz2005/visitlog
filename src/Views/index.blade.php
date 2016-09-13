<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VisitLog</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
    <link rel="stylesheet"
          href="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->

    <!--[if lt IE 9]>
    <script src="//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

<div class="container-fluid">
    <div class="row">

        <div>
            <h4><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span> Laravel Visit Log</h4>
        </div>

        <div class="col-sm-12 col-md-12 table-container">
            <table id="table-log" class="table table-striped">
                @if (config('visitlog.iptolocation'))
                    <thead>
                    <tr>
                        <th>IP</th>
                        <th>Browser</th>
                        <th>OS</th>
                        @if (config('visitlog.log_user'))
                            <th>User</th>
                        @endif
                        <th>Country</th>
                        <th>Country Code</th>
                        <th>Region</th>
                        <th>City</th>
                        <th>Zip</th>
                        <th>Timezone</th>
                        <th>Location (Lat, Lon)</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($visitlogs as $visitlog)
                        <tr>
                            <td>{{$visitlog->ip}}</td>
                            <td>{{$visitlog->browser}}</td>
                            <td>{{$visitlog->os}}</td>
                            @if (config('visitlog.log_user'))
                                <td>{{$visitlog->user_id}} - {{$visitlog->user_name}}</td>
                            @endif
                            <td>{{$visitlog->country_name}}</td>
                            <td>{{$visitlog->country_code}}</td>
                            <td>{{$visitlog->region_name}}</td>
                            <td>{{$visitlog->city}}</td>
                            <td>{{$visitlog->zip_code}}</td>
                            <td>{{$visitlog->time_zone}}</td>
                            <td>{{$visitlog->latitude}}, {{$visitlog->longitude}}</td>
                            <td>{{$visitlog->created_at}}</td>
                            <td>
                                <a data-placement="top" data-original-title="Delete"
                                   class="confirm-delete text-danger"
                                   data-label="Visit Log"
                                   rel="{{route('__delete_visitlog__', ['id'=>$visitlog->id])}}"
                                   href="javascript:void(0);">
                                    <b class="glyphicon glyphicon-trash"></b>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                @else
                    <thead>
                    <tr>
                        <th>IP</th>
                        <th>Browser</th>
                        <th>OS</th>
                        @if (config('visitlog.log_user'))
                            <th>User</th>
                        @endif
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($visitlogs as $visitlog)
                        <tr>
                            <td>{{$visitlog->ip}}</td>
                            <td>{{$visitlog->browser}}</td>
                            <td>{{$visitlog->os}}</td>
                            @if (config('visitlog.log_user'))
                                <td>{{$visitlog->user_id}} - {{$visitlog->user_name}}</td>
                            @endif
                            <td>{{$visitlog->created_at}}</td>
                            <td>
                                <a data-placement="top" data-original-title="Delete"
                                   class="confirm-delete text-danger"
                                   data-label="Visit Log"
                                   rel="{{route('__delete_visitlog__', ['id'=>$visitlog->id])}}"
                                   href="javascript:void(0);">
                                    <b class="glyphicon glyphicon-trash"></b>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                @endif
            </table>
        </div>
    </div>
</div>

<!-- delete confirm modal start -->
<div class="modal fade " id="modal-delete-confirm" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header modal-header-danger">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span></button>
                <h4 class="modal-title">
                    <b class="glyphicon-4x glyphicon glyphicon-trash"></b>
                    Confirm Delete
                </h4>
            </div>

            <div class="modal-body"></div>

            <div class="modal-footer">
                <button class="btn btn-default col-sm-2 pull-right" data-dismiss="modal">
                    Close
                </button>

                <form action="#" method="POST" style="display: inline;">
                    {{csrf_field()}}
                    {{method_field('DELETE')}}

                    <button style="margin-right: 10px;" type="button"
                            class="btn confirm-delete-red-button btn-danger col-sm-2 pull-right"
                            id="frm_submit">Delete
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
<!-- delete confirm modal end -->

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
<script src="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.js"></script>
<script>
    $(document).ready(function () {
        var $body = $('body');

        $('#table-log').DataTable();

        // confirm delete
        $body.on('click', '.confirm-delete', function (e) {
            var label = $(this).data('label');
            var $dialog = $('#modal-delete-confirm');

            $dialog.find('.modal-body').html('You are about to delete <strong>' + label + '</strong>, continue ?');
            $dialog.find('form').attr('action', this.rel);
            $dialog.modal('show');

            e.preventDefault();
        });

        $body.on('click', '.confirm-delete-red-button', function (e) {
            $(this).attr('disabled', true);
            $(this).closest('form')[0].submit();
        });
    });
</script>
</body>
</html>
