@extends('layouts.admin.app')
@section('title', $page_title)
@section('content')
<section class="content-header">
	<div class="content-header-left">
		<h1>Add Category</h1>
	</div>
	<div class="content-header-right">
		<a href="{{ route('services.index') }}" class="btn btn-primary btn-sm">View All</a>
	</div>
</section>

<section class="content">
	<div class="row">
		<div class="col-md-12">
			<form action="{{ route('services.store') }}" id="regform" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">
				@csrf
				<div class="box box-info">
					<div class="box-body">
                        <!-- <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Parent Service </label>
                           <div class="col-md-9">
                               <select name="parent_id" id="parent_id" class="form-control">
                                   <option value="0" selected> No Parent</option>
                                   @foreach ($categories as $category)
                                       <option value="{{ $category->slug }}">{{ $category->title }}</option>
                                   @endforeach
                               </select>
                               <span style="color: red">{{ $errors->first('parent_id') }}</span>
                           </div>
                        </div> -->
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Title<span style='color:red'>*</span></label>
							<div class="col-sm-9">
								<input type="text" autocomplete="off" class="form-control" name="title" value="{{ old('title') }}" placeholder="Enter category name">
								<span style="color: red">{{ $errors->first('title') }}</span>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Subtitle</label>
							<div class="col-sm-9">
								<input type="text" autocomplete="off" class="form-control" name="subtitle" value="{{ old('subtitle') }}" placeholder="Enter category subtitle (e.g., Explore real career stories)">
								<span style="color: red">{{ $errors->first('subtitle') }}</span>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Description</label>
							<div class="col-sm-9">
								<textarea class="form-control texteditor" name="description" style="height:140px;" placeholder="Enter category description">{{ old('description') }}</textarea>
								<span style="color: red">{{ $errors->first('description') }}</span>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Image</label>
                            <div class="col-sm-6" style="padding-top:5px">
                                <input type="file" class="form-control" accept="image/*"  name="image" id="image">
                                <span style="color: red">{{ $errors->first('image') }}</span>
                            </div>
                            <div class="col-sm-4" >
                                <img style="width: 150px; height: 150px; object-fit: cover; border-radius: 8px;" id="banner_preview"  src="{{ asset('admin/assets/images/default.jpg') }}"  alt="Image Preview">
                            </div>
                        </div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">What You'll Discover</label>
							<div class="col-sm-9">
								<div id="discover-points-container">
									<div class="discover-point-item mb-2">
										<div class="input-group">
											<input type="text" class="form-control" name="discover_points[]" placeholder="Enter discover point (e.g., Real job experiences)">
											<button type="button" class="btn btn-danger remove-point" style="display:none;"><i class="fa fa-times"></i></button>
										</div>
									</div>
								</div>
								<button type="button" class="btn btn-sm btn-info mt-2" id="add-discover-point"><i class="fa fa-plus"></i> Add Another Point</button>
								<p class="help-block text-muted mt-2">Add multiple points that users will discover in this category</p>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Status</label>
							<div class="col-sm-9">
								<select name="status" class="form-control">
									<option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>Active</option>
									<option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Inactive</option>
								</select>
								<span style="color: red">{{ $errors->first('status') }}</span>
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
		$("#regform").validate({
			rules: {
				title: "required"
			}
		});
	});
</script>
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
				toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons",

			});
		}
        image.onchange = evt => {
			const [file] = image.files
			if (file) {
				banner_preview.src = URL.createObjectURL(file)
			}
		}

		// Discover Points Management
		let pointCount = 1;
		$('#add-discover-point').on('click', function() {
			pointCount++;
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

	});
</script>
@endpush
