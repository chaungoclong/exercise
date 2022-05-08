<!-- Edit Position Modal -->
<div class="modal fade" id="editPositionModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered modal-add-new-position">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-5 pb-5">
                <div class="text-center mb-4">
                    <h1 class="position-title">Edit Position</h1>
                </div>
                <!-- Add Position form -->
                <form id="editPositionForm" class="row" action="">
                    <div class="col-12">
                        <label class="form-label" for="name">Position Name</label>
                        <input type="text" id="name" name="name" class="form-control"
                            placeholder="Enter position name" tabindex="-1" />
                    </div>

                    <div class="col-12">
                        <label class="form-label" for="slug">Position Slug</label>
                        <input type="text" id="slug" name="slug" class="form-control"
                            placeholder="Enter position slug" tabindex="-1" />
                    </div>

                    <div class="col-12 text-center mt-2">
                        <button type="submit" class="btn btn-primary me-1">Submit</button>
                        <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                            aria-label="Close">
                            Discard
                        </button>
                    </div>
                </form>
                <!--/ Add Position form -->
            </div>
        </div>
    </div>
</div>
<!--/ Edit Position Modal -->
