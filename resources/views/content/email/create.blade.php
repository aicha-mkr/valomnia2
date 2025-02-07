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
                            <div class="row mb-4">
                                <div class="col-6">
                                    <h5 class="card-header">Select KPIs to Include</h5>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="totalRevenueCheckbox" onclick="toggleCard('totalRevenueCard')" />
                                        <label class="form-check-label" for="totalRevenueCheckbox">
                                            Total Revenue
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="totalClientsCheckbox" onclick="toggleCard('totalClientsCard')" />
                                        <label class="form-check-label" for="totalClientsCheckbox">
                                            Total Customers
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="averageSalesCheckbox" onclick="toggleCard('averageSalesCard')" />
                                        <label class="form-check-label" for="averageSalesCheckbox">
                                            Average Sales
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="totalOrdersCheckbox" onclick="toggleCard('totalOrdersCard')" />
                                        <label class="form-check-label" for="totalOrdersCheckbox">
                                            Total Orders
                                        </label>
                                    </div>
                                </div>
                            
                                <div class="col-6 d-flex flex-wrap justify-content-start position-relative" style="height: 400px;">
                                    <!-- Cards Section -->
                                    <div id="kpiCards" class="d-flex flex-wrap">
                                        <!-- Total Revenue Card -->
                                        <div class="card d-none square-card" id="totalRevenueCard">
                                            <div class="card-body d-flex align-items-center">
                                                <i class="fas fa-dollar-sign me-3" style="font-size: 24px;"></i>
                                                <div>
                                                    <h5 class="card-title">Total Revenue</h5>
                                                    <h6 class="card-subtitle mb-2 text-muted">$84,686.00</h6>
                                                    <p class="card-text">+24% vs last month</p>
                                                </div>
                                            </div>
                                        </div>
                            
                                        <!-- Total Customers Card -->
                                        <div class="card d-none square-card" id="totalClientsCard">
                                            <div class="card-body d-flex align-items-center">
                                                <i class="fas fa-users me-3" style="font-size: 24px;"></i>
                                                <div>
                                                    <h5 class="card-title">Total Customers</h5>
                                                    <h6 class="card-subtitle mb-2 text-muted">8,634</h6>
                                                    <p class="card-text">+12% vs last month</p>
                                                </div>
                                            </div>
                                        </div>
                            
                                        <!-- Average Sales Card -->
                                        <div class="card d-none square-card" id="averageSalesCard">
                                            <div class="card-body d-flex align-items-center">
                                                <i class="fas fa-shopping-cart me-3" style="font-size: 24px;"></i>
                                                <div>
                                                    <h5 class="card-title">Average Sales</h5>
                                                    <h6 class="card-subtitle mb-2 text-muted">$1,240.00</h6>
                                                    <p class="card-text">+6% vs last month</p>
                                                </div>
                                            </div>
                                        </div>
                            
                                        <!-- Total Orders Card -->
                                        <div class="card d-none square-card" id="totalOrdersCard">
                                            <div class="card-body d-flex align-items-center">
                                                <i class="fas fa-clipboard-list me-3" style="font-size: 24px;"></i>
                                                <div>
                                                    <h5 class="card-title">Total Orders</h5>
                                                    <h6 class="card-subtitle mb-2 text-muted">2,420</h6>
                                                    <p class="card-text">+18% vs last month</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <script>
                                function toggleCard(cardId) {
                                    const card = document.getElementById(cardId);
                                    if (card.classList.contains('d-none')) {
                                        card.classList.remove('d-none');
                                    } else {
                                        card.classList.add('d-none');
                                    }
                                }
                            </script>
                            
                            <style>
                                .square-card {
                                    width: 200px; /* Adjust this for a square shape */
                                    height: 200px; /* Adjust this for a square shape */
                                    margin: 10px; /* Space between cards */
                                    position: relative; /* Allow overlapping */
                                    transition: transform 0.2s; /* Smooth transition */
                                    border-radius: 10px; /* Rounded corners */
                                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow */
                                }
                            
                                .square-card:hover {
                                    transform: scale(1.05); /* Slight zoom effect on hover */
                                }
                            
                                #kpiCards {
                                    position: relative;
                                    display: flex;
                                    flex-wrap: wrap;
                                    justify-content: flex-start;
                                }
                            </style>
                            
                            <!-- Add Font Awesome CDN in your HTML head -->
                            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
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