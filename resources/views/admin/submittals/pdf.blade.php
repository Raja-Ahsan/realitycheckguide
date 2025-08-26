<!doctype html>
<html>
    <head>
        <meta charset="utf-8" />
        <style>
            body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
            h1 { font-size: 18px; margin-bottom: 10px; }
            .section { margin-bottom: 12px; }
            .table { width: 100%; border-collapse: collapse; }
            .table th, .table td { border: 1px solid #ccc; padding: 6px; }
        </style>
    </head>
    <body>
        <h1>Submittal: {{ $submittal->title }}</h1>
        <p>Project: {{ optional($submittal->project)->name }}</p>
        <hr/>
        <h3>Included Sections</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Spec Section</th>
                    <th>Title</th>
                    <th>Manufacturer</th>
                    <th>Type</th>
                </tr>
            </thead>
            <tbody>
                @foreach($submittal->sections()->where('included', true)->get() as $section)
                    <tr>
                        <td>{{ $section->spec_section }}</td>
                        <td>{{ $section->title }}</td>
                        <td>{{ $section->manufacturer }}</td>
                        <td>{{ $section->product_type }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </body>
</html>


