<!-- Edit User Modal -->
<div class="modal fade"
    id="editUserModal"
    tabindex="-1"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-create-user">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body pb-5 px-sm-5 pt-50">
                <div class="text-center mb-2">
                    <h1 class="mb-1">Update User</h1>
                    <p>Update User</p>
                </div>
                <form id="editUserForm"
                    class="row gy-1 pt-75"
                    action="">
                    <div class="col-12">
                        <div class="d-flex">
                            <a href="#"
                                class="me-25">
                                <img src="https://cdn3.iconfinder.com/data/icons/login-5/512/LOGIN_6-512.png"
                                    id="avatarPreview"
                                    class="uploadedAvatar rounded me-50"
                                    alt="profile image"
                                    height="100"
                                    width="100" />
                            </a>
                            <!-- upload and reset button -->
                            <div class="d-flex align-items-end mt-75 ms-1">
                                <div>
                                    <label for="avatar"
                                        class="btn btn-sm btn-primary mb-75 me-75">Upload</label>
                                    <input type="file"
                                        id="avatar"
                                        name="avatar"
                                        style="width: 0px; height: 0px;"
                                        accept="image/*" />
                                    <p class="mb-0">Allowed file types: png, jpg, jpeg.</p>
                                </div>
                            </div>
                            <!--/ upload and reset button -->
                        </div>
                    </div>
                    {{-- First Name --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label"
                            for="first_name">First Name</label>
                        <input type="text"
                            id="first_name"
                            name="first_name"
                            class="form-control"
                            placeholder="John" />
                    </div>

                    {{-- Last Name --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label"
                            for="last_name">Last Name</label>
                        <input type="text"
                            id="last_name"
                            name="last_name"
                            class="form-control" />
                    </div>

                    {{-- Email --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label"
                            for="email">Email:</label>
                        <input type="text"
                            id="email"
                            name="email"
                            class="form-control" />
                    </div>

                    {{-- UserName --}}
                    <div class="col-6">
                        <label class="form-label"
                            for="username">Username:</label>
                        <input type="text"
                            id="username"
                            name="username"
                            class="form-control" />
                    </div>

                    {{-- Phone --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label"
                            for="phone">Phone</label>
                        <input type="text"
                            id="phone"
                            name="phone"
                            class="form-control"
                            placeholder="0399898559" />
                    </div>

                    {{-- Role --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label"
                            for="role_id">Role</label>
                        <select id="role_id"
                            name="role_id"
                            class="select2 form-select">
                            <option value="">Select Role</option>
                        </select>
                    </div>

                    {{-- Address --}}
                    <div class="col-12">
                        <label class="form-label"
                            for="address">Address</label>
                        <input type="text"
                            id="address"
                            name="address"
                            class="form-control" />
                    </div>

                    {{-- New Password --}}
                    <div class="col-12 col-sm-6 mb-1">
                        <label class="form-label"
                            for="password">Password</label>
                        <div class="input-group form-password-toggle input-group-merge">
                            <input type="password"
                                id="password"
                                name="password"
                                class="form-control"
                                placeholder="Enter password" />
                            <div class="input-group-text cursor-pointer">
                                <i data-feather="eye"></i>
                            </div>
                        </div>
                    </div>

                    {{-- Confirm Password --}}
                    <div class="col-12 col-sm-6 mb-1">
                        {{-- Confirm new password --}}
                        <label class="form-label"
                            for="confirm_password">Retype Password</label>
                        <div class="input-group form-password-toggle input-group-merge">
                            <input type="password"
                                class="form-control"
                                id="confirm_password"
                                name="confirm_password"
                                placeholder="Confirm your new password" />
                            <div class="input-group-text cursor-pointer"><i data-feather="eye"></i></div>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="col-6">
                        <div class="mt-1">
                            <label class="form-check-label fw-bolder"
                                for="status">Status</label>
                            <div class="form-check form-switch form-check-primary">
                                <input type="checkbox"
                                    name="status"
                                    class="form-check-input"
                                    id="status"
                                    value="1" />
                                <label class="form-check-label"
                                    for="status">
                                    <span class="switch-icon-left"><i data-feather="check"></i></span>
                                    <span class="switch-icon-right"><i data-feather="x"></i></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Gender --}}
                    <div class="col-12 col-sm-6 mb-1">
                        <label class="d-block form-label">Gender</label>
                        <div class="form-check my-50">
                            <input type="radio"
                                id="gender_male"
                                name="gender"
                                class="form-check-input"
                                value="{{ \App\Models\User::GENDER_MALE }}" />
                            <label class="form-check-label"
                                for="gender_male">Nam</label>
                        </div>
                        <div class="form-check">
                            <input type="radio"
                                id="gender_female"
                                name="gender"
                                class="form-check-input"
                                value="{{ \App\Models\User::GENDER_FEMALE }}" />
                            <label class="form-check-label"
                                for="gender_female">Nữ</label>
                        </div>
                        <span id="gender_error"></span>
                    </div>

                    {{-- Submit --}}
                    <div class="col-12 text-center mt-2 pt-50">
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
            </div>
        </div>
    </div>
</div>
<!--/ Edit User Modal -->
