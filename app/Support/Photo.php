<?php

namespace App\Support;

// Real photographs from Wikimedia Commons (free licence), shown by file name.
class Photo
{
    const BASE = "https://commons.wikimedia.org/wiki/Special:FilePath/";

    // - file name on Commons -> picture address, scaled to the width we need
    public static function url($file, $width = 1600)
    {
        return self::BASE . rawurlencode($file) . "?width=" . $width;
    }

    // - big photos for the home page slideshow (Bali first)
    const HERO = [
        ["Bali, Indonesia", "Tanah-Lot Bali Indonesia Pura-Tanah-Lot-01.jpg"],
        ["Santorini, Greece", "Oia - Santorini 2019.jpg"],
        ["Maldives", "Anantara Kihavah - Aerial Hero Shot 2024.jpg"],
        ["Banff, Canada", "Moraine Lake Banff National Park.jpg"],
        ["Cox's Bazar, Bangladesh", "Cox's Bazar Sea Beach in 2024.02.jpg"],
    ];

    const DESTINATIONS = [
        "Bali" => "Tegallalang Rice Terraces Bali 1.jpg",
        "Swiss Alps" => "ThunerseeInterlaken.jpg",
        "Cliffs of Moher" => "Cliffs of Moher 20160818-1.jpg",
        "Everest Region" => "Sunset view of Everest.jpg",
        "Maldives" => "Anantara Kihavah - Aerial Hero Shot 2024.jpg",
        "Santorini" => "Oia - Santorini 2019.jpg",
        "Banff" => "Moraine Lake Banff National Park.jpg",
        "Cox's Bazar" => "Cox's Bazar Sea Beach in 2024.02.jpg",
        "Sundarbans" => "Mangrove Vegetation of Sundarban, West Bengal, India 05.jpg",
    ];

    const HOTELS = [
        "Ubud Garden Resort" => "Viceroy - panoramio.jpg",
        "Alpine Lake Lodge" => "Interlaken20240121151652.jpg",
        "Blue Lagoon Water Villas" => "Diamonds Thudufushi Beach and Water Villas, May 2017 -04.jpg",
        "Caldera View Suites" => "Oia, Santorini sunset.jpg",
        "Bay Breeze Resort" => "Cox's Bazar Sea Beach in 2024.09.jpg",
        "Himalayan View Hotel" => "Ama Dablam, Nepal.jpg",
        "Pine Peak Lodge" => "Moraine Lake Summer Banff National Park.jpg",
        "Mangrove Eco Resort" => "PXL 20250518 070423311.MP Hingalganj mangrove plantation Sundarbans and People and River Icchamati 01.jpg",
    ];

    // - packages that should not reuse the destination photo
    const PACKAGES = [
        "Bali Adventure Week" => "Tanah-Lot Bali Indonesia Pura-Tanah-Lot-01.jpg",
        "Kathmandu and Nagarkot Short Trip" => "Ama Dablam, Nepal.jpg",
    ];
}
