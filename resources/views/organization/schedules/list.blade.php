@extends(Helper::check_if_org_template_enabled()?'layouts.organisation':'layouts.app') 
@section('content')
    @include ('teams.orgleftmenu')
    <div class="col-sm-10 col-xs-12 groups">
        <div class="panel">
            <div class="panel-top-container clearfix">
                <div class="pull-left"><h4 class="panel-heading">Schedule</h4></div>
                <div class="pull-right panel-right-btn">
                </div> {{-- /.panel-right-btn--}}
            </div> {{-- /.panel-top-container --}}
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            <div class="sportsjun-datafilter">
                <form method="GET">
                    <div class="form-group">
                        <label>Find events in your organization</label>
                        <div class="input-group">
                            <input id="filter_event" class="form-control" name="filter-event"
                                   value="{{ $filter_event }}"/>
                            <span class="input-group-btn"><button class="btn btn-tiny btn-primary "
                                                                  type="submit">Find</button></span>
                        </div>
                    </div>
                </form>
            </div>
            @if($tournaments->count())

                <div>
                    @include('organization.schedules.partials.schedule_list')
                </div>
            @else
                <div id="schedule_empty text-left">
                    <p>No Records</p>
                </div>
            @endif

        </div> {{-- /.panel --}}
    </div> {{-- /.col-lg-10 --}}
    @include('organization.groups.partials.create_group_modal')
    <script>
        $(document).ready(function () {
            $('#filter_event').autocomplete({
                        minLength: 0,
                        source: function (request, response) {
                            $.getJSON("{{route('organization.schedules.tournamentlist',['id'=>$id])}}", request, function (data, status, xhr) {
                                var items = [];
                                for (var key in data) {
                                    items.push(
                                            {
                                                label: data[key],
                                                value: data[key]
                                            }
                                    )
                                }
                                ;
                                response(data);
                            });
                        }
                    }
            );
            $('#filter_event').on('focus', function () {
                $(this).autocomplete("search");
            });

        });
    </script>
@endsection
