<?php

namespace App\Http\Controllers\Games\SpinData\FortuneTiger;

class FortuneTigerBonus
{
    /**
     * Bonus entries: grids with 3+ Symbol_6 (scatter) to trigger free spins.
     * Symbol_6 is the scatter symbol. When 3 appear anywhere on the 3x3 grid,
     * the bonus round (free spins) is triggered.
     *
     * @return array
     */
    public static function getBonus(): array
    {
        return [
            // 3 scatter across top row [1,2,3]
            [
                [
                    "Symbol_6", "Symbol_6", "Symbol_6", "Symbol_5", "Symbol_3", "Symbol_4", "Symbol_2", "Symbol_5", "Symbol_3"
                ],
                [1, 2, 3],
                [
                    [
                        "index" => 0,
                        "name" => "Symbol_6",
                        "combine" => 3,
                        "way_243" => 1,
                        "payout" => 3,
                        "multiply" => 0,
                        "win_amount" => 0,
                        "active_icon" => [
                            1,
                            2,
                            3
                        ]
                    ]
                ],
                [],
                0,
                3
            ],
            // 3 scatter across middle row [4,5,6]
            [
                [
                    "Symbol_3", "Symbol_5", "Symbol_2", "Symbol_6", "Symbol_6", "Symbol_6", "Symbol_4", "Symbol_3", "Symbol_5"
                ],
                [4, 5, 6],
                [
                    [
                        "index" => 1,
                        "name" => "Symbol_6",
                        "combine" => 3,
                        "way_243" => 1,
                        "payout" => 3,
                        "multiply" => 0,
                        "win_amount" => 0,
                        "active_icon" => [
                            4,
                            5,
                            6
                        ]
                    ]
                ],
                [],
                0,
                3
            ],
            // 3 scatter across bottom row [7,8,9]
            [
                [
                    "Symbol_4", "Symbol_2", "Symbol_5", "Symbol_3", "Symbol_4", "Symbol_2", "Symbol_6", "Symbol_6", "Symbol_6"
                ],
                [7, 8, 9],
                [
                    [
                        "index" => 2,
                        "name" => "Symbol_6",
                        "combine" => 3,
                        "way_243" => 1,
                        "payout" => 3,
                        "multiply" => 0,
                        "win_amount" => 0,
                        "active_icon" => [
                            7,
                            8,
                            9
                        ]
                    ]
                ],
                [],
                0,
                3
            ],
            // 3 scatter diagonal [1,5,9]
            [
                [
                    "Symbol_6", "Symbol_3", "Symbol_5", "Symbol_4", "Symbol_6", "Symbol_2", "Symbol_3", "Symbol_5", "Symbol_6"
                ],
                [1, 5, 9],
                [
                    [
                        "index" => 3,
                        "name" => "Symbol_6",
                        "combine" => 3,
                        "way_243" => 1,
                        "payout" => 3,
                        "multiply" => 0,
                        "win_amount" => 0,
                        "active_icon" => [
                            1,
                            5,
                            9
                        ]
                    ]
                ],
                [],
                0,
                3
            ],
            // 3 scatter anti-diagonal [3,5,7]
            [
                [
                    "Symbol_2", "Symbol_4", "Symbol_6", "Symbol_5", "Symbol_6", "Symbol_3", "Symbol_6", "Symbol_2", "Symbol_5"
                ],
                [3, 5, 7],
                [
                    [
                        "index" => 4,
                        "name" => "Symbol_6",
                        "combine" => 3,
                        "way_243" => 1,
                        "payout" => 3,
                        "multiply" => 0,
                        "win_amount" => 0,
                        "active_icon" => [
                            3,
                            5,
                            7
                        ]
                    ]
                ],
                [],
                0,
                3
            ],
            // 3 scatter left column [1,4,7]
            [
                [
                    "Symbol_6", "Symbol_2", "Symbol_5", "Symbol_6", "Symbol_3", "Symbol_4", "Symbol_6", "Symbol_5", "Symbol_3"
                ],
                [1, 4, 7],
                [
                    [
                        "index" => 5,
                        "name" => "Symbol_6",
                        "combine" => 3,
                        "way_243" => 1,
                        "payout" => 3,
                        "multiply" => 0,
                        "win_amount" => 0,
                        "active_icon" => [
                            1,
                            4,
                            7
                        ]
                    ]
                ],
                [],
                0,
                3
            ],
            // 3 scatter middle column [2,5,8]
            [
                [
                    "Symbol_3", "Symbol_6", "Symbol_4", "Symbol_5", "Symbol_6", "Symbol_2", "Symbol_4", "Symbol_6", "Symbol_3"
                ],
                [2, 5, 8],
                [
                    [
                        "index" => 6,
                        "name" => "Symbol_6",
                        "combine" => 3,
                        "way_243" => 1,
                        "payout" => 3,
                        "multiply" => 0,
                        "win_amount" => 0,
                        "active_icon" => [
                            2,
                            5,
                            8
                        ]
                    ]
                ],
                [],
                0,
                3
            ],
            // 3 scatter right column [3,6,9]
            [
                [
                    "Symbol_5", "Symbol_3", "Symbol_6", "Symbol_2", "Symbol_4", "Symbol_6", "Symbol_3", "Symbol_5", "Symbol_6"
                ],
                [3, 6, 9],
                [
                    [
                        "index" => 7,
                        "name" => "Symbol_6",
                        "combine" => 3,
                        "way_243" => 1,
                        "payout" => 3,
                        "multiply" => 0,
                        "win_amount" => 0,
                        "active_icon" => [
                            3,
                            6,
                            9
                        ]
                    ]
                ],
                [],
                0,
                3
            ],
        ];
    }
}
