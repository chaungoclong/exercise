<!-- Add Role Modal -->
<div class="modal fade"
    id="createRoleModal"
    data-bs-backdrop="static"
    tabindex="-1"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-add-new-role">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body px-5 pb-5">
                <div class="text-center mb-4">
                    <h1 class="role-title">Add New Role</h1>
                    <p>Set role permissions</p>
                </div>
                <!-- Add role form -->
                <form id="createRoleForm"
                    class="row"
                    action="{{ route('roles.store') }}">
                    <div class="col-6">
                        <label class="form-label"
                            for="name">Role Name</label>
                        <input type="text"
                            id="name"
                            name="name"
                            class="form-control"
                            placeholder="Enter role name"
                            tabindex="-1" />
                    </div>
                    <div class="col-6">
                        <label class="form-label"
                            for="slug">Role Slug</label>
                        <input type="text"
                            id="slug"
                            name="slug"
                            class="form-control"
                            placeholder="Enter role slug"
                            tabindex="-1" />
                    </div>
                    <div class="col-12">
                        <h4 class="mt-2 pt-50">Role Permissions</h4>
                        <select name="permissions[]"
                            id="permissions"
                            class="form-select select2"
                            multiple>
                        </select>
                    </div>
                    <div class="col-12 text-center mt-2">
                        <button type="submit"
                            class="btn btn-primary me-1">Submit</button>
                        <button type="reset"
                            class="btn btn-outline-secondary"
                            data-bs-dismiss="modal"
                            aria-label="Close">
                            Discard
                        </button>
                    </div>
                </form>
                <!--/ Add role form -->
            </div>
        </div>
    </div>
</div>
<!--/ Add Role Modal -->
