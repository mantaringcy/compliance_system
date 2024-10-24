<div>

    <!-- Modal -->
    <div class="modal fade modal-lg" id="addRequestComplianceModal" tabindex="-1" aria-labelledby="viewComplianceModalLabel" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                {{-- Modal Header --}}
                <div class="modal-header">
                    <h5 class="modal-title fw-semibold" id="staticBackdropLabel">Request for <span class="text-primary fw-bolder fst-italic">Addition of Compliance</span></h5>
                    
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                {{-- Modal Body --}}
                <div class="modal-body p-0 m-0">
                    
                    {{-- Form --}}
                    <div class="details">
                        <form action="" id="aRequestComplianceForm" method="POST">
                            @csrf

                            <div class="row p-0 m-0">

                                <input type="hidden" id="aRequestComplianceId">
    
                                <!-- Compliance Name -->
                                <div class="col-md-6 mb-md-3 mb-4">
                                    <p>Compliance Name</p>
                                    <h6 class="fw-semibold" id="aRequestComplianceName">Compliance Name</h6>
                                </div>
                                
                                <!-- Department Name -->
                                <div class="col-md-6 mb-3">
                                    <p>Department Name</p>
                                    <h6 class="fw-semibold" id="aRequestDepartmentName">Department Name</h6>
                                </div>
    
                                <span class="line"></span>
    
                                <!-- Reference -->
                                <div class="col-md-6 col-md-6 mb-md-3 mb-4 mt-4">
                                    <p>Reference Date</p>
                                    <h6 class="fw-semibold" id="aRequestReferenceDate">Reference Date</h6>
                                </div>
    
                                <!-- Frequency -->
                                <div class="col-md-6 mb-3 mt-4">
                                    <p>Frequency</p>
                                    <h6 class="fw-semibold" id="aRequestFrequency">Frequency</h6>
                                </div>
    
                                <span class="line"></span>
    
                                <!-- Start Working On -->
                                <div class="col-md-6 mt-4">
                                    <p>Start Working On</p>
                                    <h6 class="fw-semibold" id="aRequestStartWorkingOn">Start On</h6>
                                </div>
    
                                <!-- Submit On -->
                                <div class="col-md-6 mt-4">
                                    <p>Submit On</p>
                                    <h6 class="fw-semibold" id="aRequestSubmitOn">Submit On</h6>
                                </div>
    
                            </div>
                            {{-- End of Row --}}
                        
                    </div>

                    {{-- Modal Footer --}}
                    <div class="modal-footer request-footer" id="request-footer">
                        @if (Auth::user()->role->id == 3)
                            <button class="btn border-0" id="addCancelButton">Cancel Request</button>
                            <button class="btn border-0 text-white" id="addApproveButton">Approve Request</button>
                        @else
                            <button class="btn border-0 text-white hover" id="addCancelButton">Cancel Request</button>
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