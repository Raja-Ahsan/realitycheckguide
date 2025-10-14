@extends('layouts.website.master')
@section('title', 'Quiz Center')
@section('content')

<!-- ***** Quiz Section Start ***** -->
<section class="section" id="quiz-section" style="margin-top: 150px;">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-heading">
                    <h2 style="color: #000;">Test Your<span class=""> Knowledge</span> </h2>
                    <p>Choose a category and start your quiz to test your knowledge!</p>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-12">
                <div class="quiz-selection-card">
                    <div class="card">
                        <div class="card-header">
                            <h4><i class="fa fa-question-circle"></i> Select Quiz Category</h4>
                        </div>
                        <div class="card-body">
                            @if($categories->count() > 0)
                                <form id="quiz-form">
                                    @csrf
                                    <div class="form-group">
                                        <label for="category_id">Choose a Category:</label>
                                        <select class="form-control" id="category_id" name="category_id" required>
                                            <option value="">Select a category...</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" data-description="{{ $category->description }}">
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div id="category-description" class="alert alert-info" style="display: none;">
                                        <strong>Description:</strong> <span id="description-text"></span>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="user_name">Your Name:</label>
                                        <input type="text" class="form-control" id="user_name" name="user_name" placeholder="Enter your name" required>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="user_email">Your Email (Optional):</label>
                                        <input type="email" class="form-control" id="user_email" name="user_email" placeholder="Enter your email">
                                    </div>
                                    
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary btn-lg" id="start-quiz-btn">
                                            <i class="fa fa-play"></i> Start Quiz
                                        </button>
                                    </div>
                                </form>
                            @else
                                <div class="alert alert-warning text-center">
                                    <h5>No Quiz Categories Available</h5>
                                    <p>Please check back later for available quizzes.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quiz Container (Hidden initially) -->
        <div id="quiz-container" style="display: none;">
            <div class="row">
                <div class="col-lg-12">
                    <div class="quiz-card">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 id="quiz-title"><i class="fa fa-question-circle"></i> Quiz</h4>
                                <div class="quiz-progress">
                                    <span id="current-question">1</span> / <span id="total-questions">0</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div id="quiz-content">
                                    <!-- Quiz questions will be loaded here -->
                                </div>
                                
                                <div class="quiz-navigation text-center mt-4">
                                    <button type="button" class="btn btn-success btn-lg" id="submit-quiz-btn" style="display: none;">
                                        <i class="fa fa-check"></i> Submit Quiz
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Results Container (Hidden initially) -->
        <div id="results-container" style="display: none;">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="results-card">
                        <div class="card">
                            <div class="card-header text-center">
                                <h4><i class="fa fa-trophy"></i> Quiz Results</h4>
                            </div>
                            <div class="card-body">
                                <div id="results-content">
                                    <!-- Results will be displayed here -->
                                </div>
                                
                                <div class="text-center mt-4">
                                    <button type="button" class="btn btn-primary" id="retake-quiz-btn">
                                        <i class="fa fa-refresh"></i> Take Another Quiz
                                    </button>
                                    <a href="{{ route('quiz.results') }}" class="btn btn-info">
                                        <i class="fa fa-list"></i> View All Results
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ***** Quiz Section End ***** -->

@endsection

@push('css')
<style>
.quiz-selection-card, .quiz-card, .results-card {
    margin-bottom: 30px;
}

.quiz-progress {
    background: #f8f9fa;
    padding: 8px 15px;
    border-radius: 20px;
    font-weight: bold;
    color: #007bff;
}

.question-item {
    margin-bottom: 30px;
    padding: 20px;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    background: #f8f9fa;
}

.question-text {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 20px;
    color: #333;
}

.option-item {
    margin-bottom: 10px;
}

.option-item input[type="radio"] {
    margin-right: 10px;
}

.option-item label {
    font-size: 16px;
    cursor: pointer;
    padding: 10px;
    border-radius: 5px;
    transition: background-color 0.3s;
}

.option-item label:hover {
    background-color: #e9ecef;
}

.option-item input[type="radio"]:checked + label {
    background-color: #007bff;
    color: white;
}

.score-display {
    text-align: center;
    padding: 30px;
}

.score-circle {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 20px;
}

.score-excellent { background-color: #28a745; color: white; }
.score-good { background-color: #17a2b8; color: white; }
.score-average { background-color: #ffc107; color: black; }
.score-poor { background-color: #dc3545; color: white; }

.result-item {
    padding: 15px;
    margin-bottom: 10px;
    border-radius: 5px;
}

.result-correct {
    background-color: #d4edda;
    border-left: 4px solid #28a745;
}

.result-incorrect {
    background-color: #f8d7da;
    border-left: 4px solid #dc3545;
}

.loading {
    text-align: center;
    padding: 50px;
}

.spinner {
    border: 4px solid #f3f3f3;
    border-top: 4px solid #007bff;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    animation: spin 1s linear infinite;
    margin: 0 auto 20px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
@endpush

@push('js')
<script>
$(document).ready(function() {
    console.log('jQuery loaded successfully');
    console.log('Quiz page loaded');
    
    let currentQuiz = null;
    let currentQuestionIndex = 0;
    let userAnswers = {};
    
    // Test if form exists
    //console.log('Quiz form exists:', $('#quiz-form').length > 0);
    //console.log('Category dropdown exists:', $('#category_id').length > 0);
    //console.log('Start button exists:', $('#start-quiz-btn').length > 0);
    
    // Test button
    $('#test-js-btn').click(function() {
        //console.log('Test button clicked - JavaScript is working!');
        alert('JavaScript is working! Check console for details.');
    });
    
    // Show category description when selected
    $('#category_id').change(function() {
        const selectedOption = $(this).find('option:selected');
        const description = selectedOption.data('description');
       
        if (description) {
            $('#description-text').text(description);
            $('#category-description').show();
        } else {
            $('#category-description').hide();
        }
    });
    
    // Start quiz
    $('#quiz-form').submit(function(e) {
        console.log('Form submit event triggered');
        e.preventDefault();
        
        const categoryId = $('#category_id').val();
        const userName = $('#user_name').val();
        const userEmail = $('#user_email').val();
        
        console.log('Form values:', {
            categoryId: categoryId,
            userName: userName,
            userEmail: userEmail
        });
        
        if (!categoryId || !userName) {
            alert('Please select a category and enter your name.');
            return;
        }
        
        console.log('Calling loadQuiz function');
        loadQuiz(categoryId, userName, userEmail);
    });
    
    // Alternative: Direct button click handler
    $('#start-quiz-btn').click(function(e) {
        console.log('Start quiz button clicked directly');
        e.preventDefault();
        
        const categoryId = $('#category_id').val();
        const userName = $('#user_name').val();
        const userEmail = $('#user_email').val();
        
        if (!categoryId || !userName) {
            alert('Please select a category and enter your name.');
            return;
        }
        
        console.log('Calling loadQuiz function from button click');
        loadQuiz(categoryId, userName, userEmail);
    });
    
    // Load quiz via AJAX
    function loadQuiz(categoryId, userName, userEmail) {
        $('#quiz-container').hide();
        $('#results-container').hide();
        
        console.log('Loading quiz for category:', categoryId);
        console.log('User name:', userName);
        console.log('User email:', userEmail);
        
        $.ajax({
            url: '{{ route("quiz.load") }}',
            method: 'POST',
            data: {
                category_id: categoryId,
                user_name: userName,
                user_email: userEmail,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {
                console.log('Sending AJAX request...');
                $('#quiz-content').html('<div class="loading"><div class="spinner"></div><p>Loading quiz...</p></div>');
                $('#quiz-container').show();
            },
            success: function(response) {
                console.log('AJAX Success:', response);
                if (response.success) {
                    currentQuiz = response;
                    currentQuestionIndex = 0;
                    userAnswers = {};
                    
                    $('#quiz-title').html('<i class="fa fa-question-circle"></i> ' + response.category.name);
                    $('#total-questions').text(response.total_questions);
                    
                    displayQuestion();
                } else {
                    alert(response.message || 'Failed to load quiz.');
                    $('#quiz-container').hide();
                }
            },
            error: function(xhr) {
                console.log('AJAX Error:', xhr);
                console.log('Status:', xhr.status);
                console.log('Response:', xhr.responseText);
                
                let errorMessage = 'An error occurred while loading the quiz.';
                
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.status === 419) {
                    errorMessage = 'CSRF token mismatch. Please refresh the page and try again.';
                } else if (xhr.status === 500) {
                    errorMessage = 'Server error. Please try again later.';
                }
                
                alert(errorMessage);
                $('#quiz-container').hide();
            }
        });
    }
    
    // Display all questions
    function displayQuestion() {
        if (!currentQuiz || !currentQuiz.questions.length) {
            return;
        }
        
        $('#current-question').text('1');
        $('#total-questions').text(currentQuiz.questions.length);
        
        let html = '';
        
        currentQuiz.questions.forEach(function(question, index) {
            const questionNumber = index + 1;
            
            html += '<div class="question-item">';
            html += '<div class="question-text">' + questionNumber + '. ' + question.question + '</div>';
            html += '<div class="options">';
            
            Object.keys(question.options).forEach(function(key) {
                const optionText = question.options[key];
                const isChecked = userAnswers[question.id] === key ? 'checked' : '';
                
                html += '<div class="option-item">';
                html += '<input type="radio" name="question_' + question.id + '" value="' + key + '" id="option_' + question.id + '_' + key + '" ' + isChecked + '>';
                html += '<label for="option_' + question.id + '_' + key + '">' + key + '. ' + optionText + '</label>';
                html += '</div>';
            });
            
            html += '</div>';
            html += '</div>';
        });
        
        $('#quiz-content').html(html);
        $('#submit-quiz-btn').show();
        
        // Store answer when option is selected
        $('input[name^="question_"]').change(function() {
            const questionId = $(this).attr('name').split('_')[1];
            const answer = $(this).val();
            userAnswers[questionId] = answer;
            console.log('Answer stored:', questionId, answer);
        });
    }
    
    // Submit quiz
    $('#submit-quiz-btn').click(function() {
        submitQuiz();
    });
    
    // Submit quiz via AJAX
    function submitQuiz() {
        const answers = {};
        Object.keys(userAnswers).forEach(function(questionId) {
            answers[questionId] = userAnswers[questionId];
        });
        
        console.log('Submitting quiz with answers:', answers);
        
        $.ajax({
            url: '{{ route("quiz.submit") }}',
            method: 'POST',
            data: {
                category_id: currentQuiz.category.id,
                user_name: $('#user_name').val(),
                user_email: $('#user_email').val(),
                answers: answers,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {
                $('#quiz-content').html('<div class="loading"><div class="spinner"></div><p>Submitting quiz...</p></div>');
            },
            success: function(response) {
                console.log('Quiz submission success:', response);
                if (response.success) {
                    displayResults(response);
                } else {
                    alert(response.message || 'Failed to submit quiz.');
                }
            },
            error: function(xhr) {
                console.log('Quiz submission error:', xhr);
                const response = xhr.responseJSON;
                alert(response.message || 'An error occurred while submitting the quiz.');
            }
        });
    }
    
    // Display results
    function displayResults(response) {
        const result = response.result;
        const detailedResults = response.detailed_results;
        
        let html = '<div class="score-display">';
        
        // Score circle
        let scoreClass = 'score-poor';
        if (result.score_percentage >= 90) scoreClass = 'score-excellent';
        else if (result.score_percentage >= 70) scoreClass = 'score-good';
        else if (result.score_percentage >= 50) scoreClass = 'score-average';
        
        html += '<div class="score-circle ' + scoreClass + '">';
        html += result.score_percentage + '%';
        html += '</div>';
        
        html += '<h3>Congratulations, ' + result.user_name + '!</h3>';
        html += '<p class="lead">You scored ' + result.correct_answers + ' out of ' + result.total_questions + ' questions correctly.</p>';
        html += '<p><strong>Grade:</strong> ' + result.grade + '</p>';
        html += '<p><strong>Category:</strong> ' + result.category_name + '</p>';
        html += '</div>';
        
        // Detailed results
        html += '<h5>Detailed Results:</h5>';
        detailedResults.forEach(function(item, index) {
            const resultClass = item.is_correct ? 'result-correct' : 'result-incorrect';
            const icon = item.is_correct ? 'fa-check' : 'fa-times';
            
            html += '<div class="result-item ' + resultClass + '">';
            html += '<div class="d-flex justify-content-between align-items-start">';
            html += '<div class="flex-grow-1">';
            html += '<strong>Question ' + (index + 1) + ':</strong> ' + item.question + '<br>';
            html += '<strong>Your Answer:</strong> ' + (item.user_answer || 'Not answered') + '<br>';
            html += '<strong>Correct Answer:</strong> ' + item.correct_answer;
            html += '</div>';
            html += '<div class="ml-3">';
            html += '<i class="fa ' + icon + ' fa-2x"></i>';
            html += '</div>';
            html += '</div>';
            html += '</div>';
        });
        
        $('#results-content').html(html);
        $('#quiz-container').hide();
        $('#results-container').show();
    }
    
    // Retake quiz
    $('#retake-quiz-btn').click(function() {
        $('#results-container').hide();
        $('#quiz-container').hide();
        $('html, body').animate({
            scrollTop: $('#quiz-section').offset().top
        }, 500);
    });
});
</script>
@endpush
