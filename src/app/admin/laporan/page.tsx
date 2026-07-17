'use client';

import { useEffect, useState, useCallback } from 'react';
import { useRouter } from 'next/navigation';
import { toast } from 'sonner';
import { Search, ChevronLeft, ChevronRight, MapPin, Calendar, Download } from 'lucide-react';
import { cn, formatTanggalShort, STATUS_LAPORAN_LABEL, STATUS_LAPORAN_COLOR, JENIS_SAMPAH_LABEL, JENIS_SAMPAH_ICON } from '@/lib/utils';

interface Pagination { page: number; limit: number; total: number; totalPages: number; }

export default function AdminLaporanPage() {
  const router = useRouter();
  const [data, setData] = useState<any[]>([]);
  const [pagination, setPagination] = useState<Pagination>({ page: 1, limit: 12, total: 0, totalPages: 0 });
  const [loading, setLoading] = useState(true);
  const [status, setStatus] = useState('');
  const [jenis, setJenis] = useState('');
  const [search, setSearch] = useState('');
  const [from, setFrom] = useState('');
  const [to, setTo] = useState('');

  const fetchData = useCallback(async (page = 1) => {
    setLoading(true);
    try {
      const params = new URLSearchParams({ page: String(page), limit: '12' });
      if (status) params.set('status', status);
      if (jenis) params.set('jenis', jenis);
      if (search) params.set('search', search);
      if (from) params.set('from', from);
      if (to) params.set('to', to);
      const res = await fetch(`/api/admin/laporan?${params}`);
      if (!res.ok) throw new Error('Gagal memuat data');
      const json = await res.json();
      setData(json.data);
      setPagination(json.pagination);
    } catch {
      toast.error('Gagal memuat data laporan');
    } finally {
      setLoading(false);
    }
  }, [status, jenis, search, from, to]);

  useEffect(() => { fetchData(); }, [fetchData]);

  return (
    <div className="p-4 sm:p-6 space-y-6">
      <div className="flex items-center justify-between">
        <h1 className="text-2xl font-bold text-gray-900">Semua Laporan</h1>
        <span className="text-sm text-gray-500">{pagination.total} total laporan</span>
      </div>

      {/* Filters */}
      <div className="bg-white rounded-xl p-4 shadow-sm border border-gray-100 space-y-3">
        <div className="flex flex-col sm:flex-row gap-3">
          <div className="relative flex-1">
            <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
            <input type="text" placeholder="Cari kode, lokasi, nama..." value={search} onChange={(e) => setSearch(e.target.value)}
              className="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent" />
          </div>
          <select value={status} onChange={(e) => setStatus(e.target.value)} className="px-3 py-2 border border-gray-200 rounded-lg text-sm">
            <option value="">Semua Status</option>
            {Object.entries(STATUS_LAPORAN_LABEL).map(([k, v]) => <option key={k} value={k}>{v}</option>)}
          </select>
          <select value={jenis} onChange={(e) => setJenis(e.target.value)} className="px-3 py-2 border border-gray-200 rounded-lg text-sm">
            <option value="">Semua Jenis</option>
            {Object.entries(JENIS_SAMPAH_LABEL).map(([k, v]) => <option key={k} value={k}>{v}</option>)}
          </select>
        </div>
        <div className="flex flex-col sm:flex-row gap-3 items-start sm:items-center">
          <div className="flex items-center gap-2">
            <Calendar className="w-4 h-4 text-gray-400" />
            <input type="date" value={from} onChange={(e) => setFrom(e.target.value)} className="px-3 py-2 border border-gray-200 rounded-lg text-sm" />
            <span className="text-gray-400">-</span>
            <input type="date" value={to} onChange={(e) => setTo(e.target.value)} className="px-3 py-2 border border-gray-200 rounded-lg text-sm" />
          </div>
          <button onClick={() => { setSearch(''); setStatus(''); setJenis(''); setFrom(''); setTo(''); }} className="px-4 py-2 text-sm text-gray-600 hover:text-gray-900">Reset</button>
        </div>
      </div>

      {/* Grid */}
      {loading ? (
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
          {[...Array(6)].map((_, i) => <div key={i} className="bg-white rounded-xl p-5 shadow-sm animate-pulse h-40" />)}
        </div>
      ) : data.length === 0 ? (
        <div className="text-center py-12 text-gray-500">Tidak ada laporan ditemukan</div>
      ) : (
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
          {data.map((lap) => (
            <button key={lap.id} onClick={() => router.push(`/admin/laporan/${lap.id}`)}
              className="bg-white rounded-xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-shadow text-left">
              <div className="flex items-start justify-between mb-3">
                <span className="text-2xl">{JENIS_SAMPAH_ICON[lap.jenis_sampah]}</span>
                <span className={cn('text-xs px-2 py-1 rounded-full border', STATUS_LAPORAN_COLOR[lap.status])}>
                  {STATUS_LAPORAN_LABEL[lap.status]}
                </span>
              </div>
              <p className="font-semibold text-gray-900 mb-1">{lap.kode_laporan}</p>
              <p className="text-sm text-gray-600 mb-2 line-clamp-2">{lap.keterangan || 'Tidak ada keterangan'}</p>
              <div className="flex items-center gap-1 text-xs text-gray-500 mb-1">
                <MapPin className="w-3 h-3" /><span className="truncate">{lap.lokasi_text}</span>
              </div>
              <div className="flex items-center justify-between mt-3 pt-3 border-t border-gray-100">
                <div className="text-xs text-gray-500">
                  <span>{lap.pelapor_name}</span>
                  {lap.petugas_name && <span className="text-gray-400"> → {lap.petugas_name}</span>}
                </div>
                <span className="text-xs text-gray-400">{formatTanggalShort(lap.created_at)}</span>
              </div>
            </button>
          ))}
        </div>
      )}

      {/* Pagination */}
      {pagination.totalPages > 1 && (
        <div className="flex items-center justify-center gap-2">
          <button onClick={() => fetchData(pagination.page - 1)} disabled={pagination.page <= 1} className="p-2 rounded-lg border border-gray-200 disabled:opacity-50 hover:bg-gray-50">
            <ChevronLeft className="w-4 h-4" />
          </button>
          <span className="text-sm text-gray-600">Halaman {pagination.page} dari {pagination.totalPages}</span>
          <button onClick={() => fetchData(pagination.page + 1)} disabled={pagination.page >= pagination.totalPages} className="p-2 rounded-lg border border-gray-200 disabled:opacity-50 hover:bg-gray-50">
            <ChevronRight className="w-4 h-4" />
          </button>
        </div>
      )}
    </div>
  );
}
