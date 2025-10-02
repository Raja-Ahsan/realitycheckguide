@extends('layouts.website.master')

@section('title', $video->title)

@section('content')
    @if (!empty($banner->image))
        <section class="inner-banner creator-profile-banner"
            style="margin-top: 80px; height: 200px; background-size: cover; background-image: url('{{ asset('admin/assets/images/banner') }}/{{ $banner->image }}');">
        @else
        <section class="inner-banner creator-profile-banner" 
            style="margin-top: 80px; height: 200px; background-size: cover; background-image: url('{{ asset('public/admin/assets/images/images.png') }}');">
    @endif
        <div class="banner-wrapper position-relative z-1">
            <div class="container">
                <div class="row"> 
                    <div class="col-lg-12 col-xl-12" data-aos="fade-up" data-aos-easing="linear" data-aos-duration="1500"> 
                        <h1 class="hd-70 mt-5">Video</h1>
                        <p class="hd-20 text-white">Video Detail</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="video-detail-sec pt-100 pb-100 mt-5">
        <div class="container">
            <div class="row">
                <!-- Video Player Section -->
                <div class="col-lg-8">
                    <div class="video-player-container mb-4">
                        @if($video->video_path)
                            <video controls class="w-100" style="max-height: 500px;">
                                <source src="{{ asset('storage/app/public/' . $video->video_path) }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        @else
                            <div class="bg-secondary d-flex align-items-center justify-content-center" style="height: 400px;">
                                <div class="text-center text-white">
                                    <i class="fa fa-video fa-4x mb-3"></i>
                                    <h4>Video Not Available</h4>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Video Information -->
                    <div class="video-info">
                        <h1 class="hd-40 mb-3">{{ $video->title }}</h1>
                        
                        <div class="video-meta mb-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <span class="badge badge-{{ $video->is_intro ? 'info' : 'warning' }} badge-pill mr-2">
                                        {{ $video->is_intro ? 'Intro Video' : 'Paid Video' }}
                                    </span>
                                    @if($video->price > 0)
                                        <span class="badge badge-success badge-pill">
                                            ${{ number_format($video->price, 2) }}
                                        </span>
                                    @else
                                        <span class="badge badge-success badge-pill">Free</span>
                                    @endif
                                </div>
                                <div class="col-md-6 text-right">
                                    <small class="text-muted">
                                        <i class="fa fa-eye"></i> {{ number_format($video->views_count ?? 0) }} views
                                        <i class="fa fa-calendar ml-2"></i> {{ $video->created_at->format('M d, Y') }}
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="video-description mb-4">
                            <h5>Description</h5>
                            <p>{{ $video->description ?: 'No description available.' }}</p>
                        </div>

                        <!-- Creator Information -->
                        <div class="creator-info mb-4">
                            <h5>Creator</h5>
                            <div class="d-flex align-items-center">
                                @if($video->creator->image)
                                    <img src="{{ asset('storage/app/public/' . $video->creator->image) }}" 
                                         alt="{{ $video->creator->name }}" 
                                         class="rounded-circle mr-3" style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mr-3" 
                                         style="width: 50px; height: 50px;">
                                        <i class="fa fa-user text-white"></i>
                                    </div>
                                @endif
                                <div>
                                    <h6 class="mb-0">{{ $video->creator->name }}</h6>
                                    <small class="text-muted">{{ $video->creator->designation ?? 'Content Creator' }}</small>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="video-actions">
                            @if($video->is_intro)
                                <!-- Intro video is always accessible -->
                                <div class="alert alert-info">
                                    <i class="fa fa-info-circle"></i> This is a free introduction video from {{ $video->creator->name }}.
                                </div>
                            @else
                                @if($hasPurchased)
                                    <!-- User has purchased this video -->
                                    <div class="alert alert-success">
                                        <i class="fa fa-check-circle"></i> You have purchased this video!
                                        @if($video->downloads_enabled)
                                            <a href="{{ route('videos.download', $video) }}" class="btn btn-outline-primary btn-sm ml-2">
                                                <i class="fa fa-download"></i> Download Video
                                            </a>
                                        @endif
                                    </div>
                                @else
                                    <!-- User needs to purchase -->
                                    <div class="alert alert-warning">
                                        <i class="fa fa-lock"></i> This is a paid video. Purchase required to watch.
                                        <a href="{{ route('videos.buy', $video) }}" class="btn btn-primary ml-2">
                                            <i class="fa fa-shopping-cart"></i> Buy for ${{ number_format($video->price, 2) }}
                                        </a>
                                    </div>
                                @endif
                            @endif
                        </div>

                        <!-- Q&A Section -->
                        @if($canAccess && $video->hasQuestions())
                            <div class="qa-section mt-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">
                                            <i class="fa fa-question-circle"></i> 
                                            Learning Assessment ({{ $video->getTotalQuestionsAttribute() }} Questions)
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <!-- Progress Bar -->
                                        <div class="progress mb-3" id="learning-progress-bar">
                                            <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                <span id="progress-text">0% Complete</span>
                            </div>
                        </div>

                        <!-- Questions Container -->
                        <div id="questions-container">
                            @foreach($video->questions as $index => $question)
                                <div class="question-item mb-4" data-question-id="{{ $question->id }}">
                                    <div class="question-header">
                                        <h6 class="mb-2">
                                            Question {{ $index + 1 }}: {{ $question->question }}
                                        </h6>
                                    </div>
                                    
                                    <div class="question-options">
                                        @foreach($question->options as $option)
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="radio" 
                                                       name="question_{{ $question->id }}" 
                                                       value="{{ $option->id }}" 
                                                       id="option_{{ $option->id }}"
                                                       data-question-id="{{ $question->id }}">
                                                <label class="form-check-label" for="option_{{ $option->id }}">
                                                    {{ $option->option_text }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                    
                                    <div class="question-feedback mt-2" style="display: none;">
                                        <!-- Feedback will be shown here -->
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Submit Button -->
                        <div class="text-center mt-3">
                            <button type="button" class="btn btn-primary" id="submit-answers-btn" disabled>
                                <i class="fa fa-check"></i> Submit Answers
                            </button>
                        </div>

                        <!-- Learning Progress Summary -->
                        <div class="learning-summary mt-4" id="learning-summary" style="display: none;">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="fa fa-chart-line"></i> Your Learning Progress
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>Questions Answered:</strong> <span id="answered-count">0</span>/<span id="total-count">{{ $video->getTotalQuestionsAttribute() }}</span></p>
                                            <p class="mb-1"><strong>Correct Answers:</strong> <span id="correct-count">0</span></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>Learning Score:</strong> <span id="learning-score">0</span>%</p>
                                            <p class="mb-1"><strong>Progress:</strong> <span id="progress-percentage">0</span>%</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($canAccess && !$video->hasQuestions())
                <div class="qa-section mt-4">
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle"></i> 
                        This video doesn't have any learning assessment questions yet.
                    </div>
                </div>
            @endif
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Related Videos -->
                    <div class="related-videos">
                        <h5 class="mb-3">More from {{ $video->creator->name }}</h5>
                        @forelse($relatedVideos as $relatedVideo)
                            <div class="video-card mb-3">
                                <div class="row">
                                    <div class="col-4">
                                        @if($relatedVideo->thumbnail_path)
                                            <img src="{{ asset('storage/app/public/' . $relatedVideo->thumbnail_path) }}" 
                                                 alt="{{ $relatedVideo->title }}" 
                                                 class="img-fluid rounded" style="height: 80px; object-fit: cover;">
                                        @else
                                            <div class="bg-secondary rounded d-flex align-items-center justify-content-center" 
                                                 style="height: 80px;">
                                                <i class="fa fa-video text-white"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-8">
                                        <h6 class="mb-1">
                                            <a href="{{ route('videos.show', $relatedVideo) }}" class="text-decoration-none">
                                                {{ Str::limit($relatedVideo->title, 40) }}
                                            </a>
                                        </h6>
                                        <div class="video-meta-small">
                                            @if($relatedVideo->is_intro)
                                                <span class="label label-info">Free</span>
                                            @else
                                                <span class="label label-info">${{ number_format($relatedVideo->price, 2) }}</span>
                                            @endif
                                            <small class="text-muted ml-2">
                                                <i class="fa fa-eye"></i> {{ number_format($relatedVideo->views_count ?? 0) }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted">No other videos from this creator yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection

@push('styles')
<style>
.video-player-container {
    background: #000;
    border-radius: 8px;
    overflow: hidden;
}

.video-player-container video {
    width: 100%;
    height: auto;
}

.video-card {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 10px;
    transition: all 0.3s ease;
}

.video-card:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.badge-sm {
    font-size: 0.7rem;
    padding: 4px 8px;
}

.video-meta-small {
    margin-top: 5px;
}

.question-item {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 15px;
    background: #f8f9fa;
}

.question-feedback {
    padding: 10px;
    border-radius: 4px;
    margin-top: 10px;
}

.feedback-correct {
    background-color: #d4edda;
    border: 1px solid #c3e6cb;
    color: #155724;
}

.feedback-incorrect {
    background-color: #f8d7da;
    border: 1px solid #f5c6cb;
    color: #721c24;
}

.progress-bar {
    transition: width 0.3s ease;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    let answeredQuestions = 0;
    let correctAnswers = 0;
    let totalQuestions = {{ $video->getTotalQuestionsAttribute() }};
    let userAnswers = {};

    // Load existing progress if any
    loadLearningProgress();

    // Handle radio button changes
    $('input[type="radio"]').on('change', function() {
        const questionId = $(this).data('question-id');
        const optionId = $(this).val();
        
        // Store the answer
        userAnswers[questionId] = optionId;
        
        // Update answered count
        answeredQuestions = Object.keys(userAnswers).length;
        
        // Enable submit button if all questions answered
        if (answeredQuestions === totalQuestions) {
            $('#submit-answers-btn').prop('disabled', false);
        }
        
        // Update progress bar
        updateProgressBar();
    });

    // Submit answers
    $('#submit-answers-btn').on('click', function() {
        if (answeredQuestions < totalQuestions) {
            alert('Please answer all questions before submitting.');
            return;
        }

        $(this).prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Submitting...');

        // Submit each answer
        let submittedCount = 0;
        let totalCorrect = 0;

        Object.keys(userAnswers).forEach(function(questionId) {
            const optionId = userAnswers[questionId];
            
            $.ajax({
                url: '{{ route("videos.question.answer", ["video" => $video->id, "question" => ":questionId"]) }}'.replace(':questionId', questionId),
                method: 'POST',
                data: {
                    option_id: optionId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    submittedCount++;
                    
                    if (response.is_correct) {
                        totalCorrect++;
                    }
                    
                    // Show feedback for this question
                    showQuestionFeedback(questionId, response.is_correct, response.correct_option_id);
                    
                    if (submittedCount === Object.keys(userAnswers).length) {
                        // All answers submitted
                        correctAnswers = totalCorrect;
                        updateLearningSummary();
                        $('#learning-summary').show();
                        $('#submit-answers-btn').hide();
                    }
                },
                error: function(xhr) {
                    console.error('Error submitting answer:', xhr.responseJSON);
                    alert('Error submitting answer. Please try again.');
                    $('#submit-answers-btn').prop('disabled', false).html('<i class="fa fa-check"></i> Submit Answers');
                }
            });
        });
    });

    function showQuestionFeedback(questionId, isCorrect, correctOptionId) {
        const questionElement = $(`.question-item[data-question-id="${questionId}"]`);
        const feedbackElement = questionElement.find('.question-feedback');
        
        // Disable all radio buttons for this question
        questionElement.find('input[type="radio"]').prop('disabled', true);
        
        // Highlight correct answer
        questionElement.find(`input[value="${correctOptionId}"]`).closest('.form-check').addClass('text-success');
        
        // Show feedback
        if (isCorrect) {
            feedbackElement.html('<i class="fa fa-check-circle text-success"></i> Correct! Well done.')
                .addClass('feedback-correct')
                .show();
        } else {
            feedbackElement.html('<i class="fa fa-times-circle text-danger"></i> Incorrect. The correct answer is highlighted above.')
                .addClass('feedback-incorrect')
                .show();
        }
    }

    function updateProgressBar() {
        const percentage = totalQuestions > 0 ? (answeredQuestions / totalQuestions) * 100 : 0;
        $('#learning-progress-bar .progress-bar').css('width', percentage + '%');
        $('#progress-text').text(Math.round(percentage) + '% Complete');
    }

    function updateLearningSummary() {
        const learningScore = answeredQuestions > 0 ? (correctAnswers / answeredQuestions) * 100 : 0;
        const progressPercentage = totalQuestions > 0 ? (answeredQuestions / totalQuestions) * 100 : 0;
        
        $('#answered-count').text(answeredQuestions);
        $('#total-count').text(totalQuestions);
        $('#correct-count').text(correctAnswers);
        $('#learning-score').text(Math.round(learningScore));
        $('#progress-percentage').text(Math.round(progressPercentage));
    }

    function loadLearningProgress() {
        $.ajax({
            url: '{{ route("videos.learning-progress", $video->id) }}',
            method: 'GET',
            success: function(response) {
                if (response.progress.answered_questions > 0) {
                    answeredQuestions = response.progress.answered_questions;
                    correctAnswers = response.progress.correct_answers;
                    
                    updateProgressBar();
                    updateLearningSummary();
                    $('#learning-summary').show();
                    
                    // Disable submit button if already completed
                    if (answeredQuestions >= totalQuestions) {
                        $('#submit-answers-btn').hide();
                    }
                }
            },
            error: function(xhr) {
                console.error('Error loading progress:', xhr.responseJSON);
            }
        });
    }
});
</script>
@endpush
