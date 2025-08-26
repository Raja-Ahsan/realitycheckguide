@extends('layouts.website.master')

@section('title', 'Purchase ' . $video->title)

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-md-8">
            <!-- Video Preview -->
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-video"></i> {{ $video->title }}</h4>
                </div>
                <div class="card-body">
                    @if($video->thumbnail_path)
                        <img src="{{ asset('storage/app/public/' . $video->thumbnail_path) }}" alt="{{ $video->title }}" class="img-fluid mb-3">
                    @endif
                    
                    <div class="video-info">
                        <p class="text-muted">
                            <i class="fas fa-user"></i> {{ $video->creator->name }} |
                            <i class="fas fa-clock"></i> {{ $video->duration ? gmdate('i:s', $video->duration) : 'N/A' }} |
                            <i class="fas fa-eye"></i> {{ number_format($video->views_count ?? 0) }} views
                        </p>
                        
                        <div class="description mb-3">
                            <h6>Description:</h6>
                            <p>{{ $video->description }}</p>
                        </div>

                        @if($video->learning_objectives)
                            <div class="learning-objectives mb-3">
                                <h6>Learning Objectives:</h6>
                                <p>{{ $video->learning_objectives }}</p>
                            </div>
                        @endif

                        @if($video->prerequisites)
                            <div class="prerequisites mb-3">
                                <h6>Prerequisites:</h6>
                                <p>{{ $video->prerequisites }}</p>
                            </div>
                        @endif

                        <div class="tags mb-3">
                            <h6>Tags:</h6>
                            @if($video->tags && count($video->tags) > 0)
                                @foreach($video->tags as $tag)
                                    <span class="badge badge-secondary mr-1">{{ $tag }}</span>
                                @endforeach
                            @else
                                <span class="text-muted">No tags</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Purchase Card -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-shopping-cart"></i> Purchase Video</h5>
                </div>
                <div class="card-body">
                    <div class="pricing-info mb-4">
                        <div class="text-center">
                            <h3 class="text-primary">${{ number_format($video->price, 2) }}</h3>
                            <p class="text-muted">One-time purchase</p>
                        </div>
                        
                        <div class="features-list">
                            <h6>What you get:</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success"></i> Lifetime access to this video</li>
                                <li><i class="fas fa-check text-success"></i> Download option (if enabled)</li>
                                <li><i class="fas fa-check text-success"></i> HD quality video</li>
                                <li><i class="fas fa-check text-success"></i> Mobile and desktop compatible</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Commission Info -->
                    <div class="commission-info mb-3">
                        <div class="alert alert-info">
                            <small>
                                <i class="fas fa-info-circle"></i> 
                                Platform commission: {{ $commissionRate }}% 
                                (Creator earns: ${{ number_format($earnings['creator_earning'], 2) }})
                            </small>
                        </div>
                    </div>

                    <!-- Payment Form -->
                    <form id="payment-form">
                        <div class="form-group">
                            <label for="card-element">Credit or debit card</label>
                            <div id="card-element" class="form-control">
                                <!-- Stripe Elements will create input elements here -->
                            </div>
                            <div id="card-errors" class="text-danger mt-2" role="alert"></div>
                        </div>

                        <button type="submit" class="btn btn-success btn-lg btn-block" id="submit-button">
                            <i class="fas fa-lock"></i> Pay ${{ number_format($video->price, 2) }}
                        </button>
                    </form>

                    <div class="security-info mt-3">
                        <small class="text-muted">
                            <i class="fas fa-shield-alt"></i> 
                            Your payment information is secure and encrypted by Stripe.
                        </small>
                    </div>
                </div>
            </div>

            <!-- Creator Info -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-user"></i> About the Creator</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        @if($video->creator->profile_photo_path)
                            <img src="{{ asset('storage/' . $video->creator->profile_photo_path) }}" 
                                 alt="{{ $video->creator->name }}" 
                                 class="rounded-circle mr-3" style="width: 50px; height: 50px;">
                        @else
                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mr-3" 
                                 style="width: 50px; height: 50px;">
                                <i class="fas fa-user"></i>
                            </div>
                        @endif
                        
                        <div>
                            <h6 class="mb-1">{{ $video->creator->name }}</h6>
                            <p class="text-muted mb-0">
                                {{ $video->creator->videos()->count() }} videos available
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <h5>Processing Payment...</h5>
                <p class="text-muted">Please wait while we complete your purchase.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
// Initialize Stripe
const stripe = Stripe('{{ config("services.stripe.key") }}');
const elements = stripe.elements();

// Create card element
const card = elements.create('card', {
    style: {
        base: {
            fontSize: '16px',
            color: '#424770',
            '::placeholder': {
                color: '#aab7c4',
            },
        },
        invalid: {
            color: '#9e2146',
        },
    },
});

card.mount('#card-element');

// Handle form submission
const form = document.getElementById('payment-form');
const submitButton = document.getElementById('submit-button');

form.addEventListener('submit', async (event) => {
    event.preventDefault();
    
    // Disable submit button
    submitButton.disabled = true;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
    
    // Show loading modal
    $('#loadingModal').modal('show');
    
    try {
        // Create payment method
        const {paymentMethod, error} = await stripe.createPaymentMethod({
            type: 'card',
            card: card,
        });
        
        if (error) {
            throw new Error(error.message);
        }
        
        // Send payment method to server
        const response = await fetch('{{ route("videos.process-payment", $video) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({
                payment_method_id: paymentMethod.id,
            }),
        });
        
        const result = await response.json();
        
        if (result.error) {
            throw new Error(result.error);
        }
        
        // Check if payment requires confirmation
        if (result.payment_intent.status === 'requires_confirmation') {
            // Confirm payment
            const {error: confirmError} = await stripe.confirmCardPayment(result.payment_intent.client_secret);
            
            if (confirmError) {
                throw new Error(confirmError.message);
            }
        }
        
        // Payment successful - redirect to video
        window.location.href = '{{ route("videos.show", $video) }}';
        
    } catch (error) {
        // Hide loading modal
        $('#loadingModal').modal('hide');
        
        // Re-enable submit button
        submitButton.disabled = false;
        submitButton.innerHTML = '<i class="fas fa-lock"></i> Pay ${{ number_format($video->price, 2) }}';
        
        // Show error
        const errorElement = document.getElementById('card-errors');
        errorElement.textContent = error.message;
        
        // Scroll to error
        errorElement.scrollIntoView({ behavior: 'smooth' });
    }
});

// Handle card errors
card.addEventListener('change', ({error}) => {
    const displayError = document.getElementById('card-errors');
    if (error) {
        displayError.textContent = error.message;
    } else {
        displayError.textContent = '';
    }
});
</script>
@endpush

@push('styles')
<style>
.video-info .description,
.video-info .learning-objectives,
.video-info .prerequisites {
    border-left: 3px solid #007bff;
    padding-left: 15px;
}

.features-list ul li {
    margin-bottom: 8px;
}

.features-list ul li i {
    margin-right: 8px;
}

#card-element {
    min-height: 40px;
    padding: 10px 12px;
    border: 1px solid #ced4da;
    border-radius: 4px;
    background-color: white;
}

#card-errors {
    font-size: 14px;
}

.commission-info .alert {
    font-size: 12px;
    padding: 8px 12px;
}

.security-info {
    border-top: 1px solid #dee2e6;
    padding-top: 15px;
}
</style>
@endpush
