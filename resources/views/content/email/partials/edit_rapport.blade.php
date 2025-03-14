
<div class="row">
    <!-- Rapport Section -->
    <div id="rapportSection" class="col-md-4 section" style="display: none;">
        <!-- 30% width -->
        <div class="card mb-6">
            <h5 class="card-header">Formulaire de Rapport</h5>
            <div class="card-body">
            <form id="editForm" action="{{ route('email.update', $template->id) }}" method="POST">
                    onsubmit="console.log('Form submitted!');">
                    @csrf
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <input type="hidden" name="type" value="Rapport" />

                    <div class="mb-4">
                        <label for="rapport-email-subject" class="form-label">Sujet</label>
                        <input type="text" class="form-control" id="rapport-email-subject" name="subject"
                            placeholder="Sujet de l'email" required />
                    </div>

                    <div class="mb-4">
                        <label for="rapport-title" class="form-label">Titre</label>
                        <input type="text" class="form-control" id="rapport-title" name="title"
                            placeholder="Titre du rapport" required />
                    </div>

                    <div class="mb-4">
                        <h5>Configurer le Texte du Rapport</h5>
                        <textarea class="form-control" id="rapport-content" name="content" rows="6"
                            placeholder="Entrez le contenu du rapport ici..." required></textarea>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="report-open"
                            onclick="toggleReportSection()" />
                        <label class="form-check-label" for="report-open">Afficher Bouton de Rapport</label>
                    </div>

                    <div id="urlSection" class="mb-4" style="display: none;">
                        <label for="report-url" class="form-label">URL Spécifique</label>
                        <input type="url" class="form-control" id="report-url" name="btn_link"
                            placeholder="URL spécifique">
                    </div>

                    <div class="mb-4" id="buttonTitleSection" style="display: none;">
                        <label for="button-title" class="form-label">Titre du Bouton</label>
                        <input type="text" class="form-control" id="button-title" name="btn_name"
                            placeholder="Entrez le titre du bouton" />
                    </div>

                    <h5>Sélectionner les KPI</h5>
                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="total_revenue"
                                id="totalRevenueCheckbox" name="kpi[]" checked />
                            <label class="form-check-label" for="totalRevenueCheckbox">Total Revenue</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="total_orders"
                                id="totalOrdersCheckbox" name="kpi[]" checked />
                            <label class="form-check-label" for="totalOrdersCheckbox">Total Orders</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="total_employees"
                                id="totalEmployeesCheckbox" name="kpi[]" checked />
                            <label class="form-check-label" for="totalEmployeesCheckbox">Total Employees</label>
                        </div>
                    </div>

                    <div class="mb-4">
                        <button type="submit" class="btn btn-primary">Créer le Rapport</button>
                    </div>
                </form>

            </div>
        </div>
    </div>









    