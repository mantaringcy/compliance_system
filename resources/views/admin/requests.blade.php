@section('title', 'Request')

<x-main>
    <h2 class="fw-semibold">Request</h2>

    <table>
        <thead>
            <tr>
                <th>User</th>
                <th>Action</th>
                <th>Changes</th>
                <th>Approve</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($requests as $request)
                <tr>
                    <td>{{ $request->user->name }}</td>
                    <td>{{ $request->action }}</td>
                    <td>{{ json_encode(json_decode($request->changes), JSON_PRETTY_PRINT) }}</td>
                    <td>
                        <form action="{{ route('approveRequest', $request->id) }}" method="POST">
                            @csrf
                            <button type="submit">Approve</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-main>