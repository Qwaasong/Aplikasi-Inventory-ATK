<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FinancialTransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tanggal' => $this->tanggal?->format('Y-m-d'),
            'tipe' => $this->tipe,
            'tipe_label' => $this->tipe === 'pemasukan' ? 'Pemasukan' : 'Pengeluaran',
            'keterangan' => $this->keterangan,
            'jumlah' => (float) $this->jumlah,
            'jumlah_formatted' => 'Rp ' . number_format($this->jumlah, 0, ',', '.'),
            'purchase_id' => $this->purchase_id,
            'stock_out_id' => $this->stock_out_id,
            'ledger_entry_id' => $this->ledger_entry_id,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
