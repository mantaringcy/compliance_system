<div>

    <!-- Modal -->
    <div class="modal fade modal-lg" id="deleteRequestComplianceModal" tabindex="-1" aria-labelledby="dRequestiewComplianceModalLabel" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                {{-- Modal Header --}}
                <div class="modal-header">
                    <h5 class="modal-title fw-semibold" id="staticBackdropLabel">Request for <span class="text-danger fw-bolder fst-italic">Deletion of Compliance</span></h5>
                    
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                {{-- Modal Body --}}
                <div class="modal-body p-0 m-0">
                    
                    {{-- Form --}}
                    <div class="details">

                        <form action="">
                            @csrf
                            @method('DELETE')

                            <div class="row p-0 m-0">

                                <input type="hidden" id="dRequestComplianceId">
    
                                <!-- Compliance Name -->
                                <div class="col-md-6 mb-md-3 mb-4">
                                    <p>Compliance Name</p>
                                    <h6 class="fw-semibold" id="dRequestComplianceName">Compliance Name</h6>
                                </div>
                                
                                <!-- Department Name -->
                                <div class="col-md-6 mb-3">
                                    <p>Department Name</p>
                                    <h6 class="fw-semibold" id="dRequestDepartmentName">Department Name</h6>
                                </div>
    
                                <span class="line"></span>
    
                                <!-- Reference -->
                                <div class="col-md-6 col-md-6 mb-md-3 mb-4 mt-4">
                                    <p>Reference Date</p>
                                    <h6 class="fw-semibold" id="dRequestReferenceDate">Reference Date</h6>
                                </div>
    
                                <!-- Frequency -->
                                <div class="col-md-6 mb-3 mt-4">
                                    <p>Frequency</p>
                                    <h6 class="fw-semibold" id="dRequestFrequency">Frequency</h6>
                                </div>
    
                                <span class="line"></span>
    
                                <!-- Start Working On -->
                                <div class="col-md-6 mt-4">
                                    <p>Start Working On</p>
                                    <h6 class="fw-semibold" id="dRequestStartWorkingOn">Start On</h6>
                                </div>
    
                                <!-- Submit On -->
                                <div class="col-md-6 mt-4">
                                    <p>Submit On</p>
                                    <h6 class="fw-semibold" id="dRequestSubmitOn">Submit On</h6>
                                </div>
    
                            </div>
                            {{-- End of Row --}}
                    </div>
                    {{-- Modal Footer --}}
                    <div class="modal-footer request-footer" id="request-footer">
                        @if (Auth::user()->role->id == 3)
                            <button class="btn border-0" id="deleteCancelButton">Cancel Request</button>
                            <button class="btn border-0 text-white" id="deleteApproveButton">Approve Request</button>
                        @else
                            <button class="btn border-0 text-white" id="deleteCancelButton">Cancel Request</button>
                        @endif
                    </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
    
</div>

<style>
    #request-footer  {
        margin-bottom: 0px !important;
    }
</style>