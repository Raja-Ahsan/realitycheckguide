@extends((Auth::user()->hasRole('Admin') ? 'layouts.admin.app' :  (Auth::user()->hasRole('Electrician') ? 'layouts.electrician.app' : 'layouts.user.app')))
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
			<form action="{{ route('member_directory.store') }}" id="regform" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">
				@csrf
				<div class="box box-info">
					<div class="box-body">
						<div class="form-group">
							<label for="title" class="col-sm-2 control-label">Business Name<span style='color:red'>*</span></label>
							<div class="col-sm-9">
								<input type="text" id="title" autocomplete="off" class="form-control" name="title" placeholder="Enter company name" required>
								<span style="color: red">{{ $errors->first('title') }}</span>
							</div>
						</div> 
						<div class="form-group">
							<label for="category_id" class="col-sm-2 control-label">Membership Category<span style='color:red'>*</span></label>
							<div class="col-md-9">
								<select name="category_id[]" id="category_id" class="form-control" multiple required>
									@foreach ($categories as $category)
									<option value="{{ $category->id }}" {{ collect(old('category_id'))->contains($category->id) ? 'selected' : '' }}>
										{{ $category->title }}
									</option>
									@endforeach
								</select>
								<span style="color: red">{{ $errors->first('category_id') }}</span>
							</div>
						</div>
						<div class="form-group">
							<label for="company_address" class="col-sm-2 control-label">Address<span style='color:red'></span></label>
							<div class="col-sm-9">
								<input type="text" id="company_address" autocomplete="off" class="form-control" name="address" placeholder="Enter company address">
								<span style="color: red">{{ $errors->first('address') }}</span>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Company Logo<span style="color: red">*</span></label>
							<div class="col-sm-6" style="padding-top:5px">
								<input type="file" class="form-control" accept="image*"  name="image" id="image" required>
								<span style="color: red">{{ $errors->first('image') }}</span>
							</div>
							<div class="col-sm-4" >
								<img style="width: 80px" id="banner_preview"  src="{{ asset('public/admin/assets/images/default.jpg') }}"  alt="Image Not Found ">
							</div>
						</div>
						<div class="form-group">
							<label for="name" class="col-sm-2 control-label">Name of Contact<span style='color:red'>*</span></label>
							<div class="col-sm-9">
								<input type="text" id="name" autocomplete="off" class="form-control" name="name" value="" placeholder="Enter Key Person" required>
								<span style="color: red">{{ $errors->first('name') }}</span>
							</div>
						</div>
						<div class="form-group">
							<label for="phone_no" class="col-sm-2 control-label">Phone Number<span style='color:red'>*</span></label>
							<div class="col-sm-9">
								<input type="tel" autocomplete="off" id="phone_no" class="form-control" name="phone_no" value="" placeholder="Enter phone number" required>
								<span style="color: red">{{ $errors->first('phone_no') }}</span>
							</div>
						</div>
						<div class="form-group">
							<label for="url" class="col-sm-2 control-label">Website URL</label>
							<div class="col-sm-9">
								<input type="text" autocomplete="off" id="url" class="form-control" name="url" value="" placeholder="Enter website url">
								<span style="color: red">{{ $errors->first('url') }}</span>
							</div>
						</div>
						<div class="form-group">
							<label for="email" class="col-sm-2 control-label">Email <span style='color:red'></span></label>
							<div class="col-sm-9">
								<input type="email" autocomplete="off" id="email" class="form-control" name="email" value="" placeholder="Enter email">
								<span style="color: red">{{ $errors->first('email') }}</span>
							</div>
						</div>
						<div class="form-group">
							<label for="facebook_url" class="col-sm-2 control-label">Facebook URL</label>
							<div class="col-sm-9">
								<input type="text" autocomplete="off" id="facebook_url" class="form-control" name="facebook" value="" placeholder="Enter facebook url">
								<span style="color: red">{{ $errors->first('facebook') }}</span>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Description of Company and Services</label>
							<div class="col-sm-9">
								<textarea class="form-control" name="description" style="height:140px;" value="{{ old('description') }}" placeholder="Enter Description"></textarea>
								<span style="color: red">{{ $errors->first('description') }}</span>
							</div>
						</div>
						<div class="form-group">
							<label for="hear_about_us" class="col-sm-2 control-label">How did you hear about us?<span style='color:red'></span></label>
							<div class="col-sm-9">
								<input type="text" autocomplete="off" id="hear_about_us" class="form-control" name="hear_about_us" value="" placeholder="Enter how you heard about us">
								<span style="color: red">{{ $errors->first('hear_about_us') }}</span>
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
<!-- Choices.js CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
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
        image.onchange = evt => {
			const [file] = image.files
			if (file) {
				banner_preview.src = URL.createObjectURL(file)
			}
		}

	});
</script>

@endpush
