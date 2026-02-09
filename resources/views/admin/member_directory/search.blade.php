@foreach($models as $key=>$model)
<tr id="id-{{ $model->id }}">
    <td>{{ $models->firstItem()+$key }}.</td>
    <td>
        @if($model->image)
        <img src="{{ asset('admin/assets/images/member_directory/'.$model->image) }}" alt="" style="width:60px;">
        @else
        <img src="{{ asset('admin/assets/images/default.jpg') }}" style="width:60px;">
        @endif
    </td>
    <td>{{\Illuminate\Support\Str::limit($model->title,40)}}</td>
    <td>
        @if (!empty($model->category_titles))
            <ul class="category-list">
                @foreach ($model->category_titles as $title)
                    <li>{{ $title }}</li>
                @endforeach
            </ul>
        @else
            N/A
        @endif
    </td>
    {{-- <td>{{\Illuminate\Support\Str::limit($model->address,40)}}</td>  --}}
    <td>
        <a href="tel:{{ $model->phone_no }}">{{ \Illuminate\Support\Str::limit($model->phone_no, 40) }}</a>
    </td>
    <td>
        @if($model->url)
            <a href="{{ $model->url }}" target="_blank" class="link-url">
                {{ \Illuminate\Support\Str::limit($model->url, 40) }}
            </a><br>
        @endif
        @if($model->email)
            <a href="mailto:{{ $model->email }}" class="link-url">
                {{ \Illuminate\Support\Str::limit($model->email, 40) }}
            </a><br>
        @endif
        @if($model->facebook)
            <a href="{{ $model->facebook }}" target="_blank" class="link-url">
                {{ \Illuminate\Support\Str::limit($model->facebook, 40) }}
            </a>
        @endif
    </td>
    <td>{!!\Illuminate\Support\Str::limit($model->description,40)!!}</td>
    <td>
        @if($model->status == 'pending')
        <span class="label label-warning">Pending</span>
        @elseif($model->status == 'approved')
        <span class="label label-success">Approved</span>
        @elseif($model->status == 'rejected')
        <span class="label label-danger">Rejected</span>
        @endif
    </td>
    @if(Auth::user()->hasRole('Admin'))
    <td>{{isset($model->hasCreatedBy)?$model->hasCreatedBy->name:'N/A'}}</td>
    @endif
    <td width="250px">
        <a href="{{ route('member_directory.show', $model->id) }}" class="btn btn-info btn-xs view-project-link"><i class="fa fa-eye"></i> View</a>
        @can('member_directory-edit')
        <a href="{{route('member_directory.edit', $model->id)}}" data-toggle="tooltip" data-placement="top" title="Edit Service" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit</a>
        @endcan
        @can('member_directory-delete')
        <button class="btn btn-danger btn-xs delete" data-slug="{{ $model->id }}" data-del-url="{{ url('member_directory', $model->id) }}"><i class="fa fa-trash"></i> Delete</button>
        @endcan
    </td>
</tr>
@endforeach
<tr>
    <td colspan="13">
        Displaying {{ $models->firstItem() }} to {{ $models->lastItem() }} of
        {{ $models->total() }} records
        <div class="d-flex justify-content-center" id="pagination-links" style="margin-top: 20px;">
            {!! $models->links('pagination::bootstrap-4') !!}
        </div>
    </td>
</tr>
<script>
    $('.delete').on('click', function() {
        var id = $(this).attr('data-slug');
        var delete_url = $(this).attr('data-del-url');
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: delete_url,
                    type: 'DELETE',
                    success: function(response) {
                        // console.log(response);
                        if (response) {
                            $('#id-' + id).hide();
                            Swal.fire(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success'
                            )
                        } else {
                            Swal.fire(
                                'Not Deleted!',
                                'Sorry! Something went wrong.',
                                'danger'
                            )
                        }
                    }
                });
            }
        })
    });
</script>