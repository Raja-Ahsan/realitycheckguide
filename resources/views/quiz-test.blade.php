@extends('layouts.website.master')
@section('title', 'Quiz Test')
@section('content')

<div class="container" style="margin-top: 50px;">
    <h1>Quiz Test Page</h1>
    
    <div id="debug-info" style="background: #f0f0f0; padding: 10px; margin: 10px 0;">
        <h3>Debug Information:</h3>
        <div id="debug-content">Loading...</div>
    </div>
    
    @if($categories->count() > 0)
        <form id="quiz-form" style="background: #fff; padding: 20px; border: 1px solid #ccc;">
            <div class="form-group">
                <label>Category:</label>
                <select id="category_id" class="form-control">
                    <option value="">Select a category...</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group">
                <label>Name:</label>
                <input type="text" id="user_name" class="form-control" placeholder="Enter your name" required>
            </div>
            
            <div class="form-group">
                <label>Email:</label>
                <input type="email" id="user_email" class="form-control" placeholder="Enter your email">
            </div>
            
            <button type="submit" class="btn btn-primary">Start Quiz</button>
        </form>
        
        <div id="quiz-container" style="display: none; background: #fff; padding: 20px; border: 1px solid #ccc; margin-top: 20px;">
            <h3>Quiz Questions</h3>
            <div id="quiz-content"></div>
            <button id="submit-quiz-btn" class="btn btn-success" style="display: none;">Submit Quiz</button>
        </div>
        
        <div id="results-container" style="display: none; background: #fff; padding: 20px; border: 1px solid #ccc; margin-top: 20px;">
            <h3>Results</h3>
            <div id="results-content"></div>
        </div>
    @else
        <p>No categories available.</p>
    @endif
</div>

@endsection

@push('js')
<script>
$(document).ready(function() {
    // Debug information
    $('#debug-content').html(`
        <p>jQuery loaded: ${typeof $ !== 'undefined'}</p>
        <p>jQuery version: ${typeof $ !== 'undefined' ? $.fn.jquery : 'N/A'}</p>
        <p>CSRF token: ${$('meta[name="csrf-token"]').attr('content')}</p>
        <p>Form exists: ${$('#quiz-form').length > 0}</p>
        <p>Categories count: {{ $categories->count() }}</p>
    `);
    
    console.log('=== QUIZ DEBUG START ===');
    console.log('jQuery loaded:', typeof $ !== 'undefined');
    console.log('jQuery version:', typeof $ !== 'undefined' ? $.fn.jquery : 'N/A');
    console.log('CSRF token:', $('meta[name="csrf-token"]').attr('content'));
    console.log('Form exists:', $('#quiz-form').length > 0);
    console.log('Categories count:', {{ $categories->count() }});
    
    let currentQuiz = null;
    let userAnswers = {};
    
    // Form submission
    $('#quiz-form').submit(function(e) {
        console.log('=== FORM SUBMIT EVENT ===');
        e.preventDefault();
        
        const categoryId = $('#category_id').val();
        const userName = $('#user_name').val();
        const userEmail = $('#user_email').val();
        
        console.log('Form values:', {categoryId, userName, userEmail});
        
        if (!categoryId || !userName) {
            alert('Please select a category and enter your name.');
            return;
        }
        
        loadQuiz(categoryId, userName, userEmail);
    });
    
    function loadQuiz(categoryId, userName, userEmail) {
        console.log('=== LOAD QUIZ FUNCTION ===');
        
        $('#quiz-container').hide();
        $('#results-container').hide();
        
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
                console.log('AJAX: Sending request...');
                $('#quiz-content').html('<p>Loading quiz...</p>');
                $('#quiz-container').show();
            },
            success: function(response) {
                console.log('AJAX: Success response:', response);
                if (response.success) {
                    currentQuiz = response;
                    userAnswers = {};
                    
                    displayQuestions();
                } else {
                    alert(response.message || 'Failed to load quiz.');
                    $('#quiz-container').hide();
                }
            },
            error: function(xhr) {
                console.log('AJAX: Error response:', xhr);
                console.log('Status:', xhr.status);
                console.log('Response text:', xhr.responseText);
                
                let errorMessage = 'An error occurred while loading the quiz.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                alert(errorMessage);
                $('#quiz-container').hide();
            }
        });
    }
    
    function displayQuestions() {
        console.log('=== DISPLAY QUESTIONS ===');
        console.log('Questions count:', currentQuiz.questions.length);
        
        let html = '';
        currentQuiz.questions.forEach(function(question, index) {
            html += `<div style="margin-bottom: 20px; padding: 10px; border: 1px solid #ddd;">
                <h4>Question ${index + 1}: ${question.question}</h4>
                <div>
                    <input type="radio" name="q${question.id}" value="A" id="q${question.id}_A">
                    <label for="q${question.id}_A">A. ${question.options.A}</label><br>
                    <input type="radio" name="q${question.id}" value="B" id="q${question.id}_B">
                    <label for="q${question.id}_B">B. ${question.options.B}</label><br>
                    <input type="radio" name="q${question.id}" value="C" id="q${question.id}_C">
                    <label for="q${question.id}_C">C. ${question.options.C}</label><br>
                    <input type="radio" name="q${question.id}" value="D" id="q${question.id}_D">
                    <label for="q${question.id}_D">D. ${question.options.D}</label><br>
                </div>
            </div>`;
        });
        
        $('#quiz-content').html(html);
        $('#submit-quiz-btn').show();
        
        // Store answers
        $('input[type="radio"]').change(function() {
            const questionId = $(this).attr('name').replace('q', '');
            const answer = $(this).val();
            userAnswers[questionId] = answer;
            console.log('Answer stored:', questionId, answer);
        });
    }
    
    $('#submit-quiz-btn').click(function() {
        console.log('=== SUBMIT QUIZ ===');
        console.log('User answers:', userAnswers);
        
        $.ajax({
            url: '{{ route("quiz.submit") }}',
            method: 'POST',
            data: {
                category_id: currentQuiz.category.id,
                user_name: $('#user_name').val(),
                user_email: $('#user_email').val(),
                answers: userAnswers,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log('Submit success:', response);
                $('#results-content').html(`
                    <h4>Results for ${response.result.user_name}</h4>
                    <p>Score: ${response.result.correct_answers}/${response.result.total_questions} (${response.result.score_percentage}%)</p>
                    <p>Grade: ${response.result.grade}</p>
                `);
                $('#quiz-container').hide();
                $('#results-container').show();
            },
            error: function(xhr) {
                console.log('Submit error:', xhr);
                alert('Error submitting quiz');
            }
        });
    });
    
    console.log('=== QUIZ DEBUG END ===');
});
</script>
@endpush

