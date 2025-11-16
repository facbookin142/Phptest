<?php
// ------------------------------
// CONFIG
// ------------------------------

// Updated WhatsApp numbers
$numbers = [
    "919088780393",
    "919219516478",
    "919482399346"
];

// Telegram Link
$telegram = "https://t.me/+3t2rF83CNHI1OWRl";

// ------------------------------
// COOKIE HANDLERS
// ------------------------------
if (!isset($_COOKIE['flow_pos'])) {
    $pos = 0;
} else {
    $pos = intval($_COOKIE['flow_pos']) % 8;
}

if (!isset($_COOKIE['num_idx'])) {
    // random starting index
    $num_idx = rand(0, count($numbers)-1);
} else {
    $num_idx = intval($_COOKIE['num_idx']);
    if ($num_idx < 0 || $num_idx >= count($numbers)) $num_idx = 0;
}

// ------------------------------
// FUNCTIONS
// ------------------------------
function redirect($url) {
    header("Location: $url");
    exit();
}

function whatsappUrl($num) {
    return "https://api.whatsapp.com/send?phone=$num";
}

// ------------------------------
// MAIN FLOW (8-STEP CYCLE)
// ------------------------------
// Cycle:
// 0 → WA
// 1 → WA
// 2 → TG
// 3 → TG
// 4 → TG
// 5 → WA
// 6 → WA
// 7 → TG
// (repeat)

if (in_array($pos, [0,1,5,6])) {
    // serve WhatsApp number
    $selected = $numbers[$num_idx];

    // advance index for next time
    $new_idx = ($num_idx + 1) % count($numbers);
    setcookie("num_idx", $new_idx, time() + (3600*24*365));

    // advance cycle pos
    $new_pos = ($pos + 1) % 8;
    setcookie("flow_pos", $new_pos, time() + (3600*24*365));

    redirect( whatsappUrl($selected) );

} else {
    // Telegram open (single in PHP version)
    // (PHP cannot open multiple tabs, browser will do single redirect)

    $new_pos = ($pos + 1) % 8;
    setcookie("flow_pos", $new_pos, time() + (3600*24*365));

    redirect($telegram);
}

?>
