<?php

use App\Models\SiteSetting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/*
 * Adds the four legal pages (Privacy Policy, Terms of Service, Refund & Cancellation Policy,
 * Cookie Policy) as CMS pages, so they can be edited in Admin → Pages and are linked from the
 * footer (show_in_footer). A page is only added when its slug does not exist yet, so nothing
 * an admin has written is overwritten.
 *
 * Every detail the owner still has to confirm is wrapped in <mark class="tbc">[To confirm: …]</mark>,
 * which pages/show.blade.php highlights in amber. Fill these in from Admin → Pages.
 *
 * Note: Admin → Pages rebuilds the slug from the title when a page is saved. The titles below
 * give exactly these slugs; renaming a page in admin changes its address.
 */
return new class extends Migration
{
    private const SLUGS = ['privacy-policy', 'terms-of-service', 'refund-cancellation-policy', 'cookie-policy'];

    public function up(): void
    {
        $vars = $this->variables();
        $now = now();

        $pages = [
            ['Privacy Policy', 'privacy-policy', $this->privacyPolicy()],
            ['Terms of Service', 'terms-of-service', $this->termsOfService()],
            ['Refund & Cancellation Policy', 'refund-cancellation-policy', $this->refundPolicy()],
            ['Cookie Policy', 'cookie-policy', $this->cookiePolicy()],
        ];

        foreach ($pages as [$title, $slug, $html]) {
            if (DB::table('pages')->where('slug', $slug)->exists()) {
                continue;
            }

            DB::table('pages')->insert([
                'title'          => $title,
                'slug'           => $slug,
                'content'        => strtr(trim($html), $vars),
                'banner_image'   => null,
                'banner_title'   => null,
                'is_published'   => true,
                'show_in_footer' => true,
                'created_at'     => $now,
                'updated_at'     => $now,
            ]);
        }
    }

    public function down(): void
    {
        // Only removes pages nobody has edited since they were added
        DB::table('pages')->whereIn('slug', self::SLUGS)->whereColumn('updated_at', 'created_at')->delete();
    }

    /** Company and contact details, taken from config and Site Settings at the time of migrating. */
    private function variables(): array
    {
        $tbc = fn (string $what) => '<mark class="tbc">[To confirm: ' . e($what) . ']</mark>';

        $lines = preg_split('/\R/', (string) config('company.billing_address'));
        $address = implode(', ', array_filter(array_map(fn ($l) => trim($l, " \t,"), $lines)));

        $email = trim((string) SiteSetting::get('contact_email'));
        $phone = trim((string) SiteSetting::get('contact_phone'));
        $hours = trim((string) SiteSetting::get('contact_working_hours'));

        // Numbers the business no longer uses must never be published
        $phoneDigits = preg_replace('/\D+/', '', $phone);
        if ($phone === '' || str_contains($phoneDigits, '8792136049') || str_contains($phoneDigits, '9632527961')) {
            $phone = '';
        }

        $minutes = (int) config('session.lifetime', 120);
        $sessionDuration = config('session.expire_on_close')
            ? 'Session: deleted when you close your browser'
            : 'Session: expires ' . ($minutes % 60 === 0
                ? ($minutes / 60) . ' ' . ($minutes === 60 ? 'hour' : 'hours')
                : $minutes . ' minutes') . ' after your last page view';

        return [
            '{company}'          => e((string) config('company.name')),
            '{address}'          => $address !== '' ? e($address) : $tbc('business address'),
            '{gstin}'            => filled(config('company.gstin')) ? e((string) config('company.gstin')) : $tbc('GSTIN'),
            '{email}'            => $email !== '' ? '<a href="mailto:' . e($email) . '">' . e($email) . '</a>' : $tbc('contact email'),
            '{phone}'            => $phone !== ''
                ? '<a href="tel:' . e(preg_replace('/[^\d+]/', '', $phone)) . '">' . e($phone) . '</a>' . ($hours !== '' ? ' (' . e($hours) . ')' : '')
                : 'see our <a href="/contact-us">Contact page</a>',
            '{session_cookie}'   => e((string) config('session.cookie')),
            '{session_duration}' => e($sessionDuration),
            '{updated}'          => '13 October 2026',
        ];
    }

    private function privacyPolicy(): string
    {
        return <<<'HTML'
<p class="policy-updated">Last updated: {updated}</p>
<p class="policy-lead">This Privacy Policy explains what personal data {company} collects through this website and when you contact us, why we collect it, who we share it with, how long we keep it and what rights you have. This website collects very little, so we have kept this policy short and in plain English.</p>

<div class="policy-toc">
<p class="policy-toc__title">On this page</p>
<ol>
<li><a href="#who-we-are">Who we are</a></li>
<li><a href="#what-we-collect">What we collect and why</a></li>
<li><a href="#what-we-do-not-collect">What we do not collect</a></li>
<li><a href="#legal-basis">Why we may use your data</a></li>
<li><a href="#retention">How long we keep it</a></li>
<li><a href="#sharing">Who we share it with</a></li>
<li><a href="#security">How we protect it</a></li>
<li><a href="#your-rights">Your rights and how to use them</a></li>
<li><a href="#children">Children</a></li>
<li><a href="#cookies">Cookies and other websites</a></li>
<li><a href="#changes">Changes to this policy</a></li>
<li><a href="#contact">Contact and grievances</a></li>
</ol>
</div>

<h2 id="who-we-are">1. Who we are</h2>
<p>This website is run by <strong>{company}</strong> ("we", "us", "our").</p>
<ul>
<li><strong>Address:</strong> {address}</li>
<li><strong>GSTIN:</strong> {gstin}</li>
<li><strong>Email:</strong> {email}</li>
<li><strong>Phone:</strong> {phone}</li>
</ul>
<p>Under India's Digital Personal Data Protection Act, 2023 (the "DPDP Act") we are the Data Fiduciary for the personal data described in this policy, which means we decide why and how it is used.</p>

<h3>What this website does</h3>
<p>The website publishes reference gold and silver rates twice a day, shows the metal value of items by weight, and has pages about our coins, bars, biscuits and jewellery, news updates and a contact form. There is no online shop, no online payment and no customer account on this website. If you want to buy, you contact us, our desk confirms a quote, and you pay by bank transfer to an account listed on our <a href="/bank-details">Bank Details</a> page.</p>

<h2 id="what-we-collect">2. What we collect and why</h2>
<table>
<thead><tr><th scope="col">What we collect</th><th scope="col">When</th><th scope="col">Why we use it</th></tr></thead>
<tbody>
<tr><td data-label="What we collect">Your name, email address, phone number (optional), subject and message</td><td data-label="When">You send the <a href="/contact-us">contact form</a></td><td data-label="Why we use it">To reply to your enquiry, prepare a quote and keep a record of our conversation with you</td></tr>
<tr><td data-label="What we collect">Your name, email address, phone number (optional), the type of request and any details you add</td><td data-label="When">You send the <a href="/privacy/request">data request form</a></td><td data-label="Why we use it">To deal with your request, check that it comes from you, and keep a record that we handled it</td></tr>
<tr><td data-label="What we collect">Your phone number, email address or WhatsApp number, and what you tell us</td><td data-label="When">You call, email or message us on WhatsApp</td><td data-label="Why we use it">To answer you, give you a quote and arrange your purchase</td></tr>
<tr><td data-label="What we collect">Details needed to complete a purchase, such as your name, contact details, invoice details and the bank transfer reference. <mark class="tbc">[To confirm: any other details collected when a customer buys, e.g. PAN or ID where the law requires it]</mark></td><td data-label="When">You buy from us (this happens off the website)</td><td data-label="Why we use it">To complete the sale, issue a GST invoice, match your payment and meet our legal, tax and accounting duties</td></tr>
<tr><td data-label="What we collect">Your IP address, browser and device type (user agent), the page requested, the page you came from, and the date and time</td><td data-label="When">Automatically, each time your browser loads a page. These are the standard log files of the server that hosts our website</td><td data-label="Why we use it">To keep the website running and secure, find and fix errors, and detect and stop abuse</td></tr>
<tr><td data-label="What we collect">A few small cookies that keep forms working and remember your cookie choice</td><td data-label="When">When you visit the website</td><td data-label="Why we use it">See our <a href="/cookie-policy">Cookie Policy</a></td></tr>
</tbody>
</table>
<p>Please do not send us sensitive information, such as health details or bank card numbers, through our forms. We do not need it.</p>

<h2 id="what-we-do-not-collect">3. What we do not collect</h2>
<ul>
<li>We do not use analytics, advertising pixels, tracking cookies or chat widgets.</li>
<li>This website does not send newsletters or marketing emails.</li>
<li>The website takes no payments. We never ask for card numbers or passwords.</li>
<li>The public cannot create accounts on this website, so we hold no customer passwords or profiles.</li>
</ul>

<h2 id="legal-basis">4. Why we may use your data</h2>
<p>We use your personal data only for the purposes listed in section 2, on one of these grounds under the DPDP Act:</p>
<ul>
<li><strong>Your consent.</strong> When you send us a form or contact us, you agree to us using your details to deal with your enquiry or request. You can withdraw your consent at any time (see section 8). Withdrawing does not affect anything we did before you withdrew.</li>
<li><strong>Legitimate uses allowed by law.</strong> For example, where you have given us your details yourself for a specific purpose, such as asking for a quote, or where we must keep records to comply with the law, such as tax and GST records.</li>
</ul>
<p>Server logs are kept to run and protect the website. <mark class="tbc">[To confirm: the ground relied on for server logs, with your legal adviser]</mark></p>

<h2 id="retention">5. How long we keep it</h2>
<p>We keep personal data only for as long as we need it for the purpose it was collected for, or for as long as the law requires, and then delete it.</p>
<table>
<thead><tr><th scope="col">Data</th><th scope="col">How long we keep it</th></tr></thead>
<tbody>
<tr><td data-label="Data">Contact form enquiries</td><td data-label="How long we keep it"><mark class="tbc">[To confirm: retention period for enquiries]</mark></td></tr>
<tr><td data-label="Data">Data requests and our replies</td><td data-label="How long we keep it"><mark class="tbc">[To confirm: retention period for data-request records]</mark></td></tr>
<tr><td data-label="Data">Purchase, invoice and payment records</td><td data-label="How long we keep it">As long as tax and accounting laws require. <mark class="tbc">[To confirm: retention period for invoices and transaction records]</mark></td></tr>
<tr><td data-label="Data">Server logs</td><td data-label="How long we keep it"><mark class="tbc">[To confirm: how long the hosting provider keeps server logs]</mark></td></tr>
<tr><td data-label="Data">Cookies</td><td data-label="How long we keep it">See the <a href="/cookie-policy">Cookie Policy</a></td></tr>
</tbody>
</table>

<h2 id="sharing">6. Who we share it with</h2>
<p>We do not sell or rent your personal data. Only our authorised staff can read the enquiries and requests you send us. We share personal data only with the following, and only as much as each one needs:</p>
<table>
<thead><tr><th scope="col">Who</th><th scope="col">What they receive</th><th scope="col">When</th></tr></thead>
<tbody>
<tr><td data-label="Who">Our website hosting provider <mark class="tbc">[To confirm: hosting provider name]</mark></td><td data-label="What they receive">Everything stored on the website, including form submissions and server logs</td><td data-label="When">Always, because the website runs on their server</td></tr>
<tr><td data-label="Who">Our email provider <mark class="tbc">[To confirm: email provider]</mark></td><td data-label="What they receive">Your email address and the messages we exchange</td><td data-label="When">When we reply to you by email</td></tr>
<tr><td data-label="Who">jsDelivr, unpkg and cdnjs (services that deliver the code libraries our pages use)</td><td data-label="What they receive">Your IP address and browser details</td><td data-label="When">Each time a page loads, because your browser fetches files from them</td></tr>
<tr><td data-label="Who">Google (Google Maps)</td><td data-label="What they receive">Your IP address and browser details. Google may set its own cookies</td><td data-label="When">Only if you allow embedded content, or click to load the map on the Contact page</td></tr>
<tr><td data-label="Who">TradingView</td><td data-label="What they receive">Your IP address and browser details. TradingView may set its own cookies</td><td data-label="When">Only if you allow embedded content, or click to load a chart on the Charts page</td></tr>
<tr><td data-label="Who">WhatsApp (Meta)</td><td data-label="What they receive">Your WhatsApp number and the messages you send us</td><td data-label="When">Only if you choose to contact us on WhatsApp</td></tr>
<tr><td data-label="Who">Banks</td><td data-label="What they receive">Payment details, such as the payer's name, account and transfer reference</td><td data-label="When">Only when you pay us by bank transfer</td></tr>
<tr><td data-label="Who">Government authorities, courts or regulators</td><td data-label="What they receive">What the law requires</td><td data-label="When">Only where we are legally required to</td></tr>
</tbody>
</table>
<p><mark class="tbc">[To confirm: any other parties who receive customer data, e.g. your accountant or a courier]</mark></p>
<p>Google, TradingView, WhatsApp and banks handle your data under their own privacy policies. Some of these providers may store or process data outside India.</p>

<h2 id="security">7. How we protect it</h2>
<ul>
<li>The website uses HTTPS, so information sent between your browser and our server is encrypted.</li>
<li>Enquiries and requests are kept in our website database and can be read only by authorised staff who sign in to our password-protected admin area.</li>
<li>Our forms are protected against automated abuse and cross-site request forgery.</li>
<li>We collect only what we need.</li>
</ul>
<p>No website or computer system can be made completely secure. If a personal data breach affects you, we will inform you and the Data Protection Board of India as required by law.</p>

<h2 id="your-rights">8. Your rights and how to use them</h2>
<p>As provided under the DPDP Act and the rules made under it, and subject to the conditions and exceptions in that law, you have the right to:</p>
<ul>
<li><strong>Access:</strong> ask for a summary of the personal data we hold about you, how we use it and who we have shared it with.</li>
<li><strong>Correction:</strong> ask us to correct inaccurate or misleading data, complete incomplete data, or update it.</li>
<li><strong>Erasure:</strong> ask us to delete your personal data, unless we must keep it to comply with the law.</li>
<li><strong>Withdraw consent:</strong> withdraw consent you have given us, as easily as you gave it. We will then stop using that data, unless the law allows or requires us to keep it.</li>
<li><strong>Grievance redressal:</strong> complain to us about how we have handled your personal data and receive a response.</li>
<li><strong>Nominate:</strong> name another person to use these rights for you if you die or become unable to do so yourself.</li>
</ul>
<p>These rights apply as provided under applicable law, including as the DPDP Rules come into force.</p>
<h3>How to make a request</h3>
<ol>
<li>Use our <a href="/privacy/request">data request form</a>, or email or call us using the details in section 12.</li>
<li>Tell us which right you want to use and what it relates to, for example roughly when you contacted us.</li>
<li>We may ask you to confirm your identity before we act, so that we never show or change someone else's data.</li>
<li>We will reply within <mark class="tbc">[To confirm: response time]</mark>.</li>
</ol>
<p>If you are not satisfied with how we handle your grievance, you may be able to complain to the Data Protection Board of India, as provided under the DPDP Act.</p>

<h2 id="children">9. Children</h2>
<p>This website is not directed at children (under the DPDP Act, anyone under 18). We do not knowingly collect personal data from children. If you believe a child has sent us personal data, please contact us and we will delete it.</p>

<h2 id="cookies">10. Cookies and other websites</h2>
<p>We use only the cookies the website needs to work and to remember your cookie choice. Google Maps and TradingView content loads only if you allow it. We do not use analytics or advertising cookies. Full details are in our <a href="/cookie-policy">Cookie Policy</a>, and you can change your choice at any time with the <a href="/cookie-policy#your-choices" data-consent-open>Cookie settings</a> link at the bottom of every page.</p>
<p>Our pages link to other websites, such as WhatsApp and Google Maps. Those websites have their own privacy policies, and we are not responsible for how they handle your data.</p>

<h2 id="changes">11. Changes to this policy</h2>
<p>We may update this policy when our website, our services or the law changes. We will publish the new version on this page and change the "Last updated" date at the top.</p>

<h2 id="contact">12. Contact and grievances</h2>
<p>For any question, request or complaint about your personal data, please contact:</p>
<ul>
<li><strong>Person handling privacy requests:</strong> <mark class="tbc">[To confirm: name/designation of the person handling privacy requests]</mark></li>
<li><strong>Email:</strong> {email}</li>
<li><strong>Phone:</strong> {phone}</li>
<li><strong>Post:</strong> {company}, {address}</li>
<li><strong>Online:</strong> our <a href="/privacy/request">data request form</a> or the <a href="/contact-us">Contact page</a></li>
</ul>
HTML;
    }

    private function termsOfService(): string
    {
        return <<<'HTML'
<p class="policy-updated">Last updated: {updated}</p>
<p class="policy-lead">These Terms of Service apply to your use of this website, which is run by {company}. Please read them together with our <a href="/privacy-policy">Privacy Policy</a>, <a href="/refund-cancellation-policy">Refund &amp; Cancellation Policy</a> and <a href="/cookie-policy">Cookie Policy</a>. By using the website you agree to these terms. If you do not agree, please do not use it.</p>

<h2 id="about">1. About us and this website</h2>
<p>{company} ("we", "us", "our") has its office at {address}. GSTIN: {gstin}.</p>
<p>This website gives information: reference gold and silver rates published twice a day, the metal value of items by weight, pages about our coins, bars, biscuits and jewellery, news updates, and ways to contact us. <strong>You cannot buy or pay for anything on this website.</strong> There is no online shop, no online payment and no customer account.</p>

<h2 id="rates">2. Rates and prices are for reference only</h2>
<ul>
<li>The rates and values on this website are <strong>reference prices for the metal value only</strong>. They are not an offer to sell or to buy at that price.</li>
<li>Metal values shown by weight do not include making charges, 3% GST or any other charges that apply to a particular item.</li>
<li>Gold and silver prices move during the day, so the price when you buy may differ from the rate shown.</li>
<li>The final price is confirmed by our desk when you order, and includes making charges and 3% GST.</li>
<li>We take care to publish accurate rates, but mistakes can happen. We are not bound by a rate or value that is clearly wrong.</li>
<li>Pictures of products are for illustration. The item, weight and price are confirmed in your quote.</li>
</ul>

<h2 id="orders">3. Quotes and orders happen off the website</h2>
<ol>
<li>You contact us through the <a href="/contact-us">contact form</a>, WhatsApp or phone.</li>
<li>Our desk replies with a quote that confirms the item, price, making charges and GST.</li>
<li>Your order is confirmed when <mark class="tbc">[To confirm: what confirms an order, e.g. your acceptance of the quote and receipt of payment]</mark>.</li>
<li>You pay by bank transfer (see section 4). <mark class="tbc">[To confirm: how items are collected or delivered, and any delivery terms]</mark></li>
</ol>
<p>Cancellations and refunds are covered by our <a href="/refund-cancellation-policy">Refund &amp; Cancellation Policy</a>.</p>

<h2 id="payments">4. Payments</h2>
<ul>
<li>This website does not take payments of any kind.</li>
<li>Pay only by bank transfer into an account listed on our <a href="/bank-details">Bank Details</a> page, and only after our desk has confirmed your quote.</li>
<li>We never ask for card numbers or passwords. If anyone asks you to pay into an account that is not on our Bank Details page, do not pay. Check with us first using the details on our <a href="/contact-us">Contact page</a>.</li>
</ul>

<h2 id="use">5. Using the website</h2>
<p>You may use this website to find information and to contact us. You must not:</p>
<ul>
<li>use it in a way that breaks any law or the rights of others;</li>
<li>try to get unauthorised access to the website, its admin area, server or database, or interfere with how it works or its security;</li>
<li>send false, misleading or abusive information through our forms, or use them to send spam or advertising;</li>
<li>pretend to be someone else, or make a data request about another person without their authority;</li>
<li>upload or send viruses or other harmful code;</li>
<li>copy our rates or content in bulk, including by automated scraping, to republish them or run a competing service, without our written permission.</li>
</ul>
<p>We may block access to the website for anyone who breaks these rules.</p>

<h2 id="ip">6. Intellectual property</h2>
<p>The content of this website, including its text, rate tables, graphics, the {company} name and logo, and the site design, belongs to us or is used with the permission of its owners. You may view, download and print pages for your own personal use. You may not copy, republish or use them commercially without our written permission. Other names and logos belong to their owners.</p>

<h2 id="third-party">7. Links and content from other services</h2>
<p>The website links to, and can display content from, other services such as WhatsApp, Google Maps and TradingView charts. We do not control these services; their own terms and privacy policies apply. Embedded content loads only if you allow it (see our <a href="/cookie-policy">Cookie Policy</a>).</p>

<h2 id="availability">8. Accuracy and availability</h2>
<p>We aim to keep the website accurate and available, but we do not promise that it will always be available, uninterrupted or free of errors, or that all information on it is complete and up to date. We may change, suspend or withdraw any part of the website at any time.</p>

<h2 id="liability">9. Limitation of liability</h2>
<p>The website is provided for general information. To the extent permitted by law:</p>
<ul>
<li>we are not responsible for any loss caused by relying on the reference rates or other information on the website without first confirming a quote with our desk;</li>
<li>we are not responsible for indirect or consequential loss, or for loss caused by events outside our reasonable control, such as internet or hosting outages;</li>
<li>we are not responsible for the content, availability or practices of other websites and services.</li>
</ul>
<p>Nothing in these terms excludes or limits any liability that cannot be excluded or limited under Indian law, or affects your rights as a consumer under the Consumer Protection Act, 2019.</p>

<h2 id="privacy">10. Privacy</h2>
<p>How we handle personal data is explained in our <a href="/privacy-policy">Privacy Policy</a>.</p>

<h2 id="law">11. Governing law and disputes</h2>
<p>These terms are governed by the laws of India. Disputes about these terms or this website are subject to the jurisdiction of the courts at <mark class="tbc">[To confirm: jurisdiction, e.g. Bengaluru]</mark>. If you have a concern, please contact us first so we can try to resolve it.</p>

<h2 id="changes">12. Changes to these terms</h2>
<p>We may update these terms from time to time. The version on this page, with its "Last updated" date, applies from that date.</p>

<h2 id="contact">13. Contact us</h2>
<ul>
<li><strong>Email:</strong> {email}</li>
<li><strong>Phone and WhatsApp:</strong> see our <a href="/contact-us">Contact page</a> for current numbers and hours</li>
<li><strong>Post:</strong> {company}, {address}</li>
</ul>
HTML;
    }

    private function refundPolicy(): string
    {
        return <<<'HTML'
<p class="policy-updated">Last updated: {updated}</p>
<p class="policy-lead">This website does not sell anything online and does not take any payment. This policy explains how cancellations and refunds work for purchases you agree with our desk and pay for by bank transfer.</p>

<h2 id="how-buying-works">1. How buying from us works</h2>
<ol>
<li><strong>Enquire.</strong> Contact us through the <a href="/contact-us">contact form</a>, WhatsApp or phone.</li>
<li><strong>Quote.</strong> Our desk confirms the item, weight, metal rate, making charges and 3% GST. The rates on this website are reference prices only.</li>
<li><strong>Confirm and pay.</strong> You confirm the order and pay by bank transfer into an account listed on our <a href="/bank-details">Bank Details</a> page. <mark class="tbc">[To confirm: whether an advance or full payment is required, and when]</mark></li>
<li><strong>Collection or delivery.</strong> <mark class="tbc">[To confirm: how and when items are collected or delivered]</mark></li>
</ol>

<h2 id="before-payment">2. Cancelling before you pay</h2>
<p>If you have not paid anything, you can tell us that you no longer wish to go ahead. <mark class="tbc">[To confirm: whether any charge applies if a confirmed order is cancelled before payment, e.g. when custom work has already started]</mark></p>

<h2 id="after-payment">3. Cancelling after you have paid</h2>
<ul>
<li><strong>Whether you can cancel:</strong> <mark class="tbc">[To confirm: whether orders can be cancelled after payment]</mark></li>
<li><strong>Time limit:</strong> <mark class="tbc">[To confirm: cancellation window]</mark></li>
<li><strong>Deductions:</strong> <mark class="tbc">[To confirm: any deductions, e.g. for a change in the metal price since your price was confirmed, or bank charges]</mark></li>
</ul>

<h2 id="bullion">4. Gold and silver coins, bars and biscuits</h2>
<p>Our desk confirms the price of coins, bars and biscuits when you order, based on the metal rate at that time. Because metal prices change throughout the day: <mark class="tbc">[To confirm: whether bullion orders can be cancelled once the price is confirmed, and how any price difference is handled]</mark></p>

<h2 id="jewellery">5. Jewellery</h2>
<ul>
<li><strong>Ready-made jewellery:</strong> <mark class="tbc">[To confirm: cancellation, return or exchange terms for ready-made jewellery]</mark></li>
<li><strong>Made-to-order or customised jewellery:</strong> <mark class="tbc">[To confirm: whether these orders can be cancelled once work has started, and what happens to any advance paid]</mark></li>
</ul>

<h2 id="returns">6. Returns and exchanges</h2>
<p><mark class="tbc">[To confirm: whether returns or exchanges are accepted; the time limit; the condition the item must be in (e.g. unused, with the invoice and any certificate); and any deduction]</mark></p>

<h2 id="problems">7. If something is wrong with your item</h2>
<p>Please check your item and invoice when you collect or receive it, and tell us straight away if anything is wrong, for example the wrong item, weight or purity, or damage. <mark class="tbc">[To confirm: time limit for reporting a problem, and the remedy offered, e.g. replacement, repair or refund]</mark></p>

<h2 id="refunds">8. How refunds are paid</h2>
<ul>
<li><strong>Method:</strong> <mark class="tbc">[To confirm: refund method, e.g. bank transfer to the account the payment came from]</mark></li>
<li><strong>Timeline:</strong> <mark class="tbc">[To confirm: how soon an approved refund is paid]</mark></li>
</ul>
<p>We never ask for card numbers or passwords.</p>

<h2 id="how-to-ask">9. How to ask for a cancellation or refund</h2>
<p>Contact our desk with your name, your quote or invoice number, and the reason for your request:</p>
<ul>
<li><strong>Email:</strong> {email}</li>
<li><strong>Phone and WhatsApp:</strong> see our <a href="/contact-us">Contact page</a> for current numbers and hours</li>
</ul>

<h2 id="your-rights">10. Your legal rights</h2>
<p>Nothing in this policy affects your rights as a consumer under the Consumer Protection Act, 2019, or any other law that applies.</p>
HTML;
    }

    private function cookiePolicy(): string
    {
        return <<<'HTML'
<p class="policy-updated">Last updated: {updated}</p>
<p class="policy-lead">This policy explains the cookies used on this website, why we use them and how you can control them. In short: we use only the cookies the website needs to work, and content from Google Maps and TradingView loads only if you allow it. <strong>We do not use analytics or advertising cookies.</strong></p>

<h2 id="what-are-cookies">1. What cookies are</h2>
<p>Cookies are small text files that a website saves in your browser. They let the site remember things between pages or visits, for example that you have already made your cookie choice. Cookies set by the website you are visiting are called first-party cookies. Cookies set by another company whose content appears on the page are called third-party cookies.</p>

<h2 id="cookies-we-use">2. Cookies we use</h2>
<p>These cookies are strictly necessary: the website cannot work properly without them. They are always on and cannot be switched off in our cookie settings.</p>
<table>
<thead><tr><th scope="col">Name</th><th scope="col">Provider</th><th scope="col">Purpose</th><th scope="col">Type</th><th scope="col">Duration</th></tr></thead>
<tbody>
<tr><td data-label="Name"><code>{session_cookie}</code></td><td data-label="Provider">This website</td><td data-label="Purpose">Keeps your visit working from page to page, for example to show the result of a form you have sent</td><td data-label="Type">Strictly necessary</td><td data-label="Duration">{session_duration}</td></tr>
<tr><td data-label="Name"><code>XSRF-TOKEN</code></td><td data-label="Provider">This website</td><td data-label="Purpose">Security: protects our forms against cross-site request forgery</td><td data-label="Type">Strictly necessary</td><td data-label="Duration">{session_duration}</td></tr>
<tr><td data-label="Name"><code>josha_consent</code></td><td data-label="Provider">This website</td><td data-label="Purpose">Remembers your cookie choice, so we do not ask you again on every page</td><td data-label="Type">Strictly necessary</td><td data-label="Duration">12 months</td></tr>
</tbody>
</table>
<p>Staff who sign in to our admin area use the same session cookie to stay signed in.</p>

<h2 id="embedded-content">3. Embedded content (off until you allow it)</h2>
<p>Some pages can show content from other companies. Until you allow <strong>Embedded content</strong> in our cookie settings, or click the button to load an item, we show a placeholder and nothing is loaded from these companies.</p>
<table>
<thead><tr><th scope="col">Content</th><th scope="col">Where</th><th scope="col">Provider</th><th scope="col">What happens when it loads</th></tr></thead>
<tbody>
<tr><td data-label="Content">Map of our office</td><td data-label="Where"><a href="/contact-us">Contact page</a></td><td data-label="Provider">Google (Google Maps)</td><td data-label="What happens when it loads">Google receives your IP address and browser details and may set its own cookies. See the <a href="https://policies.google.com/privacy" target="_blank" rel="noopener">Google Privacy Policy</a>.</td></tr>
<tr><td data-label="Content">Price charts</td><td data-label="Where"><a href="/charts">Charts page</a></td><td data-label="Provider">TradingView</td><td data-label="What happens when it loads">TradingView receives your IP address and browser details and may set its own cookies. See the <a href="https://www.tradingview.com/privacy-policy/" target="_blank" rel="noopener">TradingView Privacy Policy</a>.</td></tr>
</tbody>
</table>
<p>Cookies set by Google or TradingView are controlled by them, not by us, and we cannot read them.</p>

<h2 id="other-services">4. Other services your browser connects to</h2>
<p>Our pages load code libraries from jsDelivr, unpkg and cdnjs. Our fonts are served from our own server. These services receive your IP address and browser details so that they can send the files to your browser. We do not use them to set cookies or to track you.</p>
<p>If you choose to message us on WhatsApp, WhatsApp (Meta) handles that conversation under its own privacy policy.</p>

<h2 id="what-we-do-not-use">5. What we do not use</h2>
<ul>
<li>No analytics cookies or visitor statistics tools.</li>
<li>No advertising cookies, tracking pixels or social media trackers.</li>
<li>No chat widgets.</li>
</ul>

<h2 id="your-choices">6. How to change your choice</h2>
<ul>
<li><strong>Cookie settings:</strong> use the <a href="#your-choices" data-consent-open>Cookie settings</a> link at the bottom of every page to allow or block embedded content at any time.</li>
<li><strong>If you withdraw your consent:</strong> embedded content will not load again. Cookies that Google or TradingView have already set stay in your browser until they expire or you delete them in your browser settings.</li>
<li><strong>Your browser:</strong> most browsers let you block or delete cookies. If you block the strictly necessary cookies above, our forms may not work and you may see a "page expired" message when you send one.</li>
</ul>

<h2 id="changes">7. Changes to this policy</h2>
<p>If we change the cookies we use, we will update this page and the "Last updated" date at the top.</p>

<h2 id="contact">8. Questions</h2>
<p>For questions about cookies or your personal data, see our <a href="/privacy-policy">Privacy Policy</a> or email us at {email}.</p>
HTML;
    }
};
