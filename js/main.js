jQuery(document).on('submit','#form-log',function(event)
{
	event.preventDefault();

	jQuery.ajax({
		url:'login.php',
		type: 'POST',
		dataType: 'json',
		data: $(this).serialize(),
		beforeSend: function(){

		}
	});

	.done(function(respuesta){
		console.log(respuesta);
	});

	.fail(function (resp) {
		console.log(resp.responseText);
	});

	.always(function(){
		console.log("complete");
	});

});