<div>
    <!-- Modal -->
    <div class="modal fade" id="eventComplianceModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

              {{-- Modal Header --}}
              <div class="modal-header">
                <h4 class="modal-title fw-semibold" id="staticBackdropLabel" id="eComplianceNameTitle">Compliance Details</h4>
                
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- Modal Body --}}
            <div class="modal-body p-0 m-0">

                {{-- Form --}}
                <div class="card-form">
                    <form action="" method="post" enctype="multipart/form-data" id="editComplianceForm">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="id">

                        <div class="row">

                            <!-- Compliance Name -->
                            <div class="col-md-12 mb-md-4 mb-4">
                                <h5 class="fw-semibold">Compliance Name</h5>
                                <p id="eComplianceName">Compliance Name</p>
                            </div>
                            
                            <!-- Department Name -->
                            <div class="col-md-12 mb-md-4 mb-4">
                                <h5 class="fw-semibold">Department Name</h5>
                                <p id="eDepartmentName">Department Name</p>
                            </div>

                            <!-- Start Working On -->
                            <div class="col-md-12 mb-md-4 mb-4">
                                <h5 class="fw-semibold">Start Working On</h5>
                                <p id="eStartDate">Start Working On</p>
                            </div>

                            <!-- Submit On -->
                            <div class="col-md-12 mb-md-4 mb-4">
                                <h5 class="fw-semibold">Submit On</h5>
                                <p id="eSubmitOn">Submit On</p>
                            </div>

                            <!-- Deadline -->
                            <div class="col-md-12 mb-md-4 mb-4">
                                <h5 class="fw-semibold">Deadline</h5>
                                <p id="eDeadline">Deadline</p>
                            </div>

                            <!-- Days Left -->
                            <div class="col-md-12">
                                <h5 class="fw-semibold">Days Left</h5>
                                <p id="eDaysLeft">Days Left</p>
                            </div>

                        </div>

                        {{-- Modal Footer --}}
                        <div class="modal-footer">
                            <button type="button" class="btn border-0 close-btn text-white" data-bs-dismiss="modal">Close</button>
                        </div>
                        
                    </form>
                </div>
            </div>

            </div>
        </div>
    </div>
</div>

<style>
    h3 {
        font-size: 24px !important;
    }

    h5 {
        font-size: 16px !important;
    }
</style>