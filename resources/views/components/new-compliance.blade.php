@section('title', 'New Compliance')

<x-main>
    {{-- <h2 class="fw-semibold">New Compliance</h2> --}}

    <div>
        <div class="card-lg">

            <div class="card-text">
                <h5 class="fw-semibold m-0 p-0" style="font-size: 16px !important;">New Compliance</h5>
            </div>

            <div>
                <span class="line"></span>
            </div>

            <div class="card-form">
                <form action="{{ route('new-compliance') }}" method="post">
                    @csrf
                    
                    <div class="row">

                        <!-- Compliance Name -->
                        <div class="col-md-6 mb-3">
                            <label for="comp_name" class="mb-2">Compliance Name</label>
                            <input type="text" name="compliance_name" class="form-control @error('compliance_name') is-invalid @enderror" placeholder="Compliance Name">                

                            @error('compliance_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
        
                        </div>
                        
                        <!-- Department Name -->
                        <div class="col-md-6 mb-5">
                            <label for="department_id" class="mb-2">Department Name</label>
                            <select class="form-select @error('department_id') is-invalid @enderror" aria-label="Default select example" name="department_id">
                                <option selected disabled>Select Department</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->department_name }}</option>
                                @endforeach
                        
                                {{-- @foreach ($data as $row)
                                    <option value="{{ $row->id }}">{{ $row->department_name }}</option>
                                @endforeach --}}
                                {{-- <option value="1">Marketing</option>
                                <option value="2">Sales</option>
                                <option value="3">Testing</option>
                                <option value="4">IH</option>
                                <option value="5">Consulting</option>
                                <option value="6">OSHM360</option>
                                <option value="7">FAD</option>
                                <option value="8">IMS</option>
                                <option value="9">HR</option>
                                <option value="10">IT</option>
                                <option value="11">ESH (IMS)</option> --}}
                            </select>

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
                            <input type="date" name="reference_date" class="@error('reference_date') is-invalid @enderror w-100" style="padding: 10px !important;">

                            @error('reference_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Frequency -->
                        <div class="col-md-6 mb-3">
                            <label for="comp_name" class="mb-2">Frequency</label>
                            <select class="form-select @error('frequency') is-invalid @enderror" aria-label="Default select example" name="frequency">
                                <option selected disabled>Select Frequency</option>
                                @foreach (config('static_data.frequency') as $key => $frequency)
                                    <option value="{{ $key }}">{{ $frequency }}</option>
                                @endforeach
                            </select>

                            @error('frequency')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Start Working On -->
                        <div class="col-md-6 mb-3">
                            <label for="comp_name" class="mb-2">Start Working On</label>
                            <select class="form-select @error('start_on') is-invalid @enderror" aria-label="Default select example" name="start_on">
                                <option selected disabled>Select Start</option>
                                @foreach (config('static_data.start_on') as $key => $frequency)
                                    <option value="{{ $key }}">{{ $frequency }}</option>
                                @endforeach
                            </select>

                            @error('start_on')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit On -->
                        <div class="col-md-6 mb-2">
                            <label for="comp_name" class="mb-2">Submit On</label>
                            <select class="form-select @error('submit_on') is-invalid @enderror" aria-label="Default select example" name="submit_on">
                                <option selected disabled>Select Submission</option>
                                @foreach (config('static_data.submit_on') as $key => $frequency)
                                    <option value="{{ $key }}">{{ $frequency }}</option>
                                @endforeach
                            </select>

                            @error('submit_on')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Button -->
                        <div class="col-6">
                            <button class="btn btn-primary border-0 text-white w-100 mt-2" style="width: 80px !important">Submit</button>
                        </div>

                    </div>
                    
                </form>
            </div>
            
        </div>
    </div>
</x-main>