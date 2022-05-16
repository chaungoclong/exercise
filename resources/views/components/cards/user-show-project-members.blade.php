 {{-- Project Members --}}
 <div class="col-7">
     <div class="card">
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
                 <div class="row">
                     <div class="col-12">
                         <div class="table-responsive">
                             <table class="table project-members-table">
                                 <thead>
                                     <th>User</th>
                                     <th>Position</th>
                                 </thead>
                                 <tbody>
                                     @foreach ($userWithPositions as $row)
                                         <tr>
                                             <td>
                                                 <x-cards.user-td :user="$row['user']" />
                                             </td>
                                             <td>
                                                 @foreach ($row['positions'] as $position)
                                                     <span class="badge bg-info mb-1">{{ $position->name }}</span>
                                                 @endforeach
                                             </td>
                                         </tr>
                                     @endforeach
                                 </tbody>
                             </table>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </div>
 {{-- /Project Members --}}
