@extends('layouts.contentNavbarLayout')

@section('title', 'Créer un Nouveau Template')

@section('content')


    <p class="text-center mb-4">
        <a class="btn btn-primary me-1" data-bs-toggle="collapse" href="#collapseRapport" role="button" aria-expanded="true" onclick="toggleCollapse('collapseRapport')">
            Rapport
        </a>
        <a class="btn btn-warning me-1" data-bs-toggle="collapse" href="#collapseAlerte" role="button" aria-expanded="false" onclick="toggleCollapse('collapseAlerte')">
            Alerte
        </a>
    </p>

    <div class="row">
        <div class="col-md-4">  <!-- Form Column -->
            <div class="collapse show" id="collapseRapport">
                <div class="card mb-6">
                    <h5 class="card-header">Formulaire de Rapport</h5>
                    <div class="card-body">
                        <form action="{{ route('email.store') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="rapport-title" class="form-label">Titre</label>
                                <input type="text" class="form-control" id="rapport-title" name="title" placeholder="Titre du rapport" required oninput="updatePreview()" />
                            </div>

                            <div class="mb-4">
                                <label for="rapport-email-header" class="form-label">En-tête d'Email</label>
                                <input type="text" class="form-control" id="rapport-email-header" name="email_header" placeholder="En-tête de l'email" required oninput="updatePreview()" />
                            </div>

                            <div class="mb-4">
                                <label for="rapport-email-subject" class="form-label">Sujet</label>
                                <input type="text" class="form-control" id="rapport-email-subject" name="email_subject" placeholder="Sujet de l'email" required oninput="updatePreview()" />
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="show-header" checked onchange="toggleHeaderSubject()" />
                                <label class="form-check-label" for="show-header">Afficher l'en-tête</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="show-subject" onchange="toggleHeaderSubject()" />
                                <label class="form-check-label" for="show-subject">Afficher le sujet</label>
                            </div>

                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="card-header">Select KPIs to Include</h5>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="total_revenue" id="totalRevenueCheckbox" name="kpis[]" onclick="updateKPI('total_revenue', this.checked)" />
                                        <label class="form-check-label" for="totalRevenueCheckbox">Total Revenue</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="total_clients" id="totalClientsCheckbox" name="kpis[]" onclick="updateKPI('total_clients', this.checked)" />
                                        <label class="form-check-label" for="totalClientsCheckbox">Total Customers</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="average_sales" id="averageSalesCheckbox" name="kpis[]" onclick="updateKPI('average_sales', this.checked)" />
                                        <label class="form-check-label" for="averageSalesCheckbox">Average Sales</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="total_orders" id="totalOrdersCheckbox" name="kpis[]" onclick="updateKPI('total_orders', this.checked)" />
                                        <label class="form-check-label" for="totalOrdersCheckbox">Total Orders</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <button type="submit" class="btn btn-primary">Créer le Rapport</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">  <!-- Preview Column -->
            <div class="card mb-6">
                <h5 class="card-header">Aperçu de l'Email</h5>
                <div class="card-body" id="email-preview">
                    <h3 id="preview-header">En-tête de l'email</h3>
                    <div style="border: 1px solid #ccc; padding: 15px; border-radius: 5px;">
                        <p id="preview-content">
                            <span class="fixed-text">Texte fixe : </span>
                            <textarea id="editable-content" rows="3" style="border: 1px solid #ccc; padding: 5px; width: 100%;" placeholder="Modifier le contenu ici..." oninput="updatePreviewDynamic()">
                                Description
                            </textarea>
                        </p>
                        <div id="kpi-cards" class="row mt-3">
                            <!-- KPI Cards will be inserted here -->
                        </div>
                    </div>
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

<script>
    const kpiData = {
        total_revenue: 10000,
        total_clients: 150,
        average_sales: 67,
        total_orders: 75
    };

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

    function updatePreview() {
        const emailHeader = document.getElementById('rapport-email-header').value;
        const emailSubject = document.getElementById('rapport-email-subject').value;

        document.getElementById('preview-header').innerText = emailHeader || 'En-tête de l\'email';
        
        // Update the subject based on the checked boxes
        document.getElementById('preview-subject').innerText = emailSubject || 'Sujet de l\'email';
    }

    function updatePreviewDynamic() {
        const dynamicContent = document.getElementById('editable-content').value;
        // Update any element if needed, or handle the content display elsewhere.
    }

    function toggleHeaderSubject() {
        const showHeader = document.getElementById('show-header').checked;
        const showSubject = document.getElementById('show-subject').checked;

        if (showHeader) {
            document.getElementById('preview-header').style.display = 'block';
        } else {
            document.getElementById('preview-header').style.display = 'none';
        }

        if (showSubject) {
            document.getElementById('preview-subject').style.display = 'block';
        } else {
            document.getElementById('preview-subject').style.display = 'none';
        }

        updatePreview(); // Update preview to reflect changes
    }

    function updateKPI(kpi, isChecked) {
        const kpiCardsContainer = document.getElementById('kpi-cards');

        if (isChecked) {
            const card = document.createElement('div');
            card.className = 'col-md-6 mb-2';
            card.innerHTML = `
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="bx bx-chart"></i> ${kpi.replace('_', ' ').toUpperCase()}
                        </h5>
                        <p class="card-text">Valeur: ${kpiData[kpi]}</p>
                    </div>
                </div>
            `;
            kpiCardsContainer.appendChild(card);
        } else {
            const cards = Array.from(kpiCardsContainer.children);
            const cardToRemove = cards.find(card => card.querySelector('.card-title').textContent.toLowerCase().includes(kpi.replace('_', ' ').toLowerCase()));

            if (cardToRemove) {
                kpiCardsContainer.removeChild(cardToRemove);
            }
        }
    }
</script>

@endsection