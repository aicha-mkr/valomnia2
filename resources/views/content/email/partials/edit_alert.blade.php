
<!-- Alerte Section -->
<div id="alerteSection" class="col-md-4 section" style="display: none;">
    <div class="card mb-6">
        <h5 class="card-header">Formulaire d'Alerte</h5>
        <div class="card-body">
        <form id="editForm" action="{{ route('email.update', $template->id) }}" method="POST">
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
                <div class="mb-4">
                    <label for="alerte-type" class="form-label">Type d'Alerte</label>
                    <select class="form-select" id="alerte-type" name="alert_type" required>
                        <option value="">Sélectionnez un type</option>
                        <option value="stock">Stock</option>
                        <option value="prix">Prix</option>
                    </select>
                </div>

                <input type="hidden" name="type" value="Alert" />
                <div class="mb-4">
                    <label for="alerte-title" class="form-label">Titre</label>
                    <input type="text" class="form-control" id="alerte-title" name="title"
                        placeholder="Titre de l'alerte" required />
                </div>

                <div class="mb-4">
                    <label for="alerte-email-subject" class="form-label" name="subject">Sujet d'Email</label>
                    <input type="text" class="form-control" id="alerte-email-subject" name="subject"
                        placeholder="Sujet de l'email" required />
                </div>
                <div class="mb-4">
                      <h5>Configurer le Texte</h5>
                      <textarea class="form-control" id="alerte-content" name="content" rows="6" placeholder="Entrez le contenu du alert ici..." required></textarea>
                  </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="alert-open" onclick="toggleUrlSection()" />
                    <label class="form-check-label" for="alert-open">Afficher Bouton</label>
                </div>

                <div id="alert-url-section" class="mb-4">
                    <label for="alert-url" class="form-label">URL Spécifique</label>
                    <input type="url" class="form-control" id="alert-url" name="alert_url" placeholder="URL spécifique"
                        oninput="updateButtonUrl()" />
                </div>

                <div id="alert-button-input" class="mb-4">
                    <label for="alert-button-text" class="form-label">Titre du Bouton</label>
                    <textarea class="form-control" id="alert-button-text" name="alert_text" rows="3"
                        placeholder="Entrez le titre du bouton ici" oninput="updateButtonText()"></textarea>
                </div>



                <div class="mb-4">
                    <button type="submit" class="btn btn-warning">Créer l'Alerte</button>
                </div>
            </form>
        </div>
    </div>
</div>




