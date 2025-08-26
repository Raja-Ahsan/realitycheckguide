<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            margin: 0;
            padding: 0;
        }

        h1, h4 {
            text-align: center;
            color: #031d5a;
            text-transform: uppercase;
            margin: 15px 0;
        }

        h1 {
            font-size: 20px;
            text-shadow: 1px 1px 2px black;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        table thead th {
            background-color: #fe9901;
            color: #000;
        }

        .back-image {
            display: block;
            margin: 20px auto;
            max-width: 30%;
        }

        .content {
            margin: 0 20px;
        }

        .row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .col-md-6 {
            width: 48%;
        }

        .text-center {
            text-align: center;
            font-size: 16px;
            font-weight: 600;
        }

        .text-warning {
            color: #ff9900;
        }

        hr {
            border: none;
            border-top: 2px solid #ddd;
            margin: 20px 0;
        }
    </style>
</head>

<body>
    <img class="back-image" src="{{ asset('public/admin/assets/images/page') }}/{{ $home_page_data['header_logo'] }}" alt="Logo">
    <h1>Game Details</h1>
    <hr>
    <div class="content">
    <h4>Categories</h4>
    <div class="row mb-4">
        @if($game_categories->where('parent_id', 0)->isEmpty())
            <div class="col-md-12">
                <p class="text-center text-warning">No categories found.</p>
            </div>
        @else
            @foreach($game_categories->where('parent_id', 0) as $category)
                <div class="col-md-3 mb-3">
                    <a href="#category_{{ $category->id }}" class="btn btn-info btn-block category-btn" data-toggle="collapse">
                        {{ ucfirst($category->title) }}
                    </a>
                </div>
            @endforeach
        @endif
    </div>
    

    @foreach($game_categories->where('parent_id', 0) as $category)
        <div id="category_{{ $category->id }}" class="collapse category-content">
            <hr>
            <h5 class="text-center">{{ ucfirst($category->title) }} - Men</h5> 
            @php
                $menGames = $game->where('parent_id', $category->slug)->where('gender', 'Men')->get()->sortBy('time');
            @endphp
            @if($menGames->isNotEmpty())
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Name</th>
                            <th>Date</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($menGames as $game)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><strong>{{ $game->name }}</strong></td>
                                <td>{!! \Carbon\Carbon::parse($game->date)->format('d/M/Y') ?? 'N/A' !!}</td>
                                <td>{!! \Carbon\Carbon::parse($game->time)->format('h:i:s A') !!}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No Men games found for this category.</p>
            @endif

            <hr>
            <h5 class="text-center">{{ ucfirst($category->title) }} - Women</h5> 
            @php
                $womenGames = $game->where('parent_id', $category->slug)->where('gender', 'Women')->get()->sortBy('time');
            @endphp
            @if($womenGames->isNotEmpty())
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Name</th>
                            <th>Date</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($womenGames as $game)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><strong>{{ $game->name }}</strong></td>
                                <td>{!! \Carbon\Carbon::parse($game->date)->format('d/M/Y') ?? 'N/A' !!}</td>
                                <td>{!! \Carbon\Carbon::parse($game->time)->format('h:i:s A') !!}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No Women games found for this category.</p>
            @endif
        </div>
    @endforeach
</div>

</body>

</html>
