'use client';

import { useEffect, useState, useRef } from 'react';
import { toast } from 'sonner';
import {
  Loader2,
  CreditCard,
  Upload,
  X,
  CheckCircle2,
  AlertCircle,
  Clock,
  XCircle,
} from 'lucide-react';
import {
  formatRupiah,
  formatTanggalShort,
  BULAN_LABEL,
  STATUS_IURAN_LABEL,
  STATUS_IURAN_COLOR,
} from '@/lib/utils';

export default function IuranPage() {
  const [aktif, setAktif] = useState<any[]>([]);
  const [riwayat, setRiwayat] = useState<any[]>([]);
  const [loading, setLoading] = useState(true);
  const [bayarDialog, setBayarDialog] = useState<any>(null);
  const [metode, setMetode] = useState<'transfer' | 'tunai'>('transfer');
  const [bukti, setBukti] = useState<File | null>(null);
  const [buktiPreview, setBuktiPreview] = useState<string | null>(null);
  const [submitting, setSubmitting] = useState(false);
  const fileRef = useRef<HTMLInputElement>(null);

  useEffect(() => {
    fetch('/api/warga/iuran')
      .then((r) => r.json())
      .then((d) => { setAktif(d.aktif || []); setRiwayat(d.riwayat || []); })
      .catch(console.error)
      .finally(() => setLoading(false));
  }, []);

  function handleBuktiChange(e: React.ChangeEvent<HTMLInputElement>) {
    const file = e.target.files?.[0];
    if (file) {
      setBukti(file);
      const reader = new FileReader();
      reader.onloadend = () => setBuktiPreview(reader.result as string);
      reader.readAsDataURL(file);
    }
  }

  function openBayar(item: any) {
    setBayarDialog(item);
    setMetode('transfer');
    setBukti(null);
    setBuktiPreview(null);
  }

  function closeBayar() {
    setBayarDialog(null);
    setBukti(null);
    setBuktiPreview(null);
  }

  async function handleBayar() {
    if (!bayarDialog) return;
    setSubmitting(true);
    try {
      const fd = new FormData();
      fd.append('metode_bayar', metode);
      if (bukti) fd.append('bukti_bayar', bukti);

      const res = await fetch(`/api/warga/iuran/${bayarDialog.id}/bayar`, {
        method: 'POST',
        body: fd,
      });
      if (!res.ok) {
        const err = await res.json();
        throw new Error(err.error || 'Gagal membayar');
      }

      toast.success('Pembayaran berhasil dikirim, menunggu verifikasi');
      closeBayar();
      // Refresh data
      const data = await fetch('/api/warga/iuran').then((r) => r.json());
      setAktif(data.aktif || []);
      setRiwayat(data.riwayat || []);
    } catch (err: any) {
      toast.error(err.message);
    } finally {
      setSubmitting(false);
    }
  }

  if (loading) {
    return (
      <div className="min-h-screen bg-gray-50 flex items-center justify-center">
        <Loader2 className="w-8 h-8 animate-spin text-green-600" />
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-gray-50 p-4 md:p-8">
      <div className="max-w-4xl mx-auto space-y-8">
        <h1 className="text-2xl font-bold text-gray-900">Iuran Bulanan</h1>

        {/* Tagihan Aktif */}
        <section>
          <h2 className="text-lg font-semibold text-gray-900 mb-4">Tagihan Aktif</h2>
          {aktif.length === 0 ? (
            <div className="bg-white rounded-xl p-8 text-center border border-gray-100">
              <CheckCircle2 className="w-12 h-12 text-green-400 mx-auto mb-3" />
              <p className="text-gray-500">Semua tagihan sudah lunas 🎉</p>
            </div>
          ) : (
            <div className="space-y-3">
              {aktif.map((item: any) => (
                <div key={item.id} className="bg-white rounded-xl p-5 shadow-sm border border-gray-100 flex flex-col sm:flex-row sm:items-center gap-4">
                  <div className="p-3 rounded-lg bg-amber-50 text-amber-600">
                    <CreditCard className="w-6 h-6" />
                  </div>
                  <div className="flex-1">
                    <p className="font-semibold text-gray-900">{BULAN_LABEL[item.bulan]} {item.tahun}</p>
                    <p className="text-lg font-bold text-green-700">{formatRupiah(item.nominal)}</p>
                    <p className="text-xs text-gray-400">{item.kode_tagihan}</p>
                    {item.status === 'ditolak' && item.alasan_tolak && (
                      <p className="text-xs text-red-600 mt-1 flex items-center gap-1">
                        <XCircle className="w-3 h-3" /> Ditolak: {item.alasan_tolak}
                      </p>
                    )}
                  </div>
                  <div className="flex items-center gap-3">
                    <span className={`text-xs px-3 py-1 rounded-full font-medium border ${STATUS_IURAN_COLOR[item.status]}`}>
                      {STATUS_IURAN_LABEL[item.status]}
                    </span>
                    {(item.status === 'belum_bayar' || item.status === 'ditolak') && (
                      <button
                        onClick={() => openBayar(item)}
                        className="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition"
                      >
                        Bayar
                      </button>
                    )}
                  </div>
                </div>
              ))}
            </div>
          )}
        </section>

        {/* Riwayat */}
        <section>
          <h2 className="text-lg font-semibold text-gray-900 mb-4">Riwayat Pembayaran</h2>
          {riwayat.length === 0 ? (
            <div className="bg-white rounded-xl p-8 text-center border border-gray-100">
              <p className="text-gray-400">Belum ada riwayat pembayaran</p>
            </div>
          ) : (
            <div className="space-y-3">
              {riwayat.map((item: any) => (
                <div key={item.id} className="bg-white rounded-xl p-5 shadow-sm border border-gray-100 flex flex-col sm:flex-row sm:items-center gap-4">
                  <div className="p-3 rounded-lg bg-green-50 text-green-600">
                    <CheckCircle2 className="w-6 h-6" />
                  </div>
                  <div className="flex-1">
                    <p className="font-semibold text-gray-900">{BULAN_LABEL[item.bulan]} {item.tahun}</p>
                    <p className="text-lg font-bold text-green-700">{formatRupiah(item.nominal)}</p>
                    <p className="text-xs text-gray-400">{item.kode_tagihan}</p>
                  </div>
                  <span className="text-xs px-3 py-1 rounded-full font-medium border bg-green-100 text-green-800 border-green-200">
                    Lunas
                  </span>
                </div>
              ))}
            </div>
          )}
        </section>
      </div>

      {/* Bayar Dialog */}
      {bayarDialog && (
        <div className="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
          <div className="bg-white rounded-xl w-full max-w-md p-6 space-y-5">
            <div className="flex items-center justify-between">
              <h3 className="text-lg font-bold text-gray-900">Bayar Iuran</h3>
              <button onClick={closeBayar} className="p-1 hover:bg-gray-100 rounded-lg">
                <X className="w-5 h-5" />
              </button>
            </div>
            <div className="text-sm space-y-1">
              <p className="text-gray-500">Periode</p>
              <p className="font-medium">{BULAN_LABEL[bayarDialog.bulan]} {bayarDialog.tahun}</p>
              <p className="text-2xl font-bold text-green-700">{formatRupiah(bayarDialog.nominal)}</p>
            </div>

            {/* Metode */}
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran</label>
              <div className="grid grid-cols-2 gap-3">
                {(['transfer', 'tunai'] as const).map((m) => (
                  <button
                    key={m}
                    type="button"
                    onClick={() => setMetode(m)}
                    className={`p-3 rounded-lg border-2 text-sm font-medium capitalize transition ${
                      metode === m
                        ? 'border-green-500 bg-green-50 text-green-700'
                        : 'border-gray-200 hover:border-gray-300 text-gray-700'
                    }`}
                  >
                    {m === 'transfer' ? '🏦 Transfer' : '💵 Tunai'}
                  </button>
                ))}
              </div>
            </div>

            {/* Bukti Bayar (transfer only) */}
            {metode === 'transfer' && (
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-2">Bukti Transfer</label>
                {buktiPreview ? (
                  <div className="relative rounded-lg overflow-hidden border border-gray-200">
                    <img src={buktiPreview} alt="Bukti" className="w-full max-h-48 object-cover" />
                    <button
                      type="button"
                      onClick={() => { setBukti(null); setBuktiPreview(null); }}
                      className="absolute top-2 right-2 p-1.5 bg-red-500 text-white rounded-full hover:bg-red-600"
                    >
                      <X className="w-4 h-4" />
                    </button>
                  </div>
                ) : (
                  <div
                    onClick={() => fileRef.current?.click()}
                    className="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer hover:border-green-400 transition"
                  >
                    <Upload className="w-8 h-8 text-gray-400 mx-auto mb-1" />
                    <p className="text-sm text-gray-600">Upload bukti transfer</p>
                  </div>
                )}
                <input ref={fileRef} type="file" accept="image/*" onChange={handleBuktiChange} className="hidden" />
              </div>
            )}

            <button
              onClick={handleBayar}
              disabled={submitting}
              className="w-full flex items-center justify-center gap-2 bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition font-medium disabled:opacity-50"
            >
              {submitting ? (
                <><Loader2 className="w-5 h-5 animate-spin" /> Memproses...</>
              ) : (
                'Bayar Sekarang'
              )}
            </button>
          </div>
        </div>
      )}
    </div>
  );
}
