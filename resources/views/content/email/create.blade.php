@extends('layouts.contentNavbarLayout')

@section('title', 'Créer un Nouveau Template')

@section('content')
<div class="col-md-12">
    <div class="nav-align-top">
        <ul class="nav nav-pills flex-column flex-md-row mb-6">
            <li class="nav-item"><a class="nav-link" href="javascript:void(0);"><i class="bx bx-sm bx-user me-1_5"></i> Account</a></li>
            <li class="nav-item"><a class="nav-link" href="{{url('pages/account-settings-notifications')}}"><i class="bx bx-sm bx-bell me-1_5"></i> Notifications</a></li>
            <li class="nav-item"><a class="nav-link active" href="{{url('email/create')}}"><i class="bx bx-sm bx-envelope me-1_5"></i> Create Email</a></li>
            <li class="nav-item"><a class="nav-link" href="{{url('email/liste')}}"><i class="bx bx-sm bx-envelope me-1_5"></i> Liste</a></li>
        </ul>
    </div>

    
    <h5>Créer un Nouveau Template Mail</h5>

                    <p class="text-center mb-4">

                        <a class="btn btn-primary me-1" data-bs-toggle="collapse" href="#collapseRapport" role="button" aria-expanded="true" onclick="toggleCollapse('collapseRapport')">
                            Rapport
                        </a>
                        <a class="btn btn-warning me-1" data-bs-toggle="collapse" href="#collapseAlerte" role="button" aria-expanded="false" onclick="toggleCollapse('collapseAlerte')">
                            Alerte
                        </a>
                    </p>
               

        <div class="col-12">
            <div class="collapse show" id="collapseRapport">
                <div class="card mb-6">
                    <h5 class="card-header">Formulaire de Rapport</h5>
                    <div class="card-body">
                        <form>
                            <div class="mb-4">
                                <label for="rapport-title" class="form-label">Titre</label>
                                <input type="text" class="form-control" id="rapport-title" placeholder="Titre du rapport" required />
                            </div>
                            <div class="mb-4">
                                <label for="rapport-description" class="form-label">Description</label>
                                <textarea class="form-control" id="rapport-description" rows="3" placeholder="Ajouter des informations supplémentaires" required></textarea>
                            </div>
                            <div class="mb-4">
                                <label for="rapport-email-header" class="form-label">En-tête d'Email</label>
                                <input type="text" class="form-control" id="rapport-email-header" placeholder="En-tête de l'email" required />
                            </div>
                            <div class="mb-4">
                                <label for="rapport-email-footer" class="form-label">Pied de page d'Email</label>
                                <input type="text" class="form-control" id="rapport-email-footer" placeholder="Pied de page de l'email" required />
                            </div>
                            <div class="mb-4">
                                <label for="rapport-available-reports" class="form-label">Rapports Disponibles</label>
                                <select class="form-select" id="rapport-available-reports" aria-label="Rapports Disponibles">
                                    <option selected>Choisir un rapport</option>
                                    <option value="performance">Performance Metrics</option>
                                    <option value="financial">Financial Summary</option>
                                    <option value="user-analytics">User Analytics</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <button type="submit" class="btn btn-primary">Créer le Rapport</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="collapse" id="collapseAlerte">
                <div class="card mb-6">
                    <h5 class="card-header">Formulaire d'Alerte</h5>
                    <div class="card-body">
                        <form>
                            <div class="mb-4">
                                <label for="alerte-title" class="form-label">Titre</label>
                                <input type="text" class="form-control" id="alerte-title" placeholder="Titre de l'alerte" required />
                            </div>
                            <div class="mb-4">
                                <label for="alerte-description" class="form-label">Description</label>
                                <textarea class="form-control" id="alerte-description" rows="3" placeholder="Ajouter des informations supplémentaires" required></textarea>
                            </div>
                            <div class="mb-4">
                                <label for="alerte-email-header" class="form-label">En-tête d'Email</label>
                                <input type="text" class="form-control" id="alerte-email-header" placeholder="En-tête de l'email" required />
                            </div>
                            <div class="mb-4">
                                <label for="alerte-email-footer" class="form-label">Pied de page d'Email</label>
                                <input type="text" class="form-control" id="alerte-email-footer" placeholder="Pied de page de l'email" required />
                            </div>
                            <div class="mb-4">
                                <label for="alerte-available-reports" class="form-label">Rapports Disponibles</label>
                                <select class="form-select" id="alerte-available-reports" aria-label="Rapports Disponibles">
                                    <option selected>Choisir un rapport</option>
                                    <option value="performance">Performance Metrics</option>
                                    <option value="financial">Financial Summary</option>
                                    <option value="user-analytics">User Analytics</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <button type="submit" class="btn btn-primary">Créer l'Alerte</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleCollapse(targetId) {
        const rapport = document.getElementById('collapseRapport');
        const alerte = document.getElementById('collapseAlerte');

        if (targetId === 'collapseRapport') {
            alerte.classList.remove('show');
            rapport.classList.add('show');
        } else if (targetId === 'collapseAlerte') {
            rapport.classList.remove('show');
            alerte.classList.add('show');
        }
    }
</script>

@endsection