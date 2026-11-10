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
        ["Kyoto, Japan", "Double torii path at Fushimi Inari Taisha Shrine, Kyoto, Japan.jpg"],
        ["Santorini, Greece", "Oia - Santorini 2019.jpg"],
        ["Iceland", "Skógafoss Waterfall, Iceland, 20240720 1411 3075.jpg"],
        ["Maldives", "Anantara Kihavah - Aerial Hero Shot 2024.jpg"],
        ["Machu Picchu, Peru", "Machu Picchu, Perú, 2015-07-30, DD 60.JPG"],
        ["Banff, Canada", "Moraine Lake Banff National Park.jpg"],
    ];

    const DESTINATIONS = [
        "Bali" => "Tegallalang Rice Terraces Bali 1.jpg",
        "Swiss Alps" => "ThunerseeInterlaken.jpg",
        "Cliffs of Moher" => "Cliffs of Moher 20160818-1.jpg",
        "Everest Region" => "Sunset view of Everest.jpg",
        "Maldives" => "Anantara Kihavah - Aerial Hero Shot 2024.jpg",
        "Santorini" => "Oia - Santorini 2019.jpg",
        "Banff" => "Moraine Lake Banff National Park.jpg",
        "Kyoto" => "Double torii path at Fushimi Inari Taisha Shrine, Kyoto, Japan.jpg",
        "Iceland" => "Skógafoss Waterfall, Iceland, 20240720 1411 3075.jpg",
        "Machu Picchu" => "Machu Picchu, Perú, 2015-07-30, DD 60.JPG",
        "Dubai" => "Dubai Skyline mit Burj Khalifa (18241030269).jpg",
    ];

    const HOTELS = [
        "Ubud Garden Resort" => "Viceroy - panoramio.jpg",
        "Alpine Lake Lodge" => "Interlaken20240121151652.jpg",
        "Blue Lagoon Water Villas" => "Diamonds Thudufushi Beach and Water Villas, May 2017 -04.jpg",
        "Caldera View Suites" => "Oia, Santorini sunset.jpg",
        "Gion Garden Ryokan" => "Bamboo Forest, Arashiyama, Kyoto, Japan.jpg",
        "Himalayan View Hotel" => "Ama Dablam, Nepal.jpg",
        "Pine Peak Lodge" => "Moraine Lake Summer Banff National Park.jpg",
        "Northern Lights Lodge" => "Kirkjufell, Iceland, 20240714 1631 0713.jpg",
        "Inca Trail Boutique Hotel" => "Machu Picchu, Perú, 2015-07-30, DD 47.JPG",
        "Marina Sky Hotel" => "Burj Khalifa from the sea, Dubai.jpg",
    ];

    // - packages that should not reuse the destination photo
    const PACKAGES = [
        "Bali Adventure Week" => "Tanah-Lot Bali Indonesia Pura-Tanah-Lot-01.jpg",
        "Kathmandu and Nagarkot Short Trip" => "Ama Dablam, Nepal.jpg",
        "Kyoto Temples Weekend" => "Water reflection of Kinkaku-ji Temple a sunny day, Kyoto, Japan.jpg",
        "Kyoto and Nara Culture Trip" => "Kiyomizu-dera, Kyoto, November 2016 -01.jpg",
        "Iceland Golden Circle Escape" => "Gullfoss, Iceland, 20230501 0944 3788.jpg",
        "Machu Picchu and Rainbow Mountain" => "Vinicunca (Rainbow Mountain).jpg",
        "Dubai City and Desert Break" => "Burj Al Arab Hotel, Dubai, UAE (Ank Kumar, Infosys) 07.jpg",
    ];

    // - big background photos for promo bands
    const BANDS = [
        "iceland" => "Skógafoss Waterfall, Iceland, 20240720 1411 3075.jpg",
        "santorini" => "Oia - Santorini 2019.jpg",
        "maldives" => "Anantara Kihavah - Aerial Hero Shot 2024.jpg",
        "kyoto" => "Bamboo Forest, Arashiyama, Kyoto, Japan.jpg",
        "machu" => "Machu Picchu, Perú, 2015-07-30, DD 60.JPG",
        "bali" => "Tegallalang Rice Terraces Bali 1.jpg",
        "banff" => "Moraine Lake Banff National Park.jpg",
    ];
}
