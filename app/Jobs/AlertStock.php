<?php

namespace App\Jobs;

use App\Mail\Email;
use App\Models\Alert;
use App\Models\AlertHistory;
use App\Models\EmailTemplate;
use App\Models\Warehouse;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class AlertStock implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  /**
   * Create a new job instance.
   */
  protected $alert_id;

  public function __construct($alert_id)
  {
    $this->alert_id = $alert_id;
  }

  /**
   * Execute the job.
   */
  public function handle(): void
  {
    Log::info("Démarrage du job AlertStock pour l'alerte ID: " . $this->alert_id);

    $alert = Alert::with(["user", "type"])->where("id", $this->alert_id)->first();

    if (!$alert) {
      Log::error("Alerte non trouvée: " . $this->alert_id);
      return;
    }

    echo "alert Id : " . $alert->id . "\n";
    echo "user_id : " . $alert->user_id . "\n";
    echo "organisation : " . $alert->user->organisation . "\n";

    if (isset($alert)) {
      // Recherche du template
      // 1. D'abord essayer de trouver par template_id si défini
      $template = null;
      if (!empty($alert->template_id)) {
        $template = EmailTemplate::find($alert->template_id);
      }

      // 2. Si non trouvé, chercher par type d'alerte
      if (!$template && isset($alert->type_id)) {
        $template = EmailTemplate::where('type', 'Alert')
          ->where('alert_id', $alert->type_id)
          ->first();
      }

      // 3. Si toujours pas trouvé, utiliser un template générique
      if (!$template) {
        $template = EmailTemplate::where('type', 'Alert')->first();
      }

      // Vérifier si un template a été trouvé
      if (!$template) {
        Log::error("Aucun template d'email trouvé pour l'alerte: " . $alert->id);
        return;
      }

      Log::info("Template trouvé: " . $template->id . " - " . $template->title);

      // Récupérer les warehouse_ids pour filtrage
      $warehouseIds = $alert->warehouse_ids;
      $warehouseArray = [];
      if (!empty($warehouseIds)) {
        $warehouseIds = trim($warehouseIds, ',');
        $warehouseArray = explode(',', $warehouseIds);
        Log::info("warehouse_ids bruts: " . $alert->warehouse_ids);
        Log::info("warehouse_ids nettoyés: " . implode(', ', $warehouseArray));
      }

      // Faire la correspondance entre IDs et références
      $warehouseReferences = [];
      foreach ($warehouseArray as $warehouseId) {
        if (empty($warehouseId)) continue;

        // Correspondance (à adapter selon votre structure)
        if ($warehouseId == '1') $warehouseReferences[] = 'SMPA';
        if ($warehouseId == '2') $warehouseReferences[] = 'CAMION VA Référence';
        if ($warehouseId == '3') $warehouseReferences[] = 'CAMION STAGE';
        if ($warehouseId == '4') $warehouseReferences[] = 'Main warehouse';
      }

      // Appliquer trim() à toutes les références pour éviter les problèmes d'espaces
      $warehouseReferences = array_map('trim', $warehouseReferences);

      Log::info("Références d'entrepôts à vérifier: " . implode(', ', $warehouseReferences));

      try {
        // S'assurer que les cookies sont disponibles
        if (!isset($alert->user->cookies) || empty($alert->user->cookies)) {
          Log::error("Cookies de session non disponibles pour l'utilisateur: " . $alert->user_id);
          return;
        }

        // Récupérer tous les stocks de tous les entrepôts (première page)
        $warhouses_user = Warehouse::ListStockWarhouse([
          "user_id" => $alert->user_id,
          "organisation" => $alert->user->organisation,
          "warhouseRef" => "", // Paramètre vide pour récupérer tous les stocks
          "cookies" => $alert->user->cookies
        ]);

        Log::info("Réponse brute de l'API pour tous les entrepôts reçue");

        $warhouses_user_ar = json_decode($warhouses_user, TRUE);

        // Récupérer toutes les pages de résultats avec protection contre les boucles infinies
        $allData = [];
        if (isset($warhouses_user_ar["data"])) {
          $allData = $warhouses_user_ar["data"];

          // Récupérer les pages suivantes si elles existent
          $pageCount = 0;
          $maxPages = 5; // Limiter à 5 pages maximum
          $processedOffsets = [0]; // Commencer avec l'offset 0 (première page)
          $startTime = time();
          $timeLimit = 30; // Limite de 30 secondes

          $nextPage = $warhouses_user_ar["paging"]["next"] ?? null;

          while ($nextPage && $pageCount < $maxPages && (time() - $startTime) < $timeLimit) {
            $pageCount++;

            // Extraire l'offset de l'URL nextPage
            preg_match('/offset=(\d+)/', $nextPage, $matches);
            $offset = isset($matches[1]) ? (int)$matches[1] : 100;

            // Vérifier si cet offset a déjà été traité
            if (in_array($offset, $processedOffsets)) {
              Log::warning("Offset déjà traité, arrêt de la pagination");
              break;
            }
            $processedOffsets[] = $offset;

            Log::info("Récupération de la page suivante avec offset: " . $offset . " (page " . $pageCount . " sur " . $maxPages . ")");

            $nextPageData = Warehouse::ListStockWarhouse([
              "user_id" => $alert->user_id,
              "organisation" => $alert->user->organisation,
              "warhouseRef" => "",
              "cookies" => $alert->user->cookies,
              "offset" => $offset
            ]);

            $nextPageDataAr = json_decode($nextPageData, TRUE);
            if (isset($nextPageDataAr["data"]) && !empty($nextPageDataAr["data"])) {
              $allData = array_merge($allData, $nextPageDataAr["data"]);
              $nextPage = $nextPageDataAr["paging"]["next"] ?? null;
            } else {
              Log::info("Aucune donnée supplémentaire trouvée, fin de la pagination");
              $nextPage = null;
            }
          }

          if ($pageCount >= $maxPages) {
            Log::warning("Limite de pages atteinte (" . $maxPages . "), arrêt de la pagination");
          }

          if ((time() - $startTime) >= $timeLimit) {
            Log::warning("Limite de temps atteinte (" . $timeLimit . " secondes), arrêt de la pagination");
          }
        }

        // Journaliser toutes les références d'entrepôt disponibles
        $warehouseReferencesInApi = [];
        foreach ($allData as $warhouse) {
          if (isset($warhouse["warehouseReference"])) {
            $warehouseRef = trim($warhouse["warehouseReference"]);
            if (!in_array($warehouseRef, $warehouseReferencesInApi)) {
              $warehouseReferencesInApi[] = $warehouseRef;
            }
          }
        }
        Log::info("Références d'entrepôts disponibles dans l'API: " . implode(', ', $warehouseReferencesInApi));

        Log::info("Nombre total d'éléments dans les données: " . count($allData));

        $emailsSent = false;
        $filteredCount = 0;

        foreach ($allData as $warhouse) {
          $warehouseRef = isset($warhouse["warehouseReference"]) ? trim($warhouse["warehouseReference"]) : "";
          if (!empty($warehouseReferences) && !in_array($warehouseRef, $warehouseReferences)) {
            continue; // Ignorer cet entrepôt s'il n'est pas dans la liste
          }

          $filteredCount++;
          $productName = isset($warhouse["item"]["name"]) ? $warhouse["item"]["name"] : (isset($warhouse["productName"]) ? $warhouse["productName"] : 'Inconnu');
          Log::info("Traitement de l'entrepôt: " . $warehouseRef . " - Produit: " . $productName . " - Quantité: " . ($warhouse["quantity"] ?? 'N/A'));

          if (isset($warhouse["quantity"]) && $warhouse["quantity"] <= $alert->quantity) {
            Log::info("Stock bas détecté dans l'entrepôt " . $warehouseRef . ": " . $warhouse["quantity"]);

            // Préparation des données pour l'email
            $content = $template->content;
            $quantity = $warhouse["quantity"] ?? 'N/A';
            $warehouseName = isset($warhouse["warehouse"]["name"]) ? $warhouse["warehouse"]["name"] : $warehouseRef;

            // Vérifier si le contenu contient les variables
            if (strpos($content, '[QUANTITY]') === false && strpos($content, '[PRODUCT]') === false) {
              // Si les variables ne sont pas présentes, ajouter un texte par défaut
              $content .= "<p>The stock of product <span class=\"product-name\">{$productName}</span> in warehouse <span class=\"warehouse-name\">{$warehouseName}</span> is currently <span class=\"quantity-alert\">{$quantity}</span> units.</p>";
            }

            $data = [
              'subject' => $template->subject,
              'title' => $template->title,
              'content' => str_replace(
                ['[QUANTITY]', '[PRODUCT]', '[WAREHOUSE]', '[THRESHOLD]'],
                [
                  '<span class="quantity-alert">' . $quantity . '</span>',
                  '<span class="product-name">' . $productName . '</span>',
                  '<span class="warehouse-name">' . $warehouseName . '</span>',
                  '<span class="threshold-value">' . $alert->quantity . '</span>'
                ],
                $content
              ),
              'quantity' => $quantity,
              'product_name' => $productName,
              'warehouse_name' => $warehouseName,
              'threshold' => $alert->quantity,
              'btn_name' => $template->btn_name ?? null,
              'btn_link' => $template->btn_link ?? null,
            ];

            Log::info("Envoi d'email pour stock bas: " . $productName . " - Quantité: " . $warhouse["quantity"]);
            Mail::to('mokhtaraichaa@gmail.com')->send(new Email($data, 'alert'));
            Log::info("Email envoyé avec succès");
            $emailsSent = true;
          }
        }

        Log::info("Nombre d'éléments traités après filtrage par entrepôt: " . $filteredCount);

        if ($filteredCount == 0) {
          Log::warning("Aucun élément trouvé pour l'entrepôt spécifié. Vérifiez la correspondance entre IDs et références.");
          Log::warning("Entrepôts recherchés: " . implode(', ', $warehouseReferences));
          Log::warning("Entrepôts disponibles: " . implode(', ', $warehouseReferencesInApi));
        }

        echo "warhouses_user response\n";
        echo $warhouses_user ?? "Aucune réponse";
        echo "\n";

        // Mettre à jour l'historique des alertes en préservant le compteur de tentatives
        $alertHistory = AlertHistory::where("alert_id", $alert->id)->latest()->first();
        if ($alertHistory) {
          // Ne pas réinitialiser attempts, juste mettre à jour le statut
          $alertHistory->status = 1; // Succès
          $alertHistory->save();
          Log::info("Alerte " . $alert->id . " marquée comme traitée avec succès");
        }

      } catch (\Exception $e) {
        Log::error("Erreur lors du traitement de l'alerte: " . $e->getMessage());
        Log::error("Trace: " . $e->getTraceAsString());
      }
    }
  }
}
