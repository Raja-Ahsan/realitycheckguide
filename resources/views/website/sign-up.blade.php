@extends('layouts.website.master')
@section('title', $page_title)
@section('content')
<style>
    /* Modern, unified form style */
    .form-label {
        font-weight: 500;
        color: #1a355e;
        font-size: 1rem;
        font-family: 'Poppins', 'Roboto', Arial, sans-serif;
    }

    .form-control input-field {
        font-family: 'Poppins', 'Roboto', Arial, sans-serif;
        color: #212529;
        background-color: #fff;
        border: 1.5px solid #ced4da;
        border-radius: 0.5rem;
        padding: 0.5rem 1.6rem;
        transition: border-color 0.2s, box-shadow 0.2s;
        font-size: 1.6rem !important;
    }

    .form-control input-field:focus {
        border-color: #1a355e;
        box-shadow: 0 0 0 0.1rem rgba(26, 53, 94, 0.08);
        background-color: #fff;
        color: #212529;
    }

    .form-control input-field::placeholder {
        color: #6c757d;
        opacity: 1;
    }

    .btn-warning {
        background: #000000;
        color: #ffffff;
        border: none;
        border-radius: 2rem;
        font-size: 1.1rem;
        font-weight: 600;
        letter-spacing: 1px;
        transition: background 0.2s, color 0.2s;
    }

    .btn-warning:hover,
    .btn-warning:focus {
        background: #1a355e;
        color: #fff;
    }

    #card-element {
        background: #fff;
        border: 1px solid #ced4da;
        border-radius: .375rem;
        padding: 10px 12px;
    }

    .input-field {
        border: 1px solid #ffc107;
        padding: var(--text-10) var(--text-30);
    }

    .btn {
        padding: var(--text-15) var(--text-30) !important;
        font-size: var(--text-18) !important;

    }

    h2.fs-40 {
        font-size: 2rem !important;
    }

    .paddings {
        padding: 50px 0;
    }

    .signup-form {
        background: linear-gradient(315deg, #ffc430, #ffffff);
        box-shadow: 5px 5px 20px 0px rgb(0 0 0 / 21%) !important;
    }
    
    /* Role selection styling */
    #role-info .alert {
        border-left: 4px solid #1a355e;
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    }
    
    #role-info ul {
        padding-left: 1.2rem;
    }
    
    #role-info li {
        margin-bottom: 0.5rem;
        color: #495057;
    }
    
    .form-label {
        font-weight: 600;
        color: #1a355e;
        margin-bottom: 0.5rem;
    }

    @media (max-width: 767.98px) {
        .card {
            padding: 1.5rem !important;
        }

        .signup-form {
            padding: 1rem 0;
        }

        .form-label,
        .form-control input-field {
            font-size: 0.95rem;
        }
    }
</style>

<!-- Banner Section -->
<section class="inner-banner registration-banner"
    style="margin-top: 80px; height: 200px; background-size: cover; 
           background-image: url('{{ !empty($banner->image) 
                ? asset('public/admin/assets/images/banner/' . $banner->image) 
                : asset('public/admin/assets/images/images.png') }}'); 
           width:100%;">
</section>

<!-- Signup Form Section -->
<section class="paddings" id="signup-form">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-7">
                <div class="signup-form shadow-lg border-0 rounded-4 p-4 p-md-5" data-aos="flip-left" data-aos-easing="linear"
                    data-aos-duration="1500">
                    <h2 class="mb-4 text-center heading text-uppercase fw-bold text-black fs-40"><span>Join Reality Check Guide</span></h2>
                    <p class="text-center text-muted mb-4">Share your career journey or discover real-life job stories from professionals</p>
                    <form method="POST" action="{{ route('user.register.store') }}" id="subscription-form" enctype="multipart/form-data">
                        @csrf
                        <!-- Hidden Inputs -->
                        @php
                        // Use roles passed from controller
                        $availableRoles = $roles ?? collect();
                        @endphp
                        <input type="hidden" name="amount" value="{{ $package->price ?? 0 }}">
                        <input type="hidden" name="package_id" value="{{ $package->id }}">
                        <input type="hidden" name="package_description" value="{{ $package->description }}">
                        <div class="row g-3">
                            <!-- First Name -->
                            <div class="col-12 col-md-6">
                                <input type="text" class="form-control fs-16 @error('name') is-invalid @enderror" name="name" id="input1" placeholder="First Name" value="{{ old('name') }}" required>
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <!-- Last Name -->
                            <div class="col-12 col-md-6">
                                <input type="text" class="form-control  fs-16 @error('last_name') is-invalid @enderror" name="last_name" id="input2" placeholder="Last Name" value="{{ old('last_name') }}" required>
                                @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <!-- Phone -->
                            <div class="col-12 col-md-6">
                                <input type="text" class="form-control  fs-16 @error('phone') is-invalid @enderror" name="phone" id="input4" placeholder="Phone" value="{{ old('phone') }}" required>
                                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <!-- Email -->
                            <div class="col-12 col-md-6">
                                <input type="email" class="form-control  fs-16 @error('email') is-invalid @enderror" name="email" id="input3" placeholder="Email" value="{{ old('email') }}" required>
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <!-- Password -->
                            <div class="col-12 col-md-6">
                                <input type="password" class="form-control  fs-16 @error('password') is-invalid @enderror" name="password" id="input5" placeholder="Password" required>
                                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <!-- Confirm Password -->
                            <div class="col-12 col-md-6">
                                <input type="password" class="form-control  fs-16 @error('password_confirmation') is-invalid @enderror" name="password_confirmation" id="input6" placeholder="Confirm Password" required>
                                @error('password_confirmation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <!-- Role Selection -->
                            <div class="col-12 col-md-12">
                                <label for="role" class="form-label">Select Your Role</label>
                                <select name="role" id="role" class="form-control fs-16 @error('role') is-invalid @enderror" required onchange="showRoleInfo()">
                                    <option value="">Choose your role...</option>
                                    @if($availableRoles && $availableRoles->count() > 0)
                                        @foreach($availableRoles as $role)
                                            <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    @else
                                        <!-- Fallback if no roles are loaded -->
                                        <option value="Viewer">Viewer</option>
                                        <option value="Creator">Creator (Contributor)</option>
                                    @endif
                                </select>
                                @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                
                                <!-- Role Information -->
                                <div id="role-info" class="mt-2" style="display: none;">
                                    <div class="alert alert-info p-3 rounded-3">
                                        <div id="viewer-info" style="display: none;">
                                            <strong>Viewer Role:</strong>
                                            <ul class="mb-0 mt-2">
                                                <li>Browse & watch videos</li>
                                                <li>Follow categories</li>
                                                <li>Favorite / save videos</li>
                                                <li>Request specific career videos</li>
                                                <li>Optional account for watch history & recommendations</li>
                                            </ul>
                                        </div>
                                        <div id="creator-info" style="display: none;">
                                            <strong>Creator Role (Contributor):</strong>
                                            <ul class="mb-0 mt-2">
                                                <li>Share real-life job stories</li>
                                                <li>Upload videos & add descriptions</li>
                                                <li>View content analytics</li>
                                                <li>Limited dashboard for content management</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Payment Amount & Stripe -->
                            @if($package->price > 0)
                            <div class="col-12">
                                <div class="alert alert-info text-center mb-2 rounded-3" style="background:#e3f6fc; color:#1a355e;">
                                    <strong>Package:</strong> {{ $package->title }}<br>
                                    <strong> Payment Amount:</strong> ${{ $package->price }} {{ $package->period }}
                                </div>
                                <div class="mb-3 p-3 border rounded bg-light" id="card-element"></div>
                            </div>
                            @else
                            <div class="col-12">
                                <div class="alert alert-success text-center mb-2 rounded-3" style="background:#d4edda; color:#155724;">
                                    <strong>Free Registration!</strong><br>
                                    <small>No payment required. Start sharing your career journey today!</small>
                                </div>
                            </div>
                            @endif
                            <!-- Terms and Conditions -->
                            <div class="col-12 d-flex align-items-center flex-wrap">
                                <input type="checkbox" class="form-check-input me-2" name="terms" id="Check1" required>
                                <label class="form-check-label text-black" for="Check1">
                                    I agree to the <a href="#" class="text-decoration-underline" style="color: #075eff">Terms & Conditions</a> and <a href="#" class="text-decoration-underline" style="color: #075eff">Privacy Policy</a>
                                </label>
                                @error('terms') <div class="invalid-feedback d-block ms-2">{{ $message }}</div> @enderror
                            </div>
                            <!-- Payment Methods Image -->
                            @if($package->price != 0)
                            <div class="col-12 text-end">
                                <img src="{{ asset('public/assets/website/images/stripe_secure.png') }}" alt="Pay-methods" class="img-fluid" style="width:140px;">
                            </div>
                            @endif
                            <!-- Submit Button -->
                            <div class="col-12 mt-3">
                                <button type="submit" class="btn btn-warning w-100 py-3 fs-18 fw-bold text-uppercase" id="register-btn">
                                    <span id="register-spinner" class="spinner-border spinner-border-sm d-none me-2" role="status"></span>
                                    Join Now
                                </button>
                            </div>
                        </div>
                        @if($errors->has('error'))
                        <div class="alert alert-danger mt-3">{{ $errors->first('error') }}</div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@if($package->price > 0)
<script src="https://js.stripe.com/v3/"></script>
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Stripe only for paid packages
        var stripe = Stripe("{{ config('services.stripe.key') }}");
        var elements = stripe.elements();
        var card = elements.create('card');
        card.mount('#card-element');

        // Handle form submission for paid packages
        var form = document.getElementById('subscription-form');
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            document.getElementById('register-btn').disabled = true;
            document.getElementById('register-spinner').classList.remove('d-none');
            stripe.createToken(card).then(function(result) {
                if (result.error) {
                    alert(result.error.message);
                    document.getElementById('register-btn').disabled = false;
                    document.getElementById('register-spinner').classList.add('d-none');
                } else {
                    stripeTokenHandler(result.token);
                }
            });
        });

        function stripeTokenHandler(token) {
            var hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'stripeToken');
            hiddenInput.setAttribute('value', token.id);
            form.appendChild(hiddenInput);
            form.submit();
        }
    });
</script>
@else
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        // Handle form submission for free registration
        var form = document.getElementById('subscription-form');
        form.addEventListener('submit', function() {
            document.getElementById('register-btn').disabled = true;
            document.getElementById('register-spinner').classList.remove('d-none');
        });
    });
</script>
@endif
<script>
    // Role information display
    function showRoleInfo() {
        const roleSelect = document.getElementById('role');
        const roleInfo = document.getElementById('role-info');
        const viewerInfo = document.getElementById('viewer-info');
        const creatorInfo = document.getElementById('creator-info');
        
        if (roleSelect.value) {
            roleInfo.style.display = 'block';
            if (roleSelect.value === 'Viewer') {
                viewerInfo.style.display = 'block';
                creatorInfo.style.display = 'none';
            } else if (roleSelect.value === 'Creator') {
                creatorInfo.style.display = 'block';
                viewerInfo.style.display = 'none';
            }
        } else {
            roleInfo.style.display = 'none';
        }
    }
    
    // Form validation
    document.getElementById('subscription-form').addEventListener('submit', function(e) {
        const roleSelect = document.getElementById('role');
        
        if (!roleSelect.value) {
            e.preventDefault();
            alert('Please select a role before submitting.');
            roleSelect.focus();
            return false;
        }
    });
    
    // Show role info on page load if role is pre-selected
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role');
        if (roleSelect.value) {
            showRoleInfo();
        }
    });
</script>