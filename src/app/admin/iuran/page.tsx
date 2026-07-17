'use client';

import { useEffect, useState, useCallback } from 'react';
import { toast } from 'sonner';
import { ChevronLeft, ChevronRight, FileText, X, Wallet, Clock, CheckCircle2, XCircle } from 'lucide-react';
import { cn, formatRupiah, formatTanggal, BULAN_LABEL, STATUS_IURAN_LABEL, STATUS_IURAN_COLOR } from '@/lib/utils';

interface Pagination { page: number; limit: number; total: number; totalPages: number; }

export default function AdminIuranPage() {
  const [data, setData] = useState<any[]>([]);
  const [summary, setSummary] = useState({ lunas: 0, menunggu: 0, belum: 0, ditolak: 0, total_nominal: 0 });
  const [pagination, setPagination] = useState<Pagination>({ page: 1, limit: 12, total: 0, totalPages: 0 });
  const [loading, setLoading] = useState(true);
  const [year, setYear] = useState(String(new Date().getFullYear()));
  const [month, setMonth] = useState('');
  const [status, setStatus] = useState('');
  const [showGenerateModal, setShowGenerateModal] = useState(false);
  const [generateBulan, setGenerateBulan] = useState(String(new Date().getMonth() + 1));
  const [generateTahun, setGenerateTahun] = useState(String(new Date().getFullYear()));
  const [generating, setGenerating] = useState(false);

  const fetchData = useCallback(async (page = 1) => {
    setLoading(true);
    try {
      const params = new URLSearchParams({ page: String(page), limit: '12' });
      if (year) params.set('year', year);
      if (month) params.set('month', month);
      if (status) params.set('status', status);
      const res = await fetch(`/api/admin/iuran?${params}`);
      if (!res.ok) throw new Error('Gagal memuat data');
      const json = await res.json();
      setData(json.data);
      setSummary(json.summary);
      setPagination(json.pagination);
    } catch {
      toast.error('Gagal memuat data iuran');
    } finally {
      setLoading(false);
    }
  }, [year, month, status]);

  useEffect(() => { fetchData(); }, [fetchData]);

  async function handleGenerate() {
    setGenerating(true);
    try {
      const res = await fetch('/api/admin/iuran/generate', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ bulan: parseInt(generateBulan), tahun: parseInt(generateTahun) }),
      });
      const json = await res.json();
      if (!res.ok) throw new Error(json.error);
      toast.success(json.message);
      setShowGenerateModal(false);
      fetchData();
    } catch (err: any) {
      toast.error(err.message || 'Gagal generate tagihan');
    } finally {
      setGenerating(false);
    }
  }

  return (
    <div className="p-4 sm:p-6 space-y-6">
      <div className="flex items-center justify-between">
        <h1 className="text-2xl font-bold text-gray-900">Kelola Iuran</h1>
        <button onClick={() => setShowGenerateModal(true)} className="flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700">
          <FileText className="w-4 h-4" /> Generate Tagihan
        </button>
      </div>

      {/* Summary Cards */}
      <div className="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div className="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
          <div className="flex items-center gap-2 mb-1">
            <CheckCircle2 className="w-4 h-4 text-green-600" />
            <span className="text-sm text-gray-500">Lunas</span>
          </div>
          <p className="text-xl font-bold text-gray-900">{summary.lunas}</p>
        </div>
        <div className="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
          <div className="flex items-center gap-2 mb-1">
            <Clock className="w-4 h-4 text-amber-600" />
            <span className="text-sm text-gray-500">Menunggu</span>
          </div>
          <p className="text-xl font-bold text-gray-900">{summary.menunggu}</p>
        </div>
        <div className="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
          <div className="flex items-center gap-2 mb-1">
            <Wallet className="w-4 h-4 text-blue-600" />
            <span className="text-sm text-gray-500">Belum Bayar</span>
          </div>
          <p className="text-xl font-bold text-gray-900">{summary.belum}</p>
        </div>
        <div className="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
          <div className="flex items-center gap-2 mb-1">
            <Wallet className="w-4 h-4 text-green-600" />
            <span className="text-sm text-gray-500">Total Terkumpul</span>
          </div>
          <p className="text-lg font-bold text-green-600">{formatRupiah(summary.total_nominal)}</p>
        </div>
      </div>

      {/* Filters */}
      <div className="flex flex-wrap gap-3">
        <select value={year} onChange={(e) => setYear(e.target.value)} className="px-3 py-2 border border-gray-200 rounded-lg text-sm">
          <option value="">Semua Tahun</option>
          {[2024, 2025, 2026, 2027].map((y) => <option key={y} value={y}>{y}</option>)}
        </select>
        <select value={month} onChange={(e) => setMonth(e.target.value)} className="px-3 py-2 border border-gray-200 rounded-lg text-sm">
          <option value="">Semua Bulan</option>
          {Object.entries(BULAN_LABEL).map(([k, v]) => <option key={k} value={k}>{v}</option>)}
        </select>
        <select value={status} onChange={(e) => setStatus(e.target.value)} className="px-3 py-2 border border-gray-200 rounded-lg text-sm">
          <option value="">Semua Status</option>
          {Object.entries(STATUS_IURAN_LABEL).map(([k, v]) => <option key={k} value={k}>{v}</option>)}
        </select>
      </div>

      {/* Data */}
      {loading ? (
        <div className="space-y-3">
          {[...Array(6)].map((_, i) => <div key={i} className="bg-white rounded-xl p-4 shadow-sm animate-pulse h-16" />)}
        </div>
      ) : data.length === 0 ? (
        <div className="text-center py-12 text-gray-500">Tidak ada data iuran</div>
      ) : (
        <div className="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
          <div className="overflow-x-auto">
            <table className="w-full text-sm">
              <thead className="bg-gray-50">
                <tr>
                  <th className="text-left py-3 px-4 text-gray-500 font-medium">Kode</th>
                  <th className="text-left py-3 px-4 text-gray-500 font-medium">Warga</th>
                  <th className="text-left py-3 px-4 text-gray-500 font-medium">Periode</th>
                  <th className="text-left py-3 px-4 text-gray-500 font-medium">Nominal</th>
                  <th className="text-left py-3 px-4 text-gray-500 font-medium">Status</th>
                  <th className="text-left py-3 px-4 text-gray-500 font-medium">Verifikator</th>
                </tr>
              </thead>
              <tbody>
                {data.map((item) => (
                  <tr key={item.id} className="border-t border-gray-100 hover:bg-gray-50">
                    <td className="py-3 px-4 font-mono text-xs">{item.kode_tagihan}</td>
                    <td className="py-3 px-4 font-medium">{item.warga_name}</td>
                    <td className="py-3 px-4">{BULAN_LABEL[item.bulan as keyof typeof BULAN_LABEL]} {item.tahun}</td>
                    <td className="py-3 px-4 font-medium">{formatRupiah(item.nominal)}</td>
                    <td className="py-3 px-4">
                      <span className={cn('text-xs px-2 py-1 rounded-full border', STATUS_IURAN_COLOR[item.status])}>
                        {STATUS_IURAN_LABEL[item.status]}
                      </span>
                    </td>
                    <td className="py-3 px-4 text-gray-500">{item.verifikator_name || '-'}</td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
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

      {/* Generate Modal */}
      {showGenerateModal && (
        <div className="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
          <div className="bg-white rounded-xl p-6 w-full max-w-md">
            <div className="flex items-center justify-between mb-4">
              <h3 className="text-lg font-semibold">Generate Tagihan Iuran</h3>
              <button onClick={() => setShowGenerateModal(false)}><X className="w-5 h-5 text-gray-400" /></button>
            </div>
            <p className="text-sm text-gray-600 mb-4">Tagihan akan dibuat untuk semua warga aktif yang belum memiliki tagihan di periode yang dipilih.</p>
            <div className="space-y-4">
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                <select value={generateBulan} onChange={(e) => setGenerateBulan(e.target.value)} className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
                  {Object.entries(BULAN_LABEL).map(([k, v]) => <option key={k} value={k}>{v}</option>)}
                </select>
              </div>
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                <select value={generateTahun} onChange={(e) => setGenerateTahun(e.target.value)} className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
                  {[2024, 2025, 2026, 2027].map((y) => <option key={y} value={y}>{y}</option>)}
                </select>
              </div>
            </div>
            <div className="flex gap-2 mt-6">
              <button onClick={() => setShowGenerateModal(false)} className="flex-1 px-4 py-2 border border-gray-200 rounded-lg text-sm hover:bg-gray-50">Batal</button>
              <button onClick={handleGenerate} disabled={generating} className="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700 disabled:opacity-50">
                {generating ? 'Generating...' : 'Generate'}
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
