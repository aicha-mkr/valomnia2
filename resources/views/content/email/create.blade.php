@extends('layouts.contentNavbarLayout')

@section('title', 'Créer un Nouveau Template')

@section('content')

    <div class="text-center mb-4">
        <button class="btn btn-primary me-1" onclick="toggleSections('rapport')">Rapport</button>
        <button class="btn btn-warning me-1" onclick="toggleSections('alerte')">Alerte</button>
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
                            <label for="rapport-title" class="form-label">Titre</label>
                            <input type="text" class="form-control" id="rapport-title" name="title" placeholder="Titre du rapport" required />
                        </div>
                        <div class="mb-4">
                            <label for="rapport-email-header" class="form-label">En-tête d'Email</label>
                            <input type="text" class="form-control" id="rapport-email-header" name="email_header" placeholder="En-tête de l'email" required />
                        </div>
                        <div class="mb-4">
                            <label for="rapport-email-subject" class="form-label">Sujet</label>
                            <input type="text" class="form-control" id="rapport-email-subject" name="email_subject" placeholder="Sujet de l'email" required />
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="show-header" checked />
                            <label class="form-check-label" for="show-header">Afficher l'en-tête</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="show-subject" />
                            <label class="form-check-label" for="show-subject">Afficher le sujet</label>
                        </div>
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="card-header">Select KPIs to Include</h5>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="total_revenue" id="totalRevenueCheckbox" name="kpis[]" />
                                    <label class="form-check-label" for="totalRevenueCheckbox">Total Revenue</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="total_clients" id="totalClientsCheckbox" name="kpis[]" />
                                    <label class="form-check-label" for="totalClientsCheckbox">Total Customers</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="average_sales" id="averageSalesCheckbox" name="kpis[]" />
                                    <label class="form-check-label" for="averageSalesCheckbox">Average Sales</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="total_orders" id="totalOrdersCheckbox" name="kpis[]" />
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

        <!-- Email Template for Rapport -->
        <div id="rapportTemplate" class="col-md-8" style="display: none;"> <!-- 70% width -->
          <div class="card mb-6">
                <div class="card-body">
                    
                    <div>
                        <!-- Place your email template here -->

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
                    <h2
                      class="o_heading o_text-dark o_mb-xxs"
                      data-color="Dark"
                      data-size="Heading 2"
                      data-min="20"
                      data-max="40"
                      style="
                        font-family: Helvetica, Arial, sans-serif;
                        font-weight: bold;
                        margin-top: 0px;
                        margin-bottom: 4px;
                        color: #242b3d;
                        font-size: 30px;
                        line-height: 39px;
                      ">
                      Notification Title
                    </h2>
                    <p style="margin-top: 0px; margin-bottom: 0px">That thus much less heron other hello</p>
                  </td>
                </tr>
              </tbody>
            </table>
            <!--[if mso]></td></tr></table><![endif]-->
          </td>
       
      </tbody>
    </table>
    <!--mssssssgggiiiii-->
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
                    Your Account Details
                  </h4>
                  <p style="margin-top: 0px; margin-bottom: 0px">
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
                    <!--[if mso]><table cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td width="200" align="center" valign="top" style="padding: 0px 8px;"><![endif]-->
                    <div
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
                                <p
                                  class="o_text-xxs o_text-light"
                                  data-size="Text XXS"
                                  data-min="8"
                                  data-max="16"
                                  data-color="Light"
                                  style="
                                    font-size: 12px;
                                    line-height: 19px;
                                    color: #82899a;
                                    margin-top: 0px;
                                    margin-bottom: 0px;
                                  ">
                                  monthly
                                </p>
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
                                <p class="o_mb-xs" style="margin-top: 0px; margin-bottom: 8px">
                                  <span class="o_text-primary" data-color="Primary" style="color: #126de5">●</span>
                                  goodness familiarly
                                </p>
                                <p class="o_mb-xs" style="margin-top: 0px; margin-bottom: 8px">
                                  <span class="o_text-primary" data-color="Primary" style="color: #126de5">●</span>
                                  overpaid kneeled
                                </p>
                                <p class="o_mb-md" style="margin-top: 0px; margin-bottom: 24px"><span>&nbsp; </span></p>
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
                                        <a
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
                                          >Learn More</a
                                        >
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
                                <p
                                  class="o_text-xxs o_text-light"
                                  data-size="Text XXS"
                                  data-min="8"
                                  data-max="16"
                                  data-color="Light"
                                  style="
                                    font-size: 12px;
                                    line-height: 19px;
                                    color: #82899a;
                                    margin-top: 0px;
                                    margin-bottom: 0px;
                                  ">
                                  monthly
                                </p>
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
                                <p class="o_mb-xs" style="margin-top: 0px; margin-bottom: 8px">
                                  <span class="o_text-primary" data-color="Primary" style="color: #126de5">●</span>
                                  goodness familiarly
                                </p>
                                <p class="o_mb-xs" style="margin-top: 0px; margin-bottom: 8px">
                                  <span class="o_text-primary" data-color="Primary" style="color: #126de5">●</span>
                                  overpaid kneeled
                                </p>
                                <p class="o_mb-md" style="margin-top: 0px; margin-bottom: 24px">
                                  <span class="o_text-primary" data-color="Primary" style="color: #126de5">●</span>
                                  lubberly one
                                </p>
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
                                        <a
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
                                          >Learn More</a
                                        >
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
                                <p
                                  class="o_text-xxs o_text-light"
                                  data-size="Text XXS"
                                  data-min="8"
                                  data-max="16"
                                  data-color="Light"
                                  style="
                                    font-size: 12px;
                                    line-height: 19px;
                                    color: #82899a;
                                    margin-top: 0px;
                                    margin-bottom: 0px;
                                  ">
                                  monthly
                                </p>
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
                                <p class="o_mb-xs" style="margin-top: 0px; margin-bottom: 8px">
                                  <span class="o_text-primary" data-color="Primary" style="color: #126de5">●</span>
                                  goodness familiarly
                                </p>
                                <p class="o_mb-xs" style="margin-top: 0px; margin-bottom: 8px">
                                  <span class="o_text-primary" data-color="Primary" style="color: #126de5">●</span>
                                  overpaid kneeled
                                </p>
                                <p class="o_mb-md" style="margin-top: 0px; margin-bottom: 24px">
                                  <span class="o_text-primary" data-color="Primary" style="color: #126de5">●</span>
                                  lubberly one
                                </p>
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
                                        <a
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
                                          >Learn More</a
                                        >
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
      data-module="spacer000"
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
                <td
                  class="o_bg-dark o_px-md o_py-lg o_br-b o_sans o_text-xs o_text-dark_light"
                  align="center"
                  data-bgcolor="Bg Dark"
                  data-color="Dark Light"
                  data-size="Text XS"
                  data-min="10"
                  data-max="18"
                  style="
                    font-family: Helvetica, Arial, sans-serif;
                    margin-top: 0px;
                    margin-bottom: 0px;
                    font-size: 14px;
                    line-height: 21px;
                    background-color: #242b3d;
                    color: #a0a3ab;
                    border-radius: 0px 0px 4px 4px;
                    padding-left: 24px;
                    padding-right: 24px;
                    padding-top: 32px;
                    padding-bottom: 32px;
                  ">
                  <p class="o_mb" style="margin-top: 0px; margin-bottom: 16px">
                    <a
                      class="o_text-dark_light"
                      href="https://example.com/"
                      data-color="Dark Light"
                      style="text-decoration: none; outline: none; color: #a0a3ab"
                      ><img
                        src="http://www.stampready.net/dashboard/editor/user_uploads/zip_uploads/2018/11/19/pcVNfzKjZ3goPqkxr2hYT0ws/service_canceled/images/logo_icon-white.png"
                        width="36"
                        height="36"
                        alt="SimpleApp"
                        style="
                          max-width: 36px;
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
                  <p class="o_mb" style="margin-top: 0px; margin-bottom: 16px">©2018 SimpleApp Inc</p>
                  <p class="o_mb" style="margin-top: 0px; margin-bottom: 16px">
                    2603 Woodridge Lane,<br />
                    Memphis, TN 38104, USA
                  </p>
                  <p style="margin-top: 0px; margin-bottom: 0px">
                    <a
                      class="o_text-dark_light o_underline"
                      href="https://example.com/"
                      data-color="Dark Light"
                      style="text-decoration: underline; outline: none; color: #a0a3ab"
                      >Help Center</a
                    >
                    <span class="o_hide-xs">&nbsp; • &nbsp;</span
                    ><br
                      class="o_hide-lg"
                      style="
                        display: none;
                        font-size: 0;
                        max-height: 0;
                        width: 0;
                        line-height: 0;
                        overflow: hidden;
                        mso-hide: all;
                        visibility: hidden;
                      " />
                    <a
                      class="o_text-dark_light o_underline"
                      href="https://example.com/"
                      data-color="Dark Light"
                      style="text-decoration: underline; outline: none; color: #a0a3ab"
                      >Preferences</a
                    >
                    <span class="o_hide-xs">&nbsp; • &nbsp;</span
                    ><br
                      class="o_hide-lg"
                      style="
                        display: none;
                        font-size: 0;
                        max-height: 0;
                        width: 0;
                        line-height: 0;
                        overflow: hidden;
                        mso-hide: all;
                        visibility: hidden;
                      " />
                    <a
                      class="o_text-dark_light o_underline"
                      href="https://example.com/"
                      data-color="Dark Light"
                      style="text-decoration: underline; outline: none; color: #a0a3ab"
                      >Unsubscribe</a
                    >
                  </p>
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
        <div id="alerteSection" class="col-md-6 section" style="display: none;">
            <div class="card mb-6">
                <h5 class="card-header">Formulaire d'Alerte</h5>
                <div class="card-body">
                    <form >
                        @csrf
                        <div class="mb-4">
                            <label for="alerte-type" class="form-label">Type d'Alerte</label>
                            <select class="form-select" id="alerte-type" name="alert_type" required>
                                <option value="" disabled selected>Choisir un type d'alerte</option>
                                <option value="stock">Stock</option>
                                <option value="chiffre">Chiffre</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="alerte-title" class="form-label">Titre</label>
                            <input type="text" class="form-control" id="alerte-title" name="title" placeholder="Titre de l'alerte" required />
                        </div>
                        <div class="mb-4">
                            <label for="alerte-email-subject" class="form-label">Sujet d'Email</label>
                            <input type="text" class="form-control" id="alerte-email-subject" name="email_subject" placeholder="Sujet de l'email" required />
                        </div>
                        <div class="mb-4">
                            <label for="alerte-url" class="form-label">URL Spécifique</label>
                            <input type="url" class="form-control" id="alerte-url" name="alert_url" placeholder="URL spécifique" required />
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="alert-open" />
                            <label class="form-check-label" for="alert-open">Bouton si ouvert</label>
                        </div>
                        <div class="mb-4">
                            <button type="submit" class="btn btn-warning">Créer l'Alerte</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Email Template for Alerte -->
        <div id="alerteTemplate" class="col-md-6" style="display: none;">
            <div class="card mb-6">
                <h5 class="card-header">Template Email Alerte</h5>
                <div class="card-body">
                    <p>Voici le template d'email pour l'alerte.</p>
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
                                      <h2 class="o_heading o_text-dark o_mb-xxs" data-color="Dark" data-size="Heading 2" data-min="20" data-max="40" style="font-family: Helvetica, Arial, sans-serif;font-weight: bold;margin-top: 0px;margin-bottom: 4px;color: #242b3d;font-size: 30px;line-height: 39px;">Welcome to B2B Valomnia</h2>
                                     
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
                                      <p style="margin-top: 0px;margin-bottom: 0px;">Welcome to B2B Valomnia! We're excited to have you on board.</p>
                      
                                      <p style="margin-top: 0px;margin-bottom: 0px;">Below are your login credentials to get started:</p>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                              <!--[if mso]></td></tr></table><![endif]-->
                            </td>
                          </tr>
                        </tbody>
                      </table>
                      <table data-module="label-xs0" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
                        <tbody>
                          <tr>
                            <td class="o_bg-light o_px-xs" align="center" data-bgcolor="Bg Light" style="background-color: #dbe5ea;padding-left: 8px;padding-right: 8px;">
                              <!--[if mso]><table width="432" cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td><![endif]-->
                              <table class="o_block-xs" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" style="max-width: 432px;margin: 0 auto;">
                                <tbody>
                                  <tr>
                                    <td class="o_bg-white o_px-md o_py o_sans o_text-xs o_text-light" align="center" data-bgcolor="Bg White" data-color="Light" data-size="Text XS" data-min="10" data-max="18" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 14px;line-height: 21px;background-color: #ffffff;color: #82899a;padding-left: 24px;padding-right: 24px;padding-top: 16px;padding-bottom: 16px;">
                                      <p class="o_mb" style="margin-top: 0px;margin-bottom: 16px;"><strong>E-mail Address</strong></p>
                                      <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                                        <tbody>
                                          <tr>
                                            <td width="284" class="o_bg-ultra_light o_br o_text-xs o_sans o_px-xs o_py" align="center" data-bgcolor="Bg Ultra Light" data-size="Text XS" data-min="10" data-max="18" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 14px;line-height: 21px;background-color: #ebf5fa;border-radius: 4px;padding-left: 8px;padding-right: 8px;padding-top: 16px;padding-bottom: 16px;">
                                              <p class="o_text-dark" data-color="Dark" style="color: #242b3d;margin-top: 0px;margin-bottom: 0px;"><strong>robertallen@company.com</strong></p>
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
                      </table><table data-module="label-xs0"  width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
                        <tbody>
                          <tr>
                            <td class="o_bg-light o_px-xs" align="center" data-bgcolor="Bg Light" style="background-color: #dbe5ea;padding-left: 8px;padding-right: 8px;">
                              <!--[if mso]><table width="432" cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td><![endif]-->
                              <table class="o_block-xs" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" style="max-width: 432px;margin: 0 auto;">
                                <tbody>
                                  <tr>
                                    <td class="o_bg-white o_px-md o_py o_sans o_text-xs o_text-light" align="center" data-bgcolor="Bg White" data-color="Light" data-size="Text XS" data-min="10" data-max="18" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 14px;line-height: 21px;background-color: #ffffff;color: #82899a;padding-left: 24px;padding-right: 24px;padding-top: 16px;padding-bottom: 16px;">
                                      <p class="o_mb" style="margin-top: 0px;margin-bottom: 16px;"><strong>Password</strong></p>
                                      <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                                        <tbody>
                                          <tr>
                                            <td width="284" class="o_bg-ultra_light o_br o_text-xs o_sans o_px-xs o_py" align="center" data-bgcolor="Bg Ultra Light" data-size="Text XS" data-min="10" data-max="18" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 14px;line-height: 21px;background-color: #ebf5fa;border-radius: 4px;padding-left: 8px;padding-right: 8px;padding-top: 16px;padding-bottom: 16px;">
                                              <p class="o_text-dark" data-color="Dark" style="color: #242b3d;margin-top: 0px;margin-bottom: 0px;"><strong>ROmdmmloed**</strong></p>
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
                              <!--[if mso]><table width="432" cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td><![endif]-->
                              <table class="o_block-xs" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" style="max-width: 432px;margin: 0 auto;">
                                <tbody>
                                  <tr>
                                    <td class="o_bg-white o_px-md o_py o_sans o_text o_text-secondary" align="left" data-bgcolor="Bg White" data-color="Secondary" data-size="Text Default" data-min="12" data-max="20" style="    text-align: left;font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;background-color: #ffffff;color: #424651;padding-left: 24px;padding-right: 24px;padding-top: 16px;padding-bottom: 16px;">
                                      <p style="margin-top: 0px;margin-bottom: 0px;">To access your account, <strong>PRESS</strong> the button below:</p>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
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
                                            <td width="300" class="o_btn o_bg-success o_br o_heading o_text" align="center" data-bgcolor="Bg Success" data-size="Text Default" data-min="12" data-max="20" style="font-family: Helvetica, Arial, sans-serif;font-weight: bold;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;mso-padding-alt: 12px 24px;background-color: #0ec06e;border-radius: 4px;">
                                              <a class="o_text-white" href="https://example.com/" data-color="White" style="text-decoration: none;outline: none;color: #ffffff;display: block;padding: 12px 24px;mso-text-raise: 3px;">Access Your Account</a>
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
                              <table class="o_block-xs" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" style="max-width: 432px;margin: 0 auto;">
                                <tbody>
                                  <tr>
                                    <td class="o_bg-white o_px-md o_py o_sans o_text o_text-secondary" align="left" data-bgcolor="Bg White" data-color="Secondary" data-size="Text Default" data-min="12" data-max="20" style="    text-align: left;font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;background-color: #ffffff;color: #424651;padding-left: 24px;padding-right: 24px;padding-top: 16px;padding-bottom: 16px;">
                                      <p style="margin-top: 0px;margin-bottom: 0px;"> <strong> Next Steps:</strong> </p>
                                      <p style="margin-top: 0px;margin-bottom: 0px;">
                                       
                                          We recommend updating your password after your first login to secure your account.
                      
                                          If you have any questions or need assistance, our support team is here to help.
                      
                                          Thank you for joining B2B Valomnia, and we look forward to supporting your business journey.
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
                                     
                                      <p class="o_mb" style="margin-top: 0px;margin-bottom: 16px;">©2025 Valomnia</p>
                                    
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

        if (section === 'rapport') {
            rapportSection.style.display = 'block';
            rapportTemplate.style.display = 'block';
            alerteSection.style.display = 'none';
            alerteTemplate.style.display = 'none';
        } else if (section === 'alerte') {
            rapportSection.style.display = 'none';
            rapportTemplate.style.display = 'none';
            alerteSection.style.display = 'block';
            alerteTemplate.style.display = 'block';
        }
    }
</script>
@endsection