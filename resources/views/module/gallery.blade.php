<x-main>
    <h2 class="fw-bold mb-5">{{ $compliance->compliance_name }}</h2>

    <div>
        <div class="card-lg">
            @if(empty($groupedByDeadline))
                <div class="alert alert-info m-0" role="alert">
                    No deadlines available.
                </div>
            @else

                @foreach($groupedByDeadline as $deadline => $monthlyCompliances)

                    {{-- Deadline and Icon --}}
                    <div class="deadline-toggle d-flex justify-content-between align-items-center text-decoration-none clickable" data-toggle="collapse" data-target="#collapse-{{ $loop->index }}">
                        <p class="deadline m-0 p-0">
                            {{ $deadline }}
                        </p>
                        <i class="fas fa-chevron-down ml-2"></i>
                    </div>
                    
                    {{-- Image --}}
                    <div id="collapse-{{ $loop->index }}" class="collapse">
                        <div class="file-container">
                            <div class="d-flex">
                                @foreach($monthlyCompliances as $monthlyCompliance)
                                    @if(empty($monthlyCompliance->file_path))
                                        <div class="alert alert-info block">
                                            There are currently no uploaded files.
                                        </div>
                                    @else
                                        @foreach($monthlyCompliance->file_path as $file)
                                            <div class="file-container">
                                                <div class="">
                                                    <a href="{{ asset($file) }}" target="_blank">
                                                        @if (strpos($file, '.pdf') !== false)
                                                            <img src="{{ asset('images/pdf.png') }}" class="square-image" alt="PDF File">
                                                        @else
                                                            <img src="{{ asset($file) }}" class="square-image" alt="File" onerror="this.onerror=null; this.src='{{ asset('path/to/default-image.png') }}';">
                                                        @endif
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach

            @endif
        </div>
    </div>

 
</x-main>


<style>
    .square-image {
        width: 150px;
        height: 150px;
        object-fit: cover !important;
        border: none !important;
    }

    .card-lg {
        padding: 25px !important;
    }

    .deadline-toggle {
        border-top: 1px solid #E7EAEE !important;
        border-bottom: 1px solid #E7EAEE !important;
        padding: 15px 25px !important;
        cursor: pointer; /* Change cursor to pointer */
    }

    .deadline-toggle.active {
       background-color: #EDF2FF !important;
    }

    .deadline {
        color: #131920 !important;
    }

    .deadline.active,
    .icon-active
    {
        color: #4680FF !important;
    }

    .file-container {
        padding: 15px 7.5px !important;
    }


</style>

<script>
    // Show/Hide Image
    document.querySelectorAll('.clickable').forEach(item => {
        item.addEventListener('click', event => {
            const target = document.querySelector(item.getAttribute('data-target'));
            target.classList.toggle('collapse');
        });
    });

    // Togggle Icon
    document.addEventListener("DOMContentLoaded", function () {
        const deadlineToggles = document.querySelectorAll(".deadline-toggle");

        deadlineToggles.forEach(toggle => {
            toggle.addEventListener("click", function () {
                const icon = this.querySelector("i");
                const deadlineText = this.querySelector('.deadline');

                if (icon.classList.contains("fa-chevron-down")) {
                    icon.classList.remove("fa-chevron-down");
                    icon.classList.add("fa-chevron-up");
                    icon.classList.add('icon-active'); // Remove icon color class
                    deadlineText.classList.add('active'); // Change to your desired color class
                    toggle.classList.add('active'); // Change to your desired color class
                } else {
                    icon.classList.remove("fa-chevron-up");
                    icon.classList.add("fa-chevron-down");
                    icon.classList.remove('icon-active'); // Remove icon color class
                    deadlineText.classList.remove('active'); // Change to your desired color class
                    toggle.classList.remove('active'); // Change to your desired color class
                }
            });
        });
    });
</script>