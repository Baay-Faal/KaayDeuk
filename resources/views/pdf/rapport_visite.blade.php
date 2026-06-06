<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Rapport de visite</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: serif; font-size: 12px; color: #1a1a1a; line-height: 1.6; }

        .header { background-color: #2d6a4f; color: #ffffff; padding: 24px 32px; margin-bottom: 24px; }
        .header h1 { font-size: 22px; font-weight: bold; letter-spacing: 1px; }
        .header .subtitle { font-size: 11px; color: #95d5b2; margin-top: 4px; }
        .header .ref { font-size: 11px; color: #95d5b2; float: right; margin-top: -40px; }

        .statut-badge {
            padding: 8px 16px;
            margin-bottom: 20px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
        }
        .statut-realisee { background-color: #d4edda; color: #155724; border-left: 4px solid #28a745; }
        .statut-planifiee { background-color: #fff3cd; color: #856404; border-left: 4px solid #ffc107; }
        .statut-annulee   { background-color: #f8d7da; color: #721c24; border-left: 4px solid #dc3545; }

        .section { margin-bottom: 20px; }
        .section-title {
            font-size: 11px; font-weight: bold; text-transform: uppercase;
            letter-spacing: 1px; color: #2d6a4f; border-bottom: 1px solid #2d6a4f;
            padding-bottom: 4px; margin-bottom: 12px;
        }

        .info-grid { width: 100%; }
        .info-grid td { width: 50%; vertical-align: top; padding-right: 16px; }

        .field { margin-bottom: 8px; }
        .field-label { font-size: 10px; color: #666; text-transform: uppercase; letter-spacing: 0.5px; }
        .field-value { font-size: 12px; font-weight: bold; color: #1a1a1a; }

        .bien-box { background-color: #f0faf4; border: 1px solid #c3e6cb; padding: 14px 16px; margin-bottom: 20px; }
        .bien-titre { font-size: 14px; font-weight: bold; color: #2d6a4f; margin-bottom: 4px; }
        .bien-ref { font-size: 10px; color: #888; margin-bottom: 10px; }
        .bien-details td { font-size: 11px; padding: 2px 8px 2px 0; width: 25%; }

        .note-box { background-color: #2d6a4f; color: #ffffff; padding: 16px 20px; margin-bottom: 20px; text-align: center; }
        .note-chiffre { font-size: 36px; font-weight: bold; }
        .note-max { font-size: 14px; color: #95d5b2; }
        .note-label { font-size: 10px; color: #95d5b2; text-transform: uppercase; margin-top: 4px; }
        .etoiles { font-size: 18px; margin-top: 4px; color: #ffd700; }

        .rapport-box {
            background-color: #f9f9f9; border: 1px solid #e0e0e0;
            padding: 16px; min-height: 80px; margin-bottom: 20px;
            font-size: 12px; color: #333; line-height: 1.8;
        }
        .rapport-vide { color: #999; font-style: italic; }

        .commentaire-box {
            background-color: #fff8e1; border: 1px solid #ffe082;
            border-left: 4px solid #ffc107;
            padding: 14px 16px; margin-bottom: 20px;
            font-size: 12px; color: #555;
        }

        .footer { margin-top: 24px; border-top: 1px solid #dde3ec; padding-top: 10px; text-align: center; font-size: 9px; color: #999; }
        .clearfix::after { content: ""; display: table; clear: both; }
        .mt-8 { margin-top: 8px; }
    </style>
</head>
<body>

    <div class="header clearfix">
        <div>
            <h1>KAAY DEUK</h1>
            <div class="subtitle">Rapport de Visite Immobiliere</div>
        </div>
        <div class="ref">
            Visite N° {{ $visite->id }}<br>
            Genere le {{ now()->format('d/m/Y') }}
        </div>
    </div>

    {{-- STATUT --}}
    @php
        $statutClass = match($visite->statut->value) {
            'realisee'  => 'statut-realisee',
            'planifiee' => 'statut-planifiee',
            'annulee'   => 'statut-annulee',
            default     => 'statut-planifiee',
        };
    @endphp
    <div class="statut-badge {{ $statutClass }}">
        VISITE {{ strtoupper($visite->statut->label()) }}
    </div>

    {{-- BIEN --}}
    <div class="section">
        <div class="section-title">Bien Visite</div>
        <div class="bien-box">
            <div class="bien-titre">{{ $visite->bien->titre }}</div>
            <div class="bien-ref">Reference : {{ $visite->bien->reference }}</div>
            <table class="bien-details">
                <tr>
                    <td>
                        <div class="field-label">Type</div>
                        <div class="field-value">{{ $visite->bien->type_bien->label() }}</div>
                    </td>
                    <td>
                        <div class="field-label">Surface</div>
                        <div class="field-value">{{ number_format($visite->bien->surface, 0, ',', ' ') }} m2</div>
                    </td>
                    <td>
                        <div class="field-label">Quartier</div>
                        <div class="field-value">{{ $visite->bien->quartier }}</div>
                    </td>
                    <td>
                        <div class="field-label">Prix</div>
                        <div class="field-value">{{ number_format($visite->bien->prix, 0, ',', ' ') }} FCFA</div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    {{-- INFORMATIONS --}}
    <div class="section">
        <div class="section-title">Informations de la Visite</div>
        <table class="info-grid">
            <tr>
                <td>
                    <div class="field">
                        <div class="field-label">Date de la visite</div>
                        <div class="field-value">{{ $visite->date_visite->format('d/m/Y') }}</div>
                    </div>
                    <div class="field">
                        <div class="field-label">Heure</div>
                        <div class="field-value">{{ $visite->heure_visite?->format('H:i') ?? 'N/A' }}</div>
                    </div>
                </td>
                <td>
                    <div class="field">
                        <div class="field-label">Client</div>
                        <div class="field-value">{{ $visite->client->prenom }} {{ $visite->client->nom }}</div>
                    </div>
                    <div class="field">
                        <div class="field-label">Telephone</div>
                        <div class="field-value">{{ $visite->client->telephone ?? 'N/A' }}</div>
                    </div>
                    <div class="field">
                        <div class="field-label">Agent</div>
                        <div class="field-value">{{ $visite->agent->name }}</div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    {{-- NOTE CLIENT --}}
    @if($visite->note_client)
    <div class="section">
        <div class="section-title">Evaluation du Client</div>
        <div class="note-box">
            <div class="note-label">Note attribuee par le client</div>
            <div class="note-chiffre">
                {{ $visite->note_client }}<span class="note-max">/5</span>
            </div>
            <div class="etoiles">
                @for($i = 1; $i <= 5; $i++)
                    {{ $i <= $visite->note_client ? '★' : '☆' }}
                @endfor
            </div>
        </div>
    </div>
    @endif

    {{-- RAPPORT AGENT --}}
    <div class="section">
        <div class="section-title">Rapport de l'Agent</div>
        <div class="rapport-box">
            @if($visite->rapport)
                {{ $visite->rapport }}
            @else
                <span class="rapport-vide">Aucun rapport redige pour cette visite.</span>
            @endif
        </div>
    </div>

    {{-- COMMENTAIRE CLIENT --}}
    @if($visite->commentaire_client)
    <div class="section">
        <div class="section-title">Commentaire du Client</div>
        <div class="commentaire-box">
            "{{ $visite->commentaire_client }}"
        </div>
    </div>
    @endif

    {{-- NOTES INTERNES --}}
    @if($visite->notes)
    <div class="section">
        <div class="section-title">Notes Internes</div>
        <div class="rapport-box">{{ $visite->notes }}</div>
    </div>
    @endif

    <div class="footer">
        Kaay Deuk Immobilier &bull; Dakar, Senegal &bull; Rapport genere le {{ now()->format('d/m/Y a H:i') }}
        &bull; Visite N° {{ $visite->id }}
    </div>

</body>
</html>