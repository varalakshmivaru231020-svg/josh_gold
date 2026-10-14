<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

// Four evergreen Updates posts (Admin → Updates) about how rates, purity, bullion and payments work.
// Cover images are in storage/app/public/blogs. The "sdfg" test post is hidden (not deleted).
return new class extends Migration
{
    public function up(): void
    {
        $now = now();
        foreach ($this->posts() as $i => [$slug, $title, $image, $body]) {
            if (DB::table('blogs')->where('slug', $slug)->exists()) {
                continue;
            }
            DB::table('blogs')->insert([
                'title' => $title, 'slug' => $slug, 'image' => 'blogs/' . $image, 'description' => $body,
                'is_active' => true, 'sort_order' => $i + 1,
                'created_at' => $now->copy()->subMinutes($i), 'updated_at' => $now,
            ]);
        }
        DB::table('blogs')->where('slug', 'sdfg')->update(['is_active' => false]);
    }

    public function down(): void
    {
        DB::table('blogs')->whereIn('slug', array_column($this->posts(), 0))->delete();
        DB::table('blogs')->where('slug', 'sdfg')->update(['is_active' => true]);
    }

    private function posts(): array
    {
        return [
            ['how-we-publish-our-gold-and-silver-rates', 'How we publish our gold and silver rates', 'daily-rates.webp', <<<'HTML'
<p>Every working day we publish two reference rates for gold and silver, one in the morning and one in the evening. Here is what those numbers mean and how every other price on this site is worked out from them.</p>
<h2>One rate, every purity</h2>
<p>We publish the rate for fine metal: 24K gold and 999 silver. Every lower purity is calculated from that same rate by its metal content, so the prices always agree with each other:</p>
<ul>
<li><strong>22K gold</strong> contains 91.6% gold, so it is priced at 22/24 of the 24K rate.</li>
<li><strong>18K gold</strong> contains 75% gold, so it is priced at 18/24 of the 24K rate.</li>
<li><strong>925 sterling silver</strong> contains 92.5% silver and is priced from the 999 rate in the same way.</li>
</ul>
<h2>Grams, sovereigns, tolas and ounces</h2>
<p>Gold is usually quoted per 10 grams and silver per kilogram. Our rate pages also show the units people buy in: 1 gram, 8 grams (one sovereign), a tola (11.66 grams) and a troy ounce (31.10 grams), so you can compare like with like.</p>
<h2>What the rate does not include</h2>
<p>The published rate is the value of the metal only. Making charges, where they apply, and 3% GST are added to your final quote. Coins and bars carry small making charges; jewellery carries more, depending on the design and the work involved.</p>
<h2>Always check the time</h2>
<p>Every rate shows when it was published, at the top of each page and beside the rate tables. If you are comparing prices, compare rates from the same session. To lock in a price, <a href="/contact-us">ask our desk for a firm quote</a>.</p>
HTML],
            ['hallmarking-and-huid-how-to-check-your-gold', 'Hallmarking and HUID: how to check your gold', 'hallmark-huid.webp', <<<'HTML'
<p>A hallmark is an independent certificate of purity stamped on the piece itself. Before you buy gold jewellery, or when you sell or exchange it, the hallmark tells you exactly what you are paying for.</p>
<h2>The three marks to look for</h2>
<p>Hallmarked gold jewellery in India carries three marks, certified by the Bureau of Indian Standards (BIS):</p>
<ul>
<li><strong>The BIS logo</strong>, a triangle-shaped mark.</li>
<li><strong>The purity grade</strong>, written as carat and fineness. For example, 22K916 means 22 carat, 91.6% gold; 18K750 means 18 carat, 75% gold.</li>
<li><strong>The HUID</strong>, a six-character alphanumeric code that is unique to that piece.</li>
</ul>
<h2>Check any HUID yourself</h2>
<p>You can check a HUID in the government's free <strong>BIS CARE</strong> app, using its "Verify HUID" option. It shows the purity, the type of article and the hallmarking centre that tested it. If the details do not match the piece in front of you, ask the seller before you pay.</p>
<h2>Ask for the details on your bill</h2>
<p>A proper bill lists the item, its gross and net weight, the purity, the HUID, the rate used, the making charges and the GST separately. Keep it safely; you will need it if you ever sell or exchange the piece.</p>
<p>Questions about a piece you own or want to buy? <a href="/contact-us">Talk to our desk</a>.</p>
HTML],
            ['gold-coins-bars-and-biscuits-which-suits-you', 'Gold coins, bars and biscuits: which suits you?', 'coins-bars-biscuits.webp', <<<'HTML'
<p>Coins, bars and biscuits are all made of 24K gold and are priced at the value of the gold they contain. The difference is mostly size, and size affects the making charge you pay per gram.</p>
<h2>Gold coins: 0.5 g to 100 g</h2>
<p>Coins are the easiest way to start. Small weights such as 0.5 g, 1 g, 2 g and 5 g are popular as gifts and for regular saving, and an 8 g coin matches one sovereign. Because the making charge is spread over less gold, small coins cost a little more per gram than large pieces.</p>
<h2>Gold bars: 5 g to 1 kg</h2>
<p>Bars hold more gold for a lower making charge per gram, so they suit larger purchases. Minted bars are stamped with their weight and purity.</p>
<h2>Gold biscuits: 50 g to 1 kg</h2>
<p>Biscuits are heavier cast bars, including the traditional 10 tola size (116.64 g). They are usually chosen for larger, longer-term holdings.</p>
<h2>Before you buy</h2>
<ul>
<li>Check the current price for any weight on our <a href="/#by-weight">price by weight</a> list, which updates with every rate we publish.</li>
<li>Remember that 3% GST and making charges are added to the metal value.</li>
<li>Keep the invoice and the packaging; both matter when you sell later.</li>
<li>Gold prices move every day. Past prices are not a guide to future returns, so buy an amount that suits your own plans.</li>
</ul>
<p>See our <a href="/collections/gold-coins">gold coins</a>, <a href="/collections/gold-bars">gold bars</a> and <a href="/collections/gold-biscuits">gold biscuits</a>, or <a href="/contact-us">ask for a quote</a>.</p>
HTML],
            ['how-our-quotation-and-payment-process-keeps-you-safe', 'How our quotation and payment process keeps you safe', 'quotation-payment.webp', <<<'HTML'
<p>Buying gold, diamonds or silver is a large payment, so we keep the process simple and transparent. This is how an order with us works, step by step.</p>
<h2>1. Tell us what you need</h2>
<p>Send an enquiry from our <a href="/contact-us">Contact page</a>, press "Get a quote" on any item, WhatsApp us or call our desk. Tell us the metal, purity and weight, or the piece of jewellery you have in mind.</p>
<h2>2. Get a firm quote</h2>
<p>We confirm the price at the current rate, the making charges where they apply, and the GST, so you know the full amount before you commit.</p>
<h2>3. Pay only into our listed accounts</h2>
<p>Pay by bank transfer to one of the accounts on our <a href="/bank-details">Bank Details</a> page. Never pay into an account that is not listed there, even if a message appears to come from us.</p>
<h2>What we will never ask for</h2>
<ul>
<li>Your card number, CVV or card PIN</li>
<li>Your net-banking password or UPI PIN</li>
<li>One-time passwords (OTPs) sent to your phone</li>
</ul>
<p>If anyone asks for these in our name, do not share them, and contact us on the number shown on this website.</p>
<h2>Your details</h2>
<p>We use the details you send only to answer your enquiry. Our <a href="/privacy-policy">Privacy Policy</a> explains how we handle them, and you can <a href="/privacy/request">ask to see, correct or delete them</a> at any time.</p>
HTML],
        ];
    }
};
