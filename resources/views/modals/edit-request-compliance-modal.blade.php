<div>

    <!-- Modal -->
    <div class="modal fade modal-lg" id="editRequestComplianceModal" tabindex="-1" aria-labelledby="viewComplianceModalLabel" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                {{-- Modal Header --}}
                <div class="modal-header">
                    <h5 class="modal-title fw-semibold" id="staticBackdropLabel">Request for <span class="text-success fw-bolder fst-italic">Editing of Compliance</span></h5>
                    
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                {{-- Modal Body --}}
                <div class="modal-body p-0 m-0">
                    
                    {{-- Form --}}
                    <div class="details">
                        <form action="" >
                            @csrf
                            @method('PUT')


                            <div class="row p-0 m-0">

                                <input type="hidden" id="eRequestComplianceId">

                                <!-- Head -->
                                <div class="col-md-12 d-flex mb-2">
                                    <div class="col-md-6 mb-3">
                                        <h6 class="fw-bolder">Original Compliance</h6>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <h6 class="fw-bolder fst-italic">Request to Change</h6>
                                    </div>
                                </div>

                                <!-- Compliance Name -->
                                <div class="col-md-12 d-flex mb-2">
                                    <div class="col-md-6 mb-3">
                                        <p>Compliance Name</p>
                                        <h6 class="fw-semibold" id="eOriginalComplianceName">Compliance Name</h6>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <p>Compliance Name</p>
                                        <h6 class="fw-semibold fst-italic" id="eRequestComplianceName">Compliance Name</h6>
                                    </div>
                                </div>
                                
                                <!-- Department Name -->
                                <div class="col-md-12 d-flex mb-2">
                                    <div class="col-md-6 mb-3">
                                        <p>Department Name</p>
                                        <h6 class="fw-semibold" id="eOriginalDepartmentName">Department Name</h6>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <p>Department Name</p>
                                        <h6 class="fw-semibold fst-italic" id="eRequestDepartmentName">Department Name</h6>
                                    </div>
                                </div>

                                <!-- Reference -->
                                <div class="col-md-12 d-flex mb-2">
                                    <div class="col-md-6 mb-3">
                                        <p>Reference Date</p>
                                        <h6 class="fw-semibold" id="eOriginalReferenceDate">Reference Date</h6>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <p>Reference Date</p>
                                        <h6 class="fw-semibold" id="eRequestReferenceDate">Reference Date</h6>
                                    </div>
                                </div>

                                <!-- Frequency -->
                                <div class="col-md-12 d-flex mb-2">
                                    <div class="col-md-6 mb-3">
                                        <p>Frequency</p>
                                        <h6 class="fw-semibold" id="eOriginalFrequency">Frequency</h6>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <p>Frequency</p>
                                        <h6 class="fw-semibold fst-italic" id="eRequestFrequency">Frequency</h6>
                                    </div>
                                </div>

                                <!-- Start Working On -->
                                <div class="col-md-12 d-flex mb-2">
                                    <div class="col-md-6 mb-3">
                                        <p>Start Working On</p>
                                        <h6 class="fw-semibold" id="eOriginalStartWorkingOn">Start Working On</h6>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <p>Start Working On</p>
                                        <h6 class="fw-semibold fst-italic" id="eRequestStartWorkingOn">Start Working On</h6>
                                    </div>
                                </div>

                                <!-- Submit On -->
                                <div class="col-md-12 d-flex">
                                    <div class="col-md-6">
                                        <p>Submit On</p>
                                        <h6 class="fw-semibold" id="eOriginalSubmitOn">Submit On</h6>
                                    </div>

                                    <div class="col-md-6">
                                        <p>Submit On</p>
                                        <h6 class="fw-semibold fst-italic" id="eRequestSubmitOn">Submit On</h6>
                                    </div>
                                </div>

                            </div>
                            {{-- End of Row --}}

                        
                    </div>
                    {{-- Modal Footer --}}
                    <div class="modal-footer request-footer" id="request-footer">
                        @if (Auth::user()->role->id == 3)
                            <button class="btn border-0" id="editCancelButton">Cancel Request</button>
                            <button class="btn border-0 text-white" id="editApproveButton">Approve Request</button>
                        @else
                            <button class="btn border-0 text-white" id="editCancelButton">Cancel Request</button>
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