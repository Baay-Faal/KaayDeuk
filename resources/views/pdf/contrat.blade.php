<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Contrat {{ $transaction->reference }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: serif; font-size: 12px; color: #1a1a1a; line-height: 1.6; }

        /* En-tête */
        .header { background-color: #1a3c5e; color: #ffffff; padding: 24px 32px; margin-bottom: 24px; }
        .header-top { display: block; margin-bottom: 8px; }
        .header h1 { font-size: 22px; font-weight: bold; letter-spacing: 1px; }
        .header .subtitle { font-size: 11px; color: #a8c4e0; margin-top: 4px; }
        .header .ref { font-size: 11px; color: #a8c4e0; text-align: right; float: right; margin-top: -40px; }

        /* Bandeau type contrat */
        .type-badge {
            background-color: #e8f0fb;
            border-left: 4px solid #1a3c5e;
            padding: 10px 20px;
            margin-bottom: 20px;
            font-size: 13px;
            font-weight: bold;
            color: #1a3c5e;
        }

        /* Sections */
        .section { margin-bottom: 20px; }
        .section-title {
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #1a3c5e;
            border-bottom: 1px solid #1a3c5e;
            padding-bottom: 4px;
            margin-bottom: 12px;
        }

        /* Grille 2 colonnes */
        .grid-2 { width: 100%; }
        .grid-2 td { width: 50%; vertical-align: top; padding-right: 16px; }

        /* Champs */
        .field { margin-bottom: 8px; }
        .field-label { font-size: 10px; color: #666; text-transform: uppercase; letter-spacing: 0.5px; }
        .field-value { font-size: 12px; font-weight: bold; color: #1a1a1a; }

        /* Bien */
        .bien-box {
            background-color: #f5f7fa;
            border: 1px solid #dde3ec;
            padding: 14px 16px;
            margin-bottom: 20px;
        }
        .bien-titre { font-size: 14px; font-weight: bold; color: #1a3c5e; margin-bottom: 4px; }
        .bien-ref { font-size: 10px; color: #888; margin-bottom: 10px; }
        .bien-details { width: 100%; }
        .bien-details td { font-size: 11px; padding: 2px 8px 2px 0; width: 25%; }

        /* Montants */
        .montants-box { background-color: #1a3c5e; color: #ffffff; padding: 16px 20px; margin-bottom: 20px; }
        .montant-principal { font-size: 20px; font-weight: bold; margin-bottom: 4px; }
        .montant-label { font-size: 10px; color: #a8c4e0; text-transform: uppercase; }
        .commissions { width: 100%; margin-top: 12px; border-top: 1px solid #2d5a8e; padding-top: 10px; }
        .commissions td { font-size: 11px; color: #c8d8ec; padding: 2px 0; }
        .commissions .val { text-align: right; font-weight: bold; color: #ffffff; }

        /* Dates */
        .dates-grid { width: 100%; }
        .dates-grid td { width: 33%; vertical-align: top; }

        /* Clauses */
        .clause { margin-bottom: 10px; padding-left: 12px; border-left: 2px solid #dde3ec; }
        .clause-num { font-size: 10px; font-weight: bold; color: #1a3c5e; text-transform: uppercase; }
        .clause-text { font-size: 11px; color: #444; margin-top: 2px; }

        /* Signatures */
        .signatures { width: 100%; margin-top: 32px; }
        .signatures td { width: 33%; vertical-align: top; text-align: center; }
        .sig-box { border-top: 1px solid #333; margin: 0 12px; padding-top: 8px; }
        .sig-label { font-size: 10px; color: #666; text-transform: uppercase; letter-spacing: 0.5px; }
        .sig-name { font-size: 11px; font-weight: bold; margin-top: 4px; }
        .sig-area { height: 50px; }

        /* Pied de page */
        .footer { margin-top: 24px; border-top: 1px solid #dde3ec; padding-top: 10px; text-align: center; font-size: 9px; color: #999; }

        .clearfix::after { content: ""; display: table; clear: both; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .mt-4 { margin-top: 4px; }
        .mt-8 { margin-top: 8px; }
    </style>
</head>
<body>

    {{-- EN-TÊTE --}}
    <div class="header clearfix">
        <div>
            <h1>KAAY DEUK</h1>
            <div class="subtitle">Plateforme Immobiliere Senegalaise &bull; Dakar, Senegal</div>
        </div>
        <div class="ref">
            Ref : {{ $transaction->reference }}<br>
            Date : {{ now()->format('d/m/Y') }}
        </div>
    </div>

    {{-- TYPE DE CONTRAT --}}
    <div class="type-badge">
        CONTRAT DE {{ strtoupper($transaction->type->label()) }}
        &nbsp;&mdash;&nbsp;
        {{ $transaction->bien->titre ?? 'Bien immobilier' }}
    </div>

    {{-- BIEN --}}
    <div class="section">
        <div class="section-title">Bien Immobilier Concerne</div>
        <div class="bien-box">
            <div class="bien-titre">{{ $transaction->bien->titre }}</div>
            <div class="bien-ref">Reference : {{ $transaction->bien->reference }}</div>
            <table class="bien-details">
                <tr>
                    <td>
                        <div class="field-label">Type</div>
                        <div class="field-value">{{ $transaction->bien->type_bien->label() }}</div>
                    </td>
                    <td>
                        <div class="field-label">Surface</div>
                        <div class="field-value">{{ number_format($transaction->bien->surface, 0, ',', ' ') }} m2</div>
                    </td>
                    <td>
                        <div class="field-label">Quartier</div>
                        <div class="field-value">{{ $transaction->bien->quartier }}</div>
                    </td>
                    <td>
                        <div class="field-label">Ville</div>
                        <div class="field-value">{{ $transaction->bien->ville }}</div>
                    </td>
                </tr>
            </table>
            @if($transaction->bien->adresse)
            <div class="mt-8">
                <span class="field-label">Adresse : </span>
                <span>{{ $transaction->bien->adresse }}</span>
            </div>
            @endif
        </div>
    </div>

    {{-- PARTIES --}}
    <div class="section">
        <div class="section-title">Parties du Contrat</div>
        <table class="grid-2">
            <tr>
                <td>
                    <div class="field-label">Acheteur / Locataire</div>
                    <div class="field-value">{{ $transaction->client->prenom }} {{ $transaction->client->nom }}</div>
                    <div class="mt-4">
                        <span class="field-label">Tel : </span>{{ $transaction->client->telephone ?? 'N/A' }}
                    </div>
                    <div>
                        <span class="field-label">Email : </span>{{ $transaction->client->email ?? 'N/A' }}
                    </div>
                    @if($transaction->client->adresse)
                    <div>
                        <span class="field-label">Adresse : </span>{{ $transaction->client->adresse }}
                    </div>
                    @endif
                </td>
                <td>
                    <div class="field-label">Agent Responsable</div>
                    <div class="field-value">{{ $transaction->agent->name }}</div>
                    <div class="mt-4">
                        <span class="field-label">Email : </span>{{ $transaction->agent->email }}
                    </div>
                    <div>
                        <span class="field-label">Agence : </span>Kaay Deuk Immobilier
                    </div>
                </td>
            </tr>
        </table>
    </div>

    {{-- MONTANTS --}}
    <div class="montants-box">
        <div class="montant-label">
            @if($transaction->type->value === 'location') Loyer Mensuel @else Prix de Vente @endif
        </div>
        <div class="montant-principal">
            {{ number_format($transaction->montant, 0, ',', ' ') }} FCFA
        </div>
        <table class="commissions">
            <tr>
                <td>Commission agence ({{ round(($transaction->commission_agence / $transaction->montant) * 100, 1) }}%)</td>
                <td class="val">{{ number_format($transaction->commission_agence, 0, ',', ' ') }} FCFA</td>
            </tr>
            <tr>
                <td>Commission agent ({{ round(($transaction->commission_agent / $transaction->montant) * 100, 1) }}%)</td>
                <td class="val">{{ number_format($transaction->commission_agent, 0, ',', ' ') }} FCFA</td>
            </tr>
        </table>
    </div>

    {{-- DATES --}}
    <div class="section">
        <div class="section-title">Dates du Contrat</div>
        <table class="dates-grid">
            <tr>
                <td>
                    <div class="field-label">Date de signature</div>
                    <div class="field-value">
                        {{ $transaction->date_signature ? $transaction->date_signature->format('d/m/Y') : 'N/A' }}
                    </div>
                </td>
                <td>
                    <div class="field-label">Debut du contrat</div>
                    <div class="field-value">
                        {{ $transaction->date_debut_contrat ? $transaction->date_debut_contrat->format('d/m/Y') : 'N/A' }}
                    </div>
                </td>
                <td>
                    <div class="field-label">Fin du contrat</div>
                    <div class="field-value">
                        {{ $transaction->date_fin_contrat ? $transaction->date_fin_contrat->format('d/m/Y') : 'Indeterminee' }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    {{-- CLAUSES --}}
    <div class="section">
        <div class="section-title">Clauses et Conditions</div>

        <div class="clause">
            <div class="clause-num">Article 1 — Objet du contrat</div>
            <div class="clause-text">
                Le present contrat a pour objet la
                {{ $transaction->type->value === 'location' ? 'location' : 'vente' }}
                du bien immobilier designe ci-dessus, entre les parties mentionnees, aux conditions financieres stipulees.
            </div>
        </div>

        <div class="clause">
            <div class="clause-num">Article 2 — Prix et modalites de paiement</div>
            <div class="clause-text">
                Le montant de la transaction est fixe a
                <strong>{{ number_format($transaction->montant, 0, ',', ' ') }} FCFA</strong>
                @if($transaction->type->value === 'location')
                    par mois, payable le premier de chaque mois.
                @else
                    , payable selon les modalites convenues entre les parties.
                @endif
            </div>
        </div>

        <div class="clause">
            <div class="clause-num">Article 3 — Obligations des parties</div>
            <div class="clause-text">
                Chacune des parties s'engage a respecter les termes du present contrat.
                @if($transaction->type->value === 'location')
                    Le locataire s'engage a maintenir le bien en bon etat et a payer le loyer a l'echeance.
                    Le bailleur s'engage a assurer la jouissance paisible du bien.
                @else
                    Le vendeur s'engage a transferer la propriete du bien libre de toute charge.
                    L'acheteur s'engage a regler le montant convenu dans les delais fixes.
                @endif
            </div>
        </div>

        <div class="clause">
            <div class="clause-num">Article 4 — Commission de l'agence</div>
            <div class="clause-text">
                Les honoraires de l'agence Kaay Deuk Immobilier s'elevent a
                <strong>{{ number_format($transaction->commission_agence, 0, ',', ' ') }} FCFA</strong>,
                conformement au mandat signe avec l'agence.
            </div>
        </div>

        @if($transaction->notes)
        <div class="clause">
            <div class="clause-num">Article 5 — Notes complementaires</div>
            <div class="clause-text">{{ $transaction->notes }}</div>
        </div>
        @endif
    </div>

    {{-- SIGNATURES --}}
    <table class="signatures">
        <tr>
            <td>
                <div class="sig-area"></div>
                <div class="sig-box">
                    <div class="sig-label">Le Client</div>
                    <div class="sig-name">{{ $transaction->client->prenom }} {{ $transaction->client->nom }}</div>
                </div>
            </td>
            <td>
                <div class="sig-area"></div>
                <div class="sig-box">
                    <div class="sig-label">L'Agent</div>
                    <div class="sig-name">{{ $transaction->agent->name }}</div>
                </div>
            </td>
            <td>
                <div class="sig-area"></div>
                <div class="sig-box">
                    <div class="sig-label">Kaay Deuk Immobilier</div>
                    <div class="sig-name">Directeur General</div>
                </div>
            </td>
        </tr>
    </table>

    {{-- PIED DE PAGE --}}
    <div class="footer">
        Kaay Deuk Immobilier &bull; Dakar, Senegal &bull; Document genere le {{ now()->format('d/m/Y a H:i') }}
        &bull; Ref : {{ $transaction->reference }}
    </div>

</body>
</html>