'use client';

import { useEffect, useState } from 'react';
import { toast } from 'sonner';
import Link from 'next/link';
import { LineChart, Line, XAxis, YAxis, CartesianGrid, Tooltip, ResponsiveContainer, PieChart, Pie, Cell } from 'recharts';
import { Users, FileText, Wallet, TrendingUp, ArrowRight, AlertCircle } from 'lucide-react';
import { cn, formatRupiah, STATUS_LAPORAN_LABEL, STATUS_LAPORAN_COLOR, JENIS_SAMPAH_LABEL } from '@/lib/utils';

const PIE_COLORS = ['#3B82F6', '#F59E0B', '#8B5CF6', '#10B981', '#EF4444'];

export default function AdminDashboardPage() {
  const [data, setData] = useState<any>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => { fetchDashboard(); }, []);

  async function fetchDashboard() {
    try {
      const res = await fetch('/api/admin/dashboard');
      if (!res.ok) throw new Error('Gagal memuat dashboard');
      setData(await res.json());
    } catch {
      toast.error('Gagal memuat data dashboard');
    } finally {
      setLoading(false);
    }
  }

  if (loading) {
    return (
      <div className="p-6 space-y-6">
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
          {[...Array(4)].map((_, i) => (
            <div key={i} className="bg-white rounded-xl p-6 shadow-sm animate-pulse h-28" />
          ))}
        </div>
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <div className="bg-white rounded-xl p-6 shadow-sm animate-pulse h-72" />
          <div className="bg-white rounded-xl p-6 shadow-sm animate-pulse h-72" />
        </div>
      </div>
    );
  }

  if (!data) return null;

  const kpis = [
    { label: 'Warga Aktif', value: data.totalWargaAktif, icon: Users, color: 'text-blue-600', bg: 'bg-blue-50' },
    { label: 'Laporan Bulan Ini', value: data.laporanBulanIni, icon: FileText, color: 'text-amber-600', bg: 'bg-amber-50' },
    { label: 'Iuran Terkumpul', value: formatRupiah(data.iuranTerkumpul), icon: Wallet, color: 'text-green-600', bg: 'bg-green-50' },
    { label: 'Tingkat Penyelesaian', value: `${data.tingkatPenyelesaian}%`, icon: TrendingUp, color: 'text-purple-600', bg: 'bg-purple-50' },
  ];

  const statusDistData = (data.statusDist || []).map((item: any) => ({
    name: STATUS_LAPORAN_LABEL[item.status] || item.status,
    value: item.count,
  }));

  return (
    <div className="p-4 sm:p-6 space-y-6">
      <div className="flex items-center justify-between">
        <h1 className="text-2xl font-bold text-gray-900">Dashboard Admin</h1>
        {data.pendingApproval > 0 && (
          <Link href="/admin/pengguna?tab=pending" className="flex items-center gap-2 px-4 py-2 bg-amber-50 border border-amber-200 rounded-lg text-amber-800 text-sm hover:bg-amber-100">
            <AlertCircle className="w-4 h-4" />
            {data.pendingApproval} Pendaftaran Menunggu
          </Link>
        )}
      </div>

      {/* KPI Cards */}
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {kpis.map((kpi) => (
          <div key={kpi.label} className="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <div className="flex items-center gap-3">
              <div className={cn('p-2.5 rounded-lg', kpi.bg)}>
                <kpi.icon className={cn('w-5 h-5', kpi.color)} />
              </div>
              <div>
                <p className="text-sm text-gray-500">{kpi.label}</p>
                <p className="text-2xl font-bold text-gray-900">{kpi.value}</p>
              </div>
            </div>
          </div>
        ))}
      </div>

      {/* Charts Row */}
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {/* Iuran Chart */}
        <div className="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
          <h2 className="text-lg font-semibold text-gray-900 mb-4">Trend Iuran (6 Bulan)</h2>
          <div className="h-64">
            <ResponsiveContainer width="100%" height="100%">
              <LineChart data={data.iuranChart}>
                <CartesianGrid strokeDasharray="3 3" stroke="#f0f0f0" />
                <XAxis dataKey="label" tick={{ fontSize: 11 }} />
                <YAxis tick={{ fontSize: 11 }} tickFormatter={(v) => `${(v / 1000000).toFixed(0)}jt`} />
                <Tooltip formatter={(v: number) => [formatRupiah(v), 'Iuran']} />
                <Line type="monotone" dataKey="total" stroke="#10B981" strokeWidth={2} dot={{ fill: '#10B981' }} />
              </LineChart>
            </ResponsiveContainer>
          </div>
        </div>

        {/* Status Distribution */}
        <div className="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
          <h2 className="text-lg font-semibold text-gray-900 mb-4">Distribusi Status Laporan</h2>
          {statusDistData.length > 0 ? (
            <div className="h-64 flex items-center">
              <ResponsiveContainer width="100%" height="100%">
                <PieChart>
                  <Pie data={statusDistData} cx="50%" cy="50%" outerRadius={80} dataKey="value" label={({ name, percent }) => `${name} ${(percent * 100).toFixed(0)}%`} labelLine={false}>
                    {statusDistData.map((_: any, idx: number) => (
                      <Cell key={idx} fill={PIE_COLORS[idx % PIE_COLORS.length]} />
                    ))}
                  </Pie>
                  <Tooltip />
                </PieChart>
              </ResponsiveContainer>
            </div>
          ) : (
            <p className="text-center text-gray-400 py-12">Belum ada data laporan bulan ini</p>
          )}
        </div>
      </div>

      {/* Recent Activity */}
      <div className="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <div className="flex items-center justify-between mb-4">
          <h2 className="text-lg font-semibold text-gray-900">Aktivitas Terbaru</h2>
          <Link href="/admin/laporan" className="text-sm text-green-600 hover:text-green-700 flex items-center gap-1">
            Lihat Semua <ArrowRight className="w-4 h-4" />
          </Link>
        </div>
        {data.aktivitasTerbaru.length === 0 ? (
          <p className="text-gray-500 text-center py-6">Belum ada aktivitas</p>
        ) : (
          <div className="overflow-x-auto">
            <table className="w-full text-sm">
              <thead>
                <tr className="border-b border-gray-100">
                  <th className="text-left py-2 text-gray-500 font-medium">Kode</th>
                  <th className="text-left py-2 text-gray-500 font-medium">Pelapor</th>
                  <th className="text-left py-2 text-gray-500 font-medium">Jenis</th>
                  <th className="text-left py-2 text-gray-500 font-medium">Status</th>
                  <th className="text-left py-2 text-gray-500 font-medium">Tanggal</th>
                </tr>
              </thead>
              <tbody>
                {data.aktivitasTerbaru.map((item: any) => (
                  <tr key={item.id} className="border-b border-gray-50 hover:bg-gray-50">
                    <td className="py-2.5">
                      <Link href={`/admin/laporan/${item.id}`} className="font-mono text-xs text-green-600 hover:underline">
                        {item.kode_laporan}
                      </Link>
                    </td>
                    <td className="py-2.5">{item.pelapor_name}</td>
                    <td className="py-2.5">{JENIS_SAMPAH_LABEL[item.jenis_sampah]}</td>
                    <td className="py-2.5">
                      <span className={cn('text-xs px-2 py-1 rounded-full border', STATUS_LAPORAN_COLOR[item.status])}>
                        {STATUS_LAPORAN_LABEL[item.status]}
                      </span>
                    </td>
                    <td className="py-2.5 text-gray-500 text-xs">
                      {new Date(item.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })}
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        )}
      </div>
    </div>
  );
}
