@extends('layouts.admin.app')

@section('content')
<div class="container-fluid">
    <h3>Edit: {{ $submittal->title }}</h3>
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-header">Sections</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('submittals.breakout', $submittal->id) }}">
                        @csrf
                        <div id="sections-wrapper">
                            @foreach($submittal->sections as $idx => $section)
                                <div class="border p-2 mb-2">
                                    <div class="row g-2">
                                        <div class="col-md-3">
                                            <input name="sections[{{ $idx }}][spec_section]" class="form-control" value="{{ $section->spec_section }}" placeholder="Spec Section" />
                                        </div>
                                        <div class="col-md-4">
                                            <input name="sections[{{ $idx }}][title]" class="form-control" value="{{ $section->title }}" placeholder="Title" />
                                        </div>
                                        <div class="col-md-3">
                                            <input name="sections[{{ $idx }}][manufacturer]" class="form-control" value="{{ $section->manufacturer }}" placeholder="Manufacturer" />
                                        </div>
                                        <div class="col-md-2">
                                            <input name="sections[{{ $idx }}][product_type]" class="form-control" value="{{ $section->product_type }}" placeholder="Type" />
                                        </div>
                                    </div>
                                    <div class="form-check mt-2">
                                        <input type="checkbox" class="form-check-input" name="sections[{{ $idx }}][included]" value="1" {{ $section->included ? 'checked' : '' }} />
                                        <label class="form-check-label">Include in submittal</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <button class="btn btn-primary" type="submit">Save Sections</button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">Status</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('submittals.status', $submittal->id) }}">
                        @csrf
                        <div class="row g-2">
                            <div class="col-md-4">
                                <select class="form-control" name="status">
                                    @foreach(['pending','sent','approved','revise_resubmit','rejected'] as $s)
                                        <option value="{{ $s }}" {{ $submittal->status === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ', $s)) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <input class="form-control" name="comment" placeholder="Comment (optional)" />
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-secondary w-100" type="submit">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-header">Vendor + Received</div>
                <div class="card-body">
                    <form class="mb-2" method="POST" action="{{ route('submittals.reminder', $submittal->id) }}">
                        @csrf
                        <div class="row g-2 align-items-center">
                            <div class="col-md-4">
                                <input type="datetime-local" name="remind_at" class="form-control" value="{{ $submittal->remind_at ? $submittal->remind_at->format('Y-m-d\TH:i') : '' }}" />
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-outline-secondary w-100">Set Reminder</nbutton>
                            </div>
                        </div>
                    </form>
                    <form class="mb-2" method="POST" action="{{ route('submittals.received', $submittal->id) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-2 align-items-center">
                            <div class="col-md-6">
                                <input type="file" name="received_file" class="form-control" />
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="comment" class="form-control" placeholder="Comment" />
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-outline-primary w-100">Mark Received</button>
                            </div>
                        </div>
                    </form>
                    <form method="POST" action="{{ route('submittals.send-vendor', $submittal->id) }}">
                        @csrf
                        <div class="row g-2 align-items-center">
                            <div class="col-md-4">
                                <input type="email" name="vendor_email" class="form-control" placeholder="vendor@example.com" value="{{ $submittal->vendor_email }}" />
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="body" class="form-control" placeholder="Email body (optional)" />
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-outline-warning w-100">Send to Vendor</button>
                            </div>
                        </div>
                    </form>
                    <form class="mt-2" method="POST" action="{{ route('submittals.vendor-returned', $submittal->id) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-2 align-items-center">
                            <div class="col-md-6">
                                <input type="file" name="vendor_file" class="form-control" />
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="comment" class="form-control" placeholder="Comment" />
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-outline-success w-100">Vendor Returned</button>
                            </div>
                        </div>
                    </form>
                    <form class="mt-2" method="POST" action="{{ route('submittals.extract-comments', $submittal->id) }}">
                        @csrf
                        <div class="row g-2 align-items-center">
                            <div class="col-md-10">
                                <input type="text" name="comments" class="form-control" placeholder="Paste/enter extracted comments" />
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-outline-dark w-100">Log Comments</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header">Upload Specs</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('submittals.upload-spec', $submittal->id) }}" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="spec_file" class="form-control mb-2" required />
                        <button class="btn btn-outline-primary" type="submit">Upload</button>
                    </form>
                    <ul class="mt-2">
                        @foreach($specs as $s)
                            <li>{{ $s->original_filename }} ({{ $s->created_at->format('Y-m-d') }})</li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">Send Email</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('submittals.send-email', $submittal->id) }}">
                        @csrf
                        <input class="form-control mb-2" type="email" name="to_email" placeholder="recipient@example.com" required />
                        <button class="btn btn-outline-success" type="submit">Send</button>
                        <a class="btn btn-outline-secondary" href="{{ route('submittals.export-pdf', $submittal->id) }}">Download PDF</a>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">Timeline</div>
                <div class="card-body" style="max-height: 300px; overflow:auto;">
                    <ul class="list-unstyled">
                        @foreach($submittal->timelineEvents()->orderByDesc('happened_at')->get() as $e)
                            <li class="mb-2">
                                <strong>{{ ucfirst(str_replace('_',' ', $e->event_type)) }}</strong>
                                <div class="text-muted small">{{ $e->happened_at->format('Y-m-d H:i') }}</div>
                                @if($e->message)
                                    <div>{{ $e->message }}</div>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


