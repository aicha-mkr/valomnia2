<?php

namespace App\Console\Commands;

use App\Jobs\AlertStock;
use App\Models\Alert;
use App\Models\AlertHistory;
use Illuminate\Console\Command;
use Carbon\Carbon;

class
TriggerAlerts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:trigger-alerts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Triggers alerts programmed.';

    /**
     * Execute the console command.
     */
  // app/Console/Commands/TriggerAlerts.php
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

    // Recherche du template
    $template = null;
    if (!empty($alert->template_id)) {
      $template = EmailTemplate::find($alert->template_id);
    }
    if (!$template && isset($alert->type_id)) {
      $template = EmailTemplate::where('type', 'Alert')
        ->where('alert_id', $alert->type_id)
        ->first();
    }
    if (!$template) {
      $template = EmailTemplate::where('type', 'Alert')->first();
    }

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

    // Correspondance entre IDs et références
    $warehouseReferences = [];
    foreach ($warehouseArray as $warehouseId) {
      if (empty($warehouseId)) continue;
      if ($warehouseId == '1') $warehouseReferences[] = 'SMPA';
      if ($warehouseId == '2') $warehouseReferences[] = 'CAMION VA Référence';
      if ($warehouseId == '3') $warehouseReferences[] = 'CAMION STAGE';
      if ($warehouseId == '4') $warehouseReferences[] = 'Main warehouse';
    }
    $warehouseReferences = array_map('trim', $warehouseReferences);
    Log::info("Références d'entrepôts à vérifier: " . implode(', ', $warehouseReferences));

    try {
      if (!isset($alert->user->cookies) || empty($alert->user->cookies)) {
        Log::error("Cookies de session non disponibles pour l'utilisateur: " . $alert->user_id);
        return;
      }

      // Récupérer tous les stocks
      $warhouses_user = Warehouse::ListStockWarhouse([
        "user_id" => $alert->user_id,
        "organisation" => $alert->user->organisation,
        "warhouseRef" => "",
        "cookies" => $alert->user->cookies
      ]);

      Log::info("Réponse brute de l'API pour tous les entrepôts reçue");

      $warhouses_user_ar = json_decode($warhouses_user, TRUE);
      $allData = [];
      if (isset($warhouses_user_ar["data"])) {
        $allData = $warhouses_user_ar["data"];
        $pageCount = 0;
        $maxPages = 5;
        $processedOffsets = [0];
        $startTime = time();
        $timeLimit = 30;

        $nextPage = $warhouses_user_ar["paging"]["next"] ?? null;

        while ($nextPage && $pageCount < $maxPages && (time() - $startTime) < $timeLimit) {
          $pageCount++;
          preg_match('/offset=(\d+)/', $nextPage, $matches);
          $offset = isset($matches[1]) ? (int)$matches[1] : 100;

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

      // Collecter les produits avec stock bas
      $expiredProducts = [];
      $filteredCount = 0;

      foreach ($allData as $warhouse) {
        $warehouseRef = isset($warhouse["warehouseReference"]) ? trim($warhouse["warehouseReference"]) : "";
        if (!empty($warehouseReferences) && !in_array($warehouseRef, $warehouseReferences)) {
          continue;
        }

        $filteredCount++;
        $productName = isset($warhouse["item"]["name"]) ? $warhouse["item"]["name"] : (isset($warhouse["productName"]) ? $warhouse["productName"] : 'Inconnu');
        Log::info("Traitement de l'entrepôt: " . $warehouseRef . " - Produit: " . $productName . " - Quantité: " . ($warhouse["quantity"] ?? 'N/A'));

        if (isset($warhouse["quantity"]) && $warhouse["quantity"] <= $alert->quantity) {
          Log::info("Stock bas détecté dans l'entrepôt " . $warehouseRef . ": " . $warhouse["quantity"]);
          $warehouseName = isset($warhouse["warehouse"]["name"]) ? $warhouse["warehouse"]["name"] : $warehouseRef;
          $expiredProducts[] = [
            'product_name' => $productName,
            'warehouse_name' => $warehouseName,
            'quantity' => $warhouse["quantity"],
            'threshold' => $alert->quantity
          ];
        }
      }

      Log::info("Nombre d'éléments traités après filtrage par entrepôt: " . $filteredCount);
      Log::info("Nombre de produits avec stock bas: " . count($expiredProducts));

      if (empty($expiredProducts)) {
        Log::info("Aucun produit avec stock bas détecté.");
        return;
      }

      // Préparer le contenu de l'email without adding the phrase
      $content = $template->content;

      $data = [
        'subject' => $template->subject,
        'title' => $template->title,
        'content' => $content,
        'expired_products' => $expiredProducts,
        'btn_name' => $template->btn_name ?? null,
        'btn_link' => $template->btn_link ?? null,
      ];

      Log::info("Envoi d'email avec " . count($expiredProducts) . " produits en stock bas");
      Mail::to('mokhtaraichaa@gmail.com')->send(new Email($data, 'alert'));
      Log::info("Email envoyé avec succès");

      // Mettre à jour l'historique des alertes
      $alertHistory = AlertHistory::where("alert_id", $alert->id)->latest()->first();
      if ($alertHistory) {
        $alertHistory->status = 1;
        $alertHistory->save();
        Log::info("Alerte " . $alert->id . " marquée comme traitée avec succès");
      }

      echo "warhouses_user response\n";
      echo $warhouses_user ?? "Aucune réponse";
      echo "\n";

    } catch (\Exception $e) {
      Log::error("Erreur lors du traitement de l'alerte: " . $e->getMessage());
      Log::error("Trace: " . $e->getTraceAsString());
    }
  }}
