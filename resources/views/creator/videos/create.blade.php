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
                            <form action="{{ route('creator.videos.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                
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
                                                Supported formats: MP4, AVI, MOV, WMV, FLV, WebM (Max: 100MB)
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
                                <li><i class="fas fa-check text-success"></i> Video max size: 100MB</li>
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

    // Form submission validation
    $('form').on('submit', function(e) {
        console.log('Form submission started');
        console.log('Question count:', questionCount);
        console.log('Form data:', $(this).serialize());
        
        // Validate Q&A if questions exist - Temporarily disabled for debugging
        // if (questionCount > 0) {
        //     console.log('Validating Q&A questions...');
        //     let isValid = true;
        //     $('.question-item').each(function() {
        //         const questionText = $(this).find('.question-text').val().trim();
        //         const correctOption = $(this).find('input[name*="[correct_option]"]:checked').length;
        //         const options = $(this).find('input[name*="[options]"]');
        //         
        //         console.log('Question validation:', {
        //             questionText: questionText,
        //             correctOption: correctOption,
        //             optionsCount: options.length
        //         });
        //         
        //         if (!questionText) {
        //             isValid = false;
        //             $(this).find('.question-text').addClass('is-invalid');
        //         } else {
        //             $(this).find('.question-text').removeClass('is-invalid');
        //         }
        //         
        //         if (!correctOption) {
        //             isValid = false;
        //             alert('Please select a correct answer for Question ' + $(this).attr('data-question-index'));
        //         }
        //         
        //         options.each(function() {
        //             if (!$(this).val().trim()) {
        //                 isValid = false;
        //                 $(this).addClass('is-invalid');
        //             } else {
        //                 $(this).removeClass('is-invalid');
        //             }
        //         });
        //     });
        //     
        //     if (!isValid) {
        //         e.preventDefault();
        //         alert('Please complete all question fields and select correct answers.');
        //         return false;
        //     }
        // }
        
        console.log('Form validation passed, submitting...');
    });
});
</script>
@endpush
