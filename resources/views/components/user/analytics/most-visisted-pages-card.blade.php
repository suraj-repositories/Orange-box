 <div class="card overflow-hidden">

     <div class="card-header">
         <div class="d-flex align-items-center">
             <div class="border border-dark rounded-2 me-2 widget-icons-sections">
                 <i data-feather="table" class="widgets-icons"></i>
             </div>
             <h5 class="card-title mb-0">Most Visited Pages</h5>
         </div>
     </div>

     <div class="card-body p-0">
         <div class="table-responsive">
             <table class="table table-traffic mb-0">
                 <tbody>

                     <thead>
                         <tr>
                             <th>Page name</th>
                             <th>Visitors</th>
                             <th>Unique</th>
                             <th colspan="2">Bounce rate</th>
                         </tr>
                     </thead>

                     @forelse ($rows as $row)
                         <tr>
                             <td>
                                 {{ $row['path'] }}
                                 <a href="{{ $row['url'] }}" class="ms-1" target="_blank">
                                     <i data-feather="link" class="ms-1 text-primary"
                                         style="height: 15px; width: 15px;"></i>
                                 </a>
                             </td>
                             <td>{{ $row['views'] }}</td>
                             <td>{{ $row['unique'] }}</td>
                             <td>{{ $row['bounce_rate'] }}</td>
                             <td class="w-25">
                                 <div id="{{ $row['sparkline_id'] }}" class="apex-charts sparkline-bounce"
                                     data-values='@json($row['bounce_trend'])'
                                     data-labels='@json($row['labels'])'></div>
                             </td>
                         </tr>
                     @empty
                         <tr>
                             <td colspan="5" class="py-5">
                                 <x-no-data message="No Data Yet."/>
                             </td>
                         </tr>
                     @endforelse

                 </tbody>
             </table>
         </div>
     </div>

 </div>
