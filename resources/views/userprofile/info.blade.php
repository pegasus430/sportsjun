@extends('layouts.app')
@section('content')
@include ('album._leftmenu')

<div class="col_middle">
		<div class="panel-heading">
            {{ trans('message.users.fields.heading') }}
		</div>
		@if($userDetails->id==Auth::user()->id)
			<div class="form-header header-primary"><h4><a class="fa fa-pencil-square" href="{{ route('user.edit',[Auth::user()->id]) }}" style="float:right"></a></h4></div>
		@endif
		<div class="panel-body">
			<div class="table-responsive table-bordered">
				<table class="table">
				<tbody>
						<tr>
							<th>{{ trans('message.users.fields.name') }}</th>
							<td>{{ $userDetails->name }}</td>
						</tr>
					
						<tr>
							<th>{{ trans('message.users.fields.email') }}</th>
							<td>{{ $userDetails->email }}</td>
							
						</tr>
						<tr>
							<th>{{ trans('message.users.fields.gender') }}</th>
							<td>{{  $userDetails->gender }}</td>
							
						</tr>
						<tr>
							<th>{{ trans('message.users.fields.dob') }}</th>
							<td>{{ $userDetails->dob }}</td>
						</tr>
						<tr>
							<th>{{ trans('message.users.fields.address') }}</th>
							<td>{{ $userDetails->address }}</td>
						</tr>
							<tr>
							<th>{{ trans('message.users.fields.location') }}</th>
							<td>{{ $userDetails->location }}</td>
						</tr>
						<tr>
							<th>{{ trans('message.users.fields.about') }}</th>
							<td>{{ $userDetails->about }}</td>
						</tr>
				</tbody>
				</table>
			</div>
                            <!-- /.table-responsive -->
        </div>
</div>
@endsection
