<?php

return [
    // Ongkos kirim tetap
    'ongkir' => 12000,

    'kota' => ['Pekanbaru', 'Kampar', 'Siak'],

    'waktu_tiba' => ['08.00-09.00 WIB', '11.00-12.00 WIB', '17.00-18.00 WIB'],

    // Kode voucher (huruf kapital)
    'vouchers' => [
        'SANGKARASA11' => ['nama' => 'RASARUMAH', 'potongan' => 10000, 'min_belanja' => 50000],
        'HEMAT5'       => ['nama' => 'HEMAT5',    'potongan' => 5000,  'min_belanja' => 25000],
    ],

    // Rekening dummy untuk transfer bank (ganti dengan data asli usaha)
    'rekening' => [
        'BCA'     => '1234567890',
        'Mandiri' => '0987654321',
        'BNI'     => '1122334455',
        'BRI'     => '5566778899',
    ],
    'atas_nama' => 'SangkaRasa Catering',
    'whatsapp' => '6281200000000',
];
