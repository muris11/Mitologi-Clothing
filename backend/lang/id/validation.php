<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Baris-baris Bahasa untuk Validasi
    |--------------------------------------------------------------------------
    |
    | Baris-baris berikut ini berisi pesan standar kesalahan yang digunakan oleh
    | kelas validator. Beberapa aturan mempunyai banyak versi seperti aturan
    | 'size'. Jangan ragu untuk mengoptimalkan setiap pesan yang ada di sini.
    |
    */

    'accepted'        => 'Isian :attribute harus diterima.',
    'active_url'      => 'Isian :attribute bukan URL yang valid.',
    'after'           => 'Isian :attribute harus tanggal setelah :date.',
    'after_or_equal'  => 'Isian :attribute harus tanggal setelah atau sama dengan :date.',
    'alpha'           => 'Isian :attribute hanya boleh berisi huruf.',
    'alpha_dash'      => 'Isian :attribute hanya boleh berisi huruf, angka, dan strip.',
    'alpha_num'       => 'Isian :attribute hanya boleh berisi huruf dan angka.',
    'array'           => 'Isian :attribute harus berupa sebuah array.',
    'before'          => 'Isian :attribute harus tanggal sebelum :date.',
    'before_or_equal' => 'Isian :attribute harus tanggal sebelum atau sama dengan :date.',
    'between'         => [
        'numeric' => 'Isian :attribute harus antara :min dan :max.',
        'file'    => 'Isian :attribute harus antara :min dan :max kilobytes.',
        'string'  => 'Isian :attribute harus antara :min dan :max karakter.',
        'array'   => 'Isian :attribute harus antara :min dan :max item.',
    ],
    'boolean'         => 'Isian :attribute harus berupa true atau false.',
    'confirmed'       => 'Konfirmasi :attribute tidak cocok.',
    'date'            => 'Isian :attribute bukan tanggal yang valid.',
    'date_equals'     => 'Isian :attribute harus tanggal yang sama dengan :date.',
    'date_format'     => 'Isian :attribute tidak cocok dengan format :format.',
    'different'       => 'Isian :attribute dan :other harus berbeda.',
    'digits'          => 'Isian :attribute harus berupa angka :digits.',
    'digits_between'  => 'Isian :attribute harus antara angka :min dan :max.',
    'dimensions'      => 'Bidang :attribute tidak memiliki dimensi gambar yang valid.',
    'distinct'        => 'Bidang :attribute memiliki nilai yang duplikat.',
    'email'           => 'Isian :attribute harus berupa alamat surel yang valid.',
    'ends_with'       => 'Isian :attribute harus diakhiri dengan: :values.',
    'exists'          => 'Isian :attribute yang dipilih tidak valid.',
    'file'            => 'Bidang :attribute harus berupa sebuah berkas.',
    'filled'          => 'Isian :attribute harus memiliki nilai.',
    'gt'              => [
        'numeric' => 'Isian :attribute harus lebih besar dari :value.',
        'file'    => 'Isian :attribute harus lebih besar dari :value kilobytes.',
        'string'  => 'Isian :attribute harus lebih besar dari :value karakter.',
        'array'   => 'Isian :attribute harus lebih dari :value item.',
    ],
    'gte'             => [
        'numeric' => 'Isian :attribute harus lebih besar dari atau sama dengan :value.',
        'file'    => 'Isian :attribute harus lebih besar dari atau sama dengan :value kilobytes.',
        'string'  => 'Isian :attribute harus lebih besar dari atau sama dengan :value karakter.',
        'array'   => 'Isian :attribute harus mempunyai :value item atau lebih.',
    ],
    'image'           => 'Isian :attribute harus berupa gambar.',
    'in'              => 'Isian :attribute yang dipilih tidak valid.',
    'in_array'        => 'Bidang :attribute tidak ada di dalam :other.',
    'integer'         => 'Isian :attribute harus merupakan bilangan bulat.',
    'ip'              => 'Isian :attribute harus berupa alamat IP yang valid.',
    'ipv4'            => 'Isian :attribute harus berupa alamat IPv4 yang valid.',
    'ipv6'            => 'Isian :attribute harus berupa alamat IPv6 yang valid.',
    'json'            => 'Isian :attribute harus berupa JSON string yang valid.',
    'lt'              => [
        'numeric' => 'Isian :attribute harus kurang dari :value.',
        'file'    => 'Isian :attribute harus kurang dari :value kilobytes.',
        'string'  => 'Isian :attribute harus kurang dari :value karakter.',
        'array'   => 'Isian :attribute harus kurang dari :value item.',
    ],
    'lte'             => [
        'numeric' => 'Isian :attribute harus kurang dari atau sama dengan :value.',
        'file'    => 'Isian :attribute harus kurang dari atau sama dengan :value kilobytes.',
        'string'  => 'Isian :attribute harus kurang dari atau sama dengan :value karakter.',
        'array'   => 'Isian :attribute harus tidak lebih dari :value item.',
    ],
    'max'             => [
        'numeric' => 'Isian :attribute seharusnya tidak lebih dari :max.',
        'file'    => 'Isian :attribute seharusnya tidak lebih dari :max kilobytes.',
        'string'  => 'Isian :attribute seharusnya tidak lebih dari :max karakter.',
        'array'   => 'Isian :attribute seharusnya tidak lebih dari :max item.',
    ],
    'mimes'           => 'Isian :attribute harus dokumen berjenis : :values.',
    'mimetypes'       => 'Isian :attribute harus dokumen berjenis : :values.',
    'min'             => [
        'numeric' => 'Isian :attribute harus minimal :min.',
        'file'    => 'Isian :attribute harus minimal :min kilobytes.',
        'string'  => 'Isian :attribute harus minimal :min karakter.',
        'array'   => 'Isian :attribute harus minimal :min item.',
    ],
    'not_in'          => 'Isian :attribute yang dipilih tidak valid.',
    'not_regex'       => 'Format isian :attribute tidak valid.',
    'numeric'         => 'Isian :attribute harus berupa angka.',
    'password'        => 'Kata sandi salah.',
    'present'         => 'Bidang :attribute wajib ada.',
    'regex'           => 'Format isian :attribute tidak valid.',
    'required'        => 'Bidang :attribute wajib diisi.',
    'required_if'     => 'Bidang :attribute wajib diisi bila :other adalah :value.',
    'required_unless' => 'Bidang :attribute wajib diisi kecuali :other memiliki nilai :values.',
    'required_with'   => 'Bidang :attribute wajib diisi bila terdapat :values.',
    'required_with_all' => 'Bidang :attribute wajib diisi bila terdapat :values.',
    'required_without' => 'Bidang :attribute wajib diisi bila tidak terdapat :values.',
    'required_without_all' => 'Bidang :attribute wajib diisi bila tidak terdapat ada :values.',
    'same'            => 'Isian :attribute dan :other harus sama.',
    'size'            => [
        'numeric' => 'Isian :attribute harus berukuran :size.',
        'file'    => 'Isian :attribute harus berukuran :size kilobyte.',
        'string'  => 'Isian :attribute harus berukuran :size karakter.',
        'array'   => 'Isian :attribute harus mengandung :size item.',
    ],
    'starts_with'     => 'Isian :attribute harus diawali dengan salah satu dari berikut ini: :values.',
    'string'          => 'Isian :attribute harus berupa string.',
    'timezone'        => 'Isian :attribute harus berupa zona waktu yang valid.',
    'unique'          => 'Isian :attribute sudah ada sebelumnya.',
    'uploaded'        => 'Isian :attribute gagal diunggah.',
    'url'             => 'Format isian :attribute tidak valid.',
    'uuid'            => 'Isian :attribute harus merupakan UUID yang valid.',

    /*
    |--------------------------------------------------------------------------
    | Kustomisasi Baris-baris Bahasa untuk Validasi
    |--------------------------------------------------------------------------
    |
    | Di sini Anda dapat menentukan pesan validasi kustom untuk atribut dengan
    | menggunakan konvensi "attribute.rule" dalam penamaan barisnya. Hal ini
    | cepat dalam menentukan baris bahasa kustom yang spesifik untuk aturan
    | atribut yang diberikan.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Atribut Validasi Kustom
    |--------------------------------------------------------------------------
    |
    | Baris-baris berikut digunakan untuk menukar 'placeholder' atribut dengan
    | sesuatu yang lebih mudah dimengerti oleh pembaca seperti "Alamat Surel"
    | daripada "email". Hal ini membantu kita dalam membuat pesan menjadi lebih
    | bersih.
    |
    */

    'attributes' => [],

];
