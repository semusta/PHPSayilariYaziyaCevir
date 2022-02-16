<?php

/**
 * Ondalık kısımlı ve tam sayıları yazıya çevirir
 * Çevrilcek sayi "" işaretleri içinde ondalık kısım virgül
 * olacak şekilde girilmelidir.
 * Örnek SayiCevir::cevir("123456,12");
 * string olarak geri dönüş sağlar
 */

class SayiCevir
{
    /**
     * @param $sayi
     * @return string
     * SayiCevir::cevir("123456,12")
     */
    public static function cevir($sayi)
    {
        //sayı kontrolu geçerli değilse mesaj döndür
        if(!preg_match('/^\d+,\d+$|^\d+$/', $sayi)) {
            return 'Girilen sayı geçerli değil !';
        }

        if (strpos($sayi, ',')) {
            $sayi = explode(',', $sayi);
            $tam = self::cevir2($sayi[0]);
            $ondalik = self::cevir2($sayi[1]);
            return $tam . ' tl ' . $ondalik . ' kuruş';
        }

        return self::cevir2($sayi) . ' tl';
    }

    /**
     * @param $sayi
     * @return string
     * verilen sayıyı yazı olarak geri döndürür
     */
    private static function cevir2($sayi)
    {
        //rakamların ve kısımların yazı karşılıkları
        $trRakam = ["", "bir", "iki", "üç", "dört", "beş", "altı", "yedi", "sekiz", "dokuz"];
        $trOnlar = ["", "on", "yirmi", "otuz", "kırk", "elli", "altmış", "yetmiş", "seksen", "doksan"];
        $trBinler = ["", "bin", "milyon", "milyar", "trilyon", "kattrilyon"];

        $bSay = strlen($sayi);
        $rakamlar = str_split($sayi);

        $basamaklar = [];
        $b = 0;

        /*
         * 3lü kısım halinde rakamlar parçalanıyor
         * ve diziye ekleniyor
         */
        for ($i = 0; $i < count($rakamlar); $i++) {
            @$basamaklar[$b] .= $rakamlar[--$bSay];
            if ((($i + 1) % 3) == 0)
                $b++;
        }

        /*
         * dizideki her üçlü hane yazıya çeviriliyor
         */
        for ($i = 0; $i < count($basamaklar); $i++) {
            @$birler = $basamaklar[$i][0];
            @$onlar = $basamaklar[$i][1];
            @$yuzler = $basamaklar[$i][2];

            $yuz = $yuzler == 0 ? '' : ' yüz ';

            if ($yuzler == 1) $yuzler--;

            @$islem[$i] = trim($trRakam[$yuzler] . $yuz . $trOnlar[$onlar] . ' ' . $trRakam[$birler]);
        }

        $sonuc = '';

        /*
         * islem dizisinde elden edilen kısımlar
         * bin milyon milyar ... olarak
         * son kısım elde ediliyor
         */
        for ($i = 0; $i < count($islem); $i++) {
            if (empty($islem[$i])) continue;
            if ($i == 1) {
                if ($islem[$i] == 'bir') {
                    $islem[$i] = '';
                }
            }
            $sonuc = $islem[$i] . ' ' . $trBinler[$i] . ' ' . $sonuc;
        }

        $sonuc = preg_replace('/\s+/', ' ', $sonuc);
        $sonuc = trim($sonuc);

        return $sonuc;
    }
}

//örnek kullanım
echo SayiCevir::cevir("100,10") . "\n";
echo SayiCevir::cevir("13456213,1245") . "\n";
echo SayiCevir::cevir("1000000100,110110") . "\n";
echo SayiCevir::cevir("1541510101510") . "\n";
echo SayiCevir::cevir("1000dsf100100100") . "\n"; //hata verir sayı içinde harf var
