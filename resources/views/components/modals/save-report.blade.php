<!-- Edit User Modal -->
<div class="modal fade"
    id="saveReportModal"
    tabindex="-1"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <span class="fw-bolder fs-2 text-white">
                    Create Report
                </span>
                <button type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body pb-5 px-sm-5 pt-50">
                <form id="saveReportForm"
                    class="row gy-1 pt-75">
                    {{-- Project --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label"
                            for="project_id">Project</label>
                        <select id="project_id"
                            class="form-select text-capitalize mb-md-0 mb-2 select2"
                            name="project_id">
                            <option value=""></option>

                        </select>
                    </div>

                    {{-- Position --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label"
                            for="position_id">Position</label>
                        <select id="position_id"
                            class="form-select text-capitalize mb-md-0 mb-2 select2"
                            name="position_id">
                            <option value=""></option>
                        </select>
                    </div>

                    {{-- Working type --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label"
                            for="working_type">Working Type</label>
                        <select id="working_type"
                            class="form-select text-capitalize mb-md-0 mb-2 select2"
                            name="working_type">
                            <option value=""> Select Working Type </option>
                            @foreach (config('constants.working_type') as $key => $type)
                                <option value="{{ $key }}">{{ $type['name'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Working Time --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label"
                            for="working_time">Working Time:</label>
                        <input type="number"
                            id="working_time"
                            name="working_time"
                            class="form-control" />
                    </div>

                    {{-- Date --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label"
                            for="date">Date</label>
                        <input type="text"
                            id="date"
                            class="flatpickr form-select"
                            placeholder="dd-mm-yyyyy"
                            name="date">
                    </div>

                    {{-- Note --}}
                    <div class="col-12">
                        <label class="form-label"
                            for="note">Note</label>
                        <input type="text"
                            id="note"
                            name="note"
                            class="form-control" />
                    </div>

                    <input type="hidden"
                        name="user_id"
                        value="{{ auth()->id() }}">

                    {{-- Submit --}}
                    <div class="col-12 text-center mt-2 pt-50">
                        <button type="submit"
                            class="btn btn-primary me-1"
                            id="saveBtn">Submit</button>
                        <button type="reset"
                            class="btn btn-outline-secondary"
                            data-bs-dismiss="modal"
                            aria-label="Close">
                            Discard
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--/ Edit User Modal -->
