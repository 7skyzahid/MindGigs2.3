<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

@extends('layouts.app')

<script type="text/javascript">
function myFunction() {
alert("I am an alert box!");
}

$(document).ready(function(){


	var MaxInputsacc       = 8; //maximum input boxes allowed
	var AccousedWrapper   = $("#AccousedWrapper"); //Input boxes wrapper ID
	var AddButtonacc       = $("#AddAccoused"); //Add button ID

	var x = AccousedWrapper.length; //initlal text box count
	var FieldCountacc=1; //to keep track of text box added

	$(AddButtonacc).click(function (e)  //on add input button click
	{
		//alert("zzzzzz");
		if(x <= MaxInputsacc) //max input box allowed
		{
			FieldCountacc++; //text box added increment

			//add input box
			$(AccousedWrapper).append('<div class="form-group row"></div><label for="blogbody" class="col-xs-offset-1 col-xs-2 col-form-label"></label><div id="za" class="col-xs-7"><input type="text"  size="20" name="skill[]" value="" placeholder="Name" /><input type="hidden" name="x[]" value="'+x+'"><a href="#" class="removeclassza">     Remove</a></div><div class="form-group row"></div>');
			x++; //text box increment
		}
		return false;
	});

	$("body").on("click",".removeclassza", function(e){ //user click on remove text
		if( x > 1 ) {
			$(this).parent('#za').remove(); //remove text box
			x--; //decrement textbox
		}
		return false;
	})

});
</script>
@section('content')
<div class="container">
	<div class="row">
	<br><br>
		<div class="col-md-6 col-md-offset-3">
			<div class="panel panel-default">
				<div class="panel-heading">Write A New Blog</div>

				<div class="panel-body">

					<form method="POST" action="{{url('skill')}}" id="fb">
						{!! csrf_field() !!}


							<div class="form-group row">
								<label for="blogtitle" class="col-xs-offset-1 col-xs-2 col-form-label">Name</label>

								<a id="AddAccoused" class="btn btn-info" href="#">Add Skill</a>
								<div id="AccousedWrapper">

								</div>


							</div>



						</div>

						<div class="form-group row">
							<label for="blogbody" class="col-xs-offset-1 col-xs-2 col-form-label">Body</label>
							<div class="col-xs-7">
								<textarea required class="form-control" name="category" rows="10" type="text" id="blogbody"></textarea>
							</div>
						</div>

						<div class="form-group row">
								<button type="submit" class="col-xs-offset-5 btn btn-primary">Publish</button>
								<button type="button" class="btn btn-primary" onclick="window.location.href='../blogs'">Cancel</button>
						</div>


					</form>

				</div>
			</div>
		</div>
	</div>
</div>

@stop