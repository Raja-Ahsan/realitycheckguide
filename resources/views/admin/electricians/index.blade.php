@extends((Auth::user()->hasRole('Admin') ? 'layouts.admin.app' :  (Auth::user()->hasRole('Electrician') ? 'layouts.electrician.app' : 'layouts.user.app')))
@section('title', $page_title)
@section('content')
<style>
	.link-url {
		color: black;
		text-decoration: none;
	}
	
	.link-url:hover {
		text-decoration: underline;
		color: blue;
	}
</style>
<input type="hidden" id="page_url" value="{{ route('member_directory.index') }}">
<section class="content-header">
	<div class="content-header-left">
		<h1>{{ $page_title }}</h1>
	</div>
	@can('member_directory-create')
	<div class="content-header-right">
		<a href="{{ route('member_directory.create') }}" class="btn btn-primary btn-sm">Add Member</a>
	</div>
	@endcan
</section>

<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box box-info">
				<div class="box-body">
					<div class="row" style="margin-bottom: 10px;"> 
						<div class="d-flex col-sm-8">
							<input type="text" id="search" class="form-control" placeholder="Search by Business Name">
						</div>
						<div class="d-flex col-sm-4">
							<select name="" id="status" class="form-control status" style="margin-bottom:5px">
								<option value="All" selected>Search by status</option>
								<option value="pending">Pending</option>
								<option value="approved">Approved</option>
								<option value="rejected">Rejected</option>
							</select>
						</div>
					</div>
					<div class="card-body table-responsive p-0">
						<table id="" class="table table-hover table-bordered">
							<thead>
								<tr>
									<th>No.</th>
									<th>Company Logo</th>
									<th>Business Name</th>
									<th>Membership Category</th>
									{{-- <th>Address</th> --}}
									<th>Phone No</th>
									<th>Website URL</th>
									<th>Description</th>
									<th>Status</th>
									@if(Auth::user()->hasRole('Admin'))
									<th>Created by</th>
									@endif
									<th width="250px">Action</th>
								</tr>
							</thead>
							<tbody id="body">
								@foreach($models as $key=>$model)
								<tr id="id-{{ $model->id }}">
									<td>{{ $models->firstItem()+$key }}.</td>
									<td>
										@if($model->image)
										<img src="{{ asset('public/admin/assets/images/member_directory/'.$model->image) }}" alt="" style="width:60px;">
										@else
										<img src="{{ asset('public/admin/assets/images/default.jpg') }}" style="width:60px;">
										@endif
									</td>
									<td>{{\Illuminate\Support\Str::limit($model->title,40)}}</td>
									<td>
										@if (!empty($model->category_titles))
											<ul class="category-list">
												@foreach ($model->category_titles as $title)
													<li>{{ $title }}</li>
												@endforeach
											</ul>
										@else
											N/A
										@endif
									</td>
									{{-- <td>{{\Illuminate\Support\Str::limit($model->address,40)}}</td>  --}}
									<td>
										<a href="tel:{{ $model->phone_no }}">{{ \Illuminate\Support\Str::limit($model->phone_no, 40) }}</a>
									</td>
									<td>
										@if($model->url)
											<a href="{{ $model->url }}" target="_blank" class="link-url">
												{{ \Illuminate\Support\Str::limit($model->url, 40) }}
											</a><br>
										@endif
										@if($model->email)
											<a href="mailto:{{ $model->email }}" class="link-url">
												{{ \Illuminate\Support\Str::limit($model->email, 40) }}
											</a><br>
										@endif
										@if($model->facebook)
											<a href="{{ $model->facebook }}" target="_blank" class="link-url">
												{{ \Illuminate\Support\Str::limit($model->facebook, 40) }}
											</a>
										@endif
									</td>
									<td>{!!\Illuminate\Support\Str::limit($model->description,40)!!}</td>
									<td>
										@if($model->status == 'pending')
										<span class="label label-warning">Pending</span>
										@elseif($model->status == 'approved')
										<span class="label label-success">Approved</span>
										@elseif($model->status == 'rejected')
										<span class="label label-danger">Rejected</span>
										@endif
									</td>
									@if(Auth::user()->hasRole('Admin'))
									<td>{{isset($model->hasCreatedBy)?$model->hasCreatedBy->name:'N/A'}}</td>
									@endif
									<td width="250px">
										<a href="{{ route('member_directory.show', $model->id) }}" class="btn btn-info btn-xs view-project-link"><i class="fa fa-eye"></i> View</a>
										@can('member_directory-edit')
										<a href="{{route('member_directory.edit', $model->id)}}" data-toggle="tooltip" data-placement="top" title="Edit Service" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit</a>
										@endcan
										@can('member_directory-delete')
										<button class="btn btn-danger btn-xs delete" data-slug="{{ $model->id }}" data-del-url="{{ url('member_directory', $model->id) }}"><i class="fa fa-trash"></i> Delete</button>
										@endcan
									</td>
								</tr>
								@endforeach
								<tr>
                                    <td colspan="13">
                                        Displaying {{ $models->firstItem() }} to {{ $models->lastItem() }} of
                                        {{ $models->total() }} records
                                        <div class="d-flex justify-content-center" id="pagination-links" style="margin-top: 20px;">
                                            {!! $models->links('pagination::bootstrap-4') !!}
                                        </div>
                                    </td>
                                </tr>
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
		// Function to load projects via AJAX
		function loadProjects(url, pushToHistory = true) {
			$.ajax({
				url: url,
				type: 'GET',
				dataType: 'html',
				success: function(data) {
					console.log('AJAX success function reached.');
					var $html = $(data);
					$('#body').html($html.find('#body').html());
					$('#pagination-links').html($html.find('#pagination-links').html());
	
					// Update URL in browser without reloading
					if (pushToHistory) {
						console.log('Calling history.pushState with URL:', url);
						history.pushState(null, '', url);
						console.log('history.pushState called. Current URL:', window.location.href);
					}
				},
				error: function(xhr, status, error) {
					console.error("AJAX Error:", status, error);
					console.error(xhr.responseText);
				}
			});
		}
	
		// Handle pagination link clicks - Use e.preventDefault() for AJAX
		$(document).on('click', '#pagination-links a', function(e) {
			e.preventDefault(); // Prevent default link behavior
			var newUrl = $(this).attr('href');
			if (newUrl) {
				loadProjects(newUrl);
			}
		});
	
		// Handle browser back/forward buttons after a direct visit or initial load
		$(window).on('popstate', function() {
			var currentPageUrl = window.location.href;
			console.log('Popstate triggered. Loading URL:', currentPageUrl);
			// Ensure we only load if we are on the member_directory index page
			if (currentPageUrl.includes('member_directory')) {
				loadProjects(currentPageUrl, false); // Don't push again to history
			}
		});
	
		// Handle search input
		$('#search').on('keyup', function() {
			var search = $(this).val();
			var status = $('#status').val();
			// Use the base URL from the hidden input
			var url = $('#page_url').val();
			// Append search and status parameters
			url += '?search=' + search + '&status=' + status;
			loadProjects(url);
		});
	
		// Handle status dropdown change
		$('#status').on('change', function() {
			var status = $(this).val();
			var search = $('#search').val();
			// Use the base URL from the hidden input
			var url = $('#page_url').val();
			// Append search and status parameters
			url += '?search=' + search + '&status=' + status;
			loadProjects(url);
		});
	
		// Handle clicks on View links
		$(document).on('click', '.view-project-link', function(e) {
			e.preventDefault(); // Prevent default link navigation
	
			var baseUrl = $(this).attr('href');
			var currentQueryString = window.location.search; // Get the current query string (e.g., ?page=2)
	
			// Combine the base URL and the current query string
			var newUrl = baseUrl + currentQueryString;
	
			// Navigate to the new URL
			window.location.href = newUrl;
		});
	
	});
	</script>
@endpush

@push('css')
<style>
.category-list {
    list-style: circle;
    margin: 0;
    padding-left: 20px;
}
.category-list li {
    margin-bottom: 3px;
}
</style>
@endpush