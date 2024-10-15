<div>
    <!-- Modal -->
    <div class="modal fade modal-lg" id="deleteComplianceModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                {{-- Modal Header --}}
                <div class="modal-header">
                    <h5 class="modal-title fw-semibold" id="staticBackdropLabel">Are you sure you want to delete this <span id="dComplianceName" class="fw-semibold fst-italic">Compliance Name</span> Compliance?</h5>
                    
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

               {{-- Modal Body --}}
               <div class="modal-body p-0 m-0">


                <form action="" method="post" id="deleteComplianceForm">
                    @csrf
                    @method('DELETE')

                    <input type="hidden" name="complianceId" id="complianceId">


                    {{-- Modal Footer --}}
                    <div class="modal-footer border-0">
                        <button type="button" class="btn border-0 close-btn" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn border-0 text-white delete-compliance-button" form="deleteComplianceForm">Delete</button>
                    </div>

                </form>

                    

                </div>
            </div>
        </div>
    </div>
</div>