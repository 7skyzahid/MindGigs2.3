@extends('layouts.app')

@section('content')
<br><br>
<div class="container">
	<div class="row">
        <div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">Scoreboard</div>

				<div class="panel-body">

					
						<div class="form-group row">
							<label for="proftitle" class="col-xs-offset-1 col-xs-4 col-form-label">Signed Up Users</label>
							<div class="col-xs-7">
								<table class="table table-responsive table-hover"> <tr><td>{{$sb}}</td></tr> </table>
							</div>
						</div>

						<div class="form-group row">
							<label for="proftitle" class="col-xs-offset-1 col-xs-4 col-form-label">Users from Countries</label>
							<div class="col-xs-7">
								<table class="table table-responsive table-hover">
									<tr>
										<th> Country </th>
										<th> Members </th>
									<tr>
									@foreach ($arr as $ar)
									<tr>
										@if ($ar->country == "")
											<td>unknown</td> <td>{{$ar->peoplecount}}</td>
										@else
	    									<td>{{$ar->country}}</td>  <td>{{$ar->peoplecount}}</td>
	    								@endif
	    							<tr>
									@endforeach
									</tr>
								</table>
							</div>
						</div>

				</div>
			</div>
		</div>
	</div>
</div>

@stop