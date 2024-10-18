@section('title', 'Projection')

<x-main>
    <h2 class="fw-bold mb-5">Monthly Projection</h2>

    {{-- showAllCompliances --}}
    {{-- @foreach ($monthlyProjections as $monthYear => $compliances)
        <h2 class="fw-semibold">{{ $monthYear }}</h2> <!-- Show the month and year -->


        <ul>
            @foreach ($compliances as $compliance)
                <li>
                    <span class="fw-semibold m-0 p-0 mt-3">Compliance Name: </span>{{ $compliance['name'] }}
                    <span class="fw-semibold m-0 p-0">Adjusted Date: </span>{{ \Carbon\Carbon::parse($compliance['adjusted_date'])->format('F j, Y') }}
                    <span class="fw-semibold m-0 p-0">Display Date: </span>{{ \Carbon\Carbon::parse($compliance['display_date'])->format('F j, Y') }}</li> <!-- Show compliance name and adjusted date -->
            @endforeach
        </ul>
    @endforeach --}}

    {{-- projections --}}
    {{-- @foreach ($results as $result)
        <div class="compliance-item">
            <h2>{{ $result['compliance']->compliance_name }}</h2>
            <p>Reference Date: {{ $result['compliance']->reference_date }}</p>
            <p>Frequency: {{ config('static_data.frequency')[$result['compliance']->frequency] }}</p>

            <ul>
                @foreach ($result['projections'] as $projection)
                    <li>
                        Deadline: {{ $projection }}
                    </li>
                @endforeach
            </ul>
        </div>
    @endforeach --}}

    {{-- deadlines --}}

    <div class="container mt-4">
        @foreach ($groupedResults as $monthYear => $items)
            <div class="card mb-3">
                <div class="card-header">
                    <h5>{{ $monthYear }}</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach ($items as $item)
                            <li class="list-group-item">
                                {{ \Carbon\Carbon::parse($item['deadline'])->format('F j, Y') }} : {{ $item['compliance']['compliance_name'] }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endforeach
    </div>

</x-main>