<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Baris Bahasa Validasi
    |--------------------------------------------------------------------------
    |
    | Baris bahasa berikut berisi pesan default kesalahan yang digunakan oleh
    | class validasi. Beberapa aturan memiliki beberapa versi seperti aturan
    | size. Silakan sesuaikan pesan ini sesuai kebutuhan aplikasi Anda.
    |
    */

    'accepted'             => 'Kolom :attribute harus diterima.',
    'accepted_if'          => 'Kolom :attribute harus diterima ketika :other bernilai :value.',
    'active_url'           => 'Kolom :attribute bukan URL yang valid.',
    'after'                => 'Kolom :attribute harus berupa tanggal setelah :date.',
    'after_or_equal'       => 'Kolom :attribute harus berupa tanggal setelah atau sama dengan :date.',
    'alpha'                => 'Kolom :attribute hanya boleh berisi huruf.',
    'alpha_dash'           => 'Kolom :attribute hanya boleh berisi huruf, angka, strip, dan garis bawah.',
    'alpha_num'            => 'Kolom :attribute hanya boleh berisi huruf dan angka.',
    'array'                => 'Kolom :attribute harus berupa array.',
    'ascii'                => 'Kolom :attribute hanya boleh berisi karakter alfanumerik satu-byte dan simbol.',
    'before'               => 'Kolom :attribute harus berupa tanggal sebelum :date.',
    'before_or_equal'      => 'Kolom :attribute harus berupa tanggal sebelum atau sama dengan :date.',
    'between'              => [
        'numeric' => 'Kolom :attribute harus antara :min dan :max.',
        'file'    => 'Kolom :attribute harus antara :min dan :max kilobyte.',
        'string'  => 'Kolom :attribute harus antara :min dan :max karakter.',
        'array'   => 'Kolom :attribute harus memiliki antara :min dan :max item.',
    ],
    'boolean'              => 'Kolom :attribute harus bernilai benar atau salah.',
    'confirmed'            => 'Konfirmasi :attribute tidak cocok.',
    'current_password'     => 'Password salah.',
    'date'                 => 'Kolom :attribute bukan tanggal yang valid.',
    'date_equals'          => 'Kolom :attribute harus berupa tanggal yang sama dengan :date.',
    'date_format'          => 'Kolom :attribute tidak sesuai dengan format :format.',
    'decimal'              => 'Kolom :attribute harus memiliki :decimal angka di belakang koma.',
    'declined'             => 'Kolom :attribute harus ditolak.',
    'declined_if'          => 'Kolom :attribute harus ditolak ketika :other bernilai :value.',
    'different'            => 'Kolom :attribute dan :other harus berbeda.',
    'digits'               => 'Kolom :attribute harus terdiri dari :digits digit.',
    'digits_between'       => 'Kolom :attribute harus antara :min dan :max digit.',
    'dimensions'           => 'Kolom :attribute memiliki dimensi gambar yang tidak valid.',
    'distinct'             => 'Kolom :attribute memiliki nilai duplikat.',
    'doesnt_end_with'      => 'Kolom :attribute tidak boleh diakhiri dengan salah satu dari: :values.',
    'doesnt_start_with'    => 'Kolom :attribute tidak boleh diawali dengan salah satu dari: :values.',
    'email'                => 'Kolom :attribute harus berupa alamat email yang valid.',
    'ends_with'            => 'Kolom :attribute harus diakhiri dengan salah satu dari: :values.',
    'enum'                 => 'Pilihan :attribute tidak valid.',
    'exists'               => 'Pilihan :attribute tidak valid.',
    'file'                 => 'Kolom :attribute harus berupa berkas.',
    'filled'               => 'Kolom :attribute harus memiliki nilai.',
    'gt'                   => [
        'numeric' => 'Kolom :attribute harus lebih besar dari :value.',
        'file'    => 'Kolom :attribute harus lebih besar dari :value kilobyte.',
        'string'  => 'Kolom :attribute harus lebih besar dari :value karakter.',
        'array'   => 'Kolom :attribute harus memiliki lebih dari :value item.',
    ],
    'gte'                  => [
        'numeric' => 'Kolom :attribute harus lebih besar atau sama dengan :value.',
        'file'    => 'Kolom :attribute harus lebih besar atau sama dengan :value kilobyte.',
        'string'  => 'Kolom :attribute harus lebih besar atau sama dengan :value karakter.',
        'array'   => 'Kolom :attribute harus memiliki :value item atau lebih.',
    ],
    'image'                => 'Kolom :attribute harus berupa gambar.',
    'in'                   => 'Pilihan :attribute tidak valid.',
    'in_array'             => 'Kolom :attribute tidak ada di dalam :other.',
    'integer'              => 'Kolom :attribute harus berupa bilangan bulat.',
    'ip'                   => 'Kolom :attribute harus berupa alamat IP yang valid.',
    'ipv4'                 => 'Kolom :attribute harus berupa alamat IPv4 yang valid.',
    'ipv6'                 => 'Kolom :attribute harus berupa alamat IPv6 yang valid.',
    'json'                 => 'Kolom :attribute harus berupa string JSON yang valid.',
    'lowercase'            => 'Kolom :attribute harus berupa huruf kecil.',
    'lt'                   => [
        'numeric' => 'Kolom :attribute harus kurang dari :value.',
        'file'    => 'Kolom :attribute harus kurang dari :value kilobyte.',
        'string'  => 'Kolom :attribute harus kurang dari :value karakter.',
        'array'   => 'Kolom :attribute harus memiliki kurang dari :value item.',
    ],
    'lte'                  => [
        'numeric' => 'Kolom :attribute harus kurang dari atau sama dengan :value.',
        'file'    => 'Kolom :attribute harus kurang dari atau sama dengan :value kilobyte.',
        'string'  => 'Kolom :attribute harus kurang dari atau sama dengan :value karakter.',
        'array'   => 'Kolom :attribute tidak boleh lebih dari :value item.',
    ],
    'mac_address'          => 'Kolom :attribute harus berupa alamat MAC yang valid.',
    'max'                  => [
        'numeric' => 'Kolom :attribute tidak boleh lebih besar dari :max.',
        'file'    => 'Kolom :attribute tidak boleh lebih besar dari :max kilobyte.',
        'string'  => 'Kolom :attribute tidak boleh lebih dari :max karakter.',
        'array'   => 'Kolom :attribute tidak boleh memiliki lebih dari :max item.',
    ],
    'max_digits'           => 'Kolom :attribute tidak boleh memiliki lebih dari :max digit.',
    'mimes'                => 'Kolom :attribute harus berupa file dengan tipe: :values.',
    'mimetypes'            => 'Kolom :attribute harus berupa file dengan tipe: :values.',
    'min'                  => [
        'numeric' => 'Kolom :attribute minimal :min.',
        'file'    => 'Kolom :attribute minimal :min kilobyte.',
        'string'  => 'Kolom :attribute minimal :min karakter.',
        'array'   => 'Kolom :attribute minimal harus memiliki :min item.',
    ],
    'min_digits'           => 'Kolom :attribute harus memiliki minimal :min digit.',
    'missing'              => 'Kolom :attribute harus kosong.',
    'missing_if'           => 'Kolom :attribute harus kosong ketika :other bernilai :value.',
    'missing_unless'       => 'Kolom :attribute harus kosong kecuali :other bernilai :value.',
    'missing_with'         => 'Kolom :attribute harus kosong ketika :values ada.',
    'missing_with_all'     => 'Kolom :attribute harus kosong ketika semua :values ada.',
    'multiple_of'          => 'Kolom :attribute harus kelipatan dari :value.',
    'not_in'               => 'Pilihan :attribute tidak valid.',
    'not_regex'            => 'Format kolom :attribute tidak valid.',
    'numeric'              => 'Kolom :attribute harus berupa angka.',
    'password'             => [
        'letters' => 'Kolom :attribute harus berisi minimal satu huruf.',
        'mixed'   => 'Kolom :attribute harus berisi minimal satu huruf besar dan satu huruf kecil.',
        'numbers' => 'Kolom :attribute harus berisi minimal satu angka.',
        'symbols' => 'Kolom :attribute harus berisi minimal satu simbol.',
        'uncompromised' => 'Kolom :attribute yang dimasukkan muncul dalam kebocoran data. Silakan pilih :attribute lain.',
    ],
    'present'              => 'Kolom :attribute harus ada.',
    'prohibited'           => 'Kolom :attribute dilarang diisi.',
    'prohibited_if'        => 'Kolom :attribute dilarang diisi ketika :other bernilai :value.',
    'prohibited_unless'    => 'Kolom :attribute dilarang diisi kecuali :other ada di :values.',
    'prohibits'            => 'Kolom :attribute melarang :other untuk ada.',
    'regex'                => 'Format kolom :attribute tidak valid.',
    'required'             => 'Kolom :attribute wajib diisi.',
    'required_array_keys'  => 'Kolom :attribute harus berisi entri untuk: :values.',
    'required_if'          => 'Kolom :attribute wajib diisi ketika :other bernilai :value.',
    'required_if_accepted' => 'Kolom :attribute wajib diisi ketika :other diterima.',
    'required_unless'      => 'Kolom :attribute wajib diisi kecuali :other ada di :values.',
    'required_with'        => 'Kolom :attribute wajib diisi ketika :values ada.',
    'required_with_all'    => 'Kolom :attribute wajib diisi ketika :values ada.',
    'required_without'     => 'Kolom :attribute wajib diisi ketika :values tidak ada.',
    'required_without_all' => 'Kolom :attribute wajib diisi ketika tidak ada satupun dari :values ada.',
    'same'                 => 'Kolom :attribute dan :other harus sama.',
    'size'                 => [
        'numeric' => 'Kolom :attribute harus berukuran :size.',
        'file'    => 'Kolom :attribute harus berukuran :size kilobyte.',
        'string'  => 'Kolom :attribute harus berukuran :size karakter.',
        'array'   => 'Kolom :attribute harus mengandung :size item.',
    ],
    'starts_with'          => 'Kolom :attribute harus diawali dengan salah satu dari: :values.',
    'string'               => 'Kolom :attribute harus berupa teks.',
    'timezone'             => 'Kolom :attribute harus berupa zona waktu yang valid.',
    'unique'               => 'Kolom :attribute sudah digunakan.',
    'uploaded'             => 'Kolom :attribute gagal diunggah.',
    'uppercase'            => 'Kolom :attribute harus berupa huruf besar.',
    'url'                  => 'Format kolom :attribute tidak valid.',
    'ulid'                 => 'Kolom :attribute harus berupa ULID yang valid.',
    'uuid'                 => 'Kolom :attribute harus berupa UUID yang valid.',

    /*
    |--------------------------------------------------------------------------
    | Pesan Kustom Validasi
    |--------------------------------------------------------------------------
    |
    | Di sini Anda dapat menentukan pesan validasi khusus untuk atribut dengan
    | menggunakan konvensi "attribute.rule" untuk menamai baris. Hal ini membuat
    | cepat dalam menentukan pesan bahasa khusus untuk aturan tertentu.
    |
    */

    'custom' => [
        'email' => [
            'required' => 'Alamat email wajib diisi.',
            'email'    => 'Alamat email tidak valid.',
        ],
        'password' => [
            'required' => 'Password wajib diisi.',
            'min'      => 'Password minimal harus :min karakter.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Atribut Validasi Kustom
    |--------------------------------------------------------------------------
    |
    | Baris berikut digunakan untuk mengganti placeholder atribut dengan sesuatu
    | yang lebih ramah pembaca, seperti "Alamat Email" daripada "email".
    |
    */

    'attributes' => [
        'name'     => 'Nama',
        'email'    => 'Alamat Email',
        'password' => 'Kata Sandi',
    ],

];
