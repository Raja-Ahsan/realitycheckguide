@extends('layouts.admin.app')
@section('title', 'Edit Quiz Category')
@section('content')
<section class="content-header">
	<div class="content-header-left">
		<h1>Edit Quiz Category</h1>
	</div>
	<div class="content-header-right">
		<a href="{{ route('admin.quiz.categories.index') }}" class="btn btn-primary btn-sm">View All</a>
	</div>
</section>

<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box box-info">
				<div class="box-body">
					<form action="{{ route('admin.quiz.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
						@csrf
						@method('PUT')
						<div class="form-group">
							<label for="name">Category Name <span class="text-danger">*</span></label>
							<input type="text" class="form-control" id="name" name="name" value="{{ old('name', $category->name) }}" placeholder="Enter category name" required>
							<span class="text-danger">{{ $errors->first('name') }}</span>
						</div>
						
						<div class="form-group">
							<label for="description">Description</label>
							<textarea class="form-control" id="description" name="description" rows="4" placeholder="Enter category description">{{ old('description', $category->description) }}</textarea>
							<span class="text-danger">{{ $errors->first('description') }}</span>
						</div>
						
						<div class="form-group">
							<label for="is_active">Status</label>
							<div class="form-check">
								<input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
								<label class="form-check-label" for="is_active">
									Active
								</label>
							</div>
						</div>
						
						<div class="form-group">
							<button type="submit" class="btn btn-success">Update</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>
@endsection
