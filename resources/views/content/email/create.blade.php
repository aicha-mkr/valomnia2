@extends('layouts.contentNavbarLayout')

@section('title', 'Create New Template')

@section('content')
  <div class="row">
    <div class="col-md-12">
      <div class="nav-align-top">
        <ul class="nav nav-pills flex-column flex-md-row mb-6">
          <li class="nav-item">
            <a class="nav-link" href="{{ url('organisation/email/liste') }}">
              <button type="button" class="btn btn-primary">
                <span class="bx bx-left-arrow-alt bx-sm me-2"></span>Return
              </button>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </div>
  <div class="text-center mb-4">
    <button id="rapportBtn" class="btn rounded-pill btn-primary"
            onclick="toggleSections('rapport', this)">Report</button>
    <button id="alerteBtn" class="btn rounded-pill btn-label-primary"
            onclick="toggleSections('alerte', this)">Alert</button>
  </div>

  <div class="row">
    <!-- Report Section -->
    <div id="rapportSection" class="col-md-4 section" style="display: block;">
      <!-- 30% width -->
      <div class="card mb-6">
        <h5 class="card-header">Report Form</h5>
        <div class="card-body">
          <form id="rapportForm" action="{{ route('organisation.email.templates.store') }}" method="POST"
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
              <label for="rapport-email-subject" class="form-label">Subject</label>
              <input type="text" class="form-control" id="rapport-email-subject" name="subject"
                     placeholder="Email subject" required />
            </div>

            <div class="mb-4">
              <label for="rapport-title" class="form-label">Title</label>
              <input type="text" class="form-control" id="rapport-title" name="title"
                     placeholder="Report title" required oninput="updateReportTemplateContent()" />
            </div>

            <div class="mb-4">
              <label for="rapport-content" class="form-label">Content</label>
              <textarea class="form-control" id="rapport-content" rows="3"
                        placeholder="Report content" required oninput="updateReportTemplateContent()"></textarea>
            </div>

            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="report-open"
                     onclick="toggleReportSection()" checked />
              <label class="form-check-label" for="report-open">Show Report Button</label>
            </div>

            <div id="urlSection" class="mb-4" style="display: block;">
              <label for="report-url" class="form-label">Specific URL</label>
              <input type="url" class="form-control" id="report-url" name="btn_link"
                     placeholder="Specific URL">
            </div>

            <div class="mb-4" id="buttonTitleSection" style="display: block;">
              <label for="button-title" class="form-label">Button Title</label>
              <input type="text" class="form-control" id="button-rr" name="btn_name"
                     placeholder="Enter button title" value="View Full Report" oninput="updateReportButtonText()" />
            </div>

            <h5>Select KPIs</h5>
            <div class="mb-4">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="total_orders"
                       id="rapportTotalOrdersCheckbox" name="kpi[]" checked onchange="updateReportKPIs()" />
                <label class="form-check-label" for="rapportTotalOrdersCheckbox">Total Orders</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="total_revenue"
                       id="rapportTotalRevenueCheckbox" name="kpi[]" checked onchange="updateReportKPIs()" />
                <label class="form-check-label" for="rapportTotalRevenueCheckbox">Total Revenue</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="average_sales"
                       id="rapportAverageSalesCheckbox" name="kpi[]" checked onchange="updateReportKPIs()" />
                <label class="form-check-label" for="rapportAverageSalesCheckbox">Average Sales</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="total_quantities"
                       id="rapportTotalQuantitiesCheckbox" name="kpi[]" checked onchange="updateReportKPIs()" />
                <label class="form-check-label" for="rapportTotalQuantitiesCheckbox">Total Quantities</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="total_clients"
                       id="rapportTotalClientsCheckbox" name="kpi[]" checked onchange="updateReportKPIs()" />
                <label class="form-check-label" for="rapportTotalClientsCheckbox">Total Clients</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="top_selling_items"
                       id="rapportTopSellingItemsCheckbox" name="kpi[]" checked onchange="updateReportKPIs()" />
                <label class="form-check-label" for="rapportTopSellingItemsCheckbox">Top Selling Items</label>
              </div>
            </div>

            <div class="mb-4">
              <button type="submit" class="btn btn-primary">Create Report</button>
            </div>
            
            <!-- Hidden fields for form submission -->
            <input type="hidden" id="hidden-title" name="title" />
            <input type="hidden" id="hidden-content" name="content" />
          </form>

        </div>
      </div>
    </div>
    <!-- Email Template for Rapport -->
    <div id="rapportTemplate" class="col-md-8" style="display: none;">
      <!-- 70% width -->
      <div class="card mb-6">
        <div class="card-body">
          <div>
            <html xmlns="http://www.w3.org/1999/xhtml">

            <head>
              <title>Preview Fullscreen</title>
              <style type="text/css">
                a {
                  text-decoration: none;
                  outline: none;
                }

                @media (max-width: 509px) {
                  .o_col-full {
                    max-width: 100% !important;
                  }

                  .o_col-half {
                    max-width: 50% !important;
                  }

                  .o_hide-lg {
                    display: inline-block !important;
                    font-size: inherit !important;
                    max-height: none !important;
                    line-height: inherit !important;
                    overflow: visible !important;
                    width: auto !important;
                    visibility: visible !important;
                  }

                  .o_hide-xs,
                  .o_hide-xs.o_col_i {
                    display: none !important;
                    font-size: 0 !important;
                    max-height: 0 !important;
                    width: 0 !important;
                    line-height: 0 !important;
                    overflow: hidden !important;
                    visibility: hidden !important;
                    height: 0 !important;
                  }

                  .o_xs-center {
                    text-align: center !important;
                  }

                  .o_xs-left {
                    text-align: left !important;
                  }

                  .o_xs-right {
                    text-align: left !important;
                  }

                  table.o_xs-left {
                    margin-left: 0 !important;
                    margin-right: auto !important;
                    float: none !important;
                  }

                  table.o_xs-right {
                    margin-left: auto !important;
                    margin-right: 0 !important;
                    float: none !important;
                  }

                  table.o_xs-center {
                    margin-left: auto !important;
                    margin-right: auto !important;
                    float: none !important;
                  }

                  h1.o_heading {
                    font-size: 32px !important;
                    line-height: 41px !important;
                  }

                  h2.o_heading {
                    font-size: 26px !important;
                    line-height: 37px !important;
                  }

                  h3.o_heading {
                    font-size: 20px !important;
                    line-height: 30px !important;
                  }

                  .o_xs-py-md {
                    padding-top: 24px !important;
                    padding-bottom: 24px !important;
                  }

                  .o_xs-pt-xs {
                    padding-top: 8px !important;
                  }

                  .o_xs-pb-xs {
                    padding-bottom: 8px !important;
                  }
                }

                @media screen {
                  @font-face {
                    font-family: 'Roboto';
                    font-style: normal;
                    font-weight: 400;
                    src:
                      local('Roboto'),
                      local('Roboto-Regular'),
                      url(https://fonts.gstatic.com/s/roboto/v18/KFOmCnqEu92Fr1Mu7GxKOzY.woff2) format('woff2');
                    unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F,
                    U+A720-A7FF;
                  }

                  @font-face {
                    font-family: 'Roboto';
                    font-style: normal;
                    font-weight: 400;
                    src:
                      local('Roboto'),
                      local('Roboto-Regular'),
                      url(https://fonts.gstatic.com/s/roboto/v18/KFOmCnqEu92Fr1Mu4mxK.woff2) format('woff2');
                    unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074,
                    U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
                  }

                  @font-face {
                    font-family: 'Roboto';
                    font-style: normal;
                    font-weight: 700;
                    src:
                      local('Roboto Bold'),
                      local('Roboto-Bold'),
                      url(https://fonts.gstatic.com/s/roboto/v18/KFOlCnqEu92Fr1MmWUlfChc4EsA.woff2) format('woff2');
                    unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F,
                    U+A720-A7FF;
                  }

                  @font-face {
                    font-family: 'Roboto';
                    font-style: normal;
                    font-weight: 700;
                    src:
                      local('Roboto Bold'),
                      local('Roboto-Bold'),
                      url(https://fonts.gstatic.com/s/roboto/v18/KFOlCnqEu92Fr1MmWUlfBBc4.woff2) format('woff2');
                    unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074,
                    U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
                  }

                  .o_sans,
                  .o_heading {
                    font-family: 'Roboto', sans-serif !important;
                  }

                  .o_heading,
                  strong,
                  b {
                    font-weight: 700 !important;
                  }

                  a[x-apple-data-detectors] {
                    color: inherit !important;
                    text-decoration: none !important;
                  }
                }

                #canvas td.o_hide,
                #canvas td.o_hide div {
                  display: block !important;
                  font-family: 'Roboto', sans-serif;
                  font-size: 16px !important;
                  color: #000;
                  font-size: inherit !important;
                  max-height: none !important;
                  width: auto !important;
                  line-height: inherit !important;
                  visibility: visible !important;
                }

                .CodeMirror {
                  line-height: 1.4;
                  font-size: 12px;
                  font-family: sans-serif;
                }

                /* Custom styles for report button visibility */
                table[data-module="button-dark"][data-visible="false"] {
                  display: none !important;
                  visibility: hidden !important;
                  opacity: 0 !important;
                }

                table[data-module="button-dark"]:not([data-visible="false"]) {
                  display: table !important;
                  visibility: visible !important;
                  opacity: 1 !important;
                }

                /* Force button visibility when needed */
                .force-show-button {
                  display: table !important;
                  visibility: visible !important;
                  opacity: 1 !important;
                }

                .force-hide-button {
                  display: none !important;
                  visibility: hidden !important;
                  opacity: 0 !important;
                }
              </style>
            </head>

            <body>
            <table data-module="preview-text" data-visible="false" width="100%" cellspacing="0"
                   cellpadding="0" border="0" role="presentation">
              <tbody>
              <tr>
                <td class="o_hide" align="center" style="
              display: none;
              font-size: 0;
              max-height: 0;
              width: 0;
              line-height: 0;
              overflow: hidden;
              mso-hide: all;
              visibility: hidden;
            ">
                  Email Summary (Hidden)
                </td>
              </tr>
              </tbody>
            </table>
            <table data-module="header-primary0" width="100%" cellspacing="0" cellpadding="0" border="0"
                   role="presentation">
              <tbody>
              <tr>
                <td class="o_bg-light o_px-xs o_pt-lg o_xs-pt-xs" align="center"
                    data-bgcolor="Bg Light"
                    style="background-color: #dbe5ea; padding-left: 8px; padding-right: 8px; padding-top: 32px">
                  <!--[if mso]><table width="632" cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td><![endif]-->
                  <table class="o_block" width="100%" cellspacing="0" cellpadding="0" border="0"
                         role="presentation" style="max-width: 632px; margin: 0 auto">
                    <tbody>
                    <tr>
                      <td class="o_bg-primary o_px o_py-md o_br-t o_sans o_text"
                          align="center" data-bgcolor="Bg Primary"
                          data-size="Text Default" data-min="12" data-max="20" style="
                      font-family: Helvetica, Arial, sans-serif;
                      margin-top: 0px;
                      margin-bottom: 0px;
                      font-size: 16px;
                      line-height: 24px;
                      background-color: #fff;
                      border-radius: 4px 4px 0px 0px;
                      padding-left: 16px;
                      padding-right: 16px;
                      padding-top: 24px;
                      padding-bottom: 24px;
                    ">
                        <p style="margin-top: 0px; margin-bottom: 0px">
                          <a class="o_text-white" data-color="White"
                             style="text-decoration: none; outline: none; color: #ffffff"><img
                              src="https://www.valomnia.com/wp-content/themes/jupiter/images/jupiter-logo.png"
                              width="136" height="36" alt="SimpleApp" style="
                            max-width: 136px;
                            -ms-interpolation-mode: bicubic;
                            vertical-align: middle;
                            border: 0;
                            line-height: 100%;
                            height: auto;
                            outline: none;
                            text-decoration: none;
                          " data-crop="false" /></a>
                        </p>
                      </td>
                    </tr>
                    </tbody>
                  </table>
                  <!--[if mso]></td></tr></table><![endif]-->
                </td>
              </tr>
              </tbody>
            </table>
            <table data-module="hero-icon-lines" data-visible="false"
                   data-thumb="http://www.stampready.net/dashboard/editor/user_uploads/zip_uploads/2018/11/19/pcVNfzKjZ3goPqkxr2hYT0ws/service_canceled/thumbnails/hero-icon-lines.png"
                   width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
              <tbody>
              <tr>
                <td class="o_bg-light o_px-xs" align="center" data-bgcolor="Bg Light"
                    style="background-color: #dbe5ea; padding-left: 8px; padding-right: 8px">
                  <!--[if mso]><table width="632" cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td><![endif]-->
                  <table class="o_block" width="100%" cellspacing="0" cellpadding="0" border="0"
                         role="presentation" style="max-width: 632px; margin: 0 auto">
                    <tbody>
                    <tr>
                      <td class="o_bg-ultra_light o_px-md o_py-xl o_xs-py-md o_sans o_text-md o_text-light"
                          align="center" data-bgcolor="Bg Ultra Light" data-color="Light"
                          data-size="Text MD" data-min="15" data-max="23" style="
                      font-family: Helvetica, Arial, sans-serif;
                      margin-top: 0px;
                      margin-bottom: 0px;
                      font-size: 19px;
                      line-height: 28px;
                      background-color: #ebf5fa;
                      color: #82899a;
                      padding-left: 24px;
                      padding-right: 24px;
                      padding-top: 64px;
                      padding-bottom: 64px;
                    ">
                        <table role="presentation" cellspacing="0" cellpadding="0"
                               border="0">
                          <tbody>
                          <tr>
                            <td class="o_bb-primary" height="40" width="32"
                                data-border-bottom-color="Border Primary 2"
                                style="border-bottom: 1px solid #126de5">
                              &nbsp;
                            </td>
                            <td rowspan="2"
                                class="o_sans o_text o_text-secondary o_px o_py"
                                align="center" data-color="Secondary"
                                data-size="Text Default" data-min="12"
                                data-max="20" style="
                              font-family: Helvetica, Arial, sans-serif;
                              margin-top: 0px;
                              margin-bottom: 0px;
                              font-size: 16px;
                              line-height: 24px;
                              color: #424651;
                              padding-left: 16px;
                              padding-right: 16px;
                              padding-top: 16px;
                              padding-bottom: 16px;
                            ">
                              <img src="http://www.stampready.net/dashboard/editor/user_uploads/zip_uploads/2018/11/19/pcVNfzKjZ3goPqkxr2hYT0ws/service_canceled/images/check-48-primary.png"
                                   width="48" height="48" alt="" style="
                                max-width: 48px;
                                -ms-interpolation-mode: bicubic;
                                vertical-align: middle;
                                border: 0;
                                line-height: 100%;
                                height: auto;
                                outline: none;
                                text-decoration: none;
                              " data-crop="false" />
                            </td>
                            <td class="o_bb-primary" height="40" width="32"
                                data-border-bottom-color="Border Primary 2"
                                style="border-bottom: 1px solid #126de5">
                              &nbsp;
                            </td>
                          </tr>
                          <tr>
                            <td height="40">&nbsp;</td>
                            <td height="40">&nbsp;</td>
                          </tr>
                          <tr>
                            <td
                              style="font-size: 8px; line-height: 8px; height: 8px">
                              &nbsp;</td>
                            <td
                              style="font-size: 8px; line-height: 8px; height: 8px">
                              &nbsp;</td>
                          </tr>
                          </tbody>
                        </table>

                        <h2 id="report-title2" class="o_heading o_text-dark o_mb-xxs"
                            data-color="Dark" data-size="Heading 2" data-min="20"
                            data-max="40" style="
                        font-family: Helvetica, Arial, sans-serif;
                        font-weight: bold;
                        margin-top: 0px;
                        margin-bottom: 4px;
                        color: #242b3d;
                        font-size: 30px;
                        line-height: 39px;">
                          Report Title
                        </h2>
                      </td>
                    </tr>
                    </tbody>
                  </table>
                  <!--[if mso]></td></tr></table><![endif]-->
                </td>

              </tbody>
            </table>
            <table data-module="spacer0"
                   data-thumb="http://www.stampready.net/dashboard/editor/user_uploads/zip_uploads/2018/11/19/pcVNfzKjZ3goPqkxr2hYT0ws/service_canceled/thumbnails/spacer.png"
                   width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
              <tbody>
              <tr>
                <td class="o_bg-light o_px-xs" align="center" data-bgcolor="Bg Light"
                    style="background-color: #dbe5ea; padding-left: 8px; padding-right: 8px">
                  <!--[if mso]><table width="632" cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td><![endif]-->
                  <table class="o_block" width="100%" cellspacing="0" cellpadding="0" border="0"
                         role="presentation" style="max-width: 632px; margin: 0 auto">
                    <tbody>
                    <tr>
                      <td class="o_bg-white"
                          style="font-size: 24px; line-height: 24px; height: 24px; background-color: #ffffff"
                          data-bgcolor="Bg White">
                        &nbsp;
                      </td>
                    </tr>
                    </tbody>
                  </table>
                  <!--[if mso]></td></tr></table><![endif]-->
                </td>
              </tr>
              </tbody>
            </table>
            <!--[if mso]></td></tr></table><![endif]-->
            </td>
            </tr>
            </tbody>
            </table>
            <table data-module="spacer0"
                   data-thumb="http://www.stampready.net/dashboard/editor/user_uploads/zip_uploads/2018/11/19/pcVNfzKjZ3goPqkxr2hYT0ws/service_canceled/thumbnails/spacer.png"
                   width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
              <tbody>
              <tr>
                <td class="o_bg-light o_px-xs" align="center" data-bgcolor="Bg Light"
                    style="background-color: #dbe5ea; padding-left: 8px; padding-right: 8px">
                  <!--[if mso]><table width="632" cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td><![endif]-->
                  <table class="o_block" width="100%" cellspacing="0" cellpadding="0" border="0"
                         role="presentation" style="max-width: 632px; margin: 0 auto">
                    <tbody>
                    <tr>
                      <td class="o_bg-white"
                          style="font-size: 24px; line-height: 24px; height: 24px; background-color: #ffffff"
                          data-bgcolor="Bg White">
                        &nbsp;
                      </td>
                    </tr>
                    </tbody>
                  </table>
                  <!--[if mso]></td></tr></table><![endif]-->
                </td>
              </tr>
              </tbody>
            </table>
            <!--[if mso]></td></tr></table><![endif]-->
            </td>
            </tr>
            </tbody>
            </table>

            <table data-module="content-lg" data-visible="false"
                   data-thumb="http://www.stampready.net/dashboard/editor/user_uploads/zip_uploads/2018/11/19/pcVNfzKjZ3goPqkxr2hYT0ws/service_canceled/thumbnails/content-lg.png"
                   width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
              <tbody>
              <tr>
                <td class="o_bg-light o_px-xs" align="center" data-bgcolor="Bg Light"
                    style="background-color: #dbe5ea; padding-left: 8px; padding-right: 8px">
                  <!--[if mso]><table width="632" cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td><![endif]-->
                  <table class="o_block" width="100%" cellspacing="0" cellpadding="0" border="0"
                         role="presentation" style="max-width: 632px; margin: 0 auto">
                    <tbody>
                    <tr>
                      <td class="o_bg-white o_px-md o_py o_sans o_text o_text-secondary"
                          align="center" data-bgcolor="Bg White" data-color="Secondary"
                          data-size="Text Default" data-min="12" data-max="20" style="
                    font-family: Helvetica, Arial, sans-serif;
                    margin-top: 0px;
                    margin-bottom: 0px;
                    font-size: 16px;
                    line-height: 24px;
                    background-color: #ffffff;
                    color: #424651;
                    padding-left: 24px;
                    padding-right: 24px;
                    padding-top: 16px;
                    padding-bottom: 16px;">
                        <p id="rapport-template-text"
                           style="margin-top: 0px; margin-bottom: 0px">
                          Welcome to your comprehensive business report. This document provides a detailed overview of your company's performance metrics and key insights for the reporting period. Below you will find the most important indicators that reflect your business growth and operational efficiency.
                        </p>
                        
                        <!-- KPIs Preview Section -->
                        <div id="rapport-kpis-preview" style="margin-top: 20px;">
                          <!-- KPIs will be dynamically inserted here -->
                        </div>
                      </td>
                    </tr>
                    </tbody>
                  </table>
                  <!--[if mso]></td></tr></table><![endif]-->
                </td>
              </tr>
              </tbody>
            </table>

            <table data-module="spacer0"
                   data-thumb="http://www.stampready.net/dashboard/editor/user_uploads/zip_uploads/2018/11/19/pcVNfzKjZ3goPqkxr2hYT0ws/service_canceled/thumbnails/spacer.png"
                   width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
              <tbody>
              <tr>
                <td class="o_bg-light o_px-xs" align="center" data-bgcolor="Bg Light"
                    style="background-color: #dbe5ea; padding-left: 8px; padding-right: 8px">
                  <!--[if mso]><table width="632" cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td><![endif]-->
                  <table class="o_block" width="100%" cellspacing="0" cellpadding="0" border="0"
                         role="presentation" style="max-width: 632px; margin: 0 auto">
                    <tbody>
                    <tr>
                      <td class="o_bg-white"
                          style="font-size: 24px; line-height: 24px; height: 24px; background-color: #ffffff"
                          data-bgcolor="Bg White">
                        &nbsp;
                      </td>
                    </tr>
                    </tbody>
                  </table>
                  <!--[if mso]></td></tr></table><![endif]-->
                </td>
              </tr>
              </tbody>
            </table>
            <!--[if mso]></td></tr></table><![endif]-->
            </td>
            </tr>
            </tbody>
            </table>
            <table data-module="button-dark" data-visible="false"
                   data-thumb="http://www.stampready.net/dashboard/editor/user_uploads/zip_uploads/2018/11/19/pcVNfzKjZ3goPqkxr2hYT0ws/service_canceled/thumbnails/button-dark.png"
                   width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
              <tbody>
              <tr>
                <td id="reportTemplate" class="o_bg-light o_px-xs" align="center"
                    data-bgcolor="Bg Light"
                    style="background-color: #dbe5ea; padding-left: 8px; padding-right: 8px">
                  <!--[if mso]><table width="632" cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td><![endif]-->
                  <table class="o_block" width="100%" cellspacing="0" cellpadding="0" border="0"
                         role="presentation" style="max-width: 632px; margin: 0 auto">
                    <tbody>
                    <tr>
                      <td class="o_bg-white o_px-md o_py-xs" align="center"
                          data-bgcolor="Bg White" style="
                background-color: #ffffff;
                padding-left: 24px;
                padding-right: 24px;
                padding-top: 8px;
                padding-bottom: 8px;
              ">
                        <table align="center" cellspacing="0" cellpadding="0" border="0"
                               role="presentation">
                          <tbody>
                          <tr>
                            <td width="300"
                                class="o_btn o_bg-dark o_br o_heading o_text"
                                align="center" data-bgcolor="Bg Dark"
                                data-size="Text Default" data-min="12"
                                data-max="20" style="
                        font-family: Helvetica, Arial, sans-serif;
                        font-weight: bold;
                        margin-top: 0px;
                        margin-bottom: 0px;
                        font-size: 16px;
                        line-height: 24px;
                        mso-padding-alt: 12px 24px;
                        background-color: #242b3d;
                        border-radius: 4px;
                      ">
                              <a id="action-link2" class=" o_text-white"
                                 href="https://example.com/"
                                 data-color="White" style="
                          text-decoration: none;
                          outline: none;
                          color: #ffffff;
                          display: block;
                          padding: 12px 24px;
                          mso-text-raise: 3px;
                        ">View Full Report</a>
                            </td>
                          </tr>
                          </tbody>
                        </table>
                      </td>
                    </tr>
                    </tbody>
                  </table>
                  <!--[if mso]></td></tr></table><![endif]-->
                </td>
              </tr>
              </tbody>
            </table>

            <table data-module="spacer0"
                   data-thumb="http://www.stampready.net/dashboard/editor/user_uploads/zip_uploads/2018/11/19/pcVNfzKjZ3goPqkxr2hYT0ws/service_canceled/thumbnails/spacer.png"
                   width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
              <tbody>
              <tr>
                <td class="o_bg-light o_px-xs" align="center" data-bgcolor="Bg Light"
                    style="background-color: #dbe5ea; padding-left: 8px; padding-right: 8px">
                  <!--[if mso]><table width="632" cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td><![endif]-->
                  <table class="o_block" width="100%" cellspacing="0" cellpadding="0" border="0"
                         role="presentation" style="max-width: 632px; margin: 0 auto">
                    <tbody>
                    <tr>
                      <td class="o_bg-white"
                          style="font-size: 24px; line-height: 24px; height: 24px; background-color: #ffffff"
                          data-bgcolor="Bg White">
                        &nbsp;
                      </td>
                    </tr>
                    </tbody>
                  </table>
                  <!--[if mso]></td></tr></table><![endif]-->
                </td>
              </tr>
              </tbody>
            </table>
            <!--[if mso]></td></tr></table><![endif]-->
            </td>
            </tr>
            </tbody>
            </table>
            <table data-module="spacer0"
                   data-thumb="http://www.stampready.net/dashboard/editor/user_uploads/zip_uploads/2018/11/19/pcVNfzKjZ3goPqkxr2hYT0ws/service_canceled/thumbnails/spacer.png"
                   width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
              <tbody>
              <tr>
                <td class="o_bg-light o_px-xs" align="center" data-bgcolor="Bg Light"
                    style="background-color: #dbe5ea; padding-left: 8px; padding-right: 8px">
                  <!--[if mso]><table width="632" cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td><![endif]-->
                  <table class="o_block" width="100%" cellspacing="0" cellpadding="0" border="0"
                         role="presentation" style="max-width: 632px; margin: 0 auto">
                    <tbody>
                    <tr>
                      <td class="o_bg-white"
                          style="font-size: 24px; line-height: 24px; height: 24px; background-color: #ffffff"
                          data-bgcolor="Bg White">
                        &nbsp;
                      </td>
                    </tr>
                    </tbody>
                  </table>
                  <!--[if mso]></td></tr></table><![endif]-->
                </td>
              </tr>
              </tbody>
            </table>
            <!--[if mso]></td></tr></table><![endif]-->
            </td>
            </tr>
            </tbody>
            </table>
            <table data-module="spacer0"
                   data-thumb="http://www.stampready.net/dashboard/editor/user_uploads/zip_uploads/2018/11/19/pcVNfzKjZ3goPqkxr2hYT0ws/service_canceled/thumbnails/spacer.png"
                   width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
              <tbody>
              <tr>
                <td class="o_bg-light o_px-xs" align="center" data-bgcolor="Bg Light"
                    style="background-color: #dbe5ea; padding-left: 8px; padding-right: 8px">
                  <!--[if mso]><table width="632" cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td><![endif]-->
                  <table class="o_block" width="100%" cellspacing="0" cellpadding="0" border="0"
                         role="presentation" style="max-width: 632px; margin: 0 auto">
                    <tbody>
                    <tr>
                      <td class="o_bg-white"
                          style="font-size: 24px; line-height: 24px; height: 24px; background-color: #ffffff"
                          data-bgcolor="Bg White">
                        &nbsp;
                      </td>
                    </tr>
                    </tbody>
                  </table>
                  <!--[if mso]></td></tr></table><![endif]-->
                </td>
              </tr>
              </tbody>
            </table>
            <!--[if mso]></td></tr></table><![endif]-->
            </td>
            </tr>
            </tbody>
            </table>

            <table data-module="order-summary0"
                   data-thumb="http://www.stampready.net/dashboard/editor/user_uploads/zip_uploads/2020/03/13/0D6ItbpLZUSmhj3YORzyfKEg/account_addons/thumbnails/order-summary.png"
                   width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
              <tbody>
              <tr>
                <td class="o_bg-light o_px-xs" align="center" data-bgcolor="Bg Light"
                    style="background-color: #dbe5ea;padding-left: 8px;padding-right: 8px;">
                  <!--[if mso]><table width="632" cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td><![endif]-->
                  <table class="o_block" width="100%" cellspacing="0" cellpadding="0" border="0"
                         role="presentation" style="max-width: 632px;margin: 0 auto;">
                    <tbody>
                    <tr>
                      <td class="o_bg-white o_sans o_text-xs o_text-light o_px-md o_pt-xs"
                          align="center" data-bgcolor="Bg White" data-color="Light"
                          data-size="Text XS" data-min="10" data-max="18"
                          style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 14px;line-height: 21px;background-color: #ffffff;color: #82899a;padding-left: 24px;padding-right: 24px;padding-top: 8px;">
                        <!-- TOP 5 PRODUCTS table will be shown dynamically via KPIs selection -->
                      </td>
                    </tr>
                    </tbody>
                  </table>
                  <!--[if mso]></td></tr></table><![endif]-->
                </td>
              </tr>
              </tbody>
            </table>

    
          

            <table data-module="footer" data-visible="false"
                   data-thumb="http://www.stampready.net/dashboard/editor/user_uploads/zip_uploads/2018/11/19/pcVNfzKjZ3goPqkxr2hYT0ws/service_canceled/thumbnails/footer.png"
                   width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
              <tbody>
              <tr>
                <td class="o_bg-light o_px-xs o_pb-lg o_xs-pb-xs" align="center"
                    data-bgcolor="Bg Light"
                    style="background-color: #dbe5ea; padding-left: 8px; padding-right: 8px; padding-bottom: 32px">
                  <!--[if mso]><table width="632" cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td><![endif]-->
                  <table class="o_block" width="100%" cellspacing="0" cellpadding="0" border="0"
                         role="presentation" style="max-width: 632px; margin: 0 auto">
                    <tbody>
                    <tr>


                      <td class="o_bg-white o_px-md o_py-lg o_bt-light o_br-b o_sans o_text-xs o_text-light"
                          align="center" data-color="Light" data-size="Text XS"
                          data-min="10" data-max="18" data-border-top-color="Border Light"
                          style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 14px;line-height: 21px;background-color: #ffffff;color: #82899a;border-top: 1px solid #d3dce0;border-radius: 0px 0px 4px 4px;padding-left: 50px;padding-right: 50px;padding-top: 32px;padding-bottom: 32px;">

                        <p class="o_mb" style="margin-top: 0px;margin-bottom: 16px;">
                          ©2025 Valomnia
                        </p>

                      </td>
                </td>

                </td>
              </tr>
              </tbody>
            </table>
            <!--[if mso]></td></tr></table><![endif]-->
            <div class="o_hide-xs" style="font-size: 64px; line-height: 64px; height: 64px">&nbsp;</div>
            </td>
            </tr>
            </tbody>
            </table>



            </body>

            </html>
          </div>
        </div>
      </div>
    </div>
  </div>
    <!-- Alert Section -->
  <div id="alerteSection" class="section" style="display: none;">
    <div class="row">
      <!-- Alert Form -->
      <div class="col-md-4">
        <div class="card mb-6">
          <h5 class="card-header">Alert Form</h5>
          <div class="card-body">
            <form id="alerteForm" action="{{ route('organisation.email.templates.store') }}" method="POST">
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
              <input type="hidden" name="type" value="Alert" />

              <!-- Required alert_id field -->
              <input type="hidden" name="alert_id" value="1" />

              <div class="mb-4">
                <label for="alerte-email-subject" class="form-label">Subject</label>
                <input type="text" class="form-control" id="alerte-email-subject" name="subject"
                       placeholder="Email subject" required oninput="updateTemplateSubject()" />
              </div>

              <div class="mb-4">
                <label for="alerte-title" class="form-label">Title</label>
                <input type="text" class="form-control" id="alerte-title" name="title"
                       placeholder="Alert title" required oninput="updateTemplateTitle()" />
              </div>

              <div class="mb-4">
                <label for="alert-type-selector" class="form-label">Alert Type</label>
                <select class="form-select" id="alert-type-selector">
                  <option value="custom" data-slug="custom">Custom Alert</option>
                  <option value="stock" data-slug="expired-stock">Stock Alert</option>
                  <option value="checkin" data-slug="checkin-out-of-hours">checkin out of hours</option>
                  <option value="sales" data-slug="vente-seuil-depasse-pdv" selected>Alerte de seuil de ventes</option>
                </select>
              </div>

              <div class="mb-4">
                <label for="alert-description" class="form-label">Description</label>
                <textarea class="form-control" id="alert-description" rows="3"
                          placeholder="Enter a description for this alert..." required
                          oninput="updateAlertDescription()"></textarea>
              </div>

              

              <!-- Hidden content field -->
              <input type="hidden" id="hidden-content" name="content" />

              <div class="mb-4">
                <button type="submit" class="btn btn-primary">Create Alert</button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Email Template for Alerte -->
      <div id="alerteTemplate" class="col-md-8">
        <div class="card mb-6">
          <div class="card-body">
        <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
          <style type="text/css">
            a {
              text-decoration: none;
              outline: none;
            }
            @media (max-width: 449px) {
              .o_col-full {
                max-width: 100% !important;
              }

              .o_col-half {
                max-width: 50% !important;
              }

              .o_hide-lg {
                display: inline-block !important;
                font-size: inherit !important;
                max-height: none !important;
                line-height: inherit !important;
                overflow: visible !important;
                width: auto !important;
                visibility: visible !important;
              }

              .o_hide-xs,
              .o_hide-xs.o_col_i {
                display: none !important;
                font-size: 0 !important;
                max-height: 0 !important;
                width: 0 !important;
                line-height: 0 !important;
                overflow: hidden !important;
                visibility: hidden !important;
                height: 0 !important;
              }

              .o_xs-center {
                text-align: center !important;
              }

              .o_xs-left {
                text-align: left !important;
              }

              .o_xs-right {
                text-align: left !important;
              }

              table.o_xs-left {
                margin-left: 0 !important;
                margin-right: auto !important;
                float: none !important;
              }

              table.o_xs-right {
                margin-left: auto !important;
                margin-right: 0 !important;
                float: none !important;
              }

              table.o_xs-center {
                margin-left: auto !important;
                margin-right: auto !important;
                float: none !important;
              }

              h1.o_heading {
                font-size: 32px !important;
                line-height: 41px !important;
              }

              h2.o_heading {
                font-size: 26px !important;
                line-height: 37px !important;
              }

              h3.o_heading {
                font-size: 20px !important;
                line-height: 30px !important;
              }

              .o_xs-py-md {
                padding-top: 24px !important;
                padding-bottom: 24px !important;
              }

              .o_xs-pt-xs {
                padding-top: 8px !important;
              }

              .o_xs-pb-xs {
                padding-bottom: 8px !important;
              }

              table.stock-table {
                width: 100% !important;
              }

              table.stock-table th,
              table.stock-table td {
                font-size: 14px !important;
                padding: 6px !important;
              }
            }

            @media screen {
              @font-face {
                font-family: 'Roboto';
                font-style: normal;
                font-weight: 400;
                src: local("Roboto"), local("Roboto-Regular"), url(https://fonts.gstatic.com/s/roboto/v18/KFOmCnqEu92Fr1Mu7GxKOzY.woff2) format("woff2");
                unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
              }
              @font-face {
                font-family: 'Roboto';
                font-style: normal;
                font-weight: 400;
                src: local("Roboto"), local("Roboto-Regular"), url(https://fonts.gstatic.com/s/roboto/v18/KFOmCnqEu92Fr1Mu4mxK.woff2) format("woff2");
                unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
              }
              @font-face {
                font-family: 'Roboto';
                font-style: normal;
                font-weight: 700;
                src: local("Roboto Bold"), local("Roboto-Bold"), url(https://fonts.gstatic.com/s/roboto/v18/KFOlCnqEu92Fr1MmWUlfChc4EsA.woff2) format("woff2");
                unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
              }
              @font-face {
                font-family: 'Roboto';
                font-style: normal;
                font-weight: 700;
                src: local("Roboto Bold"), local("Roboto-Bold"), url(https://fonts.gstatic.com/s/roboto/v18/KFOlCnqEu92Fr1MmWUlfBBc4.woff2) format("woff2");
                unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
              }
              .o_sans, .o_heading {
                font-family: "Roboto", sans-serif !important;
              }

              .o_heading, strong, b {
                font-weight: 700 !important;
              }

              a[x-apple-data-detectors] {
                color: inherit !important;
                text-decoration: none !important;
              }

              .button {
                padding: 10px 20px;
                background-color: #0ec06e;
                color: white;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                margin: 20px 0;
              }

              table.stock-table {
                border-collapse: collapse;
                width: 100%;
                margin: 16px 0;
              }

              table.stock-table th {
                background-color: #f8f9fa;
                padding: 8px;
                text-align: left;
                font-weight: bold;
                border: 1px solid #d3dce0;
              }

              table.stock-table td {
                padding: 8px;
                border: 1px solid #d3dce0;
              }

              .quantity-alert {
                color: #dc3545;
                font-weight: bold;
                font-size: 1.1em;
              }

              .product-name {
                color: #0366d6;
                font-weight: bold;
                font-style: italic;
              }

              .warehouse-name {
                color: #6f42c1;
                font-weight: bold;
              }

              .threshold-value {
                color: #fd7e14;
                font-weight: bold;
              }

              .timestamp {
                font-size: 12px;
                color: #82899a;
                margin-top: 8px;
              }
            }

            #canvas td.o_hide, #canvas td.o_hide div {
              display: block !important;
              font-family: "Roboto", sans-serif;
              font-size: 16px !important;
              color: #000;
              font-size: inherit !important;
              max-height: none !important;
              width: auto !important;
              line-height: inherit !important;
              visibility: visible !important;
            }

            .CodeMirror {
              line-height: 1.4;
              font-size: 12px;
              font-family: sans-serif;
            }
          </style>
        </head>
        <body>
        <table data-module="preview-text" data-visible="false" width="100%" cellspacing="0"
               cellpadding="0" border="0" role="presentation">
          <tbody>
          <tr>
            <td class="o_hide" align="center" style="
                display: none;
                font-size: 0;
                max-height: 0;
                width: 0;
                line-height: 0;
                overflow: hidden;
                mso-hide: all;
                visibility: hidden;
              ">
              Email Summary (Hidden)
            </td>
          </tr>
          </tbody>
        </table>
        <table data-module="header0" data-thumb="http://www.stampready.net/dashboard/editor/user_uploads/zip_uploads/2018/11/19/8pREHJbyxUVqTg6cslF4iBY3/account_verification/thumbnails/header.png" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
          <tbody>
          <tr>
            <td class="o_bg-light o_px-xs o_pt-lg o_xs-pt-xs" align="center" data-bgcolor="Bg Light" style="background-color: #dbe5ea;padding-left: 8px;padding-right: 8px;padding-top: 32px;">
              <!--[if mso]><table width="432" cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td><![endif]-->
              <table class="o_block-xs" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" style="max-width: 432px;margin: 0 auto;">
                <tbody>
                <tr>
                  <td class="o_bg-dark o_px o_py-md o_br-t o_sans o_text" align="center" data-bgcolor="Bg Dark" data-size="Text Default" data-min="12" data-max="20" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;background-color: #fff;border-radius: 4px 4px 0px 0px;padding-left: 16px;padding-right: 16px;padding-top: 24px;padding-bottom: 24px;">
                    <p style="margin-top: 0px;margin-bottom: 0px;"><a class="o_text-white" href="#" data-color="White" style="text-decoration: none;outline: none;color: #ffffff;"><img src="https://www.valomnia.com/wp-content/themes/jupiter/images/jupiter-logo.png" width="136" height="36" alt="SimpleApp" style="max-width: 136px;-ms-interpolation-mode: bicubic;vertical-align: middle;border: 0;line-height: 100%;height: auto;outline: none;text-decoration: none;"></a></p>
                  </td>
                </tr>
                </tbody>
              </table>
              <!--[if mso]></td></tr></table><![endif]-->
            </td>
          </tr>
          </tbody>
        </table>
        <table data-module="hero-icon-lines0" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
          <tbody>
          <tr>
            <td class="o_bg-light o_px-xs" align="center" data-bgcolor="Bg Light" style="background-color: #dbe5ea;padding-left: 8px;padding-right: 8px;">
              <!--[if mso]><table width="432" cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td><![endif]-->
              <table class="o_block-xs" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" style="max-width: 432px;margin: 0 auto;">
                <tbody>
                <tr>
                  <td class="o_bg-ultra_light o_px-md o_py-xl o_xs-py-md o_sans o_text-md o_text-light" align="center" data-bgcolor="Bg Ultra Light" data-color="Light" data-size="Text MD" data-min="15" data-max="23" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 19px;line-height: 28px;background-color: #ebf5fa;color: #82899a;padding-left: 24px;padding-right: 24px;padding-top: 64px;padding-bottom: 64px;">
                    <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                      <tbody>
                      <tr>
                        <td class="o_bb-primary" height="40" width="32" data-border-bottom-color="Border Primary 2" style="border-bottom: 1px solid #126de5;"> </td>
                        <td rowspan="2" class="o_sans o_text o_text-secondary o_px o_py" align="center" data-color="Secondary" data-size="Text Default" data-min="12" data-max="20" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;color: #424651;padding-left: 16px;padding-right: 16px;padding-top: 16px;padding-bottom: 16px;">
                          <img src="{{asset('assets/img/email-setting/alert/default.png')}}" width="48" height="48" alt="" style="max-width: 48px;-ms-interpolation-mode: bicubic;vertical-align: middle;border: 0;line-height: 100%;height: auto;outline: none;text-decoration: none;">
                        </td>
                        <td class="o_bb-primary" height="40" width="32" data-border-bottom-color="Border Primary 2" style="border-bottom: 1px solid #126de5;"> </td>
                      </tr>
                      <tr>
                        <td height="40"> </td>
                        <td height="40"> </td>
                      </tr>
                      <tr>
                        <td style="font-size: 8px; line-height: 8px; height: 8px;"> </td>
                        <td style="font-size: 8px; line-height: 8px; height: 8px;"> </td>
                      </tr>
                      </tbody>
                    </table>
                    <h2 class="o_heading o_text-dark o_mb-xxs" id="template-title" data-color="Dark" data-size="Heading 2" data-min="20" data-max="40" style="font-family: Helvetica, Arial, sans-serif;font-weight: bold;margin-top: 0px;margin-bottom: 4px;color: #242b3d;font-size: 30px;line-height: 39px;">
                      Alerte de seuil de ventes                    </h2>
                  </td>
                </tr>
                </tbody>
              </table>
              <!--[if mso]></td></tr></table><![endif]-->
            </td>
          </tr>
          </tbody>
        </table>
        <table data-module="spacer0" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
          <tbody>
          <tr>
            <td class="o_bg-light o_px-xs" align="center" data-bgcolor="Bg Light" style="background-color: #dbe5ea;padding-left: 8px;padding-right: 8px;">
              <!--[if mso]><table width="432" cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td><![endif]-->
              <table class="o_block-xs" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" style="max-width: 432px;margin: 0 auto;">
                <tbody>
                <tr>
                  <td class="o_bg-white" style="font-size: 24px;line-height: 24px;height: 24px;background-color: #ffffff;" data-bgcolor="Bg White"> </td>
                </tr>
                </tbody>
              </table>
              <!--[if mso]></td></tr></table><![endif]-->
            </td>
          </tr>
          </tbody>
        </table>
        <table data-module="content0" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
          <tbody>
          <tr>
            <td class="o_bg-light o_px-xs" align="center" data-bgcolor="Bg Light" style="background-color: #dbe5ea;padding-left: 8px;padding-right: 8px;">
              <!--[if mso]><table width="432" cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td><![endif]-->
              <table class="o_block-xs" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" style="max-width: 432px;margin: 0 auto;">
                <tbody>
                <tr>
                  <td class="o_bg-white o_px-md o_py o_sans o_text o_text-secondary" align="left" data-bgcolor="Bg White" data-color="Secondary" data-size="Text Default" data-min="12" data-max="20" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;background-color: #ffffff;color: #424651;padding-left: 24px;padding-right: 24px;padding-top: 16px;padding-bottom: 16px;">
                    <p id="alert-description-preview" style="margin-top: 0px;margin-bottom: 16px;">The following points of sale have exceeded the defined sales threshold:</p>
                    <div id="template-text-alert">
                      <table class="stock-table" style="width:100%; border-collapse: collapse; margin-top: 10px; margin-bottom: 16px; font-family: Helvetica, Arial, sans-serif;">
                        <thead><tr>
                          <th style="border: 1px solid #d3dce0; padding: 8px; background-color: #f8f9fa; text-align: left; font-weight: bold; font-size: 14px;">Point of Sale</th>
                          <th style="border: 1px solid #d3dce0; padding: 8px; background-color: #f8f9fa; text-align: left; font-weight: bold; font-size: 14px;">Sales Amount</th>
                          <th style="border: 1px solid #d3dce0; padding: 8px; background-color: #f8f9fa; text-align: left; font-weight: bold; font-size: 14px;">Threshold</th>
                          <th style="border: 1px solid #d3dce0; padding: 8px; background-color: #f8f9fa; text-align: left; font-weight: bold; font-size: 14px;">Excess</th>
                        </tr></thead>
                        <tbody><tr>
                          <td style="border: 1px solid #d3dce0; padding: 8px; font-size: 14px;"><span style="color: #0366d6; font-weight: bold;">Downtown Store</span></td>
                          <td style="border: 1px solid #d3dce0; padding: 8px; font-size: 14px;"><span style="color: #28a745; font-weight: bold;">15,000 TND</span></td>
                          <td style="border: 1px solid #d3dce0; padding: 8px; font-size: 14px;"><span style="color: #fd7e14; font-weight: bold;">10,000 TND</span></td>
                          <td style="border: 1px solid #d3dce0; padding: 8px; font-size: 14px;"><span style="color: #dc3545; font-weight: bold;">+50%</span></td>
                        </tr>
                        </tbody>
                      </table>
                      <p class="timestamp" style="font-size: 12px; color: #82899a; margin-top: 8px;">Alert generated on: [Date and Time]</p>
                      <!-- KPIs Preview for Alert -->
                      <div id="alert-kpis-preview" style="margin-top: 16px;"></div>
                    </div>
                  </td>
                </tr>
                </tbody>
              </table>
              <!--[if mso]></td></tr></table><![endif]-->
            </td>
          </tr>
          </tbody>
        </table>
        <table data-module="spacer0" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
          <tbody>
          <tr>
            <td class="o_bg-light o_px-xs" align="center" data-bgcolor="Bg Light" style="background-color: #dbe5ea;padding-left: 8px;padding-right: 8px;">
              <!--[if mso]><table width="432" cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td><![endif]-->
              <table class="o_block-xs" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" style="max-width: 432px;margin: 0 auto;">
                <tbody>
                <tr>
                  <td class="o_bg-white" style="font-size: 24px;line-height: 24px;height: 24px;background-color: #ffffff;" data-bgcolor="Bg White"> </td>
                </tr>
                </tbody>
              </table>
              <!--[if mso]></td></tr></table><![endif]-->
            </td>
          </tr>
          </tbody>
        </table>
        <table data-module="footer-light-2cols0" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
          <tbody>
          <tr>
            <td class="o_bg-light o_px-xs o_pb-lg o_xs-pb-xs" align="center" data-bgcolor="Bg Light" style="background-color: #dbe5ea;padding-left: 8px;padding-right: 8px;padding-bottom: 32px;">
              <!--[if mso]><table width="432" cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td><![endif]-->
              <table class="o_block-xs" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" style="max-width: 432px;margin: 0 auto;">
                <tbody>
                <tr>
                  <td class="o_bg-white o_br-b o_sans o_text-xs o_text-light" align="center" data-bgcolor="Bg White" data-color="Light" data-size="Text XS" data-min="10" data-max="18" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 14px;line-height: 21px;background-color: #ffffff;color: #82899a;border-radius: 0px 0px 4px 4px;padding: 32px;">
                    <p class="o_mb-xs" style="margin-top: 0px;margin-bottom: 8px;">© 2025 Valomnia. All rights reserved.</p>
                  </td>
                </tr>
                </tbody>
              </table>
              <div class="o_hide-xs" style="font-size: 64px; line-height: 64px; height: 64px;"> </div>
              <!--[if mso]></td></tr></table><![endif]-->
            </td>
          </tr>
          </tbody>
        </table>
        </body>
        </html>
      </div>
    </div>
  </div>
  </div>


  </div>

  <script>
    function toggleSections(section, button) {
      const rapportSection = document.getElementById("rapportSection");
      const alerteSection = document.getElementById("alerteSection");
      const rapportTemplate = document.getElementById("rapportTemplate");
      const alerteTemplate = document.getElementById("alerteTemplate");
      const rapportBtn = document.getElementById("rapportBtn");
      const alerteBtn = document.getElementById("alerteBtn");

      if (section === "rapport") {
        rapportSection.style.display = "block";
        alerteSection.style.display = "none";
        rapportTemplate.style.display = "block";
        alerteTemplate.style.display = "none";
        rapportBtn.classList.add("btn-primary");
        rapportBtn.classList.remove("btn-label-primary");
        alerteBtn.classList.add("btn-label-primary");
        alerteBtn.classList.remove("btn-primary");
      } else {
        rapportSection.style.display = "none";
        alerteSection.style.display = "block";
        rapportTemplate.style.display = "none";
        alerteTemplate.style.display = "block";
        alerteBtn.classList.add("btn-primary");
        alerteBtn.classList.remove("btn-label-primary");
        rapportBtn.classList.add("btn-label-primary");
        rapportBtn.classList.remove("btn-primary");
      }
    }

    function updateTemplateTitle() {
      const title = document.getElementById("alerte-title").value;
      document.getElementById("template-title").innerText = title;
    }

    function updateTemplateSubject() {
      // Subject is not typically displayed in the template body, but you can handle it if needed
    }

    function updateAlertDescription() {
      const description = document.getElementById("alert-description").value;
      document.getElementById("alert-description-preview").innerText = description;

      // Update hidden content with the description and template
      updateTemplateText();
    }

    function updateTemplateText() {
      const description = document.getElementById("alert-description").value || "";

      // Get the current template content
      const templateContent = document.getElementById("template-text-alert").innerHTML;

      // Update the hidden content field with the template content
      document.getElementById("hidden-content").value = templateContent;
    }

    function updateReportTitle() {
      const title = document.getElementById("rapport-title").value;
      document.getElementById("report-title-preview").innerText = title;
    }

    function updateRapportContent() {
      const content = document.getElementById("rapport-content").value;
      document.getElementById("rapport-content-preview").innerText = content;
    }

    function toggleReportSection() {
      const checkbox = document.getElementById("report-open");
      const urlSection = document.getElementById("urlSection");
      const buttonTitleSection = document.getElementById("buttonTitleSection");
      const buttonContainer = document.getElementById("button-container");
      const rapportTemplate = document.getElementById("rapportTemplate");
      const reportButton = document.getElementById("action-link2");
      const reportButtonContainer = reportButton ? reportButton.closest('table[data-module="button-dark"]') : null;

      console.log('=== toggleReportSection DEBUG ===');
      console.log('checkbox checked:', checkbox.checked);
      console.log('rapportTemplate found:', !!rapportTemplate);
      console.log('rapportTemplate display:', rapportTemplate ? rapportTemplate.style.display : 'N/A');
      console.log('reportButton found:', !!reportButton);
      console.log('reportButtonContainer found:', !!reportButtonContainer);
      
      // Check if template is visible
      if (rapportTemplate && rapportTemplate.style.display === "none") {
        console.warn('Template is hidden! Button changes won\'t be visible.');
      }
      
      if (reportButtonContainer) {
        console.log('Button container current display:', reportButtonContainer.style.display);
        console.log('Button container current visibility:', reportButtonContainer.style.visibility);
        console.log('Button container data-visible:', reportButtonContainer.getAttribute('data-visible'));
        console.log('Button container classes:', reportButtonContainer.className);
      }

      if (checkbox.checked) {
        urlSection.style.display = "block";
        buttonTitleSection.style.display = "block";
        buttonContainer.style.display = "block"; // Use block for proper display
        
        // Show the button in the template preview - multiple approaches
        if (reportButtonContainer) {
          // Approach 1: CSS classes
          reportButtonContainer.classList.remove('force-hide-button');
          reportButtonContainer.classList.add('force-show-button');
          
          // Approach 2: Direct styles
          reportButtonContainer.style.display = "table";
          reportButtonContainer.style.visibility = "visible";
          reportButtonContainer.style.opacity = "1";
          
          // Approach 3: Remove data-visible attribute
          reportButtonContainer.removeAttribute('data-visible');
          
          console.log('Button container shown with multiple approaches');
          console.log('After change - display:', reportButtonContainer.style.display);
          console.log('After change - visibility:', reportButtonContainer.style.visibility);
          console.log('After change - classes:', reportButtonContainer.className);
        } else {
          console.error('Report button container not found!');
        }
      } else {
        urlSection.style.display = "none";
        buttonTitleSection.style.display = "none";
        buttonContainer.style.display = "none";
        
        // Hide the button in the template preview
        if (reportButtonContainer) {
          // Approach 1: CSS classes
          reportButtonContainer.classList.remove('force-show-button');
          reportButtonContainer.classList.add('force-hide-button');
          
          // Approach 2: Direct styles
          reportButtonContainer.style.display = "none";
          reportButtonContainer.style.visibility = "hidden";
          reportButtonContainer.style.opacity = "0";
          
          // Approach 3: Set data-visible attribute
          reportButtonContainer.setAttribute('data-visible', 'false');
          
          console.log('Button container hidden with multiple approaches');
        } else {
          console.error('Report button container not found!');
        }
      }
      console.log('=== END DEBUG ===');
    }

    function updateReportButtonText() {
      const buttonText = document.getElementById("button-rr").value || "View Full Report";
      
      // Update the button text in the template preview
      const reportButton = document.getElementById("action-link2");
      if (reportButton) {
        reportButton.innerText = buttonText;
      }
    }

    function toggleAlertButtonSection() {
      const checkbox = document.getElementById("alert-button-open");
      const urlSection = document.getElementById("alertUrlSection");
      const buttonTitleSection = document.getElementById("alertButtonTitleSection");
      const buttonContainer = document.getElementById("alert-button-container");

      if (checkbox.checked) {
        urlSection.style.display = "block";
        buttonTitleSection.style.display = "block";
        buttonContainer.style.display = "table-cell"; // Use table-cell for proper centering
      } else {
        urlSection.style.display = "none";
        buttonTitleSection.style.display = "none";
        buttonContainer.style.display = "none";
      }
    }

    function updateAlertButtonText() {
      const buttonText = document.getElementById("alert-button-title").value;
      document.getElementById("alert-button-preview").innerText = buttonText;
    }

    document.addEventListener("DOMContentLoaded", function () {
      // Set default section to Report (open by default)
      toggleSections("rapport", document.getElementById("rapportBtn"));

      // Set initial description for alert
      const descriptionInput = document.getElementById("alert-description");
      if (descriptionInput && !descriptionInput.value) {
        descriptionInput.value = "The following points of sale have exceeded the defined sales threshold:";
        updateAlertDescription();
      }

      // Initialize the hidden content field with the default template content
      const templateContent = document.getElementById("template-text-alert").innerHTML;
      document.getElementById("hidden-content").value = templateContent;

      // Add event listeners for report KPIs checkboxes
      const reportKpiCheckboxes = [
        'rapportTotalOrdersCheckbox',
        'rapportTotalRevenueCheckbox',
        'rapportAverageSalesCheckbox',
        'rapportTotalQuantitiesCheckbox',
        'rapportTotalClientsCheckbox',
        'rapportTopSellingItemsCheckbox'
      ];

      reportKpiCheckboxes.forEach(checkboxId => {
        const checkbox = document.getElementById(checkboxId);
        if (checkbox) {
          checkbox.addEventListener('change', updateReportKPIs);
        }
      });

      // Add event listeners for report title and content fields
      const rapportTitle = document.getElementById("rapport-title");
      if (rapportTitle) {
        rapportTitle.addEventListener('input', updateReportTemplateContent);
      }

      const rapportContent = document.getElementById("rapport-content");
      if (rapportContent) {
        rapportContent.addEventListener('input', updateReportTemplateContent);
      }

      // Initialize report KPIs preview
      updateReportKPIs();
      
      // Initialize report button (visible by default)
      const reportButton = document.getElementById("action-link2");
      const reportButtonContainer = reportButton ? reportButton.closest('table[data-module="button-dark"]') : null;
      if (reportButtonContainer) {
        reportButtonContainer.classList.remove('force-hide-button');
        reportButtonContainer.classList.add('force-show-button');
        reportButtonContainer.style.display = "table";
        reportButtonContainer.style.visibility = "visible";
        reportButtonContainer.style.opacity = "1";
        reportButtonContainer.removeAttribute('data-visible');
        console.log('Report button initialized as visible by default');
      }
      
      // Initialize button text
      updateReportButtonText();
    });

    document.addEventListener("DOMContentLoaded", function () {
      const alertTypeSelector = document.getElementById("alert-type-selector");
      const hiddenContentInput = document.getElementById("hidden-content");

      const alerteTemplateNode = document.getElementById("alerteTemplate");
      let dynamicContentTargetInPreview = null;

      if (alerteTemplateNode) {
        // Priority to #template-text-alert if it exists (more specific)
        let specificTarget = alerteTemplateNode.querySelector("#template-text-alert");
        if (specificTarget) {
          dynamicContentTargetInPreview = specificTarget;
        } else {
          // Otherwise, fallback to #template-text (parent container)
          dynamicContentTargetInPreview = alerteTemplateNode.querySelector("#template-text");
          if(dynamicContentTargetInPreview) {
            console.warn("Target #template-text-alert not found, using #template-text for preview.");
          } else {
            console.error("No valid target (#template-text-alert or #template-text) found in #alerteTemplate for dynamic preview.");
          }
        }
      }

      if (!dynamicContentTargetInPreview && alertTypeSelector) { // Only a warning if the selector exists, as otherwise the functionality is not expected
        console.warn("Target element for dynamic preview not found in #alerteTemplate. Specific content will not be injected into template preview.");
      }

      // Templates for each alert type
      const expiredStockHTML =
        '<table class="stock-table" style="width:100%; border-collapse: collapse; margin-top: 10px; margin-bottom: 16px; font-family: Helvetica, Arial, sans-serif;">' +
        '<thead><tr>' +
        '<th style="border: 1px solid #d3dce0; padding: 8px; background-color: #f8f9fa; text-align: left; font-weight: bold; font-size: 14px;">Product</th>' +
        '<th style="border: 1px solid #d3dce0; padding: 8px; background-color: #f8f9fa; text-align: left; font-weight: bold; font-size: 14px;">Warehouse</th>' +
        '<th style="border: 1px solid #d3dce0; padding: 8px; background-color: #f8f9fa; text-align: left; font-weight: bold; font-size: 14px;">Quantity</th>' +
        '<th style="border: 1px solid #d3dce0; padding: 8px; background-color: #f8f9fa; text-align: left; font-weight: bold; font-size: 14px;">Threshold</th>' +
        '</tr></thead>' +
        '<tbody><tr>' +
        '<td style="border: 1px solid #d3dce0; padding: 8px; font-size: 14px;"><span class="product-name" style="color: #0366d6; font-weight: bold; font-style: italic;">Sample Product</span></td>' +
        '<td style="border: 1px solid #d3dce0; padding: 8px; font-size: 14px;"><span class="warehouse-name" style="color: #6f42c1; font-weight: bold;">Main Warehouse</span></td>' +
        '<td style="border: 1px solid #d3dce0; padding: 8px; font-size: 14px;"><span class="quantity-alert" style="color: #dc3545; font-weight: bold; font-size: 1.1em;">5</span></td>' +
        '<td style="border: 1px solid #d3dce0; padding: 8px; font-size: 14px;"><span class="threshold-value" style="color: #fd7e14; font-weight: bold;">10</span></td>' +
        '</tr>' +
        '</tbody></table>' +
        '<p class="timestamp" style="font-size: 12px; color: #82899a; margin-top: 8px;">Alert generated on: [Date and Time]</p>';

      const checkInOutHoursHTML =
        '<table class="stock-table" style="width:100%; border-collapse: collapse; margin-top: 10px; margin-bottom: 16px; font-family: Helvetica, Arial, sans-serif;">' +
        '<thead><tr>' +
        '<th style="border: 1px solid #d3dce0; padding: 8px; background-color: #f8f9fa; text-align: left; font-weight: bold; font-size: 14px;">Employee Name</th>' +
        '<th style="border: 1px solid #d3dce0; padding: 8px; background-color: #f8f9fa; text-align: left; font-weight: bold; font-size: 14px;">Client</th>' +
        '<th style="border: 1px solid #d3dce0; padding: 8px; background-color: #f8f9fa; text-align: left; font-weight: bold; font-size: 14px;">Check-in Time</th>' +
        '</tr></thead>' +
        '<tbody><tr>' +
        '<td style="border: 1px solid #d3dce0; padding: 8px; font-size: 14px;">John Smith (example)</td>' +
        '<td style="border: 1px solid #d3dce0; padding: 8px; font-size: 14px;">Alpha Client (example)</td>' +
        '<td style="border: 1px solid #d3dce0; padding: 8px; font-size: 14px;">19:30 (example)</td>' +
        '</tr>' +
        '</tbody></table>' +
        '<p class="timestamp" style="font-size: 12px; color: #82899a; margin-top: 8px;">Report generated on: [Date and Time]</p>';

      const salesThresholdHTML =
        '<table class="stock-table" style="width:100%; border-collapse: collapse; margin-top: 10px; margin-bottom: 16px; font-family: Helvetica, Arial, sans-serif;">' +
        '<thead><tr>' +
        '<th style="border: 1px solid #d3dce0; padding: 8px; background-color: #f8f9fa; text-align: left; font-weight: bold; font-size: 14px;">Point of Sale</th>' +
        '<th style="border: 1px solid #d3dce0; padding: 8px; background-color: #f8f9fa; text-align: left; font-weight: bold; font-size: 14px;">Sales Amount</th>' +
        '<th style="border: 1px solid #d3dce0; padding: 8px; background-color: #f8f9fa; text-align: left; font-weight: bold; font-size: 14px;">Threshold</th>' +
        '<th style="border: 1px solid #d3dce0; padding: 8px; background-color: #f8f9fa; text-align: left; font-weight: bold; font-size: 14px;">Excess</th>' +
        '</tr></thead>' +
        '<tbody><tr>' +
        '<td style="border: 1px solid #d3dce0; padding: 8px; font-size: 14px;"><span style="color: #0366d6; font-weight: bold;">Downtown Store</span></td>' +
        '<td style="border: 1px solid #d3dce0; padding: 8px; font-size: 14px;"><span style="color: #28a745; font-weight: bold;">15,000 TND</span></td>' +
        '<td style="border: 1px solid #d3dce0; padding: 8px; font-size: 14px;"><span style="color: #fd7e14; font-weight: bold;">10,000 TND</span></td>' +
        '<td style="border: 1px solid #d3dce0; padding: 8px; font-size: 14px;"><span style="color: #dc3545; font-weight: bold;">+50%</span></td>' +
        '</tr>' +
        '</tbody></table>' +
        '<p class="timestamp" style="font-size: 12px; color: #82899a; margin-top: 8px;">Alert generated on: [Date and Time]</p>';

      const defaultAlertTextOriginal = '<p style="font-family: Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px; color: #424651;">This is a custom alert message. </p>';

      function updateDynamicContentInPreview(htmlContent) {
        if (dynamicContentTargetInPreview) {
          dynamicContentTargetInPreview.innerHTML = htmlContent;
        }
      }

      function updateHiddenInput(htmlContent) {
        if (hiddenContentInput) {
          hiddenContentInput.value = htmlContent;
        }
      }

      if (alertTypeSelector) {
        alertTypeSelector.addEventListener("change", function() {
          const selectedOption = this.options[this.selectedIndex];
          const selectedSlug = selectedOption.getAttribute("data-slug");
          let templateHTML = "";

          // Update title based on alert type
          let alertTitle = "";
          let alertDescription = "";

          switch(selectedSlug) {
            case "expired-stock":
              templateHTML = expiredStockHTML;
              alertTitle = "Stock Alert";
              alertDescription = "The following products have stock below or equal to the defined threshold:";
              break;
            case "checkin-out-of-hours":
              templateHTML = checkInOutHoursHTML;
              alertTitle = "Check-in Alert";
              alertDescription = "Here is the list of employees with working hours outside check-in:";
              break;
            case "vente-seuil-depasse-pdv":
              templateHTML = salesThresholdHTML;
              alertTitle = "Alerte de seuil de ventes";
              alertDescription = "The following points of sale have exceeded the defined sales threshold:";
              break;
            case "custom":
            default:
              templateHTML = defaultAlertTextOriginal;
              alertTitle = "Custom Alert";
              alertDescription = "This is a custom alert. You can modify this description according to your needs.";
          }

          // Update title in template if title field is empty
          const titleInput = document.getElementById("alerte-title");
          if (titleInput && !titleInput.value) {
            titleInput.value = alertTitle;
            document.getElementById("template-title").innerText = alertTitle;
          }

          // Update description if description field is empty
          const descriptionInput = document.getElementById("alert-description");
          if (descriptionInput && !descriptionInput.value) {
            descriptionInput.value = alertDescription;
            document.getElementById("alert-description-preview").innerText = alertDescription;
          }

          updateHiddenInput(templateHTML);
          updateDynamicContentInPreview(templateHTML);
        });

        // Trigger change event to initialize with selected value
        const event = new Event('change');
        alertTypeSelector.dispatchEvent(event);
      }
    });

    function updateAlertKPIs() {
      const kpiLabels = {
        'total_orders': 'Total Orders',
        'total_revenue': 'Total Revenue',
        'average_sales': 'Average Sales',
        'total_quantities': 'Total Quantities',
        'total_clients': 'Total Clients',
        'top_selling_items': 'Top Selling Items'
      };
      const selectedKPIs = [];
      if (document.getElementById('alertTotalOrdersCheckbox').checked) selectedKPIs.push('total_orders');
      if (document.getElementById('alertRevenueGeneratedCheckbox').checked) selectedKPIs.push('revenue_generated');
      if (document.getElementById('alertNumberOfOrdersCheckbox').checked) selectedKPIs.push('number_of_orders');
      if (document.getElementById('alertAverageBasketSizeCheckbox').checked) selectedKPIs.push('average_basket_size');
      let html = '';
      if (selectedKPIs.length > 0) {
        html += '<h5>KPIs</h5><ul>';
        selectedKPIs.forEach(kpi => {
          html += `<li>${kpiLabels[kpi]}</li>`;
        });
        html += '</ul>';
      }
      document.getElementById('alert-kpis-preview').innerHTML = html;
      updateTemplateText();
    }

    // Call updateAlertKPIs on DOMContentLoaded to initialize
    document.addEventListener('DOMContentLoaded', function () {
      updateAlertKPIs();
    });

    function updateReportKPIs() {
      console.log('updateReportKPIs() called');
      
      const kpiLabels = {
        'total_orders': 'Total Orders',
        'total_revenue': 'Total Revenue',
        'average_sales': 'Average Sales',
        'total_quantities': 'Total Quantities',
        'total_clients': 'Total Clients',
        'top_selling_items': 'Top Selling Items'
      };
      
      const selectedKPIs = [];
      if (document.getElementById('rapportTotalOrdersCheckbox').checked) selectedKPIs.push('total_orders');
      if (document.getElementById('rapportTotalRevenueCheckbox').checked) selectedKPIs.push('total_revenue');
      if (document.getElementById('rapportAverageSalesCheckbox').checked) selectedKPIs.push('average_sales');
      if (document.getElementById('rapportTotalQuantitiesCheckbox').checked) selectedKPIs.push('total_quantities');
      if (document.getElementById('rapportTotalClientsCheckbox').checked) selectedKPIs.push('total_clients');
      if (document.getElementById('rapportTopSellingItemsCheckbox').checked) selectedKPIs.push('top_selling_items');
      
      console.log('Selected KPIs:', selectedKPIs);
      
      let html = '';
      if (selectedKPIs.length > 0) {
        html += '<div style="margin-top: 20px; margin-bottom: 20px;">';
        html += '<h4 style="color: #242b3d; font-family: Helvetica, Arial, sans-serif; margin-bottom: 15px;">Key Performance Indicators</h4>';
        
        // Show cards for regular KPIs first
        const regularKPIs = selectedKPIs.filter(kpi => kpi !== 'top_selling_items');
        if (regularKPIs.length > 0) {
          console.log('Regular KPIs selected - showing cards:', regularKPIs);
          html += '<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 20px;">';
          
          regularKPIs.forEach(kpi => {
            const label = kpiLabels[kpi];
            let value = '';
            let color = '#0366d6';
            
            // Simuler des valeurs pour l'aperçu
            switch(kpi) {
              case 'total_orders':
                value = '1,247';
                color = '#28a745';
                break;
              case 'total_revenue':
                value = '45,230 TND';
                color = '#dc3545';
                break;
              case 'average_sales':
                value = '36.27 TND';
                color = '#fd7e14';
                break;
              case 'total_quantities':
                value = '3,891';
                color = '#6f42c1';
                break;
              case 'total_clients':
                value = '892';
                color = '#17a2b8';
                break;
            }
            
            html += `<div style="background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 8px; padding: 15px; text-align: center;">`;
            html += `<div style="font-size: 24px; font-weight: bold; color: ${color}; margin-bottom: 5px;">${value}</div>`;
            html += `<div style="font-size: 14px; color: #6c757d; font-weight: 500;">${label}</div>`;
            html += `</div>`;
          });
          
          html += '</div>';
        }
        
        // Show table for top selling items after the cards
        if (selectedKPIs.includes('top_selling_items')) {
          console.log('Top selling items selected - showing table');
          html += '<div style="margin-top: 20px;">';
          html += '<h5 style="color: #242b3d; font-family: Helvetica, Arial, sans-serif; margin-bottom: 12px;">TOP 5 PRODUCTS</h5>';
          html += '<table style="width: 100%; border-collapse: collapse; margin-top: 12px; font-family: Helvetica, Arial, sans-serif;">';
          html += '<thead><tr>';
          html += '<th style="border-bottom: 1px solid #d3dce0; padding: 8px; font-weight: bold; text-align: left;">Reference</th>';
          html += '<th style="border-bottom: 1px solid #d3dce0; padding: 8px; font-weight: bold; text-align: left;">Name</th>';
          html += '<th style="border-bottom: 1px solid #d3dce0; padding: 8px; font-weight: bold; text-align: left;">Revenue</th>';
          html += '</tr></thead>';
          html += '<tbody>';
          html += '<tr><td style="padding: 8px; border-bottom: 1px solid #f0f0f0;">BK-0012</td><td style="padding: 8px; border-bottom: 1px solid #f0f0f0;">SARDINE À LA SAUCE TOMATE 125 G</td><td style="padding: 8px; border-bottom: 1px solid #f0f0f0;">16 941.625 DT</td></tr>';
          html += '<tr><td style="padding: 8px; border-bottom: 1px solid #f0f0f0;">BK-0021</td><td style="padding: 8px; border-bottom: 1px solid #f0f0f0;">THON 160GR HUILE VÉGÉTALE</td><td style="padding: 8px; border-bottom: 1px solid #f0f0f0;">16 027.200 DT</td></tr>';
          html += '<tr><td style="padding: 8px; border-bottom: 1px solid #f0f0f0;">AFL30002</td><td style="padding: 8px; border-bottom: 1px solid #f0f0f0;">Brownies Noisette 30</td><td style="padding: 8px; border-bottom: 1px solid #f0f0f0;">12 103.144 DT</td></tr>';
          html += '<tr><td style="padding: 8px; border-bottom: 1px solid #f0f0f0;">AFL30001</td><td style="padding: 8px; border-bottom: 1px solid #f0f0f0;">Brownies Pépites 30</td><td style="padding: 8px; border-bottom: 1px solid #f0f0f0;">7 798.695 DT</td></tr>';
          html += '<tr><td style="padding: 8px;">BK-0020</td><td style="padding: 8px;">THON 160GR HUILE D\'OLIVE</td><td style="padding: 8px;">3 888 DT</td></tr>';
          html += '</tbody></table>';
          html += '</div>';
        }
        
        html += '</div>';
      }
      
      console.log('Generated HTML:', html);
      
      // Mettre à jour l'aperçu dans le template
      const kpisPreviewElement = document.getElementById('rapport-kpis-preview');
      console.log('KPIs preview element found:', kpisPreviewElement);
      if (kpisPreviewElement) {
        kpisPreviewElement.innerHTML = html;
        console.log('HTML updated in preview element');
      }
      
      // Mettre à jour le contenu caché pour le formulaire
      updateReportTemplateContent();
    }

    function updateReportTemplateContent() {
      const title = document.getElementById("rapport-title").value || "Weekly Business Report";
      const content = document.getElementById("rapport-content").value || "Here is your weekly business report with key performance indicators:";
      const kpisContent = document.getElementById('rapport-kpis-preview').innerHTML;
      
      // Mettre à jour l'aperçu du titre dans le template
      const titlePreview = document.getElementById("report-title2");
      if (titlePreview) {
        titlePreview.innerText = title;
      }
      
      // Mettre à jour l'aperçu du contenu dans le template
      const contentPreview = document.getElementById("rapport-template-text");
      if (contentPreview) {
        contentPreview.innerHTML = content;
      }
      
      // Mettre à jour les champs cachés pour le formulaire
      const hiddenTitle = document.getElementById("hidden-title");
      if (hiddenTitle) {
        hiddenTitle.value = title;
      }
      
      const fullContent = content + kpisContent;
      const hiddenContent = document.getElementById("hidden-content");
      if (hiddenContent) {
        hiddenContent.value = fullContent;
      }
    }

    // Test function to force show button
    function testShowButton() {
      console.log('=== TEST SHOW BUTTON ===');
      const reportButton = document.getElementById("action-link2");
      const reportButtonContainer = reportButton ? reportButton.closest('table[data-module="button-dark"]') : null;
      
      console.log('Test - reportButton found:', !!reportButton);
      console.log('Test - reportButtonContainer found:', !!reportButtonContainer);
      
      if (reportButtonContainer) {
        reportButtonContainer.style.display = "table";
        reportButtonContainer.style.visibility = "visible";
        reportButtonContainer.style.opacity = "1";
        reportButtonContainer.removeAttribute('data-visible');
        reportButtonContainer.classList.remove('force-hide-button');
        reportButtonContainer.classList.add('force-show-button');
        console.log('Test - Button should now be visible');
      } else {
        console.error('Test - Button container not found!');
      }
    }
  </script>
@endsection
