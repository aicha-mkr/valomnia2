<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta charset="UTF-8">
  <title>{{ $title ?? 'Weekly Business Report' }}</title>
  <style type="text/css">
    /* Copie ici tous les styles du rapport preview, y compris les media queries, polices, etc. */
    /* ... (styles du rapport preview) ... */
  </style>
</head>
<body style="margin:0; padding:0; background:#f4f6fa;">
  <table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#f4f6fa">
    <tr>
      <td align="center">
        <table width="632" cellpadding="0" cellspacing="0" border="0" style="background:#fff; border-radius:4px; margin:40px auto; box-shadow:0 4px 24px #e9eff3;">
          <!-- Header -->
          <tr>
            <td style="background:#e9eff3; padding:40px 0 0 0; border-radius:4px 4px 0 0; text-align:center;">
              <img src="https://www.valomnia.com/wp-content/themes/jupiter/images/jupiter-logo.png" alt="Valomnia" style="width:136px; margin-bottom:24px;">
              <img src="http://www.stampready.net/dashboard/editor/user_uploads/zip_uploads/2018/11/19/pcVNfzKjZ3goPqkxr2hYT0ws/service_canceled/images/check-48-primary.png" width="48" height="48" alt="" style="max-width:48px; margin-bottom:16px;">
              <h2 style="font-family: Helvetica, Arial, sans-serif; font-weight: bold; margin-top: 0px; margin-bottom: 4px; color: #242b3d; font-size: 30px; line-height: 39px;">{{ $title ?? 'Weekly Business Report' }}</h2>
              <div style="color:#82899a; font-size:16px; margin-bottom:32px;">Here is your weekly business report with key performance indicators:</div>
            </td>
          </tr>
          <!-- KPI Section -->
          <tr>
            <td style="padding:32px 24px 16px 24px; background:#f8fafc;">
              <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:24px;">
                <tr>
                  <td colspan="5" style="text-align:center; font-size:18px; font-weight:bold; color:#242b3d; padding-bottom:24px;">Key Performance Indicators</td>
                </tr>
                <tr>
                  <td align="center" style="background:#fff; border-radius:8px; box-shadow:0 2px 8px #e9eff3; padding:20px 0; min-width:100px; margin:4px;">
                    <span style="font-size:24px; font-weight:bold; color:#1bb934; display:block;">{{ $report->total_orders ?? '-' }}</span>
                    <span style="font-size:14px; color:#82899a;">Total Orders</span>
                  </td>
                  <td width="12"></td>
                  <td align="center" style="background:#fff; border-radius:8px; box-shadow:0 2px 8px #e9eff3; padding:20px 0; min-width:100px; margin:4px;">
                    <span style="font-size:24px; font-weight:bold; color:#e74c3c; display:block;">{{ $report->total_revenue ?? '-' }}</span>
                    <span style="font-size:14px; color:#82899a;">Total Revenue</span>
                  </td>
                  <td width="12"></td>
                  <td align="center" style="background:#fff; border-radius:8px; box-shadow:0 2px 8px #e9eff3; padding:20px 0; min-width:100px; margin:4px;">
                    <span style="font-size:24px; font-weight:bold; color:#fd7e14; display:block;">{{ $report->average_sales ?? '-' }}</span>
                    <span style="font-size:14px; color:#82899a;">Average Sales</span>
                  </td>
                  <td width="12"></td>
                  <td align="center" style="background:#fff; border-radius:8px; box-shadow:0 2px 8px #e9eff3; padding:20px 0; min-width:100px; margin:4px;">
                    <span style="font-size:24px; font-weight:bold; color:#126de5; display:block;">{{ $report->total_quantities ?? '-' }}</span>
                    <span style="font-size:14px; color:#82899a;">Total Quantities</span>
                  </td>
                  <td width="12"></td>
                  <td align="center" style="background:#fff; border-radius:8px; box-shadow:0 2px 8px #e9eff3; padding:20px 0; min-width:100px; margin:4px;">
                    <span style="font-size:24px; font-weight:bold; color:#0ec06e; display:block;">{{ $report->total_clients ?? '-' }}</span>
                    <span style="font-size:14px; color:#82899a;">Total Clients</span>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <!-- Top 5 Products -->
          <tr>
            <td style="padding:32px 24px 0 24px;">
              <div style="font-size:16px; font-weight:bold; color:#242b3d; margin-bottom:16px; text-align:center;">TOP 5 PRODUCTS</div>
              <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse; margin-bottom:32px;">
                <tr>
                  <th style="background:#f8fafc; color:#82899a; font-weight:bold; padding:10px 8px; border:1px solid #e9eff3;">Reference</th>
                  <th style="background:#f8fafc; color:#82899a; font-weight:bold; padding:10px 8px; border:1px solid #e9eff3;">Name</th>
                  <th style="background:#f8fafc; color:#82899a; font-weight:bold; padding:10px 8px; border:1px solid #e9eff3;">Revenue</th>
                </tr>
                @if(!empty($report->top_selling_items))
                  @foreach(json_decode($report->top_selling_items, true) as $item)
                    <tr>
                      <td style="color:#242b3d; padding:10px 8px; border:1px solid #e9eff3;">{{ $item['reference'] ?? '-' }}</td>
                      <td style="color:#242b3d; padding:10px 8px; border:1px solid #e9eff3;">{{ $item['name'] ?? '-' }}</td>
                      <td style="color:#242b3d; padding:10px 8px; border:1px solid #e9eff3;">{{ $item['revenue'] ?? '-' }}</td>
                    </tr>
                  @endforeach
                @else
                  <tr><td colspan="3" style="text-align:center; color:#82899a; padding:10px 8px;">No top selling items available.</td></tr>
                @endif
              </table>
              <div style="text-align:center; margin:32px 0;">
                <a href="{{ $btn_link ?? url('/organisation/reports') }}" style="background:#242b3d; color:#fff; text-decoration:none; font-weight:bold; font-size:16px; padding:14px 36px; border-radius:6px; display:inline-block;">{{ $btn_name ?? 'View Full Report' }}</a>
              </div>
            </td>
          </tr>
          <!-- Footer -->
          <tr>
            <td style="background:#f4f6fa; text-align:center; color:#82899a; font-size:13px; padding:24px 0 16px 0; border-radius:0 0 4px 4px;">©{{ date('Y') }} Valomnia</td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
          