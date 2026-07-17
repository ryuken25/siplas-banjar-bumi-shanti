'use client';

import { useEffect, useState } from 'react';
import Link from 'next/link';
import { Loader2, FileText } from 'lucide-react';
import {
  formatTanggalShort,
  STATUS_LAPORAN_LABEL,
  STATUS_LAPORAN_COLOR,
  JENIS_SAMPAH_LABEL,
  JENIS_SAMPAH_ICON,
} from '@/lib/utils';

const TABS = ['semua', 'dikirim', 'diterima', 'diproses', 'selesai', 'ditolak'] as const;
const TAB_LABELS: Record<string, string> = {
  semua: 'Semua',
  dikirim: 'Dikirim',
  diterima: 'Diterima',
  diproses: 'Diproses',
  selesai: 'Selesai',
  ditolak: 'Ditolak',
};

export default function LaporanSayaPage() {
  const [laporan, setLaporan] = useState<any[]>([]);
  const [activeTab, setActiveTab] = useState<string>('semua');
  const [loading, setLoading] = useState(true);
  const [loadingMore, setLoadingMore] = useState(false);
  const [hasMore, setHasMore] = useState(true);
  const [offset, setOffset] = useState(0);
  const LIMIT = 12;

  async function fetchLaporan(status: string, off: number, append = false) {
    if (append) setLoadingMore(true);
    else setLoading(true);

    try {
      const params = new URLSearchParams({ limit: String(LIMIT), offset: String(off) });
      if (status !== 'semua') params.set('status', status);
      const res = await fetch(`/api/warga/laporan?${params}`);
      const data = await res.json();
      setLaporan((prev) => (append ? [...prev, ...data.laporan] : data.laporan));
      setHasMore(data.hasMore);
    } catch (err) {
      console.error(err);
    } finally {
      setLoading(false);
      setLoadingMore(false);
    }
  }

  useEffect(() => {
    setOffset(0);
    fetchLaporan(activeTab, 0);
  }, [activeTab]);

  function handleLoadMore() {
    const newOffset = offset + LIMIT;
    setOffset(newOffset);
    fetchLaporan(activeTab, newOffset, true);
  }

  return (
    <div className="min-h-screen bg-gray-50 p-4 md:p-8">
      <div className="max-w-6xl mx-auto space-y-6">
        <h1 className="text-2xl font-bold text-gray-900">Laporan Saya</h1>

        {/* Tabs */}
        <div className="flex gap-2 overflow-x-auto pb-1">
          {TABS.map((tab) => (
            <button
              key={tab}
              onClick={() => setActiveTab(tab)}
              className={`px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition ${
                activeTab === tab
                  ? 'bg-green-600 text-white'
                  : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50'
              }`}
            >
              {TAB_LABELS[tab]}
            </button>
          ))}
        </div>

        {/* List */}
        {loading ? (
          <div className="flex items-center justify-center py-20">
            <Loader2 className="w-8 h-8 animate-spin text-green-600" />
          </div>
        ) : laporan.length === 0 ? (
          <div className="text-center py-20">
            <FileText className="w-12 h-12 text-gray-300 mx-auto mb-3" />
            <p className="text-gray-500">Tidak ada laporan</p>
          </div>
        ) : (
          <>
            <div className="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
              {laporan.map((lap: any) => (
                <Link
                  key={lap.id}
                  href={`/warga/laporan/${lap.id}`}
                  className="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition"
                >
                  {lap.foto ? (
                    <img src={lap.foto} alt={lap.kode_laporan} className="w-full h-40 object-cover" />
                  ) : (
                    <div className="w-full h-40 bg-gray-100 flex items-center justify-center">
                      <span className="text-4xl">{JENIS_SAMPAH_ICON[lap.jenis_sampah] || '🗑️'}</span>
                    </div>
                  )}
                  <div className="p-4 space-y-2">
                    <div className="flex items-center justify-between">
                      <span className="text-sm font-semibold text-gray-900">{lap.kode_laporan}</span>
                      <span className={`text-xs px-2 py-0.5 rounded-full font-medium border ${STATUS_LAPORAN_COLOR[lap.status]}`}>
                        {STATUS_LAPORAN_LABEL[lap.status]}
                      </span>
                    </div>
                    <div className="flex items-center gap-2 text-sm text-gray-600">
                      <span>{JENIS_SAMPAH_ICON[lap.jenis_sampah]}</span>
                      <span>{JENIS_SAMPAH_LABEL[lap.jenis_sampah]}</span>
                    </div>
                    <p className="text-xs text-gray-400">{formatTanggalShort(lap.tanggal_lapor)}</p>
                  </div>
                </Link>
              ))}
            </div>

            {hasMore && (
              <div className="text-center pt-4">
                <button
                  onClick={handleLoadMore}
                  disabled={loadingMore}
                  className="px-6 py-2.5 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition disabled:opacity-50"
                >
                  {loadingMore ? (
                    <span className="flex items-center gap-2"><Loader2 className="w-4 h-4 animate-spin" /> Memuat...</span>
                  ) : (
                    'Muat Lebih Banyak'
                  )}
                </button>
              </div>
            )}
          </>
        )}
      </div>
    </div>
  );
}
