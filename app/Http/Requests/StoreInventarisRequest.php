<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInventarisRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Set ke true jika semua user terautentikasi boleh menambah
        // Atau tambahkan logika otorisasi di sini jika perlu
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Pindahkan rules dari InventarisController@store ke sini
        return [
            'nama_barang' => 'required|string|max:255',
            'kategori' => 'required|string|in:tidak_habis_pakai,habis_pakai,aset_tetap',
            'pemilik' => 'required|string|max:255',
            'sumber_dana' => 'required|string|max:255',
            'tahun_beli' => 'required|date',
            'nomor_unit' => 'required|integer|min:1',
            'kondisi_baik' => 'required|integer|min:0',
            'kondisi_rusak_ringan' => 'required|integer|min:0',
            'kondisi_rusak_berat' => 'required|integer|min:0',
            'keterangan' => 'nullable|string',
            'lokasi' => 'nullable|string',
            'unit_id' => 'required|exists:units,id',
            'room_id' => 'required|exists:rooms,id',
            'initial_stok' => 'nullable|integer|min:0|required_if:kategori,habis_pakai',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
         return [
             'initial_stok.required_if' => 'Stok awal wajib diisi untuk kategori barang habis pakai.',
             // Tambahkan pesan custom lainnya jika perlu
         ];
    }
}
