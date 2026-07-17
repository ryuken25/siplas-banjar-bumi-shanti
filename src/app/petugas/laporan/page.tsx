'use client';
import { useEffect, useState } from 'react';
import Link from 'next/link';
import { STATUS_LAPORAN_LABEL, STATUS_LAPORAN_COLOR, JENIS_SAMPAH_LABEL, JENIS_SAMPAH_ICON, formatTanggalShort } from '@/lib/utils';

export default function PetugasLaporan() {
  const [data, setData] = useState<any[]>([]);
  const [status, setStatus] = useState('');
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const q = status ? `?status=${status}` : '';
    fetch(`/api/petugas/laporan${q}`).then(r => r.json()).then(d => { setData(d.data || []); setLoading(false); });
  }, [status]);

  const statuses = ['', 'dikirim', 'diterima', 'diproses', 'selesai', 'ditolak'];

  return (
    <div className="space-y-6 animate-fade-in">
      <h1 className="text-2xl font-bold">Kelola Laporan</h1>
      <div className="flex gap-2 overflow-x-auto pb-2">
        {statuses.map(s => (
          <button key={s} onClick={() => { setStatus(s); setLoading(true); }}
            className={`px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap transition-colors ${status === s ? 'bg-brand-500 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'}`}>
            {s === '' ? 'Semua' : (STATUS_LAPORAN_LABEL[s] || s)}
          </button>
        ))}
      </div>
      {loading ? <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">{[1,2,3].map(i => <div key={i} className="h-40 bg-gray-100 rounded-xl animate-pulse" />)}</div> : (
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          {data.map((l: any) => (
            <Link key={l.id} href={`/petugas/laporan/${l.id}`}
              className="block bg-white rounded-xl border border-gray-100 p-4 hover:shadow-md transition-shadow">
              <div className="flex items-center justify-between mb-2">
                <span className="text-xs font-mono text-gray-500">{l.kode_laporan}</span>
                <span className={`px-2 py-0.5 text-xs font-medium rounded-full ${STATUS_LAPORAN_COLOR[l.status] || ''}`}>{STATUS_LAPORAN_LABEL[l.status]}</span>
              </div>
              <p className="text-sm font-medium mb-1">{JENIS_SAMPAH_ICON[l.jenis_sampah]} {JENIS_SAMPAH_LABEL[l.jenis_sampah]}</p>
              <p className="text-xs text-gray-500 mb-2">{l.lokasi_text}</p>
              <p className="text-xs text-gray-400">Pelapor: {l.pelapor_name || '-'} · {formatTanggalShort(l.tanggal_lapor)}</p>
            </Link>
          ))}
          {!data.length && <p className="col-span-full text-center text-gray-400 py-8">Tidak ada laporan.</p>}
        </div>
      )}
    </div>
  );
}
