<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #f2f2f2;
            padding: 10px;
        }
        header img {
            height: 40px;
        }
        .new-setting {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .new-setting button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            cursor: pointer;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        .action {
            display: flex;
            justify-content: space-around;
        }
        .action button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 5px 10px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            cursor: pointer;
        }
        .kpi-row {
            display: flex;
            align-items: center;
        }
        .kpi-row input[type="checkbox"] {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <header>
        <img src="{{ asset('assets/img/logo/valomnialogo.png') }}" alt="Valomnia Logo" width="150" />
        <p>{{ date('d/m/Y') }}</p>
    </header>
    <div class="new-setting">
        <h1>Paramètres d'envoi d'e-mail</h1>
        <button>Ajouter un nouveau paramètre</button>
    </div>
    <table>
        <tr>
            <th>Titre</th>
            <th>Date</th>
            <th>Déclenché</th>
            <th>Action</th>
        </tr>
        <tr>
            <td>Nouvelle configuration</td>
            <td>11/04/2023</td>
            <td>Hebdomadaire</td>
            <td class="action">
                <button>Modifier</button>
                <button>Supprimer</button>
            </td>
        </tr>
        <tr>
            <td>Ancienne configuration</td>
            <td>01/01/2023</td>
            <td>Mensuel</td>
            <td class="action">
                <button>Modifier</button>
                <button>Supprimer</button>
            </td>
        </tr>
    </table>
    <h1>Récapitulatif Hebdomadaire</h1>
    <table>
        <tr>
            <th>KPI</th>
            <th>Valeur</th>
        </tr>
        <tr class="kpi-row">
            <td>
                <input type="checkbox" id="total-revenue" {{ $recapData['totalRevenue'] ? 'checked' : '' }}>
                <label for="total-revenue">Total Revenue</label>
            </td>
            <td>{{ $recapData['totalRevenue'] }}</td>
        </tr>
        <tr class="kpi-row">
            <td>
                <input type="checkbox" id="total-orders" {{ $recapData['totalOrders'] ? 'checked' : '' }}>
                <label for="total-orders">Total Orders</label>
            </td>
            <td>{{ $recapData['totalOrders'] }}</td>
        </tr>
        <tr class="kpi-row">
            <td>
                <input type="checkbox" id="total-employees" {{ $recapData['totalEmployees'] ? 'checked' : '' }}>
                <label for="total-employees">Total Employees</label>
            </td>
            <td>{{ $recapData['totalEmployees'] }}</td>
        </tr>
        <tr class="kpi-row">
            <td>
                <input type="checkbox" id="average-sales" {{ $recapData['averageSales'] ? 'checked' : '' }}>
                <label for="average-sales">Average Sales</label>
            </td>
            <td>{{ $recapData['averageSales'] }}</td>
        </tr>
    </table>
</body>
</html>