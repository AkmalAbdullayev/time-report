<?php
function secToTime($seconds)
{
    $t = round($seconds);
    return sprintf('%02d:%02d:%02d', ($t / 3600), ($t / 60 % 60), $t % 60);
}

function getDayNameFromNumber($day)
{
    $arr = [
        1 => 'Mon',
        2 => 'Tue',
        3 => 'Wed',
        4 => 'Thu',
        5 => 'Fri',
        6 => 'Sat',
        7 => 'Sun'
    ];

    return $arr[$day];
}

function getWeekDays()
{
    return [
        [
            'number' => 0,
            'day' => 'Понедельник'
        ],
        [
            'number' => 1,
            'day' => 'Вторник'
        ],
        [
            'number' => 2,
            'day' => 'Среда'
        ],
        [
            'number' => 3,
            'day' => 'Четверг'
        ],
        [
            'number' => 4,
            'day' => 'Пятница'
        ],
        [
            'number' => 5,
            'day' => 'Суббота'
        ],
        [
            'number' => 6,
            'day' => 'Воскресенье'
        ],
    ];
}

function getDayName($dayNumber = 0)
{
    $days = [
        0 => 'Понедельник',
        1 => 'Вторник',
        2 => 'Среда',
        3 => 'Четверг',
        4 => 'Пятница',
        5 => 'Суббота',
        6 => 'Воскресенье'
    ];

    return $days[$dayNumber];
}

function getAuthenticationModes($number = null)
{
    $modes = [
        0 => 'All',
        1 => 'Card',
        2 => 'FP',
        3 => 'Face',
        4 => 'Card/FP',
        5 => 'Card/Face',
        6 => 'FP/Face',
        7 => 'Card/FP/Face'
    ];

    if ($number === null)
        return $modes;
    else
        return $modes[$number];
}
