@extends('layouts.admin.app')
@section('title', $page_title)
@section('content')
<input type="hidden" id="page_url" value="{{ route('services.index') }}">
<section class="content-header">
	<div class="content-header-left">
		<h1>{{ $page_title }}</h1>
	</div>
	@can('services-create')
	<div class="content-header-right">
		<a href="{{ route('services.create') }}" class="btn btn-primary btn-sm">Add Service</a>
	</div>
	@endcan
</section>

<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box box-info">
				<div class="box-body">
					<div class="row" style="margin-bottom: 10px;">
						<!-- {{-- <div class="col-sm-1">Search:</div> --}} -->
						<div class="d-flex col-sm-8">
							<input type="text" id="search" class="form-control" placeholder="Search by Title">
						</div>
						<div class="d-flex col-sm-4">
							<select name="" id="status" class="form-control status" style="margin-bottom:5px">
								<option value="All" selected>Search by status</option>
								<option value="1">Active</option>
								<option value="2">In-Active</option>
							</select>
						</div>
					</div>
					<div class="card-body table-responsive p-0">
						<table id="" class="table table-hover table-bordered">
							<thead>
								<tr>
									<th>No.</th>
									<th>Image</th>
									<th>Title</th>
									<th>Description</th>
									<th>Status</th>
									<th width="140">Action</th>
								</tr>
							</thead>
							<tbody id="body">
								@forelse($models as $key=>$model)
								<tr id="id-{{ $model->slug }}">
									<td>{{ $models->firstItem()+$key }}.</td>
									<td>
										@if($model->image)
										<img src="{{ asset('admin/assets/images/categories/'.$model->image) }}" alt="{{ $model->title }}" style="width:60px; height:60px; object-fit:cover; border-radius:4px;">
										@else
										<img src="{{ asset('admin/assets/images/default.jpg') }}" alt="No Image" style="width:60px; height:60px; object-fit:cover; border-radius:4px;">
										@endif
									</td>

									<td>{{ \Illuminate\Support\Str::limit($model->title, 40) }}</td>
									<td>
										@if(!empty($model->description))
											{{ \Illuminate\Support\Str::limit(strip_tags($model->description), 50) }}
										@else
											<span class="text-muted">No description</span>
										@endif
									</td>

									<td>
										@if($model->status == '1' || $model->status == 1)
										<span class="label label-success">Active</span>
										@else
										<span class="label label-danger">In-Active</span>
										@endif
									</td>
									<td width="250px">
										@can('services-edit')
										<a href="{{route('services.edit', $model->slug)}}" data-toggle="tooltip" data-placement="top" title="Edit Category" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit</a>
										@endcan
										@can('services-delete')
										<button class="btn btn-danger btn-xs delete" data-slug="{{ $model->slug }}" data-del-url="{{ url('services', $model->slug) }}"><i class="fa fa-trash"></i> Delete</button>
										@endcan
									</td>
								</tr>
								@empty
								<tr>
									<td colspan="6" class="text-center">
										<p class="text-muted">No categories found. <a href="{{ route('services.create') }}">Create your first category</a></p>
									</td>
								</tr>
								@endforelse
								@if($models->count() > 0)
								<tr>
									<td colspan="6">
										Displying {{$models->firstItem()}} to {{$models->lastItem()}} of {{$models->total()}} records
										<div class="d-flex justify-content-center">
											{!! $models->links('pagination::bootstrap-4') !!}
										</div>
									</td>
								</tr>
								@endif
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
@endsection

@push('js')
<script>
	$(document).ready(function() {
		var searchTimeout;
		
		// Function to load categories via AJAX
		function loadCategories(url, pushToHistory = true) {
			$.ajax({
				url: url,
				type: 'GET',
				dataType: 'html',
				beforeSend: function() {
					// Show loading indicator if needed
				},
				success: function(data) {
					var $html = $(data);
					$('#body').html($html.find('tbody').html());
					
					// Update URL in browser without reloading
					if (pushToHistory) {
						history.pushState(null, '', url);
					}
				},
				error: function(xhr, status, error) {
					console.error("AJAX Error:", status, error);
					console.error(xhr.responseText);
					alert('Error loading categories. Please refresh the page.');
				}
			});
		}
	
		// Handle search input with debounce
		$('#search').on('keyup', function() {
			clearTimeout(searchTimeout);
			var search = $(this).val();
			var status = $('#status').val();
			// Use the base URL from the hidden input
			var url = $('#page_url').val();
			// Append search and status parameters
			url += '?search=' + encodeURIComponent(search) + '&status=' + status;
			
			searchTimeout = setTimeout(function() {
				loadCategories(url);
			}, 500); // Wait 500ms after user stops typing
		});
	
		// Handle status dropdown change
		$('#status').on('change', function() {
			var search = $('#search').val();
			var status = $(this).val();
			// Use the base URL from the hidden input
			var url = $('#page_url').val();
			// Append search and status parameters
			url += '?search=' + encodeURIComponent(search) + '&status=' + status;
			loadCategories(url);
		});
	});
</script>
@endpush