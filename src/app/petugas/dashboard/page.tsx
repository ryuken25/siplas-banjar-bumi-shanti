'use client';
import { useEffect, useState } from 'react';
import { ClipboardList, Clock, CheckCircle, Bell } from 'lucide-react';
import StatCard from '@/components/stat-card';
import { formatTanggalShort } from '@/lib/utils';

export default function PetugasDashboard() {
  const [data, setData] = useState<any>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetch('/api/petugas/dashboard').then(r => r.json()).then(d => { setData(d); setLoading(false); });
  }, []);

  if (loading) return <div className="animate-pulse space-y-6"><div className="grid grid-cols-2 lg:grid-cols-4 gap-4">{[1,2,3,4].map(i => <div key={i} className="h-28 bg-gray-100 rounded-xl" />)}</div><div className="h-64 bg-gray-100 rounded-xl" /></div>;
  if (!data) return <p className="text-gray-500">Gagal memuat data.</p>;

  return (
    <div className="space-y-6 animate-fade-in">
      <h1 className="text-2xl font-bold text-gray-900">Dashboard Petugas</h1>
      <div className="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <StatCard title="Laporan Baru" value={data.stat?.baru ?? 0} icon={ClipboardList} color="bg-blue-50 text-blue-600" />
        <StatCard title="Diproses" value={data.stat?.diproses ?? 0} icon={Clock} color="bg-amber-50 text-amber-600" />
        <StatCard title="Selesai Hari Ini" value={data.stat?.selesai_hari_ini ?? 0} icon={CheckCircle} color="bg-green-50 text-green-600" />
        <StatCard title="Verifikasi Iuran" value={data.stat?.menunggu_verifikasi ?? 0} icon={Bell} color="bg-purple-50 text-purple-600" />
      </div>
      <div className="bg-white rounded-xl border border-gray-100 p-6">
        <h2 className="text-lg font-semibold mb-4">Laporan Terbaru</h2>
        {data.laporanTerbaru?.length ? (
          <div className="space-y-3">
            {data.laporanTerbaru.map((l: any) => (
              <div key={l.id} className="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div>
                  <p className="font-medium text-sm">{l.kode_laporan} — {l.pelapor_name || 'Warga'}</p>
                  <p className="text-xs text-gray-500">{formatTanggalShort(l.tanggal_lapor)}</p>
                </div>
                <span className="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700">{l.status}</span>
              </div>
            ))}
          </div>
        ) : <p className="text-gray-400 text-sm">Belum ada laporan.</p>}
      </div>
    </div>
  );
}
