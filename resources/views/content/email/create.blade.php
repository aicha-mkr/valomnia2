@extends('layouts.contentNavbarLayout')

@section('title', 'Créer un Nouveau Template')

@section('content')
<div class="row">
  <div class="col-md-12">
      <div class="nav-align-top">
          <ul class="nav nav-pills flex-column flex-md-row mb-6">
              <li class="nav-item">
                  <a class="nav-link" href="{{ url('organisation/email/liste') }}">
                      <button type="button" class="btn btn-primary">
                <span class="tf-icons bx bx-pie-chart-alt bx-18px me-2"></span>Return
              </button>
                  </a>
              </li>
          </ul>
      </div>
  </div>
</div>
<div class="text-center mb-4">
  <button id="rapportBtn" class="btn rounded-pill btn-primary" onclick="toggleSections('rapport', this)">Rapport</button>
  <button id="alerteBtn" class="btn rounded-pill btn-label-primary" onclick="toggleSections('alerte', this)">Alerte</button>
</div>

    <div class="row">
      <!-- Rapport Section -->
      <div id="rapportSection" class="col-md-4 section" style="display: none;"> <!-- 30% width -->
          <div class="card mb-6">
              <h5 class="card-header">Formulaire de Rapport</h5>
              <div class="card-body">
                <form action="{{ route('email.store') }}" method="POST">
                  @csrf
                      <div class="mb-4">
                        <label for="rapport-email-subject" class="form-label">Sujet</label>
                        <input type="text" class="form-control" id="rapport-email-subject" name="email_subject" placeholder="Sujet de l'email" required />
                    </div>
                    <div class="mb-4">
                      <label for="alerte-title" class="form-label">Titre</label>
                      <input type="text" class="form-control" id="rapport-title" name="title" placeholder="Titre de l'alerte" required oninput="updateReportTitle()" />
                  </div>

                  <div class="mb-4">
                    <h5>Configurer le Texte du Rapport</h5>
                    <textarea class="form-control" id="rapport-content" name="rapport_content" rows="6"
                              placeholder="Entrez le contenu du rapport ici..." oninput="updateRapportContent()"></textarea>
                </div>


                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="report-open" onclick="toggleReportSection()" />
                  <label class="form-check-label" for="report-open">Afficher Bouton de Rapport</label>
              </div>

              <div id="urlSection" class="mb-4" style="display: none;">
                  <label for="report-url" class="form-label">URL Spécifique</label>
                  <input type="url" class="form-control" id="report-url" name="report_url" placeholder="URL spécifique" oninput="updateReportButtonUrl()">              </div>

              <div class="mb-4" id="buttonTitleSection" style="display: none;">
                  <label for="button-title" class="form-label">Titre du Bouton</label>
                  <input type="text" class="form-control" id="button-title" placeholder="Entrez le titre du bouton" oninput="updateReportButtonText()" />
              </div>




              <h5>Sélectionner les KPI</h5>
              <div>
                  <div class="form-check">
                      <input class="form-check-input" type="checkbox" value="total_revenue" id="totalRevenueCheckbox" onchange="toggleKPI('kpi1', this.checked)" checked />
                      <label class="form-check-label" for="totalRevenueCheckbox">Total Revenue</label>
                  </div>
                  <div class="form-check">
                      <input class="form-check-input" type="checkbox" value="total_orders" id="totalOrdersCheckbox" onchange="toggleKPI('kpi2', this.checked)" checked />
                      <label class="form-check-label" for="totalOrdersCheckbox">Total Orders</label>
                  </div>
                  <div class="form-check">
                      <input class="form-check-input" type="checkbox" value="total_employees" id="totalEmployeesCheckbox" onchange="toggleKPI('kpi3', this.checked)" checked />
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









        <!-- Email Template for Rapport -->
        <div id="rapportTemplate" class="col-md-8" style="display: none;"> <!-- 70% width -->
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
    </style>
             </head>
         <body>
    <table
      data-module="preview-text"
      data-visible="false"
      width="100%"
      cellspacing="0"
      cellpadding="0"
      border="0"
      role="presentation">
      <tbody>
        <tr>
          <td
            class="o_hide"
            align="center"
            style="
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
    <table
      data-module="header-primary0"
      width="100%"
      cellspacing="0"
      cellpadding="0"
      border="0"
      role="presentation">
      <tbody>
        <tr>
          <td
            class="o_bg-light o_px-xs o_pt-lg o_xs-pt-xs"
            align="center"
            data-bgcolor="Bg Light"
            style="background-color: #dbe5ea; padding-left: 8px; padding-right: 8px; padding-top: 32px">
            <!--[if mso]><table width="632" cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td><![endif]-->
            <table
              class="o_block"
              width="100%"
              cellspacing="0"
              cellpadding="0"
              border="0"
              role="presentation"
              style="max-width: 632px; margin: 0 auto">
              <tbody>
                <tr>
                  <td
                    class="o_bg-primary o_px o_py-md o_br-t o_sans o_text"
                    align="center"
                    data-bgcolor="Bg Primary"
                    data-size="Text Default"
                    data-min="12"
                    data-max="20"
                    style="
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
                      <a
                        class="o_text-white"
                        data-color="White"
                        style="text-decoration: none; outline: none; color: #ffffff"
                        ><img
                          src="https://www.valomnia.com/wp-content/themes/jupiter/images/jupiter-logo.png"
                          width="136"
                          height="36"
                          alt="SimpleApp"
                          style="
                            max-width: 136px;
                            -ms-interpolation-mode: bicubic;
                            vertical-align: middle;
                            border: 0;
                            line-height: 100%;
                            height: auto;
                            outline: none;
                            text-decoration: none;
                          "
                          data-crop="false"
                      /></a>
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
    <table
      data-module="hero-icon-lines"
      data-visible="false"
      data-thumb="http://www.stampready.net/dashboard/editor/user_uploads/zip_uploads/2018/11/19/pcVNfzKjZ3goPqkxr2hYT0ws/service_canceled/thumbnails/hero-icon-lines.png"
      width="100%"
      cellspacing="0"
      cellpadding="0"
      border="0"
      role="presentation">
      <tbody>
        <tr>
          <td
            class="o_bg-light o_px-xs"
            align="center"
            data-bgcolor="Bg Light"
            style="background-color: #dbe5ea; padding-left: 8px; padding-right: 8px">
            <!--[if mso]><table width="632" cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td><![endif]-->
            <table
              class="o_block"
              width="100%"
              cellspacing="0"
              cellpadding="0"
              border="0"
              role="presentation"
              style="max-width: 632px; margin: 0 auto">
              <tbody>
                <tr>
                  <td
                    class="o_bg-ultra_light o_px-md o_py-xl o_xs-py-md o_sans o_text-md o_text-light"
                    align="center"
                    data-bgcolor="Bg Ultra Light"
                    data-color="Light"
                    data-size="Text MD"
                    data-min="15"
                    data-max="23"
                    style="
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
                    <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                      <tbody>
                        <tr>
                          <td
                            class="o_bb-primary"
                            height="40"
                            width="32"
                            data-border-bottom-color="Border Primary 2"
                            style="border-bottom: 1px solid #126de5">
                            &nbsp;
                          </td>
                          <td
                            rowspan="2"
                            class="o_sans o_text o_text-secondary o_px o_py"
                            align="center"
                            data-color="Secondary"
                            data-size="Text Default"
                            data-min="12"
                            data-max="20"
                            style="
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
                            <img
                              src="http://www.stampready.net/dashboard/editor/user_uploads/zip_uploads/2018/11/19/pcVNfzKjZ3goPqkxr2hYT0ws/service_canceled/images/check-48-primary.png"
                              width="48"
                              height="48"
                              alt=""
                              style="
                                max-width: 48px;
                                -ms-interpolation-mode: bicubic;
                                vertical-align: middle;
                                border: 0;
                                line-height: 100%;
                                height: auto;
                                outline: none;
                                text-decoration: none;
                              "
                              data-crop="false" />
                          </td>
                          <td
                            class="o_bb-primary"
                            height="40"
                            width="32"
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
                          <td style="font-size: 8px; line-height: 8px; height: 8px">&nbsp;</td>
                          <td style="font-size: 8px; line-height: 8px; height: 8px">&nbsp;</td>
                        </tr>
                      </tbody>
                    </table>

                    <h2 id="report-title" class="o_heading o_text-dark o_mb-xxs" data-color="Dark" data-size="Heading 2" data-min="20" data-max="40"
                    style="
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
    <table
      data-module="spacer0"
      data-thumb="http://www.stampready.net/dashboard/editor/user_uploads/zip_uploads/2018/11/19/pcVNfzKjZ3goPqkxr2hYT0ws/service_canceled/thumbnails/spacer.png"
      width="100%"
      cellspacing="0"
      cellpadding="0"
      border="0"
      role="presentation">
      <tbody>
        <tr>
          <td
            class="o_bg-light o_px-xs"
            align="center"
            data-bgcolor="Bg Light"
            style="background-color: #dbe5ea; padding-left: 8px; padding-right: 8px">
            <!--[if mso]><table width="632" cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td><![endif]-->
            <table
              class="o_block"
              width="100%"
              cellspacing="0"
              cellpadding="0"
              border="0"
              role="presentation"
              style="max-width: 632px; margin: 0 auto">
              <tbody>
                <tr>
                  <td
                    class="o_bg-white"
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
    <table
    data-module="spacer0"
    data-thumb="http://www.stampready.net/dashboard/editor/user_uploads/zip_uploads/2018/11/19/pcVNfzKjZ3goPqkxr2hYT0ws/service_canceled/thumbnails/spacer.png"
    width="100%"
    cellspacing="0"
    cellpadding="0"
    border="0"
    role="presentation">
    <tbody>
      <tr>
        <td
          class="o_bg-light o_px-xs"
          align="center"
          data-bgcolor="Bg Light"
          style="background-color: #dbe5ea; padding-left: 8px; padding-right: 8px">
          <!--[if mso]><table width="632" cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td><![endif]-->
          <table
            class="o_block"
            width="100%"
            cellspacing="0"
            cellpadding="0"
            border="0"
            role="presentation"
            style="max-width: 632px; margin: 0 auto">
            <tbody>
              <tr>
                <td
                  class="o_bg-white"
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

    <table
    data-module="content-lg"
    data-visible="false"
    data-thumb="http://www.stampready.net/dashboard/editor/user_uploads/zip_uploads/2018/11/19/pcVNfzKjZ3goPqkxr2hYT0ws/service_canceled/thumbnails/content-lg.png"
    width="100%"
    cellspacing="0"
    cellpadding="0"
    border="0"
    role="presentation">
    <tbody>
      <tr>
        <td
          class="o_bg-light o_px-xs"
          align="center"
          data-bgcolor="Bg Light"
          style="background-color: #dbe5ea; padding-left: 8px; padding-right: 8px">
          <!--[if mso]><table width="632" cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td><![endif]-->
          <table
            class="o_block"
            width="100%"
            cellspacing="0"
            cellpadding="0"
            border="0"
            role="presentation"
            style="max-width: 632px; margin: 0 auto">
            <tbody>
              <tr>
                <td
                  class="o_bg-white o_px-md o_py o_sans o_text o_text-secondary"
                  align="center"
                  data-bgcolor="Bg White"
                  data-color="Secondary"
                  data-size="Text Default"
                  data-min="12"
                  data-max="20"
                  style="
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
                    padding-bottom: 16px;
                  ">

                  <p id="rapport-template-text" style="margin-top: 0px; margin-bottom: 0px">
                    Callously piranha however moronic selfless more because spitefully dear some far forward where
                    mounted underneath however feeling out less alas.
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

  <table
  data-module="spacer0"
  data-thumb="http://www.stampready.net/dashboard/editor/user_uploads/zip_uploads/2018/11/19/pcVNfzKjZ3goPqkxr2hYT0ws/service_canceled/thumbnails/spacer.png"
  width="100%"
  cellspacing="0"
  cellpadding="0"
  border="0"
  role="presentation">
  <tbody>
    <tr>
      <td
        class="o_bg-light o_px-xs"
        align="center"
        data-bgcolor="Bg Light"
        style="background-color: #dbe5ea; padding-left: 8px; padding-right: 8px">
        <!--[if mso]><table width="632" cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td><![endif]-->
        <table
          class="o_block"
          width="100%"
          cellspacing="0"
          cellpadding="0"
          border="0"
          role="presentation"
          style="max-width: 632px; margin: 0 auto">
          <tbody>
            <tr>
              <td
                class="o_bg-white"
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
<table

data-module="button-dark"
data-visible="false"
data-thumb="http://www.stampready.net/dashboard/editor/user_uploads/zip_uploads/2018/11/19/pcVNfzKjZ3goPqkxr2hYT0ws/service_canceled/thumbnails/button-dark.png"
width="100%"
cellspacing="0"
cellpadding="0"
border="0"
role="presentation">
<tbody>
  <tr>
    <td
    id="reportTemplate"
      class="o_bg-light o_px-xs"
      align="center"
      data-bgcolor="Bg Light"
      style="background-color: #dbe5ea; padding-left: 8px; padding-right: 8px">
      <!--[if mso]><table width="632" cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td><![endif]-->
      <table

        class="o_block"
        width="100%"
        cellspacing="0"
        cellpadding="0"
        border="0"
        role="presentation"
        style="max-width: 632px; margin: 0 auto">
        <tbody>
          <tr>
            <td
              class="o_bg-white o_px-md o_py-xs"
              align="center"
              data-bgcolor="Bg White"
              style="
                background-color: #ffffff;
                padding-left: 24px;
                padding-right: 24px;
                padding-top: 8px;
                padding-bottom: 8px;
              ">
              <table align="center" cellspacing="0" cellpadding="0" border="0" role="presentation">
                <tbody>
                  <tr>
                    <td
                      width="300"
                      class="o_btn o_bg-dark o_br o_heading o_text"
                      align="center"
                      data-bgcolor="Bg Dark"
                      data-size="Text Default"
                      data-min="12"
                      data-max="20"
                      style="
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
                      <a
                       id="action-link"
                        class="o_text-white"
                        href="https://example.com/"
                        data-color="White"
                        style="
                          text-decoration: none;
                          outline: none;
                          color: #ffffff;
                          display: block;
                          padding: 12px 24px;
                          mso-text-raise: 3px;
                        "
                        >Contact Support</a
                      >
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

  <table
      data-module="spacer0"
      data-thumb="http://www.stampready.net/dashboard/editor/user_uploads/zip_uploads/2018/11/19/pcVNfzKjZ3goPqkxr2hYT0ws/service_canceled/thumbnails/spacer.png"
      width="100%"
      cellspacing="0"
      cellpadding="0"
      border="0"
      role="presentation">
      <tbody>
        <tr>
          <td
            class="o_bg-light o_px-xs"
            align="center"
            data-bgcolor="Bg Light"
            style="background-color: #dbe5ea; padding-left: 8px; padding-right: 8px">
            <!--[if mso]><table width="632" cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td><![endif]-->
            <table
              class="o_block"
              width="100%"
              cellspacing="0"
              cellpadding="0"
              border="0"
              role="presentation"
              style="max-width: 632px; margin: 0 auto">
              <tbody>
                <tr>
                  <td
                    class="o_bg-white"
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
    <table
    data-module="spacer0"
    data-thumb="http://www.stampready.net/dashboard/editor/user_uploads/zip_uploads/2018/11/19/pcVNfzKjZ3goPqkxr2hYT0ws/service_canceled/thumbnails/spacer.png"
    width="100%"
    cellspacing="0"
    cellpadding="0"
    border="0"
    role="presentation">
    <tbody>
      <tr>
        <td
          class="o_bg-light o_px-xs"
          align="center"
          data-bgcolor="Bg Light"
          style="background-color: #dbe5ea; padding-left: 8px; padding-right: 8px">
          <!--[if mso]><table width="632" cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td><![endif]-->
          <table
            class="o_block"
            width="100%"
            cellspacing="0"
            cellpadding="0"
            border="0"
            role="presentation"
            style="max-width: 632px; margin: 0 auto">
            <tbody>
              <tr>
                <td
                  class="o_bg-white"
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
  <table
  data-module="spacer0"
  data-thumb="http://www.stampready.net/dashboard/editor/user_uploads/zip_uploads/2018/11/19/pcVNfzKjZ3goPqkxr2hYT0ws/service_canceled/thumbnails/spacer.png"
  width="100%"
  cellspacing="0"
  cellpadding="0"
  border="0"
  role="presentation">
  <tbody>
    <tr>
      <td
        class="o_bg-light o_px-xs"
        align="center"
        data-bgcolor="Bg Light"
        style="background-color: #dbe5ea; padding-left: 8px; padding-right: 8px">
        <!--[if mso]><table width="632" cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td><![endif]-->
        <table
          class="o_block"
          width="100%"
          cellspacing="0"
          cellpadding="0"
          border="0"
          role="presentation"
          style="max-width: 632px; margin: 0 auto">
          <tbody>
            <tr>
              <td
                class="o_bg-white"
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

<table

  data-module="pricing-3cols"
      data-visible="false"
      data-thumb="http://www.stampready.net/dashboard/editor/user_uploads/zip_uploads/2018/11/19/pcVNfzKjZ3goPqkxr2hYT0ws/service_canceled/thumbnails/pricing-3cols.png"
      width="100%"
      cellspacing="0"
      cellpadding="0"
      border="0"
      role="presentation">
      <tbody>
        <tr>

          <td
            class="o_bg-light o_px-xs"
            align="center"
            data-bgcolor="Bg Light"
            style="background-color: #dbe5ea; padding-left: 8px; padding-right: 8px">
            <!--[if mso]><table width="632" cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td><![endif]-->
            <table
              class="o_block"
              width="100%"
              cellspacing="0"
              cellpadding="0"
              border="0"
              role="presentation"
              style="max-width: 632px; margin: 0 auto">

              <tbody>
                <tr>
                  <td
                    id="kpi-list"
                    class="o_re o_bg-white o_px o_pb-md"
                    align="center"
                    data-bgcolor="Bg White"
                    style="
                      font-size: 0;
                      vertical-align: top;
                      background-color: #ffffff;
                      padding-left: 16px;
                      padding-right: 16px;
                      padding-bottom: 24px;
                    ">
                    <h4
                    class="o_heading o_text-dark o_mb-xs"
                    data-color="Dark"
                    data-size="Heading 4"
                    data-min="10"
                    data-max="26"
                    style="
                      font-family: Helvetica, Arial, sans-serif;
                      font-weight: bold;
                      margin-top: 0px;
                      margin-bottom: 8px;
                      color: #242b3d;
                      font-size: 18px;
                      line-height: 23px;
                    ">
                    Your kpi Details
                  </h4>
                    <!--[if mso]><table cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td width="200" align="center" valign="top" style="padding: 0px 8px;"><![endif]-->
                    <div
                      id="kpi1"
                      class="o_col o_col-2 o_col-full"
                      style="display: inline-block; vertical-align: top; width: 100%; max-width: 180px">
                      <div style="font-size: 24px; line-height: 24px; height: 24px">&nbsp;</div>
                      <div class="o_px-xs" style="padding-left: 8px; padding-right: 8px">
                        <table width="100%" role="presentation" cellspacing="0" cellpadding="0" border="0">
                          <tbody>
                            <tr>
                              <td
                                class="o_bg-ultra_light o_br o_px o_py-md o_sans o_text o_text-secondary"
                                align="center"
                                data-bgcolor="Bg Ultra Light"
                                data-size="Text Default"
                                data-min="12"
                                data-max="20"
                                data-color="Secondary"
                                style="
                                  font-family: Helvetica, Arial, sans-serif;
                                  margin-top: 0px;
                                  margin-bottom: 0px;
                                  font-size: 16px;
                                  line-height: 24px;
                                  background-color: #ebf5fa;
                                  color: #424651;
                                  border-radius: 4px;
                                  padding-left: 16px;
                                  padding-right: 16px;
                                  padding-top: 24px;
                                  padding-bottom: 24px;
                                ">
                                <p class="o_mb-xxs" style="margin-top: 0px; margin-bottom: 4px">
                                  <strong>Basic</strong>
                                </p>
                                <h2
                                  id="kpiValue1"
                                  class="o_heading o_text-primary"
                                  data-size="Heading 2"
                                  data-min="20"
                                  data-max="40"
                                  data-color="Primary"
                                  style="
                                    font-family: Helvetica, Arial, sans-serif;
                                    font-weight: bold;
                                    margin-top: 0px;
                                    margin-bottom: 0px;
                                    color: #126de5;
                                    font-size: 30px;
                                    line-height: 39px;
                                  ">
                                  $46
                                </h2>

                              </td>
                            </tr>
                            <tr>
                              <td
                                class="o_py o_sans o_text-xs o_text-secondary"
                                align="center"
                                data-color="Secondary"
                                data-size="Text XS"
                                data-min="10"
                                data-max="18"
                                style="
                                  font-family: Helvetica, Arial, sans-serif;
                                  margin-top: 0px;
                                  margin-bottom: 0px;
                                  font-size: 14px;
                                  line-height: 21px;
                                  color: #424651;
                                  padding-top: 16px;
                                  padding-bottom: 16px;
                                ">

                                <p class="o_mb-md" style="margin-top: 0px; margin-bottom: 24px"><span>&nbsp; </span></p>
                                <table align="center" cellspacing="0" cellpadding="0" border="0" role="presentation">
                                  <tbody>
                                    <tr>


                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <!--[if mso]></td><td width="200" align="center" valign="top" style="padding: 0px 8px;"><![endif]-->
                    <div
                      id="kpi2"
                      class="o_col o_col-2 o_col-full"
                      style="display: inline-block; vertical-align: top; width: 100%; max-width: 180px">
                      <div style="font-size: 24px; line-height: 24px; height: 24px">&nbsp;</div>
                      <div class="o_px-xs" style="padding-left: 8px; padding-right: 8px">
                        <table width="100%" role="presentation" cellspacing="0" cellpadding="0" border="0">
                          <tbody>
                            <tr>
                              <td
                                class="o_bg-ultra_light o_br o_px o_py-md o_sans o_text o_text-secondary"
                                align="center"
                                data-bgcolor="Bg Ultra Light"
                                data-size="Text Default"
                                data-min="12"
                                data-max="20"
                                data-color="Secondary"
                                style="
                                  font-family: Helvetica, Arial, sans-serif;
                                  margin-top: 0px;
                                  margin-bottom: 0px;
                                  font-size: 16px;
                                  line-height: 24px;
                                  background-color: #ebf5fa;
                                  color: #424651;
                                  border-radius: 4px;
                                  padding-left: 16px;
                                  padding-right: 16px;
                                  padding-top: 24px;
                                  padding-bottom: 24px;
                                ">
                                <p class="o_mb-xxs" style="margin-top: 0px; margin-bottom: 4px">
                                  <strong>Premium</strong>
                                </p>
                                <h2
                                id="kpiValue2"
                                  class="o_heading o_text-primary"
                                  data-size="Heading 2"
                                  data-min="20"
                                  data-max="40"
                                  data-color="Primary"
                                  style="
                                    font-family: Helvetica, Arial, sans-serif;
                                    font-weight: bold;
                                    margin-top: 0px;
                                    margin-bottom: 0px;
                                    color: #126de5;
                                    font-size: 30px;
                                    line-height: 39px;
                                  ">
                                  $76
                                </h2>

                              </td>
                            </tr>
                            <tr>
                              <td
                                class="o_py o_sans o_text-xs o_text-secondary"
                                align="center"
                                data-color="Secondary"
                                data-size="Text XS"
                                data-min="10"
                                data-max="18"
                                style="
                                  font-family: Helvetica, Arial, sans-serif;
                                  margin-top: 0px;
                                  margin-bottom: 0px;
                                  font-size: 14px;
                                  line-height: 21px;
                                  color: #424651;
                                  padding-top: 16px;
                                  padding-bottom: 16px;
                                ">


                                <table align="center" cellspacing="0" cellpadding="0" border="0" role="presentation">
                                  <tbody>
                                    <tr>
                                      <td
                                        class="o_btn o_bg-dark o_br o_heading o_text-xs"
                                        align="center"
                                        data-bgcolor="Bg Dark"
                                        data-size="Text XS"
                                        data-min="10"
                                        data-max="18"
                                        style="
                                          font-family: Helvetica, Arial, sans-serif;
                                          font-weight: bold;
                                          margin-top: 0px;
                                          margin-bottom: 0px;
                                          font-size: 14px;
                                          line-height: 21px;
                                          mso-padding-alt: 12px 24px;
                                          background-color: #242b3d;
                                          border-radius: 4px;
                                        ">

                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <!--[if mso]></td><td width="200" align="center" valign="top" style="padding: 0px 8px;"><![endif]-->
                    <div
                     id="kpi3"
                      class="o_col o_col-2 o_col-full"
                      style="display: inline-block; vertical-align: top; width: 100%; max-width: 200px">
                      <div style="font-size: 24px; line-height: 24px; height: 24px">&nbsp;</div>
                      <div class="o_px-xs" style="padding-left: 8px; padding-right: 8px">
                        <table width="100%" role="presentation" cellspacing="0" cellpadding="0" border="0">
                          <tbody>
                            <tr>
                              <td
                                class="o_bg-ultra_light o_br o_px o_py-md o_sans o_text o_text-secondary"
                                align="center"
                                data-bgcolor="Bg Ultra Light"
                                data-size="Text Default"
                                data-min="12"
                                data-max="20"
                                data-color="Secondary"
                                style="
                                  font-family: Helvetica, Arial, sans-serif;
                                  margin-top: 0px;
                                  margin-bottom: 0px;
                                  font-size: 16px;
                                  line-height: 24px;
                                  background-color: #ebf5fa;
                                  color: #424651;
                                  border-radius: 4px;
                                  padding-left: 16px;
                                  padding-right: 16px;
                                  padding-top: 24px;
                                  padding-bottom: 24px;
                                ">
                                <p class="o_mb-xxs" style="margin-top: 0px; margin-bottom: 4px">
                                  <strong>Enterprise</strong>
                                </p>
                                <h2
                                id="kpiValue3"
                                  class="o_heading o_text-primary"
                                  data-size="Heading 2"
                                  data-min="20"
                                  data-max="40"
                                  data-color="Primary"
                                  style="
                                    font-family: Helvetica, Arial, sans-serif;
                                    font-weight: bold;
                                    margin-top: 0px;
                                    margin-bottom: 0px;
                                    color: #126de5;
                                    font-size: 30px;
                                    line-height: 39px;
                                  ">
                                  $156
                                </h2>

                              </td>
                            </tr>
                            <tr>
                              <td
                                class="o_py o_sans o_text-xs o_text-secondary"
                                align="center"
                                data-color="Secondary"
                                data-size="Text XS"
                                data-min="10"
                                data-max="18"
                                style="
                                  font-family: Helvetica, Arial, sans-serif;
                                  margin-top: 0px;
                                  margin-bottom: 0px;
                                  font-size: 14px;
                                  line-height: 21px;
                                  color: #424651;
                                  padding-top: 16px;
                                  padding-bottom: 16px;
                                ">

                                <table align="center" cellspacing="0" cellpadding="0" border="0" role="presentation">
                                  <tbody>
                                    <tr>
                                      <td
                                        class="o_btn o_bg-dark o_br o_heading o_text-xs"
                                        align="center"
                                        data-bgcolor="Bg Dark"
                                        data-size="Text XS"
                                        data-min="10"
                                        data-max="18"
                                        style="
                                          font-family: Helvetica, Arial, sans-serif;
                                          font-weight: bold;
                                          margin-top: 0px;
                                          margin-bottom: 0px;
                                          font-size: 14px;
                                          line-height: 21px;
                                          mso-padding-alt: 12px 24px;
                                          background-color: #242b3d;
                                          border-radius: 4px;
                                        ">


                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
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


    <table data-module="order-summary0" data-thumb="http://www.stampready.net/dashboard/editor/user_uploads/zip_uploads/2020/03/13/0D6ItbpLZUSmhj3YORzyfKEg/account_addons/thumbnails/order-summary.png" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
      <tbody>
        <tr>
          <td class="o_bg-light o_px-xs" align="center" data-bgcolor="Bg Light" style="background-color: #dbe5ea;padding-left: 8px;padding-right: 8px;">
            <!--[if mso]><table width="632" cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td><![endif]-->
            <table class="o_block" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" style="max-width: 632px;margin: 0 auto;">
              <tbody>
                <tr>
                  <td class="o_bg-white o_sans o_text-xs o_text-light o_px-md o_pt-xs" align="center" data-bgcolor="Bg White" data-color="Light" data-size="Text XS" data-min="10" data-max="18" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 14px;line-height: 21px;background-color: #ffffff;color: #82899a;padding-left: 24px;padding-right: 24px;padding-top: 8px;">
                    <p style="margin-top: 0px;margin-bottom: 0px;">TOP 5 PRODUCTS</p>
                    <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
                      <tbody>
                        <tr>
                          <td class="o_re o_bb-light" style="font-size: 8px;line-height: 8px;height: 8px;vertical-align: top;border-bottom: 1px solid #d3dce0;" data-border-bottom-color="Border Light">&nbsp; </td>
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




    <table data-module="addon-row0" data-thumb="http://www.stampready.net/dashboard/editor/user_uploads/zip_uploads/2020/03/13/0D6ItbpLZUSmhj3YORzyfKEg/account_addons/thumbnails/addon-row.png" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
      <tbody>
        <tr>
          <td class="o_bg-light o_px-xs" align="center" data-bgcolor="Bg Light" style="background-color: #dbe5ea;padding-left: 8px;padding-right: 8px;">
            <!--[if mso]><table width="632" cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td><![endif]-->
            <table class="o_block" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" style="max-width: 632px;margin: 0 auto;">
              <tbody>
                <tr>
                  <td class="o_re o_bg-white o_px o_pt" align="center" data-bgcolor="Bg White" style="font-size: 0;vertical-align: top;background-color: #ffffff;padding-left: 16px;padding-right: 16px;padding-top: 16px;">
                    <!--[if mso]><table cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td width="100" align="center" valign="top" style="padding: 0px 8px;"><![endif]-->
                    <div class="o_col o_col-1 o_col-full" style="display: inline-block;vertical-align: top;width: 100%;max-width: 100px;">
                      <div class="o_px-xs o_sans o_text o_center" data-size="Text Default" data-min="12" data-max="20" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;text-align: center;padding-left: 8px;padding-right: 8px;">
                        <p style="margin-top: 0px;margin-bottom: 0px;"><a class="o_text-primary" href="https://example.com/" data-color="Primary" style="text-decoration: none;outline: none;color: #126de5;"><img src="http://www.stampready.net/dashboard/editor/user_uploads/zip_uploads/2020/03/13/0D6ItbpLZUSmhj3YORzyfKEg/account_addons/images/thumb_84_4.jpg" width="84" height="84" alt="" style="max-width: 84px;-ms-interpolation-mode: bicubic;vertical-align: middle;border: 0;line-height: 100%;height: auto;outline: none;text-decoration: none;" data-crop="false"></a></p>
                      </div>
                    </div>
                    <!--[if mso]></td><td width="300" align="left" valign="top" style="padding: 0px 8px;"><![endif]-->
                    <div class="o_col o_col-3 o_col-full" style="display: inline-block;vertical-align: top;width: 100%;max-width: 300px;">
                      <div style="font-size: 10px; line-height: 10px; height: 10px;">&nbsp; </div>
                      <div class="o_px-xs o_sans o_text-xs o_text-secondary o_left o_xs-center" data-color="Secondary" data-size="Text XS" data-min="10" data-max="18" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 14px;line-height: 21px;color: #424651;text-align: left;padding-left: 8px;padding-right: 8px;">
                        <p class="o_text o_text-dark" data-color="Dark" data-size="Text Default" data-min="12" data-max="20" style="font-size: 16px;line-height: 24px;color: #242b3d;margin-top: 0px;margin-bottom: 0px;"><strong>SuperChat Add-On</strong></p>
                        <p class="o_mb-xxs" style="margin-top: 0px;margin-bottom: 4px;">Less dear heroically much indignantly</p>
                        <p class="o_text-primary" data-color="Primary" style="color: #126de5;margin-top: 0px;margin-bottom: 0px;"><strong>$9.00</strong></p>
                      </div>
                    </div>
                    <!--[if mso]></td><td width="200" align="right" valign="top" style="padding: 0px 8px;"><![endif]-->
                    <div class="o_col o_col-2 o_col-full" style="display: inline-block;vertical-align: top;width: 100%;max-width: 200px;">
                      <div style="font-size: 10px; line-height: 10px; height: 10px;">&nbsp; </div>
                      <div class="o_px-xs o_sans o_text-xxs o_text-light o_right o_xs-center" data-color="Light" data-size="Text XXS" data-min="8" data-max="16" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 12px;line-height: 19px;color: #82899a;text-align: right;padding-left: 8px;padding-right: 8px;">
                        <table class="o_right o_xs-center" cellspacing="0" cellpadding="0" border="0" role="presentation" style="text-align: right;margin-left: auto;margin-right: 0;">
                          <tbody>
                            <tr>
                              <td class="o_btn-xs o_bg-dark o_br o_heading o_text-xs" align="center" data-bgcolor="Bg Dark" data-size="Text XS" data-min="10" data-max="18" style="font-family: Helvetica, Arial, sans-serif;font-weight: bold;margin-top: 0px;margin-bottom: 0px;font-size: 14px;line-height: 21px;mso-padding-alt: 7px 16px;background-color: #242b3d;border-radius: 4px;">

                              </td>
                            </tr>
                          </tbody>
                        </table>
                        <div style="font-size: 8px; line-height: 8px; height: 8px;">&nbsp; </div>
                        <p style="margin-top: 0px;margin-bottom: 0px;"><br></p>
                      </div>
                    </div>
                    <!--[if mso]></td></tr><tr><td colspan="3" style="padding: 0px 8px;"><![endif]-->
                    <div class="o_px-xs" style="padding-left: 8px;padding-right: 8px;">
                      <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
                        <tbody>
                          <tr>
                            <td class="o_re o_bb-light" style="font-size: 16px;line-height: 16px;height: 16px;vertical-align: top;border-bottom: 1px solid #d3dce0;" data-border-bottom-color="Border Light">&nbsp; </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
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
    <table data-module="addon-row0" data-thumb="http://www.stampready.net/dashboard/editor/user_uploads/zip_uploads/2020/03/13/0D6ItbpLZUSmhj3YORzyfKEg/account_addons/thumbnails/addon-row.png" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
      <tbody>
        <tr>
          <td class="o_bg-light o_px-xs" align="center" data-bgcolor="Bg Light" style="background-color: #dbe5ea;padding-left: 8px;padding-right: 8px;">
            <!--[if mso]><table width="632" cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td><![endif]-->
            <table class="o_block" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" style="max-width: 632px;margin: 0 auto;">
              <tbody>
                <tr>
                  <td class="o_re o_bg-white o_px o_pt" align="center" data-bgcolor="Bg White" style="font-size: 0;vertical-align: top;background-color: #ffffff;padding-left: 16px;padding-right: 16px;padding-top: 16px;">
                    <!--[if mso]><table cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td width="100" align="center" valign="top" style="padding: 0px 8px;"><![endif]-->
                    <div class="o_col o_col-1 o_col-full" style="display: inline-block;vertical-align: top;width: 100%;max-width: 100px;">
                      <div class="o_px-xs o_sans o_text o_center" data-size="Text Default" data-min="12" data-max="20" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;text-align: center;padding-left: 8px;padding-right: 8px;">
                        <p style="margin-top: 0px;margin-bottom: 0px;"><a class="o_text-primary" href="https://example.com/" data-color="Primary" style="text-decoration: none;outline: none;color: #126de5;"><img src="http://www.stampready.net/dashboard/editor/user_uploads/zip_uploads/2020/03/13/0D6ItbpLZUSmhj3YORzyfKEg/account_addons/images/thumb_84_4.jpg" width="84" height="84" alt="" style="max-width: 84px;-ms-interpolation-mode: bicubic;vertical-align: middle;border: 0;line-height: 100%;height: auto;outline: none;text-decoration: none;" data-crop="false"></a></p>
                      </div>
                    </div>
                    <!--[if mso]></td><td width="300" align="left" valign="top" style="padding: 0px 8px;"><![endif]-->
                    <div class="o_col o_col-3 o_col-full" style="display: inline-block;vertical-align: top;width: 100%;max-width: 300px;">
                      <div style="font-size: 10px; line-height: 10px; height: 10px;">&nbsp; </div>
                      <div class="o_px-xs o_sans o_text-xs o_text-secondary o_left o_xs-center" data-color="Secondary" data-size="Text XS" data-min="10" data-max="18" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 14px;line-height: 21px;color: #424651;text-align: left;padding-left: 8px;padding-right: 8px;">
                        <p class="o_text o_text-dark" data-color="Dark" data-size="Text Default" data-min="12" data-max="20" style="font-size: 16px;line-height: 24px;color: #242b3d;margin-top: 0px;margin-bottom: 0px;"><strong>SuperChat Add-On</strong></p>
                        <p class="o_mb-xxs" style="margin-top: 0px;margin-bottom: 4px;">Less dear heroically much indignantly</p>
                        <p class="o_text-primary" data-color="Primary" style="color: #126de5;margin-top: 0px;margin-bottom: 0px;"><strong>$9.00</strong></p>
                      </div>
                    </div>
                    <!--[if mso]></td><td width="200" align="right" valign="top" style="padding: 0px 8px;"><![endif]-->
                    <div class="o_col o_col-2 o_col-full" style="display: inline-block;vertical-align: top;width: 100%;max-width: 200px;">
                      <div style="font-size: 10px; line-height: 10px; height: 10px;">&nbsp; </div>
                      <div class="o_px-xs o_sans o_text-xxs o_text-light o_right o_xs-center" data-color="Light" data-size="Text XXS" data-min="8" data-max="16" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 12px;line-height: 19px;color: #82899a;text-align: right;padding-left: 8px;padding-right: 8px;">
                        <table class="o_right o_xs-center" cellspacing="0" cellpadding="0" border="0" role="presentation" style="text-align: right;margin-left: auto;margin-right: 0;">
                          <tbody>
                            <tr>
                              <td class="o_btn-xs o_bg-dark o_br o_heading o_text-xs" align="center" data-bgcolor="Bg Dark" data-size="Text XS" data-min="10" data-max="18" style="font-family: Helvetica, Arial, sans-serif;font-weight: bold;margin-top: 0px;margin-bottom: 0px;font-size: 14px;line-height: 21px;mso-padding-alt: 7px 16px;background-color: #242b3d;border-radius: 4px;">

                              </td>
                            </tr>
                          </tbody>
                        </table>
                        <div style="font-size: 8px; line-height: 8px; height: 8px;">&nbsp; </div>
                        <p style="margin-top: 0px;margin-bottom: 0px;"><br></p>
                      </div>
                    </div>
                    <!--[if mso]></td></tr><tr><td colspan="3" style="padding: 0px 8px;"><![endif]-->
                    <div class="o_px-xs" style="padding-left: 8px;padding-right: 8px;">
                      <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
                        <tbody>
                          <tr>
                            <td class="o_re o_bb-light" style="font-size: 16px;line-height: 16px;height: 16px;vertical-align: top;border-bottom: 1px solid #d3dce0;" data-border-bottom-color="Border Light">&nbsp; </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
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
    <table data-module="addon-row0" data-thumb="http://www.stampready.net/dashboard/editor/user_uploads/zip_uploads/2020/03/13/0D6ItbpLZUSmhj3YORzyfKEg/account_addons/thumbnails/addon-row.png" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
      <tbody>
        <tr>
          <td class="o_bg-light o_px-xs" align="center" data-bgcolor="Bg Light" style="background-color: #dbe5ea;padding-left: 8px;padding-right: 8px;">
            <!--[if mso]><table width="632" cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td><![endif]-->
            <table class="o_block" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" style="max-width: 632px;margin: 0 auto;">
              <tbody>
                <tr>
                  <td class="o_re o_bg-white o_px o_pt" align="center" data-bgcolor="Bg White" style="font-size: 0;vertical-align: top;background-color: #ffffff;padding-left: 16px;padding-right: 16px;padding-top: 16px;">
                    <!--[if mso]><table cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td width="100" align="center" valign="top" style="padding: 0px 8px;"><![endif]-->
                    <div class="o_col o_col-1 o_col-full" style="display: inline-block;vertical-align: top;width: 100%;max-width: 100px;">
                      <div class="o_px-xs o_sans o_text o_center" data-size="Text Default" data-min="12" data-max="20" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;text-align: center;padding-left: 8px;padding-right: 8px;">
                        <p style="margin-top: 0px;margin-bottom: 0px;"><a class="o_text-primary" href="https://example.com/" data-color="Primary" style="text-decoration: none;outline: none;color: #126de5;"><img src="http://www.stampready.net/dashboard/editor/user_uploads/zip_uploads/2020/03/13/0D6ItbpLZUSmhj3YORzyfKEg/account_addons/images/thumb_84_4.jpg" width="84" height="84" alt="" style="max-width: 84px;-ms-interpolation-mode: bicubic;vertical-align: middle;border: 0;line-height: 100%;height: auto;outline: none;text-decoration: none;" data-crop="false"></a></p>
                      </div>
                    </div>
                    <!--[if mso]></td><td width="300" align="left" valign="top" style="padding: 0px 8px;"><![endif]-->
                    <div class="o_col o_col-3 o_col-full" style="display: inline-block;vertical-align: top;width: 100%;max-width: 300px;">
                      <div style="font-size: 10px; line-height: 10px; height: 10px;">&nbsp; </div>
                      <div class="o_px-xs o_sans o_text-xs o_text-secondary o_left o_xs-center" data-color="Secondary" data-size="Text XS" data-min="10" data-max="18" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 14px;line-height: 21px;color: #424651;text-align: left;padding-left: 8px;padding-right: 8px;">
                        <p class="o_text o_text-dark" data-color="Dark" data-size="Text Default" data-min="12" data-max="20" style="font-size: 16px;line-height: 24px;color: #242b3d;margin-top: 0px;margin-bottom: 0px;"><strong>SuperChat Add-On</strong></p>
                        <p class="o_mb-xxs" style="margin-top: 0px;margin-bottom: 4px;">Less dear heroically much indignantly</p>
                        <p class="o_text-primary" data-color="Primary" style="color: #126de5;margin-top: 0px;margin-bottom: 0px;"><strong>$9.00</strong></p>
                      </div>
                    </div>
                    <!--[if mso]></td><td width="200" align="right" valign="top" style="padding: 0px 8px;"><![endif]-->
                    <div class="o_col o_col-2 o_col-full" style="display: inline-block;vertical-align: top;width: 100%;max-width: 200px;">
                      <div style="font-size: 10px; line-height: 10px; height: 10px;">&nbsp; </div>
                      <div class="o_px-xs o_sans o_text-xxs o_text-light o_right o_xs-center" data-color="Light" data-size="Text XXS" data-min="8" data-max="16" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 12px;line-height: 19px;color: #82899a;text-align: right;padding-left: 8px;padding-right: 8px;">
                        <table class="o_right o_xs-center" cellspacing="0" cellpadding="0" border="0" role="presentation" style="text-align: right;margin-left: auto;margin-right: 0;">
                          <tbody>
                            <tr>
                              <td class="o_btn-xs o_bg-dark o_br o_heading o_text-xs" align="center" data-bgcolor="Bg Dark" data-size="Text XS" data-min="10" data-max="18" style="font-family: Helvetica, Arial, sans-serif;font-weight: bold;margin-top: 0px;margin-bottom: 0px;font-size: 14px;line-height: 21px;mso-padding-alt: 7px 16px;background-color: #242b3d;border-radius: 4px;">

                              </td>
                            </tr>
                          </tbody>
                        </table>
                        <div style="font-size: 8px; line-height: 8px; height: 8px;">&nbsp; </div>
                        <p style="margin-top: 0px;margin-bottom: 0px;"><br></p>
                      </div>
                    </div>
                    <!--[if mso]></td></tr><tr><td colspan="3" style="padding: 0px 8px;"><![endif]-->
                    <div class="o_px-xs" style="padding-left: 8px;padding-right: 8px;">
                      <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
                        <tbody>
                          <tr>
                            <td class="o_re o_bb-light" style="font-size: 16px;line-height: 16px;height: 16px;vertical-align: top;border-bottom: 1px solid #d3dce0;" data-border-bottom-color="Border Light">&nbsp; </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
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
    <table data-module="addon-row0" data-thumb="http://www.stampready.net/dashboard/editor/user_uploads/zip_uploads/2020/03/13/0D6ItbpLZUSmhj3YORzyfKEg/account_addons/thumbnails/addon-row.png" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
      <tbody>
        <tr>
          <td class="o_bg-light o_px-xs" align="center" data-bgcolor="Bg Light" style="background-color: #dbe5ea;padding-left: 8px;padding-right: 8px;">
            <!--[if mso]><table width="632" cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td><![endif]-->
            <table class="o_block" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" style="max-width: 632px;margin: 0 auto;">
              <tbody>
                <tr>
                  <td class="o_re o_bg-white o_px o_pt" align="center" data-bgcolor="Bg White" style="font-size: 0;vertical-align: top;background-color: #ffffff;padding-left: 16px;padding-right: 16px;padding-top: 16px;">
                    <!--[if mso]><table cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td width="100" align="center" valign="top" style="padding: 0px 8px;"><![endif]-->
                    <div class="o_col o_col-1 o_col-full" style="display: inline-block;vertical-align: top;width: 100%;max-width: 100px;">
                      <div class="o_px-xs o_sans o_text o_center" data-size="Text Default" data-min="12" data-max="20" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;text-align: center;padding-left: 8px;padding-right: 8px;">
                        <p style="margin-top: 0px;margin-bottom: 0px;"><a class="o_text-primary" href="https://example.com/" data-color="Primary" style="text-decoration: none;outline: none;color: #126de5;"><img src="http://www.stampready.net/dashboard/editor/user_uploads/zip_uploads/2020/03/13/0D6ItbpLZUSmhj3YORzyfKEg/account_addons/images/thumb_84_4.jpg" width="84" height="84" alt="" style="max-width: 84px;-ms-interpolation-mode: bicubic;vertical-align: middle;border: 0;line-height: 100%;height: auto;outline: none;text-decoration: none;" data-crop="false"></a></p>
                      </div>
                    </div>
                    <!--[if mso]></td><td width="300" align="left" valign="top" style="padding: 0px 8px;"><![endif]-->
                    <div class="o_col o_col-3 o_col-full" style="display: inline-block;vertical-align: top;width: 100%;max-width: 300px;">
                      <div style="font-size: 10px; line-height: 10px; height: 10px;">&nbsp; </div>
                      <div class="o_px-xs o_sans o_text-xs o_text-secondary o_left o_xs-center" data-color="Secondary" data-size="Text XS" data-min="10" data-max="18" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 14px;line-height: 21px;color: #424651;text-align: left;padding-left: 8px;padding-right: 8px;">
                        <p class="o_text o_text-dark" data-color="Dark" data-size="Text Default" data-min="12" data-max="20" style="font-size: 16px;line-height: 24px;color: #242b3d;margin-top: 0px;margin-bottom: 0px;"><strong>SuperChat Add-On</strong></p>
                        <p class="o_mb-xxs" style="margin-top: 0px;margin-bottom: 4px;">Less dear heroically much indignantly</p>
                        <p class="o_text-primary" data-color="Primary" style="color: #126de5;margin-top: 0px;margin-bottom: 0px;"><strong>$9.00</strong></p>
                      </div>
                    </div>
                    <!--[if mso]></td><td width="200" align="right" valign="top" style="padding: 0px 8px;"><![endif]-->
                    <div class="o_col o_col-2 o_col-full" style="display: inline-block;vertical-align: top;width: 100%;max-width: 200px;">
                      <div style="font-size: 10px; line-height: 10px; height: 10px;">&nbsp; </div>
                      <div class="o_px-xs o_sans o_text-xxs o_text-light o_right o_xs-center" data-color="Light" data-size="Text XXS" data-min="8" data-max="16" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 12px;line-height: 19px;color: #82899a;text-align: right;padding-left: 8px;padding-right: 8px;">
                        <table class="o_right o_xs-center" cellspacing="0" cellpadding="0" border="0" role="presentation" style="text-align: right;margin-left: auto;margin-right: 0;">
                          <tbody>
                            <tr>
                              <td class="o_btn-xs o_bg-dark o_br o_heading o_text-xs" align="center" data-bgcolor="Bg Dark" data-size="Text XS" data-min="10" data-max="18" style="font-family: Helvetica, Arial, sans-serif;font-weight: bold;margin-top: 0px;margin-bottom: 0px;font-size: 14px;line-height: 21px;mso-padding-alt: 7px 16px;background-color: #242b3d;border-radius: 4px;">

                              </td>
                            </tr>
                          </tbody>
                        </table>
                        <div style="font-size: 8px; line-height: 8px; height: 8px;">&nbsp; </div>
                        <p style="margin-top: 0px;margin-bottom: 0px;"><br></p>
                      </div>
                    </div>
                    <!--[if mso]></td></tr><tr><td colspan="3" style="padding: 0px 8px;"><![endif]-->
                    <div class="o_px-xs" style="padding-left: 8px;padding-right: 8px;">
                      <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
                        <tbody>
                          <tr>
                            <td class="o_re o_bb-light" style="font-size: 16px;line-height: 16px;height: 16px;vertical-align: top;border-bottom: 1px solid #d3dce0;" data-border-bottom-color="Border Light">&nbsp; </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
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
    <table data-module="addon-row0" data-thumb="http://www.stampready.net/dashboard/editor/user_uploads/zip_uploads/2020/03/13/0D6ItbpLZUSmhj3YORzyfKEg/account_addons/thumbnails/addon-row.png" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
      <tbody>
        <tr>
          <td class="o_bg-light o_px-xs" align="center" data-bgcolor="Bg Light" style="background-color: #dbe5ea;padding-left: 8px;padding-right: 8px;">
            <!--[if mso]><table width="632" cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td><![endif]-->
            <table class="o_block" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" style="max-width: 632px;margin: 0 auto;">
              <tbody>
                <tr>
                  <td class="o_re o_bg-white o_px o_pt" align="center" data-bgcolor="Bg White" style="font-size: 0;vertical-align: top;background-color: #ffffff;padding-left: 16px;padding-right: 16px;padding-top: 16px;">
                    <!--[if mso]><table cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td width="100" align="center" valign="top" style="padding: 0px 8px;"><![endif]-->
                    <div class="o_col o_col-1 o_col-full" style="display: inline-block;vertical-align: top;width: 100%;max-width: 100px;">
                      <div class="o_px-xs o_sans o_text o_center" data-size="Text Default" data-min="12" data-max="20" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;text-align: center;padding-left: 8px;padding-right: 8px;">
                        <p style="margin-top: 0px;margin-bottom: 0px;"><a class="o_text-primary" href="https://example.com/" data-color="Primary" style="text-decoration: none;outline: none;color: #126de5;"><img src="http://www.stampready.net/dashboard/editor/user_uploads/zip_uploads/2020/03/13/0D6ItbpLZUSmhj3YORzyfKEg/account_addons/images/thumb_84_4.jpg" width="84" height="84" alt="" style="max-width: 84px;-ms-interpolation-mode: bicubic;vertical-align: middle;border: 0;line-height: 100%;height: auto;outline: none;text-decoration: none;" data-crop="false"></a></p>
                      </div>
                    </div>
                    <!--[if mso]></td><td width="300" align="left" valign="top" style="padding: 0px 8px;"><![endif]-->
                    <div class="o_col o_col-3 o_col-full" style="display: inline-block;vertical-align: top;width: 100%;max-width: 300px;">
                      <div style="font-size: 10px; line-height: 10px; height: 10px;">&nbsp; </div>
                      <div class="o_px-xs o_sans o_text-xs o_text-secondary o_left o_xs-center" data-color="Secondary" data-size="Text XS" data-min="10" data-max="18" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 14px;line-height: 21px;color: #424651;text-align: left;padding-left: 8px;padding-right: 8px;">
                        <p class="o_text o_text-dark" data-color="Dark" data-size="Text Default" data-min="12" data-max="20" style="font-size: 16px;line-height: 24px;color: #242b3d;margin-top: 0px;margin-bottom: 0px;"><strong>SuperChat Add-On</strong></p>
                        <p class="o_mb-xxs" style="margin-top: 0px;margin-bottom: 4px;">Less dear heroically much indignantly</p>
                        <p class="o_text-primary" data-color="Primary" style="color: #126de5;margin-top: 0px;margin-bottom: 0px;"><strong>$9.00</strong></p>
                      </div>
                    </div>
                    <!--[if mso]></td><td width="200" align="right" valign="top" style="padding: 0px 8px;"><![endif]-->
                    <div class="o_col o_col-2 o_col-full" style="display: inline-block;vertical-align: top;width: 100%;max-width: 200px;">
                      <div style="font-size: 10px; line-height: 10px; height: 10px;">&nbsp; </div>
                      <div class="o_px-xs o_sans o_text-xxs o_text-light o_right o_xs-center" data-color="Light" data-size="Text XXS" data-min="8" data-max="16" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 12px;line-height: 19px;color: #82899a;text-align: right;padding-left: 8px;padding-right: 8px;">
                        <table class="o_right o_xs-center" cellspacing="0" cellpadding="0" border="0" role="presentation" style="text-align: right;margin-left: auto;margin-right: 0;">
                          <tbody>
                            <tr>
                              <td class="o_btn-xs o_bg-dark o_br o_heading o_text-xs" align="center" data-bgcolor="Bg Dark" data-size="Text XS" data-min="10" data-max="18" style="font-family: Helvetica, Arial, sans-serif;font-weight: bold;margin-top: 0px;margin-bottom: 0px;font-size: 14px;line-height: 21px;mso-padding-alt: 7px 16px;background-color: #242b3d;border-radius: 4px;">

                              </td>
                            </tr>
                          </tbody>
                        </table>
                        <div style="font-size: 8px; line-height: 8px; height: 8px;">&nbsp; </div>
                        <p style="margin-top: 0px;margin-bottom: 0px;"><br></p>
                      </div>
                    </div>
                    <!--[if mso]></td></tr><tr><td colspan="3" style="padding: 0px 8px;"><![endif]-->
                    <div class="o_px-xs" style="padding-left: 8px;padding-right: 8px;">
                      <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
                        <tbody>
                          <tr>
                            <td class="o_re o_bb-light" style="font-size: 16px;line-height: 16px;height: 16px;vertical-align: top;border-bottom: 1px solid #d3dce0;" data-border-bottom-color="Border Light">&nbsp; </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
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

    <table
    data-module="footer"
    data-visible="false"
    data-thumb="http://www.stampready.net/dashboard/editor/user_uploads/zip_uploads/2018/11/19/pcVNfzKjZ3goPqkxr2hYT0ws/service_canceled/thumbnails/footer.png"
    width="100%"
    cellspacing="0"
    cellpadding="0"
    border="0"
    role="presentation">
    <tbody>
      <tr>
        <td
          class="o_bg-light o_px-xs o_pb-lg o_xs-pb-xs"
          align="center"
          data-bgcolor="Bg Light"
          style="background-color: #dbe5ea; padding-left: 8px; padding-right: 8px; padding-bottom: 32px">
          <!--[if mso]><table width="632" cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td><![endif]-->
          <table
            class="o_block"
            width="100%"
            cellspacing="0"
            cellpadding="0"
            border="0"
            role="presentation"
            style="max-width: 632px; margin: 0 auto">
            <tbody>
              <tr>


  <td class="o_bg-white o_px-md o_py-lg o_bt-light o_br-b o_sans o_text-xs o_text-light" align="center"  data-color="Light" data-size="Text XS" data-min="10" data-max="18" data-border-top-color="Border Light" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 14px;line-height: 21px;background-color: #ffffff;color: #82899a;border-top: 1px solid #d3dce0;border-radius: 0px 0px 4px 4px;padding-left: 50px;padding-right: 50px;padding-top: 32px;padding-bottom: 32px;">

    <p class="o_mb" style="margin-top: 0px;margin-bottom: 16px;">©2025 Valomnia</p>

  </td></td>

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

        <!-- Alerte Section -->
        <div id="alerteSection" class="col-md-4 section" style="display: none;">
          <div class="card mb-6">
              <h5 class="card-header">Formulaire d'Alerte</h5>
              <div class="card-body">
                  <form action="{{ route('email.store') }}" method="POST">
                      @csrf
                      <div class="mb-4">
                          <label for="alerte-type" class="form-label">Type d'Alerte</label>
                          <select class="form-select" id="alerte-type" name="alert_type" required>
                              <option value="">Sélectionnez un type</option>
                              <option value="stock">Stock</option>
                              <option value="prix">Prix</option>
                          </select>
                      </div>

                      <div class="mb-4">
                          <label for="alerte-title" class="form-label">Titre</label>
                          <input type="text" class="form-control" id="alerte-title" name="alert_title" placeholder="Titre de l'alerte" required />
                      </div>

                      <div class="mb-4">
                          <label for="alerte-email-subject" class="form-label">Sujet d'Email</label>
                          <input type="text" class="form-control" id="alerte-email-subject" name="email_subject" placeholder="Sujet de l'email" required />
                      </div>





                      <form>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="alert-open" onclick="toggleUrlSection()" />
        <label class="form-check-label" for="alert-open">Afficher Bouton</label>
    </div>

    <div id="alert-url-section" class="mb-4">
        <label for="alert-url" class="form-label">URL Spécifique</label>
        <input type="url" class="form-control" id="alert-url" name="alert_url" placeholder="URL spécifique" oninput="updateButtonUrl()" />
    </div>

    <div id="alert-button-input" class="mb-4">
        <label for="alert-button-text" class="form-label">Titre du Bouton</label>
        <textarea class="form-control" id="alert-button-text" name="alert_text" rows="3" placeholder="Entrez le titre du bouton ici" oninput="updateButtonText()"></textarea>
    </div>



                      <div class="mb-4">
                          <button type="submit" class="btn btn-warning">Créer l'Alerte</button>
                      </div>
                  </form>
              </div>
          </div>
      </div>
        <!-- Email Template for Alerte -->
        <div id="alerteTemplate"  class="col-md-8" style="display: none;"> <!-- 70% width -->
            <div class="card mb-6">
              <div class="card-body">

                    <div>
                      <html xmlns="http://www.w3.org/1999/xhtml"><head><style type="text/css">
                        a { text-decoration: none; outline: none; }
                        @media (max-width: 449px) {
                          .o_col-full { max-width: 100% !important; }
                          .o_col-half { max-width: 50% !important; }
                          .o_hide-lg { display: inline-block !important; font-size: inherit !important; max-height: none !important; line-height: inherit !important; overflow: visible !important; width: auto !important; visibility: visible !important; }
                          .o_hide-xs, .o_hide-xs.o_col_i { display: none !important; font-size: 0 !important; max-height: 0 !important; width: 0 !important; line-height: 0 !important; overflow: hidden !important; visibility: hidden !important; height: 0 !important; }
                          .o_xs-center { text-align: center !important; }
                          .o_xs-left { text-align: left !important; }
                          .o_xs-right { text-align: left !important; }
                          table.o_xs-left { margin-left: 0 !important; margin-right: auto !important; float: none !important; }
                          table.o_xs-right { margin-left: auto !important; margin-right: 0 !important; float: none !important; }
                          table.o_xs-center { margin-left: auto !important; margin-right: auto !important; float: none !important; }
                          h1.o_heading { font-size: 32px !important; line-height: 41px !important; }
                          h2.o_heading { font-size: 26px !important; line-height: 37px !important; }
                          h3.o_heading { font-size: 20px !important; line-height: 30px !important; }
                          .o_xs-py-md { padding-top: 24px !important; padding-bottom: 24px !important; }
                          .o_xs-pt-xs { padding-top: 8px !important; }
                          .o_xs-pb-xs { padding-bottom: 8px !important; }
                        }
                        @media screen {
                          @font-face {
                            font-family: 'Roboto';
                            font-style: normal;
                            font-weight: 400;
                            src: local("Roboto"), local("Roboto-Regular"), url(https://fonts.gstatic.com/s/roboto/v18/KFOmCnqEu92Fr1Mu7GxKOzY.woff2) format("woff2");
                            unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF; }
                          @font-face {
                            font-family: 'Roboto';
                            font-style: normal;
                            font-weight: 400;
                            src: local("Roboto"), local("Roboto-Regular"), url(https://fonts.gstatic.com/s/roboto/v18/KFOmCnqEu92Fr1Mu4mxK.woff2) format("woff2");
                            unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD; }
                          @font-face {
                            font-family: 'Roboto';
                            font-style: normal;
                            font-weight: 700;
                            src: local("Roboto Bold"), local("Roboto-Bold"), url(https://fonts.gstatic.com/s/roboto/v18/KFOlCnqEu92Fr1MmWUlfChc4EsA.woff2) format("woff2");
                            unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF; }
                          @font-face {
                            font-family: 'Roboto';
                            font-style: normal;
                            font-weight: 700;
                            src: local("Roboto Bold"), local("Roboto-Bold"), url(https://fonts.gstatic.com/s/roboto/v18/KFOlCnqEu92Fr1MmWUlfBBc4.woff2) format("woff2");
                            unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD; }
                          .o_sans, .o_heading { font-family: "Roboto", sans-serif !important; }
                          .o_heading, strong, b { font-weight: 700 !important; }
                          a[x-apple-data-detectors] { color: inherit !important; text-decoration: none !important; }
                          .button {
    padding: 10px 20px;
    background-color: #0ec06e; /* Couleur de fond */
    color: white; /* Couleur du texte */
    border: none; /* Pas de bordure */
    border-radius: 4px; /* Coins arrondis */
    cursor: pointer; /* Curseur en forme de main */
    margin: 20px 0; /* Marge verticale */
}

                        }
                        #canvas td.o_hide, #canvas td.o_hide div { display: block!important; font-family: "Roboto", sans-serif; font-size: 16px!important; color: #000; font-size: inherit!important; max-height: none!important; width: auto!important; line-height: inherit!important; visibility: visible!important;}
                        .CodeMirror { line-height: 1.4; font-size: 12px; font-family: sans-serif; }
                      </style>
                      </head><body><table data-module="preview-text" data-visible="false" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
                        <tbody>
                          <tr>
                            <td class="o_hide" align="center" style="display: none;font-size: 0;max-height: 0;width: 0;line-height: 0;overflow: hidden;mso-hide: all;visibility: hidden;">Email Summary (Hidden)</td>
                          </tr>
                        </tbody>
                      </table><table data-module="header0" data-thumb="http://www.stampready.net/dashboard/editor/user_uploads/zip_uploads/2018/11/19/8pREHJbyxUVqTg6cslF4iBY3/account_verification/thumbnails/header.png" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
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
                      </table><table data-module="hero-icon-lines0"  width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
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
                                            <td class="o_bb-primary" height="40" width="32" data-border-bottom-color="Border Primary 2" style="border-bottom: 1px solid #126de5;">&nbsp; </td>
                                            <td rowspan="2" class="o_sans o_text o_text-secondary o_px o_py" align="center" data-color="Secondary" data-size="Text Default" data-min="12" data-max="20" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;color: #424651;padding-left: 16px;padding-right: 16px;padding-top: 16px;padding-bottom: 16px;">
                                              <img src="{{asset('assets/img/email-setting/alert/default.png')}}" width="48" height="48" alt="" style="max-width: 48px;-ms-interpolation-mode: bicubic;vertical-align: middle;border: 0;line-height: 100%;height: auto;outline: none;text-decoration: none;">
                                            </td>
                                            <td class="o_bb-primary" height="40" width="32" data-border-bottom-color="Border Primary 2" style="border-bottom: 1px solid #126de5;">&nbsp; </td>
                                          </tr>
                                          <tr>
                                            <td height="40">&nbsp; </td>
                                            <td height="40">&nbsp; </td>
                                          </tr>
                                          <tr>
                                            <td style="font-size: 8px; line-height: 8px; height: 8px;">&nbsp; </td>
                                            <td style="font-size: 8px; line-height: 8px; height: 8px;">&nbsp; </td>
                                          </tr>
                                        </tbody>
                                      </table>
                                      <h2 class="o_heading o_text-dark o_mb-xxs" id="template-title" data-color="Dark" data-size="Heading 2" data-min="20" data-max="40" style="font-family: Helvetica, Arial, sans-serif;font-weight: bold;margin-top: 0px;margin-bottom: 4px;color: #242b3d;font-size: 30px;line-height: 39px;">Titre de l'alerte par défaut</h2>

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
                                    <td class="o_bg-white" style="font-size: 24px;line-height: 24px;height: 24px;background-color: #ffffff;" data-bgcolor="Bg White">&nbsp; </td>
                                  </tr>
                                </tbody>
                              </table>
                              <!--[if mso]></td></tr></table><![endif]-->
                            </td>
                          </tr>
                        </tbody>
                      </table>
                      <table data-module="content0"  width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
                        <tbody>
                          <tr>
                            <td class="o_bg-light o_px-xs" align="center" data-bgcolor="Bg Light" style="background-color: #dbe5ea;padding-left: 8px;padding-right: 8px;">
                              <!--[if mso]><table width="432" cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td><![endif]-->
                              <table class="o_block-xs" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" style="max-width: 432px;margin: 0 auto;">
                                <tbody>
                                  <tr>
                                    <td class="o_bg-white o_px-md o_py o_sans o_text o_text-secondary" align="left" data-bgcolor="Bg White" data-color="Secondary" data-size="Text Default" data-min="12" data-max="20" style="text-align: left;font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;background-color: #ffffff;color: #424651;padding-left: 24px;padding-right: 24px;padding-top: 16px;padding-bottom: 16px;">
                                      <p id="template-text" style="margin-top: 0px;margin-bottom: 0px;">Welcome to B2B Valomnia! We're excited to have you on board.</p>

                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                              <!--[if mso]></td></tr></table><![endif]-->
                            </td>
                          </tr>
                        </tbody>
                      </table>

                      <table data-module="spacer00" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
                        <tbody>
                          <tr>
                            <td class="o_bg-light o_px-xs" align="center" data-bgcolor="Bg Light" style="background-color: #dbe5ea;padding-left: 8px;padding-right: 8px;">
                              <!--[if mso]><table width="432" cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td><![endif]-->
                              <table class="o_block-xs" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" style="max-width: 432px;margin: 0 auto;">
                                <tbody>
                                  <tr>
                                    <td class="o_bg-white" style="font-size: 24px;line-height: 24px;height: 24px;background-color: #ffffff;" data-bgcolor="Bg White">&nbsp; </td>
                                  </tr>
                                </tbody>
                              </table>
                              <!--[if mso]></td></tr></table><![endif]-->
                            </td>
                          </tr>
                        </tbody>
                      </table>
                          <table data-module="content0"  width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
                        <tbody>
                          <tr>
                            <td class="o_bg-light o_px-xs" align="center" data-bgcolor="Bg Light" style="background-color: #dbe5ea;padding-left: 8px;padding-right: 8px;">
                              <!--[if mso]></td></tr></table><![endif]-->
                            </td>
                          </tr>
                        </tbody>
                      </table>
                      <table data-module="button-success" data-visible="false"  width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" style="opacity: 1;">
                        <tbody>
                          <tr>
                            <td class="o_bg-light o_px-xs" align="center" data-bgcolor="Bg Light" style="background-color: #dbe5ea;padding-left: 8px;padding-right: 8px;">
                              <!--[if mso]><table width="432" cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td><![endif]-->
                              <table class="o_block-xs" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" style="max-width: 432px;margin: 0 auto;">
        <tbody>
            <tr>
                <td class="o_bg-white o_px-md o_py-xs" align="center" data-bgcolor="Bg White" style="background-color: #ffffff;padding-left: 24px;padding-right: 24px;padding-top: 8px;padding-bottom: 8px;">
                    <table align="center" cellspacing="0" cellpadding="0" border="0" role="presentation">
                        <tbody>
                            <tr>
                                <td width="300" class="o_btn o_bg-success o_br o_heading o_text"  id="alert-button" align="center" data-bgcolor="Bg Success" data-size="Text Default" data-min="12" data-max="20" style="font-family: Helvetica, Arial, sans-serif;font-weight: bold;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;mso-padding-alt: 12px 24px;background-color: #0ec06e;border-radius: 4px; display: none;">
                                    <a class="o_text-white" id="alert-link"  href="https://example.com/" data-color="White" style="text-decoration: none;outline: none;color: #ffffff;display: block;padding: 12px 24px;mso-text-raise: 3px;">Access Your Account</a>
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
                          <table data-module="content0"  width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
                        <tbody>
                          <tr>
                            <td class="o_bg-light o_px-xs" align="center" data-bgcolor="Bg Light" style="background-color: #dbe5ea;padding-left: 8px;padding-right: 8px;">
                              <!--[if mso]><table width="432" cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td><![endif]-->

                              <!--[if mso]></td></tr></table><![endif]-->
                            </td>
                          </tr>
                        </tbody>
                      </table>
                      <table data-module="spacer-lg0"  width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
                        <tbody>
                          <tr>
                            <td class="o_bg-light o_px-xs" align="center" data-bgcolor="Bg Light" style="background-color: #dbe5ea;padding-left: 8px;padding-right: 8px;">
                              <!--[if mso]><table width="432" cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td><![endif]-->
                              <table class="o_block-xs" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" style="max-width: 432px;margin: 0 auto;">
                                <tbody>
                                  <tr>
                                    <td class="o_bg-white" style="font-size: 48px;line-height: 48px;height: 48px;background-color: #ffffff;" data-bgcolor="Bg White">&nbsp; </td>
                                  </tr>
                                </tbody>
                              </table>
                              <!--[if mso]></td></tr></table><![endif]-->
                            </td>
                          </tr>
                        </tbody>
                      </table><table data-module="footer-white0"  width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
                        <tbody>
                          <tr>
                            <td class="o_bg-light o_px-xs o_pb-lg o_xs-pb-xs" align="center" data-bgcolor="Bg Light" style="background-color: #dbe5ea;padding-left: 8px;padding-right: 8px;padding-bottom: 32px;">
                              <!--[if mso]><table width="432" cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td><![endif]-->
                              <table class="o_block-xs" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" style="max-width: 432px;margin: 0 auto;">
                                <tbody>
                                  <tr>
                                    <td class="o_bg-white o_px-md o_py-lg o_bt-light o_br-b o_sans o_text-xs o_text-light" align="center" data-bgcolor="Bg White" data-color="Light" data-size="Text XS" data-min="10" data-max="18" data-border-top-color="Border Light" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 14px;line-height: 21px;background-color: #ffffff;color: #82899a;border-top: 1px solid #d3dce0;border-radius: 0px 0px 4px 4px;padding-left: 24px;padding-right: 24px;padding-top: 32px;padding-bottom: 32px;">

                                      <p class="o_mb" style="margin-top: 0px;margin-bottom: 16px;">©2006 Valomnia</p>

                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                              <!--[if mso]></td></tr></table><![endif]-->
                              <div class="o_hide-xs" style="font-size: 64px; line-height: 64px; height: 64px;">&nbsp; </div>
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

@endsection
@section('page-script')
<script>
 function toggleSections(section) {
    const rapportSection = document.getElementById('rapportSection');
    const rapportTemplate = document.getElementById('rapportTemplate');
    const alerteSection = document.getElementById('alerteSection');
    const alerteTemplate = document.getElementById('alerteTemplate');

    // Réinitialiser l'affichage des sections
    rapportSection.style.display = 'none';
    rapportTemplate.style.display = 'none';
    alerteSection.style.display = 'none';
    alerteTemplate.style.display = 'none';

    // Afficher la section et le modèle sélectionnés
    if (section === 'rapport') {
        rapportSection.style.display = 'block';
        rapportTemplate.style.display = 'block';
        document.getElementById('rapportBtn').classList.remove('btn-label-primary');
        document.getElementById('rapportBtn').classList.add('btn-primary');
        document.getElementById('alerteBtn').classList.remove('btn-primary');
        document.getElementById('alerteBtn').classList.add('btn-label-primary');
    } else if (section === 'alerte') {
        alerteSection.style.display = 'block';
        alerteTemplate.style.display = 'block';
        document.getElementById('alerteBtn').classList.remove('btn-label-primary');
        document.getElementById('alerteBtn').classList.add('btn-primary');
        document.getElementById('rapportBtn').classList.remove('btn-primary');
        document.getElementById('rapportBtn').classList.add('btn-label-primary');
    }
}

// Initialisation : afficher la section Rapport par défaut
document.addEventListener('DOMContentLoaded', function() {
    toggleSections('rapport'); // Affiche par défaut la section Rapport
});

    function toggleReportSection() {
    const checkbox = document.getElementById('report-open');
    const urlSection = document.getElementById('urlSection');
    const buttonTitleSection = document.getElementById('buttonTitleSection');
    const reportTemplate = document.getElementById('reportTemplate');

    // Affiche ou masque la section d'URL de rapport, le titre et le bouton
    urlSection.style.display = checkbox.checked ? 'block' : 'none';
    buttonTitleSection.style.display = checkbox.checked ? 'block' : 'none';
    reportTemplate.style.display = checkbox.checked ? 'block' : 'none';

    console.log(checkbox.checked ? 'URL section, button title, and button displayed' : 'Sections hidden');
}

function updateReportButtonText() {
    const buttonText = document.getElementById('button-title').value;
    const actionLink = document.getElementById('action-link');
    actionLink.innerText = buttonText || "Voir le Rapport"; // Texte par défaut
    console.log('Button text updated to:', actionLink.innerText);
}

function updateReportButtonUrl() {
    const reportUrl = document.getElementById('report-url').value;
    console.log('Report URL updated to:', reportUrl);
}











    function updateReportTitle() {
        // Get the value from the input field
        const titleInput = document.getElementById('rapport-title').value;
        // Get the template title element
        const reportTitle = document.getElementById('report-title');
        // Update the title or set default if input is empty
        reportTitle.innerText = titleInput || "Titre du rapport par défaut"; // Default title
    }
    function updateRapportContent() {
        // Get the value from the textarea
        const rapportContent = document.getElementById('rapport-content').value;
        // Get the rapport template text element
        const rapportTemplateText = document.getElementById('rapport-template-text');
        // Update the paragraph or set default if input is empty
        rapportTemplateText.innerText = rapportContent || "Contenu par défaut du rapport. Veuillez entrer votre texte ci-dessus."; // Default text
    }

    function toggleTitle() {
        const titleElement = document.getElementById('report-title');
        const titleCheckbox = document.getElementById('show-title');
        titleElement.style.display = titleCheckbox.checked ? 'block' : 'none'; // Show or hide title
    }

    function toggleParagraph() {
        const contentElement = document.getElementById('rapport-template-text');
        const paragraphCheckbox = document.getElementById('show-paragraph');
        contentElement.style.display = paragraphCheckbox.checked ? 'block' : 'none'; // Show or hide content
    }






    function updateAlertText() {
        const text = document.getElementById('alerte-text').value;
        document.getElementById('template-text').innerText = text || "Welcome to B2B Valomnia! We're excited to have you on board."; // Texte par défaut
    }

    function toggleUrlSection() {
        const checkbox = document.getElementById('alert-open');
        const urlSection = document.getElementById('alert-url-section');
        const buttonInput = document.getElementById('alert-button-input');
        const button = document.getElementById('alert-button');

        const displayStyle = checkbox.checked ? 'block' : 'none';

        // Afficher/Masquer les sections et le bouton
        urlSection.style.display = displayStyle;
        buttonInput.style.display = displayStyle;
        button.style.display = checkbox.checked ? 'block' : 'none';

        console.log(checkbox.checked ? 'Sections et bouton affichés' : 'Sections et bouton cachés');
    }

    // Fonction pour mettre à jour l'URL du bouton
    function updateButtonUrl() {
        const buttonUrl = document.getElementById('alert-url').value;
        document.getElementById('alert-link').href = buttonUrl || "https://example.com/";
    }

    // Fonction pour mettre à jour le texte du bouton
    function updateButtonText() {
        const buttonText = document.getElementById('alert-button-text').value;
        document.getElementById('alert-link').innerText = buttonText || "Access Your Account";
    }

    function toggleKPI(kpiId, isChecked) {
    const kpiElement = document.getElementById(kpiId);
    if (isChecked) {
        kpiElement.style.display = "block";
    } else {
        kpiElement.style.display = "none"; // Hide the KPI when unchecked
    }
}

</script>
@endsection
