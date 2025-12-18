<?php

namespace App\Http\Controllers;

use App\Enums\TaxRegime;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CompanyController extends Controller
{
    public function index()
    {
        $user = request()->user();
        $office = $user->office;

        $companies = $office
            ->companies()
            ->with('accountant')
            ->latest()
            ->paginate(10)
            ->through(function ($company) {
                return [
                    'id' => $company->id,
                    'name' => $company->name,
                    'cnpj' => $company->cnpj,
                    'tax_regime' => $company->tax_regime?->value,
                    'tax_regime_label' => $company->tax_regime?->label(),
                    'is_active' => $company->is_active,
                    'accountant_name' => optional($company->accountant)->name,
                ];
            });

        // Metrics for cards (use same scope as listing)
        $total = (int) $companies->total();
        $active = (int) $office->companies()->where('is_active', true)->count();
        $inactive = max(0, $total - $active);

        return Inertia::render('Companies/Index', [
            'companies' => $companies,
            'metrics' => [
                'total' => $total,
                'active' => $active,
                'inactive' => $inactive,
            ],
        ]);
    }

    public function importCsvTemplate(Request $request): StreamedResponse
    {
        if (! $request->user()?->office) {
            abort(403);
        }

        $filename = 'modelo_importacao_empresas.csv';

        return response()->streamDownload(function () {
            $out = fopen('php://output', 'w');

            // Help Excel (pt-BR) recognize UTF-8 and the semicolon delimiter.
            fwrite($out, "\xEF\xBB\xBF");
            fwrite($out, "sep=;\n");

            // Required fields are marked with * in the header.
            fputcsv($out, [
                'Razão Social*',
                'CNPJ*',
                'Regime Tributário*',
                'Nome Fantasia',
                'Telefone',
                'E-mail',
                'CEP',
                'Rua',
                'Número',
                'Cidade',
                'UF',
            ], ';');

            fputcsv($out, [
                'ACME LTDA',
                "'81635898000176",
                'simples_nacional',
                'ACME',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ], ';');

            fputcsv($out, [
                'Foo Bar ME',
                "'11222333000100",
                'lucro_presumido',
                '',
                '(11) 99999-9999',
                'contato@foobar.com',
                '01001-000',
                'Praça da Sé',
                '100',
                'São Paulo',
                'SP',
            ], ';');

            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function show($company_id)
    {
        $user = request()->user();
        $office = $user->office;

        $company = $office
            ->companies()
            ->with('accountant', 'contents')
            ->findOrFail($company_id);

        $company->setAttribute('accountant_name', optional($company->accountant)->name);

        $libraryContents = $office
            ->contents()
            ->with('uploader')
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Companies/Show', [
            'company' => $company,
            'libraryContents' => $libraryContents,
        ]);
    }

    public function create(): Response
    {
        $office = request()->user()->office;

        $accountants = $office->users()->get()->map(function (User $user) {
            return [
                'value' => $user->id,
                'label' => $user->name,
            ];
        });

        return Inertia::render('Companies/Create', [
            'accountants' => $accountants,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'cnpj' => 'required|string|max:255',
            'tax_regime' => 'required|in:'.TaxRegime::values(),

            'fantasy_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'street' => 'nullable|string|max:255',
            'number' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:255',
            'accountant_id' => 'nullable|exists:users,id',
        ]);

        $company = Company::create([
            'name' => $request->name,
            'fantasy_name' => $request->input('fantasy_name') ?: null,
            'cnpj' => $request->cnpj,
            'tax_regime' => $request->tax_regime,
            'phone' => $request->input('phone') ?: null,
            'email' => $request->input('email') ?: null,
            'street' => $request->input('street') ?: null,
            'number' => $request->input('number') ?: null,
            'city' => $request->input('city') ?: null,
            'state' => $request->input('state') ?: null,
            'zip_code' => $request->input('zip_code') ?: null,
            'is_active' => $request->boolean('is_active', true),
            'creator_id' => optional(request()->user())->id,
            'office_id' => optional(request()->user())->office_id,
            'accountant_id' => $request->input('accountant_id') ?: null,
        ]);

        return redirect()->route('companies.index')->with('success', 'Empresa criada com sucesso.');
    }

    public function importCsv(Request $request): RedirectResponse
    {
        $user = $request->user();
        $office = $user?->office;

        if (! $office) {
            abort(403);
        }

        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:5120'],
        ]);

        $file = $request->file('file');
        $path = $file->getRealPath();

        $handle = fopen($path, 'r');
        if (! $handle) {
            return back()->with('error', 'Não foi possível ler o arquivo CSV.');
        }

        // Read first line(s) and detect delimiter.
        $firstLine = fgets($handle);
        if ($firstLine === false) {
            fclose($handle);

            return back()->with('error', 'CSV inválido: arquivo vazio.');
        }

        // Strip UTF-8 BOM if present.
        $firstLine = preg_replace('/^\xEF\xBB\xBF/', '', $firstLine);
        $trimmedFirst = trim($firstLine);

        // Optional Excel hint: "sep=;" line
        if (strtolower($trimmedFirst) === 'sep=;') {
            $firstLine = fgets($handle);
            if ($firstLine === false) {
                fclose($handle);

                return back()->with('error', 'CSV inválido: cabeçalho não encontrado.');
            }
            $firstLine = preg_replace('/^\xEF\xBB\xBF/', '', $firstLine);
        }

        $delimiters = [',', ';', "\t"];
        $bestDelimiter = ',';
        $bestCount = -1;
        foreach ($delimiters as $d) {
            $count = substr_count($firstLine, $d);
            if ($count > $bestCount) {
                $bestCount = $count;
                $bestDelimiter = $d;
            }
        }

        $header = str_getcsv($firstLine, $bestDelimiter);
        if (! is_array($header) || count($header) === 0) {
            fclose($handle);

            return back()->with('error', 'CSV inválido: cabeçalho não encontrado.');
        }

        $normalize = function ($v) {
            $key = trim((string) $v);
            // Template marks required columns with "*". Strip it so "Razão Social*" matches "Razão Social".
            $key = preg_replace('/\*+$/', '', $key);
            $key = trim($key);

            $key = Str::ascii($key);
            $key = strtolower($key);
            $key = preg_replace('/[^a-z0-9]+/', '_', $key);
            $key = trim($key, '_');

            return $key;
        };

        $aliases = [
            'name' => ['name', 'razao_social', 'razao', 'nome', 'nome_razao'],
            'cnpj' => ['cnpj'],
            'tax_regime' => ['tax_regime', 'regime_tributario', 'regime'],
            'fantasy_name' => ['fantasy_name', 'nome_fantasia', 'fantasia'],
            'phone' => ['phone', 'telefone'],
            'email' => ['email', 'e_mail'],
            'zip_code' => ['zip_code', 'cep'],
            'street' => ['street', 'rua'],
            'number' => ['number', 'numero'],
            'city' => ['city', 'cidade'],
            'state' => ['state', 'uf', 'estado'],
        ];

        $aliasToCanonical = [];
        foreach ($aliases as $canonical => $keys) {
            foreach ($keys as $k) {
                $aliasToCanonical[$k] = $canonical;
            }
        }
        $headerMap = [];
        foreach ($header as $idx => $col) {
            $key = $normalize($col);
            if ($key !== '') {
                $canonical = $aliasToCanonical[$key] ?? $key;
                $headerMap[$canonical] = $idx;
            }
        }

        $requiredCols = ['name', 'cnpj', 'tax_regime'];
        $missing = array_values(array_filter($requiredCols, fn ($c) => ! array_key_exists($c, $headerMap)));
        if (! empty($missing)) {
            fclose($handle);

            return back()->with('error', 'CSV inválido: colunas obrigatórias ausentes: '.implode(', ', $missing));
        }

        $created = 0;
        $updated = 0;
        $skipped = 0;
        $errors = [];

        // If there was a "sep" line, header is line 2. For user feedback, keep count aligned with the file.
        $line = 1;
        if (strtolower($trimmedFirst) === 'sep=;') {
            $line = 2;
        }

        DB::beginTransaction();
        try {
            while (($raw = fgets($handle)) !== false) {
                $line++;

                $row = str_getcsv($raw, $bestDelimiter);

                if (! is_array($row) || count(array_filter($row, fn ($v) => trim((string) $v) !== '')) === 0) {
                    $skipped++;

                    continue;
                }

                $get = function (string $key) use ($headerMap, $row, $normalize) {
                    $idx = $headerMap[$normalize($key)] ?? null;
                    if ($idx === null) {
                        return null;
                    }

                    return isset($row[$idx]) ? trim((string) $row[$idx]) : null;
                };

                $name = $get('name');
                $cnpjRaw = $get('cnpj');
                $taxRegime = $get('tax_regime');

                $cnpjDigits = '';
                $cnpjRawStr = trim((string) ($cnpjRaw ?? ''));

                // Excel may export long numbers as scientific notation (e.g. 8,16359E+13).
                if ($cnpjRawStr !== '' && preg_match('/[eE]/', $cnpjRawStr)) {
                    // Accept comma as decimal separator.
                    $normalizedSci = str_replace(',', '.', $cnpjRawStr);

                    if (preg_match('/^([+-]?[0-9]+(?:\.[0-9]+)?)\s*[eE]\s*([+-]?[0-9]+)$/', $normalizedSci, $m)) {
                        $mantissa = $m[1];
                        $exp = (int) $m[2];

                        // Detect loss of precision: if mantissa has fewer than 14 significant digits, Excel likely rounded.
                        $mantissaSig = preg_replace('/\D/', '', $mantissa);
                        $mantissaSig = ltrim($mantissaSig, '0');
                        if (strlen($mantissaSig) < 14) {
                            $errors[] = [
                                'line' => $line,
                                'field' => 'cnpj',
                                'message' => 'CNPJ exportado em notação científica (ex.: 8,16359E+13) e perdeu precisão. Formate a coluna CNPJ como TEXTO antes de salvar o CSV.',
                            ];

                            continue;
                        }

                        // Expand scientific notation to a digit string (integer).
                        // We only need integer expansion; CNPJ should be 14 digits.
                        $parts = explode('.', $mantissa, 2);
                        $intPart = $parts[0] ?? '0';
                        $fracPart = $parts[1] ?? '';
                        $digits = preg_replace('/\D/', '', $intPart.$fracPart);
                        $decimals = strlen($fracPart);

                        $shift = $exp - $decimals;
                        if ($shift >= 0) {
                            $cnpjDigits = $digits.str_repeat('0', $shift);
                        } else {
                            // Would result in a decimal number; not valid for CNPJ.
                            $cnpjDigits = '';
                        }
                    }
                }

                if ($cnpjDigits === '') {
                    $cnpjDigits = preg_replace('/\D/', '', $cnpjRawStr);
                }

                if ($name === null || $name === '') {
                    $errors[] = ['line' => $line, 'field' => 'name', 'message' => 'Nome é obrigatório.'];

                    continue;
                }

                if ($cnpjDigits === '') {
                    $errors[] = ['line' => $line, 'field' => 'cnpj', 'message' => 'CNPJ é obrigatório.'];

                    continue;
                }

                if (strlen($cnpjDigits) !== 14) {
                    $errors[] = ['line' => $line, 'field' => 'cnpj', 'message' => 'CNPJ deve conter 14 dígitos (apenas números).'];

                    continue;
                }

                $validTaxRegimes = explode(',', TaxRegime::values());
                if (! in_array($taxRegime, $validTaxRegimes, true)) {
                    $errors[] = ['line' => $line, 'field' => 'tax_regime', 'message' => 'Regime tributário inválido.'];

                    continue;
                }

                $payload = [
                    'name' => $name,
                    'cnpj' => $cnpjDigits,
                    'tax_regime' => $taxRegime,
                    'fantasy_name' => ($get('fantasy_name') ?: null),
                    'phone' => ($get('phone') ?: null),
                    'email' => ($get('email') ?: null),
                    'zip_code' => ($get('zip_code') ?: null),
                    'street' => ($get('street') ?: null),
                    'number' => ($get('number') ?: null),
                    'city' => ($get('city') ?: null),
                    'state' => ($get('state') ?: null),
                    'creator_id' => $user?->id,
                    'office_id' => $office->id,
                ];

                $existing = $office->companies()->where('cnpj', $cnpjDigits)->first();

                if ($existing) {
                    $existing->fill($payload);
                    $existing->save();
                    $updated++;
                } else {
                    Company::create(array_merge($payload, [
                        'is_active' => true,
                        'accountant_id' => null,
                    ]));
                    $created++;
                }
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            fclose($handle);
            throw $e;
        }

        fclose($handle);

        $summary = [
            'created' => $created,
            'updated' => $updated,
            'skipped' => $skipped,
            'errors_count' => count($errors),
            'errors' => array_slice($errors, 0, 50),
        ];

        if (count($errors) > 0) {
            return redirect()->route('companies.index')
                ->with('importSummary', $summary)
                ->with('error', 'Importação concluída com erros.');
        }

        return redirect()->route('companies.index')
            ->with('importSummary', $summary)
            ->with('success', 'Importação concluída com sucesso.');
    }

    public function destroy(Company $company): RedirectResponse
    {
        $user = request()->user();

        if ($company->office_id !== optional($user)->office_id) {
            abort(403);
        }

        $company->delete();

        return redirect()->route('companies.index')->with('success', 'Empresa excluída com sucesso.');
    }
}
