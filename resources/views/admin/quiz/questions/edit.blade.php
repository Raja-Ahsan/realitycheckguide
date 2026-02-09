@extends('layouts.admin.app')
@section('title', 'Edit Quiz Question')
@section('content')
<section class="content-header">
	<div class="content-header-left">
		<h1>Edit Quiz Question</h1>
	</div>
	<div class="content-header-right">
		<a href="{{ route('admin.quiz.questions.index') }}" class="btn btn-primary btn-sm">View All</a>
	</div>
</section>

<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box box-info">
				<div class="box-body">
					<form action="{{ route('admin.quiz.questions.update', $question->id) }}" method="POST" enctype="multipart/form-data">
						@csrf
						@method('PUT')
						<div class="form-group">
							<label for="category_id">Category <span class="text-danger">*</span></label>
							<select class="form-control" id="category_id" name="category_id" required>
								<option value="">Select Category</option>
								@foreach($categories as $category)
									<option value="{{ $category->id }}" {{ old('category_id', $question->category_id) == $category->id ? 'selected' : '' }}>
										{{ $category->name }}
									</option>
								@endforeach
							</select>
							<span class="text-danger">{{ $errors->first('category_id') }}</span>
						</div>
						
						<div class="form-group">
							<label for="question">Question <span class="text-danger">*</span></label>
							<textarea class="form-control" id="question" name="question" rows="3" placeholder="Enter the question" required>{{ old('question', $question->question) }}</textarea>
							<span class="text-danger">{{ $errors->first('question') }}</span>
						</div>
						
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="option_a">Option A <span class="text-danger">*</span></label>
									<input type="text" class="form-control" id="option_a" name="option_a" value="{{ old('option_a', $question->option_a) }}" placeholder="Enter option A" required>
									<span class="text-danger">{{ $errors->first('option_a') }}</span>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="option_b">Option B <span class="text-danger">*</span></label>
									<input type="text" class="form-control" id="option_b" name="option_b" value="{{ old('option_b', $question->option_b) }}" placeholder="Enter option B" required>
									<span class="text-danger">{{ $errors->first('option_b') }}</span>
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="option_c">Option C <span class="text-danger">*</span></label>
									<input type="text" class="form-control" id="option_c" name="option_c" value="{{ old('option_c', $question->option_c) }}" placeholder="Enter option C" required>
									<span class="text-danger">{{ $errors->first('option_c') }}</span>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="option_d">Option D <span class="text-danger">*</span></label>
									<input type="text" class="form-control" id="option_d" name="option_d" value="{{ old('option_d', $question->option_d) }}" placeholder="Enter option D" required>
									<span class="text-danger">{{ $errors->first('option_d') }}</span>
								</div>
							</div>
						</div>
						
						<div class="form-group">
							<label for="correct_option">Correct Answer <span class="text-danger">*</span></label>
							<select class="form-control" id="correct_option" name="correct_option" required>
								<option value="">Select Correct Answer</option>
								<option value="A" {{ old('correct_option', $question->correct_option) == 'A' ? 'selected' : '' }}>A</option>
								<option value="B" {{ old('correct_option', $question->correct_option) == 'B' ? 'selected' : '' }}>B</option>
								<option value="C" {{ old('correct_option', $question->correct_option) == 'C' ? 'selected' : '' }}>C</option>
								<option value="D" {{ old('correct_option', $question->correct_option) == 'D' ? 'selected' : '' }}>D</option>
							</select>
							<span class="text-danger">{{ $errors->first('correct_option') }}</span>
						</div>
						
						<div class="form-group">
							<label for="is_active">Status</label>
							<div class="form-check">
								<input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', $question->is_active) ? 'checked' : '' }}>
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
