<?php
$ta = file_get_contents(sys_get_temp_dir() . '/osa-ta.html');
$en = file_get_contents(sys_get_temp_dir() . '/osa-en.html');
if ($ta === false || $en === false) {
    fwrite(STDERR, "Missing temp HTML files\n");
    exit(1);
}
echo 'ta_bytes=' . strlen($ta) . PHP_EOL;
echo 'en_bytes=' . strlen($en) . PHP_EOL;
echo 'ta_has_mukappu=' . (str_contains($ta, 'முகப்பு') ? 'yes' : 'no') . PHP_EOL;
echo 'en_has_Home=' . (str_contains($en, '>Home<') ? 'yes' : 'no') . PHP_EOL;
echo 'ta_has_Home=' . (str_contains($ta, '>Home<') ? 'yes' : 'no') . PHP_EOL;
echo 'en_has_tamil_about=' . (str_contains($en, 'பாரம்பரியத்தை') ? 'yes' : 'no') . PHP_EOL;
echo 'en_has_preserving=' . (str_contains($en, 'Preserving tradition') ? 'yes' : 'no') . PHP_EOL;
echo 'ta_has_preserving=' . (str_contains($ta, 'Preserving tradition') ? 'yes' : 'no') . PHP_EOL;
preg_match('/<html[^>]*lang="(ta|en)"/', $ta, $m1);
preg_match('/<html[^>]*lang="(ta|en)"/', $en, $m2);
echo 'ta_html_lang=' . ($m1[1] ?? '?') . PHP_EOL;
echo 'en_html_lang=' . ($m2[1] ?? '?') . PHP_EOL;
preg_match('/class="([^"]*lang-[^"]*)"/', $ta, $c1);
preg_match('/class="([^"]*lang-[^"]*)"/', $en, $c2);
echo 'ta_class=' . ($c1[1] ?? '?') . PHP_EOL;
echo 'en_class=' . ($c2[1] ?? '?') . PHP_EOL;
