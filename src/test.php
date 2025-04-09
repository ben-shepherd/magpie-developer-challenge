<?php

require 'vendor/autoload.php';

// $dateCombinations = [
//     'Delivery by Thursday 10th Apr 2025',
//     'Available on 16 Apr 2025',
//     'Delivery by Thursday 10th Apr 2025',
//     'Available on 16 Apr 2025', 
//     'Free Shipping',
//     'Delivery from Friday 9th May 2025',
//     'Delivers Wednesday 9th Apr 2025',
//     'Free Delivery 2025-04-10',
//     'Free Delivery tomorrow',
//     'Delivers Wednesday 9th Apr 2025',
//     'Free Delivery 2025-04-10',
//     'Delivers 9 Apr 2025',
//     'Order within 6 hours and have it 11 Apr 2025',
//     'Free Delivery Thursday 10th Apr 2025',
//     'Free Delivery Thursday 10th Apr 2025'
// ];

$untrimmed = "\n                    \n                        \n                            iPhone 11\n                            64GB\n                        \n                        \n                        \n                            \n                                                                    \n                                        \n                                    \n                                                                    \n                                        \n                                    \n                                                            \n                        \n                        \n                            \u00a3699.99                        \n                        \n                            Availability: In Stock at B90 4SB                        \n                                                    \n                                Unavailable for delivery                            \n                                            \n                ";
$trimmed = str_replace("\n", "", $untrimmed);
$trimmed = trim(preg_replace('/\s\s+/', ' ', $trimmed));

echo $trimmed;
