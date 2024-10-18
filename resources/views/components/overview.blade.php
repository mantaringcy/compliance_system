@section('title', 'Overview')

<x-main>

    <h2 class="fw-bold mb-5">Overview</h2>
 

    <div class="container">
        <h1>Current Month Deadlines</h1>
    
        @if(!empty($currentMonthDeadlines))
            @foreach($currentMonthDeadlines as $item)
                <div class="card mb-3"> <!-- You can customize this card as needed -->
                    <div class="card-body">
                        <h5 class="card-title">{{ $item['compliance_name'] }}</h5>
                        <p class="card-text">
                            Deadline: {{ \Carbon\Carbon::parse($item['deadline'])->format('F j, Y') }}
                        </p>
                    </div>
                </div>
            @endforeach
        @else
            <p>No deadlines for the current month.</p>
        @endif
    </div>
    
</x-main>