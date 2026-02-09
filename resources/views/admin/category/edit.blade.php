@extends('layouts.admin.app')
@section('title', $page_title)
@section('content')

<section class="content-header">
	<div class="content-header-left">
		<h1>Edit Category</h1>
	</div>
	<div class="content-header-right">
		<a href="{{ route('services.index') }}" class="btn btn-primary btn-sm">View All</a>
	</div>
</section>

<section class="content">
	<div class="row">
		<div class="col-md-12">
			<form action="{{ route('services.update', $model->slug) }}" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">
				@csrf
				{{ method_field('PATCH') }}
				<div class="box box-info">
					<div class="box-body">
                        <!-- <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Parent Category</label>
                            <div class="col-md-9">
                                <select name="parent_id" id="parent_id" class="form-control">
                                    <option value="" selected>No Parent</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->slug }}" {{ $model->parent_id == $category->slug ? 'selected':'' }}>{{ $category->title }}</option>
                                    @endforeach
                                </select>
                                <span style="color: red">{{ $errors->first('parent_id') }}</span>
                            </div>
						</div> -->
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Title <span style="color:red">*</span></label>
							<div class="col-sm-9">
								<input type="text" autocomplete="off" class="form-control" name="title" value="{{$model->title}}">
								<span style="color: red">{{ $errors->first('title') }}</span>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Subtitle</label>
							<div class="col-sm-9">
								<input type="text" autocomplete="off" class="form-control" name="subtitle" value="{{$model->subtitle ?? ''}}" placeholder="Enter category subtitle">
								<span style="color: red">{{ $errors->first('subtitle') }}</span>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Description</label>
							<div class="col-sm-9">
								<textarea class="form-control texteditor" name="description" style="height:140px;" placeholder="Enter category description">{!! $model->description !!}</textarea>
								<span style="color: red">{{ $errors->first('description') }}</span>
							</div>
						</div>
						<div class="form-group">
                            <label for="" class="col-sm-2 control-label">Image</label>
                            <div class="col-sm-6" style="padding-top:5px">
                                <input type="file" class="form-control" accept="image/*" name="image" id="image" >
                                <span style="color: red">{{ $errors->first('image') }}</span>
                            </div>
                            <div class="col-sm-4">
                                @if(!empty($model->image))
                                    <img style="width: 150px; height: 150px; object-fit: cover; border-radius: 8px;" id="banner_preview" src="{{ asset('admin/assets/images/categories') }}/{{ $model->image }}" alt="Category Image">
                                @else
                                    <img style="width: 150px; height: 150px; object-fit: cover; border-radius: 8px;" id="banner_preview" src="{{ asset('admin/assets/images/default.jpg') }}" alt="Image Preview">
                                @endif
                            </div>
                        </div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">What You'll Discover</label>
							<div class="col-sm-9">
								<div id="discover-points-container">
									@php
										$discoverPoints = !empty($model->discover_points) ? json_decode($model->discover_points, true) : [];
									@endphp
									@if(!empty($discoverPoints) && is_array($discoverPoints))
										@foreach($discoverPoints as $point)
										<div class="discover-point-item mb-2">
											<div class="input-group">
												<input type="text" class="form-control" name="discover_points[]" value="{{ $point }}" placeholder="Enter discover point">
												<button type="button" class="btn btn-danger remove-point"><i class="fa fa-times"></i></button>
											</div>
										</div>
										@endforeach
									@else
										<div class="discover-point-item mb-2">
											<div class="input-group">
												<input type="text" class="form-control" name="discover_points[]" placeholder="Enter discover point">
												<button type="button" class="btn btn-danger remove-point" style="display:none;"><i class="fa fa-times"></i></button>
											</div>
										</div>
									@endif
								</div>
								<button type="button" class="btn btn-sm btn-info mt-2" id="add-discover-point"><i class="fa fa-plus"></i> Add Another Point</button>
								<p class="help-block text-muted mt-2">Add multiple points that users will discover in this category</p>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Status</label>
							<div class="col-sm-9">
								<select name="status" class="form-control" id="">
									<option value="1" {{ $model->status==1?'selected':'' }}>Active</option>
									<option value="0" {{ $model->status==0?'selected':'' }}>In-Active</option>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label for="" class="col-sm-2 control-label"></label>
							<div class="col-sm-6">
								<button type="submit" class="btn btn-success pull-left">Submit</button>
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
<script>
	$(document).ready(function() {
		if ($(".texteditor").length > 0) {
			tinymce.init({
				selector: "textarea.texteditor",
				theme: "modern",
				height: 150,
				plugins: [
					"advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
					"searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
					"save table contextmenu directionality emoticons template paste textcolor"
				],
				toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons",
			});
		}
		
		const image = document.getElementById('image');
		const banner_preview = document.getElementById('banner_preview');
		
		if (image) {
			image.onchange = evt => {
				const [file] = image.files;
				if (file) {
					banner_preview.src = URL.createObjectURL(file);
				}
			}
		}

		// Discover Points Management
		$('#add-discover-point').on('click', function() {
			const newPoint = `
				<div class="discover-point-item mb-2">
					<div class="input-group">
						<input type="text" class="form-control" name="discover_points[]" placeholder="Enter discover point">
						<button type="button" class="btn btn-danger remove-point"><i class="fa fa-times"></i></button>
					</div>
				</div>
			`;
			$('#discover-points-container').append(newPoint);
			updateRemoveButtons();
		});

		$(document).on('click', '.remove-point', function() {
			$(this).closest('.discover-point-item').remove();
			updateRemoveButtons();
		});

		function updateRemoveButtons() {
			const items = $('.discover-point-item').length;
			if (items > 1) {
				$('.remove-point').show();
			} else {
				$('.remove-point').hide();
			}
		}

		// Initialize remove buttons visibility
		updateRemoveButtons();
	});
</script>
@endpush
