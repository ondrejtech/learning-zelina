<?php

function print_r_reverse($input) {
        $lines = preg_split('#\r?\n#', trim($input));
        if (trim($lines[ 0 ]) != 'Array' && trim($lines[ 0 ]) != 'stdClass Object') {
            // bottomed out to something that isn't an array or object
            if ($input === '') {
                return null;
            }
            
            return $input;
        } else {
            // this is an array or object, lets parse it
            $match = array();
            if (preg_match("/(\s{5,})\(/", $lines[ 1 ], $match)) {
                // this is a tested array/recursive call to this function
                // take a set of spaces off the beginning
                $spaces = $match[ 1 ];
                $spaces_length = strlen($spaces);
                $lines_total = count($lines);
                for ($i = 0; $i < $lines_total; $i++) {
                    if (substr($lines[ $i ], 0, $spaces_length) == $spaces) {
                        $lines[ $i ] = substr($lines[ $i ], $spaces_length);
                    }
                }
            }
            $is_object = trim($lines[ 0 ]) == 'stdClass Object';
            array_shift($lines); // Array
            array_shift($lines); // (
            array_pop($lines); // )
            $input = implode("\n", $lines);
            $matches = array();
            // make sure we only match stuff with 4 preceding spaces (stuff for this array and not a nested one)
            preg_match_all("/^\s{4}\[(.+?)\] \=\> /m", $input, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
            $pos = array();
            $previous_key = '';
            $in_length = strlen($input);
            // store the following in $pos:
            // array with key = key of the parsed array's item
            // value = array(start position in $in, $end position in $in)
            foreach ($matches as $match) {
                $key = $match[ 1 ][ 0 ];
                $start = $match[ 0 ][ 1 ] + strlen($match[ 0 ][ 0 ]);
                $pos[ $key ] = array($start, $in_length);
                if ($previous_key != '') {
                    $pos[ $previous_key ][ 1 ] = $match[ 0 ][ 1 ] - 1;
                }
                $previous_key = $key;
            }
            $ret = array();
            foreach ($pos as $key => $where) {
                // recursively see if the parsed out value is an array too
                $ret[ $key ] = print_r_reverse(substr($input, $where[ 0 ], $where[ 1 ] - $where[ 0 ]));
            }
            
            return $is_object ? (object)$ret : $ret;
        }
    }

print_r(print_r_reverse("
(
    [0] => 1
    [1] => 1
    [2] => 1
    [3] => 3
    [4] => 1
    [5] => 1
    [6] => 2
    [7] => 1
    [8] => 6
    [9] => 7
    [10] => 7
    [11] => 5
    [12] => 5
    [13] => 3
    [14] => 2
    [15] => 7
    [16] => 1
    [17] => 3
    [18] => 3
    [19] => 5
    [20] => 1
    [21] => 6
    [22] => 3
    [23] => 6
    [24] => 5
    [25] => 4
    [26] => 2
    [27] => 7
    [28] => 3
    [29] => 9
    [30] => 7
    [31] => 4
    [32] => 9
    [33] => 7
    [34] => 9
    [35] => 7
    [36] => 9
    [37] => 9
    [38] => 6
    [39] => 6
    [40] => 5
    [41] => 6
    [42] => 8
    [43] => 3
    [44] => 4
    [45] => 5
    [46] => 4
    [47] => 4
    [48] => 2
    [49] => 4
    [50] => 4
    [51] => 7
    [52] => 8
    [53] => 6
    [54] => 4
    [55] => 4
    [56] => 8
    [57] => 1
    [58] => 6
    [59] => 7
    [60] => 6
    [61] => 8
    [62] => 4
    [63] => 4
    [64] => 1
    [65] => 8
    [66] => 8
    [67] => 9
    [68] => 10
    [69] => 10
    [70] => 7
    [71] => 8
    [72] => 8
    [73] => 3
    [74] => 8
    [75] => 7
    [76] => 5
    [77] => 8
    [78] => 3
    [79] => 8
    [80] => 8
    [81] => 8
    [82] => 8
    [83] => 8
    [84] => 8
    [85] => 3
    [86] => 3
    [87] => 6
    [88] => 4
    [89] => 7
    [90] => 4
    [91] => 4
    [92] => 7
    [93] => 8
    [94] => 8
    [95] => 9
    [96] => 9
    [97] => 4
    [98] => 4
    [99] => 3
    [100] => 8
    [101] => 8
    [102] => 5
    [103] => 5
    [104] => 8
    [105] => 8
    [106] => 8
    [107] => 6
    [108] => 7
    [109] => 7
    [110] => 8
    [111] => 8
    [112] => 8
    [113] => 7
    [114] => 8
    [115] => 8
    [116] => 8
    [117] => 8
    [118] => 8
    [119] => 8
    [120] => 9
    [121] => 4
    [122] => 5
    [123] => 8
    [124] => 8
    [125] => 6
    [126] => 3
    [127] => 5
    [128] => 8
    [129] => 7
    [130] => 7
    [131] => 5
    [132] => 8
    [133] => 6
    [134] => 8
    [135] => 8
    [136] => 8
    [137] => 8
    [138] => 8
    [139] => 8
    [140] => 8
    [141] => 8
    [142] => 8
    [143] => 8
    [144] => 8
    [145] => 8
    [146] => 8
    [147] => 1
    [148] => 4
    [149] => 8
    [150] => 8
    [151] => 8
    [152] => 8
    [153] => 8
    [154] => 8
    [155] => 8
    [156] => 8
    [157] => 3
    [158] => 3
    [159] => 4
    [160] => 3
    [161] => 6
    [162] => 4
    [163] => 6
    [164] => 7
    [165] => 5
    [166] => 5
    [167] => 4
    [168] => 6
    [169] => 1
    [170] => 7
    [171] => 4
    [172] => 6
    [173] => 4
    [174] => 7
    [175] => 8
    [176] => 7
)
"));

?>