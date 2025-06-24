<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Report;

class ReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $report;

    /**
     * Create a new message instance.
     */
    public function __construct(Report $report)
    {
        $this->report = $report;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Weekly Report is Ready',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Générer le contenu dynamique du rapport
        $content = $this->generateReportContent();

        return new Content(
            markdown: 'emails.rapport',
            with: [
                'report' => $this->report,
                'title' => 'Your Report for ' . $this->report->date->format('F d, Y'),
                'content' => $content,
                'btn_link' => url('/organisation/reports'),
                'btn_name' => 'Voir le Rapport Complet',
            ],
        );
    }

    /**
     * Generate dynamic content for the report
     */
    private function generateReportContent(): string
    {
        $content = "<h3>Résumé du Rapport</h3>";
        $content .= "<p>Voici un résumé de vos performances pour la période du " . 
                   $this->report->startDate->format('d/m/Y') . " au " . 
                   $this->report->endDate->format('d/m/Y') . " :</p>";
        
        $content .= "<ul>";
        $content .= "<li><strong>Total des commandes :</strong> " . number_format($this->report->total_orders) . "</li>";
        $content .= "<li><strong>Revenu total :</strong> " . number_format($this->report->total_revenue, 2) . " €</li>";
        $content .= "<li><strong>Ventes moyennes :</strong> " . number_format($this->report->average_sales, 2) . " €</li>";
        $content .= "<li><strong>Nombre total de clients :</strong> " . number_format($this->report->total_clients) . "</li>";
        $content .= "<li><strong>Quantités totales vendues :</strong> " . number_format($this->report->total_quantities) . "</li>";
        $content .= "</ul>";

        // Ajouter les articles les plus vendus si disponibles
        if (!empty($this->report->top_selling_items)) {
            $topItems = json_decode($this->report->top_selling_items, true);
            if (is_array($topItems) && !empty($topItems)) {
                $content .= "<h4>Articles les plus vendus :</h4>";
                $content .= "<table style='width: 100%; border-collapse: collapse;'>";
                $content .= "<thead><tr><th style='text-align: left; padding: 8px; border-bottom: 1px solid #ddd;'>Produit</th><th style='text-align: right; padding: 8px; border-bottom: 1px solid #ddd;'>Quantité</th><th style='text-align: right; padding: 8px; border-bottom: 1px solid #ddd;'>Revenu</th></tr></thead>";
                $content .= "<tbody>";
                foreach (array_slice($topItems, 0, 5) as $item) {
                    $content .= "<tr>";
                    $content .= "<td style='padding: 8px; border-bottom: 1px solid #ddd;'>" . htmlspecialchars($item['name'] ?? 'Produit inconnu') . "</td>";
                    $content .= "<td style='text-align: right; padding: 8px; border-bottom: 1px solid #ddd;'>" . number_format($item['quantity'] ?? 0) . "</td>";
                    $content .= "<td style='text-align: right; padding: 8px; border-bottom: 1px solid #ddd;'>" . number_format($item['revenue'] ?? 0, 2) . " €</td>";
                    $content .= "</tr>";
                }
                $content .= "</tbody></table>";
            }
        }

        $content .= "<p style='margin-top: 20px;'>Ce rapport a été généré automatiquement le " . $this->report->date->format('d/m/Y à H:i') . ".</p>";
        
        return $content;
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
