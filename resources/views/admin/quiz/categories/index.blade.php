@extends('layouts.admin.app')
@section('title', 'Quiz Categories')
@section('content')
<input type="hidden" id="page_url" value="{{ route('admin.quiz.categories.index') }}">
<section class="content-header">
	<div class="content-header-left">
		<h1>Quiz Categories</h1>
	</div>
	<div class="content-header-right">
		<a href="{{ route('admin.quiz.categories.create') }}" class="btn btn-primary btn-sm">Add Category</a>
	</div>
</section>

<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box box-info">
				<div class="box-body">
					<div class="row" style="margin-bottom: 10px;">
						<div class="d-flex col-sm-8">
							<input type="text" id="search" class="form-control" placeholder="Search by Name">
						</div>
						<div class="d-flex col-sm-4">
							<select name="" id="status" class="form-control status" style="margin-bottom:5px">
								<option value="All" selected>Search by status</option>
								<option value="1">Active</option>
								<option value="0">In-Active</option>
							</select>
						</div>
					</div>
					<div class="card-body table-responsive p-0">
						<table id="" class="table table-hover table-bordered">
							<thead>
								<tr>
									<th>No.</th>
									<th>Name</th>
									<th>Description</th>
									<th>Questions</th>
									<th>Status</th>
									<th width="140">Action</th>
								</tr>
							</thead>
							<tbody id="body">
								@foreach($categories as $key=>$category)
								<tr id="id-{{ $category->id }}">
									<td>{{ $categories->firstItem()+$key }}</td>
									<td>{{ $category->name }}</td>
									<td>{{ Str::limit($category->description, 50) }}</td>
									<td>
										<span class="badge badge-info">{{ $category->active_questions_count }}</span> Active
										<span class="badge badge-secondary">{{ $category->total_questions }}</span> Total
									</td>
									<td>
										@if($category->is_active)
											<span class="badge badge-success">Active</span>
										@else
											<span class="badge badge-danger">In-Active</span>
										@endif
									</td>
									<td width="140">
										<a href="{{ route('admin.quiz.categories.edit', $category->id) }}" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit</a>
										<form action="{{ route('admin.quiz.categories.toggle-status', $category->id) }}" method="POST" style="display: inline;">
											@csrf
											<button type="submit" class="btn btn-{{ $category->is_active ? 'warning' : 'success' }} btn-xs">
												<i class="fa fa-{{ $category->is_active ? 'pause' : 'play' }}"></i> 
												{{ $category->is_active ? 'Deactivate' : 'Activate' }}
											</button>
										</form>
										@if($category->questions_count == 0)
										<form action="{{ route('admin.quiz.categories.destroy', $category->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this category?')">
											@csrf
											@method('DELETE')
											<button type="submit" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> Delete</button>
										</form>
										@endif
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<div class="dataTables_info" id="example2_info" role="status" aria-live="polite">
								Showing {{ $categories->firstItem() }} to {{ $categories->lastItem() }} of {{ $categories->total() }} entries
							</div>
						</div>
						<div class="col-sm-6">
							<div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
								{{ $categories->links() }}
							</div>
						</div>
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
    $("#search").keyup(function() {
        var value = this.value.toLowerCase().trim();
        $("#body tr").show().filter(function() {
            return $(this).text().toLowerCase().trim().indexOf(value) == -1;
        }).hide();
    });

    $("#status").change(function() {
        var value = this.value;
        if (value == "All") {
            $("#body tr").show();
        } else {
            $("#body tr").hide();
            $("#body tr").filter(function() {
                return $(this).find("td:nth-child(5)").text().toLowerCase().trim().indexOf(value == "1" ? "active" : "in-active") != -1;
            }).show();
        }
    });
});
</script>
@endpush
