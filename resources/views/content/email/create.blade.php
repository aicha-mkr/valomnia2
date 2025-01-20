@extends('layouts.contentNavbarLayout')

@section('title', 'Liste des Modèles d\'E-mail')

@section('content')
<div class="col-md-12">
    <div class="nav-align-top">
      <ul class="nav nav-pills flex-column flex-md-row mb-6">
        <li class="nav-item"><a class="nav-link active" href="javascript:void(0);"><i class="bx bx-sm bx-user me-1_5"></i> Account</a></li>
        <li class="nav-item"><a class="nav-link" href="{{url('pages/account-settings-notifications')}}"><i class="bx bx-sm bx-bell me-1_5"></i> Notifications</a></li>
        <li class="nav-item"><a class="nav-link" href="{{url('email/create')}}"><i class="bx bx-sm bx-envelope me-1_5"></i> Create Email</a></li>
        <li class="nav-item"><a class="nav-link" href="{{url('email/liste')}}"><i class="bx bx-sm bx-envelope me-1_5"></i> Liste</a></li>

      </ul>
    <div class="content">
        <div class="card">
            <div class="card-header">
                <h2>Paramètres d'envoi d'e-mail</h2>
                <button class="btn btn-success">Ajouter un nouveau paramètre</button>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Date</th>
                        <th>Déclenché</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Nouvelle configuration</td>
                        <td>11/04/2023</td>
                        <td>Hebdomadaire</td>
                        <td>
                            <button class="btn btn-primary">Modifier</button>
                            <button class="btn btn-danger">Supprimer</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Ancienne configuration</td>
                        <td>01/01/2023</td>
                        <td>Mensuel</td>
                        <td>
                            <button class="btn btn-primary">Modifier</button>
                            <button class="btn btn-danger">Supprimer</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
<br><br<<br><br<<br><br>
        <div class="card">
            <div class="card-header">
                <h2>Récapitulatif Hebdomadaire</h2>
            </div>
           
            <div class="card">
                
                <div class="kpi-container">
                     <br><br><br><br><br>
                    <div class="kpi-item left">
                        <h3>{{ $recapDataArray['total_revenue'] }} TND</h3>
                        <small>Total Revenue</small>
                    </div>
                    <div class="kpi-item center">
                        <h3>{{ $recapDataArray['total_clients'] }}</h3>
                        <small>Total Clients</small>
                    </div>
                    <div class="kpi-item right">
                        <h3>{{ $recapDataArray['average_sales'] }} TND</h3>
                        <small>Average Sales</small>
                    </div>
                </div>
                
                <div class="chart-container">
                    
                    <div id="orderStatisticsChart"></div>
                </div>
                
            </b>
        </div>

        <div class="card-footer">
            
            <h3>KPIs</h3>
            <div class="kpi-container">
                <div class="kpi-row">
                    <input type="checkbox" id="total-revenue" checked onchange="updateKPI(this, '{{ $recapDataArray['total_revenue'] }} TND')">
                    <label for="total-revenue">Total Revenue</label>
                    <span id="value-total-revenue">{{ $recapDataArray['total_revenue'] }} TND</span>
                </div>
                <div class="kpi-row">
                    <input type="checkbox" id="total-clients" checked onchange="updateKPI(this, '{{ $recapDataArray['total_clients'] }}')">
                    <label for="total-clients">Total Clients</label>
                    <span id="value-total-clients">{{ $recapDataArray['total_clients'] }}</span>
                </div>
                <div class="kpi-row">
                    <input type="checkbox" id="average-sales" checked onchange="updateKPI(this, '{{ $recapDataArray['average_sales'] }} TND')">
                    <label for="average-sales">Average Sales</label>
                    <span id="value-average-sales">{{ $recapDataArray['average_sales'] }} TND</span>
                </div>
                <div class="kpi-row">
                    <input type="checkbox" id="total-orders" checked onchange="updateKPI(this, '{{ $recapDataArray['total_orders'] }}')">
                    <label for="total-orders">Total Orders</label>
                    <span id="value-total-orders">{{ $recapDataArray['total_orders'] }}</span>
                </div>
            </div>
        </div>

        <div class="selected-kpis">
            <h3>KPI Sélectionnés</h3>
            <div id="selectedKPIValue">Aucun KPI sélectionné</div>
        </div>
        
        <script>
            function updateKPI(checkbox, value) {
                const selectedKPIValue = document.getElementById('selectedKPIValue');
                const selectedKPIs = Array.from(document.querySelectorAll('.kpi-row input[type="checkbox"]:checked'))
                                          .map(cb => cb.nextSibling.textContent);
                const selectedValues = selectedKPIs.length > 0 ? selectedKPIs.join(', ') : 'Aucun KPI sélectionné';
                selectedKPIValue.textContent = selectedValues;
            }
        </script>

        <style>
            .kpi-container {
                display: flex;
                justify-content: space-between;
                margin-top: 20px;
            }
            .kpi-item {
                flex: 1;
                text-align: center;
            }
            .kpi-item.left {
                text-align: left;
            }
            .kpi-item.center {
                text-align: center;
            }
            .kpi-item.right {
                text-align: right;
            }
            .selected-kpis {
                margin-top: 20px;
                font-weight: bold;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }
            th, td {
                padding: 10px;
                text-align: left;
                border-bottom: 1px solid #ddd;
            }
            th {
                background-color: #f2f2f2;
            }
            .kpi-row {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 10px;
            }
        </style>
    </div>
@endsection