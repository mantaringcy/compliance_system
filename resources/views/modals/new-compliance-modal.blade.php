<div>
    <!-- Modal -->
    <div class="modal fade modal-lg" id="newComplianceModal" tabindex="-1" aria-labelledby="newComplianceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                {{-- Modal Header --}}
                <div class="modal-header">
                    <h5 class="modal-title fw-semibold" id="staticBackdropLabel">New Compliance</h5>
                    
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                {{-- Modal Body --}}
                <div class="modal-body p-0 m-0">

                    {{-- Form --}}
                    <div class="card-form">
                        <form action="{{ route('compliances.store') }}" method="post" id="newComplianceForm">
                            @csrf
                            
                            <div class="row">

                                <!-- Compliance Name -->
                                <div class="col-md-6 mb-3">
                                    <label for="comp_name" class="mb-2">Compliance Name</label>
                                    <input type="text" name="compliance_name" class="form-control @error('compliance_name') is-invalid @enderror" placeholder="Compliance Name" id="nComplianceName" value="{{ old('compliance_name') }}">             

                                    <div class="invalid-feedback compliance_name" style="display: block;"></div>
                                    @error('compliance_name')
                                        <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
                                    @enderror
                
                                </div>
                                
                                <!-- Department Name -->
                                <div class="col-md-6 mb-5">
                                    <label for="department_id" class="mb-2">Department Name</label>
                                    <select class="form-select @error('department_id') is-invalid @enderror" aria-label="Default select example" name="department_id" id="departmentSelect">
                                        @php
                                            $userDepartment = auth()->user()->department->department_name ?? ''; // Assuming the user’s department is available this way
                                        @endphp

                                        @if ($userDepartment == 'IMS')
                                            <option selected disabled>Select Department</option>
                                            @foreach ($departments as $department)
                                                <option value="{{ $department->id }}">{{ $department->department_name }}</option>
                                            @endforeach
                                        @else
                                            @foreach ($departments as $department)
                                                @if ($department->department_name === $userDepartment)
                                                    <option value="{{ $department->id }}">{{ $department->department_name }}</option>
                                                @endif
                                            @endforeach
                                        @endif
                                    </select>

                                    <div class="invalid-feedback department_id" style="display: block;"></div>
                                    @error('department_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Deadline -->
                                <div class="col-12 mb-3">
                                    <h6 class="fw-semibold">Deadline</h6>
                                    <div>
                                        <span class="line"></span>
                                    </div>
                                </div>

                                <!-- Reference -->
                                <div class="col-md-6 mb-3">
                                    <label for="comp_name" class="mb-2">Reference Date</label><br>
                                    <input type="date" name="reference_date" class="@error('reference_date') is-invalid @enderror w-100" style="padding: 10px !important;" value="{{ old('reference_date') }}" id="nReferenceDate">

                                    <div class="invalid-feedback reference_date" style="display: block;"></div>
                                    @error('reference_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Frequency -->
                                <div class="col-md-6 mb-3">
                                    <label for="comp_name" class="mb-2">Frequency</label>
                                    <select class="form-select @error('frequency') is-invalid @enderror" aria-label="Default select example" name="frequency" id="nFrequency" value="{{ old('frequency') }}">
                                        <option selected disabled>Select Frequency</option>
                                        @foreach (config('static_data.frequency') as $key => $frequency)
                                            <option value="{{ $key }}">{{ $frequency }}</option>
                                        @endforeach
                                    </select>

                                    <div class="invalid-feedback frequency" style="display: block;"></div>
                                    @error('frequency')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Start Working On -->
                                <div class="col-md-6 mb-3">
                                    <label for="comp_name" class="mb-2">Start Working On</label>
                                    <select class="form-select @error('start_working_on') is-invalid @enderror" aria-label="Default select example" name="start_working_on" value="{{ old('start_working_on') }}" id="nStartWorkingOn">
                                        <option selected disabled>Select Start</option>
                                        @foreach (config('static_data.start_working_on') as $key => $startWorkingOn)
                                            <option value="{{ $key }}">{{ $startWorkingOn }}</option>
                                        @endforeach
                                    </select>

                                    <div class="invalid-feedback start_working_on" style="display: block;"></div>
                                    @error('start_working_on')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Submit On -->
                                <div class="col-md-6 mb-2">
                                    <label for="comp_name" class="mb-2">Submit On</label>
                                    <select class="form-select @error('submit_on') is-invalid @enderror" aria-label="Default select example" name="submit_on" value="{{ old('submit_on') }}" id="nSubmitOn">
                                        <option selected disabled>Select Submission</option>
                                        @foreach (config('static_data.submit_on') as $key => $submitOn)
                                            <option value="{{ $key }}">{{ $submitOn }}</option>
                                        @endforeach
                                    </select>

                                    <div class="invalid-feedback submit_on" style="display: block;"></div>
                                    @error('submit_on')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

              
                                <!-- Button -->
                                {{-- <div class="col-6">
                                    <button class="btn btn-primary border-0 text-white w-100 mt-2" style="width: 80px !important">Submit</button>
                                </div> --}}

                                
                            </div>

                            {{-- Modal Footer --}}
                            <div class="modal-footer">
                                <button type="button" class="btn border-0 close-btn" data-bs-dismiss="modal">Close</button>
                                <button class="btn border-0 text-white" id="saveComplianceButton">Add Compliance</button>
                            </div>
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>