<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\View;

class GenericAlertMail extends Mailable
{
  use Queueable, SerializesModels;

  public $subject_line;
  public $content_body; // This will be the pre-rendered HTML content from the template
  public $data; // Array of data to pass to the email view

  /**
   * Create a new message instance.
   *
   * @param string $subject_line
   * @param string $template_content The raw HTML/Blade content from EmailTemplate model
   * @param array $data Data to be injected into the template
   */
  public function __construct($subject_line, $template_content, $data)
  {
    $this->subject_line = $subject_line;
    $this->data = $data;
    // Render the template content with the provided data
    // This assumes your $template_content is a Blade string that can be rendered.
    // If $template_content is a view name, this approach needs to be different.
    // For now, let's assume it's a Blade string.
    // A more robust way would be to save the template content to a temporary file and use that view.
    // Or, if your EmailTemplate->content is always a view path, use that directly.

    // For simplicity, we'll pass the raw content and data to a generic wrapper view
    // which will then try to render the passed content using Blade's compileString if needed,
    // or simply display it if it's already HTML.
    // However, the job ProcessSalesThresholdAlertJob directly passes the template content from the DB.
    // We need a way to replace placeholders in that content.

    $this->content_body = $this->replacePlaceholders($template_content, $data);
    $this->subject = $this->replacePlaceholders($subject_line, $data);

  }

  /**
   * Build the message.
   *
   * @return $this
   */
  public function build()
  {
    // Instead of a specific view, we are setting the HTML content directly.
    // This is because the template content comes from the database.
    return $this->subject($this->subject)
      ->html($this->content_body);
  }

  /**
   * Replace placeholders in the given content string.
   *
   * @param string $content
   * @param array $data
   * @return string
   */
  private function replacePlaceholders(string $content, array $data): string
  {
    // Standard placeholders like [ALERT_TITLE], [ALERT_DESCRIPTION]
    if (isset($data["alert_title"])) {
      $content = str_replace("[ALERT_TITLE]", htmlspecialchars($data["alert_title"] ?? "N/A"), $content);
    }
    if (isset($data["alert_description"])) {
      $content = str_replace("[ALERT_DESCRIPTION]", htmlspecialchars($data["alert_description"] ?? "N/A"), $content);
    }
    if (isset($data["recipient_name"])) {
      $content = str_replace("[RECIPIENT_NAME]", htmlspecialchars($data["recipient_name"] ?? "User"), $content);
    }

    // Specific for 'vente-seuil-depasse-pdv'
    if (isset($data["alert_type_slug"]) && $data["alert_type_slug"] === "vente-seuil-depasse-pdv") {
      $content = str_replace("[POINT_DE_VENTE_REF]", htmlspecialchars($data["point_de_vente_ref"] ?? "N/A"), $content);
      $content = str_replace("[MOYENNE_VENTES_HISTORIQUE]", htmlspecialchars($data["moyenne_ventes_historique"] ?? "N/A"), $content);
      $content = str_replace("[SEUIL_POURCENTAGE_CONFIGURE]", htmlspecialchars($data["seuil_pourcentage_configure"] ?? "N/A"), $content);
      $content = str_replace("[PERIODE_HISTORIQUE_JOURS]", htmlspecialchars($data["periode_historique_jours"] ?? "N/A"), $content);

      $salesListHtml = "<ul>";
      if (!empty($data["liste_ventes_depassement"]) && is_array($data["liste_ventes_depassement"])) {
        foreach ($data["liste_ventes_depassement"] as $sale) {
          $salesListHtml .= "<li>Vente ID: " . htmlspecialchars($sale["id"] ?? "N/A") .
            " (Ref: " . htmlspecialchars($sale["reference"] ?? "N/A") .
            ") - Date: " . htmlspecialchars($sale["date"] ?? "N/A") .
            " - Montant: " . htmlspecialchars($sale["amount"] ?? "N/A") .
            " (" . htmlspecialchars($sale["percentage_of_average"] ?? "N/A") . "% de la moyenne)</li>";
        }
      }
      $salesListHtml .= "</ul>";
      $content = str_replace("[LISTE_VENTES_DEPASSEMENT]", $salesListHtml, $content);
    }
    // Add other placeholder replacements for other alert types if this Mailable is truly generic

    return $content;
  }
}

