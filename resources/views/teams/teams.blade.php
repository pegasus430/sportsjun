@extends(Auth::user() || (isset($is_widget) && $is_widget) ? (Helper::check_if_org_template_enabled()?'layouts.organisation':'layouts.app') : 'home.layout')
@section('content')
    @if (Auth::user() || (isset($is_widget) && $is_widget))
            @include ('teams.orgleftmenu')
    @endif

    <div id="content" class="col-sm-10 tournament_profile">
        <div class="col-md-8 col-md-offset-2 col-xs-12">
            <div class="team_view">
                {!! Helper::makeImageHtml($orgInfoObj->logoImage,array('height'=>100,'width'=>'100%', 'class'=>'no-border-radius') )!!}
                <h1>{{ $orgInfoObj->name }}</h1>
            </div>
            <div class="group_no clearfix">
                <h4 class="stage_head">{{ trans('message.organization.fields.orgdetails') }}</h4>
            </div>
            <table class="table table-striped table-bordered">
                <tbody>
                <tr>
                    <th>{{   trans('message.organization.fields.name') }}</th>
                    <td>{{ $orgInfoObj->name }}</td>
                </tr>

                <tr>
                    <th>{{   trans('message.organization.fields.contactnumber') }}</th>
                    <td>{{ $orgInfoObj->contact_number }}</td>

                </tr>
                @if ($orgInfoObj->alternate_contact_number)
                    <tr>
                        <th>{{   trans('message.organization.fields.altcontactnumber') }}</th>
                        <td>{{ $orgInfoObj->alternate_contact_number }}</td>
                    </tr>
                @endif
                @if ( $orgInfoObj->contact_name)
                    <tr>
                        <th>{{   trans('message.organization.fields.contactpersonname') }}</th>
                        <td> {{ $orgInfoObj->contact_name }}</td>
                    </tr>
                @endif
                <tr>
                    <th>{{   trans('message.organization.fields.email') }}</th>
                    <td>{{ $orgInfoObj->email }}</td>
                </tr>
                <tr>
                    <th>{{   trans('message.organization.fields.organizationtype') }}</th>
                    <td>{{ $orgInfoObj->organization_type }}</td>
                </tr>
                @if ( $orgInfoObj->social_facebook)
                    <tr>
                        <th>{{   trans('message.organization.fields.facebook') }}</th>
                        <td><a href="{{ $orgInfoObj->social_facebook}}">{{ $orgInfoObj->social_facebook}}</a></td>
                    </tr>
                @endif
                @if ( $orgInfoObj->social_twitter)
                    <tr>
                        <th>{{   trans('message.organization.fields.twitter') }}</th>
                        <td><a href="{{ $orgInfoObj->social_twitter}}">{{ $orgInfoObj->social_twitter}}</a></td>
                    </tr>
                @endif
                @if ( $orgInfoObj->social_linkedin)
                    <tr>
                        <th>{{   trans('message.organization.fields.linkedin') }}</th>
                        <td><a href="{{ $orgInfoObj->social_linkedin}}">{{ $orgInfoObj->social_linkedin}}</a></td>
                    </tr>
                @endif
                @if ( $orgInfoObj->social_googleplus)
                    <tr>
                        <th>{{   trans('message.organization.fields.googleplus') }}</th>
                        <td><a href="{{ $orgInfoObj->social_googleplus}}">{{ $orgInfoObj->social_googleplus}}</a></td>
                    </tr>
                @endif
                @if ( $orgInfoObj->website_url)
                    <tr>
                        <th>{{   trans('message.organization.fields.websiteurl') }}</th>
                        <td> <a href="{{$orgInfoObj->website_url}}">{{$orgInfoObj->website_url}}</a></td>
                    </tr>
                @endif
                <tr>
                    <th>{{   trans('message.organization.fields.about') }}</th>
                    <td>{{ $orgInfoObj->about}}</td>
                </tr>
                <tr>
                    <th>{{   trans('message.organization.fields.location') }}</th>
                    <td>{{ $orgInfoObj->location}}</td>

                </tr>

                </tbody>
            </table>
        </div>
    </div>
@endsection
