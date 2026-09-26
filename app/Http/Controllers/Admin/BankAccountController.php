<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BankAccountController extends Controller
{
    public function index()
    {
        $accounts = BankAccount::ordered()->paginate(10);
        return view('admin.bank-accounts.index', compact('accounts'));
    }

    public function create()
    {
        return view('admin.bank-accounts.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());
        $data['is_active'] = $request->has('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        if ($request->hasFile('qr_image')) {
            $data['qr_image'] = $request->file('qr_image')->store('bank-accounts', 'public');
        }

        BankAccount::create($data);

        return redirect()->route('admin.bank-accounts.index')
            ->with('success', 'Bank account added successfully.');
    }

    public function edit(BankAccount $bank_account)
    {
        return view('admin.bank-accounts.edit', ['account' => $bank_account]);
    }

    public function update(Request $request, BankAccount $bank_account)
    {
        $data = $request->validate($this->rules());
        $data['is_active'] = $request->has('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        if ($request->hasFile('qr_image')) {
            if ($bank_account->qr_image) {
                Storage::disk('public')->delete($bank_account->qr_image);
            }
            $data['qr_image'] = $request->file('qr_image')->store('bank-accounts', 'public');
        } elseif ($request->boolean('remove_qr_image') && $bank_account->qr_image) {
            Storage::disk('public')->delete($bank_account->qr_image);
            $data['qr_image'] = null;
        }

        $bank_account->update($data);

        return redirect()->route('admin.bank-accounts.index')
            ->with('success', 'Bank account updated successfully.');
    }

    public function destroy(BankAccount $bank_account)
    {
        if ($bank_account->qr_image) {
            Storage::disk('public')->delete($bank_account->qr_image);
        }
        $bank_account->delete();

        return redirect()->route('admin.bank-accounts.index')
            ->with('success', 'Bank account deleted successfully.');
    }

    private function rules(): array
    {
        return [
            'bank_name'      => 'required|string|max:255',
            'account_holder' => 'required|string|max:255',
            'account_number' => 'required|string|max:50',
            'ifsc_code'      => 'required|string|max:20',
            'branch'         => 'nullable|string|max:255',
            'account_type'   => 'nullable|string|max:50',
            'upi_id'         => 'nullable|string|max:255',
            'qr_image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'sort_order'     => 'nullable|integer',
        ];
    }
}
