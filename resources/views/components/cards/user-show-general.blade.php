 {{-- General --}}
 <div class="col-5">
     <div class="card">
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
                 <div class="info-container">
                     <ul class="list-unstyled">
                         <li class="mb-75">
                             <span class="fw-bolder me-25">Name:</span>
                             <span>{{ $project->name }}</span>
                         </li>
                         <li class="mb-75">
                             <span class="fw-bolder me-25">Slug:</span>
                             <span>{{ $project->slug }}</span>
                         </li>
                         <li class="mb-75">
                             <span class="fw-bolder me-25">Start Date:</span>
                             <span>{{ $project->start_date_text }}</span>
                         </li>
                         <li class="mb-75">
                             <span class="fw-bolder me-25">Deadline:</span>
                             <span>{{ $project->deadline_text }}</span>
                         </li>
                         <li class="mb-75">
                             <span class="fw-bolder me-25">Status:</span>
                             <span class=" {{ config('constants.project_status.' . $project->status . '.badge') }}">
                                 {{ config('constants.project_status.' . $project->status . '.name') }}
                             </span>
                         </li>
                         <li class="mb-75">
                             <span class="fw-bolder me-25">Details:</span>
                             <p>
                                 {{ $project->detail }}
                             </p>
                         </li>
                     </ul>
                     <div class="d-flex justify-content-center pt-2">
                         <a href="javascript:;"
                             class="btn btn-primary me-1 project-edit"
                             data-url-edit="{{ route('projects.edit', $project) }}"
                             data-url-update="{{ route('projects.update', $project) }}">
                             Edit
                         </a>
                         <a href="javascript:;"
                             class="btn btn-outline-danger project-delete"
                             data-url-delete="{{ route('projects.destroy', $project) }}">Delete</a>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </div>
 {{-- /General --}}
