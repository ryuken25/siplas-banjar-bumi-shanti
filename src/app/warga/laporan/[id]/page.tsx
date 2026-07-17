'use client';

import { useEffect, useState } from 'react';
import { useParams } from 'next/navigation';
import Link from 'next/link';
import {
  ArrowLeft,
  MapPin,
  Loader2,
  CheckCircle2,
  Clock,
  Package,
  Send,
  XCircle,
} from 'lucide-react';
import {
  formatTanggal,
  STATUS_LAPORAN_LABEL,
  STATUS_LAPORAN_COLOR,
  JENIS_SAMPAH_LABEL,
  JENIS_SAMPAH_ICON,
} from '@/lib/utils';

const TIMELINE_STEPS = ['dikirim', 'diterima', 'diproses', 'selesai'] as const;
const TIMELINE_ICONS: Record<string, any> = {
  dikirim: Send,
  diterima: CheckCircle2,
  diproses: Package,
  selesai: CheckCircle2,
};

export default function LaporanDetailPage() {
  const { id } = useParams();
  const [laporan, setLaporan] = useState<any>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetch(`/api/warga/laporan/${id}`)
      .then((r) => r.json())
      .then(setLaporan)
      .catch(console.error)
      .finally(() => setLoading(false));
  }, [id]);

  if (loading) {
    return (
      <div className="min-h-screen bg-gray-50 flex items-center justify-center">
        <Loader2 className="w-8 h-8 animate-spin text-green-600" />
      </div>
    );
  }

  if (!laporan || laporan.error) {
    return (
      <div className="min-h-screen bg-gray-50 p-4 md:p-8">
        <div className="max-w-3xl mx-auto text-center py-20">
          <XCircle className="w-12 h-12 text-red-400 mx-auto mb-3" />
          <p className="text-gray-600">{laporan?.error || 'Laporan tidak ditemukan'}</p>
          <Link href="/warga/laporan-saya" className="text-green-600 hover:underline mt-4 inline-block">
            Kembali ke daftar laporan
          </Link>
        </div>
      </div>
    );
  }

  const isDitolak = laporan.status === 'ditolak';
  const currentIdx = TIMELINE_STEPS.indexOf(laporan.status as any);

  return (
    <div className="min-h-screen bg-gray-50 p-4 md:p-8">
      <div className="max-w-3xl mx-auto space-y-6">
        {/* Back */}
        <Link href="/warga/laporan-saya" className="inline-flex items-center gap-1 text-sm text-gray-600 hover:text-gray-900">
          <ArrowLeft className="w-4 h-4" /> Kembali
        </Link>

        {/* Header */}
        <div className="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
          {laporan.foto ? (
            <img src={laporan.foto} alt={laporan.kode_laporan} className="w-full max-h-80 object-cover" />
          ) : (
            <div className="w-full h-48 bg-gray-100 flex items-center justify-center">
              <span className="text-5xl">{JENIS_SAMPAH_ICON[laporan.jenis_sampah] || '🗑️'}</span>
            </div>
          )}
          <div className="p-5 space-y-4">
            <div className="flex items-center justify-between flex-wrap gap-2">
              <h1 className="text-xl font-bold text-gray-900">{laporan.kode_laporan}</h1>
              <span className={`text-sm px-3 py-1 rounded-full font-medium border ${STATUS_LAPORAN_COLOR[laporan.status]}`}>
                {STATUS_LAPORAN_LABEL[laporan.status]}
              </span>
            </div>

            {/* Info grid */}
            <div className="grid sm:grid-cols-2 gap-4 text-sm">
              <div>
                <p className="text-gray-500">Jenis Sampah</p>
                <p className="font-medium text-gray-900">
                  {JENIS_SAMPAH_ICON[laporan.jenis_sampah]} {JENIS_SAMPAH_LABEL[laporan.jenis_sampah]}
                </p>
              </div>
              <div>
                <p className="text-gray-500">Tanggal Lapor</p>
                <p className="font-medium text-gray-900">{formatTanggal(laporan.tanggal_lapor)}</p>
              </div>
              <div className="sm:col-span-2">
                <p className="text-gray-500">Lokasi</p>
                <p className="font-medium text-gray-900 flex items-center gap-1">
                  <MapPin className="w-4 h-4 text-gray-400" /> {laporan.lokasi_text}
                </p>
              </div>
              {laporan.keterangan && (
                <div className="sm:col-span-2">
                  <p className="text-gray-500">Keterangan</p>
                  <p className="text-gray-900">{laporan.keterangan}</p>
                </div>
              )}
              {laporan.petugas_name && (
                <div>
                  <p className="text-gray-500">Petugas</p>
                  <p className="font-medium text-gray-900">{laporan.petugas_name}</p>
                </div>
              )}
            </div>
          </div>
        </div>

        {/* Alasan Tolak */}
        {isDitolak && laporan.alasan_tolak && (
          <div className="bg-red-50 border border-red-200 rounded-xl p-5">
            <h3 className="font-semibold text-red-800 mb-1">Laporan Ditolak</h3>
            <p className="text-sm text-red-700">{laporan.alasan_tolak}</p>
          </div>
        )}

        {/* Timeline */}
        <div className="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
          <h2 className="font-semibold text-gray-900 mb-4">Status Timeline</h2>
          <div className="space-y-0">
            {TIMELINE_STEPS.map((step, idx) => {
              const Icon = TIMELINE_ICONS[step];
              const isPast = idx <= currentIdx && !isDitolak;
              const isCurrent = idx === currentIdx && !isDitolak;
              const dateField = step === 'dikirim' ? 'tanggal_lapor' :
                step === 'diterima' ? 'tanggal_diterima' :
                step === 'diproses' ? 'tanggal_diproses' : 'tanggal_selesai';
              const date = laporan[dateField];

              return (
                <div key={step} className="flex gap-4">
                  <div className="flex flex-col items-center">
                    <div className={`w-10 h-10 rounded-full flex items-center justify-center ${
                      isPast ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-400'
                    } ${isCurrent ? 'ring-4 ring-green-200' : ''}`}>
                      <Icon className="w-5 h-5" />
                    </div>
                    {idx < TIMELINE_STEPS.length - 1 && (
                      <div className={`w-0.5 h-12 ${isPast ? 'bg-green-500' : 'bg-gray-200'}`} />
                    )}
                  </div>
                  <div className="pt-2 pb-6">
                    <p className={`font-medium ${isPast ? 'text-gray-900' : 'text-gray-400'}`}>
                      {STATUS_LAPORAN_LABEL[step]}
                    </p>
                    {date && <p className="text-xs text-gray-500">{formatTanggal(date)}</p>}
                  </div>
                </div>
              );
            })}
          </div>
        </div>
      </div>
    </div>
  );
}
