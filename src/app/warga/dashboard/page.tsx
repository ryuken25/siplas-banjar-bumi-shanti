'use client';

import { useEffect, useState } from 'react';
import Link from 'next/link';
import { useRouter } from 'next/navigation';
import {
  Trash2,
  FileText,
  CreditCard,
  CheckCircle2,
  Plus,
  ArrowRight,
  Clock,
  AlertCircle,
  Loader2,
} from 'lucide-react';
import {
  formatTanggalShort,
  formatRupiah,
  BULAN_LABEL,
  STATUS_LAPORAN_LABEL,
  STATUS_LAPORAN_COLOR,
  STATUS_IURAN_LABEL,
  STATUS_IURAN_COLOR,
  JENIS_SAMPAH_LABEL,
  JENIS_SAMPAH_ICON,
} from '@/lib/utils';

interface DashboardData {
  laporanTerbaru: any[];
  tagihanBelum: any[];
  stat: {
    laporan_aktif: number;
    laporan_selesai: number;
    tagihan_belum: number;
    tagihan_lunas: number;
  };
}

export default function WargaDashboard() {
  const router = useRouter();
  const [data, setData] = useState<DashboardData | null>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetch('/api/warga/dashboard')
      .then((r) => r.json())
      .then(setData)
      .catch(console.error)
      .finally(() => setLoading(false));
  }, []);

  if (loading) {
    return (
      <div className="min-h-screen bg-gray-50 p-4 md:p-8">
        <div className="max-w-6xl mx-auto space-y-6">
          <div className="h-8 w-48 bg-gray-200 rounded animate-pulse" />
          <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
            {[...Array(4)].map((_, i) => (
              <div key={i} className="h-28 bg-gray-200 rounded-xl animate-pulse" />
            ))}
          </div>
          <div className="grid md:grid-cols-2 gap-6">
            <div className="h-64 bg-gray-200 rounded-xl animate-pulse" />
            <div className="h-64 bg-gray-200 rounded-xl animate-pulse" />
          </div>
        </div>
      </div>
    );
  }

  if (!data) return null;

  const stats = [
    {
      label: 'Laporan Aktif',
      value: data.stat.laporan_aktif,
      icon: Clock,
      color: 'bg-blue-500',
      lightColor: 'bg-blue-50 text-blue-700',
    },
    {
      label: 'Laporan Selesai',
      value: data.stat.laporan_selesai,
      icon: CheckCircle2,
      color: 'bg-green-500',
      lightColor: 'bg-green-50 text-green-700',
    },
    {
      label: 'Tagihan Belum',
      value: data.stat.tagihan_belum,
      icon: AlertCircle,
      color: 'bg-red-500',
      lightColor: 'bg-red-50 text-red-700',
    },
    {
      label: 'Tagihan Lunas',
      value: data.stat.tagihan_lunas,
      icon: CreditCard,
      color: 'bg-emerald-500',
      lightColor: 'bg-emerald-50 text-emerald-700',
    },
  ];

  return (
    <div className="min-h-screen bg-gray-50 p-4 md:p-8">
      <div className="max-w-6xl mx-auto space-y-6">
        {/* Header */}
        <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
          <h1 className="text-2xl font-bold text-gray-900">Dashboard</h1>
          <div className="flex gap-3">
            <Link
              href="/warga/lapor"
              className="inline-flex items-center gap-2 bg-green-600 text-white px-4 py-2.5 rounded-lg hover:bg-green-700 transition font-medium text-sm"
            >
              <Plus className="w-4 h-4" />
              Lapor Sampah
            </Link>
            <Link
              href="/warga/laporan-saya"
              className="inline-flex items-center gap-2 bg-white border border-gray-300 text-gray-700 px-4 py-2.5 rounded-lg hover:bg-gray-50 transition font-medium text-sm"
            >
              <FileText className="w-4 h-4" />
              Semua Laporan
            </Link>
          </div>
        </div>

        {/* Stat Cards */}
        <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
          {stats.map((stat) => (
            <div key={stat.label} className="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
              <div className="flex items-center gap-3">
                <div className={`p-2.5 rounded-lg ${stat.lightColor}`}>
                  <stat.icon className="w-5 h-5" />
                </div>
                <div>
                  <p className="text-sm text-gray-500">{stat.label}</p>
                  <p className="text-2xl font-bold text-gray-900">{stat.value}</p>
                </div>
              </div>
            </div>
          ))}
        </div>

        <div className="grid md:grid-cols-2 gap-6">
          {/* Laporan Terbaru */}
          <div className="bg-white rounded-xl shadow-sm border border-gray-100">
            <div className="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
              <h2 className="font-semibold text-gray-900">Laporan Terbaru</h2>
              <Link href="/warga/laporan-saya" className="text-sm text-green-600 hover:text-green-700 flex items-center gap-1">
                Lihat Semua <ArrowRight className="w-4 h-4" />
              </Link>
            </div>
            <div className="p-4 space-y-3">
              {data.laporanTerbaru.length === 0 ? (
                <p className="text-center text-gray-400 py-8">Belum ada laporan</p>
              ) : (
                data.laporanTerbaru.map((lap: any) => (
                  <Link
                    key={lap.id}
                    href={`/warga/laporan/${lap.id}`}
                    className="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition"
                  >
                    <div className="text-2xl">{JENIS_SAMPAH_ICON[lap.jenis_sampah] || '🗑️'}</div>
                    <div className="flex-1 min-w-0">
                      <p className="text-sm font-medium text-gray-900 truncate">{lap.kode_laporan}</p>
                      <p className="text-xs text-gray-500">{JENIS_SAMPAH_LABEL[lap.jenis_sampah]} · {formatTanggalShort(lap.tanggal_lapor)}</p>
                    </div>
                    <span className={`text-xs px-2 py-1 rounded-full font-medium border ${STATUS_LAPORAN_COLOR[lap.status]}`}>
                      {STATUS_LAPORAN_LABEL[lap.status]}
                    </span>
                  </Link>
                ))
              )}
            </div>
          </div>

          {/* Tagihan Belum */}
          <div className="bg-white rounded-xl shadow-sm border border-gray-100">
            <div className="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
              <h2 className="font-semibold text-gray-900">Tagihan Belum Dibayar</h2>
              <Link href="/warga/iuran" className="text-sm text-green-600 hover:text-green-700 flex items-center gap-1">
                Lihat Semua <ArrowRight className="w-4 h-4" />
              </Link>
            </div>
            <div className="p-4 space-y-3">
              {data.tagihanBelum.length === 0 ? (
                <p className="text-center text-gray-400 py-8">Semua tagihan sudah lunas 🎉</p>
              ) : (
                data.tagihanBelum.map((tagihan: any) => (
                  <div key={tagihan.id} className="flex items-center gap-3 p-3 rounded-lg bg-gray-50">
                    <CreditCard className="w-5 h-5 text-gray-400" />
                    <div className="flex-1 min-w-0">
                      <p className="text-sm font-medium text-gray-900">
                        {BULAN_LABEL[tagihan.bulan]} {tagihan.tahun}
                      </p>
                      <p className="text-xs text-gray-500">{formatRupiah(tagihan.nominal)}</p>
                    </div>
                    <span className={`text-xs px-2 py-1 rounded-full font-medium border ${STATUS_IURAN_COLOR[tagihan.status]}`}>
                      {STATUS_IURAN_LABEL[tagihan.status]}
                    </span>
                    <Link
                      href="/warga/iuran"
                      className="text-xs bg-green-600 text-white px-3 py-1.5 rounded-lg hover:bg-green-700 transition font-medium"
                    >
                      Bayar
                    </Link>
                  </div>
                ))
              )}
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
