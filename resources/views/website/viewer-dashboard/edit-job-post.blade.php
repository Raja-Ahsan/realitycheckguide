@extends('layouts.user.app')
@section('title', 'Edit Job Post')
@section('content')
<section class="content-header">
    <h1 style="color:#c98900 !important; font-weight: 700;">Edit Job Post</h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Edit Job Post</h3>
                </div>
                <form method="POST" action="{{ route('user.update-job-post', $jobPost->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Job Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $jobPost->name) }}" required>
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="job_category_id">Job Category <span class="text-danger">*</span></label>
                                    <select class="form-control" id="job_category_id" name="job_category_id" required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('job_category_id', $jobPost->job_category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('job_category_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="city_id">City <span class="text-danger">*</span></label>
                                    <select class="form-control" id="city_id" name="city_id" required>
                                        <option value="">Select City</option>
                                        @foreach($cities as $city)
                                            <option value="{{ $city->id }}" {{ old('city_id', $jobPost->city_id) == $city->id ? 'selected' : '' }}>
                                                {{ $city->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('city_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="state_id">State <span class="text-danger">*</span></label>
                                    <select class="form-control" id="state_id" name="state_id" required>
                                        <option value="">Select State</option>
                                        @foreach($states as $state)
                                            <option value="{{ $state->id }}" {{ old('state_id', $jobPost->state_id) == $state->id ? 'selected' : '' }}>
                                                {{ $state->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('state_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="budget_min">Minimum Budget ($) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" min="0" class="form-control" id="budget_min" name="budget_min" value="{{ old('budget_min', $jobPost->budget_min) }}" required>
                                    @error('budget_min')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="budget_max">Maximum Budget ($) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" min="0" class="form-control" id="budget_max" name="budget_max" value="{{ old('budget_max', $jobPost->budget_max) }}" required>
                                    @error('budget_max')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="deadline">Deadline <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="deadline" name="deadline" value="{{ old('deadline', $jobPost->deadline ? $jobPost->deadline->format('Y-m-d') : '') }}" required>
                            @error('deadline')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">Job Description <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="description" name="description" rows="6" placeholder="Describe the job requirements, scope of work, and any specific details..." required>{{ old('description', $jobPost->description) }}</textarea>
                            @error('description')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Update Job Post
                        </button>
                        <a href="{{ route('user.my-job-posts') }}" class="btn btn-default">
                            <i class="fa fa-arrow-left"></i> Back to Job Posts
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@push('js')
<script>
$(document).ready(function() {
    // Auto-populate states when city is selected
    $('#city_id').on('change', function() {
        var cityId = $(this).val();
        if (cityId) {
            $.ajax({
                url: '{{ route("get_states") }}',
                type: 'GET',
                data: { city_id: cityId },
                success: function(data) {
                    $('#state_id').empty();
                    $('#state_id').append('<option value="">Select State</option>');
                    $.each(data, function(key, value) {
                        $('#state_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                }
            });
        } else {
            $('#state_id').empty();
            $('#state_id').append('<option value="">Select State</option>');
        }
    });
});
</script>
@endpush 