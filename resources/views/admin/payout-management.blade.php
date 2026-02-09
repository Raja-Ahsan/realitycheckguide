@extends('layouts.admin.app')

@section('title', $page_title)

@section('content')
    <section class="content-header">
        <h1>{{ $page_title }}</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">{{ $page_title }}</li>
        </ol>
    </section>

    <section class="content">
        <!-- Pending Payouts -->
        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title">Pending Payouts ({{ $pendingPayouts->count() }})</h3>
            </div>
            <div class="box-body">
                @if($pendingPayouts->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Creator</th>
                                    <th>Amount</th>
                                    <th>Requested Date</th>
                                    <th>Wallet Balance</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingPayouts as $payout)
                                <tr>
                                    <td>
                                        <strong>{{ $payout->creator->name }}</strong><br>
                                        <small>{{ $payout->creator->email }}</small>
                                    </td>
                                    <td>${{ number_format($payout->amount, 2) }}</td>
                                    <td>{{ $payout->created_at->format('M d, Y H:i') }}</td>
                                    <td>${{ number_format($payout->wallet->balance, 2) }}</td>
                                    <td>
                                        <button type="button" class="btn btn-success btn-sm" 
                                                onclick="processPayout({{ $payout->id }}, 'approve')">
                                            <i class="fa fa-check"></i> Approve
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm" 
                                                onclick="processPayout({{ $payout->id }}, 'reject')">
                                            <i class="fa fa-times"></i> Reject
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center">
                        <p class="text-muted">No pending payouts at the moment.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Completed Payouts -->
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Completed Payouts</h3>
            </div>
            <div class="box-body">
                @if($completedPayouts->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Creator</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Processed Date</th>
                                    <th>Admin Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($completedPayouts as $payout)
                                <tr>
                                    <td>
                                        <strong>{{ $payout->creator->name }}</strong><br>
                                        <small>{{ $payout->creator->email }}</small>
                                    </td>
                                    <td>${{ number_format($payout->amount, 2) }}</td>
                                    <td>
                                        @if($payout->status === 'completed')
                                            <span class="label label-success">Completed</span>
                                        @elseif($payout->status === 'rejected')
                                            <span class="label label-danger">Rejected</span>
                                        @else
                                            <span class="label label-warning">{{ ucfirst($payout->status) }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $payout->updated_at->format('M d, Y H:i') }}</td>
                                    <td>{{ $payout->admin_notes ?: 'No notes' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="text-center">
                        {{ $completedPayouts->links() }}
                    </div>
                @else
                    <div class="text-center">
                        <p class="text-muted">No completed payouts yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </section>


<!-- Process Payout Modal -->
<div class="modal fade" id="processPayoutModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Process Payout</h4>
            </div>
            <form id="processPayoutForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="admin_notes">Admin Notes (Optional)</label>
                        <textarea class="form-control" id="admin_notes" name="admin_notes" rows="3" 
                                  placeholder="Add any notes about this payout decision..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Confirm</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
function processPayout(payoutId, action) {
    var form = $('#processPayoutForm');
    var modal = $('#processPayoutModal');
    
    // Add action field to form
    if (form.find('input[name="action"]').length === 0) {
        form.append('<input type="hidden" name="action" value="' + action + '">');
    } else {
        form.find('input[name="action"]').val(action);
    }
    
    // Update form action
    form.attr('action', '{{ route("admin.payout.process", ":id") }}'.replace(':id', payoutId));
    
    // Update modal title
    modal.find('.modal-title').text(action === 'approve' ? 'Approve Payout' : 'Reject Payout');
    
    // Show modal
    modal.modal('show');
}

$(document).ready(function() {
    // Handle form submission
    $('#processPayoutForm').on('submit', function(e) {
        e.preventDefault();
        
        var form = $(this);
        var submitBtn = form.find('button[type="submit"]');
        var originalText = submitBtn.text();
        
        // Disable button and show loading
        submitBtn.prop('disabled', true).text('Processing...');
        
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                // Close modal
                $('#processPayoutModal').modal('hide');
                
                // Show success message
                toastr.success('Payout processed successfully!');
                
                // Reload page after short delay
                setTimeout(function() {
                    location.reload();
                }, 1000);
            },
            error: function(xhr) {
                var errorMessage = 'An error occurred while processing the payout.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                toastr.error(errorMessage);
            },
            complete: function() {
                // Re-enable button
                submitBtn.prop('disabled', false).text(originalText);
            }
        });
    });
});
</script>
@endpush
