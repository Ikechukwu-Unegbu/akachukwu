<?php

namespace App\Http\Controllers\SystemUser;

use App\Models\Bank;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class BankController extends Controller
{
    protected CONST VENDORS = ['monnify', 'palmpay'];
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Bank::query();

        // Search by name
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('code', 'like', "%{$search}%");
        }

        // Filter by type
        if ($request->has('type') && !empty($request->type)) {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->has('status') && $request->status !== null) {
            $query->where('status', (bool) $request->status);
        }

        // Sorting
        $sortField = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_dir', 'desc');

        // Validate sort fields to prevent SQL injection
        $validSortFields = ['name', 'code', 'type', 'status', 'created_at'];
        $sortField = in_array($sortField, $validSortFields) ? $sortField : 'created_at';
        $sortDirection = in_array(strtolower($sortDirection), ['asc', 'desc']) ? $sortDirection : 'desc';

        $query->orderBy($sortField, $sortDirection);

        $banks = $query->paginate(20)->withQueryString();

        return view('system-user.banks.index', compact('banks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('system-user.banks.create', [
            'vendors' => self::VENDORS,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:banks',
            'type' => 'required|in:' . implode(',', self::VENDORS),
            'image' => 'nullable|string|max:512',
            'ussd_template' => 'nullable|string',
            'base_ussd_code' => 'nullable|string',
            'transfer_ussd_template' => 'nullable|string',
            'bank_id' => 'nullable|string',
            'nip_bank_code' => 'nullable|string',
            'status' => 'boolean',
        ]);
        $validated['status'] = $validated['status'] ?? true;
        Bank::create($validated);

        return redirect()->route('system-user.banks.index')->with('success', 'Bank created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Bank $bank)
    {
        return view('system-user.banks.show', compact('bank'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bank $bank)
    {
        $vendors  = self::VENDORS;
        return view('system-user.banks.edit', compact('bank', 'vendors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bank $bank)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:banks,code,' . $bank->id,
            'type' => 'required|in:monnify,other',
            'image' => 'nullable|string|max:512',
            'ussd_template' => 'nullable|string',
            'base_ussd_code' => 'nullable|string',
            'transfer_ussd_template' => 'nullable|string',
            'bank_id' => 'nullable|string',
            'nip_bank_code' => 'nullable|string',
            'status' => 'boolean',
        ]);
        $validated['status'] = !isset($validated['status']) ? false : true;

        $bank->update($validated);

        return redirect()->route('admin.bank.index')->with('success', 'Bank updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bank $bank)
    {
        $bank->delete();
        return redirect()->route('admin.bank.index')->with('success', 'Bank deleted successfully.');
    }

    public function showBankConfig()
    {
        $monnifyBanks = Bank::where('type', 'monnify')->get();

        $currentConfig = json_decode(SiteSetting::first()->bank_config, true) ?? [
            'default' => ['palm_pay' => true],
            'monnify' => []
        ];

        $enabledBanks = $currentConfig['monnify'] ?? [];

        return view('system-user.banks.settings', compact('monnifyBanks', 'enabledBanks'));
    }

    public function updateBankConfig(Request $request)
    {
        $validated = $request->validate([
            'enabled_monnify_banks' => 'required|array',
            'enabled_monnify_banks.*' => 'exists:banks,id',
        ]);

        $currentConfig = json_decode(SiteSetting::first()->bank_config, true) ?? [];

        $newConfig = [
            'default' => [
                'palm_pay' => $currentConfig['default']['palm_pay'] ?? true
            ],
            'monnify' => $validated['enabled_monnify_banks']
        ];

        SiteSetting::first()->update([
            'bank_config' => json_encode($newConfig)
        ]);

        return redirect()->back()->with('success', 'Bank configuration updated successfully');
    }

    public function getBankConfig()
    {
        $config = SiteSetting::first()->bank_config ?? json_encode([
            'default' => ['palm_pay' => true],
            'monnify' => []
        ]);

        return response()->json(json_decode($config, true));
    }
}
