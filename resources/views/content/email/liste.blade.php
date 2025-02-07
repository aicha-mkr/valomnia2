@extends('layouts/contentNavbarLayout')

@section('title', 'Liste des Modèles d\'E-mail')

@section('content')
<div class="col-md-12">
    <div class="nav-align-top">
        <ul class="nav nav-pills flex-column flex-md-row mb-6">
            <li class="nav-item"><a class="nav-link " href="{{url('pages/account-settings-account')}}"><i class="bx bx-sm bx-user me-1_5"></i> Account</a></li>
            <li class="nav-item"><a class="nav-link" href="{{url('pages/account-settings-notifications')}}"><i class="bx bx-sm bx-bell me-1_5"></i> Notifications</a></li>
            <li class="nav-item"><a class="nav-link" href="{{url('email/create')}}"><i class="bx bx-sm bx-envelope me-1_5"></i> Create Email</a></li>
            <li class="nav-item"><a class="nav-link active" href="{{url('email/liste')}}"><i class="bx bx-sm bx-envelope me-1_5"></i> Liste</a></li>
        </ul>

        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h5>Email Template Manager</h5>
                        <p>A list of all email templates including reports and alerts.</p>
                    </div>
                    <a href="{{url('email/create')}}" class="btn btn-primary">
                        <i class="bx bx-plus"></i> New Template
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Last Updated</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Monthly Performance Report</td>
                                <td><span class="badge bg-label-primary">Report</span></td>
                                <td>10/08/2024</td>
                                <td>
                                    <button class="btn p-0 text-primary" onclick="editRecord('Monthly Performance Report', 'Report', '10/08/2024')">
                                        <i class="bx bx-edit-alt"></i>
                                    </button>
                                    <button class="btn p-0 text-danger" onclick="deleteRecord('Monthly Performance Report')">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>Critical System Alert</td>
                                <td><span class="badge bg-label-warning">Alert</span></td>
                                <td>10/08/2024</td>
                                <td>
                                    <button class="btn p-0 text-primary" onclick="editRecord('Critical System Alert', 'Alert', '10/08/2024')">
                                        <i class="bx bx-edit-alt"></i>
                                    </button>
                                    <button class="btn p-0 text-danger" onclick="deleteRecord('Critical System Alert')">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <!-- Ajoutez d'autres modèles d'e-mail ici -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Offcanvas pour éditer un enregistrement -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="editRecord" aria-labelledby="editRecordLabel">
            <div class="offcanvas-header">
                <h5 id="editRecordLabel">Edit Record</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <form id="editForm">
                    <div class="mb-3">
                        <label for="editTitle" class="form-label">Title</label>
                        <input type="text" class="form-control" id="editTitle" placeholder="Title">
                    </div>
                    <div class="mb-3">
                        <label for="editType" class="form-label">Type</label>
                        <input type="text" class="form-control" id="editType" placeholder="Type">
                    </div>
                    <div class="mb-3">
                        <label for="editDate" class="form-label">Last Updated</label>
                        <input type="date" class="form-control" id="editDate">
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function editRecord(title, type, lastUpdated) {
        // Remplir les champs du formulaire d'édition avec les données de l'enregistrement
        document.getElementById('editTitle').value = title;
        document.getElementById('editType').value = type;
        document.getElementById('editDate').value = lastUpdated;

        // Ouvrir l'offcanvas d'édition
        var editOffcanvas = new bootstrap.Offcanvas(document.getElementById('editRecord'));
        editOffcanvas.show();
    }

    function deleteRecord(title) {
        // Logique de suppression à mettre en œuvre
        if (confirm('Are you sure you want to delete "' + title + '"?')) {
            // Effectuer la suppression ici
            console.log('Deleted: ' + title);
        }
    }
</script>

@endsection
