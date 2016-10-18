@extends(Auth::user() ? 'layouts.app' : 'home.layout')
@section('content')
    @if (Auth::user())
        @include ('teams.orgleftmenu')
    @else

    @endif

    <div id="content" class="col-sm-10 tournament_profile">
        <div class="col-md-8 col-md-offset-2 col-xs-12">
            <div class="team_view">
                {!! Helper::Images(!empty($orgInfo[0]['logo'])?$orgInfo[0]['logo']:'','organization',array('height'=>100,'width'=>'100%', 'class'=>'no-border-radius') )!!}
                <h1>{{ $orgInfo[0]['name'] or "" }}</h1>
            </div>
            <div class="group_no clearfix">
                <h4 class="stage_head">{{ trans('message.organization.fields.orgdetails') }}</h4>
            </div>
            <table class="table table-striped table-bordered">
                <tbody>
                <tr>
                    <th>{{   trans('message.organization.fields.name') }}</th>
                    <td>{{ $orgInfo[0]['name']}}</td>
                </tr>

                <tr>
                    <th>{{   trans('message.organization.fields.contactnumber') }}</th>
                    <td>{{ $orgInfo[0]['contact_number']}}</td>

                </tr>
                @if ($orgInfo[0]['alternate_contact_number'] !== '')
                    <tr>
                        <th>{{   trans('message.organization.fields.altcontactnumber') }}</th>
                        <td>{{ $orgInfo[0]['alternate_contact_number']}}</td>
                    </tr>
                @endif
                @if ( $orgInfo[0]['contact_name'])
                    <tr>
                        <th>{{   trans('message.organization.fields.contactpersonname') }}</th>
                        <td> {{ $orgInfo[0]['contact_name'] }}</td>
                    </tr>
                @endif
                <tr>
                    <th>{{   trans('message.organization.fields.email') }}</th>
                    <td>{{ $orgInfo[0]['email']}}</td>
                </tr>
                <tr>
                    <th>{{   trans('message.organization.fields.organizationtype') }}</th>
                    <td>{{ $orgInfo[0]['organization_type']}}</td>
                </tr>
                @if ( $orgInfo[0]['social_facebook'])
                    <tr>
                        <th>{{   trans('message.organization.fields.facebook') }}</th>
                        <td>{{ $orgInfo[0]['social_facebook']}}</td>
                    </tr>
                @endif
                @if ( $orgInfo[0]['social_twitter'])
                    <tr>
                        <th>{{   trans('message.organization.fields.twitter') }}</th>
                        <td>{{ $orgInfo[0]['social_twitter']}}</td>
                    </tr>
                @endif
                @if ( $orgInfo[0]['social_linkedin'])
                    <tr>
                        <th>{{   trans('message.organization.fields.linkedin') }}</th>
                        <td>{{ $orgInfo[0]['social_linkedin']}}</td>
                    </tr>
                @endif
                @if ( $orgInfo[0]['social_googleplus'])
                    <tr>
                        <th>{{   trans('message.organization.fields.googleplus') }}</th>
                        <td>{{ $orgInfo[0]['social_googleplus']}}</td>
                    </tr>
                @endif
                @if ( $orgInfo[0]['website_url'])
                    <tr>
                        <th>{{   trans('message.organization.fields.websiteurl') }}</th>
                        <td>{{ $orgInfo[0]['website_url']}}</td>
                    </tr>
                @endif
                <tr>
                    <th>{{   trans('message.organization.fields.about') }}</th>
                    <td>{{ $orgInfo[0]['about']}}</td>
                </tr>
                <tr>
                    <th>{{   trans('message.organization.fields.location') }}</th>
                    <td>{{ $orgInfo[0]['location']}}</td>

                </tr>

                </tbody>
            </table>
        </div>
    </div>
@endsection
