<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Todo</title>
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<!-- Latest compiled and minified CSS & JS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
	<script src="//code.jquery.com/jquery.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
</head>
<body>
	<div class="container">
		<a class="btn btn-success" data-toggle="modal" href="#add-new">Add</a>
		<div class="table-responsive">
			<table class="table table-hover">
				<thead>
					<tr>
						<th>#</th>
						<th>Todo</th>
						<th>Created at</th>
						<th>Updated at</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					
					{{-- biến $todos được controller trả cho view
					chứa dữ liệu tất cả các bản ghi trong bảng todos. Dùng foreach để hiển
					thị từng bản ghi ra table này. --}}
					
					@foreach ($todos as $todo)
					<tr id="tr-{{$todo->id}}">
						<td>{{$todo->id}}</td>
						<td>{{$todo->todo}}</td>
						<td>{{$todo->created_at}}</td>
						<td>{{$todo->updated_at}}</td>
						<td>
							<button style="display: inline-block; width: 67px;" class="btn btn-warning" data-toggle="modal" href="#edit" data-id="{{$todo->id}}" >Edit</button>
							<button style="display: inline-block; width: 67px;" class="btn btn-info" data-toggle="modal" href="#show" data-id="{{$todo->id}}">Show</button>
							<button data-id="{{$todo->id}}" type="submit" class="btn btn-danger">Delete</button>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
	<div class="modal fade" id="add-new">
		<div class="modal-dialog">
			<form id="add" method="POST">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title">Modal title</h4>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<input type="text" class="form-control" name="todo" placeholder="Todo" id="todo">
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Save changes</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	<div class="modal fade" id="show">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Modal title</h4>
				</div>
				<div class="modal-body">
					<h1></h1>
					<h2></h2>
					<h3></h3>
					<h5></h5>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="edit">
		<div class="modal-dialog">
			<form id="update" method="POST">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title">Modal title</h4>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<input type="hidden" id="todo-id">
							<input type="text" class="form-control" name="todo" placeholder="Todo" id="todo-edit">
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Save changes</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</body>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
	<script type="text/javascript">
		

		$(function(){
			$.ajaxSetup({
			    headers: {
			        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			    }
			});
			
			$(document).on('click', '.btn-danger',function(){
				var btn = $(this);

				var id = btn.data('id');
				swal({
					title: "Are you sure?",
					icon: "warning",
					buttons: true,
					dangerMode: true,
				})
				.then((willDelete) => {
					if (willDelete) {
						$.ajax({
							type: 'delete',
							url: '/todos-ajax/'+id,
							success: function(response){
								btn.parents('tr').remove()
								toastr.warning(response.message)
							}
						})
					}
				});
			})

			$('#add').submit(function(e){
				e.preventDefault()
				$.ajax({
					type: 'post',
					url: '/todos-ajax',
					data: {
						todo: $('#todo').val(),
					},
					success: function(response){
						toastr.success('Success')
						$('#add-new').modal('hide')
						$('#todo').val("")

						var temp = `<tr id="tr-`+response.id+`">
										<td>`+response.id+`</td>
										<td>`+response.todo+`</td>
										<td>`+response.created_at+`</td>
										<td>`+response.updated_at+`</td>
										<td>
											<button style="display: inline-block; width: 67px;" class="btn btn-warning" data-toggle="modal" href="#edit" data-id="`+response.id+`" >Edit</button>
											<button style="display: inline-block; width: 67px;" class="btn btn-info" data-toggle="modal" href="#show" data-id="`+response.id+`">Show</button>
											<button data-id="`+response.id+`" type="submit" class="btn btn-danger">Delete</button>
										</td>
									</tr>`

						$('tbody').prepend(temp)

					}
				})

			})

			$(document).on('click','.btn-info',function(){
				var btn =$(this)
				var id = btn.data('id')

				$.ajax({
					type: 'get',
					url: '/todos-ajax/'+id,
					success: function(response){
						$('h1').text(response.id)
						$('h2').text(response.todo)
						$('h3').text(response.created_at)
						$('h5').text(response.updated_at)
					}
				})
			})

			$(document).on('click','.btn-warning',function(){
				var btn = $(this)
				var id = btn.data('id')

				$.ajax({
					type: 'get',
					url: '/todos-ajax/'+id,
					success: function(response){
						$('#todo-edit').val(response.todo)
						$('#todo-id').val(response.id)
					}
				})
			})

			$('#update').submit(function(e){
				e.preventDefault();
				$.ajax({
					type: 'patch',
					url: '/todos-ajax/'+$('#todo-id').val(),
					data:{
						todo: $('#todo-edit').val()
					},
					success: function(response){
						console.log(response)
						var temp = `
										<td>`+response.id+`</td>
										<td>`+response.todo+`</td>
										<td>`+response.created_at+`</td>
										<td>`+response.updated_at+`</td>
										<td>
											<button style="display: inline-block; width: 67px;" class="btn btn-warning" data-toggle="modal" href="#edit" data-id="`+response.id+`" >Edit</button>
											<button style="display: inline-block; width: 67px;" class="btn btn-info" data-toggle="modal" href="#show" data-id="`+response.id+`">Show</button>

											<button data-id="`+response.id+`" type="submit" class="btn btn-danger">Delete</button>
										</td>
									`
						$('#tr-'+$('#todo-id').val()).html(temp);
						toastr.success('Success')
						$('#edit').modal('hide')
					}
				})
			})

		})
	</script>
</html>