'use client';

import { useEffect, useState } from 'react';
import { useRouter } from 'next/navigation';
import { toast } from 'sonner';
import { ArrowLeft, MapPin, User, Calendar, Clock, CheckCircle2, Play } from 'lucide-react';
import { cn, formatTanggal, STATUS_LAPORAN_LABEL, STATUS_LAPORAN_COLOR, JENIS_SAMPAH_LABEL, JENIS_SAMPAH_ICON } from '@/lib/utils';

export default function AdminLaporanDetailPage({ params }: { params: { id: string } }) {
  const router = useRouter();
  const [data, setData] = useState<any>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    (async () => {
      try {
        const res = await fetch(`/api/admin/laporan/${params.id}`);
        if (!res.ok) throw new Error('Gagal memuat');
        setData(await res.json());
      } catch {
        toast.error('Gagal memuat detail laporan');
      } finally {
        setLoading(false);
      }
    })();
  }, [params.id]);

  if (loading) {
    return (
      <div className="p-6 space-y-4">
        <div className="h-8 bg-gray-200 rounded w-1/4 animate-pulse" />
        <div className="bg-white rounded-xl p-6 animate-pulse h-96" />
      </div>
    );
  }

  if (!data) return null;

  const timeline = [
    { label: 'Dikirim', date: data.tanggal_lapor, done: true, icon: Clock },
    { label: 'Diterima', date: data.tanggal_diterima, done: ['diterima', 'diproses', 'selesai'].includes(data.status), icon: CheckCircle2 },
    { label: 'Diproses', date: data.tanggal_diproses, done: ['diproses', 'selesai'].includes(data.status), icon: Play },
    { label: 'Selesai', date: data.tanggal_selesai, done: data.status === 'selesai', icon: CheckCircle2 },
  ];

  return (
    <div className="p-4 sm:p-6 space-y-6">
      <button onClick={() => router.back()} className="flex items-center gap-2 text-gray-600 hover:text-gray-900">
        <ArrowLeft className="w-4 h-4" /> Kembali
      </button>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div className="lg:col-span-2 space-y-6">
          <div className="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div className="flex items-start justify-between mb-4">
              <div>
                <h1 className="text-xl font-bold text-gray-900">{data.kode_laporan}</h1>
                <p className="text-sm text-gray-500 flex items-center gap-1 mt-1">
                  <Calendar className="w-3.5 h-3.5" /> {formatTanggal(data.tanggal_lapor)}
                </p>
              </div>
              <span className={cn('text-sm px-3 py-1.5 rounded-full border font-medium', STATUS_LAPORAN_COLOR[data.status])}>
                {STATUS_LAPORAN_LABEL[data.status]}
              </span>
            </div>

            {data.foto && (
              <div className="mb-4 rounded-lg overflow-hidden">
                <img src={data.foto} alt="Foto laporan" className="w-full max-h-80 object-cover" />
              </div>
            )}

            <div className="space-y-3">
              <div className="flex items-center gap-2">
                <span className="text-xl">{JENIS_SAMPAH_ICON[data.jenis_sampah]}</span>
                <span className="font-medium">{JENIS_SAMPAH_LABEL[data.jenis_sampah]}</span>
              </div>
              <div className="flex items-start gap-2 text-gray-600">
                <MapPin className="w-4 h-4 mt-0.5 flex-shrink-0" />
                <span>{data.lokasi_text}</span>
              </div>
              {data.keterangan && <p className="text-gray-700 bg-gray-50 rounded-lg p-3">{data.keterangan}</p>}
              {data.status === 'ditolak' && data.alasan_tolak && (
                <div className="bg-red-50 border border-red-200 rounded-lg p-3">
                  <p className="text-sm font-medium text-red-800">Alasan Ditolak:</p>
                  <p className="text-sm text-red-700">{data.alasan_tolak}</p>
                </div>
              )}
            </div>
          </div>

          {data.status !== 'ditolak' && (
            <div className="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
              <h2 className="text-lg font-semibold text-gray-900 mb-4">Timeline</h2>
              <div className="space-y-4">
                {timeline.map((item, idx) => (
                  <div key={item.label} className="flex items-start gap-3">
                    <div className={cn('w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0', item.done ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-400')}>
                      <item.icon className="w-4 h-4" />
                    </div>
                    <div className="flex-1">
                      <p className={cn('font-medium', item.done ? 'text-gray-900' : 'text-gray-400')}>{item.label}</p>
                      <p className="text-sm text-gray-500">{item.date ? formatTanggal(item.date) : 'Menunggu'}</p>
                    </div>
                  </div>
                ))}
              </div>
            </div>
          )}
        </div>

        <div className="space-y-6">
          <div className="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <h3 className="font-semibold text-gray-900 mb-3 flex items-center gap-2"><User className="w-4 h-4" /> Pelapor</h3>
            <div className="space-y-2 text-sm">
              <p className="font-medium text-gray-900">{data.pelapor_name}</p>
              {data.pelapor_email && <p className="text-gray-600">{data.pelapor_email}</p>}
              {data.pelapor_telp && <p className="text-gray-600">{data.pelugas_telp}</p>}
              {data.pelapor_alamat && <p className="text-gray-600">{data.pelapor_alamat}</p>}
            </div>
          </div>

          {data.petugas_name && (
            <div className="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
              <h3 className="font-semibold text-gray-900 mb-3 flex items-center gap-2"><User className="w-4 h-4" /> Petugas</h3>
              <p className="font-medium text-gray-900">{data.petugas_name}</p>
            </div>
          )}
        </div>
      </div>
    </div>
  );
}
