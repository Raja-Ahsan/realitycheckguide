@extends(Auth::user()->hasRole('Admin') ? 'layouts.admin.app' : (Auth::user()->hasRole('Electrician') ? 'layouts.electrician.app' : 'layouts.user.app'))
@section('title', $page_title)
@section('content')

    <section class="content-header">
        <div class="content-header-left">
            <h1>{{ $page_title }}</h1>
        </div>
        <div class="content-header-right">
            <a href="{{ route('member_directory.index') }}" class="btn btn-primary btn-sm">View All</a>
        </div>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <form action="{{ route('member_directory.update', $model->id) }}" class="form-horizontal"
                    enctype="multipart/form-data" method="post" accept-charset="utf-8">
                    @csrf
                    {{ method_field('PATCH') }}
                    <div class="box box-info">
                        <div class="box-body">

                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">Business Name<span
                                        style="color:red">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" autocomplete="off" class="form-control" name="title"
                                        value="{{ $model->title }}" required>
                                    <span style="color: red">{{ $errors->first('title') }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="category_id" class="col-sm-2 control-label">Membership Category<span
                                        style='color:red'>*</span></label>
                                <div class="col-md-9">
                                    @php
                                        $selectedCategories = is_array(json_decode($model->category_id, true))
                                            ? json_decode($model->category_id, true)
                                            : [];
                                    @endphp
                                    <select name="category_id[]" id="category_id" class="form-control" multiple required>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ in_array($category->id, $selectedCategories) ? 'selected' : '' }}>
                                                {{ $category->title }}</option>
                                        @endforeach
                                    </select>
                                    <span style="color: red">{{ $errors->first('category_id') }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">Address<span
                                        style='color:red'></span></label>
                                <div class="col-sm-9">
                                    <input type="text" autocomplete="off" class="form-control" name="address"
                                        value="{{ $model->address }}" placeholder="Enter company address">
                                    <span style="color: red">{{ $errors->first('address') }}</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">Company Logo<span
                                        style="color:red">*</span></label>
                                <div class="col-sm-6" style="padding-top:5px">
                                    <input type="file" class="form-control" accept="image/*" name="image"
                                        id="image">
                                    <span style="color: red">{{ $errors->first('image') }}</span>
                                </div>
                                <div class="col-sm-4">
                                    <img style="width: 80px" id="banner_preview"
                                        src="{{ !empty($model->image) ? asset('public/admin/assets/images/member_directory/' . $model->image) : asset('public/admin/assets/images/default.jpg') }}"
                                        alt="Image Preview">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">Name of Contact<span
                                        style="color:red">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" autocomplete="off" class="form-control" name="name"
                                        value="{{ $model->name }}" required>
                                    <span style="color: red">{{ $errors->first('name') }}</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">Phone Number<span
                                        style="color:red">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" autocomplete="off" class="form-control" name="phone_no"
                                        value="{{ $model->phone_no }}" required>
                                    <span style="color: red">{{ $errors->first('phone_no') }}</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">Website URL</label>
                                <div class="col-sm-9">
                                    <input type="text" autocomplete="off" class="form-control" name="url"
                                        value="{{ $model->url }}">
                                    <span style="color: red">{{ $errors->first('url') }}</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">Email <span
                                        style="color:red"></span></label>
                                <div class="col-sm-9">
                                    <input type="email" autocomplete="off" class="form-control" name="email"
                                        value="{{ $model->email }}">
                                    <span style="color: red">{{ $errors->first('email') }}</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">Facebook URL</label>
                                <div class="col-sm-9">
                                    <input type="text" autocomplete="off" class="form-control" name="facebook"
                                        value="{{ $model->facebook }}">
                                    <span style="color: red">{{ $errors->first('facebook') }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">Description of Company and
                                    Services</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" name="description" style="height:140px;">{{ old('description', $model->description) }}</textarea>
                                    <span style="color: red">{{ $errors->first('description') }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="hear_about_us" class="col-sm-2 control-label">How did you hear about us?<span
                                        style='color:red'></span></label>
                                <div class="col-sm-9">
                                    <input type="text" autocomplete="off" id="hear_about_us" class="form-control"
                                        name="hear_about_us" value="{{ old('hear_about_us', $model->hear_about_us) }}"
                                        placeholder="Enter how you heard about us">
                                    <span style="color: red">{{ $errors->first('hear_about_us') }}</span>
                                </div>
                            </div>
                            @if (Auth::user()->hasRole('Admin'))
                                <div class="form-group">
                                    <label for="" class="col-sm-2 control-label">Status</label>
                                    <div class="col-sm-8">
                                        <select name="status" class="form-control" id="status">
                                            <option value="pending" {{ $model->status == 'pending' ? 'selected' : '' }}>
                                                Pending</option>
                                            <option value="approved" {{ $model->status == 'approved' ? 'selected' : '' }}>
                                                Approved</option>
                                            <option value="rejected" {{ $model->status == 'rejected' ? 'selected' : '' }}>
                                                Rejected</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group" id="rejection-reason-group"
                                    style="display: {{ $model->status == 'rejected' ? 'block' : 'none' }};">
                                    <label for="rejection_reason" class="col-sm-2 control-label">Rejection Reason <span style="color:red">*</span></label>
                                    <div class="col-sm-8">
                                        <textarea name="rejection_reason" id="rejection_reason" class="form-control" rows="3" {{ $model->status == 'rejected' ? 'required' : '' }}>{{ old('rejection_reason', $model->rejection_reason ?? '') }}</textarea>
                                    </div>
                                </div>
                            @endif
                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label"></label>
                                <div class="col-sm-6">
                                    <button type="submit" class="btn btn-success pull-left">Update</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

@endsection
@push('js')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categoryChoices = new Choices('#category_id', {
                removeItemButton: true,
                maxItemCount: 3, // Optional: limit selection to 3 categories
                searchResultLimit: 5,
                placeholderValue: 'Select up to 3 categories',
                noResultsText: 'No results found',
                noChoicesText: 'No choices to choose from'
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            image.onchange = evt => {
                const [file] = image.files
                if (file) {
                    banner_preview.src = URL.createObjectURL(file)
                }
            }
            $('#status').on('change', function() {
                if ($(this).val() == 'rejected') {
                    $('#rejection-reason-group').show();
                } else {
                    $('#rejection-reason-group').hide();
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusSelect = document.getElementById('status');
            const rejectionReasonGroup = document.getElementById('rejection-reason-group');
            const rejectionReasonTextarea = document.getElementById('rejection_reason');

            function toggleRejectionReason() {
                if (statusSelect.value === 'rejected') {
                    rejectionReasonGroup.style.display = 'block';
                    rejectionReasonTextarea.setAttribute('required', 'required');
                } else {
                    rejectionReasonGroup.style.display = 'none';
                    rejectionReasonTextarea.removeAttribute('required');
                }
            }

            statusSelect.addEventListener('change', toggleRejectionReason);

            // Initial call to set the correct state on page load
            toggleRejectionReason();
        });
    </script>
@endpush
