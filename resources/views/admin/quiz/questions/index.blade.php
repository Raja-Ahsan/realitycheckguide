@extends('layouts.admin.app')
@section('title', 'Quiz Questions')
@section('content')
<input type="hidden" id="page_url" value="{{ route('admin.quiz.questions.index') }}">
<section class="content-header">
	<div class="content-header-left">
		<h1>Quiz Questions</h1>
	</div>
	<div class="content-header-right">
		<a href="{{ route('admin.quiz.questions.create') }}" class="btn btn-primary btn-sm">Add Question</a>
	</div>
</section>

<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box box-info">
				<div class="box-body">
					<div class="row" style="margin-bottom: 10px;">
						<div class="d-flex col-sm-4">
							<input type="text" id="search" class="form-control" placeholder="Search by Question">
						</div>
						<div class="d-flex col-sm-3">
							<select name="" id="category_filter" class="form-control" style="margin-bottom:5px">
								<option value="">All Categories</option>
								@foreach($categories as $category)
									<option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
										{{ $category->name }}
									</option>
								@endforeach
							</select>
						</div>
						<div class="d-flex col-sm-3">
							<select name="" id="status" class="form-control status" style="margin-bottom:5px">
								<option value="All" selected>Search by status</option>
								<option value="1">Active</option>
								<option value="0">In-Active</option>
							</select>
						</div>
						<div class="d-flex col-sm-2">
							<button type="button" class="btn btn-warning btn-sm" id="bulk-action-btn" style="display: none;">Bulk Action</button>
						</div>
					</div>
					
					<!-- Bulk Action Form -->
					<form id="bulk-action-form" style="display: none; margin-bottom: 10px;">
						@csrf
						<div class="row">
							<div class="col-sm-3">
								<select name="action" class="form-control" required>
									<option value="">Select Action</option>
									<option value="activate">Activate</option>
									<option value="deactivate">Deactivate</option>
									<option value="delete">Delete</option>
								</select>
							</div>
							<div class="col-sm-3">
								<button type="submit" class="btn btn-primary btn-sm">Apply</button>
								<button type="button" class="btn btn-secondary btn-sm" id="cancel-bulk">Cancel</button>
							</div>
						</div>
					</form>
					
					<div class="card-body table-responsive p-0">
						<table id="" class="table table-hover table-bordered">
							<thead>
								<tr>
									<th width="30">
										<input type="checkbox" id="select-all">
									</th>
									<th>No.</th>
									<th>Category</th>
									<th>Question</th>
									<th>Correct Answer</th>
									<th>Status</th>
									<th width="140">Action</th>
								</tr>
							</thead>
							<tbody id="body">
								@foreach($questions as $key=>$question)
								<tr id="id-{{ $question->id }}">
									<td>
										<input type="checkbox" class="question-checkbox" value="{{ $question->id }}">
									</td>
									<td>{{ $questions->firstItem()+$key }}</td>
									<td>{{ $question->category->name }}</td>
									<td>{{ Str::limit($question->question, 80) }}</td>
									<td>
										<span class="badge badge-success">{{ $question->correct_option }}</span>
									</td>
									<td>
										@if($question->is_active)
											<span class="badge badge-success">Active</span>
										@else
											<span class="badge badge-danger">In-Active</span>
										@endif
									</td>
									<td width="140">
										<a href="{{ route('admin.quiz.questions.edit', $question->id) }}" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit</a>
										<form action="{{ route('admin.quiz.questions.toggle-status', $question->id) }}" method="POST" style="display: inline;">
											@csrf
											<button type="submit" class="btn btn-{{ $question->is_active ? 'warning' : 'success' }} btn-xs">
												<i class="fa fa-{{ $question->is_active ? 'pause' : 'play' }}"></i> 
												{{ $question->is_active ? 'Deactivate' : 'Activate' }}
											</button>
										</form>
										<form action="{{ route('admin.quiz.questions.destroy', $question->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this question?')">
											@csrf
											@method('DELETE')
											<button type="submit" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> Delete</button>
										</form>
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<div class="dataTables_info" id="example2_info" role="status" aria-live="polite">
								Showing {{ $questions->firstItem() }} to {{ $questions->lastItem() }} of {{ $questions->total() }} entries
							</div>
						</div>
						<div class="col-sm-6">
							<div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
								{{ $questions->links() }}
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
    // Search functionality
    $("#search").keyup(function() {
        var value = this.value.toLowerCase().trim();
        $("#body tr").show().filter(function() {
            return $(this).text().toLowerCase().trim().indexOf(value) == -1;
        }).hide();
    });

    // Status filter
    $("#status").change(function() {
        var value = this.value;
        if (value == "All") {
            $("#body tr").show();
        } else {
            $("#body tr").hide();
            $("#body tr").filter(function() {
                return $(this).find("td:nth-child(6)").text().toLowerCase().trim().indexOf(value == "1" ? "active" : "in-active") != -1;
            }).show();
        }
    });

    // Category filter
    $("#category_filter").change(function() {
        var categoryId = this.value;
        if (categoryId == "") {
            $("#body tr").show();
        } else {
            $("#body tr").hide();
            $("#body tr").filter(function() {
                return $(this).find("td:nth-child(3)").text().trim() == $("#category_filter option:selected").text().trim();
            }).show();
        }
    });

    // Select all checkbox
    $("#select-all").change(function() {
        $(".question-checkbox").prop('checked', this.checked);
        toggleBulkActionButton();
    });

    // Individual checkboxes
    $(".question-checkbox").change(function() {
        toggleBulkActionButton();
        if ($(".question-checkbox:checked").length == $(".question-checkbox").length) {
            $("#select-all").prop('checked', true);
        } else {
            $("#select-all").prop('checked', false);
        }
    });

    function toggleBulkActionButton() {
        if ($(".question-checkbox:checked").length > 0) {
            $("#bulk-action-btn").show();
        } else {
            $("#bulk-action-btn").hide();
            $("#bulk-action-form").hide();
        }
    }

    // Show bulk action form
    $("#bulk-action-btn").click(function() {
        $("#bulk-action-form").show();
    });

    // Cancel bulk action
    $("#cancel-bulk").click(function() {
        $("#bulk-action-form").hide();
    });

    // Bulk action form submission
    $("#bulk-action-form").submit(function(e) {
        e.preventDefault();
        
        var selectedIds = [];
        $(".question-checkbox:checked").each(function() {
            selectedIds.push($(this).val());
        });

        if (selectedIds.length === 0) {
            alert('Please select at least one question.');
            return;
        }

        var action = $("select[name='action']").val();
        if (!action) {
            alert('Please select an action.');
            return;
        }

        if (action === 'delete' && !confirm('Are you sure you want to delete the selected questions?')) {
            return;
        }

        // Add selected IDs to form
        selectedIds.forEach(function(id) {
            $(this).append('<input type="hidden" name="question_ids[]" value="' + id + '">');
        }.bind(this));

        this.submit();
    });
});
</script>
@endpush
