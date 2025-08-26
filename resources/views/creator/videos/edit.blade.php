@extends('layouts.creator.app')

@section('title', 'Edit Video')

@section('content')
    <!-- Debug Info -->
    @if(config('app.debug'))
        <div class="alert alert-info">
            <strong>Debug Info:</strong>
            Video ID: {{ $video->id }}, 
            Creator ID: {{ $video->creator_id }}, 
            Current User: {{ Auth::user()->id }},
            Route: {{ route('creator.videos.update', $video) }}
        </div>
    @endif
    
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Video</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('creator.dashboard') }}">Creator Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('creator.videos.index') }}">My Videos</a></li>
                        <li class="breadcrumb-item active">Edit Video</li>
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
                                <i class="fas fa-edit"></i> Edit Video Information
                            </h3>
                        </div>
                        <div class="card-body">
                            <!-- Display any general errors -->
                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            
                            <form action="{{ route('creator.videos.update', $video) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                
                                <div class="form-group">
                                    <label for="title">Video Title *</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title', $video->title) }}" required>
                                    @error('title')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="4">{{ old('description', $video->description) }}</textarea>
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
                                                            {{ old('category_id', $video->category_id) == $category->id ? 'selected' : '' }}>
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
                                                   id="tags" name="tags" value="{{ old('tags', is_array($video->tags) ? implode(', ', $video->tags) : $video->tags) }}" 
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
                                            <label for="is_intro">Video Type</label>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" 
                                                       id="is_intro" name="is_intro" value="1"
                                                       {{ old('is_intro', $video->is_intro) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="is_intro">
                                                    This is an intro video (free for all viewers)
                                                </label>
                                            </div>
                                            <small class="form-text text-muted">
                                                Intro videos are free and accessible to everyone
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="downloads_enabled">Downloads</label>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" 
                                                       id="downloads_enabled" name="downloads_enabled" value="1"
                                                       {{ old('downloads_enabled', $video->downloads_enabled) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="downloads_enabled">
                                                    Allow users to download this video after purchase
                                                </label>
                                            </div>
                                            <small class="form-text text-muted">
                                                Check this box to allow users to download the video after purchase
                                            </small>
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
                                                       id="price" name="price" value="{{ old('price', $video->price) }}" 
                                                       step="0.01" min="0" max="999.99" required>
                                            </div>
                                            <div class="price-info">
                                                <small class="text-muted">Price range: ${{ \App\Models\AdminSetting::getMinVideoPrice() }} - ${{ \App\Models\AdminSetting::getMaxVideoPrice() }}</small>
                                            </div>
                                            @error('price')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Pricing Rules</label>
                                            <div class="alert alert-info">
                                                <small>
                                                    <strong>Current Rules:</strong><br>
                                                    • Min Price: ${{ \App\Models\AdminSetting::getMinVideoPrice() }}<br>
                                                    • Max Price: ${{ \App\Models\AdminSetting::getMaxVideoPrice() }}<br>
                                                    • Custom pricing unlocks after {{ \App\Models\AdminSetting::getVideosSoldThreshold() }} videos sold
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="thumbnail">Thumbnail</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input @error('thumbnail') is-invalid @enderror" 
                                               id="thumbnail" name="thumbnail" accept="image/*">
                                        <label class="custom-file-label" for="thumbnail">Choose thumbnail image</label>
                                    </div>
                                    <small class="form-text text-muted">
                                        Supported formats: JPEG, PNG, JPG, GIF (Max: 2MB)
                                    </small>
                                    @error('thumbnail')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    
                                    @if($video->thumbnail_path)
                                        <div class="mt-2">
                                            <p><strong>Current Thumbnail:</strong></p>
                                            <img src="{{ asset('storage/app/public/' . $video->thumbnail_path) }}" 
                                                 alt="Current thumbnail" class="img-thumbnail" style="max-width: 200px;">
                                        </div>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="video_file">Video File</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input @error('video_file') is-invalid @enderror" 
                                               id="video_file" name="video_file" accept="video/*">
                                        <label class="custom-file-label" for="video_file">Choose new video file (optional)</label>
                                    </div>
                                    <small class="form-text text-muted">
                                        Supported formats: MP4, AVI, MOV, WMV, FLV, WEBM (Max: 100MB)
                                    </small>
                                    @error('video_file')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    
                                    <div class="mt-2">
                                        <p><strong>Current Video File:</strong></p>
                                        <div class="alert alert-info">
                                            <i class="fas fa-video"></i> {{ basename($video->video_path) }}
                                            <br>
                                            <small class="text-muted">Leave empty to keep the current video file</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Update Video
                                    </button>
                                    <a href="{{ route('creator.videos.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Cancel
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-info-circle"></i> Video Information
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <strong>Status:</strong><br>
                                    <span class="badge badge-{{ $video->status === 'active' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($video->status) }}
                                    </span>
                                </div>
                                <div class="col-6">
                                    <strong>Type:</strong><br>
                                    @if($video->is_intro)
                                        <span class="badge badge-info">Intro Video</span>
                                    @else
                                        <span class="badge badge-warning">Paid Video</span>
                                    @endif
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-6">
                                    <strong>Views:</strong><br>
                                    <span class="text-info">{{ number_format($video->views_count) }}</span>
                                </div>
                                <div class="col-6">
                                    <strong>Purchases:</strong><br>
                                    <span class="text-success">{{ number_format($video->purchases_count) }}</span>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-6">
                                    <strong>Created:</strong><br>
                                    <small class="text-muted">{{ $video->created_at->format('M d, Y') }}</small>
                                </div>
                                <div class="col-6">
                                    <strong>Updated:</strong><br>
                                    <small class="text-muted">{{ $video->updated_at->format('M d, Y') }}</small>
                                </div>
                            </div>
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
    // Update custom file input labels
    $('.custom-file-input').on('change', function() {
        var fileName = $(this).val().split('\\').pop();
        if (fileName) {
            $(this).next('.custom-file-label').html(fileName);
        } else {
            // Reset to default label based on input type
            if ($(this).attr('id') === 'video_file') {
                $(this).next('.custom-file-label').html('Choose new video file (optional)');
            } else if ($(this).attr('id') === 'thumbnail') {
                $(this).next('.custom-file-label').html('Choose thumbnail image');
            }
        }
    });
    
    // Handle intro video logic
    $('#is_intro').on('change', function() {
        var isIntro = $(this).is(':checked');
        var priceInput = $('#price');
        
        if (isIntro) {
            // Intro video: set price to 0 and disable
            priceInput.val('0.00').prop('disabled', true);
            priceInput.addClass('bg-light');
            $('.price-info').html('<span class="text-success">Intro videos are free!</span>');
        } else {
            // Paid video: enable price input
            priceInput.prop('disabled', false).removeClass('bg-light');
            $('.price-info').html('<small class="text-muted">Price range: ${{ \App\Models\AdminSetting::getMinVideoPrice() }} - ${{ \App\Models\AdminSetting::getMaxVideoPrice() }}</small>');
        }
    });
    
    // Debug form submission
    $('form').on('submit', function(e) {
        console.log('Form submitted');
        console.log('Form data:', $(this).serialize());
        
        // Check if required fields are filled
        var title = $('#title').val();
        var price = $('#price').val();
        var isIntro = $('#is_intro').is(':checked');
        
        if (!title) {
            alert('Please fill in the video title');
            e.preventDefault();
            return false;
        }
        
        if (isIntro && price > 0) {
            alert('Intro videos must be free (price = $0)');
            e.preventDefault();
            return false;
        }
        
        if (!isIntro && !price) {
            alert('Please set a price for paid videos');
            e.preventDefault();
            return false;
        }
        
        console.log('Form validation passed, submitting...');
    });
});
</script>
@endpush
