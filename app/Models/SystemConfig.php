<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemConfig extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Ambil nilai konfigurasi berdasarkan key.
     * Jika tidak ada, kembalikan $default.
     */
    public static function getValue(string $key, mixed $default = null): mixed
    {
        $config = static::where('key', $key)->first();
        return $config ? $config->value : $default;
    }

    /**
     * Simpan atau update nilai konfigurasi.
     */
    public static function setValue(string $key, mixed $value): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }
<<<<<<< HEAD
}
=======
}
>>>>>>> d3b8efd95c1fd8065ace124ea5abb73573121522
