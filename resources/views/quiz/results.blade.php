@extends('layouts.website.master')
@section('title', 'Quiz Results')
@section('content')

<!-- ***** Quiz Results Section Start ***** -->
<section class="section" id="quiz-results-section" style="margin-top: 150px;">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-heading">
                    <h2>Quiz<span class=""> Results</span> </h2>
                    <p>View all quiz attempts and track your progress</p>
                </div>
            </div>
        </div>
        
        <!-- Filters -->
        <div class="row mb-4">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="{{ route('quiz.results') }}" id="filter-form">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="category_id">Filter by Category:</label>
                                        <select class="form-control" id="category_id" name="category_id">
                                            <option value="">All Categories</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="date_from">From Date:</label>
                                        <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="date_to">To Date:</label>
                                        <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <div>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-filter"></i> Filter
                                            </button>
                                            <a href="{{ route('quiz.results') }}" class="btn btn-secondary">
                                                <i class="fa fa-refresh"></i> Clear
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Statistics -->
        <div class="row mb-4">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fa fa-bar-chart"></i> Statistics</h5>
                    </div>
                    <div class="card-body">
                        <div class="row" id="stats-container">
                            <div class="col-md-3">
                                <div class="stat-item text-center">
                                    <div class="stat-number text-primary">{{ $results->total() }}</div>
                                    <div class="stat-label">Total Attempts</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-item text-center">
                                    <div class="stat-number text-success">{{ number_format($results->avg('score_percentage'), 1) }}%</div>
                                    <div class="stat-label">Average Score</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-item text-center">
                                    <div class="stat-number text-info">{{ $results->max('score_percentage') }}%</div>
                                    <div class="stat-label">Highest Score</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-item text-center">
                                    <div class="stat-number text-warning">{{ $results->where('completed_at', '>=', now()->subDays(7))->count() }}</div>
                                    <div class="stat-label">This Week</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Results Table -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fa fa-list"></i> Quiz Attempts</h5>
                    </div>
                    <div class="card-body">
                        @if($results->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Category</th>
                                            <th>Score</th>
                                            <th>Grade</th>
                                            <th>Questions</th>
                                            <th>Correct</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($results as $index => $result)
                                            <tr>
                                                <td>{{ $results->firstItem() + $index }}</td>
                                                <td>
                                                    <strong>{{ $result->user_name }}</strong>
                                                    @if($result->user_email)
                                                        <br><small class="text-muted">{{ $result->user_email }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge badge-info">{{ $result->category->name }}</span>
                                                </td>
                                                <td>
                                                    <div class="score-display">
                                                        @php
                                                            $scoreClass = 'text-danger';
                                                            if ($result->score_percentage >= 90) $scoreClass = 'text-success';
                                                            elseif ($result->score_percentage >= 70) $scoreClass = 'text-info';
                                                            elseif ($result->score_percentage >= 50) $scoreClass = 'text-warning';
                                                        @endphp
                                                        <span class="{{ $scoreClass }} font-weight-bold">{{ $result->score_percentage }}%</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    @php
                                                        $gradeClass = 'badge-danger';
                                                        if ($result->score_percentage >= 90) $gradeClass = 'badge-success';
                                                        elseif ($result->score_percentage >= 70) $gradeClass = 'badge-info';
                                                        elseif ($result->score_percentage >= 50) $gradeClass = 'badge-warning';
                                                    @endphp
                                                    <span class="badge {{ $gradeClass }}">{{ $result->grade }}</span>
                                                </td>
                                                <td>{{ $result->total_questions }}</td>
                                                <td>{{ $result->correct_answers }}</td>
                                                <td>
                                                    <small>{{ $result->completed_at->format('M d, Y') }}</small><br>
                                                    <small class="text-muted">{{ $result->completed_at->format('H:i') }}</small>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Pagination -->
                            <div class="row mt-4">
                                <div class="col-sm-6">
                                    <div class="dataTables_info">
                                        Showing {{ $results->firstItem() }} to {{ $results->lastItem() }} of {{ $results->total() }} entries
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="dataTables_paginate paging_simple_numbers">
                                        {{ $results->links() }}
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fa fa-question-circle fa-3x text-muted mb-3"></i>
                                <h5>No Quiz Results Found</h5>
                                <p class="text-muted">No quiz attempts match your current filters.</p>
                                <a href="{{ route('quiz.index') }}" class="btn btn-primary">
                                    <i class="fa fa-play"></i> Take a Quiz
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="row mt-4">
            <div class="col-lg-12 text-center">
                <a href="{{ route('quiz.index') }}" id="take-another-quiz-btn" class="btn btn-primary btn-lg">
                    <i class="fa fa-play"></i> Take Another Quiz
                </a>
            </div>
        </div>
    </div>
</section>
<!-- ***** Quiz Results Section End ***** -->

@endsection

@push('css')
<style>
.stat-item {
    padding: 20px;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    margin-bottom: 20px;
    background: #f8f9fa;
}

.stat-number {
    font-size: 2.5rem;
    font-weight: bold;
    margin-bottom: 5px;
}

.stat-label {
    font-size: 14px;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.score-display {
    font-size: 18px;
}

.table th {
    background-color: #f8f9fa;
    border-top: none;
    font-weight: 600;
}

.table td {
    vertical-align: middle;
}

.badge {
    font-size: 12px;
}

.card {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    border: none;
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
    font-weight: 600;
}

@media (max-width: 768px) {
    .stat-number {
        font-size: 2rem;
    }
    
    .table-responsive {
        font-size: 14px;
    }
}
</style>
@endpush

@push('js')
<script>
$(document).ready(function() {
    // Auto-submit form when filters change
    $('#category_id, #date_from, #date_to').change(function() {
        $('#filter-form').submit();
    });
    
    // Load additional stats via AJAX if needed
    loadAdditionalStats();
    
    function loadAdditionalStats() {
        const categoryId = $('#category_id').val();
        
        $.ajax({
            url: '{{ route("quiz.stats") }}',
            method: 'GET',
            data: { category_id: categoryId },
            success: function(response) {
                if (response.success) {
                    // Update stats if needed
                    console.log('Stats loaded:', response.stats);
                }
            }
        });
    }
});
</script>
@endpush
