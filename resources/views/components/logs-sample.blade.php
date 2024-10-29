@section('title', 'Logs')

<x-main>
    <!-- {{-- <h2 class="fw-semibold mb-4">Logs</h2> --}} -->
<!-- 
    {{-- <table>
        <thead>
            <tr>
                <th>At</th>
                <th>User</th>
                <th>Type</th>
                <th>Compliance</th>
                <th>Changes</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>

    

            @foreach($logs as $log)
                    @php
                        $approvalJSON = json_decode($log->changes, true);

                        $changesData = json_decode($log->changes, true);
                        $oldData = $changesData['old'] ?? [];  // Provide default empty array if 'old' is not present
                        $newData = $changesData['new'] ?? [];  // Provide default empty array if 'new' is not present

                        $frequencyMapping = config('static_data.frequency');
                        $startWorkingOnMapping = config('static_data.start_working_on');
                        $submitOnMapping = config('static_data.submit_on');

                        unset($oldData['id'], $newData['id']);
                        unset($newData['_token']);
                        unset($newData['complianceId']);
                        unset($newData['_method']);

                        $keyMapping = [
                            'compliance_name' => 'Compliance Name',
                            'department_id' => 'Department',
                            'reference_date' => 'Reference Date',
                            'frequency' => 'Frequency',
                            'start_working_on' => 'Start Working On',
                            'submit_on' => 'Submit On'
                        ];

                        $orderedKeys = [
                            'compliance_name', 
                            'department_id', 
                            'frequency', 
                            'reference_date', 
                            'start_working_on',
                            'submit_on'
                        ];


                        // Find the changes between old and new data
                        $changes = array_diff_assoc($newData, $oldData);
                    @endphp
                <tr>
                    <td>{{ \Carbon\Carbon::parse($log->created_at)->format('d M Y h:i A') }}</td>
                    <td>{{ $log->user->username }}</td>
                    <td>
                        <span class="badge rounded-pill text-bg-primary">
                            {{ $log->action }}
                        </span>
                    </td>
                    <td class="fst-italic">
                        @if ($log->action == 'add/approval')
                            {{ $approvalJSON['compliance_name'] }}
                        @else
                            {{ $log->compliance ? $log->compliance->compliance_name : $log->compliance_name }}
                        @endif
                    </td>
                    @if ($log->action == 'add')
                        <td>Addition of Compliance <strong>{{ $log->compliance_name }}</strong></td>
                    @elseif ($log->action == 'edit')
                        <td class="border border-1">
                            @if(empty($changes))
                                <span>No changes were made.</span>
                            @else
                                    @foreach($orderedKeys as $key)
                                        @if (array_key_exists($key, $changes))
                                            <strong>{{ $keyMapping[$key] ?? ucfirst(str_replace('_', ' ', $key)) }}:</strong> 
                                            <span>Old: 
                                                @if ($key === 'department_id')
                                                    {{ $departments[$oldData[$key] - 1]['department_name'] }}
                                                @elseif ($key === 'reference_date')
                                                    {{ \Carbon\Carbon::parse($oldData[$key])->format('F j, Y') }}
                                                @elseif ($key === 'frequency')
                                                    {{ $frequencyMapping[$oldData[$key]] ?? 'N/A' }}
                                                @elseif ($key === 'start_working_on')
                                                    {{ $startWorkingOnMapping[$oldData[$key]] ?? 'N/A' }}
                                                @elseif ($key === 'submit_on')
                                                    {{ $submitOnMapping[$oldData[$key]] ?? 'N/A' }}
                                                @else
                                                    {{ $oldData[$key] }}
                                                @endif
                                            </span> 
                                            <span>New: 
                                                @if ($key === 'department_id')
                                                    {{ $departments[$changes[$key] - 1]['department_name'] }}
                                                @elseif ($key === 'reference_date')
                                                    {{ \Carbon\Carbon::parse($changes[$key])->format('F j, Y') }}
                                                @elseif ($key === 'frequency')
                                                    {{ $frequencyMapping[$changes[$key]] ?? 'N/A' }}
                                                @elseif ($key === 'start_working_on')
                                                    {{ $startWorkingOnMapping[$changes[$key]] ?? 'N/A' }}
                                                @elseif ($key === 'submit_on')
                                                    {{ $submitOnMapping[$changes[$key]] ?? 'N/A' }}
                                                @else
                                                    {{ $changes[$key] }}
                                                @endif
                                            </span><br>
                                        @endif
                                    @endforeach
                            @endif
                        </td>
                    @elseif ($log->action == 'delete')
                        <td>Deletion of Compliance <strong>{{ $log->compliance_name }}</strong></td>
                    @elseif ($log->action == 'add/approval')
                        <td>Request for Addition of Compliance <strong>{{ $approvalJSON['compliance_name'] }}</strong></td>
                    @elseif ($log->action == 'edit/approval')
                        <td>Request for Change of Compliance
                            @foreach($orderedKeys as $key)
                                @if ($key === 'compliance_name') 
                                    <strong>{{$oldData[$key]}}</strong>
                                @else
                                @endif
                            @endforeach
                        </td>
                    @elseif ($log->action == 'delete/approval')
                        <td>Request for Deletion of Compliance <strong>{{ $log->compliance_name }}</strong></td>
                    @elseif ($log->action == 'add/approved')
                    @elseif ($log->action == 'edit/approved')
                    <td>Approved for Change of Compliance
                        @foreach($orderedKeys as $key)
                            @if ($key === 'compliance_name') 
                                <strong>{{$oldData[$key]}}</strong>
                            @else
                            @endif
                        @endforeach
                    </td>
                    @elseif ($log->action == 'delete/approved')
                        <td>Approved for Deletion of Compliance <strong>{{ $log->compliance_name }}</strong></td>
                    @endif
                    

                    <td>ACTION</td>
                </tr>
            @endforeach
        </tbody>
    </table> --}} -->

    <div>
        <div class="card-lg table-card">

            <div class="card-top">
                <h5 class="fw-semibold m-0 p-0" style="font-size: 16px !important;">Logs</h5>
            </div>
            
            <table class="table logs-table w-100" id="logsTable">
                <thead>
                    <tr>
                        <th>AT</th>
                        <th>USER</th>
                        <th>TYPE</th>
                        <th>COMPLIANCE</th>
                        <th>CHANGES</th>
                        <th>ACTION</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($logs as $log)
                            @php
                                $approvalJSON = json_decode($log->changes, true);
        
                                $changesData = json_decode($log->changes, true);

                                // if ($log->action == 'add/approved') {
                                //     echo gettype($log->action);
                                // }
                                // dd($log->action);

                                // dd($changesData);

                                // echo $changesData['_token'];

                                $oldData = $changesData['old'] ?? [];  // Provide default empty array if 'old' is not present
                                $newData = $changesData['new'] ?? [];  // Provide default empty array if 'new' is not present
        
                                $frequencyMapping = config('static_data.frequency');
                                $startWorkingOnMapping = config('static_data.start_working_on');
                                $submitOnMapping = config('static_data.submit_on');
        
                                unset($oldData['id'], $newData['id']);
                                unset($newData['_token']);
                                unset($newData['complianceId']);
                                unset($newData['_method']);
        
                                $keyMapping = [
                                    'compliance_name' => 'Compliance Name',
                                    'department_id' => 'Department',
                                    'reference_date' => 'Reference Date',
                                    'frequency' => 'Frequency',
                                    'start_working_on' => 'Start Working On',
                                    'submit_on' => 'Submit On'
                                ];
        
                                $orderedKeys = [
                                    'compliance_name', 
                                    'department_id', 
                                    'frequency', 
                                    'reference_date', 
                                    'start_working_on',
                                    'submit_on'
                                ];
        
                                $changes = array_diff_assoc($newData, $oldData);
                            @endphp
                        <tr>
                            <td data-bs-toggle="tooltip" data-bs-placement="top" title="{{ \Carbon\Carbon::parse($log->created_at)->format('d M Y h:i A') }}">{{ \Carbon\Carbon::parse($log->created_at)->format('d M Y') }}</td>
                            <td>{{ $log->user->username }}</td>
                            <td>
                                <span class="badge rounded-pill text-bg-primary">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="fst-italic">
                                @if ($log->action == 'add/approval')
                                    {{ $changesData['compliance_name'] }}
                                @elseif ($log->action == 'add/approved')
                                    {{ $changesData['compliance_name'] }}
                                @elseif ($log->action == 'cancelled')
                                    {{ $changesData['compliance_name'] }}
                                @else
                                    {{ $log->compliance ? $log->compliance->compliance_name : $log->compliance_name }}
                                @endif
                            </td>
                            @if ($log->action == 'add')
                                <td>Addition of Compliance <strong>{{ $log->compliance_name }}</strong></td>
                            @elseif ($log->action == 'edit')
                                <td>
                                    @if(empty($changes))
                                        <span>No changes were made.</span>
                                    @else
                                            @foreach($orderedKeys as $key)
                                                @if (array_key_exists($key, $changes))
                                                    <strong>{{ $keyMapping[$key] ?? ucfirst(str_replace('_', ' ', $key)) }}:</strong> 
                                                    <span>Old: 
                                                        @if ($key === 'department_id')
                                                            {{ $departments[$oldData[$key] - 1]['department_name'] }}
                                                        @elseif ($key === 'reference_date')
                                                            {{ \Carbon\Carbon::parse($oldData[$key])->format('F j, Y') }}
                                                        @elseif ($key === 'frequency')
                                                            {{ $frequencyMapping[$oldData[$key]] ?? 'N/A' }}
                                                        @elseif ($key === 'start_working_on')
                                                            {{ $startWorkingOnMapping[$oldData[$key]] ?? 'N/A' }}
                                                        @elseif ($key === 'submit_on')
                                                            {{ $submitOnMapping[$oldData[$key]] ?? 'N/A' }}
                                                        @else
                                                            {{ $oldData[$key] }}
                                                        @endif
                                                    </span> 
                                                    <span>New: 
                                                        @if ($key === 'department_id')
                                                            {{ $departments[$changes[$key] - 1]['department_name'] }}
                                                        @elseif ($key === 'reference_date')
                                                            {{ \Carbon\Carbon::parse($changes[$key])->format('F j, Y') }}
                                                        @elseif ($key === 'frequency')
                                                            {{ $frequencyMapping[$changes[$key]] ?? 'N/A' }}
                                                        @elseif ($key === 'start_working_on')
                                                            {{ $startWorkingOnMapping[$changes[$key]] ?? 'N/A' }}
                                                        @elseif ($key === 'submit_on')
                                                            {{ $submitOnMapping[$changes[$key]] ?? 'N/A' }}
                                                        @else
                                                            {{ $changes[$key] }}
                                                        @endif
                                                    </span><br>
                                                @endif
                                            @endforeach
                                    @endif
                                </td>
                            @elseif ($log->action == 'delete')
                                <td>Deletion of Compliance <strong>{{ $log->compliance_name }}</strong></td>
                            @elseif ($log->action == 'add/approval')
                                <td>Request for Addition of Compliance <strong>{{ $approvalJSON['compliance_name'] }}</strong></td>
                            @elseif ($log->action == 'edit/approval')
                                <td>Request for Change of Compliance
                                    @foreach($orderedKeys as $key)
                                        @if ($key === 'compliance_name') 
                                            <strong>{{$oldData[$key]}}</strong>
                                        @else
                                        @endif
                                    @endforeach
                                </td>
                            @elseif ($log->action == 'delete/approval')
                                <td>Request for Deletion of Compliance <strong>{{ $log->compliance_name }}</strong></td>
                            @elseif ($log->action == 'add/approved')
                                <td>{{ $changesData['compliance_name'] }}</td>
                                
                            @elseif ($log->action == 'edit/approved')
                            <td>Approved for Change of Compliance
                                @foreach($orderedKeys as $key)
                                    @if ($key === 'compliance_name') 
                                        <strong>{{$oldData[$key]}}</strong>
                                    @else
                                    @endif
                                @endforeach
                            </td>
                            @elseif ($log->action == 'delete/approved')
                                <td>Approved for Deletion of Compliance <strong>{{ $log->compliance_name }}</strong></td>
                            @elseif ($log->action == 'cancelled')
                                <td>{{ $changesData['compliance_name'] }}</td>
                            @endif
                            <td>ACTION</td>
                        </tr>
                    @endforeach
                </tbody>
          
            </table>
        </div>
    </div>
</x-main>

<style>
    .table-card {
        padding: 25px !important;
    }

   .card-top {
        font-weight: 500;
        margin-bottom: 25px !important;
    }

    .logs-table {
        width: 100%; /* Full width */
        border-collapse: collapse;
        border-top: 1px solid var(--border) !important;
        border-bottom: 1px solid var(--border) !important;
    }

    .logs-table thead tr th {
        /* padding: 14.5px 5px !important; */
        text-align: left !important;
        vertical-align: middle !important;
    }

    .logs-table th {
        font-size: 13px !important;
    }

    .logs-table tbody tr:last-child {
        border-bottom: 1px solid var(--card-fill) !important;
    }

    .logs-table tbody tr td {
        background: var(--card-fill) !important;
        color: var(--primary-color-text) !important;
        padding: 8px 5px !important;
    }

    .logs-table th, td {
        text-align: left; /* Horizontally center text */
        vertical-align: middle; /* Vertically center text */
    }

    .logs-table thead th {
        color: var(--primary-color-text) !important;
        background: #FCFCFC !important;
        padding: 8px 8px !important
    }

    body.dark .logs-table thead th {
        background: #303F4F !important;
    }
</style>