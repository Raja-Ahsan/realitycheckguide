@extends('layouts.creator.app')

@section('title', 'Pricing Rules')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Pricing Rules</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('creator.dashboard') }}">Creator Dashboard</a></li>
                        <li class="breadcrumb-item active">Pricing Rules</li>
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
                                <i class="fas fa-cog"></i> Current Pricing Rules
                            </h3>
                        </div>
                        <div class="card-body">
                            @if($pricingRules)
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Minimum Price Floor</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">$</span>
                                                </div>
                                                <input type="number" class="form-control" value="{{ $pricingRules->min_price_floor }}" 
                                                       step="0.01" min="0.01" max="999.99" id="min_price_floor">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Maximum Price Cap</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">$</span>
                                                </div>
                                                <input type="number" class="form-control" value="{{ $pricingRules->max_price_cap }}" 
                                                       step="0.01" min="0.01" max="999.99" id="max_price_cap">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Videos Sold Threshold</label>
                                            <input type="number" class="form-control" value="{{ $pricingRules->videos_sold_threshold }}" 
                                                   min="1" max="100" id="videos_sold_threshold">
                                            <small class="form-text text-muted">Number of videos you need to sell to unlock custom pricing</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Status</label>
                                            <div class="form-control-plaintext">
                                                @if($pricingRules->custom_pricing_enabled)
                                                    <span class="badge badge-success">Custom Pricing Unlocked!</span>
                                                @else
                                                    <span class="badge badge-warning">Custom Pricing Locked</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="button" class="btn btn-primary" onclick="updatePricingRules()">
                                        <i class="fas fa-save"></i> Update Pricing Rules
                                    </button>
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    <h6><i class="fas fa-exclamation-triangle"></i> No Pricing Rules Found</h6>
                                    <p class="mb-0">Contact admin to set up your pricing rules.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-info-circle"></i> Pricing Information
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <h6><i class="fas fa-lightbulb"></i> How It Works</h6>
                                <ul class="mb-0">
                                    <li>New creators start with limited pricing range</li>
                                    <li>Sell {{ $pricingRules ? $pricingRules->videos_sold_threshold : 15 }} videos to unlock custom pricing</li>
                                    <li>Set your own prices within the allowed range</li>
                                    <li>Maximum price cap prevents abuse</li>
                                </ul>
                            </div>

                            <div class="alert alert-success">
                                <h6><i class="fas fa-chart-line"></i> Your Progress</h6>
                                <p class="mb-1"><strong>Videos Sold:</strong> {{ auth()->user()->getTotalVideosSoldAttribute() }}</p>
                                @if($pricingRules)
                                    <p class="mb-1"><strong>Threshold:</strong> {{ $pricingRules->videos_sold_threshold }}</p>
                                    @if(!$pricingRules->custom_pricing_enabled)
                                        <p class="mb-0"><strong>Remaining:</strong> {{ $pricingRules->videos_sold_threshold - auth()->user()->getTotalVideosSoldAttribute() }}</p>
                                    @endif
                                @endif
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
function updatePricingRules() {
    let minPrice = parseFloat($('#min_price_floor').val());
    let maxPrice = parseFloat($('#max_price_cap').val());
    let threshold = parseInt($('#videos_sold_threshold').val());
    
    if (minPrice >= maxPrice) {
        alert('Minimum price must be less than maximum price.');
        return;
    }
    
    if (threshold < 1) {
        alert('Threshold must be at least 1.');
        return;
    }
    
    // Here you would make an AJAX call to update the pricing rules
    // For now, just show a success message
    alert('Pricing rules updated successfully!');
}
</script>
@endpush
