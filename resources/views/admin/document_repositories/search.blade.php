@foreach ($models as $key => $model)
<tr id="id-{{ $model->id }}">
    <td>{{ $models->firstItem() + $key }}.</td>
    <td>{!! \Illuminate\Support\Str::limit($model->file_name ?? 'N/A', 50) !!}</td>  
    <td>{{ isset($model->project) ? $model->project->company : 'N/A' }}</td>
    <td>{{ isset($model->project) ? $model->project->name : 'N/A' }}</td>
    <td>{!! \Carbon\Carbon::parse($model->created_at ?? 'N/A')->format('M d, Y') !!}</td>  
    <td>{!! \Illuminate\Support\Str::limit($model->size ?? 'N/A', 50) !!}</td>
    <td>{{isset($model->hasCreatedBy)?$model->hasCreatedBy->name:'N/A'}}</td>

    <!-- <td>
        @if ($model->status == 'upcoming')
            <span class="label label-warning">Upcoming</span>
        @elseif ($model->status == 'ongoing')
            <span class="label label-info">Ongoing</span>
        @elseif ($model->status == 'completed')
            <span class="label label-success">Completed</span>
        @endif
    </td> -->

    <td>
        <a href="{{ route('documents.show', $model->id) }}"
            class="btn btn-info btn-xs"><i class="fa fa-eye"></i> View</a>
        @can('documents-edit')
        <a href="{{ route('documents.edit', $model->id) }}"
            data-toggle="tooltip" data-placement="top" title="Edit project"
            class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit</a>
        @endcan
        @can('documents-delete')
        <button class="btn btn-danger btn-xs delete"
            data-slug="{{ $model->id }}"
            data-del-url="{{ url('documents', $model->id) }}">
            <i class="fa fa-trash"></i> Delete</button>
        @endcan
    </td>
</tr>
@endforeach
<tr>
    <td colspan="13">
        Displying {{ $models->firstItem() }} to {{ $models->lastItem() }} of
        {{ $models->total() }} records
        <div class="d-flex justify-content-center">
            {!! $models->links('pagination::bootstrap-4') !!}
        </div>
    </td>
</tr>
<script>
    //delete record
    $('.delete').on('click', function() {
        var slug = $(this).attr('data-slug');
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
                        if (response) {
                            $('#id-' + slug).hide();
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