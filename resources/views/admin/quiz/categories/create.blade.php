@extends('layouts.admin.app')
@section('title', 'Add Quiz Category')
@section('content')
<section class="content-header">
	<div class="content-header-left">
		<h1>Add Quiz Category</h1>
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
					<form action="{{ route('admin.quiz.categories.store') }}" method="POST" enctype="multipart/form-data">
						@csrf
						<div class="form-group">
							<label for="name">Category Name <span class="text-danger">*</span></label>
							<input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Enter category name" required>
							<span class="text-danger">{{ $errors->first('name') }}</span>
						</div>
						
						<div class="form-group">
							<label for="description">Description</label>
							<textarea class="form-control" id="description" name="description" rows="4" placeholder="Enter category description">{{ old('description') }}</textarea>
							<span class="text-danger">{{ $errors->first('description') }}</span>
						</div>
						
						<div class="form-group">
							<label for="is_active">Status</label>
							<div class="form-check">
								<input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}>
								<label class="form-check-label" for="is_active">
									Active
								</label>
							</div>
						</div>
						
						<div class="form-group">
							<button type="submit" class="btn btn-success">Save</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>
@endsection
