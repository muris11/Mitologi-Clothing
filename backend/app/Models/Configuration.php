<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Configuration extends Model
{
    protected $fillable = [
        'key',
        'value',
        'group',
        'label',
        'type',
        'is_sensitive',
        'description',
    ];

    protected $casts = [
        'is_sensitive' => 'boolean',
    ];

    /**
     * Get decrypted value for sensitive fields
     */
    public function getDecryptedValue(): ?string
    {
        if ($this->is_sensitive && $this->value) {
            try {
                return Crypt::decryptString($this->value);
            } catch (\Exception $e) {
                return $this->value; // Return as-is if not encrypted
            }
        }

        return $this->value;
    }

    /**
     * Set encrypted value for sensitive fields
     */
    public function setEncryptedValue(string $value): void
    {
        if ($this->is_sensitive) {
            $this->value = Crypt::encryptString($value);
        } else {
            $this->value = $value;
        }
    }

    /**
     * Scope by group
     */
    public function scopeByGroup($query, string $group)
    {
        return $query->where('group', $group);
    }

    /**
     * Get config value by key
     */
    public static function getValue(string $key, ?string $default = null): ?string
    {
        $config = self::where('key', $key)->first();

        return $config ? $config->getDecryptedValue() : $default;
    }

    /**
     * Set config value by key
     */
    public static function setValue(string $key, string $value, string $group = 'general', bool $isSensitive = false): self
    {
        $config = self::firstOrNew(['key' => $key]);
        $config->group = $group;
        $config->is_sensitive = $isSensitive;

        if ($isSensitive) {
            $config->setEncryptedValue($value);
        } else {
            $config->value = $value;
        }

        $config->save();

        return $config;
    }
}
