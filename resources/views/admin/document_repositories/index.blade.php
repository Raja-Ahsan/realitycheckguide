@extends('layouts.admin.app')
@section('title', $page_title)
@section('content')
<input type="hidden" id="page_url" value="{{ route('documents.index') }}">
<section class="content-header">
    <div class="content-header-left">
        <h1>{{ $page_title }}</h1>
    </div>
    @can('documents-create')
    <div class="content-header-right">
        <a href="{{ route('documents.create') }}" class="btn btn-primary btn-sm">Add Documents</a>
    </div>
    @endcan
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            @if (session('status'))
            <div class="callout callout-success">
                {{ session('status') }}
            </div>
            @endif
            <div class="box box-info">
                <div class="box-body">
                    <div class="row" style="margin-bottom: 10px;">
						<div class="d-flex col-sm-8">
							<input type="text" id="search" class="form-control" placeholder="Search by: File Name">
						</div>
						<div class="d-flex col-sm-4">
                            <select name="" id="status" class="form-control status" style="margin-bottom:5px">
                                <option value="All" selected>Search by status</option>
                                <option value="upcoming">Upcoming</option>
                                <option value="ongoing">Ongoing</option>
                                <option value="completed">Completed</option>
                            </select>
						</div>
					</div>
                    <div class="card-body table-responsive p-0">
                        <table id="" class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>File Name</th>
                                    <th>Company Name</th>
                                    <th>Project Name</th>
                                    <th>Upload Date</th> 
                                    <th>Size</th> 
                                    <th>Uploaded By</th>
                                    <!-- <th>Status</th> -->
                                    <th width="220">Action</th>
                                </tr>
                            </thead>
                            <tbody id="body">
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
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('js')

@endpush