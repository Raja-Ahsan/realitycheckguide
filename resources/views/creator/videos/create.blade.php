@extends('layouts.creator.app')

@section('title', 'Upload New Video')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Upload New Video</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('creator.dashboard') }}">Creator Dashboard</a></li>
                        <li class="breadcrumb-item active">Upload Video</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-upload"></i> Video Information
                            </h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('creator.videos.store') }}" method="POST" enctype="multipart/form-data" id="video-upload-form">
                                @csrf
                                
                                <!-- Upload Progress Bar -->
                                <div id="upload-progress-container" style="display: none;" class="mb-3">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6 class="card-title">
                                                <i class="fas fa-upload"></i> Uploading Video...
                                            </h6>
                                            <div class="progress" style="height: 25px;">
                                                <div id="upload-progress-bar" class="progress-bar progress-bar-striped progress-bar-animated" 
                                                     role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                                    <span id="upload-progress-text">0%</span>
                                                </div>
                                            </div>
                                            <small id="upload-status-text" class="text-muted mt-2 d-block">Preparing upload...</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="title">Video Title *</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title') }}" required>
                                    @error('title')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="4">{{ old('description') }}</textarea>
                                    @error('description')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="category_id">Category</label>
                                            <select class="form-control @error('category_id') is-invalid @enderror" 
                                                    id="category_id" name="category_id">
                                                <option value="">Select Category</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" 
                                                            {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                        {{ $category->title }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('category_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="tags">Tags</label>
                                            <input type="text" class="form-control @error('tags') is-invalid @enderror" 
                                                   id="tags" name="tags" value="{{ old('tags') }}" 
                                                   placeholder="tag1, tag2, tag3">
                                            <small class="form-text text-muted">Separate tags with commas</small>
                                            @error('tags')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="video_file">Video File *</label>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input @error('video_file') is-invalid @enderror" 
                                                       id="video_file" name="video_file" accept="video/*" required>
                                                <label class="custom-file-label" for="video_file">Choose video file</label>
                                            </div>
                                            <small class="form-text text-muted">
                                                Supported formats: MP4, AVI, MOV, WMV, FLV, WebM (Max: 200MB)
                                            </small>
                                            @error('video_file')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="thumbnail">Thumbnail Image</label>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input @error('thumbnail') is-invalid @enderror" 
                                                       id="thumbnail" name="thumbnail" accept="image/*">
                                                <label class="custom-file-label" for="thumbnail">Choose thumbnail</label>
                                            </div>
                                            <small class="form-text text-muted">
                                                Supported formats: JPEG, PNG, JPG, GIF (Max: 2MB)
                                            </small>
                                            @error('thumbnail')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="price">Price *</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">$</span>
                                                </div>
                                                <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                                       id="price" name="price" value="{{ old('price', '0.00') }}" 
                                                       step="0.01" min="0" max="999.99" required>
                                            </div>
                                            @error('price')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="downloads_enabled">Downloads</label>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" 
                                                       id="downloads_enabled" name="downloads_enabled" value="1" 
                                                       {{ old('downloads_enabled', true) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="downloads_enabled">
                                                    Allow users to download this video
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" 
                                               id="is_intro" name="is_intro" value="1" 
                                               {{ old('is_intro') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_intro">
                                            This is my free introduction video (1-minute max)
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">
                                        Only one intro video allowed per creator. Intro videos must be free.
                                    </small>
                                    @error('is_intro')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-upload"></i> Upload Video
                                    </button>
                                    <a href="{{ route('creator.dashboard') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Cancel
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Q&A Section -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-question-circle"></i> Questions & Answers (Optional)
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-sm btn-primary" id="add-question-btn">
                                    <i class="fas fa-plus"></i> Add Question
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                <strong>Q&A Guidelines:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>Add up to 10 questions to assess viewer learning</li>
                                    <li>Each question must have exactly 4 answer options</li>
                                    <li>Select one correct answer for each question</li>
                                    <li>Questions help track viewer progress and engagement</li>
                                </ul>
                            </div>
                            
                            <div id="questions-container">
                                <!-- Questions will be added dynamically here -->
                            </div>
                            
                            <div class="text-center mt-3" id="no-questions-message">
                                <p class="text-muted">No questions added yet. Click "Add Question" to get started.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <!-- Pricing Rules Info -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-info-circle"></i> Pricing Rules
                            </h3>
                        </div>
                        <div class="card-body">
                            @if($pricingRules)
                                <div class="alert alert-info">
                                    <h6><i class="fas fa-lightbulb"></i> Current Pricing Limits</h6>
                                    <p class="mb-1"><strong>Min Price:</strong> ${{ $pricingRules->min_price_floor }}</p>
                                    <p class="mb-1"><strong>Max Price:</strong> ${{ $pricingRules->max_price_cap }}</p>
                                </div>

                                @if(!$pricingRules->custom_pricing_enabled)
                                    <div class="alert alert-warning">
                                        <h6><i class="fas fa-lock"></i> Custom Pricing Locked</h6>
                                        <p class="mb-1">You need to sell {{ $pricingRules->videos_sold_threshold }} videos to unlock custom pricing.</p>
                                        <p class="mb-0">Currently sold: {{ auth()->user()->getTotalVideosSoldAttribute() }}</p>
                                    </div>
                                @else
                                    <div class="alert alert-success">
                                        <h6><i class="fas fa-unlock"></i> Custom Pricing Unlocked!</h6>
                                        <p class="mb-0">You can now set custom prices within your limits.</p>
                                    </div>
                                @endif
                            @else
                                <div class="alert alert-warning">
                                    <h6><i class="fas fa-exclamation-triangle"></i> No Pricing Rules</h6>
                                    <p class="mb-0">Contact admin to set up your pricing rules.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Upload Guidelines -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-book"></i> Upload Guidelines
                            </h3>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success"></i> Video max size: 200MB</li>
                                <li><i class="fas fa-check text-success"></i> Supported formats: MP4, AVI, MOV, WMV, FLV, WebM</li>
                                <li><i class="fas fa-check text-success"></i> Thumbnail max size: 2MB</li>
                                <li><i class="fas fa-check text-success"></i> Intro videos must be free</li>
                                <li><i class="fas fa-check text-success"></i> Only one intro video per creator</li>
                                <li><i class="fas fa-check text-success"></i> Price must be within your limits</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let questionCount = 0;
    const maxQuestions = 10;

    // File input labels
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').html(fileName);
    });

    // Intro video checkbox logic
    $('#is_intro').on('change', function() {
        if ($(this).is(':checked')) {
            $('#price').val('0.00').prop('readonly', true);
            $('.custom-file-label[for="video_file"]').html('Choose intro video (1-min max)');
        } else {
            $('#price').prop('readonly', false);
            $('.custom-file-label[for="video_file"]').html('Choose video file');
        }
    });

    // Price validation
    $('#price').on('input', function() {
        let price = parseFloat($(this).val());
        let minPrice = parseFloat('{{ $pricingRules ? $pricingRules->min_price_floor : "0.99" }}');
        let maxPrice = parseFloat('{{ $pricingRules ? $pricingRules->max_price_cap : "19.99" }}');
        
        if (price < minPrice || price > maxPrice) {
            $(this).addClass('is-invalid');
            $(this).next('.invalid-feedback').remove();
            $(this).after('<span class="invalid-feedback">Price must be between $' + minPrice + ' and $' + maxPrice + '</span>');
        } else {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').remove();
        }
    });

    // Q&A functionality
    $('#add-question-btn').on('click', function() {
        if (questionCount >= maxQuestions) {
            alert('Maximum ' + maxQuestions + ' questions allowed.');
            return;
        }
        addQuestion();
    });

    function addQuestion() {
        questionCount++;
        const questionHtml = `
            <div class="question-item card mb-3" data-question-index="${questionCount}">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Question ${questionCount}</h6>
                    <button type="button" class="btn btn-sm btn-danger remove-question-btn">
                        <i class="fas fa-trash"></i> Remove
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Question Text *</label>
                        <textarea class="form-control question-text" name="questions[${questionCount}][question]" 
                                  rows="2" placeholder="Enter your question here..." required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Answer Options *</label>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <input type="radio" name="questions[${questionCount}][correct_option]" value="1" required>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control" name="questions[${questionCount}][options][0]" 
                                               placeholder="Option 1" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <input type="radio" name="questions[${questionCount}][correct_option]" value="2" required>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control" name="questions[${questionCount}][options][1]" 
                                               placeholder="Option 2" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <input type="radio" name="questions[${questionCount}][correct_option]" value="3" required>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control" name="questions[${questionCount}][options][2]" 
                                               placeholder="Option 3" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <input type="radio" name="questions[${questionCount}][correct_option]" value="4" required>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control" name="questions[${questionCount}][options][3]" 
                                               placeholder="Option 4" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <small class="form-text text-muted">Select the radio button next to the correct answer.</small>
                    </div>
                </div>
            </div>
        `;
        
        $('#questions-container').append(questionHtml);
        $('#no-questions-message').hide();
        
        // Update add button state
        if (questionCount >= maxQuestions) {
            $('#add-question-btn').prop('disabled', true).text('Max Questions Reached');
        }
    }

    // Remove question functionality
    $(document).on('click', '.remove-question-btn', function() {
        $(this).closest('.question-item').remove();
        questionCount--;
        
        // Renumber remaining questions
        $('.question-item').each(function(index) {
            const newIndex = index + 1;
            $(this).attr('data-question-index', newIndex);
            $(this).find('.card-header h6').text('Question ' + newIndex);
            
            // Update form field names
            $(this).find('textarea[name*="[question]"]').attr('name', `questions[${newIndex}][question]`);
            $(this).find('input[name*="[correct_option]"]').attr('name', `questions[${newIndex}][correct_option]`);
            $(this).find('input[name*="[options]"]').each(function(optionIndex) {
                $(this).attr('name', `questions[${newIndex}][options][${optionIndex}]`);
            });
        });
        
        // Show/hide no questions message
        if (questionCount === 0) {
            $('#no-questions-message').show();
        }
        
        // Re-enable add button
        $('#add-question-btn').prop('disabled', false).html('<i class="fas fa-plus"></i> Add Question');
    });

    // AJAX Form submission with progress bar
    $('#video-upload-form').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this)[0];
        const formData = new FormData(form);
        const submitBtn = $('#submit-btn');
        const progressContainer = $('#upload-progress-container');
        const progressBar = $('#upload-progress-bar');
        const progressText = $('#upload-progress-text');
        const statusText = $('#upload-status-text');
        
        // Show progress bar
        progressContainer.show();
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Uploading...');
        
        // Create XMLHttpRequest for progress tracking
        const xhr = new XMLHttpRequest();
        
        // Track upload progress
        xhr.upload.addEventListener('progress', function(e) {
            if (e.lengthComputable) {
                const percentComplete = (e.loaded / e.total) * 100;
                const percentRounded = Math.round(percentComplete);
                
                progressBar.css('width', percentComplete + '%')
                          .attr('aria-valuenow', percentRounded);
                progressText.text(percentRounded + '%');
                
                if (percentRounded < 50) {
                    statusText.text('Uploading video file...');
                } else if (percentRounded < 90) {
                    statusText.text('Processing video...');
                } else {
                    statusText.text('Finalizing upload...');
                }
            }
        }, false);
        
        // Handle completion
        xhr.addEventListener('load', function() {
            if (xhr.status === 200) {
                progressBar.removeClass('progress-bar-striped progress-bar-animated')
                          .addClass('bg-success');
                progressText.text('100%');
                statusText.html('<i class="fas fa-check-circle text-success"></i> Upload successful! Redirecting...');
                
                // Parse response
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.redirect) {
                        setTimeout(function() {
                            window.location.href = response.redirect;
                        }, 1500);
                    } else {
                        window.location.reload();
                    }
                } catch (e) {
                    // If response is HTML (error page), show it
                    if (xhr.responseText.includes('<!DOCTYPE') || xhr.responseText.includes('<html')) {
                        // Redirect to show error
                        window.location.href = '{{ route("creator.videos.create") }}';
                    } else {
                        window.location.reload();
                    }
                }
            } else {
                // Handle error
                progressBar.removeClass('bg-primary')
                          .addClass('bg-danger');
                progressText.text('Error');
                statusText.html('<i class="fas fa-exclamation-triangle text-danger"></i> Upload failed. Please try again.');
                submitBtn.prop('disabled', false).html('<i class="fas fa-upload"></i> Upload Video');
                
                // Show detailed error message
                let errorMessage = '';
                let errorDetails = [];
                
                try {
                    const response = JSON.parse(xhr.responseText);
                    console.log('Error response:', response);
                    
                    // Check for validation errors first
                    if (response.errors) {
                        // Handle Laravel validation errors
                        Object.keys(response.errors).forEach(function(field) {
                            if (Array.isArray(response.errors[field])) {
                                response.errors[field].forEach(function(error) {
                                    errorDetails.push(error);
                                });
                            } else {
                                errorDetails.push(response.errors[field]);
                            }
                        });
                        errorMessage = 'Validation Error:\n' + errorDetails.join('\n');
                    } else if (response.message) {
                        // Use the message from response
                        errorMessage = response.message;
                        
                        // Add PHP limits info if available
                        if (response.php_limits) {
                            errorMessage += '\n\nCurrent PHP Limits:';
                            errorMessage += '\n- upload_max_filesize: ' + response.php_limits.upload_max_filesize;
                            errorMessage += '\n- post_max_size: ' + response.php_limits.post_max_size;
                            errorMessage += '\n- Your file size: ' + response.php_limits.file_size_mb + 'MB';
                        }
                    } else {
                        errorMessage = 'Upload failed. Please check your file and try again.';
                    }
                } catch (e) {
                    console.error('Error parsing response:', e);
                    console.log('Raw response:', xhr.responseText);
                    
                    // Check if response contains specific error messages
                    const responseText = xhr.responseText.toLowerCase();
                    if (responseText.includes('post content-length') || responseText.includes('post_max_size') || responseText.includes('too large')) {
                        errorMessage = 'File size exceeds server limit. Maximum allowed size is 200MB. Please reduce file size or contact administrator to update php.ini file.';
                    } else if (responseText.includes('upload_max_filesize')) {
                        errorMessage = 'File size exceeds upload limit. Maximum allowed size is 200MB.';
                    } else if (responseText.includes('413') || responseText.includes('request entity too large')) {
                        errorMessage = 'File is too large. Maximum allowed size is 200MB.';
                    } else if (responseText.includes('validation') || responseText.includes('required')) {
                        errorMessage = 'Validation failed. Please check all required fields are filled correctly.';
                    } else {
                        errorMessage = 'Upload failed. Status: ' + xhr.status + '. Please check your file and try again.';
                    }
                }
                
                // Show error in alert
                alert(errorMessage);
                
                // Also log to console for debugging
                console.error('Upload failed:', {
                    status: xhr.status,
                    statusText: xhr.statusText,
                    response: xhr.responseText
                });
            }
        }, false);
        
        // Handle errors
        xhr.addEventListener('error', function() {
            progressBar.removeClass('bg-primary')
                      .addClass('bg-danger');
            progressText.text('Error');
            statusText.html('<i class="fas fa-exclamation-triangle text-danger"></i> Network error. Please try again.');
            submitBtn.prop('disabled', false).html('<i class="fas fa-upload"></i> Upload Video');
            alert('Network error occurred. Please check your connection and try again.');
        }, false);
        
        // Handle abort
        xhr.addEventListener('abort', function() {
            progressBar.removeClass('bg-primary')
                      .addClass('bg-warning');
            progressText.text('Cancelled');
            statusText.html('<i class="fas fa-times text-warning"></i> Upload cancelled.');
            submitBtn.prop('disabled', false).html('<i class="fas fa-upload"></i> Upload Video');
        }, false);
        
        // Send request
        xhr.open('POST', form.action);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.send(formData);
    });
});
</script>
@endpush
