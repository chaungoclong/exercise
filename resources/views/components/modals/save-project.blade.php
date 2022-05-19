<!-- Edit User Modal -->
<div class="modal fade"
    id="saveProjectModal"
    tabindex="-1"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-save-project">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <div class="fw-bolder text-white fs-2"
                    id="saveProjectModalLabel">
                </div>
                <button type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body pb-5 pt-50">
                <form id="saveProjectForm">
                    <div class="row">
                        <div class="col-12 p-0">
                            <div class="card shadow-none">
                                <div class="card-header">
                                    <h4 class="card-title">General</h4>
                                    <div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            <li>
                                                <a data-action="collapse"><i data-feather="chevron-down"></i></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-content collapse show">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 col-md-6 mb-1">
                                                <label class="form-label"
                                                    for="name">Name</label>
                                                <input type="text"
                                                    id="name"
                                                    name="name"
                                                    class="form-control" />
                                            </div>

                                            <div class="col-12 col-md-6 mb-1">
                                                <label class="form-label"
                                                    for="slug">Slug</label>
                                                <input type="text"
                                                    id="slug"
                                                    name="slug"
                                                    class="form-control" />
                                            </div>

                                            <div class="col-12 col-md-6 mb-1">
                                                <label class="form-label"
                                                    for="start_date">Start Date</label>
                                                <input type="text"
                                                    id="start_date"
                                                    name="start_date"
                                                    class="form-control" />
                                            </div>

                                            <div class="col-12 col-md-6 mb-1">
                                                <label class="form-label"
                                                    for="due_date">Deadline</label>
                                                <input type="text"
                                                    id="due_date"
                                                    name="due_date"
                                                    class="form-control" />
                                            </div>

                                            <div class="col-12 col-md-6 mb-1">
                                                <label class="form-label"
                                                    for="duration">Duration</label>
                                                <input type="number"
                                                    id="duration"
                                                    name="duration"
                                                    class="form-control" />
                                            </div>

                                            <div class="col-12 col-md-6 mb-1">
                                                <label class="form-label"
                                                    for="revenue">Revenue</label>
                                                <input type="number"
                                                    id="slug"
                                                    name="revenue"
                                                    class="form-control" />
                                            </div>

                                            <div class="col-12 col-md-6 mb-1">
                                                <label class="form-label"
                                                    for="status">Status</label>
                                                <select id="status"
                                                    name="status"
                                                    class="form-control select2">
                                                </select>
                                            </div>

                                            <div class="col-12 mb-1">
                                                <label class="form-label"
                                                    for="detail">Detail</label>
                                                <textarea placeholder="Detail"
                                                    class="form-control"
                                                    id="detail"
                                                    name="detail"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <div class="col-12 p-0">
                            <div class="card shadow-none">
                                <div class="card-header">
                                    <h4 class="card-title">Project Members</h4>
                                    <div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            <li>
                                                <a data-action="collapse"><i data-feather="chevron-down"></i></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-content collapse show">
                                    <div class="card-body">
                                        <div class="row mb-2">
                                            <div class="col-12">
                                                <button class="btn btn-outline-success"
                                                    type="button"
                                                    id="addRowInput">Add</button>
                                            </div>
                                        </div>
                                        <div class="wrapper-row-input">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- Submit --}}
                        <div class="col-12 text-center mt-2 pt-50">
                            <button type="submit"
                                class="btn btn-primary me-1"
                                id="saveBtn">
                                <span>Save</span>
                            </button>
                            <button type="reset"
                                class="btn btn-outline-secondary"
                                data-bs-dismiss="modal"
                                aria-label="Close">
                                Discard
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--/ Edit User Modal -->
