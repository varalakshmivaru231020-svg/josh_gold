<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Data requests under the DPDP Act (see, correct or delete personal data, withdraw consent).
 * Each request is saved as a ContactMessage with the subject "[Data request] …",
 * so staff handle it in Admin → Enquiries alongside other messages.
 */
class PrivacyRequestController extends Controller
{
    public const TYPES = [
        'access'   => 'Access my data',
        'correct'  => 'Correct my data',
        'delete'   => 'Delete my data',
        'withdraw' => 'Withdraw consent',
        'other'    => 'Other',
    ];

    public function create()
    {
        return view('privacy-request', [
            'types' => self::TYPES,
            'email' => trim((string) SiteSetting::get('contact_email')),
            'phone' => trim((string) SiteSetting::get('contact_phone')),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|max:255',
            'phone'        => 'nullable|string|max:20',
            'request_type' => ['required', Rule::in(array_keys(self::TYPES))],
            'details'      => 'nullable|string|max:2000',
            'confirm'      => 'accepted',
        ], [
            'name.required'         => 'Please enter your name.',
            'email.required'        => 'Please enter your email address, so we can reply to you.',
            'email.email'           => 'Please enter a valid email address.',
            'phone.max'             => 'Please enter a phone number of 20 characters or fewer.',
            'request_type.required' => 'Please choose what you would like us to do.',
            'request_type.in'       => 'Please choose what you would like us to do.',
            'details.max'           => 'Please keep the details to 2,000 characters or fewer.',
            'confirm.accepted'      => 'Please confirm that you are the person this request is about, or authorised to act for them.',
        ]);

        $type = self::TYPES[$data['request_type']];
        $phone = trim((string) ($data['phone'] ?? ''));
        $details = trim((string) ($data['details'] ?? ''));

        // Written as sentences so it reads well even where line breaks are not shown
        $message = implode("\n\n", [
            "Data request: {$type}.",
            'Phone: ' . ($phone !== '' ? $phone : 'not given') . '.',
            'Details: ' . ($details !== '' ? $details : '(none given)'),
            'The person confirmed they are the person this request is about, or authorised to act for them. '
                . 'Verify their identity before acting on the request, and reply to the email address they gave.',
        ]);

        ContactMessage::create([
            'name'    => $data['name'],
            'email'   => $data['email'],
            'phone'   => $phone !== '' ? $phone : null,
            'subject' => '[Data request] ' . $type,
            'message' => $message,
        ]);

        return redirect()->route('privacy.request')
            ->with('success', 'Thank you. We have received your request and will reply by email. We may ask you to confirm your identity first.');
    }
}
