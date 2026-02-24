<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;
use App\Models\PageSection;

class AboutPageSectionsSeeder extends Seeder
{
    public function run()
    {
        $page = Page::where('slug', 'about-us')->first();

        if (!$page) {
            $page = Page::create([
                'title' => 'About Us',
                'slug' => 'about-us',
                'content' => '',
                'is_published' => true,
            ]);
        }

        // Remove existing sections to avoid duplicates
        $page->sections()->delete();

        // 1. About Intro Section (New)
        $page->sections()->create([
            'section_type' => 'about_intro',
            'heading' => 'About Us - Bengaluru Law House Publication',
            'subheading' => 'Introduction',
            'content' => '<p>Bengaluru Law House (BLR) stands as a pillar of legal knowledge and publishing excellence. With decades of dedicated service, we have bridged the gap between complex legal statutes and the people they serve. Our publications are trusted by judicial officers, legal professionals, and students across India.</p><p>We specialize in comprehensive legal directories, student textbooks, and professional law journals that set the standard for accuracy and depth in legal reporting.</p>',
            'cards' => [
                ['image' => 'about_intro_main.jpg'] // Note: User needs to upload or I use placeholder
            ],
            'sort_order' => 1,
        ]);

        // 2. Visionary Section
        $page->sections()->create([
            'section_type' => 'visionary',
            'heading' => 'A Legacy of Legal Excellence',
            'subheading' => 'Dedicated to transforming the legal landscape since 1995.',
            'content' => '<p>Our journey began with a simple vision: to provide the most reliable legal resources to the legal fraternity. Today, that vision has grown into a multi-faceted organization leading in law, media, and literature.</p>',
            'cards' => [
                ['title' => 'Integrity', 'content' => 'Upholding the highest ethical standards in publishing and practice.', 'icon' => 'fas fa-balance-scale'],
                ['title' => 'Expertise', 'content' => 'Deeply rooted in legal traditions with modern analytical tools.', 'icon' => 'fas fa-gavel'],
                ['title' => 'Commitment', 'content' => 'Unwavering support for the legal education system in India.', 'icon' => 'fas fa-handshake'],
            ],
            'sort_order' => 2,
        ]);

        // 3. Counters Section
        $page->sections()->create([
            'section_type' => 'counters',
            'heading' => 'Our Achievements',
            'subheading' => 'Milestones that define our commitment.',
            'cards' => [
                ['title' => 'Books Published', 'count' => 150, 'icon' => 'fas fa-book'],
                ['title' => 'Happy Clients', 'count' => 10000, 'icon' => 'fas fa-smile'],
                ['title' => 'Years of Legacy', 'count' => 28, 'icon' => 'fas fa-history'],
                ['title' => 'Awards Won', 'count' => 25, 'icon' => 'fas fa-trophy'],
            ],
            'sort_order' => 3,
        ]);

        // 4. Vision & Mission
        $page->sections()->create([
            'section_type' => 'vision_mission',
            'heading' => 'Our Core Philosophy',
            'subheading' => 'The principles that guide every endeavor at Bengaluru Law House.',
            'cards' => [
                [
                    'vision' => 'To be the global benchmark for legal knowledge dissemination, empowering every citizen with the power of law and justice.',
                    'mission' => 'To provide high-quality, accessible legal literature, and to lead with integrity in the fields of legal practice, journalism, and cinema.'
                ]
            ],
            'sort_order' => 4,
        ]);

        // 5. Beyond the Bar (Professional Profiles) - Matched to Image
        $page->sections()->create([
            'section_type' => 'professional_profiles',
            'heading' => 'Author, Filmmaker, Journalist — A Multifaceted Visionary',
            'subheading' => 'BEYOND THE BAR',
            'content' => 'Advocate Veerendrababu\'s life story is one of extraordinary breadth and cultural richness. His accomplishments stretch far beyond the courtroom, reflecting a deeply creative, civic-minded, and culturally rooted personality that has made him a beloved figure in Karnataka\'s literary, cinematic, and media worlds.',
            'cards' => [
                [
                    'type' => 'Kannada Literary Author',
                    'description' => 'A proud and passionate contributor to Kannada literature, Veerendrababu has authored two significant works. Swayam Krushi (2008) is a landmark work on self-sustaining agriculture that resonated deeply with rural Karnataka. His novel Thankappa (2025) is his latest literary offering — a work of Kannada fiction that celebrates culture, identity, and the timeless human spirit.',
                    'icon' => 'fas fa-pen-nib'
                ],
                [
                    'type' => 'Filmmaker, Director & Lead Actor',
                    'description' => 'A bold creative force in Kannada cinema, Veerendrababu brought the story of Swayam Krushi from the page to the silver screen — wearing multiple hats as the lead actor, director, and producer of the film. His cinematic vision transforms stories of the soil, the farmer, and society into powerful visual narratives that honour Karnataka\'s rural heritage and champion the dignity of ordinary people.',
                    'icon' => 'fas fa-film'
                ],
                [
                    'type' => 'Senior Journalist & Chief Editor',
                    'description' => 'A seasoned and respected voice in Karnataka\'s media landscape, Veerendrababu served as Chief Editor of Mussanje — a prominent Kannada evening daily newspaper — and spearheaded editorial operations at V News24, a Kannada television news channel. His journalism career reflects a lifelong commitment to truth, accountability, and the right of every citizen to accurate and fearless public information.',
                    'icon' => 'fas fa-newspaper'
                ],
            ],
            'sort_order' => 5,
        ]);

        // 6. CTA Section
        $page->sections()->create([
            'section_type' => 'cta',
            'heading' => 'Need a quality legal assistance?',
            'subheading' => 'Schedule a consultation with our expert team today, protect your right.',
            'cards' => [
                ['btn_text' => 'Book Consultation', 'btn_link' => '/contact-us']
            ],
            'sort_order' => 6,
        ]);
    }
}
