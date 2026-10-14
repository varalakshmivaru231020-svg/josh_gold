# Privacy, legal and accessibility review

JOSHA GOLD AND DIAMOND JEWELLERY · josha.aksglobalfms.com · reviewed 13–14 October 2026

This is a working record, not legal advice. The policy pages are plain-English drafts. Have them reviewed by a lawyer before relying on them.

## 1. What the site is (facts the review is based on)

- Laravel 12 site with a public website and a staff-only admin panel. Hosted on a CloudPanel server (hosting provider not recorded).
- Publishes reference gold and silver rates twice a day. Shows the metal value by weight, excluding making charges and 3% GST.
- **No online shop, no online payment, no public accounts.** Customers enquire by the contact form, WhatsApp or phone. The desk confirms a quote and the customer pays by bank transfer to the accounts on the Bank Details page.
- Personal data handled through the site:
  - contact form and data-request form (name, email, optional phone, message), stored in `contact_messages`;
  - web server logs (IP address, browser, pages, time);
  - staff admin accounts.
- The site sends **no emails, SMS or newsletters**.

## 2. Changes made

| Area | Change |
|---|---|
| Policy pages | Privacy Policy, Terms of Service, Refund and Cancellation Policy and Cookie Policy added as CMS pages (Admin → Pages), linked in the footer and in the sitemap. Unknown details are marked `[To confirm: …]` and shown in amber. |
| Data rights | `/privacy/request`: form to ask for access, correction, deletion or withdrawal of consent. Stored as an enquiry with the subject "[Data request] …"; rate-limited. Linked from the Privacy Policy and the Contact page. |
| Contact form | Labels tied to fields, errors announced to screen readers, a note on why details are asked for, a link to the Privacy Policy, "we never ask for card numbers or passwords", and a separate rate limit. |
| Cookie consent | Removed on 14 Oct 2026 at the owner's request: the map and charts now load with the page, so no optional cookies are left to ask about. The Cookie and Privacy policies say this. |
| Embedded content | Since 14 Oct 2026 the Google Maps embed (Contact page) and the TradingView charts (Charts page) load with the page (owner's decision). Previously they waited for consent. |
| Third parties removed | Google Fonts replaced by self-hosted fonts (`public/fonts`, OFL). Unused Swiper and Glide libraries removed. External placeholder-image service replaced by local initials. |
| Misleading content | Stock photo shown as the founder on the About page replaced with a monogram. The price charts show a notice whenever demo (sample) prices exist. |
| Accessibility | Focus rings across the light theme, a focus trap and focus return in the phone menu and cookie dialog, Esc to close, labelled forms, decorative icons hidden from screen readers. Scroll animations are disabled for "reduced motion" users. |
| Assets | Seven unused files of unknown origin deleted (old banners, old logo, test page). |

## 3. Third-party integration audit

| Service | Where | What it receives | Consent |
|---|---|---|---|
| jsDelivr CDN | Bootstrap, amCharts (inner pages) | Visitor IP and browser (to serve files) | Not required (needed to show the page); listed in the Privacy Policy |
| unpkg CDN | AOS animation library (inner pages) | IP and browser | Same as above |
| cdnjs (Cloudflare) | Font Awesome icons (inner pages) | IP and browser | Same as above |
| Google Maps | Contact page map | IP and browser; Google may set cookies | **Loads with the page; no consent step** |
| TradingView | XAU/USD and XAG/USD charts on /charts | IP and browser; TradingView may set cookies | **Loads with the page; no consent step** |
| WhatsApp (Meta) | wa.me links | Only what the visitor sends after choosing to open WhatsApp | Visitor's own choice; no script on the site |
| Admin panel only | Google Fonts (Inter), DataTables, Select2, Summernote, Chart.js from CDNs | Staff IP addresses | Staff-only; consider self-hosting |

No analytics, advertising pixels, chat widgets, payment SDKs or API keys exist in the public code. `.env` is not web-accessible and `APP_DEBUG` is off in production.

## 4. Asset licence register

| Asset | Source | Licence / status |
|---|---|---|
| Josha logo | Supplied by the client | Client-owned |
| Fonts: Cormorant Garamond, Inter | Self-hosted in `public/fonts` | SIL Open Font License (`public/fonts/OFL.txt`) |
| Font Awesome Free icons | cdnjs | Icons CC BY 4.0, fonts SIL OFL, code MIT |
| Site photos | `public/images/site/SOURCES.txt` | Unsplash License, Pexels License, CC0 or public domain; no attribution required |
| Service photos | Listed in `SOURCES.txt` | Public domain / Pexels License |
| amCharts 3 (rate charts) | jsDelivr | Free use with the visible "JS chart by amCharts" link (kept) |
| TradingView widgets | tradingview.com | Free embed with the visible TradingView attribution links (kept) |
| Bootstrap, AOS, Chart.js and the admin libraries | CDNs | MIT |
| Uploaded images (gallery, about, updates, bank QR, sliders) | Uploaded in admin | Client content; **licence not verified** |
| Letters and notices (`public/*.html`) | Bengaluru Law House documents | Shown as published by their authors; **confirm permission** |

## 5. Which laws apply (needs confirmation by a lawyer)

- **India – Digital Personal Data Protection Act, 2023 and DPDP Rules, 2025:** applies, because the business is in India and processes personal data of people in India. The Rules commence in phases; **check which obligations are already in force** before relying on dates. The Privacy Policy, consent and data-request form are built with the Act's notice, consent and rights requirements in mind.
- **India – Information Technology Act, 2000 and the SPDI Rules, 2011:** the privacy-policy obligation for businesses collecting personal information applies until it is replaced by the DPDP regime.
- **Consumer Protection Act, 2019 / E-Commerce Rules, 2020:** the site takes no online orders, so the e-commerce rules may not apply. Prices are shown as metal value excluding making charges and GST, and that is stated wherever prices appear. Confirm with a lawyer.
- **GDPR (EU/UK):** only applies if the business targets people in the EU/UK. Nothing on the site suggests that, so **no GDPR compliance is claimed**. Note: since 14 Oct 2026 Google and TradingView content loads without asking first, which would not meet GDPR-style consent rules if they ever applied.
- **CCPA/CPRA (California):** not applicable on current facts (no California business and the thresholds are not met). No compliance is claimed.
- **Email and SMS marketing:** the site sends none, so unsubscribe and suppression rules don't apply today. If the business starts promotional SMS, calls or WhatsApp messages, India's TRAI commercial-communication rules (TCCCPR) and WhatsApp's opt-in policy apply. Collect and record opt-in first.
- **Accessibility:** no statute mandates WCAG for private Indian businesses, but WCAG 2.1 AA was used as the benchmark.

## 6. Remaining gaps and owner actions

1. **Fill the `[To confirm]` placeholders** in Admin → Pages: 11 in the Privacy Policy, 3 in the Terms and 13 in the Refund and Cancellation Policy. They cover retention periods, the hosting and email providers, response time, the privacy contact person, jurisdiction, and every refund and cancellation rule. Until they are filled the pages show amber markers. **Do not invent refund promises.**
2. **Lawyer review** of all four policies.
3. **About page claims** need confirming or removing: founder name, "7+ years", "founded 2023", "200%+ growth", "100% Commitment", the health-and-safety and "catering" lines (these look like template text), "(WIN WIN skeeye)", and a real founder photo.
4. **Demo rate data:** 6,258 sample rows still feed the charts, trends and Gold Pulse (labelled as samples). Remove them before relying on the site commercially (Admin → Dashboard → Remove sample data).
5. **Test content:** the Updates post "sdfg" is public; delete it.
6. **Data retention:** nothing deletes old enquiries or data requests automatically. Decide retention periods, then add a scheduled clean-up.
7. **Server backups** in `~/backups` contain personal data; keep them only as long as needed and restrict access.
8. **Credentials:** revoke the GitHub token stored in the `origin` remote URL and the WhatsApp API token that was hard-coded in the removed `WhatsAppService.php` (both are in the git history). The seeders still contain default admin passwords (`admin123`); never run them in production.
9. **Policy URLs:** Admin → Pages rebuilds a page's address from its title. Renaming a policy changes its URL, and the cookie banner and contact page link to `/cookie-policy` and `/privacy-policy`.
10. **Optional hardening:** self-host Bootstrap, AOS and Font Awesome to stop sharing visitor IPs with CDNs; self-host the admin fonts; add security headers (CSP, HSTS).
11. **WhatsApp on 080 3556 5332:** a landline only works with WhatsApp Business if it is registered (voice verification).

## 7. Tests run

- All public pages return 200 with no errors, including the policy pages, the data request page, the charts and the contact page. Removed pages return 404.
- Forms: missing-field errors are shown and announced; valid submissions are stored; test rows deleted.
- Consent (headless Chrome):
  - First visit makes no requests to Google, TradingView or Google Fonts.
  - Reject keeps embeds off across reloads; Accept or "Load" loads them; switching off removes them.
  - Keyboard order, focus trap and Esc all work; no console errors.
- Phone width (390 px): no sideways scrolling on the pages checked.

## 8. Change log

- **14 Oct 2026:** at the owner's request, the "Load map" / "Load chart" placeholders and the cookie banner were removed. The Contact map and the Charts page TradingView charts load with the page. The Cookie Policy and Privacy Policy were updated to describe this (migration `2026_10_14_180000_embeds_load_with_page`). The section 7 consent tests no longer apply.
