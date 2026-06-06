<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Recu {{ $transaction->reference }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: serif; font-size: 12px; color: #1a1a1a; line-height: 1.6; }

        .header { background-color: #7b2d8b; color: #ffffff; padding: 24px 32px; margin-bottom: 24px; }
        .header h1 { font-size: 22px; font-weight: bold; letter-spacing: 1px; }
        .header .subtitle { font-size: 11px; color: #e0b0f0; margin-top: 4px; }
        .header .ref { font-size: 11px; color: #e0b0f0; float: right; margin-top: -40px; }

        .recu-titre {
            text-align: center; font-size: 18px; font-weight: bold;
            color: #7b2d8b; border: 2px solid #7b2d8b;
            padding: 10px; margin-bottom: 24px; letter-spacing: 2px;
        }

        .section { margin-bottom: 20px; }
        .section-title {
            font-size: 11px; font-weight: bold; text-transform: uppercase;
            letter-spacing: 1px; color: #7b2d8b;
            border-bottom: 1px solid #7b2d8b;
            padding-bottom: 4px; margin-bottom: 12px;
        }

        .montant-principal-box {
            background-color: #7b2d8b; color: #ffffff;
            padding: 20px; text-align: center; margin-bottom: 24px;
        }
        .montant-chiffre { font-size: 28px; font-weight: bold; letter-spacing: 1px; }
        .montant-lettres { font-size: 11px; color: #e0b0f0; margin-top: 6px; font-style: italic; }
        .montant-type { font-size: 10px; color: #e0b0f0; text-transform: uppercase; margin-bottom: 8px; }

        .table-details { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table-details tr { border-bottom: 1px solid #f0f0f0; }
        .table-details tr:last-child { border-bottom: none; }
        .table-details td { padding: 8px 4px; font-size: 12px; }
        .table-details .td-label { color: #666; width: 40%; }
        .table-details .td-value { font-weight: bold; text-align: right; }

        .commissions-box { background-color: #f9f0fc; border: 1px solid #e8b4f8; padding: 14px 16px; margin-bottom: 20px; }
        .commissions-title { font-size: 11px; font-weight: bold; color: #7b2d8b; margin-bottom: 10px; }
        .comm-table { width: 100%; }
        .comm-table td { font-size: 11px; padding: 3px 0; }
        .comm-table .val { text-align: right; font-weight: bold; }
        .comm-total { border-top: 1px solid #c880e8; margin-top: 6px; padding-top: 6px; font-weight: bold; }

        .bien-resume {
            background-color: #f5f5f5; border: 1px solid #e0e0e0;
            padding: 12px 16px; margin-bottom: 20px;
        }
        .bien-titre-small { font-size: 13px; font-weight: bold; color: #333; }
        .bien-info { font-size: 11px; color: #666; margin-top: 4px; }

        .validite {
            border: 1px dashed #ccc; padding: 12px; margin-bottom: 20px;
            text-align: center; font-size: 11px; color: #555;
        }

        .cachet {
            width: 120px; height: 120px; border: 3px solid #7b2d8b;
            border-radius: 60px; text-align: center; padding: 20px 10px;
            float: right; margin-top: -80px; color: #7b2d8b;
        }
        .cachet-text { font-size: 9px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
        .cachet-valide { font-size: 14px; font-weight: bold; margin: 6px 0; color: #28a745; }

        .footer { margin-top: 24px; border-top: 1px solid #dde3ec; padding-top: 10px; text-align: center; font-size: 9px; color: #999; clear: both; }
        .clearfix::after { content: ""; display: table; clear: both; }
    </style>
</head>
<body>

    <div class="header clearfix">
        <div>
            <h1>KAAY DEUK</h1>
            <div class="subtitle">Plateforme Immobiliere Senegalaise</div>
        </div>
        <div class="ref">
            Recu N° {{ $transaction->reference }}<br>
            Date : {{ now()->format('d/m/Y') }}
        </div>
    </div>

    <div class="recu-titre">RECU DE {{ strtoupper($transaction->type->label()) }}</div>

    {{-- MONTANT PRINCIPAL --}}
    <div class="montant-principal-box">
        <div class="montant-type">Montant de la transaction</div>
        <div class="montant-chiffre">{{ number_format($transaction->montant, 0, ',', ' ') }} FCFA</div>
        <div class="montant-lettres">
            @php
                $montant = (int) $transaction->montant;
                // Conversion simplifiée pour l'affichage
                $millions = floor($montant / 1000000);
                $milliers = floor(($montant % 1000000) / 1000);
                $reste = $montant % 1000;
                $lettres = '';
                if ($millions > 0) $lettres .= $millions . ' million(s) ';
                if ($milliers > 0) $lettres .= $milliers . ' mille ';
                if ($reste > 0) $lettres .= $reste . ' ';
                $lettres .= 'francs CFA';
            @endphp
            {{ ucfirst(trim($lettres)) }}
        </div>
    </div>

    {{-- DETAILS TRANSACTION --}}
    <div class="section">
        <div class="section-title">Details de la Transaction</div>
        <table class="table-details">
            <tr>
                <td class="td-label">Reference</td>
                <td class="td-value">{{ $transaction->reference }}</td>
            </tr>
            <tr>
                <td class="td-label">Type d'operation</td>
                <td class="td-value">{{ $transaction->type->label() }}</td>
            </tr>
            <tr>
                <td class="td-label">Date de signature</td>
                <td class="td-value">{{ $transaction->date_signature ? $transaction->date_signature->format('d/m/Y') : 'N/A' }}</td>
            </tr>
            <tr>
                <td class="td-label">Client</td>
                <td class="td-value">{{ $transaction->client->prenom }} {{ $transaction->client->nom }}</td>
            </tr>
            <tr>
                <td class="td-label">Agent</td>
                <td class="td-value">{{ $transaction->agent->name }}</td>
            </tr>
            @if($transaction->date_debut_contrat)
            <tr>
                <td class="td-label">Debut du contrat</td>
                <td class="td-value">{{ $transaction->date_debut_contrat->format('d/m/Y') }}</td>
            </tr>
            @endif
            @if($transaction->date_fin_contrat)
            <tr>
                <td class="td-label">Fin du contrat</td>
                <td class="td-value">{{ $transaction->date_fin_contrat->format('d/m/Y') }}</td>
            </tr>
            @endif
        </table>
    </div>

    {{-- BIEN --}}
    <div class="section">
        <div class="section-title">Bien Concerne</div>
        <div class="bien-resume">
            <div class="bien-titre-small">{{ $transaction->bien->titre }}</div>
            <div class="bien-info">
                Ref : {{ $transaction->bien->reference }}
                &bull; {{ $transaction->bien->type_bien->label() }}
                &bull; {{ $transaction->bien->quartier }}, {{ $transaction->bien->ville }}
                &bull; {{ number_format($transaction->bien->surface, 0, ',', ' ') }} m2
            </div>
        </div>
    </div>

    {{-- COMMISSIONS --}}
    <div class="commissions-box">
        <div class="commissions-title">Repartition des Commissions</div>
        <table class="comm-table">
            <tr>
                <td>Montant brut de la transaction</td>
                <td class="val">{{ number_format($transaction->montant, 0, ',', ' ') }} FCFA</td>
            </tr>
            <tr>
                <td>Commission agence Kaay Deuk</td>
                <td class="val">{{ number_format($transaction->commission_agence, 0, ',', ' ') }} FCFA</td>
            </tr>
            <tr>
                <td>Commission agent {{ $transaction->agent->name }}</td>
                <td class="val">{{ number_format($transaction->commission_agent, 0, ',', ' ') }} FCFA</td>
            </tr>
            <tr class="comm-total">
                <td>Total commissions</td>
                <td class="val">{{ number_format($transaction->commission_agence + $transaction->commission_agent, 0, ',', ' ') }} FCFA</td>
            </tr>
        </table>
    </div>

    {{-- VALIDITE + CACHET --}}
    <div class="clearfix">
        <div class="validite">
            Ce recu est un document officiel emis par Kaay Deuk Immobilier.<br>
            Il atteste du paiement effectue dans le cadre de la transaction referencee ci-dessus.<br>
            <strong>Document genere electroniquement — valide sans signature manuscrite.</strong>
        </div>

        <div class="cachet">
            <div class="cachet-text">Kaay Deuk</div>
            <div class="cachet-valide">VALIDE</div>
            <div class="cachet-text">{{ now()->format('d/m/Y') }}</div>
        </div>
    </div>

    <div class="footer">
        Kaay Deuk Immobilier &bull; Dakar, Senegal &bull; Recu genere le {{ now()->format('d/m/Y a H:i') }}
        &bull; Ref : {{ $transaction->reference }}
    </div>

</body>
</html>