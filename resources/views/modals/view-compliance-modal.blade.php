<div>

    <!-- Modal -->
    <div class="modal fade modal-lg" id="viewComplianceModal" tabindex="-1" aria-labelledby="viewComplianceModalLabel" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                {{-- Modal Header --}}
                <div class="modal-header">
                    <h5 class="modal-title fw-semibold" id="staticBackdropLabel">Compliance</h5>
                    
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                {{-- Modal Body --}}
                <div class="modal-body p-0 m-0">
                    
                    {{-- Form --}}
                    <div class="details">

                        <div class="row p-0 m-0">

                            <!-- Compliance Name -->
                            <div class="col-md-6 mb-md-3 mb-4">
                                <p>Compliance Name</p>
                                <h6 class="fw-semibold" id="vComplianceName">Compliance Name</h6>
                            </div>
                            
                            <!-- Department Name -->
                            <div class="col-md-6 mb-3">
                                <p>Department Name</p>
                                <h6 class="fw-semibold" id="vDepartmentName">Department Name</h6>
                            </div>

                            <span class="line"></span>

                            <!-- Deadline -->
                            {{-- <div class="c6">
                                <h6 class="fw-semibold">Deadline</h6>
                                <div>
                                    <span class="line"></span>
                                </div>
                            </div> --}}

                            <!-- Reference -->
                            <div class="col-md-6 col-md-6 mb-md-3 mb-4 mt-4">
                                <p>Reference Date</p>
                                <h6 class="fw-semibold" id="vReferenceDate">Reference Date</h6>
                            </div>

                            <!-- Frequency -->
                            <div class="col-md-6 mb-3 mt-4">
                                <p>Frequency</p>
                                <h6 class="fw-semibold" id="vFrequency">Frequency</h6>
                            </div>

                            <span class="line"></span>

                            <!-- Start Working On -->
                            <div class="col-md-6 mt-4">
                                <p>Start Working On</p>
                                <h6 class="fw-semibold" id="vStartWorkingOn">Start On</h6>
                            </div>

                            <!-- Submit On -->
                            <div class="col-md-6 mt-4">
                                <p>Submit On</p>
                                <h6 class="fw-semibold" id="vSubmitOn">Submit On</h6>
                            </div>

                        </div>
                        {{-- End of Row --}}

                    </div>

                </div>
            </div>
        </div>
    </div>
    
</div>

{{-- <script>
    const departmentMapping = @json($departments);
    const frequencyMapping = @json(config('static_data.frequency'));
    const startOnMapping = @json(config('static_data.start_working_on'));
    const submitOnMapping = @json(config('static_data.submit_on'));
</script> --}}