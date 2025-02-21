@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/apex-charts/apex-charts.css')}}">
<link rel="stylesheet" href="{{asset('assets/css/app-logistics-dashboard.css')}}">
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/apex-charts/apexcharts.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/app-logistics-dashboard.js')}}"></script>

@endsection

@section('content')
<div class="row">
  <div class="col-sm-6 col-lg-3 mb-4">
    <div class="card card-border-shadow-primary h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-primary">

                <i class="bx bxs-alarm-snooze"></i>
             </span>
          </div>
          <h4 class="ms-1 mb-0">42</h4>
        </div>
        <p class="mb-1">Sent alerts</p>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-lg-3 mb-4">
    <div class="card card-border-shadow-danger h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-danger"><i class="bx bx-alarm-exclamation"></i></span>
          </div>
          <h4 class="ms-1 mb-0">8</h4>
        </div>
        <p class="mb-1">Sent alerts with errors</p>

      </div>
    </div>
  </div>
  <div class="col-sm-6 col-lg-3 mb-4">
    <div class="card card-border-shadow-success h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-success"><i class="bx bx-user-plus"></i></span>
          </div>
          <h4 class="ms-1 mb-0">27</h4>
        </div>
        <p class="mb-1">New users</p>

      </div>
    </div>
  </div>
  <div class="col-sm-6 col-lg-3 mb-4">
    <div class="card card-border-shadow-info h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-info"><i class="bx bx-time-five"></i></span>
          </div>
          <h4 class="ms-1 mb-0">13</h4>
        </div>
        <p class="mb-1">Scheduled alert</p>

      </div>
    </div>
  </div>
</div>
<div class="row">

  <!-- Shipment statistics-->
  <div class="col-lg-12 col-xxl-12 mb-4 order-3 order-xxl-1">
    <div class="card h-100">
      <div class="card-header d-flex align-items-center justify-content-between">
        <div class="card-title mb-0">
          <h5 class="m-0 me-2">Alerts statistics</h5>
          <small class="text-muted">Total number of alert 230</small>
        </div>
        <div class="dropdown">
          <button type="button" class="btn btn-label-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">June</button>
          <ul class="dropdown-menu">
            <li>
              <a class="dropdown-item" href="javascript:void(0);">January</a>
            </li>
            <li>
              <a class="dropdown-item" href="javascript:void(0);">February</a>
            </li>
            <li>
              <a class="dropdown-item" href="javascript:void(0);">March</a>
            </li>
            <li>
              <a class="dropdown-item" href="javascript:void(0);">April</a>
            </li>
            <li>
              <a class="dropdown-item" href="javascript:void(0);">May</a>
            </li>
            <li>
              <a class="dropdown-item" href="javascript:void(0);">June</a>
            </li>
            <li>
              <a class="dropdown-item" href="javascript:void(0);">July</a>
            </li>
            <li>
              <a class="dropdown-item" href="javascript:void(0);">August</a>
            </li>
            <li>
              <a class="dropdown-item" href="javascript:void(0);">September</a>
            </li>
            <li>
              <a class="dropdown-item" href="javascript:void(0);">October</a>
            </li>
            <li>
              <a class="dropdown-item" href="javascript:void(0);">November</a>
            </li>
            <li>
              <a class="dropdown-item" href="javascript:void(0);">December</a>
            </li>
          </ul>
        </div>
      </div>
      <div class="card-body" style="position: relative;">
        <div id="shipmentStatisticsChart" style="min-height: 270px;">

        </div>
        <div class="resize-triggers">
          <div class="expand-trigger">
            <div style="width: 682px; height: 295px;"></div>
          </div>
          <div class="contract-trigger"></div>
        </div>
      </div>
    </div>
  </div>
  <!--/ Programed alerts statistics -->


  <!-- On route vehicles Table -->
  <div class="col-12 order-5">
    <div class="card">
      <div class="card-header d-flex align-items-center justify-content-between">
        <div class="card-title mb-0">
          <h5 class="m-0 me-2">Today programed alerts </h5>
        </div>
        <div class="dropdown">
          <button class="btn p-0" type="button" id="routeVehicles" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="bx bx-dots-vertical-rounded"></i>
          </button>
          <div class="dropdown-menu dropdown-menu-end" aria-labelledby="routeVehicles">
            <a class="dropdown-item" href="javascript:void(0);">Select All</a>
            <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
            <a class="dropdown-item" href="javascript:void(0);">Share</a>
          </div>
        </div>
      </div>
      <div class="card-datatable table-responsive">
        <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
          <div class="table-responsive">
            <table class="dt-route-vehicles table dataTable no-footer dtr-column" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 1391px;">
              <thead class="border-top">
                <tr>
                  <th class="control sorting_disabled dtr-hidden" rowspan="1" colspan="1" style="width: 0px; display: none;" aria-label=""></th>
                  <th class="sorting_disabled dt-checkboxes-cell dt-checkboxes-select-all" rowspan="1" colspan="1" style="width: 18px;" data-col="1" aria-label="">
                    <input type="checkbox" class="form-check-input">
                  </th>
                  <th class="sorting sorting_asc" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 155px;" aria-label="location: activate to sort column descending" aria-sort="ascending">organisation</th>
                  <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 250px;" aria-label="starting route: activate to sort column ascending">Date last alert</th>
                  <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 247px;" aria-label="ending route: activate to sort column ascending">Date next alert</th>
                  <th class="w-20 sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 242px;" aria-label="progress: activate to sort column ascending">progress</th>
                </tr>
              </thead>
              <tbody>
                <tr class="odd">
                  <td class="  control" tabindex="0" style="display: none;"></td>
                  <td class="  dt-checkboxes-cell">
                    <input type="checkbox" class="dt-checkboxes form-check-input">
                  </td>
                  <td class="sorting_1">
                    <div class="d-flex justify-content-start align-items-center user-name">
                      <div class="avatar-wrapper">
                        <div class="avatar me-2">
                          <span class="avatar-initial rounded-circle bg-label-secondary">

                            <i class="bx bxs-buildings"></i>
                          </span>
                        </div>
                      </div>
                      <div class="d-flex flex-column">
                        Cosmetic
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="text-body">1 hours ago</div>
                  </td>
                  <td>
                    <div class="text-body">00:00 </div>
                  </td>

                  <td>
                    <div class="d-flex align-items-center">
                      <div div="" class="progress w-100" style="height: 8px;">
                        <div class="progress-bar" role="progressbar" style="width:60%;" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                      <div class="text-body ms-3">60%</div>
                    </div>
                  </td>
                </tr>
                <tr class="even">
                  <td class="  control" tabindex="0" style="display: none;"></td>
                  <td class="  dt-checkboxes-cell">
                    <input type="checkbox" class="dt-checkboxes form-check-input">
                  </td>
                  <td class="sorting_1">
                    <div class="d-flex justify-content-start align-items-center user-name">
                      <div class="avatar-wrapper">
                        <div class="avatar me-2">
                          <span class="avatar-initial rounded-circle bg-label-secondary">
                            <i class="bx bxs-buildings"></i>
                          </span>
                        </div>
                      </div>
                      <div class="d-flex flex-column">
                       Agro
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="text-body">2 hours ago</div>
                  </td>
                  <td>
                    <div class="text-body">15:00</div>
                  </td>

                  <td>
                    <div class="d-flex align-items-center">
                      <div div="" class="progress w-100" style="height: 8px;">
                        <div class="progress-bar" role="progressbar" style="width:82%;" aria-valuenow="82" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                      <div class="text-body ms-3">82%</div>
                    </div>
                  </td>
                </tr>
                <tr class="odd">
                  <td class="  control" tabindex="0" style="display: none;"></td>
                  <td class="  dt-checkboxes-cell">
                    <input type="checkbox" class="dt-checkboxes form-check-input">
                  </td>
                  <td class="sorting_1">
                    <div class="d-flex justify-content-start align-items-center user-name">
                      <div class="avatar-wrapper">
                        <div class="avatar me-2">
                          <span class="avatar-initial rounded-circle bg-label-secondary">
                            <i class="bx bxs-buildings"></i>
                          </span>
                        </div>
                      </div>
                      <div class="d-flex flex-column">
                        Lepidor
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="text-body">10:00 </div>
                  </td>
                  <td>
                    <div class="text-body">12:00</div>
                  </td>

                  <td>
                    <div class="d-flex align-items-center">
                      <div div="" class="progress w-100" style="height: 8px;">
                        <div class="progress-bar" role="progressbar" style="width:30%;" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                      <div class="text-body ms-3">30%</div>
                    </div>
                  </td>
                </tr>
                <tr class="even">
                  <td class="  control" tabindex="0" style="display: none;"></td>
                  <td class="  dt-checkboxes-cell">
                    <input type="checkbox" class="dt-checkboxes form-check-input">
                  </td>
                  <td class="sorting_1">
                    <div class="d-flex justify-content-start align-items-center user-name">
                      <div class="avatar-wrapper">
                        <div class="avatar me-2">
                          <span class="avatar-initial rounded-circle bg-label-secondary">
                            <i class="bx bxs-buildings"></i>
                          </span>
                        </div>
                      </div>
                      <div class="d-flex flex-column">
                        ToyTeam
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="text-body">06:00</div>
                  </td>
                  <td>
                    <div class="text-body">20:00</div>
                  </td>

                  <td>
                    <div class="d-flex align-items-center">
                      <div div="" class="progress w-100" style="height: 8px;">
                        <div class="progress-bar" role="progressbar" style="width:90%;" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                      <div class="text-body ms-3">90%</div>
                    </div>
                  </td>
                </tr>
                <tr class="odd">
                  <td class="  control" tabindex="0" style="display: none;"></td>
                  <td class="  dt-checkboxes-cell">
                    <input type="checkbox" class="dt-checkboxes form-check-input">
                  </td>
                  <td class="sorting_1">
                    <div class="d-flex justify-content-start align-items-center user-name">
                      <div class="avatar-wrapper">
                        <div class="avatar me-2">
                          <span class="avatar-initial rounded-circle bg-label-secondary">
                            <i class="bx bxs-buildings"></i>
                          </span>
                        </div>
                      </div>
                      <div class="d-flex flex-column">
                         STIEL
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="text-body">02:00</div>
                  </td>
                  <td>
                    <div class="text-body">21:00</div>
                  </td>

                  <td>
                    <div class="d-flex align-items-center">
                      <div div="" class="progress w-100" style="height: 8px;">
                        <div class="progress-bar" role="progressbar" style="width:24%;" aria-valuenow="24" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                      <div class="text-body ms-3">24%</div>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="row d-flex align-items-center">
            <div class="col-sm-12 col-md-6">
              <div class="dataTables_info pt-0" id="DataTables_Table_0_info" role="status" aria-live="polite">Showing 1 to 5 of 25 entries</div>
            </div>
            <div class="col-sm-12 col-md-6">
              <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
                <ul class="pagination">
                  <li class="paginate_button page-item previous disabled" id="DataTables_Table_0_previous">
                    <a aria-controls="DataTables_Table_0" aria-disabled="true" role="link" data-dt-idx="previous" tabindex="0" class="page-link">Previous</a>
                  </li>
                  <li class="paginate_button page-item active">
                    <a href="#" aria-controls="DataTables_Table_0" role="link" aria-current="page" data-dt-idx="0" tabindex="0" class="page-link">1</a>
                  </li>
                  <li class="paginate_button page-item ">
                    <a href="#" aria-controls="DataTables_Table_0" role="link" data-dt-idx="1" tabindex="0" class="page-link">2</a>
                  </li>
                  <li class="paginate_button page-item ">
                    <a href="#" aria-controls="DataTables_Table_0" role="link" data-dt-idx="2" tabindex="0" class="page-link">3</a>
                  </li>
                  <li class="paginate_button page-item ">
                    <a href="#" aria-controls="DataTables_Table_0" role="link" data-dt-idx="3" tabindex="0" class="page-link">4</a>
                  </li>
                  <li class="paginate_button page-item ">
                    <a href="#" aria-controls="DataTables_Table_0" role="link" data-dt-idx="4" tabindex="0" class="page-link">5</a>
                  </li>
                  <li class="paginate_button page-item next" id="DataTables_Table_0_next">
                    <a href="#" aria-controls="DataTables_Table_0" role="link" data-dt-idx="next" tabindex="0" class="page-link">Next</a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
